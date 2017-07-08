n:hover,
.tablenav .tablenav-pages a:hover,
.tablenav .tablenav-pages a:focus {
  color: #fff;
  background-color: #52accc;
}

.view-switch a.current:before {
  color: #52accc;
}

.view-switch a:hover:before {
  color: #e1a948;
}

/* Admin Menu */
#adminmenuback,
#adminmenuwrap,
#adminmenu {
  background: #52accc;
}

#adminmenu a {
  color: #fff;
}

#adminmenu div.wp-menu-image:before {
  color: #e5f8ff;
}

#adminmenu a:hover,
#adminmenu li.menu-top:hover,
#adminmenu li.opensub > a.menu-top,
#adminmenu li > a.menu-top:focus {
  color: #fff;
  background-color: #096484;
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
  background: #4796b3;
}

#adminmenu li.wp-has-submenu.wp-not-current-submenu.opensub:hover:after {
  border-right-color: #4796b3;
}

#adminmenu .wp-submenu .wp-submenu-head {
  color: #e2ecf1;
}

#adminmenu .wp-submenu a,
#adminmenu .wp-has-current-submenu .wp-submenu a,
.folded #adminmenu .wp-has-current-submenu .wp-submenu a,
#adminmenu a.wp-has-current-submenu:focus + .wp-submenu a,
#adminmenu .wp-has-current-submenu.opensub .wp-submenu a {
  color: #e2ecf1;
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
  color: #fff;
}

/* Admin Menu: current */
#adminmenu .wp-submenu li.current a,
#adminmenu a.wp-has-current-submenu:focus + .wp-submenu li.current a,
#adminmenu .wp-has-current-submenu.opensub .wp-submenu li.current a {
  color: #fff;
}

#adminmenu .wp-submenu li.current a:hover, #adminmenu .wp-submenu li.current a:focus,
#adminmenu a.wp-has-current-submenu:focus + .wp-submenu li.current a:hover,
#adminmenu a.wp-has-current-submenu:focus + .wp-submenu li.current a:focus,
#adminmenu .wp-has-current-submenu.opensub .wp-submenu li.current a:hover,
#adminmenu .wp-has-current-submenu.opensub .wp-submenu li.current a:focus {
  color: #fff;
}

ul#adminmenu a.wp-has-current-submenu:after,
ul#adminmenu > li.current > a.current:after {
  border-right-color: #f1f1f1;
}

#adminmenu li.current a.menu-top,
#adminmenu li.wp-has-current-submenu a.wp-has-current-submenu,
#adminmenu li.wp-has-current-submenu .wp-submenu .wp-submenu-head,
.folded #adminmenu li.current.menu-top {
  color: #fff;
  background: #096484;
}

#adminmenu li.wp-has-current-submenu div.wp-menu-image:before,
#adminmenu a.current:hover div.wp-menu-image:before,
#adminmenu li.wp-has-current-submenu a:focus div.wp-menu-image:before,
#adminmenu li.wp-has-current-submenu.opensub div.wp-menu-image:before,
#adminmenu li:hover div.wp-menu-image:before,
#adminmenu li a:focus div.wp-menu-image:before,
#adminmenu li.opensub div.wp-menu-image:before,
.ie8 #adminmenu li.opensub div.wp-menu-image:before {
  color: #fff;
}

/* Admin Menu: bubble */
#adminmenu .awaiting-mod,
#adminmenu .update-plugins {
  color: #fff;
  background: #e1a948;
}

#adminmenu li.current a .awaiting-mod,
#adminmenu li a.wp-has-current-submenu .update-plugins,
#adminmenu li:hover a .awaiting-mod,
#adminmenu li.menu-top:hover > a .update-plugins {
  color: #fff;
  background: #4796b3;
}

/* Admin Menu: collapse button */
#collapse-menu {
  color: #e5f8ff;
}

#collapse-menu:hover {
  color: #fff;
}

#collapse-button div:after {
  color: #e5f8ff;
}

#collapse-menu:hover #collapse-button div:after {
  color: #fff;
}

/* Admin Bar */
#wpadminbar {
  color: #fff;
  background: #52accc;
}

#wpadminbar .ab-item,
#wpadminbar a.ab-item,
#wpadminbar > #wp-toolbar span.ab-label,
#wpadminbar > #wp-toolbar span.noticon {
  color: #fff;
}

