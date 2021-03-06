sel.text + t.tagStart;
				} else {
					sel.text = t.tagStart + sel.text + endTag;
				}
			} else {
				if ( !t.tagEnd ) {
					sel.text = t.tagStart;
				} else if ( t.isOpen(ed) === false ) {
					sel.text = t.tagStart;
					t.openTag(element, ed);
				} else {
					sel.text = endTag;
					t.closeTag(element, ed);
				}
			}
			canvas.focus();
		} else if ( canvas.selectionStart || canvas.selectionStart === 0 ) { // FF, WebKit, Opera
			startPos = canvas.selectionStart;
			endPos = canvas.selectionEnd;
			cursorPos = endPos;
			scrollTop = canvas.scrollTop;
			l = v.substring(0, startPos); // left of the selection
			r = v.substring(endPos, v.length); // right of the selection
			i = v.substring(startPos, endPos); // inside the selection
			if ( startPos !== endPos ) {
				if ( !t.tagEnd ) {
					canvas.value = l + i + t.tagStart + r; // insert self closing tags after the selection
					cursorPos += t.tagStart.length;
				} else {
					canvas.value = l + t.tagStart + i + endTag + r;
					cursorPos += t.tagStart.length + endTag.length;
				}
			} else {
				if ( !t.tagEnd ) {
					canvas.value = l + t.tagStart + r;
					cursorPos = startPos + t.tagStart.length;
				} else if ( t.isOpen(ed) === false ) {
					canvas.value = l + t.tagStart + r;
					t.openTag(element, ed);
					cursorPos = startPos + t.tagStart.length;
				} else {
					canvas.value = l + endTag + r;
					cursorPos = startPos + endTag.length;
					t.closeTag(element, ed);
				}
			}

			canvas.selectionStart = cursorPos;
			canvas.selectionEnd = cursorPos;
			canvas.scrollTop = scrollTop;
			canvas.focus();
		} else { // other browsers?
			if ( !endTag ) {
				canvas.value += t.tagStart;
			} else if ( t.isOpen(ed) !== false ) {
				canvas.value += t.tagStart;
				t.openTag(element, ed);
			} else {
				canvas.value += endTag;
				t.closeTag(element, ed);
			}
			canvas.focus();
		}
	};

	// removed
	qt.SpellButton = function() {};

	// the close tags button
	qt.CloseButton = function() {
		qt.Button.call( this, 'close', quicktagsL10n.closeTags, '', quicktagsL10n.closeAllOpenTags );
	};

	qt.CloseButton.prototype = new qt.Button();

	qt._close = function(e, c, ed) {
		var button, element, tbo = ed.openTags;

		if ( tbo ) {
			while ( tbo.length > 0 ) {
				button = ed.getButton(tbo[tbo.length - 1]);
				element = document.getElementById(ed.name + '_' + button.id);

				if ( e ) {
					button.callback.call(button, element, c, ed);
				} else {
					button.closeTag(element, ed);
				}
			}
		}
	};

	qt.CloseButton.prototype.callback = qt._close;

	qt.closeAllTags = function(editor_id) {
		var ed = this.getInstance(editor_id);
		qt._close('', ed.canvas, ed);
	};

	// the link button
	qt.LinkButton = function() {
		var attr = {
			ariaLabel: quicktagsL10n.link
		};

		qt.TagButton.call( this, 'link', 'link', '', '</a>', '', '', '', attr );
	};
	qt.LinkButton.prototype = new qt.TagButton();
	qt.LinkButton.prototype.callback = function(e, c, ed, defaultValue) {
		var URL, t = this;

		if ( typeof wpLink !== 'undefined' ) {
			wpLink.open( ed.id );
			return;
		}

		if ( ! defaultValue ) {
			defaultValue = 'http://';
		}

		if ( t.isOpen(ed) === false ) {
			URL = prompt( quicktagsL10n.enterURL, defaultValue );
			if ( URL ) {
				t.tagStart = '<a href="' + URL + '">';
				qt.TagButton.prototype.callback.call(t, e, c, ed);
			}
		} else {
			qt.TagButton.prototype.callback.call(t, e, c, ed);
		}
	};

	// the img button
	qt.ImgButton = function() {
		var attr = {
			ariaLabel: quicktagsL10n.image
		};

		qt.TagButton.call( this, 'img', 'img', '', '', '', '', '', attr );
	};
	qt.ImgButton.prototype = new qt.TagButton();
	qt.ImgButton.prototype.callback = function(e, c, ed, defaultValue) {
		if ( ! defaultValue ) {
			defaultValue = 'http://';
		}
		var src = prompt(quicktagsL10n.enterImageURL, defaultValue), alt;
		if ( src ) {
			alt = prompt(quicktagsL10n.enterImageDescription, '');
			this.tagStart = '<img src="' + src + '" alt="' + alt + '" />';
			qt.TagButton.prototype.callback.call(this, e, c, ed);
		}
	};

	qt.DFWButton = function() {
		qt.Button.call( this, 'dfw', '', 'f', quicktagsL10n.dfw );
	};
	qt.DFWButton.prototype = new qt.Button();
	qt.DFWButton.prototype.callback = function() {
		var wp;

		if ( ! ( wp = window.wp ) || ! wp.editor || ! wp.editor.dfw ) {
			return;
		}

		window.wp.editor.dfw.toggle();
	};

	qt.TextDirectionButton = function() {
		qt.Button.call( this, 'textdirection', quicktagsL10n.textdirection, '', quicktagsL10n.toggleTextdirection );
	};
	qt.TextDirectionButton.prototype = new qt.Button();
	qt.TextDirectionButton.prototype.callback = function(e, c) {
		var isRTL = ( 'rtl' === document.getElementsByTagName('html')[0].dir ),
			currentDirection = c.style.direction;

		if ( ! currentDirection ) {
			currentDirection = ( isRTL ) ? 'rtl' : 'ltr';
		}

		c.style.direction = ( 'rtl' === currentDirection ) ? 'ltr' : 'rtl';
		c.focus();
	};

	// ensure backward compatibility
	edButtons[10]  = new qt.TagButton( 'strong', 'b', '<strong>', '</strong>', '', '', '', { ariaLabel: quicktagsL10n.strong, ariaLabelClose: quicktagsL10n.strongClose } );
	edButtons[20]  = new qt.TagButton( 'em', 'i', '<em>', '</em>', '', '', '', { ariaLabel: quicktagsL10n.em, ariaLabelClose: quicktagsL10n.emClose } );
	edButtons[30]  = new qt.LinkButton(); // special case
	edButtons[40]  = new qt.TagButton( 'block', 'b-quote', '\n\n<blockquote>', '</blockquote>\n\n', '', '', '', { ariaLabel: quicktagsL10n.blockquote, ariaLabelClose: quicktagsL10n.blockquoteClose } );
	edButtons[50]  = new qt.TagButton( 'del', 'del', '<del datetime="' + _datetime + '">', '</del>', '', '', '', { ariaLabel: quicktagsL10n.del, ariaLabelClose: quicktagsL10n.delClose } );
	edButtons[60]  = new qt.TagButton( 'ins', 'ins', '<ins datetime="' + _datetime + '">', '</ins>', '', '', '', { ariaLabel: quicktagsL10n.ins, ariaLabelClose: quicktagsL10n.insClose } );
	edButtons[70]  = new qt.ImgButton(); // special case
	edButtons[80]  = new qt.TagButton( 'ul', 'ul', '<ul>\n', '</ul>\n\n', '', '', '', { ariaLabel: quicktagsL10n.ul, ariaLabelClose: quicktagsL10n.ulClose } );
	edButtons[90]  = new qt.TagButton( 'ol', 'ol', '<ol>\n', '</ol>\n\n', '', '', '', { ariaLabel: quicktagsL10n.ol, ariaLabelClose: quicktagsL10n.olClose } );
	edButtons[100] = new qt.TagButton( 'li', 'li', '\t<li>', '</li>\n', '', '', '', { ariaLabel: quicktagsL10n.li, ariaLabelClose: quicktagsL10n.liClose } );
	edButtons[110] = new qt.TagButton( 'code', 'code', '<code>', '</code>', '', '', '', { ariaLabel: quicktagsL10n.code, ariaLabelClose: quicktagsL10n.codeClose } );
	edButtons[120] = new qt.TagButton( 'more', 'more', '<!--more-->\n\n', '', '', '', '', { ariaLabel: quicktagsL10n.more } );
	edButtons[140] = new qt.CloseButton();

})();
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         !function a(b,c,d){function e(g,h){if(!c[g]){if(!b[g]){var i="function"==typeof require&&require;if(!h&&i)return i(g,!0);if(f)return f(g,!0);var j=new Error("Cannot find module '"+g+"'");throw j.code="MODULE_NOT_FOUND",j}var k=c[g]={exports:{}};b[g][0].call(k.exports,function(a){var c=b[g][1][a];return e(c?c:a)},k,k.exports,a,b,c,d)}return c[g].exports}for(var f="function"==typeof require&&require,g=0;g<d.length;g++)e(d[g]);return e}({1:[function(a,b,c){var d,e,f,g,h=jQuery;window.wp=window.wp||{},g=wp.media=function(a){var b,c=g.view.MediaFrame;if(c)return a=_.defaults(a||{},{frame:"select"}),"select"===a.frame&&c.Select?b=new c.Select(a):"post"===a.frame&&c.Post?b=new c.Post(a):"manage"===a.frame&&c.Manage?b=new c.Manage(a):"image"===a.frame&&c.ImageDetails?b=new c.ImageDetails(a):"audio"===a.frame&&c.AudioDetails?b=new c.AudioDetails(a):"video"===a.frame&&c.VideoDetails?b=new c.VideoDetails(a):"edit-attachments"===a.frame&&c.EditAttachments&&(b=new c.EditAttachments(a)),delete a.frame,g.frame=b,b},_.extend(g,{model:{},view:{},controller:{},frames:{}}),f=g.model.l10n=window._wpMediaModelsL10n||{},g.model.settings=f.settings||{},delete f.settings,d=g.model.Attachment=a("./models/attachment.js"),e=g.model.Attachments=a("./models/attachments.js"),g.model.Query=a("./models/query.js"),g.model.PostImage=a("./models/post-image.js"),g.model.Selection=a("./models/selection.js"),g.compare=function(a,b,c,d){return _.isEqual(a,b)?c===d?0:c>d?-1:1:a>b?-1:1},_.extend(g,{template:wp.template,post:wp.ajax.post,ajax:wp.ajax.send,fit:function(a){var b,c=a.width,d=a.height,e=a.maxWidth,f=a.maxHeight;return _.isUndefined(e)||_.isUndefined(f)?_.isUndefined(f)?b="width":_.isUndefined(e)&&d>f&&(b="height"):b=c