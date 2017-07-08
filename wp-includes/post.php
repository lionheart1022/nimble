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
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  ÿØÿà JFIF  H H  ÿÛ C 		



          ÿÛ C                                                 ÿÀ ½ô" ÿÄ             	ÿÄ X   !1"AQq23RTVa‘”¢ÑÒ#U“¡Br±Á4Sb’Óğ$C‚•²³%56Dtuƒ7cáñs£ÂÿÄ             ÿÄ          !a1AÿÚ   ? êÕµµ•w—9Çğ¨zO'ñ-Æ8«õV¿Eíû½÷è±(ä:È$”^&H×<u´•Óøí'ã:æq{_Y}ˆ9lQK4Š&—ÈíhÚJË­ÁqJ(Äµ4å‘ŸÚ¸pür“e%£õÔ1éÓ>ÑÃ6°@NÀÜÎ»{6l[>‘ÕÒC„T6gÊÂØ™Ò\w[°íA Ñaõ•²jéb2¸m6Ü;I°	[‡ÖQI«ªˆÄã´_qì"à­£Aê©[ôÅÁµùÀ;ÜÛ³°¦œURº)ƒƒªó7µ¶#oiA§""" """ """ """ """ """ """ """ """ """ """ """ """ """ ˜ÃôŸ¢§Ô5ÙÚ7>Û®ÄPè‚_’˜ÿ ’ûñüÉÉLÉ}øşeÑ;ä¦?ä¾ü2rSò_~?™tDAÎù)ù/¿Ìœ”Çü—ßæ]s¾JcşKïÇó'%1ÿ %÷ãù—DDï’˜ÿ ’ûñüÉÉLÉ}øşeÑ;ä¦?ä¾ü2rSò_~?™tDAÎù)ù/¿Ìœ”Çü—ßæ]s¾JcşKïÇó'%1ÿ %÷ãù—DDï’˜ÿ ’ûñüÉÉLÉ}øşeÑ;ä¦?ä¾ü2rSò_~?™tDAÎù)ù/¿Ìœ”Çü—ßæ]s¾JcşKïÇó'%1ÿ %÷ãù—DDï’˜ÿ ’ûñüÉÉLÉ}øşeÑ;ä¦?ä¾ü2rSò_~?™tDAÎù)ù/¿Ìœ”Çü—ßæ]s¾JcşKïÇó'%1ÿ %÷ãù—DDï’˜ÿ ’ûñüÉÉLÉ}øşeÑ;ä¦?ä¾ü2rSò_~?™tDAÎù)ù/¿Ìœ”Çü—ßæ]s¾JcşKïÇó'%1ÿ %÷ãù—DDï’˜ÿ ’ûñüÉÉLÉ}øşeÑ;ä¦?ä¾ü2rSò_~?™tDAÎù)ù/¿Ìœ”Çü—ßæ]s¾JcşKïÇó'%1ÿ %÷ãù—DDï’˜ÿ ’ûñüÉÉLÉ}øşeÑ;ä¦?ä¾ü2rSò_~?™tDAÎù)ù/¿Ìœ”Çü—ßæ]s¾JcşKïÇó'%1ÿ %÷ãù—DDï’˜ÿ ’ûñüÉÉLÉ}øşeÑ;ä¦?ä¾ü2rSò_~?™tDAÎù)ù/¿Ìœ”Çü—ßæ]s¾JcşKïÇó"èˆ€ˆˆˆ€ˆˆˆ€ˆˆ5y8KĞÈ§ªŠzıC)ø¤ª–)YNd†A¬rİ[İpiÙëSÇÂÆ¶õAšò3˜HÌ¶ì¸ÛµsÜS§b†#VìZ8¸é‘àGFÖ™ù™4|q­‘±TjLviÈ×Û{ŠAÀt¨êªë£¨‚Š§Œ6›Š5ºàg}C…QÎíkƒä³G4Æ÷Aºâ:[£˜s©ÙWˆB×ÕTŠ×g& ŒÙ[åÊÑw°ë5¸¶çemdÙ¶2ùr—oÜ²ë˜3€DúgbyŸSš|ÏÕº¢ë3Jàéj³fhmòîYx×~ *ià­Š††¢ÎÅHÍk$mÈ$oú¹o<Åm®ÛtìZG€Í‰E†C_µóBúˆ cÃ‹¢Ùám›l¤V•ğmI‚ékñêJˆšÉMeè…;ZÊ³€ípË‘Ğ¹åº "\"" """ """ """ """ "+SëòM‹¯´¤Qbæ¯Õ·šÜí<îŒÀæUs×VÓş{Pd¢³	ª.ûPĞÛtu«È-Ï!2à.}+Mâ·õY öâ ±qŠQÜß›bÀì¶=éwÖ½ú/Ò‚WMâ·õN=7ŠßÕG±Õ În®±Ñ´ÈÍ›aœo#}úWÀ~)p5QÛ¤æA'Ç¦ñ[ú§›Åoê£¯ˆd&ÌÍ˜?³¶ãb©}0Ù˜ß?PÛ³§©‡›Åoêœzo¿ªÏ‰ßÁ2ÖØ/ÓëW!uf²Òµº»oïÕ¼ ÎãÓx­ıUxôŞ+Uˆ%Q3¨ƒ‰¼¯ç›¤4M.¾±ã` ³bÉDÎm¤x'yËéô1¹Ù³8-¿©d¢n#í9Îê'GOà©Äcö²_ÆÍµe"cBÒÜ¥î;I¹ÚvõÁSˆ6ŞKõæYHƒJ½ÅÚÇ‚wXîìY(ˆˆ€ˆˆˆ€ˆˆˆ€ˆˆˆ€ˆˆˆ€ˆˆˆ‚Üñ™#-ö,^%/XYÈƒ‰KÖ‰KÖr ÁâRõ„âRõ…œˆ0x”½a8”½ag"%/XUâRõ…šˆˆ€ˆˆˆ€ˆˆˆ€ˆˆˆ€ˆˆˆ€ˆˆˆ€‹Sà¬ª²:šs4Ú˜å4şÈuyCdŒ™{çŸÇcÑLOI«_ˆ7¡m›WLX×¼~Ñiq%í,ëõ ØQ×Êrï¶Å	ÆÚŞpkú9ÙoÓ·ağıPI¢Æ£’µùøÌa–ØÛ~7ëØ²Mí³z.AQÂôÇ””õ%Ò?YWTè®ëX†Böi¿6æıcr¸ìG…—ËÅÆH×0C#æiË®âç°9ÒIâä 4ì7¸67ÄZd­á.¢<& (ç†åÅ)‘ÑË+df¦›9çÆ	 {ô,ñîİ?î- ¬k¤‘Ù„=øÙ5–sˆ½Ú;Ş³¹ADDD@DDD@DDD@DDD@DDD@DDE«i,:k&(îâÌa£î|Ú—š+¶êõ‚^yi¶]Ç~ÂƒiE¢T¥,˜ƒbhu^z	©¬Úp6ôfÕ—\fËšÛmyİn•·İ Õëİ›Áø,ƒv®Í¶¶ù:rZûn‚ykeÇw"ıìŞ«õ)ß(Ì,î‚¨ˆ€‹CÒÓGMğÜc	Çê0ÜŸPq6)ÜÖÔ9’İüÂ×°Uµ³~«|@DDD@DDD@DDD@DDD@DDD@DDQ¯Ò]cËŠÑµí6sLñè<åNThÏŞÔ^ÑÌ‚Mg*4gïj/h‹æNThÏŞÔ^ÑÌ‚Mg*4gïj/h‹æNThÏŞÔ^ÑÌ‚Mg*4gïj/h‹æNThÏŞÔ^ÑÌ‚Mg*4gïj/h‹æNThÏŞÔ^ÑÌ‚Mg*4gïj/h‹æNThÏŞÔ^ÑÌ‚Mg*4gïj/h‹æNThÏŞÔ^ÑÌ‚Mg*4gïj/h‹æNThÏŞÔ^ÑÌ‚Mg*4gïj/h‹æNThÏŞÔ^ÑÌ‚Mg*4gïj/h‹æNThÏŞÔ^ÑÌ‚Mg*4gïj/h‹æNThÏŞÔ^ÑÌ‚Mg*4gïj/h‹æNThÏŞÔ^ÑÌ‚Mg*4gïj/h‹æNThÏŞÔ^ÑÌ‚Mg*4gïj/h‹æNThÏŞÔ^ÑÌ‚Mg*4gïj/h‹æNThÏŞÔ^ÑÌ‚Mg*4gïj/h‹æNThÏŞÔ^ÑÌ‚Mg*4gïj/h‹æNThÏŞÔ^ÑÌ‚Mg*4gïj/h‹æNThÏŞÔ^ÑÌ‚Mg*4gïj/h‹æNThÏŞÔ^ÑÌ‚Mg*4gïj/h‹æNThÏŞÔ^ÑÌ‚Mg*4gïj/h‹æUn“hÛˆkqZ2ã°QÌ‚I~biŸş0Ç÷
