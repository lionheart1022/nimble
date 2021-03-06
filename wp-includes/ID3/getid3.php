                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                <?php
/**
 * Canonical API to handle WordPress Redirecting
 *
 * Based on "Permalink Redirect" from Scott Yang and "Enforce www. Preference"
 * by Mark Jaquith
 *
 * @package WordPress
 * @since 2.3.0
 */

/**
 * Redirects incoming links to the proper URL based on the site url.
 *
 * Search engines consider www.somedomain.com and somedomain.com to be two
 * different URLs when they both go to the same location. This SEO enhancement
 * prevents penalty for duplicate content by redirecting all incoming links to
 * one or the other.
 *
 * Prevents redirection for feeds, trackbacks, searches, comment popup, and
 * admin URLs. Does not redirect on non-pretty-permalink-supporting IIS 7+,
 * page/post previews, WP admin, Trackbacks, robots.txt, searches, or on POST
 * requests.
 *
 * Will also attempt to find the correct link when a user enters a URL that does
 * not exist based on exact WordPress query. Will instead try to parse the URL
 * or query in an attempt to figure the correct page to go to.
 *
 * @since 2.3.0
 *
 * @global WP_Rewrite $wp_rewrite
 * @global bool $is_IIS
 * @global WP_Query $wp_query
 * @global wpdb $wpdb
 *
 * @param string $requested_url Optional. The URL that was requested, used to
 *		figure if redirect is needed.
 * @param bool $do_redirect Optional. Redirect to the new URL.
 * @return string|void The string of the URL, if redirect needed.
 */
