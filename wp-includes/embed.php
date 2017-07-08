			 *
			 * @since 3.5.0
			 *
			 * @param int $blog_id Blog ID.
			 */
			do_action( 'make_undelete_blog', $blog_id );
		}
	}

	if ( isset( $details['public'] ) ) {
		switch_to_blog( $blog_id );
		update_option( 'blog_public', $details['public'] );
		restore_current_blog();
	}

	refresh_blog_details($blog_id);

	return true;
}

/**
 * Clean the blog cache
 *
 * @since 3.5.0
 *
 * @param stdClass $blog The blog details as returned from get_blog_details()
 */
function clean_blog_cache( $blog ) {
	$blog_id = $blog->blog_id;
	$domain_path_key = md5( $blog->domain . $blog->path );

	wp_cache_delete( $blog_id , 'blog-details' );
	wp_cache_delete( $blog_id . 'short' , 'blog-details' );
	wp_cache_delete(  $domain_path_key, 'blog-lookup' );
	wp_cache_delete( 'current_blog_' . $blog->domain, 'site-options' );
	wp_cache_delete( 'current_blog_' . $blog->domain . $blog->path, 'site-options' );
	wp_cache_delete( 'get_id_from_blogname_' . trim( $blog->path, '/' ), 'blog-details' );
	wp_cache_delete( $domain_path_key, 'blog-id-cache' );
}

/**
 * Retrieve option value for a given blog id based on name of option.
 *
 * If the option does not exist or does not have a value, then the return value
 * will be false. This is useful to check whether you need to install an option
 * and is commonly used during installation of plugin options and to test
 * whether upgrading is required.
 *
 * If the option was serialized then it will be unserialized when it is returned.
 *
 * @since MU
 *
 * @param int    $id      A blog ID. Can be null to refer to the current blog.
 * @param string $option  Name of option to retrieve. Expected to not be SQL-escaped.
 * @param mixed  $default Optional. Default value to return if the option does not exist.
 * @return mixed Value set for the option.
 */
function get_blog_option( $id, $option, $default = false ) {
	$id = (int) $id;

	if ( empty( $id ) )
		$id = get_current_blog_id();

	if ( get_current_blog_id() == $id )
		return get_option( $option, $default );

	switch_to_blog( $id );
	$value = get_option( $option, $default );
	restore_current_blog();

	/**
	 * Filter a blog option value.
	 *
	 * The dynamic portion of the hook name, `$option`, refers to the blog option name.
	 *
	 * @since 3.5.0
	 *
	 * @param string  $value The option value.
	 * @param int     $id    Blog ID.
	 */
	return apply_filters( "blog_option_{$option}", $value, $id );
}

/**
 * Add a new option for a given blog id.
 *
 * You do not need to serialize values. If the value needs to be serialized, then
 * it will be serialized before it is inserted into the database. Remember,
 * resources can not be serialized or added as an option.
 *
 * You can create options without values and then update the values later.
 * Existing options will not be updated and checks are performed to ensure that you
 * aren't adding a protected WordPress option. Care should be taken to not name
 * options the same as the ones which are protected.
 *
 * @since MU
 *
 * @param int    $id     A blog ID. Can be null to refer to the current blog.
 * @param string $option Name of option to add. Expected to not be SQL-escaped.
 * @param mixed  $value  Optional. Option value, can be anything. Expected to not be SQL-escaped.
 * @return bool False if option was not added and true if option was added.
 */
function add_blog_option( $id, $option, $value ) {
	$id = (int) $id;

	if ( empty( $id ) )
		$id = get_current_blog_id();

	if ( get_current_blog_id() == $id )
		return add_option( $option, $value );

	switch_to_blog( $id );
	$return = add_option( $option, $value );
	restore_current_blog();

	return $return;
}

/**
 * Removes option by name for a given blog id. Prevents removal of protected WordPress options.
 *
 * @since MU
 *
 * @param int    $id     A blog ID. Can be null to refer to the current blog.
 * @param string $option Name of option to remove. Expected to not be SQL-escaped.
 * @return bool True, if option is successfully deleted. False on failure.
 */
function delete_blog_option( $id, $option ) {
	$id = (int) $id;

	if ( empty( $id ) )
		$id = get_current_blog_id();

	if ( get_current_blog_id() == $id )
		return delete_option( $option );

	switch_to_blog( $id );
	$return = delete_option( $option );
	restore_current_blog();

	return $return;
}

/**
 * Update an option for a particular blog.
 *
 * @since MU
 *
 * @param int    $id     The blog id
 * @param string $option The option key
 * @param mixed  $value  The option value
 * @return bool True on success, false on failure.
 */
function update_blog_option( $id, $option, $value, $deprecated = null ) {
	$id = (int) $id;

	if ( null !== $deprecated  )
		_deprecated_argument( __FUNCTION__, '3.1' );

	if ( get_current_blog_id() == $id )
		return update_option( $option, $value );

	switch_to_blog( $id );
	$return = update_option( $option, $value );
	restore_current_blog();

	refresh_blog_details( $id );

	return $return;
}

/**
 * Switch the current blog.
 *
 * This function is useful if you need to pull posts, or other information,
 * from other blogs. You can switch back afterwards using restore_current_blog().
 *
 * Things that aren't switched:
 *  - autoloaded options. See #14992
 *  - plugins. See #14941
 *
 * @see restore_current_blog()
 * @since MU
 *
 * @global wpdb            $wpdb
 * @global int             $blog_id
 * @global array           $_wp_switched_stack
 * @global bool            $switched
 * @global string          $table_prefix
 * @global WP_Object_Cache $wp_object_cache
 *
 * @param int  $new_blog   The id of the blog you want to switch to. Default: current blog
 * @param bool $deprecated Deprecated argument
 * @return true Always returns True.
 */