¯úÎPëÙ·ĞÇF±V³~‘Ö±õ“ÉPæb°2¼¼Ùu‹õ!Ñ9«&/Š ¢õ÷Ô‡Eüæ­ü˜¾)õ!Ñ9«&/Š ¢õ÷Ô‡Eüæ­ü˜¾)õ!Ñ9«&/Š ­ƒDôsÆEWÄÙ‡êA¹ŒC›!viN¶Hy‘å †İ×p°Ş½=õ!Ñ9«&/Š}Ht_ÎjßÉ‹âƒÏMà××Ã›K0“I+‡Ú²S±™Üïa¶[\‹ìXtœ¶¤Ô4cØ\f
™)Æ²pÑ#c·Ú°…„s‡Xôì^‘úè¿œÕ¿“Å>¤:/ç5oäÅñAç(¸=§¨²:m!ÃrST:Ÿ<¢sZµ­¹p±¸ÛĞzv/Š-£«š¶&é6šHC¦‘ìˆ˜¤‹›Îis¬ßi^úè¿œÕ¿“Å>¤:/ç5oäÅñAæó 4ÌÄ©i]¤8dĞÔRÉW%T´²±¼ÆI¬1óÜë£hße˜îhØéâ~”áæ a´ãVş{Z[Ÿ¡Í."Û‚ô'Ô‡Eüæ­ü˜¾)õ!Ñ9«&/Š:b|á´8uMhÒJ³L×^
w÷Êö†°9À¹¿g´ïÛºÛV˜½}õ!Ñ9«&/Š}Ht_ÎjßÉ‹âƒÏ´œ`õ4´5<©Ãé£8İP&{5€É’ú¸Ú÷:Í2fM­=
Åƒhü˜>)W¤§–V1òÒÅ#Ú6¬Ø6bâm·)h=;ôOÔ‡Eüæ­ü˜¾)õ!Ñ9«&/Š7Pè-F)QM>PSQÓ6	Y;<sm¼mk›m­¾ò¾8;¤˜ÕMM¤4-Ãá¨––¦¥Ú£3¡²9ÍhÎ2Ùûó/E}Ht_ÎjßÉ‹âŸRóš»òbø ó¤|á±âtÔUÚI‡ÇÆ šc$¶3ÈÙ#îÖ!Û¿`_ZA3%f’aÓ¾J†S±‘¿p3:ÈüÙKXÂĞo”Ü¯G}Ht_ÎjßÉ‹âŸRóš·òbø óİ7x|¦v;Jğ–Km¯/73Ş—lÛÍnÅ­éG…ITøŒX“ŞÂg’ŸÁ5àÛ+v¸[¦Áz£êC¢şsVşL_úè¿œÕ¿“Å•´oÂñécÄëÆK<Ó™¬\è™vÆÑqw<ì
j·ƒúj|2¦¾=%Âj<r?‹²Wkc~¯+—i'w¢Çq^úè¿œÕ¿“Å>¤:/ç5oäÅñAÀ àªŠrÈaÒœ2J³µĞµÿ ³—8±¾Ó”:âÀ‹ujna†6‰t›
mL™5Qk;XÜÌ;ò¸gYzêC¢şsVşL_úè¿œÕ¿“Å]Á®§¯¢­Ç°ê'PIrŞæ—k"İ­µöfµºÁêV!ĞjNTâõ8ı'ŒKÅğöH×Í#¢³vfk´Øìè^‘úèÇœÕß“Å>¤:/ç5oäÅñAç¸83 }C —Jğ–8Fçƒ­»vfg îÓk,*İ¦§&‘a•ã0ÒÈ#”Ùºòà$»€»Y—Ÿm×^‘úè¿œÕ¿“Å>¤:/ç5oäÅñAçcÁ½#Y$Ò¬2!˜sœûæ‘4ó¯ÙÙĞA_tº£³QaUÒ8c–¾“ËLD9Øó#˜øöÌĞÊ-×ôYzêC¢şsVşL_úè¿œÕ¿“Å™´ÇE°œÀ(1¨±kør†¶Ù^2½æÒ^ímà‹Z^¾úè¿œÕ¿“Å>¤:/ç5oäÅñAä^¾úè¿œÕ¿“Å>¤:/ç5oäÅñAä^¾úè¿œÕ¿“Å>¤:/ç5oäÅñAä€ÿ ß¸wşªúzÃêC¢şsVşL_z‹èU£4µU7I+K ‘²©‹ia¿òAèôD@DDD@DDD@DDD@DDD@DDD@DDD@DDD@DDD@DDD@DDD@DDD@DDD@DDD@DDD@DDD@DDD@DDD@DDD@DDD@DDD@DDDAOTÅ&&÷â3Mİ DaÛ ï­“°8z•^35¸´â2I³mq=÷â¦-éKzUìÏH‡
¬˜ç¯’s™Î#)f@9¤l¿jE±ZzrÊlfV:Ä5¶%€Û™`\w~½+d·¥-éQ­,PSÏOG3Ìj%`çJzvúnvnÚVB¥½)oJ
¢¥½)oJ
¢¥½)oJ
¢¥½)oJ
¢¥½)oJ
¢¥½)oJ
¢¥½)oJ
¢¥½)oJ
¢¥½)oJ
¢¥½)oJ
¢¥½)oJ
¢¥½)oJ
¢¥½)oJ
¢¥½)oJ
¢¥½)oJ
¢¥½)oJ
¢¥½)oJ
¢¥½)oJ
¢¥½)oJ
¢"" """ """ """ """ ""*fË~vû* """ """ """ """ """ """ """ ""+ÕÔt’ÖVLÚzXi¤6kZ:Ê±Aa…+*©*ã–	œ×^Ûì®¸6#+¶î(3‘X£®¢­ƒ_G<u0fs5±8=¹˜â×‹}1©~K]>&î-^Ñ‹XÔÆ÷SãLj5zŒ®Ö±½èeÛš.ƒÓZ,gH0|Fá8‹+AtÒDÜ”`:š=kN÷µûByÖ;®D8nki¥„Ö‚§#u»­ßÛ6åuÔÔÎf­Ñ0ÇÇ´[!ŞÛunA¢G¤x¬ºIÜc‘CI©,ÅupçÑ6™â OØæ‹^ìönÑm€İmz/‰TâxuHnºf]Î`!³ˆ4Ù²˜z
ú“G0Y&ï¤ˆÇOEM‘ºÙ^ÉövË|Ñ$ Ã`‚" """ """ """ """ """ """ ""o¥Æ0É°ê¢öÅ.Wk"9^ÇÆñ$oi7kÚ¸²Ö+¸+Áñ*¸jñJúÚéãy™Ñş{C€Œ§5²6ÖØ¶¬Vš¾¦†Hh+8…S­’«VÙ²ØÜó°ÜlZÿ '4ïÎóÿ ¦ø ›ÀğJ<ôtwîz†°†¦¢WJæ·#[Íy·¡H-S“šwçyÿ ‡Ó|S“šwçyÿ ‡Ó|PmhµNNißçşMñNNißçşMñAµ¢Õ99§~wŸø}7Å99§~wŸø}7ÅÖ‹TäæùŞáôßôÍÓö—iisAÍî}8¸¾Ñ{ô ÚQºİ0`–fMA;[‹5Œç¶÷ \œ \7ğé_|±¤ç%XÌÙË¡-¶]ûºÔú±Úğ‚.—¤A$Tòº¢-c%hÍ·>L¤37mïe“·W3æ¤‘‚Ä^ÖçKû$swuİMµ­kCZ2´l nUA¯¿JÍMè^,qJèË¹íÖ¿(iwÛjû¨Òê:z£O%%X!ÖÖj®Íî½÷sTê „Ã´¦
Úş()g‹9~¦g7˜æ³múÅú6)´DD@DDD@DDD@DDD@DD¸Î/ÜÚcPbtÍdy¿ŸÓr ãá.´”µĞ‚	ŒÉLá›.ñk’Î]½+kt1¹Ùˆçn¸Ùü5ú¼ïŠuÚoDÇSG%-kf©‰²²..\Fbæåqi#5Ûºÿ ÎÕ¥Ó|>zºZGÃUM=\š¨DĞåá¹ˆÍ˜ƒa¾×ı
Øuú¼~*†–Ä6än$“nÄYdñ¿DË'ú/+‹ú”â°x¿©A­éN”VàõL†Ó»X×~Éµ¶¶JYµô°ÏkkX×Û÷…Ö%fà•¯kë(¢¨{˜éZ@êºÏ 4 €Ø ATEnxÙ$G!³Òwl;Ğ\K½EË„Só¥mLŒŒ‚{ëvÛZúâ.‰Ñ> ¸¼ávåm»3‚Jálm)•†¡äFA-¾ÛßoğRHˆƒâY£ÙÍ®± ÅğÙÉl”·cƒkvÙ|c1	iLD–‡µÍ.nñqĞ´
§ˆ×Ï8t„µ­ØíÜ÷m‡VÀƒ£""" """ """ """ """ ""(×Í6cÏ;Õ5óxåš(Í|Ş9M|Ş9A&Š£e35•&^ÁÏu…×Í>)G€©zí¾¥6ºM"Œ×Íã”×Íã•Q&Š3_7Vm+œèAq¹Ú‚ò*ÅFkæñÊ	DQzù¼ršù¼r‚Q^¾o¦¾o ”E¯›Ç)¯›Ç(%EëæñÊkæñÊ	DVéÉ0°¦ÛÕf$Dò6 ûE¯›Ç)¯›Ç(%EëæñÊkæñÊ	DQzù¼ršù¼r‚Q^¾o¦¾o ”E¯ŸÇ*QàìAT"âÇqEKI»Û“yïB£¨©ÜìäÖ"÷=;ÕëkŒÑëŸ¶=tMÏ$Y†f´ô¸oa¦Šäöû›ú‰WU¡=1ax‘™œë‹ vƒø„’zXÃÌ’1‚!šRâPwunAu7Eıt{‹»æ÷­ï`é_PÏM5Ì22@İÈC­q~AAöøØşø^Ëç‹Áâ÷`–*ˆˆˆ€ˆˆˆ€ˆˆˆ€ˆˆˆ€ˆˆˆ€ˆˆ"Ÿß»´¯‡½¬i{ÎV·i'p_oïİÚV+ÿ vÔÿ ünş*1<<î¨Œÿ ¼×£ş¹ŸŞC`²º$A^Díf"_U‡ÑÎêmv´¶×`ôíü«âx¾‰SE€²z¹X®k#`¥¦h×çÍ5ÜâzËT"‘¼öpXoâ»xül«¡··í]é'i%yrñ÷–ööaåuÇZnf3e'•¼Zc²H_²ÇÑ}ã©H1ì{s0‡°´#&e¶èı»—¿µÿ 2ôÇ’¤T…€ŠR~ ~*¢ó»Ò¢»»Ò¢©'dciVëë#¤¦|ò_+:òz‚ÑqLS®vRK·q—ül8˜a4U1SMUU’Øc{€.#m‡¥\‡iŞ?«ÑzZú~-Y%„œö~ğáû@ïºÆ:Km^²]_V°îAĞ†˜aÒÕKKDrTÓÛ_\™›vknº¤Æa—y\Á˜=B
HÄg»/|Ç{‰ŞJ‘¦›§h.:öt8¾´B9XñpWÚÔğ\]Î-ì+jÙ›t´ŞŠµı…R›À3±V£À?° ‹DDó$Œ7I#ƒc`Ì÷À•ô¼ãô´Óºúa†èQ-4“3^èÉ`{Í„ãa%ÍŒ}%°z-*~E†œC§vª«K?[Ò"m²¸7µu\HğœgƒÃ¦RT73¸¶¸tÒ†ğq©;ØûºşºßzA‰Òâõ8[l2X<?°öØßŞ½ŠK²¦'…]Z…$\áµm’gˆÔ³;Æö(•,Îñ½ˆ>‘ºùÈÔK]ÅôÅkª+j;'©kc›S)Œµ¹m³­»6ş
_­£Ãpê¬F­Å”´q>yŞ.â#¥Î6'`\óëÁ'Şs{-OÈ¬‰¶Â8/Ña¯,ÙHçfÃ²ÂâÖÙşzV]‚`”1Õ2Nxàûg¾LÎ¾}eÁ#eœµ?¬O¿yÍìµ?"}bx%ûÎoe©ùëMÆí&‹ao¨3óÙ#˜øÉi ZSwlµ¶•“„à”X[dm6oµ7vs~’æq?ŠĞ>±<ıç7²Ôü‹;áÃƒLk¤Âpúùd®­~ª3OPÀ]bm™Í néS­7ÎF¦F¨¯¤D@DDD@DDD@DDD@DDD@DDDAşıİ¥ab×îmM·êİükû÷v•‰‰AŸ÷ühğ¶Km_Z§,–Û.İ€-cÒ-mM5áTú†½æH,Íüáp ŞãĞ‚nªZ8öõÇûÎ"ÔË’$gsØC‡¬(I¸5šºˆUbØ¸ŠÎ"˜£¶iv{ªBğ|	Ò*¸°ÜqÓ™ö¿tôò¶\¤e˜6;Z:vµ‰Ë…ºŸ[¼vM·€VÇ¡sb˜­mäÂ‘ÕÁÍf·4!Äí»ºTû‚òÒHîq–9Îs¨ÈÒnzxÎÁĞ¦xï_•pË[ıwá˜•)†ÒâX|Â¢†¶&OM3w:9fŸÿ 
fÀÅrO£}%m78 «vc7šœ^ùa’¡åş'ñ]nÀÅts^wzTB—wzT3œÂã¹¢çğA­i5mçl óc»ûÖşABÒN*ØE†ËúFõv¶GM3å;ÜìÇıåÔEM‹bv³5Ü*cmö±hk¿Âÿ Š	ºªŠjHõ•2¶ï`ç›\õzNÅ.'WÅ8å>YQJÌ¬fËoéô+ØÎ‡côB³8Œ‘(|NÈö8\4ƒø®;‰ãœ%³HâÑşïWk?Š:8İn‚èÚDN%»ošŞ”ûş,v*Jˆkhã«„82A˜´±ã ‚Ó´B·<‡0µµ€ÚŞ8ÍeD8½$4Î•óHÈÃ5ÒØ½ä]Ùv\ú¦üeÕÚMÄKYMrJ7ùnGmšÏÕ³áïÕ–Û¡oxdÚÈ´8Û’&Û¬|ß€9ú«m7€gb­G€aT¦ğìU¨ğì("Ñqo¤¿ãÀiñÊ_ÂCœõÔíç¾;u·i³É,q0¾G†0os‚ÖñÌRŠ³TÊwç’\ûsvõ_záØ”3Æecws…¶u®ÑÁ¦((ÒVV†½àïl[ãoâb²qN°év#~Z	*İ…ä|Dff{İ±¹ÎÖô)zjgCˆ¾ywÌìÏ?²:n8K\\íD-SÀ›À-7ì[…0æÔ³;Æö(•,Îñ½ˆ>‘?øHÿ öÊÏú^"Ñù06WÅ$´§ì‡cõ€f¤ls†Gu~…íÎğ‘ÿ í•Ÿô¼5‡a•5òjiš™–G=ícw¹ÏykZ6åvãräl5màÕÑNúy16Ï©.‚<±êõüë0Şîİ·%T¤„:SYŒ™"„Knx•ò:Fíuö± XŠ*}ÒH[.T-”æs›ÎnaÎmÛ´µó>‹é‘’áµ!Ñf<€\EÆĞàº9¯:Mî­CiÂËÚic‚XÛ#Ynpy•¹¾â¦8 ÿ êŒÿ ëGüZğÑ¼uÕ³PÇ‡TËYNáğDÇÈæ8îÉš×[…-ßı4uøJ±íÔD^W DDD@DDD@DDD@DDD@DDD@DDOïİÚV&$rĞT;ª7Ñe¿¿wiXµÿ Ğ¦ıÃüiùéª!|2±Ú¹ZXñ¸åp±Ú7.,t[„{ñ<‚*Ú:Iêa†‘¥Õ:’m¹ßó9¦û¯›­w8ã_e–Aãí-Ò]/ÆF<ú‘l4<-˜È,ÛĞ>çG"©¨e4rË¤R²õ2º/±†”mÊÇÙk“µİ_á»« ™Ó8i+ùØ÷8›?öšzv_±`p%§¡ÔhÔ4f©õõyŸ+w°í<Ñµ­üâ­›‹¿®ó}‹É¼.áU˜ÇõøU3ÖbÒSS·ûrÃñ^¯/èZ&pg>%Ã;§uñÛÂ_X]ÿ ÚÖXÚçìÂ×x¢¢;>`z;£ØnG¶—§š7îÕ‹ŞuÜ¶*? ?¤(ü üP^wzT,Ñ6h_¯•à´Û~Õ4îô¨„ÍnVÆ™í”Íw5ß¢³F1¼;Ä±¬Iœ^*ç6––‡;UC¤”–’}Àz`Z®ŸMzj* 6ÔÍ´õ5»ÿ Šª D×wÒsò¥­É z-&7İ™ZìDO¯l¦iy§ÄnÍŒô~«eŠKÚÛº•Òıˆ11˜ÄŞñZ´8&*ı/ƒ£‡_E$/Š fkLo37ïÎwu-²~|OoX_:ñœ~ñA1G¢õr4’Ø›â°æw¯rÙiéY³WÜñ…q7€gb­G€aT¦ğìU¨ğì("Ñì­.êõ Õq™MiÌK¢a-czºl£¤Ô@ÇI+Äq¶ä—zUg¬•í#±Âû¬zO [jÁÒ=‹ÃâŒT¾XNº¢6‡s².Ó½¦ûB8–7BÊPèçt$JÆ½‘=Ûà¾åf6Ó¾(ªàË=<Ş×±ÛV¯‡i-mªk*Xdì†™¤Fxå(tÍ|²¾à­â²6ÓResÁÍ¯=ƒnğŞúÃ`ÚŠÁ§Ÿ¹x°0Å¦hqgoOàº6PÙ¡kÇJå8cª+æYE„Rj#LcZßY~Rèº4ãÅ/}ˆ‰å,Îñ½Š'¡K3¼ob¤DA¥ø}V#¢xÖFÜõu”50S°Ğd’'5¢ç`¹+Ë4pÓA3j(ğöSÎ[¬ee=ìwúÄëÔZ™i›ŞPÿ D_H-^«+ÌD—Í|¤‘cp]¶àí_c‚Ÿ¤8vo´Í¸¸×ÀM­–Ä—u‹Õˆ¯±:<‡' ü5IPú—ÑQ!ó
èC‰À’×<ğ—ƒiŞ‹b8lQPQU	jd0¼†åp¾Vºçzõ'² ˆ‹ˆˆ€ˆˆˆ€ˆˆˆ€ˆˆˆ€ˆˆˆ€ˆˆˆ‚)ıû»JÅÄ?¡Mû‡ø,§÷îí+ş…7îàƒXv3zûu\Gp%b¼5#hÌƒYÓÍÁô¡´MÄ¡¬ÉG&`)ÜÖ‡´ïcï}†İTÍTCª¥£m_ÕÆÆFßSl³ªÛöcµbºÈ7°­»GY“
ˆl½ÜM½.+NnxıÕãüJ	!Gàâ£Ô…€ŠÎïJˆRîïJˆ@ZfœÌŞ?JŞ˜cs¿Şå [šĞ4Õ¹´–&g°Ô1Î…ÀÅˆ³ğWóìXAöØ¯5üÔn;>ìµ3¥²ní_eíX¸k¿íÉ~ú ëv:ßÍK£7…ªúÆ ğY($é¼;j<û
¥7€gb­G€aA¬WÈ#¡çöcwğWÖ:ö·“11héÛ¹ÄÚ˜Ù« ÎÂè¥×°‘píıWS ¯Õ÷âÍ¥}˜ç+eh`7s‡[¬§ìz›Ğj´:CGKŒ7«}TÒ—ÎÒc{o˜F@náÖ¥±7f­>5ÀYò¼aUÄÎ.éw=¤öt ˆÑ-<CÀœhè í'ñ%n:XÙ©vØ‘ú®kÁ»eu]<µóÅSVÙĞyŞl/ßúëxĞ[Æéb×¸ø ß:»;Æö(Ş©vwìAôŠL±šìF«1*Md!‚ré$l{öºÂÔ*8NÇpüCŠWa:ÖÀZÚçDÇ‚bÖZ6æ™¯&á fÚv]IE¡ÁÂeEn‹bTx<±2‡0æ–I!/š»—"'2ÊÂ8H§Äj§¦nPÙ)©f®yfYZøb{¢…ÍğšÉ#pe·Úè7$\ò«…¯õFº—sêd„ËÙc¿>ÖÕí{[ª"G7¼qh;Ô®ğ€1¼]˜t\ñì–Ijæd‘dØîmåÑïiŞƒnDDD@DDD@DDD@DDD@DDD@DDD@DDOïİÚV5s\ê9šÑw ²_ß»´¯”ŸÓİÃ+¦ÃñrŠº™Ù*)f™¬‘êsNĞ©†ğ‡¡5µÑÑãÔU5•ÓÓE3_$q°kZ6’W#á‹Àª>õlÆCá ª¥§¨ç·#'•°5¶iı¦]»} …›¡”š‚iœx–Úfã,c Ã¢”äf²na{:3µ—²åŸ&/´Û·bÕ¦ ’ªg¶:j{É<Î 5±³6g“âŒ‡jÕÂ‡–Û¤xw´3â øLÓ¸NŠclE-<.±1qgSCa¼—É+İeå!OŠ§%Ê/‘ÃÒ½›I§ÚˆVCEAĞÔÖT¼GOOìsŞ÷5­ í$®¥G,x\,•¥’İ®Øw•ùÛƒÖ»Å¨qXYğúˆª£?Ú…áãşUú;OS]<5pí†¦6M,oèWg›KŠBÀÅG©
? ?Ş•¥İŞ•€¹æš¹ƒI]ºüZ,Ş·.†´)ÑLZ·“§keğÇ@ë<^ûııh5Ø¦q¸YM“™~…ôü‹¾¥”Ü¿ü·_ÄuŸş7 ¶ù.}=ã	pv<ãûzşbÉn	ŠÈÛ6’brßÅgè¾‡bôø¼õÕìlP:\Qæ}ó\°nëAºaş«)|EFå_h$é¼;j<û
¥7€gb­G€aA¢ôÖÂŞÜ¹³¹­èÙ¶÷ÛØ¥F”Ó:|(†3Y+^ÒÁ—0ß¶ã±.«Š¾]8£e;ê(©æ|•.mÛÀË­Ìò;VÑ[Şİ\ 	¼_õQØVã¦\S2™)e{ esf®˜ºåÖ°êXõu˜–k¶‘pİ kƒíÔwµËäÚHQ/š®l‚C‘»K[ºÃ­`OˆVµ…º™5ÏÙšİèül¾xÅtŒñÓ6–÷ušY§ÿ Úm ŠJlCRş1®«¨—[—¾ÎşÃ³Óe¾htN»Şîø¸’OjÔ°ŒÀ¨+2E<Úéõˆc¦qŒ8í±,"ëwÑV9±6â×ÛéÚƒjê—gxŞÅ;Õ.Îñ½ˆ,×b}3ª«êb¤¥e³Ï;ÛbæÂîq \›(®]è?œ8g¶Süêjh!3Ñ¶XÎö<ø‹Ü\È)ÿ )Ÿü»Ğ8pÏl§ùÕ¶iÌJ†c˜Kj&L*©ƒŞŞ‡;5Î[›)NâàŞAOùLø'qpo §ü¦|GòïAüáÃ=²ŸçN]è?œ8g¶SüêC¸¸7SşS>	Ü\È)ÿ )Ÿü»Ğ8pÏl§ùÓ—zçí”ÿ :î.äÿ ”Ï‚wò
ÊgÁ.ôÎ3Û)şuVéÎ„¹Á­Ò4¹ÆÍh¬‚äÀsÖqpo §ü¦|FƒƒqCNÜuLø ÌDDD@DDD@DDD@DDD@DDD@DDOïİÚWÊÎ4-$œÇj§gŒPsşôgDñ
Äª´ŠˆT·¥²’p\É¡‘‘’r0‡.7á’Ê×49ò¼»a.¿OjıÒ½‡H´kÀŸU%,x”&JˆÃKÚ×{\n\qßCİvı$Ä;5p|ªVñ¯'¹µEì‘ÓHçÇàÜ\â[û¦û®,î¥ëŠo¢ŒÃSÇH+¤½¯1º(,ì¦ùO7qµ—Ş!ôCÑj¬B¦®,v¶–:‰_+)£ÈÃÜ]‘¹6mì.¦—qä^,´¾ºO>;Á}*_¬«Áä~#·“@:øŞà GĞïF<â¯ü¸>UĞx/ààş›§£Äê+£ÄdW	Ûr:6–ór¼ªÄ¶6¥!Gàâ¾8ƒ<b¯Å™Aº¬>Ş•¦Ğ±;Ï ÂE›Üöxå;Ï ÂK•›Üöxå;Ï ÂE›Üöxå;Ï ÂE›Üöxå;Ï ½MàØ«QàØWÔlÈÀİöG·;zÅD¢Íî{<rÏgPa,Zœ+ª7¨¥SÖ[·Ö6©~ç³Ç)Üöxå'0ÿ ‘õø¬¨0ú
sx)ãˆõµ ^õ+Üöxå;Ï ššai£lƒûmø¯–RÃxĞ;ŸsÙã”î{<rƒ¡K³¼obÆî{<r²€°©ªÊ~3NèsdÍnpôÿ %†0‰ÀÉÇeÕåË—ùö¬ÊÎ3ÅİÅ¼6Ì»ºöïÙ¹cBüg4M’8ËoöÒ_£Ğf±¥¬kIÌ@±wZúD@DDD@Xx®kéÛ³TŒ{‰¸a½…‹m~µ˜¬VŠ³â®—;	Ì.2gÇâÛ ¾ˆˆˆ€ˆˆˆ€ˆˆˆ€ˆˆˆ€ˆˆˆ€ˆˆˆ‚I4¯Ñş#Ç[#ßˆOÅ©™nÙ29ûKÜÆŒ=+>´=ô”õG8ê3sd!®#s»XÓµ¶õ©º¼2‚®¢–¢¦,Ô/t´®7æ=ì1“mİãÈÚ aà×D4ò:Z&âùcsXÛK£‰¬Ë”ì:ù‰&ş`¥Ó«”CIˆÃ<ÎˆÎØãuÜXÑw~ oÂ‰ÃøUĞšè;+uP>O¬™º»k-fyÙîëÛzÊ¥Ğ—~/ ‘®{%a¥Íö9ª-­’ÛËß`.Júƒƒİ§ªmT8dqÔ4Äá#] 7§llŒ÷ÛK[ı2htÃG+j¡¥‚º7TT¹í¥faöº±™Å–¸<İ½ŠekX?z-…UCWOLçTÓHù)e|:¼áÍkA³Y!h¸&ÛÉ[*" ""ÅôÛGpŒV<*º£U]0§t1ïSšvdñ¬ñw[pÚ§”f#£8%4“VÒ6ied1½ä¸´ò™¢¶Y	(0›§ú[›»ÖÊ÷_kóf½÷wßÔ¯Ã¦5PÇº›ŠwFÙè£7’Ñ73ù›Á­CËÁ>†>:ˆ™O,QTÃ©1²i2µßh5À8ºò)±uíÑe!C š=GˆÔ×Åİ-]+¨¥yÙI#¦–î?hçHù	sœâ{aPğ©¡5p6 Wjiß%Ì5~¬pe;8Ö [m‡b–¢Òİ¬¬ŠØÍTî‘´ñf“S|Å–¸;=‹>´6*5'Ì×çk9Ì‘wÛòBÁø/ŒƒíÂªá­§§s«)Ÿ#éæ{ÜK5™ÆPÑfY¬•Í·¥Èˆˆˆ€ˆˆ5Úİ>ÑŠ,iø-MV¯dĞöu;êsõäÆnî½‹èiş†Übôä‡‹;xq ı¢\9»ÕüGC´o’ykh›,•/l³?3ÚKÙ©Ún×Z'–ìQ?è¯DZç˜£ÍDB9ä—Âæºñmæ¹åœç÷Ö&Ä ‘©Ó‡¬Ä£®eT4ï«´ßk&ª0ˆcvøX,h8IĞÉ^áİãf`ÆK!Ê×“·™ÒE¶ßà®ĞèQŒI‘Ó—CŠÇ=L$óD4ìÉmËg[i$’\IÚPp{¡­–iY†FÇÔİ)k5YõÛ¬3ë]~Ô¸^”àX@¥¤ªcªÌF~-qŸV—6Ë‹\İjU@`£ØñÕQÂçW2#«•îsÜ×e.¸ØÎq·³Få>€ˆˆ-ÔM©„É—5­³´Úç~À°{·PM<ûEö2ı6RHƒâu±‡å-¿ì»~Í‹íX­©}<"FDf9ØÂÑ¾Ïxiwû·º¾ˆˆ€ˆˆˆ€ˆˆˆ€ˆˆˆ€ˆˆˆ€ˆˆˆ€ˆˆ
+Àå®ÄhjÛ]=3(Ë‹éâq—0·:ÄZÊUAé5fI®š(j*&ÔK$.pm,Ï ¾2×[3AµÒT³l:m«„Qcu’qIß;îâ5¢OØ<í€]Şµ™…hİE"91:Š¢'tùä&övÜ›ÎËßÖµx1Şm.CÍw?ân±ºËFwídnÛ[èR8^°Ë×5µôO©«|3RÕUÔÎ8œÒYÌ™Öïš?gxZ¹Vf74DYlDDD@DD˜–ÔVbœy˜E3x»éÅ<n!Ïÿ i¼mŞ¥‡O¡µpšCİªÇqZy)í˜óËïgvÂŞo©Nb“VCFé(ãÖNÁ–ÅÜÒàr‚	³Míu&'¤±Í3\ÆÄã¬uß®-ÜÂãUÎ¶û­v¬zâŞ¢5“á²œ^ªváí{Üm6s³>ßÙÙê[ÁjñZ˜dv#L)¥³X;6ô›Øô©-ÚãŒŸÁhDDD@Zö%¢••x„íÅª et-…°±Ç,Vı¦íéçlô­…`âÕğG	£XçH/4¿+2“{Şâ¬ºg,eFÓhµD5†¥ØµT¡Ô­¦Õ9ÆÙ›ş×~ó·g¥JA‡¾*†Jj$xlz²Ç„øÊ!˜¾“ñY\ì8kÆMP67¾k‚î;/·r–Â*+ê(Ä•Ğê'¹=Û;.ïÿ ;ÒÕ“LÔDQDD@Ef¨¸BKMÍ£µCn˜b=Îãq®¬®É˜³X¬¶Lú±›-ïm¨'ÑCAyc§-svm6õu§tc6µMîl,PL¢ŒÖMıc½i¬›úÇzĞI¢ŒÖMıc½i¬›úÇzĞI¢ŒÖMıc½i­›úÇzĞI¢"" """ """ """ """ """ ·$ğDZ$‘¬.¾Pâí´ÚıAab”X> È…cÿ £Ê]ã™ğ¹²†9¦Ï‰Ìuò9×_8¾aø¬‘>¯YöL’,¬yh-˜Ù­¿½QÒè+n’«#¥tÙxÄ–Ìı§§¬ƒ,hÎY¬VpîèVÚİw×/šL#GUKWïf¸º‘ÒÖÏ;s9mØÙ%{IÈ]Ğ³âÂ©cÂ»™Îu6¨Às¸´‹“Òn¡¢Ğ'R:'ÌÇÑêò<j®ãÈÜÿ ³ÚZ½Á],ÆĞÌÉ³YÙr/³ÒWT¡x6Y%]·[+5nÖ8noPhü:É&ys];áßF´3H_âµ,Š9Ì”â"ËI{|7æõ.”¼‹ô–sG
“í ñ_ÿ ÚÖuœî?ëY¡?tâİƒüTúÖhOİ8Ÿ÷`ÿ pšm$Ğ6ÒÁFºI¢¦ÒL&hîøÎ@Ëi3×Í \oXu˜æ‰ÔQÖ1˜>¦®Y'}ì“(²¼:6¸c«7|W^‘Ï½zëY¡?tâİƒüU/¢_H}Òm#¡ÀhğêøjkÜæG,Í‡VXçó²Èã¹½KÎ0éğñ£Î–A3^³“¨æannq°6'§o¡gğötvÇı¼¶öy¸Bg^ÓDEÅØDDDAË´Ïé¢Ú)¤•xn]=U¯Y,„Æu‘¶AlÒ4îwR…úÖhOİ8Ÿ÷`ÿ qÿ ¤›ş–ñĞ]oèİ¿ÑcXÒ¾uÆGhÉÔX™®hX_ö6Èöƒ¤“· ö˜GõÛşµš÷N'ıØ?ÅO­f„ıÓ‰ÿ vñWŸŒh¤˜|;	"¶7³[;%È˜½ÂŞ3¢9/oá·-Øş‚6C€¸Îá+],³4µÁ…±çµØâÒ.NïÅ^ï^˜Ğ~ôcL4‚‚¶
™Ù$–qÑ71¾YEÓW~.oúXÃEÁ<Z¯ş’öçœÕtÂìDE†–ªXçDCEÎÅ6ÑKˆEˆ¾qøH1ÔZí€·mˆšâ6ÊyE;vg@ãÒlƒİ ìR¨‚4RJÃ6ªñiüE"ˆ#¸´ş"qiüE"ˆ#¸´ş"qiüE"ˆˆ€ˆˆˆ€ˆˆˆ€ˆˆˆ€ˆˆˆ€ˆˆˆ€ˆˆ<õô–Òİ*Á4“‡Åêğèf£‘òÇM+£kœ%°$æËPb¼8WĞÒWRcx¤”µÚî/'p¾£c¹Şã±ƒöºbáÏ‚=+Ó|s­Á_HØhéŸ¼fGÆìÎ“0°k²ËŸEôsázd‹¤™räemCF^«÷m]±³NV]µÇã\6´NF9‰ÉÅ²Š†Ç[˜·<"qû[yºv*Sc\8ÔÅ®‡ÅİkéxİšÖÈ35Ï%ã(Ë·jÙ~®¼/—JîéRfŸÃ;QwìËÎû>w7fŞ…O«¯ú“t©5˜¸íFL­Ø]]¬»ŒêµíÓÎÛ§xN_¤X„¸­=-\©{ØëTäaÛg´/d/1èïÑ×„z)ÂñjÙè$–º
ª—q‰_#„r‡¼í‹kºJôâç0a±^!¢º1‰Tš¬G¢¬© 4ÏQO¯Ê7Ïi6
QArA¼İÃ=‘9 ŞnáÇÈ§Q ôÍÜ3Øàùê=Ñ**–UQ`”ÕQmŠxi¡F’-ÍsZØTº """ ""šíÑJú§Õ×`Ô5uR[Y<ôĞÉ#¬,.ç4“`,¬rA¼İÃ=‘N¢.Aè7›¸g±Áò' ôÍÜ3Øàùê Š ÑMÃª[U‡àô4uM6x)¢@°€æ´ªU[¨©§¦óÔÊÈ`Œ^IdpkZ:Ë€Jzšz˜Y=4¬š	ã–75Ã¬8\¥xşS„É)‚:^iZ.F®FÉ³vü¶Z“8&|3Jiñê¨àsedtÇ6¬	j]PæÈ#5~{I~s·æ:ÆªÄ°êF5õUPÓ±îÕ±ÒÈÖÿ s³rÑßÁDÒËPé´‚±ìœ4j‘¬nH,5»˜ç³¤n$¬—pW†O‡ÑáÕ•/šš¶²¶F4İ0®l­|opq?íÍÈŞo
s\Ğæ›´ín!h’pa;¦ÎÜv¡±¶­µ-Ï µ®yÊğdÊçI«Íkeky·YÚ /Ñš³#1'ÔSqHhÙIµÔ Ö¼]òXÙ»›arJ¹bb£42%ğ2¿f©ÕM{âßÎÌØÜÇn½¶¨W
^Wû5_øëgšM[3,jŠó#g1—düõ Õp¥åx³Uÿ š®¼¯öj¿ñÔÏv ÌĞbf6½½6íµÕÃ‰l2¹ì~Ktv[ƒú ‚Õp¥åx³Uÿ š®¼¯öj¿ñÔ¿waÚ2´= €z•×â±²WFè¤³@9Ãn_ªá«…/+Àıš¯ütÕp¥åx³Uÿ ¦Î)ÍMCŒOÜmp±¶îŸZY¶cÛ†'ç:ÖËcm  „Õp¥åx³Uÿ ¾™	ùÛ«ÉqÔõwË}¶û}öS¦½µs|½áŞ®¶¿5_ÕägêËã‹ô ËDDD@DDD@DDD@DDD@DDD@DDN{ó;œw•Md1õª?¿wiTAõ¬“Æ>´Ö?Æ>µbIØÍëJÒô'Gªâ¢Äñx)ê¥pfªùË/Ó.LÚ¶ú]dæ±ş1õ¦±ş1õ­B—L°ùcÇW‘8]²2V9¤uÜ(é8YĞó³f/LìVFæe;dÿ w8»3†Şè:±ş1õ¦²OúÔ.:Ç?aRĞÔÇ ØPdk$ñ­gÑ’`Û½G)/ ;JÎïJŠÖIãZ•wzTB­d1õ¦²Oú×Ê úÖIãZk$ñ­X©ª¦¤¦–ªªfSÓ@Ó$ÓÈCXÆ7{œNà™ô»éqŠG¤ÚÃé¦Àé•òÖëjvï\İP=	ëêAéıd1õ¦²OúÖÁ·:?§˜;ë(šiké¬16G>"w88[<nèu½mì­‰Æ×A•¬“Æ>´ÖIãZøT”şŠ³øöóMàØ«QàØPFë$ñ­5’xÇÖ¾QÖ²OúÓY'Œ}kåX­­¤¡¤–²²VÁMKå•æÀ ƒ'Y'Œ}i¬“Æ>µçŒSéGTÍ(|xF^S»W#Ş\Ù¥wK˜ñÍ tl]ŸF4ÓÒL[}á“cãwúXğ:BlÉ<cëMd1õ¬hë"yµÕôZÉ<cëR¬ïØ¢³;Æö øª|Œ§{£]'ìGÖz$•õ‘¿eß	.^úän?,é$nb¬É[p²i.Æ<†ÜôÖƒâ5cÿ %%ÿ —©_Xe´o-{CÖÛô~‹é¸ÖG†ææ Ü¾+èâÔ-•Ñ:L®mïpfß…}NrEÆksº÷½J­Äj$‹[9æ<¶HÏ}±¹¶zo±\v/@Ÿ[v‡e$°å.Ûøî¾”0 í¿Näâ5/}¸”­.Ù¿øÙg¬Wâ”,Ëy9¯‡ Kl7íNéĞ[ÃÍ·õ~2‘Z§©†¢=dNÌßRº€ˆˆˆ€ˆˆˆ€ˆˆˆ€ˆˆˆ€ˆˆˆ€ˆˆ"ß»´¨İ!Çpü­Æ1êè¨btÓ;¦ÃsZ:\ã`Z’~îÒ¼ïô¥Ók0í§w3e~"IÚÚvQ©…§¼<i¾“:H(Opğ‡’4Îÿ Ysû³ïí°\ÏŠ“{şÖ×_i>’¯A.±Ä…|1ÄÙ ¸Àm%k°è¯Ş…òiÑ• ï°ë[@ÑŠã]#™º2I?²‰ª£ššwÁ;rJÍàÿ ”ˆğ©¦š8YugÃÙ°ĞÖû¦HyìõÛĞ½-ÁŞÑé.'IvE;ß­ï˜ëoë¤/Èü„8îÜ’é< iè4¦L%îÿ WÅbØõğ‚æŸÅ¹‚aA&xÁRÔ^ v•­`³ç€-–‹ÀÒ‚ó»Ò¢»»Ò¢›ş˜ú]YM†àº)xá¯.¯«lkÄG$qúlë¸şÌ´,µÁí^¾úXhı#Áısã~Š¹‘ÒÔtµ³±ÙÛØâÆ¯!Á%‰nBİ†è:X)AÂ>p÷–ëŞèkĞêbÒdì¿jõ&$\áµy—€ŠzS×U¼^ª( ¿ì¶Wãøå²ôNÂ\ox|¹âYkm¢=7€gb­G€aT¦ğìU¨ğì("Ñy‡é{¥•¯Â´JdP1‚²¡ÛC$2İ­7å ¯O.-ô™Ğxñì‹¤şÚÃõ†Ã|‘¶ÏtG¯¤·Ò‹gÁ`ĞêNÌÍnĞº¯ø®'K¤•°¼÷>Zw>ª3»3láÔnW%¢Äö—¼¿²åÛx-ÃÙCG6vå­Ÿ#§¾ö´ŒÌg¨ÜúQ]‹sœ6­ªLñ´|!„¸-ÖˆZ0Œ²Ô³;Æö(•,Îñ½ˆ-VÊÈ©#Û¢ÜŞÒóX§ÃòÜÂáĞÌ¹¿ÏJÏ{š;r –"Ö¸gìooù#N!„6åĞX¿U¼ú•Î=†Hò:ÎyÍ‰¾ë7_õõ„×Óíç·fÓ´toAİl7V/	¼‡ÁäÛ›«´nUî–¾Çš9×È6‹^ãÖ¤ôı23ÖÎ¾”4ZFeÜİ¢Û9»?‚)kğÈF¯UŸ#o”2ü×íŞvv¯Šš¼3#ÔÜÉA.9F`o»fİ¤).1Oıc}aW]Û§vÛÚİëA‚Ügh9àÆï-fÀ¯Ób0TÉ«Œ;½Íwer9i-Ì¸Üwİ?Š¼€ˆˆˆ€ˆˆˆ€ˆˆˆ€ˆˆˆ€ˆˆˆ€ˆˆ"]áö¿ŠğÇ	Dı#Ó|gÍxç©p¦ôA	ÕÅo÷
÷,ÍkõŒvÖ»3\=a\JşŠÔsI$ú+‹ñ@{Ú
ö™X=œû~óOj3Å ¸Çı`¹ôYJQ×ÉOSğøXÎfßwVÕ¸é úY¢:3_¤˜ìÁQOKK,šÖ˜æÌ$•æÍµP¥ht‚ìÏãnıŞ„
…Ï¬Â¡ªÕ6+ƒ~vòİ—¹İ{-"º°ÔUIPñc+¯kŞË2“¬¥¢u©ß›8sI'>Í÷QS²>„US9Ó²?IªœÑìMø>/CŠGßQOÿ îµÜáø¶êGD84Ç4Î*©ğS ¾9ß«c™+_rcÎƒgQ]wF>ŠxœÅ’é&34ç¿¥ÃÚé$>l¡­¡¥qÑÇæ¯fØ˜zÚáp}Kt¢ş?…á4¸m=0"
X™Ygd¡­»óa½OQxÚP^wzTB—wzTBÅÄñL7
¡–¿ªŠŠ†yjgpcÚOğYKÊ_H}$¨Åôú|4Jçaø.JhàÌuzì¹¦“.ì×v[úNpçÃ‡éF ÍÀ_=[™WDµ¦3ˆ8en{=×.ß”È0İÃñ*SˆÔI#Xjø››M„ÓKR$vkÜZÒ6uİaj¶­ÏÁqHôG:wlŒmö‹aõl$
ÔïzS‡`BêÌL½´µ0êøÛ›!9§(Ûn‹Ôàxí(ªÂk#«„®,;ZzÓg4ö…äè¤§ycšY,FÎiëô­ƒƒı'—FtÃÅ ÃK3Û!ï]Íqı’s¤à£À²”VX*)Úå*‚N›À3±V£À?°ªSxv*Ôxöhˆ‚Åm}úÉ›[³<ÛoPé'±h[¤Ø~-Åâ£ÖjàsœexÊ	 hßëXºMXêìfY	.‰tpƒ¸5»6v‘u÷ÓÄâàlÂ?Ú79E†ı¤ÙGlq×Ö§ˆpc£ıÔèíš«‰€ÍIšœ·k…³YÆ@He`f*
ù¥¨.& ç’[_o¥mõô§r†§©•ßlÖ‹ l÷A¶ÇmSêZÔ¸u†H\c	›•Àíp=`Ü_•Ò4rZZ˜›$6Fõ´­Ê˜sâ˜5wr´‚7ÀrSÕ4g£Ğ»&R&¯*¹e4ÎRÌïØ¢T³;Æö#/ŠV¤ëM£é+õXY‰­3Ö›´‚{/vômYs2Æ[0=—Ğn?UlÒĞ‡ãË°akt Â˜u€h,ì.°É¿ní—F%®ŒÒÓÎ=éßÛ½g¶–’=‰ô Ë¨hz`û¡-5ùkğ6€I°¿UúÕ÷aT.µãæ´eknl6“üÕè©©¢%ÑFÖo- ]\.kEÜl:ÊA„aÁ¹uÖ·OGÿ ´MÚ„¶G5Å—6·"Öô•˜ˆ1…Ñºa6R$ÏpNİ¹­Ù}«-D?¿wiTU~îÒ¨ƒı)ñ™(x01wØ½tÎ=Leæwë^U‰Ûš7¥èï¥Õ[›£ú5G—›%ló—za„ ?şÅæÈÀPe][vÛúS2ù'bËôeª³±êN–ÉO5ûCÛü—§¨àjò‡Ñ²B4gA§Ş©Åz³7¨2Ô€¥G)/ ;JÎïJˆRîïJˆAV‹¸²¼-¦x¹¬ÒÌb¹Ãú]eLüÆß¢÷,ò˜iæ™½ôQ½ãµ­'ù/ ân50K;¶H\é	şÓ®OñA#†ÕšybªÏso¿q¸·ó]GDÜê¬¶X£sG«Ñ8‚ç3ˆÈÒFíînÅÉàğMì
Z—Hê)p÷áÍŒ¤cØ÷]×´¿ÁN)P&©–±×ûGmnÃ²Ê6ºBæßc‡ùüB‘ª?dğ°pîú_C¿‰Aí.
±â%…V8İóSF_ûÀewêø7.QÀ<Î:†0îcæ9up‚N›À3±V£À?°ªSxv*ÔxökâY5q>O¥Ş¡uö°ñ™XElŞØ$·÷PrªöÅ#õ…­¸Öfq³@;ÜO¡câXUEL´øSÅ±
`ÓNç4’×·YÒ­cğæ£v²DàÆ`ApÒ¦ÙvAÛ{‚²õ5Š-(ÓšŒ'xES |A±Û/¬¦lvØvZGÅ «§Ã›İ
‘QTöK0hks[eƒ@ØÕ¦A9åÆ¨°ñ±v4¹·ß¹ÂëvÄIt;zulsãBàæz¹%©yÉ&HˆÚ20€?Ş+¶h»óQFzqİc[…ÄÆ€Ö°ä zõÕtg?ŠıÉ‘¶ô)fwìQ
]ã{r|Tä’÷êÚ?mX’ššzVÂdEˆsmøu¬™¢l±äuí°Üƒqú…ˆì6³2Ğ	Û¾÷õ §¢,™‹‹HqpÌ…¿ûUJèuo™ïa ‹¸lµöZ©Á0ü­„e7ØNßA_Pàô0½²5ŸhÑ`ònzXƒ9Â¥Î1¸¥âÍ,è·à®2‘Ì–=iË!mÆÃ›7ñWŸ…Ğ¼ÜÇ´æ½¯·>×][î5ØXÒÂÇ\«lÛ}›R
`#Ô½ú™3÷àíµ²ŸG¡e
ªgFd·V7ºâÊÈÂ¨D­”GÏc‹Únw•óÜ|:àêG5¹Ggù(2õ‘Ü70»·ïÿ 6_K2‡DÌ¥¦ãißk²ĞÿÙ                                                                                                                                                                                                                                                                                                                                                                                                                                         ÿØÿà JFIF  H H  ÿÛ C 		



          ÿÛ C                                                 ÿÀ _" ÿÄ              ÿÄ 8     !12AQa"q3B#R‘¡±SbÁCrÑğ$’á4ÿÄ               ÿÄ               ÿÚ   ? út „À                     n+§&;Öœúé0éË^Öù“}SæF¹kå¦³Âá´îÑ¹ó÷ûµL˜k}u˜×mN–¥+JÅkˆH             ­òã§Ñ_»›'ÄøzøwyöXòò|W4ø+şî\œN|+ÌûÚÉÄàÇâ¼G³—'ÅqG‚³oìòÀuäøŸo©ös_.Køí6ûª 5ü.m×uåæDËn"ÜÙ:êÿ .Õûùƒ‘1[OhÛj×8Í^5H·itNhÇËl³·4ÇÑ¯Ç°9±ğ™/©éZÌsn}ÃbÇ|ÓKNãSË¯=4üV*V´¤Mâ¼Ñ»tÜYË[Ú–æ¬êc´ƒ¿x~™kœõ˜½­ò¬ñ8éŸêˆšLDÚ+×ê?»„ùø©Ëµ˜ë36õSñ¾_ËçOFkÓl
L‚ƒ·ÂóÛÇ1Xş®œÁ_Í¿°<–Øø>&şN½g£Ù¦TğR!py˜ş×xhucøwNñÍ>îZR½+d€                                                            °                      H      H                               œgN÷úGPl82|ZŸôé¿yèåÉñ*ÿ «–=ìZÕ¬nÓ¨÷säø‡OÕÍÿ kÇµíiİ¦f}ĞC'Å­ÿ NŸ¼¹²q¼MûßQé LÌ÷ ±Ãgç­fº›vßE¸+V8šÍºzO¿“n^'MóZb)hšÄõærüœ»¬rÏÕÒ­kÁ_±3“¿ª½{wm~2+|º·?ÕÅèÇ/6˜šDÖÑ;ÜÎÿ  6áë†ÜÔo•’7×Ö¾Œ8ˆäÍ#SIëMzG’—Ï–ó3Û¶º†`ëÉÄâÖI¤Úm–bu?§Lòqv¼Z"±^~¶×¯«"g·P[&KäkÏ4ªèÇÀñWıYèéÇğŸõ/û@<å«÷ğÖgìö1ğ-?O4úÏWDDDj#PÃx›wX÷tãøN8ñŞgíÑŞ,|'ÃHûÏV                                                  3ÅŸMòùNµ Ğ          o“<Vˆû¹ïñáİçØCñnOËÅÉ¶?	Ääüìİ?– è¿†+Ä0ŸˆÒgX©kÊôà8jşiõ–ñZ×¤F£ØœßÉÚ±Š=Èà-n¹²ÚŞÎÀÇ¸é¯†;,                       €    „                         ·ÉJuµ¢¿pXräø—^Ó7Ÿg.O‹dŸb¿~ õäâ0ãñŞ#Ùãdâ¸Œ+ÏÛ³ z¹>+‚<6şÎlŸâ-áÕ`/|Ùrxí2        nW¦·ğVeÓá|E¼Z 8Ç«áXcÇ3oìéÇÃàÇá¤G¸<l|/“ÃIûºqü+,øíûuz€9qü7†¯}ßîè¦<tğÖ+öX                                                   qÍiHæÉ6rñqjeÇ#qN–€_N'ærå¤kùëÙLœfJÄŞ)‘:ú»ÏÚn&s^”Á½owyá³[&MòÍoÚóŞ#Ò¶L¼Dcçšâ´sF»ı‘‹å`âï[tŞ¹&}Ûcáb¿.fÓ6ÇÒ%´Ö³;˜ÜÇi€    2ÉÅpôñ^7éA¨ãŸˆstÃŠ×5ñqŠ?¸;&b:Ìé…øîŸ«š}#«8øug®\–¼·§‚GÜÿ ŒÏ“òpÏŞO“ÇdüÌœ‘éW`Zü;u¾ï>îŠbÇOb                             	BP     %	                      ™ˆë3¨GÄëhâ{ô´nß“áiú÷?íêæÉñoôéûÙç èÉÇqWızHèç™™Ìî@       m‚âoÚ“÷€Äzşoú—×´:qü?†§éæŸpxõ­­:¬LÏ³£Ã¸›şX÷{­k¬DG²AçãøMê_~ĞêÇÁpÔíHûÏVÀ                                                             ‹^•ñLGÜ9¯ñ½§š}”üO“ò°ê=lÅ/›<wˆs~‹Éù¹µ•^ŸáëŞ9§Ü·ÄqoXë7Ÿd|ÏˆdğÒ1Ç¬ºëJV5Xˆû$ÉÎÍ6ö†Øø.©¹õ­€"";        "@Ú³’#Í\“¨ôc<•´W]g°:"Ñ+CŸ¾®ˆî	                      $@    %	€                     Ç¼t¿¤êw{./Ìáï_=n?`x@   µ1ä¿†³o°*:ñü3‰·}R=İ8şŠ<v›}ºËi‡Ï“ÁIŸw³…áñøiv ò±ü+4øæ+ıİXşÃ×Å»ºÀV˜qSÁX…€                                                               åâğbZßW¤1üvKşNŸyuNS~y¬M½VËø†OãzBÕøv.ù-9'İÔ”ÃŠ
D.              Îõæ†\±'¯/fìrt¿Üuo»Væ›F›‚Á€                     	 @     	                      z|¼×§¤«™Dn^İø>ù>e«»KZã¥#U¬GØ6>Š¿éåYèéÇğŸõ/ûCÑ†>…§èßß«xˆİ                                                                             E§é™¨$gÍ½oÿ vFçútù£Í,úÌÿ ué©€      
Ïs•`Ê                          L€€   0                                                                                          n ¾NXìÊs^{tã<Y&Û‰ï     	FÑ6Õ¢=S1¸˜R)ôÇ•€ùúuÖÑi¶»ûôZ1û•¤Dhˆ‰»ïİjx:ù­®š‘¨‰ë¤ˆæëíÛ`‘X´ôŸ)X                                        	B@                                                                                    ÁÔ Ñ¨gP@™cxçÙ&ó¯îè›W³š0eŸo¸'µ—Úz:YcáùgšgrÔ     _zè	ğ­wæçğuëYæÜtN¼ÁJwûÇ‘©ò’¾ y}úwÒÀ                                        Â H                         “šŸyöÉ˜ˆÜ²œİ~˜ßM§/Z×Ş`“',Åc­­ëÑYË’&ŸO[n5ï÷[-fÑ¬[ÚT¦?X˜¬{óoyŠ×è»ß^°Ï-mäŞ­_¦#}âu-ç9Şã{­É]ÄëÃØ‹›å×›Å®«                                              ¸6 ¦ ¸7>Œ^x§,ıS­™³^–ğıµ§zşÛ½Uæ¦ızê}œñ—%§QªÜÑ4ş]vŸTÇ:Zë"fÓŞ- Öxœzu˜í·åÓú­‹$ßš-¶¬êc»?Ã×’±iğî#÷0cšM­¾i¶·ßËîÄ}c—ÖvÚ æŸ(LD@ú½trÇŸT€                  æ®õ¸ß¢³š¼–µzòôÅ1d›ÌÄÄn¾“¸cõrÍù§››–Ñå­èjÏi‰×t¹±Íy±røãé½oüº@                                 @                     Ó•+–-mvFzÚiÓ¼y)«jÏ—ê‰¦]òLÇxgÍo™[Ìk›¤7˜ÜiXˆˆòÂ±¬Nâ:ï¯:ôÇ?Fÿ LjZ ¤a¦¢=                                                 >IEL×œtİb&fb:û³¶|´úoÍ>ë¾›e§ÌÇ5í¿6qŠ½g%¾e§§íö€ckdœ“iúmÉjê=c¯Dãù³;¬M¢½¹·‰ÛŸItR±XÕ)¨[Võş€ç§h¤s[–İ'§”Çÿ oLW˜æi…ù#îWêò'–|å #–¾‰                  Œøfü‘hæôÅ2eŠ×¤Ç4ï—îÇñ3zDEf&ñªÛË`éG=9ù7õkzrÆkÍ1s_–-¸½£Ù‹Úi›_3S5™õ¯”ƒlœEy-òæ&ñ×Ù|w›o¦£¦§×l)Ãåæ¬ÛQË¸ŸxŸ-y6Ç†)ËõLòÇ/°3¯%m1“–9gqo>«ü¿®ñ^•½{û´å®÷¨ßªA0Ş'Ê¿O,rÿ •şM9¹§¿üı— Ô   S@l                         @     		                                                                             G$'P            €s#r„€       ÚŸN;LÚ³Ó«ÿ .à¿#6ù""+[óÖßğ½8mkw™­gšµnµÅ»Õ|]e` €@š êh&b;Î€_áëú¹¾İ[ÄÄÆãÌ                                 ”$                                                                                     Ú6	6¨	Ù´    BÊ¬        Î 4 u 4)|Ø©â´CüGxbmıÔ<ëñùçÃªÿ vÉ’ş+LƒÓ¿‚ïô¬/ñşŠLûÏG¨Şüoo>Xöai›u´ÌÏ¸ ôø+ópõõŸÑæ;>~·§ï î                               ˜@	                                                                              Â9"»Ü#h Ø        a	€H   gP  çqYøˆËjsê#¶½èZô¬nÓ÷a~?{O7ÙæÏYÜõwøIğV#ïÕ…øŒ÷ñ^~ÑÑ˜¨„¦)yíÒ7û-òõzEû[SûH(:²|Ûd¾­b‘½n;G®Öú~O]DMwYéÒÎÁ…8l–¥­á×XßŸ«:ÄM¢&u­ïÄGÏù*Úºµg<ë}:G”xü-'{œ“´3ÍlvÉ3JòÕ@¼-ù8ŠO”ôş¬‘½uàöÄRÜÔ­½ciE                                	                                                                        6 £`²6€´         #fÁ"6l    ,       àø5’·õG{Ÿ§6	Ÿåê54šÅãš7_8@¨ëäù8ïoÓ6¬ÒŞËÅ1Òm©n±:ğÏnî?™—ò÷ôz+={õLq5­m®½1Ìzo³ù#%÷ËÖ”@4œù¦¼³yåôflê	FÍ$uHÒ@—}à×òÎ?á×ÖKWù£Ñè"€                               À„€                     ÌDn{ 9ÿ {õÅŠm³Ñl\G=§«8òzOüaÏÃæÖ+|ÛuÇi¬Ìÿ e«Æa›Du®ûLÆ°Ç6KüÊáÇÒÖë6ô…mÃæ¬okMãÊİbAµ²R½çZşÌyjá´Ó×§øcšÑ–0å˜×ÕÉ’À®,´É^jöYÏ98ËÖ;^¼Ú÷t                                    l6	  …•        iÔ%ËÅfŠÄÿ `rñY­lº‰é
Ó6Jö2¯ºú^.2İ¯ÛÕÕ‰ìòšâÍj}èlÛ:d‹ÇI[`§jìØ.²´@  L%İ       "õæ¬×Ö4.µÓĞkÅÓ—ˆ·¤õş¬t¨lê¡ ôÇ35™‰ŠLë™iáíŠÌëšÜµÿ È2M)kÌÅcs·E0àæóÕmÉx·¿šÕŞ
ÒmÒki‹zÍgÌ¿hüËV‘ï,¯æ[sGª¦zb­£åÛš&6Ì|äÍK{õıŞ»Ä—±‚üøkoXE\                         @       %	€                     q±iá­¯mÇ³pS‰á¦‘«ÄG¡ÏÃ^ñnjÍã·R¸ø\±ÏZÖŞúW/ÃM'tˆ÷ìş\~?Sá´sÄ{ÇFÜN?™‚ÕóÖãîå­­ò¸|öı6å™öğqÓ,|Ì9§¶JrïİØçÅÃø²FéÍ3_²	§%æŸË2"Ÿ3‡â&;M¦iû4ÅÄgùu™ÇÏ¸éjË¦+X¯,F£¶™p¸ïÒŞS<¿`0â¿=²äñÛ¤DyC`                               7Ø$W`'fĞ   Â!  ²²$       2â3F*oÏÉäæÍl–wüGÃó<ÁzOV²ÊÇX	@4áçYcİÛ·Í¯İ×°Y*¤…áHZd¢  ,ªÑØ       Åñx/ûK‰êq”çáíëcöyjƒ{cÁT¿4Şcs1å¶£ˆ¦m-zxm°iò°Ú1n&&ÿ OO)U¾^8Å®X“Í=:[ïİË9rOyóæıÕ½g¬ƒ{åÇjósZ>_—îdâ­.5¿)8`ƒKæÉxÕ§p¢ÔÅ–şLû·§ÃóOŠbŸÜÈÛÑ§Ã°G‹wûº)‹<5ˆEytá¸‹ö¤ıç£z|:óã¾½¡Şœ_.iÿ s¢""5¨ô                                                        Íø|Øí3‚ñ·Y¥ºÂg‡Ë“¦l›¯ò×£ rS–+¯¦;B@       RùkIÔî|ç^€¸Êù­¸ˆäÜFçÏ~‹d½«5ˆë`¹¸sÎL“yëêåòíşv­iiİkûÔÇXìo–bÜµëÒûDnùÙ&+1&7½s„Æ+ÚÜÖúzÄÄwì·È§¼Ç/[E«Ò’""5€               hØ$FĞn´ n@        L%U€ { *       <ïˆŞyõèáıO_ŒÁó)¸ï*ÑË=Ah…£¢µ˜ÒÀ¯^ı!ÒÇJoÖÃX¡0ˆLğ´+@,”$  MP@,       <kW–Ó_å=—Âe¾yšGÓ>sê1ÙO†ÿ =ÿ jº)Áğôı;ŸYê¨òë·†³o³zp<E»ÄR=ŞœDGaÇO†ÓõÚmöèè§‚Gİ                                 €                                 àµk™Ñ‹Wtëè	E¯Zës­ô†xïx¤Å¾»Öu:/[^“ÍMLx<ä|ÓY·Ó¸¯ŠVÉ~Xw™Ô)’2[“UÜw˜™×Uæ¼ÔÕÿ °1´ÚrÖmÒi:{¯ŸÒ"~¾–˜%ëµ÷ßY™ê°1µrZºˆˆ®µ«yFŸ.9kYëË­~Ë Zï›]}R             ¸ G26Tí     €#fÁ(Ú6‚Û6®Í‚â›NÁaH  ´*š‚@  V{‰²       rñ\5oÛ¤º”ÉH<i¬ã¶¤çêô3àŒµÿ w”¸'ÒÚ·p[fÕêÓ>l•¯õF¦´¬yÄ-YN^³*Ö5 Ò…aháhV’ªÀ   °@                                            €        ¡                      "×¥|S÷K±“|ñY˜×Õ¯Ì¦æ7ÛşQl´¬ê|»û1¥yí5]M7ºy­rò÷ˆæ[}{gˆë:uæög7´×ï·O¾¥¼a¦â}¹eâ""";GH<ÖqÛš<5ë=º¯ÃïVôæé¦ "+i·œ÷H              6   T$      #fÕ™ìÚ›6íRmİ•ø*uŸPk9iÏÉ¿©;rá¤õ¼÷–Ÿ`j²İ½SÍ`i´íŸ2w Ò%m²Z$%X•€#¸À  ÙU•      Ù 1S&:Ş5hû/=äø\‘='uõkÃàä‹_›šuÑÓ^½=TÇŞb{ÇIzó)æ!< ˜´4‰a0šÚc§ôD%œJà°„€ &ªÀ                                                    L!0                     ¨à              áÀ‘]Èm@À0„À$  YOe@    ¬É2Îù<£¸&÷×İË—æÅ¹¢e¬BAseó[óæ¿%}Ë€Ë—×ªÕÇêÓIĞ*…ôhÒyM@Ñ¥‘=€‰J©‘+C8^$	£°ˆH  ‰î”H     :€&b;³µæ{vDÌÌõ@	B@íÕé›~Vê’óôVßË:VÑõJ—¬Z=á5šÏiØ#I‰Ñ¤ÓÖû/
ßÄ	¬´¬²^²VR€H ,ª`            ­«hİgp	Æ¶Î¼F+ß’³¹Öú@                                     @                             ˆÚm£h;@        ,Ø  YYî   ²–9-¾‘û{o¤U4I¥´4”     ¾Éòí3@˜ì•c²Ş`•¢U¬,Î%xÇu•X  $P    e~²Õ”÷˜ 3Í—“^àµ¯×H¬ZøòGï¦=[,ï¯N‘îŞ¶å[Ş9­Ú°LÖ“kkQ;V˜µxµ/÷‰†˜ø™ÇõV:oÙ…xœ×¼GMÈ:DéXñiH·6]{m{÷‹G—vUé?íik"½“ä!vU– a ,        Ã78³R¶Ôc´NçÜÆVknJÌŞ±¾IéÑ‰›åÃòçè¼LÛöLÌDn{)ó±rÍ¢Ñ1^ìxıü¨ó¤Z&ñì¤~&I¦Ç×Yç¼yÿ Œ“ls\véaÍ|XxŒué4·Iö²ÿ 'Š¶*à´DV5õïÊ=‡¯ÎœŸÍ³PsÍkøg¦yçVïq>kğ5äù˜õá·vÔá°c·5)>­     øŒ4ñ^#Ù„üB“:ÅKdu›qó|G'hŒQîŸÀMºæËkû[ñœ5;ßséXş;%ÿ 'ÛŞ[Ó„áéÚ‘÷­A<TÌÎhˆÓØ                            `                        À9‘¹Â 'hØ   &t¬å.¬xŒ¹7<‘Zwßª-<˜æŞzşéW1·ÍŸCæ{1Ë¼x¦ÓŞ ãá¢Óß]R¬tÄÄÆá.,¦?~îº^-÷i"À    	ªUë  ²I@  E»1ÓKöf	D@ ¶Hë=`7†vÈšWt­­½OjÇytÅiZî#–
8·›ôÅšâ¼Ú'š5hî¶\Ø?ÔíåÇ–—ÜVw XJ$	gkuuöÂgøõôˆku¼Ô¯iû¯ G˜/Â¼ËB©€H  ”-*€   Ê{µgnà¨Ò W-9é1çä´$¶Áš±¶óÓMëh´ïš:õz.lØòuµ§q x8Ú^<9zÿ Rœ¸³Úºİ·ÑŸ?5+ş¦?¼z:÷K_š¿ª± ”[²@g3,ënlÓşØëûµ–}³Oû£üZöO’*DOV°Æİ%¥dBU…€ Â* jÖ73¨KŸ§7>µêş+ÌÅgšÑÔ+<LÆLQËªäõï
pó’Ü³\QLsgÍÏ<ÑÃÛù¸|~€éÁÆsóóG‡s¸í¨SñÖİfk—ò‰İ¿¢f·ŒójÓŸjÇ1N-c’/OX«úƒæ_‰¼^ó?*~˜û­ÇÖ~]o¹¥¢tÚ0Ò2ÎHñLjW,FL¼U2|¹¥ifTÁ¿´ãß%wöÛ´ ˆˆí   &Ñftç¿ÃSõsO°:ã8ŒŸ“†~ö>G“ó2òG¥AÕ|˜éâ´GİÏˆà•İçØ§Ã°G[nóîè¦<tğÖ#ì_ŸÇdü¼\‘ëcğ|Eÿ ;4ıªìÏN†§éæŸ[utDDtˆĞ    +{Åc}÷Ò ¥ùâzjc¤Ä‚À                            „€                     ‰”¢À€        e–ŸIWŠğó>ZŞ<J_WÇjÇœi–‘—VÁ¿)Òœdÿ 
+åæcİ¸^_HÔ­5ç×4tô.-ïQÕ×Z[Ï¤­Û·AbUùç÷Vo(´í³Õì	   YU£°   *&P  ¥Ù¯`„ÄôVÊã¿Õ4ıãŞ¨ 9rÇ&O^wPZqsJòës˜äË“$ıVı|E1rÍ­]Ï¬99 vğøâ”ïõ[¬¸»K·:Ö›¼İvP ’ç¶ãŠß”ÇGLõ†9¼1o:ÿ €_ôi1[ê˜n	­Zz‚iÙsI  X   YYî   ;÷h¥Áœôùy¢V–{åOo)ù¾½zwY:ÅfŞıW©–Õ­'™twé=Áõ^ßOök\yúVc_îötÖ•¯†4™@"T·”ú/(òi~ŸuÜØ/»Z'Ë¤:+;dí	¤¦cjW¤ƒ¢%fu–	 î²«  ½y«5õHğbùX¢›æ×šñJDÌÄu·t€  )|Ø©â¼@.9-ñ]±ÖÙ'Ù3â<4ŒQë ìe“‹áéâ¼}£«Àä¿çf›{Cl|NÔ‰ŸYê¿6é‡¯î|G'yŒQıİšˆÉ¬õË{^[Ó‡ÁO"          ®MòLÄnÑÚ=ÕÃˆë]yî{Ì´                             ˜@	                     '°    ‰˜à‘IÉù’¸eÍ 4ç…yåP¹Ú˜ëÉö«XsDv·TÇ@›@  /ÑÛÍErGÓ¿@tŒ1çò·õm¸À L €X  JT •»Ê²´÷•@c–“¸µ|tíï•·X÷€L^³õYÍ5’±úoş[‚Ñ(èòMA.~&±¾m÷òt1ÍÃÎIÜ[^Àã:ø|³hŠë¤GY^œ>*Çnió™^µ­cU@%	DZ#·”¦{€åµ-,j7Ú]U­§¿D×³HÖ‘¢   	„¢;¤  D¤À¨  ·ğ¬­¼ ÍıÏ‘Í>€Î+jŞ¿ÊÕI_`‘	}^ˆÔùÊÈj ++Â-€pä‰+Qúq¸e½>dtµ<Ó‹$ÌG4õúgn“#r‰÷Ñ¬1£XÄ$£²©€H  -zWÅ1v|F,¹""—äõe_‡aïyœ“î	¿Ä8zö™¼û)ø3'åaå[:©‡<5ˆX…â²~nmG¥ZSáü5{Ç4úË Vµ¬j±öH               ®JÚÕ˜¬òÌù‚Â˜m¼q¾ı§ö\                            $                      D÷Be  ™#é]Ø€     „€  ”$c €c®{d¥9ñÎ¹{­{×Ï7g~'æG,F£ÍœißÂqŸ6y/¨¿—»©àG÷züó±uñ×¥šeĞ ,"  ++"A )ï*­>)@"Q)Dƒ’óËy‰í¸uoésä¤ß=)sûC¢ñ©˜ÏhLvVW 9 Üú'@A J«+ ˜^Âğ!e!h€  ,ªÑØ   	î  ÉŠ'UÊeY€G4-Y‰³µuÙl11N½ç¸.%   ‰’eIëæ
ß­mÌ8YÖ½İcş]÷T"Şˆ‰é¿$ìVµejÂQ	 €€     Lë^é‰Ø                "±ºy¤                            `B@                     •VT   Ú>¤/“ÊT    JA4­¯¦6Í`t9Í’?V–§×Y?û@:€	@8¾!Nµ¿ìãz|e9°Oûz¼ÀK·ás?6ñå§·ávş=£Ö ôÀÂUX  $P„rU`ùp|ª®:àÇ[óëêõ2×õzwh‰ìkwhÎ|K‚D&  %  §šğ¤­PiC8^q	  TÜ  BÓÙP   ghú™ÎöÓ%g¼)ÔGYZ–‰¬Lv–y:÷Äy#âzDk—ÈŠÄ¬ J©”GYÄ1Éo®+å1ıÛÚtÃ4};ó¯Pi^ºöhÊ¢c´õ_`y´«=Ç¯Uà,¬$ &ˆH  ­í1¯/u‘hÜœÓëÚ²&Ößeù?›¯’ÚúzÜj#R½bc§—’@                rd®:óJÑÖ6LnÅ®8‹w€°                €           ¡0                     %(@  
Ş>–.†:$  	3µ‹Ûê×£;H–z½çU·ÇÃÍºß¤z7ŠÄF¢4n?ÂÛ]g¯”BÓÂâÖ­»¦c£Ÿ›(˜µ÷×îŠŞ""5¼†8xˆÉnZÇhÜËe@Ğ&»‰¬ùôxöZcÑì¼Î6œ¼Dú[¨0tğ?Š§ï¿èævü.»ÍkzWüƒÓ ¡TÀ$  g¸›    $$øš)xÕ–H %    "ÊÂò¤‚ëÃ:Îáx•a`   X   Y  [øeš_Ã,»<±Ólxyÿ ä[ş×E·1Ö‹~dÛı½i¬#KDìĞĞªì2+ş	´=g^^kt†\ÕŞÿ ±«[¿HôÍ¢d˜ú~éŠÄ'–m:€S…Åü9­ºòÛPÒÖœ±¦*ÀÏ¦ûuk|ŸY_@ˆZ$  YU     ›DwG=A"&ñ­÷Ğ                                              ˜@	                      	  vîÑK‚€ OaÍ<Tóròê'Ï`Zu¼5ÅIñMw>[ìÃ/øuO.Z{I£H¶ÔÍš˜ãv¾PÊù£‡Ç‰æ»‚öµí¹ë)®^/%úGÓ_H`ijSÚŞ£ÎU\5K^Tê>Ğêe[âäÔN¢«RbÕÜvE\AÇñı5¿§Gc.&œØ/¿ô”ô~2OÚsÓø\
óë`v€cº X  {*²     3GÖ¬¯›Å
H%(    )Jô·İ¬1¿N­`BÊÂĞ	  0•a`   g™»,µn³ g~Ìq^õÏX·Ù¶§Õ^g%¯3?Löı$O7“KcŸ.Ìô
ÏÌó”jWå÷>ˆ#~G,ù¯Ğ´b¸3Š¦!´a>«ÄDvUÅiïÑ­kì      Â	  L5oEcsÛ¯Ú€§$ô‰íEuæ                                               B                      D¡iT  E»$)'ºA¸{Ów´Æ·Ó]Şƒ<¸âñïÅ7Ü}»¢¹òS¥gö–¼?h¼ÎHúc´z¦ü~‹kÚAËiµ§s=PèüoZ«<>¦c{× 0Şœºd	ˆµ§–:ÌözxéÉJ×ÑÅÂäŠ_¯›¿q ( y+Ë’Õô—¯ÁÓ“†¤zÆÿ «?ÏÆR<²wı»½?°   -„U  'ºQ €   –o)eæß,n’ç¡   ‘	  €¼nÅ?DÇ=f´/áx€  U0	  Q2€ [ÄÍV%½ÅKVmé- ä¯¤'–¾‰      4 'P
š•€F“¨      ´Îâ±æùæ#¾ä›[›¾¿÷Ğn‰gÉ;ê¼W·¬F`                                             	B@                     Ueg¸   3ÉÒTêÒñÑ@¤¡ !Ë’“™õêëVÕæıg»:³aµg}ãÉÍhRÑYÛIË3´îX·ÅÂå¼oÃ_ptp–›cŸièÙ\X«œ±ıV  +Xœµ·w¯İ»é0Ü   î²«  €*    YñË©Í–5—îUH$@	  Wz¼JÌîˆ^¤óV'Õ¤R  a ,  *¬¨        'FÚ4           E«¿iÒäşn²°              9 æöŠîPsB9¤ ëêš¡1Ü                           (L                      ‹$À¨  ÌÚ³à    
eü»<ì“;zŒ-Âà›ncöØ3àñ×åóÍzï¤ºQºÄj;À‘RN„tÖ;2Úøç .   ´*˜€  ‰Be    2Ï"ŞUÉÇ`sˆ‰è ˆLP  Ò–k´ÿ å­qV¾ò
`¬Æ>­R     -!  ³İdX ¤è- Ô§• €     õæŠïêĞ€3Œ“óæ–˜Ö·Xócn;QÏÉ?+zæÿ ğ@                  hŠóHnÌ€Ê      Á€                                                 Q3İ  —][öƒ`”«° r¬ÖÒ¸
E!= €  ¤õU1Ü    °  «+     (YšúJéËK|Ù˜’ˆ‹ in[z'åÜĞÓåOªc$Åm-¢µ%”bŸ>‹Å+        	éU`  i      ÌGp½kâ}ÙG†ÜÜ“ÍhòƒaÉø¼ÓçV‘ò£¼oª™sŞ±xµ·%wßğÌœß.Ü-tqââg.)Ç—¥¯ËoWWÁHÿ l0ÅÃn2âÉ_£›t·ßĞÓ4Æ9:}6ä¼Ïr1N[æ¦KLÚ¾­1ğ3¯ŠöéiÜiÑ\4­ùÿ \Æ¦AÅLŸÿ >iï8î¼ğùï9+jÄóOL“=£Ú98|wåßjÎõ! +Š¶®:ÖÓ¹ˆÔÊÀ           ¸ G27 ²9¡T‚y¥@       Ht@*J@                              €                     (ZU  !kwT   eJ 	P  Ö“º¬Ï÷†€   ˜J!  %$‚               thi`¤ê          e²WÚ“©†èÉ^jZ¾°+ˆÇ›«6»×Hi“'<biå¼~]{Ì´áq^8o—’5=cö)ÁSåR—™™§†ÑĞ–æœio+sFç}ÌüìØm“^¶´Æº:#‡Å˜Šëš53æ¶,qR'q_PqaÇ–ß7‡‹òÖ'¬kÊ[q=­Š˜iËí>Ztê +ˆ@ f            7'¸ÈÜ€  )õŸ¼hæ]…;¯©ë®Ò‹OOu‘¨ÿ ”‚   Xi:                                   %	                     U‘ €  ºmÙ   %( •VR{‚DLë¿FWâğWõn}°â¿Ä?’Ÿ¼°¿ß«_`zV½kâ˜…qfÅ—'%'sİåu•ñ^ØòVñŞíVš]Z^/Hµ{JÀ   ,ªĞ   
„÷         Ô§”-¨4                  ,ù¹cQâ¦âfcÎ;±Ç—&HDF¼ØO?4ï¿ê˜º²åŠG¬úË[o—®™åÃ©¤uŸ5ğãµ7Ö ‹[rñ;õ^)	ZF¡`         a›–sR¹?/Sö™n‹V¶Z7àç­±câyi1˜ë[t©±Eyy#—ÑpF½úí ¤è-¨]JyR                                        	„&                      D¤@  5g~àªÕKåÅOâaÍ~?xbmı˜ßÍ>T‡İ5˜šÍ»Ç“Ç¶L—ŸªÓ/^”äáâ¾š¸vÅ¿İkkš»óè®~˜-öFË¬úLJ4òø˜µsZ³;Ôôdëø…‹şhr´Â4  wü77YÃ?z½…KMm¯zõ‡·%rR/^Ò   &˜î	  BÓÙP   @$Òt      ¹/É¯¦m¹×@XS&lxüSÖ{Bqä¦JóRw °              ™qó×^k€¥1V“Ñh¬Go>é   Vù)Oè           Yî-£@ªt¤€                                               H                      ¬‰”  ˜Ú@rqØfqsÒf&½ãÙå½şı=^&|S‹,Ò|»}\t›ÛQû·ü=á+“>s-§HÖ9ãsÇGn\ßL{éÏ7ÔÄú/Ï‚Ö¨­xœ±ò­ÊæÉ¼:ó˜…rú[²™rE53?h<WX¤¹šß,^ñ3Ly)n^iåG”4Îª	ĞˆH èàøŸ“}OåÛ¿şX ÷G'Ãóscœs=iÛìë   ‚;  4¤è       ²¿³ËÖ×ÓÁ¨æ6>‹D
g–ó=ë+`É{åÍ¹úkmTm>|â¼rúJ“’ö·ó?OÕ]t+Ÿ?húsW_¼#øø¨ŞïMró{{‚óÅd·Ê®=De–º•-lÖÇŸí»Óê­»/Nü•¬Û_.ójÌuèŞØ#rWó&9zö-òÆñ_ÃÏ][&·?fœoNxšÌRgt™kÃaœXb“;ÓP                ½iâ%–}ÄEâu5ÿ M³×ôÇ?NnŠÛ7×ßUOiÿ à™‹WU§5W’c^HmtÔù›SÉ3Ö›ëéìÎ¿FIó¤}>ºßVñŠº×xÖµ=“ZÖ±ªÆ Á¿•ı¾Í                                                              &$                     	ìªÊ€   ó>'ó«mtåé/M­m´n=$6¼<¥·ÌÛlß¬õÅ:ŸåÎlœ&|T›Z>˜ô‘j-vs*V¶KÌjft¨‘$	 %jc½ü5™ûq^qä‹Ç“Ø­¢Õ‹GiyÔønú¯İè`Ãò±E7½y‚ÂÀ#IĞ          Ã1ZÖÕ&&a»†˜1~7%/]ïê¨-Ãqg†´şfJÎŠñg/Ê›DóÄêkådÇ™š±ôâÉ'İjğ–O™ËğòÆÍÜ5ï{ÿ ›ÔÌõéÙ§=éŸN]Û-51îÛ›%­µm;¯¬5¾*^k6µë çÇ‹TÏ|ÕÕruäï+pí\S6ßÕ;÷Ó¤Q3½uÀ                      ÌGyĞñ¼=W4û0¿Ämú)ûÈ;•¶JSÅh»Ì¿Ä_½õíÑ¿Ä0G‡vû0¿Ä2Ï‚±_îå¾l×ñ^^¦óá¥½aä;ş}ãµ?–Ê+¬             f"&g´+%rV-^± °ã½²Û‰¾+eùuˆÜi¸Œ•Åy¾dcğXªÎ\qnY´E½6å›çÅ\yg'=m1®½}dˆÇl‘)iß7-ëÔø«Ï|sˆğùtgN6Ö´O5+ëIŞÖšñ|\E)»MuzöZü.[î-xä·ûzı¶ˆˆˆˆí      "×­cv˜ˆ÷sßâ5{O4û¤qş+‹ÉùXu¶ÿ Ø?ÆdüÜÜ±éPtß6*xíµ/[Ö-Yİg´¹éğş½ã}İ­k¬j=                             „€                     ¬÷Y    .7Ãdû6S-y±^=bAâšL$„ }û/‹\¾ÛªŸ¼øïíA†|–ß¦Ìb&gQŸgµø|sH­ş¸¯ªõ¥k¬DG°<œ|oÓËÿ s¢Ÿÿ Rÿ µ]à1§ÃÓµ7>³Õ¶€                 UâiçÓ¨5Ùs[5Ó•oâ_76¾ÂVµÉKFâY_‰Õşµóg†>ºÌÇIìÖü<[&ûUDd¾KbÜWUêpö™¾¦İ#´:#ğù'¬-¨ôì€                 æãseÅäD÷p[&Kø­2NüV
w¼oÒ:°¿Ä«ú)3÷èáßâ-çËÌ-3iİ§qlt›ÌÄ~˜Ø*5ÃƒæVó½rÂkÃÖykÍüKWš#È¦”½ü5™û5ábgŸ–7–#èßfœñÖ‹Ú"ñ1mDtXğt‹ä˜Œ{×¿Ù\ÔŠeµc´-›5oœÜÑ*dË|šæò]ùsëù£NtÒÜ·­¿–vdP       6\¹ÿ iªî7Í(Ã|õâ'KsÇ/6ÁÔn!Án;&£$LrÌôÇ©Ş¾ëj™¸»×'XåJıÁÕ—51Äo¬Ï†#¼±ÉÆÄb›Ò75Z³Òa…«ÍJdÃÍÉŠf³×®½šbÃó)“éå­ãQ3;´Ï¬7Ïó+L¶LÑÓ—¦’““øâÚûÃoÃócÇ[ÎíS¸iò±óóòÇ?óyƒ›ŠÃÍÄb·'5{YÓòéÉÉË¾‹ Âœ:Ì}Vµká¬ÏHmËYëª@  É’˜ãw@,8ïñ<QÒ•›Oô"ü~o.:ÿ ïÜ›ˆc“Œá©ŞûŸHêÆ>6ë›-¯ìß	ÃÓÃHûÏPaøì—üœ3oy>_Ä2x¯ãÒ€9+ğì[ŞKMçİÑL8©á¤Bà               ?ÿÙ                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            wOFF     7è     7œ                        OS/2     `   `&cmap  h   \   \æ@á,gasp  Ä         glyf  Ì  0,  0,¶zhead  1ø   6   6„hhea  20   $   $Ähmtx  2T    öloca  3p      §ù´maxp  4          T ºname  4   ¨  ¨•™Qpost  7È            ø   ™Ì   ™Ì  ë 3	                               @  æÀÿÀ @À @                                      @         à=æÿıÿÿ      à æ ÿıÿÿ ÿã B                ÿÿ             79               79               79    
  ÿÀ À  	     " ' D d  35##35#335#!35#!35##35##35#%!!7!"3!267>54&'.#3!2654&'>54&#".#"333MÍ€€ÿ €M€44Müšfü€!





!€	
		
	ıj/"w"/	_D'E0CššššššššššššššfşšfM	

ş‚				~
	üº	*#00#+	E`%E0   
  ÿÎ À     % * / 4 9 > [  764'#54&+"#2735##35#335#!35#!35##35##35#%!!7!"3!267>54&'.#$fPU#ñ33MÍ€€ÿ €M€44Müšfü€!





!€	
		
	2¡”¡?ššššššššššššššfşšfM	

ş‚				~
	   ÿÀ À  	     " ' ¨ ­ ² ·  35##35#335#!35#!35##35##35#%!!7!"3!!#";267>=4&'.+5!#";267>=4&'.+5!#";267>=4&'.+5!5!267>54&'.##53!#53!#53333MÍ€€ÿ €M€44Müšfü€!





!›şš5!





!´
				
L34!
		
!³	
		
	K35!	



	!³	
		
	Kşš±	
		
	ı5ÍÍÍÍÍş™ÌÌššššššššššššššfşšfM	

ş‚		M€		±

		

±

MM		±

		

±

MM		±

		

±

€M		~
	ı ÍÍÍÍÍÍ   
  ÿó   	     " ' D ^  3##3#!3#!3#!3##3##3#%!!7!"3!267>54&'.##3!267>54&'.+!333MM33ş€ç´3gı3Íı!





!ç
				
ı´L

	!æ	
			
ı3ÚşfšşfšşfšşfšşfšşfšşfšfıšfM	

ı‚				~
	ü³

		

~

ıM   ÿÀ À  ) 8 G  2>54.#"35".54>32#3?35#%5#76&'.5#3 j»‹PP‹»jj»‹PP‹»j]£zFFz£]]£zFFz£]¾SZ
	
Y3³€WZ	

	X3³@P‹»jj»‹PP‹»jj»‹P@Fz£]]£zFFz£]]£zFµ3`		aM³3`

]O³     ÿÀ À  # 8  35#35#735#2>54.#"35".54>32#Rì3³Wñ3³j»‹PP‹»jj»‹PP‹»j]£zFFz£]]£zFFz£]€3éO³3çM³ı@P‹»jj»‹PP‹»jj»‹P@Fz£]]£zFFz£]]£zF  
    s  ! % ) - 1 5 9 = A  !!72#!"&'.5467>3!3#3#3#3#3#3#3#3#³üšf	
		
	ü€!





!€ıh33Mç33şæMMÿ €³3&ı4ÌM

ı				ä		³ş  ş  ş  ş  ş  ş  ş  ş    ÿÀ À  ! 6  33535#5##32>54.#"34>32#".5ÍLÍÍLÍşóP‹»jj»‹PP‹»jj»‹P@Fz£]]£zFFz£]]£zFæLÍÍLÍÍ&j»‹PP‹»jj»‹PP‹»j]£zFFz£]]£zFFz£]      ÿÀ À  % :  %?'#";2>54.#"35".54>32#(ˆ"´3SöóW2(j»‹PP‹»jj»‹PP‹»j]£zFFz£]]£zFFz£]ó$½4YZ3şÍP‹»jj»‹PP‹»jj»‹P@Fz£]]£zFFz£]]£zF      ÿÀ À  % :  /7326=74.#"32>5##".54>32Í$½4YZ33P‹»jj»‹PP‹»jj»‹P@Fz£]]£zFFz£]]£zFèˆ"´3SöóW2(j»‹PP‹»jj»‹PP‹»j]£zFFz£]]£zFFz£]     ÿÀ À  % :  7'54&#"'%32>54.#"34>32#".53$½4YZ3şÍP‹»jj»‹PP‹»jj»‹P@Fz£]]£zFFz£]]£zF˜ˆ"´3SöóW2(j»‹PP‹»jj»‹PP‹»j]£zFFz£]]£zFFz£]     ÿÀ À  % :  7'32654&+7'"32>54.#2#".54>3Øˆ"´3SöóW2(j»‹PP‹»jj»‹PP‹»j]£zFFz£]]£zFFz£]$½5XZ33P‹»jj»‹PP‹»jj»‹P@Fz£]]£zFFz£]]£zF     ÿÀ À    "  %'!32654&#"%!!!!267>5Mÿ ³MššıÍ,  --  ,™üšfMü µs ³Mšg --  ,, æıM³ ü  «    	ÿÉ÷·   . ?  &"7'7'2764/'&"2764/&"2764/ğ'p'ş '&C,,Dş'p'à'&C,ıè,Dâ

şÇ

8	
ı

şÈ	


9o&'ş 'p'D,,Cü³&'à'p'D,ıè,C”
	şÈ

9

ışÇ


	8

   ÿÀ À 
    % 0 = J  3#35##3";2654&+3#%!!!!267>52#"&5463!2#"&5463fLLLÌL4çfffffüšfMü µşóş Mæ443ÿ  3ÿ  Ìıg™ü  «f   f    &  32654&#"!32654&#"!32654&#" 5&%55%&5¥6%%66%%6¦5%&55&%5À%55%%55%%55%%55%%55%%55%    é — &  32>54&'150.#"10>72 8K++K8 Mƒ^6QŠºhh»S5_L­)G66G)#@:8([;H;;H;['7:?#   ÿÀ À   & ; P  32654&#"332654&#"332654&#"!32>54.#"34>32#".5&´³ısP‹»jj»‹PP‹»jj»‹P@Fz£]]£zFFz£]]£zFÀj»‹PP‹»jj»‹PP‹»j]£zFFz£]]£zFFz£]   ÿÀ À   0  !5##32>54.#"34>32#".5ÚÍLş&P‹»jj»‹PP‹»jj»‹P@Fz£]]£zFFz£]]£zFÍş³M şój»‹PP‹»jj»‹PP‹»j]£zFFz£]]£zFFz£]     — [i4  ! 6  77'7'''267>.'.">2"&'.>7r6666ÖJLIJJ½Ä½JJILKJ¹À¹K-@¢©£@AB@@A¥¬¥A@@AB’6‘“7““7“‘6‘‘¢J¾ÅÀLNMMNLÀÅ¾JJJJJ,A@@AA¦®§BDCCDB§®¦A    ÿÀ À 	  ( =  7'!75!5!5!5%32>54.#"34>32#".5Z››ş³3š››M3şæş@P‹»jj»‹PP‹»jj»‹P@Fz£]]£zFFz£]]£zF¸]`B©/GH__>®1JMj»‹PP‹»jj»‹PP‹»j]£zFFz£]]£zFFz£]   ÿÀ À   2  ''32>54.#"34>32#".5Œ$$5ğ3şæP‹»jj»‹PP‹»jj»‹P@Fz£]]£zFFz£]]£zFËˆ"3çˆ2j»‹PP‹»jj»‹PP‹»j]£zFFz£]]£zFFz£]    ÿÀÀ  	  3  3#35#%32>54.#"34>32#".5ßMMMMş&Q‹ºjj»‹PP‹»jjº‹Q@Gy£]]£zFFz£]]£yG³şšfşgMM¦j»‹PP‹»jj»‹PP‹»j]£zFFz£]]£zFFz£]    M 3   '	 &$¤Nı©şò¢şŞ"™Mı²	    ÿÀ À   2 G  >54&#"3>3253535#%32>54.#"34>32#".5&EH^UR\3>=@@,.LLLLLş&P‹»jj»‹PP‹»jj»‹P@Fz£]]£zFFz£]]£zF\ZKU^VP:9@@6<
	M)\MMÀj»‹PP‹»jj»‹PP‹»j]£zFFz£]]£zFFz£]      ÿÀ À ' , M Z g  %267.'.5467>;54&'.#!"3!7#'#"3!267>54&'.#32654&#"!32654&#"˜	




!	
	ı³!





!M5ææşş!




 	
		
	ü‚4%&44&%4³4&%44%&4À
ş

	D
		

ı‚

	Mæ3şM 3	

ş
		Ë
	ı&44&%44%&44&%44%   ÿÀ À > C P ]  467>;54&'.#!"3!2673!267>54&'.#'#"!!#32654&#"!32654&#"€


!	
	ı³!





!M	 	
		
	ş!


ıÍ3Mææı€4%&44&%4³4&%44%&4so

	D
		

ı‚

		

Ë
	3	

ş	fıšæ3şMó&44&%44%&44&%44%      ÿÀ À  t ‰   #63267.'+>32666&30654'>7>7>54&'#.'57332&7546732654'54'>54&'&'.'532>54.#"34>32#".59	N!E!_9{3&-"("
uN$($8 ş&P‹»jj»‹PP‹»jj»‹P@Fz£]]£zFFz£]]£zF3Q	
1I 
CQ7
_()	Z

