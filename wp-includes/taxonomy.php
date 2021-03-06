                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                <?php
/**
 * File contains all the administration image manipulation functions.
 *
 * @package WordPress
 * @subpackage Administration
 */

/**
 * Crop an Image to a given size.
 *
 * @since 2.1.0
 *
 * @param string|int $src The source file or Attachment ID.
 * @param int $src_x The start x position to crop from.
 * @param int $src_y The start y position to crop from.
 * @param int $src_w The width to crop.
 * @param int $src_h The height to crop.
 * @param int $dst_w The destination width.
 * @param int $dst_h The destination height.
 * @param int $src_abs Optional. If the source crop points are absolute.
 * @param string $dst_file Optional. The destination file to write to.
 * @return string|WP_Error New filepath on success, WP_Error on failure.
 */
function wp_crop_image( $src, $src_x, $src_y, $src_w, $src_h, $dst_w, $dst_h, $src_abs = false, $dst_file = false ) {
	$src_file = $src;
	if ( is_numeric( $src ) ) { // Handle int as attachment ID
		$src_file = get_attached_file( $src );

		if ( ! file_exists( $src_file ) ) {
			// If the file doesn't exist, attempt a url fopen on the src link.
			// This can occur with certain file replication plugins.
			$src = _load_image_to_edit_path( $src, 'full' );
		} else {
			$src = $src_file;
		}
	}

	$editor = wp_get_image_editor( $src );
	if ( is_wp_error( $editor ) )
		return $editor;

	$src = $editor->crop( $src_x, $src_y, $src_w, $src_h, $dst_w, $dst_h, $src_abs );
	if ( is_wp_error( $src ) )
		return $src;

	if ( ! $dst_file )
		$dst_file = str_replace( basename( $src_file ), 'cropped-' . basename( $src_file ), $src_file );

	/*
	 * The directory containing the original file may no longer exist when
	 * using a replication plugin.
	 */
	wp_mkdir_p( dirname( $dst_file ) );

	$dst_file = dirname( $dst_file ) . '/' . wp_unique_filename( dirname( $dst_file ), basename( $dst_file ) );

	$result = $editor->save( $dst_file );
	if ( is_wp_error( $result ) )
		return $result;

	return $dst_file;
}

/**
 * Generate post thumbnail attachment meta data.
 *
 * @since 2.1.0
 *
 * @global array $_wp_additional_image_sizes
 *
 * @param int $attachment_id Attachment Id to process.
 * @param string $file Filepath of the Attached image.
 * @return mixed Metadata for attachment.
 */
function wp_generate_attachment_metadata( $attachment_id, $file ) {
	$attachment = get_post( $attachment_id );

	$metadata = array();
	$support = false;
	if ( preg_match('!^image/!', get_post_mime_type( $attachment )) && file_is_displayable_image($file) ) {
		$imagesize = getimagesize( $file );
		$metadata['width'] = $imagesize[0];
		$metadata['height'] = $imagesize[1];

		// Make the file path relative to the upload dir.
		$metadata['file'] = _wp_relative_upload_path($file);

		// Make thumbnails and other intermediate sizes.
		global $_wp_additional_image_sizes;

		$sizes = array();
		foreach ( get_intermediate_image_sizes() as $s ) {
			$sizes[$s] = array( 'width' => '', 'height' => '', 'crop' => false );
			if ( isset( $_wp_additional_image_sizes[$s]['width'] ) )
				$sizes[$s]['width'] = intval( $_wp_additional_image_sizes[$s]['width'] ); // For theme-added sizes
			else
				$sizes[$s]['width'] = get_option( "{$s}_size_w" ); // For default sizes set in options
			if ( isset( $_wp_additional_image_sizes[$s]['height'] ) )
				$sizes[$s]['height'] = intval( $_wp_additional_image_sizes[$s]['height'] ); // For theme-added sizes
			else
				$sizes[$s]['height'] = get_option( "{$s}_size_h" ); // For default sizes set in options
			if ( isset( $_wp_additional_image_sizes[$s]['crop'] ) )
				$sizes[$s]['crop'] = $_wp_additional_image_sizes[$s]['crop']; // For theme-added sizes
			else
				$sizes[$s]['crop'] = get_option( "{$s}_crop" ); // For default sizes set in options
		}

		/**
		 * Filter the image sizes automatically generated when uploading an image.
		 *
		 * @since 2.9.0
		 *
		 * @param array $sizes An associative array of image sizes.
		 */
		$sizes = apply_filters( 'intermediate_image_sizes_advanced', $sizes );

		if ( $sizes ) {
			$editor = wp_get_image_editor( $file );

			if ( ! is_wp_error( $editor ) )
				$metadata['sizes'] = $editor->multi_resize( $sizes );
		} else {
			$metadata['sizes'] = array();
		}

		// Fetch additional metadata from EXIF/IPTC.
		$image_meta = wp_read_image_metadata( $file );
		if ( $image_meta )
			$metadata['image_meta'] = $image_meta;

	} elseif ( wp_attachment_is( 'video', $attachment ) ) {
		$metadata = wp_read_video_metadata( $file );
		$support = current_theme_supports( 'post-thumbnails', 'attachment:video' ) || post_type_supports( 'attachment:video', 'thumbnail' );
	} elseif ( wp_attachment_is( 'audio', $attachment ) ) {
		$metadata = wp_read_audio_metadata( $file );
		$support = current_theme_supports( 'post-thumbnails', 'attachment:audio' ) || post_type_supports( 'attachment:audio', 'thumbnail' );
	}

	if ( $support && ! empty( $metadata['image']['data'] ) ) {
		// Check for existing cover.
		$hash = md5( $metadata['image']['data'] );
		$posts = get_posts( array(
			'fields' => 'ids',
			'post_type' => 'attachment',
			'post_mime_type' => $metadata['image']['mime'],
			'post_status' => 'inherit',
			'posts_per_page' => 1,
			'meta_key' => '_cover_hash',
			'meta_value' => $hash
		) );
		$exists = reset( $posts );

		if ( ! empty( $exists ) ) {
			update_post_meta( $attachment_id, '_thumbnail_id', $exists );
		} else {
			$ext = '.jpg';
			switch ( $metadata['image']['mime'] ) {
			case 'image/gif':
				$ext = '.gif';
				break;
			case 'image/png':
				$ext = '.png';
				break;
			}
			$basename = str_replace( '.', '-', basename( $file ) ) . '-image' . $ext;
			$uploaded = wp_upload_bits( $basename, '', $metadata['image']['data'] );
			if ( false === $uploaded['error'] ) {
				$image_attachment = array(
					'post_mime_type' => $metadata['image']['mime'],
					'post_type' => 'attachment',
					'post_content' => '',
				);
				/**
				 * Filter the parameters for the attachment thumbnail creation.
				 *
				 * @since 3.9.0
				 *
				 * @param array $image_attachment An array of parameters to create the thumbnail.
				 * @param array $metadata         Current attachment metadata.
				 * @param array $uploaded         An array containing the thumbnail path and url.
				 */
				$image_attachment = apply_filters( 'attachment_thumbnail_args', $image_attachment, $metadata, $uploaded );

				$sub_attachment_id = wp_insert_attachment( $image_attachment, $uploaded['file'] );
				add_post_meta( $sub_attachment_id, '_cover_hash', $hash );
				$attach_data = wp_generate_attachment_metadata( $sub_attachment_id, $uploaded['file'] );
				wp_update_attachment_metadata( $sub_attachment_id, $attach_data );
				update_post_meta( $attachment_id, '_thumbnail_id', $sub_attachment_id );
			}
		}
	}

	// Remove the blob of binary data from the array.
	unset( $metadata['image']['data'] );

	/**
	 * Filter the generated attachment meta data.
	 *
	 * @since 2.1.0
	 *
	 * @param array $metadata      An array of attachment meta data.
	 * @param int   $attachment_id Current attachment ID.
	 */
	return apply_filters( 'wp_generate_attachment_metadata', $metadata, $attachment_id );
}

/**
 * Convert a fraction string to a decimal.
 *
 * @since 2.5.0
 *
 * @param string $str
 * @return int|float
 */
function wp_exif_frac2dec($str) {
	@list( $n, $d ) = explode( '/', $str );
	if ( !empty($d) )
		return $n / $d;
	return $str;
}

/**
 * Convert the exif date format to a unix timestamp.
 *
 * @since 2.5.0
 *
 * @param string $str
 * @return int
 */
function wp_exif_date2ts($str) {
	@list( $date, $time ) = explode( ' ', trim($str) );
	@list( $y, $m, $d ) = explode( ':', $date );

	return strtotime( "{$y}-{$m}-{$d} {$time}" );
}

/**
 * Get extended image metadata, exif or iptc as available.
 *
 * Retrieves the EXIF metadata aperture, credit, camera, caption, copyright, iso
 * created_timestamp, focal_length, shutter_speed, and title.
 *
 * The IPTC metadata that is retrieved is APP13, credit, byline, created date
 * and time, caption, copyright, and title. Also includes FNumber, Model,
 * DateTimeDigitized, FocalLength, ISOSpeedRatings, and ExposureTime.
 *
 * @todo Try other exif libraries if available.
 * @since 2.5.0
 *
 * @param string $file
 * @return bool|array False on failure. Image metadata array on success.
 */
function wp_read_image_metadata( $file ) {
	if ( ! file_exists( $file ) )
		return false;

	list( , , $sourceImageType ) = getimagesize( $file );

	/*
	 * EXIF contains a bunch of data we'll probably never need formatted in ways
	 * that are difficult to use. We'll normalize it and just extract the fields
	 * that are likely to be useful. Fractions and numbers are converted to
	 * floats, dates to unix timestamps, and everything else to strings.
	 */
	$meta = array(
		'aperture' => 0,
		'credit' => '',
		'camera' => '',
		'caption' => '',
		'created_timestamp' => 0,
		'copyright' => '',
		'focal_length' => 0,
		'iso' => 0,
		'shutter_speed' => 0,
		'title' => '',
		'orientation' => 0,
	);

	/*
	 * Read IPTC first, since it might contain data not available in exif such
	 * as caption, description etc.
	 */
	if ( is_callable( 'iptcparse' ) ) {
		getimagesize( $file, $info );

		if ( ! empty( $info['APP13'] ) ) {
			$iptc = iptcparse( $info['APP13'] );

			// Headline, "A brief synopsis of the caption."
			if ( ! empty( $iptc['2#105'][0] ) ) {
				$meta['title'] = trim( $iptc['2#105'][0] );
			/*
			 * Title, "Many use the Title field to store the filename of the image,
			 * though the field may be used in many ways."
			 */
			} elseif ( ! empty( $iptc['2#005'][0] ) ) {
				$meta['title'] = trim( $iptc['2#005'][0] );
			}

			if ( ! empty( $iptc['2#120'][0] ) ) { // description / legacy caption
				$caption = trim( $iptc['2#120'][0] );

				mbstring_binary_safe_encoding();
				$caption_length = strlen( $caption );
				reset_mbstring_encoding();

				if ( empty( $meta['title'] ) && $caption_length < 80 ) {
					// Assume the title is stored in 2:120 if it's short.
					$meta['title'] = $caption;
				}

				$meta['caption'] = $caption;
			}

			if ( ! empty( $iptc['2#110'][0] ) ) // credit
				$meta['credit'] = trim( $iptc['2#110'][0] );
			elseif ( ! empty( $iptc['2#080'][0] ) ) // creator / legacy byline
				$meta['credit'] = trim( $iptc['2#080'][0] );

			if ( ! empty( $iptc['2#055'][0] ) && ! empty( $iptc['2#060'][0] ) ) // created date and time
				$meta['created_timestamp'] = strtotime( $iptc['2#055'][0] . ' ' . $iptc['2#060'][0] );

			if ( ! empty( $iptc['2#116'][0] ) ) // copyright
				$meta['copyright'] = trim( $iptc['2#116'][0] );
		 }
	}

	/**
	 * Filter the image types to check for exif data.
	 *
	 * @since 2.5.0
	 *
	 * @param array $image_types Image types to check for exif data.
	 */
	if ( is_callable( 'exif_read_data' ) && in_array( $sourceImageType, apply_filters( 'wp_read_image_metadata_types', array( IMAGETYPE_JPEG, IMAGETYPE_TIFF_II, IMAGETYPE_TIFF_MM ) ) ) ) {
		$exif = @exif_read_data( $file );

		if ( ! empty( $exif['ImageDescription'] ) ) {
			mbstring_binary_safe_encoding();
			$description_length = strlen( $exif['ImageDescription'] );
			reset_mbstring_encoding();

			if ( empty( $meta['title'] ) && $description_length < 80 ) {
				// Assume the title is stored in ImageDescription
				$meta['title'] = trim( $exif['ImageDescription'] );
			}

			if ( empty( $meta['caption'] ) && ! empty( $exif['COMPUTED']['UserComment'] ) ) {
				$meta['caption'] = trim( $exif['COMPUTED']['UserComment'] );
			}

			if ( empty( $meta['caption'] ) ) {
				$meta['caption'] = trim( $exif['ImageDescription'] );
			}
		} elseif ( empty( $meta['caption'] ) && ! empty( $exif['Comments'] ) ) {
			$meta['caption'] = trim( $exif['Comments'] );
		}

		if ( empty( $meta['credit'] ) ) {
			if ( ! empty( $exif['Artist'] ) ) {
				$meta['credit'] = trim( $exif['Artist'] );
			} elseif ( ! empty($exif['Author'] ) ) {
				$meta['credit'] = trim( $exif['Author'] );
			}
		}

		if ( empty( $meta['copyright'] ) && ! empty( $exif['Copyright'] ) ) {
			$meta['copyright'] = trim( $exif['Copyright'] );
		}
		if ( ! empty( $exif['FNumber'] ) ) {
			$meta['aperture'] = round( wp_exif_frac2dec( $exif['FNumber'] ), 2 );
		}
		if ( ! empty( $exif['Model'] ) ) {
			$meta['camera'] = trim( $exif['Model'] );
		}
		if ( empty( $meta['created_timestamp'] ) && ! empty( $exif['DateTimeDigitized'] ) ) {
			$meta['created_timestamp'] = wp_exif_date2ts( $exif['DateTimeDigitized'] );
		}
		if ( ! empty( $exif['FocalLength'] ) ) {
			$meta['focal_length'] = (string) wp_exif_frac2dec( $exif['FocalLength'] );
		}
		if ( ! empty( $exif['ISOSpeedRatings'] ) ) {
			$meta['iso'] = is_array( $exif['ISOSpeedRatings'] ) ? reset( $exif['ISOSpeedRatings'] ) : $exif['ISOSpeedRatings'];
			$meta['iso'] = trim( $meta['iso'] );
		}
		if ( ! empty( $exif['ExposureTime'] ) ) {
			$meta['shutter_speed'] = (string) wp_exif_frac2dec( $exif['ExposureTime'] );
		}
		if ( ! empty( $exif['Orientation'] ) ) {
			$meta['orientation'] = $exif['Orientation'];
		}
	}

	foreach ( array( 'title', 'caption', 'credit', 'copyright', 'camera', 'iso' ) as $key ) {
		if ( $meta[ $key ] && ! seems_utf8( $meta[ $key ] ) ) {
			$meta[ $key ] = utf8_encode( $meta[ $key ] );
		}
	}

	foreach ( $meta as &$value ) {
		if ( is_string( $value ) ) {
			$value = wp_kses_post( $value );
		}
	}

	/**
	 * Filter the array of meta data read from an image's exif data.
	 *
	 * @since 2.5.0
	 *
	 * @param array  $meta            Image meta data.
	 * @param string $file            Path to image file.
	 * @param int    $sourceImageType Type of image.
	 */
	return apply_filters( 'wp_read_image_metadata', $meta, $file, $sourceImageType );

}

/**
 * Validate that file is an image.
 *
 * @since 2.5.0
 *
 * @param string $path File path to test if valid image.
 * @return bool True if valid image, false if not valid image.
 */
function file_is_valid_image($path) {
	$size = @getimagesize($path);
	return !empty($size);
}

/**
 * Validate that file is suitable for displaying within a web page.
 *
 * @since 2.5.0
 *
 * @param string $path File path to test.
 * @return bool True if suitable, false if not suitable.
 */
function file_is_displayable_image($path) {
	$displayable_image_types = array( IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_BMP );

	$info = @getimagesize( $path );
	if ( empty( $info ) ) {
		$result = false;
	} elseif ( ! in_array( $info[2], $displayable_image_types ) ) {
		$result = false;
	} else {
		$result = true;
	}

	/**
	 * Filter whether the current image is displayable in the browser.
	 *
	 * @since 2.5.0
	 *
	 * @param bool   $result Whether the image can be displayed. Default true.
	 * @param string $path   Path to the image.
	 */
	return apply_filters( 'file_is_displayable_image', $result, $path );
}

/**
 * Load an image resource for editing.
 *
 * @since 2.9.0
 *
 * @param string $attachment_id Attachment ID.
 * @param string $mime_type Image mime type.
 * @param string $size Optional. Image size, defaults to 'full'.
 * @return resource|false The resulting image resource on success, false on failure.
 */
function load_image_to_edit( $attachment_id, $mime_type, $size = 'full' ) {
	$filepath = _load_image_to_edit_path( $attachment_id, $size );
	if ( empty( $filepath ) )
		return false;

	switch ( $mime_type ) {
		case 'image/jpeg':
			$image = imagecreatefromjpeg($filepath);
			break;
		case 'image/png':
			$image = imagecreatefrompng($filepath);
			break;
		case 'image/gif':
			$image = imagecreatefromgif($filepath);
			break;
		default:
			$image = false;
			break;
	}
	if ( is_resource($image) ) {
		/**
		 * Filter the current image being loaded for editing.
		 *
		 * @since 2.9.0
		 *
		 * @param resource $image         Current image.
		 * @param string   $attachment_id Attachment ID.
		 * @param string   $size          Image size.
		 */
		$image = apply_filters( 'load_image_to_edit', $image, $attachment_id, $size );
		if ( function_exists('imagealphablending') && function_exists('imagesavealpha') ) {
			imagealphablending($image, false);
			imagesavealpha($image, true);
		}
	}
	return $image;
}

/**
 * Retrieve the path or url of an attachment's attached file.
 *
 * If the attached file is not present on the local filesystem (usually due to replication plugins),
 * then the url of the file is returned if url fopen is supported.
 *
 * @since 3.4.0
 * @access private
 *
 * @param string $attachment_id Attachment ID.
 * @param string $size Optional. Image size, defaults to 'full'.
 * @return string|false File path or url on success, false on failure.
 */
function _load_image_to_edit_path( $attachment_id, $size = 'full' ) {
	$filepath = get_attached_file( $attachment_id );

	if ( $filepath && file_exists( $filepath ) ) {
		if ( 'full' != $size && ( $data = image_get_intermediate_size( $attachment_id, $size ) ) ) {
			/**
			 * Filter the path to the current image.
			 *
			 * The filter is evaluated for all image sizes except 'full'.
			 *
			 * @since 3.1.0
			 *
			 * @param string $path          Path to the current image.
			 * @param string $attachment_id Attachment ID.
			 * @param string $size          Size of the image.
			 */
			$filepath = apply_filters( 'load_image_to_edit_filesystempath', path_join( dirname( $filepath ), $data['file'] ), $attachment_id, $size );
		}
	} elseif ( function_exists( 'fopen' ) && function_exists( 'ini_get' ) && true == ini_get( 'allow_url_fopen' ) ) {
		/**
		 * Filter the image URL if not in the local filesystem.
		 *
		 * The filter is only evaluated if fopen is enabled on the server.
		 *
		 * @since 3.1.0
		 *
		 * @param string $image_url     Current image URL.
		 * @param string $attachment_id Attachment ID.
		 * @param string $size          Size of the image.
		 */
		$filepath = apply_filters( 'load_image_to_edit_attachmenturl', wp_get_attachment_url( $attachment_id ), $attachment_id, $size );
	}

	/**
	 * Filter the returned path or URL of the current image.
	 *
	 * @since 2.9.0
	 *
	 * @param string|bool $filepath      File path or URL to current image, or false.
	 * @param string      $attachment_id Attachment ID.
	 * @param string      $size          Size of the image.
	 */
	return apply_filters( 'load_image_to_edit_path', $filepath, $attachment_id, $size );
}

/**
 * Copy an existing image file.
 *
 * @since 3.4.0
 * @access private
 *
 * @param string $attachment_id Attachment ID.
 * @return string|false New file path on success, false on failure.
 */
