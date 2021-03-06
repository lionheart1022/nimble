te when cron is run.
 * @param array $args Optional. Arguments to pass to the hook's callback function.
 * @return false|int The UNIX timestamp of the next time the scheduled event will occur.
 */
function wp_next_scheduled( $hook, $args = array() ) {
	$crons = _get_cron_array();
	$key = md5(serialize($args));
	if ( empty($crons) )
		return false;
	foreach ( $crons as $timestamp => $cron ) {
		if ( isset( $cron[$hook][$key] ) )
			return $timestamp;
	}
	return false;
}

/**
 * Send request to run cron through HTTP request that doesn't halt page loading.
 *
 * @since 2.1.0
 */
function spawn_cron( $gmt_time = 0 ) {
	if ( ! $gmt_time )
		$gmt_time = microtime( true );

	if ( defined('DOING_CRON') || isset($_GET['doing_wp_cron']) )
		return;

	/*
	 * Get the cron lock, which is a unix timestamp of when the last cron was spawned
	 * and has not finished running.
	 *
	 * Multiple processes on multiple web servers can run this code concurrently,
	 * this lock attempts to make spawning as atomic as possible.
	 */
	$lock = get_transient('doing_cron');

	if ( $lock > $gmt_time + 10 * MINUTE_IN_SECONDS )
		$lock = 0;

	// don't run if another process is currently running it or more than once every 60 sec.
	if ( $lock + WP_CRON_LOCK_TIMEOUT > $gmt_time )
		return;

	//sanity check
	$crons = _get_cron_array();
	if ( !is_array($crons) )
		return;

	$keys = array_keys( $crons );
	if ( isset($keys[0]) && $keys[0] > $gmt_time )
		return;

	if ( defined( 'ALTERNATE_WP_CRON' ) && ALTERNATE_WP_CRON ) {
		if ( ! empty( $_POST ) || defined( 'DOING_AJAX' ) ||  defined( 'XMLRPC_REQUEST' ) ) {
			return;
		}

		$doing_wp_cron = sprintf( '%.22F', $gmt_time );
		set_transient( 'doing_cron', $doing_wp_cron );

		ob_start();
		wp_redirect( add_query_arg( 'doing_wp_cron', $doing_wp_cron, wp_unslash( $_SERVER['REQUEST_URI'] ) ) );
		echo ' ';

		// flush any buffers and send the headers
		while ( @ob_end_flush() );
		flush();

		WP_DEBUG ? include_once( ABSPATH . 'wp-cron.php' ) : @include_once( ABSPATH . 'wp-cron.php' );
		return;
	}

	// Set the cron lock with the current unix timestamp, when the cron is being spawned.
	$doing_wp_cron = sprintf( '%.22F', $gmt_time );
	set_transient( 'doing_cron', $doing_wp_cron );

	/**
	 * Filter the cron request arguments.
	 *
	 * @since 3.5.0
	 *
	 * @param array $cron_request_array {
	 *     An array of cron request URL arguments.
	 *
	 *     @type string $url  The cron request URL.
	 *     @type int    $key  The 22 digit GMT microtime.
	 *     @type array  $args {
	 *         An array of cron request arguments.
	 *
	 *         @type int  $timeout   The request timeout in seconds. Default .01 seconds.
	 *         @type bool $blocking  Whether to set blocking for the request. Default false.
	 *         @type bool $sslverify Whether SSL should be verified for the request. Default false.
	 *     }
	 * }
	 */
	$cron_request = apply_filters( 'cron_request', array(
		'url'  => add_query_arg( 'doing_wp_cron', $doing_wp_cron, site_url( 'wp-cron.php' ) ),
		'key'  => $doing_wp_cron,
		'args' => array(
			'timeout'   => 0.01,
			'blocking'  => false,
			/** This filter is documented in wp-includes/class-http.php */
			'sslverify' => apply_filters( 'https_local_ssl_verify', false )
		)
	) );

	wp_remote_post( $cron_request['url'], $cron_request['args'] );
}

/**
 * Run scheduled callbacks or spawn cron for all scheduled events.
 *
 * @since 2.1.0
 */
function wp_cron() {
	// Prevent infinite loops caused by lack of wp-cron.php
	if ( strpos($_SERVER['REQUEST_URI'], '/wp-cron.php') !== false || ( defined('DISABLE_WP_CRON') && DISABLE_WP_CRON ) )
		return;

	if ( false === $crons = _get_cron_array() )
		return;

	$gmt_time = microtime( true );
	$keys = array_keys( $crons );
	if ( isset($keys[0]) && $keys[0] > $gmt_time )
		return;

	$schedules = wp_get_schedules();
	foreach ( $crons as $timestamp => $cronhooks ) {
		if ( $timestamp > $gmt_time ) break;
		foreach ( (array) $cronhooks as $hook => $args ) {
			if ( isset($schedules[$hook]['callback']) && !call_user_func( $schedules[$hook]['callback'] ) )
				continue;
			spawn_cron( $gmt_time );
			break 2;
		}
	}
}