—+6	>	#97c69şj»‹PP‹»jj»‹PP‹»j]£zFFz£]]£zFFz£]     ÿÀ À H ]  6>32>7&67>>7.5467.'&'.7.'#"&''74>32#".5 &B)[/<%%<0Z)B&!**!&B)[0<%%<0Z)B&!**!š8aƒJJƒa88aƒJJƒa8R0Z)B&!**!&B)Z0<%%<0Z)B&!**!&B)Z0<%%<nJƒa88aƒJJƒa88aƒJ      s 9 F S  5.'.+"33!267>7!523!267>74&'.#!*!!532654&#"!32654&#"Í($
€(İ
ıÇ		Q	€ı,	æ€ıš™&%%&ş &%%&•
ıìN&KşÌêı½%%&&%%&&       & Z  	   0  %!5!!#53!!5!!552#!"&'.5467>3!šÿ  şÌ³³Müšfüšfüª()TÚLLLLfş3ÍÍ€€Mıs!!Œ       s  	    5  %#3+3+3+53!!72#!"&'.5467>3!3MM³MM³MM³MM™üšf	
		
	ü€!





!€¦´şLMş³ ş ÍÍ€ı4ÌM

ı				ä		   ÿÚ ¦ 	  %- bÿ <şÄşÄ<ÿ b¦ş¿3ùş¡¥¥_ù3    ÿÜ © 
   %-''%7 ş <<<< şv
À.îî.À
v©ş¿4ùş¡¥¥_ù4Aì'·şızz·'ì     ZÿÄ¦¼    	!	! şZLşZşZ¦¦ü´¼ş^¢ıªş^¢    ÿÀ À 
     4&+"3%4&+"34&+"3 #"$šşM#"#šşM$"#šÚüæ³ü3ÍşfıÍ3  ÿÕA  3  2?>7>&'.>&'.>7;C&.¹=º@—™’;BD?A@£«¥A=5„‰‚343645„‰ƒ333659;’™—@º=¹.&C;A¥«£@A?DB=46343‚‰„556333ƒ‰„5    ÿÀ À  + @ U  2?>767>4&'."62&'.732>54.#"34>32#".5›1"F)F9ˆ1ILI,(q')'(q)'(ş9P‹»jj»‹PP‹»jj»‹P@Fz£]]£zFFz£]]£zFu1ˆ9F)F"1ILI,')'q((')q(‰j»‹PP‹»jj»‹PP‹»j]£zFFz£]]£zFFz£]     ÿÀ À ! & 1  4&#""!54.'>5%!!!!267>5d3>>2KP?.EVP+OüšfMü µì&TH.R*.F%! 2?+ÔıM³ ü  «      ÿÀ À     !!267>5#'!!7!!267>5³üÍ9
M€ıæMü€9aı¬M
6ßyı³Mæü€
!0    ÿÀ À    !!#!!³üšfMüK ÀıM³ üU        ÿèŞ‘    	&"!"3!764'5!	!5Şş_"F"ş› .- f"F"¡""üo³Íş3şM|,,í9(şó'8â++},Y+Xš şfşg ™     /ÿÀØ    	33!2653>'&"#!	!#«ş„,,í9('8â++şƒ,Y+Xšÿ š™ÿ ™ş^!F"ş› .- f"F!¢""üo³Íş3şM        f  	  +  !5!!5!!!7!"3!267>54&'.#æ4şÌgÍş3füšfü€!





!€	
		
	MMMš€€gıL´L		ı4

Ì	
      s  & 3 c p }  %!#"3!267>=#32654&#"34632#"&53267>7.'.#"32654&'>774632#"&54632#"&5³üš!