#wpadminbar .ab-icon,
#wpadminbar .ab-icon:before,
#wpadminbar .ab-item:before,
#wpadminbar .ab-item:after {
  color: #e5f8ff;
}

#wpadminbar:not(.mobile) .ab-top-menu > li:hover > .ab-item,
#wpadminbar:not(.mobile) .ab-top-menu > li > .ab-item:focus,
#wpadminbar.nojq .quicklinks .ab-top-menu > li > .ab-item:focus,
#wpadminbar.nojs .ab-top-menu > li.menupop:hover > .ab-item,
#wpadminbar .ab-top-menu > li.menupop.hover > .ab-item {
  color: #fff;
  background: #4796b3;
}

#wpadminbar:not(.mobile) > #wp-toolbar li:hover span.ab-label,
#wpadminbar:not(.mobile) > #wp-toolbar li.hover span.ab-label,
#wpadminbar:not(.mobile) > #wp-toolbar a:focus span.ab-label {
  color: #fff;
}

#wpadminbar:not(.mobile) li:hover .ab-icon:before,
#wpadminbar:not(.mobile) li:hover .ab-item:before,
#wpadminbar:not(.mobile) li:hover .ab-item:after,
#wpadminbar:not(.mobile) li:hover #adminbarsearch:before {
  color: #fff;
}

/* Admin Bar: submenu */
#wpadminbar .menupop .ab-sub-wrapper {
  background: #4796b3;
}

#wpadminbar .quicklinks .menupop ul.ab-sub-secondary,
#wpadminbar .quicklinks .menupop ul.ab-sub-secondary .ab-submenu {
  background: #74b6ce;
}

#wpadminbar .ab-submenu .ab-item,
#wpadminbar .quicklinks .menupop ul li a,
#wpadminbar .quicklinks .menupop.hover ul li a,
#wpadminbar.nojs .quicklinks .menupop:hover ul li a {
  color: #e2ecf1;
}

#wpadminbar .quicklinks li .blavatar,
#wpadminbar .menupop .menupop > .ab-item:before {
  color: #e5f8ff;
}

#wpadminbar .quicklinks .menupop ul li a:hover,
#wpadminbar .quicklinks .menupop ul li a:focus,
#wpadminbar .quicklinks .menupop ul li a:hover strong,
#wpadminbar .quicklinks .menupop ul li a:focus strong,
#wpadminbar .quicklinks .ab-sub-wrapper .menupop.hover > a,
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
  color: #fff;
}

#wpadminbar .quicklinks li a:hover .blavatar,
#wpadminbar .quicklinks li a:focus .blavatar,
#wpadminbar .quicklinks .ab-sub-wrapper .menupop.hover > a .blavatar,
#wpadminbar .menupop .menupop > .ab-item:hover:before,
#wpadminbar.mobile .quicklinks .ab-icon:before,
#wpadminbar.mobile .quicklinks .ab-item:before {
  color: #fff;
}

#wpadminbar.mobile .quicklinks .hover .ab-icon:before,
#wpadminbar.mobile .quicklinks .hover .ab-item:before {
  color: #e5f8ff;
}

/* Admin Bar: search */
#wpadminbar #adminbarsearch:before {
  color: #e5f8ff;
}

#wpadminbar > #wp-toolbar > #wp-admin-bar-top-secondary > #wp-admin-bar-search #adminbarsearch input.adminbar-input:focus {
  color: #fff;
  background: #6eb9d4;
}

/* Admin Bar: my account */
#wpadminbar .quicklinks li#wp-admin-bar-my-account.with-avatar > a img {
  border-color: #6eb9d4;
  background-color: #6eb9d4;
}

#wpadminbar #wp-admin-bar-user-info .display-name {
  color: #fff;
}

#wpadminbar #wp-admin-bar-user-info a:hover .display-name {
  color: #fff;
}

#wpadminbar #wp-admin-bar-user-info .username {
  color: #e2ecf1;
}

/* Pointers */
.wp-pointer .wp-pointer-content h3 {
  background-color: #096484;
  border-color: #07526c;
}

.wp-pointer .wp-pointer-content h3:before {
  color: #096484;
}

.wp-pointer.wp-pointer-top .wp-pointer-arrow,
.wp-pointer.wp-pointer-top .wp-pointer-arrow-inner,
.wp-pointer.wp-pointer-undefined .wp-pointer-arrow,
.wp-pointer.wp-pointer-undefined .wp-pointer-arrow-inner {
  border-bottom-color: #096484;
}

