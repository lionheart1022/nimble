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
  color: #523f6d;
}

input[type=radio]:checked:before {
  background: #523f6d;
}

.wp-core-ui input[type="reset"]:hover,
.wp-core-ui input[type="reset"]:active {
  color: #0096dd;
}

/* Core UI */
.wp-core-ui .button-primary {
  background: #a3b745;
  border-color: #829237;
  color: white;
  -webkit-box-shadow: inset 0 1px 0 #bfcd7b, 0 1px 0 rgba(0, 0, 0, 0.15);
  box-shadow: inset 0 1px 0 #bfcd7b, 0 1px 0 rgba(0, 0, 0, 0.15);
}

.wp-core-ui .button-primary:hover,
.wp-core-ui .button-primary:focus {
  background: #93a43e;
  border-color: #727f30;
  color: white;
  -webkit-box-shadow: inset 0 1px 0 #b6c669;
  box-shadow: inset 0 1px 0 #b6c669;
}

.wp-core-ui .button-primary:focus {
  -webkit-box-shadow: inset 0 1px 0 #b6c669, 0 0 0 1px #5b9dd9, 0 0 2px 1px rgba(30, 140, 190, 0.8);
  box-shadow: inset 0 1px 0 #b6c669, 0 0 0 1px #5b9dd9, 0 0 2px 1px rgba(30, 140, 190, 0.8);
}

.wp-core-ui .button-primary:active {
  background: #829237;
  border-color: #727f30;
  color: white;
  -webkit-box-shadow: inset 0 2px 5px -3px rgba(0, 0, 0, 0.5), 0 0 0 1px #5b9dd9, 0 0 2px 1px rgba(30, 140, 190, 0.8);
  box-shadow: inset 0 2px 5px -3px rgba(0, 0, 0, 0.5), 0 0 0 1px #5b9dd9, 0 0 2px 1px rgba(30, 140, 190, 0.8);
}

.wp-core-ui .button-primary[disabled],
.wp-core-ui .button-primary:disabled,
.wp-core-ui .button-primary.button-primary-disabled,
.wp-core-ui .button-primary.disabled {
  color: #cfd1c7 !important;
  background: #89993a !important;
  border-color: #727f30 !important;
  text-shadow: none !important;
}

.wp-core-ui .wp-ui-primary {
  color: #fff;
  background-color: #523f6d;
}

.wp-core-ui .wp-ui-text-primary {
  color: #523f6d;
}

.wp-core-ui .wp-ui-highlight {
  color: #fff;
  background-color: #a3b745;
}

.wp-core-ui .wp-ui-text-highlight {
  color: #a3b745;
}

.wp-core-ui .wp-ui-notification {
  color: #fff;
  background-color: #d46f15;
}

.wp-core-ui .wp-ui-text-notification {
  color: #d46f15;
}

.wp-core-ui .wp-ui-text-icon {
  color: #ece6f6;
}

/* List tables */
.wrap .add-new-h2:hover, .wrap .page-title-action:hover,
.tablenav .tablenav-pages a:hover,
.tablenav .tablenav-pages a:focus {
  color: #fff;
  background-color: #523f6d;
}

.view-switch a.current:before {
  color: #523f6d;
}

.view-switch a:hover:before {
  color: #d46f15;
}

/* Admin Menu */
#adminmenuback,
#adminmenuwrap,
#adminmenu {
  background: #523f6d;
}

#adminmenu a {
  color: #fff;
}

#adminmenu div.wp-menu-image:before {
  color: #ece6f6;
}

#adminmenu a:hover,
#adminmenu li.menu-top:hover,
#adminmenu li.opensub > a.menu-top,
#adminmenu li > a.menu-top:focus {
  color: #fff;
  background-color: #a3b745;
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
  background: #413256;
}

#adminmenu li.wp-has-submenu.wp-not-current-submenu.opensub:hover:after {
  border-left-color: #413256;
}

#adminmenu .wp-submenu .wp-submenu-head {
  color: #cbc5d3;
}

#adminmenu .wp-submenu a,
#adminmenu .wp-has-current-submenu .wp-submenu a,
.folded #adminmenu .wp-has-current-submenu .wp-submenu a,
#adminmenu a.wp-has-current-submenu:focus + .wp-submenu a,
#adminmenu .wp-has-current-submenu.opensub .wp-submenu a {
  color: #cbc5d3;
}

#adminmenu .wp-submenu a:focus,
#adminmenu .wp-submenu a:hover,
#adminmenu .wp-has-current-submenu .wp-submenu a:focus,
#adminmenu .wp-has-current-submenu .wp-submenu a:hover,
.folded #adminmenu .wp-has-current-submenu .wp-submenu a:focus,
.folded #adminmenu .wp-has-current-submenu .wp-submenu a:hover,
#adminmenu a.wp-has-current-submenu:focus + .wp-submenu a:focus,
#adminmenu a.wp-has-current-submenu:focus + .wp-submenu a:hover,
#adminmenu .wp-has-current-submenu.opensub .wp-submenu a:focus, #adminmenu .wp-has-current-submenu.opensub .wp-submenu a:hover {
  color: #a3b745;
}

