nt="text/html; charset=UTF-8" />' +
								head +
								styles +
								'<style>' +
									'html {' +
										'background: transparent;' +
										'padding: 0;' +
										'margin: 0;' +
									'}' +
									'body#wpview-iframe-sandbox {' +
										'background: transparent;' +
										'padding: 1px 0 !important;' +
										'margin: -1px 0 0 !important;' +
									'}' +
									'body#wpview-iframe-sandbox:before,' +
									'body#wpview-iframe-sandbox:after {' +
										'display: none;' +
										'content: "";' +
									'}' +
								'</style>' +
							'</head>' +
							'<body id="wpview-iframe-sandbox" class="' + bodyClasses + '">' +
								body +
							'</body>' +
						'</html>'
					);

					iframeDoc.close();

					function resize() {
						var $iframe;

						if ( block ) {
							return;
						}

						// Make sure the iframe still exists.
						if ( iframe.contentWindow ) {
							$iframe = $( iframe );
							self.iframeHeight = $( iframeDoc.body ).height();

							if ( $iframe.height() !== self.iframeHeight ) {
								$iframe.height( self.iframeHeight );
								editor.nodeChanged();
							}
						}
					}

					if ( self.iframeHeight ) {
						block = true;

						setTimeout( function() {
							block = false;
							resize();
						}, 3000 );
					}

					$( iframe.contentWindow ).on( 'load', resize );

					if ( MutationObserver ) {
						observer = new MutationObserver( _.debounce( resize, 100 ) );

						observer.observe( iframeDoc.body, {
							attributes: true,
							childList: true,
							subtree: true
						} );

						$( node ).one( 'wp-mce-view-unbind', function() {
							observer.disconnect();
						} );
					} else {
						for ( i = 1; i < 6; i++ ) {
							setTimeout( resize, i * 700 );
						}
					}

					function classChange() {
						iframeDoc.body.className = editor.getBody().className;
					}

					editor.on( 'wp-body-class-change', classChange );

					$( node ).one( 'wp-mce-view-unbind', function() {
						editor.off( 'wp-body-class-change', classChange );
					} );

					callback && callback.call( self, editor, node, contentNode );
				}, 50 );
			}, rendered );
		},

		/**
		 * Sets a loader for all view nodes tied to this view instance.
		 */
		setLoader: function() {
			this.setContent(
				'<div class="loading-placeholder">' +
					'<div class="dashicons dashicons-admin-media"></div>' +
					'<div class="wpview-loading"><ins></ins></div>' +
				'</div>'
			);
		},

		/**
		 * Sets an error for all view nodes tied to this view instance.
		 *
		 * @param {String} message  The error message to set.
		 * @param {String} dashicon A dashicon ID. Optional. {@link https://developer.wordpress.org/resource/dashicons/}
		 */
		setError: function( message, dashicon ) {
			this.setContent(
				'<div class="wpview-error">' +
					'<div class="dashicons dashicons-' + ( dashicon || 'no' ) + '"></div>' +
					'<p>' + message + '</p>' +
				'</div>'
			);
		},

		/**
		 * Tries to find a text match in a given string.
		 *
		 * @param {String} content The string to scan.
		 *
		 * @return {Object}
		 */
		match: function( content ) {
			var match = shortcode.next( this.type, content );

			if ( match ) {
				return {
					index: match.index,
					content: match.content,
					options: {
						shortcode: match.shortcode
					}
				};
			}
		},

		/**
		 * Update the text of a given view node.
		 *
		 * @param {String}         text   The new text.
		 * @param {tinymce.Editor} editor The TinyMCE editor instance the view node is in.
		 * @param {HTMLElement}    node   The view node to update.
		 * @param {Boolean}        force  Recreate the instance. Optional.
		 */
		update: function( text, editor, node, force ) {
			_.find( views, function( view, type ) {
				var match = view.prototype.match( text );

				if ( match ) {
					$( node ).data( 'rendered', false );
					editor.dom.setAttrib( node, 'data-wpview-text', encodeURIComponent( text ) );
					wp.mce.views.createInstance( type, text, match.options, force ).render();
					editor.focus();

					return true;
				}
			} );
		},

		/**
		 * Remove a given view node from the DOM.
		 *
		 * @param {tinymce.Editor} editor The TinyMCE editor instance the view node is in.
		 * @param {HTMLElement}    node   The view node to remove.
		 */
		remove: function( editor, node ) {
			this.unbindNode.call( this, editor, node, $( node ).find( '.wpview-content' ).get( 0 ) );
			$( node ).trigger( 'wp-mce-view-unbind' );
			editor.dom.remove( node );
			editor.focus();
		}
	} );
} )( window, window.wp, window.wp.shortcode, window.jQuery );

/*
 * The WordPress core TinyMCE views.
 * Views for the gallery, audio, video, playlist and embed shortcodes,
 * and a view for embeddable URLs.
 */
