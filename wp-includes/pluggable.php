=e?a.Deferred().reject().promise():(this._diffId=e,this.trigger("update:revisions",c,d),f=this.diffs.get(e),f?(this.receiveDiff(f),a.Deferred().resolve().promise()):b.immediate?this._ensureDiff():(this._debouncedEnsureDiff(),a.Deferred().reject().promise()))},changeRevisionHandler:function(){this.updateDiff()},receiveDiff:function(a){_.isUndefined(a)||_.isUndefined(a.id)?this.set({loading:!1,error:!0}):this._diffId===a.id&&this.trigger("update:diff",a)},_ensureDiff:function(){return this.diffs.ensure(this._diffId,this).always(this.receiveDiff)}}),b.view.Frame=wp.Backbone.View.extend({className:"revisions",template:wp.template("revisions-frame"),initialize:function(){this.listenTo(this.model,"update:diff",this.renderDiff),this.listenTo(this.model,"change:compareTwoMode",this.updateCompareTwoMode),this.listenTo(this.model,"change:loading",this.updateLoadingStatus),this.listenTo(this.model,"change:error",this.updateErrorStatus),this.views.set(".revisions-control-frame",new b.view.Controls({model:this.model}))},render:function(){return wp.Backbone.View.prototype.render.apply(this,arguments),a("html").css("overflow-y","scroll"),a("#wpbody-content .wrap").append(this.el),this.updateCompareTwoMode(),this.renderDiff(this.model.diff()),this.views.ready(),this},renderDiff:function(a){this.views.set(".revisions-diff-frame",new b.view.Diff({model:a}))},updateLoadingStatus:function(){this.$el.toggleClass("loading",this.model.get("loading"))},updateErrorStatus:function(){this.$el.toggleClass("diff-error",this.model.get("error"))},updateCompareTwoMode:function(){this.$el.toggleClass("comparing-two-revisions",this.model.get("compareTwoMode"))}}),b.view.Controls=wp.Backbone.View.extend({className:"revisions-controls",initialize:function(){_.bindAll(this,"setWidth"),this.views.add(new b.view.Buttons({model:this.model})),this.views.add(new b.view.Checkbox({model:this.model}));var a=new b.model.Slider({frame:this.model,revisions:this.model.revisions}),c=new b.model.Tooltip({frame:this.model,revisions:this.model.revisions,slider:a});this.views.add(new b.view.Tooltip({model:c})),this.views.add(new b.view.Tickmarks({model:c})),this.views.add(new b.view.Slider({model:a})),this.views.add(new b.view.Metabox({model:this.model}))},ready:function(){this.top=this.$el.offset().top,this.window=a(window),this.window.on("scroll.wp.revisions",{controls:this},function(a){var b=a.data.controls,c=b.$el.parent(),d=b.window.scrollTop(),e=b.views.parent;d>=b.top?(e.$el.hasClass("pinned")||(b.setWidth(),c.css("height",c.height()+"px"),b.window.on("resize.wp.revisions.pinning click.wp.revisions.pinning",{controls:b},function(a){a.data.controls.setWidth()})),e.$el.addClass("pinned")):e.$el.hasClass("pinned")?(b.window.off(".wp.revisions.pinning"),b.$el.css("width","auto"),e.$el.removeClass("pinned"),c.css("height","auto"),b.top=b.$el.offset().top):b.top=b.$el.offset().top})},setWidth:function(){this.$el.css("width",this.$el.parent().width()+"px")}}),b.view.Tickmarks=wp.Backbone.View.extend({className:"revisions-tickmarks",direction:isRtl?"right":"left",initialize:function(){this.listenTo(this.model,"change:revision",this.reportTickPosition)},reportTickPosition:function(a,b){var c,d,e,f,g=this.model.revisions.indexOf(b);d=this.$el.allOffsets(),e=this.$el.parent().allOffsets(),g===this.model.revisions.length-1?c={rightPlusWidth:d.left-e.left+1,leftPlusWidth:d.right-e.right+1}:(f=this.$("div:nth-of-type("+(g+1)+")"),c=f.allPositions(),_.extend(c,{left:c.left+d.left-e.left,right:c.right+d.right-e.right}),_.extend(c,{leftPlusWidth:c.left+f.outerWidth(),rightPlusWidth:c.right+f.outerWidth()})),this.model.set({offset:c})},ready:function(){var a,b;a=this.model.revisions.length-1,b=1/a,this.$el.css("width",50*this.model.revisions.length+"px"),_(a).times(function(a){this.$el.append('<div style="'+this.direction+": "+100*b*a+'%"></div>')},this)}}),b.view.Metabox=wp.Backbone.View.extend({className:"revisions-meta",initialize:function(){this.views.add(new b.view.MetaFrom({model:this.model,className:"diff-meta diff-meta-from"})),this.views.add(new b.view.MetaTo({model:this.model}))}}),b.view.Meta=wp.Backbone.View.extend({template:wp.template("revisions-meta"),events:{"click .restore-revision":"restoreRevision"},initialize:function(){this.listenTo(this.model,"update:revisions",this.render)},prepare:function(){return _.extend(this.model.toJSON()[this.type]||{},{type:this.type})},restoreRevision:function(){document.location=this.model.get("to").attributes.restoreUrl}}),b.view.MetaFrom=b.view.Meta.extend({className:"diff-meta diff-meta-from",type:"from"}),b.view.MetaTo=b.view.Meta.extend({className:"diff-meta diff-meta-to",type:"to"}),b.view.Checkbox=wp.Backbone.View.extend({className:"revisions-checkbox",template:wp.template("revisions-checkbox"),events:{"click .compare-two-revisions":"compareTwoToggle"},initialize:function(){this.listenTo(this.model,"change:compareTwoMode",this.updateCompareTwoMode)},ready:function(){this.model.revisions.length<3&&a(".revision-toggle-compare-mode").hide()},updateCompareTwoMode:function(){this.$(".compare-two-revisions").prop("checked",this.model.get("compareTwoMode"))},compareTwoToggle:function(){this.model.set({compareTwoMode:a(".compare-two-revisions").prop("checked")})}}),b.view.Tooltip=wp.Backbone.View.extend({className:"revisions-tooltip",template:wp.template("revisions-meta"),initialize:function(){this.listenTo(this.model,"change:offset",this.render),this.listenTo(this.model,"change:hovering",this.toggleVisibility),this.listenTo(this.model,"change:scrubbing",this.toggleVisibility)},prepare:function(){return _.isNull(this.model.get("revision"))?void 0:_.extend({type:"tooltip"},{attributes:this.model.get("revision").toJSON()})},render:function(){var a,b,c,d,e={},f=this.model.revisions.indexOf(this.model.get("revision"))+1;d=f/this.model.revisions.length>.5,isRtl?(b=d?"left":"right",c=d?"leftPlusWidth":b):(b=d?"right":"left",c=d?"rightPlusWidth":b),a="right"===b?"left":"right",wp.Backbone.View.prototype.render.apply(this,arguments),e[b]=this.model.get("offset")[c]+"px",e[a]="",this.$el.toggleClass("flipped",d).css(e)},visible:function(){return this.model.get("scrubbing")||this.model.get("hovering")},toggleVisibility:function(){this.visible()?this.$el.stop().show().fadeTo(100-100*this.el.style.opacity,1):this.$el.stop().fadeTo(300*this.el.style.opacity,0,function(){a(this).hide()})}}),b.view.Buttons=wp.Backbone.View.extend({className:"revisions-buttons",template:wp.template("revisions-buttons"),events:{"click .revisions-next .button":"nextRevision","click .revisions-previous .button":"previousRevision"},initialize:function(){this.listenTo(this.model,"update:revisions",this.disabledButtonCheck)},ready:function(){this.disabledButtonCheck()},gotoModel:function(a){var b={to:this.model.revisions.at(a)};a?b.from=this.model.revisions.at(a-1):this.model.unset("from",{silent:!0}),this.model.set(b)},nextRevision:function(){var a=this.model.revisions.indexOf(this.model.get("to"))+1;this.gotoModel(a)},previousRevision:function(){var a=this.model.revisions.indexOf(this.model.get("to"))-1;this.gotoModel(a)},disabledButtonCheck:function(){var b=this.model.revisions.length-1,c=0,d=a(".revisions-next .button"),e=a(".revisions-previous .button"),f=this.model.revisions.indexOf(this.model.get("to"));d.prop("disabled",b===f),e.prop("disabled",c===f)}}),b.view.Slider=wp.Backbone.View.extend({className:"wp-slider",direction:isRtl?"right":"left",events:{mousemove:"mouseMove"},initialize:function(){_.bindAll(this,"start","slide","stop","mouseMove","mouseEnter","mouseLeave"),this.listenTo(this.model,"update:slider",this.applySliderSettings)},ready:function(){this.$el.css("width",50*this.model.revisions.length+"px"),this.$el.slider(_.extend(this.model.toJSON(),{start:this.start,slide:this.slide,stop:this.stop})),this.$el.hoverIntent({over:this.mouseEnter,out:this.mouseLeave,timeout:800}),this.applySliderSettings()},mouseMove:function(b){var c=this.model.revisions.length-1,d=this.$el.allOffsets()[this.direction],e=this.$el.width(),f=e/c,g=(isRtl?a(window).width()-b.pageX:b.pageX)-d,h=Math.floor((g+f/2)/f);0>h?h=0:h>=this.model.revisions.length&&(h=this.model.revisions.length-1),this.model.set({hoveredRevision:this.model.revisions.at(h)})},mouseLeave:function(){this.model.set({hovering:!1})},mouseEnter:function(){this.model.set({hovering:!0})},applySliderSettings:function(){this.$el.slider(_.pick(this.model.toJSON(),"value","values","range"));var a=this.$("a.ui-slider-handle");this.model.get("compareTwoMode")?(a.first().toggleClass("to-handle",!!isRtl).toggleClass("from-handle",!isRtl),a.last().toggleClass("from-handle",!!isRtl).toggleClass("to-handle",!isRtl)):a.removeClass("from-handle to-handle")},start:function(b,c){this.model.set({scrubbing:!0}),a(window).on("mousemove.wp.revisions",{view:this},function(b){var d,e=b.data.view,f=e.$el.offset().left,g=f,h=f+e.$el.width(),i=h,j="0",k="100%",l=a(c.handle);e.model.get("compareTwoMode")&&(d=l.parent().find(".ui-slider-handle"),l.is(d.first())?(i=d.last().offset().left,k=i-g):(f=d.first().offset().left+d.first().width(),j=f-g)),b.pageX<f?l.css("left",j):b.pageX>i?l.css("left",k):l.css("left",b.pageX-g)})},getPosition:function(a){return isRtl?this.model.revisions.length-a-1:a},slide:function(a,b){var c,d;if(this.model.get("compareTwoMode")){if(b.values[1]===b.values[0])return!1;isRtl&&b.values.reverse(),c={from:this.model.revisions.at(this.getPosition(b.values[0])),to:this.model.revisions.at(this.getPosition(b.values[1]))}}else c={to:this.model.revisions.at(this.getPosition(b.value))},this.getPosition(b.value)>0?c.from=this.model.revisions.at(this.getPosition(b.value)-1):c.from=void 0;d=this.model.revisions.at(this.getPosition(b.value)),this.model.get("scrubbing")&&(c.hoveredRevision=d),this.model.set(c)},stop:function(){a(window).off("mousemove.wp.revisions"),this.model.updateSliderSettings(),this.model.set({scrubbing:!1})}}),b.view.Diff=wp.Backbone.View.extend({className:"revisions-diff",template:wp.template("revisions-diff"),prepare:function(){return _.extend({fields:this.model.fields.toJSON()},this.options)}}),b.Router=Backbone.Router.extend({initialize:function(a){this.model=a.model,this.listenTo(this.model,"update:diff",_.debounce(this.updateUrl,250)),this.listenTo(this.model,"change:compareTwoMode",this.updateUrl)},baseUrl:function(a){return this.model.get("baseUrl")+a},updateUrl:function(){var a=this.model.has("from")?this.model.get("from").id:0,b=this.model.get("to").id;this.model.get("compareTwoMode")?this.navigate(this.baseUrl("?from="+a+"&to="+b),{replace:!0}):this.navigate(this.baseUrl("?revision="+b),{replace:!0})},handleRoute:function(a,b){var c=_.isUndefined(b);c||(b=this.model.revisions.get(a),a=this.model.revisions.prev(b),b=b?b.id:0,a=a?a.id:0)}}),b.init=function(){var a;window.adminpage&&"revision-php"===window.adminpage&&(a=new b.model.FrameState({initialDiffState:{to:parseInt(b.settings.to,10),from:parseInt(b.settings.from,10),compareTwoMode:"1"===b.settings.compareTwoMode},diffData:b.settings.diffData,baseUrl:b.settings.baseUrl,postId:parseInt(b.settings.postId,10)},{revisions:new b.model.Revisions(b.settings.revisionData)}),b.view.frame=new b.view.Frame({model:a}).render())},a(b.init)}(jQuery);                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    /**
 * PressThis App
 *
 */