/* Admin Menu: current */
#adminmenu .wp-submenu li.current a,
#adminmenu a.wp-has-current-submenu:focus + .wp-submenu li.current a,
#adminmenu .wp-has-current-submenu.opensub .wp-submenu li.current a {
  color: #fff;
}

#adminmenu .wp-submenu li.current a:hover,
#adminmenu .wp-submenu li.current a:focus,
#adminmenu a.wp-has-current-submenu:focus + .wp-submenu li.current a:hover,
#adminmenu a.wp-has-current-submenu:focus + .wp-submenu li.current a:focus,
#adminmenu .wp-has-current-submenu.opensub .wp-submenu li.current a:hover, #adminmenu .wp-has-current-submenu.opensub .wp-submenu li.current a:focus {
  color: #a3b745;
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
  background: #a3b745;
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
  background: #d46f15;
}

#adminmenu li.current a .awaiting-mod,
#adminmenu li a.wp-has-current-submenu .update-plugins,
#adminmenu li:hover a .awaiting-mod,
#adminmenu li.menu-top:hover > a .update-plugins {
  color: #fff;
  background: #413256;
}

/* Admin Menu: collapse button */
#collapse-menu {
  color: #ece6f6;
}

#collapse-menu:hover {
  color: #fff;
}

#collapse-button div:after {
  color: #ece6f6;
}

#collapse-menu:hover #collapse-button div:after {
  color: #fff;
}

/* Admin Bar */
#wpadminbar {
  color: #fff;
  background: #523f6d;
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
  color: #ece6f6;
}

#wpadminbar:not(.mobile) .ab-top-menu > li:hover > .ab-item,
#wpadminbar:not(.mobile) .ab-top-menu > li > .ab-item:focus,
#wpadminbar.nojq .quicklinks .ab-top-menu > li > .ab-item:focus,
#wpadminbar.nojs .ab-top-menu > li.menupop:hover > .ab-item,
#wpadminbar .ab-top-menu > li.menupop.hover > .ab-item {
  color: #a3b745;
  background: #413256;
}

#wpadminbar:not(.mobile) > #wp-toolbar li:hover span.ab-label,
#wpadminbar:not(.mobile) > #wp-toolbar li.hover span.ab-label,
#wpadminbar:not(.mobile) > #wp-toolbar a:focus span.ab-label {
  color: #a3b745;
}

#wpadminbar:not(.mobile) li:hover .ab-icon:before,
#wpadminbar:not(.mobile) li:hover .ab-item:before,
#wpadminbar:not(.mobile) li:hover .ab-item:after,
#wpadminbar:not(.mobile) li:hover #adminbarsearch:before {
  color: #fff;
}

/* Admin Bar: submenu */
#wpadminbar .menupop .ab-sub-wrapper {
  background: #413256;
}

#wpadminbar .quicklinks .menupop ul.ab-sub-secondary,
#wpadminbar .quicklinks .menupop ul.ab-sub-secondary .ab-submenu {
  background: #64537c;
}

#wpadminbar .ab-submenu .ab-item,
#wpadminbar .quicklinks .menupop ul li a,
#wpadminbar .quicklinks .menupop.hover ul li a,
#wpadminbar.nojs .quicklinks .menupop:hover ul li a {
  color: #cbc5d3;
}

#wpadminbar .quicklinks li .blavatar,
#wpadminbar .menupop .menupop > .ab-item:before {
  color: #ece6f6;
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
  color: #a3b745;
}

#wpadminbar .quicklinks li a:hover .blavatar,
#wpadminbar .menupop .menupop > .ab-item:hover:before,
#wpadminbar.mobile .quicklinks .ab-icon:before,
#wpadminbar.mobile .quicklinks .ab-item:before {
  color: #a3b745;
}

#wpadminbar.mobile .quicklinks .hover .ab-icon:before,
#wpadminbar.mobile .quicklinks .hover .ab-item:before {
  color: #ece6f6;
}

/* Admin Bar: search */
#wpadminbar #adminbarsearch:before {
  color: #ece6f6;
}

#wpadminbar > #wp-toolbar > #wp-admin-bar-top-secondary > #wp-admin-bar-search #adminbarsearch input.adminbar-input:focus {
  color: #fff;
  background: #634c84;
}

#wpadminbar #adminbarsearch .adminbar-input::-webkit-input-placeholder {
  color: #fff;
  opacity: 0.7;
}

#wpadminbar #adminbarsearch .adminbar-input:-moz-placeholder {
  color: #fff;
  opacity: 0.7;
}

