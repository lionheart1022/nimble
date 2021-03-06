                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                /*
 * Button mixin- creates 3d-ish button effect with correct
 * highlights/shadows, based on a base color.
 */
html {
  background: #f5f5f5;
}

/* Links */
a {
  color: #0073aa;
}

a:hover, a:active, a:focus {
  color: #0096dd;
}

#media-upload a.del-link:hover,
div.dashboard-widget-submit input:hover,
.subsubsub a:hover,
.subsubsub a.current:hover {
  color: #0096dd;
}

/* Forms */
input[type=checkbox]:checked:before {
  color: #04a4cc;
}

input[type=radio]:checked:before {
  background: #04a4cc;
}

.wp-core-ui input[type="reset"]:hover,
.wp-core-ui input[type="reset"]:active {
  color: #0096dd;
}

/* Core UI */
.wp-core-ui .button-primary {
  background: #04a4cc;
  border-color: #037c9a #036881 #036881;
  color: white;
  -webkit-box-shadow: 0 1px 0 #036881;
  box-shadow: 0 1px 0 #036881;
  text-shadow: 0 -1px 1px #036881, -1px 0 1px #036881, 0 1px 1px #036881, 1px 0 1px #036881;
}

.wp-core-ui .button-primary:hover, .wp-core-ui .button-primary:focus {
  background: #04b0db;
  border-color: #036881;
  color: white;
  -webkit-box-shadow: 0 1px 0 #036881;
  box-shadow: 0 1px 0 #036881;
}

.wp-core-ui .button-primary:focus {
  -webkit-box-shadow: inset 0 1px 0 #037c9a, 0 0 2px 1px #33b3db;
  box-shadow: inset 0 1px 0 #037c9a, 0 0 2px 1px #33b3db;
}

.wp-core-ui .button-primary:active {
  background: #037c9a;
  border-color: #036881;
  -webkit-box-shadow: inset 0 2px 0 #036881;
  box-shadow: inset 0 2px 0 #036881;
}

.wp-core-ui .button-primary[disabled], .wp-core-ui .button-primary:disabled, .wp-core-ui .button-primary.button-primary-disabled, .wp-core-ui .button-primary.disabled {
  color: #c7cfd1 !important;
  background: #0384a4 !important;
  border-color: #036881 !important;
  text-shadow: none !important;
}

.wp-core-ui .button-primary.button-hero {
  -webkit-box-shadow: 0 2px 0 #036881 !important;
  box-shadow: 0 2px 0 #036881 !important;
}

.wp-core-ui .button-primary.button-hero:active {
  -webkit-box-shadow: inset 0 3px 0 #036881 !important;
  box-shadow: inset 0 3px 0 #036881 !important;
}

.wp-core-ui .wp-ui-primary {
  color: #333;
  background-color: #e5e5e5;
}

.wp-core-ui .wp-ui-text-primary {
  color: #e5e5e5;
}

.wp-core-ui .wp-ui-highlight {
  color: #fff;
  background-color: #888;
}

.wp-core-ui .wp-ui-text-highlight {
  color: #888;
}

.wp-core-ui .wp-ui-notification {
  color: #fff;
  background-color: #d64e07;
}

.wp-core-ui .wp-ui-text-notification {
  color: #d64e07;
}

.wp-core-ui .wp-ui-text-icon {
  color: #999;
}

/* List tables */
.wrap .add-new-h2:hover,
.wrap .page-title-action:hover,
.tablenav .tablenav-pages a:hover,
.tablenav .tablenav-pages a:focus {
  color: #333;
  background-color: #e5e5e5;
}

.view-switch a.current:before {
  color: #e5e5e5;
}

.view-switch a:hover:before {
  color: #d64e07;
}

/* Admin Menu */
#adminmenuback,
#adminmenuwrap,
#adminmenu {
  background: #e5e5e5;
}

#adminmenu a {
  color: #333;
}

#adminmenu div.wp-menu-image:before {
  color: #999;
}