function redirect_canonical( $requested_url = null, $do_redirect = true ) {
	global $wp_rewrite, $is_IIS, $wp_query, $wpdb;

	if ( isset( $_SERVER['REQUEST_METHOD'] ) && ! in_array( strtoupper( $_SERVER['REQUEST_METHOD'] ), array( 'GET', 'HEAD' ) ) ) {
		return;
	}

	// If we're not in wp-admin and the post has been published and preview nonce
	// is non-existent or invalid then no need for preview in query
	if ( is_preview() && get_query_var( 'p' ) && 'publish' == get_post_status( get_query_var( 'p' ) ) ) {
		if ( ! isset( $_GET['preview_id'] )
			|| ! isset( $_GET['preview_nonce'] )
			|| ! wp_verify_nonce( $_GET['preview_nonce'], 'post_preview_' . (int) $_GET['preview_id'] ) ) {
			$wp_query->is_preview = false;
		}
	}

	if ( is_trackback() || is_search() || is_comments_popup() || is_admin() || is_preview() || is_robots() || ( $is_IIS && !iis7_supports_permalinks() ) ) {
		return;
	}

	if ( !$requested_url ) {
		// build the URL in the address bar
		$requested_url  = is_ssl() ? 'https://' : 'http://';
		$requested_url .= $_SERVER['HTTP_HOST'];
		$requested_url .= $_SERVER['REQUEST_URI'];
	}

	$original = @parse_url($requested_url);
	if ( false === $original )
		return;

	// Some PHP setups turn requests for / into /index.php in REQUEST_URI
	// See: https://core.trac.wordpress.org/ticket/5017
	// See: https://core.trac.wordpress.org/ticket/7173
	// Disabled, for now:
	// $original['path'] = preg_replace('|/index\.php$|', '/', $original['path']);

	$redirect = $original;
	$redirect_url = false;

	// Notice fixing
	if ( !isset($redirect['path']) )
		$redirect['path'] = '';
	if ( !isset($redirect['query']) )
		$redirect['query'] = '';

	// If the original URL ended with non-breaking spaces, they were almost
	// certainly inserted by accident. Let's remove them, so the reader doesn't
	// see a 404 error with no obvious cause.
	$redirect['path'] = preg_replace( '|(%C2%A0)+$|i', '', $redirect['path'] );

	// It's not a preview, so remove it from URL
	if ( get_query_var( 'preview' ) ) {
		$redirect['query'] = remove_query_arg( 'preview', $redirect['query'] );
	}

	if ( is_feed() && ( $id = get_query_var( 'p' ) ) ) {
		if ( $redirect_url = get_post_comments_feed_link( $id, get_query_var( 'feed' ) ) ) {
			$redirect['query'] = _remove_qs_args_if_not_in_url( $redirect['query'], array( 'p', 'page_id', 'attachment_id', 'pagename', 'name', 'post_type', 'feed'), $redirect_url );
			$redirect['path'] = parse_url( $redirect_url, PHP_URL_PATH );
		}
	}

	if ( is_singular() && 1 > $wp_query->post_count && ($id = get_query_var('p')) ) {

		$vars = $wpdb->get_results( $wpdb->prepare("SELECT post_type, post_parent FROM $wpdb->posts WHERE ID = %d", $id) );

		if ( isset($vars[0]) && $vars = $vars[0] ) {
			if ( 'revision' == $vars->post_type && $vars->post_parent > 0 )
				$id = $vars->post_parent;

			if ( $redirect_url = get_permalink($id) )
				$redirect['query'] = _remove_qs_args_if_not_in_url( $redirect['query'], array( 'p', 'page_id', 'attachment_id', 'pagename', 'name', 'post_type' ), $redirect_url );
		}
	}

	// These tests give us a WP-generated permalink
	if ( is_404() ) {

		// Redirect ?page_id, ?p=, ?attachment_id= to their respective url's
		$id = max( get_query_var('p'), get_query_var('page_id'), get_query_var('attachment_id') );
		if ( $id && $redirect_post = get_post($id) ) {
			$post_type_obj = get_post_type_object($redirect_post->post_type);
			if ( $post_type_obj->public ) {
				$redirect_url = get_permalink($redirect_post);
				$redirect['query'] = _remove_qs_args_if_not_in_url( $redirect['query'], array( 'p', 'page_id', 'attachment_id', 'pagename', 'name', 'post_type' ), $redirect_url );
			}
		}

		if ( get_query_var( 'day' ) && get_query_var( 'monthnum' ) && get_query_var( 'year' ) ) {
			$year  = get_query_var( 'year' );
			$month = get_query_var( 'monthnum' );
			$day   = get_query_var( 'day' );
			$date  = sprintf( '%04d-%02d-%02d', $year, $month, $day );
			if ( ! wp_checkdate( $month, $day, $year, $date ) ) {
				$redirect_url = get_month_link( $year, $month );
				$redirect['query'] = _remove_qs_args_if_not_in_url( $redirect['query'], array( 'year', 'monthnum', 'day' ), $redirect_url );
			}
		} elseif ( get_query_var( 'monthnum' ) && get_query_var( 'year' ) && 12 < get_query_var( 'monthnum' ) ) {
			$redirect_url = get_year_link( get_query_var( 'year' ) );
			$redirect['query'] = _remove_qs_args_if_not_in_url( $redirect['query'], array( 'year', 'monthnum' ), $redirect_url );
		}

		if ( ! $redirect_url ) {
			if ( $redirect_url = redirect_guess_404_permalink() ) {
				$redirect['query'] = _remove_qs_args_if_not_in_url( $redirect['query'], array( 'page', 'feed', 'p', 'page_id', 'attachment_id', 'pagename', 'name', 'post_type' ), $redirect_url );
			}
		}

	} elseif ( is_object($wp_rewrite) && $wp_rewrite->using_permalinks() ) {
		// rewriting of old ?p=X, ?m=2004, ?m=200401, ?m=20040101
		if ( is_attachment() && !empty($_GET['attachment_id']) && ! $redirect_url ) {
			if ( $redirect_url = get_attachment_link(get_query_var('attachment_id')) )
				$redirect['query'] = remove_query_arg('attachment_id', $redirect['query']);
		} elseif ( is_single() && !empty($_GET['p']) && ! $redirect_url ) {
			if ( $redirect_url = get_permalink(get_query_var('p')) )
				$redirect['query'] = remove_query_arg(array('p', 'post_type'), $redirect['query']);
		} elseif ( is_single() && !empty($_GET['name'])  && ! $redirect_url ) {
			if ( $redirect_url = get_permalink( $wp_query->get_queried_object_id() ) )
				$redirect['query'] = remove_query_arg('name', $redirect['query']);
		} elseif ( is_page() && !empty($_GET['page_id']) && ! $redirect_url ) {
			if ( $redirect_url = get_permalink(get_query_var('page_id')) )
				$redirect['query'] = remove_query_arg('page_id', $redirect['query']);
		} elseif ( is_page() && !is_feed() && isset($wp_query->queried_object) && 'page' == get_option('show_on_front') && $wp_query->queried_object->ID == get_option('page_on_front')  && ! $redirect_url ) {
			$redirect_url = home_url('/');
		} elseif ( is_home() && !empty($_GET['page_id']) && 'page' == get_option('show_on_front') && get_query_var('page_id') == get_option('page_for_posts')  && ! $redirect_url ) {
			if ( $redirect_url = get_permalink(get_option('page_for_posts')) )
				$redirect['query'] = remove_query_arg('page_id', $redirect['query']);
		} elseif ( !empty($_GET['m']) && ( is_year() || is_month() || is_day() ) ) {
			$m = get_query_var('m');
			switch ( strlen($m) ) {
				case 4: // Yearly
					$redirect_url = get_year_link($m);
					break;
				case 6: // Monthly
					$redirect_url = get_month_link( substr($m, 0, 4), substr($m, 4, 2) );
					break;
				case 8: // Daily
					$redirect_url = get_day_link(substr($m, 0, 4), substr($m, 4, 2), substr($m, 6, 2));
					break;
			}
			if ( $redirect_url )
				$redirect['query'] = remove_query_arg('m', $redirect['query']);
		// now moving on to non ?m=X year/month/day links
		} elseif ( is_day() && get_query_var('year') && get_query_var('monthnum') && !empty($_GET['day']) ) {
			if ( $redirect_url = get_day_link(get_query_var('year'), get_query_var('monthnum'), get_query_var('day')) )
				$redirect['query'] = remove_query_arg(array('year', 'monthnum', 'day'), $redirect['query']);
		} elseif ( is_month() && get_query_var('year') && !empty($_GET['monthnum']) ) {
			if ( $redirect_url = get_month_link(get_query_var('year'), get_query_var('monthnum')) )
				$redirect['query'] = remove_query_arg(array('year', 'monthnum'), $redirect['query']);
		} elseif ( is_year() && !empty($_GET['year']) ) {
			if ( $redirect_url = get_year_link(get_query_var('year')) )
				$redirect['query'] = remove_query_arg('year', $redirect['query']);
		} elseif ( is_author() && !empty($_GET['author']) && preg_match( '|^[0-9]+$|', $_GET['author'] ) ) {
			$author = get_userdata(get_query_var('author'));
			if ( ( false !== $author ) && $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE $wpdb->posts.post_author = %d AND $wpdb->posts.post_status = 'publish' LIMIT 1", $author->ID ) ) ) {
				if ( $redirect_url = get_author_posts_url($author->ID, $author->user_nicename) )
					$redirect['query'] = remove_query_arg('author', $redirect['query']);
			}
		} elseif ( is_category() || is_tag() || is_tax() ) { // Terms (Tags/categories)

			$term_count = 0;
			foreach ( $wp_query->tax_query->queried_terms as $tax_query )
				$term_count += count( $tax_query['terms'] );

			$obj = $wp_query->get_queried_object();
			if ( $term_count <= 1 && !empty($obj->term_id) && ( $tax_url = get_term_link((int)$obj->term_id, $obj->taxonomy) ) && !is_wp_error($tax_url) ) {
				if ( !empty($redirect['query']) ) {
					// Strip taxonomy query vars off the url.
					$qv_remove = array( 'term', 'taxonomy');
					if ( is_category() ) {
						$qv_remove[] = 'category_name';
						$qv_remove[] = 'cat';
					} elseif ( is_tag() ) {
						$qv_remove[] = 'tag';
						$qv_remove[] = 'tag_id';
					} else { // Custom taxonomies will have a custom query var, remove those too:
						$tax_obj = get_taxonomy( $obj->taxonomy );
						if ( false !== $tax_obj->query_var )
							$qv_remove[] = $tax_obj->query_var;
					}

					$rewrite_vars = array_diff( array_keys($wp_query->query), array_keys($_GET) );

					if ( !array_diff($rewrite_vars, array_keys($_GET))  ) { // Check to see if all the Query vars are coming from the rewrite, none are set via $_GET
						$redirect['query'] = remove_query_arg($qv_remove, $redirect['query']); //Remove all of the per-tax qv's

						// Create the destination url for this taxonomy
						$tax_url = parse_url($tax_url);
						if ( ! empty($tax_url['query']) ) { // Taxonomy accessible via ?taxonomy=..&term=.. or any custom qv..
							parse_str($tax_url['query'], $query_vars);
							$redirect['query'] = add_query_arg($query_vars, $redirect['query']);
						} else { // Taxonomy is accessible via a "pretty-URL"
							$redirect['path'] = $tax_url['path'];
						}

					} else { // Some query vars are set via $_GET. Unset those from $_GET that exist via the rewrite
						foreach ( $qv_remove as $_qv ) {
							if ( isset($rewrite_vars[$_qv]) )
								$redirect['query'] = remove_query_arg($_qv, $redirect['query']);
						}
					}
				}

			}
		} elseif ( is_single() && strpos($wp_rewrite->permalink_structure, '%category%') !== false && $cat = get_query_var( 'category_name' ) ) {
			$category = get_category_by_path( $cat );
			$post_terms = wp_get_object_terms($wp_query->get_queried_object_id(), 'category', array('fields' => 'tt_ids'));
			if ( (!$category || is_wp_error($category)) || ( !is_wp_error($post_terms) && !empty($post_terms) && !in_array($category->term_taxonomy_id, $post_terms) ) )
				$redirect_url = get_permalink($wp_query->get_queried_object_id());
		}

		// Post Paging
		if ( is_singular() && ! is_front_page() && get_query_var('page') ) {
			if ( !$redirect_url )
				$redirect_url = get_permalink( get_queried_object_id() );
			$redirect_url = trailingslashit( $redirect_url ) . user_trailingslashit( get_query_var( 'page' ), 'single_paged' );
			$redirect['query'] = remove_query_arg( 'page', $redirect['query'] );
		}

		// paging and feeds
		if ( get_query_var('paged') || is_feed() || get_query_var('cpage') ) {
			while ( preg_match( "#/$wp_rewrite->pagination_base/?[0-9]+?(/+)?$#", $redirect['path'] ) || preg_match( '#/(comments/?)?(feed|rss|rdf|atom|rss2)(/+)?$#', $redirect['path'] ) || preg_match( "#/{$wp_rewrite->comments_pagination_base}-[0-9]+(/+)?$#", $redirect['path'] ) ) {
				// Strip off paging and feed
				$redirect['path'] = preg_replace("#/$wp_rewrite->pagination_base/?[0-9]+?(/+)?$#", '/', $redirect['path']); // strip off any existing paging
				$redirect['path'] = preg_replace('#/(comments/?)?(feed|rss2?|rdf|atom)(/+|$)#', '/', $redirect['path']); // strip off feed endings
				$redirect['path'] = preg_replace("#/{$wp_rewrite->comments_pagination_base}-[0-9]+?(/+)?$#", '/', $redirect['path']); // strip off any existing comment paging
			}

			$addl_path = '';
			if ( is_feed() && in_array( get_query_var('feed'), $wp_rewrite->feeds ) ) {
				$addl_path = !empty( $addl_path ) ? trailingslashit($addl_path) : '';
				if ( !is_singular() && get_query_var( 'withcomments' ) )
					$addl_path .= 'comments/';
				if ( ( 'rss' == get_default_feed() && 'feed' == get_query_var('feed') ) || 'rss' == get_query_var('feed') )
					$addl_path .= user_trailingslashit( 'feed/' . ( ( get_default_feed() == 'rss2' ) ? '' : 'rss2' ), 'feed' );
				else
					$addl_path .= user_trailingslashit( 'feed/' . ( ( get_default_feed() ==  get_query_var('feed') || 'feed' == get_query_var('feed') ) ? '' : get_query_var('feed') ), 'feed' );
				$redirect['query'] = remove_query_arg( 'feed', $redirect['query'] );
			} elseif ( is_feed() && 'old' == get_query_var('feed') ) {
				$old_feed_files = array(
					'wp-atom.php'         => 'atom',
					'wp-commentsrss2.php' => 'comments_rss2',
					'wp-feed.php'         => get_default_feed(),
					'wp-rdf.php'          => 'rdf',
					'wp-rss.php'          => 'rss2',
					'wp-rss2.php'         => 'rss2',
				);
				if ( isset( $old_feed_files[ basename( $redirect['path'] ) ] ) ) {
					$redirect_url = get_feed_link( $old_feed_files[ basename( $redirect['path'] ) ] );
					wp_redirect( $redirect_url, 301 );
					die();
				}
			}

			if ( get_query_var('paged') > 0 ) {
				$paged = get_query_var('paged');
				$redirect['query'] = remove_query_arg( 'paged', $redirect['query'] );
				if ( !is_feed() ) {
					if ( $paged > 1 && !is_single() ) {
						$addl_path = ( !empty( $addl_path ) ? trailingslashit($addl_path) : '' ) . user_trailingslashit("$wp_rewrite->pagination_base/$paged", 'paged');
					} elseif ( !is_single() ) {
						$addl_path = !empty( $addl_path ) ? trailingslashit($addl_path) : '';
					}
				} elseif ( $paged > 1 ) {
					$redirect['query'] = add_query_arg( 'paged', $paged, $redirect['query'] );
				}
			}

			if ( get_option('page_comments') && ( ( 'newest' == get_option('default_comments_page') && get_query_var('cpage') > 0 ) || ( 'newest' != get_option('default_comments_page') && get_query_var('cpage') > 1 ) ) ) {
				$addl_path = ( !empty( $addl_path ) ? trailingslashit($addl_path) : '' ) . user_trailingslashit( $wp_rewrite->comments_pagination_base . '-' . get_query_var('cpage'), 'commentpaged' );
				$redirect['query'] = remove_query_arg( 'cpage', $redirect['query'] );
			}

			$redirect['path'] = user_trailingslashit( preg_replace('|/' . preg_quote( $wp_rewrite->index, '|' ) . '/?$|', '/', $redirect['path']) ); // strip off trailing /index.php/
			if ( !empty( $addl_path ) && $wp_rewrite->using_index_permalinks() && strpos($redirect['path'], '/' . $wp_rewrite->index . '/') === false )
				$redirect['path'] = trailingslashit($redirect['path']) . $wp_rewrite->index . '/';
			if ( !empty( $addl_path ) )
				$redirect['path'] = trailingslashit($redirect['path']) . $addl_path;
			$redirect_url = $redirect['scheme'] . '://' . $redirect['host'] . $redirect['path'];
		}

		if ( 'wp-register.php' == basename( $redirect['path'] ) ) {
			if ( is_multisite() ) {
				/** This filter is documented in wp-login.php */
				$redirect_url = apply_filters( 'wp_signup_location', network_site_url( 'wp-signup.php' ) );
			} else {
				$redirect_url = site_url( 'wp-login.php?action=register' );
			}

			wp_redirect( $redirect_url, 301 );
			die();
		}
	}

	// tack on any additional query vars
	$redirect['query'] = preg_replace( '#^\??&*?#', '', $redirect['query'] );
	if ( $redirect_url && !empty($redirect['query']) ) {
		parse_str( $redirect['query'], $_parsed_query );
		$redirect = @parse_url($redirect_url);

		if ( ! empty( $_parsed_query['name'] ) && ! empty( $redirect['query'] ) ) {
			parse_str( $redirect['query'], $_parsed_redirect_query );

			if ( empty( $_parsed_redirect_query['name'] ) )
				unset( $_parsed_query['name'] );
		}

		$_parsed_query = rawurlencode_deep( $_parsed_query );
		$redirect_url = add_query_arg( $_parsed_query, $redirect_url );
	}

	if ( $redirect_url )
		$redirect = @parse_url($redirect_url);

	// www.example.com vs example.com
	$user_home = @parse_url(home_url());
	if ( !empty($user_home['host']) )
		$redirect['host'] = $user_home['host'];
	if ( empty($user_home['path']) )
		$user_home['path'] = '/';

	// Handle ports
	if ( !empty($user_home['port']) )
		$redirect['port'] = $user_home['port'];
	else
		unset($redirect['port']);

	// trailing /index.php
	$redirect['path'] = preg_replace('|/' . preg_quote( $wp_rewrite->index, '|' ) . '/*?$|', '/', $redirect['path']);

	// Remove trailing spaces from the path
	$redirect['path'] = preg_replace( '#(%20| )+$#', '', $redirect['path'] );

	if ( !empty( $redirect['query'] ) ) {
		// Remove trailing spaces from certain terminating query string args
		$redirect['query'] = preg_replace( '#((p|page_id|cat|tag)=[^&]*?)(%20| )+$#', '$1', $redirect['query'] );

		// Clean up empty query strings
		$redirect['query'] = trim(preg_replace( '#(^|&)(p|page_id|cat|tag)=?(&|$)#', '&', $redirect['query']), '&');

		// Redirect obsolete feeds
		$redirect['query'] = preg_replace( '#(^|&)feed=rss(&|$)#', '$1feed=rss2$2', $redirect['query'] );

		// Remove redundant leading ampersands
		$redirect['query'] = preg_replace( '#^\??&*?#', '', $redirect['query'] );
	}

	// strip /index.php/ when we're not using PATHINFO permalinks
	if ( !$wp_rewrite->using_index_permalinks() )
		$redirect['path'] = str_replace( '/' . $wp_rewrite->index . '/', '/', $redirect['path'] );

	// trailing slashes
	if ( is_object($wp_rewrite) && $wp_rewrite->using_permalinks() && !is_404() && (!is_front_page() || ( is_front_page() && (get_query_var('paged') > 1) ) ) ) {
		$user_ts_type = '';
		if ( get_query_var('paged') > 0 ) {
			$user_ts_type = 'paged';
		} else {
			foreach ( array('single', 'category', 'page', 'day', 'month', 'year', 'home') as $type ) {
				$func = 'is_' . $type;
				if ( call_user_func($func) ) {
					$user_ts_type = $type;
					break;
				}
			}
		}
		$redirect['path'] = user_trailingslashit($redirect['path'], $user_ts_type);
	} elseif ( is_front_page() ) {
		$redirect['path'] = trailingslashit($redirect['path']);
	}

	// Strip multiple slashes out of the URL
	if ( strpos($redirect['path'], '//') > -1 )
		$redirect['path'] = preg_replace('|/+|', '/', $redirect['path']);

	// Always trailing slash the Front Page URL
	if ( trailingslashit( $redirect['path'] ) == trailingslashit( $user_home['path'] ) )
		$redirect['path'] = trailingslashit($redirect['path']);

	// Ignore differences in host capitalization, as this can lead to infinite redirects
	// Only redirect no-www <=> yes-www
	if ( strtolower($original['host']) == strtolower($redirect['host']) ||
		( strtolower($original['host']) != 'www.' . strtolower($redirect['host']) && 'www.' . strtolower($original['host']) != strtolower($redirect['host']) ) )
		$redirect['host'] = $original['host'];

	$compare_original = array( $original['host'], $original['path'] );

	if ( !empty( $original['port'] ) )
		$compare_original[] = $original['port'];

	if ( !empty( $original['query'] ) )
		$compare_original[] = $original['query'];

	$compare_redirect = array( $redirect['host'], $redirect['path'] );

	if ( !empty( $redirect['port'] ) )
		$compare_redirect[] = $redirect['port'];

	if ( !empty( $redirect['query'] ) )
		$compare_redirect[] = $redirect['query'];

	if ( $compare_original !== $compare_redirect ) {
		$redirect_url = $redirect['scheme'] . '://' . $redirect['host'];
		if ( !empty($redirect['port']) )
			$redirect_url .= ':' . $redirect['port'];
		$redirect_url .= $redirect['path'];
		if ( !empty($redirect['query']) )
			$redirect_url .= '?' . $redirect['query'];
	}

	if ( ! $redirect_url || $redirect_url == $requested_url ) {
		return;
	}

	// Hex encoded octets are case-insensitive.
	if ( false !== strpos($requested_url, '%') ) {
		if ( !function_exists('lowercase_octets') ) {
			function lowercase_octets($matches) {
				return strtolower( $matches[0] );
			}
		}
		$requested_url = preg_replace_callback('|%[a-fA-F0-9][a-fA-F0-9]|', 'lowercase_octets', $requested_url);
	}

	/**
	 * Filter the canonical redirect URL.
	 *
	 * Returning false to this filter will cancel the redirect.
	 *
	 * @since 2.3.0
	 *
	 * @param string $redirect_url  The redirect URL.
	 * @param string $requested_url The requested URL.
	 */
	$redirect_url = apply_filters( 'redirect_canonical', $redirect_url, $requested_url );

	// yes, again -- in case the filter aborted the request
	if ( ! $redirect_url || $redirect_url == $requested_url ) {
		return;
	}

	if ( $do_redirect ) {
		// protect against chained redirects
		if ( !redirect_canonical($redirect_url, false) ) {
			wp_redirect($redirect_url, 301);
			exit();
		} else {
			// Debug
			// die("1: $redirect_url<br />2: " . redirect_canonical( $redirect_url, false ) );
			return;
		}
	} else {
		return $redirect_url;
	}
}