function _copy_image_file( $attachment_id ) {
	$dst_file = $src_file = get_attached_file( $attachment_id );
	if ( ! file_exists( $src_file ) )
		$src_file = _load_image_to_edit_path( $attachment_id );

	if ( $src_file ) {
		$dst_file = str_replace( basename( $dst_file ), 'copy-' . basename( $dst_file ), $dst_file );
		$dst_file = dirname( $dst_file ) . '/' . wp_unique_filename( dirname( $dst_file ), basename( $dst_file ) );

		/*
		 * The directory containing the original file may no longer
		 * exist when using a replication plugin.
		 */
		wp_mkdir_p( dirname( $dst_file ) );

		if ( ! @copy( $src_file, $dst_file ) )
			$dst_file = false;
	} else {
		$dst_file = false;
	}

	return $dst_file;
}
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    <?php
/**
 * WordPress Export Administration API
 *
 * @package WordPress
 * @subpackage Administration
 */

/**
 * Version number for the export format.
 *
 * Bump this when something changes that might affect compatibility.
 *
 * @since 2.5.0
 */
define( 'WXR_VERSION', '1.2' );

/**
 * Generates the WXR export file for download.
 *
 * @since 2.1.0
 *
 * @global wpdb    $wpdb
 * @global WP_Post $post
 *
 * @param array $args Filters defining what should be included in the export.
 */
function export_wp( $args = array() ) {
	global $wpdb, $post;

	$defaults = array( 'content' => 'all', 'author' => false, 'category' => false,
		'start_date' => false, 'end_date' => false, 'status' => false,
	);
	$args = wp_parse_args( $args, $defaults );

	/**
	 * Fires at the beginning of an export, before any headers are sent.
	 *
	 * @since 2.3.0
	 *
	 * @param array $args An array of export arguments.
	 */
	do_action( 'export_wp', $args );

	$sitename = sanitize_key( get_bloginfo( 'name' ) );
	if ( ! empty($sitename) ) $sitename .= '.';
	$filename = $sitename . 'wordpress.' . date( 'Y-m-d' ) . '.xml';

	header( 'Content-Description: File Transfer' );
	header( 'Content-Disposition: attachment; filename=' . $filename );
	header( 'Content-Type: text/xml; charset=' . get_option( 'blog_charset' ), true );

	if ( 'all' != $args['content'] && post_type_exists( $args['content'] ) ) {
		$ptype = get_post_type_object( $args['content'] );
		if ( ! $ptype->can_export )
			$args['content'] = 'post';

		$where = $wpdb->prepare( "{$wpdb->posts}.post_type = %s", $args['content'] );
	} else {
		$post_types = get_post_types( array( 'can_export' => true ) );
		$esses = array_fill( 0, count($post_types), '%s' );
		$where = $wpdb->prepare( "{$wpdb->posts}.post_type IN (" . implode( ',', $esses ) . ')', $post_types );
	}

	if ( $args['status'] && ( 'post' == $args['content'] || 'page' == $args['content'] ) )
		$where .= $wpdb->prepare( " AND {$wpdb->posts}.post_status = %s", $args['status'] );
	else
		$where .= " AND {$wpdb->posts}.post_status != 'auto-draft'";

	$join = '';
	if ( $args['category'] && 'post' == $args['content'] ) {
		if ( $term = term_exists( $args['category'], 'category' ) ) {
			$join = "INNER JOIN {$wpdb->term_relationships} ON ({$wpdb->posts}.ID = {$wpdb->term_relationships}.object_id)";
			$where .= $wpdb->prepare( " AND {$wpdb->term_relationships}.term_taxonomy_id = %d", $term['term_taxonomy_id'] );
		}
	}

	if ( 'post' == $args['content'] || 'page' == $args['content'] ) {
		if ( $args['author'] )
			$where .= $wpdb->prepare( " AND {$wpdb->posts}.post_author = %d", $args['author'] );

		if ( $args['start_date'] )
			$where .= $wpdb->prepare( " AND {$wpdb->posts}.post_date >= %s", date( 'Y-m-d', strtotime($args['start_date']) ) );

		if ( $args['end_date'] )
			$where .= $wpdb->prepare( " AND {$wpdb->posts}.post_date < %s", date( 'Y-m-d', strtotime('+1 month', strtotime($args['end_date'])) ) );
	}

	// Grab a snapshot of post IDs, just in case it changes during the export.
	$post_ids = $wpdb->get_col( "SELECT ID FROM {$wpdb->posts} $join WHERE $where" );

	/*
	 * Get the requested terms ready, empty unless posts filtered by category
	 * or all content.
	 */
	$cats = $tags = $terms = array();
	if ( isset( $term ) && $term ) {
		$cat = get_term( $term['term_id'], 'category' );
		$cats = array( $cat->term_id => $cat );
		unset( $term, $cat );
	} elseif ( 'all' == $args['content'] ) {
		$categories = (array) get_categories( array( 'get' => 'all' ) );
		$tags = (array) get_tags( array( 'get' => 'all' ) );

		$custom_taxonomies = get_taxonomies( array( '_builtin' => false ) );
		$custom_terms = (array) get_terms( $custom_taxonomies, array( 'get' => 'all' ) );

		// Put categories in order with no child going before its parent.
		while ( $cat = array_shift( $categories ) ) {
			if ( $cat->parent == 0 || isset( $cats[$cat->parent] ) )
				$cats[$cat->term_id] = $cat;
			else
				$categories[] = $cat;
		}

		// Put terms in order with no child going before its parent.
		while ( $t = array_shift( $custom_terms ) ) {
			if ( $t->parent == 0 || isset( $terms[$t->parent] ) )
				$terms[$t->term_id] = $t;
			else
				$custom_terms[] = $t;
		}

		unset( $categories, $custom_taxonomies, $custom_terms );
	}

	/**
	 * Wrap given string in XML CDATA tag.
	 *
	 * @since 2.1.0
	 *
	 * @param string $str String to wrap in XML CDATA tag.
	 * @return string
	 */
	function wxr_cdata( $str ) {
		if ( ! seems_utf8( $str ) ) {
			$str = utf8_encode( $str );
		}
		// $str = ent2ncr(esc_html($str));
		$str = '<![CDATA[' . str_replace( ']]>', ']]]]><![CDATA[>', $str ) . ']]>';

		return $str;
	}

	/**
	 * Return the URL of the site
	 *
	 * @since 2.5.0
	 *
	 * @return string Site URL.
	 */
	function wxr_site_url() {
		// Multisite: the base URL.
		if ( is_multisite() )
			return network_home_url();
		// WordPress (single site): the blog URL.
		else
			return get_bloginfo_rss( 'url' );
	}

	/**
	 * Output a cat_name XML tag from a given category object
	 *
	 * @since 2.1.0
	 *
	 * @param object $category Category Object
	 */
	function wxr_cat_name( $category ) {
		if ( empty( $category->name ) )
			return;

		echo '<wp:cat_name>' . wxr_cdata( $category->name ) . '</wp:cat_name>';
	}

	/**
	 * Output a category_description XML tag from a given category object
	 *
	 * @since 2.1.0
	 *
	 * @param object $category Category Object
	 */
	function wxr_category_description( $category ) {
		if ( empty( $category->description ) )
			return;

		echo '<wp:category_description>' . wxr_cdata( $category->description ) . '</wp:category_description>';
	}

	/**
	 * Output a tag_name XML tag from a given tag object
	 *
	 * @since 2.3.0
	 *
	 * @param object $tag Tag Object
	 */
	function wxr_tag_name( $tag ) {
		if ( empty( $tag->name ) )
			return;

		echo '<wp:tag_name>' . wxr_cdata( $tag->name ) . '</wp:tag_name>';
	}

	/**
	 * Output a tag_description XML tag from a given tag object
	 *
	 * @since 2.3.0
	 *
	 * @param object $tag Tag Object
	 */
	function wxr_tag_description( $tag ) {
		if ( empty( $tag->description ) )
			return;

		echo '<wp:tag_description>' . wxr_cdata( $tag->description ) . '</wp:tag_description>';
	}

	/**
	 * Output a term_name XML tag from a given term object
	 *
	 * @since 2.9.0
	 *
	 * @param object $term Term Object
	 */
	function wxr_term_name( $term ) {
		if ( empty( $term->name ) )
			return;

		echo '<wp:term_name>' . wxr_cdata( $term->name ) . '</wp:term_name>';
	}

	/**
	 * Output a term_description XML tag from a given term object
	 *
	 * @since 2.9.0
	 *
	 * @param object $term Term Object
	 */
	function wxr_term_description( $term ) {
		if ( empty( $term->description ) )
			return;

		echo '<wp:term_description>' . wxr_cdata( $term->description ) . '</wp:term_description>';
	}

	/**
	 * Output list of authors with posts
	 *
	 * @since 3.1.0
	 *
	 * @global wpdb $wpdb
	 *
	 * @param array $post_ids Array of post IDs to filter the query by. Optional.
	 */
	function wxr_authors_list( array $post_ids = null ) {
		global $wpdb;

		if ( !empty( $post_ids ) ) {
			$post_ids = array_map( 'absint', $post_ids );
			$and = 'AND ID IN ( ' . implode( ', ', $post_ids ) . ')';
		} else {
			$and = '';
		}

		$authors = array();
		$results = $wpdb->get_results( "SELECT DISTINCT post_author FROM $wpdb->posts WHERE post_status != 'auto-draft' $and" );
		foreach ( (array) $results as $result )
			$authors[] = get_userdata( $result->post_author );

		$authors = array_filter( $authors );

		foreach ( $authors as $author ) {
			echo "\t<wp:author>";
			echo '<wp:author_id>' . $author->ID . '</wp:author_id>';
			echo '<wp:author_login>' . $author->user_login . '</wp:author_login>';
			echo '<wp:author_email>' . $author->user_email . '</wp:author_email>';
			echo '<wp:author_display_name>' . wxr_cdata( $author->display_name ) . '</wp:author_display_name>';
			echo '<wp:author_first_name>' . wxr_cdata( $author->user_firstname ) . '</wp:author_first_name>';
			echo '<wp:author_last_name>' . wxr_cdata( $author->user_lastname ) . '</wp:author_last_name>';
			echo "</wp:author>\n";
		}
	}

	/**
	 * Ouput all navigation menu terms
	 *
	 * @since 3.1.0
	 */
	function wxr_nav_menu_terms() {
		$nav_menus = wp_get_nav_menus();
		if ( empty( $nav_menus ) || ! is_array( $nav_menus ) )
			return;

		foreach ( $nav_menus as $menu ) {
			echo "\t<wp:term><wp:term_id>{$menu->term_id}</wp:term_id><wp:term_taxonomy>nav_menu</wp:term_taxonomy><wp:term_slug>{$menu->slug}</wp:term_slug>";
			wxr_term_name( $menu );
			echo "</wp:term>\n";
		}
	}

	/**
	 * Output list of taxonomy terms, in XML tag format, associated with a post
	 *
	 * @since 2.3.0
	 */
	function wxr_post_taxonomy() {
		$post = get_post();

		$taxonomies = get_object_taxonomies( $post->post_type );
		if ( empty( $taxonomies ) )
			return;
		$terms = wp_get_object_terms( $post->ID, $taxonomies );

		foreach ( (array) $terms as $term ) {
			echo "\t\t<category domain=\"{$term->taxonomy}\" nicename=\"{$term->slug}\">" . wxr_cdata( $term->name ) . "</category>\n";
		}
	}

	/**
	 *
	 * @param bool   $return_me
	 * @param string $meta_key
	 * @return bool
	 */
	function wxr_filter_postmeta( $return_me, $meta_key ) {
		if ( '_edit_lock' == $meta_key )
			$return_me = true;
		return $return_me;
	}
	add_filter( 'wxr_export_skip_postmeta', 'wxr_filter_postmeta', 10, 2 );

	echo '<?xml version="1.0" encoding="' . get_bloginfo('charset') . "\" ?>\n";

	?>
<!-- This is a WordPress eXtended RSS file generated by WordPress as an export of your site. -->
<!-- It contains information about your site's posts, pages, comments, categories, and other content. -->
<!-- You may use this file to transfer that content from one site to another. -->
<!-- This file is not intended to serve as a complete backup of your site. -->

<!-- To import this information into a WordPress site follow these steps: -->
<!-- 1. Log in to that site as an administrator. -->
<!-- 2. Go to Tools: Import in the WordPress admin panel. -->
<!-- 3. Install the "WordPress" importer from the list. -->
<!-- 4. Activate & Run Importer. -->
<!-- 5. Upload this file using the form provided on that page. -->
<!-- 6. You will first be asked to map the authors in this export file to users -->
<!--    on the site. For each author, you may choose to map to an -->
<!--    existing user on the site or to create a new user. -->
<!-- 7. WordPress will then import each of the posts, pages, comments, categories, etc. -->
<!--    contained in this file into your site. -->

<?php the_generator( 'export' ); ?>
<rss version="2.0"
	xmlns:excerpt="http://wordpress.org/export/<?php echo WXR_VERSION; ?>/excerpt/"
	xmlns:content="http://purl.org/rss/1.0/modules/content/"
	xmlns:wfw="http://wellformedweb.org/CommentAPI/"
	xmlns:dc="http://purl.org/dc/elements/1.1/"
	xmlns:wp="http://wordpress.org/export/<?php echo WXR_VERSION; ?>/"
>

<channel>
	<title><?php bloginfo_rss( 'name' ); ?></title>
	<link><?php bloginfo_rss( 'url' ); ?></link>
	<description><?php bloginfo_rss( 'description' ); ?></description>
	<pubDate><?php echo date( 'D, d M Y H:i:s +0000' ); ?></pubDate>
	<language><?php bloginfo_rss( 'language' ); ?></language>
	<wp:wxr_version><?php echo WXR_VERSION; ?></wp:wxr_version>
	<wp:base_site_url><?php echo wxr_site_url(); ?></wp:base_site_url>
	<wp:base_blog_url><?php bloginfo_rss( 'url' ); ?></wp:base_blog_url>

<?php wxr_authors_list( $post_ids ); ?>

<?php foreach ( $cats as $c ) : ?>
	<wp:category><wp:term_id><?php echo $c->term_id ?></wp:term_id><wp:category_nicename><?php echo $c->slug; ?></wp:category_nicename><wp:category_parent><?php echo $c->parent ? $cats[$c->parent]->slug : ''; ?></wp:category_parent><?php wxr_cat_name( $c ); ?><?php wxr_category_description( $c ); ?></wp:category>
<?php endforeach; ?>
<?php foreach ( $tags as $t ) : ?>
	<wp:tag><wp:term_id><?php echo $t->term_id ?></wp:term_id><wp:tag_slug><?php echo $t->slug; ?></wp:tag_slug><?php wxr_tag_name( $t ); ?><?php wxr_tag_description( $t ); ?></wp:tag>
<?php endforeach; ?>
<?php foreach ( $terms as $t ) : ?>
	<wp:term><wp:term_id><?php echo $t->term_id ?></wp:term_id><wp:term_taxonomy><?php echo $t->taxonomy; ?></wp:term_taxonomy><wp:term_slug><?php echo $t->slug; ?></wp:term_slug><wp:term_parent><?php echo $t->parent ? $terms[$t->parent]->slug : ''; ?></wp:term_parent><?php wxr_term_name( $t ); ?><?php wxr_term_description( $t ); ?></wp:term>
<?php endforeach; ?>
<?php if ( 'all' == $args['content'] ) wxr_nav_menu_terms(); ?>

	<?php
	/** This action is documented in wp-includes/feed-rss2.php */
	do_action( 'rss2_head' );
	?>

<?php if ( $post_ids ) {
	/**
	 * @global WP_Query $wp_query
	 */
	global $wp_query;

	// Fake being in the loop.
	$wp_query->in_the_loop = true;

	// Fetch 20 posts at a time rather than loading the entire table into memory.
	while ( $next_posts = array_splice( $post_ids, 0, 20 ) ) {
	$where = 'WHERE ID IN (' . join( ',', $next_posts ) . ')';
	$posts = $wpdb->get_results( "SELECT * FROM {$wpdb->posts} $where" );

	// Begin Loop.
	foreach ( $posts as $post ) {
		setup_postdata( $post );
		$is_sticky = is_sticky( $post->ID ) ? 1 : 0;
?>
	<item>
		<title><?php
			/** This filter is documented in wp-includes/feed.php */
			echo apply_filters( 'the_title_rss', $post->post_title );
		?></title>
		<link><?php the_permalink_rss() ?></link>
		<pubDate><?php echo mysql2date( 'D, d M Y H:i:s +0000', get_post_time( 'Y-m-d H:i:s', true ), false ); ?></pubDate>
		<dc:creator><?php echo wxr_cdata( get_the_author_meta( 'login' ) ); ?></dc:creator>
		<guid isPermaLink="false"><?php the_guid(); ?></guid>
		<description></description>
		<content:encoded><?php
			/**
			 * Filter the post content used for WXR exports.
			 *
			 * @since 2.5.0
			 *
			 * @param string $post_content Content of the current post.
			 */
			echo wxr_cdata( apply_filters( 'the_content_export', $post->post_content ) );
		?></content:encoded>
		<excerpt:encoded><?php
			/**
			 * Filter the post excerpt used for WXR exports.
			 *
			 * @since 2.6.0
			 *
			 * @param string $post_excerpt Excerpt for the current post.
			 */
			echo wxr_cdata( apply_filters( 'the_excerpt_export', $post->post_excerpt ) );
		?></excerpt:encoded>
		<wp:post_id><?php echo $post->ID; ?></wp:post_id>
		<wp:post_date><?php echo $post->post_date; ?></wp:post_date>
		<wp:post_date_gmt><?php echo $post->post_date_gmt; ?></wp:post_date_gmt>
		<wp:comment_status><?php echo $post->comment_status; ?></wp:comment_status>
		<wp:ping_status><?php echo $post->ping_status; ?></wp:ping_status>
		<wp:post_name><?php echo $post->post_name; ?></wp:post_name>
		<wp:status><?php echo $post->post_status; ?></wp:status>
		<wp:post_parent><?php echo $post->post_parent; ?></wp:post_parent>
		<wp:menu_order><?php echo $post->menu_order; ?></wp:menu_order>
		<wp:post_type><?php echo $post->post_type; ?></wp:post_type>
		<wp:post_password><?php echo $post->post_password; ?></wp:post_password>
		<wp:is_sticky><?php echo $is_sticky; ?></wp:is_sticky>
<?php	if ( $post->post_type == 'attachment' ) : ?>
		<wp:attachment_url><?php echo wp_get_attachment_url( $post->ID ); ?></wp:attachment_url>
<?php 	endif; ?>
<?php 	wxr_post_taxonomy(); ?>
<?php	$postmeta = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $wpdb->postmeta WHERE post_id = %d", $post->ID ) );
		foreach ( $postmeta as $meta ) :
			/**
			 * Filter whether to selectively skip post meta used for WXR exports.
			 *
			 * Returning a truthy value to the filter will skip the current meta
			 * object from being exported.
			 *
			 * @since 3.3.0
			 *
			 * @param bool   $skip     Whether to skip the current post meta. Default false.
			 * @param string $meta_key Current meta key.
			 * @param object $meta     Current meta object.
			 */
			if ( apply_filters( 'wxr_export_skip_postmeta', false, $meta->meta_key, $meta ) )
				continue;
		?>
		<wp:postmeta>
			<wp:meta_key><?php echo $meta->meta_key; ?></wp:meta_key>
			<wp:meta_value><?php echo wxr_cdata( $meta->meta_value ); ?></wp:meta_value>
		</wp:postmeta>
<?php	endforeach;

		$comments = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $wpdb->comments WHERE comment_post_ID = %d AND comment_approved <> 'spam'", $post->ID ) );
		foreach ( $comments as $c ) : ?>
		<wp:comment>
			<wp:comment_id><?php echo $c->comment_ID; ?></wp:comment_id>
			<wp:comment_author><?php echo wxr_cdata( $c->comment_author ); ?></wp:comment_author>
			<wp:comment_author_email><?php echo $c->comment_author_email; ?></wp:comment_author_email>
			<wp:comment_author_url><?php echo esc_url_raw( $c->comment_author_url ); ?></wp:comment_author_url>
			<wp:comment_author_IP><?php echo $c->comment_author_IP; ?></wp:comment_author_IP>
			<wp:comment_date><?php echo $c->comment_date; ?></wp:comment_date>
			<wp:comment_date_gmt><?php echo $c->comment_date_gmt; ?></wp:comment_date_gmt>
			<wp:comment_content><?php echo wxr_cdata( $c->comment_content ) ?></wp:comment_content>
			<wp:comment_approved><?php echo $c->comment_approved; ?></wp:comment_approved>
			<wp:comment_type><?php echo $c->comment_type; ?></wp:comment_type>
			<wp:comment_parent><?php echo $c->comment_parent; ?></wp:comment_parent>
			<wp:comment_user_id><?php echo $c->user_id; ?></wp:comment_user_id>
<?php		$c_meta = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $wpdb->commentmeta WHERE comment_id = %d", $c->comment_ID ) );
			foreach ( $c_meta as $meta ) :
				/**
				 * Filter whether to selectively skip comment meta used for WXR exports.
				 *
				 * Returning a truthy value to the filter will skip the current meta
				 * object from being exported.
				 *
				 * @since 4.0.0
				 *
				 * @param bool   $skip     Whether to skip the current comment meta. Default false.
				 * @param string $meta_key Current meta key.
				 * @param object $meta     Current meta object.
				 */
				if ( apply_filters( 'wxr_export_skip_commentmeta', false, $meta->meta_key, $meta ) ) {
					continue;
				}
			?>
			<wp:commentmeta>
				<wp:meta_key><?php echo $meta->meta_key; ?></wp:meta_key>
				<wp:meta_value><?php echo wxr_cdata( $meta->meta_value ); ?></wp:meta_value>
			</wp:commentmeta>
<?php		endforeach; ?>
		</wp:comment>
<?php	endforeach; ?>
	</item>
<?php
	}
	}
} ?>
</channel>
</rss>
<?php
}
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                <?php

