),d=this.container.find(".reorder-toggle"),f=this.$sectionContent.find(".widget-title");b=Boolean(b),b!==this.$sectionContent.hasClass("reordering")&&(this.isReordering=b,this.$sectionContent.toggleClass("reordering",b),b?(_(this.getWidgetFormControls()).each(function(a){a.collapse()}),c.attr({tabindex:"-1","aria-hidden":"true"}),d.attr("aria-label",e.reorderLabelOff),a.a11y.speak(e.reorderModeOn),f.attr("aria-hidden","true")):(c.removeAttr("tabindex aria-hidden"),d.attr("aria-label",e.reorderLabelOn),a.a11y.speak(e.reorderModeOff),f.attr("aria-hidden","false")))},getWidgetFormControls:function(){var a=[];return _(this.setting()).each(function(b){var c=d(b),e=f.control(c);e&&a.push(e)}),a},addWidget:function(a){var d,e,g,h,i,j,k,l,m,n,o=this,p="widget_form",q=c(a),r=q.number,s=q.id_base,t=f.Widgets.availableWidgets.findWhere({id_base:s});return t?r&&!t.get("is_multi")?!1:(t.get("is_multi")&&!r&&(t.set("multi_number",t.get("multi_number")+1),r=t.get("multi_number")),d=b.trim(b("#widget-tpl-"+t.get("id")).html()),t.get("is_multi")?d=d.replace(/<[^<>]+>/g,function(a){return a.replace(/__i__|%i%/g,r)}):t.set("is_disabled",!0),e=b(d),g=b("<li/>").addClass("customize-control").addClass("customize-control-"+p).append(e),g.find("> .widget-icon").remove(),t.get("is_multi")&&(g.find('input[name="widget_number"]').val(r),g.find('input[name="multi_number"]').val(r)),a=g.find('[name="widget-id"]').val(),g.hide(),i="widget_"+t.get("id_base"),t.get("is_multi")&&(i+="["+r+"]"),g.attr("id","customize-control-"+i.replace(/\]/g,"").replace(/\[/g,"-")),j=f.has(i),j||(m={transport:f.Widgets.data.selectiveRefreshableWidgets[t.get("id_base")]?"postMessage":"refresh",previewer:this.setting.previewer},n=f.create(i,i,"",m),n.set({})),h=f.controlConstructor[p],k=new h(i,{params:{settings:{"default":i},content:g,sidebar_id:o.params.sidebar_id,widget_id:a,widget_id_base:t.get("id_base"),type:p,is_new:!j,width:t.get("width"),height:t.get("height"),is_wide:t.get("is_wide"),active:!0},previewer:o.setting.previewer}),f.control.add(i,k),f.each(function(b){if(b.id!==o.setting.id&&0===b.id.indexOf("sidebars_widgets[")){var c=b().slice(),d=_.indexOf(c,a);-1!==d&&(c.splice(d),b(c))}}),l=this.setting().slice(),-1===_.indexOf(l,a)&&(l.push(a),this.setting(l)),g.slideDown(function(){j&&k.updateWidget({instance:k.setting()})}),k):!1}}),b.extend(f.panelConstructor,{widgets:f.Widgets.WidgetsPanel}),b.extend(f.sectionConstructor,{sidebar:f.Widgets.SidebarSection}),b.extend(f.controlConstructor,{widget_form:f.Widgets.WidgetControl,sidebar_widgets:f.Widgets.SidebarControl}),f.bind("ready",function(){f.Widgets.availableWidgetsPanel=new f.Widgets.AvailableWidgetsPanelView({collection:f.Widgets.availableWidgets}),f.previewer.bind("highlight-widget-control",f.Widgets.highlightWidgetFormControl),f.previewer.bind("focus-widget-control",f.Widgets.focusWidgetFormControl)}),f.Widgets.highlightWidgetFormControl=function(a){var b=f.Widgets.getWidgetFormControlForWidget(a);b&&b.highlightSectionAndControl()},f.Widgets.focusWidgetFormControl=function(a){var b=f.Widgets.getWidgetFormControlForWidget(a);b&&b.focus()},f.Widgets.getSidebarWidgetControlContainingWidget=function(a){var b=null;return f.control.each(function(c){"sidebar_widgets"===c.params.type&&-1!==_.indexOf(c.setting(),a)&&(b=c)}),b},f.Widgets.getWidgetFormControlForWidget=function(a){var b=null;return f.control.each(function(c){"widget_form"===c.params.type&&c.params.widget_id===a&&(b=c)}),b}}}(window.wp,jQuery);                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         var showNotice,adminMenu,columns,validateForm,screenMeta;!function(a,b,c){var d=a(document),e=a(b),f=a(document.body);adminMenu={init:function(){},fold:function(){},restoreMenuState:function(){},toggle:function(){},favorites:function(){}},columns={init:function(){var b=this;a(".hide-column-tog","#adv-settings").click(function(){var c=a(this),d=c.val();c.prop("checked")?b.checked(d):b.unchecked(d),columns.saveManageColumnsState()})},saveManageColumnsState:function(){var b=this.hidden();a.post(ajaxurl,{action:"hidden-columns",hidden:b,screenoptionnonce:a("#screenoptionnonce").val(),page:pagenow})},checked:function(b){a(".column-"+b).removeClass("hidden"),this.colSpanChange(1)},unchecked:function(b){a(".column-"+b).addClass("hidden"),this.colSpanChange(-1)},hidden:function(){return a(".manage-column[id]").filter(":hidden").map(function(){return this.id}).get().join(",")},useCheckboxesForHidden:function(){this.hidden=function(){return a(".hide-column-tog").not(":checked").map(function(){var a=this.id;return a.substring(a,a.length-5)}).get().join(",")}},colSpanChange:function(b){var c,d=a("table").find(".colspanchange");d.length&&(c=parseInt(d.attr("colspan"),10)+b,d.attr("colspan",c.toString()))}},d.ready(function(){columns.init()}),validateForm=function(b){return!a(b).find(".form-required").filter(function(){return""===a("input:visible",this).val()}).addClass("form-invalid").find("input:visible").change(function(){a(this).closest(".form-invalid").removeClass("form-invalid")}).length},showNotice={warn:function(){var a=commonL10n.warnDelete||"";return confirm(a)?!0:!1},note:function(a){alert(a)}},screenMeta={element:null,toggles:null,page:null,init:function(){this.element=a("#screen-meta"),this.toggles=a("#screen-meta-links").find(".show-settings"),this.page=a("#wpcontent"),this.toggles.click(this.toggleEvent)},toggleEvent:function(){var b=a("#"+a(this).attr("aria-controls"));b.length&&(b.is(":visible")?screenMeta.close(b,a(this)):screenMeta.open(b,a(this)))},open:function(b,c){a("#screen-meta-links").find(".screen-meta-toggle").not(c.parent()).css("visibility","hidden"),b.parent().show(),b.slideDown("fast",function(){b.focus(),c.addClass("screen-meta-active").attr("aria-expanded",!0)}),d.trigger("screen:options:open")},close:function(b,c){b.slideUp("fast",function(){c.removeClass("screen-meta-active").attr("aria-expanded",!1),a(".screen-meta-toggle").css("visibility",""),b.parent().hide()}),d.trigger("screen:options:close")}},a(".contextual-help-tabs").delegate("a","click",function(b){var c,d=a(this);return b.preventDefault(),d.is(".active a")?!1:(a(".contextual-help-tabs .active").removeClass("active"),d.parent("li").addClass("active"),c=a(d.attr("href")),a(".help-tab-content").not(c).removeClass("active").hide(),void c.addClass("active").show())}),d.ready(function(){function c(){var c,d=a("a.wp-has-current-submenu");c=b.innerWidth?Math.max(b.innerWidth,document.documentElement.clientWidth):961,f.hasClass("folded")||f.hasClass("auto-fold")&&c&&960>=c&&c>782?d.attr("aria-haspopup","true"):d.attr("aria-haspopup","false")}function g(a){var b,c,d,f,g,h,i,j=a.find(".wp-submenu");g=a.offset().top,h=e.scrollTop(),i=g-h-30,b=g+j.height()+1,c=C.height(),d=60+b-c,f=e.height()+h-50,b-d>f&&(d=b-f),d>i&&(d=i),d>1?j.css("margin-top","-"+d+"px"):j.css("margin-top","")}function h(){a(".notice.is-dismissible").each(function(){var b=a(this),c=a('<button type="button" class="notice-dismiss"><span class="screen-reader-text"></span></button>'),d=commonL10n.dismiss||"";c.find(".screen-reader-text").text(d),c.on("click.wp-dismiss-notice",function(a){a.preventDefault(),b.fadeTo(100,0,function(){b.slideUp(100,function(){b.remove()})})}),b.append(c)})}function i(a){var b=e.scrollTop(),c=!a||"scroll"!==a.type;if(!(y||A||D.data("wp-responsive"))){if(P.menu+P.adminbar<P.window||P.menu+P.adminbar+20>P.wpwrap)return void k();if(O=!0,P.menu+P.adminbar>P.window){if(0>b)return void(L||(L=!0,M=!1,B.css({position:"fixed",top:"",bottom:""})));if(b+P.window>d.height()-1)return void(M||(M=!0,L=!1,B.css({position:"fixed",top:"",bottom:0})));b>K?L?(L=!1,N=B.offset().top-P.adminbar-(b-K),N+P.menu+P.adminbar<b+P.window&&(N=b+P.window-P.menu-P.adminbar),B.css({position:"absolute",top:N,bottom:""})):!M&&B.offset().top+P.menu<b+P.window&&(M=!0,B.css({position:"fixed",top:"",bottom:0})):K>b?M?(M=!1,N=B.offset().top-P.adminbar+(K-b),N+P.menu>b+P.window&&(N=b),B.css({position:"absolute",top:N,bottom:""})):!L&&B.offset().top>=b+P.adminbar&&(L=!0,B.css({position:"fixed",top:"",bottom:""})):c&&(L=M=!1,N=b+P.window-P.menu-P.adminbar-1,N>0?B.css({position:"absolute",top:N,bottom:""}):k())}K=b}}function j(){P={window:e.height(),wpwrap:C.height(),adminbar:J.height(),menu:B.height()}}function k(){!y&&O&&(L=M=O=!1,B.css({position:"",top:"",bottom:""}))}function l(){j(),D.data("wp-responsive")?(f.removeClass("sticky-menu"),k()):P.menu+P.adminbar>P.window?(i(),f.removeClass("sticky-menu")):(f.addClass("sticky-menu"),k())}function m(){a(".aria-button-if-js").attr("role","button")}var n,o,p,q,r,s,t,u,v=!1,w=a("input.current-page"),x=w.val(),y=/iPhone|iPad|iPod/.test(navigator.userAgent),z=-1!==navigator.userAgent.indexOf("Android"),A=a(document.documentElement).hasClass("ie8"),B=a("#adminmenuwrap"),C=a("#wpwrap"),D=a("#adminmenu"),E=a("#wp-responsive-overlay"),F=a("#wp-toolbar"),G=F.find('a[aria-haspopup="true"]'),H=a(".meta-box-sortables"),I=!1,J=a("#wpadminbar"),K=0,L=!1,M=!1,N=0,O=!1,P={window:e.height(),wpwrap:C.height(),adminbar:J.height(),menu:B.height()};D.on("click.wp-submenu-head",".wp-submenu-head",function(b){a(b.target).parent().siblings("a").get(0).click()}),a("#collapse-menu").on("click.collapse-menu",function(){var e,g;a("#adminmenu div.wp-submenu").css("margin-top",""),e=b.innerWidth?Math.max(b.innerWidth,document.documentElement.clientWidth):961,e&&960>e?f.hasClass("auto-fold")?(f.removeClass("auto-fold").removeClass("folded"),setUserSetting("unfold",1),setUserSetting("mfold","o"),g="open"):(f.addClass("auto-fold"),setUserSetting("unfold",0),g="folded"):f.hasClass("folded")?(f.removeClass("folded"),setUserSetting("mfold","o"),g="open"):(f.addClass("folded"),setUserSetting("mfold","f"),g="folded"),c(),d.trigger("wp-collapse-menu",{state:g})}),d.on("wp-window-resized wp-responsive-activate wp-responsive-deactivate",c),("ontouchstart"in b||/IEMobile\/[1-9]/.test(navigator.userAgent))&&(s=y?"touchstart":"click",f.on(s+".wp-mobile-hover",function(b){D.data("wp-responsive")||a(b.target).closest("#adminmenu").length||D.find("li.opensub").removeClass("opensub")}),D.find("a.wp-has-submenu").on(s+".wp-mobile-hover",function(b){var c=a(this).parent();D.data("wp-responsive")||c.hasClass("opensub")||c.hasClass("wp-menu-open")&&!(c.width()<40)||(b.preventDefault(),g(c),D.find("li.opensub").removeClass("opensub"),c.addClass("opensub"))})),y||z||(D.find("li.wp-has-submenu").hoverIntent({over:function(){var b=a(this),c=b.find(".wp-submenu"),d=parseInt(c.css("top"),10);isNaN(d)||d>-5||D.data("wp-responsive")||(g(b),D.find("li.opensub").removeClass("opensub"),b.addClass("opensub"))},out:function(){D.data("wp-responsive")||a(this).removeClass("opensub").find(".wp-submenu").css("margin-top","")},timeout:200,sensitivity:7,interval:90}),D.on("focus.adminmenu",".wp-submenu a",function(b){D.data("wp-responsive")||a(b.target).closest("li.menu-top").addClass("opensub")}).on("blur.adminmenu",".wp-submenu a",function(b){D.data("wp-responsive")||a(b.target).closest("li.menu-top").removeClass("opensub")}).find("li.wp-has-submenu.wp-not-current-submenu").on("focusin.adminmenu",function(){g(a(this))})),a("div.updated, div.error, div.notice").not(".inline, .below-h2").insertAfter(a(".wrap h1, .wrap h2").first()),d.on("wp-plugin-update-error",function(){h()}),screenMeta.init(),a("tbody").children().children(".check-column").find(":checkbox").click(function(b){if("undefined"==b.shiftKey)return!0;if(b.shiftKey){if(!v)return!0;n=a(v).closest("form").find(":checkbox").filter(":visible:enabled"),o=n.index(v),p=n.index(this),q=a(this).prop("checked"),o>0&&p>0&&o!=p&&(r=p>o?n.slice(o,p):n.slice(p,o),r.prop("checked",function(){return a(this).closest("tr").is(":visible")?q:!1}))}v=this;var c=a(this).closest("tbody").find(":checkbox").filter(":visible:enabled").not(":checked");return a(this).closest("table").children("thead, tfoot").find(":checkbox").prop("checked",function(){return 0===c.length}),!0}),a("thead, tfoot").find(".check-column :checkbox").on("click.wp-toggle-checkboxes",function(b){var c=a(this),d=c.closest("table"),e=c.prop("checked"),f=b.shiftKey||c.data("wp-toggle");d.children("tbody").filter(":visible").children().children(".check-column").find(":checkbox").prop("checked",function(){return a(this).is(":hidden,:disabled")?!1:f?!a(this).prop("checked"):e?!0:!1}),d.children("thead,  tfoot").filter(":visible").children().children(".check-column").find(":checkbox").prop("checked",function(){return f?!1:e?!0:!1})}),a("#wpbody-content").on({focusin:function(){clearTimeout(t),u=a(this).find(".row-actions"),a(".row-actions").not(this).removeClass("visible"),u.addClass("visible")},focusout:function(){t=setTimeout(function(){u.removeClass("visible")},30)}},".has-row-actions"),a("tbody").on("click",".toggle-row",function(){a(this).closest("tr").toggleClass("is-expanded")}),a("#default-password-nag-no").click(function(){return setUserSetting("default_password_nag","hide"),a("div.default-password-nag").hide(),!1}),a("#newcontent").bind("keydown.wpevent_InsertTab",function(b){var c,d,e,f,g,h=b.target;if(27==b.keyCode)return b.preventDefault(),void a(h).data("tab-out",!0);if(!(9!=b.keyCode||b.ctrlKey||b.altKey||b.shiftKey)){if(a(h).data("tab-out"))return void a(h).data("tab-out",!1);c=h.selectionStart,d=h.selectionEnd,e=h.value,document.selection?(h.focus(),g=document.selection.createRange(),g.text="	"):c>=0&&(f=this.scrollTop,h.value=e.substring(0,c).concat("	",e.substring(d)),h.selectionStart=h.selectionEnd=c+1,this.scrollTop=f),b.stopPropagation&&b.stopPropagation(),b.preventDefault&&b.preventDefault()}}),w.length&&w.closest("form").submit(function(){-1==a('select[name="action"]').val()&&-1==a('select[name="action2"]').val()&&w.val()==x&&w.val("1")}),a('.search-box input[type="search"], .search-box input[type="submit"]').mousedown(function(){a('select[name^="action"]').val("-1")}),a("#contextual-help-link, #show-settings-link").on("focus.scroll-into-view",function(a){a.target.scrollIntoView&&a.target.scrollIntoView(!1)}),function(){function b(){c.prop("disabled",""===d.map(function(){return a(this).val()}).get().join(""))}var c,d,e=a("form.wp-upload-form");e.length&&(c=e.find('input[type="submit"]'),d=e.find('input[type="file"]'),b(),d.on("change",b))}(),y||(e.on("scroll.pin-menu",i),d.on("tinymce-editor-init.pin-menu",function(a,b){b.on("wp-autoresize",j)})),b.wpResponsive={init:function(){var c=this;d.on("wp-responsive-activate.wp-responsive",function(){c.activate()}).on("wp-responsive-deactivate.wp-responsive",function(){c.deactivate()}),a("#wp-admin-bar-menu-toggle a").attr("aria-expanded","false"),a("#wp-admin-bar-menu-toggle").on("click.wp-responsive",function(b){b.preventDefault(),J.find(".hover").removeClass("hover"),C.toggleClass("wp-responsive-open"),C.hasClass("wp-responsive-open")?(a(this).find("a").attr("aria-expanded","true"),a("#adminmenu a:first").focus()):a(this).find("a").attr("aria-expanded","false")}),D.on("click.wp-responsive","li.wp-has-submenu > a",function(b){D.data("wp-responsive")&&(a(this).parent("li").toggleClass("selected"),b.preventDefault())}),c.trigger(),d.on("wp-window-resized.wp-responsive",a.proxy(this.trigger,this)),e.on("load.wp-responsive",function(){var a=navigator.userAgent.indexOf("AppleWebKit/")>-1?e.width():b.innerWidth;782>=a&&c.disableSortables()})},activate:function(){l(),f.hasClass("auto-fold")||f.addClass("auto-fold"),D.data("wp-responsive",1),this.disableSortables()},deactivate:function(){l(),D.removeData("wp-responsive"),this.enableSortables()},trigger:function(){var a;b.innerWidth&&(a=Math.max(b.innerWidth,document.documentElement.clientWidth),782>=a?I||(d.trigger("wp-responsive-activate"),I=!0):I&&(d.trigger("wp-responsive-deactivate"),I=!1),480>=a?this.enableOverlay():this.disableOverlay())},enableOverlay:function(){0===E.length&&(E=a('<div id="wp-responsive-overlay"></div>').insertAfter("#wpcontent").hide().on("click.wp-responsive",function(){F.find(".menupop.hover").removeClass("hover"),a(this).hide()})),G.on("click.wp-responsive",function(){E.show()})},disableOverlay:function(){G.off("click.wp-responsive"),E.hide()},disableSortables:function(){if(H.length)try{H.sortable("disable")}catch(a){}},enableSortables:function(){if(H.length)try{H.sortable("enable")}catch(a){}}},a(document).ajaxComplete(function(){m()}),b.wpResponsive.init(),l(),c(),h(),m(),d.on("wp-pin-menu wp-window-resized.pin-menu postboxes-columnchange.pin-menu postbox-toggled.pin-menu wp-collapse-menu.pin-menu wp-scroll-start.pin-menu",l),a(".wp-initial-focus").focus()}),function(){function a(){d.trigger("wp-window-resized")}function c(){b.clearTimeout(f),f=b.setTimeout(a,200)}var f;e.on("resize.wp-fire-once",c)}(),function(){if("-ms-user-select"in document.documentElement.style&&navigator.userAgent.match(/IEMobile\/10\.0/)){var a=document.createElement("style");a.appendChild(document.createTextNode("@-ms-viewport{width:auto!important}")),document.getElementsByTagName("head")[0].appendChild(a)}}()}(jQuery,window);                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      /* global setUserSetting, ajaxurl, commonL10n, alert, confirm, pagenow */
var showNotice, adminMenu, columns, validateForm, screenMeta;
( function( $, window, undefined ) {
	var $document = $( document ),
		$window = $( window ),
		$body = $( document.body );

// Removed in 3.3.
// (perhaps) needed for back-compat
adminMenu = {
	init : function() {},
	fold : function() {},
	restoreMenuState : function() {},
	toggle : function() {},
	favorites : function() {}
};

// show/hide/save table columns
columns = {
	init : function() {
		var that = this;
		$('.hide-column-tog', '#adv-settings').click( function() {
			var $t = $(this), column = $t.val();
			if ( $t.prop('checked') )
				that.checked(column);
			else
				that.unchecked(column);

			columns.saveManageColumnsState();
		});
	},

	saveManageColumnsState : function() {
		var hidden = this.hidden();
		$.post(ajaxurl, {
			action: 'hidden-columns',
			hidden: hidden,
			screenoptionnonce: $('#screenoptionnonce').val(),
			page: pagenow
		});
	},

	checked : function(column) {
		$('.column-' + column).removeClass( 'hidden' );
		this.colSpanChange(+1);
	},

	unchecked : function(column) {
		$('.column-' + column).addClass( 'hidden' );
		this.colSpanChange(-1);
	},

	hidden : function() {
		return $( '.manage-column[id]' ).filter( ':hidden' ).map(function() {
			return this.id;
		}).get().join( ',' );
	},

	useCheckboxesForHidden : function() {
		this.hidden = function(){
			return $('.hide-column-tog').not(':checked').map(function() {
				var id = this.id;
				return id.substring( id, id.length - 5 );
			}).get().join(',');
		};
	},

	colSpanChange : function(diff) {
		var $t = $('table').find('.colspanchange'), n;
		if ( !$t.length )
			return;
		n = parseInt( $t.attr('colspan'), 10 ) + diff;
		$t.attr('colspan', n.toString());
	}
};

$document.ready(function(){columns.init();});

validateForm = function( form ) {
	return !$( form )
		.find( '.form-required' )
		.filter( function() { return $( 'input:visible', this ).val() === ''; } )
		.addClass( 'form-invalid' )
		.find( 'input:visible' )
		.change( function() { $( this ).closest( '.form-invalid' ).removeClass( 'form-invalid' ); } )
		.length;
};

// stub for doing better warnings
showNotice = {
	warn : function() {
		var msg = commonL10n.warnDelete || '';
		if ( confirm(msg) ) {
			return true;
		}

		return false;
	},

	note : function(text) {
		alert(text);
	}
};

screenMeta = {
	element: null, // #screen-meta
	toggles: null, // .screen-meta-toggle
	page:    null, // #wpcontent

	init: function() {
		this.element = $('#screen-meta');
		this.toggles = $( '#screen-meta-links' ).find( '.show-settings' );
		this.page    = $('#wpcontent');

		this.toggles.click( this.toggleEvent );
	},

	toggleEvent: function() {
		var panel = $( '#' + $( this ).attr( 'aria-controls' ) );

		if ( !panel.length )
			return;

		if ( panel.is(':visible') )
			screenMeta.close( panel, $(this) );
		else
			screenMeta.open( panel, $(this) );
	},

	open: function( panel, button ) {

		$( '#screen-meta-links' ).find( '.screen-meta-toggle' ).not( button.parent() ).css( 'visibility', 'hidden' );

		panel.parent().show();
		panel.slideDown( 'fast', function() {
			panel.focus();
			button.addClass( 'screen-meta-active' ).attr( 'aria-expanded', true );
		});

		$document.trigger( 'screen:options:open' );
	},

	close: function( panel, button ) {
		panel.slideUp( 'fast', function() {
			button.removeClass( 'screen-meta-active' ).attr( 'aria-expanded', false );
			$('.screen-meta-toggle').css('visibility', '');
			panel.parent().hide();
		});

		$document.trigger( 'screen:options:close' );
	}
};

/**
 * Help tabs.
 */
$('.contextual-help-tabs').delegate('a', 'click', function(e) {
	var link = $(this),
		panel;

	e.preventDefault();

	// Don't do anything if the click is for the tab already showing.
	if ( link.is('.active a') )
		return false;

	// Links
	$('.contextual-help-tabs .active').removeClass('active');
	link.parent('li').addClass('active');

	panel = $( link.attr('href') );

	// Panels
	$('.help-tab-content').not( panel ).removeClass('active').hide();
	panel.addClass('active').show();
});

$document.ready( function() {
	var checks, first, last, checked, sliced, mobileEvent, transitionTimeout, focusedRowActions,
		lastClicked = false,
		pageInput = $('input.current-page'),
		currentPage = pageInput.val(),
		isIOS = /iPhone|iPad|iPod/.test( navigator.userAgent ),
		isAndroid = navigator.userAgent.indexOf( 'Android' ) !== -1,
		isIE8 = $( document.documentElement ).hasClass( 'ie8' ),
		$adminMenuWrap = $( '#adminmenuwrap' ),
		$wpwrap = $( '#wpwrap' ),
		$adminmenu = $( '#adminmenu' ),
		$overlay = $( '#wp-responsive-overlay' ),
		$toolbar = $( '#wp-toolbar' ),
		$toolbarPopups = $toolbar.find( 'a[aria-haspopup="true"]' ),
		$sortables = $('.meta-box-sortables'),
		wpResponsiveActive = false,
		$adminbar = $( '#wpadminbar' ),
		lastScrollPosition = 0,
		pinnedMenuTop = false,
		pinnedMenuBottom = false,
		menuTop = 0,
		menuIsPinned = false,
		height = {
			window: $window.height(),
			wpwrap: $wpwrap.height(),
			adminbar: $adminbar.height(),
			menu: $adminMenuWrap.height()
		};


	// when the menu is folded, make the fly-out submenu header clickable
	$adminmenu.on('click.wp-submenu-head', '.wp-submenu-head', function(e){
		$(e.target).parent().siblings('a').get(0).click();
	});

	$('#collapse-menu').on('click.collapse-menu', function() {
		var respWidth, state;

		// reset any compensation for submenus near the bottom of the screen
		$('#adminmenu div.wp-submenu').css('margin-top', '');

		if ( window.innerWidth ) {
			// window.innerWidth is affected by zooming on phones
			respWidth = Math.max( window.innerWidth, document.documentElement.clientWidth );
		} else {
			// IE < 9 doesn't support @media CSS rules
			respWidth = 961;
		}

		if ( respWidth && respWidth < 960 ) {
			if ( $body.hasClass('auto-fold') ) {
				$body.removeClass('auto-fold').removeClass('folded');
				setUserSetting('unfold', 1);
				setUserSetting('mfold', 'o');
				state = 'open';
			} else {
				$body.addClass('auto-fold');
				setUserSetting('unfold', 0);
				state = 'folded';
			}
		} else {
			if ( $body.hasClass('folded') ) {
				$body.removeClass('folded');
				setUserSetting('mfold', 'o');
				state = 'open';
			} else {
				$body.addClass('folded');
				setUserSetting('mfold', 'f');
				state = 'folded';
			}
		}

		currentMenuItemHasPopup();
		$document.trigger( 'wp-collapse-menu', { state: state } );
	});

	// Handle the `aria-haspopup` attribute on the current menu item when it has a sub-menu.
	function currentMenuItemHasPopup() {
		var respWidth,
			$current = $( 'a.wp-has-current-submenu' );

		if ( window.innerWidth ) {
			respWidth = Math.max( window.innerWidth, document.documentElement.clientWidth );
		} else {
			respWidth = 961;
		}

		if ( $body.hasClass( 'folded' ) || ( $body.hasClass( 'auto-fold' ) && respWidth && respWidth <= 960 && respWidth > 782 ) ) {
			// When folded or auto-folded and not responsive view, the current menu item does have a fly-out sub-menu.
			$current.attr( 'aria-haspopup', 'true' );
		} else {
			// When expanded or in responsive view, reset aria-haspopup.
			$current.attr( 'aria-haspopup', 'false' );
		}
	}

	$document.on( 'wp-window-resized wp-responsive-activate wp-responsive-deactivate', currentMenuItemHasPopup );

	/**
	 * Ensure an admin submenu is within the visual viewport.
	 *
	 * @since 4.1.0
	 *
	 * @param {jQuery} $menuItem The parent menu item containing the submenu.
	 */
	function adjustSubmenu( $menuItem ) {
		var bottomOffset, pageHeight, adjustment, theFold, menutop, wintop, maxtop,
			$submenu = $menuItem.find( '.wp-submenu' );

		menutop = $menuItem.offset().top;
		wintop = $window.scrollTop();
		maxtop = menutop - wintop - 30; // max = make the top of the sub almost touch admin bar

		bottomOffset = menutop + $submenu.height() + 1; // Bottom offset of the menu
		pageHeight = $wpwrap.height(); // Height of the entire page
		adjustment = 60 + bottomOffset - pageHeight;
		theFold = $window.height() + wintop - 50; // The fold

		if ( theFold < ( bottomOffset - adjustment ) ) {
			adjustment = bottomOffset - theFold;
		}

		if ( adjustment > maxtop ) {
			adjustment = maxtop;
		}

		if ( adjustment > 1 ) {
			$submenu.css( 'margin-top', '-' + adjustment + 'px' );
		} else {
			$submenu.css( 'margin-top', '' );
		}
	}

	if ( 'ontouchstart' in window || /IEMobile\/[1-9]/.test(navigator.userAgent) ) { // touch screen device
		// iOS Safari works with touchstart, the rest work with click
		mobileEvent = isIOS ? 'touchstart' : 'click';

		// close any open submenus when touch/click is not on the menu
		$body.on( mobileEvent+'.wp-mobile-hover', function(e) {
			if ( $adminmenu.data('wp-responsive') ) {
				return;
			}

			if ( ! $( e.target ).closest( '#adminmenu' ).length ) {
				$adminmenu.find( 'li.opensub' ).removeClass( 'opensub' );
			}
		});

		$adminmenu.find( 'a.wp-has-submenu' ).on( mobileEvent + '.wp-mobile-hover', function( event ) {
			var $menuItem = $(this).parent();

			if ( $adminmenu.data( 'wp-responsive' ) ) {
				return;
			}

			// Show the sub instead of following the link if:
			//	- the submenu is not open
			//	- the submenu is not shown inline or the menu is not folded
			if ( ! $menuItem.hasClass( 'opensub' ) && ( ! $menuItem.hasClass( 'wp-menu-open' ) || $menuItem.width() < 40 ) ) {
				event.preventDefault();
				adjustSubmenu( $menuItem );
				$adminmenu.find( 'li.opensub' ).removeClass( 'opensub' );
				$menuItem.addClass('opensub');
			}
		});
	}

	if ( ! isIOS && ! isAndroid ) {
		$adminmenu.find( 'li.wp-has-submenu' ).hoverIntent({
			over: function() {
				var $menuItem = $( this ),
					$submenu = $menuItem.find( '.wp-submenu' ),
					top = parseInt( $submenu.css( 'top' ), 10 );

				if ( isNaN( top ) || top > -5 ) { // the submenu is visible
					return;
				}

				if ( $adminmenu.data( 'wp-responsive' ) ) {
					// The menu is in responsive mode, bail
					return;
				}

				adjustSubmenu( $menuItem );
				$adminmenu.find( 'li.opensub' ).removeClass( 'opensub' );
				$menuItem.addClass( 'opensub' );
			},
			out: function(){
				if ( $adminmenu.data( 'wp-responsive' ) ) {
					// The menu is in responsive mode, bail
					return;
				}

				$( this ).removeClass( 'opensub' ).find( '.wp-submenu' ).css( 'margin-top', '' );
			},
			timeout: 200,
			sensitivity: 7,
			interval: 90
		});

		$adminmenu.on( 'focus.adminmenu', '.wp-submenu a', function( event ) {
			if ( $adminmenu.data( 'wp-responsive' ) ) {
				// The menu is in responsive mode, bail
				return;
			}

			$( event.target ).closest( 'li.menu-top' ).addClass( 'opensub' );
		}).on( 'blur.adminmenu', '.wp-submenu a', function( event ) {
			if ( $adminmenu.data( 'wp-responsive' ) ) {
				return;
			}

			$( event.target ).closest( 'li.menu-top' ).removeClass( 'opensub' );
		}).find( 'li.wp-has-submenu.wp-not-current-submenu' ).on( 'focusin.adminmenu', function() {
			adjustSubmenu( $( this ) );
		});
	}

	/*
	 * The `.below-h2` class is here just for backwards compatibility with plugins
	 * that are (incorrectly) using it. Do not use. Use `.inline` instead. See #34570.
	 */
	$( 'div.updated, div.error, div.notice' ).not( '.inline, .below-h2' ).insertAfter( $( '.wrap h1, .wrap h2' ).first() );

	// Make notices dismissible
	function makeNoticesDismissible() {
		$( '.notice.is-dismissible' ).each( function() {
			var $el = $( this ),
				$button = $( '<button type="button" class="notice-dismiss"><span class="screen-reader-text"></span></button>' ),
				btnText = commonL10n.dismiss || '';

			// Ensure plain text
			$button.find( '.screen-reader-text' ).text( btnText );
			$button.on( 'click.wp-dismiss-notice', function( event ) {
				event.preventDefault();
				$el.fadeTo( 100, 0, function() {
					$el.slideUp( 100, function() {
						$el.remove();
					});
				});
			});

			$el.append( $button );
		});
	}

	$document.on( 'wp-plugin-update-error', function() {
		makeNoticesDismissible();
	});

	// Init screen meta
	screenMeta.init();

	// check all checkboxes
	$('tbody').children().children('.check-column').find(':checkbox').click( function(e) {
		if ( 'undefined' == e.shiftKey ) { return true; }
		if ( e.shiftKey ) {
			if ( !lastClicked ) { return true; }
			checks = $( lastClicked ).closest( 'form' ).find( ':checkbox' ).filter( ':visible:enabled' );
			first = checks.index( lastClicked );
			last = checks.index( this );
			checked = $(this).prop('checked');
			if ( 0 < first && 0 < last && first != last ) {
				sliced = ( last > first ) ? checks.slice( first, last ) : checks.slice( last, first );
				sliced.prop( 'checked', function() {
					if ( $(this).closest('tr').is(':visible') )
						return checked;

					return false;
				});
			}
		}
		lastClicked = this;

		// toggle "check all" checkboxes
		var unchecked = $(this).closest('tbody').find(':checkbox').filter(':visible:enabled').not(':checked');
		$(this).closest('table').children('thead, tfoot').find(':checkbox').prop('checked', function() {
			return ( 0 === unchecked.length );
		});

		return true;
	});

	$('thead, tfoot').find('.check-column :checkbox').on( 'click.wp-toggle-checkboxes', function( event ) {
		var $this = $(this),
			$table = $this.closest( 'table' ),
			controlChecked = $this.prop('checked'),
			toggle = event.shiftKey || $this.data('wp-toggle');

		$table.children( 'tbody' ).filter(':visible')
			.children().children('.check-column').find(':checkbox')
			.prop('checked', function() {
				if ( $(this).is(':hidden,:disabled') ) {
					return false;
				}

				if ( toggle ) {
					return ! $(this).prop( 'checked' );
				} else if ( controlChecked ) {
					return true;
				}

				return false;
			});

		$table.children('thead,  tfoot').filter(':visible')
			.children().children('.check-column').find(':checkbox')
			.prop('checked', function() {
				if ( toggle ) {
					return false;
				} else if ( controlChecked ) {
					return true;
				}

				return false;
			});
	});

	// Show row actions on keyboard focus of its parent container element or any other elements contained within
	$( '#wpbody-content' ).on({
		focusin: function() {
			clearTimeout( transitionTimeout );
			focusedRowActions = $( this ).find( '.row-actions' );
			// transitionTimeout is necessary for Firefox, but Chrome won't remove the CSS class without a little help.
			$( '.row-actions' ).not( this ).removeClass( 'visible' );
			focusedRowActions.addClass( 'visible' );
		},
		focusout: function() {
			// Tabbing between post title and .row-actions links needs a brief pause, otherwise
			// the .row-actions div gets hidden in transit in some browsers (ahem, Firefox).
			transitionTimeout = setTimeout( function() {
				focusedRowActions.removeClass( 'visible' );
			}, 30 );
		}
	}, '.has-row-actions' );

	// Toggle list table rows on small screens
	$( 'tbody' ).on( 'click', '.toggle-row', function() {
		$( this ).closest( 'tr' ).toggleClass( 'is-expanded' );
	});

	$('#default-password-nag-no').click( function() {
		setUserSetting('default_password_nag', 'hide');
		$('div.default-password-nag').hide();
		return false;
	});

	// tab in textareas
	$('#newcontent').bind('keydown.wpevent_InsertTab', function(e) {
		var el = e.target, selStart, selEnd, val, scroll, sel;

		if ( e.keyCode == 27 ) { // escape key
			// when pressing Escape: Opera 12 and 27 blur form fields, IE 8 clears them
			e.preventDefault();
			$(el).data('tab-out', true);
			return;
		}

		if ( e.keyCode != 9 || e.ctrlKey || e.altKey || e.shiftKey ) // tab key
			return;

		if ( $(el).data('tab-out') ) {
			$(el).data('tab-out', false);
			return;
		}

		selStart = el.selectionStart;
		selEnd = el.selectionEnd;
		val = el.value;

		if ( document.selection ) {
			el.focus();
			sel = document.selection.createRange();
			sel.text = '\t';
		} else if ( selStart >= 0 ) {
			scroll = this.scrollTop;
			el.value = val.substring(0, selStart).concat('\t', val.substring(selEnd) );
			el.selectionStart = el.selectionEnd = selStart + 1;
			this.scrollTop = scroll;
		}

		if ( e.stopPropagation )
			e.stopPropagation();
		if ( e.preventDefault )
			e.preventDefault();
	});

	if ( pageInput.length ) {
		pageInput.closest('form').submit( function() {

			// Reset paging var for new filters/searches but not for bulk actions. See #17685.
			if ( $('select[name="action"]').val() == -1 && $('select[name="action2"]').val() == -1 && pageInput.val() == currentPage )
				pageInput.val('1');
		});
	}

	$('.search-box input[type="search"], .search-box input[type="submit"]').mousedown(function () {
		$('select[name^="action"]').val('-1');
	});

	// Scroll into view when focused
	$('#contextual-help-link, #show-settings-link').on( 'focus.scroll-into-view', function(e){
		if ( e.target.scrollIntoView )
			e.target.scrollIntoView(false);
	});

	// Disable upload buttons until files are selected
	(function(){
		var button, input, form = $('form.wp-upload-form');
		if ( ! form.length )
			return;
		button = form.find('input[type="submit"]');
		input = form.find('input[type="file"]');

		function toggleUploadButton() {
			button.prop('disabled', '' === input.map( function() {
				return $(this).val();
			}).get().join(''));
		}
		toggleUploadButton();
		input.on('change', toggleUploadButton);
	})();

	function pinMenu( event ) {
		var windowPos = $window.scrollTop(),
			resizing = ! event || event.type !== 'scroll';

		if ( isIOS || isIE8 || $adminmenu.data( 'wp-responsive' ) ) {
			return;
		}

		if ( height.menu + height.adminbar < height.window ||
			height.menu + height.adminbar + 20 > height.wpwrap ) {
			unpinMenu();
			return;
		}

		menuIsPinned = true;

		if ( height.menu + height.adminbar > height.window ) {
			// Check for overscrolling
			if ( windowPos < 0 ) {
				if ( ! pinnedMenuTop ) {
					pinnedMenuTop = true;
					pinnedMenuBottom = false;

					$adminMenuWrap.css({
						position: 'fixed',
						top: '',
						bottom: ''
					});
				}

				return;
			} else if ( windowPos + height.window > $document.height() - 1 ) {
				if ( ! pinnedMenuBottom ) {
					pinnedMenuBottom = true;
					pinnedMenuTop = false;

					$adminMenuWrap.css({
						position: 'fixed',
						top: '',
						bottom: 0
					});
				}

				return;
			}

			if ( windowPos > lastScrollPosition ) {
				// Scrolling down
				if ( pinnedMenuTop ) {
					// let it scroll
					pinnedMenuTop = false;
					menuTop = $adminMenuWrap.offset().top - height.adminbar - ( windowPos - lastScrollPosition );

					if ( menuTop + height.menu + height.adminbar < windowPos + height.window ) {
						menuTop = windowPos + height.window - height.menu - height.adminbar;
					}

					$adminMenuWrap.css({
						position: 'absolute',
						top: menuTop,
						bottom: ''
					});
				} else if ( ! pinnedMenuBottom && $adminMenuWrap.offset().top + height.menu < windowPos + height.window ) {
					// pin the bottom
					pinnedMenuBottom = true;

					$adminMenuWrap.css({
						position: 'fixed',
						top: '',
						bottom: 0
					});
				}
			} else if ( windowPos < lastScrollPosition ) {
				// Scrolling up
				if ( pinnedMenuBottom ) {
					// let it scroll
					pinnedMenuBottom = false;
					menuTop = $adminMenuWrap.offset().top - height.adminbar + ( lastScrollPosition - windowPos );

					if ( menuTop + height.menu > windowPos + height.window ) {
						menuTop = windowPos;
					}

					$adminMenuWrap.css({
						position: 'absolute',
						top: menuTop,
						bottom: ''
					});
				} else if ( ! pinnedMenuTop && $adminMenuWrap.offset().top >= windowPos + height.adminbar ) {
					// pin the top
					pinnedMenuTop = true;

					$adminMenuWrap.css({
						position: 'fixed',
						top: '',
						bottom: ''
					});
				}
			} else if ( resizing ) {
				// Resizing
				pinnedMenuTop = pinnedMenuBottom = false;
				menuTop = windowPos + height.window - height.menu - height.adminbar - 1;

				if ( menuTop > 0 ) {
					$adminMenuWrap.css({
						position: 'absolute',
						top: menuTop,
						bottom: ''
					});
				} else {
					unpinMenu();
				}
			}
		}

		lastScrollPosition = windowPos;
	}

	function resetHeights() {
		height = {
			window: $window.height(),
			wpwrap: $wpwrap.height(),
			adminbar: $adminbar.height(),
			menu: $adminMenuWrap.height()
		};
	}

	function unpinMenu() {
		if ( isIOS || ! menuIsPinned ) {
			return;
		}

		pinnedMenuTop = pinnedMenuBottom = menuIsPinned = false;
		$adminMenuWrap.css({
			position: '',
			top: '',
			bottom: ''
		});
	}

	function setPinMenu() {
		resetHeights();

		if ( $adminmenu.data('wp-responsive') ) {
			$body.removeClass( 'sticky-menu' );
			unpinMenu();
		} else if ( height.menu + height.adminbar > height.window ) {
			pinMenu();
			$body.removeClass( 'sticky-menu' );
		} else {
			$body.addClass( 'sticky-menu' );
			unpinMenu();
		}
	}

	if ( ! isIOS ) {
		$window.on( 'scroll.pin-menu', pinMenu );
		$document.on( 'tinymce-editor-init.pin-menu', function( event, editor ) {
			editor.on( 'wp-autoresize', resetHeights );
		});
	}

	window.wpResponsive = {
		init: function() {
			var self = this;

			// Modify functionality based on custom activate/deactivate event
			$document.on( 'wp-responsive-activate.wp-responsive', function() {
				self.activate();
			}).on( 'wp-responsive-deactivate.wp-responsive', function() {
				self.deactivate();
			});

			$( '#wp-admin-bar-menu-toggle a' ).attr( 'aria-expanded', 'false' );

			// Toggle sidebar when toggle is clicked
			$( '#wp-admin-bar-menu-toggle' ).on( 'click.wp-responsive', function( event ) {
				event.preventDefault();

				// close any open toolbar submenus
				$adminbar.find( '.hover' ).removeClass( 'hover' );

				$wpwrap.toggleClass( 'wp-responsive-open' );
				if ( $wpwrap.hasClass( 'wp-responsive-open' ) ) {
					$(this).find('a').attr( 'aria-expanded', 'true' );
					$( '#adminmenu a:first' ).focus();
				} else {
					$(this).find('a').attr( 'aria-expanded', 'false' );
				}
			} );

			// Add menu events
			$adminmenu.on( 'click.wp-responsive', 'li.wp-has-submenu > a', function( event ) {
				if ( ! $adminmenu.data('wp-responsive') ) {
					return;
				}

				$( this ).parent( 'li' ).toggleClass( 'selected' );
				event.preventDefault();
			});

			self.trigger();
			$document.on( 'wp-window-resized.wp-responsive', $.proxy( this.trigger, this ) );

			// This needs to run later as UI Sortable may be initialized later on $(document).ready()
			$window.on( 'load.wp-responsive', function() {
				var width = navigator.userAgent.indexOf('AppleWebKit/') > -1 ? $window.width() : window.innerWidth;

				if ( width <= 782 ) {
					self.disableSortables();
				}
			});
		},

		activate: function() {
			setPinMenu();

			if ( ! $body.hasClass( 'auto-fold' ) ) {
				$body.addClass( 'auto-fold' );
			}

			$adminmenu.data( 'wp-responsive', 1 );
			this.disableSortables();
		},

		deactivate: function() {
			setPinMenu();
			$adminmenu.removeData('wp-responsive');
			this.enableSortables();
		},

		trigger: function() {
			var width;

			if ( window.innerWidth ) {
				// window.innerWidth is affected by zooming on phones
				width = Math.max( window.innerWidth, document.documentElement.clientWidth );
			} else {
				// Exclude IE < 9, it doesn't support @media CSS rules
				return;
			}

			if ( width <= 782 ) {
				if ( ! wpResponsiveActive ) {
					$document.trigger( 'wp-responsive-activate' );
					wpResponsiveActive = true;
				}
			} else {
				if ( wpResponsiveActive ) {
					$document.trigger( 'wp-responsive-deactivate' );
					wpResponsiveActive = false;
				}
			}

			if ( width <= 480 ) {
				this.enableOverlay();
			} else {
				this.disableOverlay();
			}
		},

		enableOverlay: function() {
			if ( $overlay.length === 0 ) {
				$overlay = $( '<div id="wp-responsive-overlay"></div>' )
					.insertAfter( '#wpcontent' )
					.hide()
					.on( 'click.wp-responsive', function() {
						$toolbar.find( '.menupop.hover' ).removeClass( 'hover' );
						$( this ).hide();
					});
			}

			$toolbarPopups.on( 'click.wp-responsive', function() {
				$overlay.show();
			});
		},

		disableOverlay: function() {
			$toolbarPopups.off( 'click.wp-responsive' );
			$overlay.hide();
		},

		disableSortables: function() {
			if ( $sortables.length ) {
				try {
					$sortables.sortable('disable');
				} catch(e) {}
			}
		},

		enableSortables: function() {
			if ( $sortables.length ) {
				try {
					$sortables.sortable('enable');
				} catch(e) {}
			}
		}
	};

	// Add an ARIA role `button` to elements that behave like UI controls when JavaScript is on.
	function aria_button_if_js() {
		$( '.aria-button-if-js' ).attr( 'role', 'button' );
	}

	$( document ).ajaxComplete( function() {
		aria_button_if_js();
	});

	window.wpResponsive.init();
	setPinMenu();
	currentMenuItemHasPopup();
	makeNoticesDismissible();
	aria_button_if_js();

	$document.on( 'wp-pin-menu wp-window-resized.pin-menu postboxes-columnchange.pin-menu postbox-toggled.pin-menu wp-collapse-menu.pin-menu wp-scroll-start.pin-menu', setPinMenu );

	// Set initial focus on a specific element.
	$( '.wp-initial-focus' ).focus();
});

// Fire a custom jQuery event at the end of window resize
( function() {
	var timeout;

	function triggerEvent() {
		$document.trigger( 'wp-window-resized' );
	}

	function fireOnce() {
		window.clearTimeout( timeout );
		timeout = window.setTimeout( triggerEvent, 200 );
	}

	$window.on( 'resize.wp-fire-once', fireOnce );
}());

// Make Windows 8 devices play along nicely.
(function(){
	if ( '-ms-user-select' in document.documentElement.style && navigator.userAgent.match(/IEMobile\/10\.0/) ) {
		var msViewportStyle = document.createElement( 'style' );
		msViewportStyle.appendChild(
			document.createTextNode( '@-ms-viewport{width:auto!important}' )
		);
		document.getElementsByTagName( 'head' )[0].appendChild( msViewportStyle );
	}
})();

}( jQuery, window ));
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  <?php
/**
 * WordPress user administration API.
 *
 * @package WordPress
 * @subpackage Administration
 */