/**
 * Removes arguments from a query string if they are not present in a URL
 * DO NOT use this in plugin code.
 *
 * @since 3.4.0
 * @access private
 *
 * @param string $query_string
 * @param array $args_to_check
 * @param string $url
 * @return string The altered query string
 */
function _remove_qs_args_if_not_in_url( $query_string, Array $args_to_check, $url ) {
	$parsed_url = @parse_url( $url );
	if ( ! empty( $parsed_url['query'] ) ) {
		parse_str( $parsed_url['query'], $parsed_query );
		foreach ( $args_to_check as $qv ) {
			if ( !isset( $parsed_query[$qv] ) )
				$query_string = remove_query_arg( $qv, $query_string );
		}
	} else {
		$query_string = remove_query_arg( $args_to_check, $query_string );
	}
	return $query_string;
}

/**
 * Attempts to guess the correct URL based on query vars
 *
 * @since 2.3.0
 *
 * @global wpdb $wpdb WordPress database abstraction object.
 * @global WP_Rewrite $wp_rewrite
 *
 * @return false|string The correct URL if one is found. False on failure.
 */
function redirect_guess_404_permalink() {
	global $wpdb, $wp_rewrite;

	if ( get_query_var('name') ) {
		$where = $wpdb->prepare("post_name LIKE %s", $wpdb->esc_like( get_query_var('name') ) . '%');

		// if any of post_type, year, monthnum, or day are set, use them to refine the query
		if ( get_query_var('post_type') )
			$where .= $wpdb->prepare(" AND post_type = %s", get_query_var('post_type'));
		else
			$where .= " AND post_type IN ('" . implode( "', '", get_post_types( array( 'public' => true ) ) ) . "')";

		if ( get_query_var('year') )
			$where .= $wpdb->prepare(" AND YEAR(post_date) = %d", get_query_var('year'));
		if ( get_query_var('monthnum') )
			$where .= $wpdb->prepare(" AND MONTH(post_date) = %d", get_query_var('monthnum'));
		if ( get_query_var('day') )
			$where .= $wpdb->prepare(" AND DAYOFMONTH(post_date) = %d", get_query_var('day'));

		$post_id = $wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE $where AND post_status = 'publish'");
		if ( ! $post_id )
			return false;
		if ( get_query_var( 'feed' ) )
			return get_post_comments_feed_link( $post_id, get_query_var( 'feed' ) );
		elseif ( get_query_var( 'page' ) )
			return trailingslashit( get_permalink( $post_id ) ) . user_trailingslashit( get_query_var( 'page' ), 'single_paged' );
		else
			return get_permalink( $post_id );
	}

	return false;
}