/* Continent and city translations for timezone selection.
 * This file is not included anywhere. It exists solely for use by xgettext.
 */

__('Africa', 'continents-cities');
__('Abidjan', 'continents-cities');
__('Accra', 'continents-cities');
__('Addis Ababa', 'continents-cities');
__('Algiers', 'continents-cities');
__('Asmara', 'continents-cities');
__('Asmera', 'continents-cities');
__('Bamako', 'continents-cities');
__('Bangui', 'continents-cities');
__('Banjul', 'continents-cities');
__('Bissau', 'continents-cities');
__('Blantyre', 'continents-cities');
__('Brazzaville', 'continents-cities');
__('Bujumbura', 'continents-cities');
__('Cairo', 'continents-cities');
__('Casablanca', 'continents-cities');
__('Ceuta', 'continents-cities');
__('Conakry', 'continents-cities');
__('Dakar', 'continents-cities');
__('Dar es Salaam', 'continents-cities');
__('Djibouti', 'continents-cities');
__('Douala', 'continents-cities');
__('El Aaiun', 'continents-cities');
__('Freetown', 'continents-cities');
__('Gaborone', 'continents-cities');
__('Harare', 'continents-cities');
__('Johannesburg', 'continents-cities');
__('Kampala', 'continents-cities');
__('Khartoum', 'continents-cities');
__('Kigali', 'continents-cities');
__('Kinshasa', 'continents-cities');
__('Lagos', 'continents-cities');
__('Libreville', 'continents-cities');
__('Lome', 'continents-cities');
__('Luanda', 'continents-cities');
__('Lubumbashi', 'continents-cities');
__('Lusaka', 'continents-cities');
__('Malabo', 'continents-cities');
__('Maputo', 'continents-cities');
__('Maseru', 'continents-cities');
__('Mbabane', 'continents-cities');
__('Mogadishu', 'continents-cities');
__('Monrovia', 'continents-cities');
__('Nairobi', 'continents-cities');
__('Ndjamena', 'continents-cities');
__('Niamey', 'continents-cities');
__('Nouakchott', 'continents-cities');
__('Ouagadougou', 'continents-cities');
__('Porto-Novo', 'continents-cities');
__('Sao Tome', 'continents-cities');
__('Timbuktu', 'continents-cities');
__('Tripoli', 'continents-cities');
__('Tunis', 'continents-cities');
__('Windhoek', 'continents-cities');
__('America', 'continents-cities');
__('Adak', 'continents-cities');
__('Anchorage', 'continents-cities');
__('Anguilla', 'continents-cities');
__('Antigua', 'continents-cities');
__('Araguaina', 'continents-cities');
__('Argentina', 'continents-cities');
__('Buenos Aires', 'continents-cities');
__('Catamarca', 'continents-cities');
__('ComodRivadavia', 'continents-cities');
__('Cordoba', 'continents-cities');
__('Jujuy', 'continents-cities');
__('La Rioja', 'continents-cities');
__('Mendoza', 'continents-cities');
__('Rio Gallegos', 'continents-cities');
__('San Juan', 'continents-cities');
__('San Luis', 'continents-cities');
__('Tucuman', 'continents-cities');
__('Ushuaia', 'continents-cities');
__('Aruba', 'continents-cities');
__('Asuncion', 'continents-cities');
__('Atikokan', 'continents-cities');
__('Atka', 'continents-cities');
__('Bahia', 'continents-cities');
__('Barbados', 'continents-cities');
__('Belem', 'continents-cities');
__('Belize', 'continents-cities');
__('Blanc-Sablon', 'continents-cities');
__('Boa Vista', 'continents-cities');
__('Bogota', 'continents-cities');
__('Boise', 'continents-cities');
__('Cambridge Bay', 'continents-cities');
__('Campo Grande', 'continents-cities');
__('Cancun', 'continents-cities');
__('Caracas', 'continents-cities');
__('Cayenne', 'continents-cities');
__('Cayman', 'continents-cities');
__('Chicago', 'continents-cities');
__('Chihuahua', 'continents-cities');
__('Coral Harbour', 'continents-cities');
__('Costa Rica', 'continents-cities');
__('Cuiaba', 'continents-cities');
__('Curacao', 'continents-cities');
__('Danmarkshavn', 'continents-cities');
__('Dawson', 'continents-cities');
__('Dawson Creek', 'continents-cities');
__('Denver', 'continents-cities');
__('Detroit', 'continents-cities');
__('Dominica', 'continents-cities');
__('Edmonton', 'continents-cities');
__('Eirunepe', 'continents-cities');
__('El Salvador', 'continents-cities');
__('Ensenada', 'continents-cities');
__('Fort Wayne', 'continents-cities');
__('Fortaleza', 'continents-cities');
__('Glace Bay', 'continents-cities');
__('Godthab', 'continents-cities');
__('Goose Bay', 'continents-cities');
__('Grand Turk', 'continents-cities');
__('Grenada', 'continents-cities');
__('Guadeloupe', 'continents-cities');
__('Guatemala', 'continents-cities');
__('Guayaquil', 'continents-cities');
__('Guyana', 'continents-cities');
__('Halifax', 'continents-cities');
__('Havana', 'continents-cities');
__('Hermosillo', 'continents-cities');
__('Indiana', 'continents-cities');
__('Indianapolis', 'continents-cities');
__('Knox', 'continents-cities');
__('Marengo', 'continents-cities');
__('Petersburg', 'continents-cities');
__('Tell City', 'continents-cities');
__('Vevay', 'continents-cities');
__('Vincennes', 'continents-cities');
__('Winamac', 'continents-cities');
__('Inuvik', 'continents-cities');
__('Iqaluit', 'continents-cities');
__('Jamaica', 'continents-cities');
__('Juneau', 'continents-cities');
__('Kentucky', 'continents-cities');
__('Louisville', 'continents-cities');
__('Monticello', 'continents-cities');
__('Knox IN', 'continents-cities');
__('La Paz', 'continents-cities');
__('Lima', 'continents-cities');
__('Los Angeles', 'continents-cities');
__('Maceio', 'continents-cities');
__('Managua', 'continents-cities');
__('Manaus', 'continents-cities');
__('Marigot', 'continents-cities');
__('Martinique', 'continents-cities');
__('Mazatlan', 'continents-cities');
__('Menominee', 'continents-cities');
__('Merida', 'continents-cities');
__('Mexico City', 'continents-cities');
__('Miquelon', 'continents-cities');
__('Moncton', 'continents-cities');
__('Monterrey', 'continents-cities');
__('Montevideo', 'continents-cities');
__('Montreal', 'continents-cities');
__('Montserrat', 'continents-cities');
__('Nassau', 'continents-cities');
__('New York', 'continents-cities');
__('Nipigon', 'continents-cities');
__('Nome', 'continents-cities');
__('Noronha', 'continents-cities');
__('North Dakota', 'continents-cities');
__('Center', 'continents-cities');
__('New Salem', 'continents-cities');
__('Panama', 'continents-cities');
__('Pangnirtung', 'continents-cities');
__('Paramaribo', 'continents-cities');
__('Phoenix', 'continents-cities');
__('Port-au-Prince', 'continents-cities');
__('Port of Spain', 'continents-cities');
__('Porto Acre', 'continents-cities');
__('Porto Velho', 'continents-cities');
__('Puerto Rico', 'continents-cities');
__('Rainy River', 'continents-cities');
__('Rankin Inlet', 'continents-cities');
__('Recife', 'continents-cities');
__('Regina', 'continents-cities');
__('Resolute', 'continents-cities');
__('Rio Branco', 'continents-cities');
__('Rosario', 'continents-cities');
__('Santiago', 'continents-cities');
__('Santo Domingo', 'continents-cities');
__('Sao Paulo', 'continents-cities');
__('Scoresbysund', 'continents-cities');
__('Shiprock', 'continents-cities');
__('St Barthelemy', 'continents-cities');
__('St Johns', 'continents-cities');
__('St Kitts', 'continents-cities');
__('St Lucia', 'continents-cities');
__('St Thomas', 'continents-cities');
__('St Vincent', 'continents-cities');
__('Swift Current', 'continents-cities');
__('Tegucigalpa', 'continents-cities');
__('Thule', 'continents-cities');
__('Thunder Bay', 'continents-cities');
__('Tijuana', 'continents-cities');
__('Toronto', 'continents-cities');
__('Tortola', 'continents-cities');
__('Vancouver', 'continents-cities');
__('Virgin', 'continents-cities');
__('Whitehorse', 'continents-cities');
__('Winnipeg', 'continents-cities');
__('Yakutat', 'continents-cities');
__('Yellowknife', 'continents-cities');
__('Antarctica', 'continents-cities');
__('Casey', 'continents-cities');
__('Davis', 'continents-cities');
__('DumontDUrville', 'continents-cities');
__('Mawson', 'continents-cities');
__('McMurdo', 'continents-cities');
__('Palmer', 'continents-cities');
__('Rothera', 'continents-cities');
__('South Pole', 'continents-cities');
__('Syowa', 'continents-cities');
__('Vostok', 'continents-cities');
__('Arctic', 'continents-cities');
__('Longyearbyen', 'continents-cities');
__('Asia', 'continents-cities');
__('Aden', 'continents-cities');
__('Almaty', 'continents-cities');
__('Amman', 'continents-cities');
__('Anadyr', 'continents-cities');
__('Aqtau', 'continents-cities');
__('Aqtobe', 'continents-cities');
__('Ashgabat', 'continents-cities');
__('Ashkhabad', 'continents-cities');
__('Baghdad', 'continents-cities');
__('Bahrain', 'continents-cities');
__('Baku', 'continents-cities');
__('Bangkok', 'continents-cities');
__('Beirut', 'continents-cities');
__('Bishkek', 'continents-cities');
__('Brunei', 'continents-cities');
__('Calcutta', 'continents-cities');
__('Choibalsan', 'continents-cities');
__('Chongqing', 'continents-cities');
__('Chungking', 'continents-cities');
__('Colombo', 'continents-cities');
__('Dacca', 'continents-cities');
__('Damascus', 'continents-cities');
__('Dhaka', 'continents-cities');
__('Dili', 'continents-cities');
__('Dubai', 'continents-cities');
__('Dushanbe', 'continents-cities');
__('Gaza', 'continents-cities');
__('Harbin', 'continents-cities');
__('Ho Chi Minh', 'continents-cities');
__('Hong Kong', 'continents-cities');
__('Hovd', 'continents-cities');
__('Irkutsk', 'continents-cities');
__('Istanbul', 'continents-cities');
__('Jakarta', 'continents-cities');
__('Jayapura', 'continents-cities');
__('Jerusalem', 'continents-cities');
__('Kabul', 'continents-cities');
__('Kamchatka', 'continents-cities');
__('Karachi', 'continents-cities');
__('Kashgar', 'continents-cities');
__('Katmandu', 'continents-cities');
__('Kolkata', 'continents-cities');
__('Krasnoyarsk', 'continents-cities');
__('Kuala Lumpur', 'continents-cities');
__('Kuching', 'continents-cities');
__('Kuwait', 'continents-cities');
__('Macao', 'continents-cities');
__('Macau', 'continents-cities');
__('Magadan', 'continents-cities');
__('Makassar', 'continents-cities');
__('Manila', 'continents-cities');
__('Muscat', 'continents-cities');
__('Nicosia', 'continents-cities');
__('Novosibirsk', 'continents-cities');
__('Omsk', 'continents-cities');
__('Oral', 'continents-cities');
__('Phnom Penh', 'continents-cities');
__('Pontianak', 'continents-cities');
__('Pyongyang', 'continents-cities');
__('Qatar', 'continents-cities');
__('Qyzylorda', 'continents-cities');
__('Rangoon', 'continents-cities');
__('Riyadh', 'continents-cities');
__('Saigon', 'continents-cities');
__('Sakhalin', 'continents-cities');
__('Samarkand', 'continents-cities');
__('Seoul', 'continents-cities');
__('Shanghai', 'continents-cities');
__('Singapore', 'continents-cities');
__('Taipei', 'continents-cities');
__('Tashkent', 'continents-cities');
__('Tbilisi', 'continents-cities');
__('Tehran', 'continents-cities');
__('Tel Aviv', 'continents-cities');
__('Thimbu', 'continents-cities');
__('Thimphu', 'continents-cities');
__('Tokyo', 'continents-cities');
__('Ujung Pandang', 'continents-cities');
__('Ulaanbaatar', 'continents-cities');
__('Ulan Bator', 'continents-cities');
__('Urumqi', 'continents-cities');
__('Vientiane', 'continents-cities');
__('Vladivostok', 'continents-cities');
__('Yakutsk', 'continents-cities');
__('Yekaterinburg', 'continents-cities');
__('Yerevan', 'continents-cities');
__('Atlantic', 'continents-cities');
__('Azores', 'continents-cities');
__('Bermuda', 'continents-cities');
__('Canary', 'continents-cities');
__('Cape Verde', 'continents-cities');
__('Faeroe', 'continents-cities');
__('Faroe', 'continents-cities');
__('Jan Mayen', 'continents-cities');
__('Madeira', 'continents-cities');
__('Reykjavik', 'continents-cities');
__('South Georgia', 'continents-cities');
__('St Helena', 'continents-cities');
__('Stanley', 'continents-cities');
__('Australia', 'continents-cities');
__('ACT', 'continents-cities');
__('Adelaide', 'continents-cities');
__('Brisbane', 'continents-cities');
__('Broken Hill', 'continents-cities');
__('Canberra', 'continents-cities');
__('Currie', 'continents-cities');
__('Darwin', 'continents-cities');
__('Eucla', 'continents-cities');
__('Hobart', 'continents-cities');
__('LHI', 'continents-cities');
__('Lindeman', 'continents-cities');
__('Lord Howe', 'continents-cities');
__('Melbourne', 'continents-cities');
__('North', 'continents-cities');
__('NSW', 'continents-cities');
__('Perth', 'continents-cities');
__('Queensland', 'continents-cities');
__('South', 'continents-cities');
__('Sydney', 'continents-cities');
__('Tasmania', 'continents-cities');
__('Victoria', 'continents-cities');
__('West', 'continents-cities');
__('Yancowinna', 'continents-cities');
__('Etc', 'continents-cities');
__('GMT', 'continents-cities');
__('GMT+0', 'continents-cities');
__('GMT+1', 'continents-cities');
__('GMT+10', 'continents-cities');
__('GMT+11', 'continents-cities');
__('GMT+12', 'continents-cities');
__('GMT+2', 'continents-cities');
__('GMT+3', 'continents-cities');
__('GMT+4', 'continents-cities');
__('GMT+5', 'continents-cities');
__('GMT+6', 'continents-cities');
__('GMT+7', 'continents-cities');
__('GMT+8', 'continents-cities');
__('GMT+9', 'continents-cities');
__('GMT-0', 'continents-cities');
__('GMT-1', 'continents-cities');
__('GMT-10', 'continents-cities');
__('GMT-11', 'continents-cities');
__('GMT-12', 'continents-cities');
__('GMT-13', 'continents-cities');
__('GMT-14', 'continents-cities');
__('GMT-2', 'continents-cities');
__('GMT-3', 'continents-cities');
__('GMT-4', 'continents-cities');
__('GMT-5', 'continents-cities');
__('GMT-6', 'continents-cities');
__('GMT-7', 'continents-cities');
__('GMT-8', 'continents-cities');
__('GMT-9', 'continents-cities');
__('GMT0', 'continents-cities');
__('Greenwich', 'continents-cities');
__('UCT', 'continents-cities');
__('Universal', 'continents-cities');
__('UTC', 'continents-cities');
__('Zulu', 'continents-cities');
__('Europe', 'continents-cities');
__('Amsterdam', 'continents-cities');
__('Andorra', 'continents-cities');
__('Athens', 'continents-cities');
__('Belfast', 'continents-cities');
__('Belgrade', 'continents-cities');
__('Berlin', 'continents-cities');
__('Bratislava', 'continents-cities');
__('Brussels', 'continents-cities');
__('Bucharest', 'continents-cities');
__('Budapest', 'continents-cities');
__('Chisinau', 'continents-cities');
__('Copenhagen', 'continents-cities');
__('Dublin', 'continents-cities');
__('Gibraltar', 'continents-cities');
__('Guernsey', 'continents-cities');
__('Helsinki', 'continents-cities');
__('Isle of Man', 'continents-cities');
__('Jersey', 'continents-cities');
__('Kaliningrad', 'continents-cities');
__('Kiev', 'continents-cities');
__('Lisbon', 'continents-cities');
__('Ljubljana', 'continents-cities');
__('London', 'continents-cities');
__('Luxembourg', 'continents-cities');
__('Madrid', 'continents-cities');
__('Malta', 'continents-cities');
__('Mariehamn', 'continents-cities');
__('Minsk', 'continents-cities');
__('Monaco', 'continents-cities');
__('Moscow', 'continents-cities');
__('Oslo', 'continents-cities');
__('Paris', 'continents-cities');
__('Podgorica', 'continents-cities');
__('Prague', 'continents-cities');
__('Riga', 'continents-cities');
__('Rome', 'continents-cities');
__('Samara', 'continents-cities');
__('San Marino', 'continents-cities');
__('Sarajevo', 'continents-cities');
__('Simferopol', 'continents-cities');
__('Skopje', 'continents-cities');
__('Sofia', 'continents-cities');
__('Stockholm', 'continents-cities');
__('Tallinn', 'continents-cities');
__('Tirane', 'continents-cities');
__('Tiraspol', 'continents-cities');
__('Uzhgorod', 'continents-cities');
__('Vaduz', 'continents-cities');
__('Vatican', 'continents-cities');
__('Vienna', 'continents-cities');
__('Vilnius', 'continents-cities');
__('Volgograd', 'continents-cities');
__('Warsaw', 'continents-cities');
__('Zagreb', 'continents-cities');
__('Zaporozhye', 'continents-cities');
__('Zurich', 'continents-cities');
__('Indian', 'continents-cities');
__('Antananarivo', 'continents-cities');
__('Chagos', 'continents-cities');
__('Christmas', 'continents-cities');
__('Cocos', 'continents-cities');
__('Comoro', 'continents-cities');
__('Kerguelen', 'continents-cities');
__('Mahe', 'continents-cities');
__('Maldives', 'continents-cities');
__('Mauritius', 'continents-cities');
__('Mayotte', 'continents-cities');
__('Reunion', 'continents-cities');
__('Pacific', 'continents-cities');
__('Apia', 'continents-cities');
__('Auckland', 'continents-cities');
__('Chatham', 'continents-cities');
__('Easter', 'continents-cities');
__('Efate', 'continents-cities');
__('Enderbury', 'continents-cities');
__('Fakaofo', 'continents-cities');
__('Fiji', 'continents-cities');
__('Funafuti', 'continents-cities');
__('Galapagos', 'continents-cities');
__('Gambier', 'continents-cities');
__('Guadalcanal', 'continents-cities');
__('Guam', 'continents-cities');
__('Honolulu', 'continents-cities');
__('Johnston', 'continents-cities');
__('Kiritimati', 'continents-cities');
__('Kosrae', 'continents-cities');
__('Kwajalein', 'continents-cities');
__('Majuro', 'continents-cities');
__('Marquesas', 'continents-cities');
__('Midway', 'continents-cities');
__('Nauru', 'continents-cities');
__('Niue', 'continents-cities');
__('Norfolk', 'continents-cities');
__('Noumea', 'continents-cities');
__('Pago Pago', 'continents-cities');
__('Palau', 'continents-cities');
__('Pitcairn', 'continents-cities');
__('Ponape', 'continents-cities');
__('Port Moresby', 'continents-cities');
__('Rarotonga', 'continents-cities');
__('Saipan', 'continents-cities');
__('Samoa', 'continents-cities');
__('Tahiti', 'continents-cities');
__('Tarawa', 'continents-cities');
__('Tongatapu', 'continents-cities');
__('Truk', 'continents-cities');
__('Wake', 'continents-cities');
__('Wallis', 'continents-cities');
__('Yap', 'continents-cities');
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                           <?php
/**
 * Users List Table class.
 *
 * @since 3.1.0
 * @access private
 *
 * @package WordPress
 * @subpackage List_Table
 */
