s
	 * @param bool $vert Flip along Vertical Axis
	 * @return true|WP_Error
	 */
	public function flip( $horz, $vert ) {
		try {
			if ( $horz )
				$this->image->flipImage();

			if ( $vert )
				$this->image->flopImage();
		}
		catch ( Exception $e ) {
			return new WP_Error( 'image_flip_error', $e->getMessage() );
		}
		return true;
	}

	/**
	 * Saves current image to file.
	 *
	 * @since 3.5.0
	 * @access public
	 *
	 * @param string $destfilename
	 * @param string $mime_type
	 * @return array|WP_Error {'path'=>string, 'file'=>string, 'width'=>int, 'height'=>int, 'mime-type'=>string}
	 */
	public function save( $destfilename = null, $mime_type = null ) {
		$saved = $this->_save( $this->image, $destfilename, $mime_type );

		if ( ! is_wp_error( $saved ) ) {
			$this->file = $saved['path'];
			$this->mime_type = $saved['mime-type'];

			try {
				$this->image->setImageFormat( strtoupper( $this->get_extension( $this->mime_type ) ) );
			}
			catch ( Exception $e ) {
				return new WP_Error( 'image_save_error', $e->getMessage(), $this->file );
			}
		}

		return $saved;
	}

	/**
	 *
	 * @param Imagick $image
	 * @param string $filename
	 * @param string $mime_type
	 * @return array|WP_Error
	 */
	protected function _save( $image, $filename = null, $mime_type = null ) {
		list( $filename, $extension, $mime_type ) = $this->get_output_format( $filename, $mime_type );

		if ( ! $filename )
			$filename = $this->generate_filename( null, null, $extension );

		try {
			// Store initial Format
			$orig_format = $this->image->getImageFormat();

			$this->image->setImageFormat( strtoupper( $this->get_extension( $mime_type ) ) );
			$this->make_image( $filename, array( $image, 'writeImage' ), array( $filename ) );

			// Reset original Format
			$this->image->setImageFormat( $orig_format );
		}
		catch ( Exception $e ) {
			return new WP_Error( 'image_save_error', $e->getMessage(), $filename );
		}

		// Set correct file permissions
		$stat = stat( dirname( $filename ) );
		$perms = $stat['mode'] & 0000666; //same permissions as parent folder, strip off the executable bits
		@ chmod( $filename, $perms );

		/** This filter is documented in wp-includes/class-wp-image-editor-gd.php */
		return array(
			'path'      => $filename,
			'file'      => wp_basename( apply_filters( 'image_make_intermediate_size', $filename ) ),
			'width'     => $this->size['width'],
			'height'    => $this->size['height'],
			'mime-type' => $mime_type,
		);
	}

	/**
	 * Streams current image to browser.
	 *
	 * @since 3.5.0
	 * @access public
	 *
	 * @param string $mime_type
	 * @return true|WP_Error
	 */
	public function stream( $mime_type = null ) {
		list( $filename, $extension, $mime_type ) = $this->get_output_format( null, $mime_type );

		try {
			// Temporarily change format for stream
			$this->image->setImageFormat( strtoupper( $extension ) );

			// Output stream of image content
			header( "Content-Type: $mime_type" );
			print $this->image->getImageBlob();

			// Reset Image to original Format
			$this->image->setImageFormat( $this->get_extension( $this->mime_type ) );
		}
		catch ( Exception $e ) {
			return new WP_Error( 'image_stream_error', $e->getMessage() );
		}

		return true;
	}
}
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         <?php
/**
 * WordPress GD Image Editor
 *
 * @package WordPress
 * @subpackage Image_Editor
 */

/**
 * WordPress Image Editor Class for Image Manipulation through GD
 *
 * @since 3.5.0
 * @package WordPress
 * @subpackage Image_Editor
 * @uses WP_Image_Editor Extends class
 */
class WP_Image_Editor_GD extends WP_Image_Editor {
	/**
	 * GD Resource.
	 *
	 * @access protected
	 * @var resource
	 */
	protected $image;

	public function __destruct() {
		if ( $this->image ) {
			// we don't need the original in memory anymore
			imagedestroy( $this->image );
		}
	}

	/**
	 * Checks to see if current environment supports GD.
	 *
	 * @since 3.5.0
	 *
	 * @static
	 * @access public
	 *
	 * @param array $args
	 * @return bool
	 */
	public static function test( $args = array() ) {
		if ( ! extension_loaded('gd') || ! function_exists('gd_info') )
			return false;

		// On some setups GD library does not provide imagerotate() - Ticket #11536
		if ( isset( $args['methods'] ) &&
			 in_array( 'rotate', $args['methods'] ) &&
			 ! function_exists('imagerotate') ){

				return false;
		}

		return true;
	}

	/**
	 * Checks to see if editor supports the mime-type specified.
	 *
	 * @since 3.5.0
	 *
	 * @static
	 * @access public
	 *
	 * @param string $mime_type
	 * @return bool
	 */
	public static function supports_mime_type( $mime_type ) {
		$image_types = imagetypes();
		switch( $mime_type ) {
			case 'image/jpeg':
				return ($image_types & IMG_JPG) != 0;
			case 'image/png':
				return ($image_types & IMG_PNG) != 0;
			case 'image/gif':
				return ($image_types & IMG_GIF) != 0;
		}

		return false;
	}

	/**
	 * Loads image from $this->file into new GD Resource.
	 *
	 * @since 3.5.0
	 * @access protected
	 *
	 * @return bool|WP_Error True if loaded successfully; WP_Error on failure.
	 */
	public function load() {
		if ( $this->image )
			return true;

		if ( ! is_file( $this->file ) && ! preg_match( '|^https?://|', $this->file ) )
			return new WP_Error( 'error_loading_image', __('File doesn&#8217;t exist?'), $this->file );

		/**
		 * Filter the memory limit allocated for image manipulation.
		 *
		 * @since 3.5.0
		 *
		 * @param int|string $limit Maximum memory limit to allocate for images. Default WP_MAX_MEMORY_LIMIT.
		 *                          Accepts an integer (bytes), or a shorthand string notation, such as '256M'.
		 */
		// Set artificially high because GD uses uncompressed images in memory
		@ini_set( 'memory_limit', apply_filters( 'image_memory_limit', WP_MAX_MEMORY_LIMIT ) );

		$this->image = @imagecreatefromstring( file_get_contents( $this->file ) );

		if ( ! is_resource( $this->image ) )
			return new WP_Error( 'invalid_image', __('File is not an image.'), $this->file );

		$size = @getimagesize( $this->file );
		if ( ! $size )
			return new WP_Error( 'invalid_image', __('Could not read image size.'), $this->file );

		if ( function_exists( 'imagealphablending' ) && function_exists( 'imagesavealpha' ) ) {
			imagealphablending( $this->image, false );
			imagesavealpha( $this->image, true );
		}

		$this->update_size( $size[0], $size[1] );
		$this->mime_type = $size['mime'];

		return $this->set_quality();
	}

	/**
	 * Sets or updates current image size.
	 *
	 * @since 3.5.0
	 * @access protected
	 *
	 * @param int $width
	 * @param int $height
	 * @return true
	 */
	protected function update_size( $width = false, $height = false ) {
		if ( ! $width )
			$width = imagesx( $this->image );

		if ( ! $height )
			$height = imagesy( $this->image );

		return parent::update_size( $width, $height );
	}

	/**
	 * Resizes current image.
	 * Wraps _resize, since _resize returns a GD Resource.
	 *
	 * At minimum, either a height or width must be provided.
	 * If one of the two is set to null, the resize will
	 * maintain aspect ratio according to the provided dimension.
	 *
	 * @since 3.5.0
	 * @access public
	 *
	 * @param  int|null $max_w Image width.
	 * @param  int|null $max_h Image height.
	 * @param  bool     $crop
	 * @return true|WP_Error
	 */
	public function resize( $max_w, $max_h, $crop = false ) {
		if ( ( $this->size['width'] == $max_w ) && ( $this->size['height'] == $max_h ) )
			return true;

		$resized = $this->_resize( $max_w, $max_h, $crop );

		if ( is_resource( $resized ) ) {
			imagedestroy( $this->image );
			$this->image = $resized;
			return true;

		} elseif ( is_wp_error( $resized ) )
			return $resized;

		return new WP_Error( 'image_resize_error', __('Image resize failed.'), $this->file );
	}

	/**
	 *
	 * @param int $max_w
	 * @param int $max_h
	 * @param bool|array $crop
	 * @return resource|WP_Error
	 */
	protected function _resize( $max_w, $max_h, $crop = false ) {
		$dims = image_resize_dimensions( $this->size['width'], $this->size['height'], $max_w, $max_h, $crop );
		if ( ! $dims ) {
			return new WP_Error( 'error_getting_dimensions', __('Could not calculate resized image dimensions'), $this->file );
		}
		list( $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h ) = $dims;

		$resized = wp_imagecreatetruecolor( $dst_w, $dst_h );
		imagecopyresampled( $resized, $this->image, $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h );

		if ( is_resource( $resized ) ) {
			$this->update_size( $dst_w, $dst_h );
			return $resized;
		}

		return new WP_Error( 'image_resize_error', __('Image resize failed.'), $this->file );
	}

	/**
	 * Resize multiple images from a single source.
	 *
	 * @since 3.5.0
	 * @access public
	 *
	 * @param array $sizes {
	 *     An array of image size arrays. Default sizes are 'small', 'medium', 'large'.
	 *
	 *     Either a height or width must be provided.
	 *     If one of the two is set to null, the resize will
	 *     maintain aspect ratio according to the provided dimension.
	 *
	 *     @type array $size {
	 *         @type int  ['width']  Optional. Image width.
	 *         @type int  ['height'] Optional. Image height.
	 *         @type bool ['crop']   Optional. Whether to crop the image. Default false.
	 *     }
	 * }
	 * @return array An array of resized images' metadata by size.
	 */
	public function multi_resize( $sizes ) {
		$metadata = array();
		$orig_size = $this->size;

		foreach ( $sizes as $size => $size_data ) {
			if ( ! isset( $size_data['width'] ) && ! isset( $size_data['height'] ) ) {
				continue;
			}

			if ( ! isset( $size_data['width'] ) ) {
				$size_data['width'] = null;
			}
			if ( ! isset( $size_data['height'] ) ) {
				$size_data['height'] = null;
			}

			if ( ! isset( $size_data['crop'] ) ) {
				$size_data['crop'] = false;
			}

			$image = $this->_resize( $size_data['width'], $size_data['height'], $size_data['crop'] );
			$duplicate = ( ( $orig_size['width'] == $size_data['width'] ) && ( $orig_size['height'] == $size_data['height'] ) );

			if ( ! is_wp_error( $image ) && ! $duplicate ) {
				$resized = $this->_save( $image );

				imagedestroy( $image );

				if ( ! is_wp_error( $resized ) && $resized ) {
					unset( $resized['path'] );
					$metadata[$size] = $resized;
				}
			}

			$this->size = $orig_size;
		}

		return $metadata;
	}

	/**
	 * Crops Image.
	 *
	 * @since 3.5.0
	 * @access public
	 *
	 * @param int  $src_x   The start x position to crop from.
	 * @param int  $src_y   The start y position to crop from.
	 * @param int  $src_w   The width to crop.
	 * @param int  $src_h   The height to crop.
	 * @param int  $dst_w   Optional. The destination width.
	 * @param int  $dst_h   Optional. The destination height.
	 * @param bool $src_abs Optional. If the source crop points are absolute.
	 * @return bool|WP_Error
	 */
	public function crop( $src_x, $src_y, $src_w, $src_h, $dst_w = null, $dst_h = null, $src_abs = false ) {
		// If destination width/height isn't specified, use same as
		// width/height from source.
		if ( ! $dst_w )
			$dst_w = $src_w;
		if ( ! $dst_h )
			$dst_h = $src_h;

		$dst = wp_imagecreatetruecolor( $dst_w, $dst_h );

		if ( $src_abs ) {
			$src_w -= $src_x;
			$src_h -= $src_y;
		}

		if ( function_exists( 'imageantialias' ) )
			imageantialias( $dst, true );

		imagecopyresampled( $dst, $this->image, 0, 0, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h );

		if ( is_resource( $dst ) ) {
			imagedestroy( $this->image );
			$this->image = $dst;
			$this->update_size();
			return true;
		}

		return new WP_Error( 'image_crop_error', __('Image crop failed.'), $this->file );
	}

	/**
	 * Rotates current image counter-clockwise by $angle.
	 * Ported from image-edit.php
	 *
	 * @since 3.5.0
	 * @access public
	 *
	 * @param float $angle
	 * @return true|WP_Error
	 */
	public function rotate( $angle ) {
		if ( function_exists('imagerotate') ) {
			$transparency = imagecolorallocatealpha( $this->image, 255, 255, 255, 127 );
			$rotated = imagerotate( $this->image, $angle, $transparency );

			if ( is_resource( $rotated ) ) {
				imagealphablending( $rotated, true );
				imagesavealpha( $rotated, true );
				imagedestroy( $this->image );
				$this->image = $rotated;
				$this->update_size();
				return true;
			}
		}
		return new WP_Error( 'image_rotate_error', __('Image rotate failed.'), $this->file );
	}