/* Media */
.media-item .bar,
.media-progress-bar div {
  background-color: #096484;
}

.details.attachment {
  -webkit-box-shadow: inset 0 0 0 3px #fff, inset 0 0 0 7px #096484;
  box-shadow: inset 0 0 0 3px #fff, inset 0 0 0 7px #096484;
}

.attachment.details .check {
  background-color: #096484;
  -webkit-box-shadow: 0 0 0 1px #fff, 0 0 0 2px #096484;
  box-shadow: 0 0 0 1px #fff, 0 0 0 2px #096484;
}

.media-selection .attachment.selection.details .thumbnail {
  -webkit-box-shadow: 0px 0px 0px 1px #fff, 0px 0px 0px 3px #096484;
  box-shadow: 0px 0px 0px 1px #fff, 0px 0px 0px 3px #096484;
}

/* Themes */
.theme-browser .theme.active .theme-name,
.theme-browser .theme.add-new-theme a:hover:after,
.theme-browser .theme.add-new-theme a:focus:after {
  background: #096484;
}

.theme-browser .theme.add-new-theme a:hover span:after,
.theme-browser .theme.add-new-theme a:focus span:after {
  color: #096484;
}

.theme-section.current,
.theme-filter.current {
  border-bottom-color: #52accc;
}

body.more-filters-opened .more-filters {
  color: #fff;
  background-color: #52accc;
}

body.more-filters-opened .more-filters:before {
  color: #fff;
}

body.more-filters-opened .more-filters:hover,
body.more-filters-opened .more-filters:focus {
  background-color: #096484;
  color: #fff;
}

body.more-filters-opened .more-filters:hover:before,
body.more-filters-opened .more-filters:focus:before {
  color: #fff;
}

/* Widgets */
.widgets-chooser li.widgets-chooser-selected {
  background-color: #096484;
  color: #fff;
}

.widgets-chooser li.widgets-chooser-selected:before,
.widgets-chooser li.widgets-chooser-selected:focus:before {
  color: #fff;
}

/* Customize */
#customize-theme-controls .widget-area-select .selected {
  background-color: #096484;
  color: #fff;
}

#customize-footer-actions .devices button:focus {
  border-bottom-color: #096484;
}

/* Responsive Component */
div#wp-responsive-toggle a:before {
  color: #e5f8ff;
}

.wp-responsive-open div#wp-responsive-toggle a {
  border-color: transparent;
  background: #096484;
}

.wp-responsive-open #wpadminbar #wp-admin-bar-menu-toggle a {
  background: #4796b3;
}

.wp-responsive-open #wpadminbar #wp-admin-bar-menu-toggle .ab-icon:before {
  color: #e5f8ff;
}

/* TinyMCE */
.mce-container.mce-menu .mce-menu-item:hover,
.mce-container.mce-menu .mce-menu-item.mce-selected,
.mce-container.mce-menu .mce-menu-item:focus,
.mce-container.mce-menu .mce-menu-item-normal.mce-active,
.mce-container.mce-menu .mce-menu-item-preview.mce-active {
  background: #096484;
}
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                 /*
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
  color: #096484;
}

input[type=radio]:checked:before {
  background: #096484;
}

.wp-core-ui input[type="reset"]:hover,
.wp-core-ui input[type="reset"]:active {
  color: #0096dd;
}

/* Core UI */
.wp-core-ui .button-primary {
  background: #e1a948;
  border-color: #d39323 #bd831f #bd831f;
  color: white;
  -webkit-box-shadow: 0 1px 0 #bd831f;
  box-shadow: 0 1px 0 #bd831f;
  text-shadow: 0 -1px 1px #bd831f, -1px 0 1px #bd831f, 0 1px 1px #bd831f, 1px 0 1px #bd831f;
}

.wp-core-ui .button-primary:hover, .wp-core-ui .button-primary:focus {
  background: #e3af55;
  border-color: #bd831f;
  color: white;
  -webkit-box-shadow: 0 1px 0 #bd831f;
  box-shadow: 0 1px 0 #bd831f;
}