function switch_to_blog( $new_blog, $deprecated = null ) {
	global $wpdb;

	if ( empty( $new_blog ) )
		$new_blog = $GLOBALS['blog_id'];

	$GLOBALS['_wp_switched_stack'][] = $GLOBALS['blog_id'];

	/*
	 * If we're switching to the same blog id that we're on,
	 * set the right vars, do the associated actions, but skip
	 * the extra unnecessary work
	 */
	if ( $new_blog == $GLOBALS['blog_id'] ) {
		/**
		 * Fires when the blog is switched.
		 *
		 * @since MU
		 *
		 * @param int $new_blog New blog ID.
		 * @param int $new_blog Blog ID.
		 */
		do_action( 'switch_blog', $new_blog, $new_blog );
		$GLOBALS['switched'] = true;
		return true;
	}

	$wpdb->set_blog_id( $new_blog );
	$GLOBALS['table_prefix'] = $wpdb->get_blog_prefix();
	$prev_blog_id = $GLOBALS['blog_id'];
	$GLOBALS['blog_id'] = $new_blog;

	if ( function_exists( 'wp_cache_switch_to_blog' ) ) {
		wp_cache_switch_to_blog( $new_blog );
	} else {
		global $wp_object_cache;

		if ( is_object( $wp_object_cache ) && isset( $wp_object_cache->global_groups ) )
			$global_groups = $wp_object_cache->global_groups;
		else
			$global_groups = false;

		wp_cache_init();

		if ( function_exists( 'wp_cache_add_global_groups' ) ) {
			if ( is_array( $global_groups ) ) {
				wp_cache_add_global_groups( $global_groups );
			} else {
				wp_cache_add_global_groups( array( 'users', 'userlogins', 'usermeta', 'user_meta', 'useremail', 'userslugs', 'site-transient', 'site-options', 'site-lookup', 'blog-lookup', 'blog-details', 'rss', 'global-posts', 'blog-id-cache' ) );
			}
			wp_cache_add_non_persistent_groups( array( 'comment', 'counts', 'plugins' ) );
		}
	}

	if ( did_action( 'init' ) ) {
		wp_roles()->reinit();
		$current_user = wp_get_current_user();
		$current_user->for_blog( $new_blog );
	}

	/** This filter is documented in wp-includes/ms-blogs.php */
	do_action( 'switch_blog', $new_blog, $prev_blog_id );
	$GLOBALS['switched'] = true;

	return true;
}

/**
 * Restore the current blog, after calling switch_to_blog()
 *
 * @see switch_to_blog()
 * @since MU
 *
 * @global wpdb            $wpdb
 * @global array           $_wp_switched_stack
 * @global int             $blog_id
 * @global bool            $switched
 * @global string          $table_prefix
 * @global WP_Object_Cache $wp_object_cache
 *
 * @return bool True on success, false if we're already on the current blog
 */
function restore_current_blog() {
	global $wpdb;

	if ( empty( $GLOBALS['_wp_switched_stack'] ) )
		return false;

	$blog = array_pop( $GLOBALS['_wp_switched_stack'] );

	if ( $GLOBALS['blog_id'] == $blog ) {
		/** This filter is documented in wp-includes/ms-blogs.php */
		do_action( 'switch_blog', $blog, $blog );
		// If we still have items in the switched stack, consider ourselves still 'switched'
		$GLOBALS['switched'] = ! empty( $GLOBALS['_wp_switched_stack'] );
		return true;
	}

	$wpdb->set_blog_id( $blog );
	$prev_blog_id = $GLOBALS['blog_id'];
	$GLOBALS['blog_id'] = $blog;
	$GLOBALS['table_prefix'] = $wpdb->get_blog_prefix();

	if ( function_exists( 'wp_cache_switch_to_blog' ) ) {
		wp_cache_switch_to_blog( $blog );
	} else {
		global $wp_object_cache;

		if ( is_object( $wp_object_cache ) && isset( $wp_object_cache->global_groups ) )
			$global_groups = $wp_object_cache->global_groups;
		else
			$global_groups = false;

		wp_cache_init();

		if ( function_exists( 'wp_cache_add_global_groups' ) ) {
			if ( is_array( $global_groups ) ) {
				wp_cache_add_global_groups( $global_groups );
			} else {
				wp_cache_add_global_groups( array( 'users', 'userlogins', 'usermeta', 'user_meta', 'useremail', 'userslugs', 'site-transient', 'site-options', 'site-lookup', 'blog-lookup', 'blog-details', 'rss', 'global-posts', 'blog-id-cache' ) );
			}
			wp_cache_add_non_persistent_groups( array( 'comment', 'counts', 'plugins' ) );
		}
	}

	if ( did_action( 'init' ) ) {
		wp_roles()->reinit();
		$current_user = wp_get_current_user();
		$current_user->for_blog( $blog );
	}

	/** This filter is documented in wp-includes/ms-blogs.php */
	do_action( 'switch_blog', $blog, $prev_blog_id );

	// If we still have items in the switched stack, consider ourselves still 'switched'
	$GLOBALS['switched'] = ! empty( $GLOBALS['_wp_switched_stack'] );

	return true;
}