class WP_Users_List_Table extends WP_List_Table {

	/**
	 * Site ID to generate the Users list table for.
	 *
	 * @since 3.1.0
	 * @access public
	 * @var int
	 */
	public $site_id;

	/**
	 * Whether or not the current Users list table is for Multisite.
	 *
	 * @since 3.1.0
	 * @access public
	 * @var bool
	 */
	public $is_site_users;

	/**
	 * Constructor.
	 *
	 * @since 3.1.0
	 * @access public
	 *
	 * @see WP_List_Table::__construct() for more information on default arguments.
	 *
	 * @param array $args An associative array of arguments.
	 */
	public function __construct( $args = array() ) {
		parent::__construct( array(
			'singular' => 'user',
			'plural'   => 'users',
			'screen'   => isset( $args['screen'] ) ? $args['screen'] : null,
		) );

		$this->is_site_users = 'site-users-network' == $this->screen->id;

		if ( $this->is_site_users )
			$this->site_id = isset( $_REQUEST['id'] ) ? intval( $_REQUEST['id'] ) : 0;
	}

	/**
	 * Check the current user's permissions.
	 *
 	 * @since 3.1.0
	 * @access public
	 *
	 * @return bool
	 */
	public function ajax_user_can() {
		if ( $this->is_site_users )
			return current_user_can( 'manage_sites' );
		else
			return current_user_can( 'list_users' );
	}

	/**
	 * Prepare the users list for display.
	 *
	 * @since 3.1.0
	 * @access public
	 *
	 * @global string $role
	 * @global string $usersearch
	 */
	public function prepare_items() {
		global $role, $usersearch;

		$usersearch = isset( $_REQUEST['s'] ) ? wp_unslash( trim( $_REQUEST['s'] ) ) : '';

		$role = isset( $_REQUEST['role'] ) ? $_REQUEST['role'] : '';

		$per_page = ( $this->is_site_users ) ? 'site_users_network_per_page' : 'users_per_page';
		$users_per_page = $this->get_items_per_page( $per_page );

		$paged = $this->get_pagenum();

		$args = array(
			'number' => $users_per_page,
			'offset' => ( $paged-1 ) * $users_per_page,
			'role' => $role,
			'search' => $usersearch,
			'fields' => 'all_with_meta'
		);

		if ( '' !== $args['search'] )
			$args['search'] = '*' . $args['search'] . '*';

		if ( $this->is_site_users )
			$args['blog_id'] = $this->site_id;

		if ( isset( $_REQUEST['orderby'] ) )
			$args['orderby'] = $_REQUEST['orderby'];

		if ( isset( $_REQUEST['order'] ) )
			$args['order'] = $_REQUEST['order'];

		// Query the user IDs for this page
		$wp_user_search = new WP_User_Query( $args );

		$this->items = $wp_user_search->get_results();

		$this->set_pagination_args( array(
			'total_items' => $wp_user_search->get_total(),
			'per_page' => $users_per_page,
		) );
	}

	/**
	 * Output 'no users' message.
	 *
	 * @since 3.1.0
	 * @access public
	 */
	public function no_items() {
		_e( 'No users found.' );
	}

	/**
	 * Return an associative array listing all the views that can be used
	 * with this table.
	 *
	 * Provides a list of roles and user count for that role for easy
	 * filtering of the user table.
	 *
	 * @since  3.1.0
	 * @access protected
	 *
	 * @global string $role
	 *
	 * @return array An array of HTML links, one for each view.
	 */
	protected function get_views() {
		global $role;

		$wp_roles = wp_roles();

		if ( $this->is_site_users ) {
			$url = 'site-users.php?id=' . $this->site_id;
			switch_to_blog( $this->site_id );
			$users_of_blog = count_users();
			restore_current_blog();
		} else {
			$url = 'users.php';
			$users_of_blog = count_users();
		}
		$total_users = $users_of_blog['total_users'];
		$avail_roles =& $users_of_blog['avail_roles'];
		unset($users_of_blog);

		$class = empty($role) ? ' class="current"' : '';
		$role_links = array();
		$role_links['all'] = "<a href='$url'$class>" . sprintf( _nx( 'All <span class="count">(%s)</span>', 'All <span class="count">(%s)</span>', $total_users, 'users' ), number_format_i18n( $total_users ) ) . '</a>';
		foreach ( $wp_roles->get_names() as $this_role => $name ) {
			if ( !isset($avail_roles[$this_role]) )
				continue;

			$class = '';

			if ( $this_role == $role ) {
				$class = ' class="current"';
			}

			$name = translate_user_role( $name );
			/* translators: User role name with count */
			$name = sprintf( __('%1$s <span class="count">(%2$s)</span>'), $name, number_format_i18n( $avail_roles[$this_role] ) );
			$role_links[$this_role] = "<a href='" . esc_url( add_query_arg( 'role', $this_role, $url ) ) . "'$class>$name</a>";
		}

		return $role_links;
	}

	/**
	 * Retrieve an associative array of bulk actions available on this table.
	 *
	 * @since  3.1.0
	 * @access protected
	 *
	 * @return array Array of bulk actions.
	 */
	protected function get_bulk_actions() {
		$actions = array();

		if ( is_multisite() ) {
			if ( current_user_can( 'remove_users' ) )
				$actions['remove'] = __( 'Remove' );
		} else {
			if ( current_user_can( 'delete_users' ) )
				$actions['delete'] = __( 'Delete' );
		}

		return $actions;
	}

	/**
	 * Output the controls to allow user roles to be changed in bulk.
	 *
	 * @since 3.1.0
	 * @access protected
	 *
	 * @param string $which Whether this is being invoked above ("top")
	 *                      or below the table ("bottom").
	 */
	protected function extra_tablenav( $which ) {
		if ( 'top' != $which )
			return;
	?>
	<div class="alignleft actions">
		<?php if ( current_user_can( 'promote_users' ) ) : ?>
		<label class="screen-reader-text" for="new_role"><?php _e( 'Change role to&hellip;' ) ?></label>
		<select name="new_role" id="new_role">
			<option value=""><?php _e( 'Change role to&hellip;' ) ?></option>
			<?php wp_dropdown_roles(); ?>
		</select>
	<?php
			submit_button( __( 'Change' ), 'button', 'changeit', false );
		endif;

		/**
		 * Fires just before the closing div containing the bulk role-change controls
		 * in the Users list table.
		 *
		 * @since 3.5.0
		 */
		do_action( 'restrict_manage_users' );
		echo '</div>';
	}

	/**
	 * Capture the bulk action required, and return it.
	 *
	 * Overridden from the base class implementation to capture
	 * the role change drop-down.
	 *
	 * @since  3.1.0
	 * @access public
	 *
	 * @return string The bulk action required.
	 */
	public function current_action() {
		if ( isset($_REQUEST['changeit']) && !empty($_REQUEST['new_role']) )
			return 'promote';

		return parent::current_action();
	}

	/**
	 * Get a list of columns for the list table.
	 *
	 * @since  3.1.0
	 * @access public
	 *
	 * @return array Array in which the key is the ID of the column,
	 *               and the value is the description.
	 */
	public function get_columns() {
		$c = array(
			'cb'       => '<input type="checkbox" />',
			'username' => __( 'Username' ),
			'name'     => __( 'Name' ),
			'email'    => __( 'E-mail' ),
			'role'     => __( 'Role' ),
			'posts'    => __( 'Posts' )
		);

		if ( $this->is_site_users )
			unset( $c['posts'] );

		return $c;
	}

	/**
	 * Get a list of sortable columns for the list table.
	 *
	 * @since 3.1.0
	 * @access protected
	 *
	 * @return array Array of sortable columns.
	 */
	protected function get_sortable_columns() {
		$c = array(
			'username' => 'login',
			'name'     => 'name',
			'email'    => 'email',
		);

		if ( $this->is_site_users )
			unset( $c['posts'] );

		return $c;
	}

	/**
	 * Generate the list table rows.
	 *
	 * @since 3.1.0
	 * @access public
	 */
	public function display_rows() {
		// Query the post counts for this page
		if ( ! $this->is_site_users )
			$post_counts = count_many_users_posts( array_keys( $this->items ) );

		$editable_roles = array_keys( get_editable_roles() );

		foreach ( $this->items as $userid => $user_object ) {
			if ( count( $user_object->roles ) <= 1 ) {
				$role = reset( $user_object->roles );
			} elseif ( $roles = array_intersect( array_values( $user_object->roles ), $editable_roles ) ) {
				$role = reset( $roles );
			} else {
				$role = reset( $user_object->roles );
			}

			if ( is_multisite() && empty( $user_object->allcaps ) )
				continue;

			echo "\n\t" . $this->single_row( $user_object, $style = '', $role, isset( $post_counts ) ? $post_counts[ $userid ] : 0 );
		}
	}

	/**
	 * Generate HTML for a single row on the users.php admin panel.
	 *
	 * @since 3.1.0
	 * @since 4.2.0 The `$style` argument was deprecated.
	 * @access public
	 *
	 * @param object $user_object The current user object.
	 * @param string $style       Deprecated. Not used.
	 * @param string $role        Optional. Key for the $wp_roles array. Default empty.
	 * @param int    $numposts    Optional. Post count to display for this user. Defaults
	 *                            to zero, as in, a new user has made zero posts.
	 * @return string Output for a single row.
	 */
	public function single_row( $user_object, $style = '', $role = '', $numposts = 0 ) {
		$wp_roles = wp_roles();

		if ( ! ( $user_object instanceof WP_User ) ) {
			$user_object = get_userdata( (int) $user_object );
		}
		$user_object->filter = 'display';
		$email = $user_object->user_email;

		if ( $this->is_site_users )
			$url = "site-users.php?id={$this->site_id}&amp;";
		else
			$url = 'users.php?';

		$checkbox = '';
		// Check if the user for this row is editable
		if ( current_user_can( 'list_users' ) ) {
			// Set up the user editing link
			$edit_link = esc_url( add_query_arg( 'wp_http_referer', urlencode( wp_unslash( $_SERVER['REQUEST_URI'] ) ), get_edit_user_link( $user_object->ID ) ) );

			// Set up the hover actions for this user
			$actions = array();

			if ( current_user_can( 'edit_user',  $user_object->ID ) ) {
				$edit = "<strong><a href=\"$edit_link\">$user_object->user_login</a></strong><br />";
				$actions['edit'] = '<a href="' . $edit_link . '">' . __( 'Edit' ) . '</a>';
			} else {
				$edit = "<strong>$user_object->user_login</strong><br />";
			}

			if ( !is_multisite() && get_current_user_id() != $user_object->ID && current_user_can( 'delete_user', $user_object->ID ) )
				$actions['delete'] = "<a class='submitdelete' href='" . wp_nonce_url( "users.php?action=delete&amp;user=$user_object->ID", 'bulk-users' ) . "'>" . __( 'Delete' ) . "</a>";
			if ( is_multisite() && get_current_user_id() != $user_object->ID && current_user_can( 'remove_user', $user_object->ID ) )
				$actions['remove'] = "<a class='submitdelete' href='" . wp_nonce_url( $url."action=remove&amp;user=$user_object->ID", 'bulk-users' ) . "'>" . __( 'Remove' ) . "</a>";

			/**
			 * Filter the action links displayed under each user in the Users list table.
			 *
			 * @since 2.8.0
			 *
			 * @param array   $actions     An array of action links to be displayed.
			 *                             Default 'Edit', 'Delete' for single site, and
			 *                             'Edit', 'Remove' for Multisite.
			 * @param WP_User $user_object WP_User object for the currently-listed user.
			 */
			$actions = apply_filters( 'user_row_actions', $actions, $user_object );

			// Set up the checkbox ( because the user is editable, otherwise it's empty )
			$checkbox = '<label class="screen-reader-text" for="user_' . $user_object->ID . '">' . sprintf( __( 'Select %s' ), $user_object->user_login ) . '</label>'
						. "<input type='checkbox' name='users[]' id='user_{$user_object->ID}' class='$role' value='{$user_object->ID}' />";

		} else {
			$edit = '<strong>' . $user_object->user_login . '</strong>';
		}
		$role_name = isset( $wp_roles->role_names[$role] ) ? translate_user_role( $wp_roles->role_names[$role] ) : __( 'None' );
		$avatar = get_avatar( $user_object->ID, 32 );

		$r = "<tr id='user-$user_object->ID'>";

		list( $columns, $hidden, $sortable, $primary ) = $this->get_column_info();

		foreach ( $columns as $column_name => $column_display_name ) {
			$classes = "$column_name column-$column_name";
			if ( $primary === $column_name ) {
				$classes .= ' has-row-actions column-primary';
			}
			if ( 'posts' === $column_name ) {
				$classes .= ' num'; // Special case for that column
			}

			if ( in_array( $column_name, $hidden ) ) {
				$classes .= ' hidden';
			}

			$data = 'data-colname="' . wp_strip_all_tags( $column_display_name ) . '"';

			$attributes = "class='$classes' $data";

			if ( 'cb' === $column_name ) {
				$r .= "<th scope='row' class='check-column'>$checkbox</th>";
			} else {
				$r .= "<td $attributes>";
				switch ( $column_name ) {
					case 'username':
						$r .= "$avatar $edit";
						break;
					case 'name':
						$r .= "$user_object->first_name $user_object->last_name";
						break;
					case 'email':
						$r .= "<a href='" . esc_url( "mailto:$email" ) . "'>$email</a>";
						break;
					case 'role':
						$r .= $role_name;
						break;
					case 'posts':
						if ( $numposts > 0 ) {
							$r .= "<a href='edit.php?author=$user_object->ID' class='edit'>";
							$r .= '<span aria-hidden="true">' . $numposts . '</span>';
							$r .= '<span class="screen-reader-text">' . sprintf( _n( '%s post by this author', '%s posts by this author', $numposts ), number_format_i18n( $numposts ) ) . '</span>';
							$r .= '</a>';
						} else {
							$r .= 0;
						}
						break;
					default:
						/**
						 * Filter the display output of custom columns in the Users list table.
						 *
						 * @since 2.8.0
						 *
						 * @param string $output      Custom column output. Default empty.
						 * @param string $column_name Column name.
						 * @param int    $user_id     ID of the currently-listed user.
						 */
						$r .= apply_filters( 'manage_users_custom_column', '', $column_name, $user_object->ID );
				}

				if ( $primary === $column_name ) {
					$r .= $this->row_actions( $actions );
				}
				$r .= "</td>";
			}
		}
		$r .= '</tr>';

		return $r;
	}

	/**
	 * Gets the name of the default primary column.
	 *
	 * @since 4.3.0
	 * @access protected
	 *
	 * @return string Name of the default primary column, in this case, 'username'.
	 */
	protected function get_default_primary_column_name() {
		return 'username';
	}
}
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    <?php
/**
 * Theme Installer List Table class.
 *
 * @package WordPress
 * @subpackage List_Table
 * @since 3.1.0
 * @access private
 */
class WP_Theme_Install_List_Table extends WP_Themes_List_Table {

	public $features = array();

	/**
	 *
	 * @return bool
	 */
	public function ajax_user_can() {
		return current_user_can( 'install_themes' );
	}

	/**
	 *
	 * @global array  $tabs
	 * @global string $tab
	 * @global int    $paged
	 * @global string $type
	 * @global array  $theme_field_defaults
	 */
	public function prepare_items() {
		include( ABSPATH . 'wp-admin/includes/theme-install.php' );

		global $tabs, $tab, $paged, $type, $theme_field_defaults;
		wp_reset_vars( array( 'tab' ) );

		$search_terms = array();
		$search_string = '';
		if ( ! empty( $_REQUEST['s'] ) ){
			$search_string = strtolower( wp_unslash( $_REQUEST['s'] ) );
			$search_terms = array_unique( array_filter( array_map( 'trim', explode( ',', $search_string ) ) ) );
		}

		if ( ! empty( $_REQUEST['features'] ) )
			$this->features = $_REQUEST['features'];

		$paged = $this->get_pagenum();

		$per_page = 36;

		// These are the tabs which are shown on the page,
		$tabs = array();
		$tabs['dashboard'] = __( 'Search' );
		if ( 'search' == $tab )
			$tabs['search']	= __( 'Search Results' );
		$tabs['upload'] = __( 'Upload' );
		$tabs['featured'] = _x( 'Featured', 'themes' );
		//$tabs['popular']  = _x( 'Popular', 'themes' );
		$tabs['new']      = _x( 'Latest', 'themes' );
		$tabs['updated']  = _x( 'Recently Updated', 'themes' );

		$nonmenu_tabs = array( 'theme-information' ); // Valid actions to perform which do not have a Menu item.

		/** This filter is documented in wp-admin/theme-install.php */
		$tabs = apply_filters( 'install_themes_tabs', $tabs );

		/**
		 * Filter tabs not associated with a menu item on the Install Themes screen.
		 *
		 * @since 2.8.0
		 *
		 * @param array $nonmenu_tabs The tabs that don't have a menu item on
		 *                            the Install Themes screen.
		 */
		$nonmenu_tabs = apply_filters( 'install_themes_nonmenu_tabs', $nonmenu_tabs );

		// If a non-valid menu tab has been selected, And it's not a non-menu action.
		if ( empty( $tab ) || ( ! isset( $tabs[ $tab ] ) && ! in_array( $tab, (array) $nonmenu_tabs ) ) )
			$tab = key( $tabs );

		$args = array( 'page' => $paged, 'per_page' => $per_page, 'fields' => $theme_field_defaults );

		switch ( $tab ) {
			case 'search':
				$type = isset( $_REQUEST['type'] ) ? wp_unslash( $_REQUEST['type'] ) : 'term';
				switch ( $type ) {
					case 'tag':
						$args['tag'] = array_map( 'sanitize_key', $search_terms );
						break;
					case 'term':
						$args['search'] = $search_string;
						break;
					case 'author':
						$args['author'] = $search_string;
						break;
				}

				if ( ! empty( $this->features ) ) {
					$args['tag'] = $this->features;
					$_REQUEST['s'] = implode( ',', $this->features );
					$_REQUEST['type'] = 'tag';
				}

				add_action( 'install_themes_table_header', 'install_theme_search_form', 10, 0 );
				break;

			case 'featured':
			// case 'popular':
			case 'new':
			case 'updated':
				$args['browse'] = $tab;
				break;

			default:
				$args = false;
				break;
		}

		/**
		 * Filter API request arguments for each Install Themes screen tab.
		 *
		 * The dynamic portion of the hook name, `$tab`, refers to the theme install
		 * tabs. Default tabs are 'dashboard', 'search', 'upload', 'featured',
		 * 'new', and 'updated'.
		 *
		 * @since 3.7.0
		 *
		 * @param array $args An array of themes API arguments.
		 */
		$args = apply_filters( 'install_themes_table_api_args_' . $tab, $args );

		if ( ! $args )
			return;

		$api = themes_api( 'query_themes', $args );

		if ( is_wp_error( $api ) )
			wp_die( $api->get_error_message() . '</p> <p><a href="#" onclick="document.location.reload(); return false;">' . __( 'Try again' ) . '</a>' );

		$this->items = $api->themes;

		$this->set_pagination_args( array(
			'total_items' => $api->info['results'],
			'per_page' => $args['per_page'],
			'infinite_scroll' => true,
		) );
	}

	/**
	 * @access public
	 */
	public function no_items() {
		_e( 'No themes match your request.' );
	}

	/**
	 *
	 * @global array $tabs
	 * @global string $tab
	 * @return array
	 */
	protected function get_views() {
		global $tabs, $tab;

		$display_tabs = array();
		foreach ( (array) $tabs as $action => $text ) {
			$class = ( $action == $tab ) ? ' class="current"' : '';
			$href = self_admin_url('theme-install.php?tab=' . $action);
			$display_tabs['theme-install-'.$action] = "<a href='$href'$class>$text</a>";
		}

		return $display_tabs;
	}