.wp-core-ui .button-primary:focus {
  -webkit-box-shadow: inset 0 1px 0 #d39323, 0 0 2px 1px #33b3db;
  box-shadow: inset 0 1px 0 #d39323, 0 0 2px 1px #33b3db;
}

.wp-core-ui .button-primary:active {
  background: #d39323;
  border-color: #bd831f;
  -webkit-box-shadow: inset 0 2px 0 #bd831f;
  box-shadow: inset 0 2px 0 #bd831f;
}

.wp-core-ui .button-primary[disabled], .wp-core-ui .button-primary:disabled, .wp-core-ui .button-primary.button-primary-disabled, .wp-core-ui .button-primary.disabled {
  color: #d1cdc7 !important;
  background: #db9925 !important;
  border-color: #bd831f !important;
  text-shadow: none !important;
}

.wp-core-ui .button-primary.button-hero {
  -webkit-box-shadow: 0 2px 0 #bd831f !important;
  box-shadow: 0 2px 0 #bd831f !important;
}

.wp-core-ui .button-primary.button-hero:active {
  -webkit-box-shadow: inset 0 3px 0 #bd831f !important;
  box-shadow: inset 0 3px 0 #bd831f !important;
}

.wp-core-ui .wp-ui-primary {
  color: #fff;
  background-color: #52accc;
}

.wp-core-ui .wp-ui-text-primary {
  color: #52accc;
}

.wp-core-ui .wp-ui-highlight {
  color: #fff;
  background-color: #096484;
}

.wp-core-ui .wp-ui-text-highlight {
  color: #096484;
}

.wp-core-ui .wp-ui-notification {
  color: #fff;
  background-color: #e1a948;
}

.wp-core-ui .wp-ui-text-notification {
  color: #e1a948;
}

.wp-core-ui .wp-ui-text-icon {
  color: #e5f8ff;
}

/* List tables */
.wrap .add-new-h2:hover,
.wrap .page-title-action:hover,
.tablenav .tablenav-pages a:hover,
.tablenav .tablenav-pages a:focus {
  color: #fff;
  background-color: #52accc;
}

.view-switch a.current:before {
  color: #52accc;
}

.view-switch a:hover:before {
  color: #e1a948;
}

/* Admin Menu */
#adminmenuback,
#adminmenuwrap,
#adminmenu {
  background: #52accc;
}

#adminmenu a {
  color: #fff;
}

#adminmenu div.wp-menu-image:before {
  color: #e5f8ff;
}

#adminmenu a:hover,
#adminmenu li.menu-top:hover,
#adminmenu li.opensub > a.menu-top,
#adminmenu li > a.menu-top:focus {
  color: #fff;
  background-color: #096484;
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
  background: #4796b3;
}

#adminmenu li.wp-has-submenu.wp-not-current-submenu.opensub:hover:after {
  border-left-color: #4796b3;
}

#adminmenu .wp-submenu .wp-submenu-head {
  color: #e2ecf1;
}

#adminmenu .wp-submenu a,
#adminmenu .wp-has-current-submenu .wp-submenu a,
.folded #adminmenu .wp-has-current-submenu .wp-submenu a,
#adminmenu a.wp-has-current-submenu:focus + .wp-submenu a,
#adminmenu .wp-has-current-submenu.opensub .wp-submenu a {
  color: #e2ecf1;
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
  color: #fff;
}

/* Admin Menu: current */
#adminmenu .wp-submenu li.current a,
#adminmenu a.wp-has-current-submenu:focus + .wp-submenu li.current a,
#adminmenu .wp-has-current-submenu.opensub .wp-submenu li.current a {
  color: #fff;
}

#adminmenu .wp-submenu li.current a:hover, #adminmenu .wp-submenu li.current a:focus,
#adminmenu a.wp-has-current-submenu:focus + .wp-submenu li.current a:hover,
#adminmenu a.wp-has-current-submenu:focus + .wp-submenu li.current a:focus,
#adminmenu .wp-has-current-submenu.opensub .wp-submenu li.current a:hover,
#adminmenu .wp-has-current-submenu.opensub .wp-submenu li.current a:focus {
  color: #fff;
}

ul#adminmenu a.wp-has-current-submenu:after,
ul#adminmenu > li.current > a.current:after {
  border-left-color: #f1f1f1;
}

#adminmenu li.current a.menu-top,
#adminmenu li.wp-has-current-submenu a.wp-has-current-submenu,
#adminmenu li.wp-has-current-submenu .wp-submenu .wp-submenu-head,
.folded #adminmenu li.current.menu-top {
  color: #fff;
  background: #096484;
}