/**
 *
 * @global WP_Rewrite $wp_rewrite
 */
function wp_redirect_admin_locations() {
	global $wp_rewrite;
	if ( ! ( is_404() && $wp_rewrite->using_permalinks() ) )
		return;

	$admins = array(
		home_url( 'wp-admin', 'relative' ),
		home_url( 'dashboard', 'relative' ),
		home_url( 'admin', 'relative' ),
		site_url( 'dashboard', 'relative' ),
		site_url( 'admin', 'relative' ),
	);
	if ( in_array( untrailingslashit( $_SERVER['REQUEST_URI'] ), $admins ) ) {
		wp_redirect( admin_url() );
		exit;
	}

	$logins = array(
		home_url( 'wp-login.php', 'relative' ),
		home_url( 'login', 'relative' ),
		site_url( 'login', 'relative' ),
	);
	if ( in_array( untrailingslashit( $_SERVER['REQUEST_URI'] ), $logins ) ) {
		wp_redirect( site_url( 'wp-login.php', 'login' ) );
		exit;
	}
}
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              <?php
/**
 * Object Cache API
 *
 * @link https://codex.wordpress.org/Function_Reference/WP_Cache
 *
 * @package WordPress
 * @subpackage Cache
 */

/**
 * Adds data to the cache, if the cache key doesn't already exist.
 *
 * @since 2.0.0
 *
 * @global WP_Object_Cache $wp_object_cache
 *
 * @param int|string $key The cache key to use for retrieval later
 * @param mixed $data The data to add to the cache store
 * @param string $group The group to add the cache to
 * @param int $expire When the cache data should be expired
 * @return bool False if cache key and group already exist, true on success
 */
function wp_cache_add( $key, $data, $group = '', $expire = 0 ) {
	global $wp_object_cache;

	return $wp_object_cache->add( $key, $data, $group, (int) $expire );
}

/**
 * Closes the cache.
 *
 * This function has ceased to do anything since WordPress 2.5. The
 * functionality was removed along with the rest of the persistent cache. This
 * does not mean that plugins can't implement this function when they need to
 * make sure that the cache is cleaned up after WordPress no longer needs it.
 *
 * @since 2.0.0
 *
 * @return true Always returns True
 */
function wp_cache_close() {
	return true;
}

/**
 * Decrement numeric cache item's value
 *
 * @since 3.3.0
 *
 * @global WP_Object_Cache $wp_object_cache
 *
 * @param int|string $key The cache key to increment
 * @param int $offset The amount by which to decrement the item's value. Default is 1.
 * @param string $group The group the key is in.
 * @return false|int False on failure, the item's new value on success.
 */
function wp_cache_decr( $key, $offset = 1, $group = '' ) {
	global $wp_object_cache;

	return $wp_object_cache->decr( $key, $offset, $group );
}

/**
 * Removes the cache contents matching key and group.
 *
 * @since 2.0.0
 *
 * @global WP_Object_Cache $wp_object_cache
 *
 * @param int|string $key What the contents in the cache are called
 * @param string $group Where the cache contents are grouped
 * @return bool True on successful removal, false on failure
 */
function wp_cache_delete($key, $group = '') {
	global $wp_object_cache;

	return $wp_object_cache->delete($key, $group);
}

/**
 * Removes all cache items.
 *
 * @since 2.0.0
 *
 * @global WP_Object_Cache $wp_object_cache
 *
 * @return bool False on failure, true on success
 */
function wp_cache_flush() {
	global $wp_object_cache;

	return $wp_object_cache->flush();
}