/**
 * Creates a new user from the "Users" form using $_POST information.
 *
 * @since 2.0.0
 *
 * @return int|WP_Error WP_Error or User ID.
 */
function add_user() {
	return edit_user();
}

/**
 * Edit user settings based on contents of $_POST
 *
 * Used on user-edit.php and profile.php to manage and process user options, passwords etc.
 *
 * @since 2.0.0
 *
 * @param int $user_id Optional. User ID.
 * @return int|WP_Error user id of the updated user
 */
function edit_user( $user_id = 0 ) {
	$wp_roles = wp_roles();
	$user = new stdClass;
	if ( $user_id ) {
		$update = true;
		$user->ID = (int) $user_id;
		$userdata = get_userdata( $user_id );
		$user->user_login = wp_slash( $userdata->user_login );
	} else {
		$update = false;
	}

	if ( !$update && isset( $_POST['user_login'] ) )
		$user->user_login = sanitize_user($_POST['user_login'], true);

	$pass1 = $pass2 = '';
	if ( isset( $_POST['pass1'] ) )
		$pass1 = $_POST['pass1'];
	if ( isset( $_POST['pass2'] ) )
		$pass2 = $_POST['pass2'];

	if ( isset( $_POST['role'] ) && current_user_can( 'edit_users' ) ) {
		$new_role = sanitize_text_field( $_POST['role'] );
		$potential_role = isset($wp_roles->role_objects[$new_role]) ? $wp_roles->role_objects[$new_role] : false;
		// Don't let anyone with 'edit_users' (admins) edit their own role to something without it.
		// Multisite super admins can freely edit their blog roles -- they possess all caps.
		if ( ( is_multisite() && current_user_can( 'manage_sites' ) ) || $user_id != get_current_user_id() || ($potential_role && $potential_role->has_cap( 'edit_users' ) ) )
			$user->role = $new_role;

		// If the new role isn't editable by the logged-in user die with error
		$editable_roles = get_editable_roles();
		if ( ! empty( $new_role ) && empty( $editable_roles[$new_role] ) )
			wp_die(__('You can&#8217;t give users that role.'));
	}

	if ( isset( $_POST['email'] ))
		$user->user_email = sanitize_text_field( wp_unslash( $_POST['email'] ) );
	if ( isset( $_POST['url'] ) ) {
		if ( empty ( $_POST['url'] ) || $_POST['url'] == 'http://' ) {
			$user->user_url = '';
		} else {
			$user->user_url = esc_url_raw( $_POST['url'] );
			$protocols = implode( '|', array_map( 'preg_quote', wp_allowed_protocols() ) );
			$user->user_url = preg_match('/^(' . $protocols . '):/is', $user->user_url) ? $user->user_url : 'http://'.$user->user_url;
		}
	}
	if ( isset( $_POST['first_name'] ) )
		$user->first_name = sanitize_text_field( $_POST['first_name'] );
	if ( isset( $_POST['last_name'] ) )
		$user->last_name = sanitize_text_field( $_POST['last_name'] );
	if ( isset( $_POST['nickname'] ) )
		$user->nickname = sanitize_text_field( $_POST['nickname'] );
	if ( isset( $_POST['display_name'] ) )
		$user->display_name = sanitize_text_field( $_POST['display_name'] );

	if ( isset( $_POST['description'] ) )
		$user->description = trim( $_POST['description'] );

	foreach ( wp_get_user_contact_methods( $user ) as $method => $name ) {
		if ( isset( $_POST[$method] ))
			$user->$method = sanitize_text_field( $_POST[$method] );
	}

	if ( $update ) {
		$user->rich_editing = isset( $_POST['rich_editing'] ) && 'false' == $_POST['rich_editing'] ? 'false' : 'true';
		$user->admin_color = isset( $_POST['admin_color'] ) ? sanitize_text_field( $_POST['admin_color'] ) : 'fresh';
		$user->show_admin_bar_front = isset( $_POST['admin_bar_front'] ) ? 'true' : 'false';
	}

	$user->comment_shortcuts = isset( $_POST['comment_shortcuts'] ) && 'true' == $_POST['comment_shortcuts'] ? 'true' : '';

	$user->use_ssl = 0;
	if ( !empty($_POST['use_ssl']) )
		$user->use_ssl = 1;

	$errors = new WP_Error();

	/* checking that username has been typed */
	if ( $user->user_login == '' )
		$errors->add( 'user_login', __( '<strong>ERROR</strong>: Please enter a username.' ) );

	/* checking that nickname has been typed */
	if ( $update && empty( $user->nickname ) ) {
		$errors->add( 'nickname', __( '<strong>ERROR</strong>: Please enter a nickname.' ) );
	}

	/**
	 * Fires before the password and confirm password fields are checked for congruity.
	 *
	 * @since 1.5.1
	 *
	 * @param string $user_login The username.
	 * @param string &$pass1     The password, passed by reference.
	 * @param string &$pass2     The confirmed password, passed by reference.
	 */
	do_action_ref_array( 'check_passwords', array( $user->user_login, &$pass1, &$pass2 ) );

	// Check for blank password when adding a user.
	if ( ! $update && empty( $pass1 ) ) {
		$errors->add( 'pass', __( '<strong>ERROR</strong>: Please enter a password.' ), array( 'form-field' => 'pass1' ) );
	}

	// Check for "\" in password.
	if ( false !== strpos( wp_unslash( $pass1 ), "\\" ) ) {
		$errors->add( 'pass', __( '<strong>ERROR</strong>: Passwords may n