#adminmenu li.wp-has-current-submenu div.wp-menu-image:before,
#adminmenu a.current:hover div.wp-menu-image:before,
#adminmenu li.wp-has-current-submenu a:focus div.wp-menu-image:before,
#adminmenu li.wp-has-current-submenu.opensub div.wp-menu-image:before,
#adminmenu li:hover div.wp-menu-image:before,
#adminmenu li a:focus div.wp-menu-image:before,
#adminmenu li.opensub div.wp-menu-image:before,
.ie8 #adminmenu li.opensub div.wp-menu-image:before {
  color: #fff;
}

/* Admin Menu: bubble */
#adminmenu .awaiting-mod,
#adminmenu .update-plugins {
  color: #fff;
  background: #e1a948;
}

#adminmenu li.current a .awaiting-mod,
#adminmenu li a.wp-has-current-submenu .update-plugins,
#adminmenu li:hover a .awaiting-mod,
#adminmenu li.menu-top:hover > a .update-plugins {
  color: #fff;
  background: #4796b3;
}

/* Admin Menu: collapse button */
#collapse-menu {
  color: #e5f8ff;
}

#collapse-menu:hover {
  color: #fff;
}

#collapse-button div:after {
  color: #e5f8ff;
}

#collapse-menu:hover #collapse-button div:after {
  color: #fff;
}

/* Admin Bar */
#wpadminbar {
  color: #fff;
  background: #52accc;
}

#wpadminbar .ab-item,
#wpadminbar a.ab-item,
#wpadminbar > #wp-toolbar span.ab-label,
#wpadminbar > #wp-toolbar span.noticon {
  color: #fff;
}

#wpadminbar .ab-icon,
#wpadminbar .ab-icon:before,
#wpadminbar .ab-item:before,
#wpadminbar .ab-item:after {
  color: #e5f8ff;
}

#wpadminbar:not(.mobile) .ab-top-menu > li:hover > .ab-item,
#wpadminbar:not(.mobile) .ab-top-menu > li > .ab-item:focus,
#wpadminbar.nojq .quicklinks .ab-top-menu > li > .ab-item:focus,
#wpadminbar.nojs .ab-top-menu > li.menupop:hover > .ab-item,
#wpadminbar .ab-top-menu > li.menupop.hover > .ab-item {
  color: #fff;
  background: #4796b3;
}

#wpadminbar:not(.mobile) > #wp-toolbar li:hover span.ab-label,
#wpadminbar:not(.mobile) > #wp-toolbar li.hover span.ab-label,
#wpadminbar:not(.mobile) > #wp-toolbar a:focus span.ab-label {
  color: #fff;
}

#wpadminbar:not(.mobile) li:hover .ab-icon:before,
#wpadminbar:not(.mobile) li:hover .ab-item:before,
#wpadminbar:not(.mobile) li:hover .ab-item:after,
#wpadminbar:not(.mobile) li:hover #adminbarsearch:before {
  color: #fff;
}

/* Admin Bar: submenu */
#wpadminbar .menupop .ab-sub-wrapper {
  background: #4796b3;
}

#wpadminbar .quicklinks .menupop ul.ab-sub-secondary,
#wpadminbar .quicklinks .menupop ul.ab-sub-secondary .ab-submenu {
  background: #74b6ce;
}

#wpadminbar .ab-submenu .ab-item,
#wpadminbar .quicklinks .menupop ul li a,
#wpadminbar .quicklinks .menupop.hover ul li a,
#wpadminbar.nojs .quicklinks .menupop:hover ul li a {
  color: #e2ecf1;
}

#wpadminbar .quicklinks li .blavatar,
#wpadminbar .menupop .menupop > .ab-item:before {
  color: #e5f8ff;
}

#wpadminbar .quicklinks .menupop ul li a:hover,
#wpadminbar .quicklinks .menupop ul li a:focus,
#wpadminbar .quicklinks .menupop ul li a:hover strong,
#wpadminbar .quicklinks .menupop ul li a:focus strong,
#wpadminbar .quicklinks .ab-sub-wrapper .menupop.hover > a,
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
  color: #fff;
}

