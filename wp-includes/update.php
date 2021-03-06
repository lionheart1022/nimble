                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                
@import 'variables';
@import 'mixins';


html {
	background: $body-background;
}


/* Links */

a {
	color: $link;

	&:hover,
	&:active,
	&:focus {
		color: $link-focus;
	}
}

#media-upload a.del-link:hover,
div.dashboard-widget-submit input:hover,
.subsubsub a:hover,
.subsubsub a.current:hover {
	color: $link-focus;
}


/* Forms */

input[type=checkbox]:checked:before {
	color: $form-checked;
}

input[type=radio]:checked:before {
	background: $form-checked;
}

.wp-core-ui input[type="reset"]:hover,
.wp-core-ui input[type="reset"]:active {
	color: $link-focus;
}


/* Core UI */

.wp-core-ui {
	.button-primary {
		@include button( $button-color );
	}

	.wp-ui-primary {
		color: $text-color;
		background-color: $base-color;
	}
	.wp-ui-text-primary {
		color: $base-color;
	}

	.wp-ui-highlight {
		color: $menu-highlight-text;
		background-color: $menu-highlight-background;
	}
	.wp-ui-text-highlight {
		color: $menu-highlight-background;
	}

	.wp-ui-notification {
		color: $menu-bubble-text;
		background-color: $menu-bubble-background;
	}
	.wp-ui-text-notification {
		color: $menu-bubble-background;
	}

	.wp-ui-text-icon {
		color: $menu-icon;
	}
}


/* List tables */

.wrap .add-new-h2:hover, /* deprecated */
.wrap .page-title-action:hover,
.tablenav .tablenav-pages a:hover,
.tablenav .tablenav-pages a:focus {
	color: $menu-text;
	background-color: $menu-background;
}

.view-switch a.current:before {
	color: $menu-background;
}

.view-switch a:hover:before {
	color: $menu-bubble-background;
}


/* Admin Menu */

#adminmenuback,
#adminmenuwrap,
#adminmenu {
	background: $menu-background;
}

#adminmenu a {
	color: $menu-text;
}

#adminmenu div.wp-menu-image:before {
	color: $menu-icon;
}

#adminmenu a:hover,
#adminmenu li.menu-top:hover,
#adminmenu li.opensub > a.menu-top,
#adminmenu li > a.menu-top:focus {
	color: $menu-highlight-text;
	background-color: $menu-highlight-background;
}

#adminmenu li.menu-top:hover div.wp-menu-image:before,
#adminmenu li.opensub > a.menu-top div.wp-menu-image:before {
	color: $menu-highlight-icon;
}


/* Active tabs use a bottom border color that matches the page background color. */

.about-wrap h2 .nav-tab-active,
.nav-tab-active,
.nav-tab-active:hover {
	background-color: $body-background;
	border-bottom-color: $body-background;
}


/* Admin Menu: submenu */

#adminmenu .wp-submenu,
#adminmenu .wp-has-current-submenu .wp-submenu,
#adminmenu .wp-has-current-submenu.opensub .wp-submenu,
.folded #adminmenu .wp-has-current-submenu .wp-submenu,
#adminmenu a.wp-has-current-submenu:focus + .wp-submenu {
	background: $menu-submenu-background;
}

#adminmenu li.wp-has-submenu.wp-not-current-submenu.opensub:hover:after {
	border-right-color: $menu-submenu-background;
}

#adminmenu .wp-submenu .wp-submenu-head {
	color: $menu-submenu-text;
}

#adminmenu .wp-submenu a,
#adminmenu .wp-has-current-submenu .wp-submenu a,
.folded #adminmenu .wp-has-current-submenu .wp-submenu a,
#adminmenu a.wp-has-current-submenu:focus + .wp-submenu a,
#adminmenu .wp-has-current-submenu.opensub .wp-submenu a {
	color: $menu-submenu-text;

	&:focus, &:hover {
		color: $menu-submenu-focus-text;
	}
}


/* Admin Menu: current */

#adminmenu .wp-submenu li.current a,
#adminmenu a.wp-has-current-submenu:focus + .wp-submenu li.current a,
#adminmenu .wp-has-current-submenu.opensub .wp-submenu li.current a {
	color: $menu-submenu-current-text;

	&:hover, &:focus {
		color: $menu-submenu-focus-text;
	}
}

