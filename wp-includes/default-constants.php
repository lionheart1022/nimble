4.0 view links.
		if ( ! empty( $views ) ) {
			echo '<ul class="filter-links">';
			foreach ( $views as $class => $view ) {
				echo "<li class='$class'>$view</li>";
			}
			echo '</ul>';
		}
?>
	</div>

	<div class="search-form">
		<label for="media-search-input" class="screen-reader-text"><?php esc_html_e( 'Search Media' ); ?></label>
		<input type="search" placeholder="<?php esc_attr_e( 'Search' ) ?>" id="media-search-input" class="search" name="s" value="<?php _admin_search_query(); ?>"></div>
	</div>
	<?php
	}

	/**
	 *
	 * @return array
	 */
	public function get_columns() {
		$posts_columns = array();
		$posts_columns['cb'] = '<input type="checkbox" />';
		/* translators: column name */
		$posts_columns['title'] = _x( 'File', 'column name' );
		$posts_columns['author'] = __( 'Author' );

		$taxonomies = get_taxonomies_for_attachments( 'objects' );
		$taxonomies = wp_filter_object_list( $taxonomies, array( 'show_admin_column' => true ), 'and', 'name' );

		/**
		 * Filter the taxonomy columns for attachments in the Media list table.
		 *
		 * @since 3.5.0
		 *
		 * @param array  $taxonomies An array of registered taxonomies to show for attachments.
		 * @param string $post_type  The post type. Default 'attachment'.
		 */
		$taxonomies = apply_filters( 'manage_taxonomies_for_attachment_columns', $taxonomies, 'attachment' );
		$taxonomies = array_filter( $taxonomies, 'taxonomy_exists' );

		foreach ( $taxonomies as $taxonomy ) {
			if ( 'category' == $taxonomy )
				$column_key = 'categories';
			elseif ( 'post_tag' == $taxonomy )
				$column_key = 'tags';
			else
				$column_key = 'taxonomy-' . $taxonomy;

			$posts_columns[ $column_key ] = get_taxonomy( $taxonomy )->labels->name;
		}

		/* translators: column name */
		if ( !$this->detached ) {
			$posts_columns['parent'] = _x( 'Uploaded to', 'column name' );
			if ( post_type_supports( 'attachment', 'comments' ) )
				$posts_columns['comments'] = '<span class="vers comment-grey-bubble" title="' . esc_attr__( 'Comments' ) . '"><span class="screen-reader-text">' . __( 'Comments' ) . '</span></span>';
		}
		/* translators: column name */
		$posts_columns['date'] = _x( 'Date', 'column name' );
		/**
		 * Filter the Media list table columns.
		 *
		 * @since 2.5.0
		 *
		 * @param array $posts_columns An array of columns displayed in the Media list table.
		 * @param bool  $detached      Whether the list table contains media not attached
		 *                             to any posts. Default true.
		 */
		return apply_filters( 'manage_media_columns', $posts_columns, $this->detached );
	}

	/**
	 *
	 * @return array
	 */
	protected function get_sortable_columns() {
		return array(
			'title'    => 'title',
			'author'   => 'author',
			'parent'   => 'parent',
			'comments' => 'comment_count',
			'date'     => array( 'date', true ),
		);
	}

	/**
	 * Handles the checkbox column output.
	 *
	 * @since 4.3.0
	 * @access public
	 *
	 * @param WP_Post $post The current WP_Post object.
	 */
	public function column_cb( $post ) {
		if ( current_user_can( 'edit_post', $post->ID ) ) { ?>
			<label class="screen-reader-text" for="cb-select-<?php echo $post->ID; ?>"><?php
				echo sprintf( __( 'Select %s' ), _draft_or_post_title() );
			?></label>
			<input type="checkbox" name="media[]" id="cb-select-<?php echo $post->ID; ?>" value="<?php echo $post->ID; ?>" />
		<?php }
	}

	/**
	 * Handles the title column output.
	 *
	 * @since 4.3.0
	 * @access public
	 *
	 * @param WP_Post $post The current WP_Post object.
	 */
	public function column_title( $post ) {
		list( $mime ) = explode( '/', $post->post_mime_type );

		$title = _draft_or_post_title();
		$thumb = wp_get_attachment_image( $post->ID, array( 60, 60 ), true, array( 'alt' => '' ) );
		$link_start = $link_end = '';

		if ( current_user_can( 'edit_post', $post->ID ) && ! $this->is_trash ) {
			$link_start = '<a href="' . get_edit_post_link( $post->ID ) . '">';
			$link_end = '</a>';
		}

		$class = $thumb ? ' class="has-media-icon"' : '';

		?>
		<strong<?php echo $class; ?>>
			<?php echo $link_start; ?>
				<?php if ( $thumb ) : ?>
				<span class="media-icon <?php echo sanitize_html_class( $mime . '-icon' ); ?>"><?php echo $thumb; ?></span>
				<?php endif; ?>

				<span aria-hidden="true"><?php echo $title; ?></span>
				<span class="screen-reader-text"><?php printf( __( 'Edit &#8220;%s&#8221;' ), $title ); ?></span>
			<?php echo $link_end; ?>
			<?php _media_states( $post ); ?>
		</strong>
		<p class="filename"><span class="screen-reader-text"><?php _e( 'File name:' ); ?> </span><?php echo wp_basename( $post->guid ); ?></p>
		<?php
	}

	/**
	 * Handles the author column output.
	 *
	 * @since 4.3.0
	 * @access public
	 *
	 * @param WP_Post $post The current WP_Post object.
	 */
	public function column_author( $post ) {
		printf( '<a href="%s">%s</a>',
			esc_url( add_query_arg( array( 'author' => get_the_author_meta('ID') ), 'upload.php' ) ),
			get_the_author()
		);
	}

	/**
	 * Handles the description column output.
	 *
	 * @since 4.3.0
	 * @access public
	 *
	 * @param WP_Post $post The current WP_Post object.
	 */
	public function column_desc( $post ) {
		echo has_excerpt() ? $post->post_excerpt : '';
	}

	/**
	 * Handles the date column output.
	 *
	 * @since 4.3.0
	 * @access public
	 *
	 * @param WP_Post $post The current WP_Post object.
	 */
	public function column_date( $post ) {
		if ( '0000-00-00 00:00:00' == $post->post_date ) {
			$h_time = __( 'Unpublished' );
		} else {
			$m_time = $post->post_date;
			$time = get_post_time( 'G', true, $post, false );
			if ( ( abs( $t_diff = time() - $time ) ) < DAY_IN_SECONDS ) {
				if ( $t_diff < 0 ) {
					$h_time = sprintf( __( '%s from now' ), human_time_diff( $time ) );
				} else {
					$h_time = sprintf( __( '%s ago' ), human_time_diff( $time ) );
				}
			} else {
				$h_time = mysql2date( __( 'Y/m/d' ), $m_time );
			}
		}

		echo $h_time;
	}

	/**
	 * Handles the parent column output.
	 *
	 * @since 4.3.0
	 * @access public
	 *
	 * @param WP_Post $post The current WP_Post object.
	 */
	public function column_parent( $post ) {
		$user_can_edit = current_user_can( 'edit_post', $post->ID );

		if ( $post->post_parent > 0 ) {
			$parent = get_post( $post->post_parent );
		} else {
			$parent = false;
		}

		if ( $parent ) {
			$title = _draft_or_post_title( $post->post_parent );
			$parent_type = get_post_type_object( $parent->post_type );
?>
			<strong>
			<?php if ( $parent_type && $parent_type->show_ui && current_user_can( 'edit_post', $post->post_parent ) ) { ?>
				<a href="<?php echo get_edit_post_link( $post->post_parent ); ?>">
					<?php echo $title ?></a><?php
			} else {
				echo $title;
			} ?></strong>,
			<?php echo get_the_time( __( 'Y/m/d' ) ); ?><br />
			<?php
			if ( $user_can_edit ):
				$detach_url = add_query_arg( array(
					'parent_post_id' => $post->post_parent,
					'media[]' => $post->ID,
					'_wpnonce' => wp_create_nonce( 'bulk-' . $this->_args['plural'] )
				), 'upload.php' ); ?>
			<a class="hide-if-no-js detach-from-parent" href="<?php echo $detach_url ?>"><?php _e( 'Detach' ); ?></a>
			<?php endif;
		} else {
			_e( '(Unattached)' ); ?><br />
			<?php if ( $user_can_edit ) { ?>
				<a class="hide-if-no-js"
					onclick="findPosts.open( 'media[]','<?php echo $post->ID ?>' ); return false;"
					href="#the-list">
					<?php _e( 'Attach' ); ?></a>
			<?php }
		}
	}

	/**
	 * Handles the comments column output.
	 *
	 * @since 4.3.0
	 * @access public
	 *
	 * @param WP_Post $post The current WP_Post object.
	 */
	public function column_comments( $post ) {
		echo '<div class="post-com-count-wrapper">';

		$pending_comments = get_pending_comments_num( $post->ID );
		$this->comments_bubble( $post->ID, $pending_comments );

		echo '</div>';
	}

	/**
	 * Handles output for the default column.
	 *
	 * @since 4.3.0
	 * @access public
	 *
	 * @param WP_Post $post        The current WP_Post object.
	 * @param string  $column_name Current column name.
	 */
	public function column_default( $post, $column_name ) {
		if ( 'categories' == $column_name ) {
			$taxonomy = 'category';
		} elseif ( 'tags' == $column_name ) {
			$taxonomy = 'post_tag';
		} elseif ( 0 === strpos( $column_name, 'taxonomy-' ) ) {
			$taxonomy = substr( $column_name, 9 );
		} else {
			$taxonomy = false;
		}

		if ( $taxonomy ) {
			$terms = get_the_terms( $post->ID, $taxonomy );
			if ( is_array( $terms ) ) {
				$out = array();
				foreach ( $terms as $t ) {
					$posts_in_term_qv = array();
					$posts_in_term_qv['taxonomy'] = $taxonomy;
					$posts_in_term_qv['term'] = $t->slug;

					$out[] = sprintf( '<a href="%s">%s</a>',
						esc_url( add_query_arg( $posts_in_term_qv, 'upload.php' ) ),
						esc_html( sanitize_term_field( 'name', $t->name, $t->term_id, $taxonomy, 'display' ) )
					);
				}
				/* translators: used between list items, there is a space after the comma */
				echo join( __( ', ' ), $out );
			} else {
				echo '<span aria-hidden="true">&#8212;</span><span class="screen-reader-text">' . get_taxonomy( $taxonomy )->labels->no_terms . '</span>';
			}

			return;
		}

		/**
		 * Fires for each custom column in the Media list table.
		 *
		 * Custom columns are registered us