/**
 * Determines if switch_to_blog() is in effect
 *
 * @since 3.5.0
 *
 * @global array $_wp_switched_stack
 *
 * @return bool True if switched, false otherwise.
 */
function ms_is_switched() {
	return ! empty( $GLOBALS['_wp_switched_stack'] );
}

/**
 * Check if a particular blog is archived.
 *
 * @since MU
 *
 * @param int $id The blog id
 * @return string Whether the blog is archived or not
 */
function is_archived( $id ) {
	return get_blog_status($id, 'archived');
}

/**
 * Update the 'archived' status of a particular blog.
 *
 * @since MU
 *
 * @param int    $id       The blog id
 * @param string $archived The new status
 * @return string $archived
 */
function update_archived( $id, $archived ) {
	update_blog_status($id, 'archived', $archived);
	return $archived;
}

/**
 * Update a blog details field.
 *
 * @since MU
 *
 * @global wpdb $wpdb
 *
 * @param int    $blog_id BLog ID
 * @param string $pref    A field name
 * @param string $value   Value for $pref
 * @param null   $deprecated
 * @return string|false $value
 */
function update_blog_status( $blog_id, $pref, $value, $deprecated = null ) {
	global $wpdb;

	if ( null !== $deprecated  )
		_deprecated_argument( __FUNCTION__, '3.1' );

	if ( ! in_array( $pref, array( 'site_id', 'domain', 'path', 'registered', 'last_updated', 'public', 'archived', 'mature', 'spam', 'deleted', 'lang_id') ) )
		return $value;

	$result = $wpdb->update( $wpdb->blogs, array($pref => $value, 'last_updated' => current_time('mysql', true)), array('blog_id' => $blog_id) );

	if ( false === $result )
		return false;

	refresh_blog_details( $blog_id );

	if ( 'spam' == $pref ) {
		if ( $value == 1 ) {
			/** This filter is documented in wp-includes/ms-blogs.php */
			do_action( 'make_spam_blog', $blog_id );
		} else {
			/** This filter is documented in wp-includes/ms-blogs.php */
			do_action( 'make_ham_blog', $blog_id );
		}
	} elseif ( 'mature' == $pref ) {
		if ( $value == 1 ) {
			/** This filter is documented in wp-includes/ms-blogs.php */
			do_action( 'mature_blog', $blog_id );
		} else {
			/** This filter is documented in wp-includes/ms-blogs.php */
			do_action( 'unmature_blog', $blog_id );
		}
	} elseif ( 'archived' == $pref ) {
		if ( $value == 1 ) {
			/** This filter is documented in wp-includes/ms-blogs.php */
			do_action( 'archive_blog', $blog_id );
		} else {
			/** This filter is documented in wp-includes/ms-blogs.php */
			do_action( 'unarchive_blog', $blog_id );
		}
	} elseif ( 'deleted' == $pref ) {
		if ( $value == 1 ) {
			/** This filter is documented in wp-includes/ms-blogs.php */
			do_action( 'make_delete_blog', $blog_id );
		} else {
			/** This filter is documented in wp-includes/ms-blogs.php */
			do_action( 'make_undelete_blog', $blog_id );
		}
	} elseif ( 'public' == $pref ) {
		/**
		 * Fires after the current blog's 'public' setting is updated.
		 *
		 * @since MU
		 *
		 * @param int    $blog_id Blog ID.
		 * @param string $value   The value of blog status.
 		 */
		do_action( 'update_blog_public', $blog_id, $value ); // Moved here from update_blog_public().
	}

	return $value;
}

/**
 * Get a blog details field.
 *
 * @since MU
 *
 * @global wpdb $wpdb
 *
 * @param int    $id   The blog id
 * @param string $pref A field name
 * @return bool|string|null $value
 */
function get_blog_status( $id, $pref ) {
	global $wpdb;

	$details = get_blog_details( $id, false );
	if ( $details )
		return $details->$pref;

	return $wpdb->get_var( $wpdb->prepare("SELECT %s FROM {$wpdb->blogs} WHERE blog_id = %d", $pref, $id) );
}

/**
 * Get a list of most recently updated blogs.
 *
 * @since MU
 *
 * @global wpdb $wpdb
 *
 * @param mixed $deprecated Not used
 * @param int   $start      The offset
 * @param int   $quantity   The maximum number of blogs to retrieve. Default is 40.
 * @return array The list of blogs
 */
function get_last_updated( $deprecated = '', $start = 0, $quantity = 40 ) {
	global $wpdb;

	if ( ! empty( $deprecated ) )
		_deprecated_argument( __FUNCTION__, 'MU' ); // never used

	return $wpdb->get_results( $wpdb->prepare("SELECT blog_id, domain, path FROM $wpdb->blogs WHERE site_id = %d AND public = '1' AND archived = '0' AND mature = '0' AND spam = '0' AND deleted = '0' AND last_updated != '0000-00-00 00:00:00' ORDER BY last_updated DESC limit %d, %d", $wpdb->siteid, $start, $quantity ) , ARRAY_A );
}

/**
 * Handler for updating the blog date when a post is published or an already published post is changed.
 *
 * @since 3.3.0
 *
 * @param string $new_status The new post status
 * @param string $old_status The old post status
 * @param object $post       Post object
 */
