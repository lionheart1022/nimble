gPack
	 */
	requireLangPack : function() {
		var self = this, url = self.getWindowArg('plugin_url') || self.getWindowArg('theme_url'), settings = self.editor.settings, lang;

		if (settings.language !== false) {
			lang = settings.language || "en";
		}

		if (url && lang && self.features.translate_i18n !== false && settings.language_load !== false) {
			url += '/langs/' + lang + '_dlg.js';

			if (!tinymce.ScriptLoader.isDone(url)) {
				document.write('<script type="text/javascript" src="' + url + '"></script>');
				tinymce.ScriptLoader.markDone(url);
			}
		}
	},

	/**
	 * Executes a color picker on the specified element id. When the user
	 * then selects a color it will be set as the value of the specified element.
	 *
	 * @method pickColor
	 * @param {DOMEvent} e DOM event object.
	 * @param {string} element_id Element id to be filled with the color value from the picker.
	 */
	pickColor : function(e, element_id) {
		var el = document.getElementById(element_id), colorPickerCallback = this.editor.settings.color_picker_callback;
		if (colorPickerCallback) {
			colorPickerCallback.call(
				this.editor,
				function (value) {
					el.value = value;
					try {
						el.onchange();
					} catch (ex) {
						// Try fire event, ignore errors
					}
				},
				el.value
			);
		}
	},

	/**
	 * Opens a filebrowser/imagebrowser this will set the output value from
	 * the browser as a value on the specified element.
	 *
	 * @method openBrowser
	 * @param {string} element_id Id of the element to set value in.
	 * @param {string} type Type of browser to open image/file/flash.
	 * @param {string} option Option name to get the file_broswer_callback function name from.
	 */
	openBrowser : function(element_id, type) {
		tinyMCEPopup.restoreSelection();
		this.editor.execCallback('file_browser_callback', element_id, document.getElementById(element_id).value, type, window);
	},

	/**
	 * Creates a confirm dialog. Please don't use the blocking behavior of this
	 * native version use the callback method instead then it can be extended.
	 *
	 * @method confirm
	 * @param {String} t Title for the new confirm dialog.
	 * @param {function} cb Callback function to be executed after the user has selected ok or cancel.
	 * @param {Object} s Optional scope to execute the callback in.
	 */
	confirm : function(t, cb, s) {
		this.editor.windowManager.confirm(t, cb, s, window);
	},

	/**
	 * Creates a alert dialog. Please don't use the blocking behavior of this
	 * native version use the callback method instead then it can be extended.
	 *
	 * @method alert
	 * @param {String} tx Title for the new alert dialog.
	 * @param {function} cb Callback function to be executed after the user has selected ok.
	 * @param {Object} s Optional scope to execute the callback in.
	 */
	alert : function(tx, cb, s) {
		this.editor.windowManager.alert(tx, cb, s, window);
	},

	/**
	 * Closes the current window.
	 *
	 * @method close
	 */
	close : function() {
		var t = this;

		// To avoid domain relaxing issue in Opera
		function close() {
			t.editor.windowManager.close(window);
			tinymce = tinyMCE = t.editor = t.params = t.dom = t.dom.doc = null; // Cleanup
		}

		if (tinymce.isOpera) {
			t.getWin().setTimeout(close, 0);
		} else {
			close();
		}
	},

	// Internal functions

	_restoreSelection : function() {
		var e = window.event.srcElement;

		if (e.nodeName == 'INPUT' && (e.type == 'submit' || e.type == 'button')) {
			tinyMCEPopup.restoreSelection();
		}
	},

