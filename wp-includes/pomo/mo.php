                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                <?php
/**
 * WordPress Administration Revisions API
 *
 * @package WordPress
 * @subpackage Administration
 */

/**
 * Get the revision UI diff.
 *
 * @since 3.6.0
 *
 * @param object|int $post         The post object. Also accepts a post ID.
 * @param int        $compare_from The revision ID to compare from.
 * @param int        $compare_to   The revision ID to come to.
 *
 * @return array|bool Associative array of a post's revisioned fields and their diffs.
 *                    Or, false on failure.
 */
function wp_get_revision_ui_diff( $post, $compare_from, $compare_to ) {
	if ( ! $post = get_post( $post ) )
		return false;

	if ( $compare_from ) {
		if ( ! $compare_from = get_post( $compare_from ) )
			return false;
	} else {
		// If we're dealing with the first revision...
		$compare_from = false;
	}

	if ( ! $compare_to = get_post( $compare_to ) )
		return false;

	// If comparing revisions, make sure we're dealing with the right post parent.
	// The parent post may be a 'revision' when revisions are disabled and we're looking at autosaves.
	if ( $compare_from && $compare_from->post_parent !== $post->ID && $compare_from->ID !== $post->ID )
		return false;
	if ( $compare_to->post_parent !== $post->ID && $compare_to->ID !== $post->ID )
		return false;

	if ( $compare_from && strtotime( $compare_from->post_date_gmt ) > strtotime( $compare_to->post_date_gmt ) ) {
		$temp = $compare_from;
		$compare_from = $compare_to;
		$compare_to = $temp;
	}

	// Add default title if title field is empty
	if ( $compare_from && empty( $compare_from->post_title ) )
		$compare_from->post_title = __( '(no title)' );
	if ( empty( $compare_to->post_title ) )
		$compare_to->post_title = __( '(no title)' );

	$return = array();

	foreach ( _wp_post_revision_fields() as $field => $name ) {
		/**
		 * Contextually filter a post revision field.
		 *
		 * The dynamic portion of the hook name, `$field`, corresponds to each of the post
		 * fields of the revision object being iterated over in a foreach statement.
		 *
		 * @since 3.6.0
		 *
		 * @param string  $compare_from->$field The current revision field to compare to or from.
		 * @param string  $field                The current revision field.
		 * @param WP_Post $compare_from         The revision post object to compare to or from.
		 * @param string  null                  The context of whether the current revision is the old
		 *                                      or the new one. Values are 'to' or 'from'.
		 */
		$content_from = $compare_from ? apply_filters( "_wp_post_revision_field_$field", $compare_from->$field, $field, $compare_from, 'from' ) : '';

		/** This filter is documented in wp-admin/includes/revision.php */
		$content_to = apply_filters( "_wp_post_revision_field_$field", $compare_to->$field, $field, $compare_to, 'to' );

		$args = array(
			'show_split_view' => true
		);

		/**
		 * Filter revisions text diff options.
		 *
		 * Filter the options passed to {@see wp_text_diff()} when viewing a post revision.
		 *
		 * @since 4.1.0
		 *
		 * @param array   $args {
		 *     Associative array of options to pass to {@see wp_text_diff()}.
		 *
		 *     @type bool $show_split_view True for split view (two columns), false for
		 *                                 un-split view (single column). Default true.
		 * }
		 * @param string  $field        The current revision field.
		 * @param WP_Post $compare_from The revision post to compare from.
		 * @param WP_Post $compare_to   The revision post to compare to.
		 */
		$args = apply_filters( 'revision_text_diff_options', $args, $field, $compare_from, $compare_to );

		$diff = wp_text_diff( $content_from, $content_to, $args );

		if ( ! $diff && 'post_title' === $field ) {
			// It's a better user experience to still show the Title, even if it didn't change.
			// No, you didn't see this.
			$diff = '<table class="diff"><colgroup><col class="content diffsplit left"><col class="content diffsplit middle"><col class="content diffsplit right"></colgroup><tbody><tr>';
			$diff .= '<td>' . esc_html( $compare_from->post_title ) . '</td><td></td><td>' . esc_html( $compare_to->post_title ) . '</td>';
			$diff .= '</tr></tbody>';
			$diff .= '</table>';
		}

		if ( $diff ) {
			$return[] = array(
				'id' => $field,
				'name' => $name,
				'diff' => $diff,
			);
		}
	}

	/**
	 * Filter the fields displayed in the post revision diff UI.
	 *
	 * @since 4.1.0
	 *
	 * @param array   $return       Revision UI fields. Each item is an array of id, name and diff.
	 * @param WP_Post $compare_from The revision post to compare from.
	 * @param WP_Post $compare_to   The revision post to compare to.
	 */
	return apply_filters( 'wp_get_revision_ui_diff', $return, $compare_from, $compare_to );

}

/**
 * Prepare revisions for JavaScript.
 *
 * @since 3.6.0
 *
 * @param object|int $post                 The post object. Also accepts a post ID.
 * @param int        $selected_revision_id The selected revision ID.
 * @param int        $from                 Optional. The revision ID to compare from.
 *
 * @return array An associative array of revision data and related settings.
 */
function wp_prepare_revisions_for_js( $post, $selected_revision_id, $from = null ) {
	$post = get_post( $post );
	$authors = array();
	$now_gmt = time();

	$revisions = wp_get_post_revisions( $post->ID, array( 'order' => 'ASC', 'check_enabled' => false ) );
	// If revisions are disabled, we only want autosaves and the current post.
	if ( ! wp_revisions_enabled( $post ) ) {
		foreach ( $revisions as $revision_id => $revision ) {
			if ( ! wp_is_post_autosave( $revision ) )
				unset( $revisions[ $revision_id ] );
		}
		$revisions = array( $post->ID => $post ) + $revisions;
	}

	$show_avatars = get_option( 'show_avatars' );

	cache_users( wp_list_pluck( $revisions, 'post_author' ) );

	$can_restore = current_user_can( 'edit_post', $post->ID );
	$current_id = false;

	foreach ( $revisions as $revision ) {
		$modified = strtotime( $revision->post_modified );
		$modified_gmt = strtotime( $revision->post_modified_gmt );
		if ( $can_restore ) {
			$restore_link = str_replace( '&amp;', '&', wp_nonce_url(
				add_query_arg(
					array( 'revision' => $revision->ID,
						'action' => 'restore' ),
						admin_url( 'revision.php' )
				),
				"restore-post_{$revision->ID}"
			) );
		}

		if ( ! isset( $authors[ $revision->post_author ] ) ) {
			$authors[ $revision->post_author ] = array(
				'id' => (int) $revision->post_author,
				'avatar' => $show_avatars ? get_avatar( $revision->post_author, 32 ) : '',
				'name' => get_the_author_meta( 'display_name', $revision->post_author ),
			);
		}

		$a