/**
 * Retrieves the cache contents from the cache by key and group.
 *
 * @since 2.0.0
 *
 * @global WP_Object_Cache $wp_object_cache
 *
 * @param int|string $key What the contents in the cache are called
 * @param string $group Where the cache contents are grouped
 * @param bool $force Whether to force an update of the local cache from the persistent cache (default is false)
 * @param bool &$found Whether key was found in the cache. Disambiguates a return of false, a storable value.
 * @return bool|mixed False on failure to retrieve contents or the cache
 *		              contents on success
 */
function wp_cache_get( $key, $group = '', $force = false, &$found = null ) {
	global $wp_object_cache;

	return $wp_object_cache->get( $key, $group, $force, $found );
}

/**
 * Increment numeric cache item's value
 *
 * @since 3.3.0
 *
 * @global WP_Object_Cache $wp_object_cache
 *
 * @param int|string $key The cache key to increment
 * @param int $offset The amount by which to increment the item's value. Default is 1.
 * @param string $group The group the key is in.
 * @return false|int False on failure, the item's new value on success.
 */
function wp_cache_incr( $key, $offset = 1, $group = '' ) {
	global $wp_object_cache;

	return $wp_object_cache->incr( $key, $offset, $group );
}

/**
 * Sets up Object Cache Global and assigns it.
 *
 * @since 2.0.0
 *
 * @global WP_Object_Cache $wp_object_cache
 */
function wp_cache_init() {
	$GLOBALS['wp_object_cache'] = new WP_Object_Cache();
}

/**
 * Replaces the contents of the cache with new data.
 *
 * @since 2.0.0
 *
 * @global WP_Object_Cache $wp_object_cache
 *
 * @param int|string $key What to call the contents in the cache
 * @param mixed $data The contents to store in the cache
 * @param string $group Where to group the cache contents
 * @param int $expire When to expire the cache contents
 * @return bool False if not exists, true if contents were replaced
 */
function wp_cache_replace( $key, $data, $group = '', $expire = 0 ) {
	global $wp_object_cache;

	return $wp_object_cache->replace( $key, $data, $group, (int) $expire );
}

/**
 * Saves the data to the cache.
 *
 * @since 2.0.0
 *
 * @global WP_Object_Cache $wp_object_cache
 *
 * @param int|string $key What to call the contents in the cache
 * @param mixed $data The contents to store in the cache
 * @param string $group Where to group the cache contents
 * @param int $expire When to expire the cache contents
 * @return bool False on failure, true on success
 */
function wp_cache_set( $key, $data, $group = '', $expire = 0 ) {
	global $wp_object_cache;

	return $wp_object_cache->set( $key, $data, $group, (int) $expire );
}

/**
 * Switch the interal blog id.
 *
 * This changes the blog id used to create keys in blog specific groups.
 *
 * @since 3.5.0
 *
 * @global WP_Object_Cache $wp_object_cache
 *
 * @param int $blog_id Blog ID
 */
function wp_cache_switch_to_blog( $blog_id ) {
	global $wp_object_cache;

	$wp_object_cache->switch_to_blog( $blog_id );
}

/**
 * Adds a group or set of groups to the list of global groups.
 *
 * @since 2.6.0
 *
 * @global WP_Object_Cache $wp_object_cache
 *
 * @param string|array $groups A group or an array of groups to add
 */
function wp_cache_add_global_groups( $groups ) {
	global $wp_object_cache;

	$wp_object_cache->add_global_groups( $groups );
}

/**
 * Adds a group or set of groups to the list of non-persistent groups.
 *
 * @since 2.6.0
 *
 * @param string|array $groups A group or an array of groups to add
 */
function wp_cache_add_non_persistent_groups( $groups ) {
	// Default cache doesn't persist so nothing to do here.
}

/**
 * Reset internal cache keys and structures. If the cache backend uses global
 * blog or site IDs as part of its cache keys, this function instructs the
 * backend to reset those keys and perform any cleanup since blog or site IDs
 * have changed since cache init.
 *
 * This function is deprecated. Use wp_cache_switch_to_blog() instead of this
 * function when preparing the cache for a blog switch. For clearing the cache
 * during unit tests, consider using wp_cache_init(). wp_cache_init() is not
 * recommended outside of unit tests as the performance penality for using it is
 * high.
 *
 * @since 2.6.0
 * @deprecated 3.5.0
 *
 * @global WP_Object_Cache $wp_object_cache
 */
function wp_cache_reset() {
	_deprecated_function( __FUNCTION__, '3.5' );

	global $wp_object_cache;

	$wp_object_cache->reset();
}

/**
 * WordPress Object Cache
 *
 * The WordPress Object Cache is used to save on trips to the database. The
 * Object Cache stores all of the cache data to memory and makes the cache
 * contents available by using a key, which is used to name and later retrieve
 * the cache contents.
 *
 * The Object Cache can be replaced by other caching mechanisms by placing files
 * in the wp-content folder which is looked at in wp-settings. If that file
 * exists, then this file will not be included.
 *
 * @package WordPress
 * @subpackage Cache
 * @since 2.0.0
 */
class WP_Object_Cache {

	/**
	 * Holds the cached objects
	 *
	 * @var array
	 * @access private
	 * @since 2.0.0
	 */
	private $cache = array();

	/**
	 * The amount of times the cache data was already stored in the cache.
	 *
	 * @since 2.5.0
	 * @access private
	 * @var int
	 */
	private $cache_hits = 0;

	/**
	 * Amount of times the cache did not have the request in cache
	 *
	 * @var int
	 * @access public
	 * @since 2.0.0
	 */
	public $cache_misses = 0;

	/**
	 * List of global groups
	 *
	 * @var array
	 * @access protected
	 * @since 3.0.0
	 */
	protected $global_groups = array();

	/**
	 * The blog prefix to prepend to keys in non-global groups.
	 *
	 * @var int
	 * @access private
	 * @since 3.5.0
	 */
	private $blog_prefix;

	/**
	 * Holds the value of `is_multisite()`
	 *
	 * @var bool
	 * @access private
	 * @since 3.5.0
	 */
	private $multisite;

	/**
	 * Make private properties readable for backwards compatibility.
	 *
	 * @since 4.0.0
	 * @access public
	 *
	 * @param string $name Property to get.
	 * @return mixed Property.
	 */
	public function __get( $name ) {
		return $this->$name;
	}

	/**
	 * Make private properties settable for backwards compatibility.
	 *
	 * @since 4.0.0
	 * @access public
	 *
	 * @param string $name  Property to set.
	 * @param mixed  $value Property value.
	 * @return mixed Newly-set property.
	 */
	public function __set( $name, $value ) {
		return $this->$name = $value;
	}

	/**
	 * Make private properties checkable for backwards compatibility.
	 *
	 * @since 4.0.0
	 * @access public
	 *
	 * @param string $name Property to check if set.
	 * @return bool Whether the property is set.
	 */
	public function __isset( $name ) {
		return isset( $this->$name );
	}

	/**
	 * Make private properties un-settable for backwards compatibility.
	 *
	 * @since 4.0.0
	 * @access public
	 *
	 * @param string $name Property to unset.
	 */
	public function __unset( $name ) {
		unset( $this->$name );
	}

	/**
	 * Adds data to the cache if it doesn't already exist.
	 *
	 * @uses WP_Object_Cache::_exists Checks to see if the cache already has data.
	 * @uses WP_Object_Cache::set Sets the data after the checking the cache
	 *		contents existence.
	 *
	 * @since 2.0.0
	 *
	 * @param int|string $key What to call the contents in the cache
	 * @param mixed $data The contents to store in the cache
	 * @param string $group Where to group the cache contents
	 * @param int $expire When to expire the cache contents
	 * @return bool False if cache key and group already exist, true on success
	 */
	public function add( $key, $data, $group = 'default', $expire = 0 ) {
		if ( wp_suspend_cache_addition() )
			return false;

		if ( empty( $group ) )
			$group = 'default';

		$id = $key;
		if ( $this->multisite && ! isset( $this->global_groups[ $group ] ) )
			$id = $this->blog_prefix . $key;

		if ( $this->_exists( $id, $group ) )
			return false;

		return $this->set( $key, $data, $group, (int) $expire );
	}

