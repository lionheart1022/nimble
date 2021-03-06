
		if ( button.hasClass('disabled') ) {
			return;
		}

		elem.val(pop);
		t.refreshEditor(postid, nonce, function() {
			t.setDisabled($('#image-undo-' + postid), true);
			t.setDisabled(button, pop > 0);
			// When redo gets disabled, move focus to the undo button to avoid a focus loss.
			if ( 0 === pop ) {
				$( '#image-undo-' + postid ).focus();
			}
		});
	},

	setNumSelection : function(postid) {
		var sel, elX = $('#imgedit-sel-width-' + postid), elY = $('#imgedit-sel-height-' + postid),
			x = this.intval( elX.val() ), y = this.intval( elY.val() ),
			img = $('#image-preview-' + postid), imgh = img.height(), imgw = img.width(),
			sizer = this.hold.sizer, x1, y1, x2, y2, ias = this.iasapi;

		if ( x < 1 ) {
			elX.val('');
			return false;
		}

		if ( y < 1 ) {
			elY.val('');
			return false;
		}

		if ( x && y && ( sel = ias.getSelection() ) ) {
			x2 = sel.x1 + Math.round( x * sizer );
			y2 = sel.y1 + Math.round( y * sizer );
			x1 = sel.x1;
			y1 = sel.y1;

			if ( x2 > imgw ) {
				x1 = 0;
				x2 = imgw;
				elX.val( Math.round( x2 / sizer ) );
			}

			if ( y2 > imgh ) {
				y1 = 0;
				y2 = imgh;
				elY.val( Math.round( y2 / sizer ) );
			}

			ias.setSelection( x1, y1, x2, y2 );
			ias.update();
			this.setCropSelection(postid, ias.getSelection());
		}
	},

	round : function(num) {
		var s;
		num = Math.round(num);

		if ( this.hold.sizer > 0.6 ) {
			return num;
		}

		s = num.toString().slice(-1);

		if ( '1' === s ) {
			return num - 1;
		} else if ( '9' === s ) {
			return num + 1;
		}

		return num;
	},

	setRatioSelection : function(postid, n, el) {
		var sel, r, x = this.intval( $('#imgedit-crop-width-' + postid).val() ),
			y = this.intval( $('#imgedit-crop-height-' + postid).val() ),
			h = $('#image-preview-' + postid).height();

		if ( !this.intval( $(el).val() ) ) {
			$(el).val('');
			return;
		}

		if ( x && y ) {
			this.iasapi.setOptions({
				aspectRatio: x + ':' + y
			});

			if ( sel = this.iasapi.getSelection(true) ) {
				r = Math.ceil( sel.y1 + ( ( sel.x2 - sel.x1 ) / ( x / y ) ) );

				if ( r > h ) {
					r = h;
					if ( n ) {
						$('#imgedit-crop-height-' + postid).val('');
					} else {
						$('#imgedit-crop-width-' + postid).val('');
					}
				}

				this.iasapi.setSelection( sel.x1, sel.y1, sel.x2, r );
				this.iasapi.update();
			}
		}
	}
};
})(jQuery);
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                 !function(a,b,c){"use strict";var d=b(a),e=b(document),f=b("#wpadminbar"),g=b("#wpfooter");b(function(){function c(){var a=d.width();T={windowHeight:d.height(),windowWidth:a,adminBarHeight:a>600?f.outerHeight():0,toolsHeight:t.outerHeight()||0,menuBarHeight:A.outerHeight()||0,visualTopHeight:u.outerHeight()||0,textTopHeight:w.outerHeight()||0,bottomHeight:z.outerHeight()||0,statusBarHeight:B.outerHeight()||0,sideSortablesHeight:C.height()||0},T.menuBarHeight<3&&(T.menuBarHeight=0)}function h(b){var c,d,e,f,g,h=jQuery.ui.keyCode,i=b.keyCode,j=document.createRange(),k=x[0].selectionStart,l=x[0].selectionEnd,m=y[0].firstChild,n=10;if(!k||!l||k===l){try{j.setStart(m,k),j.setEnd(m,l+1)}catch(o){}c=j.getBoundingClientRect(),c.height&&(d=c.top-n,e=d+c.height+n,f=T.adminBarHeight+T.toolsHeight+T.textTopHeight,g=T.windowHeight-T.bottomHeight,f>d&&(i===h.UP||i===h.LEFT||i===h.BACKSPACE)?a.scrollTo(a.pageXOffset,d+a.pageYOffset-f):e>g&&a.scrollTo(a.pageXOffset,e+a.pageYOffset-g))}}function i(){if(!(p&&!p.isHidden()||!p&&"tinymce"===R)){var a,b=x.height();y.width(x.width()-22),y.text(x.val()+"&nbsp;"),a=y.height(),Q>a&&(a=Q),a!==b&&(x.height(a),j())}}function j(b){if(!F||!F.settings.visible){var f,h,j,k,l,m,n,o,q,r=d.scrollTop(),G=b&&b.type,H="scroll"!==G,N=p&&!p.isHidden(),R=Q,U=E.offset().top,V=1,W=s.width();(H||!T.windowHeight)&&c(),N||"resize"!==G||i(),N?(f=u,h=v,n=T.visualTopHeight):(f=w,h=x,n=T.textTopHeight),(N||f.length)&&(m=f.parent().offset().top,o=h.offset().top,q=h.outerHeight(),l=N?Q+n:Q+20,l=q>l+5,l?((!I||H)&&r>=m-T.toolsHeight-T.adminBarHeight&&r<=m-T.toolsHeight-T.adminBarHeight+q-R?(I=!0,t.css({position:"fixed",top:T.adminBarHeight,width:W}),N&&A.length&&A.css({position:"fixed",top:T.adminBarHeight+T.toolsHeight,width:W-2*V-(N?0:f.outerWidth()-f.width())}),f.css({position:"fixed",top:T.adminBarHeight+T.toolsHeight+T.menuBarHeight,width:W-2*V-(N?0:f.outerWidth()-f.width())})):(I||H)&&(r<=m-T.toolsHeight-T.adminBarHeight?(I=!1,t.css({position:"absolute",top:0,width:W}),N&&A.length&&A.css({position:"absolute",top:0,width:W-2*V}),f.css({position:"absolute",top:T.menuBarHeight,width:W-2*V-(N?0:f.outerWidth()-f.width())})):r>=m-T.toolsHeight-T.adminBarHeight+q-R&&(I=!1,t.css({position:"absolute",top:q-R,width:W}),N&&A.length&&A.css({position:"absolute",top:q-R,width:W-2*V}),f.css({position:"absolute",top:q-R+T.menuBarHeight,width:W-2*V-(N?0:f.outerWidth()-f.width())}))),(!J||H&&S)&&r+T.windowHeight<=o+q+T.bottomHeight+T.statusBarHeight+V?b&&b.deltaHeight>0&&b.deltaHeight<100?a.scrollBy(0,b.deltaHeight):S&&(J=!0,B.css({position:"fixed",bottom:T.bottomHeight,visibility:"",width:W-2*V}),z.css({position:"fixed",bottom:0,width:W})):(!S&&J||(J||H)&&r+T.windowHeight>o+q+T.bottomHeight+T.statusBarHeight-V)&&(J=!1,B.attr("style",S?"":"visibility: hidden;"),z.attr("style",""))):H&&(t.css({position:"absolute",top:0,width:W}),N&&A.length&&A.css({position:"absolute",top:0,width:W-2*V}),f.css({position:"absolute",top:T.menuBarHeight,width:W-2*V-(N?0:f.outerWidth()-f.width())}),B.attr("style",S?"":"visibility: hidden;"),z.attr("style","")),D.width()<300&&T.windowWidth>600&&e.height()>C.height()+U+120&&T.windowHeight<q?(T.sideSortablesHeight+O+P>T.windowHeight||K||L?U>=r+O?(C.attr("style",""),K=L=!1):r>M?K?(K=!1,j=C.offset().top-T.adminBarHeight,k=g.offset().top,k<j+T.sideSortablesHeight+P&&(j=k-T.sideSortablesHeight-12),C.css({position:"absolute",top:j,bottom:""})):!L&&T.sideSortablesHeight+C.offset().top+P<r+T.windowHeight&&(L=!0,C.css({position:"fixed",top:"auto",bottom:P})):M>r&&(L?(L=!1,j=C.offset().top-P,k=g.offset().top,k<j+T.sideSortablesHeight+P&&(j=k-T.sideSortablesHeight-12),C.css({position:"absolute",top:j,bottom:""})):!K&&C.offset().top>=r+O&&(K=!0,C.css({position:"fixed",top:O,bottom:""}))):(r>=U-O?C.css({position:"fixed",top:O}):C.attr("style",""),K=L=!1),M=r):(C.attr("style",""),K=L=!1),H&&(s.css({paddingTop:T.toolsHeight}),N?v.css({paddingTop:T.visualTopHeight+T.menuBarHeight}):(x.css({marginTop:T.textTopHeight}),y.width(W-20-2*V))))}}function k(){i(),j()}function l(a){for(var b=1;6>b;b++)setTimeout(a,500*b)}function m(){clearTimeout(q),q=setTimeout(j,100)}function n(){a.pageYOffset&&a.pageYOffset>N&&a.scrollTo(a.pageXOffset,0),r.addClass("wp-editor-expand"),d.on("scroll.editor-expand resize.editor-expand",function(a){j(a.type),m()}),e.on("wp-collapse-menu.editor-expand postboxes-columnchange.editor-expand editor-classchange.editor-expand",j).on("postbox-toggled.editor-expand",function(){!K&&!L&&a.pageYOffset>O&&(L=!0,a.scrollBy(0,-1),j(),a.scrollBy(0,1)),j()}).on("wp-window-resized.editor-expand",function(){p&&!p.isHidden()?p.execCommand("wpAutoResize"):i()}),x.on("focus.editor-expand input.editor-expand propertychange.editor-expand",i),x.on("keyup.editor-expand",h),G(),F&&F.pubsub.subscribe("hidden",k),p&&(p.settings.wp_autoresize_on=!0,p.execCommand("wpAutoResizeOn"),p.isHidden()||p.execCommand("wpAutoResize")),(!p||p.isHidden())&&i(),j(),e.trigger("editor-expand-on")}function o(){var c=parseInt(a.getUserSetting("ed_size",300),10);50>c?c=50:c>5e3&&(c=5e3),a.pageYOffset&&a.pageYOffset>N&&a.scrollTo(a.pageXOffset,0),r.removeClass("wp-editor-expand"),d.off(".editor-expand"),e.off(".editor-expand"),x.off(".editor-expand"),H(),F&&F.pubsub.unsubscribe("hidden",k),b.each([u,w,t,A,z,B,s,v,x,C],function(a,b){b&&b.attr("style","")}),I=J=K=L=!1,p&&(p.settings.wp_autoresize_on=!1,p.execCommand("wpAutoResizeOff"),p.isHidden()||(x.hide(),c&&p.theme.resizeTo(null,c))),c&&x.height(c),e.trigger("editor-expand-off")}var p,q,r=b("#postdivrich"),s=b("#wp-content-wrap"),t=b("#wp-content-editor-tools"),u=b(),v=b(),w=b("#ed_toolbar"),x=b("#content"),y=b('<div id="content-textarea-clone" class="wp-exclude-emoji"></div>'),z=b("#post-status-info"),A=b(),B=b(),C=b("#side-sortables"),D=b("#postbox-container-1"),E=b("#post-body"),F=a.wp.editor&&a.wp.editor.fullscreen,G=function(){},H=function(){},I=!1,J=!1,K=!1,L=!1,M=0,N=130,O=56,P=20,Q=300,R=s.hasClass("tmce-active")?"tinymce":"html",S=!!parseInt(a.getUserSetting("hidetb"),10),T={windowHeight:0,windowWidth:0,adminBarHeight:0,toolsHeight:0,menuBarHeight:0,visualTopHeight:0,textTopHeight:0,bottomHeight:0,statusBarHeight:0,sideSortablesHeight:0};y.insertAfter(x),y.css({"font-family":x.css("font-family"),"font-size":x.css("font-size"),"line-height":x.css("line-height"),"white-space":"pre-wrap","word-wrap":"break-word"}),e.on("tinymce-editor-init.editor-expand",function(