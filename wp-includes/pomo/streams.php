ool) wp_is_post_autosave( $revision );
		$current = ! $autosave && $revision->post_modified_gmt === $post->post_modified_gmt;
		if ( $current && ! empty( $current_id ) ) {
			// If multiple revisions have the same post_modified_gmt, highest ID is current.
			if ( $current_id < $revision->ID ) {
				$revisions[ $current_id ]['current'] = false;
				$current_id = $revision->ID;
			} else {
				$current = false;
			}
		} elseif ( $current ) {
			$current_id = $revision->ID;
		}

		$revisions[ $revision->ID ] = array(
			'id'         => $revision->ID,
			'title'      => get_the_title( $post->ID ),
			'author'     => $authors[ $revision->post_author ],
			'date'       => date_i18n( __( 'M j, Y @ H:i' ), $modified ),
			'dateShort'  => date_i18n( _x( 'j M @ H:i', 'revision date short format' ), $modified ),
			'timeAgo'    => sprintf( __( '%s ago' ), human_time_diff( $modified_gmt, $now_gmt ) ),
			'autosave'   => $autosave,
			'current'    => $current,
			'restoreUrl' => $can_restore ? $restore_link : false,
		);
	}

	/**
	 * If we only have one revision, the initial revision is missing; This happens
	 * when we have an autsosave and the user has clicked 'View the Autosave'
	 */
	if ( 1 === sizeof( $revisions ) ) {
		$revisions[ $post->ID ] = array(
			'id'         => $post->ID,
			'title'      => get_the_title( $post->ID ),
			'author'     => $authors[ $post->post_author ],
			'date'       => date_i18n( __( 'M j, Y @ H:i' ), strtotime( $post->post_modified ) ),
			'dateShort'  => date_i18n( _x( 'j M @ H:i', 'revision date short format' ), strtotime( $post->post_modified ) ),
			'timeAgo'    => sprintf( __( '%s ago' ), human_time_diff( strtotime( $post->post_modified_gmt ), $now_gmt ) ),
			'autosave'   => false,
			'current'    => true,
			'restoreUrl' => false,
		);
		$current_id = $post->ID;
	}

	/*
	 * If a post has been saved since the last revision (no revisioned fields
	 * were changed), we may not have a "current" revision. Mark the latest
	 * revision as "current".
	 */
	if ( empty( $current_id ) ) {
		if ( $revisions[ $revision->ID ]['autosave'] ) {
			$revision = end( $revisions );
			while ( $revision['autosave'] ) {
				$revision = prev( $revisions );
			}
			$current_id = $revision['id'];
		} else {
			$current_id = $revision->ID;
		}
		$revisions[ $current_id ]['current'] = true;
	}

	// Now, grab the initial diff.
	$compare_two_mode = is_numeric( $from );
	if ( ! $compare_two_mode ) {
		$found = array_search( $selected_revision_id, array_keys( $revisions ) );
		if ( $found ) {
			$from = array_keys( array_slice( $revisions, $found - 1, 1, true ) );
			$from = reset( $from );
		} else {
			$from = 0;
		}
	}

	$from = absint( $from );

	$diffs = array( array(
		'id' => $from . ':' . $selected_revision_id,
		'fields' => wp_get_revision_ui_diff( $post->ID, $from, $selected_revision_id ),
	));

	return array(
		'postId'           => $post->ID,
		'nonce'            => wp_create_nonce( 'revisions-ajax-nonce' ),
		'revisionData'     => array_values( $revisions ),
		'to'               => $selected_revision_id,
		'from'             => $from,
		'diffData'         => $diffs,
		'baseUrl'          => parse_url( admin_url( 'revision.php' ), PHP_URL_PATH ),
		'compareTwoMode'   => absint( $compare_two_mode ), // Apparently booleans are not allowed
		'revisionIds'      => array_keys( $revisions ),
	);
}

/**
 * Print JavaScript templates required for the revisions experience.
 *
 * @since 4.1.0
 *
 * @global WP_Post $post The global `$post` object.
 */
function wp_print_revision_templates() {
	global $post;
	?><script id="tmpl-revisions-frame" type="text/html">
		<div class="revisions-control-frame"></div>
		<div class="revisions-diff-frame"></div>
	</script>

	<script id="tmpl-revisions-buttons" type="text/html">
		<div class="revisions-previous">
			<input class="button" type="button" value="<?php echo esc_attr_x( 'Previous', 'Button label for a previous revision' ); ?>" />
		</div>

		<div class="revisions-next">
			<input class="button" type="button" value="<?php echo esc_attr_x( 'Next', 'Button label for a next revision' ); ?>" />
		</div>
	</script>

	<script id="tmpl-revisions-checkbox" type="text/html">
		<div class="revision-toggle-compare-mode">
			<label>
				<input type="checkbox" class="compare-two-revisions"
				<#
				if ( 'undefined' !== typeof data && data.model.attributes.compareTwoMode ) {
					#> checked="checked"<#
				}
				#>
				/>
				<?php esc_attr_e( 'Compare any two revisions' ); ?>
			</label>
		</div>
	</script>

	<script id="tmpl-revisions-meta" type="text/html">
		<# if ( ! _.isUndefined( data.attributes ) ) { #>
			<div class="diff-title">
				<# if ( 'from' === data.type ) { #>
					<strong><?php _ex( 'From:', 'Followed by post revision info' ); ?></strong>
				<# } else if ( 'to' === data.type ) { #>
					<strong><?php _ex( 'To:', 'Followed by post revision info' ); ?></strong>
				<# } #>
				<div class="author-card<# if ( data.attributes.autosave ) { #> autosave<# } #>">
					{{{ data.attributes.author.avatar }}}
					<div class="author-info">
					<# if ( data.attributes.autosave ) { #>
						<span class="byline"><?php printf( __( 'Autosave by %s' ),
							'<span class="author-name">{{ data.attributes.author.name }}</span>' ); ?></span>
					<# } else if ( data.attributes.current ) { #>
						<span class="byline"><?php printf( __( 'Current Revision by %s' ),
							'<span class="author-name">{{ data.attributes.author.name }}</span>' ); ?></span>
					<# } else { #>
						<span class="byline"><?php printf( __( 'Revision by %s' ),
							'<span class="author-name">{{ data.attributes.author.name }}</span>' ); ?></span>
					<# } #>
						<span class="time-ago">{{ data.attributes.timeAgo }}</span>
						<span class="date">({{ data.attributes.dateShort }})</span>
					</div>
				<# if ( 'to' === data.type && data.attributes.restoreUrl ) { #>
					<input  <?php if ( wp_check_post_lock( $post->ID ) ) { ?>
					