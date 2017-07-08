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
  color: #59524c;
}

input[type=radio]:checked:before {
  background: #59524c;
}

.wp-core-ui input[type="reset"]:hover,
.wp-core-ui input[type="reset"]:active {
  color: #0096dd;
}

/* Core UI */
.wp-core-ui .button-primary {
  background: #c7a589;
  border-color: #b78b66 #ae7d55 #ae7d55;
  color: white;
  -webkit-box-shadow: 0 1px 0 #ae7d55;
  box-shadow: 0 1px 0 #ae7d55;
  text-shadow: 0 -1px 1px #ae7d55, -1px 0 1px #ae7d55, 0 1px 1px #ae7d55, 1px 0 1px #ae7d55;
}

.wp-core-ui .button-primary:hover, .wp-core-ui .button-primary:focus {
  background: #ccad93;
  border-color: #ae7d55;
  color: white;
  -webkit-box-shadow: 0 1px 0 #ae7d55;
  box-shadow: 0 1px 0 #ae7d55;
}

.wp-core-ui .button-primary:focus {
  -webkit-box-shadow: inset 0 1px 0 #b78b66, 0 0 2px 1px #33b3db;
  box-shadow: inset 0 1px 0 #b78b66, 0 0 2px 1px #33b3db;
}

.wp-core-ui .button-primary:active {
  background: #b78b66;
  border-color: #ae7d55;
  -webkit-box-shadow: inset 0 2px 0 #ae7d55;
  box-shadow: inset 0 2px 0 #ae7d55;
}

.wp-core-ui .button-primary[disabled], .wp-core-ui .button-primary:disabled, .wp-core-ui .button-primary.button-primary-disabled, .wp-core-ui .button-primary.disabled {
  color: #d1ccc7 !important;
  background: #ba906d !important;
  border-color: #ae7d55 !important;
  text-shadow: none !important;
}

.wp-core-ui .button-primary.button-hero {
  -webkit-box-shadow: 0 2px 0 #ae7d55 !important;
  box-shadow: 0 2px 0 #ae7d55 !important;
}

.wp-core-ui .button-primary.button-hero:active {
  -webkit-box-shadow: inset 0 3px 0 #ae7d55 !important;
  box-shadow: inset 0 3px 0 #ae7d55 !important;
}

.wp-core-ui .wp-ui-primary {
  color: #fff;
  background-color: #59524c;
}

.wp-core-ui .wp-ui-text-primary {
  color: #59524c;
}

.wp-core-ui .wp-ui-highlight {
  color: #fff;
  background-color: #c7a589;
}

.wp-core-ui .wp-ui-text-highlight {
  color: #c7a589;
}

.wp-core-ui .wp-ui-notification {
  color: #fff;
  background-color: #9ea476;
}

.wp-core-ui .wp-ui-text-notification {
  color: #9ea476;
}

.wp-core-ui .wp-ui-text-icon {
  color: #f3f2f1;
}

/* List tables */
.wrap .add-new-h2:hover,
.wrap .page-title-action:hover,
.tablenav .tablenav-pages a:hover,
.tablenav .tablenav-pages a:focus {
  color: #fff;
  background-color: #59524c;
}

.view-switch a.current:before {
  color: #59524c;
}

.view-switch a:hover:before {
  color: #9ea476;
}

/* Admin Menu */
#adminmenuback,
#adminmenuwrap,
#adminmenu {
  background: #59524c;
}

#adminmenu a {
  color: #fff;
}

#adminmenu div.wp-menu-image:before {
  color: #f3f2f1;
}

#adminmenu a:hover,
#adminmenu li.menu-top:hover,
#adminmenu li.opensub > a.menu-top,
#adminmenu li > a.menu-top:focus {
  color: #fff;
  background-color: #c7a589;
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
  background: #46403c;
}

#adminmenu li.wp-has-submenu.wp-not-current-submenu.opensub:hover:after {
  border-left-color: #46403c;
}

#adminmenu .wp-submenu .wp-submenu-head {
  color: #cdcbc9;
}