!€	
	MM-  --  -'ş|	$%	2K9'-BX8)*@_D)
-  -&8K2ş´Z		ı				 --  -- 0?ELH:"#Xii"( -- $
(^WE#şg     s   ,  !27%!"'%!"3!267>54&'.#³üšƒ8ƒüÄş˜&ş˜Tü€!





!€	
		
	ğıj–ş~‚6ş—iM		ı				ä

   )ÿâÑÀ    64'#4&#!"#273!	!3U|,,î7(şó(8á,,|,X,Xš şfşf š¡"F"e .- şš"F"ş_""‘şMş3Í³  "ÿï —    275!2654&#!5.%!	!"¡"F"e .- şš"F"ş_""‘şMş3Í³kş„,,î8((8á,,ş„,X,Xšÿ š™ÿš  ÿüÿÀÀ $ .  4&'.#!"3!267>5!!!-!"°L

ü€!
		
!€

Lü™gıfÍ!şßş™24så9/
		

ü‚

		

%9åfş€³³€42       ô€ <  >7''.7>6?6&'.'&>7šK]€—MMyK^€—M+L@3o

Ç3
g<MZ3]µ™qZ‘]]µ™pMNyL^€–MNyK(6C%B3	Ç>,M@/[‘]]µ™p[‘]      & Z  ! 2  "3!26=4&#!"3!26=4&#!"3!26=4&#!0 ü` ü` ü`Z		ş™şš		     ÿÀ À  ) .  "32>54.#".54>32#!5# j»‹PP‹»jj»‹PP‹»j]£zFFz£]]£zFFz£]óçÍÀP‹»jj»‹PP‹»jj»‹Pü@Fz£]]£zFFz£]]£zFæLL  
  ÿÌ À  	     " ' D [  35##35#335#!35#!35##35##35#%!!7!"3!267>54&'.#'&"#";?64'333MÍ€€ÿ €M€44Müšfü€!