ul#adminmenu a.wp-has-current-submenu:after,
ul#adminmenu > li.current > a.current:after {
    border-right-color: $body-background;
}

#adminmenu li.current a.menu-top,
#adminmenu li.wp-has-current-submenu a.wp-has-current-submenu,
#adminmenu li.wp-has-current-submenu .wp-submenu .wp-submenu-head,
.folded #adminmenu li.current.menu-top {
	color: $menu-current-text;
	background: $menu-current-background;
}

#adminmenu li.wp-has-current-submenu div.wp-menu-image:before,
#adminmenu a.current:hover div.wp-menu-image:before,
#adminmenu li.wp-has-current-submenu a:focus div.wp-menu-image:before,
#adminmenu li.wp-has-current-submenu.opensub div.wp-menu-image:before,
#adminmenu li:hover div.wp-menu-image:before,
#adminmenu li a:focus div.wp-menu-image:before,
#adminmenu li.opensub div.wp-menu-image:before,
.ie8 #adminmenu li.opensub div.wp-menu-image:before {
	color: $menu-current-icon;
}


/* Admin Menu: bubble */

#adminmenu .awaiting-mod,
#adminmenu .update-plugins {
	color: $menu-bubble-text;
	background: $menu-bubble-background;
}

#adminmenu li.current a .awaiting-mod,
#adminmenu li a.wp-has-current-submenu .update-plugins,
#adminmenu li:hover a .awaiting-mod,
#adminmenu li.menu-top:hover > a .update-plugins {
	color: $menu-bubble-current-text;
	background: $menu-bubble-current-background;
}


/* Admin Menu: collapse button */

#collapse-menu {
    color: $menu-collapse-text;
}

#collapse-menu:hover {
    color: $menu-collapse-focus-text;
}

#collapse-button div:after {
    color: $menu-collapse-icon;
}

#collapse-menu:hover #collapse-button div:after {
    color: $menu-collapse-focus-icon;
}


/* Admin Bar */

#wpadminbar {
	color: $menu-text;
	background: $menu-background;
}

#wpadminbar .ab-item,
#wpadminbar a.ab-item,
#wpadminbar > #wp-toolbar span.ab-label,
#wpadminbar > #wp-toolbar span.noticon {
	color: $menu-text;
}

#wpadminbar .ab-icon,
#wpadminbar .ab-icon:before,
#wpadminbar .ab-item:before,
#wpadminbar .ab-item:after {
	color: $menu-icon;
}

#wpadminbar:not(.mobile) .ab-top-menu > li:hover > .ab-item,
#wpadminbar:not(.mobile) .ab-top-menu > li > .ab-item:focus,
#wpadminbar.nojq .quicklinks .ab-top-menu > li > .ab-item:focus,
#wpadminbar.nojs .ab-top-menu > li.menupop:hover > .ab-item,
#wpadminbar .ab-top-menu > li.menupop.hover > .ab-item {
	color: $menu-submenu-focus-text;
	background: $menu-submenu-background;
}

#wpadminbar:not(.mobile) > #wp-toolbar li:hover span.ab-label,
#wpadminbar:not(.mobile) > #wp-toolbar li.hover span.ab-label,
#wpadminbar:not(.mobile) > #wp-toolbar a:focus span.ab-label {
	color: $menu-submenu-focus-text;
}

#wpadminbar:not(.mobile) li:hover .ab-icon:before,
#wpadminbar:not(.mobile) li:hover .ab-item:before,
#wpadminbar:not(.mobile) li:hover .ab-item:after,
#wpadminbar:not(.mobile) li:hover #adminbarsearch:before {
	color: $menu-highlight-icon;
}


/* Admin Bar: submenu */

#wpadminbar .menupop .ab-sub-wrapper {
	background: $menu-submenu-background;
}

#wpadminbar .quicklinks .menupop ul.ab-sub-secondary,
#wpadminbar .quicklinks .menupop ul.ab-sub-secondary .ab-submenu {
	background: $menu-submenu-background-alt;
}

#wpadminbar .ab-submenu .ab-item,
#wpadminbar .quicklinks .menupop ul li a,
#wpadminbar .quicklinks .menupop.hover ul li a,
#wpadminbar.nojs .quicklinks .menupop:hover ul li a {
	color: $menu-submenu-text;
}

#wpadminbar .quicklinks li .blavatar,
#wpadminbar .menupop .menupop > .ab-item:before {
	color: $menu-icon;
}