	/**
	 * Sets the list of global groups.
	 *
	 * @since 3.0.0
	 *
	 * @param array $groups List of groups that are global.
	 */
	public function add_global_groups( $groups ) {
		$groups = (array) $groups;

		$groups = array_fill_keys( $groups, true );
		$this->global_groups = array_merge( $this->global_groups, $groups );
	}

	/**
	 * Decrement numeric cache item's value
	 *
	 * @since 3.3.0
	 *
	 * @param int|string $key The cache key to increment
	 * @param int $offset The amount by which to decrement the item's value. Default is 1.
	 * @param string $group The group the key is in.
	 * @return false|int False on failure, the item's new value on success.
	 */
	public function decr( $key, $offset = 1, $group = 'default' ) {
		if ( empty( $group ) )
			$group = 'default';

		if ( $this->multisite && ! isset( $this->global_groups[ $group ] ) )
			$key = $this->blog_prefix . $key;

		if ( ! $this->_exists( $key, $group ) )
			return false;

		if ( ! is_numeric( $this->cache[ $group ][ $key ] ) )
			$this->cache[ $group ][ $key ] = 0;

		$offset = (int) $offset;

		$this->cache[ $group ][ $key ] -= $offset;

		if ( $this->cache[ $group ][ $key ] < 0 )
			$this->cache[ $group ][ $key ] = 0;

		return $this->cache[ $group ][ $key ];
	}

	/**
	 * Remove the contents of the cache key in the group
	 *
	 * If the cache key does not exist in the group, then nothing will happen.
	 *
	 * @since 2.0.0
	 *
	 * @param int|string $key What the contents in the cache are called
	 * @param string $group Where the cache contents are grouped
	 * @param bool $deprecated Deprecated.
	 *
	 * @return bool False if the contents weren't deleted and true on success
	 */
	public function delete( $key, $group = 'default', $deprecated = false ) {
		if ( empty( $group ) )
			$group = 'default';

		if ( $this->multisite && ! isset( $this->global_groups[ $group ] ) )
			$key = $this->blog_prefix . $key;

		if ( ! $this->_exists( $key, $group ) )
			return false;

		unset( $this->cache[$group][$key] );
		return true;
	}

	/**
	 * Clears the object cache of all data
	 *
	 * @since 2.0.0
	 *
	 * @return true Always returns true
	 */
	public function flush() {
		$this->cache = array();

		return true;
	}

	/**
	 * Retrieves the cache contents, if it exists
	 *
	 * The contents will be first attempted to be retrieved by searching by the
	 * key in the cache group. If the cache is hit (success) then the contents
	 * are returned.
	 *
	 * On failure, the number of cache misses will be incremented.
	 *
	 * @since 2.0.0
	 *
	 * @param int|string $key What the contents in the cache are called
	 * @param string $group Where the cache contents are grouped
	 * @param string $force Whether to force a refetch rather than relying on the local cache (default is false)
	 * @return false|mixed False on failure to retrieve contents or the cache
	 *		               contents on success
	 */
	public function get( $key, $group = 'default', $force = false, &$found = null ) {
		if ( empty( $group ) )
			$group = 'default';

		if ( $this->multisite && ! isset( $this->global_groups[ $group ] ) )
			$key = $this->blog_prefix . $key;

		if ( $this->_exists( $key, $group ) ) {
			$found = true;
			$this->cache_hits += 1;
			if ( is_object($this->cache[$group][$key]) )
				return clone $this->cache[$group][$key];
			else
				return $this->cache[$group][$key];
		}

		$found = false;
		$this->cache_misses += 1;
		return false;
	}

	/**
	 * Increment numeric cache item's value
	 *
	 * @since 3.3.0
	 *
	 * @param int|string $key The cache key to increment
	 * @param int $offset The amount by which to increment the item's value. Default is 1.
	 * @param string $group The group the key is in.
	 * @return false|int False on failure, the item's new value on success.
	 */
	public function incr( $key, $offset = 1, $group = 'default' ) {
		if ( empty( $group ) )
			$group = 'default';

		if ( $this->multisite && ! isset( $this->global_groups[ $group ] ) )
			$key = $this->blog_prefix . $key;

		if ( ! $this->_exists( $key, $group ) )
			return false;

		if ( ! is_numeric( $this->cache[ $group ][ $key ] ) )
			$this->cache[ $group ][ $key ] = 0;

		$offset = (int) $offset;

		$this->cache[ $group ][ $key ] += $offset;

		if ( $this->cache[ $group ][ $key ] < 0 )
			$this->cache[ $group ][ $key ] = 0;

		return $this->cache[ $group ][ $key ];
	}

	/**
	 * Replace the contents in the cache, if contents already exist
	 *
	 * @since 2.0.0
	 * @see WP_Object_Cache::set()
	 *
	 * @param int|string $key What to call the contents in the cache
	 * @param mixed $data The contents to store in the cache
	 * @param string $group Where to group the cache contents
	 * @param int $expire When to expire the cache contents
	 * @return bool False if not exists, true if contents were replaced
	 */
	public function replace( $key, $data, $group = 'default', $expire = 0 ) {
		if ( empty( $group ) )
			$group = 'default';

		$id = $key;
		if ( $this->multisite && ! isset( $this->global_groups[ $group ] ) )
			$id = $this->blog_prefix . $key;

		if ( ! $this->_exists( $id, $group ) )
			return false;

		return $this->set( $key, $data, $group, (int) $expire );
	}

	/**
	 * Reset keys
	 *
	 * @since 3.0.0
	 * @deprecated 3.5.0
	 */
	public function reset() {
		_deprecated_function( __FUNCTION__, '3.5', 'switch_to_blog()' );

		// Clear out non-global caches since the blog ID has changed.
		foreach ( array_keys( $this->cache ) as $group ) {
			if ( ! isset( $this->global_groups[ $group ] ) )
				unset( $this->cache[ $group ] );
		}
	}

	/**
	 * Sets the data contents into the cache
	 *
	 * The cache contents is grouped by the $group parameter followed by the
	 * $key. This allows for duplicate ids in unique groups. Therefore, naming of
	 * the group should be used with care and should follow normal function
	 * naming guidelines outside of core WordPress usage.
	 *
	 * The $expire parameter is not used, because the cache will automatically
	 * expire for each time a page is accessed and PHP finishes. The method is
	 * more for cache plugins which use files.
	 *
	 * @since 2.0.0
	 *
	 * @param int|string $key What to call the contents in the cache
	 * @param mixed $data The contents to store in the cache
	 * @param string $group Where to group the cache contents
	 * @param int $expire Not Used
	 * @return true Always returns true
	 */
	public function set( $key, $data, $group = 'default', $expire = 0 ) {
		if ( empty( $group ) )
			$group = 'default';

		if ( $this->multisite && ! isset( $this->global_groups[ $group ] ) )
			$key = $this->blog_prefix . $key;

		if ( is_object( $data ) )
			$data = clone $data;

		$this->cache[$group][$key] = $data;
		return true;
	}

	/**
	 * Echoes the stats of the caching.
	 *
	 * Gives the cache hits, and cache misses. Also prints every cached group,
	 * key and the data.
	 *
	 * @since 2.0.0
	 */
	public function stats() {
		echo "<p>";
		echo "<strong>Cache Hits:</strong> {$this->cache_hits}<br />";
		echo "<strong>Cache Misses:</strong> {$this->cache_misses}<br />";
		echo "</p>";
		echo '<ul>';
		foreach ($this->cache as $group => $cache) {
			echo "<li><strong>Group:</strong> $group - ( " . number_format( strlen( serialize( $cache ) ) / 1024, 2 ) . 'k )</li>';
		}
		echo '</ul>';
	}

	/**
	 * Switch the interal blog id.
	 *
	 * This changes the blog id used to create keys in blog specific groups.
	 *
	 * @since 3.5.0
	 *
	 * @param int $blog_id Blog ID
	 */
	public function switch_to_blog( $blog_id ) {
		$blog_id = (int) $blog_id;
		$this->blog_prefix = $this->multisite ? $blog_id . ':' : '';
	}