function _update_blog_date_on_post_publish( $new_status, $old_status, $post ) {
	$post_type_obj = get_post_type_object( $post->post_type );
	if ( ! $post_type_obj || ! $post_type_obj->public ) {
		return;
	}

	if ( 'publish' != $new_status && 'publish' != $old_status ) {
		return;
	}

	// Post was freshly published, published post was saved, or published post was unpublished.

	wpmu_update_blogs_date();
}

/**
 * Handler for updating the blog date when a published post is deleted.
 *
 * @since 3.4.0
 *
 * @param int $post_id Post ID
 */
function _update_blog_date_on_post_delete( $post_id ) {
	$post = get_post( $post_id );

	$post_type_obj = get_post_type_object( $post->post_type );
	if ( ! $post_type_obj || ! $post_type_obj->public ) {
		return;
	}

	if ( 'publish' != $post->post_status ) {
		return;
	}

	wpmu_update_blogs_date();
}

/**
 * Handler for updating the blog posts count date when a post is deleted.
 *
 * @since 4.0.0
 *
 * @param int $post_id Post ID.
 */
function _update_posts_count_on_delete( $post_id ) {
	$post = get_post( $post_id );

	if ( ! $post || 'publish' !== $post->post_status ) {
		return;
	}

	update_posts_count();
}

/**
 * Handler for updating the blog posts count date when a post status changes.
 *
 * @since 4.0.0
 *
 * @param string $new_status The status the post is changing to.
 * @param string $old_status The status the post is changing from.
 */
function _update_posts_count_on_transition_post_status( $new_status, $old_status ) {
	if ( $new_status === $old_status ) {
		return;
	}

	if ( 'publish' !== $new_status && 'publish' !== $old_status ) {
		return;
	}

	update_posts_count();
}

                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   <?php
/**
 * Date and Time Locale object
 *
 * @package WordPress
 * @subpackage i18n
 */

/**
 * Class that loads the calendar locale.
 *
 * @since 2.1.0
 */
class WP_Locale {
	/**
	 * Stores the translated strings for the full weekday names.
	 *
	 * @since 2.1.0
	 * @var array
	 */
	public $weekday;

	/**
	 * Stores the translated strings for the one character weekday names.
	 *
	 * There is a hack to make sure that Tuesday and Thursday, as well
	 * as Sunday and Saturday, don't conflict. See init() method for more.
	 *
	 * @see WP_Locale::init() for how to handle the hack.
	 *
	 * @since 2.1.0
	 * @var array
	 */
	public $weekday_initial;

	/**
	 * Stores the translated strings for the abbreviated weekday names.
	 *
	 * @since 2.1.0
	 * @var array
	 */
	public $weekday_abbrev;

	/**
	 * Stores the translated strings for the full month names.
	 *
	 * @since 2.1.0
	 * @var array
	 */
	public $month;

	/**
	 * Stores the translated strings for the abbreviated month names.
	 *
	 * @since 2.1.0
	 * @var array
	 */
	public $month_abbrev;

	/**
	 * Stores the translated strings for 'am' and 'pm'.
	 *
	 * Also the capitalized versions.
	 *
	 * @since 2.1.0
	 * @var array
	 */
	public $meridiem;

	/**
	 * The text direction of the locale language.
	 *
	 * Default is left to right 'ltr'.
	 *
	 * @since 2.1.0
	 * @var string
	 */
	public $text_direction = 'ltr';

	/**
	 * @var array
	 */
	public $number_format;