!€	
		
	şù¡“¡ššššššššššššššfşšfM	

ş‚				~
	üŞgOV#     ÿÀà¾    3?3!.5#!5#76&'<ÁÒ7Òxş\x3Ïx¤ËÒFãDã´¤xş<Üºş\xà=     2ÿÔÎ©    3?3!.5#!5#76&'BÂÒ7Óxş[†3Ïx¥ÌÒ/ãDã´¤xwÜ»ş[xà>     &ÿÉş· 7 f  "&/&4?>328103261810654&/&4?>32#'27>54&/&"#"&'.#"û,$$T--			$$T-,ıx-ª3ˆ
		3T				şö"!
	T7p$f$T			#f$Up--ıxÀpˆ		pT			"şõ				T2         ~ 2 `  %!54654654>7>7>7.'.'.54>32%!54&'./7>54&#"0 ü -GU)3U@8Q26Q7$Bf7(QA)ü<ˆ`UI…D @^g74	h<Zm 	
	6F. 
s\4T; )BT+JpP"
%8*’<V.,4=P_C-x/M^
-);D		  ÿÀÀ  )  32>54.#"34>32#".5Q‹ºjj»‹PP‹»jjº‹Q@Gy£]]£zFFz£]]£yGÀj»‹PP‹»jj»‹PP‹»j]£zFFz£]]£zFFz£]        ~ 2  %!54654654>7>7>7.'.'.54>32 ü -GU)3U@8Q26Q7$Bf7(QA) 	
	6F. 