	/**
	 * Utility function to determine whether a key exists in the cache.
	 *
	 * @since 3.4.0
	 *
	 * @access protected
	 * @param string $key
	 * @param string $group
	 * @return bool
	 */
	protected function _exists( $key, $group ) {
		return isset( $this->cache[ $group ] ) && ( isset( $this->cache[ $group ][ $key ] ) || array_key_exists( $key, $this->cache[ $group ] ) );
	}

	/**
	 * Sets up object properties; PHP 5 style constructor
	 *
	 * @since 2.0.8
	 *
     * @global int $blog_id
	 */
	public function __construct() {
		global $blog_id;

		$this->multisite = is_multisite();
		$this->blog_prefix =  $this->multisite ? $blog_id . ':' : '';


		/**
		 * @todo This should be moved to the PHP4 style constructor, PHP5
		 * already calls __destruct()
		 */
		register_shutdown_function( array( $this, '__destruct' ) );
	}

	/**
	 * Will save the object cache before object is completely destroyed.
	 *
	 * Called upon object destruction, which should be when PHP ends.
	 *
	 * @since  2.0.8
	 *
	 * @return true True value. Won't be used by PHP
	 */
	public function __destruct() {
		return true;
	}
}
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         <?php
/**
 * Link/Bookmark API
 *
 * @package WordPress
 * @subpackage Bookmark
 */

/**
 * Retrieve Bookmark data
 *
 * @since 2.1.0
 *
 * @global wpdb $wpdb WordPress database abstraction object.
 *
 * @param int|stdClass $bookmark
 * @param string $output Optional. Either OBJECT, ARRAY_N, or ARRAY_A constant
 * @param string $filter Optional, default is 'raw'.
 * @return array|object|null Type returned depends on $output value.
 */
function get_bookmark($bookmark, $output = OBJECT, $filter = 'raw') {
	global $wpdb;

	if ( empty($bookmark) ) {
		if ( isset($GLOBALS['link']) )
			$_bookmark = & $GLOBALS['link'];
		else
			$_bookmark = null;
	} elseif ( is_object($bookmark) ) {
		wp_cache_add($bookmark->link_id, $bookmark, 'bookmark');
		$_bookmark = $bookmark;
	} else {
		if ( isset($GLOBALS['link']) && ($GLOBALS['link']->link_id == $bookmark) ) {
			$_bookmark = & $GLOBALS['link'];
		} elseif ( ! $_bookmark = wp_cache_get($bookmark, 'bookmark') ) {
			$_bookmark = $wpdb->get_row($wpdb->prepare("SELECT * FROM $wpdb->links WHERE link_id = %d LIMIT 1", $bookmark));
			if ( $_bookmark ) {
				$_bookmark->link_category = array_unique( wp_get_object_terms( $_bookmark->link_id, 'link_category', array( 'fields' => 'ids' ) ) );
				wp_cache_add( $_bookmark->link_id, $_bookmark, 'bookmark' );
			}
		}
	}

	if ( ! $_bookmark )
		return $_bookmark;

	$_bookmark = sanitize_bookmark($_bookmark, $filter);

	if ( $output == OBJECT ) {
		return $_bookmark;
	} elseif ( $output == ARRAY_A ) {
		return get_object_vars($_bookmark);
	} elseif ( $output == ARRAY_N ) {
		return array_values(get_object_vars($_bookmark));
	} else {
		return $_bookmark;
	}
}

/**
 * Retrieve single bookmark data item or field.
 *
 * @since 2.3.0
 *
 * @param string $field The name of the data field to return
 * @param int $bookmark The bookmark ID to get field
 * @param string $context Optional. The context of how the field will be used.
 * @return string|WP_Error
 */
function get_bookmark_field( $field, $bookmark, $context = 'display' ) {
	$bookmark = (int) $bookmark;
	$bookmark = get_bookmark( $bookmark );

	if ( is_wp_error($bookmark) )
		return $bookmark;

	if ( !is_object($bookmark) )
		return '';

	if ( !isset($bookmark->$field) )
		return '';

	return sanitize_bookmark_field($field, $bookmark->$field, $bookmark->link_id, $context);
}

/**
 * Retrieves the list of bookmarks
 *
 * Attempts to retrieve from the cache first based on MD5 hash of arguments. If
 * that fails, then the query will be built from the arguments and executed. The
 * results will be stored to the cache.
 *
 * @since 2.1.0
 *
 * @global wpdb $wpdb WordPress database abstraction object.
 *
 * @param string|array $args {
 *     Optional. String or array of arguments to retrieve bookmarks.
 *
 *     @type string   $orderby        How to order the links by. Accepts post fields. Default 'name'.
 *     @type string   $order          Whether to order bookmarks in ascending or descending order.
 *                                    Accepts 'ASC' (ascending) or 'DESC' (descending). Default 'ASC'.
 *     @type int      $limit          Amount of bookmarks to display. Accepts 1+ or -1 for all.
 *                                    Default -1.
 *     @type string   $category       Comma-separated list of category ids to include links from.
 *                                    Default empty.
 *     @type string   $category_name  Category to retrieve links for by name. Default empty.
 *     @type int|bool $hide_invisible Whether to show or hide links marked as 'invisible'. Accepts
 *                                    1|true or 0|false. Default 1|true.
 *     @type int|bool $show_updated   Whether to display the time the bookmark was last updated.
 *                                    Accepts 1|true or 0|false. Default 0|false.
 *     @type string   $include        Comma-separated list of bookmark IDs to include. Default empty.
 *     @type string   $exclude        Comma-separated list of bookmark IDs to exclude. Default empty.
 * }
 * @return array List of bookmark row objects.
 */