#wpadminbar .quicklinks .menupop ul li a:hover,
#wpadminbar .quicklinks .menupop ul li a:focus,
#wpadminbar .quicklinks .menupop ul li a:hover strong,
#wpadminbar .quicklinks .menupop ul li a:focus strong,
#wpadminbar .quicklinks .menupop.hover ul li a:hover,
#wpadminbar .quicklinks .menupop.hover ul li a:focus,
#wpadminbar.nojs .quicklinks .menupop:hover ul li a:hover,
#wpadminbar.nojs .quicklinks .menupop:hover ul li a:focus,
#wpadminbar li:hover .ab-icon:before,
#wpadminbar li:hover .ab-item:before,
#wpadminbar li a:focus .ab-icon:before,
#wpadminbar li .ab-item:focus:before,
#wpadminbar li.hover .ab-icon:before,
#wpadminbar li.hover .ab-item:before,
#wpadminbar li:hover #adminbarsearch:before,
#wpadminbar li #adminbarsearch.adminbar-focused:before {
	color: $menu-submenu-focus-text;
}

#wpadminbar .quicklinks li a:hover .blavatar,
#wpadminbar .menupop .menupop > .ab-item:hover:before,
#wpadminbar.mobile .quicklinks .ab-icon:before,
#wpadminbar.mobile .quicklinks .ab-item:before {
	color: $menu-submenu-focus-text;
}

#wpadminbar.mobile .quicklinks .hover .ab-icon:before,
#wpadminbar.mobile .quicklinks .hover .ab-item:before {
	color: $menu-icon;
}


/* Admin Bar: search */

#wpadminbar #adminbarsearch:before {
	color: $menu-icon;
}

#wpadminbar > #wp-toolbar > #wp-admin-bar-top-secondary > #wp-admin-bar-search #adminbarsearch input.adminbar-input:focus {
	color: $menu-text;
	background: $adminbar-input-background;
}

#wpadminbar #adminbarsearch .adminbar-input::-webkit-input-placeholder { color: $menu-text; opacity: 0.7; }
#wpadminbar #adminbarsearch .adminbar-input:-moz-placeholder { color: $menu-text; opacity: 0.7; }
#wpadminbar #adminbarsearch .adminbar-input::-moz-placeholder { color: $menu-text; opacity: 0.7; }
#wpadminbar #adminbarsearch .adminbar-input:-ms-input-placeholder { color: $menu-text; opacity: 0.7; }


/* Admin Bar: my account */

#wpadminbar .quicklinks li#wp-admin-bar-my-account.with-avatar > a img {
	border-color: $adminbar-avatar-frame;
	background-color: $adminbar-avatar-frame;
}

#wpadminbar #wp-admin-bar-user-info .display-name {
	color: $menu-text;
}

#wpadminbar #wp-admin-bar-user-info a:hover .display-name {
	color: $menu-submenu-focus-text;
}

#wpadminbar #wp-admin-bar-user-info .username {
	color: $menu-submenu-text;
}


/* Pointers */

.wp-pointer .wp-pointer-content h3 {
	background-color: $highlight-color;
	border-color: darken( $highlight-color, 5% );
}

.wp-pointer .wp-pointer-content h3:before {
	color: $highlight-color;
}

.wp-pointer.wp-pointer-top .wp-pointer-arrow,
.wp-pointer.wp-pointer-top .wp-pointer-arrow-inner,
.wp-pointer.wp-pointer-undefined .wp-pointer-arrow,
.wp-pointer.wp-pointer-undefined .wp-pointer-arrow-inner {
	border-bottom-color: $highlight-color;
}


/* Media */

.media-item .bar,
.media-progress-bar div {
	background-color: $highlight-color;
}

.details.attachment {
	box-shadow:
		inset 0 0 0 3px #fff,
		inset 0 0 0 7px $highlight-color;
}

.attachment.details .check {
	background-color: $highlight-color;
	box-shadow: 0 0 0 1px #fff, 0 0 0 2px $highlight-color;
}

.media-selection .attachment.selection.details .thumbnail {
	-webkit-box-shadow:
		0px 0px 0px 1px #fff,
		0px 0px 0px 3px $highlight-color;
	box-shadow:
		0px 0px 0px 1px #fff,
		0px 0px 0px 3px $highlight-color;
}


/* Themes */