s\4T; )BT+JpP"
%8*’     &ÿÉş· 7  "&/&4?>328103261810654&/&4?>32#û,$$T--			$$T-,ıx-7p$f$T			#f$Up--ıx    ÿÀÀ   32>54.#"Q‹ºjj»‹PP‹»jjº‹QÀj»‹PP‹»jj»‹PP‹»j    ÿÁî¿   - C | ’  4&#"326534&#"3265"3!5!2654&#!#"&'.'0&5461>32'#".'#"&'.54632>324>32#"&'.'0&5461>32d	
Ê		ı_3HH3T|3HH3ı15.',(¶! 	

€15-'-'.K8M.K8M©H4ıv4H||H4Š4HşZ)r	-82G'y.BIL	0;34;1u*u	.[15>21.AJ;y)r	-82G'       s  2  !5>54&'5!7!"2#3!26="&546354&#1±EZZEüEZZEb/ü@B]]BÀB]]B$ƒzNNzƒƒzNNzƒOõ]BB]õõ]BB]õ     – Vj*  ) 8 G L  ."267>4&'"&'.467>2"77'7'7'72674&%3#jK½Ä½KKKKKK½Ä½KKKKK-B¥¬¥BABBAB¥¬¥BABBAıâ:„„6$$8$~~$=ƒş¸00*KKKKK½Ä½KKKKKK½Ä½KıYABBAB¥¬¥BABBAB¥¬¥B˜;6%$59$%=Úş       ÿÀ À  ! .  "32>54.#4>32.5"&'# j»‹PP‹»jj»ŠQQŠ»jş@Fz£]R”:ı‰18ÀR”:w18Fz£]ÀP‹»jj»‹PP‹»jj»‹Pş ]£zF81ı‰:”Rş@81w:”R]£zF    ÿÁè¨     '&"%64'7''7èxDıYV@§üƒ#€2Ñ’x’x/yıYşÀU§Dı/"2’y“x      >şB  3 H U _  3#5!!>7!5!54&'.#!"3!267>="32>54.#4632#"&5%!.5467!±©©ÍüÌ³ş4$üŞ&&$$x3[C''C[34ZD&'C[3½oNOooONoş—şüÓIIş³¤,xB
m
ı—

H
!'C[34ZD&&DZ43[C'øNooNOooO$I

	
        zÜÿ_<õ      Ò2¥ˆ    Ò2¥ˆÿüÿÀÀ             ÀÿÀ   ÿüÿû                G                                                       	                 —                                           Z                      /           )  " ÿü                2  &          &         –             
   ®0(¸lÚ&zÎ"v´$Èşj²d°ú		v

”nútÂ2d~²„Ò(bœä’àPœúB†
>rŒÈb„L”ZŒ    G ¸                         &                ş       ¼              ›       İ      	  ]      
 4        !                É       ? Ş  	     	    	  Ç  	    	  ¦  	  è  	 	  i  	 
 4N  	  ( 5  	  0 ™  	   Ğ  	  ~WooCommerce W o o C o m m e r c ehttp://woothemes.com h t t p : / / w o o t h e m e s . c o mJames Koster J a m e s   K o s t e rhttp://jameskoster.co.uk h t t p : / / j a m e s k o s t e r . c o . u kSIL OFL S I L   O F Lhttp://scripts.sil.org/cms/scripts/page.php?site_id=nrsi&id=OFL h t t p : / / s c r i p t s . s i l . o r g / c m s / s c r i p t s / p a g e . p h p ? s i t e _ i d = n r s i & i d = O F LVersion 1.0 V e r s i o n   1 . 0WooCommerce W o o C o m m e r c eWooCommerce W o o C o m m e r c eRegular R e g u l a rWooCommerce W o o C o m m e r c eFont generated by IcoMoon. F o n t   g e n e r a t e d   b y   I c o M o o n .                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            €  0OS/2&   ¼   `cmapæ@á,     \gasp     x   glyf¶z  €  0,head„  1¬   6hheaÄ  1ä   $hmtxö  2  loca§ù´  3$   maxp T º  3´    name•™Q  3Ô  ¨post     7|     ø   ™Ì   ™Ì  ë 3	                               @  æÀÿÀ @À @                                      @         à=æÿıÿÿ      à æ ÿıÿÿ ÿã B                ÿÿ             79               79               79    
  ÿÀ À  	     " ' D d  35##35#335#!35#!35##35##35#%!!7!"3!267>54&'.#3!2654&'>54&#".#"333MÍ€€ÿ €M€44Müšfü€!





!€	
		
	ıj/"w"/	_D'E0CššššššššššššššfşšfM	

ş‚				~
	üº	*#00#+	E`%E0   
  ÿÎ À     % * / 4 9 > [  764'#54&+"#2735##35#335#!35#!35##35##35#%!!7!"3!267>54&'.#$fPU#ñ33MÍ€€ÿ €M€44Müšfü€!





!€	
		
	2¡”¡?ššššššššššššššfşšfM	

ş‚				~
	   ÿÀ À  	     " ' ¨ ­ ² ·  35##35#335#!35#!35##35##35#%!!7!"3!!#";267>=4&'.+5!#";267>=4&'.+5!#";267>=4&'.+5!5!267>54&'.##53!#53!#53333MÍ€€ÿ €M€44Müšfü€!





!›şš5!





!´
				
L34!
		
!³	
		
	K35!	



	!³	
		
	Kşš±	
		
	ı5ÍÍÍÍÍş™ÌÌššššššššššššššfşšfM	

ş‚		M€		±

		

±

MM		±

		

±

MM		±

		

±

€M		~
	ı ÍÍÍÍÍÍ   
  ÿó   	     " ' D ^  3##3#!3#!3#!3##3##3#%!!7!"3!267>54&'.##3!267>54&'.+!333MM33ş€ç´3gı3Íı!





!ç
				
ı´L

	!æ	
			
ı3ÚşfšşfšşfšşfšşfšşfšşfšfıšfM	

ı‚				~
	ü³

		

~

ıM   ÿÀ À  ) 8 G  2>54.#"35".54>32#3?35#%5#76&'.5#3 j»‹PP‹»jj»‹PP‹»j]£zFFz£]]£zFFz£]¾SZ
	
Y3³€WZ	

	X3³@P‹»jj»‹PP‹»jj»‹P@Fz£]]£zFFz£]]£zFµ3`		aM³3`

]O³     ÿÀ À  # 8  35#35#735#2>54.#"35".54>32#Rì3³Wñ3³j»‹PP‹»jj»‹PP‹»j]£zFFz£]]£zFFz£]€3éO³3çM³ı@P‹»jj»‹PP‹»jj»‹P@Fz£]]£zFFz£]]£zF  
    s  ! % ) - 1 5 9 = A  !!72#!"&'.5467>3!3#3#3#3#3#3#3#3#³üšf	
		
	ü€!





!€ıh33Mç33şæMMÿ €³3&ı4ÌM

ı				ä		³ş  ş  ş  ş  ş  ş  ş  ş    ÿÀ À  ! 6  33535#5##32>54.#"34>32#".5ÍLÍÍLÍşóP‹»jj»‹PP‹»jj»‹P@Fz£]]£zFFz£]]£zFæLÍÍLÍÍ&j»‹PP‹»jj»‹PP‹»j]£zFFz£]]£zFFz£]      ÿÀ À  % :  %?'#";2>54.#"35".54>32#(ˆ"´3SöóW2(j»‹PP‹»jj»‹PP‹»j]£zFFz£]]£zFFz£]ó$½4YZ3şÍP‹»jj»‹PP‹»jj»‹P@Fz£]]£zFFz£]]£zF      ÿÀ À  % :  /7326=74.#"32>5##".54>32Í$½4YZ33P‹»jj»‹PP‹»jj»‹P@Fz£]]£zFFz£]]£zFèˆ"´3SöóW2(j»‹PP‹»jj»‹PP‹»j]£zFFz£]]£zFFz£]     ÿÀ À  % :  7'54&#"'%32>54.#"34>32#".53$½4YZ3şÍP‹»jj»‹PP‹»jj»‹P@Fz£]]£zFFz£]]£zF˜ˆ"´3SöóW2(j»‹PP‹»jj»‹PP‹»j]£zFFz£]]£zFFz£]     ÿÀ À  % :  7'32654&+7'"32>54.#2#".54>3Øˆ"´3SöóW2(j»‹PP‹»jj»‹PP‹»j]£zFFz£]]£zFFz£]$½5XZ33P‹»jj»‹PP‹»jj»‹P@Fz£]]£zFFz£]]£zF     ÿÀ À    "  %'!32654&#"%!!!!267>5Mÿ ³MššıÍ,  --  ,™üšfMü µs ³Mšg --  ,, æıM³ ü  «    	ÿÉ÷·   . ?  &"7'7'2764/'&"2764/&"2764/ğ'p'ş '&C,,Dş'p'à'&C,ıè,Dâ

şÇ

8	
ı

şÈ	


9o&'ş 'p'D,,Cü³&'à'p'D,ıè,C”
	şÈ

9

ışÇ


	8

   ÿÀ À 
    % 0 = J  3#35##3";2654&+3#%!!!!267>52#"&5463!2#"&5463fLLLÌL4çfffffüšfMü µşóş Mæ443ÿ  3ÿ  Ìıg™ü  «f   f    &  32654&#"!32654&#"!32654&#" 5&%55%&5¥6%%66%%6¦5%&55&%5À%55%%55%%55%%55%%55%%55%    é — &  32>54&'150.#"10>72 8K++K8 Mƒ^6QŠºhh»S5_L­)G66G)#@:8([;H;;H;['7:?#   ÿÀ À   & ; P  32654&#"332654&#"332654&#"!32>54.#"34>32#".5&´³ısP‹»jj»‹PP‹»jj»‹P@Fz£]]£zFFz£]]£zFÀj»‹PP‹»jj»‹PP‹»j]£zFFz£]]£zFFz£]   ÿÀ À   0  !5##32>54.#"34>32#".5ÚÍLş&P‹»jj»‹PP‹»jj»‹P@Fz£]]£zFFz£]]£zFÍş³M şój»‹PP‹»jj»‹PP‹»j]£zFFz£]]£zFFz£]     — [i4  ! 6  77'7'''267>.'.">2"&'.>7r6666ÖJLIJJ½Ä½JJILKJ¹À¹K-@¢©£@AB@@A¥¬¥A@@AB’6‘“7““7“‘6‘‘¢J¾ÅÀLNMMNLÀÅ¾JJJJJ,A@@AA¦®§BDCCDB§®¦A    ÿÀ À 	  ( =  7'!75!5!5!5%32>54.#"34>32#".5Z››ş³3š››M3şæş@P‹»jj»‹PP‹»jj»‹P@Fz£]]£zFFz£]]£zF¸]`B©/GH__>®1JMj»‹PP‹»jj»‹PP‹»j]£zFFz£]]£zFFz£]   ÿÀ À   2  ''32>54.#"34>32#".5Œ$$5ğ3şæP‹»jj»‹PP‹»jj»‹P@Fz£]]£zFFz£]]£zFËˆ"3çˆ2j»‹PP‹»jj»‹PP‹»j]£zFFz£]]£zFFz£]    ÿÀÀ  	  3  3#35#%32>54.#"34>32#".5ßMMMMş&Q‹ºjj»‹PP‹»jjº‹Q@Gy£]]£zFFz£]]£yG³şšfşgMM¦j»‹PP‹»jj»‹PP‹»j]£zFFz£]]£zFFz£]    M 3   '	 &$¤Nı©şò¢şŞ"™Mı²	    ÿÀ À   2 G  >54&#"3>3253535#%32>54.#"34>32#".5&EH^UR\3>=@@,.LLLLLş&P‹»jj»‹PP‹»jj»‹P@Fz£]]£zFFz£]]£zF\ZKU^VP:9@@6<
	M)\MMÀj»‹PP‹»jj»‹PP‹»j]£zFFz£]]£zFFz£]      ÿÀ À ' , M Z g  %267.'.5467>;54&'.#!"3!7#'#"3!267>54&'.#32654&#"!32654&#"˜	




!	
	ı³!





!M5ææşş!




 	
		
	ü‚4%&44&%4³4&%44%&4À
ş

	D
		

ı‚

	Mæ3şM 3	

ş
		Ë
	ı&44&%44%&44&%44%   ÿÀ À > C P ]  467>;54&'.#!"3!2673!267>54&'.#'#"!!#32654&#"!32654&#"€


!	
	ı³!





!M	 	
		
	ş!


ıÍ3Mææı€4%&44&%4³4&%44%&4so

	D
		

ı‚

		

Ë
	3	

ş	fıšæ3şMó&44&%44%&44&%44%      ÿÀ À  t ‰   #63267.'+>32666&30654'>7>7>54&'#.'57332&7546732654'54'>54&'&'.'532>54.#"34>32#".59	N!E!_9{3&-"("
uN$($8 ş&P‹»jj»‹PP‹»jj»‹P@Fz£]]£zFFz£]]£zF3Q	
1I 
CQ7
_()	Z

—+6	>	#97c69şj»‹PP‹»jj»‹PP‹»j]£zFFz£]]£zFFz£]     ÿÀ À H ]  6>32>7&67>>7.5467.'&'.7.'#"&''74>32#".5 &B)[/<%%<0Z)B&!**!&B)[0<%%<0Z)B&!**!š8aƒJJƒa88aƒJJƒa8R0Z)B&!**!&B)Z0<%%<0Z)B&!**!&B)Z0<%%<nJƒa88aƒJJƒa88aƒJ      s 9 F S  5.'.+"33!267>7!523!267>74&'.#!*!!532654&#"!32654&#"Í($
€(İ
ıÇ		Q	€ı,	æ€ıš™&%%&ş &%%&•
ıìN&KşÌêı½%%&&%%&&       & Z  	   0  %!5!!#53!!5!!552#!"&'.5467>3!šÿ  şÌ³³Müšfüšfüª()TÚLLLLfş3ÍÍ€€Mıs!!Œ       s  	    5  %#3+3+3+53!!72#!"&'.5467>3!3MM³MM³MM³MM™üšf	
		
	ü€!





!€¦´şLMş³ ş ÍÍ€ı4ÌM

ı				ä		   ÿÚ ¦ 	  %- bÿ <şÄşÄ<ÿ b¦ş¿3ùş¡¥¥_ù3    ÿÜ © 
   %-''%7 ş <<<< şv
À.îî.À
v©ş¿4ùş¡¥¥_ù4Aì'·şızz·'ì     ZÿÄ¦¼    	!	! şZLşZşZ¦¦ü´¼ş^¢ıªş^¢    ÿÀ À 
     4&+"3%4&+"34&+"3 #"$šşM#"#šşM$"#šÚüæ³ü3ÍşfıÍ3  ÿÕA  3  2?>7>&'.>&'.>7;C&.¹=º@—™’;BD?A@£«¥A=5„‰‚343645„‰ƒ333659;’™—@º=¹.&C;A¥«£@A?DB=46343‚‰„556333ƒ‰„5    ÿÀ À  + @ U  2?>767>4&'."62&'.732>54.#"34>32#".5›1"F)F9ˆ1ILI,(q')'(q)'(ş9P‹»jj»‹PP‹»jj»‹P@Fz£]]£zFFz£]]£zFu1ˆ9F)F"1ILI,')'q((')q(‰j»‹PP‹»jj»‹PP‹»j]£zFFz£]]£zFFz£]     ÿÀ À ! & 1  4&#""!54.'>5%!!!!267>5d3>>2KP?.EVP+OüšfMü µì&TH.R*.F%! 2?+ÔıM³ ü  «      ÿÀ À     !!267>5#'!!7!!267>5³üÍ9
M€ıæMü€9aı¬M
6ßyı³Mæü€
!0    ÿÀ À    !!#!!³üšfMüK ÀıM³ üU        ÿèŞ‘    	&"!"3!764'5!	!5Şş_"F"ş› .- f"F"¡""üo³Íş3şM|,,í9(şó'8â++},Y+Xš şfşg ™     /ÿÀØ    	33!2653>'&"#!	!#«ş„,,í9('8â++şƒ,Y+Xšÿ š™ÿ ™ş^!F"ş› .- f"F!¢""üo³Íş3şM        f  	  +  !5!!5!!!7!"3!267>54&'.#æ4şÌgÍş3füšfü€!





!€	
		
	MMMš€€gıL´L		ı4

Ì	
      s  & 3 c p }  %!#"3!267>=#32654&#"34632#"&53267>7.'.#"32654&'>774632#"&54632#"&5³üš!





