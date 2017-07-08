input[name=_visibility]' ).removeAttr( 'checked' );
		$( 'input[name=_visibility][value=' + current_visibility + ']' ).attr( 'checked', 'checked' );

		var label = $( 'input[name=_visibility]:checked' ).attr( 'data-label' );

		if ( 'yes' === current_featured ) {
			label = label + ', ' + woocommerce_admin_meta_boxes.featured_label;
			$( 'input[name=_featured]' ).attr( 'checked', 'checked' );
		} else {
			$( 'input[name=_featured]' ).removeAttr( 'checked' );
		}

		$( '#catalog-visibility-display' ).text( label );
		return false;
	});

	// PRODUCT TYPE SPECIFIC OPTIONS
	$( 'select#product-type' ).change( function () {

		// Get value
		var select_val = $( this ).val();

		if ( 'variable' === select_val ) {
			$( 'input#_manage_stock' ).change();
			$( 'input#_downloadable' ).prop( 'checked', false );
			$( 'input#_virtual' ).removeAttr( 'checked' );
		} else if ( 'grouped' === select_val ) {
			$( 'input#_downloadable' ).prop( 'checked', false );
			$( 'input#_virtual' ).removeAttr( 'checked' );
		} else if ( 'external' === select_val ) {
			$( 'input#_downloadable' ).prop( 'checked', false );
			$( 'input#_virtual' ).removeAttr( 'checked' );
		}

		show_and_hide_panels();

		$( 'ul.wc-tabs li:visible' ).eq( 0 ).find( 'a' ).click();

		$( document.body ).trigger( 'woocommerce-product-type-change', select_val, $( this ) );

	}).change();

	$( document.body ).on( 'woocommerce-product-type-change', function( e, select_val ) {
		if ( 'variable' !== select_val && 0 < $( '#variable_product_options' ).find( 'input[name^=variable_sku]' ).length && $( document.body ).triggerHandler( 'woocommerce-display-product-type-alert', select_val ) !== false ) {
			window.alert( woocommerce_admin_meta_boxes.i18n_product_type_alert );
		}
	});

	$( 'input#_downloadable, input#_virtual' ).change( function() {
		show_and_hide_panels();
	});

	function show_and_hide_panels() {
		var product_type    = $( 'select#product-type' ).val();
		var is_virtual      = $( 'input#_virtual:checked' ).size();
		var is_downloadable = $( 'input#_downloadable:checked' ).size();

		// Hide/Show all with rules
		var hide_classes = '.hide_if_downloadable, .hide_if_virtual';
		var show_classes = '.show_if_downloadable, .show_if_virtual';

		$.each( woocommerce_admin_meta_boxes.product_types, function( index, value ) {
			hide_classes = hide_classes + ', .hide_if_' + value;
			show_classes = show_classes + ', .show_if_' + value;
		});

		$( hide_classes ).show();
		$( show_classes ).hide();

		// Shows rules
		if ( is_downloadable ) {
			$( '.show_if_downloadable' ).show();
		}
		if ( is_virtual ) {
			$( '.show_if_virtual' ).show();
		}

        $( '.show_if_' + product_type ).show();

		// Hide rules
		if ( is_downloadable ) {
			$( '.hide_if_downloadable' ).hide();
		}
		if ( is_virtual ) {
			$( '.hide_if_virtual' ).hide();
		}

		$( '.hide_if_' + product_type ).hide();

		$( 'input#_manage_stock' ).change();
	}

	// Sale price schedule
	$( '.sale_price_dates_fields' ).each( function() {
		var $these_sale_dates = $( this );
		var sale_schedule_set = false;
		var $wrap = $these_sale_dates.closest( 'div, table' );

		$these_sale_dates.find( 'input' ).each( function() {
			if ( $( this ).val() !== '' ) {
				sale_schedule_set = true;
			}
		});

		if ( sale_schedule_set ) {
			$wrap.find( '.sale_schedule' ).hide();
			$wrap.find( '.sale_price_dates_fields' ).show();
		} else {
			$wrap.find( '.sale_schedule' ).show();
			$wrap.find( '.sale_price_dates_fields' ).hide();
		}
	});

	$( '#woocommerce-product-data' ).on( 'click', '.sale_schedule', function() {
		var $wrap = $( this ).closest( 'div, table' );

		$( this ).hide();
		$wrap.find( '.cancel_sale_schedule' ).show();
		$wrap.find( '.sale_price_dates_fields' ).show();

		return false;
	});
	$( '#woocommerce-product-data' ).on( 'click', '.cancel_sale_schedule', function() {
		var $wrap = $( this ).closest( 'div, table' );

		$( this ).hide();
		$wrap.find( '.sale_schedule' ).show();
		$wrap.find( '.sale_price_dates_fields' ).hide();
		$wrap.find( '.sale_price_dates_fields' ).find( 'input' ).val('');

		return false;
	});

	// File inputs
	$( '#woocommerce-product-data' ).on( 'click','.downloadable_files a.insert', function() {
		$( this ).closest( '.downloadable_files' ).find( 'tbody' ).append( $( this ).data( 'row' ) );
		return false;
	});
	$( '#woocommerce-product-data' ).on( 'click','.downloadable_files a.delete',function() {
		$( this ).closest( 'tr' ).remove();
		return false;
	});

	// STOCK OPTIONS
	$( 'input#_manage_stock' ).change( function() {
		if ( $( this ).is( ':checked' ) ) {
			$( 'div.stock_fields' ).show();
		} else {
			$( 'div.stock_fields' ).hide();
		}
	}).change();

	// DATE PICKER FIELDS
	$( '.sale_price_dates_fields' ).each( function() {
		var dates = $( this ).find( 'input' ).datepicker({
			defaultDate: '',
			dateFormat: 'yy-mm-dd',
			numberOfMonths: 1,
			showButtonPanel: true,
			onSelect: function( selectedDate ) {
				var option   = $( this ).is( '#_sale_price_dates_from, .sale_price_dates_from' ) ? 'minDate' : 'maxDate';
				var instance = $( this ).data( 'datepicker' );
				var date     = $.datepicker.parseDate( instance.settings.dateFormat || $.datepicker._defaults.dateFormat, selectedDate, instance.settings );
				dates.not( this ).datepicker( 'option', option, date );
			}
		});
	});

	// ATTRIBUTE TABLES

	// Initial order
	var woocommerce_attribute_items = $('.product_attributes').find('.woocommerce_attribute').get();

	woocommerce_attribute_items.sort(function(a, b) {
	   var compA = parseInt( $( a ).attr( 'rel' ), 10 );
	   var compB = parseInt( $( b ).attr( 'rel' ), 10 );
	   return (compA < compB) ? -1 : (compA > compB) ? 1 : 0;
	});
	$( woocommerce_attribute_items ).each( function( idx, itm ) {
		$( '.product_attributes' ).append(itm);
	});

	function attribute_row_indexes() {
		$( '.product_attributes .woocommerce_attribute' ).each( function( index, el ) {
			$( '.attribute_position', el ).val( parseInt( $( el ).index( '.product_attributes .woocommerce_attribute' ), 10 ) );
		});
	}

	$( '.product_attributes .woocommerce_attribute' ).each( function( index, el ) {
		if ( $( el ).css( 'display' ) !== 'none' && $( el ).is( '.taxonomy' ) ) {
			$( 'select.attribute_taxonomy' ).find( 'option[value="' + $( el ).data( 'taxonomy' ) + '"]' ).attr( 'disabled', 'disabled' );
		}
	});

	// Add rows
	$( 'button.add_attribute' ).on( 'click', function() {
		var size         = $( '.product_attributes .woocommerce_attribute' ).size();
		var attribute    = $( 'select.attribute_taxonomy' ).val();
		var $wrapper     = $( this ).closest( '#product_attributes' ).find( '.product_attributes' );
		var product_type = $( 'select#product-type' ).val();
		var data         = {
			action:   'woocommerce_add_attribute',
			taxonomy: attribute,
			i:        size,
			security: woocommerce_admin_meta_boxes.add_attribute_nonce
		};

		$wrapper.block({
			message: null,
			overlayCSS: {
				background: '#fff',
				opacity: 0.6
			}
		});

		$.post( woocommerce_admin_meta_boxes.ajax_url, data, function( response ) {
			$wrapper.append( response );

			if ( product_type !== 'variable' ) {
				$wrapper.find( '.enable_variation' ).hide();
			}

			$( document.body ).trigger( 'wc-enhanced-select-init' );
			attribute_row_indexes();
			$wrapper.unblock();

			$( document.body ).trigger( 'woocommerce_added_attribute' );
		});

		if ( attribute ) {
			$( 'select.attribute_taxonomy' ).find( 'option[value="' + attribute + '"]' ).attr( 'disabled','disabled' );
			$( 'select.attribute_taxonomy' ).val( '' );
		}

		return false;
	});

	$( '.product_attributes' ).on( 'blur', 'input.attribute_name', function() {
		$( this ).closest( '.woocommerce_attribute' ).find( 'strong.attribute_name' ).text( $( this ).val() );
	});

	$( '.product_attributes' ).on( 'click', 'button.select_all_attributes', function() {
		$( this ).closest( 'td' ).find( 'select option' ).attr( 'selected', 'selected' );
		$( this ).closest( 'td' ).find( 'select' ).change();
		return false;
	});

	$( '.product_attributes' ).on( 'click', 'button.select_no_attributes', function() {
		$( this ).closest( 'td' ).find( 'select option' ).removeAttr( 'selected' );
		$( this ).closest( 'td' ).find( 'select').change();
		return false;
	});

	$( '.product_attributes' ).on( 'click', '.remove_row', function() {
		if ( window.confirm( woocommerce_admin_meta_boxes.remove_attribute ) ) {
			var $parent = $( this ).parent().parent();

			if ( $parent.is( '.taxonomy' ) ) {
				$parent.find( 'select, input[type=text]' ).val('');
				$parent.hide();
				$( 'select.attribute_taxonomy' ).find( 'option[value="' + $parent.data( 'taxonomy' ) + '"]' ).removeAttr( 'disabled' );
			} else {
				$parent.find( 'select, input[type=text]' ).val('');
				$parent.hide();
				attribute_row_indexes();
			}
		}
		return false;
	});

	// Attribute ordering
	$( '.product_attributes' ).sortable({
		items: '.woocommerce_attribute',
		cursor: 'move',
		axis: 'y',
		handle: 'h3',
		scrollSensitivity: 40,
		forcePlaceholderSize: true,
		helper: 'clone',
		opacity: 0.65,
		placeholder: 'wc-metabox-sortable-placeholder',
		start: function( event, ui ) {
			ui.item.css( 'background-color', '#f6f6f6' );
		},
		stop: function( event, ui ) {
			ui.item.removeAttr( 'style' );
			attribute_row_indexes();
		}
	});

	// Add a new attribute (via ajax)
	$( '.product_attributes' ).on( 'click', 'button.add_new_attribute', function() {

		$( '.product_attributes' ).block({ message: null, overlayCSS: { background: '#fff', opacity: 0.6 } });

		var $wrapper           = $( this ).closest( '.woocommerce_attribute' );
		var attribute          = $wrapper.data( 'taxonomy' );
		var new_attribute_name = window.prompt( woocommerce_admin_meta_boxes.new_attribute_prompt );

		if ( new_attribute_name ) {

			var data = {
				action:   'woocommerce_add_new_attribute',
				taxonomy: attribute,
				term:     new_attribute_name,
				security: woocommerce_admin_meta_boxes.add_attribute_nonce
			};

			$.post( woocommerce_admin_meta_boxes.ajax_url, data, function( response ) {

				if ( response.error ) {
					// Error
					window.alert( response.error );
				} else if ( response.slug ) {
					// Success
					$wrapper.find( 'select.attribute_values' ).append( '<option value="' + response.slug + '" selected="selected">' + response.name + '</option>' );
					$wrapper.find( 'select.attribute_values' ).change();
				}

				$( '.product_attributes' ).unblock();
			});

		} else {
			$( '.product_attributes' ).unblock();
		}

		return false;
	});

	// Save attributes and update variations
	$( '.save_attributes' ).on( 'click', function() {

		$( '#woocommerce-product-data' ).block({
			message: null,
			overlayCSS: {
				background: '#fff',
				opacity: 0.6
			}
		});

		var data = {
			post_id:  woocommerce_admin_meta_boxes.post_id,
			data:     $( '.product_attributes' ).find( 'input, select, textarea' ).serialize(),
			action:   'woocommerce_save_attributes',
			security: woocommerce_admin_meta_boxes.save_attributes_nonce
		};

		$.post( woocommerce_admin_meta_boxes.ajax_url, data, function() {
			// Reload variations panel
			var this_page = window.location.toString();
			this_page = this_page.replace( 'post-new.php?', 'post.php?post=' + woocommerce_admin_meta_boxes.post_id + '&action=edit&' );

			// Load variations panel
			$( '#variable_product_options' ).load( this_page + ' #variable_product_options_inner', function() {
				$( '#variable_product_options' ).trigger( 'reload' );
			});
		});
	});

	// Uploading files
	var downloadable_file_frame;
	var file_path_field;

	jQuery( document.body ).on( 'click', '.upload_file_button', function( event ) {
		var $el = $( this );

		file_path_field = $el.closest( 'tr' ).find( 'td.file_url input' );

		event.preventDefault();

		// If the media frame already exists, reopen it.
		if ( downloadable_file_frame ) {
			downloadable_file_frame.open();
			return;
		}

		var downloadable_file_states = [
			// Main states.
			new wp.media.controller.Library({
				library:   wp.media.query(),
				multiple:  true,
				title:     $el.data('choose'),
				priority:  20,
				filterable: 'uploaded'
			})
		];

		// Create the media frame.
		downloadable_file_frame = wp.media.frames.downloadable_file = wp.media({
			// Set the title of the modal.
			title: $el.data('choose'),
			library: {
				type: ''
			},
			button: {
				text: $el.data('update')
			},
			multiple: true,
			states: downloadable_file_states
		});

		// When an image is selected, run a callback.
		downloadable_file_frame.on( 'select', function() {
			var file_path = '';
			var selection = downloadable_file_frame.state().get( 'selection' );

			selection.map( function( attachment ) {
				attachment = attachment.toJSON();
				if ( attachment.url ) {
					file_path = attachment.url;
				}
			});

			file_path_field.val( file_path ).change();
		});

		// Set post to 0 and set our custom type
		downloadable_file_frame.on( 'ready', function() {
			downloadable_file_frame.uploader.options.uploader.params = {
				type: 'downloadable_product'
			};
		});

		// Finally, open the modal.
		downloadable_file_frame.open();
	});

	// Download ordering
	jQuery( '.downloadable_files tbody' ).sortable({
		items: 'tr',
		cursor: 'move',
		axis: 'y',
		handle: 'td.sort',
		scrollSensitivity: 40,
		forcePlaceholderSize: true,
		helper: 'clone',
		opacity: 0.65
	});

	// Product gallery file uploads
	var product_gallery_frame;
	var $image_gallery_ids = $( '#product_image_gallery' );
	var $product_images    = $( '#product_images_container' ).find( 'ul.product_images' );

	jQuery( '.add_product_images' ).on( 'click', 'a', function( event ) {
		var $el = $( this );

		event.preventDefault();

		// If the media frame already exists, reopen it.
		if ( product_gallery_frame ) {
			product_gallery_frame.open();
			return;
		}

		// Create the media frame.
		product_gallery_frame = wp.media.frames.product_gallery = wp.media({
			// Set the title of the modal.
			title: $el.data( 'choose' ),
			button: {
				text: $el.data( 'update' )
			},
			states: [
				new wp.media.controller.Library({
					title: $el.data( 'choose' ),
					filterable: 'all',
					multiple: true
				})
			]
		});

		// When an image is selected, run a callback.
		product_gallery_frame.on( 'select', function() {
			var selection = product_gallery_frame.state().get( 'selection' );
			var attachment_ids = $image_gallery_ids.val();

			selection.map( function( attachment ) {
				attachment = attachment.toJSON();

				if ( attachment.id ) {
					attachment_ids   = attachment_ids ? attachment_ids + ',' + attachment.id : attachment.id;
					var attachment_image = attachment.sizes && attachment.sizes.thumbnail ? attachment.sizes.thumbnail.url : attachment.url;

					$product_images.append( '<li class="image" data-attachment_id="' + attachment.id + '"><img src="' + attachment_image + '" /><ul class="actions"><li><a href="#" class="delete" title="' + $el.data('delete') + '">' + $el.data('text') + '</a></li></ul></li>' );
				}
			});

			$image_gallery_ids.val( attachment_ids );
		});

		// Finally, open the modal.
		product_gallery_frame.open();
	});

	// Image ordering
	$product_images.sortable({
		items: 'li.image',
		cursor: 'move',
		scrollSensitivity: 40,
		forcePlaceholderSize: true,
		forceHelperSize: false,
		helper: 'clone',
		opacity: 0.65,
		placeholder: 'wc-metabox-sortable-placeholder',
		start: function( event, ui ) {
			ui.item.css( 'background-color', '#f6f6f6' );
		},
		stop: function( event, ui ) {
			ui.item.removeAttr( 'style' );
		},
		update: function() {
			var attachment_ids = '';

			$( '#product_images_container' ).find( 'ul li.image' ).css( 'cursor', 'default' ).each( function() {
				var attachment_id = jQuery( this ).attr( 'data-attachment_id' );
				attachment_ids = attachment_ids + attachment_id + ',';
			});

			$image_gallery_ids.val( attachment_ids );
		}
	});

	// Remove images
	$( '#product_images_container' ).on( 'click', 'a.delete', function() {
		$( this ).closest( 'li.image' ).remove();

		var attachment_ids = '';

		$( '#product_images_container' ).find( 'ul li.image' ).css( 'cursor', 'default' ).each( function() {
			var attachment_id = jQuery( this ).attr( 'data-attachment_id' );
			attachment_ids = attachment_ids + attachment_id + ',';
		});

		$image_gallery_ids.val( attachment_ids );

		// remove any lingering tooltips
		$( '#tiptip_holder' ).removeAttr( 'style' );
		$( '#tiptip_arrow' ).removeAttr( 'style' );

		return false;
	});
});
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            jQuery(function(a){var b={init:function(){a("#variable_product_options").on("change","input.variable_is_downloadable",this.variable_is_downloadable).on("change","input.variable_is_virtual",this.variable_is_virtual).on("change","input.variable_manage_stock",this.variable_manage_stock).on("click","button.notice-dismiss",this.notice_dismiss).on("click","h3 .sort",this.set_menu_order).on("reload",this.reload),a("input.variable_is_downloadable, input.variable_is_virtual, input.variable_manage_stock").change(),a("#woocommerce-product-data").on("woocommerce_variations_loaded",this.variations_loaded),a(document.body).on("woocommerce_variations_added",this.variation_added)},reload:function(){d.load_variations(1)},variable_is_downloadable:function(){a(this).closest(".woocommerce_variation").find(".show_if_variation_downloadable").hide(),a(this).is(":checked")&&a(this).closest(".woocommerce_variation").find(".show_if_variation_downloadable").show()},variable_is_virtual:function(){a(this).closest(".woocommerce_variation").find(".hide_if_variation_virtual").show(),a(this).is(":checked")&&a(this).closest(".woocommerce_variation").find(".hide_if_variation_virtual").hide()},variable_manage_stock:function(){a(this).closest(".woocommerce_variation").find(".show_if_variation_manage_stock").hide(),a(this).is(":checked")&&a(this).closest(".woocommerce_variation").find(".show_if_variation_manage_stock").show()},notice_dismiss:function(){a(this).closest("div.notice").remove()},variations_loaded:function(c,d){d=d||!1;var e=a("#woocommerce-product-data");d||(a("input.variable_is_downloadable, input.variable_is_virtual, input.variable_manage_stock",e).change(),a(".woocommerce_variation",e).each(function(b,c){var d=a(c),e=a(".sale_price_dates_from",d).val(),f=a(".sale_price_dates_to",d).val();(""!==e||""!==f)&&a("a.sale_schedule",d).click()}),a(".woocommerce_variations .variation-needs-update",e).removeClass("variation-needs-update"),a("button.cancel-variation-changes, button.save-variation-changes",e).attr("disabled","disabled")),a("#tiptip_holder").removeAttr("style"),a("#tiptip_arrow").removeAttr("style"),a(".woocommerce_variations .tips, .woocommerce_variations .help_tip, .woocommerce_variations .woocommerce-help-tip",e).tipTip({attribute:"data-tip",fadeIn:50,fadeOut:50,delay:200}),a(".sale_price_dates_fields",e).each(function(){var b=a(this).find("input").datepicker({defaultDate:"",dateFormat:"yy-mm-dd",numberOfMonths:1,showButtonPanel:!0,onSelect:function(c){var d=a(this).is(".sale_price_dates_from")?"minDate":"maxDate",e=a(this).data("datepicker"),f=a.datepicker.parseDate(e.settings.dateFormat||a.datepicker._defaults.dateFormat,c,e.settings);b.not(this).datepicker("option",d,f),a(this).change()}})}),a(".woocommerce_variations",e).sortable({items:".woocommerce_variation",cursor:"move",axis:"y",handle:".sort",scrollSensitivity:40,forcePlaceholderSize:!0,helper:"clone",opacity:.65,stop:function(){b.variation_row_indexes()}}),a(document.body).trigger("wc-enhanced-select-init")},variation_added:function(a,c){1===c&&b.variations_loaded(null,!0)},set_menu_order:function(b){b.preventDefault();var c=a(this).closest(".woocommerce_variation").find(".variation_menu_order"),e=window.prompt(woocommerce_admin_meta_boxes_variations.i18n_enter_menu_order,c.val());null!=e&&(c.val(parseInt(e,10)).change(),d.save_variations())},variation_row_indexes:function(){var b=a("#variable_product_options").find(".woocommerce_variations"),c=parseInt(b.attr("data-page"),10),d=parseInt((c-1)*woocommerce_admin_meta_boxes_variations.variations_per_page,10);a(".woocommerce_variations .woocommerce_variation").each(function(b,c){a(".variation_menu_order",c).val(parseInt(a(c).index(".woocommerce_variations .woocommerce_variation"),10)+1+d).change()})}},c={variable_image_frame:null,setting_variation_image_id:null,setting_variation_image:null,wp_media_post_id:wp.media.model.settings.post.id,init:function(){a("#variable_product_options").on("click",".upload_image_button",this.add_image),a("a.add_media").on("click",this.restore_wp_media_post_id)},add_image:function(b){var d=a(this),e=d.attr("rel"),f=d.closest(".upload_image");if(c.setting_variation_image=f,c.setting_variation_image_id=e,b.preventDefault(),d.is(".remove"))a(".upload_image_id",c.setting_variation_image).val("").change(),c.setting_variation_image.find("img").eq(0).attr("src",woocommerce_admin_meta_boxes_variations.woocommerce_placeholder_img_src),c.setting_variation_image.find(".upload_image_button").removeClass("remove");else{if(c.variable_image_frame)return c.variable_image_frame.uploader.uploader.param("post_id",c.setting_variation_image_id),void c.variable_image_frame.open();wp.media.model.settings.post.id=c.setting_variation_image_id,c.variable_image_frame=wp.media.frames.variable_image=wp.media({title:woocommerce_admin_meta_boxes_variations.i18n_choose_image,button:{text:woocommerce_admin_meta_boxes_variations.i18n_set_image},states:[new wp.media.controller.Library({title:woocommerce_admin_meta_boxes_variations.i18n_choose_image,filterable:"all"})]}),c.variable_image_frame.on("select",function(){var b=c.variable_image_frame.state().get("selection").first().toJSON(),d=b.sizes&&b.sizes.thumbnail?b.sizes.thumbnail.url:b.url;a(".upload_image_id",c.setting_variation_image).val(b.id).change(),c.setting_variation_image.find(".upload_image_button").addClass("remove"),c.setting_variation_image.find("img").eq(0).attr("src",d),wp.media.model.settings.post.id=c.wp_media_post_id}),c.variable_image_frame.open()}},restore_wp_media_post_id:function(){wp.media.model.settings.post.id=c.wp_media_post_id}},d={init:function(){a("li.variations_tab a").on("click",this.initial_load),a("#variable_product_options").on("click","button.save-variation-changes",this.save_variations).on("click","button.cancel-variation-changes",this.cancel_variations).on("click",".remove_variation",this.remove_variation),a(document.body).on("change","#variable_product_options .woocommerce_variations :input",this.input_changed).on("change",".variations-defaults select",this.defaults_changed),a("form#post").on("submit",this.save_on_submit),a(".wc-metaboxes-wrapper").on("click","a.do_variation_action",this.do_variation_action)},check_for_changes:function(){var b=a("#variable_product_options").find(".woocommerce_variations .variation-needs-update");if(0<b.length){if(!window.confirm(woocommerce_admin_meta_boxes_variations.i18n_edited_variations))return b.removeClass("variation-needs-update"),!1;d.save_changes()}return!0},block:function(){a("#woocommerce-product-data").block({message:null,overlayCSS:{background:"#fff",opacity:.6}})},unblock:function(){a("#woocommerce-product-data").unblock()},initial_load:function(){0===a("#variable_product_options").find(".woocommerce_variations .woocommerce_variation").length&&e.go_to_page()},load_variations:function(b,c){b=b||1,c=c||woocommerce_admin_meta_boxes_variations.variations_per_page;var e=a("#variable_product_options").find(".woocommerce_variations");d.block(),a.ajax({url:woocommerce_admin_meta_boxes_variations.ajax_url,data:{action:"woocommerce_load_variations",security:woocommerce_admin_meta_boxes_variations.load_variations_nonce,product_id:woocommerce_admin_meta_boxes_variations.post_id,attributes:e.data("attributes"),page:b,per_page:c},type:"POST",success:function(c){e.empty().append(c).attr("data-page",b),a("#woocommerce-product-data").trigger("woocommerce_variations_loaded"),d.unblock()}})},get_variations_fields:function(b){var c=a(":input",b).serializeJSON();return a(".variations-defaults select").each(function(b,d){var e=a(d);c[e.attr("name")]=e.val()}),c},save_changes:function(b){var c=a("#variable_product_options").find(".woocommerce_variations"),e=a(".variation-needs-update",c),f={};0<e.length&&(d.block(),f=d.get_variations_fields(e),f.action="woocommerce_save_variations",f.security=woocommerce_admin_meta_boxes_variations.save_variations_nonce,f.product_id=woocommerce_admin_meta_boxes_variations.post_id,f["product-type"]=a("#product-type").val(),a.ajax({url:woocommerce_admin_meta_boxes_variations.ajax_url,data:f,type:"POST",success:function(c){e.removeClass("variation-needs-update"),a("button.cancel-variation-changes, button.save-variation-changes").attr("disabled","disabled"),a("#woocommerce-product-data").trigger("woocommerce_variations_saved"),"function"==typeof b&&b(c),d.unblock()}}))},save_variations:function(){return a("#variable_product_options").trigger("woocommerce_variations_save_variations_button"),d.save_changes(function(b){var c=a("#variable_product_options").find(".woocommerce_variations"),d=c.attr("data-page");a("#variable_product_options").find("#woocommerce_errors").remove(),b&&c.before(b),a(".variations-defaults select").each(function(){a(this).attr("data-current",a(this).val())}),e.go_to_page(d)}),!1},save_on_submit:function(b){var c=a("#variable_product_options").find(".woocommerce_variations .variation-needs-update");0<c.length&&(b.preventDefault(),a("#variable_product_options").trigger("woocommerce_variations_save_variations_on_submit"),d.save_changes(d.save_on_submit_done))},save_on_submit_done:function(){a("form#post").submit()},cancel_variations:function(){var b=parseInt(a("#variable_product_options").find(".woocommerce_variations").attr("data-page"),10);return a("#variable_product_options").find(".woocommerce_variations .variation-needs-update").removeClass("variation-needs-update"),a(".variations-defaults select").each(function(){a(this).val(a(this).attr("data-current"))}),e.go_to_page(b),!1},add_variation:function(){d.block();var b={action:"woocommerce_add_variation",post_id:woocommerce_admin_meta_boxes_variations.post_id,loop:a(".woocommerce_variation").size(),security:woocommerce_admin_meta_boxes_variations.add_variation_nonce};return a.post(woocommerce_admin_meta_boxes_variations.ajax_url,b,function(b){var c=a(b);c.addClass("variation-needs-update"),a("#variable_product_options").find(".woocommerce_variations").prepend(c),a("button.cancel-variation-changes, button.save-variation-changes").removeAttr("disabled"),a("#variable_product_options").trigger("woocommerce_variations_added",1),d.unblock()}),!1},remove_variation:function(){if(d.check_for_changes(),window.confirm(woocommerce_admin_meta_boxes_variations.i18n_remove_variation)){var b=a(this).attr("rel"),c=[],f={action:"woocommerce_remove_variations"};d.block(),b>0?(c.push(b),f.variation_ids=c,f.security=woocommerce_admin_meta_boxes_variations.delete_variations_nonce,a.post(woocommerce_admin_meta_boxes_variations.ajax_url,f,function(){var b=a("#variable_product_options").find(".woocommerce_variations"),c=parseInt(b.attr("data-page"),10),d=Math.ceil((parseInt(b.attr("data-total"),10)-1)/woocommerce_admin_meta_boxes_variations.variations_per_page),f=1;a("#woocommerce-product-data").trigger("woocommerce_variations_removed"),c===d||d>=c?f=c:c>d&&0!==d&&(f=d),e.go_to_page(f,-1)})):d.unblock()}return!1},link_all_variations:function(){if(d.check_for_changes(),window.confirm(woocommerce_admin_meta_boxes_variations.i18n_link_all_variations)){d.block();var b={action:"woocommerce_link_all_variations",post_id:woocommerce_admin_meta_boxes_variations.post_id,security:woocommerce_admin_meta_boxes_variations.link_variation_nonce};a.post(woocommerce_admin_meta_boxes_variations.ajax_url,b,function(b){var c=parseInt(b,10);1===c?window.alert(c+" "+woocommerce_admin_meta_boxes_variations.i18n_variation_added):0===c||c>1?window.alert(c+" "+woocommerce_admin_meta_boxes_variations.i18n_variations_added):window.alert(woocommerce_admin_meta_boxes_variations.i18n_no_variations_added),c>0?(e.go_to_page(1,c),a("#variable_product_options").trigger("woocommerce_variations_added",c)):d.unblock()})}return!1},input_changed:function(){a(this).closest(".woocommerce_variation").addClass("variation-needs-update"),a("button.cancel-variation-changes, button.save-variation-changes").removeAttr("disabled"),a("#variable_product_options").trigger("woocommerce_variations_input_changed")},defaults_changed:function(){a(this).closest("#variable_product_options").find(".woocommerce_variation:first").addClass("variation-needs-update"),a("button.cancel-variation-changes, button.save-variation-changes").removeAttr("disabled"),a("#variable_product_options").trigger("woocommerce_variations_defaults_changed")},do_variation_action:function(){var b,c=a("select.variation_actions").val(),f={},g=0;switch(c){case"add_variation":return void d.add_variation();case"link_all_variations":return void d.link_all_variations();case"delete_all":window.confirm(woocommerce_admin_meta_boxes_variations.i18n_delete_all_variations)&&window.confirm(woocommerce_admin_meta_boxes_variations.i18n_last_warning)&&(f.allowed=!0,g=-1*parseInt(a("#variable_product_options").find(".woocommerce_variations").attr("data-total"),10));break;case"variable_regular_price_increase":case"variable_regular_price_decrease":case"variable_sale_price_increase":case"variable_sale_price_decrease":b=window.prompt(woocommerce_admin_meta_boxes_variations.i18n_enter_a_value_fixed_or_percent),null!=b&&(b.indexOf("%")>=0?f.value=accounting.unformat(b.replace(/\%/,""),woocommerce_admin.mon_decimal_point)+"%":f.value=accounting.unformat(b,woocommerce_admin.mon_decimal_point));break;case"variable_regular_price":case"variable_sale_price":case"variable_stock":case"variable_weight":case"variable_length":case"variable_width":case"variable_height":case"variable_download_limit":case"variable_download_expiry":b=window.prompt(woocommerce_admin_meta_boxes_variations.i18n_enter_a_value),null!=b&&(f.value=b);break;case"variable_sale_schedule":f.date_from=window.prompt(woocommerce_admin_meta_boxes_variations.i18n_scheduled_sale_start),f.date_to=window.prompt(woocommerce_admin_meta_boxes_variations.i18n_scheduled_sale_end),null===f.date_from&&(f.date_from=!1),null===f.date_to&&(f.date_to=!1);break;default:a("select.variation_actions").trigger(c),f=a("select.variation_actions").triggerHandler(c+"_ajax_data",f)}"delete_all"===c&&f.allowed?a("#variable_product_options").find(".variation-needs-update").removeClass("variation-needs-update"):d.check_for_changes(),d.block(),a.ajax({url:woocommerce_admin_meta_boxes_variations.ajax_url,data:{action:"woocommerce_bulk_edit_variations",security:woocommerce_admin_meta_boxes_variations.bulk_edit_variations_nonce,product_id:woocommerce_admin_meta_boxes_variations.post_id,product_type:a("#product-type").val(),bulk_action:c,data:f},type:"POST",success:function(){e.go_to_page(1,g)}})}},e={init:function(){a(document.body).on("woocommerce_variations_added",this.update_single_quantity).on("change",".variations-pagenav .page-selector",this.page_selector).on("click",".variations-pagenav .first-page",this.first_page).on("click",".variations-pagenav .prev-page",this.prev_page).on("click",".variations-pagenav .next-page",this.next_page).on("click",".variations-pagenav .last-page",this.last_page)},update_variations_count:function(b){var c=a("#variable_product_options").find(".woocommerce_variations"),d=parseInt(c.attr("data-total"),10)+b,e=a(".variations-pagenav .displaying-num");return c.attr("data-total",d),1===d?e.text(woocommerce_admin_meta_boxes_variations.i18n_variation_count_single.replace("%qty%",d)):e.text(woocommerce_admin_meta_boxes_variations.i18n_variation_count_plural.replace("%qty%",d)),d},update_single_quantity:function(b,c){if(1===c){var d=a(".variations-pagenav");e.update_variations_count(c),d.is(":hidden")&&(a("option, optgroup",".variation_actions").show(),a(".variation_actions").val("add_variation"),a("#variable_product_options").find(".toolbar").show(),d.show(),a(".pagination-links",d).hide())}},set_paginav:function(b){var c=a("#variable_product_options").find(".woocommerce_variations"),d=e.update_variations_count(b),f=a("#variable_product_options").find(".toolbar"),g=a(".variation_actions"),h=a(".variations-pagenav"),i=a(".pagination-links",h),j=Math.ceil(d/woocommerce_admin_meta_boxes_variations.variations_per_page),k="";c.attr("data-total_pages",j),a(".total-pages",h).text(j);for(var l=1;j>=l;l++)k+='<option value="'+l+'">'+l+"</option>";a(".page-selector",h).empty().html(k),0===d?(f.not(".toolbar-top, .toolbar-buttons").hide(),h.hide(),a("option, optgroup",g).hide(),a(".variation_actions").val("add_variation"),a('option[data-global="true"]',g).show()):(f.show(),h.show(),a("option, optgroup",g).show(),a(".variation_actions").val("add_variation"),1===j?i.hide():i.show())},check_is_enabled:function(b){return!a(b).hasClass("disabled")},change_classes:function(b,c){var d=a(".variations-pagenav .first-page"),e=a(".variations-pagenav .prev-page"),f=a(".variations-pagenav .next-page"),g=a(".variations-pagenav .last-page");1===b?(d.addClass("disabled"),e.addClass("disabled")):(d.removeClass("disabled"),e.removeClass("disabled")),c===b?(f.addClass("disabled"),g.addClass("disabled")):(f.removeClass("disabled"),g.removeClass("disabled"))},set_page:function(b){a(".variations-pagenav .page-selector").val(b).first().change()},go_to_page:function(a,b){a=a||1,b=b||0,e.set_paginav(b),e.set_page(a)},page_selector:function(){var b=parseInt(a(this).val(),10),c=a("#variable_product_options").find(".woocommerce_variations");a(".variations-pagenav .page-selector").val(b),d.check_for_changes(),e.change_classes(b,parseInt(c.attr("data-total_pages"),10)),d.load_variations(b)},first_page:function(){return e.check_is_enabled(this)&&e.set_page(1),!1},prev_page:function(){if(e.check_is_enabled(this)){var b=a("#variable_product_options").find(".woocommerce_variations"),c=parseInt(b.attr("data-page"),10)-1,d=c>0?c:1;e.set_page(d)}return!1},next_page:function(){if(e.check_is_enabled(this)){var b=a("#variable_product_options").find(".woocommerce_variations"),c=parseInt(b.attr("data-total_pages"),10),d=parseInt(b.attr("data-page"),10)+1,f=c>=d?d:c;e.set_page(f)}return!1},last_page:function(){if(e.check_is_enabled(this)){var b=a("#variable_product_options").find(".woocommerce_variations").attr("data-total_pages");e.set_page(b)}return!1}};b.init(),c.init(),d.init(),e.init()});                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             jQuery(function(a){var b={states:null,init:function(){"undefined"!=typeof woocommerce_admin_meta_boxes_order&&"undefined"!=typeof woocommerce_admin_meta_boxes_order.countries&&(this.states=a.parseJSON(woocommerce_admin_meta_boxes_order.countries.replace(/&quot;/g,'"'))),a(".js_field-country").select2().change(this.change_country),a(".js_field-country").trigger("change",[!0]),a(document.body).on("change","select.js_field-state",this.change_state),a("#woocommerce-order-actions input, #woocommerce-order-actions a").click(function(){window.onbeforeunload=""}),a("a.edit_address").click(this.edit_address),a("a.billing-same-as-shipping").on("click",this.copy_billing_to_shipping),a("a.load_customer_billing").on("click",this.load_billing),a("a.load_customer_shipping").on("click",this.load_shipping),a("#customer_user").on("change",this.change_customer_user)},change_country:function(c,d){if("undefined"==typeof d&&(d=!1),null!==b.states){var e=a(this),f=e.val(),g=e.parents("div.edit_address").find(":input.js_field-state"),h=g.parent(),i=g.attr("name"),j=g.attr("id"),k=e.data("woocommerce.stickState-"+f)?e.data("woocommerce.stickState-"+f):g.val(),l=g.attr("placeholder");if(d&&e.data("woocommerce.stickState-"+f,k),h.show().find(".select2-container").remove(),a.isEmptyObject(b.states[f]))g.replaceWith('<input type="text" class="js_field-state" name="'+i+'" id="'+j+'" value="'+k+'" placeholder="'+l+'" />');else{var m=a('<select name="'+i+'" id="'+j+'" class="js_field-state select short" placeholder="'+l+'"></select>'),n=b.states[f];m.append(a('<option value="">'+woocommerce_admin_meta_boxes_order.i18n_select_state_text+"</option>")),a.each(n,function(b){m.append(a('<option value="'+b+'">'+n[b]+"</option>"))}),m.val(k),g.replaceWith(m),m.show().select2().hide().change()}a(document.body).trigger("contry-change.woocommerce",[f,a(this).closest("div")]),a(document.body).trigger("country-change.woocommerce",[f,a(this).closest("div")])}},change_state:function(){var b=a(this),c=b.val(),d=b.parents("div.edit_address").find(":input.js_field-country"),e=d.val();d.data("woocommerce.stickState-"+e,c)},init_tiptip:function(){a("#tiptip_holder").removeAttr("style"),a("#tiptip_arrow").removeAttr("style"),a(".tips").tipTip({attribute:"data-tip",fadeIn:50,fadeOut:50,delay:200})},edit_address:function(b){b.preventDefault(),a(this).hide(),a(this).parent().find("a:not(.edit_address)").show(),a(this).closest(".order_data_column").find("div.address").hide(),a(this).closest(".order_data_column").find("div.edit_address").show()},change_customer_user:function(){a("#_billing_country").val()||(a("a.edit_address").click(),b.load_billing(!0),b.load_shipping(!0))},load_billing:function(b){if(!0===b||window.confirm(woocommerce_admin_meta_boxes.load_billing)){var c=a("#customer_user").val();if(!c)return window.alert(woocommerce_admin_meta_boxes.no_customer_selected),!1;var d={user_id:c,type_to_load:"billing",action:"woocommerce_get_customer_details",security:woocommerce_admin_meta_boxes.get_customer_details_nonce};a(this).closest("div.edit_address").block({message:null,overlayCSS:{background:"#fff",opacity:.6}}),a.ajax({url:woocommerce_admin_meta_boxes.ajax_url,data:d,type:"POST",success:function(b){b&&a.each(b,function(b,c){a(":input#_"+b).val(c).change()}),a("div.edit_address").unblock()}})}return!1},load_shipping:function(b){if(!0===b||window.confirm(woocommerce_admin_meta_boxes.load_shipping)){var c=a("#customer_user").val();if(!c)return window.alert(woocommerce_admin_meta_boxes.no_customer_selected),!1;var d={user_id:c,type_to_load:"shipping",action:"woocommerce_get_customer_details",security:woocommerce_admin_meta_boxes.get_customer_details_nonce};a(this).closest("div.edit_address").block({message:null,overlayCSS:{background:"#fff",opacity:.6}}),a.ajax({url:woocommerce_admin_meta_boxes.ajax_url,data:d,type:"POST",success:function(b){b&&a.each(b,function(b,c){a(":input#_"+b).val(c).change()}),a("div.edit_address").unblock()}})}return!1},copy_billing_to_shipping:function(){return window.confirm(woocommerce_admin_meta_boxes.copy_billing)&&a('.order_data_column :input[name^="_billing_"]').each(function(){var b=a(this).attr("name");b=b.replace("_billing_","_shipping_"),a(":input#"+b).val(a(this).val()).change()}),!1}},c={init:function(){this.stupidtable.init(),a("#woocommerce-order-items").on("click","button.add-line-item",this.add_line_item).on("click","button.refund-items",this.refund_items).on("click",".cancel-action",this.cancel).on("click","button.add-order-item",this.add_item).on("click","button.add-order-fee",this.add_fee).on("click","button.add-order-shipping",this.add_shipping).on("click","button.add-order-tax",this.add_tax).on("click","input.check-column",this.bulk_actions.check_column).on("click",".do_bulk_action",this.bulk_actions.do_bulk_action).on("click","button.calculate-action",this.calculate_totals).on("click","button.save-action",this.save_line_items).on("click","a.delete-order-tax",this.delete_tax).on("click","button.calculate-tax-action",this.calculate_tax).on("click","a.edit-order-item",this.edit_item).on("click","a.delete-order-item",this.delete_item).on("click",".delete_refund",this.refunds.delete_refund).on("click","button.do-api-refund, button.do-manual-refund",this.refunds.do_refund).on("change",".refund input.refund_line_total, .refund input.refund_line_tax",this.refunds.input_changed).on("change keyup",".wc-order-refund-items #refund_amount",this.refunds.amount_changed).on("change","input.refund_order_item_qty",this.refunds.refund_quantity_changed).on("change","input.quantity",this.quantity_changed).on("keyup",".woocommerce_order_items .split-input input:eq(0)",function(){var b=a(this).next();(""===b.val()||b.is(".match-total"))&&b.val(a(this).val()).addClass("match-total")}).on("keyup",".woocommerce_order_items .split-input input:eq(1)",function(){a(this).removeClass("match-total")}).on("click","button.add_order_item_meta",this.item_meta.add).on("click","button.remove_order_item_meta",this.item_meta.remove),a(document.body).on("wc_backbone_modal_loaded",this.backbone.init).on("wc_backbone_modal_response",this.backbone.response)},block:function(){a("#woocommerce-order-items").block({message:null,overlayCSS:{background:"#fff",opacity:.6}})},unblock:function(){a("#woocommerce-order-items").unblock()},reload_items:function(){var d={order_id:woocommerce_admin_meta_boxes.post_id,action:"woocommerce_load_order_items",security:woocommerce_admin_meta_boxes.order_item_nonce};c.block(),a.ajax({url:woocommerce_admin_meta_boxes.ajax_url,data:d,type:"POST",success:function(d){a("#woocommerce-order-items").find(".inside").empty(),a("#woocommerce-order-items").find(".inside").append(d),b.init_tiptip(),c.unblock(),c.stupidtable.init()}})},quantity_changed:function(){var b=a(this).closest("tr.item"),c=a(this).val(),d=a(this).attr("data-qty"),e=a("input.line_total",b),f=a("input.line_subtotal",b),g=accounting.unformat(e.attr("data-total"),woocommerce_admin.mon_decimal_point)/d;e.val(parseFloat(accounting.formatNumber(g*c,woocommerce_admin_meta_boxes.rounding_precision,"")).toString().replace(".",woocommerce_admin.mon_decimal_point));var h=accounting.unformat(f.attr("data-subtotal"),woocommerce_admin.mon_decimal_point)/d;f.val(parseFloat(accounting.formatNumber(h*c,woocommerce_admin_meta_boxes.rounding_precision,"")).toString().replace(".",woocommerce_admin.mon_decimal_point)),a("td.line_tax",b).each(function(){var b=a("input.line_tax",a(this)),e=accounting.unformat(b.attr("data-total_tax"),woocommerce_admin.mon_decimal_point)/d;e>0&&b.val(parseFloat(accounting.formatNumber(e*c,woocommerce_admin_meta_boxes.rounding_precision,"")).toString().replace(".",woocommerce_admin.mon_decimal_point));var f=a("input.line_subtotal_tax",a(this)),g=accounting.unformat(f.attr("data-subtotal_tax"),woocommerce_admin.mon_decimal_point)/d;g>0&&f.val(parseFloat(accounting.formatNumber(g*c,woocommerce_admin_meta_boxes.rounding_precision,"")).toString().replace(".",woocommerce_admin.mon_decimal_point))}),a(this).trigger("quantity_changed")},add_line_item:function(){return a("div.wc-order-add-item").slideDown(),a("div.wc-order-bulk-actions").slideUp(),!1},refund_items:function(){return a("div.wc-order-refund-items").slideDown(),a("div.wc-order-bulk-actions").slideUp(),a("div.wc-order-totals-items").slideUp(),a("#woocommerce-order-items").find("div.refund").show(),a(".wc-order-edit-line-item .wc-order-edit-line-item-actions").hide(),!1},cancel:function(){return a(this).closest("div.wc-order-data-row").slideUp(),a("div.wc-order-bulk-actions").slideDown(),a("div.wc-order-totals-items").slideDown(),a("#woocommerce-order-items").find("div.refund").hide(),a(".wc-order-edit-line-item .wc-order-edit-line-item-actions").show(),"true"===a(this).attr("data-reload")&&c.reload_items(),!1},add_item:function(){return a(this).WCBackboneModal({template:"wc-modal-add-products"}),!1},add_fee:function(){c.block();var b={action:"woocommerce_add_order_fee",order_id:woocommerce_admin_meta_boxes.post_id,security:woocommerce_admin_meta_boxes.order_item_nonce};return a.post(woocommerce_admin_meta_boxes.ajax_url,b,function(b){a("table.woocommerce_order_items tbody#order_fee_line_items").append(b),c.unblock()}),!1},add_shipping:function(){c.block();var b={action:"woocommerce_add_order_shipping",order_id:woocommerce_admin_meta_boxes.post_id,security:woocommerce_admin_meta_boxes.order_item_nonce};return a.post(woocommerce_admin_meta_boxes.ajax_url,b,function(b){a("table.woocommerce_order_items tbody#order_shipping_line_items").append(b),c.unblock()}),!1},add_tax:function(){return a(this).WCBackboneModal({template:"wc-modal-add-tax"}),!1},edit_item:function(){return a(this).closest("tr").find(".view").hide(),a(this).closest("tr").find(".edit").show(),a(this).hide(),a("button.add-line-item").click(),a("button.cancel-action").attr("data-reload",!0),!1},delete_item:function(){var b=window.confirm(woocommerce_admin_meta_boxes.remove_item_notice);if(b){var d=a(this).closest("tr.item, tr.fee, tr.shipping"),e=d.attr("data-order_item_id");c.block();var f={order_item_ids:e,action:"woocommerce_remove_order_item",security:woocommerce_admin_meta_boxes.order_item_nonce};a.ajax({url:woocommerce_admin_meta_boxes.ajax_url,data:f,type:"POST",success:function(){d.remove(),c.unblock()}})}return!1},delete_tax:function(){if(window.confirm(woocommerce_admin_meta_boxes.i18n_delete_tax)){c.block();var d={action:"woocommerce_remove_order_tax",rate_id:a(this).attr("data-rate_id"),order_id:woocommerce_admin_meta_boxes.post_id,security:woocommerce_admin_meta_boxes.order_item_nonce};a.ajax({url:woocommerce_admin_meta_boxes.ajax_url,data:d,type:"POST",success:function(d){a("#woocommerce-order-items").find(".inside").empty(),a("#woocommerce-order-items").find(".inside").append(d),b.init_tiptip(),c.unblock(),c.stupidtable.init()}})}return!1},calculate_tax:function(){if(window.confirm(woocommerce_admin_meta_boxes.calc_line_taxes)){c.block();var d="",e="",f="",g="";"shipping"===woocommerce_admin_meta_boxes.tax_based_on&&(d=a("#_shipping_country").val(),e=a("#_shipping_state").val(),f=a("#_shipping_postcode").val(),g=a("#_shipping_city").val()),"billing"!==woocommerce_admin_meta_boxes.tax_based_on&&d||(d=a("#_billing_country").val(),e=a("#_billing_state").val(),f=a("#_billing_postcode").val(),g=a("#_billing_city").val());var h={action:"woocommerce_calc_line_taxes",order_id:woocommerce_admin_meta_boxes.post_id,items:a("table.woocommerce_order_items :input[name], .wc-order-totals-items :input[name]").serialize(),country:d,state:e,postcode:f,city:g,security:woocommerce_admin_meta_boxes.calc_totals_nonce};a.ajax({url:woocommerce_admin_meta_boxes.ajax_url,data:h,type:"POST",success:function(d){a("#woocommerce-order-items").find(".inside").empty(),a("#woocommerce-order-items").find(".inside").append(d),b.init_tiptip(),c.unblock(),c.stupidtable.init()}})}return!1},calculate_totals:function(){if(window.confirm(woocommerce_admin_meta_boxes.calc_totals)){c.block();var b=0,d=0,e=0;a(".woocommerce_order_items tr.shipping input.line_total").each(function(){var b=a(this).val()||"0";b=accounting.unformat(b,woocommerce_admin.mon_decimal_point),e+=parseFloat(b)}),a(".woocommerce_order_items input.line_tax").each(function(){var b=a(this).val()||"0";b=accounting.unformat(b,woocommerce_admin.mon_decimal_point),d+=parseFloat(b)}),a(".woocommerce_order_items tr.item, .woocommerce_order_items tr.fee").each(function(){var c=a(this).find("input.line_total").val()||"0";b+=accounting.unformat(c.replace(",","."))}),"yes"===woocommerce_admin_meta_boxes.round_at_subtotal&&(d=parseFloat(accounting.toFixed(d,woocommerce_admin_meta_boxes.rounding_precision))),a("#_order_total").val(accounting.formatNumber(b+d+e,woocommerce_admin_meta_boxes.currency_format_num_decimals,"",woocommerce_admin.mon_decimal_point)).change(),a("button.save-action").click()}return!1},save_line_items:function(){var d={order_id:woocommerce_admin_meta_boxes.post_id,items:a("table.woocommerce_order_items :input[name], .wc-order-totals-items :input[name]").serialize(),action:"woocommerce_save_order_items",security:woocommerce_admin_meta_boxes.order_item_nonce};return c.block(),a.ajax({url:woocommerce_admin_meta_boxes.ajax_url,data:d,type:"POST",success:function(d){a("#woocommerce-order-items").find(".inside").empty(),a("#woocommerce-order-items").find(".inside").append(d),b.init_tiptip(),c.unblock(),c.stupidtable.init()}}),a(this).trigger("items_saved"),!1},refunds:{do_refund:function(){if(c.block(),window.confirm(woocommerce_admin_meta_boxes.i18n_do_refund)){var b=a("input#refund_amount").val(),d=a("input#refund_reason").val(),e={},f={},g={};a(".refund input.refund_order_item_qty").each(function(b,c){a(c).closest("tr").data("order_item_id")&&c.value&&(e[a(c).closest("tr").data("order_item_id")]=c.value)}),a(".refund input.refund_line_total").each(function(b,c){a(c).closest("tr").data("order_item_id")&&(f[a(c).closest("tr").data("order_item_id")]=accounting.unformat(c.value,woocommerce_admin.mon_decimal_point))}),a(".refund input.refund_line_tax").each(function(b,c){if(a(c).closest("tr").data("order_item_id")){var d=a(c).data("tax_id");g[a(c).closest("tr").data("order_item_id")]||(g[a(c).closest("tr").data("order_item_id")]={}),g[a(c).closest("tr").data("order_item_id")][d]=accounting.unformat(c.value,woocommerce_admin.mon_decimal_point)}});var h={action:"woocommerce_refund_line_items",order_id:woocommerce_admin_meta_boxes.post_id,refund_amount:b,refund_reason:d,line_item_qtys:JSON.stringify(e,null,""),line_item_totals:JSON.stringify(f,null,""),line_item_tax_totals:JSON.stringify(g,null,""),api_refund:a(this).is(".do-api-refund"),restock_refunded_items:a("#restock_refunded_items:checked").size()?"true":"false",security:woocommerce_admin_meta_boxes.order_item_nonce};a.post(woocommerce_admin_meta_boxes.ajax_url,h,function(a){!0===a.success?(c.reload_items(),"fully_refunded"===a.data.status&&(window.location.href=window.location.href)):(window.alert(a.data.error),c.unblock())})}else c.unblock()},delete_refund:function(){if(window.confirm(woocommerce_admin_meta_boxes.i18n_delete_refund)){var b=a(this).closest("tr.refund"),d=b.attr("data-order_refund_id");c.block();var e={action:"woocommerce_delete_refund",refund_id:d,security:woocommerce_admin_meta_boxes.order_item_nonce};a.ajax({url:woocommerce_admin_meta_boxes.ajax_url,data:e,type:"POST",success:function(){c.reload_items()}})}return!1},input_changed:function(){var b=0,c=a(".woocommerce_order_items").find("tr.item, tr.fee, tr.shipping");c.each(function(){var c=a(this),d=c.find(".refund input:not(.refund_order_item_qty)");d.each(function(c,d){b+=parseFloat(accounting.unformat(a(d).val()||0,woocommerce_admin.mon_decimal_point))})}),a("#refund_amount").val(accounting.formatNumber(b,woocommerce_admin_meta_boxes.currency_format_num_decimals,"",woocommerce_admin.mon_decimal_point)).change()},amount_changed:function(){var b=accounting.unformat(a(this).val(),woocommerce_admin.mon_decimal_point);a("button .wc-order-refund-amount .amount").text(accounting.formatMoney(b,{symbol:woocommerce_admin_meta_boxes.currency_format_symbol,decimal:woocommerce_admin_meta_boxes.currency_format_decimal_sep,thousand:woocommerce_admin_meta_boxes.currency_format_thousand_sep,precision:woocommerce_admin_meta_boxes.currency_format_num_decimals,format:woocommerce_admin_meta_boxes.currency_format}))},refund_quantity_changed:function(){var b=a(this).closest("tr.item"),c=b.find("input.quantity").val(),d=a(this).val(),e=a("input.line_total",b),f=a("input.refund_line_total",b),g=accounting.unformat(e.attr("data-total"),woocommerce_admin.mon_decimal_point)/c;f.val(parseFloat(accounting.formatNumber(g*d,woocommerce_admin_meta_boxes.rounding_precision,"")).toString().replace(".",woocommerce_admin.mon_decimal_point)).change(),a("td.line_tax",b).each(function(){var b=a("input.line_tax",a(this)),e=a("input.refund_line_tax",a(this)),f=accounting.unformat(b.attr("data-total_tax"),woocommerce_admin.mon_decimal_point)/c;f>0?e.val(parseFloat(accounting.formatNumber(f*d,woocommerce_admin_meta_boxes.rounding_precision,"")).toString().replace(".",woocommerce_admin.mon_decimal_point)).change():e.val(0).change()}),d>0?a("#restock_refunded_items").closest("tr").show():(a("#restock_refunded_items").closest("tr").hide(),a(".woocommerce_order_items input.refund_order_item_qty").each(function(){a(this).val()>0&&a("#restock_refunded_items").closest("tr").show()})),a(this).trigger("refund_quantity_changed")}},item_meta:{add:function(){var b=a(this),d=b.closest("tr.item"),e={order_item_id:d.attr("data-order_item_id"),action:"woocommerce_add_order_item_meta",security:woocommerce_admin_meta_boxes.order_item_nonce};return c.block(),a.ajax({url:woocommerce_admin_meta_boxes.ajax_url,data:e,type:"POST",success:function(a){d.find("tbody.meta_items").append(a),c.unblock()}}),!1},remove:function(){if(window.confirm(woocommerce_admin_meta_boxes.remove_item_meta)){var b=a(this).closest("tr"),d={meta_id:b.attr("data-meta_id"),action:"woocommerce_remove_order_item_meta",security:woocommerce_admin_meta_boxes.order_item_nonce};c.block(),a.ajax({url:woocommerce_admin_meta_boxes.ajax_url,data:d,type:"POST",success:function(){b.hide(),c.unblock()}})}return!1}},bulk_actions:{check_column:function(){a(this).is(":checked")?a("#woocommerce-order-items").find(".check-column input").attr("checked","checked"):a("#woocommerce-order-items").find(".check-column input").removeAttr("checked")},do_bulk_action:function(){var b=a(this).closest(".bulk-actions").find("select").val(),d=a("#woocommerce-order-items").find(".check-column input:checked"),e=[];return a(d).each(function(){var b=a(this).closest("tr");b.attr("data-order_item_id")&&e.push(b.attr("data-order_item_id"))}),0===e.length?void window.alert(woocommerce_admin_meta_boxes.i18n_select_items):(c.bulk_actions["do_"+b]&&c.bulk_actions["do_"+b](d,e),!1)},do_delete:function(b,d){if(window.confirm(woocommerce_admin_meta_boxes.remove_item_notice)){c.block();var e={order_item_ids:d,action:"woocommerce_remove_order_item",security:woocommerce_admin_meta_boxes.order_item_nonce};a.ajax({url:woocommerce_admin_meta_boxes.ajax_url,data:e,type:"POST",success:function(){a(b).each(function(){a(this).closest("tr").remove()}),c.unblock()}})}},do_increase_stock:function(b,d){c.block();var e={};a(b).each(function(){var b=a(this).closest("tr.item, tr.fee"),c=b.find("input.quantity");e[b.attr("data-order_item_id")]=c.val()});var f={order_id:woocommerce_admin_meta_boxes.post_id,order_item_ids:d,order_item_qty:e,action:"woocommerce_increase_order_item_stock",security:woocommerce_admin_meta_boxes.order_item_nonce};a.ajax({url:woocommerce_admin_meta_boxes.ajax_url,data:f,type:"POST",success:function(a){window.alert(a),c.unblock()}})},do_reduce_stock:function(b,d){c.block();var e={};a(b).each(function(){var b=a(this).closest("tr.item, tr.fee"),c=b.find("input.quantity");e[b.attr("data-order_item_id")]=c.val()});var f={order_id:woocommerce_admin_meta_boxes.post_id,order_item_ids:d,order_item_qty:e,action:"woocommerce_reduce_order_item_stock",security:woocommerce_admin_meta_boxes.order_item_nonce};a.ajax({url:woocommerce_admin_meta_boxes.ajax_url,data:f,type:"POST",success:function(a){window.alert(a),c.unblock()}})}},backbone:{init:function(b,c){"wc-modal-add-products"===c&&a(document.body).trigger("wc-enhanced-select-init")},response:function(a,b,d){if("wc-modal-add-tax"===b){var e=d.add_order_tax,f="";d.manual_tax_rate_id&&(f=d.manual_tax_rate_id),c.backbone.add_tax(e,f)}"wc-modal-add-products"===b&&c.backbone.add_item(d.add_order_items)},add_item:function(d){if(d=d.split(",")){var e=d.length;c.block(),a.each(d,function(d,f){var g={action:"woocommerce_add_order_item",item_to_add:f,order_id:woocommerce_admin_meta_boxes.post_id,security:woocommerce_admin_meta_boxes.order_item_nonce};a.post(woocommerce_admin_meta_boxes.ajax_url,g,function(d){a("table.woocommerce_order_items tbody#order_line_items").append(d),--e||(b.init_tiptip(),c.unblock())})})}},add_tax:function(d,e){if(e&&(d=e),!d)return!1;var f=a(".order-tax-id").map(function(){return a(this).val()}).get();if(-1===a.inArray(d,f)){c.block();var g={action:"woocommerce_add_order_tax",rate_id:d,order_id:woocommerce_admin_meta_boxes.post_id,security:woocommerce_admin_meta_boxes.order_item_nonce};a.ajax({url:woocommerce_admin_meta_boxes.ajax_url,data:g,type:"POST",success:function(d){a("#woocommerce-order-items").find(".inside").empty(),a("#woocommerce-order-items").find(".inside").append(d),b.init_tiptip(),c.unblock(),c.stupidtable.init()}})}else window.alert(woocommerce_admin_meta_boxes.i18n_tax_rate_already_exists)}},stupidtable:{init:function(){a(".woocommerce_order_items").stupidtable().on("aftertablesort",this.add_arrows)},add_arrows:function(b,c){var d=a(this).find("th"),e="asc"===c.direction?"&uarr;":"&darr;",f=c.column;f>1&&(f-=1),d.find(".wc-arrow").remove(),d.eq(f).append('<span class="wc-arrow">'+e+"</span>")}}},d={init:function(){a("#woocommerce-order-notes").on("click","a.add_note",this.add_order_note).on("click","a.delete_note",this.delete_order_note)},add_order_note:function(){if(a("textarea#add_order_note").val()){a("#woocommerce-order-notes").block({message:null,overlayCSS:{background:"#fff",opacity:.6}});var b={action:"woocommerce_add_order_note",post_id:woocommerce_admin_meta_boxes.post_id,note:a("textarea#add_order_note").val(),note_type:a("select#order_note_type").val(),security:woocommerce_admin_meta_boxes.add_order_note_nonce};return a.post(woocommerce_admin_meta_boxes.ajax_url,b,function(b){a("ul.order_notes").prepend(b),a("#woocommerce-order-notes").unblock(),a("#add_order_note").val("")}),!1}},delete_order_note:function(){var b=a(this).closest("li.note");a(b).block({message:null,overlayCSS:{background:"#fff",opacity:.6}});var c={action:"woocommerce_delete_order_note",note_id:a(b).attr("rel"),security:woocommerce_admin_meta_boxes.delete_order_note_nonce};return a.post(woocommerce_admin_meta_boxes.ajax_url,c,function(){a(b).remove()}),!1}},e={init:function(){a(".order_download_permissions").on("click","button.grant_access",this.grant_access).on("click","button.revoke_access",this.revoke_access)},grant_access:function(){var b=a("#grant_access_id").val();if(b){a(".order_download_permissions").block({message:null,overlayCSS:{background:"#fff",opacity:.6}});var c={action:"woocommerce_grant_access_to_download",product_ids:b,loop:a(".order_download_permissions .wc-metabox").size(),order_id:woocommerce_admin_meta_boxes.post_id,security:woocommerce_admin_meta_boxes.grant_access_nonce};return a.post(woocommerce_admin_meta_boxes.ajax_url,c,function(b){b?a(".order_download_permissions .wc-metaboxes").append(b):window.alert(woocommerce_admin_meta_boxes.i18n_download_permission_fail),a(document.body).trigger("wc-init-datepickers"),a("#grant_access_id").val("").change(),a(".order_download_permissions").unblock()}),!1}},revoke_access:function(){if(window.confirm(woocommerce_admin_meta_boxes.i18n_permission_revoke)){var b=a(this).parent().parent(),c=a(this).attr("rel").split(",")[0],d=a(this).attr("rel").split(",")[1];if(c>0){a(b).block({message:null,overlayCSS:{background:"#fff",opacity:.6}});var e={action:"woocommerce_revoke_access_to_download",product_id:c,download_id:d,order_id:woocommerce_admin_meta_boxes.post_id,security:woocommerce_admin_meta_boxes.revoke_access_nonce};a.post(woocommerce_admin_meta_boxes.ajax_url,e,function(){a(b).fadeOut("300",function(){a(b).remove()})})}else a(b).fadeOut("300",function(){a(b).remove()})}return!1}};b.init(),c.init(),d.init(),e.init()});                                                                  /*!
 * accounting.js v0.4.2
 * Copyright 2014 Open Exchange Rates
 *
 * Freely distributable under the MIT license.
 * Portions of accounting.js are inspired or borrowed from underscore.js
 *
 * Full details and documentation:
 * http://openexchangerates.github.io/accounting.js/
 */