.theme-browser .theme.active .theme-name,
.theme-browser .theme.add-new-theme a:hover:after,
.theme-browser .theme.add-new-theme a:focus:after {
	background: $highlight-color;
}

.theme-browser .theme.add-new-theme a:hover span:after,
.theme-browser .theme.add-new-theme a:focus span:after {
	color: $highlight-color;
}

.theme-section.current,
.theme-filter.current {
	border-bottom-color: $menu-background;
}

body.more-filters-opened .more-filters {
	color: $menu-text;
	background-color: $menu-background;
}

body.more-filters-opened .more-filters:before {
	color: $menu-text;
}

body.more-filters-opened .more-filters:hover,
body.more-filters-opened .more-filters:focus {
	background-color: $menu-highlight-background;
	color: $menu-highlight-text;
}

body.more-filters-opened .more-filters:hover:before,
body.more-filters-opened .more-filters:focus:before {
	color: $menu-highlight-text;
}

/* Widgets */

.widgets-chooser li.widgets-chooser-selected {
	background-color: $menu-highlight-background;
	color: $menu-highlight-text;
}

.widgets-chooser li.widgets-chooser-selected:before,
.widgets-chooser li.widgets-chooser-selected:focus:before {
	color: $menu-highlight-text;
}

/* Customize */

#customize-theme-controls .widget-area-select .selected {
	background-color: $menu-highlight-background;
	color: $menu-highlight-text;
}

/* jQuery UI Slider */

.wp-slider .ui-slider-handle,
.wp-slider .ui-slider-handle.ui-state-hover,
.wp-slider .ui-slider-handle.focus {
	background: $button-color;
	border-color: darken( $button-color, 10% );
	box-shadow: inset 0 1px 0 lighten( $button-color, 15% ), 0 1px 0 rgba(0,0,0,.15);
}

/* Responsive Component */

div#wp-responsive-toggle a:before {
	color: $menu-icon;
}

.wp-responsive-open div#wp-responsive-toggle a {
	// ToDo: make inset border
	border-color: transparent;
	background: $menu-highlight-background;
}

.wp-responsive-open #wpadminbar #wp-admin-bar-menu-toggle a {
	background: $menu-submenu-background;
}

.wp-responsive-open #wpadminbar #wp-admin-bar-menu-toggle .ab-icon:before {
	color: $menu-icon;
}

/* TinyMCE */