	/**
	 * Sets up the translated strings and object properties.
	 *
	 * The method creates the translatable strings for various
	 * calendar elements. Which allows for specifying locale
	 * specific calendar names and text direction.
	 *
	 * @since 2.1.0
	 * @access private
	 *
	 * @global string $text_direction
	 * @global string $wp_version
	 */
	public function init() {
		// The Weekdays
		$this->weekday[0] = /* translators: weekday */ __('Sunday');
		$this->weekday[1] = /* translators: weekday */ __('Monday');
		$this->weekday[2] = /* translators: weekday */ __('Tuesday');
		$this->weekday[3] = /* translators: weekday */ __('Wednesday');
		$this->weekday[4] = /* translators: weekday */ __('Thursday');
		$this->weekday[5] = /* translators: weekday */ __('Friday');
		$this->weekday[6] = /* translators: weekday */ __('Saturday');

		// The first letter of each day. The _%day%_initial suffix is a hack to make
		// sure the day initials are unique.
		$this->weekday_initial[__('Sunday')]    = /* translators: one-letter abbreviation of the weekday */ __('S_Sunday_initial');
		$this->weekday_initial[__('Monday')]    = /* translators: one-letter abbreviation of the weekday */ __('M_Monday_initial');
		$this->weekday_initial[__('Tuesday')]   = /* translators: one-letter abbreviation of the weekday */ __('T_Tuesday_initial');
		$this->weekday_initial[__('Wednesday')] = /* translators: one-letter abbreviation of the weekday */ __('W_Wednesday_initial');
		$this->weekday_initial[__('Thursday')]  = /* translators: one-letter abbreviation of the weekday */ __('T_Thursday_initial');
		$this->weekday_initial[__('Friday')]    = /* translators: one-letter abbreviation of the weekday */ __('F_Friday_initial');
		$this->weekday_initial[__('Saturday')]  = /* translators: one-letter abbreviation of the weekday */ __('S_Saturday_initial');

		foreach ($this->weekday_initial as $weekday_ => $weekday_initial_) {
			$this->weekday_initial[$weekday_] = preg_replace('/_.+_initial$/', '', $weekday_initial_);
		}

		// Abbreviations for each day.
		$this->weekday_abbrev[__('Sunday')]    = /* translators: three-letter abbreviation of the weekday */ __('Sun');
		$this->weekday_abbrev[__('Monday')]    = /* translators: three-letter abbreviation of the weekday */ __('Mon');
		$this->weekday_abbrev[__('Tuesday')]   = /* translators: three-letter abbreviation of the weekday */ __('Tue');
		$this->weekday_abbrev[__('Wednesday')] = /* translators: three-letter abbreviation of the weekday */ __('Wed');
		$this->weekday_abbrev[__('Thursday')]  = /* translators: three-letter abbreviation of the weekday */ __('Thu');
		$this->weekday_abbrev[__('Friday')]    = /* translators: three-letter abbreviation of the weekday */ __('Fri');
		$this->weekday_abbrev[__('Saturday')]  = /* translators: three-letter abbreviation of the weekday */ __('Sat');

		// The Months
		$this->month['01'] = /* translators: month name */ __('January');
		$this->month['02'] = /* translators: month name */ __('February');
		$this->month['03'] = /* translators: month name */ __('March');
		$this->month['04'] = /* translators: month name */ __('April');
		$this->month['05'] = /* translators: month name */ __('May');
		$this->month['06'] = /* translators: month name */ __('June');
		$this->month['07'] = /* translators: month name */ __('July');
		$this->month['08'] = /* translators: month name */ __('August');
		$this->month['09'] = /* translators: month name */ __('September');
		$this->month['10'] = /* translators: month name */ __('October');
		$this->month['11'] = /* translators: month name */ __('November');
		$this->month['12'] = /* translators: month name */ __('December');

		// Abbreviations for each month. Uses the same hack as above to get around the
		// 'May' duplication.
		$this->month_abbrev[__('January')] = /* translators: three-letter abbreviation of the month */ __('Jan_January_abbreviation');
		$this->month_abbrev[__('February')] = /* translators: three-letter abbreviation of the month */ __('Feb_February_abbreviation');
		$this->month_abbrev[__('March')] = /* translators: three-letter abbreviation of the month */ __('Mar_March_abbreviation');
		$this->month_abbrev[__('April')] = /* translators: three-letter abbreviation of the month */ __('Apr_April_abbreviation');
		$this->month_abbrev[__('May')] = /* translators: three-letter abbreviation of the month */ __('May_May_abbreviation');
		$this->month_abbrev[__('June')] = /* translators: three-letter abbreviation of the month */ __('Jun_June_abbreviation');
		$this->month_abbrev[__('July')] = /* translators: three-letter abbreviation of the month */ __('Jul_July_abbreviation');
		$this->month_abbrev[__('August')] = /* translators: three-letter abbreviation of the month */ __('Aug_August_abbreviation');
		$this->month_abbrev[__('September')] = /* translators: three-letter abbreviation of the month */ __('Sep_September_abbreviation');
		$this->month_abbrev[__('October')] = /* translators: three-letter abbreviation of the month */ __('Oct_October_abbreviation');
		$this->month_abbrev[__('November')] = /* translators: three-letter abbreviation of the month */ __('Nov_November_abbreviation');
		$this->month_abbrev[__('December')] = /* translators: three-letter abbreviation of the month */ __('Dec_December_abbreviation');

		foreach ($this->month_abbrev as $month_ => $month_abbrev_) {
			$this->month_abbrev[$month_] = preg_replace('/_.+_abbreviation$/', '', $month_abbrev_);
		}

		// The Meridiems
		$this->meridiem['am'] = __('am');
		$this->meridiem['pm'] = __('pm');
		$this->meridiem['AM'] = __('AM');
		$this->meridiem['PM'] = __('PM');

		// Numbers formatting
		// See http://php.net/number_format

		/* translators: $thousands_sep argument for http://php.net/number_format, default is , */
		$trans = __('number_format_thousands_sep');
		$this->number_format['thousands_sep'] = ('number_format_thousands_sep' == $trans) ? ',' : $trans;

		/* translators: $dec_point argument for http://php.net/number_format, default is . */
		$trans = __('number_format_decimal_point');
		$this->number_format['decimal_point'] = ('number_format_decimal_point' == $trans) ? '.' : $trans;

		// Set text direction.
		if ( isset( $GLOBALS['text_direction'] ) )
			$this->text_direction = $GLOBALS['text_direction'];
		/* translators: 'rtl' or 'ltr'. This sets the text direction for WordPress. */
		elseif ( 'rtl' == _x( 'ltr', 'text direction' ) )
			$this->text_direction = 'rtl';

		if ( 'rtl' === $this->text_direction && strpos( $GLOBALS['wp_version'], '-src' ) ) {
			$this->text_direction = 'ltr';
			add_action( 'all_admin_notices', array( $this, 'rtl_src_admin_notice' ) );
		}
	}

	/**
	 * @since 3.8.0
	 */
	public function rtl_src_admin_notice() {
		/* translators: %s: Name of the directory (build) */
		echo '<div class="error"><p>' . sprintf( __( 'The %s directory of the develop repository must be used for RTL.' ), '<code>build</code>' ) . '</p></div>';
	}

