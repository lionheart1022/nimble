                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                /*
 * Button mixin- creates 3d-ish button effect with correct
 * highlights/shadows, based on a base color.
 */
html {
  background: #f1f1f1;
}

/* Links */
a {
  color: #0073aa;
}

a:hover,
a:active,
a:focus {
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
  color: #dd823b;
}

input[type=radio]:checked:before {
  background: #dd823b;
}

.wp-core-ui input[type="reset"]:hover,
.wp-core-ui input[type="reset"]:active {
  color: #0096dd;
}

/* Core UI */
.wp-core-ui .button-primary {
  background: #dd823b;
  border-color: #c36922;
  color: white;
  -webkit-box-shadow: inset 0 1px 0 #e8ac7c, 0 1px 0 rgba(0, 0, 0, 0.15);
  box-shadow: inset 0 1px 0 #e8ac7c, 0 1px 0 rgba(0, 0, 0, 0.15);
}

.wp-core-ui .button-primary:hover,
.wp-core-ui .button-primary:focus {
  background: #d97426;
  border-color: #ad5d1e;
  color: white;
  -webkit-box-shadow: inset 0 1px 0 #e59e66;
  box-shadow: inset 0 1px 0 #e59e66;
}

.wp-core-ui .button-primary:focus {
  -webkit-box-shadow: inset 0 1px 0 #e59e66, 0 0 0 1px #5b9dd9, 0 0 2px 1px rgba(30, 140, 190, 0.8);
  box-shadow: inset 0 1px 0 #e59e66, 0 0 0 1px #5b9dd9, 0 0 2px 1px rgba(30, 140, 190, 0.8);
}

.wp-core-ui .button-primary:active {
  background: #c36922;
  border-color: #ad5d1e;
  color: white;
  -webkit-box-shadow: inset 0 2px 5px -3px rgba(0, 0, 0, 0.5), 0 0 0 1px #5b9dd9, 0 0 2px 1px rgba(30, 140, 190, 0.8);
  box-shadow: inset 0 2px 5px -3px rgba(0, 0, 0, 0.5), 0 0 0 1px #5b9dd9, 0 0 2px 1px rgba(30, 140, 190, 0.8);
}

.wp-core-ui .button-primary[disabled],
.wp-core-ui .button-primary:disabled,
.wp-core-ui .button-primary.button-primary-disabled,
.wp-core-ui .button-primary.disabled {
  color: #d1cbc7 !important;
  background: #cc6d23 !important;
  border-color: #ad5d1e !important;
  text-shadow: none !important;
}

.wp-core-ui .wp-ui-primary {
  color: #fff;
  background-color: #cf4944;
}

.wp-core-ui .wp-ui-text-primary {
  color: #cf4944;
}

.wp-core-ui .wp-ui-highlight {
  color: #fff;
  background-color: #dd823b;
}

.wp-core-ui .wp-ui-text-highlight {
  color: #dd823b;
}

.wp-core-ui .wp-ui-notification {
  color: #fff;
  background-color: #ccaf0b;
}

.wp-core-ui .wp-ui-text-notification {
  color: #ccaf0b;
}

.wp-core-ui .wp-ui-text-icon {
  color: #f3f1f1;
}

/* List tables */
.wrap .add-new-h2:hover, .wrap .page-title-action:hover,
.tablenav .tablenav-pages a:hover,
.tablenav .tablenav-pages a:focus {
  color: #fff;
  background-color: #cf4944;
}

.view-switch a.current:before {
  color: #cf4944;
}

.view-switch a:hover:before {
  color: #ccaf0b;
}

/* Admin Menu */
#adminmenuback,
#adminmenuwrap,
#adminmenu {
  background: #cf4944;
}

#adminmenu a {
  color: #fff;
}

#adminmenu div.wp-menu-image:before {
  color: #f3f1f1;
}

#adminmenu a:hover,
#adminmenu li.menu-top:hover,
#adminmenu li.opensub > a.menu-top,
#adminmenu li > a.menu-top:focus {
  color: #fff;
  background-color: #dd823b;
}

#adminmenu li.menu-top:hover div.wp-menu-image:before,
#adminmenu li.opensub > a.menu-top div.wp-menu-image:before {
  color: #fff;
}

/* Active tabs use a bottom border color that matches the page background color. */
.about-wrap h2 .nav-tab-active,
.nav-tab-active,
.nav-tab-active:hover {
  background-color: #f1f1f1;
  border-bottom-color: #f1f1f1;
}

/* Admin Menu: submenu */
#adminmenu .wp-submenu,
#adminmenu .wp-has-current-submenu .wp-submenu,
#adminmenu .wp-has-current-submenu.opensub .wp-submenu,
.folded #adminmenu .wp-has-current-submenu .wp-submenu,
#adminmenu a.wp-has-current-submenu:focus + .wp-submenu {
  background: #be3631;
}

#adminmenu li.wp-has-submenu.wp-not-current-submenu.opensub:hover:after {
  border-right-color: #be3631;
}

#adminmenu .wp-submenu .wp-submenu-head {
  color: #f1c8c7;
}

#adminmenu .wp-submenu a,
#adminmenu .wp-has-current-submenu .wp-submenu a,
.folded #adminmenu .wp-has-current-submenu .wp-submenu a,
#adminmenu a.wp-has-current-submenu:focus + .wp-submenu a,
#adminmenu .wp-has-current-submenu.opensub .wp-submenu a {
  color: #f1c8c7;
}

#adminmenu .wp-submenu a:focus,
#adminmenu .wp-submenu a:hover,
#adminmenu .wp-has-current-submenu .wp-submenu a:focus,
#adminmenu .wp-has-current-submenu .wp-submenu a:hover,
.folded #adminmenu .wp-has-current-submenu .wp-submenu a:focus,
.folded #adminmenu .wp-has-current-submenu .wp-submenu a:hover,
#adminmenu a.wp-has-current-submenu:focus + .wp-submenu a:focus,
#adminmenu a.wp-has-current-submenu:focus + .wp-submenu a:hover,
#adminmenu .wp-has-current-submenu.opensub .wp-submenu a:focus, #adminmenu .wp-has-current-submenu.opensub .wp-submen