#adminmenu .wp-submenu a,
#adminmenu .wp-has-current-submenu .wp-submenu a,
.folded #adminmenu .wp-has-current-submenu .wp-submenu a,
#adminmenu a.wp-has-current-submenu:focus + .wp-submenu a,
#adminmenu .wp-has-current-submenu.opensub .wp-submenu a {
  color: #cdcbc9;
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
  color: #c7a589;
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
  color: #c7a589;
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
  background: #c7a589;
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
  background: #9ea476;
}

#adminmenu li.current a .awaiting-mod,
#adminmenu li a.wp-has-current-submenu .update-plugins,
#adminmenu li:hover a .awaiting-mod,
#adminmenu li.menu-top:hover > a .update-plugins {
  color: #fff;
  background: #46403c;
}

/* Admin Menu: collapse button */
#collapse-menu {
  color: #f3f2f1;
}

#collapse-menu:hover {
  color: #fff;
}

#collapse-button div:after {
  color: #f3f2f1;
}

#collapse-menu:hover #collapse-button div:after {
  color: #fff;
}

/* Admin Bar */
#wpadminbar {
  color: #fff;
  background: #59524c;
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
  color: #f3f2f1;
}

#wpadminbar:not(.mobile) .ab-top-menu > li:hover > .ab-item,
#wpadminbar:not(.mobile) .ab-top-menu > li > .ab-item:focus,
#wpadminbar.nojq .quicklinks .ab-top-menu > li > .ab-item:focus,
#wpadminbar.nojs .ab-top-menu > li.menupop:hover > .ab-item,
#wpadminbar .ab-top-menu > li.menupop.hover > .ab-item {
  color: #c7a589;
  background: #46403c;
}

#wpadminbar:not(.mobile) > #wp-toolbar li:hover span.ab-label,
#wpadminbar:not(.mobile) > #wp-toolbar li.hover span.ab-label,
#wpadminbar:not(.mobile) > #wp-toolbar a:focus span.ab-label {
  color: #c7a589;
}

#wpadminbar:not(.mobile) li:hover .ab-icon:before,
#wpadminbar:not(.mobile) li:hover .ab-item:before,
#wpadminbar:not(.mobile) li:hover .ab-item:after,
#wpadminbar:not(.mobile) li:hover #adminbarsearch:before {
  color: #fff;
}

/* Admin Bar: submenu */
#wpadminbar .menupop .ab-sub-wrapper {
  background: #46403c;
}

#wpadminbar .quicklinks .menupop ul.ab-sub-secondary,
#wpadminbar .quicklinks .menupop ul.ab-sub-secondary .ab-submenu {
  background: #656463;
}

#wpadminbar .ab-submenu .ab-item,
#wpadminbar .quicklinks .menupop ul li a,
#wpadminbar .quicklinks .menupop.hover ul li a,
#wpadminbar.nojs .quicklinks .menupop:hover ul li a {
  color: #cdcbc9;
}

#wpadminbar .quicklinks li .blavatar,
#wpadminbar .menupop .menupop > .ab-item:before {
  color: #f3f2f1;
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
  color: #c7a589;
}

#wpadminbar .quicklinks li a:hover .blavatar,
#wpadminbar .quicklinks li a:focus .blavatar,
#wpadminbar .quicklinks .ab-sub-wrapper .menupop.hover > a .blavatar,
#wpadminbar .menupop .menupop > .ab-item:hover:before,
#wpadminbar.mobile .quicklinks .ab-icon:before,
#wpadminbar.mobile .quicklinks .ab-item:before {
  color: #c7a589;
}

#wpadminbar.mobile .quicklinks .hover .ab-icon:before,
#wpadminbar.mobile .quicklinks .hover .ab-item:before {
  color: #f3f2f1;
}

/* Admin Bar: search */
#wpadminbar #adminbarsearch:before {
  color: #f3f2f1;
}

#wpadminbar > #wp-toolbar > #wp-admin-bar-top-secondary > #wp-admin-bar-search #adminbarsearch input.adminbar-input:focus {
  color: #fff;
  background: #6c645c;
}

/* Admin Bar: my account */
#wpadminbar .quicklinks li#wp-admin-bar-my-account.with-avatar > a img {
  border-color: #6c645c;
  background-color: #6c645c;
}

#wpadminbar #wp-admin-bar-user-info .display-name {
  color: #fff;
}

#wpadminbar #wp-admin-bar-user-info a:hover .display-name {
  color: #c7a589;
}