	/**
	 * Retrieve the full translated weekday word.
	 *
	 * Week starts on translated Sunday and can be fetched
	 * by using 0 (zero). So the week starts with 0 (zero)
	 * and ends on Saturday with is fetched by using 6 (six).
	 *
	 * @since 2.1.0
	 * @access public
	 *
	 * @param int $weekday_number 0 for Sunday through 6 Saturday
	 * @return string Full translated weekday
	 */
	public function get_weekday($weekday_number) {
		return $this->weekday[$weekday_number];
	}

	/**
	 * Retrieve the translated weekday initial.
	 *
	 * The weekday initial is retrieved by the translated
	 * full weekday word. When translating the weekday initial
	 * pay attention to make sure that the starting letter does
	 * not conflict.
	 *
	 * @since 2.1.0
	 * @access public
	 *
	 * @param string $weekday_name
	 * @return string
	 */
	public function get_weekday_initial($weekday_name) {
		return $this->weekday_initial[$weekday_name];
	}

	/**
	 * Retrieve the translated weekday abbreviation.
	 *
	 * The weekday abbreviation is retrieved by the translated
	 * full weekday word.
	 *
	 * @since 2.1.0
	 * @access public
	 *
	 * @param string $weekday_name Full translated weekday word
	 * @return string Translated weekday abbreviation
	 */
	public function get_weekday_abbrev($weekday_name) {
		return $this->weekday_abbrev[$weekday_name];
	}

	/**
	 * Retrieve the full translated month by month number.
	 *
	 * The $month_number parameter has to be a string
	 * because it must have the '0' in front of any number
	 * that is less than 10. Starts from '01' and ends at
	 * '12'.
	 *
	 * You can use an integer instead and it will add the
	 * '0' before the numbers less than 10 for you.
	 *
	 * @since 2.1.0
	 * @access public
	 *
	 * @param string|int $month_number '01' through '12'
	 * @return string Translated full month name
	 */
	public function get_month($month_number) {
		return $this->month[zeroise($month_number, 2)];
	}

	/**
	 * Retrieve translated version of month abbreviation string.
	 *
	 * The $month_name parameter is expected to be the translated or
	 * translatable version of the month.
	 *
	 * @since 2.1.0
	 * @access public
	 *
	 * @param string $month_name Translated month to get abbreviated version
	 * @return string Translated abbreviated month
	 */
	public function get_month_abbrev($month_name) {
		return $this->month_abbrev[$month_name];
	}

	/**
	 * Retrieve translated version of meridiem string.
	 *
	 * The $meridiem parameter is expected to not be translated.
	 *
	 * @since 2.1.0
	 * @access public
	 *
	 * @param string $meridiem Either 'am', 'pm', 'AM', or 'PM'. Not translated version.
	 * @return string Translated version
	 */
	public function get_meridiem($meridiem) {
		return $this->meridiem[$meridiem];
	}

	/**
	 * Global variables are deprecated. For backwards compatibility only.
	 *
	 * @deprecated For backwards compatibility only.
	 * @access private
	 *
	 * @global array $weekday
	 * @global array $weekday_initial
	 * @global array $weekday_abbrev
	 * @global array $month
	 * @global array $month_abbrev
	 *
	 * @since 2.1.0
	 */
	public function register_globals() {
		$GLOBALS['weekday']         = $this->weekday;
		$GLOBALS['weekday_initial'] = $this->weekday_initial;
		$GLOBALS['weekday_abbrev']  = $this->weekday_abbrev;
		$GLOBALS['month']           = $this->month;
		$GLOBALS['month_abbrev']    = $this->month_abbrev;
	}

	/**
	 * Constructor which calls helper methods to set up object variables
	 *
	 * @since 2.1.0
	 */
	public function __construct() {
		$this->init();
		$this->register_globals();
	}

	/**
	 * Checks if current locale is RTL.
	 *
	 * @since 3.0.0
	 * @return bool Whether locale is RTL.
	 */
	public function is_rtl() {
		return 'rtl' == $this->text_direction;
	}

	/**
	 * Register date/time format strings for general POT.
	 *
	 * Private, unused method to add some date/time formats translated
	 * on wp-admin/options-general.php to the general POT that would
	 * otherwise be added to the admin POT.
	 *
	 * @since 3.6.0
	 */
	public function _strings_for_pot() {
		/* translators: localized date format, see http://php.net/date */
		__( 'F j, Y' );
		/* translators: localized time format, see http://php.net/date */
		__( 'g:i a' );
		/* translators: localized date and time format, see http://php.net/date */
		__( 'F j, Y g:i a' );
	}
}

/**
 * Checks if current locale is RTL.
 *
 * @since 3.0.0
 *
 * @global WP_Locale $wp_locale
 *
 * @return bool Whether locale is RTL.
 */
function is_rtl() {
	global $wp_locale;
	return $wp_locale->is_rtl();
}
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  <?php
/**
 * These functions are needed to load WordPress.
 *
 * @internal This file must be parsable by PHP4.
 *
 * @package WordPress
 */

/**
 * Turn register globals off.
 *
 * @since 2.1.0
 * @access private
 */
function wp_unregister_GLOBALS() {
	if ( !ini_get( 'register_globals' ) )
		return;

	if ( isset( $_REQUEST['GLOBALS'] ) )
		die( 'GLOBALS overwrite attempt detected' );

	// Variables that shouldn't be unset
	$no_unset = array( 'GLOBALS', '_GET', '_POST', '_COOKIE', '_REQUEST', '_SERVER', '_ENV', '_FILES', 'table_prefix' );

	$input = array_merge( $_GET, $_POST, $_COOKIE, $_SERVER, $_ENV, $_FILES, isset( $_SESSION ) && is_array( $_SESSION ) ? $_SESSION : array() );
	foreach ( $input as $k => $v )
		if ( !in_array( $k, $no_unset ) && isset( $GLOBALS[$k] ) ) {
			unset( $GLOBALS[$k] );
		}
}