	/**
	 * @access public
	 */
	public function display() {
		wp_nonce_field( "fetch-list-" . get_class( $this ), '_ajax_fetch_list_nonce' );
?>
		<div class="tablenav top themes">
			<div class="alignleft actions">
				<?php
				/**
				 * Fires in the Install Themes list table header.
				 *
				 * @since 2.8.0
				 */
				do_action( 'install_themes_table_header' );
				?>
			</div>
			<?php $this->pagination( 'top' ); ?>
			<br class="clear" />
		</div>

		<div id="availablethemes">
			<?php $this->display_rows_or_placeholder(); ?>
		</div>

		<?php
		$this->tablenav( 'bottom' );
	}

	/**
	 * @access public
	 */
	public function display_rows() {
		$themes = $this->items;
		foreach ( $themes as $theme ) {
				?>
				<div class="available-theme installable-theme"><?php
					$this->single_row( $theme );
				?></div>
		<?php } // end foreach $theme_names

		$this->theme_installer();
	}

	/**
	 * Prints a theme from the WordPress.org API.
	 *
	 * @global array $themes_allowedtags
	 *
	 * @param object $theme An object that contains theme data returned by the WordPress.org API.
	 *
	 * Example theme data:
	 *   object(stdClass)[59]
	 *     public 'name' => string 'Magazine Basic'
	 *     public 'slug' => string 'magazine-basic'
	 *     public 'version' => string '1.1'
	 *     public 'author' => string 'tinkerpriest'
	 *     public 'preview_url' => string 'http://wp-themes.com/?magazine-basic'
	 *     public 'screenshot_url' => string 'http://wp-themes.com/wp-content/themes/magazine-basic/screenshot.png'
	 *     public 'rating' => float 80
	 *     public 'num_ratings' => int 1
	 *     public 'homepage' => string 'http://wordpress.org/themes/magazine-basic'
	 *     public 'description' => string 'A basic magazine style layout with a fully customizable layout through a backend interface. Designed by <a href="http://bavotasan.com">c.bavota</a> of <a href="http://tinkerpriestmedia.com">Tinker Priest Media</a>.'
	 *     public 'download_link' => string 'http://wordpress.org/themes/download/magazine-basic.1.1.zip'
	 */
	public function single_row( $theme ) {
		global $themes_allowedtags;

		if ( empty( $theme ) )
			return;

		$name   = wp_kses( $theme->name,   $themes_allowedtags );
		$author = wp_kses( $theme->author, $themes_allowedtags );

		$preview_title = sprintf( __('Preview &#8220;%s&#8221;'), $name );
		$preview_url   = add_query_arg( array(
			'tab'   => 'theme-information',
			'theme' => $theme->slug,
		), self_admin_url( 'theme-install.php' ) );

		$actions = array();

		$install_url = add_query_arg( array(
			'action' => 'install-theme',
			'theme'  => $theme->slug,
		), self_admin_url( 'update.php' ) );

		$update_url = add_query_arg( array(
			'action' => 'upgrade-theme',
			'theme'  => $theme->slug,
		), self_admin_url( 'update.php' ) );

		$status = $this->_get_theme_status( $theme );

		switch ( $status ) {
			case 'update_available':
				$actions[] = '<a class="install-now" href="' . esc_url( wp_nonce_url( $update_url, 'upgrade-theme_' . $theme->slug ) ) . '" title="' . esc_attr( sprintf( __( 'Update to version %s' ), $theme->version ) ) . '">' . __( 'Update' ) . '</a>';
				break;
			case 'newer_installed':
			case 'latest_installed':
				$actions[] = '<span class="install-now" title="' . esc_attr__( 'This theme is already installed and is up to date' ) . '">' . _x( 'Installed', 'theme' ) . '</span>';
				break;
			case 'install':
			default:
				$actions[] = '<a class="install-now" href="' . esc_url( wp_nonce_url( $install_url, 'install-theme_' . $theme->slug ) ) . '" title="' . esc_attr( sprintf( __( 'Install %s' ), $name ) ) . '">' . __( 'Install Now' ) . '</a>';
				break;
		}

		$actions[] = '<a class="install-theme-preview" href="' . esc_url( $preview_url ) . '" title="' . esc_attr( sprintf( __( 'Preview %s' ), $name ) ) . '">' . __( 'Preview' ) . '</a>';

		/**
		 * Filter the install action links for a theme in the Install Themes list table.
		 *
		 * @since 3.4.0
		 *
		 * @param array    $actions An array of theme action hyperlinks. Defaults are
		 *                          links to Install Now, Preview, and Details.
		 * @param WP_Theme $theme   Theme object.
		 */
		$actions = apply_filters( 'theme_install_actions', $actions, $theme );

		?>
		<a class="screenshot install-theme-preview" href="<?php echo esc_url( $preview_url ); ?>" title="<?php echo esc_attr( $preview_title ); ?>">
			<img src="<?php echo esc_url( $theme->screenshot_url ); ?>" width="150" />
		</a>

		<h3><?php echo $name; ?></h3>
		<div class="theme-author"><?php printf( __( 'By %s' ), $author ); ?></div>

		<div class="action-links">
			<ul>
				<?php foreach ( $actions as $action ): ?>
					<li><?php echo $action; ?></li>
				<?php endforeach; ?>
				<li class="hide-if-no-js"><a href="#" class="theme-detail"><?php _e('Details') ?></a></li>
			</ul>
		</div>

		<?php
		$this->install_theme_info( $theme );
	}

	/**
	 * Prints the wrapper for the theme installer.
	 */
	public function theme_installer() {
		?>
		<div id="theme-installer" class="wp-full-overlay expanded">
			<div class="wp-full-overlay-sidebar">
				<div class="wp-full-overlay-header">
					<a href="#" class="close-full-overlay button-secondary"><?php _e( 'Close' ); ?></a>
					<span class="theme-install"></span>
				</div>
				<div class="wp-full-overlay-sidebar-content">
					<div class="install-theme-info"></div>
				</div>
				<div class="wp-full-overlay-footer">
					<button type="button" class="collapse-sidebar button-secondary" aria-expanded="true" aria-label="<?php esc_attr_e( 'Collapse Sidebar' ); ?>">
						<span class="collapse-sidebar-arrow"></span>
						<span class="collapse-sidebar-label"><?php _e( 'Collapse' ); ?></span>
					</button>
				</div>
			</div>
			<div class="wp-full-overlay-main"></div>
		</div>
		<?php
	}

	/**
	 * Prints the wrapper for the theme installer with a provided theme's data.
	 * Used to make the theme installer work for no-js.
	 *
	 * @param object $theme - A WordPress.org Theme API object.
	 */
	public function theme_installer_single( $theme ) {
		?>
		<div id="theme-installer" class="wp-full-overlay single-theme">
			<div class="wp-full-overlay-sidebar">
				<?php $this->install_theme_info( $theme ); ?>
			</div>
			<div class="wp-full-overlay-main">
				<iframe src="<?php echo esc_url( $theme->preview_url ); ?>"></iframe>
			</div>
		</div>
		<?php
	}

	/**
	 * Prints the info for a theme (to be used in the theme installer modal).
	 *
	 * @global array $themes_allowedtags
	 *
	 * @param object $theme - A WordPress.org Theme API object.
	 */
	public function install_theme_info( $theme ) {
		global $themes_allowedtags;

		if ( empty( $theme ) )
			return;

		$name   = wp_kses( $theme->name,   $themes_allowedtags );
		$author = wp_kses( $theme->author, $themes_allowedtags );

		$install_url = add_query_arg( array(
			'action' => 'install-theme',
			'theme'  => $theme->slug,
		), self_admin_url( 'update.php' ) );

		$update_url = add_query_arg( array(
			'action' => 'upgrade-theme',
			'theme'  => $theme->slug,
		), self_admin_url( 'update.php' ) );

		$status = $this->_get_theme_status( $theme );

		?>
		<div class="install-theme-info"><?php
			switch ( $status ) {
				case 'update_available':
					echo '<a class="theme-install button-primary" href="' . esc_url( wp_nonce_url( $update_url, 'upgrade-theme_' . $theme->slug ) ) . '" title="' . esc_attr( sprintf( __( 'Update to version %s' ), $theme->version ) ) . '">' . __( 'Update' ) . '</a>';
					break;
				case 'newer_installed':
				case 'latest_installed':
					echo '<span class="theme-install" title="' . esc_attr__( 'This theme is already installed and is up to date' ) . '">' . _x( 'Installed', 'theme' ) . '</span>';
					break;
				case 'install':
				default:
					echo '<a class="theme-install button-primary" href="' . esc_url( wp_nonce_url( $install_url, 'install-theme_' . $theme->slug ) ) . '">' . __( 'Install' ) . '</a>';
					break;
			} ?>
			<h3 class="theme-name"><?php echo $name; ?></h3>
			<span class="theme-by"><?php printf( __( 'By %s' ), $author ); ?></span>
			<?php if ( isset( $theme->screenshot_url ) ): ?>
				<img class="theme-screenshot" src="<?php echo esc_url( $theme->screenshot_url ); ?>" />
			<?php endif; ?>
			<div class="theme-details">
				<?php wp_star_rating( array( 'rating' => $theme->rating, 'type' => 'percent', 'number' => $theme->num_ratings ) ); ?>
				<div class="theme-version">
					<strong><?php _e('Version:') ?> </strong>
					<?php echo wp_kses( $theme->version, $themes_allowedtags ); ?>
				</div>
				<div class="theme-description">
					<?php echo wp_kses( $theme->description, $themes_allowedtags ); ?>
				</div>
			</div>
			<input class="theme-preview-url" type="hidden" value="<?php echo esc_url( $theme->preview_url ); ?>" />
		</div>
		<?php
	}

	/**
	 * Send required variables to JavaScript land
	 *
	 * @since 3.4.0
	 * @access public
	 *
	 * @global string $tab  Current tab within Themes->Install screen
	 * @global string $type Type of search.
	 *
	 * @param array $extra_args Unused.
	 */
	public function _js_vars( $extra_args = array() ) {
		global $tab, $type;
		parent::_js_vars( compact( 'tab', 'type' ) );
	}

	/**
	 * Check to see if the theme is already installed.
	 *
	 * @since 3.4.0
	 * @access private
	 *
	 * @param object $theme - A WordPress.org Theme API object.
	 * @return string Theme status.
	 */
	private function _get_theme_status( $theme ) {
		$status = 'install';

		$installed_theme = wp_get_theme( $theme->slug );
		if ( $installed_theme->exists() ) {
			if ( version_compare( $installed_theme->get('Version'), $theme->version, '=' ) )
				$status = 'latest_installed';
			elseif ( version_compare( $installed_theme->get('Version'), $theme->version, '>' ) )
				$status = 'newer_installed';
			else
				$status = 'update_available';
		}

		return $status;
	}
}
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                           <?php
/**
 * Terms List Table class.
 *
 * @package WordPress
 * @subpackage List_Table
 * @since 3.1.0
 * @access private
 */
class WP_Terms_List_Table extends WP_List_Table {

	public $callback_args;

	private $level;

	/**
	 * Constructor.
	 *
	 * @since 3.1.0
	 * @access public
	 *
	 * @see WP_List_Table::__construct() for more information on default arguments.
	 *
	 * @global string $post_type
	 * @global string $taxonomy
	 * @global string $action
	 * @global object $tax
	 *
	 * @param array $args An associative array of arguments.
	 */
	public function __construct( $args = array() ) {
		global $post_type, $taxonomy, $action, $tax;

		parent::__construct( array(
			'plural' => 'tags',
			'singular' => 'tag',
			'screen' => isset( $args['screen'] ) ? $args['screen'] : null,
		) );

		$action    = $this->screen->action;
		$post_type = $this->screen->post_type;
		$taxonomy  = $this->screen->taxonomy;

		if ( empty( $taxonomy ) )
			$taxonomy = 'post_tag';

		if ( ! taxonomy_exists( $taxonomy ) )
			wp_die( __( 'Invalid taxonomy' ) );

		$tax = get_taxonomy( $taxonomy );

		// @todo Still needed? Maybe just the show_ui part.
		if ( empty( $post_type ) || !in_array( $post_type, get_post_types( array( 'show_ui' => true ) ) ) )
			$post_type = 'post';

	}

	/**
	 *
	 * @return bool
	 */
	public function ajax_user_can() {
		return current_user_can( get_taxonomy( $this->screen->taxonomy )->cap->manage_terms );
	}

	/**
	 * @access public
	 */
	public function prepare_items() {
		$tags_per_page = $this->get_items_per_page( 'edit_' . $this->screen->taxonomy . '_per_page' );

		if ( 'post_tag' == $this->screen->taxonomy ) {
			/**
			 * Filter the number of terms displayed per page for the Tags list table.
			 *
			 * @since 2.8.0
			 *
			 * @param int $tags_per_page Number of tags to be displayed. Default 20.
			 */
			$tags_per_page = apply_filters( 'edit_tags_per_page', $tags_per_page );

			/**
			 * Filter the number of terms displayed per page for the Tags list table.
			 *
			 * @since 2.7.0
			 * @deprecated 2.8.0 Use edit_tags_per_page instead.
			 *
			 * @param int $tags_per_page Number of tags to be displayed. Default 20.
			 */
			$tags_per_page = apply_filters( 'tagsperpage', $tags_per_page );
		} elseif ( 'category' == $this->screen->taxonomy ) {
			/**
			 * Filter the number of terms displayed per page for the Categories list table.
			 *
			 * @since 2.8.0
			 *
			 * @param int $tags_per_page Number of categories to be displayed. Default 20.
			 */
			$tags_per_page = apply_filters( 'edit_categories_per_page', $tags_per_page );
		}

		$search = !empty( $_REQUEST['s'] ) ? trim( wp_unslash( $_REQUEST['s'] ) ) : '';

		$args = array(
			'search' => $search,
			'page' => $this->get_pagenum(),
			'number' => $tags_per_page,
		);

		if ( !empty( $_REQUEST['orderby'] ) )
			$args['orderby'] = trim( wp_unslash( $_REQUEST['orderby'] ) );

		if ( !empty( $_REQUEST['order'] ) )
			$args['order'] = trim( wp_unslash( $_REQUEST['order'] ) );

		$this->callback_args = $args;

		$this->set_pagination_args( array(
			'total_items' => wp_count_terms( $this->screen->taxonomy, compact( 'search' ) ),
			'per_page' => $tags_per_page,
		) );
	}

	/**
	 *
	 * @return bool
	 */
	public function has_items() {
		// todo: populate $this->items in prepare_items()
		return true;
	}

	/**
	 * @access public
	 */
	public function no_items() {
		echo get_taxonomy( $this->screen->taxonomy )->labels->not_found;
	}

	/**
	 *
	 * @return array
	 */
	protected function get_bulk_actions() {
		$actions = array();
		$actions['delete'] = __( 'Delete' );

		return $actions;
	}

	/**
	 *
	 * @return string
	 */
	public function current_action() {
		if ( isset( $_REQUEST['action'] ) && isset( $_REQUEST['delete_tags'] ) && ( 'delete' == $_REQUEST['action'] || 'delete' == $_REQUEST['action2'] ) )
			return 'bulk-delete';

		return parent::current_action();
	}

	/**
	 *
	 * @return array
	 */
	public function get_columns() {
		$columns = array(
			'cb'          => '<input type="checkbox" />',
			'name'        => _x( 'Name', 'term name' ),
			'description' => __( 'Description' ),
			'slug'        => __( 'Slug' ),
		);

		if ( 'link_category' == $this->screen->taxonomy ) {
			$columns['links'] = __( 'Links' );
		} else {
			$columns['posts'] = _x( 'Count', 'Number/count of items' );
		}

		return $columns;
	}

	/**
	 *
	 * @return array
	 */
	protected function get_sortable_columns() {
		return array(
			'name'        => 'name',
			'description' => 'description',
			'slug'        => 'slug',
			'posts'       => 'count',
			'links'       => 'count'
		);
	}

	/**
	 * @access public
	 */
	public function display_rows_or_placeholder() {
		$taxonomy = $this->screen->taxonomy;

		$args = wp_parse_args( $this->callback_args, array(
			'page' => 1,
			'number' => 20,
			'search' => '',
			'hide_empty' => 0
		) );

		$page = $args['page'];

		// Set variable because $args['number'] can be subsequently overridden.
		$number = $args['number'];

		$args['offset'] = $offset = ( $page - 1 ) * $number;

		// Convert it to table rows.
		$count = 0;

		if ( is_taxonomy_hierarchical( $taxonomy ) && ! isset( $args['orderby'] ) ) {
			// We'll need the full set of terms then.
			$args['number'] = $args['offset'] = 0;
		}
		$terms = get_terms( $taxonomy, $args );

		if ( empty( $terms ) || ! is_array( $terms ) ) {
			echo '<tr class="no-items"><td class="colspanchange" colspan="' . $this->get_column_count() . '">';
			$this->no_items();
			echo '</td></tr>';
			return;
		}

		if ( is_taxonomy_hierarchical( $taxonomy ) && ! isset( $args['orderby'] ) ) {
			if ( ! empty( $args['search'] ) ) {// Ignore children on searches.
				$children = array();
			} else {
				$children = _get_term_hierarchy( $taxonomy );
			}
			// Some funky recursion to get the job done( Paging & parents mainly ) is contained within, Skip it for non-hierarchical taxonomies for performance sake
			$this->_rows( $taxonomy, $terms, $children, $offset, $number, $count );
		} else {
			foreach ( $terms as $term ) {
				$this->single_row( $term );
			}
		}
	}

	/**
	 * @param string $taxonomy
	 * @param array $terms
	 * @param array $children
	 * @param int   $start
	 * @param int   $per_page
	 * @param int   $count
	 * @param int   $parent
	 * @param int   $level
	 */
	private function _rows( $taxonomy, $terms, &$children, $start, $per_page, &$count, $parent = 0, $level = 0 ) {

		$end = $start + $per_page;

		foreach ( $terms as $key => $term ) {

			if ( $count >= $end )
				break;

			if ( $term->parent != $parent && empty( $_REQUEST['s'] ) )
				continue;

			// If the page starts in a subtree, print the parents.
			if ( $count == $start && $term->parent > 0 && empty( $_REQUEST['s'] ) ) {
				$my_parents = $parent_ids = array();
				$p = $term->parent;
				while ( $p ) {
					$my_parent = get_term( $p, $taxonomy );
					$my_parents[] = $my_parent;
					$p = $my_parent->parent;
					if ( in_array( $p, $parent_ids ) ) // Prevent parent loops.
						break;
					$parent_ids[] = $p;
				}
				unset( $parent_ids );

				$num_parents = count( $my_parents );
				while ( $my_parent = array_pop( $my_parents ) ) {
					echo "\t";
					$this->single_row( $my_parent, $level - $num_parents );
					$num_parents--;
				}
			}

			if ( $count >= $start ) {
				echo "\t";
				$this->single_row( $term, $level );
			}

			++$count;

			unset( $terms[$key] );

			if ( isset( $children[$term->term_id] ) && empty( $_REQUEST['s'] ) )
				$this->_rows( $taxonomy, $terms, $children, $start, $per_page, $count, $term->term_id, $level + 1 );
		}
	}

	/**
	 * @global string $taxonomy
	 * @param object $tag
	 * @param int $level
	 */
	public function single_row( $tag, $level = 0 ) {
		global $taxonomy;
 		$tag = sanitize_term( $tag, $taxonomy );

		$this->level = $level;

		echo '<tr id="tag-' . $tag->term_id . '">';
		$this->single_row_columns( $tag );
		echo '</tr>';
	}

	/**
	 * @param object $tag
	 * @return string
	 */
	public function column_cb( $tag ) {
		$default_term = get_option( 'default_' . $this->screen->taxonomy );

		if ( current_user_can( get_taxonomy( $this->screen->taxonomy )->cap->delete_terms ) && $tag->term_id != $default_term )
			return '<label class="screen-reader-text" for="cb-select-' . $tag->term_id . '">' . sprintf( __( 'Select %s' ), $tag->name ) . '</label>'
				. '<input type="checkbox" name="delete_tags[]" value="' . $tag->term_id . '" id="cb-select-' . $tag->term_id . '" />';

		return '&nbsp;';
	}