#wpadminbar .quicklinks li a:hover .blavatar,
#wpadminbar .quicklinks li a:focus .blavatar,
#wpadminbar .quicklinks .ab-sub-wrapper .menupop.hover > a .blavatar,
#wpadminbar .menupop .menupop > .ab-item:hover:before,
#wpadminbar.mobile .quicklinks .ab-icon:before,
#wpadminbar.mobile .quicklinks .ab-item:before {
  color: #fff;
}

#wpadminbar.mobile .quicklinks .hover .ab-icon:before,
#wpadminbar.mobile .quicklinks .hover .ab-item:before {
  color: #e5f8ff;
}

/* Admin Bar: search */
#wpadminbar #adminbarsearch:before {
  color: #e5f8ff;
}

#wpadminbar > #wp-toolbar > #wp-admin-bar-top-secondary > #wp-admin-bar-search #adminbarsearch input.adminbar-input:focus {
  color: #fff;
  background: #6eb9d4;
}

/* Admin Bar: my account */
#wpadminbar .quicklinks li#wp-admin-bar-my-account.with-avatar > a img {
  border-color: #6eb9d4;
  background-color: #6eb9d4;
}

#wpadminbar #wp-admin-bar-user-info .display-name {
  color: #fff;
}

#wpadminbar #wp-admin-bar-user-info a:hover .display-name {
  color: #fff;
}

#wpadminbar #wp-admin-bar-user-info .username {
  color: #e2ecf1;
}

/* Pointers */
.wp-pointer .wp-pointer-content h3 {
  background-color: #096484;
  border-color: #07526c;
}

.wp-pointer .wp-pointer-content h3:before {
  color: #096484;
}

.wp-pointer.wp-pointer-top .wp-pointer-arrow,
.wp-pointer.wp-pointer-top .wp-pointer-arrow-inner,
.wp-pointer.wp-pointer-undefined .wp-pointer-arrow,
.wp-pointer.wp-pointer-undefined .wp-pointer-arrow-inner {
  border-bottom-color: #096484;
}

/* Media */
.media-item .bar,
.media-progress-bar div {
  background-color: #096484;
}

.details.attachment {
  -webkit-box-shadow: inset 0 0 0 3px #fff, inset 0 0 0 7px #096484;
  box-shadow: inset 0 0 0 3px #fff, inset 0 0 0 7px #096484;
}

.attachment.details .check {
  background-color: #096484;
  -webkit-box-shadow: 0 0 0 1px #fff, 0 0 0 2px #096484;
  box-shadow: 0 0 0 1px #fff, 0 0 0 2px #096484;
}

.media-selection .attachment.selection.details .thumbnail {
  -webkit-box-shadow: 0px 0px 0px 1px #fff, 0px 0px 0px 3px #096484;
  box-shadow: 0px 0px 0px 1px #fff, 0px 0px 0px 3px #096484;
}

/* Themes */
.theme-browser .theme.active .theme-name,
.theme-browser .theme.add-new-theme a:hover:after,
.theme-browser .theme.add-new-theme a:focus:after {
  background: #096484;
}

.theme-browser .theme.add-new-theme a:hover span:after,
.theme-browser .theme.add-new-theme a:focus span:after {
  color: #096484;
}

.theme-section.current,
.theme-filter.current {
  border-bottom-color: #52accc;
}

body.more-filters-opened .more-filters {
  color: #fff;
  background-color: #52accc;
}

body.more-filters-opened .more-filters:before {
  color: #fff;
}

body.more-filters-opened .more-filters:hover,
body.more-filters-opened .more-filters:focus {
  background-color: #096484;
  color: #fff;
}

body.more-filters-opened .more-filters:hover:before,
body.more-filters-opened .more-filters:focus:before {
  color: #fff;
}

/* Widgets */
.widgets-chooser li.widgets-chooser-selected {
  background-color: #096484;
  color: #fff;
}

.widgets-chooser li.widgets-chooser-selected:before,
.widgets-chooser li.widgets-chooser-selected:focus:before {
  color: #fff;
}

/* Customize */
#customize-theme-controls .widget-area-select .selected {
  background-color: #096484;
  color: #fff;
}

#customize-footer-actions .devices button:focus {
  border-bottom-color: #096484;
}

/* Responsive Component */
div#wp-responsive-toggle a:before {
  color: #e5f8ff;
}

.wp-responsive-open div#wp-responsive-toggle a {
  border-color: transparent;
  background: #096484;
}

