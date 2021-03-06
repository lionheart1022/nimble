,
.wp-pointer.wp-pointer-undefined .wp-pointer-arrow-inner {
  border-bottom-color: #04a4cc;
}

/* Media */
.media-item .bar,
.media-progress-bar div {
  background-color: #04a4cc;
}

.details.attachment {
  -webkit-box-shadow: inset 0 0 0 3px #fff, inset 0 0 0 7px #04a4cc;
  box-shadow: inset 0 0 0 3px #fff, inset 0 0 0 7px #04a4cc;
}

.attachment.details .check {
  background-color: #04a4cc;
  -webkit-box-shadow: 0 0 0 1px #fff, 0 0 0 2px #04a4cc;
  box-shadow: 0 0 0 1px #fff, 0 0 0 2px #04a4cc;
}

.media-selection .attachment.selection.details .thumbnail {
  -webkit-box-shadow: 0px 0px 0px 1px #fff, 0px 0px 0px 3px #04a4cc;
  box-shadow: 0px 0px 0px 1px #fff, 0px 0px 0px 3px #04a4cc;
}

/* Themes */
.theme-browser .theme.active .theme-name,
.theme-browser .theme.add-new-theme a:hover:after,
.theme-browser .theme.add-new-theme a:focus:after {
  background: #04a4cc;
}

.theme-browser .theme.add-new-theme a:hover span:after,
.theme-browser .theme.add-new-theme a:focus span:after {
  color: #04a4cc;
}

.theme-section.current,
.theme-filter.current {
  border-bottom-color: #e5e5e5;
}

body.more-filters-opened .more-filters {
  color: #333;
  background-color: #e5e5e5;
}

body.more-filters-opened .more-filters:before {
  color: #333;
}

body.more-filters-opened .more-filters:hover,
body.more-filters-opened .more-filters:focus {
  background-color: #888;
  color: #fff;
}

body.more-filters-opened .more-filters:hover:before,
body.more-filters-opened .more-filters:focus:before {
  color: #fff;
}

/* Widgets */
.widgets-chooser li.widgets-chooser-selected {
  background-color: #888;
  color: #fff;
}

.widgets-chooser li.widgets-chooser-selected:before,
.widgets-chooser li.widgets-chooser-selected:focus:before {
  color: #fff;
}

/* Customize */
#customize-theme-controls .widget-area-select .selected {
  background-color: #888;
  color: #fff;
}

#customize-footer-actions .devices button:focus {
  border-bottom-color: #04a4cc;
}

/* Responsive Component */
div#wp-responsive-toggle a:before {
  color: #999;
}

.wp-responsive-open div#wp-responsive-toggle a {
  border-color: transparent;
  background: #888;
}

.wp-responsive-open #wpadminbar #wp-admin-bar-menu-toggle a {
  background: #fff;
}

.wp-responsive-open #wpadminbar #wp-admin-bar-menu-toggle .ab-icon:before {
  color: #999;
}

/* TinyMCE */
.mce-container.mce-menu .mce-menu-item:hover,
.mce-container.mce-menu .mce-menu-item.mce-selected,
.mce-container.mce-menu .mce-menu-item:focus,
.mce-container.mce-menu .mce-menu-item-normal.mce-active,
.mce-container.mce-menu .mce-menu-item-preview.mce-active {
  background: #04a4cc;
}

/* temporary fix for admin-bar hover color */
#wpadminbar .ab-top-menu > li:hover > .ab-item,
#wpadminbar .ab-top-menu > li.hover > .ab-item,
#wpadminbar > #wp-toolbar > #wp-admin-bar-root-default li:hover span.ab-label,
#wpadminbar > #wp-toolbar > #wp-admin-bar-top-secondary li.hover span.ab-label,
#wpadminbar .ab-top-menu > li > .ab-item:focus,
#wpadminbar.nojq .quicklinks .ab-top-menu > li > .ab-item:focus,
#wpadminbar.nojs .ab-top-menu > li.menupop:hover > .ab-item,
#wpadminbar .ab-top-menu > li.menupop.hover > .ab-item {
  color: #333;
}

/* Override the theme filter highlight color for this scheme */
.theme-section.current,
.theme-filter.current {
  border-bottom-color: #04a4cc;
}
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             /*
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
  border-color: #829237 #727f30 #727f30;
  color: white;
  -webkit