/**
 * Retrieve supported and filtered Cron recurrences.
 *
 * The supported recurrences are 'hourly' and 'daily'. A plugin may add more by
 * hooking into the 'cron_schedules' filter. The filter accepts an array of
 * arrays. The outer array has a key that is the name of the schedule or for
 * example 'weekly'. The value is an array with two keys, one is 'interval' and
 * the other is 'display'.
 *
 * The 'interval' is a number in seconds of when the cron job should run. So for
 * 'hourly', the time is 3600 or 60*60. For weekly, the value would be
 * 60*60*24*7 or 604800. The value of 'interval' would then be 604800.
 *
 * The 'display' is the description. For the 'weekly' key, the 'display' would
 * be `__( 'Once Weekly' )`.
 *
 * For your plugin, you will be passed an array. you can easily add your
 * schedule by doing the following.
 *
 *     // Filter parameter variable name is 'array'.
 *     $array['weekly'] = array(
 *         'interval' => 604800,
 *     	   'display'  => __( 'Once Weekly' )
 *     );
 *
 *
 * @since 2.1.0
 *
 * @return array
 */
function wp_get_schedules() {
	$schedules = array(
		'hourly'     => array( 'interval' => HOUR_IN_SECONDS,      'display' => __( 'Once Hourly' ) ),
		'twicedaily' => array( 'interval' => 12 * HOUR_IN_SECONDS, 'display' => __( 'Twice Daily' ) ),
		'daily'      => array( 'interval' => DAY_IN_SECONDS,       'display' => __( 'Once Daily' ) ),
	);
	/**
	 * Filter the non-default cron schedules.
	 *
	 * @since 2.1.0
	 *
	 * @param array $new_schedules An array of non-default cron schedules. Default empty.
	 */
	return array_merge( apply_filters( 'cron_schedules', array() ), $schedules );
}

/**
 * Retrieve Cron schedule for hook with arguments.
 *
 * @since 2.1.0
 *
 * @param string $hook Action hook to execute when cron is run.
 * @param array $args Optional. Arguments to pass to the hook's callback function.
 * @return string|false False, if no schedule. Schedule on success.
 */
function wp_get_schedule($hook, $args = array()) {
	$crons = _get_cron_array();
	$key = md5(serialize($args));
	if ( empty($crons) )
		return false;
	foreach ( $crons as $timestamp => $cron ) {
		if ( isset( $cron[$hook][$key] ) )
			return $cron[$hook][$key]['schedule'];
	}
	return false;
}

//
// Private functions
//

/**
 * Retrieve cron info array option.
 *
 * @since 2.1.0
 * @access private
 *
 * @return false|array CRON info array.
 */
function _get_cron_array()  {
	$cron = get_option('cron');
	if ( ! is_array($cron) )
		return false;

	if ( !isset($cron['version']) )
		$cron = _upgrade_cron_array($cron);

	unset($cron['version']);

	return $cron;
}

/**
 * Updates the CRON option with the new CRON array.
 *
 * @since 2.1.0
 * @access private
 *
 * @param array $cron Cron info array from {@link _get_cron_array()}.
 */
function _set_cron_array($cron) {
	$cron['version'] = 2;
	update_option( 'cron', $cron );
}

/**
 * Upgrade a Cron info array.
 *
 * This function upgrades the Cron info array to version 2.
 *
 * @since 2.1.0
 * @access private
 *
 * @param array $cron Cron info array from {@link _get_cron_array()}.
 * @return array An upgraded Cron info array.
 */
function _upgrade_cron_array($cron) {
	if ( isset($cron['version']) && 2 == $cron['version'])
		return $cron;

	$new_cron = array();

	foreach ( (array) $cron as $timestamp => $hooks) {
		foreach ( (array) $hooks as $hook => $args ) {
			$key = md5(serialize($args['args']));
			$new_cron[$timestamp][$hook][$key] = $args;
		}
	}

	$new_cron['version'] = 2;
	update_option( 'cron', $new_cron );
	return $new_cron;
}
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                           <?php
/**
 * BackPress Scripts enqueue
 *
 * Classes were refactored from the WP_Scripts and WordPress script enqueue API.
 *
 * @since BackPress r74
 *
 * @package BackPress
 * @uses _WP_Dependency
 * @since r74
 */
class WP_Dependencies {
	/**
	 * An array of registered handle objects.
	 *
	 * @access public
	 * @since 2.6.8
	 * @var array
	 */
	public $registered = array();