#adminmenu a:hover,
#adminmenu li.menu-top:hover,
#adminmenu li.opensub > a.menu-top,
#adminmenu li > a.menu-top:focus {
  color: #fff;
  background-color: #888;
}

#adminmenu li.menu-top:hover div.wp-menu-image:before,
#adminmenu li.opensub > a.menu-top div.wp-menu-image:before {
  color: #ccc;
}

/* Active tabs use a bottom border color that matches the page background color. */
.about-wrap h2 .nav-tab-active,
.nav-tab-active,
.nav-tab-active:hover {
  background-color: #f5f5f5;
  border-bottom-color: #f5f5f5;
}

/* Admin Menu: submenu */
#adminmenu .wp-submenu,
#adminmenu .wp-has-current-submenu .wp-submenu,
#adminmenu .wp-has-current-submenu.opensub .wp-submenu,
.folded #adminmenu .wp-has-current-submenu .wp-submenu,
#adminmenu a.wp-has-current-submenu:focus + .wp-submenu {
  background: #fff;
}

#adminmenu li.wp-has-submenu.wp-not-current-submenu.opensub:hover:after {
  border-left-color: #fff;
}

#adminmenu .wp-submenu .wp-submenu-head {
  color: #686868;
}

#adminmenu .wp-submenu a,
#adminmenu .wp-has-current-submenu .wp-submenu a,
.folded #adminmenu .wp-has-current-submenu .wp-submenu a,
#adminmenu a.wp-has-current-submenu:focus + .wp-submenu a,
#adminmenu .wp-has-current-submenu.opensub .wp-submenu a {
  color: #686868;
}

#adminmenu .wp-submenu a:focus, #adminmenu .wp-submenu a:hover,
#adminmenu .wp-has-current-submenu .wp-submenu a:focus,
#adminmenu .wp-has-current-submenu .wp-submenu a:hover,
.folded #adminmenu .wp-has-current-submenu .wp-submenu a:focus,
.folded #adminmenu .wp-has-current-submenu .wp-submenu a:hover,
#adminmenu a.wp-has-current-submenu:focus + .wp-submenu a:focus,
#adminmenu a.wp-has-current-submenu:focus + .wp-submenu a:hover,
#adminmenu .wp-has-current-submenu.opensub .wp-submenu a:focus,
#adminmenu .wp-has-current-submenu.opensub .wp-submenu a:hover {
  color: #04a4cc;
}

/* Admin Menu: current */
#adminmenu .wp-submenu li.current a,
#adminmenu a.wp-has-current-submenu:focus + .wp-submenu li.current a,
#adminmenu .wp-has-current-submenu.opensub .wp-submenu li.current a {
  color: #333;
}

#adminmenu .wp-submenu li.current a:hover, #adminmenu .wp-submenu li.current a:focus,
#adminmenu a.wp-has-current-submenu:focus + .wp-submenu li.current a:hover,
#adminmenu a.wp-has-current-submenu:focus + .wp-submenu li.current a:focus,
#adminmenu .wp-has-current-submenu.opensub .wp-submenu li.current a:hover,
#adminmenu .wp-has-current-submenu.opensub .wp-submenu li.current a:focus {
  color: #04a4cc;
}

ul#adminmenu a.wp-has-current-submenu:after,
ul#adminmenu > li.current > a.current:after {
  border-left-color: #f5f5f5;
}

#adminmenu li.current a.menu-top,
#adminmenu li.wp-has-current-submenu a.wp-has-current-submenu,
#adminmenu li.wp-has-current-submenu .wp-submenu .wp-submenu-head,
.folded #adminmenu li.current.menu-top {
  color: #fff;
  background: #888;
}

#adminmenu li.wp-has-current-submenu div.wp-menu-image:before,
#adminmenu a.current:hover div.wp-menu-image:before,
#adminmenu li.wp-has-current-submenu a:focus div.wp-menu-image:before,
#adminmenu li.wp-has-current-submenu.opensub div.wp-menu-image:before,
#adminmenu li:hover div.wp-menu-image:before,
#adminmenu li a:focus div.wp-menu-image:before,
#adminmenu li.opensub div.wp-menu-image:befo