!€	
	MM-  --  -'ş|	$%	2K9'-BX8)*@_D)
-  -&8K2ş´Z		ı				 --  -- 0?ELH:"#Xii"( -- $
(^WE#şg     s   ,  !27%!"'%!"3!267>54&'.#³üšƒ8ƒüÄş˜&ş˜Tü€!





!€	
		
	ğıj–ş~‚6ş—iM		ı				ä

   )ÿâÑÀ    64'#4&#!"#273!	!3U|,,î7(şó(8á,,|,X,Xš şfşf š¡"F"e .- şš"F"ş_""‘şMş3Í³  "ÿï —    275!2654&#!5.%!	!"¡"F"e .- şš"F"ş_""‘şMş3Í³kş„,,î8((8á,,ş„,X,Xšÿ š™ÿš  ÿüÿÀÀ $ .  4&'.#!"3!267>5!!!-!"°L

ü€!
		
!€

Lü™gıfÍ!şßş™24så9/
		

ü‚

		

%9åfş€³³€42       ô€ <  >7''.7>6?6&'.'&>7šK]€—MMyK^€—M+L@3o

Ç3
g<MZ3]µ™qZ‘]]µ™pMNyL^€–MNyK(6C%B3	Ç>,M@/[‘]]µ™p[‘]      & Z  ! 2  "3!26=4&#!"3!26=4&#!"3!26=4&#!0 ü` ü` ü`Z		ş™şš		     ÿÀ À  ) .  "32>54.#".54>32#!5# j»‹PP‹»jj»‹PP‹»j]£zFFz£]]£zFFz£]óçÍÀP‹»jj»‹PP‹»jj»‹Pü@Fz£]]£zFFz£]]£zFæLL  
  ÿÌ À  	     " ' D [  35##35#335#!35#!35##35##35#%!!7!"3!267>54&'.#'&"#";?64'333MÍ€€ÿ €M€44Müšfü€!





!€	
		
	şù¡“¡ššššššššššššššfşšfM	

ş‚				~
	üŞgOV#     ÿÀà¾    3?3!.5#!5#76&'<ÁÒ7Òxş\x3Ïx¤ËÒFãDã´¤xş<Üºş\xà=     2ÿÔÎ©    3?3!.5#!5#76&'BÂÒ7Óxş[†3Ïx¥ÌÒ/ãDã´¤xwÜ»ş[xà>     &ÿÉş· 7 f  "&/&4?>328103261810654&/&4?>32#'27>54&/&"#"&'.#"û,$$T--			$$T-,ıx-ª3ˆ
		3T				şö"!
	T7p$f$T			#f$Up--ıxÀpˆ		pT			"şõ				T2         ~ 2 `  %!54654654>7>7>7.'.'.54>32%!54&'./7>54&#"0 ü -GU)3U@8Q26Q7$Bf7(QA)ü<ˆ`UI…D @^g74	h<Zm 	
	6F. 
s\4T; )BT+JpP"
%8*’<V.,4=P_C-x/M^
-);D		  ÿÀÀ  )  32>54.#"34>32#".5Q‹ºjj»‹PP‹»jjº‹Q@Gy£]]£zFFz£]]£yGÀj»‹PP‹»jj»‹PP‹»j]£zFFz£]]£zFFz£]        ~ 2  %!54654654>7>7>7.'.'.54>32 ü -GU)3U@8Q26Q7$Bf7(QA) 	
	6F. 
s\4T; )BT+JpP"
%8*’     &ÿÉş· 7  "&/&4?>328103261810654&/&4?>32#û,$$T--			$$T-,ıx-7p$f$T			#f$Up--ıx    ÿÀÀ   32>54.#"Q‹ºjj»‹PP‹»jjº‹QÀj»‹PP‹»jj»‹PP‹»j    ÿÁî¿   - C | ’  4&#"326534&#"3265"3!5!2654&#!#"&'.'0&5461>32'#".'#"&'.54632>324>32#"&'.'0&5461>32d	
Ê		ı_3HH3T|3HH3ı15.',(¶! 	

€15-'-'.K8M.K8M©H4ıv4H||H4Š4HşZ)r	-82G'y.BIL	0;34;1u*u	.[15>21.AJ;y)r	-82G'       s  2  !5>54&'5!7!"2#3!26="&546354&#1±EZZEüEZZEb/ü@B]]BÀB]]B$ƒzNNzƒƒzNNzƒOõ]BB]õõ]BB]õ     – Vj*  ) 8 G L  ."267>4&'"&'.467>2"77'7'7'72674&%3#jK½Ä½KKKKKK½Ä½KKKKK-B¥¬¥BABBAB¥¬¥BABBAıâ:„„6$$8$~~$=ƒş¸00*KKKKK½Ä½KKKKKK½Ä½KıYABBAB¥¬¥BABBAB¥¬¥B˜;6%$59$%=Úş       ÿÀ À  ! .  "32>54.#4>32.5"&'# j»‹PP‹»jj»ŠQQŠ»jş@Fz£]R”:ı‰18ÀR”:w18Fz£]ÀP‹»jj»‹PP‹»jj»‹Pş ]£zF81ı‰:”Rş@81w:”R]£zF    ÿÁè¨     '&"%64'7''7èxDıYV@§üƒ#€2Ñ’x’x/yıYşÀU§Dı/"2’y“x      >şB  3 H U _  3#5!!>7!5!54&'.#!"3!267>="32>54.#4632#"&5%!.5467!±©©ÍüÌ³ş4$üŞ&&$$x3[C''C[34ZD&'C[3½oNOooONoş—şüÓIIş³¤,xB
m
ı—

H
!'C[34ZD&&DZ43[C'øNooNOooO$I

	
        zÜÿ_<õ      Ò2¥ˆ    Ò2¥ˆÿüÿÀÀ             ÀÿÀ   ÿüÿû                G                                                       	                 —                                           Z                      /           )  " ÿü                2  &          &         –             
   ®0(¸lÚ&zÎ"v´$Èşj²d°ú		v

”nútÂ2d~²„Ò(bœä’àPœúB†
>rŒÈb„L”ZŒ    G ¸                         &                ş       ¼              ›       İ      	  ]      
 4        !                É       ? Ş  	     	    	  Ç  	    	  ¦  	  è  	 	  i  	 
 4N  	  ( 5  	  0 ™  	   Ğ  	  ~WooCommerce W o o C o m m e r c ehttp://woothemes.com h t t p : / / w o o t h e m e s . c o mJames Koster J a m e s   K o s t e rhttp://jameskoster.co.uk h t t p : / / j a m e s k o s t e r . c o . u kSIL OFL S I L   O F Lhttp://scripts.sil.org/cms/scripts/page.php?site_id=nrsi&id=OFL h t t p : / / s c r i p t s . s i l . o r g / c m s / s c r i p t s / p a g e . p h p ? s i t e _ i d = n r s i & i d = O F LVersion 1.0 V e r s i o n   1 . 0WooCommerce W o o C o m m e r c eWooCommerce W o o C o m m e r c eRegular R e g u l a rWooCommerce W o o C o m m e r c eFont generated by IcoMoon. F o n t   g e n e r a t e d   b y   I c o M o o n .                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   P8  œ7                       LP                       ÿÜz                   W o o C o m m e r c e    R e g u l a r    V e r s i o n   1 . 0    W o o C o m m e r c e          €  0OS/2&   ¼   `cmapæ@á,     \gasp     x   glyf¶z  €  0,head„  1¬   6hheaÄ  1ä   $hmtxö  2  loca§ù´  3$   maxp T º  3´    name•™Q  3Ô  ¨post     7|     ø   ™Ì   ™Ì  ë 3	                               @  æÀÿÀ @À @                                      @         à=æÿıÿÿ      à æ ÿıÿÿ ÿã B                ÿÿ             79               79               79    
  ÿÀ À  	     " ' D d  35##35#335#!35#!35##35##35#%!!7!"3!267>54&'.#3!2654&'>54&#".#"333MÍ€€ÿ €M€44Müšfü€!





!€	
		
	ıj/"w"/	_D'E0CššššššššššššššfşšfM	

ş‚				~
	üº	*#00#+	E`%E0   
  ÿÎ À     % * / 4 9 > [  764'#54&+"#2735##35#335#!35#!35##35##35#%!!7!"3!267>54&'.#$fPU#ñ33MÍ€€ÿ €M€44Müšfü€!





!€	
		
	2¡”¡?ššššššššššššššfşšfM	

ş‚				~
	   ÿÀ À  	     " ' ¨ ­ ² ·  35##35#335#!35#!35##35##35#%!!7!"3!!#";267>=4&'.+5!#";267>=4&'.+5!#";267>=4&'.+5!5!267>54&'.##53!#53!#53333MÍ€€ÿ €M€44Müšfü€!





!›şš5!





!´
				
L34!
		
!³	
		
	K35!	



	!³	
		
	Kşš±	
		
	ı5ÍÍÍÍÍş™ÌÌššššššššššššššfşšfM	

ş‚		M€		±

		

±

MM		±

		

±

MM		±

		

±

€M		~
	ı ÍÍÍÍÍÍ   
  ÿó   	     " ' D ^  3##3#!3#!3#!3##3##3#%!!7!"3!267>54&'.##3!267>54&'.+!333MM33ş€ç´3gı3Íı!





!ç
				
ı´L

	!æ	
			
ı3ÚşfšşfšşfšşfšşfšşfšşfšfıšfM	

ı‚				~
	ü³

		

~

ıM   ÿÀ À  ) 8 G  2>54.#"35".54>32#3?35#%5#76&'.5#3 j»‹PP‹»jj»‹PP‹»j]£zFFz£]]£zFFz£]¾SZ
	
Y3³€WZ	

	X3³@P‹»jj»‹PP‹»jj»‹P@Fz£]]£zFFz£]]£zFµ3`		aM³3`

]O³     ÿÀ À  # 8  35#35#735#2>54.#"35".54>32#Rì3³Wñ3³j»‹PP‹»jj»‹PP‹»j]£zFFz£]]£zFFz£]€3éO³3çM³ı@P‹»jj»‹PP‹»jj»‹P@Fz£]]£zFFz£]]£zF  
    s  ! % ) - 1 5 9 = A  !!72#!"&'.5467>3!3#3#3#3#3#3#3#3#³üšf	
		
	ü€!





!€ıh33Mç33şæMMÿ €³3&ı4ÌM

ı				ä		³ş  ş  ş  ş  ş  ş  ş  ş    ÿÀ À  ! 6  33535#5##32>54.#"34>32#".5ÍLÍÍLÍşóP‹»jj»‹PP‹»jj»‹P@Fz£]]£zFFz£]]£zFæLÍÍLÍÍ&j»‹PP‹»jj»‹PP‹»j]£zFFz£]]£zFFz£]      ÿÀ À  % :  %?'#";2>54.#"35".54>32#(ˆ"´3SöóW2(j»‹PP‹»jj»‹PP‹»j]£zFFz£]]£zFFz£]ó$½4YZ3şÍP‹»jj»‹PP‹»jj»‹P@Fz£]]£zFFz£]]£zF      ÿÀ À  % :  /7326=74.#"32>5##".54>32Í$½4YZ33P‹»jj»‹PP‹»jj»‹P@Fz£]]£zFFz£]]£zFèˆ"´3SöóW2(j»‹PP‹»jj»‹PP‹»j]£zFFz£]]£zFFz£]     ÿÀ À  % :  7'54&#"'%32>54.#"34>32#".53$½4YZ3şÍP‹»jj»‹PP‹»jj»‹P@Fz£]]£zFFz£]]£zF˜ˆ"´3SöóW2(j»‹PP‹»jj»‹PP‹»j]£zFFz£]]£zFFz£]     ÿÀ À  % :  7'32654&+7'"32>54.#2#".54>3Øˆ"´3SöóW2(j»‹PP‹»jj»‹PP‹»j]£zFFz£]]£zFFz£]$½5XZ33P‹»jj»‹PP‹»jj»‹P@Fz£]]£zFFz£]]£zF     ÿÀ À    "  %'!32654&#"%!!!!267>5Mÿ ³MššıÍ,  --  ,™üšfMü µs ³Mšg --  ,, æıM³ ü  «    	ÿÉ÷·   . ?  &"7'7'2764/'&"2764/&"2764/ğ'p'ş '&C,,Dş'p'à'&C,ıè,Dâ

şÇ

8	
ı

şÈ	


9o&'ş 'p'D,,Cü³&'à'p'D,ıè,C”
	şÈ

9

ışÇ


	8

   ÿÀ À 
    % 0 = J  3#35##3";2654&+3#%!!!!267>52#"&5463!2#"&5463fLLLÌL4çfffffüšfMü µşóş Mæ443ÿ  3ÿ  Ìıg™ü  «f   f    &  32654&#"!32654&#"!32654&#" 5&%55%&5¥6%%66%%6¦5%&55&%5À%55%%55%%55%%55%%55%%55%    é — &  32>54&'150.#"10>72 8K++K8 Mƒ^6QŠºhh»S5_L­)G66G)#@:8([;H;;H;['7:?#   ÿÀ À   & ; P  32654&#"332654&#"332654&#"!32>54.#"34>32#".5&´³ısP‹»jj»‹PP‹»jj»‹P@Fz£]]£zFFz£]]£zFÀj»‹PP‹»jj»‹PP‹»j]£zFFz£]]£zFFz£]   ÿÀ À   0  !5##32>54.#"34>32#".5ÚÍLş&P‹»jj»‹PP‹»jj»‹P@Fz£]]£zFFz£]]£zFÍş³M şój»‹PP‹»jj»‹PP‹»j]£zFFz£]]£zFFz£]     — [i4  ! 6  77'7'''267>.'.">2"&'.>7r6666ÖJLIJJ½Ä½JJILKJ¹À¹K-@¢©£@AB@@A¥¬¥A@@AB’6‘“7““7“‘6‘‘¢J¾ÅÀLNMMNLÀÅ¾JJJJJ,A@@AA¦®§BDCCDB§®¦A    ÿÀ À 	  ( =  7'!75!5!5!5%32>54.#"34>32#".5Z››ş³3š››M3şæş@P‹»jj»‹PP‹»jj»‹P@Fz£]]£zFFz£]]£zF¸]`B©/GH__>®1JMj»‹PP‹»jj»‹PP‹»j]£zFFz£]]£zFFz£]   ÿÀ À   2  ''32>54.#"34>32#".5Œ$$5ğ3şæP‹»jj»‹PP‹»jj»‹P@Fz£]]£zFFz£]]£zFËˆ"3çˆ2j»‹PP‹»jj»‹PP‹»j]£zFFz£]]£zFFz£]    ÿÀÀ  	  3  3#35#%32>54.#"34>32#".5ßMMMMş&Q‹ºjj»‹PP‹»jjº‹Q@Gy£]]£zFFz£]]£yG³şšfşgMM¦j»‹PP‹»jj»‹PP‹»j]£zFFz£]]£zFFz£]    M 3   '	 &$¤Nı©şò¢şŞ"™Mı²	    ÿÀ À   2 G  >54&#"3>3253535#%32>54.#"34>32#".5&EH^UR\3>=@@,.LLLLLş&P‹»jj»‹PP‹»jj»‹P@Fz£]]£zFFz£]]£zF\ZKU^VP:9@@6<
	M)\MMÀj»‹PP‹»jj»‹PP‹»j]£zFFz£]]£zFFz£]      ÿÀ À ' , M Z g  %267.'.5467>;54&'.#!"3!7#'#"3!267>54&'.#32654&#"!32654&#"˜	




!	
	ı³!





!M5ææşş!




 	
		
	ü‚4%&44&%4³4&%44%&4À
ş

	D
		

ı‚

	Mæ3şM 3	

ş
		Ë
	ı&44&%44%&44&%44%   ÿÀ À > C P ]  467>;54&'.#!"3!2673!267>54&'.#'#"!!#32654&#"!32654&#"€


!	
	ı³!





!M	 	
		
	ş!


ıÍ3Mææı€4%&44&%4³4&%44%&4so

	D
		

ı‚

		

Ë
	3	

ş	fıšæ3şMó&44&%44%&44&%44%      ÿÀ À  t ‰   #63267.'+>32666&30654'>7>7>54&'#.'57332&7546732654'54'>54&'&'.'532>54.#"34>32#".59	N!E!_9{3&-"("
uN$($8 ş&P‹»jj»‹PP‹»jj»‹P@Fz£]]£zFFz£]]£zF3Q	
1I 
CQ7
_()	Z

—+6	>	#97c69şj»‹PP‹»jj»‹PP‹»j]£zFFz£]]£zFFz£]     ÿÀ À H ]  6>32>7&67>>7.5467.'&'.7.'#"&''74>32#".5 &B)[/<%%<0Z)B&!**!&B)[0<%%<0Z)B&!**!š8aƒJJƒa88aƒJJƒa8R0Z)B&!**!&B)Z0<%%<0Z)B&!**!&B)Z0<%%<nJƒa88aƒJJƒa88aƒJ      s 9 F S  5.'.+"33!267>7!523!267>74&'.#!*!!532654&#"!32654&#"Í($
€(İ
ıÇ		Q	€ı,	æ€ıš™&%%&ş &%%&•
ıìN&KşÌêı½%%&&%%&&       & Z  	   0  %!5!!#53!!5!!552#!"&'.5467>3!šÿ  şÌ³³Müšfüšfüª()TÚLLLLfş3ÍÍ€€Mıs!!Œ       s  	    5  %#3+3+3+53!!72#!"&'.5467>3!3MM³MM³MM³MM™üšf	
		
	ü€!





!€¦´şLMş³ ş ÍÍ€ı4ÌM

ı				ä		   ÿÚ ¦ 	  %- bÿ <şÄşÄ<ÿ b¦ş¿3ùş¡¥¥_ù3    ÿÜ © 
   %-''%7 ş <<<< şv
À.îî.À
v©ş¿4ùş¡¥¥_ù4Aì'·şızz·'ì     ZÿÄ¦¼    	!	! şZLşZşZ¦¦ü´¼ş^¢ıªş^¢    ÿÀ À 
     4&+"3%4&+"34&+"3 #"$šşM#"#šşM$"#šÚüæ³ü3ÍşfıÍ3  ÿÕA  3  2?>7>&'.>&'.>7;C&.¹=º@—™’;BD?A@£«¥A=5„‰‚343645„‰ƒ333659;’™—@º=¹.&C;A¥«£@A?DB=46343‚‰„556333ƒ‰„5    ÿÀ À  + @ U  2?>767>4&'."62&'.732>54.#"34>32#".5›1"F)F9ˆ1ILI,(q')'(q)'(ş9P‹»jj»‹PP‹»jj»‹P@Fz£]]£zFFz£]]£zFu1ˆ9F)F"1ILI,')'q((')q(‰j»‹PP‹»jj»‹PP‹»j]£zFFz£]]£zFFz£]     ÿÀ À ! & 1  4&#""!54.'>5%!!!!267>5d3>>2KP?.EVP+OüšfMü µì&TH.R*.F%! 2?+ÔıM³ ü  «      ÿÀ À     !!267>5#'!!7!!267>5³üÍ9
M€ıæMü€9aı¬M
6ßyı³Mæü€
!0    ÿÀ À    !!#!!³üšfMüK ÀıM³ üU        ÿèŞ‘    	&"!"3!764'5!	!5Şş_"F"ş› .- f"F"¡""üo³Íş3şM|,,í9(şó'8â++},Y+Xš şfşg ™     /ÿÀØ    	33!2653>'&"#!	!#«ş„,,í9('8â++şƒ,Y+Xšÿ š™ÿ ™ş^!F"ş› .- f"F!¢""üo³Íş3şM        f  	  +  !5!!5!!!7!"3!267>54&'.#æ4şÌgÍş3füšfü€!





!€	
		
	MMMš€€gıL´L		ı4

Ì	
      s  & 3 c p }  %!#"3!267>=#32654&#"34632#"&53267>7.'.#"32654&'>774632#"&54632#"&5³üš!





!€	
	MM-  --  -'ş|	$%	2K9'-BX8)*@_D)
-  -&8K2ş´Z		ı				 --  -- 0?ELH:"#Xii"( -- $
(^WE#şg     s   ,  !27%!"'%!"3!267>54&'.#³üšƒ8ƒüÄş˜&ş˜Tü€!





!€	
		
	ğıj–ş~‚6ş—iM		ı				ä

   )ÿâÑÀ    64'#4&#!"#273!	!3U|,,î7(şó(8á,,|,X,Xš şfşf š¡"F"e .- şš"F"ş_""‘şMş3Í³  "ÿï —    275!2654&#!5.%!	!"¡"F"e .- şš"F"ş_""‘şMş3Í³kş„,,î8((8á,,ş„,X,Xšÿ š™ÿš  ÿüÿÀÀ $ .  4&'.#!"3!267>5!!!-!"°L

ü€!
		
!€

Lü™gıfÍ!şßş™24så9/
		

ü‚

		

%9åfş€³³€42       ô€ <  >7''.7>6?6&'.'&>7šK]€—MMyK^€—M+L@3o