	/**
	 * An array of queued _WP_Dependency handle objects.
	 *
	 * @access public
	 * @since 2.6.8
	 * @var array
	 */
	public $queue = array();

	/**
	 * An array of _WP_Dependency handle objects to queue.
	 *
	 * @access public
	 * @since 2.6.0
	 * @var array
	 */
	public $to_do = array();

	/**
	 * An array of _WP_Dependency handle objects already queued.
	 *
	 * @access public
	 * @since 2.6.0
	 * @var array
	 */
	public $done = array();

	/**
	 * An array of additional arguments passed when a handle is registered.
	 *
	 * Arguments are appended to the item query string.
	 *
	 * @access public
	 * @since 2.6.0
	 * @var array
	 */
	public $args = array();

	/**
	 * An array of handle groups to enqueue.
	 *
	 * @access public
	 * @since 2.8.0
	 * @var array
	 */
	public $groups = array();

	/**
	 * A handle group to enqueue.
	 *
	 * @access public
	 * @since 2.8.0
	 * @var int
	 */
	public $group = 0;

	/**
	 * Process the items and dependencies.
	 *
	 * Processes the items passed to it or the queue, and their dependencies.
	 *
	 * @access public
	 * @since 2.1.0
	 *
	 * @param mixed $handles Optional. Items to be processed: Process queue (false), process item (string), process items (array of strings).
	 * @param mixed $group   Group level: level (int), no groups (false).
	 * @return array Handles of items that have been processed.
	 */
	public function do_items( $handles = false, $group = false ) {
		/*
		 * If nothing is passed, print the queue. If a string is passed,
		 * print that item. If an array is passed, print those items.
		 */
		$handles = false === $handles ? $this->queue : (array) $handles;
		$this->all_deps( $handles );

		foreach( $this->to_do as $key => $handle ) {
			if ( !in_array($handle, $this->done, true) && isset($this->registered[$handle]) ) {

				/*
				 * A single item may alias a set of items, by having dependencies,
				 * but no source. Queuing the item queues the dependencies.
				 *
				 * Example: The extending class WP_Scripts is used to register 'scriptaculous' as a set of registered handles:
				 *   <code>add( 'scriptaculous', false, array( 'scriptaculous-dragdrop', 'scriptaculous-slider', 'scriptaculous-controls' ) );</code>
				 *
				 * The src property is false.
				 */
				if ( ! $this->registered[$handle]->src ) {
					$this->done[] = $handle;
					continue;
				}

				/*
				 * Attempt to process the item. If successful,
				 * add the handle to the done array.
				 *
				 * Unset the item from the to_do array.
				 */
				if ( $this->do_item( $handle, $group ) )
					$this->done[] = $handle;

				unset( $this->to_do[$key] );
			}
		}

		return $this->done;
	}

	/**
	 * Process a dependency.
	 *
	 * @access public
	 * @since 2.6.0
	 *
	 * @param string $handle Name of the item. Should be unique.
	 * @return bool True on success, false if not set.
	 */
	public function do_item( $handle ) {
		return isset($this->registered[$handle]);
	}

	/**
	 * Determine dependencies.
	 *
	 * Recursively builds an array of items to process taking
	 * dependencies into account. Does NOT catch infinite loops.
	 *
	 * @access public
	 * @since 2.1.0
	 *
	 * @param mixed $handles   Item handle and argument (string) or item handles and arguments (array of strings).
	 * @param bool  $recursion Internal flag that function is calling itself.
	 * @param mixed $group     Group level: (int) level, (false) no groups.
	 * @return bool True on success, false on failure.
	 */
	public function all_deps( $handles, $recursion = false, $group = false ) {
		if ( !$handles = (array) $handles )
			return false;

		foreach ( $handles as $handle ) {
			$handle_parts = explode('?', $handle);
			$handle = $handle_parts[0];
			$queued = in_array($handle, $this->to_do, true);

			if ( in_array($handle, $this->done, true) ) // Already done
				continue;

			$moved = $this->set_group( $handle, $recursion, $group );

			if ( $queued && !$moved ) // already queued and in the right group
				continue;

			$keep_going = true;
			if ( !isset($this->registered[$handle]) )
				$keep_going = false; // Item doesn't exist.
			elseif ( $this->registered[$handle]->deps && array_diff($this->registered[$handle]->deps, array_keys($this->registered)) )
				$keep_going = false; // Item requires dependencies that don't exist.
			elseif ( $this->registered[$handle]->deps && !$this->all_deps( $this->registered[$handle]->deps, true, $group ) )
				$keep_going = false; // Item requires dependencies that don't exist.

			if ( ! $keep_going ) { // Either item or its dependencies don't exist.
				if ( $recursion )
					return false; // Abort this branch.
				else
					continue; // We're at the top level. Move on to the next one.
			}

			if ( $queued ) // Already grabbed it and its dependencies.
				continue;

			if ( isset($handle_parts[1]) )
				$this->args[$handle] = $handle_parts[1];

			$this->to_do[] = $handle;
		}

		return true;
	}