	/**
	 * Flips current image.
	 *
	 * @since 3.5.0
	 * @access public
	 *
	 * @param bool $horz Flip along Horizontal Axis
	 * @param bool $vert Flip along Vertical Axis
	 * @return true|WP_Error
	 */
	public function flip( $horz, $vert ) {
		$w = $this->size['width'];
		$h = $this->size['height'];
		$dst = wp_imagecreatetruecolor( $w, $h );

		if ( is_resource( $dst ) ) {
			$sx = $vert ? ($w - 1) : 0;
			$sy = $horz ? ($h - 1) : 0;
			$sw = $vert ? -$w : $w;
			$sh = $horz ? -$h : $h;

			if ( imagecopyresampled( $dst, $this->image, 0, 0, $sx, $sy, $w, $h, $sw, $sh ) ) {
				imagedestroy( $this->image );
				$this->image = $dst;
				return true;
			}
		}
		return new WP_Error( 'image_flip_error', __('Image flip failed.'), $this->file );
	}

	/**
	 * Saves current in-memory image to file.
	 *
	 * @since 3.5.0
	 * @access public
	 *
	 * @param string|null $filename
	 * @param string|null $mime_type
	 * @return array|WP_Error {'path'=>string, 'file'=>string, 'width'=>int, 'height'=>int, 'mime-type'=>string}
	 */
	public function save( $filename = null, $mime_type = null ) {
		$saved = $this->_save( $this->image, $filename, $mime_type );

		if ( ! is_wp_error( $saved ) ) {
			$this->file = $saved['path'];
			$this->mime_type = $saved['mime-type'];
		}

		return $saved;
	}

	/**
	 * @param resource $image
	 * @param string|null $filename
	 * @param string|null $mime_type
	 * @return WP_Error|array
	 */
	protected function _save( $image, $filename = null, $mime_type = null ) {
		list( $filename, $extension, $mime_type ) = $this->get_output_format( $filename, $mime_type );

		if ( ! $filename )
			$filename = $this->generate_filename( null, null, $extension );

		if ( 'image/gif' == $mime_type ) {
			if ( ! $this->make_image( $filename, 'imagegif', array( $image, $filename ) ) )
				return new WP_Error( 'image_save_error', __('Image Editor Save Failed') );
		}
		elseif ( 'image/png' == $mime_type ) {
			// convert from full colors to index colors, like original PNG.
			if ( function_exists('imageistruecolor') && ! imageistruecolor( $image ) )
				imagetruecolortopalette( $image, false, imagecolorstotal( $image ) );

			if ( ! $this->make_image( $filename, 'imagepng', array( $image, $filename ) ) )
				return new WP_Error( 'image_save_error', __('Image Editor Save Failed') );
		}
		elseif ( 'image/jpeg' == $mime_type ) {
			if ( ! $this->make_image( $filename, 'imagejpeg', array( $image, $filename, $this->get_quality() ) ) )
				return new WP_Error( 'image_save_error', __('Image Editor Save Failed') );
		}
		else {
			return new WP_Error( 'image_save_error', __('Image Editor Save Failed') );
		}

		// Set correct file permissions
		$stat = stat( dirname( $filename ) );
		$perms = $stat['mode'] & 0000666; //same permissions as parent folder, strip off the executable bits
		@ chmod( $filename, $perms );

		/**
		 * Filter the name of the saved image file.
		 *
		 * @since 2.6.0
		 *
		 * @param string $filename Name of the file.
		 */
		return array(
			'path'      => $filename,
			'file'      => wp_basename( apply_filters( 'image_make_intermediate_size', $filename ) ),
			'width'     => $this->size['width'],
			'height'    => $this->size['height'],
			'mime-type' => $mime_type,
		);
	}

	/**
	 * Returns stream of current image.
	 *
	 * @since 3.5.0
	 * @access public
	 *
	 * @param string $mime_type
	 * @return bool
	 */
	public function stream( $mime_type = null ) {
		list( $filename, $extension, $mime_type ) = $this->get_output_format( null, $mime_type );

		switch ( $mime_type ) {
			case 'image/png':
				header( 'Content-Type: image/png' );
				return imagepng( $this->image );
			case 'image/gif':
				header( 'Content-Type: image/gif' );
				return imagegif( $this->image );
			default:
				header( 'Content-Type: image/jpeg' );
				return imagejpeg( $this->image, null, $this->get_quality() );
		}
	}

	/**
	 * Either calls editor's save function or handles file as a stream.
	 *
	 * @since 3.5.0
	 * @access protected
	 *
	 * @param string|stream $filename
	 * @param callable $function
	 * @param array $arguments
	 * @return bool
	 */
	protected function make_image( $filename, $function, $arguments ) {
		if ( wp_is_stream( $filename ) )
			$arguments[1] = null;

		return parent::make_image( $filename, $function, $arguments );
	}
}
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      <?php
/**
 * WordPress Customize Section classes
 *
 * @package WordPress
 * @subpackage Customize
 * @since 3.4.0
 */

/**
 * Customize Section class.
 *
 * A UI container for controls, managed by the WP_Customize_Manager class.
 *
 * @since 3.4.0
 *
 * @see WP_Customize_Manager
 */
class WP_Customize_Section {

	/**
	 * Incremented with each new class instantiation, then stored in $instance_number.
	 *
	 * Used when sorting two instances whose priorities are equal.
	 *
	 * @since 4.1.0
	 *
	 * @static
	 * @access protected
	 * @var int
	 */
	protected static $instance_count = 0;

	/**
	 * Order in which this instance was created in relation to other instances.
	 *
	 * @since 4.1.0
	 * @access public
	 * @var int
	 */
	public $instance_number;

	/**
	 * WP_Customize_Manager instance.
	 *
	 * @since 3.4.0
	 * @access public
	 * @var WP_Customize_Manager
	 */
	public $manager;

	/**
	 * Unique identifier.
	 *
	 * @since 3.4.0
	 * @access public
	 * @var string
	 */
	public $id;

	/**
	 * Priority of the section which informs load order of sections.
	 *
	 * @since 3.4.0
	 * @access public
	 * @var integer
	 */
	public $priority = 160;

	/**
	 * Panel in which to show the section, making it a sub-section.
	 *
	 * @since 4.0.0
	 * @access public
	 * @var string
	 */
	public $panel = '';

	/**
	 * Capability required for the section.
	 *
	 * @since 3.4.0
	 * @access public
	 * @var string
	 */
	public $capability = 'edit_theme_options';

	/**
	 * Theme feature support for the section.
	 *
	 * @since 3.4.0
	 * @access public
	 * @var string|array
	 */
	public $theme_supports = '';

	/**
	 * Title of the section to show in UI.
	 *
	 * @since 3.4.0
	 * @access public
	 * @var string
	 */
	public $title = '';

	/**
	 * Description to show in the UI.
	 *
	 * @since 3.4.0
	 * @access public
	 * @var string
	 */
	public $description = '';

	/**
	 * Customizer controls for this section.
	 *
	 * @since 3.4.0
	 * @access public
	 * @var array
	 */
	public $controls;

	/**
	 * Type of this section.
	 *
	 * @since 4.1.0
	 * @access public
	 * @var string
	 */
	public $type = 'default';

	/**
	 * Active callback.
	 *
	 * @since 4.1.0
	 * @access public
	 *
	 * @see WP_Customize_Section::active()
	 *
	 * @var callable Callback is called with one argument, the instance of
	 *               {@see WP_Customize_Section}, and returns bool to indicate
	 *               whether the section is active (such as it relates to the URL
	 *               currently being previewed).
	 */
	public $active_callback = '';

	/**
	 * Constructor.
	 *
	 * Any supplied $args override class property defaults.
	 *
	 * @since 3.4.0
	 *
	 * @param WP_Customize_Manager $manager Customizer bootstrap instance.
	 * @param string               $id      An specific ID of the section.
	 * @param array                $args    Section arguments.
	 */
	public function __construct( $manager, $id, $args = array() ) {
		$keys = array_keys( get_object_vars( $this ) );
		foreach ( $keys as $key ) {
			if ( isset( $args[ $key ] ) ) {
				$this->$key = $args[ $key ];
			}
		}

		$this->manager = $manager;
		$this->id = $id;
		if ( empty( $this->active_callback ) ) {
			$this->active_callback = array( $this, 'active_callback' );
		}
		self::$instance_count += 1;
		$this->instance_number = self::$instance_count;

		$this->controls = array(); // Users cannot customize the $controls array.
	}

	/**
	 * Check whether section is active to current Customizer preview.
	 *
	 * @since 4.1.0
	 * @access public
	 *
	 * @return bool Whether the section is active to the current preview.
	 */
	final public function active() {
		$section = $this;
		$active = call_user_func( $this->active_callback, $this );

		/**
		 * Filter response of {@see WP_Customize_Section::active()}.
		 *
		 * @since 4.1.0
		 *
		 * @param bool                 $active  Whether the Customizer section is active.
		 * @param WP_Customize_Section $section {@see WP_Customize_Section} instance.
		 */
		$active = apply_filters( 'customize_section_active', $active, $section );

		return $active;
	}

	/**
	 * Default callback used when invoking {@see WP_Customize_Section::active()}.
	 *
	 * Subclasses can override this with their specific logic, or they may provide
	 * an 'active_callback' argument to the constructor.
	 *
	 * @since 4.1.0
	 * @access public
	 *
	 * @return true Always true.
	 */
	public function active_callback() {
		return true;
	}

	/**
	 * Gather the parameters passed to client JavaScript via JSON.
	 *
	 * @since 4.1.0
	 *
	 * @return array The array to be exported to the client as JSON.
	 */
	public function json() {
		$array = wp_array_slice_assoc( (array) $this, array( 'id', 'description', 'priority', 'panel', 'type' ) );
		$array['title'] = html_entity_decode( $this->title, ENT_QUOTES, get_bloginfo( 'charset' ) );
		$array['content'] = $this->get_content();
		$array['active'] = $this->active();
		$array['instanceNumber'] = $this->instance_number;

		if ( $this->panel ) {
			/* translators: &#9656; is the unicode right-pointing triangle, and %s is the section title in the Customizer */
			$array['customizeAction'] = sprintf( __( 'Customizing &#9656; %s' ), esc_html( $this->manager->get_panel( $this->panel )->title ) );
		} else {
			$array['customizeAction'] = __( 'Customizing' );
		}

		return $array;
	}

