 $payment_methods.size() ) {
				$payment_methods.eq(0).hide();
			}

			// If there are none selected, select the first.
			if ( 0 === $payment_methods.filter( ':checked' ).size() ) {
				$payment_methods.eq(0).attr( 'checked', 'checked' );
			}

			// Trigger click event for selected method
			$payment_methods.filter( ':checked' ).eq(0).trigger( 'click' );
		},
		get_payment_method: function() {
			return wc_checkout_form.$order_review.find( 'input[name="payment_method"]:checked' ).val();
		},
		payment_method_selected: function() {
			if ( $( '.payment_methods input.input-radio' ).length > 1 ) {
				var target_payment_box = $( 'div.payment_box.' + $( this ).attr( 'ID' ) );

				if ( $( this ).is( ':checked' ) && ! target_payment_box.is( ':visible' ) ) {
					$( 'div.payment_box' ).filter( ':visible' ).slideUp( 250 );

					if ( $( this ).is( ':checked' ) ) {
						$( 'div.payment_box.' + $( this ).attr( 'ID' ) ).slideDown( 250 );
					}
				}
			} else {
				$( 'div.payment_box' ).show();
			}

			if ( $( this ).data( 'order_button_text' ) ) {
				$( '#place_order' ).val( $( this ).data( 'order_button_text' ) );
			} else {
				$( '#place_order' ).val( $( '#place_order' ).data( 'value' ) );
			}
		},
		toggle_create_account: function() {
			$( 'div.create-account' ).hide();

			if ( $( this ).is( ':checked' ) ) {
				$( 'div.create-account' ).slideDown();
			}
		},
		init_checkout: function() {
			$( '#billing_country, #shipping_country, .country_to_state' ).change();
			$( document.body ).trigger( 'update_checkout' );
		},
		maybe_input_changed: function( e ) {
			if ( wc_checkout_form.dirtyInput ) {
				wc_checkout_form.input_changed( e );
			}
		},
		input_changed: function( e ) {
			wc_checkout_form.dirtyInput = e.target;
			wc_checkout_form.maybe_update_checkout();
		},
		queue_update_checkout: function( e ) {
			var code = e.keyCode || e.which || 0;

			if ( code === 9 ) {
				return true;
			}

			wc_checkout_form.dirtyInput = this;
			wc_checkout_form.reset_update_checkout_timer();
			wc_checkout_form.updateTimer = setTimeout( wc_checkout_form.maybe_update_checkout, '1000' );
		},
		trigger_update_checkout: function() {
			wc_checkout_form.reset_update_checkout_timer();
			wc_checkout_form.dirtyInput = false;
			$( document.body ).trigger( 'update_checkout' );
		},
		maybe_update_checkout: function() {
			var update_totals = true;

			if ( $( wc_checkout_form.dirtyInput ).size() ) {
				var $required_inputs = $( wc_checkout_form.dirtyInput ).closest( 'div' ).find( '.address-field.validate-required' );

				if ( $required_inputs.size() ) {
					$required_inputs.each( function() {
						if ( $( this ).find( 'input.input-text' ).val() === '' ) {
							update_totals = false;
						}
					});
				}
			}
			if ( update_totals ) {
				wc_checkout_form.trigger_update_checkout();
			}
		},
		ship_to_different_address: function() {
			$( 'div.shipping_address' ).hide();
			if ( $( this ).is( ':checked' ) ) {
				$( 'div.shipping_address' ).slideDown();
			}
		},
		reset_update_checkout_timer: function() {
			clearTimeout( wc_checkout_form.updateTimer );
		},
		validate_field: function() {
			var $this     = $( this ),
				$parent   = $this.closest( '.form-row' ),
				validated = true;

			if ( $parent.is( '.validate-required' ) ) {
				if ( 'checkbox' === $this.attr( 'type' ) && ! $this.is( ':checked' ) ) {
					$parent.removeClass( 'woocommerce-validated' ).addClass( 'woocommerce-invalid woocommerce-invalid-required-field' );
					validated = false;
				} else if ( $this.val() === '' ) {
					$parent.removeClass( 'woocommerce-validated' ).addClass( 'woocommerce-invalid woocommerce-invalid-required-field' );
					validated = false;
				}
			}

			if ( $parent.is( '.validate-email' ) ) {
				if ( $this.val() ) {

					/* http://stackoverflow.com/questions/2855865/jquery-validate-e-mail-address-regex */
					var pattern = new RegExp(/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i);

					if ( ! pattern.test( $this.val()  ) ) {
						$parent.removeClass( 'woocommerce-validated' ).addClass( 'woocommerce-invalid woocommerce-invalid-email' );
						validated = false;
					}
				}
			}

			if ( validated ) {
				$parent.removeClass( 'woocommerce-invalid woocommerce-invalid-required-field' ).addClass( 'woocommerce-validated' );
			}
		},
		update_checkout: function( event, args ) {
			// Small timeout to prevent multiple requests when several fields update at the same time
			wc_checkout_form.reset_update_checkout_timer();
			wc_checkout_form.updateTimer = setTimeout( wc_checkout_form.update_checkout_action, '5', args );
		},
		update_checkout_action: function( args ) {
			if ( wc_checkout_form.xhr ) {
				wc_checkout_form.xhr.abort();
			}

			if ( $( 'form.checkout' ).size() === 0 ) {
				return;
			}

			args = typeof args !== 'undefined' ? args : {
				update_shipping_method: true
			};

			var country			 = $( '#billing_country' ).val(),
				state			 = $( '#billing_state' ).val(),
				postcode		 = $( 'input#billing_postcode' ).val(),
				city			 = $( '#billing_city' ).val(),
				address			 = $( 'input#billing_address_1' ).val(),
				address_2		 = $( 'input#billing_address_2' ).val(),
				s_country		 = country,
				s_state			 = state,
				s_postcode		 = postcode,
				s_city			 = city,
				s_address		 = address,
				s_address_2		 = address_2;

			if ( $( '#ship-to-different-address' ).find( 'input' ).is( ':checked' ) ) {
				s_country		 = $( '#shipping_country' ).val();
				s_state			 = $( '#shipping_state' ).val();
				s_postcode		 = $( 'input#shipping_postcode' ).val();
				s_city			 = $( '#shipping_city' ).val();
				s_address		 = $( 'input#shipping_address_1' ).val();
				s_address_2		 = $( 'input#shipping_address_2' ).val();
			}

			var data = {
				security:					wc_checkout_params.update_order_review_nonce,
				payment_method:				wc_checkout_form.get_payment_method(),
				country:					country,
				state:						state,
				postcode:					postcode,
				city:						city,
				address:					address,
				address_2:					address_2,
				s_country:					s_country,
				s_state:					s_state,
				s_postcode:					s_postcode,
				s_city:						s_city,
				s_address:					s_address,
				s_address_2:				s_address_2,
				post_data:					$( 'form.checkout' ).serialize()
			};

			if ( false !== args.update_shipping_method ) {
				var shipping_methods = [];

				$( 'select.shipping_method, input[name^="shipping_method"][type="radio"]:checked, input[name^="shipping_method"][type="hidden"]' ).each( function() {
					shipping_methods[ $( this ).data( 'index' ) ] = $( this ).val();
				} );

				data.shipping_method = shipping_methods;
			}

			$( '.woocommerce-checkout-payment, .woocommerce-checkout-review-order-table' ).block({
				message: null,
				overlayCSS: {
					background: '#fff',
					opacity: 0.6
				}
			});

			wc_checkout_form.xhr = $.ajax({
				type:		'POST',
				url:		wc_checkout_params.wc_ajax_url.toString().replace( '%%endpoint%%', 'update_order_review' ),
				data:		data,
				success:	function( data ) {
					// Reload the page if requested
					if ( 'true' === data.reload ) {
						window.location.reload();
						return;
					}

					// Always update the fragments
					if ( data && data.fragments ) {
						$.each( data.fragments, function ( key, value ) {
							$( key ).replaceWith( value );
							$( key ).unblock();
						} );
					}

					// Check for error
					if ( 'failure' === data.result ) {

						var $form = $( 'form.checkout' );

						$( '.woocommerce-error, .woocommerce-message' ).remove();

						// Add new errors
						if ( data.messages ) {
							$form.prepend( data.messages );
						} else {
							$form.prepend( data );
						}

						// Lose focus for all fields
						$form.find( '.input-text, select' ).blur();

						// Scroll to top
						$( 'html, body' ).animate( {
							scrollTop: ( $( 'form.checkout' ).offset().top - 100 )
						}, 1000 );

					}

					// re-init methods
					wc_checkout_form.init_payment_methods();

					// Fire updated_checkout e
					$( document.body ).trigger( 'updated_checkout' );
				}

			});
		},
		submit: function() {
			wc_checkout_form.reset_update_checkout_timer();
			var $form = $( this );

			if ( $form.is( '.processing' ) ) {
				return false;
			}

			// Trigger a handler to let gateways manipulate the checkout if needed
			if ( $form.triggerHandler( 'checkout_place_order' ) !== false && $form.triggerHandler( 'checkout_place_order_' + wc_checkout_form.get_payment_method() ) !== false ) {

				$form.addClass( 'processing' );

				var form_data = $form.data();

				if ( 1 !== form_data['blockUI.isBlocked'] ) {
					$form.block({
						message: null,
						overlayCSS: {
							background: '#fff',
							opacity: 0.6
						}
					});
				}

				// ajaxSetup is global, but we use it to ensure JSON is valid once returned.
				$.ajaxSetup( {
					dataFilter: function( raw_response, dataType ) {
						// We only want to work with JSON
						if ( 'json' !== dataType ) {
							return raw_response;
						}

						try {
							// check for valid JSON
							var data = $.parseJSON( raw_response );

							if ( data && 'object' === typeof data ) {

								// Valid - return it so it can be parsed by Ajax handler
								return raw_response;
							}

						} catch ( e ) {

							// attempt to fix the malformed JSON
							var valid_json = raw_response.match( /{"result.*"}/ );

							if ( null === valid_json ) {
								console.log( 'Unable to fix malformed JSON' );
							} else {
								console.log( 'Fixed malformed JSON. Original:' );
								console.log( raw_response );
								raw_response = valid_json[0];
							}
						}

						return raw_response;
					}
				} );

				$.ajax({
					type:		'POST',
					url:		wc_checkout_params.checkout_url,
					data:		$form.serialize(),
					dataType:   'json',
					success:	function( result ) {
						try {
							if ( result.result === 'success' ) {
								if ( -1 === result.redirect.indexOf( 'https://' ) || -1 === result.redirect.indexOf( 'http://' ) ) {
									window.location = result.redirect;
								} else {
									window.location = decodeURI( result.redirect );
								}
							} else if ( result.result === 'failure' ) {
								throw 'Result failure';
							} else {
								throw 'Invalid response';
							}
						} catch( err ) {
							// Reload page
							if ( result.reload === 'true' ) {
								window.location.reload();
								return;
							}

							// Trigger update in case we need a fresh nonce
							if ( result.refresh === 'true' ) {
								$( document.body ).trigger( 'update_checkout' );
							}

							// Add new errors
							if ( result.messages ) {
								wc_checkout_form.submit_error( result.messages );
							} else {
								wc_checkout_form.submit_error( '<div class="woocommerce-error">' + wc_checkout_params.i18n_checkout_error + '</div>' );
							}
						}
					},
					error:	function( jqXHR, textStatus, errorThrown ) {
						wc_checkout_form.submit_error( '<div class="woocommerce-error">' + errorThrown + '</div>' );
					}
				});
			}

			return false;
		},
		submit_error: function( error_message ) {
			$( '.woocommerce-error, .woocommerce-message' ).remove();
			wc_checkout_form.$checkout_form.prepend( error_message );
			wc_checkout_form.$checkout_form.removeClass( 'processing' ).unblock();
			wc_checkout_form.$checkout_form.find( '.input-text, select' ).blur();
			$( 'html, body' ).animate({
				scrollTop: ( $( 'form.checkout' ).offset().top - 100 )
			}, 1000 );
			$( document.body ).trigger( 'checkout_error' );
		}
	};

	var wc_checkout_coupons = {
		init: function() {
			$( document.body ).on( 'click', 'a.showcoupon', this.show_coupon_form );
			$( document.body ).on( 'click', '.woocommerce-remove-coupon', this.remove_coupon );
			$( 'form.checkout_coupon' ).hide().submit( this.submit );
		},
		show_coupon_form: function() {
			$( '.checkout_coupon' ).slideToggle( 400, function() {
				$( '.checkout_coupon' ).find( ':input:eq(0)' ).focus();
			});
			return false;
		},
		submit: function() {
			var $form = $( this );

			if ( $form.is( '.processing' ) ) {
				return false;
			}

			$form.addClass( 'processing' ).block({
				message: null,
				overlayCSS: {
					background: '#fff',
					opacity: 0.6
				}
			});

			var data = {
				security:		wc_checkout_params.apply_coupon_nonce,
				coupon_code:	$form.find( 'input[name="coupon_code"]' ).val()
			};

			$.ajax({
				type:		'POST',
				url:		wc_checkout_params.wc_ajax_url.toString().replace( '%%endpoint%%', 'apply_coupon' ),
				data:		data,
				success:	function( code ) {
					$( '.woocommerce-error, .woocommerce-message' ).remove();
					$form.removeClass( 'processing' ).unblock();

					if ( code ) {
						$form.before( code );
						$form.slideUp();

						$( document.body ).trigger( 'update_checkout', { update_shipping_method: false } );
					}
				},
				dataType: 'html'
			});

			return false;
		},
		remove_coupon: function( e ) {
			e.preventDefault();

			var container = $( this ).parents( '.woocommerce-checkout-review-order' ),
				coupon    = $( this ).data( 'coupon' );

			container.addClass( 'processing' ).block({
				message: null,
				overlayCSS: {
					background: '#fff',
					opacity: 0.6
				}
			});

			var data = {
				security: wc_checkout_params.remove_coupon_nonce,
				coupon:   coupon
			};

			$.ajax({
				type:    'POST',
				url:     wc_checkout_params.wc_ajax_url.toString().replace( '%%endpoint%%', 'remove_coupon' ),
				data:    data,
				success: function( code ) {
					$( '.woocommerce-error, .woocommerce-message' ).remove();
					container.removeClass( 'processing' ).unblock();

					if ( code ) {
						$( 'form.woocommerce-checkout' ).before( code );

						$( document.body ).trigger( 'update_checkout', { update_shipping_method: false } );

						// remove coupon code from coupon field
						$( 'form.checkout_coupon' ).find( 'input[name="coupon_code"]' ).val( '' );
					}
				},
				error: function ( jqXHR ) {
					if ( wc_checkout_params.debug_mode ) {
						/*jshint devel: true */
						console.log( jqXHR.responseText );
					}
				},
				dataType: 'html'
			});
		}
	};

	var wc_checkout_login_form = {
		init: function() {
			$( document.body ).on( 'click', 'a.showlogin', this.show_login_form );
		},
		show_login_form: function() {
			$( 'form.login' ).slideToggle();
			return false;
		}
	};

	wc_checkout_form.init();
	wc_checkout_coupons.init();
	wc_checkout_login_form.init();
});
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                       /*global wc_add_to_cart_variation_params, wc_cart_fragments_params */
/*!
 * Variations Plugin
 */