	/**
	 * @param object $tag
	 * @return string
	 */
	public function column_name( $tag ) {
		$taxonomy = $this->screen->taxonomy;

		$pad = str_repeat( '&#8212; ', max( 0, $this->level ) );

		/**
		 * Filter display of the term name in the terms list table.
		 *
		 * The default output may include padding due to the term's
		 * current level in the term hierarchy.
		 *
		 * @since 2.5.0
		 *
		 * @see WP_Terms_List_Table::column_name()
		 *
		 * @param string $pad_tag_name The term name, padded if not top-level.
		 * @param object $tag          Term object.
		 */
		$name = apply_filters( 'term_name', $pad . ' ' . $tag->name, $tag );

		$qe_data = get_term( $tag->term_id, $taxonomy, OBJECT, 'edit' );
		$edit_link = esc_url( get_edit_term_link( $tag->term_id, $taxonomy, $this->screen->post_type ) );

		$out = '<strong><a class="row-title" href="' . $edit_link . '" title="' . esc_attr( sprintf( __( 'Edit &#8220;%s&#8221;' ), $name ) ) . '">' . $name . '</a></strong><br />';

		$out .= '<div class="hidden" id="inline_' . $qe_data->term_id . '">';
		$out .= '<div class="name">' . $qe_data->name . '</div>';

		/** This filter is documented in wp-admin/edit-tag-form.php */
		$out .= '<div class="slug">' . apply_filters( 'editable_slug', $qe_data->slug ) . '</div>';
		$out .= '<div class="parent">' . $qe_data->parent . '</div></div>';

		return $out;
	}

	/**
	 * Gets the name of the default primary column.
	 *
	 * @since 4.3.0
	 * @access protected
	 *
	 * @return string Name of the default primary column, in this case, 'name'.
	 */
	protected function get_default_primary_column_name() {
		return 'name';
	}

	/**
	 * Generates and displays row action links.
	 *
	 * @since 4.3.0
	 * @access protected
	 *
	 * @param object $tag         Tag being acted upon.
	 * @param string $column_name Current column name.
	 * @param string $primary     Primary column name.
	 * @return string Row actions output for terms.
	 */
	protected function handle_row_actions( $tag, $column_name, $primary ) {
		if ( $primary !== $column_name ) {
			return '';
		}

		$taxonomy = $this->screen->taxonomy;
		$tax = get_taxonomy( $taxonomy );
		$default_term = get_option( 'default_' . $taxonomy );

		$edit_link = esc_url( get_edit_term_link( $tag->term_id, $taxonomy, $this->screen->post_type ) );

		$actions = array();
		if ( current_user_can( $tax->cap->edit_terms ) ) {
			$actions['edit'] = '<a href="' . $edit_link . '">' . __( 'Edit' ) . '</a>';
			$actions['inline hide-if-no-js'] = '<a href="#" class="editinline">' . __( 'Quick&nbsp;Edit' ) . '</a>';
		}
		if ( current_user_can( $tax->cap->delete_terms ) && $tag->term_id != $default_term )
			$actions['delete'] = "<a class='delete-tag' href='" . wp_nonce_url( "edit-tags.php?action=delete&amp;taxonomy=$taxonomy&amp;tag_ID=$tag->term_id", 'delete-tag_' . $tag->term_id ) . "'>" . __( 'Delete' ) . "</a>";
		if ( $tax->public )
			$actions['view'] = '<a href="' . get_term_link( $tag ) . '">' . __( 'View' ) . '</a>';

		/**
		 * Filter the action links displayed for each term in the Tags list table.
		 *
		 * @since 2.8.0
		 * @deprecated 3.0.0 Use {$taxonomy}_row_actions instead.
		 *
		 * @param array  $actions An array of action links to be displayed. Default
		 *                        'Edit', 'Quick Edit', 'Delete', and 'View'.
		 * @param object $tag     Term object.
		 */
		$actions = apply_filters( 'tag_row_actions', $actions, $tag );

		/**
		 * Filter the action links displayed for each term in the terms list table.
		 *
		 * The dynamic portion of the hook name, `$taxonomy`, refers to the taxonomy slug.
		 *
		 * @since 3.0.0
		 *
		 * @param array  $actions An array of action links to be displayed. Default
		 *                        'Edit', 'Quick Edit', 'Delete', and 'View'.
		 * @param object $tag     Term object.
		 */
		$actions = apply_filters( "{$taxonomy}_row_actions", $actions, $tag );

		return $this->row_actions( $actions );
	}

	/**
	 * @param object $tag
	 * @return string
	 */
	public function column_description( $tag ) {
		return $tag->description;
	}

	/**
	 * @param object $tag
	 * @return string
	 */
	public function column_slug( $tag ) {
		/** This filter is documented in wp-admin/edit-tag-form.php */
		return apply_filters( 'editable_slug', $tag->slug );
	}

	/**
	 * @param object $tag
	 * @return string
	 */
	public function column_posts( $tag ) {
		$count = number_format_i18n( $tag->count );

		$tax = get_taxonomy( $this->screen->taxonomy );

		$ptype_object = get_post_type_object( $this->screen->post_type );
		if ( ! $ptype_object->show_ui )
			return $count;

		if ( $tax->query_var ) {
			$args = array( $tax->query_var => $tag->slug );
		} else {
			$args = array( 'taxonomy' => $tax->name, 'term' => $tag->slug );
		}

		if ( 'post' != $this->screen->post_type )
			$args['post_type'] = $this->screen->post_type;

		if ( 'attachment' == $this->screen->post_type )
			return "<a href='" . esc_url ( add_query_arg( $args, 'upload.php' ) ) . "'>$count</a>";

		return "<a href='" . esc_url ( add_query_arg( $args, 'edit.php' ) ) . "'>$count</a>";
	}

	/**
	 * @param object $tag
	 * @return string
	 */
	public function column_links( $tag ) {
		$count = number_format_i18n( $tag->count );
		if ( $count )
			$count = "<a href='link-manager.php?cat_id=$tag->term_id'>$count</a>";
		return $count;
	}

	/**
	 * @param object $tag
	 * @param string $column_name
	 * @return string
	 */
	public function column_default( $tag, $column_name ) {
		/**
		 * Filter the displayed columns in the terms list table.
		 *
		 * The dynamic portion of the hook name, `$this->screen->taxonomy`,
		 * refers to the slug of the current taxonomy.
		 *
		 * @since 2.8.0
		 *
		 * @param string $string      Blank string.
		 * @param string $column_name Name of the column.
		 * @param int    $term_id     Term ID.
		 */
		return apply_filters( "manage_{$this->screen->taxonomy}_custom_column", '', $column_name, $tag->term_id );
	}

	/**
	 * Outputs the hidden row displayed when inline editing
	 *
	 * @since 3.1.0
	 */
	public function inline_edit() {
		$tax = get_taxonomy( $this->screen->taxonomy );

		if ( ! current_user_can( $tax->cap->edit_terms ) )
			return;
?>

	<form method="get"><table style="display: none"><tbody id="inlineedit">
		<tr id="inline-edit" class="inline-edit-row" style="display: none"><td colspan="<?php echo $this->get_column_count(); ?>" class="colspanchange">

			<fieldset><div class="inline-edit-col">
				<h4><?php _e( 'Quick Edit' ); ?></h4>

				<label>
					<span class="title"><?php _ex( 'Name', 'term name' ); ?></span>
					<span class="input-text-wrap"><input type="text" name="name" class="ptitle" value="" /></span>
				</label>
	<?php if ( !global_terms_enabled() ) { ?>
				<label>
					<span class="title"><?php _e( 'Slug' ); ?></span>
					<span class="input-text-wrap"><input type="text" name="slug" class="ptitle" value="" /></span>
				</label>
	<?php } ?>
			</div></fieldset>
	<?php

		$core_columns = array( 'cb' => true, 'description' => true, 'name' => true, 'slug' => true, 'posts' => true );

		list( $columns ) = $this->get_column_info();

		foreach ( $columns as $column_name => $column_display_name ) {
			if ( isset( $core_columns[$column_name] ) )
				continue;

			/** This action is documented in wp-admin/includes/class-wp-posts-list-table.php */
			do_action( 'quick_edit_custom_box', $column_name, 'edit-tags', $this->screen->taxonomy );
		}

	?>

		<p class="inline-edit-save submit">
			<a href="#inline-edit" class="cancel button-secondary alignleft"><?php _e( 'Cancel' ); ?></a>
			<a href="#inline-edit" class="save button-primary alignright"><?php echo $tax->labels->update_item; ?></a>
			<span class="spinner"></span>
			<span class="error" style="display:none;"></span>
			<?php wp_nonce_field( 'taxinlineeditnonce', '_inline_edit', false ); ?>
			<input type="hidden" name="taxonomy" value="<?php echo esc_attr( $this->screen->taxonomy ); ?>" />
			<input type="hidden" name="post_type" value="<?php echo esc_attr( $this->screen->post_type ); ?>" />
			<br class="clear" />
		</p>
		</td></tr>
		</tbody></table></form>
	<?php
	}
}
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          <?php
/**
 * Plugins List Table class.
 *
 * @package WordPress
 * @subpackage List_Table
 * @since 3.1.0
 * @access private
 */
class WP_Plugins_List_Table extends WP_List_Table {

	/**
	 * Constructor.
	 *
	 * @since 3.1.0
	 * @access public
	 *
	 * @see WP_List_Table::__construct() for more information on default arguments.
	 *
	 * @global string $status
	 * @global int    $page
	 *
	 * @param array $args An associative array of arguments.
	 */
	public function __construct( $args = array() ) {
		global $status, $page;

		parent::__construct( array(
			'plural' => 'plugins',
			'screen' => isset( $args['screen'] ) ? $args['screen'] : null,
		) );

		$status = 'all';
		if ( isset( $_REQUEST['plugin_status'] ) && in_array( $_REQUEST['plugin_status'], array( 'active', 'inactive', 'recently_activated', 'upgrade', 'mustuse', 'dropins', 'search' ) ) )
			$status = $_REQUEST['plugin_status'];

		if ( isset($_REQUEST['s']) )
			$_SERVER['REQUEST_URI'] = add_query_arg('s', wp_unslash($_REQUEST['s']) );

		$page = $this->get_pagenum();
	}

	/**
	 * @return array
	 */
	protected function get_table_classes() {
		return array( 'widefat', $this->_args['plural'] );
	}

	/**
	 * @return bool
	 */
	public function ajax_user_can() {
		return current_user_can('activate_plugins');
	}

	/**
	 *
	 * @global string $status
	 * @global type   $plugins
	 * @global array  $totals
	 * @global int    $page
	 * @global string $orderby
	 * @global string $order
	 * @global string $s
	 */
	public function prepare_items() {
		global $status, $plugins, $totals, $page, $orderby, $order, $s;

		wp_reset_vars( array( 'orderby', 'order', 's' ) );

		/**
		 * Filter the full array of plugins to list in the Plugins list table.
		 *
		 * @since 3.0.0
		 *
		 * @see get_plugins()
		 *
		 * @param array $plugins An array of plugins to display in the list table.
		 */
		$plugins = array(
			'all' => apply_filters( 'all_plugins', get_plugins() ),
			'search' => array(),
			'active' => array(),
			'inactive' => array(),
			'recently_activated' => array(),
			'upgrade' => array(),
			'mustuse' => array(),
			'dropins' => array()
		);

		$screen = $this->screen;

		if ( ! is_multisite() || ( $screen->in_admin( 'network' ) && current_user_can( 'manage_network_plugins' ) ) ) {

			/**
			 * Filter whether to display the advanced plugins list table.
			 *
			 * There are two types of advanced plugins - must-use and drop-ins -
			 * which can be used in a single site or Multisite network.
			 *
			 * The $type parameter allows you to differentiate between the type of advanced
			 * plugins to filter the display of. Contexts include 'mustuse' and 'dropins'.
			 *
			 * @since 3.0.0
			 *
			 * @param bool   $show Whether to show the advanced plugins for the specified
			 *                     plugin type. Default true.
			 * @param string $type The plugin type. Accepts 'mustuse', 'dropins'.
			 */
			if ( apply_filters( 'show_advanced_plugins', true, 'mustuse' ) ) {
				$plugins['mustuse'] = get_mu_plugins();
			}

			/** This action is documented in wp-admin/includes/class-wp-plugins-list-table.php */
			if ( apply_filters( 'show_advanced_plugins', true, 'dropins' ) )
				$plugins['dropins'] = get_dropins();

			if ( current_user_can( 'update_plugins' ) ) {
				$current = get_site_transient( 'update_plugins' );
				foreach ( (array) $plugins['all'] as $plugin_file => $plugin_data ) {
					if ( isset( $current->response[ $plugin_file ] ) ) {
						$plugins['all'][ $plugin_file ]['update'] = true;
						$plugins['upgrade'][ $plugin_file ] = $plugins['all'][ $plugin_file ];
					}
				}
			}
		}

		set_transient( 'plugin_slugs', array_keys( $plugins['all'] ), DAY_IN_SECONDS );

		if ( ! $screen->in_admin( 'network' ) ) {
			$recently_activated = get_option( 'recently_activated', array() );

			foreach ( $recently_activated as $key => $time )
				if ( $time + WEEK_IN_SECONDS < time() )
					unset( $recently_activated[$key] );
			update_option( 'recently_activated', $recently_activated );
		}

		$plugin_info = get_site_transient( 'update_plugins' );

		foreach ( (array) $plugins['all'] as $plugin_file => $plugin_data ) {
			// Extra info if known. array_merge() ensures $plugin_data has precedence if keys collide.
			if ( isset( $plugin_info->response[ $plugin_file ] ) ) {
				$plugins['all'][ $plugin_file ] = $plugin_data = array_merge( (array) $plugin_info->response[ $plugin_file ], $plugin_data );
				// Make sure that $plugins['upgrade'] also receives the extra info since it is used on ?plugin_status=upgrade
				if ( isset( $plugins['upgrade'][ $plugin_file ] ) ) {
					$plugins['upgrade'][ $plugin_file ] = $plugin_data = array_merge( (array) $plugin_info->response[ $plugin_file ], $plugin_data );
				}

			} elseif ( isset( $plugin_info->no_update[ $plugin_file ] ) ) {
				$plugins['all'][ $plugin_file ] = $plugin_data = array_merge( (array) $plugin_info->no_update[ $plugin_file ], $plugin_data );
				// Make sure that $plugins['upgrade'] also receives the extra info since it is used on ?plugin_status=upgrade
				if ( isset( $plugins['upgrade'][ $plugin_file ] ) ) {
					$plugins['upgrade'][ $plugin_file ] = $plugin_data = array_merge( (array) $plugin_info->no_update[ $plugin_file ], $plugin_data );
				}
			}

			// Filter into individual sections
			if ( is_multisite() && ! $screen->in_admin( 'network' ) && is_network_only_plugin( $plugin_file ) && ! is_plugin_active( $plugin_file ) ) {
				// On the non-network screen, filter out network-only plugins as long as they're not individually activated
				unset( $plugins['all'][ $plugin_file ] );
			} elseif ( ! $screen->in_admin( 'network' ) && is_plugin_active_for_network( $plugin_file ) ) {
				// On the non-network screen, filter out network activated plugins
				unset( $plugins['all'][ $plugin_file ] );
			} elseif ( ( ! $screen->in_admin( 'network' ) && is_plugin_active( $plugin_file ) )
				|| ( $screen->in_admin( 'network' ) && is_plugin_active_for_network( $plugin_file ) ) ) {
				// On the non-network screen, populate the active list with plugins that are individually activated
				// On the network-admin screen, populate the active list with plugins that are network activated
				$plugins['active'][ $plugin_file ] = $plugin_data;
			} else {
				if ( ! $screen->in_admin( 'network' ) && isset( $recently_activated[ $plugin_file ] ) ) {
					// On the non-network screen, populate the recently activated list with plugins that have been recently activated
					$plugins['recently_activated'][ $plugin_file ] = $plugin_data;
				}
				// Populate the inactive list with plugins that aren't activated
				$plugins['inactive'][ $plugin_file ] = $plugin_data;
			}
		}

		if ( $s ) {
			$status = 'search';
			$plugins['search'] = array_filter( $plugins['all'], array( $this, '_search_callback' ) );
		}

		$totals = array();
		foreach ( $plugins as $type => $list )
			$totals[ $type ] = count( $list );

		if ( empty( $plugins[ $status ] ) && !in_array( $status, array( 'all', 'search' ) ) )
			$status = 'all';

		$this->items = array();
		foreach ( $plugins[ $status ] as $plugin_file => $plugin_data ) {
			// Translate, Don't Apply Markup, Sanitize HTML
			$this->items[$plugin_file] = _get_plugin_data_markup_translate( $plugin_file, $plugin_data, false, true );
		}

		$total_this_page = $totals[ $status ];

		if ( ! $orderby ) {
			$orderby = 'Name';
		} else {
			$orderby = ucfirst( $orderby );
		}

		$order = strtoupper( $order );

		uasort( $this->items, array( $this, '_order_callback' ) );

		$plugins_per_page = $this->get_items_per_page( str_replace( '-', '_', $screen->id . '_per_page' ), 999 );

		$start = ( $page - 1 ) * $plugins_per_page;

		if ( $total_this_page > $plugins_per_page )
			$this->items = array_slice( $this->items, $start, $plugins_per_page );

		$this->set_pagination_args( array(
			'total_items' => $total_this_page,
			'per_page' => $plugins_per_page,
		) );
	}