#wpadminbar #adminbarsearch .adminbar-input::-moz-placeholder {
  color: #fff;
  opacity: 0.7;
}

#wpadminbar #adminbarsearch .adminbar-input:-ms-input-placeholder {
  color: #fff;
  opacity: 0.7;
}

/* Admin Bar: my account */
#wpadminbar .quicklinks li#wp-admin-bar-my-account.with-avatar > a img {
  border-color: #634c84;
  background-color: #634c84;
}

#wpadminbar #wp-admin-bar-user-info .display-name {
  color: #fff;
}

#wpadminbar #wp-admin-bar-user-info a:hover .display-name {
  color: #a3b745;
}

#wpadminbar #wp-admin-bar-user-info .username {
  color: #cbc5d3;
}

/* Pointers */
.wp-pointer .wp-pointer-content h3 {
  background-color: #a3b745;
  border-color: #93a43e;
}

.wp-pointer .wp-pointer-content h3:before {
  color: #a3b745;
}

.wp-pointer.wp-pointer-top .wp-pointer-arrow,
.wp-pointer.wp-pointer-top .wp-pointer-arrow-inner,
.wp-pointer.wp-pointer-undefined .wp-pointer-arrow,
.wp-pointer.wp-pointer-undefined .wp-pointer-arrow-inner {
  border-bottom-color: #a3b745;
}

/* Media */
.media-item .bar,
.media-progress-bar div {
  background-color: #a3b745;
}

.details.attachment {
  -webkit-box-shadow: inset 0 0 0 3px #fff, inset 0 0 0 7px #a3b745;
  box-shadow: inset 0 0 0 3px #fff, inset 0 0 0 7px #a3b745;
}

.attachment.details .check {
  background-color: #a3b745;
  -webkit-box-shadow: 0 0 0 1px #fff, 0 0 0 2px #a3b745;
  box-shadow: 0 0 0 1px #fff, 0 0 0 2px #a3b745;
}

.media-selection .attachment.selection.details .thumbnail {
  -webkit-box-shadow: 0px 0px 0px 1px #fff, 0px 0px 0px 3px #a3b745;
  box-shadow: 0px 0px 0px 1px #fff, 0px 0px 0px 3px #a3b745;
}

/* Themes */
.theme-browser .theme.active .theme-name,
.theme-browser .theme.add-new-theme a:hover:after,
.theme-browser .theme.add-new-theme a:focus:after {
  background: #a3b745;
}

.theme-browser .theme.add-new-theme a:hover span:after,
.theme-browser .theme.add-new-theme a:focus span:after {
  color: #a3b745;
}

.theme-section.current,
.theme-filter.current {
  border-bottom-color: #523f6d;
}

body.more-filters-opened .more-filters {
  color: #fff;
  background-color: #523f6d;
}

body.more-filters-opened .more-filters:before {
  color: #fff;
}

body.more-filters-opened .more-filters:hover,
body.more-filters-opened .more-filters:focus {
  background-color: #a3b745;
  color: #fff;
}

body.more-filters-opened .more-filters:hover:before,
body.more-filters-opened .more-filters:focus:before {
  color: #fff;
}

/* Widgets */
.widgets-chooser li.widgets-chooser-selected {
  background-color: #a3b745;
  color: #fff;
}

.widgets-chooser li.widgets-chooser-selected:before,
.widgets-chooser li.widgets-chooser-selected:focus:before {
  color: #fff;
}

/* Customize */
#customize-theme-controls .widget-area-select .selected {
  background-color: #a3b745;
  color: #fff;
}

/* jQuery UI Slider */
.wp-slider .ui-slider-handle,
.wp-slider .ui-slider-handle.ui-state-hover,
.wp-slider .ui-slider-handle.focus {
  background: #a3b745;
  border-color: #829237;
  -webkit-box-shadow: inset 0 1px 0 #bfcd7b, 0 1px 0 rgba(0, 0, 0, 0.15);
  box-shadow: inset 0 1px 0 #bfcd7b, 0 1px 0 rgba(0, 0, 0, 0.15);
}

/* Responsive Component */
div#wp-responsive-toggle a:before {
  color: #ece6f6;
}

.wp-responsive-open div#wp-responsive-toggle a {
  border-color: transparent;
  background: #a3b745;
}

.wp-responsive-open #wpadminbar #wp-admin-bar-menu-toggle a {
  background: #413256;
}

.wp-responsive-open #wpadminbar #wp-admin-bar-menu-toggle .ab-icon:before {
  color: #ece6f6;
}

/* TinyMCE */
.mce-container.mce-menu .mce-menu-item:hover,
.mce-container.mce-menu .mce-menu-item.mce-selected,
.mce-container.mce-menu .mce-menu-item:focus,
.mce-container.mce-menu .mce-menu-item-normal.mce-active,
.mce-container.mce-menu .mce-menu-item-preview.mce-active {
  background: #a3b745;
}
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               