;(function ( $, window, document, undefined ) {

	$.fn.wc_variation_form = function() {
		var $form                  = this,
			$single_variation      = $form.find( '.single_variation' ),
			$product               = $form.closest( '.product' ),
			$product_id            = parseInt( $form.data( 'product_id' ), 10 ),
			$product_variations    = $form.data( 'product_variations' ),
			$use_ajax              = $product_variations === false,
			$xhr                   = false,
			$reset_variations      = $form.find( '.reset_variations' ),
			template               = wp.template( 'variation-template' ),
			unavailable_template   = wp.template( 'unavailable-variation-template' ),
			$single_variation_wrap = $form.find( '.single_variation_wrap' );

		// Always visible since 2.5.0
		$single_variation_wrap.show();

		// Unbind any existing events
		$form.unbind( 'check_variations update_variation_values found_variation' );
		$form.find( '.reset_variations' ).unbind( 'click' );
		$form.find( '.variations select' ).unbind( 'change focusin' );

		// Bind new events to form
		$form

		// On clicking the reset variation button
		.on( 'click', '.reset_variations', function() {
			$form.find( '.variations select' ).val( '' ).change();
			$form.trigger( 'reset_data' );
			return false;
		} )

		// When the variation is hidden
		.on( 'hide_variation', function() {
			$form.find( '.single_add_to_cart_button' ).attr( 'disabled', 'disabled' ).attr( 'title', wc_add_to_cart_variation_params.i18n_make_a_selection_text );
			return false;
		} )

		// When the variation is revealed
		.on( 'show_variation', function( event, variation, purchasable ) {
			if ( purchasable ) {
				$form.find( '.single_add_to_cart_button' ).removeAttr( 'disabled' ).removeAttr( 'title' );
			} else {
				$form.find( '.single_add_to_cart_button' ).attr( 'disabled', 'disabled' ).attr( 'title', wc_add_to_cart_variation_params.i18n_unavailable_text );
			}
			return false;
		} )

		// Reload product variations data
		.on( 'reload_product_variations', function() {
			$product_variations = $form.data( 'product_variations' );
			$use_ajax           = $product_variations === false;
		} )

		// Reset product data
		.on( 'reset_data', function() {
			$('.sku').wc_reset_content();
			$('.product_weight').wc_reset_content();
			$('.product_dimensions').wc_reset_content();
			$form.trigger( 'reset_image' );
			$single_variation.slideUp( 200 ).trigger( 'hide_variation' );
		} )

		// Reset product image
		.on( 'reset_image', function() {
			$form.wc_variations_image_update( false );
		} )

		// On changing an attribute
		.on( 'change', '.variations select', function() {
			$form.find( 'input[name="variation_id"], input.variation_id' ).val( '' ).change();
			$form.find( '.wc-no-matching-variations' ).remove();

			if ( $use_ajax ) {
				if ( $xhr ) {
					$xhr.abort();
				}

				var all_attributes_chosen  = true;
				var some_attributes_chosen = false;
				var data                   = {};

				$form.find( '.variations select' ).each( function() {
					var attribute_name = $( this ).data( 'attribute_name' ) || $( this ).attr( 'name' );

					if ( $( this ).val().length === 0 ) {
						all_attributes_chosen = false;
					} else {
						some_attributes_chosen = true;
					}

					data[ attribute_name ] = $( this ).val();
				});

				if ( all_attributes_chosen ) {
					// Get a matchihng variation via ajax
					data.product_id = $product_id;

					$xhr = $.ajax( {
						url: wc_cart_fragments_params.wc_ajax_url.toString().replace( '%%endpoint%%', 'get_variation' ),
						type: 'POST',
						data: data,
						success: function( variation ) {
							if ( variation ) {
								$form.trigger( 'found_variation', [ variation ] );
							} else {
								$form.trigger( 'reset_data' );
								$form.find( '.single_variation' ).after( '<p class="wc-no-matching-variations woocommerce-info">' + wc_add_to_cart_variation_params.i18n_no_matching_variations_text + '</p>' );
								$form.find( '.wc-no-matching-variations' ).slideDown( 200 );
							}
						}
					} );
				} else {
					$form.trigger( 'reset_data' );
				}
				if ( some_attributes_chosen ) {
					if ( $reset_variations.css( 'visibility' ) === 'hidden' ) {
						$reset_variations.css( 'visibility', 'visible' ).hide().fadeIn();
					}
				} else {
					$reset_variations.css( 'visibility', 'hidden' );
				}
			} else {
				$form.trigger( 'woocommerce_variation_select_change' );
				$form.trigger( 'check_variations', [ '', false ] );
				$( this ).blur();
			}

			// added to get around variation image flicker issue
			$( '.product.has-default-attributes > .images' ).fadeTo( 200, 1 );

			// Custom event for when variation selection has been changed
			$form.trigger( 'woocommerce_variation_has_changed' );
		} )

		// Upon gaining focus
		.on( 'focusin touchstart', '.variations select', function() {
			$( this ).find( 'option:selected' ).attr( 'selected', 'selected' );

			if ( ! $use_ajax ) {
				$form.trigger( 'woocommerce_variation_select_focusin' );
				$form.trigger( 'check_variations', [ $( this ).data( 'attribute_name' ) || $( this ).attr( 'name' ), true ] );
			}
		} )

		// Show single variation details (price, stock, image)
		.on( 'found_variation', function( event, variation ) {
			var $sku        = $product.find( '.product_meta' ).find( '.sku' ),
				$weight     = $product.find( '.product_weight' ),
				$dimensions = $product.find( '.product_dimensions' ),
				$qty        = $single_variation_wrap.find( '.quantity' ),
				purchasable = true;

			// Display SKU
			if ( variation.sku ) {
				$sku.wc_set_content( variation.sku );
			} else {
				$sku.wc_reset_content();
			}

			// Display weight
			if ( variation.weight ) {
				$weight.wc_set_content( variation.weight );
			} else {
				$weight.wc_reset_content();
			}

			// Display dimensions
			if ( variation.dimensions ) {
				$dimensions.wc_set_content( variation.dimensions );
			} else {
				$dimensions.wc_reset_content();
			}

			// Show images
			$form.wc_variations_image_update( variation );

			// Output correct templates
			if ( ! variation.variation_is_visible ) {
				$single_variation.html( unavailable_template );
				$form.find( 'input[name="variation_id"], input.variation_id' ).val( '' ).change();
			} else {
				$single_variation.html( template( {
					variation:    variation
				} ) );
				$form.find( 'input[name="variation_id"], input.variation_id' ).val( variation.variation_id ).change();
			}

			// Hide or show qty input
			if ( variation.is_sold_individually === 'yes' ) {
				$qty.find( 'input.qty' ).val( '1' ).attr( 'min', '1' ).attr( 'max', '' );
				$qty.hide();
			} else {
				$qty.find( 'input.qty' ).attr( 'min', variation.min_qty ).attr( 'max', variation.max_qty );
				$qty.show();
			}

			// Enable or disable the add to cart button
			if ( ! variation.is_purchasable || ! variation.is_in_stock || ! variation.variation_is_visible ) {
				purchasable = false;
			}

			// Reveal
			if ( $.trim( $single_variation.text() ) ) {
				$single_variation.slideDown( 200 ).trigger( 'show_variation', [ variation, purchasable ] );
			} else {
				$single_variation.show().trigger( 'show_variation', [ variation, purchasable ] );
			}
		})

		// Check variations
		.on( 'check_variations', function( event, exclude, focus ) {
			if ( $use_ajax ) {
				return;
			}

			var all_attributes_chosen  = true,
				some_attributes_chosen = false,
				current_settings       = {},
				$form                  = $( this ),
				$reset_variations      = $form.find( '.reset_variations' );

			$form.find( '.variations select' ).each( function() {
				var attribute_name = $( this ).data( 'attribute_name' ) || $( this ).attr( 'name' );

				if ( $( this ).val().length === 0 ) {
					all_attributes_chosen = false;
				} else {
					some_attributes_chosen = true;
				}

				if ( exclude && attribute_name === exclude ) {
					all_attributes_chosen = false;
					current_settings[ attribute_name ] = '';
				} else {
					// Add to settings array
					current_settings[ attribute_name ] = $( this ).val();
				}
			});

			var matching_variations = wc_variation_form_matcher.find_matching_variations( $product_variations, current_settings );

			if ( all_attributes_chosen ) {

				var variation = matching_variations.shift();

				if ( variation ) {
					$form.trigger( 'found_variation', [ variation ] );
				} else {
					// Nothing found - reset fields
					$form.find( '.variations select' ).val( '' );

					if ( ! focus ) {
						$form.trigger( 'reset_data' );
					}

					window.alert( wc_add_to_cart_variation_params.i18n_no_matching_variations_text );
				}

			} else {

				$form.trigger( 'update_variation_values', [ matching_variations ] );

				if ( ! focus ) {
					$form.trigger( 'reset_data' );
				}

				if ( ! exclude ) {
					$single_variation.slideUp( 200 ).trigger( 'hide_variation' );
				}
			}
			if ( some_attributes_chosen ) {
				if ( $reset_variations.css( 'visibility' ) === 'hidden' ) {
					$reset_variations.css( 'visibility', 'visible' ).hide().fadeIn();
				}
			} else {
				$reset_variations.css( 'visibility', 'hidden' );
			}
		} )

		// Disable option fields that are unavaiable for current set of attributes
		.on( 'update_variation_values', function( event, variations ) {
			if ( $use_ajax ) {
				return;
			}
			// Loop through selects and disable/enable options based on selections
			$form.find( '.variations select' ).each( function( index, el ) {

				var current_attr_name, current_attr_select = $( el );

				// Reset options
				if ( ! current_attr_select.data( 'attribute_options' ) ) {
					current_attr_select.data( 'attribute_options', current_attr_select.find( 'option:gt(0)' ).get() );
				}

				current_attr_select.find( 'option:gt(0)' ).remove();
				current_attr_select.append( current_attr_select.data( 'attribute_options' ) );
				current_attr_select.find( 'option:gt(0)' ).removeClass( 'attached' );
				current_attr_select.find( 'option:gt(0)' ).removeClass( 'enabled' );
				current_attr_select.find( 'option:gt(0)' ).removeAttr( 'disabled' );

				// Get name from data-attribute_name, or from input name if it doesn't exist
				if ( typeof( current_attr_select.data( 'attribute_name' ) ) !== 'undefined' ) {
					current_attr_name = current_attr_select.data( 'attribute_name' );
				} else {
					current_attr_name = current_attr_select.attr( 'name' );
				}

				// Loop through variations
				for ( var num in variations ) {

					if ( typeof( variations[ num ] ) !== 'undefined' ) {

						var attributes = variations[ num ].attributes;

						for ( var attr_name in attributes ) {
							if ( attributes.hasOwnProperty( attr_name ) ) {
								var attr_val = attributes[ attr_name ];

								if ( attr_name === current_attr_name ) {

									var variation_active = '';

									if ( variations[ num ].variation_is_active ) {
										variation_active = 'enabled';
									}

									if ( attr_val ) {

										// Decode entities
										attr_val = $( '<div/>' ).html( attr_val ).text();

										// Add slashes
										attr_val = attr_val.replace( /'/g, '\\\'' );
										attr_val = attr_val.replace( /"/g, '\\\"' );

										// Compare the meerkat
										current_attr_select.find( 'option[value="' + attr_val + '"]' ).addClass( 'attached ' + variation_active );

									} else {

										current_attr_select.find( 'option:gt(0)' ).addClass( 'attached ' + variation_active );

									}
								}
							}
						}
					}
				}

				// Detach unattached
				current_attr_select.find( 'option:gt(0):not(.attached)' ).remove();

				// Grey out disabled
				current_attr_select.find( 'option:gt(0):not(.enabled)' ).attr( 'disabled', 'disabled' );

			});

			// Custom event for when variations have been updated
			$form.trigger( 'woocommerce_update_variation_values' );
		});

		$form.trigger( 'wc_variation_form' );

		return $form;
	};

	/**
	 * Matches inline variation objects to chosen attributes
	 * @type {Object}
	 */
	var wc_variation_form_matcher = {
		find_matching_variations: function( product_variations, settings ) {
			var matching = [];
			for ( var i = 0; i < product_variations.length; i++ ) {
				var variation    = product_variations[i];

				if ( wc_variation_form_matcher.variations_match( variation.attributes, settings ) ) {
					matching.push( variation );
				}
			}
			return matching;
		},
		variations_match: function( attrs1, attrs2 ) {
			var match = true;
			for ( var attr_name in attrs1 ) {
				if ( attrs1.hasOwnProperty( attr_name ) ) {
					var val1 = attrs1[ attr_name ];
					var val2 = attrs2[ attr_name ];
					if ( val1 !== undefined && val2 !== undefined && val1.length !== 0 && val2.length !== 0 && val1 !== val2 ) {
						match = false;
					}
				}
			}
			return match;
		}
	};

	/**
	 * Stores the default text for an element so it can be reset later
	 */
	$.fn.wc_set_content = function( content ) {
		if ( undefined === this.attr( 'data-o_content' ) ) {
			this.attr( 'data-o_content', this.text() );
		}
		this.text( content );
	};

	/**
	 * Stores the default text for an element so it can be reset later
	 */
	$.fn.wc_reset_content = function() {
		if ( undefined !== this.attr( 'data-o_content' ) ) {
			this.text( this.attr( 'data-o_content' ) );
		}
	};

	/**
	 * Stores a default attribute for an element so it can be reset later
	 */
	$.fn.wc_set_variation_attr = function( attr, value ) {
		if ( undefined === this.attr( 'data-o_' + attr ) ) {
			this.attr( 'data-o_' + attr, ( ! this.attr( attr ) ) ? '' : this.attr( attr ) );
		}
		this.attr( attr, value );
	};

	/**
	 * Reset a default attribute for an element so it can be reset later
	 */
	$.fn.wc_reset_variation_attr = function( attr ) {
		if ( undefined !== this.attr( 'data-o_' + attr ) ) {
			this.attr( attr, this.attr( 'data-o_' + attr ) );
		}
	};

	/**
	 * Sets product images for the chosen variation
	 */
	$.fn.wc_variations_image_update = function( variation ) {
		var $form             = this,
			$product          = $form.closest('.product'),
			$product_img      = $product.find( 'div.images img:eq(0)' ),
			$product_link     = $product.find( 'div.images a.zoom:eq(0)' );

		if ( variation && variation.image_src && variation.image_src.length > 1 ) {
			$product_img.wc_set_variation_attr( 'src', variation.image_src );
			$product_img.wc_set_variation_attr( 'title', variation.image_title );
			$product_img.wc_set_variation_attr( 'alt', variation.image_title );
			$product_img.wc_set_variation_attr( 'srcset', variation.image_srcset );
			$product_img.wc_set_variation_attr( 'sizes', variation.image_sizes );
			$product_link.wc_set_variation_attr( 'href', variation.image_link );
			$product_link.wc_set_variation_attr( 'title', variation.image_caption );
		} else {
			$product_img.wc_reset_variation_attr( 'src' );
			$product_img.wc_reset_variation_attr( 'title' );
			$product_img.wc_reset_variation_attr( 'alt' );
			$product_img.wc_reset_variation_attr( 'srcset' );
			$product_img.wc_reset_variation_attr( 'sizes' );
			$product_link.wc_reset_variation_attr( 'href' );
			$product_link.wc_reset_variation_attr( 'title' );
		}
	};

	$( function() {
		if ( typeof wc_add_to_cart_variation_params !== 'undefined' ) {
			$( '.variations_form' ).each( function() {
				$( this ).wc_variation_form().find('.variations select:eq(0)').change();
			});
		}
	});

})( jQuery, window, document );
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                 // Chosen, a Select Box Enhancer for jQuery and Prototype
// by Patrick Filler for Harvest, http://getharvest.com
//
// Version 1.0.0
// Full source at https://github.com/harvesthq/chosen
// Copyright (c) 2011 Harvest http://getharvest.com
// MIT License, https://github.com/harvesthq/chosen/blob/master/LICENSE.md
// This file is generated by `grunt build`, do not edit it by hand.
(function(){var e,t,n,r,i,s={}.hasOwnProperty,o=function(e,t){function r(){this.constructor=e}for(var n in t)s.call(t,n)&&(e[n]=t[n]);r.prototype=t.prototype;e.prototype=new r;e.__super__=t.prototype;return e};r=function(){function e(){this.options_index=0;this.parsed=[]}e.prototype.add_node=function(e){return e.nodeName.toUpperCase()==="OPTGROUP"?this.add_group(e):this.add_option(e)};e.prototype.add_group=function(e){var t,n,r,i,s,o;t=this.parsed.length;this.parsed.push({array_index:t,group:!0,label:this.escapeExpression(e.label),children:0,disabled:e.disabled});s=e.childNodes;o=[];for(r=0,i=s.length;r<i;r++){n=s[r];o.push(this.add_option(n,t,e.disabled))}return o};e.prototype.add_option=function(e,t,n){if(e.nodeName.toUpperCase()==="OPTION"){if(e.text!==""){t!=null&&(this.parsed[t].children+=1);this.parsed.push({array_index:this.parsed.length,options_index:this.options_index,value:e.value,text:e.text,html:e.innerHTML,selected:e.selected,disabled:n===!0?n:e.disabled,group_array_index:t,classes:e.className,style:e.style.cssText})}else this.parsed.push({array_index:this.parsed.length,options_index:this.options_index,empty:!0});return this.options_index+=1}};e.prototype.escapeExpression=function(e){var t,n;if(e==null||e===!1)return"";if(!/[\&\<\>\"\'\`]/.test(e))return e;t={"<":"&lt;",">":"&gt;",'"':"&quot;","'":"&#x27;","`":"&#x60;"};n=/&(?!\w+;)|[\<\>\"\'\`]/g;return e.replace(n,function(e){return t[e]||"&amp;"})};return e}();r.select_to_array=function(e){var t,n,i,s,o;n=new r;o=e.childNodes;for(i=0,s=o.length;i<s;i++){t=o[i];n.add_node(t)}return n.parsed};t=function(){function e(t,n){this.form_field=t;this.options=n!=null?n:{};if(!e.browser_is_supported())return;this.is_multiple=this.form_field.multiple;this.set_default_text();this.set_default_values();this.setup();this.set_up_html();this.register_observers()}e.prototype.set_default_values=function(){var e=this;this.click_test_action=function(t){return e.test_active_click(t)};this.activate_action=function(t){return e.activate_field(t)};this.active_field=!1;this.mouse_on_container=!1;this.results_showing=!1;this.result_highlighted=null;this.result_single_selected=null;this.allow_single_deselect=this.options.allow_single_deselect!=null&&this.form_field.options[0]!=null&&this.form_field.options[0].text===""?this.options.allow_single_deselect:!1;this.disable_search_threshold=this.options.disable_search_threshold||0;this.disable_search=this.options.disable_search||!1;this.enable_split_word_search=this.options.enable_split_word_search!=null?this.options.enable_split_word_search:!0;this.group_search=this.options.group_search!=null?this.options.group_search:!0;this.search_contains=this.options.search_contains||!1;this.single_backstroke_delete=this.options.single_backstroke_delete!=null?this.options.single_backstroke_delete:!0;this.max_selected_options=this.options.max_selected_options||Infinity;this.inherit_select_classes=this.options.inherit_select_classes||!1;this.display_selected_options=this.options.display_selected_options!=null?this.options.display_selected_options:!0;return this.display_disabled_options=this.options.display_disabled_options!=null?this.options.display_disabled_options:!0};e.prototype.set_default_text=function(){this.form_field.getAttribute("data-placeholder")?this.default_text=this.form_field.getAttribute("data-placeholder"):this.is_multiple?this.default_text=this.options.placeholder_text_multiple||this.options.placeholder_text||e.default_multiple_text:this.default_text=this.options.placeholder_text_single||this.options.placeholder_text||e.default_single_text;return this.results_none_found=this.form_field.getAttribute("data-no_results_text")||this.options.no_results_text||e.default_no_result_text};e.prototype.mouse_enter=function(){return this.mouse_on_container=!0};e.prototype.mouse_leave=function(){return this.mouse_on_container=!1};e.prototype.input_focus=function(e){var t=this;if(this.is_multiple){if(!this.active_field)return setTimeout(function(){return t.container_mousedown()},50)}else if(!this.active_field)return this.activate_field()};e.prototype.input_blur=function(e){var t=this;if(!this.mouse_on_container){this.active_field=!1;return setTimeout(function(){return t.blur_test()},100)}};e.prototype.results_option_build=function(e){var t,n,r,i,s;t="";s=this.results_data;for(r=0,i=s.length;r<i;r++){n=s[r];n.group?t+=this.result_add_group(n):t+=this.result_add_option(n);if(e!=null?e.first:void 0)n.selected&&this.is_multiple?this.choice_build(n):n.selected&&!this.is_multiple&&this.single_set_selected_text(n.text)}return t};e.prototype.result_add_option=function(e){var t,n;if(!e.search_match)return"";if(!this.include_option_in_results(e))return"";t=[];!e.disabled&&(!e.selected||!this.is_multiple)&&t.push("active-result");e.disabled&&(!e.selected||!this.is_multiple)&&t.push("disabled-result");e.selected&&t.push("result-selected");e.group_array_index!=null&&t.push("group-option");e.classes!==""&&t.push(e.classes);n=e.style.cssText!==""?' style="'+e.style+'"':"";return'<li class="'+t.join(" ")+'"'+n+' data-option-array-index="'+e.array_index+'">'+e.search_text+"</li>"};e.prototype.result_add_group=function(e){return!e.search_match&&!e.group_match?"":e.active_options>0?'<li class="group-result">'+e.search_text+"</li>":""};e.prototype.results_update_field=function(){this.set_default_text();this.is_multiple||this.results_reset_cleanup();this.result_clear_highlight();this.result_single_selected=null;this.results_build();if(this.results_showing)return this.winnow_results()};e.prototype.results_toggle=function(){return this.results_showing?this.results_hide():this.results_show()};e.prototype.results_search=function(e){return this.results_showing?this.winnow_results():this.results_show()};e.prototype.winnow_results=function(){var e,t,n,r,i,s,o,u,a,f,l,c,h;this.no_results_clear();i=0;o=this.get_search_text();e=o.replace(/[-[\]{}()*+?.,\\^$|#\s]/g,"\\$&");r=this.search_contains?"":"^";n=new RegExp(r+e,"i");f=new RegExp(e,"i");h=this.results_data;for(l=0,c=h.length;l<c;l++){t=h[l];t.search_match=!1;s=null;if(this.include_option_in_results(t)){if(t.group){t.group_match=!1;t.active_options=0}if(t.group_array_index!=null&&this.results_data[t.group_array_index]){s=this.results_data[t.group_array_index];s.active_options===0&&s.search_match&&(i+=1);s.active_options+=1}if(!t.group||!!this.group_search){t.search_text=t.group?t.label:t.html;t.search_match=this.search_string_match(t.search_text,n);t.search_match&&!t.group&&(i+=1);if(t.search_match){if(o.length){u=t.search_text.search(f);a=t.search_text.substr(0,u+o.length)+"</em>"+t.search_text.substr(u+o.length);t.search_text=a.substr(0,u)+"<em>"+a.substr(u)}s!=null&&(s.group_match=!0)}else t.group_array_index!=null&&this.results_data[t.group_array_index].search_match&&(t.search_match=!0)}}}this.result_clear_highlight();if(i<1&&o.length){this.update_results_content("");return this.no_results(o)}this.update_results_content(this.results_option_build());return this.winnow_results_set_highlight()};e.prototype.search_string_match=function(e,t){var n,r,i,s;if(t.test(e))return!0;if(this.enable_split_word_search&&(e.indexOf(" ")>=0||e.indexOf("[")===0)){r=e.replace(/\[|\]/g,"").split(" ");if(r.length)for(i=0,s=r.length;i<s;i++){n=r[i];if(t.test(n))return!0}}};e.prototype.choices_count=function(){var e,t,n,r;if(this.selected_option_count!=null)return this.selected_option_count;this.selected_option_count=0;r=this.form_field.options;for(t=0,n=r.length;t<n;t++){e=r[t];e.selected&&(this.selected_option_count+=1)}return this.selected_option_count};e.prototype.choices_click=function(e){e.preventDefault();if(!this.results_showing&&!this.is_disabled)return this.results_show()};e.prototype.keyup_checker=function(e){var t,n;t=(n=e.which)!=null?n:e.keyCode;this.search_field_scale();switch(t){case 8:if(this.is_multiple&&this.backstroke_length<1&&this.choices_count()>0)return this.keydown_backstroke();if(!this.pending_backstroke){this.result_clear_highlight();return this.results_search()}break;case 13:e.preventDefault();if(this.results_showing)return this.result_select(e);break;case 27:this.results_showing&&this.results_hide();return!0;case 9:case 38:case 40:case 16:case 91:case 17:break;default:return this.results_search()}};e.prototype.container_width=function(){return this.options.width!=null?this.options.width:""+this.form_field.offsetWidth+"px"};e.prototype.include_option_in_results=function(e){return this.is_multiple&&!this.display_selected_options&&e.selected?!1:!this.display_disabled_options&&e.disabled?!1:e.empty?!1:!0};e.browser_is_supported=function(){return window.navigator.appName==="Microsoft Internet Explorer"?document.documentMode>=8:/iP(od|hone)/i.test(window.navigator.userAgent)?!1:/Android/i.test(window.navigator.userAgent)&&/Mobile/i.test(window.navigator.userAgent)?!1:!0};e.default_multiple_text="Select Some Options";e.default_single_text="Select an Option";e.default_no_result_text="No results match";return e}();e=jQuery;e.fn.extend({chosen:function(r){return t.browser_is_supported()?this.each(function(t){var i,s;i=e(this);s=i.data("chosen");r==="destroy"&&s?s.destroy():s||i.data("chosen",new n(this,r))}):this}});n=function(t){function n(){i=n.__super__.constructor.apply(this,arguments);return i}o(n,t);n.prototype.setup=function(){this.form_field_jq=e(this.form_field);this.current_selectedIndex=this.form_field.selectedIndex;return this.is_rtl=this.form_field_jq.hasClass("chosen-rtl")};n.prototype.set_up_html=function(){var t,n;t=["chosen-container"];t.push("chosen-container-"+(this.is_multiple?"multi":"single"));this.inherit_select_classes&&this.form_field.className&&t.push(this.form_field.className);this.is_rtl&&t.push("chosen-rtl");n={"class":t.join(" "),style:"width: "+this.container_width()+";",title:this.form_field.title};this.form_field.id.length&&(n.id=this.form_field.id.replace(/[^\w]/g,"_")+"_chosen");this.container=e("<div />",n);this.is_multiple?this.container.html('<ul class="chosen-choices"><li class="search-field"><input type="text" value="'+this.default_text+'" class="default" autocomplete="off" style="width:25px;" /></li></ul><div class="chosen-drop"><ul class="chosen-results"></ul></div>'):this.container.html('<a class="chosen-single chosen-default" tabindex="-1"><span>'+this.default_text+'</span><div><b></b></div></a><div class="chosen-drop"><div class="chosen-search"><input type="text" autocomplete="off" /></div><ul class="chosen-results"></ul></div>');this.form_field_jq.hide().after(this.container);this.dropdown=this.container.find("div.chosen-drop").first();this.search_field=this.container.find("input").first();this.search_results=this.container.find("ul.chosen-results").first();this.search_field_scale();this.search_no_results=this.container.find("li.no-results").first();if(this.is_multiple){this.search_choices=this.container.find("ul.chosen-choices").first();this.search_container=this.container.find("li.search-field").first()}else{this.search_container=this.container.find("div.chosen-search").first();this.selected_item=this.container.find(".chosen-single").first()}this.results_build();this.set_tab_index();this.set_label_behavior();return this.form_field_jq.trigger("chosen:ready",{chosen:this})};n.prototype.register_observers=function(){var e=this;this.container.bind("mousedown.chosen",function(t){e.container_mousedown(t)});this.container.bind("mouseup.chosen",function(t){e.container_mouseup(t)});this.container.bind("mouseenter.chosen",function(t){e.mouse_enter(t)});this.container.bind("mouseleave.chosen",function(t){e.mouse_leave(t)});this.search_results.bind("mouseup.chosen",function(t){e.search_results_mouseup(t)});this.search_results.bind("mouseover.chosen",function(t){e.search_results_mouseover(t)});this.search_results.bind("mouseout.chosen",function(t){e.search_results_mouseout(t)});this.search_results.bind("mousewheel.chosen DOMMouseScroll.chosen",function(t){e.search_results_mousewheel(t)});this.form_field_jq.bind("chosen:updated.chosen",function(t){e.results_update_field(t)});this.form_field_jq.bind("chosen:activate.chosen",function(t){e.activate_field(t)});this.form_field_jq.bind("chosen:open.chosen",function(t){e.container_mousedown(t)});this.search_field.bind("blur.chosen",function(t){e.input_blur(t)});this.search_field.bind("keyup.chosen",function(t){e.keyup_checker(t)});this.search_field.bind("keydown.chosen",function(t){e.keydown_checker(t)});this.search_field.bind("focus.chosen",function(t){e.input_focus(t)});return this.is_multiple?this.search_choices.bind("click.chosen",function(t){e.choices_click(t)}):this.container.bind("click.chosen",function(e){e.preventDefault()})};n.prototype.destroy=function(){e(document).unbind("click.chosen",this.click_test_action);this.search_field[0].tabIndex&&(this.form_field_jq[0].tabIndex=this.search_field[0].tabIndex);this.container.remove();this.form_field_jq.removeData("chosen");return this.form_field_jq.show()};n.prototype.search_field_disabled=function(){this.is_disabled=this.form_field_jq[0].disabled;if(this.is_disabled){this.container.addClass("chosen-disabled");this.search_field[0].disabled=!0;this.is_multiple||this.selected_item.unbind("focus.chosen",this.activate_action);return this.close_field()}this.container.removeClass("chosen-disabled");this.search_field[0].disabled=!1;if(!this.is_multiple)return this.selected_item.bind("focus.chosen",this.activate_action)};n.prototype.container_mousedown=function(t){if(!this.is_disabled){t&&t.type==="mousedown"&&!this.results_showing&&t.preventDefault();if(t==null||!e(t.target).hasClass("search-choice-close")){if(!this.active_field){this.is_multiple&&this.search_field.val("");e(document).bind("click.chosen",this.click_test_action);this.results_show()}else if(!this.is_multiple&&t&&(e(t.target)[0]===this.selected_item[0]||e(t.target).parents("a.chosen-single").length)){t.preventDefault();this.results_toggle()}return this.activate_field()}}};n.prototype.container_mouseup=function(e){if(e.target.nodeName==="ABBR"&&!this.is_disabled)return this.results_reset(e)};n.prototype.search_results_mousewheel=function(e){var t,n,r;t=-((n=e.originalEvent)!=null?n.wheelDelta:void 0)||((r=e.originialEvent)!=null?r.detail:void 0);if(t!=null){e.preventDefault();e.type==="DOMMouseScroll"&&(t*=40);return this.search_results.scrollTop(t+this.search_results.scrollTop())}};n.prototype.blur_test=function(e){if(!this.active_field&&this.container.hasClass("chosen-container-active"))return this.close_field()};n.prototype.close_field=function(){e(document).unbind("click.chosen",this.click_test_action);this.active_field=!1;this.results_hide();this.container.removeClass("chosen-container-active");this.clear_backstroke();this.show_search_field_default();return this.search_field_scale()};n.prototype.activate_field=function(){this.container.addClass("chosen-container-active");this.active_field=!0;this.search_field.val(this.search_field.val());return this.search_field.focus()};n.prototype.test_active_click=function(t){return this.container.is(e(t.target).closest(".chosen-container"))?this.active_field=!0:this.close_field()};n.prototype.results_build=function(){this.parsing=!0;this.selected_option_count=null;this.results_data=r.select_to_array(this.form_field);if(this.is_multiple)this.search_choices.find("li.search-choice").remove();else if(!this.is_multiple){this.single_set_selected_text();if(this.disable_search||this.form_field.options.length<=this.disable_search_threshold){this.search_field[0].readOnly=!0;this.container.addClass("chosen-container-single-nosearch")}else{this.search_field[0].readOnly=!1;this.container.removeClass("chosen-container-single-nosearch")}}this.update_results_content(this.results_option_build({first:!0}));this.search_field_disabled();this.show_search_field_default();this.search_field_scale();return this.parsing=!1};n.prototype.result_do_highlight=function(e){var t,n,r,i,s;if(e.length){this.result_clear_highlight();this.result_highlight=e;this.result_highlight.addClass("highlighted");r=parseInt(this.search_results.css("maxHeight"),10);s=this.search_results.scrollTop();i=r+s;n=this.result_highlight.position().top+this.search_results.scrollTop();t=n+this.result_highlight.outerHeight();if(t>=i)return this.search_results.scrollTop(t-r>0?t-r:0);if(n<s)return this.search_results.scrollTop(n)}};n.prototype.result_clear_highlight=function(){this.result_highlight&&this.result_highlight.removeClass("highlighted");return this.result_highlight=null};n.prototype.results_show=function(){if(this.is_multiple&&this.max_selected_options<=this.choices_count()){this.form_field_jq.trigger("chosen:maxselected",{chosen:this});return!1}this.container.addClass("chosen-with-drop");this.form_field_jq.trigger("chosen:showing_dropdown",{chosen:this});this.results_showing=!0;this.search_field.focus();this.search_field.val(this.search_field.val());return this.winnow_results()};n.prototype.update_results_content=function(e){return this.search_results.html(e)};n.prototype.results_hide=function(){if(this.results_showing){this.result_clear_highlight();this.container.removeClass("chosen-with-drop");this.form_field_jq.trigger("chosen:hiding_dropdown",{chosen:this})}return this.results_showing=!1};n.prototype.set_tab_index=function(e){var t;if(this.form_field.tabIndex){t=this.form_field.tabIndex;this.form_field.tabIndex=-1;return this.search_field[0].tabIndex=t}};n.prototype.set_label_behavior=function(){var t=this;this.form_field_label=this.form_field_jq.parents("label");!this.form_field_label.length&&this.form_field.id.length&&(this.form_field_label=e("label[for='"+this.form_field.id+"']"));if(this.form_field_label.length>0)return this.form_field_label.bind("click.chosen",function(e){return t.is_multiple?t.container_mousedown(e):t.activate_field()})};n.prototype.show_search_field_default=function(){if(this.is_multiple&&this.choices_count()<1&&!this.active_field){this.search_field.val(this.default_text);return this.search_field.addClass("default")}this.search_field.val("");return this.search_field.removeClass("default")};n.prototype.search_results_mouseup=function(t){var n;n=e(t.target).hasClass("active-result")?e(t.target):e(t.target).parents(".active-result").first();if(n.length){this.result_highlight=n;this.result_select(t);return this.search_field.focus()}};n.prototype.search_results_mouseover=function(t){var n;n=e(t.target).hasClass("active-result")?e(t.target):e(t.target).parents(".active-result").first();if(n)return this.result_do_highlight(n)};n.prototype.search_results_mouseout=function(t){if(e(t.target).hasClass("active-result"))return this.result_clear_highlight()};n.prototype.choice_build=function(t){var n,r,i=this;n=e("<li />",{"class":"search-choice"}).html("<span>"+t.html+"</span>");if(t.disabled)n.addClass("search-choice-disabled");else{r=e("<a />",{"class":"search-choice-close","data-option-array-index":t.array_index});r.bind("click.chosen",function(e){return i.choice_destroy_link_click(e)});n.append(r)}return this.search_container.before(n)};n.prototype.choice_destroy_link_click=function(t){t.preventDefault();t.stopPropagation();if(!this.is_disabled)return this.choice_destroy(e(t.target))};n.prototype.choice_destroy=function(e){if(this.result_deselect(e[0].getAttribute("data-option-array-index"))){this.show_search_field_default();this.is_multiple&&this.choices_count()>0&&this.search_field.val().length<1&&this.results_hide();e.parents("li").first().remove();return this.search_field_scale()}};n.prototype.results_reset=function(){this.form_field.options[0].selected=!0;this.selected_option_count=null;this.single_set_selected_text();this.show_search_field_default();this.results_reset_cleanup();this.form_field_jq.trigger("change");if(this.active_field)return this.results_hide()};n.prototype.results_reset_cleanup=function(){this.current_selectedIndex=this.form_field.selectedIndex;return this.selected_item.find("abbr").remove()};n.prototype.result_select=function(e){var t,n,r;if(this.result_highlight){t=this.result_highlight;this.result_clear_highlight();if(this.is_multiple&&this.max_selected_options<=this.choices_count()){this.form_field_jq.trigger("chosen:maxselected",{chosen:this});return!1}if(this.is_multiple)t.removeClass("active-result");else{if(this.result_single_selected){this.result_single_selected.removeClass("result-selected");r=this.result_single_selected[0].getAttribute("data-option-array-index");this.results_data[r].selected=!1}this.result_single_selected=t}t.addClass("result-selected");n=this.results_data[t[0].getAttribute("data-option-array-index")];n.selected=!0;this.form_field.options[n.options_index].selected=!0;this.selected_option_count=null;this.is_multiple?this.choice_build(n):this.single_set_selected_text(n.text);(!e.metaKey&&!e.ctrlKey||!this.is_multiple)&&this.results_hide();this.search_field.val("");(this.is_multiple||this.form_field.selectedIndex!==this.current_selectedIndex)&&this.form_field_jq.trigger("change",{selected:this.form_field.options[n.options_index].value});this.current_selectedIndex=this.form_field.selectedIndex;return this.search_field_scale()}};n.prototype.single_set_selected_text=function(e){e==null&&(e=this.default_text);if(e===this.default_text)this.selected_item.addClass("chosen-default");else{this.single_deselect_control_build();this.selected_item.removeClass("chosen-default")}return this.selected_item.find("span").text(e)};n.prototype.result_deselect=function(e){var t;t=this.results_data[e];if(!this.form_field.options[t.options_index].disabled){t.selected=!1;this.form_field.options[t.options_index].selected=!1;this.selected_option_count=null;this.result_clear_highlight();this.results_showing&&this.winnow_results();this.form_field_jq.trigger("change",{deselected:this.form_field.options[t.options_index].value});this.search_field_scale();return!0}return!1};n.prototype.single_deselect_control_build=function(){if(!this.allow_single_deselect)return;this.selected_item.find("abbr").length||this.selected_item.find("span").first().after('<abbr class="search-choice-close"></abbr>');return this.selected_item.addClass("chosen-single-with-deselect")};n.prototype.get_search_text=function(){return this.search_field.val()===this.default_text?"":e("<div/>").text(e.trim(this.search_field.val())).html()};n.prototype.winnow_results_set_highlight=function(){var e,t;t=this.is_multiple?[]:this.search_results.find(".result-selected.active-result");e=t.length?t.first():this.search_results.find(".active-result").first();if(e!=null)return this.result_do_highlight(e)};n.prototype.no_results=function(t){var n;n=e('<li class="no-results">'+this.results_none_found+' "<span></span>"</li>');n.find("span").first().html(t);return this.search_results.append(n)};n.prototype.no_results_clear=function(){return this.search_results.find(".no-results").remove()};n.prototype.keydown_arrow=function(){var e;if(!this.results_showing||!this.result_highlight)return this.results_show();e=this.result_highlight.nextAll("li.active-result").first();if(e)return this.result_do_highlight(e)};n.prototype.keyup_arrow=function(){var e;if(!this.results_showing&&!this.is_multiple)return this.results_show();if(this.result_highlight){e=this.result_highlight.prevAll("li.active-result");if(e.length)return this.result_do_highlight(e.first());this.choices_count()>0&&this.results_hide();return this.result_clear_highlight()}};n.prototype.keydown_backstroke=function(){var e;if(this.pending_backstroke){this.choice_destroy(this.pending_backstroke.find("a").first());return this.clear_backstroke()}e=this.search_container.siblings("li.search-choice").last();if(e.length&&!e.hasClass("search-choice-disabled")){this.pending_backstroke=e;return this.single_backstroke_delete?this.keydown_backstroke():this.pending_backstroke.addClass("search-choice-focus")}};n.prototype.clear_backstroke=function(){this.pending_backstroke&&this.pending_backstroke.removeClass("search-choice-focus");return this.pending_backstroke=null};n.prototype.keydown_checker=function(e){var t,n;t=(n=e.which)!=null?n:e.keyCode;this.search_field_scale();t!==8&&this.pending_backstroke&&this.clear_backstroke();switch(t){case 8:this.backstroke_length=this.search_field.val().length;break;case 9:this.results_showing&&!this.is_multiple&&this.result_select(e);this.mouse_on_container=!1;break;case 13:e.preventDefault();break;case 38:e.preventDefault();this.keyup_arrow();break;case 40:e.preventDefault();this.keydown_arrow()}};n.prototype.search_field_scale=function(){var t,n,r,i,s,o,u,a,f;if(this.is_multiple){r=0;u=0;s="position:absolute; left: -1000px; top: -1000px; display:none;";o=["font-size","font-style","font-weight","font-family","line-height","text-transform","letter-spacing"];for(a=0,f=o.length;a<f;a++){i=o[a];s+=i+":"+this.search_field.css(i)+";"}t=e("<div />",{style:s});t.text(this.search_field.val());e("body").append(t);u=t.width()+25;t.remove();n=this.container.outerWidth();u>n-10&&(u=n-10);return this.search_field.css({width:u+"px"})}};return n}(t)}).call(this);                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              /* global htmlSettingsTaxLocalizeScript, ajaxurl */
/**
 * Used by woocommerce/includes/admin/settings/views/html-settings-tax.php
 */

( function( $, data, wp, ajaxurl ) {
	$( function() {

		if ( ! String.prototype.trim ) {
			String.prototype.trim = function () {
				return this.replace( /^[\s\uFEFF\xA0]+|[\s\uFEFF\xA0]+$/g, '' );
			};
		}

		var rowTemplate        = wp.template( 'wc-tax-table-row' ),
			rowTemplateEmpty   = wp.template( 'wc-tax-table-row-empty' ),
			paginationTemplate = wp.template( 'wc-tax-table-pagination' ),
			$table             = $( '.wc_tax_rates' ),
			$tbody             = $( '#rates' ),
			$save_button       = $( 'input[name="save"]' ),
			$pagination        = $( '#rates-pagination' ),
			$search_field      = $( '#rates-search .wc-tax-rates-search-field' ),
			$submit            = $( '.submit .button-primary[type=submit]' ),
			WCTaxTableModelConstructor = Backbone.Model.extend({
				changes: {},
				setRateAttribute: function( rateID, attribute, value ) {
					var rates   = _.indexBy( this.get( 'rates' ), 'tax_rate_id' ),
						changes = {};

					if ( rates[ rateID ][ attribute ] !== value ) {
						changes[ rateID ] = {};
						changes[ rateID ][ attribute ] = value;
						rates[ rateID ][ attribute ]   = value;
					}

					this.logChanges( changes );
				},
				logChanges: function( changedRows ) {
					var changes = this.changes || {};

					_.each( changedRows, function( row, id ) {
						changes[ id ] = _.extend( changes[ id ] || { tax_rate_id : id }, row );
					} );

					this.changes = changes;
					this.trigger( 'change:rates' );
				},
				getFilteredRates: function() {
					var rates  = this.get( 'rates' ),
						search = $search_field.val().toLowerCase();

					if ( search.length ) {
						rates = _.filter( rates, function( rate ) {
							var search_text = _.toArray( rate ).join( ' ' ).toLowerCase();
							return ( -1 !== search_text.indexOf( search ) );
						} );
					}

					rates = _.sortBy( rates, function( rate ) {
						return parseInt( rate.tax_rate_order, 10 );
					} );

					return rates;
				},
				block: function() {
					$( '.wc_tax_rates' ).block({
						message: null,
						overlayCSS: {
							background: '#fff',
							opacity: 0.6
						}
					});
				},
				unblock: function() {
					$( '.wc_tax_rates' ).unblock();
				},
				save: function() {
					var self = this;

					self.block();

					Backbone.ajax({
						method: 'POST',
						dataType: 'json',
						url: ajaxurl + '?action=woocommerce_tax_rates_save_changes',
						data: {
							current_class: data.current_class,
							wc_tax_nonce: data.wc_tax_nonce,
							changes: self.changes
						},
						success: function( response, textStatus ) {
							if ( 'success' === textStatus ) {
								WCTaxTableModelInstance.set( 'rates', response.data.rates );
								WCTaxTableModelInstance.trigger( 'change:rates' );

								WCTaxTableModelInstance.changes = {};
								WCTaxTableModelInstance.trigger( 'saved:rates' );
							}

							self.unblock();
						}
					});
				}
			} ),
			WCTaxTableViewConstructor = Backbone.View.extend({
				rowTemplate: rowTemplate,
				per_page: data.limit,
				page: data.page,
				initialize: function() {
					var qty_pages = Math.ceil( _.toArray( this.model.get( 'rates' ) ).length / this.per_page );

					this.qty_pages = 0 === qty_pages ? 1 : qty_pages;
					this.page = this.sanitizePage( data.page );

					this.listenTo( this.model, 'change:rates', this.setUnloadConfirmation );
					this.listenTo( this.model, 'saved:rates', this.clearUnloadConfirmation );
					$tbody.on( 'change', ':input', { view: this }, this.updateModelOnChange );
					$tbody.on( 'sortupdate', { view: this }, this.updateModelOnSort );
					$search_field.on( 'keyup search', { view: this }, this.onSearchField );
					$pagination.on( 'click', 'a', { view: this }, this.onPageChange );
					$pagination.on( 'change', 'input', { view: this }, this.onPageChange );
					$( window ).on( 'beforeunload', { view: this }, this.unloadConfirmation );
					$submit.on( 'click', { view: this }, this.onSubmit );
					$save_button.attr( 'disabled','disabled' );

					// Can bind these directly to the buttons, as they won't get overwritten.
					$table.find( '.insert' ).on( 'click', { view: this }, this.onAddNewRow );
					$table.find( '.remove_tax_rates' ).on( 'click', { view: this }, this.onDeleteRow );
					$table.find( '.export' ).on( 'click', { view: this }, this.onExport );
				},
				render: function() {
					var rates       = this.model.getFilteredRates(),
						qty_rates   = _.size( rates ),
						qty_pages   = Math.ceil( qty_rates / this.per_page ),
						first_index = 0 === qty_rates ? 0 : this.per_page * ( this.page - 1 ),
						last_index  = this.per_page * this.page,
						paged_rates = _.toArray( rates ).slice( first_index, last_index ),
						view        = this;

					// Blank out the contents.
					this.$el.empty();

					if ( paged_rates.length ) {
						// Populate $tbody with the current page of results.
						$.each( paged_rates, function( id, rowData ) {
							view.$el.append( view.rowTemplate( rowData ) );
						} );
					} else {
						view.$el.append( rowTemplateEmpty() );
					}

					// Initialize autocomplete for countries.
					this.$el.find( 'td.country input' ).autocomplete({
						source: data.countries,
						minLength: 2
					});

					// Initialize autocomplete for states.
					this.$el.find( 'td.state input' ).autocomplete({
						source: data.states,
						minLength: 3
					});

					// Postcode and city don't have `name` values by default. They're only created if the contents changes, to save on database queries (I think)
					this.$el.find( 'td.postcode input, td.city input' ).change( function() {
						$( this ).attr( 'name', $( this ).data( 'name' ) );
					});

					if ( qty_pages > 1 ) {
						// We've now displayed our initial page, time to render the pagination box.
						$pagination.html( paginationTemplate( {
							qty_rates:    qty_rates,
							current_page: this.page,
							qty_pages:    qty_pages
						} ) );
					} else {
						$pagination.empty();
						view.page = 1;
					}

					// Disable sorting if there is a search term filtering the items.
					if ( $search_field.val() ) {
						$tbody.sortable( 'disable' );
					} else {
						$tbody.sortable( 'enable' );
					}
				},
				updateUrl: function() {
					if ( ! window.history.replaceState ) {
						return;
					}

					var url    = data.base_url,
						search = $search_field.val();

					if ( 1 < this.page ) {
						url += '&p=' + encodeURIComponent( this.page );
					}

					if ( search.length ) {
						url += '&s=' + encodeURIComponent( search );
					}

					window.history.replaceState( {}, '', url );
				},
				onSubmit: function( event ) {
					event.data.view.model.save();
					event.preventDefault();
				},
				onAddNewRow: function( event ) {
					var view    = event.data.view,
						model   = view.model,
						rates   = _.indexBy( model.get( 'rates' ), 'tax_rate_id' ),
						changes = {},
						size    = _.size( rates ),
						newRow  = _.extend( {}, data.default_rate, {
							tax_rate_id: 'new-' + size + '-' + Date.now(),
							newRow:      true
						} ),
						$current, current_id, current_order, rates_to_reorder, reordered_rates;

					$current = $tbody.children( '.current' );

					if ( $current.length ) {
						current_id            = $current.last().data( 'id' );
						current_order         = parseInt( rates[ current_id ].tax_rate_order, 10 );
						newRow.tax_rate_order = 1 + current_order;

						rates_to_reorder = _.filter( rates, function( rate ) {
							if ( parseInt( rate.tax_rate_order, 10 ) > current_order ) {
								return true;
							}
							return false;
						} );

						reordered_rates = _.map( rates_to_reorder, function( rate ) {
							rate.tax_rate_order++;
							changes[ rate.tax_rate_id ] = _.extend( changes[ rate.tax_rate_id ] || {}, { tax_rate_order : rate.tax_rate_order } );
							return rate;
						} );
					} else {
						newRow.tax_rate_order = 1 + _.max(
							_.pluck( rates, 'tax_rate_order' ),
							function ( val ) {
								// Cast them all to integers, because strings compare funky. Sighhh.
								return parseInt( val, 10 );
							}
						);
						// Move the last page
						view.page = view.qty_pages;
					}

					rates[ newRow.tax_rate_id ]   = newRow;
					changes[ newRow.tax_rate_id ] = newRow;

					model.set( 'rates', rates );
					model.logChanges( changes );

					view.render();
				},
				onDeleteRow: function( event ) {
					var view    = event.data.view,
						model   = view.model,
						rates   = _.indexBy( model.get( 'rates' ), 'tax_rate_id' ),
						changes = {},
						$current, current_id;

					event.preventDefault();

					if ( $current = $tbody.children( '.current' ) ) {
						$current.each(function(){
							current_id    = $( this ).data('id');

							delete rates[ current_id ];

							changes[ current_id ] = _.extend( changes[ current_id ] || {}, { deleted : 'deleted' } );
						});

						model.set( 'rates', rates );
						model.logChanges( changes );

						view.render();
					} else {
						window.alert( data.strings.no_rows_selected );
					}
				},
				onSearchField: function( event ){
					event.data.view.updateUrl();
					event.data.view.render();
				},
				onPageChange: function( event ) {
					var $target  = $( event.currentTarget );

					event.preventDefault();
					event.data.view.page = $target.data( 'goto' ) ? $target.data( 'goto' ) : $target.val();
					event.data.view.render();
					event.data.view.updateUrl();
				},
				onExport: function( event ) {
					var csv_data = 'data:application/csv;charset=utf-8,' + data.strings.csv_data_cols.join(',') + '\n';

					$.each( event.data.view.model.getFilteredRates(), function( id, rowData ) {
						var row = '';

						row += rowData.tax_rate_country  + ',';
						row += rowData.tax_rate_state    + ',';
						row += ( rowData.postcode        ? rowData.postcode.join( '; ' ) : '' ) + ',';
						row += ( rowData.city            ? rowData.city.join( '; ' )     : '' ) + ',';
						row += rowData.tax_rate          + ',';
						row += rowData.tax_rate_name     + ',';
						row += rowData.tax_rate_priority + ',';
						row += rowData.tax_rate_compound + ',';
						row += rowData.tax_rate_shipping + ',';
						row += data.current_class;

						csv_data += row + '\n';
					});

					$( this ).attr( 'href', encodeURI( csv_data ) );

					return true;
				},
				setUnloadConfirmation: function() {
					this.needsUnloadConfirm = true;
					$save_button.removeAttr( 'disabled' );
				},
				clearUnloadConfirmation: function() {
					this.needsUnloadConfirm = false;
					$save_button.attr( 'disabled', 'disabled' );
				},
				unloadConfirmation: function( event ) {
					if ( event.data.view.needsUnloadConfirm ) {
						event.returnValue = data.strings.unload_confirmation_msg;
						window.event.returnValue = data.strings.unload_confirmation_msg;
						return data.strings.unload_confirmation_msg;
					}
				},
				updateModelOnChange: function( event ) {
					var model     = event.data.view.model,
						$target   = $( event.target ),
						id        = $target.closest( 'tr' ).data( 'id' ),
						attribute = $target.data( 'attribute' ),
						val       = $target.val();

					if ( 'city' === attribute || 'postcode' === attribute ) {
						val = val.split( ';' );
						val = $.map( val, function( thing ) {
							return thing.trim();
						});
					}

					if ( 'tax_rate_compound' === attribute || 'tax_rate_shipping' === attribute ) {
						if ( $target.is( ':checked' ) ) {
							val = 1;
						} else {
							val = 0;
						}
					}

					model.setRateAttribute( id, attribute, val );
				},
				updateModelOnSort: function( event ) {
					var view         = event.data.view,
						model        = view.model,
						rates        = _.indexBy( model.get( 'rates' ), 'tax_rate_id' ),
						changes      = {};

					_.each( rates, function( rate ) {
						var new_position = 0;
						var old_position = parseInt( rate.tax_rate_order, 10 );

						if ( $table.find( 'tr[data-id="' + rate.tax_rate_id + '"]').size() ) {
							new_position = parseInt( $table.find( 'tr[data-id="' + rate.tax_rate_id + '"]').index(), 10 ) + parseInt( ( view.page - 1 ) * view.per_page, 10 );
						} else {
							new_position = old_position;
						}

						if ( old_position !== new_position ) {
							changes[ rate.tax_rate_id ] = _.extend( changes[ rate.tax_rate_id ] || {}, { tax_rate_order : new_position } );
						}
					} );

					if ( _.size( changes ) ) {
						model.logChanges( changes );
					}
				},
				sanitizePage: function( page_num ) {
					page_num = parseInt( page_num, 10 );
					if ( page_num < 1 ) {
						page_num = 1;
					} else if ( page_num > this.qty_pages ) {
						page_num = this.qty_pages;
					}
					return page_num;
				}
			} ),
			WCTaxTableModelInstance = new WCTaxTableModelConstructor({
				rates: data.rates
			} ),
			WCTaxTableInstance = new WCTaxTableViewConstructor({
				model:    WCTaxTableModelInstance,
				el:       '#rates'
			} );

		WCTaxTableInstance.render();

	});
})( jQuery, htmlSettingsTaxLocalizeScript, wp, ajaxurl );
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      /*global woocommerce_admin_meta_boxes */
jQuery( function( $ ) {

	// Scroll to first checked category - https://github.com/scribu/wp-category-checklist-tree/blob/d1c3c1f449e1144542efa17dde84a9f52ade1739/category-checklist-tree.php
	$( function() {
		$( '[id$="-all"] > ul.categorychecklist' ).each( function() {
			var $list = $( this );
			var $firstChecked = $list.find( ':checked' ).first();

			if ( ! $firstChecked.length ) {
				return;
			}

			var pos_first   = $list.find( 'input' ).position().top;
			var pos_checked = $firstChecked.position().top;

			$list.closest( '.tabs-panel' ).scrollTop( pos_checked - pos_first + 5 );
		});
	});

	// Prevent enter submitting post form
	$( '#upsell_product_data' ).bind( 'keypress', function( e ) {
		if ( e.keyCode === 13 ) {
			return false;
		}
	});

	// Type box
	$( '.type_box' ).appendTo( '#woocommerce-produ