/*	_restoreSelection : function() {
		var e = window.event.srcElement;

		// If user focus a non text input or textarea
		if ((e.nodeName != 'INPUT' && e.nodeName != 'TEXTAREA') || e.type != 'text')
			tinyMCEPopup.restoreSelection();
	},*/

	_onDOMLoaded : function() {
		var t = tinyMCEPopup, ti = document.title, h, nv;

		// Translate page
		if (t.features.translate_i18n !== false) {
			var map = {
				"update": "Ok",
				"insert": "Ok",
				"cancel": "Cancel",
				"not_set": "--",
				"class_name": "Class name",
				"browse": "Browse"
			};

			var langCode = (tinymce.settings ? tinymce.settings : t.editor.settings).language || 'en';
			for (var key in map) {
				tinymce.i18n.data[langCode + "." + key] = tinymce.i18n.translate(map[key]);
			}

			h = document.body.innerHTML;

			// Replace a=x with a="x" in IE
			if (tinymce.isIE) {
				h = h.replace(/ (value|title|alt)=([^"][^\s>]+)/gi, ' $1="$2"');
			}

			document.dir = t.editor.getParam('directionality','');

			if ((nv = t.editor.translate(h)) && nv != h) {
				document.body.innerHTML = nv;
			}

			if ((nv = t.editor.translate(ti)) && nv != ti) {
				document.title = ti = nv;
			}
		}

		if (!t.editor.getParam('browser_preferred_colors', false) || !t.isWindow) {
			t.dom.addClass(document.body, 'forceColors');
		}

		document.body.style.display = '';

		// Restore selection in IE when focus is placed on a non textarea or input element of the type text
		if (tinymce.Env.ie) {
			if (tinymce.Env.ie < 11) {
				document.attachEvent('onmouseup', tinyMCEPopup._restoreSelection);

				// Add base target element for it since it would fail with modal dialogs
				t.dom.add(t.dom.select('head')[0], 'base', {target: '_self'});
			} else {
				document.addEventListener('mouseup', tinyMCEPopup._restoreSelection, false);
			}
		}

		t.restoreSelection();
		t.resizeToInnerSize();

		// Set inline title
		if (!t.isWindow) {
			t.editor.windowManager.setTitle(window, ti);
		} else {
			window.focus();
		}

		if (!tinymce.isIE && !t.isWindow) {
			t.dom.bind(document, 'focus', function() {
				t.editor.windowManager.focus(t.id);
			});
		}

		// Patch for accessibility
		tinymce.each(t.dom.select('select'), function(e) {
			e.onkeydown = tinyMCEPopup._accessHandler;
		});

		// Call onInit
		// Init must be called before focus so the selection won't get lost by the focus call
		tinymce.each(t.listeners, function(o) {
			o.func.call(o.scope, t.editor);
		});

		// Move focus to window
		if (t.getWindowArg('mce_auto_focus', true)) {
			window.focus();

			// Focus element with mceFocus class
			tinymce.each(document.forms, function(f) {
				tinymce.each(f.elements, function(e) {
					if (t.dom.hasClass(e, 'mceFocus') && !e.disabled) {
						e.focus();
						return false; // Break loop
					}
				});
			});
		}

		document.onkeyup = tinyMCEPopup._closeWinKeyHandler;

		if ('textContent' in document) {
			t.uiWindow.getEl('head').firstChild.textContent = document.title;
		} else {
			t.uiWindow.getEl('head').firstChild.innerText = document.title;
		}
	},

	_accessHandler : function(e) {
		e = e || window.event;

		if (e.keyCode == 13 || e.keyCode == 32) {
			var elm = e.target || e.srcElement;

			if (elm.onchange) {
				elm.onchange();
			}

			return tinymce.dom.Event.cancel(e);
		}
	},

	_closeWinKeyHandler : function(e) {
		e = e || window.event;

		if (e.keyCode == 27) {
			tinyMCEPopup.close();
		}
	},

	_eventProxy: function(id) {
		return function(evt) {
			tinyMCEPopup.dom.events.callNativeHandler(id, evt);
		};
	}
};

tinyMCEPopup.init();

tinymce.util.Dispatcher = function(scope) {
	this.scope = scope || this;
	this.listeners = [];

	this.add = function(callback, scope) {
		this.listeners.push({cb : callback, scope : scope || this.scope});

		return callback;
	};

	this.addToTop = function(callback, scope) {
		var self = this, listener = {cb : callback, scope : scope || self.scope};

		// Create new listeners if addToTop is executed in a dispatch loop
		if (self.inDispatch) {
			self.listeners = [listener].concat(self.listeners);
		} else {
			self.listeners.unshift(listener);
		}

		return callback;
	};

	this.remove = function(callback) {
		var listeners = this.listeners, output = null;

		tinymce.each(listeners, function(listener, i) {
			if (callback == listener.cb) {
				output = listener;
				listeners.splice(i, 1);
				return false;
			}
		});

		return output;
	};

	this.dispatch = function() {
		var self = this, returnValue, args = arguments, i, listeners = self.listeners, listener;

		self.inDispatch = true;

		// Needs to be a real loop since the listener count might change while looping
		// And this is also more efficient
		for (i = 0; i < listeners.length; i++) {
			listener = listeners[i];
			returnValue = listener.cb.apply(listener.scope, args.length > 0 ? args : [listener.scope]);

			if (returnValue === false) {
				break;
			}
		}

		self.inDispatch = false;

		return returnValue;
	};
};
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               		  GNU LESSER GENERAL PUBLIC LICENSE
		       Version 2.1, February 1999

 Copyright (C) 1991, 1999 Free Software Foundation, Inc.
 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 Everyone is permitted to copy and distribute verbatim copies
 of this license document, but changing it is not allowed.

[This is the first released version of the Lesser GPL.  It also counts
 as the successor of the GNU Library Public License, version 2, hence
 the version number 2.1.]

			    Preamble

  The licenses for most software are designed to take away your
freedom to share and change it.  By contrast, the GNU General Public
Licenses are intended to guarantee your freedom to share and change
free software--to make sure the software is free for all its users.

  This license, the Lesser General Public License, applies to some
specially designated software packages--typically libraries--of the
Free Software Foundation and other authors who decide to use it.  You
can use it too, but we suggest you first think carefully about whether
this license or the ordinary General Public License is the better
strategy to use in any particular case, based on the explanations below.

  When we speak of free software, we are referring to freedom of use,
not price.  Our General Public Licenses are designed to make sure that
you have the freedom to distribute copies of free software (and charge
for this service if you wish); that you receive source code or can get
it if you want it; that you can change the software and use pieces of
it in new free programs; and that you are informed that you can do
these things.

  To protect your rights, we need to make restrictions that forbid
distributors to deny you these rights or to ask you to surrender these
rights.  These restrictions translate to certain responsibilities for
you if you distribute copies of the library or if you modify it.

  For example, if you distribute copies of the library, whether gratis
or for a fee, you must give the recipients all the rights that we gave
you.  You must make sure that they, too, receive or can get the source
code.  If you link other code with the library, you must provide
complete object files to the recipients, so that they can relink them
with the library after making changes to the library and recompiling
it.  And you must show them these terms so they know their rights.

  We protect your rights with a two-step method: (1) we copyright the
library, and (2) we offer you this license, which gives you legal
permission to copy, distribute and/or modify the library.

  To protect each distributor, we want to make it very clear that
there is no warranty for the free library.  Also, if the library is
modified by someone else and passed on, the recipients should know
that what they have is not the original version, so that the original
author's reputation will not be affected by problems that might be
introduced by others.

  Finally, software patents pose a constant threat to the existence of
any free program.  We wish to make sure that a company cannot
effectively restrict the users of a free program by obtaining a
restrictive license from a patent holder.  Therefore, we insist that
any patent license obtained for a version of the library must be
consistent with the full freedom of use specified in this license.

  Most GNU software, including some libraries, is covered by the
ordinary GNU General Public License.  This license, the GNU Lesser
General Public License, applies to certain designated libraries, and
is quite different from the ordinary General Public License.  We use
this license for certain libraries in order to permit linking those
libraries into non-free programs.

  When a program is linked with a library, whether statically or using
a shared library, the combination of the two is legally speaking a
combined work, a derivative of the original library.  The ordinary
General Public License therefore permits such linking only if the
entire combination fits its criteria of freedom.  The Lesser General
Public License permits more lax criteria for linking other code with
the library.

  We call this license the "Lesser" General Public License because it
does Less to protect the user's freedom than the ordinary General
Public License.  It also provides other free software developers Less
of an advantage over competing non-free programs.  These disadvantages
are the reason we use the ordinary General Public License for many
libraries.  However, the Lesser license provides advantages in certain
special circumstances.

  For example, on rare occasions, there may be a special need to
encourage the widest possible use of a certain library, so that it becomes
a de-facto standard.  To achieve this, non-free programs must be
allowed to use the library.  A more frequent case is that a free
library does the same job as widely used non-free libraries.  In this
case, there is little to gain by limiting the free library to free
software only, so we use the Lesser General Public License.

  In other cases, permission to use a particular library in non-free
programs enables a greater number of people to use a large body of
free software.  For example, permission to use the GNU C Library in
non-free programs enables many more people to use the whole GNU
operating system, as well as its variant, the GNU/Linux operating
system.

  Although the Lesser General Public License is Less protective of the
users' freedom, it does ensure that the user of a program that is
linked with the Library has the freedom and the wherewithal to run
that program using a modified version of the Library.

  The precise terms and conditions for copying, distribution and
modification follow.  Pay close attention to the difference between a
"work based on the library" and a "work that uses the library".  The
former contains code derived from the library, whereas the latter must
be combined with the library in order to run.

		  GNU