(function(root, undefined) {

	/* --- Setup --- */

	// Create the local library object, to be exported or referenced globally later
	var lib = {};

	// Current version
	lib.version = '0.4.1';


	/* --- Exposed settings --- */

	// The library's settings configuration object. Contains default parameters for
	// currency and number formatting
	lib.settings = {
		currency: {
			symbol : "$",		// default currency symbol is '$'
			format : "%s%v",	// controls output: %s = symbol, %v = value (can be object, see docs)
			decimal : ".",		// decimal point separator
			thousand : ",",		// thousands separator
			precision : 2,		// decimal places
			grouping : 3		// digit grouping (not implemented yet)
		},
		number: {
			precision : 0,		// default precision on numbers is 0
			grouping : 3,		// digit grouping (not implemented yet)
			thousand : ",",
			decimal : "."
		}
	};


	/* --- Internal Helper Methods --- */

	// Store reference to possibly-available ECMAScript 5 methods for later
	var nativeMap = Array.prototype.map,
		nativeIsArray = Array.isArray,
		toString = Object.prototype.toString;

	/**
	 * Tests whether supplied parameter is a string
	 * from underscore.js
	 */
	function isString(obj) {
		return !!(obj === '' || (obj && obj.charCodeAt && obj.substr));
	}

	/**
	 * Tests whether supplied parameter is a string
	 * from underscore.js, delegates to ECMA5's native Array.isArray
	 */
	function isArray(obj) {
		return nativeIsArray ? nativeIsArray(obj) : toString.call(obj) === '[object Array]';
	}

	/**
	 * Tests whether supplied parameter is a true object
	 */
	function isObject(obj) {
		return obj && toString.call(obj) === '[object Object]';
	}

	/**
	 * Extends an object with a defaults object, similar to underscore's _.defaults
	 *
	 * Used for abstracting parameter handling from API methods
	 */
	function defaults(object, defs) {
		var key;
		object = object || {};
		defs = defs || {};
		// Iterate over object non-prototype properties:
		for (key in defs) {
			if (defs.hasOwnProperty(key)) {
				// Replace values with defaults only if undefined (allow empty/zero values):
				if (object[key] == null) object[key] = defs[key];
			}
		}
		return object;
	}

	/**
	 * Implementation of `Array.map()` for iteration loops
	 *
	 * Returns a new Array as a result of calling `iterator` on each array value.
	 * Defers to native Array.map if available
	 */
	function map(obj, iterator, context) {
		var results = [], i, j;

		if (!obj) return results;

		// Use native .map method if it exists:
		if (nativeMap && obj.map === nativeMap) return obj.map(iterator, context);

		// Fallback for native .map:
		for (i = 0, j = obj.length; i < j; i++ ) {
			results[i] = iterator.call(context, obj[i], i, obj);
		}
		return results;
	}

	/**
	 * Check and normalise the value of precision (must be positive integer)
	 */
	function checkPrecision(val, base) {
		val = Math.round(Math.abs(val));
		return isNaN(val)? base : val;
	}


	/**
	 * Parses a format string or object and returns format obj for use in rendering
	 *
	 * `format` is either a string with the default (positive) format, or object
	 * containing `pos` (required), `neg` and `zero` values (or a function returning
	 * either a string or object)
	 *
	 * Either string or format.pos must contain "%v" (value) to be valid
	 */
	function checkCurrencyFormat(format) {
		var defaults = lib.settings.currency.format;

		// Allow function as format parameter (should return string or object):
		if ( typeof format === "function" ) format = format();

		// Format can be a string, in which case `value` ("%v") must be present:
		if ( isString( format ) && format.match("%v") ) {

			// Create and return positive, negative and zero formats:
			return {
				pos : format,
				neg : format.replace("-", "").replace("%v", "-%v"),
				zero : format
			};

		// If no format, or object is missing valid positive value, use defaults:
		} else if ( !format || !format.pos || !format.pos.match("%v") ) {

			// If defaults is a string, casts it to an object for faster checking next time:
			return ( !isString( defaults ) ) ? defaults : lib.settings.currency.format = {
				pos : defaults,
				neg : defaults.replace("%v", "-%v"),
				zero : defaults
			};

		}
		// Otherwise, assume format was fine:
		return format;
	}


	/* --- API Methods --- */

	/**
	 * Takes a string/array of strings, removes all formatting/cruft and returns the raw float value
	 * Alias: `accounting.parse(string)`
	 *
	 * Decimal must be included in the regular expression to match floats (defaults to
	 * accounting.settings.number.decimal), so if the number uses a non-standard decimal
	 * separator, provide it as the second argument.
	 *
	 * Also matches bracketed negatives (eg. "$ (1.99)" => -1.99)
	 *
	 * Doesn't throw any errors (`NaN`s become 0) but this may change in future
	 */
	var unformat = lib.unformat = lib.parse = function(value, decimal) {
		// Recursively unformat arrays:
		if (isArray(value)) {
			return map(value, function(val) {
				return unformat(val, decimal);
			});
		}

		// Fails silently (need decent errors):
		value = value || 0;

		// Return the value as-is if it's already a number:
		if (typeof value === "number") return value;

		// Default decimal point comes from settings, but could be set to eg. "," in opts:
		decimal = decimal || lib.settings.number.decimal;

		 // Build regex to strip out everything except digits, decimal point and minus sign:
		var regex = new RegExp("[^0-9-" + decimal + "]", ["g"]),
			unformatted = parseFloat(
				("" + value)
				.replace(/\((.*)\)/, "-$1") // replace bracketed values with negatives
				.replace(regex, '')         // strip out any cruft
				.replace(decimal, '.')      // make sure decimal point is standard
			);

		// This will fail silently which may cause trouble, let's wait and see:
		return !isNaN(unformatted) ? unformatted : 0;
	};


	/**
	 * Implementation of toFixed() that treats floats more like decimals
	 *
	 * Fixes binary rounding issues (eg. (0.615).toFixed(2) === "0.61") that present
	 * problems for accounting- and finance-related software.
	 */
	var toFixed = lib.toFixed = function(value, precision) {
		precision = checkPrecision(precision, lib.settings.number.precision);
		var power = Math.pow(10, precision);

		// Multiply up by precision, round accurately, then divide and use native toFixed():
		return (Math.round(lib.unformat(value) * power) / power).toFixed(precision);
	};


	/**
	 * Format a number, with comma-separated thousands and custom precision/decimal places
	 * Alias: `accounting.format()`
	 *
	 * Localise by overriding the precision and thousand / decimal separators
	 * 2nd parameter `precision` can be an object matching `settings.number`
	 */
	var formatNumber = lib.formatNumber = lib.format = function(number, precision, thousand, decimal) {
		// Resursively format arrays:
		if (isArray(number)) {
			return map(number, function(val) {
				return formatNumber(val, precision, thousand, decimal);
			});
		}

		// Clean up number:
		number = unformat(number);

		// Build options object from second param (if object) or all params, extending defaults:
		var opts = defaults(
				(isObject(precision) ? precision : {
					precision : precision,
					thousand : thousand,
					decimal : decimal
				}),
				lib.settings.number
			),

			// Clean up precision
			usePrecision = checkPrecision(opts.precision),

			// Do some calc:
			negative = number < 0 ? "-" : "",
			base = parseInt(toFixed(Math.abs(number || 0), usePrecision), 10) + "",
			mod = base.length > 3 ? base.length % 3 : 0;

		// Format the number:
		return negative + (mod ? base.substr(0, mod) + opts.thousand : "") + base.substr(mod).replace(/(\d{3})(?=\d)/g, "$1" + opts.thousand) + (usePrecision ? opts.decimal + toFixed(Math.abs(number), usePrecision).split('.')[1] : "");
	};


	/**
	 * Format a number into currency
	 *
	 * Usage: accounting.formatMoney(number, symbol, precision, thousandsSep, decimalSep, format)
	 * defaults: (0, "$", 2, ",", ".", "%s%v")
	 *
	 * Localise by overriding the symbol, precision, thousand / decimal separators and format
	 * Second param can be an object matching `settings.currency` which is the easiest way.
	 *
	 * To do: tidy up the parameters
	 */
	var formatMoney = lib.formatMoney = function(number, symbol, precision, thousand, decimal, format) {
		// Resursively format arrays:
		if (isArray(number)) {
			return map(number, function(val){
				return formatMoney(val, symbol, precision, thousand, decimal, format);
			});
		}

		// Clean up number:
		number = unformat(number);

		// Build options object from second param (if object) or all params, extending defaults:
		var opts = defaults(
				(isObject(symbol) ? symbol : {
					symbol : symbol,
					precision : precision,
					thousand : thousand,
					decimal : decimal,
					format : format
				}),
				lib.settings.currency
			),

			// Check format (returns object with pos, neg and zero):
			formats = checkCurrencyFormat(opts.format),

			// Choose which format to use for this value:
			useFormat = number > 0 ? formats.pos : number < 0 ? formats.neg : formats.zero;

		// Return with currency symbol added:
		return useFormat.replace('%s', opts.symbol).replace('%v', formatNumber(Math.abs(number), checkPrecision(opts.precision), opts.thousand, opts.decimal));
	};


	/**
	 * Format a list of numbers into an accounting column, padding with whitespace
	 * to line up currency symbols, thousand separators and decimals places
	 *
	 * List should be an array of numbers
	 * Second parameter can be an object containing keys that match the params
	 *
	 * Returns array of accouting-formatted number strings of same length
	 *
	 * NB: `white-space:pre` CSS rule is required on the list container to prevent
	 * browsers from collapsing the whitespace in the output strings.
	 */
	lib.formatColumn = function(list, symbol, precision, thousand, decimal, format) {
		if (!list) return [];

		// Build options object from second param (if object) or all params, extending defaults:
		var opts = defaults(
				(isObject(symbol) ? symbol : {
					symbol : symbol,
					precision : precision,
					thousand : thousand,
					decimal : decimal,
					format : format
				}),
				lib.settings.currency
			),

			// Check format (returns object with pos, neg and zero), only need pos for now:
			formats = checkCurrencyFormat(opts.format),

			// Whether to pad at start of string or after currency symbol:
			padAfterSymbol = formats.pos.indexOf("%s") < formats.pos.indexOf("%v") ? true : false,

			// Store value for the length of the longest string in the column:
			maxLength = 0,

			// Format the list according to options, store the length of the longest string:
			formatted = map(list, function(val, i) {
				if (isArray(val)) {
					// Recursively format columns if list is a multi-dimensional array:
					return lib.formatColumn(val, opts);
				} else {
					// Clean up the value
					val = unformat(val);

					// Choose which format to use for this value (pos, neg or zero):
					var useFormat = val > 0 ? formats.pos : val < 0 ? formats.neg : formats.zero,

						// Format this value, push into formatted list and save the length:
						fVal = useFormat.replace('%s', opts.symbol).replace('%v', formatNumber(Math.abs(val), checkPrecision(opts.precision), opts.thousand, opts.decimal));

					if (fVal.length > maxLength) maxLength = fVal.length;
					return fVal;
				}
			});

		// Pad each number in the list and send back the column of numbers:
		return map(formatted, function(val, i) {
			// Only if this is a string (not a nested array, which would have already been padded):
			if (isString(val) && val.length < maxLength) {
				// Depending on symbol position, pad after symbol or at index 0:
				return padAfterSymbol ? val.replace(opts.symbol, opts.symbol+(new Array(maxLength - val.length + 1).join(" "))) : (new Array(maxLength - val.length + 1).join(" ")) + val;
			}
			return val;
		});
	};


	/* --- Module Definition --- */

	// Export accounting for CommonJS. If being loaded as an AMD module, define it as such.
	// Otherwise, just add `accounting` to the global object
	if (typeof exports !== 'undefined') {
		if (typeof module !== 'undefined' && module.exports) {
			exports = module.exports = lib;
		}
		exports.accounting = lib;
	} else if (typeof define === 'function' && define.amd) {
		// Return the library as an AMD module:
		define([], function() {
			return lib;
		});
	} else {
		// Use accounting.noConflict to restore `accounting` back to its original value.
		// Returns a reference to the library's `accounting` object;
		// e.g. `var numbers = accounting.noConflict();`
		lib.noConflict = (function(oldAccounting) {
			return function() {
				// Reset the value of the root's `accounting` variable:
				root.accounting = oldAccounting;
				// Delete the noConflict method:
				lib.noConflict = undefined;
				// Return reference to the library to re-assign it:
				return lib;
			};
		})(root.accounting);

		// Declare `fx` on the root (global/window) object:
		root['accounting'] = lib;
	}

	// Root will be `window` in browser or `global` on the server:
}(this));
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  ���� JFIF  H H  �� C 		



          �� C