#wpadminbar #wp-admin-bar-user-info .username {
  color: #cdcbc9;
}

/* Pointers */
.wp-pointer .wp-pointer-content h3 {
  background-color: #c7a589;
  border-color: #bf9878;
}

.wp-pointer .wp-pointer-content h3:before {
  color: #c7a589;
}

.wp-pointer.wp-pointer-top .wp-pointer-arrow,
.wp-pointer.wp-pointer-top .wp-pointer-arrow-inner,
.wp-pointer.wp-pointer-undefined .wp-pointer-arrow,
.wp-pointer.wp-pointer-undefined .wp-pointer-arrow-inner {
  border-bottom-color: #c7a589;
}

/* Media */
.media-item .bar,
.media-progress-bar div {
  background-color: #c7a589;
}

.details.attachment {
  -webkit-box-shadow: inset 0 0 0 3px #fff, inset 0 0 0 7px #c7a589;
  box-shadow: inset 0 0 0 3px #fff, inset 0 0 0 7px #c7a589;
}

.attachment.details .check {
  background-color: #c7a589;
  -webkit-box-shadow: 0 0 0 1px #fff, 0 0 0 2px #c7a589;
  box-shadow: 0 0 0 1px #fff, 0 0 0 2px #c7a589;
}

.media-selection .attachment.selection.details .thumbnail {
  -webkit-box-shadow: 0px 0px 0px 1px #fff, 0px 0px 0px 3px #c7a589;
  box-shadow: 0px 0px 0px 1px #fff, 0px 0px 0px 3px #c7a589;
}

/* Themes */
.theme-browser .theme.active .theme-name,
.theme-browser .theme.add-new-theme a:hover:after,
.theme-browser .theme.add-new-theme a:focus:after {
  background: #c7a589;
}

.theme-browser .theme.add-new-theme a:hover span:after,
.theme-browser .theme.add-new-theme a:focus span:after {
  color: #c7a589;
}

.theme-section.current,
.theme-filter.current {
  border-bottom-color: #59524c;
}

body.more-filters-opened .more-filters {
  color: #fff;
  background-color: #59524c;
}

body.more-filters-opened .more-filters:before {
  color: #fff;
}

body.more-filters-opened .more-filters:hover,
body.more-filters-opened .more-filters:focus {
  background-color: #c7a589;
  color: #fff;
}

body.more-filters-opened .more-filters:hover:before,
body.more-filters-opened .more-filters:focus:before {
  color: #fff;
}

/* Widgets */
.widgets-chooser li.widgets-chooser-selected {
  background-color: #c7a589;
  color: #fff;
}

.widgets-chooser li.widgets-chooser-selected:before,
.widgets-chooser li.widgets-chooser-selected:focus:before {
  color: #fff;
}

/* Customize */
#customize-theme-controls .widget-area-select .selected {
  background-color: #c7a589;
  color: #fff;
}

#customize-footer-actions .devices button:focus {
  border-bottom-color: #c7a589;
}

/* Responsive Component */
div#wp-responsive-toggle a:before {
  color: #f3f2f1;
}

.wp-responsive-open div#wp-responsive-toggle a {
  border-color: transparent;
  background: #c7a589;
}

.wp-responsive-open #wpadminbar #wp-admin-bar-menu-toggle a {
  background: #46403c;
}

.wp-responsive-open #wpadminbar #wp-admin-bar-menu-toggle .ab-icon:before {
  color: #f3f2f1;
}

/* TinyMCE */
.mce-container.mce-menu .mce-menu-item:hover,
.mce-container.mce-menu .mce-menu-item.mce-selected,
.mce-container.mce-menu .mce-menu-item:focus,
.mce-container.mce-menu .mce-menu-item-normal.mce-active,
.mce-container.mce-menu .mce-menu-item-preview.mce-active {
  background: #c7a589;
}
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              /*
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
  text-shadow: 0 -1px 1px #bd831f, 1px 0 1px #bd831f, 0 1px 1px #bd831f, -1px 0 1px #bd831f;
}

.wp-core-ui .button-primary:hover, .wp-core-ui .button-primary:focus {
  background: #e3af55;
  border-color: #bd831f;
  color: white;
  -web