Ç3
g<MZ3]µ™qZ‘]]µ™pMNyL^€–MNyK(6C%B3	Ç>,M@/[‘]]µ™p[‘]      & Z  ! 2  "3!26=4&#!"3!26=4&#!"3!26=4&#!0 ü` ü` ü`Z		ş™şš		     ÿÀ À  ) .  "32>54.#".54>32#!5# j»‹PP‹»jj»‹PP‹»j]£zFFz£]]£zFFz£]óçÍÀP‹»jj»‹PP‹»jj»‹Pü@Fz£]]£zFFz£]]£zFæLL  
  ÿÌ À  	     " ' D [  35##35#335#!35#!35##35##35#%!!7!"3!267>54&'.#'&"#";?64'333MÍ€€ÿ €M€44Müšfü€!





!€	
		
	şù¡“¡ššššššššššššššfşšfM	

ş‚				~
	üŞgOV#     ÿÀà¾    3?3!.5#!5#76&'<ÁÒ7Òxş\x3Ïx¤ËÒFãDã´¤xş<Üºş\xà=     2ÿÔÎ©    3?3!.5#!5#76&'BÂÒ7Óxş[†3Ïx¥ÌÒ/ãDã´¤xwÜ»ş[xà>     &ÿÉş· 7 f  "&/&4?>328103261810654&/&4?>32#'27>54&/&"#"&'.#"û,$$T--			$$T-,ıx-ª3ˆ
		3T				şö"!
	T7p$f$T			#f$Up--ıxÀpˆ		pT			"şõ				T2         ~ 2 `  %!54654654>7>7>7.'.'.54>32%!54&'./7>54&#"0 ü -GU)3U@8Q26Q7$Bf7(QA)ü<ˆ`UI…D @^g74	h<Zm 	
	6F. 
s\4T; )BT+JpP"
%8*’<V.,4=P_C-x/M^
-);D		  ÿÀÀ  )  32>54.#"34>32#".5Q‹ºjj»‹PP‹»jjº‹Q@Gy£]]£zFFz£]]£yGÀj»‹PP‹»jj»‹PP‹»j]£zFFz£]]£zFFz£]        ~ 2  %!54654654>7>7>7.'.'.54>32 ü -GU)3U@8Q26Q7$Bf7(QA) 	
	6F. 
s\4T; )BT+JpP"
%8*’     &ÿÉş· 7  "&/&4?>328103261810654&/&4?>32#û,$$T--			$$T-,ıx-7p$f$T			#f$Up--ıx    ÿÀÀ   32>54.#"Q‹ºjj»‹PP‹»jjº‹QÀj»‹PP‹»jj»‹PP‹»j    ÿÁî¿   - C | ’  4&#"326534&#"3265"3!5!2654&#!#"&'.'0&5461>32'#".'#"&'.54632>324>32#"&'.'0&5461>32d	
Ê		ı_3HH3T|3HH3ı15.',(¶! 	

€15-'-'.K8M.K8M©H4ıv4H||H4Š4HşZ)r	-82G'y.BIL	0;34;1u*u	.[15>21.AJ;y)r	-82G'       s  2  !5>54&'5!7!"2#3!26="&546354&#1±EZZEüEZZEb/ü@B]]BÀB]]B$ƒzNNzƒƒzNNzƒOõ]BB]õõ]BB]õ     – Vj*  ) 8 G L  ."267>4&'"&'.467>2"77'7'7'72674&%3#jK½Ä½KKKKKK½Ä½KKKKK-B¥¬¥BABBAB¥¬¥BABBAıâ:„„6$$8$~~$=ƒş¸00*KKKKK½Ä½KKKKKK½Ä½KıYABBAB¥¬¥BABBAB¥¬¥B˜;6%$59$%=Úş       ÿÀ À  ! .  "32>54.#4>32.5"&'# j»‹PP‹»jj»ŠQQŠ»jş@Fz£]R”:ı‰18ÀR”:w18Fz£]ÀP‹»jj»‹PP‹»jj»‹Pş ]£zF81ı‰:”Rş@81w:”R]£zF    ÿÁè¨     '&"%64'7''7èxDıYV@§üƒ#€2Ñ’x’x/yıYşÀU§Dı/"2’y“x      >şB  3 H U _  3#5!!>7!5!54&'.#!"3!267>="32>54.#4632#"&5%!.5467!±©©ÍüÌ³ş4$üŞ&&$$x3[C''C[34ZD&'C[3½oNOooONoş—şüÓIIş³¤,xB
m
ı—

H
!'C[34ZD&&DZ43[C'øNooNOooO$I

	
        zÜÿ_<õ      Ò2¥ˆ    Ò2¥ˆÿüÿÀÀ             ÀÿÀ   ÿüÿû                G                                                       	                 —                                           Z                      /           )  " ÿü                2  &          &         –             
   ®0(¸lÚ&zÎ"v´$Èşj²d°ú		v

”nútÂ2d~²„Ò(bœä’àPœúB†
>rŒÈb„L”ZŒ    G ¸                         &                ş       ¼              ›       İ      	  ]      
 4        !                É       ? Ş  	     	    	  Ç  	    	  ¦  	  è  	 	  i  	 
 4N  	  ( 5  	  0 ™  	   Ğ  	  ~WooCommerce W o o C o m m e r c ehttp://woothemes.com h t t p : / / w o o t h e m e s . c o mJames Koster J a m e s   K o s t e rhttp://jameskoster.co.uk h t t p : / / j a m e s k o s t e r . c o . u kSIL OFL S I L   O F Lhttp://scripts.sil.org/cms/scripts/page.php?site_id=nrsi&id=OFL h t t p : / / s c r i p t s . s i l . o r g / c m s / s c r i p t s / p a g e . p h p ? s i t e _ i d = n r s i & i d = O F LVersion 1.0 V e r s i o n   1 . 0WooCommerce W o o C o m m e r c eWooCommerce W o o C o m m e r c eRegular R e g u l a rWooCommerce W o o C o m m e r c eFont generated by IcoMoon. F o n t   g e n e r a t e d   b y   I c o M o o n .                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               .woocommerce #content div.product .woocommerce-tabs ul.tabs:after,.woocommerce #content div.product .woocommerce-tabs ul.tabs:before,.woocommerce #content div.product div.thumbnails:after,.woocommerce #content div.product div.thumbnails:before,.woocommerce .col2-set:after,.woocommerce .col2-set:before,.woocommerce div.product .woocommerce-tabs ul.tabs:after,.woocommerce div.product .woocommerce-tabs ul.tabs:before,.woocommerce div.product div.thumbnails:after,.woocommerce div.product div.thumbnails:before,.woocommerce-page #content div.product .woocommerce-tabs ul.tabs:after,.woocommerce-page #content div.product .woocommerce-tabs ul.tabs:before,.woocommerce-page #content div.product div.thumbnails:after,.woocommerce-page #content div.product div.thumbnails:before,.woocommerce-page .col2-set:after,.woocommerce-page .col2-set:before,.woocommerce-page div.product .woocommerce-tabs ul.tabs:after,.woocommerce-page div.product .woocommerce-tabs ul.tabs:before,.woocommerce-page div.product div.thumbnails:after,.woocommerce-page div.product div.thumbnails:before{content:" ";display:table}.woocommerce #content div.product .woocommerce-tabs,.woocommerce #content div.product .woocommerce-tabs ul.tabs:after,.woocommerce #content div.product div.thumbnails a.first,.woocommerce #content div.product div.thumbnails:after,.woocommerce .cart-collaterals:after,.woocommerce .col2-set:after,.woocommerce .woocommerce-pagination ul.page-numbers:after,.woocommerce div.product .woocommerce-tabs,.woocommerce div.product .woocommerce-tabs ul.tabs:after,.woocommerce div.product div.thumbnails a.first,.woocommerce div.product div.thumbnails:after,.woocommerce ul.products,.woocommerce ul.products li.first,.woocommerce ul.products:after,.woocommerce-page #content div.product .woocommerce-tabs,.woocommerce-page #content div.product .woocommerce-tabs ul.tabs:after,.woocommerce-page #content div.product div.thumbnails a.first,.woocommerce-page #content div.product div.thumbnails:after,.woocommerce-page .cart-collaterals:after,.woocommerce-page .col2-set:after,.woocommerce-page .woocommerce-pagination ul.page-numbers:after,.woocommerce-page div.product .woocommerce-tabs,.woocommerce-page div.product .woocommerce-tabs ul.tabs:after,.woocommerce-page div.product div.thumbnails a.first,.woocommerce-page div.product div.thumbnails:after,.woocommerce-page ul.products,.woocommerce-page ul.products li.first,.woocommerce-page ul.products:after{clear:both}.woocommerce .col2-set,.woocommerce-page .col2-set{width:100%}.woocommerce .col2-set .col-1,.woocommerce-page .col2-set .col-1{float:left;width:48%}.woocommerce .col2-set .col-2,.woocommerce-page .col2-set .col-2{float:right;width:48%}.woocommerce img,.woocommerce-page img{height:auto;max-width:100%}.woocommerce #content div.product div.images,.woocommerce div.product div.images,.woocommerce-page #content div.product div.images,.woocommerce-page div.product div.images{float:left;width:48%}.woocommerce #content div.product div.thumbnails a,.woocommerce div.product div.thumbnails a,.woocommerce-page #content div.product div.thumbnails a,.woocommerce-page div.product div.thumbnails a{float:left;width:30.75%;margin-right:3.8%;margin-bottom:1em}.woocommerce #content div.product div.thumbnails a.last,.woocommerce div.product div.thumbnails a.last,.woocommerce-page #content div.product div.thumbnails a.last,.woocommerce-page div.product div.thumbnails a.last{margin-right:0}.woocommerce #content div.product div.thumbnails.columns-1 a,.woocommerce div.product div.thumbnails.columns-1 a,.woocommerce-page #content div.product div.thumbnails.columns-1 a,.woocommerce-page div.product div.thumbnails.columns-1 a{width:100%;margin-right:0;float:none}.woocommerce #content div.product div.thumbnails.columns-2 a,.woocommerce div.product div.thumbnails.columns-2 a,.woocommerce-page #content div.product div.thumbnails.columns-2 a,.woocommerce-page div.product div.thumbnails.columns-2 a{width:48%}.woocommerce #content div.product div.thumbnails.columns-4 a,.woocommerce div.product div.thumbnails.columns-4 a,.woocommerce-page #content div.product div.thumbnails.columns-4 a,.woocommerce-page div.product div.thumbnails.columns-4 a{width:22.05%}.woocommerce #content div.product div.thumbnails.columns-5 a,.woocommerce div.product div.thumbnails.columns-5 a,.woocommerce-page #content div.product div.thumbnails.columns-5 a,.woocommerce-page div.product div.thumbnails.columns-5 a{width:16.9%}.woocommerce #content div.product div.summary,.woocommerce div.product div.summary,.woocommerce-page #content div.product div.summary,.woocommerce-page div.product div.summary{float:right;width:48%}.woocommerce #content div.product .woocommerce-tabs ul.tabs li,.woocommerce div.product .woocommerce-tabs ul.tabs li,.woocommerce-page #content div.product .woocommerce-tabs ul.tabs li,.woocommerce-page div.product .woocommerce-tabs ul.tabs li{display:inline-block}.woocommerce #content div.product #reviews .comment:after,.woocommerce #content div.product #reviews .comment:before,.woocommerce .woocommerce-pagination ul.page-numbers:after,.woocommerce .woocommerce-pagination ul.page-numbers:before,.woocommerce div.product #reviews .comment:after,.woocommerce div.product #reviews .comment:before,.woocommerce ul.products:after,.woocommerce ul.products:before,.woocommerce-page #content div.product #reviews .comment:after,.woocommerce-page #content div.product #reviews .comment:before,.woocommerce-page .woocommerce-pagination ul.page-numbers:after,.woocommerce-page .woocommerce-pagination ul.page-numbers:before,.woocommerce-page div.product #reviews .comment:after,.woocommerce-page div.product #reviews .comment:before,.woocommerce-page ul.products:after,.woocommerce-page ul.products:before{content:" ";display:table}.woocommerce #content div.product #reviews .comment:after,.woocommerce div.product #reviews .comment:after,.woocommerce-page #content div.product #reviews .comment:after,.woocommerce-page div.product #reviews .comment:after{clear:both}.woocommerce #content div.product #reviews .comment img,.woocommerce div.product #reviews .comment img,.woocommerce-page #content div.product #reviews .comment img,.woocommerce-page div.product #reviews .comment img{float:right;height:auto}.woocommerce ul.products li.product,.woocommerce-page ul.products li.product{float:left;margin:0 3.8% 2.992em 0;padding:0;position:relative;width:22.05%}.woocommerce ul.products li.last,.woocommerce-page ul.products li.last{margin-right:0}.woocommerce-page.columns-1 ul.products li.product,.woocommerce.columns-1 ul.products li.product{width:100%;margin-right:0}.woocommerce-page.columns-2 ul.products li.product,.woocommerce.columns-2 ul.products li.product{width:48%}.woocommerce-page.columns-3 ul.products li.product,.woocommerce.columns-3 ul.products li.product{width:30.75%}.woocommerce-page.columns-5 ul.products li.product,.woocommerce.columns-5 ul.products li.product{width:16.95%}.woocommerce-page.columns-6 ul.products li.product,.woocommerce.columns-6 ul.products li.product{width:13.5%}.woocommerce .woocommerce-result-count,.woocommerce-page .woocommerce-result-count{float:left}.woocommerce .woocommerce-ordering,.woocommerce-page .woocommerce-ordering{float:right}.woocommerce .woocommerce-pagination ul.page-numbers li,.woocommerce-page .woocommerce-pagination ul.page-numbers li{display:inline-block}.woocommerce #content table.cart img,.woocommerce table.cart img,.woocommerce-page #content table.cart img,.woocommerce-page table.cart img{height:auto}.woocommerce #content table.cart td.actions,.woocommerce table.cart td.actions,.woocommerce-page #content table.cart td.actions,.woocommerce-page table.cart td.actions{text-align:right}.woocommerce #content table.cart td.actions .input-text,.woocommerce table.cart td.actions .input-text,.woocommerce-page #content table.cart td.actions .input-text,.woocommerce-page table.cart td.actions .input-text{width:80px}.woocommerce #content table.cart td.actions .coupon,.woocommerce table.cart td.actions .coupon,.woocommerce-page #content table.cart td.actions .coupon,.woocommerce-page table.cart td.actions .coupon{float:left}.woocommerce #content table.cart td.actions .coupon label,.woocommerce table.cart td.actions .coupon label,.woocommerce-page #content table.cart td.actions .coupon label,.woocommerce-page table.cart td.actions .coupon label{display:none}.woocommerce .cart-collaterals .shipping_calculator:after,.woocommerce .cart-collaterals .shipping_calculator:before,.woocommerce .cart-collaterals:after,.woocommerce .cart-collaterals:before,.woocommerce form .form-row:after,.woocommerce form .form-row:before,.woocommerce ul.cart_list li:after,.woocommerce ul.cart_list li:before,.woocommerce ul.product_list_widget li:after,.woocommerce ul.product_list_widget li:before,.woocommerce-page .cart-collaterals .shipping_calculator:after,.woocommerce-page .cart-collaterals .shipping_calculator:before,.woocommerce-page .cart-collaterals:after,.woocommerce-page .cart-collaterals:before,.woocommerce-page form .form-row:after,.woocommerce-page form .form-row:before,.woocommerce-page ul.cart_list li:after,.woocommerce-page ul.cart_list li:before,.woocommerce-page ul.product_list_widget li:after,.woocommerce-page ul.product_list_widget li:before{content:" ";display:table}.woocommerce .cart-collaterals,.woocommerce-page .cart-collaterals{width:100%}.woocommerce .cart-collaterals .related,.woocommerce-page .cart-collaterals .related{width:30.75%;float:left}.woocommerce .cart-collaterals .cross-sells,.woocommerce-page .cart-collaterals .cross-sells{width:48%;float:left}.woocommerce .cart-collaterals .cross-sells ul.products,.woocommerce-page .cart-collaterals .cross-sells ul.products{float:none}.woocommerce .cart-collaterals .cross-sells ul.products li,.woocommerce-page .cart-collaterals .cross-sells ul.products li{width:48%}.woocommerce .cart-collaterals .shipping_calculator,.woocommerce-page .cart-collaterals .shipping_calculator{width:48%;clear:right;float:right}.woocommerce .cart-collaterals .shipping_calculator:after,.woocommerce form .form-row-wide,.woocommerce form .form-row:after,.woocommerce ul.cart_list li:after,.woocommerce ul.product_list_widget li:after,.woocommerce-page .cart-collaterals .shipping_calculator:after,.woocommerce-page form .form-row-wide,.woocommerce-page form .form-row:after,.woocommerce-page ul.cart_list li:after,.woocommerce-page ul.product_list_widget li:after{clear:both}.woocommerce .cart-collaterals .shipping_calculator .col2-set .col-1,.woocommerce .cart-collaterals .shipping_calculator .col2-set .col-2,.woocommerce-page .cart-collaterals .shipping_calculator .col2-set .col-1,.woocommerce-page .cart-collaterals .shipping_calculator .col2-set .col-2{width:47%}.woocommerce .cart-collaterals .cart_totals,.woocommerce-page .cart-collaterals .cart_totals{float:right;width:48%}.woocommerce ul.cart_list li img,.woocommerce ul.product_list_widget li img,.woocommerce-page ul.cart_list li img,.woocommerce-page ul.product_list_widget li img{float:right;height:auto}.woocommerce form .form-row label,.woocommerce-page form .form-row label{display:block}.woocommerce form .form-row label.checkbox,.woocommerce-page form .form-row label.checkbox{display:inline}.woocommerce form .form-row select,.woocommerce-page form .form-row select{width:100%}.woocommerce form .form-row .input-text,.woocommerce-page form .form-row .input-text{box-sizing:border-box;width:100%}.woocommerce form .form-row-first,.woocommerce form .form-row-last,.woocommerce-page form .form-row-first,.woocommerce-page form .form-row-last{float:left;width:47%;overflow:visible}.woocommerce form .form-row-last,.woocommerce-page form .form-row-last{float:right}.woocommerce #payment .form-row select,.woocommerce-page #payment .form-row select{width:auto}.woocommerce #payment .terms,.woocommerce #payment .wc-terms-and-conditions,.woocommerce-page #payment .terms,.woocommerce-page #payment .wc-terms-and-conditions{text-align:left;padding:0 1em 0 0;float:left}.woocommerce #payment #place_order,.woocommerce-page #payment #place_order{float:right}.twentyfourteen .tfwc{padding:12px 10px 0;max-width:474px;margin:0 auto}.twentyfourteen .tfwc .product .entry-summary{padding:0!important;margin:0 0 1.618em!important}.twentyfourteen .tfwc div.product.hentry.has-post-thumbnail{margin-top:0}.twentyfourteen .tfwc .product .images img{margin-bottom:1em}@media screen and (min-width:673px){.twentyfourteen .tfwc{padding-right:30px;padding-left:30px}}@media screen and (min-width:1040px){.twentyfourteen .tfwc{padding-right:15px;padding-left:15px}}@media screen and (min-width:1110px){.twentyfourteen .tfwc{padding-right:30px;padding-left:30px}}@media screen and (min-width:1218px){.twentyfourteen .tfwc{margin-right:54px}.full-width .twentyfourteen .tfwc{margin-right:auto}}.twentyfifteen .t15wc{padding-left:7.6923%;padding-right:7.6923%;padding-top:7.6923%;margin-bottom:7.6923%;background:#fff;box-shadow:0 0 1px rgba(0,0,0,.15)}.twentyfifteen .t15wc .page-title{margin-left:0}@media screen and (min-width:38.75em){.twentyfifteen .t15wc{margin-right:7.6923%;margin-left:7.6923%;margin-top:8.3333%}}@media screen and (min-width:59.6875em){.twentyfifteen .t15wc{margin-left:8.3333%;margin-right:8.3333%;padding:10%}.single-product .twentyfifteen .entry-summary{padding:0!important}}.twentysixteen .site-main{margin-right:7.6923%;margin-left:7.6923%}.twentysixteen .entry-summary{margin-right:0;margin-left:0}#content .twentysixteen div.product div.images,#content .twentysixteen div.product div.summary{width:46.42857%}@media screen and (min-width:44.375em){.twentysixteen .site-main{margin-right:23.0769%}}@media screen and (min-width:56.875em){.twentysixteen .site-main{margin-right:0;margin-left:0}.no-sidebar .twentysixteen .site-main{margin-right:15%;margin-left:15%}.no-sidebar .twentysixteen .entry-summary{margin-right:0;margin-left:0}}                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         /*
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