function get_bookmarks( $args = '' ) {
	global $wpdb;

	$defaults = array(
		'orderby' => 'name', 'order' => 'ASC',
		'limit' => -1, 'category' => '',
		'category_name' => '', 'hide_invisible' => 1,
		'show_updated' => 0, 'include' => '',
		'exclude' => '', 'search' => ''
	);

	$r = wp_parse_args( $args, $defaults );

	$key = md5( serialize( $r ) );
	if ( $cache = wp_cache_get( 'get_bookmarks', 'bookmark' ) ) {
		if ( is_array( $cache ) && isset( $cache[ $key ] ) ) {
			$bookmarks = $cache[ $key ];
			/**
			 * Filter the returned list of bookmarks.
			 *
			 * The first time the hook is evaluated in this file, it returns the cached
			 * bookmarks list. The second evaluation returns a cached bookmarks list if the
			 * link category is passed but does not exist. The third evaluation returns
			 * the full cached results.
			 *
			 * @since 2.1.0
			 *
			 * @see get_bookmarks()
			 *
			 * @param array $bookmarks List of the cached bookmarks.
			 * @param array $r         An array of bookmark query arguments.
			 */
			return apply_filters( 'get_bookmarks', $bookmarks, $r );
		}
	}

	if ( ! is_array( $cache ) ) {
		$cache = array();
	}

	$inclusions = '';
	if ( ! empty( $r['include'] ) ) {
		$r['exclude'] = '';  //ignore exclude, category, and category_name params if using include
		$r['category'] = '';
		$r['category_name'] = '';
		$inclinks = preg_split( '/[\s,]+/', $r['include'] );
		if ( count( $inclinks ) ) {
			foreach ( $inclinks as $inclink ) {
				if ( empty( $inclusions ) ) {
					$inclusions = ' AND ( link_id = ' . intval( $inclink ) . ' ';
				} else {
					$inclusions .= ' OR link_id = ' . intval( $inclink ) . ' ';
				}
			}
		}
	}
	if (! empty( $inclusions ) ) {
		$inclusions .= ')';
	}

	$exclusions = '';
	if ( ! empty( $r['exclude'] ) ) {
		$exlinks = preg_split( '/[\s,]+/', $r['exclude'] );
		if ( count( $exlinks ) ) {
			foreach ( $exlinks as $exlink ) {
				if ( empty( $exclusions ) ) {
					$exclusions = ' AND ( link_id <> ' . intval( $exlink ) . ' ';
				} else {
					$exclusions .= ' AND link_id <> ' . intval( $exlink ) . ' ';
				}
			}
		}
	}
	if ( ! empty( $exclusions ) ) {
		$exclusions .= ')';
	}

	if ( ! empty( $r['category_name'] ) ) {
		if ( $r['category'] = get_term_by('name', $r['category_name'], 'link_category') ) {
			$r['category'] = $r['category']->term_id;
		} else {
			$cache[ $key ] = array();
			wp_cache_set( 'get_bookmarks', $cache, 'bookmark' );
			/** This filter is documented in wp-includes/bookmark.php */
			return apply_filters( 'get_bookmarks', array(), $r );
		}
	}

	$search = '';
	if ( ! empty( $r['search'] ) ) {
		$like = '%' . $wpdb->esc_like( $r['search'] ) . '%';
		$search = $wpdb->prepare(" AND ( (link_url LIKE %s) OR (link_name LIKE %s) OR (link_description LIKE %s) ) ", $like, $like, $like );
	}

	$category_query = '';
	$join = '';
	if ( ! empty( $r['category'] ) ) {
		$incategories = preg_split( '/[\s,]+/', $r['category'] );
		if ( count($incategories) ) {
			foreach ( $incategories as $incat ) {
				if ( empty( $category_query ) ) {
					$category_query = ' AND ( tt.term_id = ' . intval( $incat ) . ' ';
				} else {
					$category_query .= ' OR tt.term_id = ' . intval( $incat ) . ' ';
				}
			}
		}
	}
	if ( ! empty( $category_query ) ) {
		$category_query .= ") AND taxonomy = 'link_category'";
		$join = " INNER JOIN $wpdb->term_relationships AS tr ON ($wpdb->links.link_id = tr.object_id) INNER JOIN $wpdb->term_taxonomy as tt ON tt.term_taxonomy_id = tr.term_taxonomy_id";
	}

	if ( $r['show_updated'] ) {
		$recently_updated_test = ", IF (DATE_ADD(link_updated, INTERVAL 120 MINUTE) >= NOW(), 1,0) as recently_updated ";
	} else {
		$recently_updated_test = '';
	}

	$get_updated = ( $r['show_updated'] ) ? ', UNIX_TIMESTAMP(link_updated) AS link_updated_f ' : '';

	$orderby = strtolower( $r['orderby'] );
	$length = '';
	switch ( $orderby ) {
		case 'length':
			$length = ", CHAR_LENGTH(link_name) AS length";
			break;
		case 'rand':
			$orderby = 'rand()';
			break;
		case 'link_id':
			$orderby = "$wpdb->links.link_id";
			break;
		default:
			$orderparams = array();
			$keys = array( 'link_id', 'link_name', 'link_url', 'link_visible', 'link_rating', 'link_owner', 'link_updated', 'link_notes', 'link_description' );
			foreach ( explode( ',', $orderby ) as $ordparam ) {
				$ordparam = trim( $ordparam );

				if ( in_array( 'link_' . $ordparam, $keys ) ) {
					$orderparams[] = 'link_' . $ordparam;
				} elseif ( in_array( $ordparam, $keys ) ) {
					$orderparams[] = $ordparam;
				}
			}
			$orderby = implode( ',', $orderparams );
	}

	if ( empty( $orderby ) ) {
		$orderby = 'link_name';
	}

	$order = strtoupper( $r['order'] );
	if ( '' !== $order && ! in_array( $order, array( 'ASC', 'DESC' ) ) ) {
		$order = 'ASC';
	}

	$visible = '';
	if ( $r['hide_invisible'] ) {
		$visible = "AND link_visible = 'Y'";
	}

	$query = "SELECT * $length $recently_updated_test $get_updated FROM $wpdb->links $join WHERE 1=1 $visible $category_query";
	$query .= " $exclusions $inclusions $search";
	$query .= " ORDER BY $orderby $order";
	if ( $r['limit'] != -1 ) {
		$query .= ' LIMIT ' . $r['limit'];
	}

	$results = $wpdb->get_results( $query );

	$cache[ $key ] = $results;
	wp_cache_set( 'get_bookmarks', $cache, 'bookmark' );

	/** This filter is documented in wp-includes/bookmark.php */
	return apply_filters( 'get_bookmarks', $results, $r );
}

/**
 * Sanitizes all bookmark fields
 *
 * @since 2.3.0
 *
 * @param object|array $bookmark Bookmark row
 * @param string $context Optional, default is 'display'. How to filter the
 *		fields
 * @return object|array Same type as $bookmark but with fields sanitized.
 */
function sanitize_bookmark($bookmark, $context = 'display') {
	$fields = array('link_id', 'link_url', 'link_name', 'link_image', 'link_target', 'link_category',
		'link_description', 'link_visible', 'link_owner', 'link_rating', 'link_updated',
		'link_rel', 'link_notes', 'link_rss', );

	if ( is_object($bookmark) ) {
		$do_object = true;
		$link_id = $bookmark->link_id;
	} else {
		$do_object = false;
		$link_id = $bookmark['link_id'];
	}

	foreach ( $fields as $field ) {
		if ( $do_object ) {
			if ( isset($bookmark->$field) )
				$bookmark->$field = sanitize_bookmark_field($field, $bookmark->$field, $link_id, $context);
		} else {
			if ( isset($bookmark[$field]) )
				$bookmark[$field] = sanitize_bookmark_field($field, $bookmark[$field], $link_id, $context);
		}
	}

	return $bookmark;
}

/**
 * Sanitizes a bookmark field
 *
 * Sanitizes the bookmark fields based on what the field name is. If the field
 * has a strict value set, then it will be tested for that, else a more generic
 * filtering is applied. After the more strict filter is applied, if the
 * $context is 'raw' then the value is immediately return.
 *
 * Hooks exist for the more generic cases. With the 'edit' context, the
 * 'edit_$field' filter will be called and passed the $value and $bookmark_id
 * respectively. With the 'db' context, the 'pre_$field' filter is called and
 * passed the value. The 'display' context is the final context and has the
 * $field has the filter name and is passed the $value, $bookmark_id, and
 * $context respectively.
 *
 * @since 2.3.0
 *
 * @param string $field The bookmark field
 * @param mixed $value The bookmark field value
 * @param int $bookmark_id Bookmark ID
 * @param string $context How to filter the field value. Either 'raw', 'edit',
 *		'attribute', 'js', 'db', or 'display'
 * @return mixed The filtered value
 */
function sanitize_bookmark_field($field, $value, $bookmark_id, $context) {
	switch ( $field ) {
	case 'link_id' : // ints
	case 'link_rating' :
		$value = (int) $value;
		break;
	case 'link_category' : // array( ints )
		$value = array_map('absint', (array) $value);
		// We return here so that the categories aren't filtered.
		// The 'link_category' filter is for the name of a link category, not an array of a link's link categories
		return $value;

	case 'link_visible' : // bool stored as Y|N
		$value = preg_replace('/[^YNyn]/', '', $value);
		break;
	case 'link_target' : // "enum"
		$targets = array('_top', '_blank');
		if ( ! in_array($value, $targets) )
			$value = '';
		break;
	}

	if ( 'raw' == $context )
		return $value;

	if ( 'edit' == $context ) {
		/** This filter is documented in wp-includes/post.php */
		$value = apply_filters( "edit_$field", $value, $bookmark_id );

		if ( 'link_notes' == $field ) {
			$value = esc_html( $value ); // textarea_escaped
		} else {
			$value = esc_attr($value);
		}
	} elseif ( 'db' == $context ) {
		/** This filter is documented in wp-includes/post.php */
		$value = apply_filters( "pre_$field", $value );
	} else {
		/** This filter is documented in wp-includes/post.php */
		$value = apply_filters( $field, $value, $bookmark_id, $context );

		if ( 'attribute' == $context ) {
			$value = esc_at