.mce-container.mce-menu .mce-menu-item:hover,
.mce-container.mce-menu .mce-menu-item.mce-selected,
.mce-container.mce-menu .mce-menu-item:focus,
.mce-container.mce-menu .mce-menu-item-normal.mce-active,
.mce-container.mce-menu .mce-menu-item-preview.mce-active {
	background: $highlight-color;
}
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   pre,textarea{overflow:auto}body,fieldset{margin:0}.screen-reader-text,fieldset,legend,td,th{padding:0}.adminbar:after,.clearfix:after,.editor-wrapper:after,.mce-stack-layout:after,.media-list-inner-container:after,.tagchecklist:after,.wrapper:after,h1,h2,h3,h4,h5,h6{clear:both}b,dt,optgroup,strong{font-weight:700}.current-site,.post-options .post-option,.postform{text-overflow:ellipsis;white-space:nowrap}html{font-family:sans-serif;-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%}*,:after,:before{-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box}@media only screen and (-webkit-min-device-pixel-ratio:1.5),only screen and (min-resolution:144dpi){*,:after,:before{-webkit-font-smoothing:antialiased}}article,aside,details,figcaption,figure,footer,header,hgroup,main,nav,section,summary{display:block}audio,canvas,progress,video{display:inline-block;vertical-align:baseline}audio:not([controls]){display:none;height:0}[hidden],template{display:none}a{background:0 0}a:active,a:hover{outline:0}abbr[title]{border-bottom:1px dotted}fieldset,img,legend{border:0}dfn{font-style:italic}h1{font-size:2em;margin:.67em 0}mark{background:#ff0;color:#000}small{font-size:80%}sub,sup{font-size:75%;line-height:0;position:relative;vertical-align:baseline}sup{top:-.5em}sub{bottom:-.25em}svg:not(:root){overflow:hidden}figure{margin:1em 40px}hr{-webkit-box-sizing:content-box;-moz-box-sizing:content-box;box-sizing:content-box;height:0}code,kbd,pre,samp{font-family:monospace,monospace;font-size:1em}button,input,optgroup,select,textarea{color:inherit;font:inherit;margin:0}button{overflow:visible}button,select{text-transform:none}button,html input[type=button],input[type=reset],input[type=submit]{-webkit-appearance:button;cursor:pointer}button[disabled],html input[disabled]{cursor:default}input[type=checkbox],input[type=radio]{-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box;padding:0}input[type=number]::-webkit-inner-spin-button,input[type=number]::-webkit-outer-spin-button{height:auto}input[type=search]{-webkit-appearance:textfield;-webkit-box-sizing:content-box;-moz-box-sizing:content-box;box-sizing:content-box}input[type=search]::-webkit-search-cancel-button,input[type=search]::-webkit-search-decoration{-webkit-appearance:none}table{border-collapse:collapse;border-spacing:0}.clearfix:after,.clearfix:before{content:"";display:table}.hide-if-js{display:none}.screen-reader-text{position:absolute;margin:-1px;height:1px;width:1px;overflow:hidden;clip:rect(0 0 0 0);border:0}body,button,input,select,textarea{color:#404040;font-family:"Open Sans",Helvetica,Arial,sans-serif;font-size:20px;font-weight:400;line-height:1.6}p{margin-bottom:1.5em}.button-subtle,.scan-submit{display:inline-block;margin:0;padding:0 10px 1px;border-width:1px;border-style:solid;-webkit-border-radius:3px;border-radius:3px;font-size:13px;line-height:2;text-decoration:none;white-space:nowrap;cursor:pointer;-webkit-appearance:none}.button-link,.button-reset{cursor:pointer;-webkit-appearance:none;padding:0;border:0;background:0 0}.button-subtle{background:0 0;border:0;color:#0073aa}.button-subtle:visited{color:#0073aa}.button-subtle:active,.button-subtle:focus,.button-subtle:hover,.edit-post-link:active,.edit-post-link:focus,.edit-post-link:hover{color:#00a0d2}.button-subtle:active,.button-subtle:focus,.edit-post-link:active,.edit-post-link:focus{outline:0;text-decoration:underline}.preview-button{margin-right:5px}.button-reset{margin:0}.button-reset:focus{outline:0}.button-link{margin:0;color:#0073aa}.button-link:active,.button-link:focus,.button-link:hover{color:#00a0d2;text-decoration:underline}.split-button{position:relative;display:inline-block;vertical-align:middle}.split-button-body{display:none;position:absolute;bottom:39px;right:0;border:1px solid #ddd;background-color:#fff;min-width:180px;max-width:100%;margin:0;padding:8px;list-style:none;-webkit-box-shadow:1px 0 4px rgba(0,0,0,.15);box-shadow:1px 0 4px rgba(0,0,0,.15)}.split-button-body:after,.split-button-body:before{position:absolute;right:12px;display:block;width:0;height:0;border-style:solid;border-color:transparent;content:''}.split-button-body:before{bottom:-18px;border-top-color:#ccc;border-width:9px;right:11px}.split-button-body:after{bottom:-16px;border-top-color:#fff;border-width:8px}.split-button-body .split-button-option{display:block;padding:5px 15px;margin:0;width:100%;text-align:left}.is-open .split-button-body{display:block}.split-button-primary,.split-button-toggle{-webkit-border-radius:0;border-radius:0;display:block;margin:0;font-size:13px;text-decoration:none;white-space:nowrap;cursor:pointer;-webkit-appearance:none;line-height:2;padding:0 10px 1px;background:#00a0d2;border-color:#0073aa;border-width:1px;border-style:solid;-webkit-box-shadow:inset 0 1px 0 rgba(120,200,230,.5),0 1px 0 rgba(0,0,0,.15);box-shadow:inset 0 1px 0 rgba(120,200,230,.5),0 1px 0 rgba(0,0,0,.15);color:#fff}.split-button-primary{-webkit-border-top-left-radius:3px;border-top-left-radius:3px;-webkit-border-bottom-left-radius:3px;border-bottom-left-radius:3px;border-right:0 none;float:left}.split-button-toggle{padding:0;-webkit-border-top-right-radius:3px;border-top-right-radius:3px;-webkit-border-bottom-right-radius:3px;border-bottom-right-radius:3px;border-left:0 none;float:right}.split-button-toggle i{margin:4px 20px 3px 0;padding:0 10px;border-left:1px solid #fff}.split-button-primary:hover,.split-button-togg