/**
 * Fix `$_SERVER` variables for various setups.
 *
 * @since 3.0.0
 * @access private
 *
 * @global string $PHP_SELF The filename of the currently executing script,
 *                          relative to the document root.
 */
function wp_fix_server_vars() {
	global $PHP_SELF;

	$default_server_values = array(
		'SERVER_SOFTWARE' => '',
		'REQUEST_URI' => '',
	);

	$_SERVER = array_merge( $default_server_values, $_SERVER );

	// Fix for IIS when running with PHP ISAPI
	if ( empty( $_SERVER['REQUEST_URI'] ) || ( PHP_SAPI != 'cgi-fcgi' && preg_match( '/^Microsoft-IIS\//', $_SERVER['SERVER_SOFTWARE'] ) ) ) {

		// IIS Mod-Rewrite
		if ( isset( $_SERVER['HTTP_X_ORIGINAL_URL'] ) ) {
			$_SERVER['REQUEST_URI'] = $_SERVER['HTTP_X_ORIGINAL_URL'];
		}
		// IIS Isapi_Rewrite
		elseif ( isset( $_SERVER['HTTP_X_REWRITE_URL'] ) ) {
			$_SERVER['REQUEST_URI'] = $_SERVER['HTTP_X_REWRITE_URL'];
		} else {
			// Use ORIG_PATH_INFO if there is no PATH_INFO
			if ( !isset( $_SERVER['PATH_INFO'] ) && isset( $_SERVER['ORIG_PATH_INFO'] ) )
				$_SERVER['PATH_INFO'] = $_SERVER['ORIG_PATH_INFO'];

			// Some IIS + PHP configurations puts the script-name in the path-info (No need to append it twice)
			if ( isset( $_SERVER['PATH_INFO'] ) ) {
				if ( $_SERVER['PATH_INFO'] == $_SERVER['SCRIPT_NAME'] )
					$_SERVER['REQUEST_URI'] = $_SERVER['PATH_INFO'];
				else
					$_SERVER['REQUEST_URI'] = $_SERVER['SCRIPT_NAME'] . $_SERVER['PATH_INFO'];
			}

			// Append the query string if it exists and isn't null
			if ( ! empty( $_SERVER['QUERY_STRING'] ) ) {
				$_SERVER['REQUEST_URI'] .= '?' . $_SERVER['QUERY_STRING'];
			}
		}
	}

	// Fix for PHP as CGI hosts that set SCRIPT_FILENAME to something ending in php.cgi for all requests
	if ( isset( $_SERVER['SCRIPT_FILENAME'] ) && ( strpos( $_SERVER['SCRIPT_FILENAME'], 'php.cgi' ) == strlen( $_SERVER['SCRIPT_FILENAME'] ) - 7 ) )
		$_SERVER['SCRIPT_FILENAME'] = $_SERVER['PATH_TRANSLATED'];

	// Fix for Dreamhost and other PHP as CGI hosts
	if ( strpos( $_SERVER['SCRIPT_NAME'], 'php.cgi' ) !== false )
		unset( $_SERVER['PATH_INFO'] );

	// Fix empty PHP_SELF
	$PHP_SELF = $_SERVER['PHP_SELF'];
	if ( empty( $PHP_SELF ) )
		$_SERVER['PHP_SELF'] = $PHP_SELF = preg_replace( '/(\?.*)?$/', '', $_SERVER["REQUEST_URI"] );
}

/**
 * Check for the required PHP version, and the MySQL extension or
 * a database drop-in.
 *
 * Dies if requirements are not met.
 *
 * @since 3.0.0
 * @access private
 *
 * @global string $required_php_version The required PHP version string.
 * @global string $wp_version           The WordPress version string.
 */
function wp_check_php_mysql_versions() {
	global $required_php_version, $wp_version;
	$php_version = phpversion();

	if ( version_compare( $required_php_version, $php_version, '>' ) ) {
		wp_load_translations_early();
		header( 'Content-Type: text/html; charset=utf-8' );
		die( sprintf( __( 'Your server is running PHP version %1$s but WordPress %2$s requires at least %3$s.' ), $php_version, $wp_version, $required_php_version ) );
	}

	if ( ! extension_loaded( 'mysql' ) && ! extension_loaded( 'mysqli' ) && ! file_exists( WP_CONTENT_DIR . '/db.php' ) ) {
		wp_load_translations_early();
		 header( 'Content-Type: text/html; charset=utf-8' );
		die( __( 'Your PHP installation appears to be missing the MySQL extension which is required by WordPress.' ) );
	}
}

/**
 * Don't load all of WordPress when handling a favicon.ico request.
 *
 * Instead, send the headers for a zero-length favicon and bail.
 *
 * @since 3.0.0
 */
function wp_favicon_request() {
	if ( '/favicon.ico' == $_SERVER['REQUEST_URI'] ) {
		header('Content-Type: image/vnd.microsoft.icon');
		header('Content-Length: 0');
		exit;
	}
}