.wp-responsive-open #wpadminbar #wp-admin-bar-menu-toggle a {
  background: #4796b3;
}

.wp-responsive-open #wpadminbar #wp-admin-bar-menu-toggle .ab-icon:before {
  color: #e5f8ff;
}

/* TinyMCE */
.mce-container.mce-menu .mce-menu-item:hover,
.mce-container.mce-menu .mce-menu-item.mce-selected,
.mce-container.mce-menu .mce-menu-item:focus,
.mce-container.mce-menu .mce-menu-item-normal.mce-active,
.mce-container.mce-menu .mce-menu-item-preview.mce-active {
  background: #096484;
}
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   #accordion-section-menu_locations{position:relative;margin-bottom:15px}.menu-in-location,.menu-in-locations{display:block;font-weight:600;font-size:10px}#customize-controls .control-section .accordion-section-title:focus .menu-in-location,#customize-controls .control-section .accordion-section-title:hover .menu-in-location,#customize-controls .theme-location-set{color:#555}.wp-customizer .menu-item-bar .menu-item-handle,.wp-customizer .menu-item-settings,.wp-customizer .menu-item-settings .description-thin{-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box}.wp-customizer .menu-item-bar{margin:0}.wp-customizer .menu-item-bar .menu-item-handle{width:100%;background:#fff}.wp-customizer .menu-item-handle .item-title{margin-right:0}.wp-customizer .menu-item-handle .item-type{padding:1px 21px 0 5px;float:right;text-align:right}.wp-customizer .menu-item-settings{max-width:100%;overflow:hidden;padding:10px;background:#eee;border:1px solid #999;border-top:none}.wp-customizer .menu-item-settings .description-thin{width:100%;height:auto;margin:0 0 8px}.wp-customizer .menu-item-settings input[type=text]{width:100%}.wp-customizer .menu-item-settings .submitbox{margin:0;padding:0}.wp-customizer .menu-item-settings .link-to-original{padding:5px 0;border:none;font-style:normal;margin:0;width:100%}.wp-customizer .menu-item .submitbox .submitdelete{float:left;margin:6px 0 0;padding:0;cursor:pointer}.menu-item-reorder-nav{display:none;background-color:#fff;position:absolute;top:0;right:0}.menus-move-left:before{content:"\f341"}.menus-move-right:before{content:"\f345"}.reordering .menu-item .item-controls,.reordering .menu-item .item-type{display:none}.reordering .menu-item-reorder-nav{display:block}.customize-control input.menu-name-field{width:100%;margin:12px 0}.wp-customizer .menu-item .item-edit{position:absolute;right:-19px;top:2px;display:block;width:30px;height:38px;margin-right:0!important;-webkit-box-shadow:none;box-shadow:none;outline:0;overflow:hidden;cursor:pointer}.wp-customizer .menu-item.menu-item-edit-active .item-edit .toggle-indicator:after{content:"\f142"}.wp-customizer .menu-item-settings p.description{font-style:normal}.wp-customizer .menu-settings dl{margin:12px 0 0;padding:0}.wp-customizer .menu-settings .checkbox-input{margin-top:8px}.wp-customizer .menu-settings .menu-theme-locations{border-top:1px solid #ccc}.wp-customizer .menu-settings{margin-top:36px;border-top:none}.menu-settings .customize-control-checkbox label{line-height:1}.menu-settings .customize-control.customize-control-checkbox{margin-bottom:8px}.customize-control-menu{margin-top:4px}#customize-controls .customize-info.open.active-menu-screen-options .customize-help-toggle{color:#555}.customize-screen-options-toggle{background:0 0;border:none;color:#555;cursor:pointer;margin:0;padding:20px;position:absolute;right:0;top:30px}#customize-controls .customize-info .customize-help-toggle{padding:20px}#customize-controls .customize-info .customize-help-toggle:before{padding:4px}#customize-controls .customize-info.open.active-menu-screen-options .customize-help-toggle:active,#customize-controls .customize-info.open.active-menu-screen-options .customize-help-toggle:focus,#customize-controls .customize-info.open.active-menu-screen-options .customize-help-toggle:hover,.active-menu-screen-options .customize-screen-options-toggle,.customize-screen-options-toggle:active,.customize-screen-options-toggle:focus,.customize-screen-options-toggle:hover{color:#0073aa}#customize-controls .customize-info .customize-help-toggle:focus,.customize-screen-options-toggle:focus{outline:0}.customize-screen-options-toggle:before{-moz-osx-font-smoothing:grayscale;border:none;content:"\f111";display:block;font:18px/1 dashicons;padding:5px;text-align:center;text-decoration:none!important;text-indent:0;left:6px;position:absolute;top:6px}#customize-controls .customize-info .customize-help-toggle:focus:before,.customize-screen-options-toggle:focus:before{-webkit-border-radius:100%;border-radius:100%}.wp-customizer #screen-options-wrap{display:none;background:#fff;border-top:1px solid #ddd;padding:4px 15px 15px}.wp-customizer .metabox-prefs label{display:block;padding-right:0;line-height:30px}.wp-customizer .toggle-indicator{display:inline-block;font-size:20px;line-height:1;text-indent:-1px}.rtl .wp-customizer .toggle-indicator{text-indent:1px}.wp-customizer .toggle-indicator:after{content:"\f140";speak:none;vertical-align:top;-webkit-border-radius:50%;border-radius:50%;color:#72777c;font:400 20px/1 dashicons;-webkit-font-smoothing:antialiased;-moz-osx-font-smoothing:grayscale;text-decoration:none!important}#accordion-panel-nav_menus .field-css-classes,#accordion-panel-nav_menus .field-description,#accordion-panel-nav_menus .field-link-target,#accordion-panel-nav_menus .field-title-attribute,#accordion-panel-nav_menus .field-xfn{display:none}#accordion-panel-nav_menus.field-css-classes-active .field-css-classes,#accordion-panel-nav_menus.field-description-active .field-description,#accordion-panel-nav_menus.field-link-target-active .field-link-target,#accordion-panel-nav_menus.field-title-attribute-active .field-title-attribute,#accordion-panel-nav_menus.field-xfn-active .field-xfn{display:block}.menu-item-depth-0{margin-left:0}.menu-item-depth-1{margin-left:20px}.menu-item-depth-2{margin-left:40px}.menu-item-depth-3{margin-left:60px}.menu-item-depth-4{margin-left:80px}.menu-item-depth-5{margin-left:100px}.menu-item-depth-6{margin-left:120px}.menu-item-depth-7{margin-left:140px}.menu-item-depth-8{margin-left:160px}.menu-item-depth-9{margin-left:180px}.menu-item-depth-10{margin-left:200px}.menu-item-depth-11{margin-left:220px}.menu-item-depth-0>.menu-item-bar{margin-right:0}.menu-item-depth-1>.menu-item-bar{margin-right:20px}.menu-item-depth-2>.menu-item-bar{margin-right:40px}.menu-item-depth-3>.menu-item-bar{margin-right:60px}.menu-item-depth-4>.menu-item-bar{margin-right:80px}.menu-item-depth-5>.menu-item-bar{margin-right:100px}.menu-item-depth-6>.menu-item-bar{margin-right:120px}.menu-item-depth-7>.menu-item-bar{margin-right:140px}.menu-item-depth-8>.menu-item-bar{margin-right:160px}.menu-item-depth-9>.menu-item-bar{margin-right:180px}.menu-item-depth-10>.menu-item-bar{margin-right:200px}.menu-item-depth-11>.menu-item-bar{margin-right:220px}.menu-item-depth-0 .menu-item-transport{margin-left:0}.menu-item-depth-1 .menu-item-transport{margin-left:-20px}.menu-item-depth-3 .menu-item-transport{margin-left:-60px}.menu-item-depth-4 .menu-item-transport{margin-left:-80px}.menu-item-depth-2 .menu-item-transport{margin-left:-40px}.menu-item-depth-5 .menu-item-transport{margin-left:-100px}.menu-item-depth-6 .menu-item-transport{margin-left:-120px}.menu-item-depth-7 .menu-item-transport{margin-left:-140px}.menu-item-depth-8 .menu-item-transport{margin-left:-160px}.menu-item-depth-9 .menu-item-transport{margin-left:-180px}.menu-item-depth-10 .menu-item-transport{margin-left:-200px}.menu-item-depth-11 .menu-item-transport{margin-left:-220px}.reordering .menu-item-depth-0{margin-left:0}.reordering .menu-item-depth-1{margin-left:15px}.reordering .menu-item