	/**
	 * Checks required user capabilities and whether the theme has the
	 * feature support required by the section.
	 *
	 * @since 3.4.0
	 *
	 * @return bool False if theme doesn't support the section or user doesn't have the capability.
	 */
	final public function check_capabilities() {
		if ( $this->capability && ! call_user_func_array( 'current_user_can', (array) $this->capability ) ) {
			return false;
		}

		if ( $this->theme_supports && ! call_user_func_array( 'current_theme_supports', (array) $this->theme_supports ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Get the section's content for insertion into the Customizer pane.
	 *
	 * @since 4.1.0
	 *
	 * @return string Contents of the section.
	 */
	final public function get_content() {
		ob_start();
		$this->maybe_render();
		return trim( ob_get_clean() );
	}

	/**
	 * Check capabilities and render the section.
	 *
	 * @since 3.4.0
	 */
	final public function maybe_render() {
		if ( ! $this->check_capabilities() ) {
			return;
		}

		/**
		 * Fires before rendering a Customizer section.
		 *
		 * @since 3.4.0
		 *
		 * @param WP_Customize_Section $this WP_Customize_Section instance.
		 */
		do_action( 'customize_render_section', $this );
		/**
		 * Fires before rendering a specific Customizer section.
		 *
		 * The dynamic portion of the hook name, `$this->id`, refers to the ID
		 * of the specific Customizer section to be rendered.
		 *
		 * @since 3.4.0
		 */
		do_action( "customize_render_section_{$this->id}" );

		$this->render();
	}

	/**
	 * Render the section UI in a subclass.
	 *
	 * Sections are now rendered in JS by default, see {@see WP_Customize_Section::print_template()}.
	 *
	 * @since 3.4.0
	 */
	protected function render() {}

	/**
	 * Render the section's JS template.
	 *
	 * This function is only run for section types that have been registered with
	 * WP_Customize_Manager::register_section_type().
	 *
	 * @since 4.3.0
	 * @access public
	 *
	 * @see WP_Customize_Manager::render_template()
	 */
	public function print_template() {
        ?>
		<script type="text/html" id="tmpl-customize-section-<?php echo $this->type; ?>">
			<?php $this->render_template(); ?>
		</script>
        <?php
	}

	/**
	 * An Underscore (JS) template for rendering this section.
	 *
	 * Class variables for this section class are available in the `data` JS object;
	 * export custom variables by overriding WP_Customize_Section::json().
	 *
	 * @since 4.3.0
	 * @access protected
	 *
	 * @see WP_Customize_Section::print_template()
	 */
	protected function render_template() {
		?>
		<li id="accordion-section-{{ data.id }}" class="accordion-section control-section control-section-{{ data.type }}">
			<h3 class="accordion-section-title" tabindex="0">
				{{ data.title }}
				<span class="screen-reader-text"><?php _e( 'Press return or enter to open' ); ?></span>
			</h3>
			<ul class="accordion-section-content">
				<li class="customize-section-description-container">
					<div class="customize-section-title">
						<button class="customize-section-back" tabindex="-1">
							<span class="screen-reader-text"><?php _e( 'Back' ); ?></span>
						</button>
						<h3>
							<span class="customize-action">
								{{{ data.customizeAction }}}
							</span>
							{{ data.title }}
						</h3>
					</div>
					<# if ( data.description ) { #>
						<div class="description customize-section-description">
							{{{ data.description }}}
						</div>
					<# } #>
				</li>
			</ul>
		</li>
		<?php
	}
}

/**
 * Customize Themes Section class.
 *
 * A UI container for theme controls, which behaves like a backwards Panel.
 *
 * @since 4.2.0
 *
 * @see WP_Customize_Section
 */
class WP_Customize_Themes_Section extends WP_Customize_Section {

	/**
	 * Customize section type.
	 *
	 * @since 4.2.0
	 * @access public
	 * @var string
	 */
	public $type = 'themes';

	/**
	 * Render the themes section, which behaves like a panel.
	 *
	 * @since 4.2.0
	 * @access protected
	 */
	protected function render() {
		$classes = 'accordion-section control-section control-section-' . $this->type;
		?>
		<li id="accordion-section-<?php echo esc_attr( $this->id ); ?>" class="<?php echo esc_attr( $classes ); ?>">
			<h3 class="accordion-section-title">
				<?php
				if ( $this->manager->is_theme_active() ) {
					echo '<span class="customize-action">' . __( 'Active theme' ) . '</span> ' . $this->title;
				} else {
					echo '<span class="customize-action">' . __( 'Previewing theme' ) . '</span> ' . $this->title;
				}
				?>

				<button type="button" class="button change-theme" tabindex="0"><?php _ex( 'Change', 'theme' ); ?></button>
			</h3>
			<div class="customize-themes-panel control-panel-content themes-php">
				<h3 class="accordion-section-title customize-section-title">
					<span class="customize-action"><?php _e( 'Customizing' ); ?></span>
					<?php _e( 'Themes' ); ?>
					<span class="title-count theme-count"><?php echo count( $this->controls ) + 1 /* Active theme */; ?></span>
				</h3>
				<h3 class="accordion-section-title customize-section-title">
					<?php
					if ( $this->manager->is_theme_active() ) {
						echo '<span class="customize-action">' . __( 'Active theme' ) . '</span> ' . $this->title;
					} else {
						echo '<span class="customize-action">' . __( 'Previewing theme' ) . '</span> ' . $this->title;
					}
					?>
					<button type="button" class="button customize-theme"><?php _e( 'Customize' ); ?></button>
				</h3>

				<div class="theme-overlay" tabindex="0" role="dialog" aria-label="<?php esc_attr_e( 'Theme Details' ); ?>"></div>

				<div id="customize-container"></div>
				<?php if ( count( $this->controls ) > 4 ) : ?>
					<p><label for="themes-filter">
						<span class="screen-reader-text"><?php _e( 'Search installed themes...' ); ?></span>
						<input type="text" id="themes-filter" placeholder="<?php esc_attr_e( 'Search installed themes...' ); ?>" />
					</label></p>
				<?php endif; ?>
				<div class="theme-browser rendered">
					<ul class="themes accordion-section-content">
					</ul>
				</div>
			</div>
		</li>
<?php }
}

/**
 * Customizer section representing widget area (sidebar).
 *
 * @since 4.1.0
 *
 * @see WP_Customize_Section
 */
class WP_Customize_Sidebar_Section extends WP_Customize_Section {

	/**
	 * Type of this section.
	 *
	 * @since 4.1.0
	 * @access public
	 * @var string
	 */
	public $type = 'sidebar';

	/**
	 * Unique identifier.
	 *
	 * @since 4.1.0
	 * @access public
	 * @var string
	 */
	public $sidebar_id;

	/**
	 * Gather the parameters passed to client JavaScript via JSON.
	 *
	 * @since 4.1.0
	 *
	 * @return array The array to be exported to the client as JSON.
	 */
	public function json() {
		$json = parent::json();
		$json['sidebarId'] = $this->sidebar_id;
		return $json;
	}

	/**
	 * Whether the current sidebar is rendered on the page.
	 *
	 * @since 4.1.0
	 * @access public
	 *
	 * @return bool Whether sidebar is rendered.
	 */
	public function active_callback() {
		return $this->manager->widgets->is_sidebar_rendered( $this->sidebar_id );
	}
}

/**
 * Customize Menu Section Class
 *
 * Custom section only needed in JS.
 *
 * @since 4.3.0
 *
 * @see WP_Customize_Section
 */
class WP_Customize_Nav_Menu_Section extends WP_Customize_Section {

	/**
	 * Control type.
	 *
	 * @since 4.3.0
	 * @access public
	 * @var string
	 */
	public $type = 'nav_menu';

	/**
	 * Get section parameters for JS.
	 *
	 * @since 4.3.0
	 * @access public
	 * @return array Exported parameters.
	 */
	public function json() {
		$exported = parent::json();
		$exported['menu_id'] = intval( preg_replace( '/^nav_menu\[(\d+)\]/', '$1', $this->id ) );

		return $exported;
	}
}

/**
 * Customize Menu Section Class
 *
 * Implements the new-menu-ui toggle button instead of a regular section.
 *
 * @since 4.3.0
 *
 * @see WP_Customize_Section
 */
class WP_Customize_New_Menu_Section extends WP_Customize_Section {

	/**
	 * Control type.
	 *
	 * @since 4.3.0
	 * @access public
	 * @var string
	 */
	public $type = 'new_menu';

	/**
	 * Render the section, and the controls that have been added to it.
	 *
	 * @since 4.3.0
	 * @access protected
	 */
	protected function render() {
		?>
		<li id="accordion-section-<?php echo esc_attr( $this->id ); ?>" class="accordion-section-new-menu">
			<button type="button" class="button-secondary add-new-menu-item add-menu-toggle" aria-expanded="false">
				<?php echo esc_html( $this->title ); ?>
			</button>
			<ul class="new-menu-section-content"></ul>
		</li>
		<?php
	}
}
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              <?php
/**
 * WordPress Customize Panel classes
 *
 * @package WordPress
 * @subpackage Customize
 * @since 4.0.0
 */

/**
 * Customize Panel class.
 *
 * A UI container for sections, managed by the WP_Customize_Manager.
 *
 * @since 4.0.0
 *
 * @see WP_Customize_Manager
 */
class WP_Customize_Panel {

	/**
	 * Incremented with each new class instantiation, then stored in $instance_number.
	 *
	 * Used when sorting two instances whose priorities are equal.
	 *
	 * @since 4.1.0
	 *
	 * @static
	 * @access protected
	 * @static
	 * @var int
	 */
	protected static $instance_count = 0;

	/**
	 * Order in which this instance was created in relation to other instances.
	 *
	 * @since 4.1.0
	 * @access public
	 * @var int
	 */
	public $instance_number;

	/**
	 * WP_Customize_Manager instance.
	 *
	 * @since 4.0.0
	 * @access public
	 * @var WP_Customize_Manager
	 */
	public $manager;

	/**
	 * Unique identifier.
	 *
	 * @since 4.0.0
	 * @access public
	 * @var string
	 */
	public $id;

	/**
	 * Priority of the panel, defining the display order of panels and sections.
	 *
	 * @since 4.0.0
	 * @access public
	 * @var integer
	 */
	public $priority = 160;

	/**
	 * Capability required for the panel.
	 *
	 * @since 4.0.0
	 * @access public
	 * @var string
	 */
	public $capability = 'edit_theme_options';

	/**
	 * Theme feature support for the panel.
	 *
	 * @since 4.0.0
	 * @access public
	 * @var string|array
	 */
	public $theme_supports = '';

	/**
	 * Title of the panel to show in UI.
	 *
	 * @since 4.0.0
	 * @access public
	 * @var string
	 */
	public $title = '';

	/**
	 * Description to show in the UI.
	 *
	 * @since 4.0.0
	 * @access public
	 * @var string
	 */
	public $description = '';

	/**
	 * Customizer sections for this panel.
	 *
	 * @since 4.0.0
	 * @access public
	 * @var array
	 */
	public $sections;

	/**
	 * Type of this panel.
	 *
	 * @since 4.1.0
	 * @access public
	 * @var string
	 */
	public $type = 'default';

	/**
	 * Active callback.
	 *
	 * @since 4.1.0
	 * @access public
	 *
	 * @see WP_Customize_Section::active()
	 *
	 * @var callable Callback is called with one argument, the instance of
	 *               {@see WP_Customize_Section}, and returns bool to indicate
	 *               whether the section is active (such as it relates to the URL
	 *               currently being previewed).
	 */
	public $active_callback = '';

	/**
	 * Constructor.
	 *
	 * Any supplied $args override class property defaults.
	 *
	 * @since 4.0.0
	 *
	 * @param WP_Customize_Manager $manager Customizer bootstrap instance.
	 * @param string               $id      An specific ID for the panel.
	 * @param array                $args    Panel arguments.
	 */
	public function __construct( $manager, $id, $args = array() ) {
		$keys = array_keys( get_object_vars( $this ) );
		foreach ( $keys as $key ) {
			if ( isset( $args[ $key ] ) ) {
				$this->$key = $args[ $key ];
			}
		}

		$this->manager = $manager;
		$this->id = $id;
		if ( empty( $this->active_callback ) ) {
			$this->active_callback = array( $this, 'active_callback' );
		}
		self::$instance_count += 1;
		$this->instance_number = self::$instance_count;

		$this->sections = array(); // Users cannot customize the $sections array.
	}

	/**
	 * Check whether panel is active to current Customizer preview.
	 *
	 * @since 4.1.0
	 * @access public
	 *
	 * @return bool Whether the panel is active to the current preview.
	 */
	final public function active() {
		$panel = $this;
		$active = call_user_func( $this->active_callback, $this );

		/**
		 * Filter response of WP_Customize_Panel::active().
		 *
		 * @since 4.1.0
		 *
		 * @param bool               $active  Whether the Customizer panel is active.
		 * @param WP_Customize_Panel $panel   {@see WP_Customize_Panel} instance.
		 */
		$active = apply_filters( 'customize_panel_active', $active, $panel );

		return $active;
	}

	/**
	 * Default callback used when invoking {@see WP_Customize_Panel::active()}.
	 *
	 * Subclasses can override this with their specific logic, or they may
	 * provide an 'active_callback' argument to the constructor.
	 *
	 * @since 4.1.0
	 * @access public
	 *
	 * @return bool Always true.
	 */
	public function active_callback() {
		return true;
	}

	/**
	 * Gather the parameters passed to client JavaScript via JSON.
	 *
	 * @since 4.1.0
	 *
	 * @return array The array to be exported to the client as JSON.
	 */
	public function json() {
		$array = wp_array_slice_assoc( (array) $this, array( 'id', 'description', 'priority', 'type' ) );
		$array['title'] = html_entity_decode( $this->title, ENT_QUOTES, get_bloginfo( 'charset' ) );
		$array['content'] = $this->get_content();
		$array['active'] = $this->active();
		$array['instanceNumber'] = $this->instance_number;
		return $array;
	}

	/**
	 * Checks required user capabilities and whether the theme has the
	 * feature support required by the panel.
	 *
	 * @since 4.0.0
	 *
	 * @return bool False if theme doesn't support the panel or the user doesn't have the capability.
	 */
	final public function check_capabilities() {
		if ( $this->capability && ! call_user_func_array( 'current_user_can', (array) $this->capability ) ) {
			return false;
		}

		if ( $this->theme_supports && ! call_user_func_array( 'current_theme_supports', (array) $this->theme_supports ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Get the panel's content template for insertion into the Customizer pane.
	 *
	 * @since 4.1.0
	 *
	 * @return string Content for the panel.
	 */
	final public function get_content() {
		ob_start();
		$this->maybe_render();
		return trim( ob_get_clean() );
	}

	/**
	 * Check capabilities and render the panel.
	 *
	 * @since 4.0.0
	 */
	final public function maybe_render() {
		if ( ! $this->check_capabilities() ) {
			return;
		}

		/**
		 * Fires before rendering a Customizer panel.
		 *
		 * @since 4.0.0
		 *
		 * @param WP_Customize_Panel $this WP_Customize_Panel instance.
		 */
		do_action( 'customize_render_panel', $this );

		/**
		 * Fires before rendering a specific Customizer panel.
		 *
		 * The dynamic portion of the hook name, `$this->id`, refers to
		 * the ID of the specific Customizer panel to be rendered.
		 *
		 * @since 4.0.0
		 */
		do_action( "customize_render_panel_{$this->id}" );

		$this->render();
	}

	/**
	 * Render the panel container, and then its contents (via `this->render_content()`) in a subclass.
	 *
	 * Panel containers are now rendered in JS by default, see {@see WP_Customize_Panel::print_template()}.
	 *
	 * @since 4.0.0
	 * @access protected
	 */
	protected function render() {}

	/**
	 * Render the panel UI in a subclass.
	 *
	 * Panel contents are now rendered in JS by default, see {@see WP_Customize_Panel::print_template()}.
	 *
	 * @since 4.1.0
	 * @access protected
	 */
	protected function render_content() {}

	/**
	 * Render the panel's JS templates.
	 *
	 * This function is only run for panel types that have been registered with
	 * WP_Customize_Manager::register_panel_type().
	 *
	 * @since 4.3.0
	 *
	 * @see WP_Customize_Manager::register_panel_type()
	 */
	public function print_template() {
		?>
		<script type="text/html" id="tmpl-customize-panel-<?php echo esc_attr( $this->type ); ?>-content">
			<?php $this->content_template(); ?>
		</script>
		<script type="text/html" id="tmpl-customize-panel-<?php echo esc_attr( $this->type ); ?>">
			<?php $this->render_template(); ?>
		</script>
        <?php
	}

	/**
	 * An Underscore (JS) template for rendering this panel's container.
	 *
	 * Class variables for this panel class are available in the `data` JS object;
	 * export custom variables by overriding WP_Customize_Panel::json().
	 *
	 * @see WP_Customize_Panel::print_template()
	 *
	 * @since 4.3.0
	 * @access protected
	 */
	protected function render_template() {
		?>
		<li id="accordion-panel-{{ data.id }}" class="accordion-section control-section control-panel control-panel-{{ data.type }}">
			<h3 class="accordion-section-title" tabindex="0">
				{{ data.title }}
				<span class="screen-reader-text"><?php _e( 'Press return or enter to open this panel' ); ?></span>
			</h3>
			<ul class="accordion-sub-container control-panel-content"></ul>
		</li>
		<?php
	}

	/**
	 * An Underscore (JS) template for this panel's content (but not its container).
	 *
	 * Class variables for this panel class are available in the `data` JS object;
	 * export custom variables by overriding WP_Customize_Panel::json().
	 *
	 * @see WP_Customize_Panel::print_template()
	 *
	 * @since 4.3.0
	 * @access protected
	 */
	protected function content_template() {
		?>
		<li class="panel-meta customize-info accordion-section <# if ( ! data.description ) { #> cannot-expand<# } #>">
			<button class="customize-panel-back" tabindex="-1"><span class="screen-reader-text"><?php _e( 'Back' ); ?></span></button>
			<div class="accordion-section-title">
				<span class="preview-notice"><?php
					/* translators: %s is the site/panel title in the Customizer */
					echo sprintf( __( 'You are customizing %s' ), '<strong class="panel-title">{{ data.title }}</strong>' );
				?></span>
				<button class="customize-help-toggle dashicons dashicons-editor-help" tabindex="0" aria-expanded="false"><span class="screen-reader-text"><?php _e( 'Help' ); ?></span></button>
			</div>
			<# if ( data.description ) { #>
				<div class="description customize-panel-description">
					{{{ data.description }}}
				</div>
			<# } #>
		</li>
		<?php
	}
}

/**
 * Customize Nav Menus Panel Class
 *
 * Needed to add screen options.
 *
 * @since 4.3.0
 *
 * @see WP_Customize_Panel
 */
class WP_Customize_Nav_Menus_Panel extends WP_Customize_Panel {

	/**
	 * Control type.
	 *
	 * @since 4.3.0
	 * @access public
	 * @var string
	 */
	public $type = 'nav_menus';

	/**
	 * Render screen options for Menus.
	 *
	 * @since 4.3.0
	 * @access public
	 */
	public function render_screen_options() {
		// Essentially adds the screen options.
		add_filter( 'manage_nav-menus_columns', array( $this, 'wp_nav_menu_manage_columns' ) );

		// Display screen options.
		$screen = WP_Screen::get( 'nav-menus.php' );
		$screen->render_screen_options();
	}

	/**
	 * Returns the advanced options for the nav menus page.
	 *
	 * Link title attribute added as it's a relatively advanced concept for new users.
	 *
	 * @since 4.3.0
	 * @access public
	 *
	 * @return array The advanced menu properties.
	 */
	public function wp_nav_menu_manage_columns() {
		return array(
			'_title'      => __( 'Show advanced menu properties' ),
			'cb'          => '<input type="checkbox" />',
			'link-target' => __( 'Link Target' ),
			'attr-title'  => __( 'Title Attribute' ),
			'css-classes' => __( 'CSS Classes' ),
			'xfn'         => __( 'Link Relationship (XFN)' ),
			'description' => __( 'Description' ),
		);
	}

	/**
	 * An Underscore (JS) template for this panel's content (but not its container).
	 *
	 * Class variables for this panel class are available in the `data` JS object;
	 * export custom variables by overriding WP_Customize_Panel::json().
	 *
	 * @since 4.3.0
	 * @access protected
	 *
	 * @see WP_Customize_Panel::print_template()
	 */
	protected function content_template() {
		?>
		<li class="panel-meta customize-info accordion-section <# if ( ! data.description ) { #> cannot-expand<# } #>">
			<button type="button" class="customize-panel-back" tabindex="-1">
				<span class="screen-reader-text"><?php _e( 'Back' ); ?></span>
			</button>
			<div class="accordion-section-title">
				<span class="preview-notice">
					<?php
					/* Translators: %s is the site/panel title in the Customizer. */
					printf( __( 'You are customizing %s' ), '<strong class="panel-title">{{ data.title }}</strong>' );
					?>
				</span>
				<button type="button" class="customize-help-toggle dashicons dashicons-editor-help" aria-expanded="false">
					<span class="screen-reader-text"><?php _e( 'Help' ); ?></span>
				</button>
				<button type="button" class="customize-screen-options-toggle" aria-expanded="false">
					<span class="screen-reader-text"><?php _e( 'Menu Options' ); ?></span>
				</button>
			</div>
			<# if ( data.description ) { #>
			<div class="description customize-panel-description">{{{ data.description }}}</div>
			<# } #>
			<?php $this->render_screen_options(); ?>
		</li>
	<?php
	}
}
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   <?php
/**
 * The WordPress Toolbar
 *
 * @since 3.1.0
 *
 * @package WordPress
 * @subpackage Toolbar
 */
class WP_Admin_Bar {
	private $nodes = array();
	private $bound = false;
	public $user;

	/**
	 * @param string $name
	 * @return string|array|void
	 */
	public function __get( $name ) {
		switch ( $name ) {
			case 'proto' :
				return is_ssl() ? 'https://' : 'http://';

			case 'menu' :
				_deprecated_argument( 'WP_Admin_Bar', '3.3', 'Modify admin bar nodes with WP_Admin_Bar::get_node(), WP_Admin_Bar::add_node(), and WP_Admin_Bar::remove_node(), not the <code>menu</code> property.' );
				return array(); // Sorry, folks.
		}
	}

	/**
	 * @access public
	 */
	public function initialize() {
		$this->user = new stdClass;

		if ( is_user_logged_in() ) {
			/* Populate settings we need for the menu based on the current user. */
			$this->user->blogs = get_blogs_of_user( get_current_user_id() );
			if ( is_multisite() ) {
				$this->user->active_blog = get_active_blog_for_user( get_current_user_id() );
				$this->user->domain = empty( $this->user->active_blog ) ? user_admin_url() : trailingslashit( get_home_url( $this->user->active_blog->blog_id ) );
				$this->user->account_domain = $this->user->domain;
			} else {
				$this->user->active_blog = $this->user->blogs[get_current_blog_id()];
				$this->user->domain = trailingslashit( home_url() );
				$this->user->account_domain = $this->user->domain;
			}
		}

		add_action( 'wp_head', 'wp_admin_bar_header' );

		add_action( 'admin_head', 'wp_admin_bar_header' );

		if ( current_theme_supports( 'admin-bar' ) ) {
			/**
			 * To remove the default padding styles from WordPress for the Toolbar, use the following code:
			 * add_theme_support( 'admin-bar', array( 'callback' => '__return_false' ) );
			 */
			$admin_bar_args = get_theme_support( 'admin-bar' );
			$header_callback = $admin_bar_args[0]['callback'];
		}

		if ( empty($header_callback) )
			$header_callback = '_admin_bar_bump_cb';

		add_action('wp_head', $header_callback);

		wp_enqueue_script( 'admin-bar' );
		wp_enqueue_style( 'admin-bar' );

		/**
		 * Fires after WP_Admin_Bar is initialized.
		 *
		 * @since 3.1.0
		 */
		do_action( 'admin_bar_init' );
	}

	/**
	 * @param array $node
	 */
	public function add_menu( $node ) {
		$this->add_node( $node );
	}

	/**
	 * @param string $id
	 */
	public function remove_menu( $id ) {
		$this->remove_node( $id );
	}

	/**
	 * Add a node to the menu.
	 *
	 * @param array $args {
	 *     Arguments for adding a node.
	 *
	 *     @type string $id     ID of the item.
	 *     @type string $title  Title of the node.
	 *     @type string $parent Optional. ID of the parent node.
	 *     @type string $href   Optional. Link for the item.
	 *     @type bool   $group  Optional. Whether or not the node is a group. Default false.
	 *     @type array  $meta   Meta data including the following keys: 'html', 'class', 'rel',
	 *                          'onclick', 'target', 'title', 'tabindex'. Default empty.
	 * }
	 */
	public function add_node( $args ) {
		// Shim for old method signature: add_node( $parent_id, $menu_obj, $args )
		if ( func_num_args() >= 3 && is_string( func_get_arg(0) ) )
			$args = array_merge( array( 'parent' => func_get_arg(0) ), func_get_arg(2) );

		if ( is_object( $args ) )
			$args = get_object_vars( $args );

		// Ensure we have a valid title.
		if ( empty( $args['id'] ) ) {
			if ( empty( $args['title'] ) )
				return;

			_doing_it_wrong( __METHOD__, __( 'The menu ID should not be empty.' ), '3.3' );
			// Deprecated: Generate an ID from the title.
			$args['id'] = esc_attr( sanitize_title( trim( $args['title'] ) ) );
		}

		$defaults = array(
			'id'     => false,
			'title'  => false,
			'parent' => false,
			'href'   => false,
			'group'  => false,
			'meta'   => array(),
		);

		// If the node already exists, keep any data that isn't provided.
		if ( $maybe_defaults = $this->get_node( $args['id'] ) )
			$defaults = get_object_vars( $maybe_defaults );

		// Do the same for 'meta' items.
		if ( ! empty( $defaults['meta'] ) && ! empty( $args['meta'] ) )
			$args['meta'] = wp_parse_args( $args['meta'], $defaults['meta'] );

		$args = wp_parse_args( $args, $defaults );

		$back_compat_parents = array(
			'my-account-with-avatar' => array( 'my-account', '3.3' ),
			'my-blogs'               => array( 'my-sites',   '3.3' ),
		);

		if ( isset( $back_compat_parents[ $args['parent'] ] ) ) {
			list( $new_parent, $version ) = $back_compat_parents[ $args['parent'] ];
			_deprecated_argument( __METHOD__, $version, sprintf( 'Use <code>%s</code> as the parent for the <code>%s</code> admin bar node instead of <code>%s</code>.', $new_parent, $args['id'], $args['parent'] ) );
			$args['parent'] = $new_parent;
		}

		$this->_set_node( $args );
	}

	/**
	 * @param array $args
	 */
	final protected function _set_node( $args ) {
		$this->nodes[ $args['id'] ] = (object) $args;
	}

	/**
	 * Gets a node.
	 *
	 * @param string $id
	 * @return object Node.
	 */
	final public function get_node( $id ) {
		if ( $node = $this->_get_node( $id ) )
			return clone $node;
	}

	/**
	 * @param string $id
	 * @return object|void
	 */
	final protected function _get_node( $id ) {
		if ( $this->bound )
			return;

		if ( empty( $id ) )
			$id = 'root';

		if ( isset( $this->nodes[ $id ] ) )
			return $this->nodes[ $id ];
	}

	/**
	 * @return array|void
	 */
	final public function get_nodes() {
		if ( ! $nodes = $this->_get_nodes() )
			return;

		foreach ( $nodes as &$node ) {
			$node = clone $node;
		}
		return $nodes;
	}

	/**
	 * @return array|void
	 */
	final protected function _get_nodes() {
		if ( $this->bound )
			return;

		return $this->nodes;
	}

	/**
	 * Add a group to a menu node.
	 *
	 * @since 3.3.0
	 *
	 * @param array $args {
	 *     Array of arguments for adding a group.
	 *
	 *     @type string $id     ID of the item.
	 *     @type string $parent Optional. ID of the parent node. Default 'root'.
	 *     @type array  $meta   Meta data for the group including the following keys:
	 *                         'class', 'onclick', 'target', and 'title'.
	 * }
	 */
	final public function add_group( $args ) {
		$args['group'] = true;

		$this->add_node( $args );
	}

	/**
	 * Remove a node.
	 *
	 * @param string $id The ID of the item.
	 */
	public function remove_node( $id ) {
		$this->_unset_node( $id );
	}

	/**
	 * @param string $id
	 */
	final protected function _unset_node( $id ) {
		unset( $this->nodes[ $id ] );
	}

	/**
	 * @access public
	 */
	public function render() {
		$root = $this->_bind();
		if ( $root )
			$this->_render( $root );
	}

	/**
	 * @return object|void
	 */
	final protected function _bind() {
		if ( $this->bound )
			return;

		// Add the root node.
		// Clear it first, just in case. Don't mess with The Root.
		$this->remove_node( 'root' );
		$this->add_node( array(
			'id'    => 'root',
			'group' => false,
		) );

		// Normalize nodes: define internal 'children' and 'type' properties.
		foreach ( $this->_get_nodes() as $node ) {
			$node->children = array();
			$node->type = ( $node->group ) ? 'group' : 'item';
			unset( $node->group );

			// The Root wants your orphans. No lonely items allowed.
			if ( ! $node->parent )
				$node->parent = 'root';
		}

		foreach ( $this->_get_nodes() as $node ) {
			if ( 'root' == $node->id )
				continue;

			// Fetch the parent node. If it isn't registered, ignore the node.
			if ( ! $parent = $this->_get_node( $node->parent ) ) {
				continue;
			}

			// Generate the group class (we distinguish between top level and other level groups).
			$group_class = ( $node->parent == 'root' ) ? 'ab-top-menu' : 'ab-submenu';

			if ( $node->type == 'group' ) {
				if ( empty( $node->meta['class'] ) )
					$node->meta['class'] = $group_class;
				else
					$node->meta['class'] .= ' ' . $group_class;
			}

			// Items in items aren't allowed. Wrap nested items in 'default' groups.
			if ( $parent->type == 'item' && $node->type == 'item' ) {
				$default_id = $parent->id . '-default';
				$default    = $this->_get_node( $default_id );

				// The default group is added here to allow groups that are
				// added before standard menu items to render first.
				if ( ! $default ) {
					// Use _set_node because add_node can be overloaded.
					// Make sure to specify default settings for all properties.
					$this->_set_node( array(
						'id'        => $default_id,
						'parent'    => $parent->id,
						'type'      => 'group',
						'children'  => array(),
						'meta'      => array(
							'class'     => $group_class,
						),
						'title'     => false,
						'href'      => false,
					) );
					$default = $this->_get_node( $default_id );
					$parent->children[] = $default;
				}
				$parent = $default;

			// Groups in groups aren't allowed. Add a special 'container' node.
			// The container will invisibly wrap both groups.
			} elseif ( $parent->type == 'group' && $node->type == 'group' ) {
				$container_id = $parent->id . '-container';
				$container    = $this->_get_node( $container_id );

				// We need to create a container for this group, life is sad.
				if ( ! $container ) {
					// Use _set_node because add_node can be overloaded.
					// Make sure to specify default settings for all properties.
					$this->_set_node( array(
						'id'       => $container_id,
						'type'     => 'container',
						'children' => array( $parent ),
						'parent'   => false,
						'title'    => false,
						'href'     => false,
						'meta'     => array(),
					) );

					$container = $this->_get_node( $container_id );

					// Link the container node if a grandparent node exists.
					$grandparent = $this->_get_node( $parent->parent );

					if ( $grandparent ) {
						$container->parent = $grandparent->id;

						$index = array_search( $parent, $grandparent->children, true );
						if ( $index === false )
							$grandparent->children[] = $container;
						else
							array_splice( $grandparent->children, $index, 1, array( $container ) );
					}

					$parent->parent = $container->id;
				}

				$parent = $container;
			}

			// Update the parent ID (it might have changed).
			$node->parent = $parent->id;

			// Add the node to the tree.
			$parent->children[] = $node;
		}

		$root = $this->_get_node( 'root' );
		$this->bound = true;
		return $root;
	}

	/**
	 *
	 * @global bool $is_IE
	 * @param object $root
	 */
	final protected function _render( $root ) {
		global $is_IE;

		// Add browser classes.
		// We have to do this here since admin bar shows on the front end.
		$class = 'nojq nojs';
		if ( $is_IE ) {
			if ( strpos( $_SERVER['HTTP_USER_AGENT'], 'MSIE 7' ) )
				$class .= ' ie7';
			elseif ( strpos( $_SERVER['HTTP_USER_AGENT'], 'MSIE 8' ) )
				$class .= ' ie8';
			elseif ( strpos( $_SERVER['HTTP_USER_AGENT'], 'MSIE 9' ) )
				$class .= ' ie9';
		} elseif ( wp_is_mobile() ) {
			$class .= ' mobile';
		}

		?>
		<div id="wpadminbar" class="<?php echo $class; ?>">
			<?php if ( ! is_admin() ) { ?>
				<a class="screen-reader-shortcut" href="#wp-toolbar" tabindex="1"><?php _e( 'Skip to toolbar' ); ?></a>
			<?php } ?>
			<div class="quicklinks" id="wp-toolbar" role="navigation" aria-label="<?php esc_attr_e( 'Toolbar' ); ?>" tabindex="0">
				<?php foreach ( $root->children as $group ) {
					$this->_render_group( $group );
				} ?>
			</div>
			<?php if ( is_user_logged_in() ) : ?>
			<a class="screen-reader-shortcut" href="<?php echo esc_url( wp_logout_url() ); ?>"><?php _e('Log Out'); ?></a>
			<?php endif; ?>
		</div>

		<?php
	}

	/**
	 * @param object $node
	 */
	final protected function _render_container( $node ) {
		if ( $node->type != 'container' || empty( $node->children ) )
			return;

		?><div id="<?php echo esc_attr( 'wp-admin-bar-' . $node->id ); ?>" class="ab-group-container"><?php
			foreach ( $node->children as $group ) {
				$this->_render_group( $group );
			}
		?></div><?php
	}

	/**
	 * @param object $node
	 */
	final protected function _render_group( $node ) {
		if ( $node->type == 'container' ) {
			$this->_render_container( $node );
			return;
		}
		if ( $node->type != 'group' || empty( $node->children ) )
			return;

		if ( ! empty( $node->meta['class'] ) )
			$class = ' class="' . esc_attr( trim( $node->meta['class'] ) ) . '"';
		else
			$class = '';

		?><ul id="<?php echo esc_attr( 'wp-admin-bar-' . $node->id ); ?>"<?php echo $class; ?>><?php
			foreach ( $node->children as $item ) {
				$this->_render_item( $item );
			}
		?></ul><?php
	}

	/**
	 * @param object $node
	 */
	final protected function _render_item( $node ) {
		if ( $node->type != 'item' )
			return;

		$is_parent = ! empty( $node->children );
		$has_link  = ! empty( $node->href );

		$tabindex = isset( $node->meta['tabindex'] ) ? (int) $node->meta['tabindex'] : '';
		$aria_attributes = $tabindex ? 'tabindex="' . $tabindex . '"' : '';

		$menuclass = '';

		if ( $is_parent ) {
			$menuclass = 'menupop ';
			$aria_attributes .= ' aria-haspopup="true"';
		}

		if ( ! empty( $node->meta['class'] ) )
			$menuclass .= $node->meta['class'];

		if ( $menuclass )
			$menuclass = ' class="' . esc_attr( trim( $menuclass ) ) . '"';

		?>

		<li id="<?php echo esc_attr( 'wp-admin-bar-' . $node->id ); ?>"<?php echo $menuclass; ?>><?php
			if ( $has_link ):
				?><a class="ab-item" <?php echo $aria_attributes; ?> href="<?php echo esc_url( $node->href ) ?>"<?php
					if ( ! empty( $node->meta['onclick'] ) ) :
						?> onclick="<?php echo esc_js( $node->meta['onclick'] ); ?>"<?php
					endif;
				if ( ! empty( $node->meta['target'] ) ) :
					?> target="<?php echo esc_attr( $node->meta['target'] ); ?>"<?php
				endif;
				if ( ! empty( $node->meta['title'] ) ) :
					?> title="<?php echo esc_attr( $node->meta['title'] ); ?>"<?php
				endif;
				if ( ! empty( $node->meta['rel'] ) ) :
					?> rel="<?php echo esc_attr( $node->meta['rel'] ); ?>"<?php
				endif;
				?>><?php
			else:
				?><div class="ab-item ab-empty-item" <?php echo $aria_attributes;
				if ( ! empty( $node->meta['title'] ) ) :
					?> title="<?php echo esc_attr( $node->meta['title'] ); ?>"<?php
				endif;
				?>><?php
			endif;

			echo $node->title;

			if ( $has_link ) :
				?></a><?php
			else:
				?></div><?php
			endif;

			if ( $is_parent ) :
				?><div class="ab-sub-wrapper"><?php
					foreach ( $node->children as $group ) {
						$this->_render_group( $group );
					}
				?></div><?php
			endif;

			if ( ! empty( $node->meta['html'] ) )
				echo $node->meta['html'];

			?>
		</li><?php
	}

	/**
	 * @param string $id    Unused.
	 * @param object $node
	 */
	public function recursive_render( $id, $node ) {
		_deprecated_function( __METHOD__, '3.3', 'WP_Admin_bar::render(), WP_Admin_Bar::_render_item()' );
		$this->_render_item( $node );
	}

	/**
	 * @access public
	 */
	public function add_menus() {
		// User related, aligned right.
		add_action( 'admin_bar_menu', 'wp_admin_bar_my_account_menu', 0 );
		add_action( 'admin_bar_menu', 'wp_admin_bar_search_menu', 4 );
		add_action( 'admin_bar_menu', 'wp_admin_bar_my_account_item', 7 );

		// Site related.
		add_action( 'admin_bar_menu', 'wp_admin_bar_sidebar_toggle', 0 );
		add_action( 'admin_bar_menu', 'wp_admin_bar_wp_menu', 10 );
		add_action( 'admin_bar_menu', 'wp_admin_bar_my_sites_menu', 20 );
		add_action( 'admin_bar_menu', 'wp_admin_bar_site_menu', 30 );
		add_action( 'admin_bar_menu', 'wp_admin_bar_customize_menu', 40 );
		add_action( 'admin_bar_menu', 'wp_admin_bar_updates_menu', 50 );

		// Content related.
		if ( ! is_network_admin() && ! is_user_admin() ) {
			add_action( 'admin_bar_menu', 'wp_admin_bar_comments_menu', 60 );
			add_action( 'admin_bar_menu', 'wp_admin_bar_new_content_menu', 70 );
		}
		add_action( 'admin_bar_menu', 'wp_admin_bar_edit_menu', 80 );

		add_action( 'admin_bar_menu', 'wp_admin_bar_add_secondary_groups', 200 );

		/**
		 * Fires after menus are added to the menu bar.
		 *
		 * @since 3.1.0
		 */
		do_action( 'add_admin_bar_menus' );
	}
}
                                                                                                                                                                                                                                                                                                                                                                                                                                  <?php
/**
 * mail_fetch/setup.php
 *
 * Copyright (c) 1999-2011 CDI (cdi@thewebmasters.net) All Rights Reserved
 * Modified by Philippe Mingo 2001-2009 mingo@rotedic.com
 * An RFC 1939 compliant wrapper class for the POP3 protocol.
 *
 * Licensed under the GNU GPL. For full terms see the file COPYING.
 *
 * POP3 class
 *
 * @copyright 1999-2011 The SquirrelMail Project Team
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package plugins
 * @subpackage mail_fetch
 */

class POP3 {
    var $ERROR      = '';       //  Error string.

    var $TIMEOUT    = 60;       //  Default timeout before giving up on a
                                //  network operation.

    var $COUNT      = -1;       //  Mailbox msg count

    var $BUFFER     = 512;      //  Socket buffer for socket fgets() calls.
                                //  Per RFC 1939 the returned line a POP3
                                //  server can send is 512 bytes.

    var $FP         = '';       //  The connection to the server's
                                //  file descriptor

    var $MAILSERVER = '';       // Set this to hard code the server name

    var $DEBUG      = FALSE;    // set to true to echo pop3
                                // commands and responses to error_log
                                // this WILL log passwords!

    var $BANNER     = '';       //  Holds the banner returned by the
                                //  pop server - used for apop()

    var $ALLOWAPOP  = FALSE;    //  Allow or disallow apop()
                                //  This must be set to true
                                //  manually

	/**
	 * PHP5 constructor.
	 */
    function __construct ( $server = '', $timeout = '' ) {
        settype($this->BUFFER,"integer");
        if( !empty($server) ) {
            // Do not allow programs to alter MAILSERVER
            // if it is already specified. They can get around
            // this if they -really- want to, so don't count on it.
            if(empty($this->MAILSERVER))
                $this->MAILSERVER = $server;
        }
        if(!empty($timeout)) {
            settype($timeout,"integer");
            $this->TIMEOUT = $timeout;
            if (!ini_get('safe_mode'))
                set_time_limit($timeout);
        }
        return true;
    }

	/**
	 * PHP4 constructor.
	 */
	public function POP3( $server = '', $timeout = '' ) {
		self::__construct( $server, $timeout );
	}

    function update_timer () {
        if (!ini_get('safe_mode'))
            set_time_limit($this->TIMEOUT);
        return true;
    }

    function connect ($server, $port = 110)  {
        //  Opens a socket to the specified server. Unless overridden,
        //  port defaults to 110. Returns true on success, false on fail

        // If MAILSERVER is set, override $server with it's value

    if (!isset($port) || !$port) {$port = 110;}
        if(!empty($this->MAILSERVER))
            $server = $this->MAILSERVER;

        if(empty($server)){
            $this->ERROR = "POP3 connect: " . _("No server specified");
            unset($this->FP);
            return false;
        }

        $fp = @fsockopen("$server", $port, $errno, $errstr);

        if(!$fp) {
            $this->ERROR = "POP3 connect: " . _("Error ") . "[$errno] [$errstr]";
            unset($this->FP);
            return false;
        }

        socket_set_blocking($fp,-1);
        $this->update_timer();
        $reply = fgets($fp,$this->BUFFER);
        $reply = $this->strip_clf($reply);
        if($this->DEBUG)
            error_log("POP3 SEND [connect: $server] GOT [$reply]",0);
        if(!$this->is_ok($reply)) {
            $this->ERROR = "POP3 connect: " . _("Error ") . "[$reply]";
            unset($this->FP);
            return false;
        }
        $this->FP = $fp;
        $this->BANNER = $this->parse_banner($reply);
        return true;
    }

    function user ($user = "") {
        // Sends the USER command, returns true or false

        if( empty($user) ) {
            $this->ERROR = "POP3 user: " . _("no login ID submitted");
            return false;
        } elseif(!isset($this->FP)) {
            $this->ERROR = "POP3 user: " . _("connection not established");
            return false;
        } else {
            $reply = $this->send_cmd("USER $user");
            if(!$this->is_ok($reply)) {
                $this->ERROR = "POP3 user: " . _("Error ") . "[$reply]";
                return false;
            } else
                return true;
        }
    }

    function pass ($pass = "")     {
        // Sends the PASS command, returns # of msgs in mailbox,
        // returns false (undef) on Auth failure

        if(empty($pass)) {
            $this->ERROR = "POP3 pass: " . _("No password submitted");
            return false;
        } elseif(!isset($this->FP)) {
            $this->ERROR = "POP3 pass: " . _("connection not established");
            return false;
        } else {
            $reply = $this->send_cmd("PASS $pass");
            if(!$this->is_ok($reply)) {
                $this->ERROR = "POP3 pass: " . _("Authentication failed") . " [$reply]";
                $this->quit();
                return false;
            } else {
                //  Auth successful.
                $count = $this->last("count");
                $this->COUNT = $count;
                return $count;
            }
        }
    }

    function apop ($login,$pass) {
        //  Attempts an APOP login. If this fails, it'll
        //  try a standard login. YOUR SERVER MUST SUPPORT
        //  THE USE OF THE APOP COMMAND!
        //  (apop is optional per rfc1939)

        if(!isset($this->FP)) {
            $this->ERROR = "POP3 apop: " . _("No connection to server");
            return false;
        } elseif(!$this->ALLOWAPOP) {
            $retVal = $this->login($login,$pass);
            return $retVal;
        } elseif(empty($login)) {
            $this->ERROR = "POP3 apop: " . _("No login ID submitted");
            return false;
        } elseif(empty($pass)) {
            $this->ERROR = "POP3 apop: " . _("No password submitted");
            return false;
        } else {
            $banner = $this->BANNER;
            if( (!$banner) or (empty($banner)) ) {
                $this->ERROR = "POP3 apop: " . _("No server banner") . ' - ' . _("abort");
                $retVal = $this->login($login,$pass);
                return $retVal;
            } else {
                $AuthString = $banner;
                $AuthString .= $pass;
                $APOPString = md5($AuthString);
                $cmd = "APOP $login $APOPString";
                $reply = $this->send_cmd($cmd);
                if(!$this->is_ok($reply)) {
                    $this->ERROR = "POP3 apop: " . _("apop authentication failed") . ' - ' . _("abort");
                    $retVal = $this->login($login,$pass);
                    return $retVal;
                } else {
                    //  Auth successful.
                    $count = $this->last("count");
                    $this->COUNT = $count;
                    return $count;
                }
            }
        }
    }

    function login ($login = "", $pass = "") {
        // Sends both user and pass. Returns # of msgs in mailbox or
        // false on failure (or -1, if the error occurs while getting
        // the number of messages.)

        if( !isset($this->FP) ) {
            $this->ERROR = "POP3 login: " . _("No connection to server");
            return false;
        } else {
            $fp = $this->FP;
            if( !$this->user( $login ) ) {
                //  Preserve the error generated by user()
                return false;
            } else {
                $count = $this->pass($pass);
                if( (!$count) || ($count == -1) ) {
                    //  Preserve the error generated by last() and pass()
                    return false;
                } else
                    return $count;
            }
        }
    }

    function top ($msgNum, $numLines = "0") {
        //  Gets the header and first $numLines of the msg body
        //  returns data in an array with each returned line being
        //  an array element. If $numLines is empty, returns
        //  only the header information, and none of the body.

        if(!isset($this->FP)) {
            $this->ERROR = "POP3 top: " . _("No connection to server");
            return false;
        }
        $this->update_timer();

        $fp = $this->FP;
        $buffer = $this->BUFFER;
        $cmd = "TOP $msgNum $numLines";
        fwrite($fp, "TOP $msgNum $numLines\r\n");
        $reply = fgets($fp, $buffer);
        $reply = $this->strip_clf($reply);
        if($this->DEBUG) {
            @error_log("POP3 SEND [$cmd] GOT [$reply]",0);
        }
        if(!$this->is_ok($reply))
        {
            $this->ERROR = "POP3 top: " . _("Error ") . "[$reply]";
            return false;
        }

        $count = 0;
        $MsgArray = array();

        $line = fgets($fp,$buffer);
        while ( !preg_match('/^\.\r\n/',$line))
        {
            $MsgArray[$count] = $line;
            $count++;
            $line = fgets($fp,$buffer);
            if(empty($line))    { break; }
        }

        return $MsgArray;
    }

    function pop_list ($msgNum = "") {
        //  If called with an argument, returns that msgs' size in octets
        //  No argument returns an associative array of undeleted
        //  msg numbers and their sizes in octets

        if(!isset($this->FP))
        {
            $this->ERROR = "POP3 pop_list: " . _("No connection to server");
            return false;
        }
        $fp = $this->FP;
        $Total = $this->COUNT;
        if( (!$Total) or ($Total == -1) )
        {
            return false;
        }
        if($Total == 0)
        {
            return array("0","0");
            // return -1;   // mailbox empty
        }

        $this->update_timer();

        if(!empty($msgNum))
        {
            $cmd = "LIST $msgNum";
            fwrite($fp,"$cmd\r\n");
            $reply = fgets($fp,$this->BUFFER);
            $reply = $this->strip_clf($reply);
            if($this->DEBUG) {
                @error_log("POP3 SEND [$cmd] GOT [$reply]",0);
            }
            if(!$this->is_ok($reply))
            {
                $this->ERROR = "POP3 pop_list: " . _("Error ") . "[$reply]";
                return false;
            }
            list($junk,$num,$size) = preg_split('/\s+/',$reply);
            return $size;
        }
        $cmd = "LIST";
        $reply = $this->send_cmd($cmd);
        if(!$this->is_ok($reply))
        {
            $reply = $this->strip_clf($reply);
            $this->ERROR = "POP3 pop_list: " . _("Error ") .  "[$reply]";
            return false;
        }
        $MsgArray = array();
        $MsgArray[0] = $Total;
        for($msgC=1;$msgC <= $Total; $msgC++)
        {
            if($msgC > $Total) { break; }
            $line = fgets($fp,$this->BUFFER);
            $line = $this->strip_clf($line);
            if(strpos($line, '.') === 0)
            {
                $this->ERROR = "POP3 pop_list: " . _("Premature end of list");
                return false;
            }
            list($thisMsg,$msgSize) = preg_split('/\s+/',$line);
            settype($thisMsg,"integer");
            if($thisMsg != $msgC)
            {
                $MsgArray[$msgC] = "deleted";
            }
            else
            {
                $MsgArray[$msgC] = $msgSize;
            }
        }
        return $MsgArray;
    }

    function get ($msgNum) {
        //  Retrieve the specified msg number. Returns an array
        //  where each line of the msg is an array element.

        if(!isset($this->FP))
        {
            $this->ERROR = "POP3 get: " . _("No connection to server");
            return false;
        }

        $this->update_timer();

        $fp = $this->FP;
        $buffer = $this->BUFFER;
        $cmd = "RETR $msgNum";
        $reply = $this->send_cmd($cmd);

        if(!$this->is_ok($reply))
        {
            $this->ERROR = "POP3 get: " . _("Error ") . "[$reply]";
            return false;
        }

        $count = 0;
        $MsgArray = array();

        $line = fgets($fp,$buffer);
        while ( !preg_match('/^\.\r\n/',$line))
        {
            if ( $line{0} == '.' ) { $line = substr($line,1); }
            $MsgArray[$count] = $line;
            $count++;
            $line = fgets($fp,$buffer);
            if(empty($line))    { break; }
        }
        return $MsgArray;
    }

    function last ( $type = "count" ) {
        //  Returns the highest msg number in the mailbox.
        //  returns -1 on error, 0+ on success, if type != count
        //  results in a popstat() call (2 element array returned)

        $last = -1;
        if(!isset($this->FP))
        {
            $this->ERROR = "POP3 last: " . _("No connection to server");
            return $last;
        }

        $reply = $this->send_cmd("STAT");
        if(!$this->is_ok($reply))
        {
            $this->ERROR = "POP3 last: " . _("Error ") . "[$reply]";
            return $last;
        }

        $Vars = preg_split('/\s+/',$reply);
        $count = $Vars[1];
        $size = $Vars[2];
        settype($count,"integer");
        settype($size,"integer");
        if($type != "count")
        {
            return array($count,$size);
        }
        return $count;
    }

    function reset () {
        //  Resets the status of the remote server. This includes
        //  resetting the status of ALL msgs to not be deleted.
        //  This method automatically closes the connection to the server.

        if(!isset($this->FP))
        {
            $this->ERROR = "POP3 reset: " . _("No connection to server");
            return false;
        }
        $reply = $this->send_cmd("RSET");
        if(!$this->is_ok($reply))
        {
            //  The POP3 RSET command -never- gives a -ERR
            //  response - if it ever does, something truely
            //  wild is going on.

            $this->ERROR = "POP3 reset: " . _("Error ") . "[$reply]";
            @error_log("POP3 reset: ERROR [$reply]",0);
        }
        $this->quit();
        return true;
    }

    function send_cmd ( $cmd = "" )
    {
        //  Sends a user defined command string to the
        //  POP server and returns the results. Useful for
        //  non-compliant or custom POP servers.
        //  Do NOT includ the \r\n as part of your command
        //  string - it will be appended automatically.

        //  The return value is a standard fgets() call, which
        //  will read up to $this->BUFFER bytes of data, until it
        //  encounters a new line, or EOF, whichever happens first.

        //  This method works best if $cmd responds with only
        //  one line of data.

        if(!isset($this->FP))
        {
            $this->ERROR = "POP3 send_cmd: " . _("No connection to server");
            return false;
        }

        if(empty($cmd))
        {
            $this->ERROR = "POP3 send_cmd: " . _("Empty command string");
            return "";
        }

        $fp = $this->FP;
        $buffer = $this->BUFFER;
        $this->update_timer();
        fwrite($fp,"$cmd\r\n");
        $reply = fgets($fp,$buffer);
        $reply = $this->strip_clf($reply);
        if($this->DEBUG) { @error_log("POP3 SEND [$cmd] GOT [$reply]",0); }
        return $reply;
    }

    function quit() {
        //  Closes the connection to the POP3 server, deleting
        //  any msgs marked as deleted.

        if(!isset($this->FP))
        {
            $this->ERROR = "POP3 quit: " . _("connection does not exist");
            return false;
        }
        $fp = $this->FP;
        $cmd = "QUIT";
        fwrite($fp,"$cmd\r\n");
        $reply = fgets($fp,$this->BUFFER);
        $reply = $this->strip_clf($reply);
        if($this->DEBUG) { @error_log("POP3 SEND [$cmd] GOT [$reply]",0); }
        fclose($fp);
        unset($this->FP);
        return true;
    }

    function popstat () {
        //  Returns an array of 2 elements. The number of undeleted
        //  msgs in the mailbox, and the size of the mbox in octets.

        $PopArray = $this->last("array");

        if($PopArray == -1) { return false; }

        if( (!$PopArray) or (empty($PopArray)) )
        {
            return false;
        }
        return $PopArray;
    }

    function uidl ($msgNum = "")
    {
        //  Returns the UIDL of the msg specified. If called with
        //  no arguments, returns an associative array where each
        //  undeleted msg num is a key, and the msg's uidl is the element
        //  Array element 0 will contain the total number of msgs

        if(!isset($this->FP)) {
            $this->ERROR = "POP3 uidl: " . _("No connection to server");
            return false;
        }

        $fp = $this->FP;
        $buffer = $this->BUFFER;

        if(!empty($msgNum)) {
            $cmd = "UIDL $msgNum";
            $reply = $this->send_cmd($cmd);
            if(!$this->is_ok($reply))
            {
                $this->ERROR = "POP3 uidl: " . _("Error ") . "[$reply]";
                return false;
            }
            list ($ok,$num,$myUidl) = preg_split('/\s+/',$reply);
            return $myUidl;
        } else {
            $this->update_timer();

            $UIDLArray = array();
            $Total = $this->COUNT;
            $UIDLArray[0] = $Total;

            if ($Total < 1)
            {
                return $UIDLArray;
            }
            $cmd = "UIDL";
            fwrite($fp, "UIDL\r\n");
            $reply = fgets($fp, $buffer);
            $reply = $this->strip_clf($reply);
            if($this->DEBUG) { @error_log("POP3 SEND [$cmd] GOT [$reply]",0); }
            if(!$this->is_ok($reply))
            {
                $this->ERROR = "POP3 uidl: " . _("Error ") . "[$reply]";
                return false;
            }

            $line = "";
            $count = 1;
            $line = fgets($fp,$buffer);
            while ( !preg_match('/^\.\r\n/',$line)) {
                list ($msg,$msgUidl) = preg_split('/\s+/',$line);
                $msgUidl = $this->strip_clf($msgUidl);
                if($count == $msg) {
                    $UIDLArray[$msg] = $msgUidl;
                }
                else
                {
                    $UIDLArray[$count] = 'deleted';
                }
                $count++;
                $line = fgets($fp,$buffer);
            }
        }
        return $UIDLArray;
    }

    function delete ($msgNum = "") {
        //  Flags a specified msg as deleted. The msg will not
        //  be deleted until a quit() method is called.

        if(!isset($this->FP))
        {
            $this->ERROR = "POP3 delete: " . _("No connection to server");
            return false;
        }
        if(empty($msgNum))
        {
            $this->ERROR = "POP3 delete: " . _("No msg number submitted");
            return false;
        }
        $reply = $this->send_cmd("DELE $msgNum");
        if(!$this->is_ok($reply))
        {
            $this->ERROR = "POP3 delete: " . _("Command failed ") . "[$reply]";
            return false;
        }
        return true;
    }

    //  *********************************************************

    //  The following methods are internal to the class.

    function is_ok ($cmd = "") {
        //  Return true or false on +OK or -ERR

        if( empty($cmd) )
            return false;
        else
            return( stripos($cmd, '+OK') !== false );
    }

    function strip_clf ($text = "") {
        // Strips \r\n from server responses

        if(empty($text))
            return $text;
        else {
            $stripped = str_replace(array("\r","\n"),'',$text);
            return $stripped;
        }
    }

    function parse_banner ( $server_text ) {
        $outside = true;
        $banner = "";
        $length = strlen($server_text);
        for($count =0; $count < $length; $count++)
        {
            $digit = substr($server_text,$count,1);
            if(!empty($digit))             {
                if( (!$outside) && ($digit != '<') && ($digit != '>') )
                {
                    $banner .= $digit;
                }
                if ($digit == '<')
                {
                    $outside = false;
                }
                if($digit == '>')
                {
                    $outside = true;
                }
            }
        }
        $banner = $this->strip_clf($banner);    // Just in case
        return "<$banner>";
    }

}   // End class

// For php4 compatibility
if (!function_exists("stripos")) {
    function stripos($haystack, $needle){
        return strpos($haystack, stristr( $haystack, $needle ));
    }
}
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        <?php
/**
 * API for fetching the HTML to embed remote content based on a provided URL.
 * Used internally by the {@link WP_Embed} class, but is designed to be generic.
 *
 * @link https://codex.wordpress.org/oEmbed oEmbed Codex Article
 * @link http://oembed.com/ oEmbed Homepage
 *
 * @package WordPress
 * @subpackage oEmbed
 */

/**
 * oEmbed class.
 *
 * @package WordPress
 * @subpackage oEmbed
 * @since 2.9.0
 */
class WP_oEmbed {
	public $providers = array();
	/**
	 * @static
	 * @var array
	 */
	public static $early_providers = array();

	private $compat_methods = array( '_fetch_with_format', '_parse_json', '_parse_xml', '_parse_body' );

	/**
	 * Constructor
	 *
	 * @since 2.9.0
	 */
	public function __construct() {
		$providers = array(
			'#http://((m|www)\.)?youtube\.com/watch.*#i'          => array( 'http://www.youtube.com/oembed',                      true  ),
			'#https://((m|www)\.)?youtube\.com/watch.*#i'         => array( 'http://www.youtube.com/oembed?scheme=https',         true  ),
			'#http://((m|www)\.)?youtube\.com/playlist.*#i'       => array( 'http://www.youtube.com/oembed',                      true  ),
			'#https://((m|www)\.)?youtube\.com/playlist.*#i'      => array( 'http://www.youtube.com/oembed?scheme=https',         true  ),
			'#http://youtu\.be/.*#i'                              => array( 'http://www.youtube.com/oembed',                      true  ),
			'#https://youtu\.be/.*#i'                             => array( 'http://www.youtube.com/oembed?scheme=https',         true  ),
			'http://blip.tv/*'                                    => array( 'http://blip.tv/oembed/',                             false ),
			'#https?://(.+\.)?vimeo\.com/.*#i'                    => array( 'http://vimeo.com/api/oembed.{format}',               true  ),
			'#https?://(www\.)?dailymotion\.com/.*#i'             => array( 'http://www.dailymotion.com/services/oembed',         true  ),
			'http://dai.ly/*'                                     => array( 'http://www.dailymotion.com/services/oembed',         false ),
			'#https?://(www\.)?flickr\.com/.*#i'                  => array( 'https://www.flickr.com/services/oembed/',            true  ),
			'#https?://flic\.kr/.*#i'                             => array( 'https://www.flickr.com/services/oembed/',            true  ),
			'#https?://(.+\.)?smugmug\.com/.*#i'                  => array( 'http://api.smugmug.com/services/oembed/',            true  ),
			'#https?://(www\.)?hulu\.com/watch/.*#i'              => array( 'http://www.hulu.com/api/oembed.{format}',            true  ),
			'http://i*.photobucket.com/albums/*'                  => array( 'http://photobucket.com/oembed',                      false ),
			'http://gi*.photobucket.com/groups/*'                 => array( 'http://photobucket.com/oembed',                      false ),
			'#https?://(www\.)?scribd\.com/doc/.*#i'              => array( 'http://www.scribd.com/services/oembed',              true  ),
			'#https?://wordpress.tv/.*#i'                         => array( 'http://wordpress.tv/oembed/',                        true  ),
			'#https?://(.+\.)?polldaddy\.com/.*#i'                => array( 'https://polldaddy.com/oembed/',                      true  ),
			'#https?://poll\.fm/.*#i'                             => array( 'https://polldaddy.com/oembed/',                      true  ),
			'#https?://(www\.)?funnyordie\.com/videos/.*#i'       => array( 'http://www.funnyordie.com/oembed',                   true  ),
			'#https?://(www\.)?twitter\.com/.+?/status(es)?/.*#i' => array( 'https://api.twitter.com/1/statuses/oembed.{format}', true  ),
			'#https?://vine.co/v/.*#i'                            => array( 'https://vine.co/oembed.{format}',                    true  ),
 			'#https?://(www\.)?soundcloud\.com/.*#i'              => array( 'http://soundcloud.com/oembed',                       true  ),
			'#https?://(.+?\.)?slideshare\.net/.*#i'              => array( 'https://www.slideshare.net/api/oembed/2',            true  ),
			'#https?://instagr(\.am|am\.com)/p/.*#i'              => array( 'https://api.instagram.com/oembed',                   true  ),
			'#https?://(www\.)?rdio\.com/.*#i'                    => array( 'http://www.rdio.com/api/oembed/',                    true  ),
			'#https?://rd\.io/x/.*#i'                             => array( 'http://www.rdio.com/api/oembed/',                    true  ),
			'#https?://(open|play)\.spotify\.com/.*#i'            => array( 'https://embed.spotify.com/oembed/',                  true  ),
			'#https?://(.+\.)?imgur\.com/.*#i'                    => array( 'http://api.imgur.com/oembed',                        true  ),
			'#https?://(www\.)?meetu(\.ps|p\.com)/.*#i'           => array( 'http://api.meetup.com/oembed',                       true  ),
			'#https?://(www\.)?issuu\.com/.+/docs/.+#i'           => array( 'http://issuu.com/oembed_wp',                         true  ),
			'#https?://(www\.)?collegehumor\.com/video/.*#i'      => array( 'http://www.collegehumor.com/oembed.{format}',        true  ),
			'#https?://(www\.)?mixcloud\.com/.*#i'                => array( 'http://www.mixcloud.com/oembed',                     true  ),
			'#https?://(www\.|embed\.)?ted\.com/talks/.*#i'       => array( 'http://www.ted.com/talks/oembed.{format}',           true  ),
			'#https?://(www\.)?(animoto|video214)\.com/play/.*#i' => array( 'http://animoto.com/oembeds/create',                  true  ),
			'#https?://(.+)\.tumblr\.com/post/.*#i'               => array( 'https://www.tumblr.com/oembed/1.0',                  true  ),
			'#https?://(www\.)?kickstarter\.com/projects/.*#i'    => array( 'https://www.kickstarter.com/services/oembed',        true  ),
			'#https?://kck\.st/.*#i'                              => array( 'https://www.kickstarter.com/services/oembed',        true  ),
			'#https?://cloudup\.com/.*#i'                         => array( 'https://cloudup.com/oembed', true ),
		);

		if ( ! empty( self::$early_providers['add'] ) ) {
			foreach ( self::$early_providers['add'] as $format => $data ) {
				$providers[ $format ] = $data;
			}
		}

		if ( ! empty( self::$early_providers['remove'] ) ) {
			foreach ( self::$early_providers['remove'] as $format ) {
				unset( $providers[ $format ] );
			}
		}

		self::$early_providers = array();

		/**
		 * Filter the list of oEmbed providers.
		 *
		 * Discovery is disabled for users lacking the unfiltered_html capability.
		 * Only providers in this array will be used for those users.
		 *
		 * Supported providers:
		 *
		 * | ------------ | -------------------- | ----- | --------- |
		 * |   Provider   |        Flavor        |  SSL  |   Since   |
		 * | ------------ | -------------------- | ----- | --------- |
		 * | Blip         | blip.tv              |   !   | 2.9.0     |
		 * | Dailymotion  | dailymotion.com      |  Yes  | 2.9.0     |
		 * | Flickr       | flickr.com           |  Yes  | 2.9.0     |
		 * | Hulu         | hulu.com             |  Yes  | 2.9.0     |
		 * | Photobucket  | photobucket.com      |   !   | 2.9.0     |
		 * | Scribd       | scribd.com           |  Yes  | 2.9.0     |
		 * | Vimeo        | vimeo.com            |  Yes  | 2.9.0     |
		 * | WordPress.tv | wordpress.tv         |  Yes  | 2.9.0     |
		 * | YouTube      | youtube.com/watch    |  Yes  | 2.9.0     |
		 * | ------------ | -------------------- | ----- | --------- |
		 * | Funny or Die | funnyordie.com       |  Yes  | 3.0.0     |
		 * | Polldaddy    | polldaddy.com        |  Yes  | 3.0.0     |
		 * | SmugMug      | smugmug.com          |  Yes  | 3.0.0     |
		 * | YouTube      | youtu.be             |  Yes  | 3.0.0     |
		 * | ------------ | -------------------- | ----- | --------- |
		 * | Twitter      | twitter.com          |  Yes  | 3.4.0     |
		 * | ------------ | -------------------- | ----- | --------- |
		 * | Instagram    | instagram.com        |  Yes  | 3.5.0     |
		 * | Instagram    | instagr.am           |  Yes  | 3.5.0     |
		 * | Slideshare   | slideshare.net       |  Yes  | 3.5.0     |
		 * | SoundCloud   | soundcloud.com       |  Yes  | 3.5.0     |
		 * | ------------ | -------------------- | ----- | --------- |
		 * | Dailymotion  | dai.ly               |   !   | 3.6.0     |
		 * | Flickr       | flic.kr              |  Yes  | 3.6.0     |
		 * | Rdio         | rdio.com             |  Yes  | 3.6.0     |
		 * | Rdio         | rd.io                |  Yes  | 3.6.0     |
		 * | Spotify      | spotify.com          |  Yes  | 3.6.0     |
		 * | ------------ | -------------------- | ----- | --------- |
		 * | Imgur        | imgur.com            |  Yes  | 3.9.0     |
		 * | Meetup.com   | meetup.com           |  Yes  | 3.9.0     |
		 * | Meetup.com   | meetu.ps             |  Yes  | 3.9.0     |
		 * | ------------ | -------------------- | ----- | --------- |
		 * | Animoto      | animoto.com          |  Yes  | 4.0.0     |
		 * | Animoto      | video214.com         |  Yes  | 4.0.0     |
		 * | CollegeHumor | collegehumor.com     |  Yes  | 4.0.0     |
		 * | Issuu        | issuu.com            |  Yes  | 4.0.0     |
		 * | Mixcloud     | mixcloud.com         |  Yes  | 4.0.0     |
		 * | Polldaddy    | poll.fm              |  Yes  | 4.0.0     |
		 * | TED          | ted.com              |  Yes  | 4.0.0     |
		 * | YouTube      | youtube.com/playlist |  Yes  | 4.0.0     |
		 * | ------------ | -------------------- | ----- | --------- |
		 * | Vine         | vine.co              |  Yes  | 4.1.0     |
		 * | ------------ | -------------------- | ----- | --------- |
		 * | Tumblr       | tumblr.com           |  Yes  | 4.2.0     |
		 * | Kickstarter  | kickstarter.com      |  Yes  | 4.2.0     |
		 * | Kickstarter  | kck.st               |  Yes  | 4.2.0     |
		 * | ------------ | -------------------- | ----- | --------- |
		 *
		 * No longer supported providers:
		 *
		 * | ------------ | -------------------- | ----- | --------- | --------- |
		 * |   Provider   |        Flavor        |  SSL  |   Since   |  Removed  |
		 * | ------------ | -------------------- | ----- | --------- | --------- |
		 * | Qik          | qik.com              |  Yes  | 2.9.0     | 3.9.0     |
		 * | ------------ | -------------------- | ----- | --------- | --------- |
		 * | Viddler      | viddler.com          |  Yes  | 2.9.0     | 4.0.0     |
		 * | ------------ | -------------------- | ----- | --------- | --------- |
		 * | Revision3    | revision3.com        |   !   | 2.9.0     | 4.2.0     |
		 * | ------------ | -------------------- | ----- | --------- | --------- |
		 *
		 * @see wp_oembed_add_provider()
		 *
		 * @since 2.9.0
		 *
		 * @param array $providers An array of popular oEmbed providers.
		 */
		$this->providers = apply_filters( 'oembed_providers', $providers );

		// Fix any embeds that contain new lines in the middle of the HTML which breaks wpautop().
		add_filter( 'oembed_dataparse', array($this, '_strip_newlines'), 10, 3 );
	}

	/**
	 * Make private/protected methods readable for backwards compatibility.
	 *
	 * @since 4.0.0
	 * @access public
	 *
	 * @param callable $name      Method to call.
	 * @param array    $arguments Arguments to pass when calling.
	 * @return mixed|bool Return value of the callback, false otherwise.
	 */
	public function __call( $name, $arguments ) {
		if ( in_array( $name, $this->compat_methods ) ) {
			return call_user_func_array( array( $this, $name ), $arguments );
		}
		return false;
	}

	/**
	 * Takes a URL and returns the corresponding oEmbed provider's URL, if there is one.
	 *
	 * @since 4.0.0
	 * @access public
	 *
	 * @see WP_oEmbed::discover()
	 *
	 * @param string        $url  The URL to the content.
	 * @param string|array  $args Optional provider arguments.
	 * @return false|string False on failure, otherwise the oEmbed provider URL.
	 */
	public function get_provider( $url, $args = '' ) {

		$provider = false;

		if ( !isset($args['discover']) )
			$args['discover'] = true;

		foreach ( $this->providers as $matchmask => $data ) {
			list( $providerurl, $regex ) = $data;

			// Turn the asterisk-type provider URLs into regex
			if ( !$regex ) {
				$matchmask = '#' . str_replace( '___wildcard___', '(.+)', preg_quote( str_replace( '*', '___wildcard___', $matchmask ), '#' ) ) . '#i';
				$matchmask = preg_replace( '|^#http\\\://|', '#https?\://', $matchmask );
			}

			if ( preg_match( $matchmask, $url ) ) {
				$provider = str_replace( '{format}', 'json', $providerurl ); // JSON is easier to deal with than XML
				break;
			}
		}

		if ( !$provider && $args['discover'] )
			$provider = $this->discover( $url );

		return $provider;
	}

	/**
	 * Add an oEmbed provider just-in-time when wp_oembed_add_provider() is called
	 * before the 'plugins_loaded' hook.
	 *
	 * The just-in-time addition is for the benefit of the 'oembed_providers' filter.
	 *
	 * @since 4.0.0
	 * @access public
	 * @static
	 *
	 * @see wp_oembed_add_provider()
	 *
	 * @param string $format   Format of URL that this provider can handle. You can use
	 *                         asterisks as wildcards.
	 * @param string $provider The URL to the oEmbed provider..
	 * @param bool   $regex    Optional. Whether the $format parameter is in a regex format.
	 *                         Default false.
	 */
	public static function _add_provider_early( $format, $provider, $regex = false ) {
		if ( empty( self::$early_providers['add'] ) ) {
			self::$early_providers['add'] = array();
		}

		self::$early_providers['add'][ $format ] = array( $provider, $regex );
	}

	/**
	 * Remove an oEmbed provider just-in-time when wp_oembed_remove_provider() is called
	 * before the 'plugins_loaded' hook.
	 *
	 * The just-in-time removal is for the benefit of the 'oembed_providers' filter.
	 *
	 * @since 4.0.0
	 * @access public
	 * @static
	 *
	 * @see wp_oembed_remove_provider()
	 *
	 * @param string $format The format of URL that this provider can handle. You can use
	 *                       asterisks as wildcards.
	 */
	public static function _remove_provider_early( $format ) {
		if ( empty( self::$early_providers['remove'] ) ) {
			self::$early_providers['remove'] = array();
		}

		self::$early_providers['remove'][] = $format;
	}

	/**
	 * The do-it-all function that takes a URL and attempts to return the HTML.
	 *
	 * @see WP_oEmbed::fetch()
	 * @see WP_oEmbed::data2html()
	 *
	 * @param string $url The URL to the content that should be attempted to be embedded.
	 * @param array $args Optional arguments. Usually passed from a shortcode.
	 * @return false|string False on failure, otherwise the UNSANITIZED (and potentially unsafe) HTML that should be used to embed.
	 */
	public function get_html( $url, $args = '' ) {
		$provider = $this->get_provider( $url, $args );

		if ( !$provider || false === $data = $this->fetch( $provider, $url, $args ) )
			return false;

		/**
		 * Filter the HTML returned by the oEmbed provider.
		 *
		 * @since 2.9.0
		 *
		 * @param string $data The returned oEmbed HTML.
		 * @param string $url  URL of the content to be embedded.
		 * @param array  $args Optional arguments, usually passed from a shortcode.
		 */
		return apply_filters( 'oembed_result', $this->data2html( $data, $url ), $url, $args );
	}

	/**
	 * Attempts to discover link tags at the given URL for an oEmbed provider.
	 *
	 * @param string $url The URL that should be inspected for discovery `<link>` tags.
	 * @return false|string False on failure, otherwise the oEmbed provider URL.
	 */
	public function discover( $url ) {
		$providers = array();

		/**
		 * Filter oEmbed remote get arguments.
		 *
		 * @since 4.0.0
		 *
		 * @see WP_Http::request()
		 *
		 * @param array  $args oEmbed remote get arguments.
		 * @param string $url  URL to be inspected.
		 */
		$args = apply_filters( 'oembed_remote_get_args', array(), $url );

		// Fetch URL content
		$request = wp_safe_remote_get( $url, $args );
		if ( $html = wp_remote_retrieve_body( $request ) ) {

			/**
			 * Filter the link types that contain oEmbed provider URLs.
			 *
			 * @since 2.9.0
			 *
			 * @param array $format Array of oEmbed link types. Accepts 'application/json+oembed',
			 *                      'text/xml+oembed', and 'application/xml+oembed' (incorrect,
			 *                      used by at least Vimeo).
			 */
			$linktypes = apply_filters( 'oembed_linktypes', array(
				'application/json+oembed' => 'json',
				'text/xml+oembed' => 'xml',
				'application/xml+oembed' => 'xml',
			) );

			// Strip <body>
			$html = substr( $html, 0, stripos( $html, '</head>' ) );

			// Do a quick check
			$tagfound = false;
			foreach ( $linktypes as $linktype => $format ) {
				if ( stripos($html, $linktype) ) {
					$tagfound = true;
					break;
				}
			}

			if ( $tagfound && preg_match_all( '#<link([^<>]+)/?>#iU', $html, $links ) ) {
				foreach ( $links[1] as $link ) {
					$atts = shortcode_parse_atts( $link );

					if ( !empty($atts['type']) && !empty($linktypes[$atts['type']]) && !empty($atts['href']) ) {
						$providers[$linktypes[$atts['type']]] = htmlspecialchars_decode( $atts['href'] );

						// Stop here if it's JSON (that's all we need)
						if ( 'json' == $linktypes[$atts['type']] )
							break;
					}
				}
			}
		}

		// JSON is preferred to XML
		if ( !empty($providers['json']) )
			return $providers['json'];
		elseif ( !empty($providers['xml']) )
			return $providers['xml'];
		else
			return false;
	}

	/**
	 * Connects to a oEmbed provider and returns the result.
	 *
	 * @param string $provider The URL to the oEmbed provider.
	 * @param string $url The URL to the content that is desired to be embedded.
	 * @param array $args Optional arguments. Usually passed from a shortcode.
	 * @return false|object False on failure, otherwise the result in the form of an object.
	 */
	public function fetch( $provider, $url, $args = '' ) {
		$args = wp_parse_args( $args, wp_embed_defaults( $url ) );

		$provider = add_query_arg( 'maxwidth', (int) $args['width'], $provider );
		$provider = add_query_arg( 'maxheight', (int) $args['height'], $provider );
		$provider = add_query_arg( 'url', urlencode($url), $provider );

		/**
		 * Filter the oEmbed URL to be fetched.
		 *
		 * @since 2.9.0
		 *
		 * @param string $provider URL of the oEmbed provider.
		 * @param string $url      URL of the content to be embedded.
		 * @param array  $args     Optional arguments, usually passed from a shortcode.
		 */
		$provider = apply_filters( 'oembed_fetch_url', $provider, $url, $args );

		foreach( array( 'json', 'xml' ) as $format ) {
			$result = $this->_fetch_with_format( $provider, $format );
			if ( is_wp_error( $result ) && 'not-implemented' == $result->get_error_code() )
				continue;
			return ( $result && ! is_wp_error( $result ) ) ? $result : false;
		}
		return false;
	}

	/**
	 * Fetches result from an oEmbed provider for a specific format and complete provider URL
	 *
	 * @since 3.0.0
	 * @access private
	 * @param string $provider_url_with_args URL to the provider with full arguments list (url, maxheight, etc.)
	 * @param string $format Format to use
	 * @return false|object|WP_Error False on failure, otherwise the result in the form of an object.
	 */
	private function _fetch_with_format( $provider_url_with_args, $format ) {
		$provider_url_with_args = add_query_arg( 'format', $format, $provider_url_with_args );

		/** This filter is documented in wp-includes/class-oembed.php */
		$args = apply_filters( 'oembed_remote_get_args', array(), $provider_url_with_args );

		$response = wp_safe_remote_get( $provider_url_with_args, $args );
		if ( 501 == wp_remote_retrieve_response_code( $response ) )
			return new WP_Error( 'not-implemented' );
		if ( ! $body = wp_remote_retrieve_body( $response ) )
			return false;
		$parse_method = "_parse_$format";
		return $this->$parse_method( $body );
	}

	/**
	 * Parses a json response body.
	 *
	 * @since 3.0.0
	 * @access private
	 *
	 * @param string $response_body
	 * @return object|false
	 */
	private function _parse_json( $response_body ) {
		$data = json_decode( trim( $response_body ) );
		return ( $data && is_object( $data ) ) ? $data : false;
	}

	/**
	 * Parses an XML response body.
	 *
	 * @since 3.0.0
	 * @access private
	 *
	 * @param string $response_body
	 * @return object|false
	 */
	private function _parse_xml( $response_body ) {
		if ( ! function_exists( 'libxml_disable_entity_loader' ) )
			return false;

		$loader = libxml_disable_entity_loader( true );
		$errors = libxml_use_internal_errors( true );

		$return = $this->_parse_xml_body( $response_body );

		libxml_use_internal_errors( $errors );
		libxml_disable_entity_loader( $loader );

		return $return;
	}

	/**
	 * Helper function for parsing an XML response body.
	 *
	 * @since 3.6.0
	 * @access private
	 *
	 * @param string $response_body
	 * @return object|false
	 */
	private function _parse_xml_body( $response_body ) {
		if ( ! function_exists( 'simplexml_import_dom' ) || ! class_exists( 'DOMDocument' ) )
			return false;

		$dom = new DOMDocument;
		$success = $dom->loadXML( $response_body );
		if ( ! $success )
			return false;

		if ( isset( $dom->doctype ) )
			return false;

		foreach ( $dom->childNodes as $child ) {
			if ( XML_DOCUMENT_TYPE_NODE === $child->nodeType )
				return false;
		}

		$xml = simplexml_import_dom( $dom );
		if ( ! $xml )
			return false;

		$return = new stdClass;
		foreach ( $xml as $key => $value ) {
			$return->$key = (string) $value;
		}

		return $return;
	}

	/**
	 * Converts a data object from {@link WP_oEmbed::fetch()} and returns the HTML.
	 *
	 * @param object $data A data object result from an oEmbed provider.
	 * @param string $url The URL to the content that is desired to be embedded.
	 * @return false|string False on error, otherwise the HTML needed to embed.
	 */
	public function data2html( $data, $url ) {
		if ( ! is_object( $data ) || empty( $data->type ) )
			return false;

		$return = false;

		switch ( $data->type ) {
			case 'photo':
				if ( empty( $data->url ) || empty( $data->width ) || empty( $data->height ) )
					break;
				if ( ! is_string( $data->url ) || ! is_numeric( $data->width ) || ! is_numeric( $data->height ) )
					break;

				$title = ! empty( $data->title ) && is_string( $data->title ) ? $data->title : '';
				$return = '<a href="' . esc_url( $url ) . '"><img src="' . esc_url( $data->url ) . '" alt="' . esc_attr($title) . '" width="' . esc_attr($data->width) . '" height="' . esc_attr($data->height) . '" /></a>';
				break;

			case 'video':
			case 'rich':
				if ( ! empty( $data->html ) && is_string( $data->html ) )
					$return = $data->html;
				break;

			case 'link':
				if ( ! empty( $data->title ) && is_string( $data->title ) )
					$return = '<a href="' . esc_url( $url ) . '">' . esc_html( $data->title ) . '</a>';
				break;

			default:
				$return = false;
		}

		/**
		 * Filter the returned oEmbed HTML.
		 *
		 * Use this filter to add support for custom data types, or to filter the result.
		 *
		 * @since 2.9.0
		 *
		 * @param string $return The returned oEmbed HTML.
		 * @param object $data   A data object result from an oEmbed provider.
		 * @param string $url    The URL of the content to be embedded.
		 */
		return apply_filters( 'oembed_da