	/**
	 * Register an item.
	 *
	 * Registers the item if no item of that name already exists.
	 *
	 * @access public
	 * @since 2.1.0
	 *
	 * @param string $handle Unique item name.
	 * @param string $src    The item url.
	 * @param array  $deps   Optional. An array of item handle strings on which this item depends.
	 * @param string $ver    Optional. Version (used for cache busting).
	 * @param mixed  $args   Optional. Custom property of the item. NOT the class property $args. Examples: $media, $in_footer.
	 * @return bool Whether the item has been registered. True on success, false on failure.
	 */
	public function add( $handle, $src, $deps = array(), $ver = false, $args = null ) {
		if ( isset($this->registered[$handle]) )
			return false;
		$this->registered[$handle] = new _WP_Dependency( $handle, $src, $deps, $ver, $args );
		return true;
	}

	/**
	 * Add extra item data.
	 *
	 * Adds data to a registered item.
	 *
	 * @access public
	 * @since 2.6.0
	 *
	 * @param string $handle Name of the item. Should be unique.
	 * @param string $key    The data key.
	 * @param mixed  $value  The data value.
	 * @return bool True on success, false on failure.
	 */
	public function add_data( $handle, $key, $value ) {
		if ( !isset( $this->registered[$handle] ) )
			return false;

		return $this->registered[$handle]->add_data( $key, $value );
	}

	/**
	 * Get extra item data.
	 *
	 * Gets data associated with a registered item.
	 *
	 * @access public
	 * @since 3.3.0
	 *
	 * @param string $handle Name of the item. Should be unique.
	 * @param string $key    The data key.
	 * @return mixed Extra item data (string), false otherwise.
	 */
	public function get_data( $handle, $key ) {
		if ( !isset( $this->registered[$handle] ) )
			return false;

		if ( !isset( $this->registered[$handle]->extra[$key] ) )
			return false;

		return $this->registered[$handle]->extra[$key];
	}

	/**
	 * Un-register an item or items.
	 *
	 * @access public
	 * @since 2.1.0
	 *
	 * @param mixed $handles Item handle and argument (string) or item handles and arguments (array of strings).
	 * @return void
	 */
	public function remove( $handles ) {
		foreach ( (array) $handles as $handle )
			unset($this->registered[$handle]);
	}

	/**
	 * Queue an item or items.
	 *
	 * Decodes handles and arguments, then queues handles and stores
	 * arguments in the class property $args. For example in extending
	 * classes, $args is appended to the item url as a query string.
	 * Note $args is NOT the $args property of items in the $registered array.
	 *
	 * @access public
	 * @since 2.1.0
	 *
	 * @param mixed $handles Item handle and argument (string) or item handles and arguments (array of strings).
	 */
	public function enqueue( $handles ) {
		foreach ( (array) $handles as $handle ) {
			$handle = explode('?', $handle);
			if ( !in_array($handle[0], $this->queue) && isset($this->registered[$handle[0]]) ) {
				$this->queue[] = $handle[0];
				if ( isset($handle[1]) )
					$this->args[$handle[0]] = $handle[1];
			}
		}
	}

	/**
	 * Dequeue an item or items.
	 *
	 * Decodes handles and arguments, then dequeues handles
	 * and removes arguments from the class property $args.
	 *
	 * @access public
	 * @since 2.1.0
	 *
	 * @param mixed $handles Item handle and argument (string) or item handles and arguments (array of strings).
	 */
	public function dequeue( $handles ) {
		foreach ( (array) $handles as $handle ) {
			$handle = explode('?', $handle);
			$key = array_search($handle[0], $this->queue);
			if ( false !== $key ) {
				unset($this->queue[$key]);
				unset($this->args[$handle[0]]);
			}
		}
	}