/**
 * Die with a maintenance message when conditions are met.
 *
 * Checks for a file in the WordPress root directory named ".maintenance".
 * This file will contain the variable $upgrading, set to the time the file
 * was created. If the file was created less than 10 minutes ago, WordPress
 * enters maintenance mode and displays a message.
 *
 * The default message can be replaced by using a drop-in (maintenance.php in
 * the wp-content directory).
 *
 * @since 3.0.0
 * @access private
 *
 * @global int $upgrading the unix timestamp marking when upgrading WordPress began.
 */
function wp_maintenance() {
	if ( !file_exists( ABSPATH . '.maintenance' ) || defined( 'WP_INSTALLING' ) )
		return;

	global $upgrading;

	include( ABSPATH . '.maintenance' );
	// If the $upgrading timestamp is older than 10 minutes, don't die.
	if ( ( time() - $upgrading ) >= 600 )
		return;

	if ( file_exists( WP_CONTENT_DIR . '/maintenance.php' ) ) {
		require_once( WP_CONTENT_DIR . '/maintenance.php' );
		die();
	}

	wp_load_translations_early();

	$protocol = $_SERVER["SERVER_PROTOCOL"];
	if ( 'HTTP/1.1' != $protocol && 'HTTP/1.0' != $protocol )
		$protocol = 'HTTP/1.0';
	header( "$protocol 503 Service Unavailable", true, 503 );
	header( 'Content-Type: text/html; charset=utf-8' );
	header( 'Retry-After: 600' );
?>
	<!DOCTYPE html>
	<html xmlns="http://www.w3.org/1999/xhtml"<?php if ( is_rtl() ) echo ' dir="rtl"'; ?>>
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title><?php _e( 'Maintenance' ); ?></title>

	</head>
	<body>
		<h1><?php _e( 'Briefly unavailable for scheduled maintenance. Check back in a minute.' ); ?></h1>
	</body>
	</html>
<?php
	die();
}

/**
 * Start the WordPress micro-timer.
 *
 * @since 0.71
 * @access private
 *
 * @global float $timestart Unix timestamp set at the beginning of the page load.
 * @see timer_stop()
 *
 * @return bool Always returns true.
 */
function timer_start() {
	global $timestart;
	$timestart = microtime( true );
	return true;
}

/**
 * Retrieve or display the time from the page start to when function is called.
 *
 * @since 0.71
 *
 * @global float   $timestart Seconds from when timer_start() is called.
 * @global float   $timeend   Seconds from when function is called.
 *
 * @param int|bool $display   Whether to echo or return the results. Accepts 0|false for return,
 *                            1|true for echo. Default 0|false.
 * @param int      $precision The number of digits from the right of the decimal to display.
 *                            Default 3.
 * @return string The "second.microsecond" finished time calculation. The number is formatted
 *                for human consumption, both localized and rounded.
 */
function timer_stop( $display = 0, $precision = 3 ) {
	global $timestart, $timeend;
	$timeend = microtime( true );
	$timetotal = $timeend - $timestart;
	$r = ( function_exists( 'number_format_i18n' ) ) ? number_format_i18n( $timetotal, $precision ) : number_format( $timetotal, $precision );
	if ( $display )
		echo $r;
	return $r;
}

/**
 * Set PHP error reporting based on WordPress debug settings.
 *
 * Uses three constants: `WP_DEBUG`, `WP_DEBUG_DISPLAY`, and `WP_DEBUG_LOG`.
 * All three can be defined in wp-config.php, and by default are set to false.
 *
 * When `WP_DEBUG` is true, all PHP notices are reported. WordPress will also
 * display internal notices: when a deprecated WordPress function, function
 * argument, or file is used. Deprecated code may be removed from a later
 * version.
 *
 * It is strongly recommended that plugin and theme developers use `WP_DEBUG`
 * in their development environments.
 *
 * `WP_DEBUG_DISPLAY` and `WP_DEBUG_LOG` perform no function unless `WP_DEBUG`
 * is true.
 *
 * When `WP_DEBUG_DISPLAY` is true, WordPress will force errors to be displayed.
 * `WP_DEBUG_DISPLAY` defaults to true. Defining it as null prevents WordPress
 * from changing the global configuration setting. Defining `WP_DEBUG_DISPLAY`
 * as false will force errors to be hidden.
 *
 * When `WP_DEBUG_LOG` is true, errors will be logged to debug.log in the content
 * directory.
 *
 * Errors are never displayed for XML-RPC requests.
 *
 * @since 3.0.0
 * @access private
 */
function wp_debug_mode() {
	if ( WP_DEBUG ) {
		error_reporting( E_ALL );

		if ( WP_DEBUG_DISPLAY )
			ini_set( 'display_errors', 1 );
		elseif ( null !== WP_DEBUG_DISPLAY )
			ini_set( 'display_errors', 0 );

		if ( WP_DEBUG_LOG ) {
			ini_set( 'log_errors', 1 );
			ini_set( 'error_log', WP_CONTENT_DIR . '/debug.log' );
		}
	} else {
		error_reporting( E_CORE_ERROR | E_CORE_WARNING | E_COMPILE_ERROR | E_ERROR | E_WARNING | E_PARSE | E_USER_ERROR | E_USER_WARNING | E_RECOVERABLE_ERROR );
	}
	if ( defined( 'XMLRPC_REQUEST' ) )
		ini_set( 'display_errors', 0 );
}

/**
 * Set the location of the language directory.
 *
 * To set directory manually, define the `WP_LANG_DIR` constant
 * in wp-config.php.
 *
 * If the language directory exists within `WP_CONTENT_DIR