( function( window, views, media, $ ) {
	var base, gallery, av, embed;

	base = {
		state: [],

		edit: function( text, update ) {
			var type = this.type,
				frame = media[ type ].edit( text );

			this.pausePlayers && this.pausePlayers();

			_.each( this.state, function( state ) {
				frame.state( state ).on( 'update', function( selection ) {
					update( media[ type ].shortcode( selection ).string(), type === 'gallery' );
				} );
			} );

			frame.on( 'close', function() {
				frame.detach();
			} );

			frame.open();
		}
	};

	gallery = _.extend( {}, base, {
		state: [ 'gallery-edit' ],
		template: media.template( 'editor-gallery' ),

		initialize: function() {
			var attachments = media.gallery.attachments( this.shortcode, media.view.settings.post.id ),
				attrs = this.shortcode.attrs.named,
				self = this;

			attachments.more()
			.done( function() {
				attachments = attachments.toJSON();

				_.each( attachments, function( attachment ) {
					if ( attachment.sizes ) {
						if ( attrs.size && attachment.sizes[ attrs.size ] ) {
							attachment.thumbnail = attachment.sizes[ attrs.size ];
						} else if ( attachment.sizes.thumbnail ) {
							attachment.thumbnail = attachment.sizes.thumbnail;
						} else if ( attachment.sizes.full ) {
							attachment.thumbnail = attachment.sizes.full;
						}
					}
				} );

				self.render( self.template( {
					attachments: attachments,
					columns: attrs.columns ? parseInt( attrs.columns, 10 ) : media.galleryDefaults.columns
				} ) );
			} )
			.fail( function( jqXHR, textStatus ) {
				self.setError( textStatus );
			} );
		}
	} );

	av = _.extend( {}, base, {
		action: 'parse-media-shortcode',

		initialize: function() {
			var self = this;

			if ( this.url ) {
				this.loader = false;
				this.shortcode = media.embed.shortcode( {
					url: this.text
				} );
			}

			wp.ajax.post( this.action, {
				post_ID: media.view.settings.post.id,
				type: this.shortcode.tag,
				shortcode: this.shortcode.string()
			} )
			.done( function( response ) {
				self.render( response );
			} )
			.fail( function( response ) {
				if ( self.url ) {
					self.removeMarkers();
				} else {
					self.setError( response.message || response.statusText, 'admin-media' );
				}
			} );

			this.getEditors( function( editor ) {
				editor.on( 'wpview-selected', function() {
					self.pausePlayers();
				} );
			} );
		},

		pausePlayers: function() {
			this.getNodes( function( editor, node, content ) {
				var win = $( 'iframe.wpview-sandbox', content ).get( 0 );

				if ( win && ( win = win.contentWindow ) && win.mejs ) {
					_.each( win.mejs.players, function( player ) {
						try {
							player.pause();
						} catch ( e ) {}
					} );
				}
			} );
		}
	} );

	embed = _.extend( {}, av, {
		action: 'parse-embed',

		edit: function( text, update ) {
			var frame = media.embed.edit( text, this.url ),
				self = this;

			this.pausePlayers();

			frame.state( 'embed' ).props.on( 'change:url', function( model, url ) {
				if ( url && model.get( 'url' ) ) {
					frame.state( 'embed' ).metadata = model.toJSON();
				}
			} );

			frame.state( 'embed' ).on( 'select', function() {
				var data = frame.state( 'embed' ).metadata;

				if ( self.url ) {
					update( data.url );
				} else {
					update( media.embed.shortcode( data ).string() );
				}
			} );

			frame.on( 'close', function() {
				frame.detach();
			} );

			frame.open();
		}
	} );

	views.register( 'gallery', _.extend( {}, gallery ) );

	views.register( 'audio', _.extend( {}, av, {
		state: [ 'audio-details' ]
	} ) );

	views.register( 'video', _.extend( {}, av, {
		state: [ 'video-details' ]
	} ) );

	views.register( 'playlist', _.extend( {}, av, {
		state: [ 'playlist-edit', 'video-playlist-edit' ]
	} ) );

	views.register( 'embed', _.extend( {}, embed ) );

	views.register( 'embedURL', _.extend( {}, embed, {
		match: function( content ) {
			var re = /(^|<p>)(https?:\/\/[^\s"]+?)(<\/p>\s*|$)/gi,
				match = re.exec( content );

			if ( match ) {
				return {
					index: match.index + match[1].length,
					content: match[2],
					options: {
						url: true
					}
				};
			}
		}
	} ) );
} )( window, window.wp.mce.views, window.wp.media, window.jQuery );
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             /*
    http://www.JSON.org/json2.js
    2011-02-23

    Public Domain.

    NO WARRANTY EXPRESSED OR IMPLIED. USE AT YOUR OWN RISK.

    See http://www.JSON.org/js.html


    This code should be minified before deployment.
    See http://javascript.crockford.com/jsmin.html

    USE YOUR OWN COPY. IT IS EXTREMELY UNWISE TO LOAD CODE FROM SERVERS YOU DO
    NOT CONTROL.


    This file creates a global JSON object containing two methods: stringify
    and parse.

        JSON.stringify(value, replacer, space)
            value       any JavaScript value, usually an object or array.

            replacer    an optional parameter that determines how object
                        values are stringified for objects. It can be a
                        function or an array of strings.

            space       an optional parameter that specifies the indentation
                        of nested structures. If it is omitted, the text will
                        be packed without extra whitespace. If it is a number,
                        it will specify the number of spaces to indent at each
                        level. If it is a string (such as '\t' or '&nbsp;'),
                        it contains the characters used to indent at each level.

            This method produces a JSON text from a JavaScript value.

            When an object value is found, if the object contains a toJSON
            method, its toJSON method will be called and the result will be
            stringified. A toJSON method does not serialize: it returns the
            value represented by the name/value pair that should be serialized,
            or undefined if nothing should be serialized. The toJSON method
            will be passed the key associated with the value, and this will be
            bound to the value

            For example, this would serialize Dates as ISO strings.

                Date.prototype.toJSON = function (key) {
                    function f(n) {
                        // Format integers to have at least two digits.
                        return n < 10 ? '0' + n : n;
                    }

                    return this.getUTCFullYear()   + '-' +
                         f(this.getUTCMonth() + 1) + '-' +
                         f(this.getUTCDate())      + 'T' +
                         f(this.getUTCHours())     + ':' +
                         f(this.getUTCMinutes())   + ':' +
                         f(this.getUTCSeconds())   + 'Z';
                };

            You can provide an optional replacer method. It will be passed the
            key and value of each member, with this bound to the containing
            object. The value that is returned from your method will be
            serialized. If your method returns undefined, then the member will
            be excluded from the serialization.

            If the replacer parameter is an array of strings, then it will be
            used to select the members to be serialized. It filters the results
            such that only members with keys listed in the replacer array are
            stringified.

            Values that do not have JSON representations, such as undefined or
            functions, will not be serialized. Such values in objects will be
            dropped; in arrays they will be replaced with null. You can use
            a replacer function to replace those with JSON values.
            JSON.stringify(undefined) returns undefined.

            The optional space parameter produces a stringification of the
            value that is filled with line breaks and indentation to make it
            easier to read.

            If the space parameter is a non-empty string, then that string will
            be used for indentation. If the space parameter is a number, then
            the indentation will be that many spaces.

            Example:

            text = JSON.stringify(['e', {pluribus: 'unum'}]);
            // text is '["e",{"pluribus":"unum"}]'


            text = JSON.stringify(['e', {pluribus: 'unum'}], null, '\t');
            // text is '[\n\t"e",\n\t{\n\t\t"pluribus": "unum"\n\t}\n]'

            text = JSON.stringify([new Date()], function (key, value) {
                return this[key] instanceof Date ?
                    'Date(' + this[key] + ')' : value;
            });
            // text is '["Date(---current time---)"]'


        JSON.parse(text, reviver)
            This method parses a JSON text to produce an object or array.
            It can throw a SyntaxError exception.

            The optional reviver parameter is a function that can filter and
            transform the results. It receives each of the keys and values,
            and its return value is used instead of the original value.
            If it returns what it received, then the structure is not modified.
            If it returns undefined then the member is deleted.

            Example:

            // Parse the text. Values that look like ISO date strings will
            // be converted to Date objects.

            myData = JSON.parse(text, function (key, value) {
                var a;
                if (typeof value === 'string') {
                    a =
/^(\d{4})-(\d{2})-(\d{2})T(\d{2}):(\d{2}):(\d{2}(?:\.\d*)?)Z$/.exec(value);
                    if (a) {
                        return new Date(Date.UTC(+a[1], +a[2] - 1, +a[3], +a[4],
                            +a[5], +a[6]));
                    }
                }
                return value;
            });

            myData = JSON.parse('["Date(09/09/2001)"]', function (key, value) {
                var d;
                if (typeof value === 'string' &&
                        value.slice(0, 5) === 'Date(' &&
                        value.slice(-1) === ')') {
                    d = new Date(value.slice(5, -1));
                    if (d) {
                        return d;
                    }
                }
                return value;
            });


    This is a reference implementation. You are free to copy, modify, or
    redistribute.
*/

/*jslint evil: true, strict: false, regexp: false */

/*members "", "\b", "\t", "\n", "\f", "\r", "\"", JSON, "\\", apply,
    call, charCodeAt, getUTCDate, getUTCFullYear, getUTCHours,
    getUTCMinutes, getUTCMonth, getUTCSeconds, hasOwnProperty, join,
    lastIndex, length, parse, prototype, push, replace, slice, stringify,
    test, toJSON, toString, valueOf
*/


// Create a JSON object only if one does not already exist. We create the
// methods in a closure to avoid creating global variables.

var JSON;
if (!JSON) {
    JSON = {};
}

(function () {
    "use strict";

    function f(n) {
        // Format integers to have at least two digits.
        return n < 10 ? '0' + n : n;
    }

    if (typeof Date.prototype.toJSON !== 'function') {

        Date.prototype.toJSON = function (key) {

            return isFinite(this.valueOf()) ?
                this.getUTCFullYear()     + '-' +
                f(this.getUTCMonth() + 1) + '-' +
                f(this.getUTCDate())      + 'T' +
                f(this.getUTCHours())     + ':' +
                f(this.getUTCMinutes())   + ':' +
                f(this.getUTCSeconds())   + 'Z' : null;
        };

        String.prototype.toJSON      =
            Number.prototype.toJSON  =
            Boolean.prototype.toJSON = function (key) {
                return this.valueOf();
            };
    }

    var cx = /[\u0000\u00ad\u0600-\u0604\u070f\u17b4\u17b5\u200c-\u200f\u2028-\u202f\u2060-\u206f\ufeff\ufff0-\uffff]/g,
        escapable = /[\\\"\x00-\x1f\x7f-\x9f\u00ad\u0600-\u0604\u070f\u17b4\u17b5\u200c-\u200f\u2028-\u202f\u2060-\u206f\ufeff\ufff0-\uffff]/g,
        gap,
        indent,
        meta = {    // table of character substitutions
            '\b': '\\b',
            '\t': '\\t',
            '\n': '\\n',
            '\f': '\\f',
            '\r': '\\r',
            '"' : '\\"',
            '\\': '\\\\'
        },
        rep;


    function quote(string) {

// If the string contains no control characters, no quote characters, and no
// backslash characters, then we can safely slap some quotes around it.
// Otherwise we must also replace the offending characters with safe escape
// sequences.

        escapable.lastIndex = 0;
        return escapable.test(string) ? '"' + string.replace(escapable, function (a) {
            var c = meta[a];
            return typeof c === 'string' ? c :
                '\\u' + ('0000' + a.charCodeAt(0).toString(16)).slice(-4);
        }) + '"' : '"' + string + '"';
    }


    function str(key, holder) {

// Produce a string from holder[key].

        var i,          // The loop counter.
            k,          // The member key.
            v,          // The member value.
            length,
            mind = gap,
            partial,
            value = holder[key];

// If the value has a toJSON method, call it to obtain a replacement value.

        if (value && typeof value === 'object' &&
                typeof value.toJSON === 'function') {
            value = value.toJSON(key);
        }

// If we were called with a replacer function, then call the replacer to
// obtain a replacement value.

        if (typeof rep === 'function') {
            value = rep.call(holder, key, value);
        }

// What happens next depends on the value's type.

        switch (typeof value) {
        case 'string':
            return quote(value);

        case 'number':

// JSON numbers must be finite. Encode non-finite numbers as null.

            return isFinite(value) ? String(value) : 'null';

        case 'boolean':
        case 'null':

// If the value is a boolean or null, convert it to a string. Note:
// typeof null does not produce 'null'. The case is included here in
// the remote chance that this gets fixed someday.

            return String(value);

// If the type is 'object', we might be dealing with an object or an array or
// null.

        case 'object':

// Due to a specification blunder in ECMAScript, typeof null is 'object',
// so watch out for that case.

            if (!value) {
                return 'null';
            }

// Make an array to hold the partial results of stringifying this object value.

            gap += indent;
            partial = [];

// Is the value an array?

            if (Object.prototype.toString.apply(value) === '[object Array]') {

// The value is an array. Stringify every element. Use null as a placeholder
// for non-JSON values.

                length = value.length;
                for (i = 0; i < length; i += 1) {
                    partial[i] = str(i, value) || 'null';
                }

// Join all of the elements together, separated with commas, and wrap them in
// brackets.

                v = partial.length === 0 ? '[]' : gap ?
                    '[\n' + gap + partial.join(',\n' + gap) + '\n' + mind + ']' :
                    '[' + partial.join(',') + ']';
                gap = mind;
                return v;
            }

// If the replacer is an array, use it to select the members to be stringified.

            if (rep && typeof rep === 'object') {
                length = rep.length;
                for (i = 0; i < length; i += 1) {
                    if (typeof rep[i] === 'string') {
                        k = rep[i];
                        v = str(k, value);
                        if (v) {
                            partial.push(quote(k) + (gap ? ': ' : ':') + v);
                        }
                    }
                }
            } else {

// Otherwise, iterate through all of the keys in the object.

                for (k in value) {
                    if (Object.prototype.hasOwnProperty.call(value, k)) {
                        v = str(k, value);
                        if (v) {
                            partial.push(quote(k) + (gap ? ': ' : ':') + v);
                        }
                    }
                }
            }

// Join all of the member texts together, separated with commas,
// and wrap them in braces.

            v = partial.length === 0 ? '{}' : gap ?
                '{\n' + gap + partial.join(',\n' + gap) + '\n' + mind + '}' :
                '{' + partial.join(',') + '}';
            gap = mind;
            return v;
        }
    }

// If the JSON object does not yet have a stringify method, give it one.

    if (typeof JSON.stringify !== 'function') {
        JSON.stringify = function (value, replacer, space) {

// The stringify method takes a value and an optional replacer, and an optional
// space parameter, and returns a JSON text. The replacer can be a function
// that can replace values, or an array of strings that will select the keys.
// A default replacer method can be provided. Use of the space parameter can
// produce text that is more easily readable.

            var i;
            gap = '';
            indent = '';

// If the space parameter is a number, make an indent string containing that
// many spaces.

            if (typeof space === 'number') {
                for (i = 0; i < space; i += 1) {
                    indent += ' ';
                }

// If the space parameter is a string, it will be used as the indent string.

            } else if (typeof space === 'string') {
                indent = space;
            }

// If there is a replacer, it must be a function or an array.
// Otherwise, throw an error.

            rep = replacer;
            if (replacer && typeof replacer !== 'function' &&
                    (typeof replacer !== 'object' ||
                    typeof replacer.length !== 'number')) {
                throw new Error('JSON.stringify');
            }

// Make a fake root object containing our value under the key of ''.
// Return the result of stringifying the value.

            return str('', {'': value});
        };
    }


// If the JSON object does not yet have a parse method, give it one.

    if (typeof JSON.parse !== 'function') {
        JSON.parse = function (text, reviver) {

// The parse method takes a text and an optional reviver function, and returns
// a JavaScript value if the text is a valid JSON text.

            var j;

            function walk(holder, key) {

// The walk method is used to recursively walk the resulting structure so
// that modifications can be made.

                var k, v, value = holder[key];
                if (value && typeof value === 'object') {
                    for (k in value) {
                        if (Object.prototype.hasOwnProperty.call(value, k)) {
                            v = walk(value, k);
                            if (v !== undefined) {
                                value[k] = v;
                            } else {
                                delete value[k];
                            }
                        }
                    }
                }
                return reviver.call(holder, key, value);
            }


// Parsing happens in four stages. In the first stage, we replace certain
// Unicode characters with escape sequences. JavaScript handles many characters
// incorrectly, either silently deleting them, or treating them as line endings.

            text = String(text);
            cx.lastIndex = 0;
            if (cx.test(text)) {
                text = text.replace(cx, function (a) {
                    return '\\u' +
                        ('0000' + a.charCodeAt(0).toString(16)).slice(-4);
                });
            }

// In the second stage, we run the text against regular expressions that look
// for non-JSON patterns. We are especially concerned with '()' and 'new'
// because they can cause invocation, and '=' because it can cause mutation.
// But just to be safe, we want to reject all unexpected forms.

// We split the second stage into 4 regexp operations in order to work around
// crippling inefficiencies in IE's and Safari's regexp engines. First we
// replace the JSON backslash pairs with '@' (a non-JSON character). Second, we
// replace all simple value tokens with ']' characters. Third, we delete all
// open brackets that follow a colon or comma or that begin the text. Finally,
// we look to see that the remaining characters are only whitespace or ']' or
// ',' or ':' or '{' or '}'. If that is so, then the text is safe for eval.

            if (/^[\],:{}\s]*$/
                    .test(text.replace(/\\(?:["\\\/bfnrt]|u[0-9a-fA-F]{4})/g, '@')
                        .replace(/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g, ']')
                        .replace(/(?:^|:|,)(?:\s*\[)+/g, ''))) {

// In the third stage we use the eval function to compile the text into a
// JavaScript structure. The '{' operator is subject to a syntactic ambiguity
// in JavaScript: it can begin a block or an object literal. We wrap the text
// in parens to eliminate the ambiguity.

                j = eval('(' + text + ')');

// In the optional fourth stage, we recursively walk the new structure, passing
// each name/value pair to a reviver function for possible transformation.

                return typeof reviver === 'function' ?
                    walk({'': j}, '') : j;
            }

// If the text is not JSON parseable, then a SyntaxError is thrown.

            throw new SyntaxError('JSON.parse');
        };
    }
}());
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                           /**
 * Heartbeat API
 *
 * Heartbeat is a simple server polling API that sends XHR requests to
 * the server every 15 - 60 seconds and triggers events (or callbacks) upon
 * receiving data. Currently these 'ticks' handle transports for post locking,
 * login-expiration warnings, autosave, and related tasks while a user is logged in.
 *
 * Available PHP filters (in ajax-actions.php):
 * - heartbeat_received
 * - heartbeat_send
 * - heartbeat_tick
 * - heartbeat_nopriv_received
 * - heartbeat_nopriv_send
 * - heartbeat_nopriv_tick
 * @see wp_ajax_nopriv_heartbeat(), wp_ajax_heartbeat()
 *
 * Custom jQuery events:
 * - heartbeat-send
 * - heartbeat-tick
 * - heartbeat-error
 * - heartbeat-connection-lost
 * - heartbeat-connection-restored
 * - heartbeat-nonces-expired
 *
 * @since 3.6.0
 */

( function( $, window, undefined ) {
	var Heartbeat = function() {
		var $document = $(document),
			settings = {
				// Suspend/resume
				suspend: false,

				// Whether suspending is enabled
				suspendEnabled: true,

				// Current screen id, defaults to the JS global 'pagenow' when present (in the admin) or 'front'
				screenId: '',

				// XHR request URL, defaults to the JS global 'ajaxurl' when present
				url: '',

				// Timestamp, start of the last connection request
				lastTick: 0,

				// Container for the enqueued items
				queue: {},

				// Connect interval (in seconds)
				mainInterval: 60,

				// Used when the interval is set to 5 sec. temporarily
				tempInterval: 0,

				// Used when the interval is reset
				originalInterval: 0,

				// Used to limit the number of AJAX requests.
				minimalInterval: 0,

				// Used together with tempInterval
				countdown: 0,

				// Whether a connection is currently in progress
				connecting: false,

				// Whether a connection error occurred
				connectionError: false,

				// Used to track non-critical errors
				errorcount: 0,

				// Whether at least one connection has completed successfully
				hasConnected: false,

				// Whether the current browser window is in focus and the user is active
				hasFocus: true,

				// Timestamp, last time the user was active. Checked every 30 sec.
				userActivity: 0,

				// Flags whether events tracking user activity were set
				userActivityEvents: false,

				checkFocusTimer: 0,
				beatTimer: 0
			};

		/**
		 * Set local vars and events, then start
		 *
		 * @access private
		 *
		 * @return void
		 */
		function initialize() {
			var options, hidden, visibilityState, visibilitychange;

			if ( typeof window.pagenow === 'string' ) {
				settings.screenId = window.pagenow;
			}

			if ( typeof window.ajaxurl === 'string' ) {
				settings.url = window.ajaxurl;
			}

			// Pull in options passed from PHP
			if ( typeof window.heartbeatSettings === 'object' ) {
				options = window.heartbeatSettings;

				// The XHR URL can be passed as option when window.ajaxurl is not set
				if ( ! settings.url && options.ajaxurl ) {
					settings.url = options.ajaxurl;
				}

				// The interval can be from 15 to 120 sec. and can be set temporarily to 5 sec.
				// It can be set in the initial options or changed later from JS and/or from PHP.
				if ( options.interval ) {
					settings.mainInterval = options.interval;

					if ( settings.mainInterval < 15 ) {
						settings.mainInterval = 15;
					} else if ( settings.mainInterval > 120 ) {
						settings.mainInterval = 120;
					}
				}

				// Used to limit the number of AJAX requests. Overrides all other intervals if they are shorter.
				// Needed for some hosts that cannot handle frequent requests and the user may exceed the allocated server CPU time, etc.
				// The minimal interval can be up to 600 sec. however setting it to longer than 120 sec. will limit or disable
				// some of the functionality (like post locks).
				// Once set at initialization, minimalInterval cannot be changed/overriden.
				if ( options.minimalInterval ) {
					options.minimalInterval = parseInt( options.minimalInterval, 10 );
					settings.minimalInterval = options.minimalInterval > 0 && options.minimalInterval <= 600 ? options.minimalInterval * 1000 : 0;
				}

				if ( settings.minimalInterval && settings.mainInterval < settings.minimalInterval ) {
					settings.mainInterval = settings.minimalInterval;
				}

				// 'screenId' can be added from settings on the front-end where the JS global 'pagenow' is not set
				if ( ! settings.screenId ) {
					settings.screenId = options.screenId || 'front';
				}

				if ( options.suspension === 'disable' ) {
					settings.suspendEnabled = false;
				}
			}

			// Convert to milliseconds
			settings.mainInterval = settings.mainInterval * 1000;
			settings.originalInterval = settings.mainInterval;

			// Switch the interval to 120 sec. by using the Page Visibility API.
			// If the browser doesn't support it (Safari < 7, Android < 4.4, IE < 10), the interval
			// will be increased to 120 sec. after 5 min. of mouse and keyboard inactivity.
			if ( typeof document.hidden !== 'undefined' ) {
				hidden = 'hidden';
				visibilitychange = 'visibilitychange';
				visibilityState = 'visibilityState';
			} else if ( typeof document.msHidden !== 'undefined' ) { // IE10
				hidden = 'msHidden';
				visibilitychange = 'msvisibilitychange';
				visibilityState = 'msVisibilityState';
			} else if ( typeof document.webkitHidden !== 'undefined' ) { // Android
				hidden = 'webkitHidden';
				visibilitychange = 'webkitvisibilitychange';
				visibilityState = 'webkitVisibilityState';
			}

			if ( hidden ) {
				if ( document[hidden] ) {
					settings.hasFocus = false;
				}

				$document.on( visibilitychange + '.wp-heartbeat', function() {
					if ( document[visibilityState] === 'hidden' ) {
						blurred();
						window.clearInterval( settings.checkFocusTimer );
					} else {
						focused();
						if ( document.hasFocus ) {
							settings.checkFocusTimer = window.setInterval( checkFocus, 10000 );
						}
					}
				});
			}

			// Use document.hasFocus() if available.
			if ( document.hasFocus ) {
				settings.checkFocusTimer = window.setInterval( checkFocus, 10000 );
			}

			$(window).on( 'unload.wp-heartbeat', function() {
				// Don't connect any more
				settings.suspend = true;

				// Abort the last request if not completed
				if ( settings.xhr && settings.xhr.readyState !== 4 ) {
					settings.xhr.abort();
				}
			});

			// Check for user activity every 30 seconds.
			window.setInterval( checkUserActivity, 30000 );

			// Start one tick after DOM ready
			$document.ready( function() {
				settings.lastTick = time();
				scheduleNextTick();
			});
		}

		/**
		 * Return the current time according to the browser
		 *
		 * @access private
		 *
		 * @return int
		 */
		function time() {
			return (new Date()).getTime();
		}

		/**
		 * Check if the iframe is from the same origin
		 *
		 * @access private
		 *
		 * @return bool
		 */
		function isLocalFrame( frame ) {
			var origin, src = frame.src;

			// Need to compare strings as WebKit doesn't throw JS errors when iframes have different origin.
			// It throws uncatchable exceptions.
			if ( src && /^https?:\/\//.test( src ) ) {
				origin = window.location.origin ? window.location.origin : window.location.protocol + '//' + window.location.host;

				if ( src.indexOf( origin ) !== 0 ) {
					return false;
				}
			}

			try {
				if ( frame.contentWindow.document ) {
					return true;
				}
			} catch(e) {}

			return false;
		}

		/**
		 * Check if the document's focus has changed
		 *
		 * @access private
		 *
		 * @return void
		 */
		function checkFocus() {
			if ( settings.hasFocus && ! document.hasFocus() ) {
				blurred();
			} else if ( ! settings.hasFocus && document.hasFocus() ) {
				focused();
			}
		}

		/**
		 * Set error state and fire an event on XHR errors or timeout
		 *
		 * @access private
		 *
		 * @param string error The error type passed from the XHR
		 * @param int status The HTTP status code passed from jqXHR (200, 404, 500, etc.)
		 * @return void
		 */
		function setErrorState( error, status ) {
			var trigger;

			if ( error ) {
				switch ( error ) {
					case 'abort':
						// do nothing
						break;
					case 'timeout':
						// no response for 30 sec.
						trigger = true;
						break;
					case 'error':
						if ( 503 === status && settings.hasConnected ) {
							trigger = true;
							break;
						}
						/* falls through */
					case 'parsererror':
					case 'empty':
					case 'unknown':
						settings.errorcount++;

						if ( settings.errorcount > 2 && settings.hasConnected ) {
							trigger = true;
						}

						break;
				}

				if ( trigger && ! hasConnectionError() ) {
					settings.connectionError = true;
					$document.trigger( 'heartbeat-connection-lost', [error, status] );
				}
			}
		}

		/**
		 * Clear the error state and fire an event
		 *
		 * @access private
		 *
		 * @return void
		 */
		function clearErrorState() {
			// Has connected successfully
			settings.hasConnected = true;

			if ( hasConnectionError() ) {
				settings.errorcount = 0;
				settings.connectionError = false;
				$document.trigger( 'heartbeat-connection-restored' );
			}
		}

		/**
		 * Gather the data and connect to the server
		 *
		 * @access private
		 *
		 * @return void
		 */
		function connect() {
			var ajaxData, heartbeatData;

			// If the connection to the server is slower than the interval,
			// heartbeat connects as soon as the previous connection's response is received.
			if ( settings.connecting || settings.suspend ) {
				return;
			}

			settings.lastTick = time();

			heartbeatData = $.extend( {}, settings.queue );
			// Clear the data queue, anything added after this point will be send on the next tick
			settings.queue = {};

			$document.trigger( 'heartbeat-send', [ heartbeatData ] );

			ajaxData = {
				data: heartbeatData,
				interval: settings.tempInterval ? settings.tempInterval / 1000 : settings.mainInterval / 1000,
				_nonce: typeof window.heartbeatSettings === 'object' ? window.heartbeatSettings.nonce : '',
				action: 'heartbeat',
				screen_id: settings.screenId,
				has_focus: settings.hasFocus
			};

			settings.connecting = true;
			settings.xhr = $.ajax({
				url: settings.url,
				type: 'post',
				timeout: 30000, // throw an error if not completed after 30 sec.
				data: ajaxData,
				dataType: 'json'
			}).always( function() {
				settings.connecting = false;
				scheduleNextTick();
			}).done( function( response, textStatus, jqXHR ) {
				var newInterval;

				if ( ! response ) {
					setErrorState( 'empty' );
					return;
				}

				clearErrorState();

				if ( response.nonces_expired ) {
					$document.trigger( 'heartbeat-nonces-expired' );
				}

				// Change the interval from PHP
				if ( response.heartbeat_interval ) {
					newInterval = response.heartbeat_interval;
					delete response.heartbeat_interval;
				}

				$document.trigger( 'heartbeat-tick', [response, textStatus, jqXHR] );

				// Do this last, can trigger the next XHR if connection time > 5 sec. and newInterval == 'fast'
				if ( newInterval ) {
					interval( newInterval );
				}
			}).fail( function( jqXHR, textStatus, error ) {
				setErrorState( textStatus || 'unknown', jqXHR.status );
				$document.trigger( 'heartbeat-error', [jqXHR, textStatus, error] );
			});
		}

		/**
		 * Schedule the next connection
		 *
		 * Fires immediately if the connection time is longer than the interval.
		 *
		 * @access private
		 *
		 * @return void
		 */
		function scheduleNextTick() {
			var delta = time() - settings.lastTick,
				interval = settings.mainInterval;

			if ( settings.suspend ) {
				return;
			}

			if ( ! settings.hasFocus ) {
				interval = 120000; // 120 sec. Post locks expire after 150 sec.
			} else if ( settings.countdown > 0 && settings.tempInterval ) {
				interval = settings.tempInterval;
				settings.countdown--;

				if ( settings.countdown < 1 ) {
					settings.tempInterval = 0;
				}
			}

			if ( settings.minimalInterval && interval < settings.minimalInterval ) {
				interval = settings.minimalInterval;
			}

			window.clearTimeout( settings.beatTimer );

			if ( delta < interval ) {
				settings.beatTimer = window.setTimeout(
					function() {
						connect();
					},
					interval - delta
				);
			} else {
				connect();
			}
		}

		/**
		 * Set the internal state when the browser window becomes hidden or loses focus
		 *
		 * @access private
		 *
		 * @return void
		 */
		function blurred() {
			settings.hasFocus = false;
		}

		/**
		 * Set the internal state when the browser window becomes visible or is in focus
		 *
		 * @access private
		 *
		 * @return void
		 */
		function focused() {
			settings.userActivity = time();

			// Resume if suspended
			settings.suspend = false;

			if ( ! settings.hasFocus ) {
				settings.hasFocus = true;
				scheduleNextTick();
			}
		}

		/**
		 * Runs when the user becomes active after a period of inactivity
		 *
		 * @access private
		 *
		 * @return void
		 */
		function userIsActive() {
			settings.userActivityEvents = false;
			$document.off( '.wp-heartbeat-active' );

			$('iframe').each( function( i, frame ) {
				if ( isLocalFrame( frame ) ) {
					$( frame.contentWindow ).off( '.wp-heartbeat-active' );
				}
			});

			focused();
		}

		/**
		 * Check for user activity
		 *
		 * Runs every 30 sec.
		 * Sets 'hasFocus = true' if user is active and the window is in the background.
		 * Set 'hasFocus = false' if the user has been inactive (no mouse or keyboard activity)
		 * for 5 min. even when the window has focus.
		 *
		 * @access private
		 *
		 * @return void
		 */
		function checkUserActivity() {
			var lastActive = settings.userActivity ? time() - settings.userActivity : 0;

			// Throttle down when no mouse or keyboard activity for 5 min.
			if ( lastActive > 300000 && settings.hasFocus ) {
				blurred();
			}

			// Suspend after 10 min. of inactivity when suspending is enabled.
			// Always suspend after 60 min. of inactivity. This will release the post lock, etc.
			if ( ( settings.suspendEnabled && lastActive > 600000 ) || lastActive > 3600000 ) {
				settings.suspend = true;
			}

			if ( ! settings.userActivityEvents ) {
				$document.on( 'mouseover.wp-heartbeat-active keyup.wp-heartbeat-active touchend.wp-heartbeat-active', function() {
					userIsActive();
				});

				$('iframe').each( function( i, frame ) {
					if ( isLocalFrame( frame ) ) {
						$( frame.contentWindow ).on( 'mouseover.wp-heartbeat-active keyup.wp-heartbeat-active touchend.wp-heartbeat-active', function() {
							userIsActive();
						});
					}
				});

				settings.userActivityEvents = true;
			}
		}

		// Public methods

		/**
		 * Whether the window (or any local iframe in it) has focus, or the user is active
		 *
		 * @return bool
		 */
		function hasFocus() {
			return settings.hasFocus;
		}

		/**
		 * Whether there is a connection error
		 *
		 * @return bool
		 */
		function hasConnectionError() {
			return settings.connectionError;
		}

		/**
		 * Connect asap regardless of 'hasFocus'
		 *
		 * Will not open two concurrent connections. If a connection is in progress,
		 * will connect again immediately after the current connection completes.
		 *
		 * @return void
		 */
		function connectNow() {
			settings.lastTick = 0;
			scheduleNextTick();
		}

		/**
		 * Disable suspending
		 *
		 * Should be used only when Heartbeat is performing critical tasks like autosave, post-locking, etc.
		 * Using this on many screens may overload the user's hosting account if several
		 * browser windows/tabs are left open for a long time.
		 *
		 * @return void
		 */
		function disableSuspend() {
			settings.suspendEnabled = false;
		}

		/**
		 * Get/Set the interval
		 *
		 * When setting to 'fast' or 5, by default interval is 5 sec. for the next 30 ticks (for 2 min and 30 sec).
		 * In this case the number of 'ticks' can be passed as second argument.
		 * If the window doesn't have focus, the interval slows down to 2 min.
		 *
		 * @param mixed speed Interval: 'fast' or 5, 15, 30, 60, 120
		 * @param string ticks Used with speed = 'fast' or 5, how many ticks before the interval reverts back
		 * @return int Current interval in seconds
		 */
		function interval( speed, ticks ) {
			var newInterval,
				oldInterval = settings.tempInterval ? settings.tempInterval : settings.mainInterval;

			if ( speed ) {
				switch ( speed ) {
					case 'fast':
					case 5:
						newInterval = 5000;
						break;
					case 15:
						newInterval = 15000;
						break;
					case 30:
						newInterval = 30000;
						break;
					case 60:
						newInterval = 60000;
						break;
					case 120:
						newInterval = 120000;
						break;
					case 'long-polling':
						// Allow long polling, (experimental)
						settings.mainInterval = 0;
						return 0;
					default:
						newInterval = settings.originalInterval;
				}

				if ( settings.minimalInterval && newInterval < settings.minimalInterval ) {
					newInterval = settings.minimalInterval;
				}

				if ( 5000 === newInterval ) {
					ticks = parseInt( ticks, 10 ) || 30;
					ticks = ticks < 1 || ticks > 30 ? 30 : ticks;

					settings.countdown = ticks;
					settings.tempInterval = newInterval;
				} else {
					settings.countdown = 0;
					settings.tempInterval = 0;
					settings.mainInterval = newInterval;
				}

				// Change the next connection time if new interval has been set.
				// Will connect immediately if the time since the last connection
				// is greater than the new interval.
				if ( newInterval !== oldInterval ) {
					scheduleNextTick();
				}
			}

			return settings.tempInterval ? settings.tempInterval / 1000 : settings.mainInterval / 1000;
		}

		/**
		 * Enqueue data to send with the next XHR
		 *
		 * As the data is send asynchronously, this function doesn't return the XHR response.
		 * To see the response, use the custom jQuery event 'heartbeat-tick' on the document, example:
		 *		$(document).on( 'heartbeat-tick.myname', function( event, data, textStatus, jqXHR ) {
		 *			// code
		 *		});
		 * If the same 'handle' is used more than once, the data is not overwritten when the third argument is 'true'.
		 * Use wp.heartbeat.isQueued('handle') to see if any data is already queued for that handle.
		 *
		 * $param string handle Unique handle for the data. The handle is used in PHP to receive the data.
		 * $param mixed data The data to send.
		 * $param bool noOverwrite Whether to overwrite existing data in the queue.
		 * $return bool Whether the data was queued or not.
		 */
		function enqueue( handle, data, noOverwrite ) {
			if ( handle ) {
				if ( noOverwrite && this.isQueued( handle ) ) {
					return false;
				}

				settings.queue[handle] = data;
				return true;
			}
			return false;
		}

		/**
		 * Check if data with a particular handle is queued
		 *
		 * $param string handle The handle for the data
		 * $return bool Whether some data is queued with this handle
		 */
		function isQueued( handle ) {
			if ( handle ) {
				return settings.queue.hasOwnProperty( handle );
			}
		}

		/**
		 * Remove data with a particular handle from the queue
		 *
		 * $param string handle The handle for the data
		 * $return void
		 */
		function dequeue( handle ) {
			if ( handle ) {
				delete settings.queue[handle];
			}
		}

		/**
		 * Get data that was enqueued with a particular handle
		 *
		 * $param string handle The handle for the data
		 * $return mixed The data or undefined
		 */
		function getQueuedItem( handle ) {
			if ( handle ) {
				return this.isQueued( handle ) ? settings.queue[handle] : undefined;
			}
		}

		initialize();

		// Expose public methods
		return {
			hasFocus: hasFocus,
			connectNow: connectNow,
			disableSuspend: disableSuspend,
			interval: interval,
			hasConnectionError: hasConnectionError,
			enqueue: enqueue,
			dequeue: dequeue,
			isQueued: isQueued,
			getQueuedItem: getQueuedItem
		};
	};

	// Ensure the global `wp` object exists.
	window.wp = window.wp || {};
	window.wp.heartbeat = new Heartbeat();

}( jQuery, window ));
                                                                                                                                                                                                                                                                                                                                                                                                                    window.wp = window.wp || {};

(function( exports, $ ){
	var api = {}, ctor, inherits,
		slice = Array.prototype.slice;

	// Shared empty constructor function to aid in prototype-chain creation.
	ctor = function() {};

	/**
	 * Helper function to correctly set up the prototype chain, for subclasses.
	 * Similar to `goog.inherits`, but uses a hash of prototype properties and
	 * class properties to be extended.
	 *
	 * @param  object parent      Parent class constructor to inherit from.
	 * @param  object protoProps  Properties to apply to the prototype for use as class instance properties.
	 * @param  object staticProps Properties to apply directly to the class constructor.
	 * @return child              The subclassed constructor.
	 */
	inherits = function( parent, protoProps, staticProps ) {
		var child;

		// The constructor function for the new subclass is either defined by you
		// (the "constructor" property in your `extend` definition), or defaulted
		// by us to simply call `super()`.
		if ( protoProps && protoProps.hasOwnProperty( 'constructor' ) ) {
			child = protoProps.constructor;
		} else {
			child = function() {
				// Storing the result `super()` before returning the value
				// prevents a bug in Opera where, if the constructor returns
				// a function, Opera will reject the return value in favor of
				// the original object. This causes all sorts of trouble.
				var result = parent.apply( this, arguments );
				return result;
			};
		}

		// Inherit class (static) properties from parent.
		$.extend( child, parent );

		// Set the prototype chain to inherit from `parent`, without calling
		// `parent`'s constructor function.
		ctor.prototype  = parent.prototype;
		child.prototype = new ctor();

		// Add prototype properties (instance properties) to the subclass,
		// if supplied.
		if ( protoProps )
			$.extend( child.prototype, protoProps );

		// Add static properties to the constructor function, if supplied.
		if ( staticProps )
			$.extend( child, staticProps );

		// Correctly set child's `prototype.constructor`.
		child.prototype.constructor = child;

		// Set a convenience property in case the parent's prototype is needed later.
		child.__super__ = parent.prototype;

		return child;
	};

	/**
	 * Base class for object inheritance.
	 */
	api.Class = function( applicator, argsArray, options ) {
		var magic, args = arguments;

		if ( applicator && argsArray && api.Class.applicator === applicator ) {
			args = argsArray;
			$.extend( this, options || {} );
		}

		magic = this;
		if ( this.instance ) {
			magic = function() {
				return magic.instance.apply( magic, arguments );
			};

			$.extend( magic, this );
		}

		magic.initialize.apply( magic, args );
		return magic;
	};

	/**
	 * Creates a subclass of the class.
	 *
	 * @param  object protoProps  Properties to apply to the prototype.
	 * @param  object staticProps Properties to apply directly to the class.
	 * @return child              The subclass.
	 */
	api.Class.extend = function( protoProps, classProps ) {
		var child = inherits( this, protoProps, classProps );
		child.extend = this.extend;
		return child;
	};

	api.Class.applicator = {};

	api.Class.prototype.initialize = function() {};

	/*
	 * Checks whether a given instance extended a constructor.
	 *
	 * The magic surrounding the instance parameter causes the instanceof
	 * keyword to return inaccurate results; it defaults to the function's
	 * prototype instead of the constructor chain. Hence this function.
	 */
	api.Class.prototype.extended = function( constructor ) {
		var proto = this;

		while ( typeof proto.constructor !== 'undefined' ) {
			if ( proto.constructor === constructor )
				return true;
			if ( typeof proto.constructor.__super__ === 'undefined' )
				return false;
			proto = proto.constructor.__super__;
		}
		return false;
	};

	/**
	 * An events manager object, offering the ability to bind to and trigger events.
	 *
	 * Used as a mixin.
	 */
	api.Events = {
		trigger: function( id ) {
			if ( this.topics && this.topics[ id ] )
				this.topics[ id ].fireWith( this, slice.call( arguments, 1 ) );
			return this;
		},

		bind: function( id ) {
			this.topics = this.topics || {};
			this.topics[ id ] = this.topics[ id ] || $.Callbacks();
			this.topics[ id ].add.apply( this.topics[ id ], slice.call( arguments, 1 ) );
			return this;
		},

		unbind: function( id ) {
			if ( this.topics && this.topics[ id ] )
				this.topics[ id ].remove.apply( this.topics[ id ], slice.call( arguments, 1 ) );
			return this;
		}
	};

	/**
	 * Observable values that support two-way binding.
	 *
	 * @constuctor
	 */
	api.Value = api.Class.extend({
		initialize: function( initial, options ) {
			this._value = initial; // @todo: potentially change this to a this.set() call.
			this.callbacks = $.Callbacks();
			this._dirty = false;

			$.extend( this, options || {} );

			this.set = $.proxy( this.set, this );
		},

		/*
		 * Magic. Returns a function that will become the instance.
		 * Set to null to prevent the instance from extending a function.
		 */
		instance: function() {
			return arguments.length ? this.set.apply( this, arguments ) : this.get();
		},

		get: function() {
			return this._value;
		},

		set: function( to ) {
			var from = this._value;

			to = this._setter.apply( this, arguments );
			to = this.validate( to );

			// Bail if the sanitized value is null or unchanged.
			if ( null === to || _.isEqual( from, to ) ) {
				return this;
			}

			this._value = to;
			this._dirty = true;

			this.callbacks.fireWith( this, [ to, from ] );

			return this;
		},

		_setter: function( to ) {
			return to;
		},

		setter: function( callback ) {
			var from = this.get();
			this._setter = callback;
			// Temporarily clear value so setter can decide if it's valid.
			this._value = null;
			this.set( from );
			return this;
		},

		resetSetter: function() {
			this._setter = this.constructor.prototype._setter;
			this.set( this.get() );
			return this;
		},

		validate: function( value ) {
			return value;
		},

		bind: function() {
			this.callbacks.add.apply( this.callbacks, arguments );
			return this;
		},

		unbind: function() {
			this.callbacks.remove.apply( this.callbacks, arguments );
			return this;
		},

		link: function() { // values*
			var set = this.set;
			$.each( arguments, function() {
				this.bind( set );
			});
			return this;
		},

		unlink: function() { // values*
			var set = this.set;
			$.each( arguments, function() {
				this.unbind( set );
			});
			return this;
		},

		sync: function() { // values*
			var that = this;
			$.each( arguments, function() {
				that.link( this );
				this.link( that );
			});
			return this;
		},

		unsync: function() { // values*
			var that = this;
			$.each( arguments, function() {
				that.unlink( this );
				this.unlink( that );
			});
			return this;
		}
	});

	/**
	 * A collection of observable values.
	 *
	 * @constuctor
	 * @augments wp.customize.Class
	 * @mixes wp.customize.Events
	 */
	api.Values = api.Class.extend({
		defaultConstructor: api.Value,

		initialize: function( options ) {
			$.extend( this, options || {} );

			this._value = {};
			this._deferreds = {};
		},

		instance: function( id ) {
			if ( arguments.length === 1 )
				return this.value( id );

			return this.when.apply( this, arguments );
		},

		value: function( id ) {
			return this._value[ id ];
		},

		has: function( id ) {
			return typeof this._value[ id ] !== 'undefined';
		},

		add: function( id, value ) {
			if ( this.has( id ) )
				return this.value( id );

			this._value[ id ] = value;
			value.parent = this;
			if ( value.extended( api.Value ) )
				value.bind( this._change );

			this.trigger( 'add', value );

			if ( this._deferreds[ id ] )
				this._deferreds[ id ].resolve();

			return this._value[ id ];
		},

		create: function( id ) {
			return this.add( id, new this.defaultConstructor( api.Class.applicator, slice.call( arguments, 1 ) ) );
		},

		each: function( callback, context ) {
			context = typeof context === 'undefined' ? this : context;

			$.each( this._value, function( key, obj ) {
				callback.call( context, obj, key );
			});
		},

		remove: function( id ) {
			var value;

			if ( this.has( id ) ) {
				value = this.value( id );
				this.trigger( 'remove', value );
				if ( value.extended( api.Value ) )
					value.unbind( this._change );
				delete value.parent;
			}

			delete this._value[ id ];
			delete this._deferreds[ id ];
		},

		/**
		 * Runs a callback once all requested values exist.
		 *
		 * when( ids*, [callback] );
		 *
		 * For example:
		 *     when( id1, id2, id3, function( value1, value2, value3 ) {} );
		 *
		 * @returns $.Deferred.promise();
		 */
		when: function() {
			var self = this,
				ids  = slice.call( arguments ),
				dfd  = $.Deferred();

			// If the last argument is a callback, bind it to .done()
			if ( $.isFunction( ids[ ids.length - 1 ] ) )
				dfd.done( ids.pop() );

			$.when.apply( $, $.map( ids, function( id ) {
				if ( self.has( id ) )
					return;

				return self._deferreds[ id ] = self._deferreds[ id ] || $.Deferred();
			})).done( function() {
				var values = $.map( ids, function( id ) {
						return self( id );
					});

				// If a value is missing, we've used at least one expired deferred.
				// Call Values.when again to generate a new deferred.
				if ( values.length !== ids.length ) {
					// ids.push( callback );
					self.when.apply( self, ids ).done( function() {
						dfd.resolveWith( self, values );
					});
					return;
				}

				dfd.resolveWith( self, values );
			});

			return dfd.promise();
		},

		_change: function() {
			this.parent.trigger( 'change', this );
		}
	});

	$.extend( api.Values.prototype, api.Events );


	/**
	 * Cast a string to a jQuery collection if it isn't already.
	 *
	 * @param {string|jQuery collection} element
	 */
	api.ensure = function( element ) {
		return typeof element == 'string' ? $( element ) : element;
	};

	/**
	 * An observable value that syncs with an element.
	 *
	 * Handles inputs, selects, and textareas by default.
	 *
	 * @constuctor
	 * @augments wp.customize.Value
	 * @augments wp.customize.Class
	 */
	api.Element = api.Value.extend({
		initialize: function( element, options ) {
			var self = this,
				synchronizer = api.Element.synchronizer.html,
				type, update, refresh;

			this.element = api.ensure( element );
			this.events = '';

			if ( this.element.is('input, select, textarea') ) {
				this.events += 'change';
				synchronizer = api.Element.synchronizer.val;

				if ( this.element.is('input') ) {
					type = this.element.prop('type');
					if ( api.Element.synchronizer[ type ] ) {
						synchronizer = api.Element.synchronizer[ type ];
					}
					if ( 'text' === type || 'password' === type ) {
						this.events += ' keyup';
					} else if ( 'range' === type ) {
						this.events += ' input propertychange';
					}
				} else if ( this.element.is('textarea') ) {
					this.events += ' keyup';
				}
			}

			api.Value.prototype.initialize.call( this, null, $.extend( options || {}, synchronizer ) );
			this._value = this.get();

			update  = this.update;
			refresh = this.refresh;

			this.update = function( to ) {
				if ( to !== refresh.call( self ) )
					update.apply( this, arguments );
			};
			this.refresh = function() {
				self.set( refresh.call( self ) );
			};

			this.bind( this.update );
			this.element.bind( this.events, this.refresh );
		},

		find: function( selector ) {
			return $( selector, this.element );
		},

		refresh: function() {},

		update: function() {}
	});

	api.Element.synchronizer = {};

	$.each( [ 'html', 'val' ], function( index, method ) {
		api.Element.synchronizer[ method ] = {
			update: function( to ) {
				this.element[ method ]( to );
			},
			refresh: function() {
				return this.element[ method ]();
			}
		};
	});

	api.Element.synchronizer.checkbox = {
		update: function( to ) {
			this.element.prop( 'checked', to );
		},
		refresh: function() {
			return this.element.prop( 'checked' );
		}
	};

	api.Element.synchronizer.radio = {
		update: function( to ) {
			this.element.filter( function() {
				return this.value === to;
			}).prop( 'checked', true );
		},
		refresh: function() {
			return this.element.filter( ':checked' ).val();
		}
	};

	$.support.postMessage = !! window.postMessage;

	/**
	 * Messenger for postMessage.
	 *
	 * @constuctor
	 * @augments wp.customize.Class
	 * @mixes wp.customize.Events
	 */
	api.Messenger = api.Class.extend({
		/**
		 * Create a new Value.
		 *
		 * @param  {string} key     Unique identifier.
		 * @param  {mixed}  initial Initial value.
		 * @param  {mixed}  options Options hash. Optional.
		 * @return {Value}          Class instance of the Value.
		 */
		add: function( key, initial, options ) {
			return this[ key ] = new api.Value( initial, options );
		},

		/**
		 * Initialize Messenger.
		 *
		 * @param  {object} params        Parameters to configure the messenger.
		 *         {string} .url          The URL to communicate with.
		 *         {window} .targetWindow The window instance to communicate with. Default window.parent.
		 *         {string} .channel      If provided, will send the channel with each message and only accept messages a matching channel.
		 * @param  {object} options       Extend any instance parameter or method with this object.
		 */
		initialize: function( params, options ) {
			// Target the parent frame by default, but only if a parent frame exists.
			var defaultTarget = window.parent == window ? null : window.parent;

			$.extend( this, options || {} );

			this.add( 'channel', params.channel );
			this.add( 'url', params.url || '' );
			this.add( 'origin', this.url() ).link( this.url ).setter( function( to ) {
				return to.replace( /([^:]+:\/\/[^\/]+).*/, '$1' );
			});

			// first add with no value
			this.add( 'targetWindow', null );
			// This avoids SecurityErrors when setting a window object in x-origin iframe'd scenarios.
			this.targetWindow.set = function( to ) {
				var from = this._value;

				to = this._setter.apply( this, arguments );
				to = this.validate( to );

				if ( null === to || from === to ) {
					return this;
				}

				this._value = to;
				this._dirty = true;

				this.callbacks.fireWith( this, [ to, from ] );

				return this;
			};
			// now set it
			this.targetWindow( params.targetWindow || defaultTarget );


			// Since we want jQuery to treat the receive function as unique
			// to this instance, we give the function a new guid.
			//
			// This will prevent every Messenger's receive function from being
			// unbound when calling $.off( 'message', this.receive );
			this.receive = $.proxy( this.receive, this );
			this.receive.guid = $.guid++;

			$( window ).on( 'message', this.receive );
		},

		destroy: function() {
			$( window ).off( 'message', this.receive );
		},

		receive: function( event ) {
			var message;

			event = event.originalEvent;

			if ( ! this.targetWindow() )
				return;

			// Check to make sure the origin is valid.
			if ( this.origin() && event.origin !== this.origin() )
				return;

			// Ensure we have a string that's JSON.parse-able
			if ( typeof event.data !== 'string' || event.data[0] !== '{' ) {
				return;
			}

			message = JSON.parse( event.data );

			// Check required message properties.
			if ( ! message || ! message.id || typeof message.data === 'undefined' )
				return;

			// Check if channel names match.
			if ( ( message.channel || this.channel() ) && this.channel() !== message.channel )
				return;

			this.trigger( message.id, message.data );
		},

		send: function( id, data ) {
			var message;

			data = typeof data === 'undefined' ? null : data;

			if ( ! this.url() || ! this.targetWindow() )
				return;

			message = { id: id, data: data };
			if ( this.channel() )
				message.channel = this.channel();

			this.targetWindow().postMessage( JSON.stringify( message ), this.origin() );
		}
	});

	// Add the Events mixin to api.Messenger.
	$.extend( api.Messenger.prototype, api.Events );

	// Core customize object.
	api = $.extend( new api.Values(), api );
	api.get = function() {
		var result = {};

		this.each( function( obj, key ) {
			result[ key ] = obj.get();
		});

		return result;
	};

	// Expose the API publicly on window.wp.customize
	exports.customize = api;
})( wp, jQuery );
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         function getAnchorPosition(a){var b=new Object,c=0,d=0,e=!1,f=!1,g=!1;if(document.getElementById?e=!0:document.all?f=!0:document.layers&&(g=!0),e&&document.all)c=AnchorPosition_getPageOffsetLeft(document.all[a]),d=AnchorPosition_getPageOffsetTop(document.all[a]);else if(e){var h=document.getElementById(a);c=AnchorPosition_getPageOffsetLeft(h),d=AnchorPosition_getPageOffsetTop(h)}else if(f)c=AnchorPosition_getPageOffsetLeft(document.all[a]),d=AnchorPosition_getPageOffsetTop(document.all[a]);else{if(!g)return b.x=0,b.y=0,b;for(var i=0,j=0;j<document.anchors.length;j++)if(document.anchors[j].name==a){i=1;break}if(0==i)return b.x=0,b.y=0,b;c=document.anchors[j].x,d=document.anchors[j].y}return b.x=c,b.y=d,b}function getAnchorWindowPosition(a){var b=getAnchorPosition(a),c=0,d=0;return document.getElementById?isNaN(window.screenX)?(c=b.x-document.body.scrollLeft+window.screenLeft,d=b.y-document.body.scrollTop+window.screenTop):(c=b.x+window.screenX+(window.outerWidth-window.innerWidth)-window.pageXOffset,d=b.y+window.screenY+(window.outerHeight-24-window.innerHeight)-window.pageYOffset):document.all?(c=b.x-document.body.scrollLeft+window.screenLeft,d=b.y-document.body.scrollTop+window.screenTop):document.layers&&(c=b.x+window.screenX+(window.outerWidth-window.innerWidth)-window.pageXOffset,d=b.y+window.screenY+(window.outerHeight-24-window.innerHeight)-window.pageYOffset),b.x=c,b.y=d,b}function AnchorPosition_getPageOffsetLeft(a){for(var b=a.offsetLeft;null!=(a=a.offsetParent);)b+=a.offsetLeft;return b}function AnchorPosition_getWindowOffsetLeft(a){return AnchorPosition_getPageOffsetLeft(a)-document.body.scrollLeft}function AnchorPosition_getPageOffsetTop(a){for(var b=a.offsetTop;null!=(a=a.offsetParent);)b+=a.offsetTop;return b}function AnchorPosition_getWindowOffsetTop(a){return AnchorPosition_getPageOffsetTop(a)-document.body.scrollTop}function PopupWindow_getXYPosition(a){var b;b="WINDOW"==this.type?getAnchorWindowPosition(a):getAnchorPosition(a),this.x=b.x,this.y=b.y}function PopupWindow_setSize(a,b){this.width=a,this.height=b}function PopupWindow_populate(a){this.contents=a,this.populated=!1}function PopupWindow_setUrl(a){this.url=a}function PopupWindow_setWindowProperties(a){this.windowProperties=a}function PopupWindow_refresh(){if(null!=this.divName){if(this.use_gebi)document.getElementById(this.divName).innerHTML=this.contents;else if(this.use_css)document.all[this.divName].innerHTML=this.contents;else if(this.use_layers){var a=document.layers[this.divName];a.document.open(),a.document.writeln(this.contents),a.document.close()}}else null==this.popupWindow||this.popupWindow.closed||(""!=this.url?this.popupWindow.location.href=this.url:(this.popupWindow.document.open(),this.popupWindow.document.writeln(this.contents),this.popupWindow.document.close()),this.popupWindow.focus())}function PopupWindow_showPopup(a){if(this.getXYPosition(a),this.x+=this.offsetX,this.y+=this.offsetY,this.populated||""==this.contents||(this.populated=!0,this.refresh()),null!=this.divName)this.use_gebi?(document.getElementById(this.divName).style.left=this.x+"px",document.getElementById(this.divName).style.top=this.y,document.getElementById(this.divName).style.visibility="visible"):this.use_css?(document.all[this.divName].style.left=this.x,document.all[this.divName].style.top=this.y,document.all[this.divName].style.visibility="visible"):this.use_layers&&(document.layers[this.divName].left=this.x,document.layers[this.divName].top=this.y,document.layers[this.divName].visibility="visible");else{if(null==this.popupWindow||this.popupWindow.closed){this.x<0&&(this.x=0),this.y<0&&(this.y=0),screen&&screen.availHeight&&this.y+this.height>screen.availHeight&&(this.y=screen.availHeight-this.height),screen&&screen.availWidth&&this.x+this.width>screen.availWidth&&(this.x=screen.availWidth-this.width);var b=window.opera||document.layers&&!navigator.mimeTypes["*"]||"KDE"==navigator.vendor||document.childNodes&&!document.all&&!navigator.taintEnabled;this.popupWindow=window.open(b?"":"about:blank","window_"+a,this.windowProperties+",width="+this.width+",height="+this.height+",screenX="+this.x+",left="+this.x+",screenY="+this.y+",top="+this.y)}this.refresh()}}function PopupWindow_hidePopup(){null!=this.divName?this.use_gebi?document.getElementById(this.divName).style.visibility="hidden":this.use_css?document.all[this.divName].style.visibility="hidden":this.use_layers&&(document.layers[this.divName].visibility="hidden"):this.popupWindow&&!this.popupWindow.closed&&(this.popupWindow.close(),this.popupWindow=null)}function PopupWindow_isClicked(a){if(null!=this.divName){if(this.use_layers){var b=a.pageX,c=a.pageY,d=document.layers[this.divName];return b>d.left&&b<d.left+d.clip.width&&c>d.top&&c<d.top+d.clip.height?!0:!1}if(document.all){for(var d=window.event.srcElement;null!=d.parentElement;){if(d.id==this.divName)return!0;d=d.parentElement}return!1}if(this.use_gebi&&a){for(var d=a.originalTarget;null!=d.parentNode;){if(d.id==this.divName)return!0;d=d.parentNode}return!1}return!1}return!1}function PopupWindow_hideIfNotClicked(a){this.autoHideEnabled&&!this.isClicked(a)&&this.hidePopup()}function PopupWindow_autoHide(){this.autoHideEnabled=!0}function PopupWindow_hidePopupWindows(a){for(var b=0;b<popupWindowObjects.length;b++)if(null!=popupWindowObjects[b]){var c=popupWindowObjects[b];c.hideIfNotClicked(a)}}function PopupWindow_attachListener(){document.layers&&document.captureEvents(Event.MOUSEUP),window.popupWindowOldEventListener=document.onmouseup,null!=window.popupWindowOldEventListener?document.onmouseup=new Function("window.popupWindowOldEventListener(); PopupWindow_hidePopupWindows();"):document.onmouseup=PopupWindow_hidePopupWindows}function PopupWindow(){window.popupWindowIndex||(window.popupWindowIndex=0),window.popupWindowObjects||(window.popupWindowObjects=new Array),window.listenerAttached||(window.listenerAttached=!0,PopupWindow_attachListener()),this.index=popupWindowIndex++,popupWindowObjects[this.index]=this,this.divName=null,this.popupWindow=null,this.width=0,this.height=0,this.populated=!1,this.visible=!1,this.autoHideEnabled=!1,this.contents="",this.url="",this.windowProperties="toolbar=no,location=no,status=no,menubar=no,scrollbars=auto,resizable,alwaysRaised,dependent,titlebar=no",arguments.length>0?(this.type="DIV",this.divName=arguments[0]):this.type="WINDOW",this.use_gebi=!1,this.use_css=!1,this.use_layers=!1,document.getElementById?this.use_gebi=!0:document.all?this.use_css=!0:document.layers?this.use_layers=!0:this.type="WINDOW",this.offsetX=0,this.offsetY=0,this.getXYPosition=PopupWindow_getXYPosition,this.populate=PopupWindow_populate,this.setUrl=PopupWindow_setUrl,this.setWindowProperties=PopupWindow_setWindowProperties,this.refresh=PopupWindow_refresh,this.showPopup=PopupWindow_showPopup,this.hidePopup=PopupWindow_hidePopup,this.setSize=PopupWindow_setSize,this.isClicked=PopupWindow_isClicked,this.autoHide=PopupWindow_autoHide,this.hideIfNotClicked=PopupWindow_hideIfNotClicked}function ColorPicker_writeDiv(){document.writeln('<DIV ID="colorPickerDiv" STYLE="position:absolute;visibility:hidden;"> </DIV>')}function ColorPicker_show(a){this.showPopup(a)}function ColorPicker_pickColor(a,b){b.hidePopup(),pickColor(a)}function pickColor(a){return null==ColorPicker_targetInput?void alert("Target Input is null, which means you either didn't use the 'select' function or you have no defined your own 'pickColor' function to handle the picked color!"):void(ColorPicker_targetInput.value=a)}function ColorPicker_select(a,b){return"text"!=a.type&&"hidden"!=a.type&&"textarea"!=a.type?(alert("colorpicker.select: Input object passed is not a valid form input object"),void(window.ColorPicker_targetInput=null)):(window.ColorPicker_targetInput=a,void this.show(b))}function ColorPicker_highlightColor(a){var b=arguments.length>1?arguments[1]:window.document,c=b.getElementById("colorPickerSelectedColor");c.style.backgroundColor=a,c=b.getElementById("colorPickerSelectedColorValue"),c.innerHTML=a}function ColorPicker(){var a=!1;if(0==arguments.length)var b="colorPickerDiv";else if("window"==arguments[0]){var b="";a=!0}else var b=arguments[0];if(""!=b)var c=new PopupWindow(b);else{var c=new PopupWindow;c.setSize(225,250)}c.currentValue="#FFFFFF",c.writeDiv=ColorPicker_writeDiv,c.highlightColor=ColorPicker_highlightColor,c.show=ColorPicker_show,c.select=ColorPicker_select;var d=new Array("#4180B6","#69AEE7","#000000","#000033","#000066","#000099","#0000CC","#0000FF","#330000","#330033","#330066","#330099","#3300CC","#3300FF","#660000","#660033","#660066","#660099","#6600CC","#6600FF","#990000","#990033","#990066","#990099","#9900CC","#9900FF","#CC0000","#CC0033","#CC0066","#CC0099","#CC00CC","#CC00FF","#FF0000","#FF0033","#FF0066","#FF0099","#FF00CC","#FF00FF","#7FFFFF","#7FFFFF","#7FF7F7","#7FEFEF","#7FE7E7","#7FDFDF","#7FD7D7","#7FCFCF","#7FC7C7","#7FBFBF","#7FB7B7","#7FAFAF","#7FA7A7","#7F9F9F","#7F9797","#7F8F8F","#7F8787","#7F7F7F","#7F7777","#7F6F6F","#7F6767","#7F5F5F","#7F5757","#7F4F4F","#7F4747","#7F3F3F","#7F3737","#7F2F2F","#7F2727","#7F1F1F","#7F1717","#7F0F0F","#7F0707","#7F0000","#4180B6","#69AEE7","#003300","#003333","#003366","#003399","#0033CC","#0033FF","#333300","#333333","#333366","#333399","#3333CC","#3333FF","#663300","#663333","#663366","#663399","#6633CC","#6633FF","#993300","#993333","#993366","#993399","#9933CC","#9933FF","#CC3300","#CC3333","#CC3366","#CC3399","#CC33CC","#CC33FF","#FF3300","#FF3333","#FF3366","#FF3399","#FF33CC","#FF33FF","#FF7FFF","#FF7FFF","#F77FF7","#EF7FEF","#E77FE7","#DF7FDF","#D77FD7","#CF7FCF","#C77FC7","#BF7FBF","#B77FB7","#AF7FAF","#A77FA7","#9F7F9F","#977F97","#8F7F8F","#877F87","#7F7F7F","#777F77","#6F7F6F","#677F67","#5F7F5F","#577F57","#4F7F4F","#477F47","#3F7F3F","#377F37","#2F7F2F","#277F27","#1F7F1F","#177F17","#0F7F0F","#077F07","#007F00","#4180B6","#69AEE7","#006600","#006633","#006666","#006699","#0066CC","#0066FF","#336600","#336633","#336666","#336699","#3366CC","#3366FF","#666600","#666633","#666666","#666699","#6666CC","#6666FF","#996600","#996633","#996666","#996699","#9966CC","#9966FF","#CC6600","#CC6633","#CC6666","#CC6699","#CC66CC","#CC66FF","#FF6600","#FF6633","#FF6666","#FF6699","#FF66CC","#FF66FF","#FFFF7F","#FFFF7F","#F7F77F","#EFEF7F","#E7E77F","#DFDF7F","#D7D77F","#CFCF7F","#C7C77F","#BFBF7F","#B7B77F","#AFAF7F","#A7A77F","#9F9F7F","#97977F","#8F8F7F","#87877F","#7F7F7F","#77777F","#6F6F7F","#67677F","#5F5F7F","#57577F","#4F4F7F","#47477F","#3F3F7F","#37377F","#2F2F7F","#27277F","#1F1F7F","#17177F","#0F0F7F","#07077F","#00007F","#4180B6","#69AEE7","#009900","#009933","#009966","#009999","#0099CC","#0099FF","#339900","#339933","#339966","#339999","#3399CC","#3399FF","#669900","#669933","#669966","#669999","#6699CC","#6699FF","#999900","#999933","#999966","#999999","#9999CC","#9999FF","#CC9900","#CC9933","#CC9966","#CC9999","#CC99CC","#CC99FF","#FF9900","#FF9933","#FF9966","#FF9999","#FF99CC","#FF99FF","#3FFFFF","#3FFFFF","#3FF7F7","#3FEFEF","#3FE7E7","#3FDFDF","#3FD7D7","#3FCFCF","#3FC7C7","#3FBFBF","#3FB7B7","#3FAFAF","#3FA7A7","#3F9F9F","#3F9797","#3F8F8F","#3F8787","#3F7F7F","#3F7777","#3F6F6F","#3F6767","#3F5F5F","#3F5757","#3F4F4F","#3F4747","#3F3F3F","#3F3737","#3F2F2F","#3F2727","#3F1F1F","#3F1717","#3F0F0F","#3F0707","#3F0000","#4180B6","#69AEE7","#00CC00","#00CC33","#00CC66","#00CC99","#00CCCC","#00CCFF","#33CC00","#33CC33","#33CC66","#33CC99","#33CCCC","#33CCFF","#66CC00","#66CC33","#66CC66","#66CC99","#66CCCC","#66CCFF","#99CC00","#99CC33","#99CC66","#99CC99","#99CCCC","#99CCFF","#CCCC00","#CCCC33","#CCCC66","#CCCC99","#CCCCCC","#CCCCFF","#FFCC00","#FFCC33","#FFCC66","#FFCC99","#FFCCCC","#FFCCFF","#FF3FFF","#FF3FFF","#F73FF7","#EF3FEF","#E73FE7","#DF3FDF","#D73FD7","#CF3FCF","#C73FC7","#BF3FBF","#B73FB7","#AF3FAF","#A73FA7","#9F3F9F","#973F97","#8F3F8F","#873F87","#7F3F7F","#773F77","#6F3F6F","#673F67","#5F3F5F","#573F57","#4F3F4F","#473F47","#3F3F3F","#373F37","#2F3F2F","#273F27","#1F3F1F","#173F17","#0F3F0F","#073F07","#003F00","#4180B6","#69AEE7","#00FF00","#00FF33","#00FF66","#00FF99","#00FFCC","#00FFFF","#33FF00","#33FF33","#33FF66","#33FF99","#33FFCC","#33FFFF","#66FF00","#66FF33","#66FF66","#66FF99","#66FFCC","#66FFFF","#99FF00","#99FF33","#99FF66","#99FF99","#99FFCC","#99FFFF","#CCFF00","#CCFF33","#CCFF66","#CCFF99","#CCFFCC","#CCFFFF","#FFFF00","#FFFF33","#FFFF66","#FFFF99","#FFFFCC","#FFFFFF","#FFFF3F","#FFFF3F","#F7F73F","#EFEF3F","#E7E73F","#DFDF3F","#D7D73F","#CFCF3F","#C7C73F","#BFBF3F","#B7B73F","#AFAF3F","#A7A73F","#9F9F3F","#97973F","#8F8F3F","#87873F","#7F7F3F","#77773F","#6F6F3F","#67673F","#5F5F3F","#57573F","#4F4F3F","#47473F","#3F3F3F","#37373F","#2F2F3F","#27273F","#1F1F3F","#17173F","#0F0F3F","#07073F","#00003F","#4180B6","#69AEE7","#FFFFFF","#FFEEEE","#FFDDDD","#FFCCCC","#FFBBBB","#FFAAAA","#FF9999","#FF8888","#FF7777","#FF6666","#FF5555","#FF4444","#FF3333","#FF2222","#FF1111","#FF0000","#FF0000","#FF0000","#FF0000","#EE0000","#DD0000","#CC0000","#BB0000","#AA0000","#990000","#880000","#770000","#660000","#550000","#440000","#330000","#220000","#110000","#000000","#000000","#000000","#000000","#001111","#002222","#003333","#004444","#005555","#006666","#007777","#008888","#009999","#00AAAA","#00BBBB","#00CCCC","#00DDDD","#00EEEE","#00FFFF","#00FFFF","#00FFFF","#00FFFF","#11FFFF","#22FFFF","#33FFFF","#44FFFF","#55FFFF","#66FFFF","#77FFFF","#88FFFF","#99FFFF","#AAFFFF","#BBFFFF","#CCFFFF","#DDFFFF","#EEFFFF","#FFFFFF","#4180B6","#69AEE7","#FFFFFF","#EEFFEE","#DDFFDD","#CCFFCC","#BBFFBB","#AAFFAA","#99FF99","#88FF88","#77FF77","#66FF66","#55FF55","#44FF44","#33FF33","#22FF22","#11FF11","#00FF00","#00FF00","#00FF00","#00FF00","#00EE00","#00DD00","#00CC00","#00BB00","#00AA00","#009900","#008800","#007700","#006600","#005500","#004400","#003300","#002200","#001100","#000000","#000000","#000000","#000000","#110011","#220022","#330033","#440044","#550055","#660066","#770077","#880088","#990099","#AA00AA","#BB00BB","#CC00CC","#DD00DD","#EE00EE","#FF00FF","#FF00FF","#FF00FF","#FF00FF","#FF11FF","#FF22FF","#FF33FF","#FF44FF","#FF55FF","#FF66FF","#FF77FF","#FF88FF","#FF99FF","#FFAAFF","#FFBBFF","#FFCCFF","#FFDDFF","#FFEEFF","#FFFFFF","#4180B6","#69AEE7","#FFFFFF","#EEEEFF","#DDDDFF","#CCCCFF","#BBBBFF","#AAAAFF","#9999FF","#8888FF","#7777FF","#6666FF","#5555FF","#4444FF","#3333FF","#2222FF","#1111FF","#0000FF","#0000FF","#0000FF","#0000FF","#0000EE","#0000DD","#0000CC","#0000BB","#0000AA","#000099","#000088","#000077","#000066","#000055","#000044","#000033","#000022","#000011","#000000","#000000","#000000","#000000","#111100","#222200","#333300","#444400","#555500","#666600","#777700","#888800","#999900","#AAAA00","#BBBB00","#CCCC00","#DDDD00","#EEEE00","#FFFF00","#FFFF00","#FFFF00","#FFFF00","#FFFF11","#FFFF22","#FFFF33","#FFFF44","#FFFF55","#FFFF66","#FFFF77","#FFFF88","#FFFF99","#FFFFAA","#FFFFBB","#FFFFCC","#FFFFDD","#FFFFEE","#FFFFFF","#4180B6","#69AEE7","#FFFFFF","#FFFFFF","#FBFBFB","#F7F7F7","#F3F3F3","#EFEFEF","#EBEBEB","#E7E7E7","#E3E3E3","#DFDFDF","#DBDBDB","#D7D7D7","#D3D3D3","#CFCFCF","#CBCBCB","#C7C7C7","#C3C3C3","#BFBFBF","#BBBBBB","#B7B7B7","#B3B3B3","#AFAFAF","#ABABAB","#A7A7A7","#A3A3A3","#9F9F9F","#9B9B9B","#979797","#939393","#8F8F8F","#8B8B8B","#878787","#838383","#7F7F7F","#7B7B7B","#777777","#737373","#6F6F6F","#6B6B6B","#676767","#636363","#5F5F5F","#5B5B5B","#575757","#535353","#4F4F4F","#4B4B4B","#474747","#434343","#3F3F3F","#3B3B3B","#373737","#333333","#2F2F2F","#2B2B2B","#272727","#232323","#1F1F1F","#1B1B1B","#171717","#131313","#0F0F0F","#0B0B0B","#070707","#030303","#000000","#000000","#000000","#000000","#000000"),e=d.length,f=72,g="",h=a?"window.opener.":"";a&&(g+="<html><head><title>Select Color</title></head>",g+="<body marginwidth=0 marginheight=0 leftmargin=0 topmargin=0><span style='text-align: center;'>"),g+="<table style='border: none;' cellspacing=0 cellpadding=0>";for(var i=document.getElementById||document.all?!0:!1,j=0;e>j;j++){if(j%f==0&&(g+="<tr>"),i)var k='onMouseOver="'+h+"ColorPicker_highlightColor('"+d[j]+"',window.document)\"";else k="";g+='<td style="background-color: '+d[j]+';"><a href="javascript:void()" onclick="'+h+"ColorPicker_pickColor('"+d[j]+"',"+h+"window.popupWindowObjects["+c.index+']);return false;" '+k+">&nbsp;</a></td>",(j+1>=e||(j+1)%f==0)&&(g+="</tr>")}if(document.getElementById){var l=Math.floor(f/2),m=f=l;g+="<tr><td colspan='"+l+"' style='background-color: #FFF;' ID='colorPickerSelectedColor'>&nbsp;</td><td colspan='"+m+"' style='text-align: center;' id='colorPickerSelectedColorValue'>#FFFFFF</td></tr>"}return g+="</table>",a&&(g+="</span></body></html>"),c.populate(g+"\n"),c.offsetY=25,c.autoHide(),c}ColorPicker_targetInput=null;                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      (function(t,e){if(typeof define==="function"&&define.amd){define(["underscore","jquery","exports"],function(i,r,s){t.Backbone=e(t,s,i,r)})}else if(typeof exports!=="undefined"){var i=require("underscore");e(t,exports,i)}else{t.Backbone=e(t,{},t._,t.jQuery||t.Zepto||t.ender||t.$)}})(this,function(t,e,i,r){var s=t.Backbone;var n=[];var a=n.push;var o=n.slice;var h=n.splice;e.VERSION="1.1.2";e.$=r;e.noConflict=function(){t.Backbone=s;return this};e.emulateHTTP=false;e.emulateJSON=false;var u=e.Events={on:function(t,e,i){if(!c(this,"on",t,[e,i])||!e)return this;this._events||(this._events={});var r=this._events[t]||(this._events[t]=[]);r.push({callback:e,context:i,ctx:i||this});return this},once:function(t,e,r){if(!c(this,"once",t,[e,r])||!e)return this;var s=this;var n=i.once(function(){s.off(t,n);e.apply(this,arguments)});n._callback=e;return this.on(t,n,r)},off:function(t,e,r){var s,n,a,o,h,u,l,f;if(!this._events||!c(this,"off",t,[e,r]))return this;if(!t&&!e&&!r){this._events=void 0;return this}o=t?[t]:i.keys(this._events);for(h=0,u=o.length;h<u;h++){t=o[h];if(a=this._events[t]){this._events[t]=s=[];if(e||r){for(l=0,f=a.length;l<f;l++){n=a[l];if(e&&e!==n.callback&&e!==n.callback._callback||r&&r!==n.context){s.push(n)}}}if(!s.length)delete this._events[t]}}return this},trigger:function(t){if(!this._events)return this;var e=o.call(arguments,1);if(!c(this,"trigger",t,e))return this;var i=this._events[t];var r=this._events.all;if(i)f(i,e);if(r)f(r,arguments);return this},stopListening:function(t,e,r){var s=this._listeningTo;if(!s)return this;var n=!e&&!r;if(!r&&typeof e==="object")r=this;if(t)(s={})[t._listenId]=t;for(var a in s){t=s[a];t.off(e,r,this);if(n||i.isEmpty(t._events))delete this._listeningTo[a]}return this}};var l=/\s+/;var c=function(t,e,i,r){if(!i)return true;if(typeof i==="object"){for(var s in i){t[e].apply(t,[s,i[s]].concat(r))}return false}if(l.test(i)){var n=i.split(l);for(var a=0,o=n.length;a<o;a++){t[e].apply(t,[n[a]].concat(r))}return false}return true};var f=function(t,e){var i,r=-1,s=t.length,n=e[0],a=e[1],o=e[2];switch(e.length){case 0:while(++r<s)(i=t[r]).callback.call(i.ctx);return;case 1:while(++r<s)(i=t[r]).callback.call(i.ctx,n);return;case 2:while(++r<s)(i=t[r]).callback.call(i.ctx,n,a);return;case 3:while(++r<s)(i=t[r]).callback.call(i.ctx,n,a,o);return;default:while(++r<s)(i=t[r]).callback.apply(i.ctx,e);return}};var d={listenTo:"on",listenToOnce:"once"};i.each(d,function(t,e){u[e]=function(e,r,s){var n=this._listeningTo||(this._listeningTo={});var a=e._listenId||(e._listenId=i.uniqueId("l"));n[a]=e;if(!s&&typeof r==="object")s=this;e[t](r,s,this);return this}});u.bind=u.on;u.unbind=u.off;i.extend(e,u);var p=e.Model=function(t,e){var r=t||{};e||(e={});this.cid=i.uniqueId("c");this.attributes={};if(e.collection)this.collection=e.collection;if(e.parse)r=this.parse(r,e)||{};r=i.defaults({},r,i.result(this,"defaults"));this.set(r,e);this.changed={};this.initialize.apply(this,arguments)};i.extend(p.prototype,u,{changed:null,validationError:null,idAttribute:"id",initialize:function(){},toJSON:function(t){return i.clone(this.attributes)},sync:function(){return e.sync.apply(this,arguments)},get:function(t){return this.attributes[t]},escape:function(t){return i.escape(this.get(t))},has:function(t){return this.get(t)!=null},set:function(t,e,r){var s,n,a,o,h,u,l,c;if(t==null)return this;if(typeof t==="object"){n=t;r=e}else{(n={})[t]=e}r||(r={});if(!this._validate(n,r))return false;a=r.unset;h=r.silent;o=[];u=this._changing;this._changing=true;if(!u){this._previousAttributes=i.clone(this.attributes);this.changed={}}c=this.attributes,l=this._previousAttributes;if(this.idAttribute in n)this.id=n[this.idAttribute];for(s in n){e=n[s];if(!i.isEqual(c[s],e))o.push(s);if(!i.isEqual(l[s],e)){this.changed[s]=e}else{delete this.changed[s]}a?delete c[s]:c[s]=e}if(!h){if(o.length)this._pending=r;for(var f=0,d=o.length;f<d;f++){this.trigger("change:"+o[f],this,c[o[f]],r)}}if(u)return this;if(!h){while(this._pending){r=this._pending;this._pending=false;this.trigger("change",this,r)}}this._pending=false;this._changing=false;return this},unset:function(t,e){return this.set(t,void 0,i.extend({},e,{unset:true}))},clear:function(t){var e={};for(var r in this.attributes)e[r]=void 0;return this.set(e,i.extend({},t,{unset:true}))},hasChanged:function(t){if(t==null)return!i.isEmpty(this.changed);return i.has(this.changed,t)},changedAttributes:function(t){if(!t)return this.hasChanged()?i.clone(this.changed):false;var e,r=false;var s=this._changing?this._previousAttributes:this.attributes;for(var n in t){if(i.isEqual(s[n],e=t[n]))continue;(r||(r={}))[n]=e}return r},previous:function(t){if(t==null||!this._previousAttributes)return null;return this._previousAttributes[t]},previousAttributes:function(){return i.clone(this._previousAttributes)},fetch:function(t){t=t?i.clone(t):{};if(t.parse===void 0)t.parse=true;var e=this;var r=t.success;t.success=function(i){if(!e.set(e.parse(i,t),t))return false;if(r)r(e,i,t);e.trigger("sync",e,i,t)};q(this,t);return this.sync("read",this,t)},save:function(t,e,r){var s,n,a,o=this.attributes;if(t==null||typeof t==="object"){s=t;r=e}else{(s={})[t]=e}r=i.extend({validate:true},r);if(s&&!r.wait){if(!this.set(s,r))return false}else{if(!this._validate(s,r))return false}if(s&&r.wait){this.attributes=i.extend({},o,s)}if(r.parse===void 0)r.parse=true;var h=this;var u=r.success;r.success=function(t){h.attributes=o;var e=h.parse(t,r);if(r.wait)e=i.extend(s||{},e);if(i.isObject(e)&&!h.set(e,r)){return false}if(u)u(h,t,r);h.trigger("sync",h,t,r)};q(this,r);n=this.isNew()?"create":r.patch?"patch":"update";if(n==="patch")r.attrs=s;a=this.sync(n,this,r);if(s&&r.wait)this.attributes=o;return a},destroy:function(t){t=t?i.clone(t):{};var e=this;var r=t.success;var s=function(){e.trigger("destroy",e,e.collection,t)};t.success=function(i){if(t.wait||e.isNew())s();if(r)r(e,i,t);if(!e.isNew())e.trigger("sync",e,i,t)};if(this.isNew()){t.success();return false}q(this,t);var n=this.sync("delete",this,t);if(!t.wait)s();return n},url:function(){var t=i.result(this,"urlRoot")||i.result(this.collection,"url")||M();if(this.isNew())return t;return t.replace(/([^\/])$/,"$1/")+encodeURIComponent(this.id)},parse:function(t,e){return t},clone:function(){return new this.constructor(this.attributes)},isNew:function(){return!this.has(this.idAttribute)},isValid:function(t){return this._validate({},i.extend(t||{},{validate:true}))},_validate:function(t,e){if(!e.validate||!this.validate)return true;t=i.extend({},this.attributes,t);var r=this.validationError=this.validate(t,e)||null;if(!r)return true;this.trigger("invalid",this,r,i.extend(e,{validationError:r}));return false}});var v=["keys","values","pairs","invert","pick","omit"];i.each(v,function(t){p.prototype[t]=function(){var e=o.call(arguments);e.unshift(this.attributes);return i[t].apply(i,e)}});var g=e.Collection=function(t,e){e||(e={});if(e.model)this.model=e.model;if(e.comparator!==void 0)this.comparator=e.comparator;this._reset();this.initialize.apply(this,arguments);if(t)this.reset(t,i.extend({silent:true},e))};var m={add:true,remove:true,merge:true};var y={add:true,remove:false};i.extend(g.prototype,u,{model:p,initialize:function(){},toJSON:function(t){return this.map(function(e){return e.toJSON(t)})},sync:function(){return e.sync.apply(this,arguments)},add:function(t,e){return this.set(t,i.extend({merge:false},e,y))},remove:function(t,e){var r=!i.isArray(t);t=r?[t]:i.clone(t);e||(e={});var s,n,a,o;for(s=0,n=t.length;s<n;s++){o=t[s]=this.get(t[s]);if(!o)continue;delete this._byId[o.id];delete this._byId[o.cid];a=this.indexOf(o);this.models.splice(a,1);this.length--;if(!e.silent){e.index=a;o.trigger("remove",o,this,e)}this._removeReference(o,e)}return r?t[0]:t},set:function(t,e){e=i.defaults({},e,m);if(e.parse)t=this.parse(t,e);var r=!i.isArray(t);t=r?t?[t]:[]:i.clone(t);var s,n,a,o,h,u,l;var c=e.at;var f=this.model;var d=this.comparator&&c==null&&e.sort!==false;var v=i.isString(this.comparator)?this.comparator:null;var g=[],y=[],_={};var b=e.add,w=e.merge,x=e.remove;var E=!d&&b&&x?[]:false;for(s=0,n=t.length;s<n;s++){h=t[s]||{};if(h instanceof p){a=o=h}else{a=h[f.prototype.idAttribute||"id"]}if(u=this.get(a)){if(x)_[u.cid]=true;if(w){h=h===o?o.attributes:h;if(e.parse)h=u.parse(h,e);u.set(h,e);if(d&&!l&&u.hasChanged(v))l=true}t[s]=u}else if(b){o=t[s]=this._prepareModel(h,e);if(!o)continue;g.push(o);this._addReference(o,e)}o=u||o;if(E&&(o.isNew()||!_[o.id]))E.push(o);_[o.id]=true}if(x){for(s=0,n=this.length;s<n;++s){if(!_[(o=this.models[s]).cid])y.push(o)}if(y.length)this.remove(y,e)}if(g.length||E&&E.length){if(d)l=true;this.length+=g.length;if(c!=null){for(s=0,n=g.length;s<n;s++){this.models.splice(c+s,0,g[s])}}else{if(E)this.models.length=0;var k=E||g;for(s=0,n=k.length;s<n;s++){this.models.push(k[s])}}}if(l)this.sort({silent:true});if(!e.silent){for(s=0,n=g.length;s<n;s++){(o=g[s]).trigger("add",o,this,e)}if(l||E&&E.length)this.trigger("sort",this,e)}return r?t[0]:t},reset:function(t,e){e||(e={});for(var r=0,s=this.models.length;r<s;r++){this._removeReference(this.models[r],e)}e.previousModels=this.models;this._reset();t=this.add(t,i.extend({silent:true},e));if(!e.silent)this.trigger("reset",this,e);return t},push:function(t,e){return this.add(t,i.extend({at:this.length},e))},pop:function(t){var e=this.at(this.length-1);this.remove(e,t);return e},unshift:function(t,e){return this.add(t,i.extend({at:0},e))},shift:function(t){var e=this.at(0);this.remove(e,t);return e},slice:function(){return o.apply(this.models,arguments)},get:function(t){if(t==null)return void 0;return this._byId[t]||this._byId[t.id]||this._byId[t.cid]},at:function(t){return this.models[t]},where:function(t,e){if(i.isEmpty(t))return e?void 0:[];return this[e?"find":"filter"](function(e){for(var i in t){if(t[i]!==e.get(i))return false}return true})},findWhere:function(t){return this.where(t,true)},sort:function(t){if(!this.comparator)throw new Error("Cannot sort a set without a comparator");t||(t={});if(i.isString(this.comparator)||this.comparator.length===1){this.models=this.sortBy(this.comparator,this)}else{this.models.sort(i.bind(this.comparator,this))}if(!t.silent)this.trigger("sort",this,t);return this},pluck:function(t){return i.invoke(this.models,"get",t)},fetch:function(t){t=t?i.clone(t):{};if(t.parse===void 0)t.parse=true;var e=t.success;var r=this;t.success=function(i){var s=t.reset?"reset":"set";r[s](i,t);if(e)e(r,i,t);r.trigger("sync",r,i,t)};q(this,t);return this.sync("read",this,t)},create:function(t,e){e=e?i.clone(e):{};if(!(t=this._prepareModel(t,e)))return false;if(!e.wait)this.add(t,e);var r=this;var s=e.success;e.success=function(t,i){if(e.wait)r.add(t,e);if(s)s(t,i,e)};t.save(null,e);return t},parse:function(t,e){return t},clone:function(){return new this.constructor(this.models)},_reset:function(){this.length=0;this.models=[];this._byId={}},_prepareModel:function(t,e){if(t instanceof p)return t;e=e?i.clone(e):{};e.collection=this;var r=new this.model(t,e);if(!r.validationError)return r;this.trigger("invalid",this,r.validationError,e);return false},_addReference:function(t,e){this._byId[t.cid]=t;if(t.id!=null)this._byId[t.id]=t;if(!t.collection)t.collection=this;t.on("all",this._onModelEvent,this)},_removeReference:function(t,e){if(this===t.collection)delete t.collection;t.off("all",this._onModelEvent,this)},_onModelEvent:function(t,e,i,r){if((t==="add"||t==="remove")&&i!==this)return;if(t==="destroy")this.remove(e,r);if(e&&t==="change:"+e.idAttribute){delete this._byId[e.previous(e.idAttribute)];if(e.id!=null)this._byId[e.id]=e}this.trigger.apply(this,arguments)}});var _=["forEach","each","map","collect","reduce","foldl","inject","reduceRight","foldr","find","detect","filter","select","reject","every","all","some","any","include","contains","invoke","max","min","toArray","size","first","head","take","initial","rest","tail","drop","last","without","difference","indexOf","shuffle","lastIndexOf","isEmpty","chain","sample"];i.each(_,function(t){g.prototype[t]=function(){var e=o.call(arguments);e.unshift(this.models);return i[t].apply(i,e)}});var b=["groupBy","countBy","sortBy","indexBy"];i.each(b,function(t){g.prototype[t]=function(e,r){var s=i.isFunction(e)?e:function(t){return t.get(e)};return i[t](this.models,s,r)}});var w=e.View=function(t){this.cid=i.uniqueId("view");t||(t={});i.extend(this,i.pick(t,E));this._ensureElement();this.initialize.apply(this,arguments);this.delegateEvents()};var x=/^(\S+)\s*(.*)$/;var E=["model","collection","el","id","attributes","className","tagName","events"];i.extend(w.prototype,u,{tagName:"div",$:function(t){return this.$el.find(t)},initialize:function(){},render:function(){return this},remove:function(){this.$el.remove();this.stopListening();return this},setElement:function(t,i){if(this.$el)this.undelegateEvents();this.$el=t instanceof e.$?t:e.$(t);this.el=this.$el[0];if(i!==false)this.delegateEvents();return this},delegateEvents:function(t){if(!(t||(t=i.result(this,"events"))))return this;this.undelegateEvents();for(var e in t){var r=t[e];if(!i.isFunction(r))r=this[t[e]];if(!r)continue;var s=e.match(x);var n=s[1],a=s[2];r=i.bind(r,this);n+=".delegateEvents"+this.cid;if(a===""){this.$el.on(n,r)}else{this.$el.on(n,a,r)}}return this},undelegateEvents:function(){this.$el.off(".delegateEvents"+this.cid);return this},_ensureElement:function(){if(!this.el){var t=i.extend({},i.result(this,"attributes"));if(this.id)t.id=i.result(this,"id");if(this.className)t["class"]=i.result(this,"className");var r=e.$("<"+i.result(this,"tagName")+">").attr(t);this.setElement(r,false)}else{this.setElement(i.result(this,"el"),false)}}});e.sync=function(t,r,s){var n=T[t];i.defaults(s||(s={}),{emulateHTTP:e.emulateHTTP,emulateJSON:e.emulateJSON});var a={type:n,dataType:"json"};if(!s.url){a.url=i.result(r,"url")||M()}if(s.data==null&&r&&(t==="create"||t==="update"||t==="patch")){a.contentType="application/json";a.data=JSON.stringify(s.attrs||r.toJSON(s))}if(s.emulateJSON){a.contentType="application/x-www-form-urlencoded";a.data=a.data?{model:a.data}:{}}if(s.emulateHTTP&&(n==="PUT"||n==="DELETE"||n==="PATCH")){a.type="POST";if(s.emulateJSON)a.data._method=n;var o=s.beforeSend;s.beforeSend=function(t){t.setRequestHeader("X-HTTP-Method-Override",n);if(o)return o.apply(this,arguments)}}if(a.type!=="GET"&&!s.emulateJSON){a.processData=false}if(a.type==="PATCH"&&k){a.xhr=function(){return new ActiveXObject("Microsoft.XMLHTTP")}}var h=s.xhr=e.ajax(i.extend(a,s));r.trigger("request",r,h,s);return h};var k=typeof window!=="undefined"&&!!window.ActiveXObject&&!(window.XMLHttpRequest&&(new XMLHttpRequest).dispatchEvent);var T={create:"POST",update:"PUT",patch:"PATCH","delete":"DELETE",read:"GET"};e.ajax=function(){return e.$.ajax.apply(e.$,arguments)};var $=e.Router=function(t){t||(t={});if(t.routes)this.routes=t.routes;this._bindRoutes();this.initialize.apply(this,arguments)};var S=/\((.*?)\)/g;var H=/(\(\?)?:\w+/g;var A=/\*\w+/g;var I=/[\-{}\[\]+?.,\\\^$|#\s]/g;i.extend($.prototype,u,{initialize:function(){},route:function(t,r,s){if(!i.isRegExp(t))t=this._routeToRegExp(t);if(i.isFunction(r)){s=r;r=""}if(!s)s=this[r];var n=this;e.history.route(t,function(i){var a=n._extractParameters(t,i);n.execute(s,a);n.trigger.apply(n,["route:"+r].concat(a));n.trigger("route",r,a);e.history.trigger("route",n,r,a)});return this},execute:function(t,e){if(t)t.apply(this,e)},navigate:function(t,i){e.history.navigate(t,i);return this},_bindRoutes:function(){if(!this.routes)return;this.routes=i.result(this,"routes");var t,e=i.keys(this.routes);while((t=e.pop())!=null){this.route(t,this.routes[t])}},_routeToRegExp:function(t){t=t.replace(I,"\\$&").replace(S,"(?:$1)?").replace(H,function(t,e){return e?t:"([^/?]+)"}).replace(A,"([^?]*?)");return new RegExp("^"+t+"(?:\\?([\\s\\S]*))?$")},_extractParameters:function(t,e){var r=t.exec(e).slice(1);return i.map(r,function(t,e){if(e===r.length-1)return t||null;return t?decodeURIComponent(t):null})}});var N=e.History=function(){this.handlers=[];i.bindAll(this,"checkUrl");if(typeof window!=="undefined"){this.location=window.location;this.history=window.history}};var R=/^[#\/]|\s+$/g;var O=/^\/+|\/+$/g;var P=/msie [\w.]+/;var C=/\/$/;var j=/#.*$/;N.started=false;i.extend(N.prototype,u,{interval:50,atRoot:function(){return this.location.pathname.replace(/[^\/]$/,"$&/")===this.root},getHash:function(t){var e=(t||this).location.href.match(/#(.*)$/);return e?e[1]:""},getFragment:function(t,e){if(t==null){if(this._hasPushState||!this._wantsHashChange||e){t=decodeURI(this.location.pathname+this.location.search);var i=this.root.replace(C,"");if(!t.indexOf(i))t=t.slice(i.length)}else{t=this.getHash()}}return t.replace(R,"")},start:function(t){if(N.started)throw new Error("Backbone.history has already been started");N.started=true;this.options=i.extend({root:"/"},this.options,t);this.root=this.options.root;this._wantsHashChange=this.options.hashChange!==false;this._wantsPushState=!!this.options.pushState;this._hasPushState=!!(this.options.pushState&&this.history&&this.history.pushState);var r=this.getFragment();var s=document.documentMode;var n=P.exec(navigator.userAgent.toLowerCase())&&(!s||s<=7);this.root=("/"+this.root+"/").replace(O,"/");if(n&&this._wantsHashChange){var a=e.$('<iframe src="javascript:0" tabindex="-1">');this.iframe=a.hide().appendTo("body")[0].contentWindow;this.navigate(r)}if(this._hasPushState){e.$(window).on("popstate",this.checkUrl)}else if(this._wantsHashChange&&"onhashchange"in window&&!n){e.$(window).on("hashchange",this.checkUrl)}else if(this._wantsHashChange){this._checkUrlInterval=setInterval(this.checkUrl,this.interval)}this.fragment=r;var o=this.location;if(this._wantsHashChange&&this._wantsPushState){if(!this._hasPushState&&!this.atRoot()){this.fragment=this.getFragment(null,true);this.location.replace(this.root+"#"+this.fragment);return true}else if(this._hasPushState&&this.atRoot()&&o.hash){this.fragment=this.getHash().replace(R,"");this.history.replaceState({},document.title,this.root+this.fragment)}}if(!this.options.silent)return this.loadUrl()},stop:function(){e.$(window).off("popstate",this.checkUrl).off("hashchange",this.checkUrl);if(this._checkUrlInterval)clearInterval(this._checkUrlInterval);N.started=false},route:function(t,e){this.handlers.unshift({route:t,callback:e})},checkUrl:function(t){var e=this.getFragment();if(e===this.fragment&&this.iframe){e=this.getFragment(this.getHash(this.iframe))}if(e===this.fragment)return false;if(this.iframe)this.navigate(e);this.loadUrl()},loadUrl:function(t){t=this.fragment=this.getFragment(t);return i.any(this.handlers,function(e){if(e.route.test(t)){e.callback(t);return true}})},navigate:function(t,e){if(!N.started)return false;if(!e||e===true)e={trigger:!!e};var i=this.root+(t=this.getFragment(t||""));t=t.replace(j,"");if(this.fragment===t)return;this.fragment=t;if(t===""&&i!=="/")i=i.slice(0,-1);if(this._hasPushState){this.history[e.replace?"replaceState":"pushState"]({},document.title,i)}else if(this._wantsHashChange){this._updateHash(this.location,t,e.replace);if(this.iframe&&t!==this.getFragment(this.getHash(this.iframe))){if(!e.replace)this.iframe.document.open().close();this._updateHash(this.iframe.location,t,e.replace)}}else{return this.location.assign(i)}if(e.trigger)return this.loadUrl(t)},_updateHash:function(t,e,i){if(i){var r=t.href.replace(/(javascript:|#).*$/,"");t.replace(r+"#"+e)}else{t.hash="#"+e}}});e.history=new N;var U=function(t,e){var r=this;var s;if(t&&i.has(t,"constructor")){s=t.constructor}else{s=function(){return r.apply(this,arguments)}}i.extend(s,r,e);var n=function(){this.constructor=s};n.prototype=r.prototype;s.prototype=new n;if(t)i.extend(s.prototype,t);s.__super__=r.prototype;return s};p.extend=g.extend=$.extend=w.extend=N.extend=U;var M=function(){throw new Error('A "url" property or function must be specified')};var q=function(t,e){var i=e.error;e.error=function(r){if(i)i(t,r,e);t.trigger("error",t,r,e)}};return e});
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      /* global tinymce, wpCookies, autosaveL10n, switchEditors */
// Back-compat
window.autosave = function() {
	return true;
};

( function( $, window ) {
	function autosave() {
		var initialCompareString,
		lastTriggerSave = 0,
		$document = $(document);

		/**
		 * Returns the data saved in both local and remote autosave
		 *
		 * @return object Object containing the post data
		 */
		function getPostData( type ) {
			var post_name, parent_id, data,
				time = ( new Date() ).getTime(),
				cats = [],
				editor = typeof tinymce !== 'undefined' && tinymce.get('content');

			// Don't run editor.save() more often than every 3 sec.
			// It is resource intensive and might slow down typing in long posts on slow devices.
			if ( editor && ! editor.isHidden() && time - 3000 > lastTriggerSave ) {
				editor.save();
				lastTriggerSave = time;
			}

			data = {
				post_id: $( '#post_ID' ).val() || 0,
				post_type: $( '#post_type' ).val() || '',
				post_author: $( '#post_author' ).val() || '',
				post_title: $( '#title' ).val() || '',
				content: $( '#content' ).val() || '',
				excerpt: $( '#excerpt' ).val() || ''
			};

			if ( type === 'local' ) {
				return data;
			}

			$( 'input[id^="in-category-"]:checked' ).each( function() {
				cats.push( this.value );
			});
			data.catslist = cats.join(',');

			if ( post_name = $( '#post_name' ).val() ) {
				data.post_name = post_name;
			}

			if ( parent_id = $( '#parent_id' ).val() ) {
				data.parent_id = parent_id;
			}

			if ( $( '#comment_status' ).prop( 'checked' ) ) {
				data.comment_status = 'open';
			}

			if ( $( '#ping_status' ).prop( 'checked' ) ) {
				data.ping_status = 'open';
			}

			if ( $( '#auto_draft' ).val() === '1' ) {
				data.auto_draft = '1';
			}

			return data;
		}

		// Concatenate title, content and excerpt. Used to track changes when auto-saving.
		function getCompareString( postData ) {
			if ( typeof postData === 'object' ) {
				return ( postData.post_title || '' ) + '::' + ( postData.content || '' ) + '::' + ( postData.excerpt || '' );
			}

			return ( $('#title').val() || '' ) + '::' + ( $('#content').val() || '' ) + '::' + ( $('#excerpt').val() || '' );
		}

		function disableButtons() {
			$document.trigger('autosave-disable-buttons');
			// Re-enable 5 sec later. Just gives autosave a head start to avoid collisions.
			setTimeout( enableButtons, 5000 );
		}

		function enableButtons() {
			$document.trigger( 'autosave-enable-buttons' );
		}

		// Autosave in localStorage
		function autosaveLocal() {
			var restorePostData, undoPostData, blog_id, post_id, hasStorage, intervalTimer,
				lastCompareString,
				isSuspended = false;

			// Check if the browser supports sessionStorage and it's not disabled
			function checkStorage() {
				var test = Math.random().toString(),
					result = false;

				try {
					window.sessionStorage.setItem( 'wp-test', test );
					result = window.sessionStorage.getItem( 'wp-test' ) === test;
					window.sessionStorage.removeItem( 'wp-test' );
				} catch(e) {}

				hasStorage = result;
				return result;
			}

			/**
			 * Initialize the local storage
			 *
			 * @return mixed False if no sessionStorage in the browser or an Object containing all postData for this blog
			 */
			function getStorage() {
				var stored_obj = false;
				// Separate local storage containers for each blog_id
				if ( hasStorage && blog_id ) {
					stored_obj = sessionStorage.getItem( 'wp-autosave-' + blog_id );

					if ( stored_obj ) {
						stored_obj = JSON.parse( stored_obj );
					} else {
						stored_obj = {};
					}
				}

				return stored_obj;
			}

			/**
			 * Set the storage for this blog
			 *
			 * Confirms that the data was saved successfully.
			 *
			 * @return bool
			 */
			function setStorage( stored_obj ) {
				var key;

				if ( hasStorage && blog_id ) {
					key = 'wp-autosave-' + blog_id;
					sessionStorage.setItem( key, JSON.stringify( stored_obj ) );
					return sessionStorage.getItem( key ) !== null;
				}

				return false;
			}

			/**
			 * Get the saved post data for the current post
			 *
			 * @return mixed False if no storage or no data or the postData as an Object
			 */
			function getSavedPostData() {
				var stored = getStorage();

				if ( ! stored || ! post_id ) {
					return false;
				}

				return stored[ 'post_' + post_id ] || false;
			}

			/**
			 * Set (save or delete) post data in the storage.
			 *
			 * If stored_data evaluates to 'false' the storage key for the current post will be removed
			 *
			 * $param stored_data The post data to store or null/false/empty to delete the key
			 * @return bool
			 */
			function setData( stored_data ) {
				var stored = getStorage();

				if ( ! stored || ! post_id ) {
					return false;
				}

				if ( stored_data ) {
					stored[ 'post_' + post_id ] = stored_data;
				} else if ( stored.hasOwnProperty( 'post_' + post_id ) ) {
					delete stored[ 'post_' + post_id ];
				} else {
					return false;
				}

				return setStorage( stored );
			}

			function suspend() {
				isSuspended = true;
			}

			function resume() {
				isSuspended = false;
			}

			/**
			 * Save post data for the current post
			 *
			 * Runs on a 15 sec. interval, saves when there are differences in the post title or content.
			 * When the optional data is provided, updates the last saved post data.
			 *
			 * $param data optional Object The post data for saving, minimum 'post_title' and 'content'
			 * @return bool
			 */
			function save( data ) {
				var postData, compareString,
					result = false;

				if ( isSuspended || ! hasStorage ) {
					return false;
				}

				if ( data ) {
					postData = getSavedPostData() || {};
					$.extend( postData, data );
				} else {
					postData = getPostData('local');
				}

				compareString = getCompareString( postData );

				if ( typeof lastCompareString === 'undefined' ) {
					lastCompareString = initialCompareString;
				}

				// If the content, title and excerpt did not change since the last save, don't save again
				if ( compareString === lastCompareString ) {
					return false;
				}

				postData.save_time = ( new Date() ).getTime();
				postData.status = $( '#post_status' ).val() || '';
				result = setData( postData );

				if ( result ) {
					lastCompareString = compareString;
				}

				return result;
			}

			// Run on DOM ready
			function run() {
				post_id = $('#post_ID').val() || 0;

				// Check if the local post data is different than the loaded post data.
				if ( $( '#wp-content-wrap' ).hasClass( 'tmce-active' ) ) {
					// If TinyMCE loads first, check the post 1.5 sec. after it is ready.
					// By this time the content has been loaded in the editor and 'saved' to the textarea.
					// This prevents false positives.
					$document.on( 'tinymce-editor-init.autosave', function() {
						window.setTimeout( function() {
							checkPost();
						}, 1500 );
					});
				} else {
					checkPost();
				}

				// Save every 15 sec.
				intervalTimer = window.setInterval( save, 15000 );

				$( 'form#post' ).on( 'submit.autosave-local', function() {
					var editor = typeof tinymce !== 'undefined' && tinymce.get('content'),
						post_id = $('#post_ID').val() || 0;

					if ( editor && ! editor.isHidden() ) {
						// Last onSubmit event in the editor, needs to run after the content has been moved to the textarea.
						editor.on( 'submit', function() {
							save({
								post_title: $( '#title' ).val() || '',
								content: $( '#content' ).val() || '',
								excerpt: $( '#excerpt' ).val() || ''
							});
						});
					} else {
						save({
							post_title: $( '#title' ).val() || '',
							content: $( '#content' ).val() || '',
							excerpt: $( '#excerpt' ).val() || ''
						});
					}

					wpCookies.set( 'wp-saving-post', post_id + '-check', 24 * 60 * 60 );
				});
			}

			// Strip whitespace and compare two strings
			function compare( str1, str2 ) {
				function removeSpaces( string ) {
					return string.toString().replace(/[\x20\t\r\n\f]+/g, '');
				}

				return ( removeSpaces( str1 || '' ) === removeSpaces( str2 || '' ) );
			}

			/**
			 * Check if the saved data for the current post (if any) is different than the loaded post data on the screen
			 *
			 * Shows a standard message letting the user restore the post data if different.
			 *
			 * @return void
			 */
			function checkPost() {
				var content, post_title, excerpt, $notice,
					postData = getSavedPostData(),
					cookie = wpCookies.get( 'wp-saving-post' );

				if ( cookie === post_id + '-saved' ) {
					wpCookies.remove( 'wp-saving-post' );
					// The post was saved properly, remove old data and bail
					setData( false );
					return;
				}

				if ( ! postData ) {
					return;
				}

				// There is a newer autosave. Don't show two "restore" notices at the same time.
				if ( $( '#has-newer-autosave' ).length ) {
					return;
				}

				content = $( '#content' ).val() || '';
				post_title = $( '#title' ).val() || '';
				excerpt = $( '#excerpt' ).val() || '';

				if ( compare( content, postData.content ) && compare( post_title, postData.post_title ) &&
					compare( excerpt, postData.excerpt ) ) {

					return;
				}

				restorePostData = postData;
				undoPostData = {
					content: content,
					post_title: post_title,
					excerpt: excerpt
				};

				$notice = $( '#local-storage-notice' );
				$('.wrap h2').first().after( $notice.addClass( 'notice-warning' ).show() );

				$notice.on( 'click.autosave-local', function( event ) {
					var $target = $( event.target );

					if ( $target.hasClass( 'restore-backup' ) ) {
						restorePost( restorePostData );
						$target.parent().hide();
						$(this).find( 'p.undo-restore' ).show();
						$notice.removeClass( 'notice-warning' ).addClass( 'notice-success' );
					} else if ( $target.hasClass( 'undo-restore-backup' ) ) {
						restorePost( undoPostData );
						$target.parent().hide();
						$(this).find( 'p.local-restore' ).show();
						$notice.removeClass( 'notice-success' ).addClass( 'notice-warning' );
					}

					event.preventDefault();
				});
			}

			// Restore the current title, content and excerpt from postData.
			function restorePost( postData ) {
				var editor;

				if ( postData ) {
					// Set the last saved data
					lastCompareString = getCompareString( postData );

					if ( $( '#title' ).val() !== postData.post_title ) {
						$( '#title' ).focus().val( postData.post_title || '' );
					}

					$( '#excerpt' ).val( postData.excerpt || '' );
					editor = typeof tinymce !== 'undefined' && tinymce.get('content');

					if ( editor && ! editor.isHidden() && typeof switchEditors !== 'undefined' ) {
						// Make sure there's an undo level in the editor
						editor.undoManager.add();
						editor.setContent( postData.content ? switchEditors.wpautop( postData.content ) : '' );
					} else {
						// Make sure the Text editor is selected
						$( '#content-html' ).click();
						$( '#content' ).val( postData.content );
					}

					return true;
				}

				return false;
			}

			blog_id = typeof window.autosaveL10n !== 'undefined' && window.autosaveL10n.blog_id;

			// Check if the browser supports sessionStorage and it's not disabled,
			// then initialize and run checkPost().
			// Don't run if the post type supports neither 'editor' (textarea#content) nor 'excerpt'.
			if ( checkStorage() && blog_id && ( $('#content').length || $('#excerpt').length ) ) {
				$document.ready( run );
			}

			return {
				hasStorage: hasStorage,
				getSavedPostData: getSavedPostData,
				save: save,
				suspend: suspend,
				resume: resume
			};
		}

		// Autosave on the server
		function autosaveServer() {
			var _blockSave, _blockSaveTimer, previousCompareString, lastCompareString,
				nextRun = 0,
				isSuspended = false;

			// Block saving for the next 10 sec.
			function tempBlockSave() {
				_blockSave = true;
				window.clearTimeout( _blockSaveTimer );

				_blockSaveTimer = window.setTimeout( function() {
					_blockSave = false;
				}, 10000 );
			}

			function suspend() {
				isSuspended = true;
			}

			function resume() {
				isSuspended = false;
			}

			// Runs on heartbeat-response
			function response( data ) {
				_schedule();
				_blockSave = false;
				lastCompareString = previousCompareString;
				previousCompareString = '';

				$document.trigger( 'after-autosave', [data] );
				enableButtons();

				if ( data.success ) {
					// No longer an auto-draft
					$( '#auto_draft' ).val('');
				}
			}

			/**
			 * Save immediately
			 *
			 * Resets the timing and tells heartbeat to connect now
			 *
			 * @return void
			 */
			function triggerSave() {
				nextRun = 0;
				wp.heartbeat.connectNow();
			}

			/**
			 * Checks if the post content in the textarea has changed since page load.
			 *
			 * This also happens when TinyMCE is active and editor.save() is triggered by
			 * wp.autosave.getPostData().
			 *
			 * @return bool
			 */
			function postChanged() {
				return getCompareString() !== initialCompareString;
			}

			// Runs on 'heartbeat-send'
			function save() {
				var postData, compareString;

				// window.autosave() used for back-compat
				if ( isSuspended || _blockSave || ! window.autosave() ) {
					return false;
				}

				if ( ( new Date() ).getTime() < nextRun ) {
					return false;
				}

				postData = getPostData();
				compareString = getCompareString( postData );

				// First check
				if ( typeof lastCompareString === 'undefined' ) {
					lastCompareString = initialCompareString;
				}

				// No change
				if ( compareString === lastCompareString ) {
					return false;
				}

				previousCompareString = compareString;
				tempBlockSave();
				disableButtons();

				$document.trigger( 'wpcountwords', [ postData.content ] )
					.trigger( 'before-autosave', [ postData ] );

				postData._wpnonce = $( '#_wpnonce' ).val() || '';

				return postData;
			}

			function _schedule() {
				nextRun = ( new Date() ).getTime() + ( autosaveL10n.autosaveInterval * 1000 ) || 60000;
			}

			$document.on( 'heartbeat-send.autosave', function( event, data ) {
				var autosaveData = save();

				if ( autosaveData ) {
					data.wp_autosave = autosaveData;
				}
			}).on( 'heartbeat-tick.autosave', function( event, data ) {
				if ( data.wp_autosave ) {
					response( data.wp_autosave );
				}
			}).on( 'heartbeat-connection-lost.autosave', function( event, error, status ) {
				// When connection is lost, keep user from submitting changes.
				if ( 'timeout' === error || 603 === status ) {
					var $notice = $('#lost-connection-notice');

					if ( ! wp.autosave.local.hasStorage ) {
						$notice.find('.hide-if-no-sessionstorage').hide();
					}

					$notice.show();
					disableButtons();
				}
			}).on( 'heartbeat-connection-restored.autosave', function() {
				$('#lost-connection-notice').hide();
				enableButtons();
			}).ready( function() {
				_schedule();
			});

			return {
				tempBlockSave: tempBlockSave,
				triggerSave: triggerSave,
				postChanged: postChanged,
				suspend: suspend,
				resume: resume
			};
		}

		// Wait for TinyMCE to initialize plus 1 sec. for any external css to finish loading,
		// then 'save' to the textarea before setting initialCompareString.
		// This avoids any insignificant differences between the initial textarea content and the content
		// extracted from the editor.
		$document.on( 'tinymce-editor-init.autosave', function( event, editor ) {
			if ( editor.id === 'content' ) {
				window.setTimeout( function() {
					editor.save();
					initialCompareString = getCompareString();
				}, 1000 );
			}
		}).ready( function() {
			// Set the initial compare string in case TinyMCE is not used or not loaded first
			initialCompareString = getCompareString();
		});

		return {
			getPostData: getPostData,
			getCompareString: getCompareString,
			disableButtons: disableButtons,
			enableButtons: enableButtons,
			local: autosaveLocal(),
			server: autosaveServer()
		};
	}

	window.wp = window.wp || {};
	window.wp.autosave = autosave();

}( jQuery, window ));
                                                                                                                                                                                                                                                                                          �PNG

   IHDR  `   P   a��%  �PLTE   ���������������������������������������������������/R�pppMMQ���XXX���WWW������������������XXX���YYYNTeLRe��덡�WWWCd�������Xs�XXX���������q��uuu�����ܘ��nnn���rrr��ډ��On�]y�XXX��嘩�?_����������```qqqRRR���zzzlllfff������AAA������mmm�����ȸ�����fff���-O������ض��d}�fffNNN,O�KKK-P�      -P�������,P����TTTJJJ���Pj����JJJ���c~�-P�w�ñ�����JJJ���K^�������������������sssyyylll[l�.T�eee��ڃ�����gggbbbooo���*M����+P�jjj+N�^^^iii�����╕���]fff���1T����������)L�������������5[�NNN����������;-R�V}�[ZZ��f0N������훛��ۍ���2W�������ExӬ��9[����7X�/P�-S����l��u��h�ڄ�������嵖��Ɂ�����𨍨��L��T���&&&"$*$[��ֈ+�p��r��ܽ{ͱs��ѹ�fjo�pmb79=a|�;=J�S&h�P}|}������~�v����e���d�Ь�{���Ο�����h��uiq��}�������Ľ�F��]¤U�����˹��ӭI���ۨIE�A�ۂB�e� `6   }tRNS -9F@0_�L#(�IZ��+wS�n���=�ھ��e��ĭ���_��������������Ƚ����z��b����s��>����{��h�����Z��WΒ񡬸�d�`������ӿ�HD�  6�IDATx��{@TW������R<� y
%����!Ҵ(�؃���9�ۘwү)`i,B�h0ضQ1�٤�	θk6��1�$�d�M��=���wέǽuϽu��X驯Z�)~PTս������w�HVXa�VXa�VXa�VXa��7S�bS�I��-�[^}�\��`���n+�(���F�����g�l).ޒ�Tfaq(��}�w?������%��759�6�%B������,���'o���w�Gn�d��D7�7�e�H�Vmi��"�Annl�j�3&��w�^)W)�mR�|�����g[R��L�Z��.�J��R��y�}�y���i{.ܚ�����ppoZȜ^�M����Z����FE-�3�� ��K��SW���[Ao���
av큛G�q�vM�Wx�8��--�B�H����h��9�xcR���F��h�>�d�2����	�^�Q��~�4�o��ޡ��z}ŷ_��]�x3Ʒlظq�DjE�m�ī\�TkR�M�XCz)�������JŲ�}MŲ��� ժ�V�Vj�>�L�u��{y��/5D �ny������-j��� ���8~+��k��[/�F�@� ]|���t�8�ధ���k���#ƉnHr8���>�pT�0�ѺY�D$n-r$��F�|�IG��y{x�����j���?o��70�����ϗ�x>�U�>}�O?� ݮ[�X3���_���k��8�'`*sy���A+J,F	`�l�(�V��� &R��6�?m^�f=�D�xrאd�r_v#��k����Kn���;D��������*�Č�(����F&���w�}��#�v���t���t��]��7<���2e�.�\��
�)�r8�-��|�$����`��>0�������	����X��Q��v)�N��p�/0�Z���R2 �+�& �;����7�NX fl$���o@�Z���B�_�����`$bsB:yW����r��;D
0�!�F[���RE-zV��1�T���ɒ��8x�f���l�6ۭ�	��4I�:�^b���^���&��&�`�f����cn��&N����[o}AwaRj�G z$�Ϯ��ꫯs�����ֵఈ�=w8�ֶ:��f���~��`~I�+7\1 �ׇ �ׇ �ׇ ��G���	�2#��[Z�j�Ye0�0�r����;`Λ����ۿ����?�K�~~=V���v^��o�>��-x�H��/	YZ��:�W��2B">�+��h��|���3��lP���Э,�'�$K��/�������f�<��{b%]y%=Og���гr0���/~_H��T�*�Q���S��P�@9��޺�I6�;�|n���?� ����&�CU��h1�#���:�k�� �ɕ� s���  fbfbfbfb��ĄO�W&M@�Yi�O#ә3O�4�
�4�o����|������/~ф��§w�|���?��������&������܀y�r�����-���t��=�NJ9{�&y���Ǥ,�
+��QE�5���ՌK?�ϚŶ,4�8�o��|��l�v;��;��_!Ud$��{�Rn�h��*�����}f�|-�"��
�Ez|wT�>�T�LWT�� �<�=`9&_���H�b�g��хϮ����޷�?�f��7?.�%,Z�� FJ2 �T+r8r��dp�e
�L�07���6`��`��`��`��(�����"�)��]Ci��>��ʣ �E��|��f̏e�����ח0����?z	C�`d*�/j����f�+�B��'�`#�����Ә+�e�"n���H�ff���c� �^�6���ta��s������N����+0V&��Y;0;j���N� ��0�yr�ش�$2K����t��������!��O.~���a3��[o���G�p"&��n��ţl��8}c~C�&��E��6 \
Z-V��X�_8`:y�00L/0������!�y�&�� #Č���|x��a`���)��˿���)<��X�&QS�s��~��4J]��`B$�͔`�u��g9�Q+�@ZT��c�p��@���R�e}l�
>�+����#F���Q*�ʧl�tIk�@��l`BK1x�1��{��͍^��ޠ�H�tܦI��V�ΣO�@�X�oMv8ҕ(���"S$e���V6`��HK���/?�W�}�4/&Kd��7��������+H�������+C�4�:a���B�@=P��_��3��&y�Nf��Fc���̳��t�-�<~��-���~i~`�vko��+Q |�hW��Q&�c3H�u���|�؜�x3!V������7�yxM�������I޸]G���t4�ݪD^y�8�,v���G�/���V���� ��\L�㐔"ݸ��W^y�˗w_y=�L �v�pF�}�|�<�3͋��s���`F�bW3������k8 �o��o�A$��Lԕ�ԓZ��R'p����׏.~�z��[I��b�����͇�F�\���ㅏ��پ��a�6��8��a��a�X�����!�'�* t�K��ʕ+�J	{b�����7^{��j7^�$�Y����)|v����5&5>��1)��n�?�T��C9�*��p^n���o<�y�7/��c &�V�{"�J ��+�L��#��U_��_�_�VCJ��#�?����U��"��%Q��yO��K`��йt�����K+3j�����&&`�_)+�^���=����r`��ͬ8������g].�Ǐ3^�T��ǥԌ�X��-&�	J�d���$V��dz��-E�)'�ih�{�`� �Ka~�ʕR�LJ���o���'o���g�g���ht:E�B��ו��z��H���AA����)�̿�*�A� ��Hw W\3դ4o��y�C��~�1���56`(���~��ߒ<]	u�fx !1��|o��	R]��$/ݫyB�	̨'�˒���v{��Tl�z�<���ޓ}[t�,�K�����l�e~d��O�g�($���ݱ�^���+���� 1�L!�Dx���Q{S�� )�\y�*aBLnn����̍ы�2�4X�гB�w_����8�~����.��h�s0��+W~��.�Ҽ�u<��u�<�!�z>�I�x��|���k׳I��;��	��;arӂ�2��L�@�?�T< � �1QO�7\�=o[��%�y0����n^�U�E&ӧC��[y��U�B��
j+~��ӻ S�M�w6��k�J_^2�f��
<��! �>�������� d�`p_t��#���y���X��������#�۲JG؀�Ϳ;�fއ;�J�%����3��DH�� ×"
0L�|��5���W?�`rY�Q�)g�D�"���H2Ĳt�9��L�{�H�E=`����l�4�<�	��[�.g�6wPD�.�R �`�@��� �r�F��\�Y�j2�ʏ�t�6;����*������`�v10!2M}ѷ�~�����|端�|b�X�(�噣	�\B���yܿ�9�>�U`i���H����&#]a�y��Q��YF�x��k���yvj0���.=�D~�9Z�W�%�MJ�I��B�eZ��-�9����}!��iK�Г3��i�ݥ�[����F�!�K��,yT*E�CǞ��[!}���k>�����+7�5��q8��oH�{p����a`�3�K��v�1�@��ŋtn8����؋5�[dx4:�g����p�bF�1.
0H�&�@�t�4/0}g���� s8��d�V{�r�5MM���'R'&c���1M- 0�ijo�W�#�/܀�F���Kh��4	0��2�SV �d�{�%*N�Ȭx����*�U`/�)�LW\�۬aBB�_�r#&�\��]q��-B8<�3�h�!R@�1����S� ����E�Q��|�%va^���y0��Ngs�����tvry0�"�k�.�<ܽ� Lt���LO#��U�[��H�ʙ��<��Rh' Dr!�Qh'���pY��"�1j��/=�s��x��<F(�{+�F��Ќ +���p(d���H8Eb<Gԃ�Q���Ɩp1��V�O�*��>,�G��͔+;�2� �`�17.��p����@;���Q��£�2y�&y`P�@?j��G���T� 0���q��Iԙ�K�c�ߺ���]e���T����3��)@f���8C��/߼y�7��oބD%��I9q��G��v�L(`d�;0��y�x\ȼʈ�B����U���[2iq�DeQ{%g� :9�KW%�Lf���3�z��$��:N�Z�0e�[3O�~2,��PY���K _���ϴp-�c���H��Be�y��+%�KW�J4�0M�s�͘�.��E���\�����Jz�8��Y5>?O�4u �5��=	JtEJ3NgjeA�H2�1q���
p߉D_�0���jd�G�L�y1���!n��
B��B�����!�a�ua�� ��5����	�ّ�\�!2ׄ��^^���b|�~5^�fG*0KPwnQL���v�, �֨��aLS��
�e�.m7��ZmޥK@�yd�v!��H��_*ᚨ�\*��$�����02! ���77�Լ����&��]J�����+�� ��f���h�Ԭ���B�� ㊑N���*����4�u- �h�#��(�7 �j��>\~@����3gl0����	.��z���N�c^̼쓃�ӄJ^>��C��i#$	iv4>v�(ҙKϞ=� uϼ��l�4R�}�(�d�,f��k��=�J�,��T�}�O��Y�Fo}�p�3:K+��4��f�t�o4��t��ط2�5Y��F�I��%����1�$��W���
d̠1�1���ۘ�kF����ѼP�p��]�u��#�<���`	x�`v9�Tx��K ��׃A�KHg�%���@�4>����kr�a�(��w��1�,����"�#��
̋���} s�	*^����GW)�\k�F���.�ّ�䕖eB״�\��+rTe��Z�D&�8K�Z/�S��3�ܿ��:��,ďL�V7Q.ef�zCo��<&�[��x�_i�J_.�����0�<^�sDA�[߻{��:;�T��R�aB�&���oC�\\��tǘ����!ڥAq��'BW����a���8<-�����x�G�<�h_�Y׮DPU3�N���!^��M 3�Dy0�.�,Bˋ�y��՗}g��N\��t���~w'����h�8�g��4���g�y�\s����H�j��(�fl���4���nS.d��������`n-��o�#�4o��tp�[Ѝ�$���t���%V� 0����9}->�8z���
<�V�Ҏd�u!����?M�"�;�I[�s���79B$�]
Z�:^��D�3�Yk1Jqx4��źr_�a�G���x���<!oeBB��`UHJV0��E��~�"�w_�gMSg~��UL�/1�E~ ����:{�,ݪN<�.���g3X`Uo-=Ko�Y�E�%�2�ll�L�~;�O�,��\H�̗�n�[�A&�XEj2�^���R��h�]yI�1ᯗY2�4��V�}�&�+y̾���Td��1� $ ��D����wn��0,�8�>���>@x�G>��S�-}�L��A`�M�41� "`pߓX*U�k�����o�\+KP�H��I��(��y�wQ���/�u0)1ב�r���拧������R�k����01����OX�mK4f��,c�aj�1��S�ll�,�n����@yU���c�c�\#�[�S���Bb���-R��T!�
���%%��)���>�P�x�'�n����G��⧾��9�<U,"�R�v�L��!��0ss0ss0���9(�=���� s�H(R�9O��M����[��p������4;��<�I�A&/���O����#v��Lx�d�����83�I��Z�m�Kf������,��^�B',��b�'Ux�ehQ^r�˓��:JzŁ�
�;(�U��Z꼎cp��`P����Q܊��pp90D�8���8���}�G?� �&�v�L���~wv��ى �ى �^pʿ���Oh5o7`��b��b;��ǃq�Q���3�)�H�E�������G*��h`W��Ӣ�<���>$�t�xJ���E�1��j��\��ؐ������b����k��_3��E�%bzy�I���e���$H�`��* J��5|��-���١w>8w�MX}�8���vG����8�Җc|Kf�"�$�v��)|ے@ 3H�=��{���b�B^��\���6sһp���7���i�a>w~�/@	"y0~z��4�K�2��w�k�2g%O�@`v�	�쥥ye��Gy��a���,���g�m[�}�ݶd)@� qw��%{��Zhw�(oy���N$�nWM*J�:wy�vd�5��!L-���x��/��t�_2�I�=�� �K���������k]s�e1���\L�k�TA�S[8������vj�1�y��[$����mY���g�5@V�m�&�U`w�C���!W _�vqN4����*+l��%�u���0��(�?A�˘g-�B CO7u���g�s��w F�Ύ�h�B$
hCX�Y�ֱ��!�u��V���v�D�x�E.��Λ�7�`'t�L+#{��\�}��_O0`(����\Y�$/;��x˴�����������ºB�H	�j�Ә� _�Z`N�_���.�)0��.y�?-E?���=�{�އ����hI�zI�]+���
+���
+���
+���
+���
+���
+���
+���Zb	_��OVx����q��R�&�(���M0�W~�
�R�oIY*��8�l[uu{{u��e�Th'|E;t��S�`���uOOw���k �R�o�o�`�B�	��Kg�͍�V�>`ƄD�@�^)�W��e(vn.vhK��^dֻe	zh���w���_r��U`��ީ�������ލ[B�U@��>95i�M-Z���}	��8A­��������|9vzz��xî=p�3�׮�6�#Ɖoiq�D���G�����-p�7z��m��K6К�o�&ы]2��=##�u��;���d%�W<N�_���Qd�7c|ˆ�'R`�H�z)�T��r,ˢjqI�畊eE���eE�3ja�FY�͎����6�ӛ�7n/F�2'���vR�񿢝 5$�z�~p�����H��C��v9�)h��]�vM�1NtC��Q�����������zx$"qk�#ɳ�@q�c�A[���GL�UFP:T�����~��7��oj���I��}պ؁�鶶遁�u���`-
0�����Z�>~��L�2N��V��9E�'@�#�,F	`�l�x�&|�0���IE��i�6S۞It�'wI�,��z�rf|��ĉ���3����qb�:g��h�Bf��
	�D#n�N�u��;2�A_@s��Y��#�vM�q�Ot��
\�E� SP�p�[��jI�����_p������?�����ta�F���K��u"U�k�~��1��Ĝ�Y-% �b����;��?�t��f=�ڐ��C2c#�Tw���*.�� �Ƨ�� #�+O��79y�L{A'ڶ%�;D�hs��Q��U"�"գ(oA�d�r��;>�5�P�o�IW�/MR��7����תni�+hE�@Èe2Qj���К8QJ����݅I�qG�8>�&[7��'���u-8,�g���(Z��(Қ�����OJh����J��!�}�Љ�ׇ �ׇ ��G�w_$�2��ޠ�+�F�U��*wq�L^L����mq��333g��@�3��e���65s�������W#jgolV��6��όc���sF���x���j��DN6V��tm�`�D�V�g0'�`(�lP�+O0v�����+H��%37v���f���A�0���t�k����Ҍ��L����I!97`�bPu���!c  �"7x
-�ف��֔fH�061I;�|n���?��<�/��:T�W-�S<LK�#����*0�\0�t�%� ��	��	��	��	
0�]d��djg�Μ2ٔ+�� ����sX9���`{��9�~��;�9� +Y�4G�`���5�KSo���s���{,�͎L�ό eF��I^�>#�ph��ӻР+��^�D�h�[�>*e��P��G�Y��'ԌK?�Ϛζ�]�;�z��9�k6�i�6���j��X~ġ"|/?G�MM�!��Ut��7`���hL�mJE���^�"9�A��{��1�����T�q���[��h��8>���G?.�%,Z�� FJ2 �T+r8r��d%چ�;S�L,34�efn���9��9t���Q�a�* � �^�ZZy2"�O�����9Ν�9���#����w���`:YaaS'/`��L��y��K�_���h�'z02ŗuj�����J�PV�H����J���4f5��`NT�,}�-�{���/�l�Ɏ��vl���l0�e��S�Չ*eV�w*G�}��P�6�����(.$p1R�#��2� � ��}�=&{�f\�t70���DL
�݆<��xw�7�74n�i^m�����b�Ȍ��u�&�5y} C�-0��Jڄ]b� 3��40=l���&z�$Ku;�|��ٶ�m.##ݗ���b�������pc�E��@�+�L������N�M>�!`�Jfxi;mc���َ3���DG���l��I"i1�,�:9�T~~ʬE��GҜv�c�����bb����Ð͍^1�q��"9k�q���A��V�Σ�5D�X�oMv8ҕ(���"S$e���V6`&�!��L���+*8� S%�Kd�����c�3=�;�������^� \/0�"�5�z;�B��\��H6ɛw��u4�sZ���#&�6n�ǿuˈ�`9�F�0���mA��v�H��m7���Z����[p>�Z*���XL̈cj"�r��r?;;IK���:���4��#�ݪD^y�8�,v���G�/���V���� ��\L�㐔"��N��Ѷ�����L �v�pF�].I^� s���E�ag�����`ҪǼ�K_��X�d`�����MN� #3Qy���i���=�����?M���[I�y�b�n�7��>`��\��D3�M��!�@{������T�',"�'�* t�K߀<O��=�i�l�F7���k��^����c�G���	]�[ܘ�����Ƥ��F�����w�DU�s(���m<8/�c�#�ݴ8>28�c &�V�{"� ��+�L��#��U�ܗ�����ޱR�&G_�P)ɫH�MVz_��#36�	�"y�co��k�80�#r֮��kr"<�9�``դ��z���l?y�k���w�z��6ɘ�WH�z\J�(�8�b��դ��)/`$�vt�L����EJ��* �����Ea�(0� � ���183���������'�N��yD�ua�h�� 6ҧ�qp�12��C�{jO���p #�����0��4o�����c���02��0у�0�wnH�'�t���B�7ݰ�T�"4�K�j�k<bYr��`�6��L~��hЋ���E�-ݶ�{�<1uw�ֽ���!D>Y����B"MJF���WpF"���6󴄸޸B���V���R���Yʔܮ�J؃��c=c]�3�ݳ�<
�2�4X��Y�����i<};7�}|�=FK���5�8��Dug��^�r�����#�飞3���Ãc�$�Lb�N���NX���_Ĵ �Be��z@&~`�?��{��ɰ�D91��p���!����c�iv�p��jZ �����(jm��Bh�Q�����RG��m"�S���^[����vi6Qx�@ʆe ���[tIh�JE�L���pc�129<��އͿ�ݎ�!��g>"`�-��N ����f&�;z��K"��G�s�AL�� ×"
0)���KO_��˃��̌�n��1ðI.	0��xv��PS�Т)g�D�"���H2+��:��R�)����>�7ҥSX=��F(I��jo���q;5�R��"zvk��_!�
?��
`X+רN �
֭^���3��'l��[Y�*Pݎ�]�U�PƧAL� �A�LSG/�ꝙ;��ƹ���q6_��-0
�C�Wjs	��3=�����U`i���HP��(�D�y1�Y�Ӽt����`�����/�ý�i�N02��0�$��3�K<��~���f���2W�/��9�Jaȴ�xl��L!����K������԰����̒_ x��ۓ�m��[!}qM�v}��0u����_ÿ���N��V���A!�����w����v�1�����|�{�����`�l;k��v����"K�(p�閭�0�����E7�U i:�����o����=3�`ǌ��2�=L�/mپ�\�/�ij<;�u����ij�4� �x���9�H3~�J��/�41C���0�J<d�c�2ԁzF[\C`�2���@n����'�8	#��#w�
o�@������-ڬaBB��ǽ܈�&��(�n�ᑽʤ���!	�N�9�N�f
������=P�%�σ�t:�[���f�s��˃����n�F�{p�0�5�STp�����i�ܭf��uI힩X�EJ!�vB$r�v��q-����S��Q�&g|�Q-ig�H���X���]7_ڛ�!$C#~ ��K����nmU����#1�#�A/�7����$q1|t����V�U;��}^�n�\��BL4���a7ȫ�	���U:!<�W1�+4ɋ �<��~�*�ߏ�!��T� 0���p��I��'�c�ߺ,��U��K�.L��0g�S6�̠��98p���a����M��=����f&`RN�ww��w�����G�3�͎2�����q-2��2b���$/f9j��|Z8!QY�^�.�NN���U�@�'��� ]n>�2��P��Ug���Nܺ��kdX�ߡ0�z��IL���zW�2���(��* T�*I� ���cn������E���\��PU��^3N�G��V��������i�i�i�@�k@�;^�!B���Hh}��ʂ�d
cb�c]�;���`�瑯�lC^�`�m�ۃ�M��R����>���{'�Ʃ&���A|����R8a4;� ��7D�����
s�2�"�j��͎�*Z��(&�LS{�	^��Ø�VET��ZZj �V[����G�j��k�ߊ]�B��ji:�}I��J�P���1���t#n�����*��B�K�v��1� pE;�!H�dy3QTxdO6�|�%��. ��b��kMR�Ԕc?��w ��@bJrN�`�XԎ]���.�L�9c@����Lpf�n�u6��À�a�̞&�g���1=�A�/#3mĂ$!͎�G�"�-cQ)uϼ��چ%�G�u!��,��s�6��˽6��K ɋGf��{���M3��v��$��.Ҙ޴֤�<�����c�����ۛ6%fE4�`Pj����ZYLfY�������Ac
c�彘�kf��g�m1��w�ցG���0M�A��$����M�f��H�G�:��ZhK�u�Z����jũʈ�:t��uP*6�r�=j��E�<�Y��-�����:���`����n���A��%��PI?F�#+�+�Z]�:sL��"Gd(��H%��E�j��NEnF�dK�"��^�Ղ>D�V7QZ��6uy�Bo���<*�[8�х�&��UpP���e<��a�py0�T�e戂��
��P�A�r��f����aB�&{���^(�����;�6��]�'�qҁ0�#���	c�_k���8<��e��hov<�#vAt�'{ֵKRET�����&�u��k}���!Q��9������k�w���"���AS�н83�s#��	A!͎ƈ�"�N��TvuQ�H���]�T�Uúu����^2Ә�N�i.B_��z͎.ǵ���;�kw�zh͎�Ҽɢ��-,>lA7&�\/�)r�,�N:`r��9=~A�9z���
�l�	+N-��与d��g��45�|ڑ��&;�TooG�D�KA�ÀC��':E�����G��Y�+���&xD���7�s"*�#jV%�d���f!F�E�RG�q�4u���
!o;88<8�sX�0<͎
��V�[Չ�"��
0��]���z��u]�ƛ�^�[bA�k-��	#�7���c��r�.�@���͗aA�5�$:�HM�Ы񱚼�h�+��<�$Ә�p�ƪ������
��J^��I(�]����� L7�D�i��m^�	�]Z��h \�|�C�/���Oƞ��M(<�7�@ �_i��@I� "�=��R���v�� L^#}ڵ�5�仚Zp
-SN�#8I Rj�e�����"�e��p��z�#02K��R�U$���1OX�mK4f��X�n0L�7��o�]pj�60�L�-����L��5(�PG��hS��*|-$�����*ܴ�����(n[;*:�@�A[��䚛b�P�Ӕ��qw�'�n����G����bcc�<���|�0���2`�/�97G����{E���&O�F�`l�"	�� h����8��7�f�|�p��&��QUIs6�2�jژ�O����=�gg(D�X�`ѻ��o�j��$p*�C|a/�9e��B���w]!�P�m*�%31��Т��*�'��V�WH�����Z�����x1��%oǲSDq+�`nf�i9O2��.B��#Nơ]����S�"N�45�e"n��]������N�Atv"�����E�S�e/`��b��b;��ǃ)����PC��F�E�˞��-*Ǜ��)x�R��fGv%���vjTI��!��C�Sb�,��i�z�٢�˵2�"��X̛��a/���2,���`����o+ ��sLZ%�/(����h'A�#|WQ*^���mI\�7�k����fξh�ݑ�h�$'Nĸ��G��G?�]��K����)|ے@ C�㺧!ҲbO�!v[lP��t����`�f�V��f�1�1C�N����}ۜ����_�H��^$X0�{>�V)i�'G����I 0;،�z����u������O���:l��gے!��?t|qۖ,�h���a0`\t���dg�
��Hv�jRQ�׹�k��ߚ�Ƕ�8Ӵ���Q<�Ǘ�b:`�/��$���L���%��lK�rпS�3n�,0����s��*h�ajG8�t��Y�N3f}l��gi�Zn6�l��754��2;4�J�	o�nO��;�� ��._�vqN4�g=�]���"�l�D�]�	*^�<k�9:`x����������c Z����l�u,�e��v�ֱ�!�u��V�����N�7^h����c����٭�#�0k���=kY��>F��'0���L�,Q��^(m���n(���^�=��������-�.`���!�b��[p��Fȯ'p�La�a�t���i)zٶjع��z۲��9�Z�^~�
+���
+���
+���
+d����5��G	    IEND�B`�                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             /////////////////////////////////////////////////////////////////
/// getID3() by James Heinrich <info@getid3.org>               //
//  available at http://getid3.sourceforge.net                 //
//            or http://www.getid3.org                         //
//          also https://github.com/JamesHeinrich/getID3       //
/////////////////////////////////////////////////////////////////

*****************************************************************
*****************************************************************

   getID3() is released under multiple licenses. You may choose
   from the following licenses, and use getID3 according to the
   terms of the license most suitable to your project.

GNU GPL: https://gnu.org/licenses/gpl.html                   (v3)
         https://gnu.org/licenses/old-licenses/gpl-2.0.html  (v2)
         https://gnu.org/licenses/old-licenses/gpl-1.0.html  (v1)

GNU LGPL: https://gnu.org/licenses/lgpl.html                 (v3)

Mozilla MPL: http://www.mozilla.org/MPL/2.0/                 (v2)

getID3 Commercial License: http://getid3.org/#gCL (payment required)

*****************************************************************
*****************************************************************
Copies of each of the above licenses are included in the 'licenses'
directory of the getID3 distribution.


       +---------------------------------------------+
       | If you want to donate, there is a link on   |
       | http://www.getid3.org for PayPal donations. |
       +---------------------------------------------+


Quick Start
===========================================================================

Q: How can I check that getID3() works on my server/files?
A: Unzip getID3() to a directory, then access /demos/demo.browse.php



Support
===========================================================================

Q: I have a question, or I found a bug. What do I do?
A: The preferred method of support requests and/or bug reports is the
   forum at http://support.getid3.org/



Sourceforge Notification
===========================================================================

It's highly recommended that you sign up for notification from
Sourceforge for when new versions are released. Please visit:
http://sourceforge.net/project/showfiles.php?group_id=55859
and click the little "monitor package" icon/link.  If you're
previously signed up for the mailing list, be aware that it has
been discontinued, only the automated Sourceforge notification
will be used from now on.



What does getID3() do?
===========================================================================

Reads & parses (to varying degrees):
 ¤ tags:
  * APE (v1 and v2)
  * ID3v1 (& ID3v1.1)
  * ID3v2 (v2.4, v2.3, v2.2)
  * Lyrics3 (v1 & v2)

 ¤ audio-lossy:
  * MP3/MP2/MP1
  * MPC / Musepack
  * Ogg (Vorbis, OggFLAC, Speex)
  * AAC / MP4
  * AC3
  * DTS
  * RealAudio
  * Speex
  * DSS
  * VQF

 ¤ audio-lossless:
  * AIFF
  * AU
  * Bonk
  * CD-audio (*.cda)
  * FLAC
  * LA (Lossless Audio)
  * LiteWave
  * LPAC
  * MIDI
  * Monkey's Audio
  * OptimFROG
  * RKAU
  * Shorten
  * TTA
  * VOC
  * WAV (RIFF)
  * WavPack

 ¤ audio-video:
  * ASF: ASF, Windows Media Audio (WMA), Windows Media Video (WMV)
  * AVI (RIFF)
  * Flash
  * Matroska (MKV)
  * MPEG-1 / MPEG-2
  * NSV (Nullsoft Streaming Video)
  * Quicktime (including MP4)
  * RealVideo

 ¤ still image:
  * BMP
  * GIF
  * JPEG
  * PNG
  * TIFF
  * SWF (Flash)
  * PhotoCD

 ¤ data:
  * ISO-9660 CD-ROM image (directory structure)
  * SZIP (limited support)
  * ZIP (directory structure)
  * TAR
  * CUE


Writes:
  * ID3v1 (& ID3v1.1)
  * ID3v2 (v2.3 & v2.4)
  * VorbisComment on OggVorbis
  * VorbisComment on FLAC (not OggFLAC)
  * APE v2
  * Lyrics3 (delete only)



Requirements
===========================================================================

* PHP 4.2.0 up to 5.2.x for getID3() 1.7.x (and earlier)
* PHP 5.0.5 (or higher) for getID3() 1.8.x (and up)
* PHP 5.0.5 (or higher) for getID3() 2.0.x (and up)
* at least 4MB memory for PHP. 8MB or more is highly recommended.
  12MB is required with all modules loaded.



Usage
===========================================================================

See /demos/demo.basic.php for a very basic use of getID3() with no
fancy output, just scanning one file.

See structure.txt for the returned data structure.

*>  For an example of a complete directory-browsing,       <*
*>  file-scanning implementation of getID3(), please run   <*
*>  /demos/demo.browse.php                                 <*

See /demos/demo.mysql.php for a sample recursive scanning code that
scans every file in a given directory, and all sub-directories, stores
the results in a database and allows various analysis / maintenance
operations

To analyze remote files over HTTP or FTP you need to copy the file
locally first before running getID3(). Your code would look something
like this:

// Copy remote file locally to scan with getID3()
$remotefilename = 'http://www.example.com/filename.mp3';
if ($fp_remote = fopen($remotefilename, 'rb')) {
    $localtempfilename = tempnam('/tmp', 'getID3');
    if ($fp_local = fopen($localtempfilename, 'wb')) {
        while ($buffer = fread($fp_remote, 8192)) {
            fwrite($fp_local, $buffer);
        }
        fclose($fp_local);

		// Initialize getID3 engine
		$getID3 = new getID3;

		$ThisFileInfo = $getID3->analyze($filename);

        // Delete temporary file
        unlink($localtempfilename);
    }
    fclose($fp_remote);
}


See /demos/demo.write.php for how to write tags.



What does the returned data structure look like?
===========================================================================

See structure.txt

It is recommended that you look at the output of
/demos/demo.browse.php scanning the file(s) you're interested in to
confirm what data is actually returned for any particular filetype in
general, and your files in particular, as the actual data returned
may vary considerably depending on what information is available in
the file itself.



Notes
===========================================================================

getID3() 1.x:
If the format parser encounters a critical problem, it will return
something in $fileinfo['error'], describing the encountered error. If
a less critical error or notice is generated it will appear in
$fileinfo['warning']. Both keys may contain more than one warning or
error. If something is returned in ['error'] then the file was not
correctly parsed and returned data may or may not be correct and/or
complete. If something is returned in ['warning'] (and not ['error'])
then the data that is returned is OK - usually getID3() is reporting
errors in the file that have been worked around due to known bugs in
other programs. Some warnings may indicate that the data that is
returned is OK but that some data could not be extracted due to
errors in the file.

getID3() 2.x:
See above except errors are thrown (so you will only get one error).



Disclaimer
===========================================================================

getID3() has been tested on many systems, on many types of files,
under many operating systems, and is generally believe to be stable
and safe. That being said, there is still the chance there is an
undiscovered and/or unfixed bug that may potentially corrupt your
file, especially within the writing functions. By using getID3() you
agree that it's not my fault if any of your files are corrupted.
In fact, I'm not liable for anything :)



License
===========================================================================

GNU General Public License - see license.txt

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to:
Free Software Foundation, Inc.
59 Temple Place - Suite 330
Boston, MA  02111-1307, USA.

FAQ:
Q: Can I use getID3() in my program? Do I need a commercial license?
A: You're generally free to use getID3 however you see fit. The only
   case in which you would require a commercial license is if you're
   selling your closed-source program that integrates getID3. If you
   sell your program including a copy of getID3, that's fine as long
   as you include a copy of the sourcecode when you sell it.  Or you
   can distribute your code without getID3 and say "download it from
   getid3.sourceforge.net"



Why is it called "getID3()" if it does so much more than just that?
===========================================================================

v0.1 did in fact just do that. I don't have a copy of code that old, but I
could essentially write it today with a one-line function:
  function getID3($filename) { return unpack('a3TAG/a30title/a30artist/a30album/a4year/a28comment/c1track/c1genreid', substr(file_get_contents($filename), -128)); }


Future Plans
===========================================================================
http://www.getid3.org/phpBB3/viewforum.php?f=7

* Better support for MP4 container format
* Scan for appended ID3v2 tag at end of file per ID3v2.4 specs (Section 5.0)
* Support for JPEG-2000 (http://www.morgan-multimedia.com/jpeg2000_overview.htm)
* Support for MOD (mod/stm/s3m/it/xm/mtm/ult/669)
* Support for ACE (thanks Vince)
* Support for Ogg other than Vorbis, Speex and OggFlac (ie. Ogg+Xvid)
* Ability to create Xing/LAME VBR header for VBR MP3s that are missing VBR header
* Ability to "clean" ID3v2 padding (replace invalid padding with valid padding)
* Warn if MP3s change version mid-stream (in full-scan mode)
* check for corrupt/broken mid-file MP3 streams in histogram scan
* Support for lossless-compression formats
  (http://www.firstpr.com.au/audiocomp/lossless/#Links)
  (http://compression.ca/act-sound.html)
  (http://web.inter.nl.net/users/hvdh/lossless/lossless.htm)
* Support for RIFF-INFO chunks
  * http://lotto.st-andrews.ac.uk/~njh/tag_interchange.html
    (thanks Nick Humfrey <njhØsurgeradio*co*uk>)
  * http://abcavi.narod.ru/sof/abcavi/infotags.htm
    (thanks Kibi)
* Better support for Bink video
* http://www.hr/josip/DSP/AudioFile2.html
* http://www.pcisys.net/~melanson/codecs/
* Detect mp3PRO
* Support for PSD
* Support for JPC
* Support for JP2
* Support for JPX
* Support for JB2
* Support for IFF
* Support for ICO
* Support for ANI
* Support for EXE (comments, author, etc) (thanks p*quaedackersØplanet*nl)
* Support for DVD-IFO (region, subtitles, aspect ratio, etc)
  (thanks p*quaedackersØplanet*nl)
* More complete support for SWF - parsing encapsulated MP3 and/or JPEG content
    (thanks n8n8Øyahoo*com)
* Support for a2b
* Optional scan-through-frames for AVI verification
  (thanks rockcohenØmassive-interactive*nl)
* Support for TTF (thanks infoØbutterflyx*com)
* Support for DSS (http://www.getid3.org/phpBB3/viewtopic.php?t=171)
* Support for SMAF (http://smaf-yamaha.com/what/demo.html)
  http://www.getid3.org/phpBB3/viewtopic.php?t=182
* Support for AMR (http://www.getid3.org/phpBB3/viewtopic.php?t=195)
* Support for 3gpp (http://www.getid3.org/phpBB3/viewtopic.php?t=195)
* Support for ID4 (http://www.wackysoft.cjb.net grizlyY2KØhotmail*com)
* Parse XML data returned in Ogg comments
* Parse XML data from Quicktime SMIL metafiles (klausrathØmac*com)
* ID3v2 genre string creator function
* More complete parsing of JPG
* Support for all old-style ASF packets
* ASF/WMA/WMV tag writing
* Parse declared T??? ID3v2 text information frames, where appropriate
    (thanks Christian Fritz for the idea)
* Recognize encoder:
  http://www.guerillasoft.com/EncSpot2/index.html
  http://ff123.net/identify.html
  http://www.hydrogenaudio.org/?act=ST&f=16&t=9414
  http://www.hydrogenaudio.org/?showtopic=11785
* Support for other OS/2 bitmap structures: Bitmap Array('BA'),
  Color Icon('CI'), Color Pointer('CP'), Icon('IC'), Pointer ('PT')
  http://netghost.narod.ru/gff/graphics/summary/os2bmp.htm
* Support for WavPack RAW mode
* ASF/WMA/WMV data packet parsing
* ID3v2FrameFlagsLookupTagAlter()
* ID3v2FrameFlagsLookupFileAlter()
* obey ID3v2 tag alter/preserve/discard rules
* http://www.geocities.com/SiliconValley/Sector/9654/Softdoc/Illyrium/Aolyr.htm
* proper checking for LINK/LNK frame validity in ID3v2 writing
* proper checking for ASPI-TLEN frame validity in ID3v2 writing
* proper checking for COMR frame validity in ID3v2 writing
* http://www.geocities.co.jp/SiliconValley-Oakland/3664/index.html
* decode GEOB ID3v2 structure as encoded by RealJukebox,
  decode NCON ID3v2 structure as encoded by MusicMatch
  (probably won't happen - the formats are proprietary)



Known Bugs/Issues in getID3() that may be fixed eventually
===========================================================================
http://www.getid3.org/phpBB3/viewtopic.php?t=25

* Cannot determine bitrate for MPEG video with VBR video data
  (need documentation)
* Interlace/progressive cannot be determined for MPEG video
  (need documentation)
* MIDI playtime is sometimes inaccurate
* AAC-RAW mode files cannot be identified
* WavPack-RAW mode files cannot be identified
* mp4 files report lots of "Unknown QuickTime atom type"
   (need documentation)
* Encrypted ASF/WMA/WMV files warn about "unhandled GUID
  ASF_Content_Encryption_Object"
* Bitrate split between audio and video cannot be calculated for
  NSV, only the total bitrate. (need documentation)
* All Ogg formats (Vorbis, OggFLAC, Speex) are affected by the
  problem of large VorbisComments spanning multiple Ogg pages, but
  but only OggVorbis files can be processed with vorbiscomment.
* The version of "head" supplied with Mac OS 10.2.8 (maybe other
  versions too) does only understands a single option (-n) and
  therefore fails. getID3 ignores this and returns wrong md5_data.



Known Bugs/Issues in getID3() that cannot be fixed
--------------------------------------------------
http://www.getid3.org/phpBB3/viewtopic.php?t=25

* 32-bit PHP installations only:
  Files larger than 2GB cannot always be parsed fully by getID3()
  due to limitations in the 32-bit PHP filesystem functions.
  NOTE: Since v1.7.8b3 there is partial support for larger-than-
  2GB files, most of which will parse OK, as long as no critical
  data is located beyond the 2GB offset.
  Known will-work:
  * all file formats on 64-bit PHP
  * ZIP  (format doesn't support files >2GB)
  * FLAC (current encoders don't support files >2GB)
  Known will-not-work:
  * ID3v1 tags (always located at end-of-file)
  * Lyrics3 tags (always located at end-of-file)
  * APE tags (always located at end-of-file)
  Maybe-will-work:
  * Quicktime (will work if needed metadata is before 2GB offset,
    that is if the file has been hinted/optimized for streaming)
  * RIFF.WAV (should work fine, but gives warnings about not being
    able to parse all chunks)
  * RIFF.AVI (playtime will probably be wrong, is only based on
    "movi" chunk that fits in the first 2GB, should issue error
    to show that playtime is incorrect. Other data should be mostly
    correct, assuming that data is constant throughout the file)
* PHP <= v5 on Windows cannot read UTF-8 filenames


Known Bugs/Issues in other programs
-----------------------------------
http://www.getid3.org/phpBB3/viewtopic.php?t=25

* Windows Media Player (up to v11) and iTunes (up to v10+) do
    not correctly handle ID3v2.3 tags with UTF-16BE+BOM
    encoding (they assume the data is UTF-16LE+BOM and either
    crash (WMP) or output Asian character set (iTunes)
* Winamp (up to v2.80 at least) does not support ID3v2.4 tags,
    only ID3v2.3
    see: http://forums.winamp.com/showthread.php?postid=387524
* Some versions of Helium2 (www.helium2.com) do not write
    ID3v2.4-compliant Frame Sizes, even though the tag is marked
    as ID3v2.4)  (detected by getID3())
* MP3ext V3.3.17 places a non-compliant padding string at the end
    of the ID3v2 header. This is supposedly fixed in v3.4b21 but
    only if you manually add a registry key. This fix is not yet
    confirmed.  (detected by getID3())
* CDex v1.40 (fixed by v1.50b7) writes non-compliant Ogg comment
    strings, supposed to be in the format "NAME=value" but actually
    written just "value"  (detected by getID3())
* Oggenc 0.9-rc3 flags the encoded file as ABR whether it's
    actually ABR or VBR.
* iTunes (versions "X v2.0.3", "v3.0.1" are known-guilty, probably
    other versions are too) writes ID3v2.3 comment tags using a
    frame name 'COM ' which is not valid for ID3v2.3+ (it's an
    ID3v2.2-style frame name)  (detected by getID3())
* MP2enc does not encode mono CBR MP2 files properly (half speed
    sound and double playtime)
* MP2enc does not encode mono VBR MP2 files properly (actually
    encoded as stereo)
* tooLAME does not encode mono VBR MP2 files properly (actually
    encoded as stereo)
* AACenc encodes files in VBR mode (actually ABR) even if CBR is
   spe