	/**
	 * @staticvar string $term
	 * @param array $plugin
	 * @return bool
	 */
	public function _search_callback( $plugin ) {
		static $term = null;
		if ( is_null( $term ) )
			$term = wp_unslash( $_REQUEST['s'] );

		foreach ( $plugin as $value ) {
			if ( false !== stripos( strip_tags( $value ), $term ) ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * @global string $orderby
	 * @global string $order
	 * @param array $plugin_a
	 * @param array $plugin_b
	 * @return int
	 */
	public function _order_callback( $plugin_a, $plugin_b ) {
		global $orderby, $order;

		$a = $plugin_a[$orderby];
		$b = $plugin_b[$orderby];

		if ( $a == $b )
			return 0;

		if ( 'DESC' == $order ) {
			return strcasecmp( $b, $a );
		} else {
			return strcasecmp( $a, $b );
		}
	}

	/**
	 *
	 * @global array $plugins
	 */
	public function no_items() {
		global $plugins;

		if ( !empty( $plugins['all'] ) )
			_e( 'No plugins found.' );
		else
			_e( 'You do not appear to have any plugins available at this time.' );
	}

	/**
	 *
	 * @global string $status
	 * @return array
	 */
	public function get_columns() {
		global $status;

		return array(
			'cb'          => !in_array( $status, array( 'mustuse', 'dropins' ) ) ? '<input type="checkbox" />' : '',
			'name'        => __( 'Plugin' ),
			'description' => __( 'Description' ),
		);
	}

	/**
	 * @return array
	 */
	protected function get_sortable_columns() {
		return array();
	}

	/**
	 *
	 * @global array $totals
	 * @global string $status
	 * @return array
	 */
	protected function get_views() {
		global $totals, $status;

		$status_links = array();
		foreach ( $totals as $type => $count ) {
			if ( !$count )
				continue;

			switch ( $type ) {
				case 'all':
					$text = _nx( 'All <span class="count">(%s)</span>', 'All <span class="count">(%s)</span>', $count, 'plugins' );
					break;
				case 'active':
					$text = _n( 'Active <span class="count">(%s)</span>', 'Active <span class="count">(%s)</span>', $count );
					break;
				case 'recently_activated':
					$text = _n( 'Recently Active <span class="count">(%s)</span>', 'Recently Active <span class="count">(%s)</span>', $count );
					break;
				case 'inactive':
					$text = _n( 'Inactive <span class="count">(%s)</span>', 'Inactive <span class="count">(%s)</span>', $count );
					break;
				case 'mustuse':
					$text = _n( 'Must-Use <span class="count">(%s)</span>', 'Must-Use <span class="count">(%s)</span>', $count );
					break;
				case 'dropins':
					$text = _n( 'Drop-ins <span class="count">(%s)</span>', 'Drop-ins <span class="count">(%s)</span>', $count );
					break;
				case 'upgrade':
					$text = _n( 'Update Available <span class="count">(%s)</span>', 'Update Available <span class="count">(%s)</span>', $count );
					break;
			}

			if ( 'search' != $type ) {
				$status_links[$type] = sprintf( "<a href='%s' %s>%s</a>",
					add_query_arg('plugin_status', $type, 'plugins.php'),
					( $type == $status ) ? ' class="current"' : '',
					sprintf( $text, number_format_i18n( $count ) )
					);
			}
		}

		return $status_links;
	}

	/**
	 *
	 * @global string $status
	 * @return array
	 */
	protected function get_bulk_actions() {
		global $status;

		$actions = array();

		if ( 'active' != $status )
			$actions['activate-selected'] = $this->screen->in_admin( 'network' ) ? __( 'Network Activate' ) : __( 'Activate' );

		if ( 'inactive' != $status && 'recent' != $status )
			$actions['deactivate-selected'] = $this->screen->in_admin( 'network' ) ? __( 'Network Deactivate' ) : __( 'Deactivate' );

		if ( !is_multisite() || $this->screen->in_admin( 'network' ) ) {
			if ( current_user_can( 'update_plugins' ) )
				$actions['update-selected'] = __( 'Update' );
			if ( current_user_can( 'delete_plugins' ) && ( 'active' != $status ) )
				$actions['delete-selected'] = __( 'Delete' );
		}

		return $actions;
	}

	/**
	 * @global string $status
	 * @param string $which
	 */
	public function bulk_actions( $which = '' ) {
		global $status;

		if ( in_array( $status, array( 'mustuse', 'dropins' ) ) )
			return;

		parent::bulk_actions( $which );
	}

	/**
	 * @global string $status
	 * @param string $which
	 */
	protected function extra_tablenav( $which ) {
		global $status;

		if ( ! in_array($status, array('recently_activated', 'mustuse', 'dropins') ) )
			return;

		echo '<div class="alignleft actions">';

		if ( ! $this->screen->in_admin( 'network' ) && 'recently_activated' == $status )
			submit_button( __( 'Clear List' ), 'button', 'clear-recent-list', false );
		elseif ( 'top' == $which && 'mustuse' == $status )
			echo '<p>' . sprintf( __( 'Files in the <code>%s</code> directory are executed automatically.' ), str_replace( ABSPATH, '/', WPMU_PLUGIN_DIR ) ) . '</p>';
		elseif ( 'top' == $which && 'dropins' == $status )
			echo '<p>' . sprintf( __( 'Drop-ins are advanced plugins in the <code>%s</code> directory that replace WordPress functionality when present.' ), str_replace( ABSPATH, '', WP_CONTENT_DIR ) ) . '</p>';

		echo '</div>';
	}

	/**
	 * @return string
	 */
	public function current_action() {
		if ( isset($_POST['clear-recent-list']) )
			return 'clear-recent-list';

		return parent::current_action();
	}

	/**
	 *
	 * @global string $status
	 */
	public function display_rows() {
		global $status;

		if ( is_multisite() && ! $this->screen->in_admin( 'network' ) && in_array( $status, array( 'mustuse', 'dropins' ) ) )
			return;

		foreach ( $this->items as $plugin_file => $plugin_data )
			$this->single_row( array( $plugin_file, $plugin_data ) );
	}

	/**
	 * @global string $status
	 * @global int $page
	 * @global string $s
	 * @global array $totals
	 *
	 * @param array $item
	 */
	public function single_row( $item ) {
		global $status, $page, $s, $totals;

		list( $plugin_file, $plugin_data ) = $item;
		$context = $status;
		$screen = $this->screen;

		// Pre-order.
		$actions = array(
			'deactivate' => '',
			'activate' => '',
			'details' => '',
			'edit' => '',
			'delete' => '',
		);

		if ( 'mustuse' == $context ) {
			$is_active = true;
		} elseif ( 'dropins' == $context ) {
			$dropins = _get_dropins();
			$plugin_name = $plugin_file;
			if ( $plugin_file != $plugin_data['Name'] )
				$plugin_name .= '<br/>' . $plugin_data['Name'];
			if ( true === ( $dropins[ $plugin_file ][1] ) ) { // Doesn't require a constant
				$is_active = true;
				$description = '<p><strong>' . $dropins[ $plugin_file ][0] . '</strong></p>';
			} elseif ( defined( $dropins[ $plugin_file ][1] ) && constant( $dropins[ $plugin_file ][1] ) ) { // Constant is true
				$is_active = true;
				$description = '<p><strong>' . $dropins[ $plugin_file ][0] . '</strong></p>';
			} else {
				$is_active = false;
				$description = '<p><strong>' . $dropins[ $plugin_file ][0] . ' <span class="error-message">' . __('Inactive:') . '</span></strong> ' . sprintf( __( 'Requires <code>%s</code> in <code>wp-config.php</code>.' ), "define('" . $dropins[ $plugin_file ][1] . "', true);" ) . '</p>';
			}
			if ( $plugin_data['Description'] )
				$description .= '<p>' . $plugin_data['Description'] . '</p>';
		} else {
			if ( $screen->in_admin( 'network' ) )
				$is_active = is_plugin_active_for_network( $plugin_file );
			else
				$is_active = is_plugin_active( $plugin_file );

			if ( $screen->in_admin( 'network' ) ) {
				if ( $is_active ) {
					if ( current_user_can( 'manage_network_plugins' ) ) {
						/* translators: %s: plugin name */
						$actions['deactivate'] = '<a href="' . wp_nonce_url( 'plugins.php?action=deactivate&amp;plugin=' . $plugin_file . '&amp;plugin_status=' . $context . '&amp;paged=' . $page . '&amp;s=' . $s, 'deactivate-plugin_' . $plugin_file ) . '" aria-label="' . esc_attr( sprintf( __( 'Network deactivate %s' ), $plugin_data['Name'] ) ) . '">' . __( 'Network Deactivate' ) . '</a>';
						}
				} else {
					if ( current_user_can( 'manage_network_plugins' ) ) {
						/* translators: %s: plugin name */
						$actions['activate'] = '<a href="' . wp_nonce_url( 'plugins.php?action=activate&amp;plugin=' . $plugin_file . '&amp;plugin_status=' . $context . '&amp;paged=' . $page . '&amp;s=' . $s, 'activate-plugin_' . $plugin_file ) . '" class="edit" aria-label="' . esc_attr( sprintf( __( 'Network Activate %s' ), $plugin_data['Name'] ) ) . '">' . __( 'Network Activate' ) . '</a>';
					}
					if ( current_user_can( 'delete_plugins' ) && ! is_plugin_active( $plugin_file ) ) {
						/* translators: %s: plugin name */
						$actions['delete'] = '<a href="' . wp_nonce_url( 'plugins.php?action=delete-selected&amp;checked[]=' . $plugin_file . '&amp;plugin_status=' . $context . '&amp;paged=' . $page . '&amp;s=' . $s, 'bulk-plugins' ) . '" class="delete" aria-label="' . esc_attr( sprintf( __( 'Delete %s' ), $plugin_data['Name'] ) ) . '">' . __( 'Delete' ) . '</a>';
					}
				}
			} else {
				if ( $is_active ) {
					/* translators: %s: plugin name */
					$actions['deactivate'] = '<a href="' . wp_nonce_url( 'plugins.php?action=deactivate&amp;plugin=' . $plugin_file . '&amp;plugin_status=' . $context . '&amp;paged=' . $page . '&amp;s=' . $s, 'deactivate-plugin_' . $plugin_file ) . '" aria-label="' . esc_attr( sprintf( __( 'Deactivate %s' ), $plugin_data['Name'] ) ) . '">' . __( 'Deactivate' ) . '</a>';
				} else {
					/* translators: %s: plugin name */
					$actions['activate'] = '<a href="' . wp_nonce_url( 'plugins.php?action=activate&amp;plugin=' . $plugin_file . '&amp;plugin_status=' . $context . '&amp;paged=' . $page . '&amp;s=' . $s, 'activate-plugin_' . $plugin_file ) . '" class="edit" aria-label="' . esc_attr( sprintf( __( 'Activate %s' ), $plugin_data['Name'] ) ) . '">' . __( 'Activate' ) . '</a>';

					if ( ! is_multisite() && current_user_can( 'delete_plugins' ) ) {
						/* translators: %s: plugin name */
						$actions['delete'] = '<a href="' . wp_nonce_url( 'plugins.php?action=delete-selected&amp;checked[]=' . $plugin_file . '&amp;plugin_status=' . $context . '&amp;paged=' . $page . '&amp;s=' . $s, 'bulk-plugins' ) . '" class="delete" aria-label="' . esc_attr( sprintf( __( 'Delete %s' ), $plugin_data['Name'] ) ) . '">' . __( 'Delete' ) . '</a>';
					}
				} // end if $is_active

			 } // end if $screen->in_admin( 'network' )

			if ( ( ! is_multisite() || $screen->in_admin( 'network' ) ) && current_user_can( 'edit_plugins' ) && is_writable( WP_PLUGIN_DIR . '/' . $plugin_file ) ) {
				/* translators: %s: plugin name */
				$actions['edit'] = '<a href="plugin-editor.php?file=' . $plugin_file . '" class="edit" aria-label="' . esc_attr( sprintf( __( 'Edit %s' ), $plugin_data['Name'] ) ) . '">' . __( 'Edit' ) . '</a>';
			}
		} // end if $context

		$prefix = $screen->in_admin( 'network' ) ? 'network_admin_' : '';

		/**
		 * Filter the action links displayed for each plugin in the Plugins list table.
		 *
		 * The dynamic portion of the hook name, `$prefix`, refers to the context the
		 * action links are displayed in. The 'network_admin_' prefix is used if the
		 * current screen is the Network plugins list table. The prefix is empty ('')
		 * if the current screen is the site plugins list table.
		 *
		 * The default action links for the Network plugins list table include
		 * 'Network Activate', 'Network Deactivate', 'Edit', and 'Delete'.
		 *
		 * The default action links for the site plugins list table include
		 * 'Activate', 'Deactivate', and 'Edit', for a network site, and
		 * 'Activate', 'Deactivate', 'Edit', and 'Delete' for a single site.
		 *
		 * @since 2.5.0
		 *
		 * @param array  $actions     An array of plugin action links.
		 * @param string $plugin_file Path to the plugin file.
		 * @param array  $plugin_data An array of plugin data.
		 * @param string $context     The plugin context. Defaults are 'All', 'Active',
		 *                            'Inactive', 'Recently Activated', 'Upgrade',
		 *                            'Must-Use', 'Drop-ins', 'Search'.
		 */
		$actions = apply_filters( $prefix . 'plugin_action_links', array_filter( $actions ), $plugin_file, $plugin_data, $context );

		/**
		 * Filter the list of action links displayed for a specific plugin.
		 *
		 * The first dynamic portion of the hook name, $prefix, refers to the context
		 * the action links are displayed in. The 'network_admin_' prefix is used if the
		 * current screen is the Network plugins list table. The prefix is empty ('')
		 * if the current screen is the site plugins list table.
		 *
		 * The second dynamic portion of the hook name, $plugin_file, refers to the path
		 * to the plugin file, relative to the plugins directory.
		 *
		 * @since 2.7.0
		 *
		 * @param array  $actions     An array of plugin action links.
		 * @param string $plugin_file Path to the plugin file.
		 * @param array  $plugin_data An array of plugin data.
		 * @param string $context     The plugin context. Defaults are 'All', 'Active',
		 *                            'Inactive', 'Recently Activated', 'Upgrade',
		 *                            'Must-Use', 'Drop-ins', 'Search'.
		 */
		$actions = apply_filters( $prefix . "plugin_action_links_$plugin_file", $actions, $plugin_file, $plugin_data, $context );

		$class = $is_active ? 'active' : 'inactive';
		$checkbox_id =  "checkbox_" . md5($plugin_data['Name']);
		if ( in_array( $status, array( 'mustuse', 'dropins' ) ) ) {
			$checkbox = '';
		} else {
			$checkbox = "<label class='screen-reader-text' for='" . $checkbox_id . "' >" . sprintf( __( 'Select %s' ), $plugin_data['Name'] ) . "</label>"
				. "<input type='checkbox' name='checked[]' value='" . esc_attr( $plugin_file ) . "' id='" . $checkbox_id . "' />";
		}
		if ( 'dropins' != $context ) {
			$description = '<p>' . ( $plugin_data['Description'] ? $plugin_data['Description'] : '&nbsp;' ) . '</p>';
			$plugin_name = $plugin_data['Name'];
		}

		$id = sanitize_title( $plugin_name );
		if ( ! empty( $totals['upgrade'] ) && ! empty( $plugin_data['update'] ) )
			$class .= ' update';

		$plugin_slug = ( isset( $plugin_data['slug'] ) ) ? $plugin_data['slug'] : '';
		printf( "<tr id='%s' class='%s' data-slug='%s'>",
			$id,
			$class,
			$plugin_slug
		);

		list( $columns, $hidden, $sortable, $primary ) = $this->get_column_info();

		foreach ( $columns as $column_name => $column_display_name ) {
			$extra_classes = '';
			if ( in_array( $column_name, $hidden ) ) {
				$extra_classes = ' hidden';
			}

			switch ( $column_name ) {
				case 'cb':
					echo "<th scope='row' class='check-column'>$checkbox</th>";
					break;
				case 'name':
					echo "<td class='plugin-title column-primary'><strong>$plugin_name</strong>";
					echo $this->row_actions( $actions, true );
					echo "</td>";
					break;
				case 'description':
					$classes = 'column-description desc';

					echo "<td class='$classes{$extra_classes}'>
						<div class='plugin-description'>$description</div>
						<div class='$class second plugin-version-author-uri'>";

					$plugin_meta = array();
					if ( !empty( $plugin_data['Version'] ) )
						$plugin_meta[] = sprintf( __( 'Version %s' ), $plugin_data['Version'] );
					if ( !empty( $plugin_data['Author'] ) ) {
						$author = $plugin_data['Author'];
						if ( !empty( $plugin_data['AuthorURI'] ) )
							$author = '<a href="' . $plugin_data['AuthorURI'] . '">' . $plugin_data['Author'] . '</a>';
						$plugin_meta[] = sprintf( __( 'By %s' ), $author );
					}

					// Details link using API info, if available
					if ( isset( $plugin_data['slug'] ) && current_user_can( 'install_plugins' ) ) {
						$plugin_meta[] = sprintf( '<a href="%s" class="thickbox" aria-label="%s" data-title="%s">%s</a>',
							esc_url( network_admin_url( 'plugin-install.php?tab=plugin-information&plugin=' . $plugin_data['slug'] .
								'&TB_iframe=true&width=600&height=550' ) ),
							esc_attr( sprintf( __( 'More information about %s' ), $plugin_name ) ),
							esc_attr( $plugin_name ),
							__( 'View details' )
						);
					} elseif ( ! empty( $plugin_data['PluginURI'] ) ) {
						$plugin_meta[] = sprintf( '<a href="%s">%s</a>',
							esc_url( $plugin_data['PluginURI'] ),
							__( 'Visit plugin site' )
						);
					}

					/**
					 * Filter the array of row meta for each plugin in the Plugins list table.
					 *
					 * @since 2.8.0
					 *
					 * @param array  $plugin_meta An array of the plugin's metadata,
					 *                            including the version, author,
					 *                            author URI, and plugin URI.
					 * @param string $plugin_file Path to the plugin file, relative to the plugins directory.
					 * @param array  $plugin_data An array of plugin data.
					 * @param string $status      Status of the plugin. Defaults are 'All', 'Active',
					 *                            'Inactive', 'Recently Activated', 'Upgrade', 'Must-Use',
					 *                            'Drop-ins', 'Search'.
					 */
					$plugin_meta = apply_filters( 'plugin_row_meta', $plugin_meta, $plugin_file, $plugin_data, $status );
					echo implode( ' | ', $plugin_meta );

					echo "</div></td>";
					break;
				default:
					$classes = "$column_name column-$column_name$class";

					echo "<td class='$classes{$extra_classes}'>";

					/**
					 * Fires inside each custom column of the Plugins list table.
					 *
					 * @since 3.1.0
					 *
					 * @param string $column_name Name of the column.
					 * @param string $plugin_file Path to the plugin file.
					 * @param array  $plugin_data An array of plugin data.
					 */
					do_action( 'manage_plugins_custom_column', $column_name, $plugin_file, $plugin_data );

					echo "</td>";
			}
		}

		echo "</tr>";

		/**
		 * Fires after each row in the Plugins list table.
		 *
		 * @since 2.3.0
		 *
		 * @param string $plugin_file Path to the plugin file, relative to the plugins directory.
		 * @param array  $plugin_data An array of plugin data.
		 * @param string $status      Status of the plugin. Defaults are 'All', 'Active',
		 *                            'Inactive', 'Recently Activated', 'Upgrade', 'Must-Use',
		 *                            'Drop-ins', 'Search'.
		 */
		do_action( 'after_plugin_row', $plugin_file, $plugin_data, $status );

		/**
		 * Fires after each specific row in the Plugins list table.
		 *
		 * The dynamic portion of the hook name, `$plugin_file`, refers to the path
		 * to the plugin file, relative to the plugins directory.
		 *
		 * @since 2.7.0
		 *
		 * @param string $plugin_file Path to the plugin file, relative to the plugins directory.
		 * @param array  $plugin_data An array of plugin data.
		 * @param string $status      Status of the plugin. Defaults are 'All', 'Active',
		 *                            'Inactive', 'Recently Activated', 'Upgrade', 'Must-Use',
		 *                            'Drop-ins', 'Search'.
		 */
		do_action( "after_plugin_row_$plugin_file", $plugin_file, $plugin_data, $status );
	}

	/**
	 * Gets the name of the primary column for this specific list table.
	 *
	 * @since 4.3.0
	 * @access protected
	 *
	 * @return string Unalterable name for the primary column, in this case, 'name'.
	 */
	protected function get_primary_column_name() {
		return 'name';
	}
}
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              <?php
/**
 * Plugin Installer List Table class.
 *
 * @package WordPress
 * @subpackage List_Table
 * @since 3.1.0
 * @access private
 */
class WP_Plugin_Install_List_Table extends WP_List_Table {

	public $order = 'ASC';
	public $orderby = null;
	public $groups = array();

	private $error;

	/**
	 *
	 * @return bool
	 */
	public function ajax_user_can() {
		return current_user_can('install_plugins');
	}

	/**
	 * Return a list of slugs of installed plugins, if known.
	 *
	 * Uses the transient data from the updates API to determine the slugs of
	 * known installed plugins. This might be better elsewhere, perhaps even
	 * within get_plugins().
	 *
	 * @since 4.0.0
	 * @access protected
	 *
	 * @return array
	 */
	protected function get_installed_plugin_slugs() {
		$slugs = array();

		$plugin_info = get_site_transient( 'update_plugins' );
		if ( isset( $plugin_info->no_update ) ) {
			foreach ( $plugin_info->no_update as $plugin ) {
				$slugs[] = $plugin->slug;
			}
		}

		if ( isset( $plugin_info->response ) ) {
			foreach ( $plugin_info->response as $plugin ) {
				$slugs[] = $plugin->slug;
			}
		}

		return $slugs;
	}

	/**
	 *
	 * @global array  $tabs
	 * @global string $tab
	 * @global int    $paged
	 * @global string $type
	 * @global string $term
	 * @global string $wp_version
	 */
	public function prepare_items() {
		include( ABSPATH . 'wp-admin/includes/plugin-install.php' );

		global $tabs, $tab, $paged, $type, $term;

		wp_reset_vars( array( 'tab' ) );

		$paged = $this->get_pagenum();

		$per_page = 30;

		// These are the tabs which are shown on the page
		$tabs = array();

		if ( 'search' == $tab )
			$tabs['search']	= __( 'Search Results' );
		$tabs['featured']  = _x( 'Featured', 'Plugin Installer' );
		$tabs['popular']   = _x( 'Popular', 'Plugin Installer' );
		$tabs['recommended']   = _x( 'Recommended', 'Plugin Installer' );
		$tabs['favorites'] = _x( 'Favorites', 'Plugin Installer' );
		if ( $tab === 'beta' || false !== strpos( $GLOBALS['wp_version'], '-' ) ) {
			$tabs['beta']      = _x( 'Beta Testing', 'Plugin Installer' );
		}
		if ( current_user_can( 'upload_plugins' ) ) {
			// No longer a real tab. Here for filter compatibility.
			// Gets skipped in get_views().
			$tabs['upload'] = __( 'Upload Plugin' );
		}

		$nonmenu_tabs = array( 'plugin-information' ); // Valid actions to perform which do not have a Menu item.

		/**
		 * Filter the tabs shown on the Plugin Install screen.
		 *
		 * @since 2.7.0
		 *
		 * @param array $tabs The tabs shown on the Plugin Install screen. Defaults include 'featured', 'popular',
		 *                    'recommended', 'favorites', and 'upload'.
		 */
		$tabs = apply_filters( 'install_plugins_tabs', $tabs );

		/**
		 * Filter tabs not associated with a menu item on the Plugin Install screen.
		 *
		 * @since 2.7.0
		 *
		 * @param array $nonmenu_tabs The tabs that don't have a Menu item on the Plugin Install screen.
		 */
		$nonmenu_tabs = apply_filters( 'install_plugins_nonmenu_tabs', $nonmenu_tabs );

		// If a non-valid menu tab has been selected, And it's not a non-menu action.
		if ( empty( $tab ) || ( !isset( $tabs[ $tab ] ) && !in_array( $tab, (array) $nonmenu_tabs ) ) )
			$tab = key( $tabs );

		$args = array(
			'page' => $paged,
			'per_page' => $per_page,
			'fields' => array(
				'last_updated' => true,
				'icons' => true,
				'active_installs' => true
			),
			// Send the locale and installed plugin slugs to the API so it can provide context-sensitive results.
			'locale' => get_locale(),
			'installed_plugins' => $this->get_installed_plugin_slugs(),
		);

		switch ( $tab ) {
			case 'search':
				$type = isset( $_REQUEST['type'] ) ? wp_unslash( $_REQUEST['type'] ) : 'term';
				$term = isset( $_REQUEST['s'] ) ? wp_unslash( $_REQUEST['s'] ) : '';

				switch ( $type ) {
					case 'tag':
						$args['tag'] = sanitize_title_with_dashes( $term );
						break;
					case 'term':
						$args['search'] = $term;
						break;
					case 'author':
						$args['author'] = $term;
						break;
				}

				break;

			case 'featured':
				$args['fields']['group'] = true;
				$this->orderby = 'group';
				// No break!
			case 'popular':
			case 'new':
			case 'beta':
			case 'recommended':
				$args['browse'] = $tab;
				break;

			case 'favorites':
				$user = isset( $_GET['user'] ) ? wp_unslash( $_GET['user'] ) : get_user_option( 'wporg_favorites' );
				update_user_meta( get_current_user_id(), 'wporg_favorites', $user );
				if ( $user )
					$args['user'] = $user;
				else
					$args = false;

				add_action( 'install_plugins_favorites', 'install_plugins_favorites_form', 9, 0 );
				break;

			default:
				$args = false;
				break;
		}

		/**
		 * Filter API request arguments for each Plugin Install screen tab.
		 *
		 * The dynamic portion of the hook name, `$tab`, refers to the plugin install tabs.
		 * Default tabs include 'featured', 'popular', 'recommended', 'favorites', and 'upload'.
		 *
		 * @since 3.7.0
		 *
		 * @param array|bool $args Plugin Install API arguments.
		 */
		$args = apply_filters( "install_plugins_table_api_args_$tab", $args );

		if ( !$args )
			return;

		$api = plugins_api( 'query_plugins', $args );

		if ( is_wp_error( $api ) ) {
			$this->error = $api;
			return;
		}

		$this->items = $api->plugins;

		if ( $this->orderby ) {
			uasort( $this->items, array( $this, 'order_callback' ) );
		}

		$this->set_pagination_args( array(
			'total_items' => $api->info['results'],
			'per_page' => $args['per_page'],
		) );

		if ( isset( $api->info['groups'] ) ) {
			$this->groups = $api->info['groups'];
		}
	}

	/**
	 * @access public
	 */
	public function no_items() {
		if ( isset( $this->error ) ) {
			$message = $this->error->get_error_message() . '<p class="hide-if-no-js"><a href="#" class="button" onclick="document.location.reload(); return false;">' . __( 'Try again' ) . '</a></p>';
		} else {
			$message = __( 'No plugins match your request.' );
		}
		echo '<div class="no-plugin-results">' . $message . '</div>';
	}

	/**
	 *
	 * @global array $tabs
	 * @global string $tab
	 *
	 * @return array
	 */
	protected function get_views() {
		global $tabs, $tab;

		$display_tabs = array();
		foreach ( (array) $tabs as $action => $text ) {
			$class = ( $action == $tab ) ? ' current' : '';
			$href = self_admin_url('plugin-install.php?tab=' . $action);
			$display_tabs['plugin-install-'.$action] = "<a href='$href' class='$class'>$text</a>";
		}
		// No longer a real tab.
		unset( $display_tabs['plugin-install-upload'] );

		return $display_tabs;
	}

	/**
	 * Override parent views so we can use the filter bar display.
	 */
	public function views() {
		$views = $this->get_views();

		/** This filter is documented in wp-admin/inclues/class-wp-list-table.php */
		$views = apply_filters( "views_{$this->screen->id}", $views );

?>
<div class="wp-filter">
	<ul class="filter-links">
		<?php
		if ( ! empty( $views ) ) {
			foreach ( $views as $class => $view ) {
				$views[ $class ] = "\t<li class='$class'>$view";
			}
			echo implode( " </li>\n", $views ) . "</li>\n";
		}
		?>
	</ul>

	<?php install_search_form( isset( $views['plugin-install-search'] ) ); ?>
</div>
<?php
	}

	/**
	 * Override the parent display() so we can provide a different container.
	 */
	public function display() {
		$singular = $this->_args['singular'];

		$data_attr = '';

		if ( $singular ) {
			$data_attr = " data-wp-lists='list:$singular'";
		}

		$this->display_tablenav( 'top' );

?>
<div class="wp-list-table <?php echo implode( ' ', $this->get_table_classes() ); ?>">

	<div id="the-list"<?php echo $data_attr; ?>>
		<?php $this->display_rows_or_placeholder(); ?>
	</div>
</div>
<?php
		$this->display_tablenav( 'bottom' );
	}

	/**
	 * @global string $tab
	 *
	 * @param string $which
	 */
	protected function display_tablenav( $which ) {
		if ( $GLOBALS['tab'] === 'featured' ) {
			return;
		}

		if ( 'top' ==  $which ) {
			wp_referer_field();
		?>
			<div class="tablenav top">
				<div class="alignleft actions">
					<?php
					/**
					 * Fires before the Plugin Install table header pagination is displayed.
					 *
					 * @since 2.7.0
					 */
					do_action( 'install_plugins_table_header' ); ?>
				</div>
				<?php $this->pagination( $which ); ?>
				<br class="clear" />
			</div>
		<?php } else { ?>
			<div class="tablenav bottom">
				<?php $this->pagination( $which ); ?>
				<br class="clear" />
			</div>
		<?php
		}
	}

	/**
	 * @return array
	 */
	protected function get_table_classes() {
		return array( 'widefat', $this->_args['plural'] );
	}

	/**
	 * @return array
	 */
	public function get_columns() {
		return array();
	}

	/**
	 * @param object $plugin_a
	 * @param object $plugin_b
	 * @return int
	 */
	private function order_callback( $plugin_a, $plugin_b ) {
		$orderby = $this->orderby;
		if ( ! isset( $plugin_a->$orderby, $plugin_b->$orderby ) ) {
			return 0;
		}

		$a = $plugin_a->$orderby;
		$b = $plugin_b->$orderby;

		if ( $a == $b ) {
			return 0;
		}

		if ( 'DESC' == $this->order ) {
			return ( $a < $b ) ? 1 : -1;
		} else {
			return ( $a < $b ) ? -1 : 1;
		}
	}

	/**
	 * @global string $wp_version
	 */
	public function display_rows() {
		$plugins_allowedtags = array(
			'a' => array( 'href' => array(),'title' => array(), 'target' => array() ),
			'abbr' => array( 'title' => array() ),'acronym' => array( 'title' => array() ),
			'code' => array(), 'pre' => array(), 'em' => array(),'strong' => array(),
			'ul' => array(), 'ol' => array(), 'li' => array(), 'p' => array(), 'br' => array()
		);

		$plugins_group_titles = array(
			'Performance' => _x( 'Performance', 'Plugin installer group title' ),
			'Social'      => _x( 'Social',      'Plugin installer group title' ),
			'Tools'       => _x( 'Tools',       'Plugin installer group title' ),
		);

		$group = null;

		foreach ( (array) $this->items as $plugin ) {
			if ( is_object( $plugin ) ) {
				$plugin = (array) $plugin;
			}

			// Display the group heading if there is one
			if ( isset( $plugin['group'] ) && $plugin['group'] != $group ) {
				if ( isset( $this->groups[ $plugin['group'] ] ) ) {
					$group_name = $this->groups[ $plugin['group'] ];
					if ( isset( $plugins_group_titles[ $group_name ] ) ) {
						$group_name = $plugins_group_titles[ $group_name ];
					}
				} else {
					$group_name = $plugin['group'];
				}

				// Starting a new group, close off the divs of the last one
				if ( ! empty( $group ) ) {
					echo '</div></div>';
				}

				echo '<div class="plugin-group"><h3>' . esc_html( $group_name ) . '</h3>';
				// needs an extra wrapping div for nth-child selectors to work
				echo '<div class="plugin-items">';

				$group = $plugin['group'];
			}
			$title = wp_kses( $plugin['name'], $plugins_allowedtags );

			// Remove any HTML from the description.
			$description = strip_tags( $plugin['short_description'] );
			$version = wp_kses( $plugin['version'], $plugins_allowedtags );

			$name = strip_tags( $title . ' ' . $version );

			$author = wp_kses( $plugin['author'], $plugins_allowedtags );
			if ( ! empty( $author ) ) {
				$author = ' <cite>' . sprintf( __( 'By %s' ), $author ) . '</cite>';
			}

			$action_links = array();

			if ( current_user_can( 'install_plugins' ) || current_user_can( 'update_plugins' ) ) {
				$status = install_plugin_install_status( $plugin );

				switch ( $status['status'] ) {
					case 'install':
						if ( $status['url'] ) {
							/* translators: 1: Plugin name and version. */
							$action_links[] = '<a class="install-now button" data-slug="' . esc_attr( $plugin['slug'] ) . '" href="' . esc_url( $status['url'] ) . '" aria-label="' . esc_attr( sprintf( __( 'Install %s now' ), $name ) ) . '" data-name="' . esc_attr( $name ) . '">' . __( 'Install Now' ) . '</a>';
						}

						break;
					case 'update_available':
						if ( $status['url'] ) {
							/* translators: 1: Plugin name and version */
							$action_links[] = '<a class="update-now button" data-plugin="' . esc_attr( $status['file'] ) . '" data-slug="' . esc_attr( $plugin['slug'] ) . '" href="' . esc_url( $status['url'] ) . '" aria-label="' . esc_attr( sprintf( __( 'Update %s now' ), $name ) ) . '" data-name="' . esc_attr( $name ) . '">' . __( 'Update Now' ) . '</a>';
						}

						break;
					case 'latest_installed':
					case 'newer_installed':
						$action_links[] = '<span class="button button-disabled" title="' . esc_attr__( 'This plugin is already installed and is up to date' ) . ' ">' . _x( 'Installed', 'plugin' ) . '</span>';
						break;
				}
			}

			$details_link   = self_admin_url( 'plugin-install.php?tab=plugin-information&amp;plugin=' . $plugin['slug'] .
								'&amp;TB_iframe=true&amp;width=600&amp;height=550' );

			/* translators: 1: Plugin name and version. */
			$action_links[] = '<a href="' . esc_url( $details_link ) . '" class="thickbox" aria-label="' . esc_attr( sprintf( __( 'More information about %s' ), $name ) ) . '" data-title="' . esc_attr( $name ) . '">' . __( 'More Details' ) . '</a>';

			if ( !empty( $plugin['icons']['svg'] ) ) {
				$plugin_icon_url = $plugin['icons']['svg'];
			} elseif ( !empty( $plugin['icons']['2x'] ) ) {
				$plugin_icon_url = $plugin['icons']['2x'];
			} elseif ( !empty( $plugin['icons']['1x'] ) ) {
				$plugin_icon_url = $plugin['icons']['1x'];
			} else {
				$plugin_icon_url = $plugin['icons']['default'];
			}

			/**
			 * Filter the install action links for a plugin.
			 *
			 * @since 2.7.0
			 *
			 * @param array $action_links An array of plugin action hyperlinks. Defaults are links to Details and Install Now.
			 * @param array $plugin       The plugin currently being listed.
			 */
			$action_links = apply_filters( 'plugin_install_action_links', $action_links, $plugin );

			$date_format = __( 'M j, Y @ H:i' );
			$last_updated_timestamp = strtotime( $plugin['last_updated'] );
		?>
		<div class="plugin-card plugin-card-<?php echo sanitize_html_class( $plugin['slug'] ); ?>">
			<div class="plugin-card-top">
				<a href="<?php echo esc_url( $details_link ); ?>" class="thickbox plugin-icon"><img src="<?php echo esc_attr( $plugin_icon_url ) ?>" /></a>
				<div class="name column-name">
					<h4><a href="<?php echo esc_url( $details_link ); ?>" class="thickbox"><?php echo $title; ?></a></h4>
				</div>
				<div class="action-links">
					<?php
						if ( $action_links ) {
							echo '<ul class="plugin-action-buttons"><li>' . implode( '</li><li>', $action_links ) . '</li></ul>';
						}
					?>
				</div>
				<div class="desc column-description">
					<p><?php echo $description; ?></p>
					<p class="authors"><?php echo $author; ?></p>
				</div>
			</div>
			<div class="plugin-card-bottom">
				<div class="vers column-rating">
					<?php wp_star_rating( array( 'rating' => $plugin['rating'], 'type' => 'percent', 'number' => $plugin['num_ratings'] ) ); ?>
					<span class="num-ratings">(<?php echo number_format_i18n( $plugin['num_ratings'] ); ?>)</span>
				</div>
				<div class="column-updated">
					<strong><?php _e( 'Last Updated:' ); ?></strong> <span title="<?php echo esc_attr( date_i18n( $date_format, $last_updated_timestamp ) ); ?>">
						<?php printf( __( '%s ago' ), human_time_diff( $last_updated_timestamp ) ); ?>
					</span>
				</div>
				<div class="column-downloaded">
					<?php
					if ( $plugin['active_installs'] >= 1000000 ) {
						$active_installs_text = _x( '1+ Million', 'Active plugin installs' );
					} else {
						$active_installs_text = number_format_i18n( $plugin['active_installs'] ) . '+';
					}
					printf( __( '%s Active Installs' ), $active_installs_text );
					?>
				</div>
				<div class="column-compatibility">
					<?php
					if ( ! empty( $plugin['tested'] ) && version_compare( substr( $GLOBALS['wp_version'], 0, strlen( $plugin['tested'] ) ), $plugin['tested'], '>' ) ) {
						echo '<span class="compatibility-untested">' . __( 'Untested with your version of WordPress' ) . '</span>';
					} elseif ( ! empty( $plugin['requires'] ) && version_compare( substr( $GLOBALS['wp_version'], 0, strlen( $plugin['requires'] ) ), $plugin['requires'], '<' ) ) {
						echo '<span class="compatibility-incompatible">' . __( '<strong>Incompatible</strong> with your version of WordPress' ) . '</span>';
					} else {
						echo '<span class="compatibility-compatible">' . __( '<strong>Compatible</strong> with your version of WordPress' ) . '</span>';
					}
					?>
				</div>
			</div>
		</div>
		<?php
		}

		// Close off the group divs of the last one
		if ( ! empty( $group ) ) {
			echo '</div></div>';
		}
	}
}
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                <?php
/**
 * MS Themes List Table class.
 *
 * @package WordPress
 * @subpackage List_Table
 * @since 3.1.0
 * @access private
 */
class WP_MS_Themes_List_Table extends WP_List_Table {

	public $site_id;
	public $is_site_themes;

	private $has_items;

	/**
	 * Constructor.
	 *
	 * @since 3.1.0
	 * @access public
	 *
	 * @see WP_List_Table::__construct() for more information on default arguments.
	 *
	 * @global string $status
	 * @global int    $page
	 *
	 * @param array $args An associative array of arguments.
	 */
	public function __construct( $args = array() ) {
		global $status, $page;

		parent::__construct( array(
			'plural' => 'themes',
			'screen' => isset( $args['screen'] ) ? $args['screen'] : null,
		) );

		$status = isset( $_REQUEST['theme_status'] ) ? $_REQUEST['theme_status'] : 'all';
		if ( !in_array( $status, array( 'all', 'enabled', 'disabled', 'upgrade', 'search', 'broken' ) ) )
			$status = 'all';

		$page = $this->get_pagenum();

		$this->is_site_themes = ( 'site-themes-network' == $this->screen->id ) ? true : false;

		if ( $this->is_site_themes )
			$this->site_id = isset( $_REQUEST['id'] ) ? intval( $_REQUEST['id'] ) : 0;
	}

	/**
	 *
	 * @return array
	 */
	protected function get_table_classes() {
		// todo: remove and add CSS for .themes
		return array( 'widefat', 'plugins' );
	}

	/**
	 *
	 * @return bool
	 */
	public function ajax_user_can() {
		if ( $this->is_site_themes )
			return current_user_can( 'manage_sites' );
		else
			return current_user_can( 'manage_network_themes' );
	}

	/**
	 *
	 * @global string $status
	 * @global array $totals
	 * @global int $page
	 * @global string $orderby
	 * @global string $order
	 * @global string $s
	 */
	public function prepare_items() {
		global $status, $totals, $page, $orderby, $order, $s;

		wp_reset_vars( array( 'orderby', 'order', 's' ) );

		$themes = array(
			/**
			 * Filter the full array of WP_Theme objects to list in the Multisite
			 * themes list table.
			 *
			 * @since 3.1.0
			 *
			 * @param array $all An array of WP_Theme objects to display in the list table.
			 */
			'all' => apply_filters( 'all_themes', wp_get_themes() ),
			'search' => array(),
			'enabled' => array(),
			'disabled' => array(),
			'upgrade' => array(),
			'broken' => $this->is_site_themes ? array() : wp_get_themes( array( 'errors' => true ) ),
		);

		if ( $this->is_site_themes ) {
			$themes_per_page = $this->get_items_per_page( 'site_themes_network_per_page' );
			$allowed_where = 'site';
		} else {
			$themes_per_page = $this->get_items_per_page( 'themes_network_per_page' );
			$allowed_where = 'network';
		}

		$maybe_update = current_user_can( 'update_themes' ) && ! $this->is_site_themes && $current = get_site_transient( 'update_themes' );

		foreach ( (array) $themes['all'] as $key => $theme ) {
			if ( $this->is_site_themes && $theme->is_allowed( 'network' ) ) {
				unset( $themes['all'][ $key ] );
				continue;
			}

			if ( $maybe_update && isset( $current->response[ $key ] ) ) {
				$themes['all'][ $key ]->update = true;
				$themes['upgrade'][ $key ] = $themes['all'][ $key ];
			}

			$filter = $theme->is_allowed( $allowed_where, $this->site_id ) ? 'enabled' : 'disabled';
			$themes[ $filter ][ $key ] = $themes['all'][ $key ];
		}

		if ( $s ) {
			$status = 'search';
			$themes['search'] = array_filter( array_merge( $themes['all'], $themes['broken'] ), array( $this, '_search_callback' ) );
		}

		$totals = array();
		foreach ( $themes as $type => $list )
			$totals[ $type ] = count( $list );

		if ( empty( $themes[ $status ] ) && !in_array( $status, array( 'all', 'search' ) ) )
			$status = 'all';

		$this->items = $themes[ $status ];
		WP_Theme::sort_by_name( $this->items );

		$this->has_items = ! empty( $themes['all'] );
		$total_this_page = $totals[ $status ];

		if ( $orderby ) {
			$orderby = ucfirst( $orderby );
			$order = strtoupper( $order );

			if ( $orderby == 'Name' ) {
				if ( 'ASC' == $order )
					$this->items = array_reverse( $this->items );
			} else {
				uasort( $this->items, array( $this, '_order_callback' ) );
			}
		}

		$start = ( $page - 1 ) * $themes_per_page;

		if ( $total_this_page > $themes_per_page )
			$this->items = array_slice( $this->items, $start, $themes_per_page, true );

		$this->set_pagination_args( array(
			'total_items' => $total_this_page,
			'per_page' => $themes_per_page,
		) );
	}

	/**
	 * @staticvar string $term
	 * @param WP_Theme $theme
	 * @return bool
	 */
	public function _search_callback( $theme ) {
		static $term = null;
		if ( is_null( $term ) )
			$term = wp_unslash( $_REQUEST['s'] );

		foreach ( array( 'Name', 'Description', 'Author', 'Author', 'AuthorURI' ) as $field ) {
			// Don't mark up; Do translate.
			if ( false !== stripos( $theme->display( $field, false, true ), $term ) )
				return true;
		}

		if ( false !== stripos( $theme->get_stylesheet(), $term ) )
			return true;

		if ( false !== stripos( $theme->get_template(), $term ) )
			return true;

		return false;
	}

	// Not used by any core columns.
	/**
	 * @global string $orderby
	 * @global string $order
	 * @param array $theme_a
	 * @param array $theme_b
	 * @return int
	 */
	public function _order_callback( $theme_a, $theme_b ) {
		global $orderby, $order;

		$a = $theme_a[ $orderby