	/**
	 * Recursively search the passed dependency tree for $handle
	 *
	 * @since 4.0.0
	 *
	 * @param array  $queue  An array of queued _WP_Dependency handle objects.
	 * @param string $handle Name of the item. Should be unique.
	 * @return bool Whether the handle is found after recursively searching the dependency tree.
	 */
	protected function recurse_deps( $queue, $handle ) {
		foreach ( $queue as $queued ) {
			if ( ! isset( $this->registered[ $queued ] ) ) {
				continue;
			}

			if ( in_array( $handle, $this->registered[ $queued ]->deps ) ) {
				return true;
			} elseif ( $this->recurse_deps( $this->registered[ $queued ]->deps, $handle ) ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Query list for an item.
	 *
	 * @access public
	 * @since 2.1.0
	 *
	 * @param string $handle Name of the item. Should be unique.
	 * @param string $list   Property name of list array.
	 * @return bool Found, or object Item data.
	 */
	public function query( $handle, $list = 'registered' ) {
		switch ( $list ) {
			case 'registered' :
			case 'scripts': // back compat
				if ( isset( $this->registered[ $handle ] ) )
					return $this->registered[ $handle ];
				return false;

			case 'enqueued' :
			case 'queue' :
				if ( in_array( $handle, $this->queue ) ) {
					return true;
				}
				return $this->recurse_deps( $this->queue, $handle );

			case 'to_do' :
			case 'to_print': // back compat
				return in_array( $handle, $this->to_do );

			case 'done' :
			case 'printed': // back compat
				return in_array( $handle, $this->done );
		}
		return false;
	}

	/**
	 * Set item group, unless already in a lower group.
	 *
	 * @access public
	 * @since 2.8.0
	 *
	 * @param string $handle    Name of the item. Should be unique.
	 * @param bool   $recursion Internal flag that calling function was called recursively.
	 * @param mixed  $group     Group level.
	 * @return bool Not already in the group or a lower group
	 */
	public function set_group( $handle, $recursion, $group ) {
		$group = (int) $group;

		if ( $recursion )
			$group = min($this->group, $group);
		else
			$this->group = $group;

		if ( isset($this->groups[$handle]) && $this->groups[$handle] <= $group )
			return false;

		$this->groups[$handle] = $group;
		return true;
	}

} // WP_Dependencies

/**
 * Class _WP_Dependency
 *
 * Helper class to register a handle and associated data.
 *
 * @access private
 * @since 2.6.0
 */
class _WP_Dependency {
	/**
	 * The handle name.
	 *
	 * @access public
	 * @since 2.6.0
	 * @var null
	 */
	public $handle;

	/**
	 * The handle source.
	 *
	 * @access public
	 * @since 2.6.0
	 * @var null
	 */
	public $src;

	/**
	 * An array of handle dependencies.
	 *
	 * @access public
	 * @since 2.6.0
	 * @var array
	 */
	public $deps = array();

	/**
	 * The handle version.
	 *
	 * Used for cache-busting.
	 *
	 * @access public
	 * @since 2.6.0
	 * @var bool|string
	 */
	public $ver = false;

	/**
	 * Additional arguments for the handle.
	 *
	 * @access public
	 * @since 2.6.0
	 * @var null
	 */
	public $args = null;  // Custom property, such as $in_footer or $media.

	/**
	 * Extra data to supply to the handle.
	 *
	 * @access public
	 * @since 2.6.0
	 * @var array
	 */
	public $extra = array();

	/**
	 * Setup dependencies.
	 *
	 * @since 2.6.0
	 */
	public function __construct() {
		@list( $this->handle, $this->src, $this->deps, $this->ver, $this->args ) = func_get_args();
		if ( ! is_array($this->deps) )
			$this->deps = array();
	}

	/**
	 * Add handle data.
	 *
	 * @access public
	 * @since 2.6.0
	 *
	 * @param string $name The data key to add.
	 * @param mixed  $data The data value to add.
	 * @return bool False if not scalar, true otherwise.
	 */
	public function add_data( $name, $data ) {
		if ( !is_scalar($name) )
			return false;
		$this->extra[$name] = $data;
		return true;
	}

} // _WP_Dependencies
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                           <?php
/**
 * WordPress environment setup class.
 *
 * @package WordPress
 * @since 2.0.0
 */
class WP {
	/**
	 * Public query variables.
	 *
	 * Long list of public query variables.
	 *
	 * @since 2.0.0
	 * @access public
	 * @var array
	 */
	public $public_query_vars = array('m', 'p', 'posts', 'w', 'cat', 'withcomments', 'withoutcomments', 's', 'search', 'exact', 'sentence', 'calendar', 'page', 'paged', 'more', 'tb', 'pb', 'author', 'order', 'orderby', 'year', 'monthnum', 'day', 'hour', 'minute', 'second', 'name', 'category_name', 'tag', 'feed', 'author_name', 'static', 'pagename', 'page_id', 'error', 'comments_popup', 'attachment', 'attachment_id', 'subpost', 'subpost_id', 'preview', 'robots', 'taxonomy', 'term', 'cpage', 'post_type');

	/**
	 * Private query variables.
	 *
	 * Long list of private query variables.
	 *
	 * @since 2.0.0
	 * @var array
	 */
	public $private_query_vars = array( 'offset', 'posts_per_page', 'posts_per_archive_page', 'showposts', 'nopaging', 'post_type', 'post_status', 'category__in', 'category__not_in', 'category__and', 'tag__in', 'tag__not_in', 'tag__and', 'tag_slug__in', 'tag_slug__and', 'tag_id', 'post_mime_type', 'perm', 'comments_per_page', 'post__in', 'post__not_in', 'post_parent', 'post_parent__in', 'post_parent__not_in' );

	/**
	 * Extra query variables set by the user.
	 *
	 * @since 2.1.0
	 * @var array
	 */
	public $extra_query_vars = array();

	/**
	 * Query variables for setting up the WordPress Query Loop.
	 *
	 * @since 2.0.0
	 * @var array
	 */
	public $query_vars;

	/**
	 * String parsed to set the query variables.
	 *
	 * @since 2.0.0
	 * @var string
	 */
	public $query_string;

	/**
	 * Permalink or requested URI.
	 *
	 * @since 2.0.0
	 * @var string
	 */
	public $request;

	/**
	 * Rewrite rule the request matched.
	 *
	 * @since 2.0.0
	 * @var string
	 */
	public $matched_rule;

	/**
	 * Rewrite query the request matched.
	 *
	 * @since 2.0.0
	 * @var string
	 */
	public $matched_query;

	/**
	 * Whether already did the permalink.
	 *
	 * @since 2.0.0
	 * @var bool
	 */
	public $did_permalink = false;

	/**
	 * Add name to list of public query variables.
	 *
	 * @since 2.1.0
	 *
	 * @param string $qv Query variable name.
	 */
	public function add_query_var($qv) {
		if ( !in_array($qv, $this->public_query_vars) )
			$this->public_query_vars[] = $qv;
	}

	/**
	 * Set the value of a query variable.
	 *
	 * @since 2.3.0
	 *
	 * @param string $key Query variable name.
	 * @param mixed $value Query variable value.
	 */
	public function set_query_var($key, $value) {
		$this->query_vars[$key] = $value;
	}

	/**
	 * Parse request to find correct WordPress query.
	 *
	 * Sets up the query variables based on the request. There are also many
	 * filters and actions that can be used to further manipulate the result.
	 *
	 * @since 2.0.0
	 *
	 * @global WP_Rewrite $wp_rewrite
	 *
	 * @param array|string $extra_query_vars Set the extra query variables.
	 */
	public function parse_request($extra_query_vars = '') {
		global $wp_rewrite;

		/**
		 * Filter whether to parse the request.
		 *
		 * @since 3.5.0
		 *
		 * @param bool         $bool             Whether or not to parse the request. Default true.
		 * @param WP           $this             Current WordPress environment instance.
		 * @param array|string $extra_query_vars Extra passed query variables.
		 */
		if ( ! apply_filters( 'do_parse_request', true, $this, $extra_query_vars ) )
			return;

		$this->query_vars = array();
		$post_type_query_vars = array();

		if ( is_array( $extra_query_vars ) ) {
			$this->extra_query_vars = & $extra_query_vars;
		} elseif ( ! empty( $extra_query_vars ) ) {
			parse_str( $extra_query_vars, $this->extra_query_vars );
		}
		// Process PATH_INFO, REQUEST_URI, and 404 for permalinks.

		// Fetch the rewrite rules.
		$rewrite = $wp_rewrite->wp_rewrite_rules();

		if ( ! empty($rewrite) ) {
			// If we match a rewrite rule, this will be cleared.
			$error = '404';
			$this->did_permalink = true;

			$pathinfo = isset( $_SERVER['PATH_INFO'] ) ? $_SERVER['PATH_INFO'] : '';
			list( $pathinfo ) = explode( '?', $pathinfo );
			$pathinfo = str_replace( "%", "%25", $pathinfo );

			list( $req_uri ) = explode( '?', $_SERVER['REQUEST_URI'] );
			$self = $_SERVER['PHP_SELF'];
			$home_path = trim( parse_url( home_url(), PHP_URL_PATH ), '/' );
			$home_path_regex = sprintf( '|^%s|i', preg_quote( $home_path, '|' ) );

			// Trim path info from the end and the leading home path from the
			// front. For path info requests, this leaves us with the requesting
			// filename, if any. For 404 requests, this leaves us with the
			// requested permalink.
			$req_uri = str_replace($pathinfo, '', $req_uri);
			$req_uri = trim($req_uri, '/');
			$req_uri = preg_replace( $home_path_regex, '', $req_uri );
			$req_uri = trim($req_uri, '/');
			$pathinfo = trim($pathinfo, '/');
			$pathinfo = preg_replace( $home_path_regex, '', $pathinfo );
			$pathinfo = trim($pathinfo, '/');
			$self = trim($self, '/');
			$self = preg_replace( $home_path_regex, '', $self );
			$self = trim($self, '/');

			// The requested permalink is in $pathinfo for path info requests and
			//  $req_uri for other requests.
			if ( ! empty($pathinfo) && !preg_match('|^.*' . $wp_rewrite->index . '$|', $pathinfo) ) {
				$request = $pathinfo;
			} else {
				// If the request uri is the index, blank it out so that we don't try to match it against a rule.
				if ( $req_uri == $wp_rewrite->index )
					$req_uri = '';
				$request = $req_uri;
			}

			$this->request = $request;

			// Look for matches.
			$request_match = $request;
			if ( empty( $request_match ) ) {
				// An empty request could only match against ^$ regex
				if ( isset( $rewrite['$'] ) ) {
					$this->matched_rule = '$';
					$query = $rewrite['$'];
					$matches = array('');
				}
			} else {
				foreach ( (array) $rewrite as $match => $query ) {
					// If the requesting file is the anchor of the match, prepend it to the path info.
					if ( ! empty($req_uri) && strpos($match, $req_uri) === 0 && $req_uri != $request )
						$request_match = $req_uri . '/' . $request;

					if ( preg_match("#^$match#", $request_match, $matches) ||
						preg_match("#^$match#", urldecode($request_match), $matches) ) {

						if ( $wp_rewrite->use_verbose_page_rules && preg_match( '/pagename=\$matches\[([0-9]+)\]/', $query, $varmatch ) ) {
							// This is a verbose page match, let's check to be sure about it.
							if ( ! get_page_by_path( $matches[ $varmatch[1] ] ) )
						 		continue;
						}

						// Got a match.
						$this->matched_rule = $match;
						break;
					}
				}
			}

			if ( isset( $this->matched_rule ) ) {
				// Trim the query of everything up to the '?'.
				$query = preg_replace("!^.+\?!", '', $query);

				// Substitute the substring matches into the query.
				$query = addslashes(WP_MatchesMapRegex::apply($query, $matches));

				$this->matched_query = $query;

				// Parse the query.
				parse_str($query, $perma_query_vars);

				// If we're processing a 404 request, clear the error var since we found something.
				if ( '404' == $error )
					unset( $error, $_GET['error'] );
			}

			// If req_uri is empty or if it is a request for ourself, unset error.
			if ( empty($request) || $req_uri == $self || strpos($_SERVER['PHP_SELF'], 'wp-admin/') !== false ) {
				unset( $error, $_GET['error'] );

				if ( isset($perma_query_vars) && strpos($_SERVER['PHP_SELF'], 'wp-admin/') !== false )
					unset( $perma_query_vars );

				$this->did_permalink = false;
			}
		}

		/**
		 * Filter the query variables whitelist before processing.
		 *
		 * Allows (publicly allowed) query vars to be added, removed, or changed prior
		 * to executing the query. Needed to allow custom rewrite rules using your own arguments
		 * to work, or any other custom query variables you want to be publicly available.
		 *
		 * @since 1.5.0
		 *
		 * @param array $public_query_vars The array of whitelisted query variables.
		 */
		$this->public_query_vars = apply_filters( 'query_vars', $this->public_query_vars );

		foreach ( get_post_types( array(), 'objects' ) as $post_type => $t )
			if ( $t->query_var )
				$post_type_query_vars[$t->query_var] = $post_type;

		foreach ( $this->public_query_vars as $wpvar ) {
			if ( isset( $this->extra_query_vars[$wpvar] ) )
				$this->query_vars[$wpvar] = $this->extra_query_vars[$wpvar];
			elseif ( isset( $_POST[$wpvar] ) )
				$this->query_vars[$wpvar] = $_POST[$wpvar];
			elseif ( isset( $_GET[$wpvar] ) )
				$this->query_vars[$wpvar] = $_GET[$wpvar];
			elseif ( isset( $perma_query_vars[$wpvar] ) )
				$this->query_vars[$wpvar] = $perma_query_vars[$wpvar];

			if ( !empty( $this->query_vars[$wpvar] ) ) {
				if ( ! is_array( $this->query_vars[$wpvar] ) ) {
					$this->query_vars[$wpvar] = (string) $this->query_vars[$wpvar];
				} else {
					foreach ( $this->query_vars[$wpvar] as $vkey => $v ) {
						if ( !is_object( $v ) ) {
							$this->query_vars[$wpvar][$vkey] = (string) $v;
						}
					}
				}

				if ( isset($post_type_query_vars[$wpvar] ) ) {
					$this->query_vars['post_type'] = $post_type_query_vars[$wpvar];
					$this->query_vars['name'] = $this->query_vars[$wpvar];
				}
			}
		}

		// Convert urldecoded spaces back into +
		foreach ( get_taxonomies( array() , 'objects' ) as $taxonomy => $t )
			if ( $t->query_var && isset( $this->query_vars[$t->query_var] ) )
				$this->query_vars[$t->query_var] = str_replace( ' ', '+', $this->query_vars[$t->query_var] );

		// Limit publicly queried post_types to those that are publicly_queryable
		if ( isset( $this->query_vars['post_type']) ) {
			$queryable_post_types = get_post_types( array('publicly_queryable' => true) );
			if ( ! is_array( $this->query_vars['post_type'] ) ) {
				if ( ! in_array( $this->query_vars['post_type'], $queryable_post_types ) )
					unset( $this->query_vars['post_type'] );
			} else {
				$this->query_vars['post_type'] = array_intersect( $this->query_vars['post_type'], $queryable_post_types );
			}
		}

		// Resolve conflicts between posts with numeric slugs and date archive queries.
		$this->query_vars = wp_resolve_numeric_slug_conflicts( $this->query_vars );

		foreach ( (array) $this->private_query_vars as $var) {
			if ( isset($this->extra_query_vars[$var]) )
				$this->query_vars[$var] = $this->extra_query_vars[$var];
		}

		if ( isset($error) )
			$this->query_vars['error'] = $error;

		/**
		 * Filter the array of parsed query variables.
		 *
		 * @since 2.1.0
		 *
		 * @param array $query_vars The array of requested query variables.
		 */
		$this->query_vars = apply_filters( 'request', $this->query_vars );

		/**
		 * Fires once all query variables for the current request have been parsed.
		 *
		 * @since 2.1.0
		 *
		 * @param WP &$this Current WordPress environment instance (passed by reference).
		 */
		do_action_ref_array( 'parse_request', array( &$this ) );
	}

	/**
	 * Send additional HTTP headers for caching, content type, etc.
	 *
	 * Sets the X-Pingback header, 404 status (if 404), Content-type. If showing
	 * a feed, it will also send last-modified, etag, and 304 status if needed.
	 *
	 * @since 2.0.0
	 */
	public function send_headers() {
		$headers = array('X-Pingback' => get_bloginfo('pingback_url'));
		$status = null;
		$exit_required = false;

		if ( is_user_logged_in() )
			$headers = array_merge($headers, wp_get_nocache_headers());
		if ( ! empty( $this->query_vars['error'] ) ) {
			$status = (int) $this->query_vars['error'];
			if ( 404 === $status ) {
				if ( ! is_user_logged_in() )
					$headers = array_merge($headers, wp_get_nocache_headers());
				$headers['Content-Type'] = get_option('html_type') . '; charset=' . get_option('blog_charset');
			} elseif ( in_array( $status, array( 403, 500, 502, 503 ) ) ) {
				$exit_required = true;
			}
		} elseif ( empty( $this->query_vars['feed'] ) ) {
			$headers['Content-Type'] = get_option('html_type') . '; charset=' . get_option('blog_charset');
		} else {
			// We're showing a feed, so WP is indeed the only thing that last changed
			if ( !empty($this->query_vars['withcomments'])
				|| false !== strpos( $this->query_vars['feed'], 'comments-' )
				|| ( empty($this->query_vars['withoutcomments'])
					&& ( !empty($this->query_vars['p'])
						|| !empty($this->query_vars['name'])
						|| !empty($this->query_vars['page_id'])
						|| !empty($this->query_vars['pagename'])
						|| !empty($this->query_vars['attachment'])
						|| !empty($this->query_vars['attachment_id'])
					)
				)
			)
				$wp_last_modified = mysql2date('D, d M Y H:i:s', get_lastcommentmodified('GMT'), 0).' GMT';
			else
				$wp_last_modified = mysql2date('D, d M Y H:i:s', get_lastpostmodified('GMT'), 0).' GMT';
			$wp_etag = '"' . md5($wp_last_modified) . '"';
			$headers['Last-Modified'] = $wp_last_modified;
			$headers['ETag'] = $wp_etag;

			// Support for Conditional GET
			if (isset($_SERVER['HTTP_IF_NONE_MATCH']))
				$client_etag = wp_unslash( $_SERVER['HTTP_IF_NONE_MATCH'] );
			else $client_etag = false;

			$client_last_modified = empty($_SERVER['HTTP_IF_MODIFIED_SINCE']) ? '' : trim($_SERVER['HTTP_IF_MODIFIED_SINCE']);
			// If string is empty, return 0. If not, attempt to parse into a timestamp
			$client_modified_timestamp = $client_last_modified ? strtotime($client_last_modified) : 0;

			// Make a timestamp for our most recent modification...
			$wp_modified_timestamp = strtotime($wp_last_modified);

			if ( ($client_last_modified && $client_etag) ?
					 (($client_modified_timestamp >= $wp_modified_timestamp) && ($client_etag == $wp_etag)) :
					 (($client_modified_timestamp >= $wp_modified_timestamp) || ($client_etag == $wp_etag)) ) {
				$status = 304;
				$exit_required = true;
			}
		}

		/**
		 * Filter the HTTP headers before they're sent to the browser.
		 *
		 * @since 2.8.0
		 *
		 * @param array $headers T