( function( $, window ) {
	var PressThis = function() {
		var editor, $mediaList, $mediaThumbWrap,
			$window               = $( window ),
			$document             = $( document ),
			saveAlert             = false,
			textarea              = document.createElement( 'textarea' ),
			sidebarIsOpen         = false,
			settings              = window.wpPressThisConfig || {},
			data                  = window.wpPressThisData || {},
			smallestWidth         = 128,
			hasSetFocus           = false,
			catsCache             = [],
			isOffScreen           = 'is-off-screen',
			isHidden              = 'is-hidden',
			offscreenHidden       = isOffScreen + ' ' + isHidden,
			iOS                   = /iPad|iPod|iPhone/.test( window.navigator.userAgent ),
			$textEditor           = $( '#pressthis' ),
			textEditor            = $textEditor[0],
			textEditorMinHeight   = 600,
			textLength            = 0,
			transitionEndEvent    = ( function() {
				var style = document.documentElement.style;

				if ( typeof style.transition !== 'undefined' ) {
					return 'transitionend';
				}

				if ( typeof style.WebkitTransition !== 'undefined' ) {
					return 'webkitTransitionEnd';
				}

				return false;
			}() );

		/* ***************************************************************
		 * HELPER FUNCTIONS
		 *************************************************************** */

		/**
		 * Emulates our PHP __() gettext function, powered by the strings exported in pressThisL10n.
		 *
		 * @param key string Key of the string to be translated, as found in pressThisL10n.
		 * @returns string Original or translated string, or empty string if no key.
		 */
		function __( key ) {
			if ( key && window.pressThisL10n ) {
				return window.pressThisL10n[key] || key;
			}

			return key || '';
		}

		/**
		 * Strips HTML tags
		 *
		 * @param string string Text to have the HTML tags striped out of.
		 * @returns string Stripped text.
		 */
		function stripTags( string ) {
			string = string || '';

			return string
				.replace( /<!--[\s\S]*?(-->|$)/g, '' )
				.replace( /<(script|style)[^>]*>[\s\S]*?(<\/\1>|$)/ig, '' )
				.replace( /<\/?[a-z][\s\S]*?(>|$)/ig, '' );
		}

		/**
		 * Strip HTML tags and convert HTML entities.
		 *
		 * @param text string Text.
		 * @returns string Sanitized text.
		 */
		function sanitizeText( text ) {
			var _text = stripTags( text );

			try {
				textarea.innerHTML = _text;
				_text = stripTags( textarea.value );
			} catch ( er ) {}

			return _text;
		}

		/**
		 * Allow only HTTP or protocol relative URLs.
		 *
		 * @param url string The URL.
		 * @returns string Processed URL.
		 */
		function checkUrl( url ) {
			url = $.trim( url || '' );

			if ( /^(?:https?:)?\/\//.test( url ) ) {
				url = stripTags( url );
				return url.replace( /["\\]+/g, '' );
			}

			return '';
		}

		/**
		 * Show UX spinner
		 */
		function showSpinner() {
			$( '.spinner' ).addClass( 'is-active' );
			$( '.post-actions button' ).attr( 'disabled', 'disabled' );
		}

		/**
		 * Hide UX spinner
		 */
		function hideSpinner() {
			$( '.spinner' ).removeClass( 'is-active' );
			$( '.post-actions button' ).removeAttr( 'disabled' );
		}

		function textEditorResize( reset ) {
			var pageYOffset, height;

			if ( editor && ! editor.isHidden() ) {
 				return;
 			}

			reset = ( reset === 'reset' ) || ( textLength && textLength > textEditor.value.length );
			height = textEditor.style.height;

			if ( reset ) {
				pageYOffset = window.pageYOffset;

				textEditor.style.height = 'auto';
				textEditor.style.height = Math.max( textEditor.scrollHeight, textEditorMinHeight ) + 'px';
				window.scrollTo( window.pageXOffset, pageYOffset );
			} else if ( parseInt( textEditor.style.height, 10 ) < textEditor.scrollHeight ) {
				textEditor.style.height = textEditor.scrollHeight + 'px';
 			}

 			textLength = textEditor.value.length;
 		}

 		function mceGetCursorOffset() {
			if ( ! editor ) {
				return false;
			}

			var node = editor.selection.getNode(),
				range, view, offset;

			if ( editor.wp && editor.wp.getView && ( view = editor.wp.getView( node ) ) ) {
				offset = view.getBoundingClientRect();
			} else {
				range = editor.selection.getRng();

				try {
					offset = range.getClientRects()[0];
				} catch( er ) {}

				if ( ! offset ) {
					offset = node.getBoundingClientRect();
				}
			}

			return offset.height ? offset : false;
		}

		// Make sure the caret is always visible.
		function mceKeyup( event ) {
			var VK = window.tinymce.util.VK,
				key = event.keyCode;

			// Bail on special keys.
			if ( key <= 47 && ! ( key === VK.SPACEBAR || key === VK.ENTER || key === VK.DELETE || key === VK.BACKSPACE || key === VK.UP || key === VK.LEFT || key === VK.DOWN || key === VK.UP ) ) {
				return;
			// OS keys, function keys, num lock, scroll lock
			} else if ( ( key >= 91 && key <= 93 ) || ( key >= 112 && key <= 123 ) || key === 144 || key === 145 ) {
				return;
			}

			mceScroll( key );
		}

		function mceScroll( key ) {
			var cursorTop, cursorBottom, editorBottom,
				offset = mceGetCursorOffset(),
				bufferTop = 50,
				bufferBottom = 65,
				VK = window.tinymce.util.VK;

			if ( ! offset ) {
				return;
			}

			cursorTop = offset.top + editor.iframeElement.getBoundingClientRect().top;
			cursorBottom = cursorTop + offset.height;
			cursorTop = cursorTop - bufferTop;
			cursorBottom = cursorBottom + bufferBottom;
			editorBottom = $window.height();

			// Don't scroll if the node is taller than the visible part of the editor
			if ( editorBottom < offset.height ) {
				return;
			}

			if ( cursorTop < 0 && ( key === VK.UP || key === VK.LEFT || key === VK.BACKSPACE ) ) {
				window.scrollTo( window.pageXOffset, cursorTop + window.pageYOffset );
			} else if ( cursorBottom > editorBottom ) {
				window.scrollTo( window.pageXOffset, cursorBottom + window.pageYOffset - editorBottom );
			}
		}

		/**
		 * Replace emoji images with chars and sanitize the text content.
		 */
		function getTitleText() {
			var $element = $( '#title-container' );

			$element.find( 'img.emoji' ).each( function() {
				var $image = $( this );
				$image.replaceWith( $( '<span>' ).text( $image.attr( 'alt' ) ) );
			});

			return sanitizeText( $element.text() );
		}

		/**
		 * Prepare the form data for saving.
		 */
		function prepareFormData() {
			var $form = $( '#pressthis-form' ),
				$input = $( '<input type="hidden" name="post_category[]" value="">' );

			editor && editor.save();

			$( '#post_title' ).val( getTitleText() );

			// Make sure to flush out the tags with tagBox before saving
			if ( window.tagBox ) {
				$( 'div.tagsdiv' ).each( function() {
					window.tagBox.flushTags( this, false, 1 );
				} );
			}

			// Get selected categories
			$( '.categories-select .category' ).each( function( i, element ) {
				var $cat = $( element );

				if ( $cat.hasClass( 'selected' ) ) {
					// Have to append a node as we submit the actual form on preview
					$form.append( $input.clone().val( $cat.attr( 'data-term-id' ) || '' ) );
				}
			});
		}

		/**
		 * Submit the post form via AJAX, and redirect to the proper screen if published vs saved as a draft.
		 *
		 * @param action string publish|draft
		 */
		function submitPost( action ) {
			var data;

			saveAlert = false;
			showSpinner();

			if ( 'publish' === action ) {
				$( '#post_status' ).val( 'publish' );
			}

			prepareFormData();
			data = $( '#pressthis-form' ).serialize();

			$.ajax( {
				type: 'post',
				url: window.ajaxurl,
				data: data
			}).always( function() {
				hideSpinner();
				clearNotices();
				$( '.publish-button' ).removeClass( 'is-saving' );
			}).done( function( response ) {
				if ( ! response.success ) {
					renderError( response.data.errorMessage );
				} else if ( response.data.redirect ) {
					if ( window.opener && ( settings.redirInParent || response.data.force ) ) {
						try {
							window.opener.location.href = response.data.redirect;

							window.setTimeout( function() {
								window.self.close();
							}, 200 );
						} catch( er ) {
							window.location.href = response.data.redirect;
						}
					} else {
						window.location.href = response.data.redirect;
					}
				}
			}).fail( function() {
				renderError( __( 'serverError' ) );
			});
		}

		/**
		 * Inserts the media a user has selected from the presented list inside the editor, as an image or embed, based on type
		 *
		 * @param type string img|embed
		 * @param src string Source URL
		 * @param link string Optional destination link, for images (defaults to src)
		 */
		function insertSelectedMedia( $element ) {
			var src, link, newContent = '';

			src = checkUrl( $element.attr( 'data-wp-src' ) || '' );
			link = checkUrl( data.u );

			if ( $element.hasClass( 'is-image' ) ) {
				if ( ! link ) {
					link = src;
				}

				newContent = '<a href="' + link + '"><img class="alignnone size-full" src="' + src + '" alt="" /></a>';
			} else {
				newContent = '[embed]' + src + '[/embed]';
			}

			if ( editor && ! editor.isHidden() ) {
				if ( ! hasSetFocus ) {
					editor.setContent( '<p>' + newContent + '</p>' + editor.getContent() );
				} else {
					editor.execCommand( 'mceInsertContent', false, newContent );
				}
			} else if ( window.QTags ) {
				window.QTags.insertContent( newContent );
			}
		}

		/**
		 * Save a new user-generated category via AJAX
		 */
		function saveNewCategory() {
			var data,
				name = $( '#new-category' ).val();

			if ( ! name ) {
				return;
			}

			data = {
				action: 'press-this-add-category',
				post_id: $( '#post_ID' ).val() || 0,
				name: name,
				new_cat_nonce: $( '#_ajax_nonce-add-category' ).val() || '',
				parent: $( '#new-category-parent' ).val() || 0
			};

			$.post( window.ajaxurl, data, function( response ) {
				if ( ! response.success ) {
					renderError( response.data.errorMessage );
				} else {
					var $parent, $ul,
						$wrap = $( 'ul.categories-select' );

					$.each( response.data, function( i, newCat ) {
						var $node = $( '<li>' ).append( $( '<div class="category selected" tabindex="0" role="checkbox" aria-checked="true">' )
							.attr( 'data-term-id', newCat.term_id )
							.text( newCat.name ) );

						if ( newCat.parent ) {
							if ( ! $ul || ! $ul.length ) {
								$parent = $wrap.find( 'div[data-term-id="' + newCat.parent + '"]' ).parent();
								$ul = $parent.find( 'ul.children:first' );

								if ( ! $ul.length ) {
									$ul = $( '<ul class="children">' ).appendTo( $parent );
								}
							}

							$ul.prepend( $node );
						} else {
							$wrap.prepend( $node );
						}

						$node.focus();
					} );

					refreshCatsCache();
				}
			} );
		}

		/* ***************************************************************
		 * RENDERING FUNCTIONS
		 *************************************************************** */

		/**
		 * Hide the form letting users enter a URL to be scanned, if a URL was already passed.
		 */
		function renderToolsVisibility() {
			if ( data.hasData ) {
				$( '#scanbar' ).hide();
			}
		}

		/**
		 * Render error notice
		 *
		 * @param msg string Notice/error message
		 * @param error string error|notice CSS class for display
		 */
		function renderNotice( msg, error ) {
			var $alerts = $( '.editor-wrapper div.alerts' ),
				className = error ? 'is-error' : 'is-notice';

			$alerts.append( $( '<p class="alert ' + className + '">' ).text( msg ) );
		}

		/**
		 * Render error notice
		 *
		 * @param msg string Error message
		 */
		function renderError( msg ) {
			renderNotice( msg, true );
		}

		function clearNotices() {
			$( 'div.alerts' ).empty();
		}

		/**
		 * Render notices on page load, if any already
		 */
		function renderStartupNotices() {
			// Render errors sent in the data, if any
			if ( data.errors ) {
				$.each( data.errors, function( i, msg ) {
					renderError( msg );
				} );
			}
		}

		/**
		 * Add an image to the list of found images.
		 */
		function addImg( src, displaySrc, i ) {
			var $element = $mediaThumbWrap.clone().addClass( 'is-image' );

			$element.attr( 'data-wp-src', src ).css( 'background-image', 'url(' + displaySrc + ')' )
				.find( 'span' ).text( __( 'suggestedImgAlt' ).replace( '%d', i + 1 ) );

			$mediaList.append( $element );
		}

		/**
		 * Render the detected images and embed for selection, if any
		 */
		function renderDetectedMedia() {
			var found = 0;

			$mediaList = $( 'ul.media-list' );
			$mediaThumbWrap = $( '<li class="suggested-media-thumbnail" tabindex="0"><span class="screen-reader-text"></span></li>' );

			if ( data._embeds ) {
				$.each( data._embeds, function ( i, src ) {
					var displaySrc = '',
						cssClass = '',
						$element = $mediaThumbWrap.clone().addClass( 'is-embed' );

					src = checkUrl( src );

					if ( src.indexOf( 'youtube.com/' ) > -1 ) {
						displaySrc = 'https://i.ytimg.com/vi/' + src.replace( /.+v=([^&]+).*/, '$1' ) + '/hqdefault.jpg';
						cssClass += ' is-video';
					} else if ( src.indexOf( 'youtu.be/' ) > -1 ) {
						displaySrc = 'https://i.ytimg.com/vi/' + src.replace( /\/([^\/])$/, '$1' ) + '/hqdefault.jpg';
						cssClass += ' is-video';
					} else if ( src.indexOf( 'dailymotion.com' ) > -1 ) {
						displaySrc = src.replace( '/video/', '/thumbnail/video/' );
						cssClass += ' is-video';
					} else if ( src.indexOf( 'soundcloud.com' ) > -1 ) {
						cssClass += ' is-audio';
					} else if ( src.indexOf( 'twitter.com' ) > -1 ) {
						cssClass += ' is-tweet';
					} else {
						cssClass += ' is-video';
					}

					$element.attr( 'data-wp-src', src ).find( 'span' ).text( __( 'suggestedEmbedAlt' ).replace( '%d', i + 1 ) );

					if ( displaySrc ) {
						$element.css( 'background-image', 'url(' + displaySrc + ')' );
					}

					$mediaList.append( $element );
					found++;
				} );
			}

			if ( data._images ) {
				$.each( data._images, function( i, src ) {
					var displaySrc, img = new Image();

					src = checkUrl( src );
					displaySrc = src.replace( /^(http[^\?]+)(\?.*)?$/, '$1' );

					if ( src.indexOf( 'files.wordpress.com/' ) > -1 ) {
						displaySrc = displaySrc.replace( /\?.*$/, '' ) + '?w=' + smallestWidth;
					} else if ( src.indexOf( 'gravatar.com/' ) > -1 ) {
						displaySrc = displaySrc.replace( /\?.*$/, '' ) + '?s=' + smallestWidth;
					} else {
						displaySrc = src;
					}

					img.onload = function() {
						if ( ( img.width && img.width < 256 ) ||
							( img.height && img.height < 128 ) ) {

							return;
						}

						addImg( src, displaySrc, i );
					};

					img.src = src;
					found++;
				} );
			}

			if ( found ) {
				$( '.media-list-container' ).addClass( 'has-media' );
			}
		}

		/* ***************************************************************
		 * MONITORING FUNCTIONS
		 *************************************************************** */

		/**
		 * Interactive navigation behavior for the options modal (post format, tags, categories)
		 */
		function monitorOptionsModal() {
			var $postOptions  = $( '.post-options' ),
				$postOption   = $( '.post-option' ),
				$settingModal = $( '.setting-modal' ),
				$modalClose   = $( '.modal-close' );

			$postOption.on( 'click', function() {
				var index = $( this ).index(),
					$targetSettingModal = $settingModal.eq( index );

				$postOptions.addClass( isOffScreen )
					.one( transitionEndEvent, function() {
						$( this ).addClass( isHidden );
					} );

				$targetSettingModal.removeClass( offscreenHidden )
					.one( transitionEndEvent, function() {
						$( this ).find( '.modal-close' ).focus();
					} );
			} );

			$modalClose.on( 'click', function() {
				var $targetSettingModal = $( this ).parent(),
					index = $targetSettingModal.index();

				$postOptions.removeClass( offscreenHidden );
				$targetSettingModal.addClass( isOffScreen );

				if ( transitionEndEvent ) {
					$targetSettingModal.one( transitionEndEvent, function() {
						$( this ).addClass( isHidden );
						$postOption.eq( index - 1 ).focus();
					} );
				} else {
					setTimeout( function() {
						$targetSettingModal.addClass( isHidden );
						$postOption.eq( index - 1 ).focus();
					}, 350 );
				}
			} );
		}

		/**
		 * Interactive behavior for the sidebar toggle, to show the options modals
		 */
		function openSidebar() {
			sidebarIsOpen = true;

			$( '.options' ).removeClass( 'closed' ).addClass( 'open' );
			$( '.press-this-actions, #scanbar' ).addClass( isHidden );
			$( '.options-panel-back' ).removeClass( isHidden );

			$( '.options-panel' ).removeClass( offscreenHidden )
				.one( transitionEndEvent, function() {
					$( '.post-option:first' ).focus();
				} );
		}

		function closeSidebar() {
			sidebarIsOpen = false;

			$( '.options' ).removeClass( 'open' ).addClass( 'closed' );
			$( '.options-panel-back' ).addClass( isHidden );
			$( '.press-this-actions, #scanbar' ).removeClass( isHidden );

			$( '.options-panel' ).addClass( isOffScreen )
				.one( transitionEndEvent, function() {
					$( this ).addClass( isHidden );
					// Reset to options list
					$( '.post-options' ).removeClass( offscreenHidden );
					$( '.setting-modal').addClass( offscreenHidden );
				});
		}

		/**
		 * Interactive behavior for the post title's field placeholder
		 */
		function monitorPlaceholder() {
			var $titleField = $( '#title-container' ),
				$placeholder = $( '.post-title-placeholder' );

			$titleField.on( 'focus', function() {
				$placeholder.addClass( 'is-hidden' );
			}).on( 'blur', function() {
				if ( ! $titleField.text() && ! $titleField.html() ) {
					$placeholder.removeClass( 'is-hidden' );
				}
			}).on( 'keyup', function() {
				saveAlert = true;
			}).on( 'paste', function( event ) {
				var text, range,
					clipboard = event.originalEvent.clipboardData || window.clipboardData;

				if ( clipboard ) {
					try{
						text = clipboard.getData( 'Text' ) || clipboard.getData( 'text/plain' );

						if ( text ) {
							text = $.trim( text.replace( /\s+/g, ' ' ) );

							if ( window.getSelection ) {
								range = window.getSelection().getRangeAt(0);

								if ( range ) {
									if ( ! range.collapsed ) {
										range.deleteContents();
									}

									range.insertNode( document.createTextNode( text ) );
								}
							} else if ( document.selection ) {
								range = document.selection.createRange();

								if ( range ) {
									range.text = text;
								}
							}
						}
					} catch ( er ) {}

					event.preventDefault();
				}

				saveAlert = true;

				setTimeout( function() {
					$titleField.text( getTitleText() );
				}, 50 );
			});

			if ( $titleField.text() || $titleField.html() ) {
				$placeholder.addClass('is-hidden');
			}
		}

		function toggleCatItem( $element ) {
			if ( $element.hasClass( 'selected' ) ) {
				$element.removeClass( 'selected' ).attr( 'aria-checked', 'false' );
			} else {
				$element.addClass( 'selected' ).attr( 'aria-checked', 'true' );
			}
		}

		function monitorCatList() {
			$( '.categories-select' ).on( 'click.press-this keydown.press-this', function( event ) {
				var $element = $( event.target );

				if ( $element.is( 'div.category' ) ) {
					if ( event.type === 'keydown' && event.keyCode !== 32 ) {
						return;
					}

					toggleCatItem( $element );
					event.preventDefault();
				}
			});
		}

		function splitButtonClose() {
			$( '.split-button' ).removeClass( 'is-open' );
			$( '.split-button-toggle' ).attr( 'aria-expanded', 'false' );
		}

		/* ***************************************************************
		 * PROCESSING FUNCTIONS
		 *************************************************************** */

		/**
		 * Calls all the rendring related functions to happen on page load
		 */
		function render(){
			// We're on!
			renderToolsVisibility();
			renderDetectedMedia();
			renderStartupNotices();

			if ( window.tagBox ) {
				window.tagBox.init();
			}

			// iOS doesn't fire click events on "standard" elements without this...
			if ( iOS ) {
				$( document.body ).css( 'cursor', 'pointer' );
			}
		}

		/**
		 * Set app events and other state monitoring related code.
		 */
		function monitor() {
			var $splitButton = $( '.split-button' );

			$document.on( 'tinymce-editor-init', function( event, ed ) {
				editor = ed;

				editor.on( 'nodechange', function() {
					hasSetFocus = true;
				});

				editor.on( 'focus', function() {
					splitButtonClose();
				});

				editor.on( 'show', function() {
					setTimeout( function() {
						editor.execCommand( 'wpAutoResize' );
					}, 300 );
				});

				editor.on( 'hide', function() {
					setTimeout( function() {
						textEditorResize( 'reset' );
					}, 100 );
				});

				editor.on( 'keyup', mceKeyup );
				editor.on( 'undo redo', mceScroll );

			}).on( 'click.press-this keypress.press-this', '.suggested-media-thumbnail', function( event ) {
				if ( event.type === 'click' || event.keyCode === 13 ) {
					insertSelectedMedia( $( this ) );
				}
			}).on( 'click.press-this', function( event ) {
				if ( ! $( event.target ).closest( 'button' ).hasClass( 'split-button-toggle' ) ) {
					splitButtonClose();
				}
			});

			// Publish, Draft and Preview buttons
			$( '.post-actions' ).on( 'click.press-this', function( event ) {
				var location,
					$target = $( event.target ),
					$button = $target.closest( 'button' );

				if ( $button.length ) {
					if ( $button.hasClass( 'draft-button' ) ) {
						$( '.publish-button' ).addClass( 'is-saving' );
						submitPost( 'draft' );
					} else if ( $button.hasClass( 'publish-button' ) ) {
						$button.addClass( 'is-saving' );

						if ( window.history.replaceState ) {
							location = window.location.href;
							location += ( location.indexOf( '?' ) !== -1 ) ? '&' : '?';
							location += 'wp-press-this-reload=true';

							window.history.replaceState( null, null, location );
						}

						submitPost( 'publish' );
					} else if ( $button.hasClass( 'preview-button' ) ) {
						prepareFormData();
						window.opener && window.opener.focus();

						$( '#wp-preview' ).val( 'dopreview' );
						$( '#pressthis-form' ).attr( 'target', '_blank' ).submit().attr( 'target', '' );
						$( '#wp-preview' ).val( '' );
					} else if ( $button.hasClass( 'standard-editor-button' ) ) {
						$( '.publish-button' ).addClass( 'is-saving' );
						$( '#pt-force-redirect' ).val( 'true' );
						submitPost( 'draft' );
					} else if ( $button.hasClass( 'split-button-toggle' ) ) {
						if ( $splitButton.hasClass( 'is-open' ) ) {
							$splitButton.removeClass( 'is-open' );
							$button.attr( 'aria-expanded', 'false' );
						} else {
							$splitButton.addClass( 'is-open' );
							$button.attr( 'aria-expanded', 'true' );
						}
					}
				}
			});

			monitorOptionsModal();
			monitorPlaceholder();
			monitorCatList();

			$( '.options' ).on( 'click.press-this', function() {
				if ( $( this ).hasClass( 'open' ) ) {
					closeSidebar();
				} else {
					openSidebar();
				}
			});

			// Close the sidebar when focus moves outside of it.
			$( '.options-panel, .options-panel-back' ).on( 'focusout.press-this', function() {
				setTimeout( function() {
					var node = document.activeElement,
						$node = $( node );

					if ( sidebarIsOpen && node && ! $node.hasClass( 'options-panel-back' ) &&
						( node.nodeName === 'BODY' ||
							( ! $node.closest( '.options-panel' ).length &&
							! $node.closest( '.options' ).length ) ) ) {

						closeSidebar();
					}
				}, 50 );
			});

			$( '#post-formats-select input' ).on( 'change', function() {
				var $this = $( this );

				if ( $this.is( ':checked' ) ) {
					$( '#post-option-post-format' ).text( $( 'label[for="' + $this.attr( 'id' ) + '"]' ).text() || '' );
				}
			} );

			$window.on( 'beforeunload.press-this', function() {
				if ( saveAlert || ( editor && editor.isDirty() ) ) {
					return __( 'saveAlert' );
				}
			} ).on( 'resize.press-this', function() {
				if ( ! editor || editor.isHidden() ) {
					textEditorResize( 'reset' );
				}
			});

			$( 'button.add-cat-toggle' ).on( 'click.press-this', function() {
				var $this = $( this );

				$this.toggleClass( 'is-toggled' );
				$this.attr( 'aria-expanded', 'false' === $this.attr( 'aria-expanded' ) ? 'true' : 'false' );
				$( '.setting-modal .add-category, .categories-search-wrapper' ).toggleClass( 'is-hidden' );
			} );

			$( 'button.add-cat-submit' ).on( 'click.press-this', saveNewCategory );

			$( '.categories-search' ).on( 'keyup.press-this', function() {
				var search = $( this ).val().toLowerCase() || '';

				// Don't search when less thasn 3 extended ASCII chars
				if ( /[\x20-\xFF]+/.test( search ) && search.length < 2 ) {
					return;
				}

				$.each( catsCache, function( i, cat ) {
					cat.node.removeClass( 'is-hidden searched-parent' );
				} );

				if ( search ) {
					$.each( catsCache, function( i, cat ) {
						if ( cat.text.indexOf( search ) === -1 ) {
							cat.node.addClass( 'is-hidden' );
						} else {
							cat.parents.addClass( 'searched-parent' );
						}
					} );
				}
			} );

			$textEditor.on( 'focus.press-this input.press-this propertychange.press-this', textEditorResize );

			return true;
		}

		function refreshCatsCache() {
			$( '.categories-select' ).find( 'li' ).each( function() {
				var $this = $( this );

				catsCache.push( {
					node: $this,
					parents: $this.parents( 'li' ),
					text: $this.children( '.category' ).text().toLowerCase()
				} );
			} );
		}

		// Let's go!
		$document.ready( function() {
			render();
			monitor();
			refreshCatsCache();
		});

		// Expose public methods?
		return {
			renderNotice: renderNotice,
			renderError: renderError
		};
	};

	window.wp = window.wp || {};
	window.wp.pressThis = new PressThis();

}( jQuery, window ));
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  var commentsBox,WPSetThumbnailHTML,WPSetThumbnailID,WPRemoveThumbnail,wptitlehint,makeSlugeditClickable,editPermalink;makeSlugeditClickable=editPermalink=function(){},window.wp=window.wp||{},function(a){var b=!1;commentsBox={st:0,get:function(b,c){var d,e=this.st;return c||(c=20),this.st+=c,this.total=b,a("#commentsdiv .spinner").addClass("is-active"),d={action:"get-comments",mode:"single",_ajax_nonce:a("#add_comment_nonce").val(),p:a("#post_ID").val(),start:e,number:c},a.post(ajaxurl,d,function(b){return b=wpAjax.parseAjaxResponse(b),a("#commentsdiv .widefat").show(),a("#commentsdiv .spinner").removeClass("is-active"),"object"==typeof b&&b.responses[0]?(a("#the-comment-list").append(b.responses[0].data),theList=theExtraList=null,a("a[className*=':']").unbind(),void(commentsBox.st>commentsBox.total?a("#show-comments").hide():a("#show-comments").show().children("a").html(postL10n.showcomm))):1==b?void a("#show-comments").html(postL10n.endcomm):void a("#the-comment-list").append('<tr><td colspan="2">'+wpAjax.broken+"</td></tr>")}),!1},load:function(a){this.st=jQuery("#the-comment-list tr.comment:visible").length,this.get(a)}},WPSetThumbnailHTML=function(b){a(".inside","#postimagediv").html(b)},WPSetThumbnailID=function(b){var c=a('input[value="_thumbnail_id"]',"#list-table");c.length>0&&a("#meta\\["+c.attr("id").match(/[0-9]+/)+"\\]\\[value\\]").text(b)},WPRemoveThumbnail=function(b){a.post(ajaxurl,{action:"set-post-thumbnail",post_id:a("#post_ID").val(),thumbnail_id:-1,_ajax_nonce:b,cookie:encodeURIComponent(document.cookie)},function(a){"0"==a?alert(setPostThumbnailL10n.error):WPSetThumbnailHTML(a)})},a(document).on("heartbeat-send.refresh-lock",function(b,c){var d=a("#active_post_lock").val(),e=a("#post_ID").val(),f={};e&&a("#post-lock-dialog").length&&(f.post_id=e,d&&(f.lock=d),c["wp-refresh-post-lock"]=f)}).on("heartbeat-tick.refresh-lock",function(b,c){var d,e,f;c["wp-refresh-post-lock"]&&(d=c["wp-refresh-post-lock"],d.lock_error?(e=a("#post-lock-dialog"),e.length&&!e.is(":visible")&&(wp.autosave&&(a(document).one("heartbeat-tick",function(){wp.autosave.server.suspend(),e.removeClass("saving").addClass("saved"),a(window).off("beforeunload.edit-post")}),e.addClass("saving"),wp.autosave.server.triggerSave()),d.lock_error.avatar_src&&(f=a('<img class="avatar avatar-64 photo" width="64" height="64" alt="" />').attr("src",d.lock_error.avatar_src.replace(/&amp;/g,"&")),e.find("div.post-locked-avatar").empty().append(f)),e.show().find(".currently-editing").text(d.lock_error.text),e.find(".wp-tab-first").focus())):d.new_lock&&a("#active_post_lock").val(d.new_lock))}).on("before-autosave.update-post-slug",function(){b=document.activeElement&&"title"===document.activeElement.id}).on("after-autosave.update-post-slug",function(){a("#edit-slug-box > *").length||b||a.post(ajaxurl,{action:"sample-permalink",post_id:a("#post_ID").val(),new_title:a("#title").val(),samplepermalinknonce:a("#samplepermalinknonce").val()},function(b){"-1"!=b&&a("#edit-slug-box").html(b)})})}(jQuery),function(a){function b(){c=!1,window.clearTimeout(d),d=window.setTimeout(function(){c=!0},3e5)}var c,d;a(document).on("heartbeat-send.wp-refresh-nonces",function(b,d){var e,f=a("#wp-auth-check-wrap");(c||f.length&&!f.hasClass("hidden"))&&(e=a("#post_ID").val())&&a("#_wpnonce").val()&&(d["wp-refresh-post-nonces"]={post_id:e})}).on("heartbeat-tick.wp-refresh-nonces",function(c,d){var e=d["wp-refresh-post-nonces"];e&&(b(),e.replace&&a.each(e.replace,function(b,c){a("#"+b).val(c)}),e.heartbeatNonce&&(window.heartbeatSettings.nonce=e.heartbeatNonce))}).ready(function(){b()})}(jQuery),jQuery(document).ready(function(a){function b(){var b,c,d,e,f=0,g=a("#post_name"),h=g.val(),i=a("#sample-permalink"),j=i.html(),l=a("#sample-permalink a").html(),m=a("#edit-slug-buttons"),n=m.html(),o=a("#editable-post-name-full");for(o.find("img").replaceWith(function(){return this.alt}),o=o.html(),i.html(l),d=a("#editable-post-name"),e=d.html(),m.html('<button type="button" class="save button button-small">'+postL10n.ok+'</button> <button type="button" class="cancel button-link">'+postL10n.cancel+"</button>"),m.children(".save").click(function(){var b=d.children("input").val();return b==a("#editable-post-name-full").text()?void m.children(".cancel").click():void a.post(ajaxurl,{action:"sample-permalink",post_id:k,new_slug:b,new_title:a("#title").val(),samplepermalinknonce:a("#samplepermalinknonce").val()},function(c){var d=a("#edit-slug-box");d.html(c),d.hasClass("hidden")&&d.fadeIn("fast",function(){d.removeClass("hidden")}),m.html(n),i.html(j),g.val(b),a(".edit-slug").focus(),wp.a11y.speak(postL10n.permalinkSaved)})}),m.children(".cancel").click(function(){a("#view-post-btn").show(),d.html(e),m.html(n),i.html(j),g.val(h),a(".edit-slug").focus()}),b=0;b<o.length;++b)"%"==o.charAt(b)&&f++;c=f>o.length/4?"":o,d.html('<input type="text" id="new-post-slug" value="'+c+'" autocomplete="off" />').children("input").keydown(function(a){var b=a.which;13===b&&(a.preventDefault(),m.children(".save").click()),27===b&&m.children(".cancel").click()}).keyup(function(){g.val(this.value)}).focus()}var c,d,e,f,g,h="",i=a("#content"),j=a(document),k=a("#post_ID").val()||0,l=a("#submitpost"),m=!0,n=a("#post-visibility-select"),o=a("#timestampdiv"),p=a("#post-status-select"),q=window.navigator.platform?-1!==window.navigator.platform.indexOf("Mac"):!1;if(postboxes.add_postbox_toggles(pagenow),window.name="",a("#post-lock-dialog .notification-dialog").on("keydown",function(b){if(9==b.which){var c=a(b.target);c.hasClass("wp-tab-first")&&b.shiftKey?(a(this).find(".wp-tab-last").focus(),b.preventDefault()):c.hasClass("wp-tab-last")&&!b.shiftKey&&(a(this).find(".wp-tab-first").focus(),b.preventDefault())}}).filter(":visible").find(".wp-tab-first").focus(),wp.heartbeat&&a("#post-lock-dialog").length&&wp.heartbeat.interval(15),e=l.find(":submit, a.submitdelete, #post-preview").on("click.edit-post",function(b){var c=a(this);return c.hasClass("disabled")?void b.preventDefault():void(c.hasClass("submitdelete")||c.is("#post-preview")||a("form#post").off("submit.edit-post").on("submit.edit-post",function(b){b.isDefaultPrevented()||(wp.autosave&&wp.autosave.server.suspend(),"undefined"!=typeof commentReply&&commentReply.close(),m=!1,a(window).off("beforeunload.edit-post"),e.addClass("disabled"),"publish"===c.attr("id")?l.find("#major-publishing-actions .spinner").addClass("is-active"):l.find("#minor-publishing .spinner").addClass("is-active"))}))}),a("#post-preview").on("click.post-preview",function(b){var c=a(this),d=a("form#post"),e=a("input#wp-preview"),f=c.attr("target")||"wp-preview",g=navigator.userAgent.toLowerCase();b.preventDefault(),c.hasClass("disabled")||(wp.autosave&&wp.autosave.server.tempBlockSave(),e.val("dopreview"),d.attr("target",f).submit().attr("target",""),-1!==g.indexOf("safari")&&-1===g.indexOf("chrome")&&d.attr("action",function(a,b){return b+"?t="+(new Date).getTime()}),e.val(""))}),a("#title").on("keydown.editor-focus",function(a){var b;if(9===a.keyCode&&!a.ctrlKey&&!a.altKey&&!a.shiftKey){if(b="undefined"!=typeof tinymce&&tinymce.get("content"),b&&!b.isHidden())b.focus();else{if(!i.length)return;i.focus()}a.preventDefault()}}),a("#auto_draft").val()&&a("#title").blur(function(){var b;this.value&&!a("#edit-slug-box > *").length&&(a("form#post").one("submit",function(){b=!0}),window.setTimeout(function(){!b&&wp.autosave&&wp.autosave.server.triggerSave()},200))}),j.on("autosave-disable-buttons.edit-post",function(){e.addClass("disabled")}).on("autosave-enable-buttons.edit-post",function(){wp.heartbeat&&wp.heartbeat.hasConnectionError()||e.removeClass("disabled")}).on("before-autosave.edit-post",function(){a(".autosave-message").text(postL10n.savingText)}).on("after-autosave.edit-post",function(b,c){a(".autosave-message").text(c.message),a(document.body).hasClass("post-new-php")&&a(".submitbox .submitdelete").show()}),a(window).on("beforeunload.edit-post",function(){var a="undefined"!=typeof tinymce&&tinymce.get("content");return a&&!a.isHidden()&&a.isDirty()||wp.autosave&&wp.autosave.server.postChanged()?postL10n.saveAlert:void 0}).on("unload.edit-post",function(b){m&&(b.target&&"#document"!=b.target.nodeName||a.ajax({type:"POST",url:ajaxurl,async:!1,data:{action:"wp-remove-post-lock",_wpnonce:a("#_wpnonce").val(),post_ID:a("#post_ID").val(),active_post_lock:a("#active_post_lock").val()}}))}),a("#tagsdiv-post_tag").length?window.tagBox&&window.tagBox.init():a(".meta-box-sortables").children("div.postbox").each(function(){return 0===this.id.indexOf("tagsdiv-")?(window.tagBox&&window.tagBox.init(),!1):void 0}),a(".categorydiv").each(function(){var b,c,d,e,f,g=a(this).attr("id");d=g.split("-"),d.shift(),e=d.join("-"),f=e+"_tab","category"==e&&(f="cats"),a("a","#"+e+"-tabs").click(function(b){b.preventDefault();var c=a(this).attr("href");a(this).parent().addClass("tabs").siblings("li").removeClass("tabs"),a("#"+e+"-tabs").siblings(".tabs-panel").hide(),a(c).show(),"#"+e+"-all"==c?deleteUserSetting(f):setUserSetting(f,"pop")}),getUserSetting(f)&&a('a[href="#'+e+'-pop"]',"#"+e+"-tabs").click(),a("#new"+e).one("focus",function(){a(this).val("").removeClass("form-input-tip")}),a("#new"+e).keypress(function(b){13===b.keyCode&&(b.preventDefault(),a("#"+e+"-add-submit").click())}),a("#"+e+"-add-submit").click(function(){a("#new"+e).focus()}),b=function(b){return a("#new"+e).val()?(b.data+="&"+a(":checked","#"+e+"checklist").serialize(),a("#"+e+"-add-submit").prop("disabled",!0),b):!1},c=function(b,c){var d,f=a("#new"+e+"_parent");a("#"+e+"-add-submit").prop("disabled",!1),"undefined"!=c.parsed.responses[0]&&(d=c.parsed.responses[0].supplemental.newcat_parent)&&(f.before(d),f.remove())},a("#"+e+"checklist").wpList({alt:"",response:e+"-ajax-response",addBefore:b,addAfter:c}),a("#"+e+"-add-toggle").click(function(b){b.preventDefault(),a("#"+e+"-adder").toggleClass("wp-hidden-children"),a('a[href="#'+e+'-all"]',"#"+e+"-tabs").click(),a("#new"+e).focus()}),a("#"+e+"checklist, #"+e+"checklist-pop").on("click",'li.popular-category > label input[type="checkbox"]',function(){var b=a(this),c=b.is(":checked"),d=b.val();d&&b.parents("#taxonomy-"+e).length&&a("#in-"+e+"-"+d+", #in-popular-"+e+"-"+d).prop("checked",c)})}),a("#postcustom").length&&a("#the-list").wpList({addAfter:function(){a("table#list-table").show()},addBefore:function(b){return b.data+="&post_id="+a("#post_ID").val(),b}}),a("#submitdiv").length&&(c=a("#timestamp").html(),d=a("#post-visibility-display").html(),f=function(){"public"!=n.find("input:radio:checked").val()?(a("#sticky").prop("checked",!1),a("#sticky-span").hide()):a("#sticky-span").show(),"password"!=n.find("input:radio:checked").val()?a("#password-span").hide():a("#password-span").show()},g=function(){if(!o.length)return!0;var b,d,e,f,g=a("#post_status"),h=a('option[value="publish"]',g),i=a("#aa").val(),j=a("#mm").val(),k=a("#jj").val(),l=a("#hh").val(),m=a("#mn").val();return b=new Date(i,j-1,k,l,m),d=new Date(a("#hidden_aa").val(),a("#hidden_mm").val()-1,a("#hidden_jj").val(),a("#hidden_hh").val(),a("#hidden_mn").val()),e=new Date(a("#cur_aa").val(),a("#cur_mm").val()-1,a("#cur_jj").val(),a("#cur_hh").val(),a("#cur_mn").val()),b.getFullYear()!=i||1+b.getMonth()!=j||b.getDate()!=k||b.getMinutes()!=m?(o.find(".timestamp-wrap").addClass("form-invalid"),!1):(o.find(".timestamp-wrap").removeClass("form-invalid"),b>e&&"future"!=a("#original_post_status").val()?(f=postL10n.publishOnFuture,a("#publish").val(postL10n.schedule)):e>=b&&"publish"!=a("#original_post_status").val()?(f=postL10n.publishOn,a("#publish").val(postL10n.publish)):(f=postL10n.publishOnPast,a("#publish").val(postL10n.update)),d.toUTCString()==b.toUTCString()?a("#timestamp").html(c):a("#timestamp").html("\n"+f+" <b>"+postL10n.dateFormat.replace("%1$s",a('option[value="'+j+'"]',"#mm").attr("data-text")).replace("%2$s",parseInt(k,10)).replace("%3$s",i).replace("%4$s",("00"+l).slice(-2)).replace("%5$s",("00"+m).slice(-2))+"</b> "),"private"==n.find("input:radio:checked").val()?(a("#publish").val(postL10n.update),0===h.length?g.append('<option value="publish">'+postL10n.privatelyPublished+"</option>"):h.html(postL10n.privatelyPublished),a('option[value="publish"]',g).prop("selected",!0),a("#misc-publishing-actions .edit-post-status").hide()):("future"==a("#original_post_status").val()||"draft"==a("#original_post_status").val()?h.length&&(h.remove(),g.val(a("#hidden_post_status").val())):h.html(postL10n.published),g.is(":hidden")&&a("#misc-publishing-actions .edit-post-status").show()),a("#post-status-display").html(a("option:selected",g).text()),"private"==a("option:selected",g).val()||"publish"==a("option:selected",g).val()?a("#save-post").hide():(a("#save-post").show(),"pending"==a("option:selected",g).val()?a("#save-post").show().val(postL10n.savePending):a("#save-post").show().val(postL10n.saveDraft)),!0)},a("#visibility .edit-visibility").click(function(b){b.preventDefault(),n.is(":hidden")&&(f(),n.slideDown("fast",function(){n.find('input[type="radio"]').first().focus()}),a(this).hide())}),n.find(".cancel-post-visibility").click(function(b){n.slideUp("fast"),a("#visibility-radio-"+a("#hidden-post-visibility").val()).prop("checked",!0),a("#post_password").val(a("#hidden-post-password").val()),a("#sticky").prop("checked",a("#hidden-post-sticky").prop("checked")),a("#post-visibility-display").html(d),a("#visibility .edit-visibility").show().focus(),g(),b.preventDefault()}),n.find(".save-post-visibility").click(function(b){n.slideUp("fast"),a("#visibility .edit-visibility").show().focus(),g(),"public"!=n.find("input:radio:checked").val()&&a("#sticky").prop("checked",!1),h=a("#sticky").prop("checked")?"Sticky":"",a("#post-visibility-display").html(postL10n[n.find("input:radio:checked").val()+h]),b.preventDefault()}),n.find("input:radio").change(function(){f()}),o.siblings("a.edit-timestamp").click(function(b){o.is(":hidden")&&(o.slideDown("fast",function(){a("input, select",o.find(".timestamp-wrap")).first().focus()}),a(this).hide()),b.preventDefault()}),o.find(".cancel-timestamp").click(function(b){o.slideUp("fast").siblings("a.edit-timestamp").show().focus(),a("#mm").val(a("#hidden_mm").val()),a("#jj").val(a("#hidden_jj").val()),a("#aa").val(a("#hidden_aa").val()),a("#hh").val(a("#hidden_hh").val()),a("#mn").val(a("#hidden_mn").val()),g(),b.preventDefault()}),o.find(".save-timestamp").click(function(a){g()&&(o.slideUp("fast"),o.siblings("a.edit-timestamp").show().focus()),a.preventDefault()}),a("#post").on("submit",function(b){g()||(b.preventDefault(),o.show(),wp.autosave&&wp.autosave.enableButtons(),a("#publishing-action .spinner").removeClass("is-active"))}),p.siblings("a.edit-post-status").click(function(b){p.is(":hidden")&&(p.slideDown("fast",function(){p.find("select").focus()}),a(this).hide()),b.preventDefault()}),p.find(".save-post-status").click(function(a){p.slideUp("fast").siblings("a.edit-post-status").show().focus(),g(),a.preventDefault()}),p.find(".cancel-post-status").click(function(b){p.slideUp("fast").siblings("a.edit-post-status").show().focus(),a("#post_status").val(a("#hidden_post_status").val()),g(),b.preventDefault()})),a("#titlediv").on("click",".edit-slug",function(){b()}),wptitlehint=function(b){b=b||"title";var c=a("#"+b),d=a("#"+b+"-prompt-text");""===c.val()&&d.removeClass("screen-reader-text"),d.click(function(){a(this).addClass("screen-reader-text"),c.focus()}),c.blur(function(){""===this.value&&d.removeClass("screen-reader-text")}).focus(function(){d.addClass("screen-reader-text")}).keydown(function(b){d.addClass("screen-reader-text"),a(this).unbind(b)})},wptitlehint(),function(){function b(a){h.hasClass("wp-editor-expand")||(f?d.theme.resizeTo(null,e+a.pageY):i.height(Math.max(50,e+a.pageY)),a.preventDefault())}function c(){var b,c;h.hasClass("wp-editor-expand")||(f?(d.focus(),c=parseInt(a("#wp-content-editor-container .mce-toolbar-grp").height(),10),(10>c||c>200)&&(c=30),b=parseInt(a("#content_ifr").css("height"),10)+c-28):(i.focus(),b=parseInt(i.css("height"),10)),j.off(".wp-editor-resize"),b&&b>50&&5e3>b&&setUserSetting("ed_size",b))}var d,e,f,g=a("#post-status-info"),h=a("#postdivrich");return!i.length||"ontouchstart"in window?void a("#content-resize-handle").hide():void g.on("mousedown.wp-editor-resize",function(g){"undefined"!=typeof tinymce&&(d=tinymce.get("content")),d&&!d.isHidden()?(f=!0,e=a("#content_ifr").height()-g.pageY):(f=!1,e=i.height()-g.pageY,i.blur()),j.on("mousemove.wp-editor-resize",b).on("mouseup.wp-editor-resize mouseleave.wp-editor-resize",c),g.preventDefault()}).on("mouseup.wp-editor-resize",c)}(),"undefined"!=typeof tinymce&&a("#post-formats-select input.post-format").on("change.set-editor-class",function(){var b,c,d=this.id;d&&a(this).prop("checked")&&(b=tinymce.get("content"))&&(c=b.getBody(),c.className=c.className.replace(/\bpost-format-[^ ]+/,""),b.dom.addClass(c,"post-format-0"==d?"post-format-standard":d),a(document).trigger("editor-classchange"))}),i.on("keydown.wp-autosave",function(a){if(83===a.which){if(a.shiftKey||a.altKey||q&&(!a.metaKey||a.ctrlKey)||!q&&!a.ctrlKey)return;wp.autosave&&wp.autosave.server.triggerSave(),a.preventDefault()}}),"auto-draft"===a("#original_post_status").val()&&window.history.replaceState){var r;a("#publish").on("click",function(){r=window.location.href,r+=-1!==r.indexOf("?")?"&":"?",r+="wp-post-new-reload=true",window.history.replaceState(null,null,r)})}}),function(a,b){a(function(){function c(){var a,c;a=!d||d.isHidden()?e.val():d.getContent({format:"raw"}),c=b.count(a),c!==g&&f.text(c),g=c}var d,e=a("#content"),f=a("#wp-word-count").find(".word-count"),g=0;a(document).on("tinymce-editor-init",function(a,b){"content"===b.id&&(d=b,b.on("nodechange keyup",_.debounce(c,1e3)))}),e.on("input keyup",_.debounce(c,1e3)),c()})}(jQuery,new wp.utils.WordCounter);                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    var wpNavMenu;!function(a){var b;b=wpNavMenu={options:{menuItemDepthPerLevel:30,globalMaxDepth:11,sortableItems:"> *",targetTolerance:0},menuList:void 0,targetList:void 0,menusChanged:!1,isRTL:!("undefined"==typeof isRtl||!isRtl),negateIfRTL:"undefined"!=typeof isRtl&&isRtl?-1:1,lastSearch:"",init:function(){b.menuList=a("#menu-to-edit"),b.targetList=b.menuList,this.jQueryExtensions(),this.attachMenuEditListeners(),this.attachQuickSearchListeners(),this.attachThemeLocationsListeners(),this.attachMenuSaveSubmitListeners(),this.attachTabsPanelListeners(),this.attachUnsavedChangesListener(),b.menuList.length&&this.initSortables(),menus.oneThemeLocationNoMenus&&a("#posttype-page").addSelectedToMenu(b.addMenuItemToBottom),this.initManageLocations(),this.initAccessibility(),this.initToggles(),this.initPreviewing()},jQueryExtensions:function(){a.fn.extend({menuItemDepth:function(){var a=b.isRTL?this.eq(0).css("margin-right"):this.eq(0).css("margin-left");return b.pxToDepth(a&&-1!=a.indexOf("px")?a.slice(0,-2):0)},updateDepthClass:function(b,c){return this.each(function(){var d=a(this);c=c||d.menuItemDepth(),a(this).removeClass("menu-item-depth-"+c).addClass("menu-item-depth-"+b)})},shiftDepthClass:function(b){return this.each(function(){var c=a(this),d=c.menuItemDepth();a(this).removeClass("menu-item-depth-"+d).addClass("menu-item-depth-"+(d+b))})},childMenuItems:function(){var b=a();return this.each(function(){for(var c=a(this),d=c.menuItemDepth(),e=c.next(".menu-item");e.length&&e.menuItemDepth()>d;)b=b.add(e),e=e.next(".menu-item")}),b},shiftHorizontally:function(b){return this.each(function(){var c=a(this),d=c.menuItemDepth(),e=d+b;c.moveHorizontally(e,d)})},moveHorizontally:function(b,c){return this.each(function(){var d=a(this),e=d.childMenuItems(),f=b-c,g=d.find(".is-submenu");d.updateDepthClass(b,c).updateParentMenuItemDBId(),e&&e.each(function(){var b=a(this),c=b.menuItemDepth(),d=c+f;b.updateDepthClass(d,c).updateParentMenuItemDBId()}),0===b?g.hide():g.show()})},updateParentMenuItemDBId:function(){return this.each(function(){var b=a(this),c=b.find(".menu-item-data-parent-id"),d=parseInt(b.menuItemDepth(),10),e=d-1,f=b.prevAll(".menu-item-depth-"+e).first();0===d?c.val(0):c.val(f.find(".menu-item-data-db-id").val())})},hideAdvancedMenuItemFields:function(){return this.each(function(){var b=a(this);a(".hide-column-tog").not(":checked").each(function(){b.find(".field-"+a(this).val()).addClass("hidden-field")})})},addSelectedToMenu:function(c){return 0===a("#menu-to-edit").length?!1:this.each(function(){var d=a(this),e={},f=menus.oneThemeLocationNoMenus&&0===d.find(".tabs-panel-active .categorychecklist li input:checked").length?d.find('#page-all li input[type="checkbox"]'):d.find(".tabs-panel-active .categorychecklist li input:checked"),g=/menu-item\[([^\]]*)/;return c=c||b.addMenuItemToBottom,f.length?(d.find(".button-controls .spinner").addClass("is-active"),a(f).each(function(){var d=a(this),f=g.exec(d.attr("name")),h="undefined"==typeof f[1]?0:parseInt(f[1],10);this.className&&-1!=this.className.indexOf("add-to-top")&&(c=b.addMenuItemToTop),e[h]=d.closest("li").getItemData("add-menu-item",h)}),void b.addItemToMenu(e,c,function(){f.removeAttr("checked"),d.find(".button-controls .spinner").removeClass("is-active")})):!1})},getItemData:function(a,b){a=a||"menu-item";var c,d={},e=["menu-item-db-id","menu-item-object-id","menu-item-object","menu-item-parent-id","menu-item-position","menu-item-type","menu-item-title","menu-item-url","menu-item-description","menu-item-attr-title","menu-item-target","menu-item-classes","menu-item-xfn"];return b||"menu-item"!=a||(b=this.find(".menu-item-data-db-id").val()),b?(this.find("input").each(function(){var f;for(c=e.length;c--;)"menu-item"==a?f=e[c]+"["+b+"]":"add-menu-item"==a&&(f="menu-item["+b+"]["+e[c]+"]"),this.name&&f==this.name&&(d[e[c]]=this.value)}),d):d},setItemData:function(b,c,d){return c=c||"menu-item",d||"menu-item"!=c||(d=a(".menu-item-data-db-id",this).val()),d?(this.find("input").each(function(){var e,f=a(this);a.each(b,function(a,b){"menu-item"==c?e=a+"["+d+"]":"add-menu-item"==c&&(e="menu-item["+d+"]["+a+"]"),e==f.attr("name")&&f.val(b)})}),this):this}})},countMenuItems:function(b){return a(".menu-item-depth-"+b).length},moveMenuItem:function(c,d){var e,f,g,h=a("#menu-to-edit li"),i=h.length,j=c.parents("li.menu-item"),k=j.childMenuItems(),l=j.getItemData(),m=parseInt(j.menuItemDepth(),10),n=parseInt(j.index(),10),o=j.next(),p=o.childMenuItems(),q=parseInt(o.menuItemDepth(),10)+1,r=j.prev(),s=parseInt(r.menuItemDepth(),10),t=r.getItemData()["menu-item-db-id"];switch(d){case"up":if(f=n-1,0===n)break;0===f&&0!==m&&j.moveHorizontally(0,m),0!==s&&j.moveHorizontally(s,m),k?(e=j.add(k),e.detach().insertBefore(h.eq(f)).updateParentMenuItemDBId()):j.detach().insertBefore(h.eq(f)).updateParentMenuItemDBId();break;case"down":if(k){if(e=j.add(k),o=h.eq(e.length+n),p=0!==o.childMenuItems().length,p&&(g=parseInt(o.menuItemDepth(),10)+1,j.moveHorizontally(g,m)),i===n+e.length)break;e.detach().insertAfter(h.eq(n+e.length)).updateParentMenuItemDBId()}else{if(0!==p.length&&j.moveHorizontally(q,m),i===n+1)break;j.detach().insertAfter(h.eq(n+1)).updateParentMenuItemDBId()}break;case"top":if(0===n)break;k?(e=j.add(k),e.detach().insertBefore(h.eq(0)).updateParentMenuItemDBId()):j.detach().insertBefore(h.eq(0)).updateParentMenuItemDBId();break;case"left":if(0===m)break;j.shiftHorizontally(-1);break;case"right":if(0===n)break;if(l["menu-item-parent-id"]===t)break;j.shiftHorizontally(1)}c.focus(),b.registerChange(),b.refreshKeyboardAccessibility(),b.refreshAdvancedAccessibility()},initAccessibility:function(){var c=a("#menu-to-edit");b.refreshKeyboardAccessibility(),b.refreshAdvancedAccessibility(),c.on("mouseenter.refreshAccessibility focus.refreshAccessibility touchstart.refreshAccessibility",".menu-item",function(){b.refreshAdvancedAccessibilityOfItem(a(this).find("a.item-edit"))}),c.on("click","a.item-edit",function(){b.refreshAdvancedAccessibilityOfItem(a(this))}),c.on("click",".menus-move",function(c){var d=a(this),e=d.data("dir");"undefined"!=typeof e&&b.moveMenuItem(a(this).parents("li.menu-item").find("a.item-edit"),e),c.preventDefault()})},refreshAdvancedAccessibilityOfItem:function(b){if(!0===a(b).data("needs_accessibility_refresh")){var c,d,e,f,g,h,i,j,k,l=a(b),m=l.closest("li.menu-item").first(),n=m.menuItemDepth(),o=0===n,p=l.closest(".menu-item-handle").find(".menu-item-title").text(),q=parseInt(m.index(),10),r=o?n:parseInt(n-1,10),s=m.prevAll(".menu-item-depth-"+r).first().find(".menu-item-title").text(),t=m.prevAll(".menu-item-depth-"+n).first().find(".menu-item-title").text(),u=a("#menu-to-edit li").length,v=m.nextAll(".menu-item-depth-"+n).length;m.find(".field-move").toggle(u>1),0!==q&&(c=m.find(".menus-move-up"),c.attr("aria-label",menus.moveUp).css("display","inline")),0!==q&&o&&(c=m.find(".menus-move-top"),c.attr("aria-label",menus.moveToTop).css("display","inline")),q+1!==u&&0!==q&&(c=m.find(".menus-move-down"),c.attr("aria-label",menus.moveDown).css("display","inline")),0===q&&0!==v&&(c=m.find(".menus-move-down"),c.attr("aria-label",menus.moveDown).css("display","inline")),o||(c=m.find(".menus-move-left"),d=menus.outFrom.replace("%s",s),c.attr("aria-label",menus.moveOutFrom.replace("%s",s)).text(d).css("display","inline")),0!==q&&m.find(".menu-item-data-parent-id").val()!==m.prev().find(".menu-item-data-db-id").val()&&(c=m.find(".menus-move-right"),d=menus.under.replace("%s",t),c.attr("aria-label",menus.moveUnder.replace("%s",t)).text(d).css("display","inline")),o?(e=a(".menu-item-depth-0"),f=e.index(m)+1,u=e.length,g=menus.menuFocus.replace("%1$s",p).replace("%2$d",f).replace("%3$d",u)):(h=m.prevAll(".menu-item-depth-"+parseInt(n-1,10)).first(),i=h.find(".menu-item-data-db-id").val(),j=h.find(".menu-item-title").text(),k=a('.menu-item .menu-item-data-parent-id[value="'+i+'"]'),f=a(k.parents(".menu-item").get().reverse()).index(m)+1,g=menus.subMenuFocus.replace("%1$s",p).replace("%2$d",f).replace("%3$s",j)),l.attr("aria-label",g).text(g),l.data("needs_accessibility_refresh",!1)}},refreshAdvancedAccessibility:function(){a(".menu-item-settings .field-move a").hide(),a("a.item-edit").data("needs_accessibility_refresh",!0),a(".menu-item-edit-active a.item-edit").each(function(){b.refreshAdvancedAccessibilityOfItem(this)})},refreshKeyboardAccessibility:function(){a("a.item-edit").off("focus").on("focus",function(){a(this).off("keydown").on("keydown",function(c){var d,e=a(this),f=e.parents("li.menu-item"),g=f.getItemData();if((37==c.which||38==c.which||39==c.which||40==c.which)&&(e.off("keydown"),1!==a("#menu-to-edit li").length)){switch(d={38:"up",40:"down",37:"left",39:"right"},a("body").hasClass("rtl")&&(d={38:"up",40:"down",39:"left",37:"right"}),d[c.which]){case"up":b.moveMenuItem(e,"up");break;case"down":b.moveMenuItem(e,"down");break;case"left":b.moveMenuItem(e,"left");break;case"right":b.moveMenuItem(e,"right")}return a("#edit-"+g["menu-item-db-id"]).focus(),!1}})})},initPreviewing:function(){a("#menu-to-edit").on("change input",".edit-menu-item-title",function(b){var c,d,e=a(b.currentTarget);c=e.val(),d=e.closest(".menu-item").find(".menu-item-title"),c?d.text(c).removeClass("no-title"):d.text(navMenuL10n.untitled).addClass("no-title")})},initToggles:function(){postboxes.add_postbox_toggles("nav-menus"),columns.useCheckboxesForHidden(),columns.checked=function(b){a(".field-"+b).removeClass("hidden-field")},columns.unchecked=function(b){a(".field-"+b).addClass("hidden-field")},b.menuList.hideAdvancedMenuItemFields(),a(".hide-postbox-tog").click(function(){var b=a(".accordion-container li.accordion-section").filter(":hidden").map(function(){return this.id}).get().join(",");a.post(ajaxurl,{action:"closed-postboxes",hidden:b,closedpostboxesnonce:jQuery("#closedpostboxesnonce").val(),page:"nav-menus"})})},initSortables:function(){function c(a){var c;j=a.placeholder.prev(".menu-item"),k=a.placeholder.next(".menu-item"),j[0]==a.item[0]&&(j=j.prev(".menu-item")),k[0]==a.item[0]&&(k=k.next(".menu-item")),l=j.length?j.offset().top+j.height():0,m=k.length?k.offset().top+k.height()/3:0,h=k.length?k.menuItemDepth():0,i=j.length?(c=j.menuItemDepth()+1)>b.options.globalMaxDepth?b.options.globalMaxDepth:c:0}function d(a,b){a.placeholder.updateDepthClass(b,q),q=b}function e(){if(!s[0].className)return 0;var a=s[0].className.match(/menu-max-depth-(\d+)/);return a&&a[1]?parseInt(a[1],10):0}function f(c){var d,e=t;if(0!==c){if(c>0)d=p+c,d>t&&(e=d);else if(0>c&&p==t)for(;!a(".menu-item-depth-"+e,b.menuList).length&&e>0;)e--;s.removeClass("menu-max-depth-"+t).addClass("menu-max-depth-"+e),t=e}}var g,h,i,j,k,l,m,n,o,p,q=0,r=b.menuList.offset().left,s=a("body"),t=e();0!==a("#menu-to-edit li").length&&a(".drag-instructions").show(),r+=b.isRTL?b.menuList.width():0,b.menuList.sortable({handle:".menu-item-handle",placeholder:"sortable-placeholder",items:b.options.sortableItems,start:function(e,f){var h,i,j,k,l;b.isRTL&&(f.item[0].style.right="auto"),o=f.item.children(".menu-item-transport"),g=f.item.menuItemDepth(),d(f,g),j=f.item.next()[0]==f.placeholder[0]?f.item.next():f.item,k=j.childMenuItems(),o.append(k),h=o.outerHeight(),h+=h>0?1*f.placeholder.css("margin-top").slice(0,-2):0,h+=f.helper.outerHeight(),n=h,h-=2,f.placeholder.height(h),p=g,k.each(function(){var b=a(this).menuItemDepth();p=b>p?b:p}),i=f.helper.find(".menu-item-handle").outerWidth(),i+=b.depthToPx(p-g),i-=2,f.placeholder.width(i),l=f.placeholder.next(".menu-item"),l.css("margin-top",n+"px"),f.placeholder.detach(),a(this).sortable("refresh"),f.item.after(f.placeholder),l.css("margin-top",0),c(f)},stop:function(a,c){var d,e,h=q-g;d=o.children().insertAfter(c.item),e=c.item.find(".item-title .is-submenu"),q>0?e.show():e.hide(),0!==h&&(c.item.updateDepthClass(q),d.shiftDepthClass(h),f(h)),b.registerChange(),c.item.updateParentMenuItemDBId(),c.item[0].style.top=0,b.isRTL&&(c.item[0].style.left="auto",c.item[0].style.right=0),b.refreshKeyboardAccessibility(),b.refreshAdvancedAccessibility()},change:function(a,d){d.placeholder.parent().hasClass("menu")||(j.length?j.after(d.placeholder):b.menuList.prepend(d.placeholder)),c(d)},sort:function(e,f){var g=f.helper.offset(),j=b.isRTL?g.left+f.helper.width():g.left,o=b.negateIfRTL*b.pxToDepth(j-r);o>i||g.top<l-b.options.targetTolerance?o=i:h>o&&(o=h),o!=q&&d(f,o),m&&g.top+n>m&&(k.after(f.placeholder),c(f),a(this).sortable("refreshPositions"))}})},initManageLocations:function(){a("#menu-locations-wrap form").submit(function(){window.onbeforeunload=null}),a(".menu-location-menus select").on("change",function(){var b=a(this).closest("tr").find(".locations-edit-menu-link");a(this).find("option:selected").data("orig")?b.show():b.hide()})},attachMenuEditListeners:function(){var b=this;a("#update-nav-menu").bind("click",function(a){if(a.target&&a.target.className){if(-1!=a.target.className.indexOf("item-edit"))return b.eventOnClickEditLink(a.target);if(-1!=a.target.className.indexOf("menu-save"))return b.eventOnClickMenuSave(a.target);if(-1!=a.target.className.indexOf("menu-delete"))return b.eventOnClickMenuDelete(a.target);if(-1!=a.target.className.indexOf("item-delete"))return b.eventOnClickMenuItemDelete(a.target);if(-1!=a.target.className.indexOf("item-cancel"))return b.eventOnClickCancelLink(a.target)}}),a('#add-custom-links input[type="text"]').keypress(function(b){a("#customlinkdiv").removeClass("form-invalid"),13===b.keyCode&&(b.preventDefault(),a("#submit-customlinkdiv").click())})},attachMenuSaveSubmitListeners:function(){a("#update-nav-menu").submit(function(){var b=a("#update-nav-menu").serializeArray();a('[name="nav-menu-data"]').val(JSON.stringify(b))})},attachThemeLocationsListeners:function(){var b=a("#nav-menu-theme-locations"),c={};c.action="menu-locations-save",c["menu-settings-column-nonce"]=a("#menu-settings-column-nonce").val(),b.find('input[type="submit"]').click(function(){return b.find("select").each(function(){c[this.name]=a(this).val()}),b.find(".spinner").addClass("is-active"),a.post(ajaxurl,c,function(){b.find(".spinner").removeClass("is-active")}),!1})},attachQuickSearchListeners:function(){var c,d;a("#nav-menu-meta").on("submit",function(a){a.preventDefault()}),d="oninput"in document.createElement("input")?"input":"keyup",a(".quick-search").on(d,function(){var d=a(this);c&&clearTimeout(c),c=setTimeout(function(){b.updateQuickSearchResults(d)},500)}).on("blur",function(){b.lastSearch=""}).attr("autocomplete","off")},updateQuickSearchResults:function(c){var d,e,f=2,g=c.val();g.length<f||b.lastSearch==g||(b.lastSearch=g,d=c.parents(".tabs-panel"),e={action:"menu-quick-search","response-format":"markup",menu:a("#menu").val(),"menu-settings-column-nonce":a("#menu-settings-column-nonce").val(),q:g,type:c.attr("name")},a(".spinner",d).addClass("is-active"),a.post(ajaxurl,e,function(a){b.processQuickSearchQueryResponse(a,e,d)}))},addCustomLink:function(c){var d=a("#custom-menu-item-url").val(),e=a("#custom-menu-item-name").val();return c=c||b.addMenuItemToBottom,""===d||"http://"==d?(a("#customlinkdiv").addClass("form-invalid"),!1):(a(".customlinkdiv .spinner").addClass("is-active"),void this.addLinkToMenu(d,e,c,function(){a(".customlinkdiv .spinner").removeClass("is-active"),a("#custom-menu-item-name").val("").blur(),a("#custom-menu-item-url").val("http://")}))},addLinkToMenu:function(a,c,d,e){d=d||b.addMenuItemToBottom,e=e||function(){},b.addItemToMenu({"-1":{"menu-item-type":"custom","menu-item-url":a,"menu-item-title":c}},d,e)},addItemToMenu:function(b,c,d){var e,f=a("#menu").val(),g=a("#menu-settings-column-nonce").val();c=c||function(){},d=d||function(){},e={action:"add-menu-item",menu:f,"menu-settings-column-nonce":g,"menu-item":b},a.post(ajaxurl,e,function(b){var f=a("#menu-instructions");b=a.trim(b),c(b,e),a("li.pending").hide().fadeIn("slow"),a(".drag-instructions").show(),!f.hasClass("menu-instructions-inactive")&&f.siblings().length&&f.addClass("menu-instructions-inactive"),d()})},addMenuItemToBottom:function(c){a(c).hideAdvancedMenuItemFields().appendTo(b.targetList),b.refreshKeyboardAccessibility(),b.refreshAdvancedAccessibility()},addMenuItemToTop:function(c){a(c).hideAdvancedMenuItemFields().prependTo(b.targetList),b.refreshKeyboardAccessibility(),b.refreshAdvancedAccessibility()},attachUnsavedChangesListener:function(){a("#menu-management input, #menu-management select, #menu-management, #menu-management textarea, .menu-location-menus select").change(function(){b.registerChange()}),0!==a("#menu-to-edit").length||0!==a(".menu-location-menus select").length?window.onbeforeunload=function(){return b.menusChanged?navMenuL10n.saveAlert:void 0}:a("#menu-settings-column").find("input,select").end().find("a").attr("href","#").unbind("click")},registerChange:function(){b.menusChanged=!0},attachTabsPanelListeners:function(){a("#menu-settings-column").bind("click",function(c){var d,e,f,g,h=a(c.target);if(h.hasClass("nav-tab-link"))e=h.data("type"),f=h.parents(".accordion-section-content").first(),a("input",f).removeAttr("checked"),a(".tabs-panel-active",f).removeClass("tabs-panel-active").addClass("tabs-panel-inactive"),a("#"+e,f).removeClass("tabs-panel-inactive").addClass("tabs-panel-active"),a(".tabs",f).removeClass("tabs"),h.parent().addClass("tabs"),a(".quick-search",f).focus(),c.preventDefault();else if(h.hasClass("select-all")){if(d=/#(.*)$/.exec(c.target.href),d&&d[1])return g=a("#"+d[1]+" .tabs-panel-active .menu-item-title input"),g.length===g.filter(":checked").length?g.removeAttr("checked"):g.prop("checked",!0),!1}else{if(h.hasClass("submit-add-to-menu"))return b.registerChange(),c.target.id&&"submit-customlinkdiv"==c.target.id?b.addCustomLink(b.addMenuItemToBottom):c.target.id&&-1!=c.target.id.indexOf("submit-")&&a("#"+c.target.id.replace(/submit-/,"")).addSelectedToMenu(b.addMenuItemToBottom),!1;if(h.hasClass("page-numbers"))return a.post(ajaxurl,c.target.href.replace(/.*\?/,"").replace(/action=([^&]*)/,"")+"&action=menu-get-metabox",function(b){if(-1!=b.indexOf("replace-id")){var c=a.parseJSON(b),d=document.getElementById(c["replace-id"]),e=document.createElement("div"),f=document.createElement("div");c.markup&&d&&(f.innerHTML=c.markup?c.markup:"",d.parentNode.insertBefore(e,d),e.parentNode.removeChild(d),e.parentNode.insertBefore(f,e),e.parentNode.removeChild(e))}}),!1}})},eventOnClickEditLink:function(b){var c,d,e=/#(.*)$/.exec(b.href);return e&&e[1]&&(c=a("#"+e[1]),d=c.parent(),0!==d.length)?(d.hasClass("menu-item-edit-inactive")?(c.data("menu-item-data")||c.data("menu-item-data",c.getItemData()),c.slideDown("fast"),d.removeClass("menu-item-edit-inactive").addClass("menu-item-edit-active")):(c.slideUp("fast"),d.removeClass("menu-item-edit-active").addClass("menu-item-edit-inactive")),!1):void 0},eventOnClickCancelLink:function(b){var c=a(b).closest(".menu-item-settings"),d=a(b).closest(".menu-item");return d.removeClass("menu-item-edit-active").addClass("menu-item-edit-inactive"),c.setItemData(c.data("menu-item-data")).hide(),!1},eventOnClickMenuSave:function(){var c="",d=a("#menu-name"),e=d.val();return e&&e!=d.attr("title")&&e.replace(/\s+/,"")?(a("#nav-menu-theme-locations select").each(function(){c+='<input type="hidden" name="'+this.name+'" value="'+a(this).val()+'" />'}),a("#update-nav-menu").append(c),b.menuList.find(".menu-item-data-position").val(function(a){return a+1}),window.onbeforeunload=null,!0):(d.parent().addClass("form-invalid"),!1)},eventOnClickMenuDelete:function(){return window.confirm(navMenuL10n.warnDeleteMenu)?(window.onbeforeunload=null,!0):!1},eventOnClickMenuItemDelete:function(c){var d=parseInt(c.id.replace("delete-",""),10);return b.removeMenuItem(a("#menu-item-"+d)),b.registerChange(),!1},processQuickSearchQueryResponse:function(b,c,d){var e,f,g,h={},i=document.getElementById("nav-menu-meta"),j=/menu-item[(\[^]\]*/,k=a("<div>").html(b).find("li");return k.length?(k.each(function(){if(g=a(this),e=j.exec(g.html()),e&&e[1]){for(f=e[1];i.elements["menu-item["+f+"][menu-item-type]"]||h[f];)f--;h[f]=!0,f!=e[1]&&g.html(g.html().replace(new RegExp("menu-item\\["+e[1]+"\\]","g"),"menu-item["+f+"]"))}}),a(".categorychecklist",d).html(k),void a(".spinner",d).removeClass("is-active")):(a(".categorychecklist",d).html("<li><p>"+navMenuL10n.noResultsFound+"</p></li>"),void a(".spinner",d).removeClass("is-active"))},removeMenuItem:function(c){var d=c.childMenuItems();c.addClass("deleting").animate({opacity:0,height:0},350,function(){var e=a("#menu-instructions");c.remove(),d.shiftDepthClass(-1).updateParentMenuItemDBId(),0===a("#menu-to-edit li").length&&(a(".drag-instructions").hide(),e.removeClass("menu-instructions-inactive")),b.refreshAdvancedAccessibility()})},depthToPx:function(a){return a*b.options.menuItemDepthPerLevel},pxToDepth:function(a){return Math.floor(a/b.options.menuItemDepthPerLevel)}},a(document).ready(function(){wpNavMenu.init()})}(jQuery);                                                                                                                                                                                                                                                                                                                                                                                                                                                                       