���P�����F�V�~�ֱ���P�b�2����u��!�9�&/� ���ԇE�����)�!�9�&/� ���ԇE�����)�!�9�&/� ��D�s
�)Ʋp�#c�ڰ���s�X��^���远տ��>�:/�5o���A�(�=����:m!�rST:�<�sZ���p����zv/�-����&�6�HC���������is��i^���远տ��>�:/�5o���A��4�ĩi]�8d��R�W%T�����I�1����h�e��h���~��� a��V�{Z[���."ۂ�'ԇE�����)�!�9�&/�:b|�8uMh�J�L�^
w�����9���g��ۺ�V��}�!�9�&/�}Ht_�j�ɋ�ϴ�`�4�5<��飞8�P&{5�ɒ����:�2�fM�=
��h��>)W���V1���#�6��6b�m�)h=;�OԇE�����)�!�9�&/�7P�-
j���j|2��=%�j<r?��Wk�c~�+�i'w��q^���远տ��>�:/�5o���A��ઊr�aҜ2J��е� ��8��Ӕ:���ujna�6�t�
mL�5Qk�;X��;�gYz�C��sV�L_��远տ���]������ǰ�'PIr��k"ݭ��f����V!�jNT��8�'�K���H��#���vf�k����^����ǜ�ߓ�>�:/�5o���A�83�}C��J�8F热�vfg ��k,*���&�a��0��#�ٺ��$���Y��m�^���远տ��>�:/�5o���A�c��#Y$�Ҭ2!��s��摐4����A_t���QaU�8c�����LD9��#������-���Yz�C��sV�L_��远տ�����E���(1���k��r���^2���^�m��Z^���远տ��>�:/�5o���A�^���远տ��>�:/�5o���A�^���远տ��>�:/�5o���A���� ߸w����z��C��sV�L_z��U�4��U7I+K�����ia��A��D@DDD@DDD@DDD@DDD@DDD@DDD@DDD@DDD@DDD@DDD@DDD@DDD@DDD@DDD@DDD@DDD@DDD@DDD@DDD@DDD@DDDAO�T�&&��3M� Da۠ﭓ�8z�^35���2I�mq=��-�KzU��H��
��篒s��#)f@9�l�j��E�Zzr�lfV:�5�%�ۙ`\w~�+d��-�Q�,PS�OG3�j%`�Jzv�nvn�VB��)oJ
���)oJ
���)oJ
���)oJ
���)oJ
���)oJ
���)oJ
���)oJ
���)oJ
���)oJ
���)oJ
���)oJ
���)oJ
���)oJ
���)oJ
���)oJ
���)oJ
���)oJ
���)oJ
�"" """ """ """ """ ""*f�~v�*�""" """ """ """ """ """ """ ""+��t��VL�zX�i�6kZ:ʱA�a�+*�*�	��^�쮸6#+��(3�X�����_G<u0fs5�8=�������}1�~K]>&�-^ыX���S�Lj5z��ֱ��eۚ.��Z,gH0|F�8�+At�Dܔ`:�=kN���By�;�D8nki���
��G0Y&�龜�O�EM����^��v�|�$ �`�" """ """ """ """ """ """ ""o��0ɰ���.Wk"9^���$oi7k����+�+��*�j�J����y���{�C���5�6�ض�V����Hh+8�S���Vٲ�����lZ� '4���� ������J<��tw�z�����WJ�#[�y��H-S��w�y� ��|S��w�y� ��|Pmh�NNiߝ��M�NNiߝ��M�A���99�~w��}7�99�~w��}7�֋T���������Ӑ��iisA��}8���{���Q��0`�fMA;[�5��� \��\7��_|���%X��ˡ-�]�
��()g�9~�g7��m���6)�DD@DDD@DDD@DDD@DD��/��cPbt�dy���r���.���Ђ	��L�.�k�
�u��~*����6�n$�n�Yd�D�'��/�+����x��A��N�V��L���ӻX�~ɵ��JY����kkX�����%f����k�(��{��Z@��� 4 �� ATEnx�$G!��wl;�\K��E˄S�mL���{�vۏZ��.��>�����v�m��3�J�lm)�����FA-���o�RH���Y���ͮ������l��c�kv�|c1	iLD����.n�qд�
����8t�������m�V���""" """ """ """ """ ""(��6c�;�5�x��(�|�9M|�9A&��e35�&^��u���>)G��z���6�M"�������Q&�3_7�Vm+��Aq�ڂ�*�Fk���	DQz��r���r�Q^�o��o��E���)���(%E����k���	DV��0�����f$D�6 �E���)���(%E����k���	DQz��r���r�Q^�o��o��E���*Q���AT"��qEK�I��ۓy�B�������"�=;��k����=tM�$Y�f���oa��������WU�=1ax���� v����zX�̒1�!�R�PwunAu�7E�t{������`�_P�M5�22@ݎ�C�q~�AA�����^�����`�*����������������������"�߻�����i{�V�i'p_o���V+� v�� �n�*1<<� �������C`��$A^D�f"_U����mv���`������x��SE��z��X�k#`��h����5��z�T"���pXo�x�l�����]�'i%yr�����a�u�Znf3e'��Zc�H_���}�H1�{s0���#&e�������� 2�ǒ�T����R~ ~*��Ң��Ң�'dciV��#��|�_+:�z��qLS�vRK�q��l8��a4U1SMUU��c{�.#m��\�i�?��zZ�~-Y%���~���@��:Km^�]_V��AІ�a��KKDrT��_\��vkn����a�y\��=B
H�g�/|�{��J����h.:�t8��B9X�pW���\]�-�+j�ٛt�������R��3�V��?���DD�$��7I#�c`���������Ӻ�a��Q-4��3^��`{͍���a%͌}%�z-*~E��C�v���K?[�"m��7�u\H�g�æRT73����t���q�;�������zA����8[l2X�<�?����޽�K��'��]Z�$\�m�g�Գ;��(�,��>�����K]���k�+j;'�kc�S)���m���6�
_���p�F�Ŕ�q>y�.�#���6'`\���'�s{-OȬ���8/�a�,�H�
f���rO�}%m78 �vc7��^�a����'�]n���ts^wzTB�wzT3��㹢��A�i5m�l �c�����AB�N*�E���F�v�GM3�;�������EM�bv�5�*cm���hk��� �	���jH��2��`�\�zN�.'W�8�>YQJ̬f�o��+���c�B�8��(|N��8\4���;��%�H����Wk?�:8�n���DN%�o�ޔ��,v*J�kh㫄82A���㠂ӴB�<��0�����8�eD8�$4Ε�H��5�ؽ�]�v\���e��M�KYMrJ7�nGm������Ֆۡoxd���8ے&۬|߀9��m7�gb�G�aT���U���("�qo����i��_�C�����;u�i��,q0�G�0os�����R��T�w��\�sv�_z�ؔ3�ecws��u����((�VV����l[�o�b�qN��v#~Z	*݅��|Dff{ݱ����)zjgC��y�w���?�:�n8K\\�D-S���-7�[�0�Գ;��(�,��>�?�H� ����^"��06W��$���c��f��ls�Gu~������ 핟��5�a�5�ji���G=퍍cw��ykZ6��v�r�l5m���N�y16ϩ.�<�����0��ݷ%T��:SY���"��Knx��:F�u���X��*}�H[.T-��s��na�m۴��>�����!�f<�\E���9�:M�C�i���ic�X�#Ynpy�����8 � ꎌ� �G��Z�ѼuճPǇT�YN��D���8�ɚ�[
�C����<�i��b8lQPQU	jd0���p�V��z�'�� ��
�l��M�.+Nnx����J	!G��ԅ����J�R��J�@Zf���?Jޘcs�ސ��[��4չ��&g��1�������W��XA�د5��n;>�3��n�_e�X�k���~� �v:��K�7���Ơ�
�7�gb�G�aA�W�#����cw�W�:��
? ?�ޕ��ޕ��暹�I]��Z,޷.���)�LZ���ke���@�<^���h5ئq�YM��~�������ܿ��_�u��7 ��.}=�	pv<��z���b�n	���6�br��g辇b������lP:\Q�}�\��n�A�a��)|EF�_h$�;j<�
�7�gb�G�aA�����ܹ����ٶ��إF��:|(�3Y+^���0߶�.���]8�e;�(��|�.m��˝���;V�[��\�	�_�Q�V��\S2�)e{ esf
�g�.��3�)�uV�΄����4���h����s�qpo ���|F��qCN�uL� �DDD@DDD@DDD@DDD@DDD@DDO���W��4-$��j�g�Ps��gD�
Ī���T�����p\ɡ���r0��.7���49�a.�Oj�ҽ�H�k��U%,x�&�J��K��{\n\q�C�v�$�;5p|�V�'��E��H����\�[����,��o���S�H+���1�(,��O7q���!�C�j�B��,v��:�_+)�����]���6m�.��q�^,����O>;�}*_����~#��@:���G��F<���>U�x/��������+��d�W	�r:6��r��Ķ6�!G��8�<b����A��>�ޕ�б;����E���x�;����K����x�;����E���x�;����E���x�;����M�ثQ��W�l����G�;zŐD���{<r��g�Pa,Z�+�7���S�[��6�~��)��x�'0� ������0�
sx)����^�+��x�;�������ai�l��m���R�x�;�s���{<r��K��ob��{<r������~3N�sd�np�� %�0����e��˗�����3��ż6̻���ٹcB�g4M�8�o��_��f���kI�@�wZ�D@DDD@Xx�k���T�{���a���m~���V���
+���hj�]=3(ˋ��q
�� �_� ��u��?�Y�?t�݃�T��hO�8��`� p�m$�6��F��I���L&h���@�i3�� \oXu���Q�1�>��Y'}�(���:6�c�7|W^�Ͻz�Y�?t�݃�U/�_H}�m#��h���jk��G,͇VX���㹽K�0���ΖA3^����annq�6'�o�g��tv�����y�Bg^�DE��DDDA˴����)��xn]=U�Y,
��$��q��71�YE�W�~�.o�X�E�<Z������t��DE���X�DCE��
��q�_#�r���k��J��瞝0a�^!��1�T��G��� 4�QO��7�i6
QArA���=��9��n��ȧQ ���3����=�**�UQ`��Qm�xi��F�-�sZ�T� """ ""���J����`�5uR[Y<���#�,.�4�`,�rA���=��N�.A�7��g���' ���3���� ���Mê[U���4uM6x)��@����U[���������`�^IdpkZ:ˍ�Jz�z�Y=4��	�75ì8\�x�S��)�:�^iZ.F�Fɳv��Z�8&|3Ji���sedt�6�	j]P��#5�~{I~s��:ƪİ�F5�UPӱ�ձ���� s�r���D��P鴂��4j���nH,5�����n$��pW�O���Օ/������F4�0�l�|opq?����o
�s\�更�n!h�pa;���v�����-�� ��y��d��I��keky�Y��/њ�#1'�SqHh�I���� ּ]�Xٻ�arJ
^W��5_��g�M[3,j��#g1�d�� ��p��x�U� ������j����v���b�f6��6���Él�2��~Ktv[�����p��x�U� ������j��Կwa�2�=�
�,�k��vֻ3\=a\J���sI$�+��@{�
��X=
�Ϭ¡��6+�~v�ݗ��{-"���UIP�c+�k��2����u�ߛ8sI'>��QS��>�US9Ӳ?I����M�>/C�G�QO� ������GD84�4�*��S���9߫c�+_rc��gQ]wF>�x�Œ�&34翥���$>�l����q��捯f؞�z��p}Kt���?��4�m
X�Y�gd������a�OQx�P^wzTB�wzTB���L7
�������yjgpc�O�YK�_H}$����|4J�a�.Jh��uz칦�.��v[�Np����F ��_=[�WD��3�8en{=�.ߔ�0���*S��I#Xj���M��KR$vk�Z�6u�aj����qH�G:�wl�m��a�l$��
��zS�`B��L���0��ۛ!9�(�n�����x�(��k#���,;Zz��g4���褧yc�Y,F�i������'�Ft�� ��K3�!�]͝q��s�����V
���.&��[_o�m���r�����l֋ l�A��m�S�Z��u�H\c�	�����p=`�_��4rZZ��$6F���ʘs�5wr��7�rS�4g����&R&��*�e4�R��آT�;��#/��V��M��+�XY��3֛��{/v�mYs2�[0=��n?Ul����˰akt ��u�h,�.�ɿn�F%�����=��۽g���=���� ˨hz`���-5�k�6�I��U���aT.���eknl6���詩�%�F�o- ]\.kE�l:�A�a��uַOG� �M���G5ŗ6�"�����1�Ѻa6R$�pNݹ��}�-D?�wiTU~�Ҩ���)�(x01wؽt�=Le�w�^U�ۚ7
Z�H�)p��͌�c��]״��N)P&�����Gmnò�6�B��c���B��?d�p��_C��A�.
��%�V8��SF_��ew��7.Q�<�:�0�c�9up�N��3�V��?��Sxv*�x�k�Y5q>O�ޡu���XEl���$��Pr���#�����fq�@;�O�c�XUEL���Sű
`�N�4���Yҭc�棎v�D��`ApҦ�v�A�{���5�-(Ӛ�'xES |A��/��lv�vZG�ŝ ��Û�
�QT�K0hks[e�@�զA9�ƨ��v4����߹��v�It;zuls�B��z�%�y�&H��20�?�+�h��QFzq�c[��ƀְ� z��tg?
]��{r|T�����?mX���zV�dE�sm�u���l��u����q����6�2��	۾�����,���Hqp�����UJ�uo��a ��l��Z��0���e7�N�A_P��0��5�h�`�nz�X��9¥�1����,��2�̖=i�!m�Û7�W��м�Ǵ潯�>�][�5�X���\�l�}�R
`#Խ��3������G�e
�gFd�V7����¨D��G�c��nw���|:��G5�Gg�(2���70���� 6_K2��D̥��i�k����                                                                                                                                                                                                                                                                                                                                                                                                                                         ���� JFIF  H H  �� C 		



          �� C
L�������1X����_Ϳ�<���>&�N�g�٦T�R!py���x�huc�w
D.              ���\�'�/f�rt���uo�V�F����                     	 @     	                      z|�ק����Dn^��>�>e��KZ�#U�G�6>����Y�����/�C��>����߫x���                                                                             E�陨$gͽo� vF��t���,��� u���      
�s�`ʝ                          L��   0                                                                                          n �NX��s^{t�<Y&ۉ�
�6J��2���^.2ݯ��������j}��l�:d��I[`��j��.���@  L%�       "����4�.���k�ӗ������t�l�� ��35���L�i�����ܵ� �2M)k��cs�E0����m�x�����
�m�ki�z�g���h��V��,��[sG��zb���ۚ&6�|��K{��޻ė����koXE\                         @       %	�                     q�i᭯mǳpS�ᦑ��G���^�nj��R��\��Z���W/�M't����\~?S�s�{�F�N?�������孭�|��6�����q�,|�9��Jr��������F��3_�	�%��2"�3��&;M�i�4��g�u��ϸ�j˦+X�,F���p����S<�`0�=���ۤDyC`                               7�$W`'f�   �!  ��$       2�3F*o�����l�w�G��<�zOV���X	@4��Yc�۷ͯ�װY*���HZd�  ,���       ��x/�K��q�����c�yj�{c��T�4�cs1�
��	����^�
8���Ś��'�5h�\�?���ǖ��Vw�XJ$	gku�u��g���ku�ԯi�� �G�/��B��H  �-*�   �{�gn�� W-9�1��$��������M�h��:�z.l��u��q x8�^<9z� R���ںݷџ?5+��?�z:�K_���� �[�@g3,�nl�������}�O���Z�O�*�DOV���%�dBU�� ��*� j�73�K���7>���+��g���+<L�LQ˪���
p�ܳ\QLsg��<�����|�~����s��G�s���S���fk��ݿ�f���jӟj�1N-c�/OX������_��^�?*~�����~]o���t�0�2�H�LjW,FL�U2|��ifT�������%w�۴ ���   &�ft��S�sO�:�8����~�>G��2�G�A�|���G��������اðG[n���<t��#�_��d��\��c�|E� ;4����N����[utDDt��    +{�c}�� ���zjc�Ă�                            ��                     �����        e���IW����>�Z�<J_W�jǜi���V��)Ҝd� 
+��cݸ^_Hԭ5��4t�.-�Q��Z[Ϥ�۷AbU���Vo(�����	   YU��   *&P  �ٯ�`���V���4����� 9r�&O^wPZqsJ��s��˓$�V��|E1rͭ]Ϭ99 v�����[���K�:֛���v
+�-�p䉎+Q��q�e��>dt�<Ӌ$�G4��gn�
߭m�8Yֽ�c�]�T"ވ��$�V�e
�>�.�:$  	�3����ף;H�z��U����ͺߤz7��F�4n�?��]g��B���֭��c���(������""5��8x��nZ�h��e@�&�����x��Zc���6��D�[�0t�?�����v�.��kzW��� �T�$  g��    $$��)xՖ�H %    "�����:��x��a`   X   Y  [�e��_�,�<��lxy� �[��E�1��~d���i�#KD�����2+�	�=g^^kt�\��� ��[�H�͢d��~��'�m:�S���9����P�֜��*�Ϧ�uk|�Y_@�Z
��`v�c� X  {*��    3G֬���
H%(    )J��ݬ1�N�`B���	  0�a` �  g��,��n��g~�q^��X�ٶ��^
���jW��>�#~G,���дb�3��!�a�>��DvU�i�ѭk�      �	  L�5oEcsۯ�ڀ�$��Eu�                                               B                      D�iT  E�$)'�A�{�w�Ʒ�]ރ<������7�}����S�g���?h��H�c�z��~�k�A�i��s=P��oZ�<>�c{נ0ޜ�d	����:��zx��J�����_���q ( y+˒�����ӓ��z�� ��?��R<�w���?�   -�U  '�Q �   �o)e��,n���   �	  ��n�?D�=f�/�x�  U0	  Q2� [��V�%��KVm�- �䯤'���      4 �'P
���F��      ������#��[�����n�g�;�W��F�`                                             	B@                     Ueg�   3��T����@�� !˒�����V��
e��<�;z�-���nc��3������z鷺Q��j;��RN�t�;2���.   �*��  �Be    2�"ލU��`s��� �LP  Җk�� �qV��
`��>�R     -!  ��dX ��-�ԧ� �     ����Н�3���斘ַX�cn;Q��?+z�� �@                  �h��Hn̀
E!= �  ��U1�    �  �+     (�Y��J��K|٘���� in[z'�����O�c$�m-���%��b�>��+        	��U`  �i      �Gp�k�}�G��ܓ�h��a���ӏ�V��o��sޱx���%w���̜�.ܞ-tq��g.)Ǘ���oWW�H� l0��n2��_��t����4�9:}6��r1N[�KLڞ��1�3����i�i�\4��� \ƦA�L�� >i�8����9+j��OL�=��98|w��j��!�+���:�ӹ����           � G27 �9�T�y�@�       
��         ԧ�-�4�
g��=�+`�{�͹�kmTm>|�r�J������?O�]t�+�?h�sW_�#�����Mr�{{���d�ʮ=De�����-l�ǟ
w�o�:��ī�)3���ߍ�-���-3iݧqlt���~��*5Ã�V�r�k��yk��KW�#�����5��5�bg��7�#��f��֋�"�1mDtX�t�䘌{׿�\Ԋe�c�-�5o����*d�|���]�s���Nt�ܷ���vdP       6\�� i��7�(�|��'Ks�/6��n!�n;&�$Lr��ǩ޾�j����'X�J��՗51�o�φ#�����b��75�Z��a���Jd��Ɋf�׮��b��)����Q3;�Ϭ�7��+L��L�ӗ����������o��c�[��S�i�����?�y������b�'5{Y�������� :�}V�k��Hm�Y��@  ɒ��w�@,8��<Qҕ�O�"�~o.:� ����c������H��>6�-���	���H��Pa����3oy>_�2x����9+��[�KM���L8��B�               ?��                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            wOFF     7�     7�                        OS/2     `   `&cmap  h   \   \�@�,gasp  �         glyf  �  0,  0,��zhead  1�   6   6���hhea  20   $   $�hmtx  2T    �loca  3p   �   ����maxp  4          T �name  4   �  ���Qpost  7�            ��   ��   ���  � 3	                               @  ���� @� @                                      @         �=�����      � � ���� �� B                ��             79               79               79    
  �� �  	     " ' D d  35##35#335#!35#!35##35##35#%!!7!"3!267>54&'.#3!2654&'>54&#".#"333M̀�� �M�44M��f��!





!�
		
	


	��
  �� �     % * / 4 9 > [  764'#54&+"#2735##35#335#!35#!35##35##35#%!!7!"3!267>54&'.#$�fPU�#�33M̀�� �M�44M��f��!





!�
		
	


	   �� �  	     " ' � � � �  35##35#335#!35#!35##35##35#%!!7!"3!!#";267>=4&'.+5!#";267>=4&'.+5!#";267>=4&'.+5!5!267>54&'.##53!#53!#53333M̀�� �M�44M��f��!





!���5!





!�
				
L34
		
!
		
	



	!
		
	
		
	



		



MM		

		



MM		

		



�M		
	� ������   
  �� �  	     " ' D ^  3##3#!3#!3#!3##3##3#%!!7!"3!267>54&'.##3!267>54&'.+!333MM33����3g�3��!





!�
				


	!
			



	��

		



�M   �� �  ) 8 G  2>54.#"35".54>32#3?35#%5#76&'.5#3 j��PP��jj��PP��j]�zFFz�]]�zFFz�]�SZ
	
Y3��WZ	

	X3�@P��jj��PP��jj��P@Fz�]]�zFFz�]]�zF�3`		aM�3`

]O�     �� �  # 8  35#35#735#2>54.#"35".54>32#
   
		
	





!��h33M�33��MM� ��3&�4�M



��

8	
��

��	


9o&'� 'p'D,,C��&'�'p'D,��,C�
	��

9

����


	8

   �� � 
    % 0 = J  3#35##3";2654&+3#%!!!!267>52#"&5463!2#"&5463fLLL�L4�fffff��fM� �
	M)\MM�j��PP��jj��PP��j]�zFFz�]]�zFFz�]      �� � ' , M Z g  %267.'.5467>;54&'.#!"3!7#'#"3!267>54&'.#32654&#"!32654&#"�	




!	
	





!M5����!





		
	


	D
		



	M�3�M 3	


		
	�


!	
	





!M	 
		
	


��3M����4%&44&%4�4&%44%&4so

	D
		



		


	3	


uN$($8 �&P��jj��PP��jj��P@Fz�]]�zFFz�]]�zF3Q	
1I

_()	Z




��N
		
	





!����LM�� � ����4�M


   
�.��.�
v���4�����_�4A��'���zz�'�     Z����    	!	! �ZL�Z�Z������^����^�    �� � 
     4&+"3%4&+"34&+"3 #"$��M#"#��M$"#������3��f��3  ���A  3  2?>7>&'.>&'.>7
M���M��9a��M
6�y��M���
!0    �� �    !!#!!���fM
},Y+X� �f�g �     /����    	33!2653>'&"#!	!#���,,�9(
��,Y+X�� ��� ���^!F"�� .- f"F!�""�o���3�M        f  	  +  !5!!5!!!7!"3!267>54&'.#�4��g��3f��f��!





!�
		
	

�	
     





!�
	MM-  --  -'�|	$%	2K9'
-  -&8K2��Z		
(^WE#�g    





!�
		
	

   )����    64'#4&#!"#273!	!3U|,,�7(��(8�,


		
!

L��g�f�!����24s�9/
		



		



�3
g<MZ3]��qZ�]]��pM
  �� �  	     " ' D [  35##35#335#!35#!35##35##35#%!!7!"3!267>54&'.#'&"#";?64'333M̀�� �M�44M��f��!





!�
		
	


	�ސgOV
		3T				��"!

	6F.

%8*�<V.,4=P_C-�x/M^
-);D		  ���  )  32>54.#"34>32#".5Q��jj��PP��jj��Q@Gy�]]�zFFz�]]�yG�j��PP��jj��PP��j]�zFFz�]]�zFFz�]        ~ 2  %!54654654>7>7>7.'.'.54>32 � -GU)3U
	6F.

%8*�     &���� 7  "&/&4?>328103261810654&/&4?>32#�,$$T--			$$T-,�x-7p$f$T			#f$Up--�x    ���   32>54.#"Q��jj��PP��jj��Q�j��PP��jj��PP��j    ����   - C | �  4&#"326534&#"3265"3!5!2654&#!#"&'.'0&5461>32'#".'#"&'.54632>324>32#"&'.'0&5461>32d	
�		�_3HH3T|3HH3�15.',(�

�15-'-'.K8M
m
��

H
!'C[34ZD&&DZ43[C'�NooNOooO$I

	
        z��_<�      �2��    �2�������             ���   ����                G                                                       	                 �                                           Z                      /           )  " ��                2  &          &         �             
   �0(�l�&z�"v�$���j�d��		v

�n�t�
>r��b�L�Z�    G �                         &                �       �              �       �      	  ]      
 4        !        �      
 4N  	  ( 5  	  0 �  	 
  �� �  	     " ' D d  35##35#335#!35#!35##35##35#%!!7!"3!267>54&'.#3!2654&'>54&#".#"333M̀�� �M�44M��f��!





!�
		
	


	��
  �� �     % * / 4 9 > [  764'#54&+"#2735##35#335#!35#!35##35##35#%!!7!"3!267>54&'.#$�fPU�#�33M̀�� �M�44M��f��!





!�
		
	


	   �� �  	     " ' � � � �  35##35#335#!35#!35##35##35#%!!7!"3!!#";267>=4&'.+5!#";267>=4&'.+5!#";267>=4&'.+5!5!267>54&'.##53!#53!#53333M̀�� �M�44M��f��!





!���5!





!�
				
L34
		
!
		
	



	!
		
	
		
	



		



MM		

		



MM		

		



�M		
	� ������   
  �� �  	     " ' D ^  3##3#!3#!3#!3##3##3#%!!7!"3!267>54&'.##3!267>54&'.+!333MM33����3g�3��!





!�
				


	!
			



	��

		



�M   �� �  ) 8 G  2>54.#"35".54>32#3?35#%5#76&'.5#3 j��PP��jj��PP��j]�zFFz�]]�zFFz�]�SZ
	
Y3��WZ	

	X3�@P��jj��PP��jj��P@Fz�]]�zFFz�]]�zF�3`		aM�3`

]O�     �� �  # 8  35#35#735#2>54.#"35".54>32#
   
		
	





!��h33M�33��MM� ��3&�4�M



��

8	
��

��	


9o&'� 'p'D,,C��&'�'p'D,��,C�
	��

9

����


	8

   �� � 
    % 0 = J  3#35##3";2654&+3#%!!!!267>52#"&5463!2#"&5463fLLL�L4�fffff��fM� �
	M)\MM�j��PP��jj��PP��j]�zFFz�]]�zFFz�]      �� � ' , M Z g  %267.'.5467>;54&'.#!"3!7#'#"3!267>54&'.#32654&#"!32654&#"�	




!	
	





!M5����!





		
	


	D
		



	M�3�M 3	


		
	�


!	
	





!M	 
		
	


��3M����4%&44&%4�4&%44%&4so

	D
		



		


	3	


uN$($8 �&P��jj��PP��jj��P@Fz�]]�zFFz�]]�zF3Q	
1I

_()	Z




��N
		
	





!����LM�� � ����4�M


   
�.��.�
v���4�����_�4A��'���zz�'�     Z����    	!	! �ZL�Z�Z������^����^�    �� � 
     4&+"3%4&+"34&+"3 #"$��M#"#��M$"#������3��f��3  ���A  3  2?>7>&'.>&'.>7
M���M��9a��M
6�y��M���
!0    �� �    !!#!!���fM
},Y+X� �f�g �     /����    	33!2653>'&"#!	!#���,,�9(
��,Y+X�� ��� ���^!F"�� .- f"F!�""�o���3�M        f  	  +  !5!!5!!!7!"3!267>54&'.#�4��g��3f��f��!





!�
		
	

�	
     





!�
	MM-  --  -'�|	$%	2K9'
-  -&8K2��Z		
(^WE#�g    





!�
		
	

   )����    64'#4&#!"#273!	!3U|,,�7(��(8�,


		
!

L��g�f�!����24s�9/
		



		



�3
g<MZ3]��qZ�]]��pM
  �� �  	     " ' D [  35##35#335#!35#!35##35##35#%!!7!"3!267>54&'.#'&"#";?64'333M̀�� �M�44M��f��!





!�
		
	


	�ސgOV
		3T				��"!

	6F.

%8*�<V.,4=P_C-�x/M^
-);D		  ���  )  32>54.#"34>32#".5Q��jj��PP��jj��Q@Gy�]]�zFFz�]]�yG�j��PP��jj��PP��j]�zFFz�]]�zFFz�]        ~ 2  %!54654654>7>7>7.'.'.54>32 � -GU)3U
	6F.

%8*�     &���� 7  "&/&4?>328103261810654&/&4?>32#�,$$T--			$$T-,�x-7p$f$T			#f$Up--�x    ���   32>54.#"Q��jj��PP��jj��Q�j��PP��jj��PP��j    ����   - C | �  4&#"326534&#"3265"3!5!2654&#!#"&'.'0&5461>32'#".'#"&'.54632>324>32#"&'.'0&5461>32d	
�		�_3HH3T|3HH3�15.',(�

�15-'-'.K8M
m
��

H
!'C[34ZD&&DZ43[C'�NooNOooO$I

	
        z��_<�      �2��    �2�������             ���   ����                G                                                       	                 �                                           Z                      /           )  " ��                2  &          &         �             
   �0(�l�&z�"v�$���j�d��		v

�n�t�
>r��b�L�Z�    G �                         &                �       �              �       �      	  ]      
 4        !        �      
 4N  	  ( 5  	  0 �  	 
  �� �  	     " ' D d  35##35#335#!35#!35##35##35#%!!7!"3!267>54&'.#3!2654&'>54&#".#"333M̀�� �M�44M��f��!





!�
		
	


	��
  �� �     % * / 4 9 > [  764'#54&+"#2735##35#335#!35#!35##35##35#%!!7!"3!267>54&'.#$�fPU�#�33M̀�� �M�44M��f��!





!�
		
	


	   �� �  	     " ' � � � �  35##35#335#!35#!35##35##35#%!!7!"3!!#";267>=4&'.+5!#";267>=4&'.+5!#";267>=4&'.+5!5!267>54&'.##53!#53!#53333M̀�� �M�44M��f��!





!���5!





!�
				
L34
		
!
		
	



	!
		
	
		
	



		



MM		

		



MM		

		



�M		
	� ������   
  �� �  	     " ' D ^  3##3#!3#!3#!3##3##3#%!!7!"3!267>54&'.##3!267>54&'.+!333MM33����3g�3��!





!�
				


	!
			



	��

		



�M   �� �  ) 8 G  2>54.#"35".54>32#3?35#%5#76&'.5#3 j��PP��jj��PP��j]�zFFz�]]�zFFz�]�SZ
	
Y3��WZ	

	X3�@P��jj��PP��jj��P@Fz�]]�zFFz�]]�zF�3`		aM�3`

]O�     �� �  # 8  35#35#735#2>54.#"35".54>32#
   
		
	





!��h33M�33��MM� ��3&�4�M



��

8	
��

��	


9o&'� 'p'D,,C��&'�'p'D,��,C�
	��

9

����


	8

   �� � 
    % 0 = J  3#35##3";2654&+3#%!!!!267>52#"&5463!2#"&5463fLLL�L4�fffff��fM� �
	M)\MM�j��PP��jj��PP��j]�zFFz�]]�zFFz�]      �� � ' , M Z g  %267.'.5467>;54&'.#!"3!7#'#"3!267>54&'.#32654&#"!32654&#"�	




!	
	





!M5����!





		
	


	D
		



	M�3�M 3	


		
	�


!	
	





!M	 
		
	


��3M����4%&44&%4�4&%44%&4so

	D
		



		


	3	


uN$($8 �&P��jj��PP��jj��P@Fz�]]�zFFz�]]�zF3Q	
1I

_()	Z




��N
		
	





!����LM�� � ����4�M


   
�.��.�
v���4�����_�4A��'���zz�'�     Z����    	!	! �ZL�Z�Z������^����^�    �� � 
     4&+"3%4&+"34&+"3 #"$��M#"#��M$"#������3��f��3  ���A  3  2?>7>&'.>&'.>7
M���M��9a��M
6�y��M���
!0    �� �    !!#!!���fM
},Y+X� �f�g �     /����    	33!2653>'&"#!	!#���,,�9(
��,Y+X�� ��� ���^!F"�� .- f"F!�""�o���3�M        f  	  +  !5!!5!!!7!"3!267>54&'.#�4��g��3f��f��!





!�
		
	

�	
     





!�
	MM-  --  -'�|	$%	2K9'
-  -&8K2��Z		
(^WE#�g    





!�
		
	

   )����    64'#4&#!"#273!	!3U|,,�7(��(8�,


		
!

L��g�f�!����24s�9/
		



		



�3
g<MZ3]��qZ�]]��pM
  �� �  	     " ' D [  35##35#335#!35#!35##35##35#%!!7!"3!267>54&'.#'&"#";?64'333M̀�� �M�44M��f��!





!�
		
	


	�ސgOV
		3T				��"!

	6F.

%8*�<V.,4=P_C-�x/M^
-);D		  ���  )  32>54.#"34>32#".5Q��jj��PP��jj��Q@Gy�]]�zFFz�]]�yG�j��PP��jj��PP��j]�zFFz�]]�zFFz�]        ~ 2  %!54654654>7>7>7.'.'.54>32 � -GU)3U
	6F.

%8*�     &���� 7  "&/&4?>328103261810654&/&4?>32#�,$$T--			$$T-,�x-7p$f$T			#f$Up--�x    ���   32>54.#"Q��jj��PP��jj��Q�j��PP��jj��PP��j    ����   - C | �  4&#"326534&#"3265"3!5!2654&#!#"&'.'0&5461>32'#".'#"&'.54632>324>32#"&'.'0&5461>32d	
�		�_3HH3T|3HH3�15.',(�

�15-'-'.K8M
m
��

H
!'C[34ZD&&DZ43[C'�NooNOooO$I

	
        z��_<�      �2��    �2�������             ���   ����                G                                                       	                 �                                           Z                      /           )  " ��                2  &          &         �             
   �0(�l�&z�"v�$���j�d��		v

�n�t�
>r��b�L�Z�    G �                         &                �       �              �       �      	  ]      
 4        !        �      
 4N  	  ( 5  	  0 �  	 
Version: 3.5.2 Timestamp: Sat Nov  1 14:43:36 EDT 2014
*/
.select2-container {
    margin: 0;
    position: relative;
    display: block !important;
    zoom: 1;
    *display: inline;
    vertical-align: middle;
}

.select2-container,
.select2-drop,
.select2-search,
.select2-search input {
  /*
    Force border-box so that % widths fit the parent
    container without overlap because of margin/padding.
    More Info : http://www.quirksmode.org/css/box.html
  */
  -webkit-box-sizing: border-box; /* webkit */
     -moz-box-sizing: border-box; /* firefox */
          box-sizing: border-box; /* css3 */
}

.select2-container .select2-choice {
    display: block;
    padding: 0 0 0 8px;
    overflow: hidden;
    position: relative;

    border: 1px solid #ccc;
    white-space: nowrap;
    color: #444;
    text-decoration: none;

    border-radius: 3px;

    background-clip: padding-box;

    -webkit-touch-callout: none;
      -webkit-user-select: none;
         -moz-user-select: none;
          -ms-user-select: none;
              user-select: none;

    background-color: #fff;
    font-weight: 400;
}

html[dir="rtl"] .select2-container .select2-choice {
    padding: 0 8px 0 0;
}

.select2-container.select2-drop-above .select2-choice {
    border-bottom-color: #ccc;

    border-radius: 0 0 4px 4px;
}

.select2-container.select2-allowclear .select2-choice .select2-chosen {
    margin-right: 42px;
}

.select2-container .select2-choice > .select2-chosen {
    margin-right: 26px;
    display: block;
    overflow: hidden;

    white-space: nowrap;

    text-overflow: ellipsis;
    float: none;
    width: auto;
}

html[dir="rtl"] .select2-container .select2-choice > .select2-chosen {
    margin-left: 26px;
    margin-right: 0;
}

.select2-container .select2-choice abbr {
    display: none;
    width: 12px;
    height: 12px;
    position: absolute;
    right: 24px;
    top: 5px;

    font-size: 1px;
    text-decoration: none;

    border: 0;
    background: url('../images/select2.png') right top no-repeat;
    cursor: pointer;
    outline: 0;
}

.select2-container.select2-allowclear .select2-choice abbr {
    display: inline-block;
}

.select2-container .select2-choice abbr:hover {
    background-position: right -11px;
    cursor: pointer;
}

.select2-drop-mask {
    border: 0;
    margin: 0;
    padding: 0;
    position: fixed;
    left: 0;
    top: 0;
    min-height: 100%;
    min-width: 100%;
    height: auto;
    width: auto;
    opacity: 0;
    z-index: 9998;
    /* styles required for IE to work */
    background-color: #fff;
    filter: alpha(opacity=0);
}

.select2-drop {
    width: 100%;
    margin-top: -1px;
    position: absolute;
    z-index: 9999;
    top: 100%;

    background: #fff;
    color: #000;
    border: 1px solid #ccc;
    border-top: 0;

    border-radius: 0 0 3px 3px;
}

.select2-drop.select2-drop-above {
    margin-top: 1px;
    border-top: 1px solid #ccc;
    border-bottom: 0;

    border-radius: 3px 3px 0 0;

    //-webkit-box-shadow: 0 -4px 5px rgba(0, 0, 0, .15);
    //        box-shadow: 0 -4px 5px rgba(0, 0, 0, .15);
}

.select2-drop-active {
    border: 1px solid #666;
    border-top: none;
}

.select2-drop.select2-drop-above.select2-drop-active {
    border-top: 1px solid #666;
}

.select2-drop-auto-width {
    border-top: 1px solid #ccc;
    width: auto;
}

.select2-drop-auto-width .select2-search {
    padding-top: 4px;
}

.select2-container .select2-choice .select2-arrow {
    display: inline-block;
    width: 18px;
    height: 100%;
    position: absolute;
    right: 0;
    top: 0;

    border-radius: 0 3px 3px 0;

    background-clip: padding-box;
}

html[dir="rtl"] .select2-container .select2-choice .select2-arrow {
    left: 0;
    right: auto;

    border-radius: 3px 0 0 3px;
}

.select2-container .select2-choice .select2-arrow b {
    display: block;
    width: 100%;
    height: 100%;
    //background: url('../images/select2.png') no-repeat 0 1px;
    position: relative;

    &:after {
      position: absolute;
      display: block;
      content: "";
      top: 50%;
      left: 50%;
      border: 4px solid transparent;
      border-top-color: #666;
      margin-left: -7px;
      margin-top: -2px;
    }
}

html[dir="rtl"] .select2-container .select2-choice .select2-arrow b {
    //background-position: 2px 1px;
}

.select2-search {
    display: inline-block;
    width: 100%;
    margin: 0;
    padding-left: 4px;
    padding-right: 4px;

    position: relative;
    z-index: 10000;

    white-space: nowrap;

    //box-shadow: 0 1px 2px rgba(0,0,0,0.2);
    padding-bottom: 4px;
}

.select2-search input {
    width: 100%;
    height: auto !important;
    padding: 4px 20px 4px 5px !important;
    margin: 0;

    outline: 0;
    font-family: sans-serif;
    font-size: 1em;

    border: 1px solid #ccc;

    -webkit-box-shadow: none;
            box-shadow: none;

    background: #fff url('../images/select2.png') no-repeat 100% -22px;
}

html[dir="rtl"] .select2-search input {
    padding: 4px 5px 4px 20px;

    background: #fff url('../images/select2.png') no-repeat -37px -22px;
}

.select2-drop.select2-drop-above .select2-search input {
    margin-top: 4px;
}

.select2-search input.select2-active {
    background: #fff url('../images/select2-spinner.gif') no-repeat 100%;
}

.select2-container-active .select2-choice,
.select2-container-active .select2-choices {
    border: 1px solid #666;
    outline: none;
}

.select2-dropdown-open .select2-choice {
    border-bottom-color: transparent;
    -webkit-box-shadow: 0 1px 0 #fff inset;
            box-shadow: 0 1px 0 #fff inset;

    border-bottom-left-radius: 0;
    border-bottom-right-radius: 0;

    .select2-arrow {
      b {
        &:after {
          border-top-color: transparent;
          border-bottom-color: #666;
          margin-top: -6px;
        }
      }
    }
}

.select2-dropdown-open.select2-drop-above .select2-choice,
.select2-dropdown-open.select2-drop-above .select2-choices {
    border: 1px solid #666;
    border-top-color: transparent;
}

.select2-dropdown-open .select2-choice .select2-arrow {
    background: transparent;
    border-left: none;
    filter: none;
}
html[dir="rtl"] .select2-dropdown-open .select2-choice .select2-arrow {
    border-right: none;
}

.select2-dropdown-open .select2-choice .select2-arrow b {
    background-position: -18px 1px;
}

html[dir="rtl"] .select2-dropdown-open .select2-choice .select2-arrow b {
    background-position: -16px 1px;
}

.select2-hidden-accessible {
    border: 0;
    clip: rect(0 0 0 0);
    height: 1px;
    margin: -1px;
    overflow: hidden;
    padding: 0;
    position: absolute;
    width: 1px;
}

/* results */
.select2-results {
    max-height: 200px;
    padding: 4px;
    margin: 0;
    position: relative;
    overflow-x: hidden;
    overflow-y: auto;
    -webkit-tap-highlight-color: rgba(0, 0, 0, 0);
    background: #fafafa;
}

html[dir="rtl"] .select2-results {
    padding: 0 4px 0 0;
    margin: 4px 0 4px 4px;
}

.select2-results ul.select2-result-sub {
    margin: 0;
    padding-left: 0;
}

.select2-results li {
    list-style: none;
    display: list-item;
    background-image: none;
    margin: 3px 0;
}

.select2-results li.select2-result-with-children > .select2-result-label {
    font-weight: bold;
}

.select2-results .select2-result-label {
    padding: 5px 7px;
    margin: 0;
    cursor: pointer;

    min-height: 1em;

    -webkit-touch-callout: none;
      -webkit-user-select: none;
         -moz-user-select: none;
          -ms-user-select: none;
              user-select: none;
}

.select2-results-dept-1 .select2-result-label { padding-left: 20px }
.select2-results-dept-2 .select2-result-label { padding-left: 40px }
.select2-results-dept-3 .select2-result-label { padding-left: 60px }
.select2-results-dept-4 .select2-result-label { padding-left: 80px }
.select2-results-dept-5 .select2-result-label { padding-left: 100px }
.select2-results-dept-6 .select2-result-label { padding-left: 110px }
.select2-results-dept-7 .select2-result-label { padding-left: 120px }

.select2-results .select2-highlighted {
    background: #f1f1f1;
    color: #000;
    border-radius: 3px;
}

.select2-results li em {
    background: #feffde;
    font-style: normal;
}

.select2-results .select2-highlighted em {
    background: transparent;
}

.select2-results .select2-highlighted ul {
    background: #fff;
    color: #000;
}

.select2-results .select2-no-results,
.select2-results .select2-searching,
.select2-results .select2-ajax-error,
.select2-results .select2-selection-limit {
    background: #f4f4f4;
    display: list-item;
    padding-left: 5px;
}

/*
disabled look for disabled choices in the results dropdown
*/
.select2-results .select2-disabled.select2-highlighted {
    color: #666;
    background: #f4f4f4;
    display: list-item;
    cursor: default;
}
.select2-results .select2-disabled {
  background: #f4f4f4;
  display: list-item;
  cursor: default;
}

.select2-results .select2-selected {
    display: none;
}

.select2-more-results.select2-active {
    background: #f4f4f4 url('../images/select2-spinner.gif') no-repeat 100%;
}

.select2-results .select2-ajax-error {
    background: rgba(255, 50, 50, .2);
}

.select2-more-results {
    background: #f4f4f4;
    display: list-item;
}

/* disabled styles */

.select2-container.select2-container-disabled .select2-choice {
    background-color: #f4f4f4;
    background-image: none;
    border: 1px solid #ddd;
    cursor: default;
}

.select2-container.select2-container-disabled .select2-choice .select2-arrow {
    background-color: #f4f4f4;
    background-image: none;
    border-left: 0;
}

.select2-container.select2-container-disabled .select2-choice abbr {
    display: none;
}


/* multiselect */

.select2-container-multi .select2-choices {
    height: auto !important;
    height: 1%;
    margin: 0;
    padding: 0 5px 0 0;
    position: relative;

    border: 1px solid #ccc;
    cursor: text;
    overflow: hidden;

    background-color: #fff;
}

html[dir="rtl"] .select2-container-multi .select2-choices {
    padding: 0 0 0 5px;
}

.select2-locked {
  padding: 3px 5px 3px 5px !important;
}

.select2-container-multi .select2-choices {
    min-height: 26px;
}

.select2-container-multi.select2-container-active .select2-choices {
    border: 1px solid #666;
    outline: none;

    //-webkit-box-shadow: 0 0 5px rgba(0, 0, 0, .3);
    //        box-shadow: 0 0 5px rgba(0, 0, 0, .3);
}
.select2-container-multi .select2-choices li {
    float: left;
    list-style: none;
}
html[dir="rtl"] .select2-container-multi .select2-choices li
{
    float: right;
}
.select2-container-multi .select2-choices .select2-search-field {
    margin: 0;
    padding: 0;
    white-space: nowrap;
}

.select2-container-multi .select2-choices .select2-search-field input {
    padding: 5px;
    margin: 1px 0;
    font-family: sans-serif;
    outline: 0;
    border: 0;
    -webkit-box-shadow: none;
            box-shadow: none;
    background: transparent !important;
}

.select2-container-multi .select2-choices .select2-search-field input.select2-active {
    background: #fff url('../images/select2-spinner.gif') no-repeat 100% !important;
}

.select2-default {
    color: #999 !important;
}

.select2-container-multi .select2-choices .select2-search-choice {
    padding: 5px 8px 5px 24px;
    margin: 3px 0 3px 5px;
    position: relative;

    line-height: 15px;
    color: #333;
    cursor: default;
    border-radius: 2px;

    background-clip: padding-box;

    -webkit-touch-callout: none;
      -webkit-user-select: none;
         -moz-user-select: none;
          -ms-user-select: none;
              user-select: none;

    background-color: #e4e4e4;
}
html[dir="rtl"] .select2-container-multi .select2-choices .select2-search-choice
{
    margin: 3px 5px 3px 0;
    padding: 5px 24px 5px 8px;
}
.select2-container-multi .select2-choices .select2-search-choice .select2-chosen {
    cursor: default;
}
.select2-container-multi .select2-choices .select2-search-choice-focus {
    background: #d4d4d4;
}

.select2-search-choice-close {
    display: block;
    width: 12px;
    height: 13px;
    position: absolute;
    right: 7px;
    top: 6px;

    font-size: 1px;
    outline: none;
    background: url('../images/select2.png') right top no-repeat;
}
html[dir="rtl"] .select2-search-choice-close {
    right: auto;
    left: 7px;
}

.select2-container-multi .select2-search-choice-close {
    left: 7px;
}

html[dir="rtl"] .select2-container-multi .select2-search-choice-close {
    left: auto;
    right: 7px;
}

.select2-container-multi .select2-choices .select2-search-choice .select2-search-choice-close:hover {
  background-position: right -11px;
}
.select2-container-multi .select2-choices .select2-search-choice-focus .select2-search-choice-close {
    background-position: right -11px;
}

/* disabled styles */
.select2-container-multi.select2-container-disabled .select2-choices {
    background-color: #f4f4f4;
    background-image: none;
    border: 1px solid #ddd;
    cursor: default;
}

.select2-container-multi.select2-container-disabled .select2-choices .select2-search-choice {
    padding: 3px 5px 3px 5px;
    border: 1px solid #ddd;
    background-image: none;
    background-color: #f4f4f4;
}

.select2-container-multi.select2-container-disabled .select2-choices .select2-search-choice .select2-search-choice-close {    display: none;
    background: none;
}
/* end multiselect */


.select2-result-selectable .select2-match,
.select2-result-unselectable .select2-match {
    text-decoration: underline;
}

.select2-offscreen, .select2-offscreen:focus {
    clip: rect(0 0 0 0) !important;
    width: 1px !important;
    height: 1px !important;
    border: 0 !important;
    margin: 0 !important;
    padding: 0 !important;
    overflow: hidden !important;
    position: absolute !important;
    outline: 0 !important;
    left: 0px !important;
    top: 0px !important;
}

.select2-display-none {
    display: none;
}

.select2-measure-scrollbar {
    position: absolute;
    top: -10000px;
    left: -10000px;
    width: 100px;
    height: 100px;
    overflow: scroll;
}

/* Retina-ize icons */

@media only screen and (-webkit-min-device-pixel-ratio: 1.5), only screen and (min-resolution: 2dppx)  {
    .select2-search input {
        background-image: url('../images/select2x2.png') !important;
        background-repeat: no-repeat !important;
        background-size: 60px 40px !important;
    }

    .select2-search input {
        background-position: 100% -21px !important;
    }
}                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            /* WooCommerce styles */
.woocommerce-checkout .form-row {
	.chosen-container {
		width: 100% !important;
	}
	.chosen-container-single .chosen-single {
		height: 28px;
		line-height: 29px;
	}
	.chosen-container-single .chosen-single div b {
		background: url('../images/chosen-sprite.png') no-repeat 0 3px !important;
	}
	.chosen-container-active .chosen-single-with-drop div b {
		background-position: -18px 4px !important;
	}
	.chosen-container-single .chosen-search input {
		line-height: 13px;
		width: 100% !important;
		-webkit-box-sizing: border-box; /* Safari/Chrome, other WebKit */
		-moz-box-sizing: border-box;    /* Firefox, other Gecko */
		box-sizing: border-box;         /* Opera/IE 8+ */
	}
	.chosen-container .chosen-drop {
		width: 100% !important;
		-webkit-box-sizing: border-box; /* Safari/Chrome, other WebKit */
		-moz-box-sizing: border-box;    /* Firefox, other Gecko */
		box-sizing: border-box;         /* Opera/IE 8+ */
	}
}

@media only screen and (-webkit-min-device-pixel-ratio: 2), only screen and (min-resolution: 144dpi) {
	.woocommerce-checkout .form-row {
		.chosen-container-single .chosen-single div b {
			background-image: url('../images/chosen-sprite@2x.png') !important;
			background-position: 0 5px !important;
			background-repeat: no-repeat !important;
			background-size: 52px 37px !important;
		}
		.chosen-container-active .chosen-single-with-drop div b {
			background-position: -18px 5px !important;
		}
	}
}

/* @group Base */
.chosen-container {
  position: relative;
  display: inline-block;
  vertical-align: middle;
  font-size: 13px;
  zoom: 1;
  *display: inline;
  -webkit-user-select: none;
  -moz-user-select: none;
  user-select: none;
}
.chosen-container .chosen-drop {
  position: absolute;
  top: 100%;
  left: -9999px;
  z-index: 1010;
  -webkit-box-sizing: border-box;
  -moz-box-sizing: border-box;
  box-sizing: border-box;
  width: 100%;
  border: 1px solid #aaa;
  border-top: 0;
  background: #fff;
  box-shadow: 0 4px 5px rgba(0, 0, 0, 0.15);
}
.chosen-container.chosen-with-drop .chosen-drop {
  left: 0;
}
.chosen-container a {
  cursor: pointer;
}

/* @end */
/* @group Single Chosen */
.chosen-container-single .chosen-single {
  position: relative;
  display: block;
  overflow: hidden;
  padding: 0 0 0 8px;
  height: 26px;
  border: 1px solid #aaa;
  border-radius: 5px;
  background-color: #fff;
  background: -webkit-gradient(linear, 50% 0%, 50% 100%, color-stop(20%, #ffffff), color-stop(50%, #f6f6f6), color-stop(52%, #eeeeee), color-stop(100%, #f4f4f4));
  background: -webkit-linear-gradient(top, #ffffff 20%, #f6f6f6 50%, #eeeeee 52%, #f4f4f4 100%);
  background: -moz-linear-gradient(top, #ffffff 20%, #f6f6f6 50%, #eeeeee 52%, #f4f4f4 100%);
  background: -o-linear-gradient(top, #ffffff 20%, #f6f6f6 50%, #eeeeee 52%, #f4f4f4 100%);
  background: linear-gradient(top, #ffffff 20%, #f6f6f6 50%, #eeeeee 52%, #f4f4f4 100%);
  background-clip: padding-box;
  box-shadow: 0 0 3px white inset, 0 1px 1px rgba(0, 0, 0, 0.1);
  color: #444;
  text-decoration: none;
  white-space: nowrap;
  line-height: 26px;
}
.chosen-container-single .chosen-default {
  color: #999;
}
.chosen-container-single .chosen-single span {
  display: block;
  overflow: hidden;
  margin-right: 26px;
  text-overflow: ellipsis;
  white-space: nowrap;
}
.chosen-container-single .chosen-single-with-deselect span {
  margin-right: 38px;
}
.chosen-container-single .chosen-single abbr {
  position: absolute;
  top: 6px;
  right: 26px;
  display: block;
  width: 12px;
  height: 12px;
  background: url('../images/chosen-sprite.png') -42px 1px no-repeat;
  font-size: 1px;
}
.chosen-container-single .chosen-single abbr:hover {
  background-position: -42px -10px;
}
.chosen-container-single.chosen-disabled .chosen-single abbr:hover {
  background-position: -42px -10px;
}
.chosen-container-single .chosen-single div {
  position: absolute;
  top: 0;
  right: 0;
  display: block;
  width: 18px;
  height: 100%;
}
.chosen-container-single .chosen-single div b {
  display: block;
  width: 100%;
  height: 100%;
  backg