tion:1.25s;transition-duration:1.25s;-webkit-transition-timing-function:cubic-bezier(.25,-2.5,.75,8);transition-timing-function:cubic-bezier(.25,-2.5,.75,8)}#accordion-section-add_menu{margin:15px 12px}.new-menu-section-content{display:none;padding:15px 0 0;clear:both}#accordion-section-add_menu .accordion-section-title{padding-left:45px}#accordion-section-add_menu .accordion-section-title:before{font:400 20px/1 dashicons;position:absolute;top:12px;left:14px;content:"\f132"}#create-new-menu-submit{float:right;margin:0 0 12px}.menu-delete-item{float:left;padding:1em 0;width:100%}li.assigned-to-menu-location .menu-delete-item{display:none}li.assigned-to-menu-location .add-new-menu-item{margin-bottom:1em}.menu-delete{color:#a00;cursor:pointer;text-decoration:underline}.menu-delete:focus,.menu-delete:hover{color:red;text-decoration:none}.menu-item-handle{margin-top:-1px}.ui-sortable-disabled .menu-item-handle{cursor:default}.menu-item-handle:hover{position:relative;z-index:10;color:#0073aa}#available-menu-items .menu-item-handle:hover .item-add,.menu-item-handle:hover .item-edit,.menu-item-handle:hover .item-type{color:#0073aa}.menu-item-edit-active .menu-item-handle{border-color:#999;border-bottom:none}.customize-control-nav_menu_item{margin-bottom:0}.customize-control-nav_menu{margin-top:12px}#available-menu-items .item-add:focus:before,#available-menu-items-search .clear-results:focus,#customize-controls .customize-info .customize-help-toggle:focus:before,.customize-screen-options-toggle:focus:before,.menu-delete:focus,.menu-item-bar .item-delete:focus:before,.wp-customizer .menu-item .submitbox .submitdelete:focus,.wp-customizer button:focus .toggle-indicator:after{-webkit-box-shadow:0 0 0 1px #5b9dd9,0 0 2px 1px rgba(30,140,190,.8);box-shadow:0 0 0 1px #5b9dd9,0 0 2px 1px rgba(30,140,190,.8)}@media screen and (max-width:782px){#available-menu-items #available-menu-items-search .accordion-section-content{top:63px}}@media screen and (max-width:640px){#available-menu-items #available-menu-items-search .accordion-section-content{top:133px}}                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                       #accordion-section-menu_locations {
	position: relative;
	margin-bottom: 15px;
}

.menu-in-location,
.menu-in-locations {
	display: block;
	font-weight: 600;
	font-size: 10px;
}

#customize-controls .theme-location-set,
#customize-controls .control-section .accordion-section-title:focus .menu-in-location,
#customize-controls .control-section .accordion-section-title:hover .menu-in-location {
	color: #555;
}

.wp-customizer .menu-item-bar .menu-item-handle,
.wp-customizer .menu-item-settings,
.wp-customizer .menu-item-settings .description-thin {
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
}

.wp-customizer .menu-item-bar {
	margin: 0;
}

.wp-customizer .menu-item-bar .menu-item-handle {
	width: 100%;
	background: #fff;
}

.wp-customizer .menu-item-handle .item-title {
	margin-right: 0;
}

.wp-customizer .menu-item-handle .item-type {
	padding: 1px 21px 0 5px;
	float: right;
	text-align: right;
}

.wp-customizer .menu-item-settings {
	max-width: 100%;
	overflow: hidden;
	padding: 10px;
	background: #eee;
	border: 1px solid #999;
	border-top: none;
}

.wp-customizer .menu-item-settings .description-thin {
	width: 100%;
	height: auto;
	margin: 0 0 8px 0;
}

.wp-customizer .menu-item-settings input[type="text"] {
	width: 100%;
}

.wp-customizer .menu-item-settings .submitbox {
	margin: 0;
	padding: 0;
}

.wp-customizer .menu-item-settings .link-to-original {
	padding: 5px 0;
	border: none;
	font-style: normal;
	margin: 0;
	width: 100%;
}

.wp-customizer .menu-item .submitbox .submitdelete {
	float: left;
	margin: 6px 0 0;
	padding: 0;
	cursor: pointer;
}


/**
 * Menu items reordering styles
 */

.menu-item-reorder-nav {
	display: none;
	background-color: #fff;
	position: absolute;
	top: 0;
	right: 0;
}

.menus-move-left:before {
	content: "\f341";
}

.menus-move-right:before {
	content: "\f345";
}

.reordering .menu-item .item-controls,
.reordering .menu-item .item-type {
	display: none;
}

.reordering .menu-item-reorder-nav {
	display: block;
}

.customize-control input.menu-name-field {
	width: 100%; /* Override the 98% default for customizer inputs, to align with the size of menu items. */
	margin: 12px 0;
}

.wp-customizer .menu-item .item-edit {
	position: absolute;
	right: -19px;
	top: 2px;
	display: block;
	width: 30px;
	height: 38px;
	margin-right: 0 !important;
	-webkit-box-shadow: none;
	box-shadow: none;
	outline: none;
	overflow: hidden;
	cursor: pointer;
}

.wp-customizer .menu-item.menu-item-edit-active .item-edit .toggle-indicator:after {
	content: "\f142";
}

.wp-customizer .menu-item-settings p.description {
	font-style: normal;
}

.wp-customizer .menu-settings dl {
	margin: 12px 0 0 0;
	padding: 0;
}

.wp-customizer .menu-settings .checkbox-input {
	margin-top: 8px;
}

.wp-customizer .menu-settings .menu-theme-locations {
	border-top: 1px solid #ccc;
}

.wp-customizer .menu-settings {
	margin-top: 36px;
	border-top: none;
}

.menu-settings .customize-control-checkbox label {
	line-height: 1;
}

/* @todo update selector or potentially remove */
.menu-settings .customize-control.customize-control-checkbox {
	margin-bottom: 8px; /* Override collapsing at smaller viewports. */
}

.customize-control-menu {
	margin-top: 4px;
}

#customize-controls .customize-info.open.active-menu-screen-options .customize-help-toggle {
	color: #555;
}

/* Screen Options */
.customize-screen-options-toggle {
	background: none;
	border: none;
	color: #555;
	cursor: pointer;
	margin: 0;
	padding: 20px;
	position: absolute;
	right: 0;
	top: 30px;
}

#customize-controls .customize-info .customize-help-toggle {
	padding: 20px;
}

#customize-controls .customize-info .customize-help-toggle:before {
	padding: 4px;
}

.customize-screen-options-toggle:hover,
.customize-screen-options-toggle:active,
.customize-screen-options-toggle:focus,
.active-menu-screen-options .customize-screen-options-toggle,
#customize-controls .customize-info.open.active-menu-screen-options .customize-help-toggle:hover,
#customize-controls .customize-info.open.active-menu-screen-options .customize-help-toggle:active,
#customize-controls .customize-info.open.active-menu-screen-options .customize-help-toggle:focus {
	color: #0073aa;
}

.customize-screen-options-toggle:focus,
#customize-controls .customize-info .customize-help-toggle:focus {
	outline: none;
}

.customize-screen-options-toggle:before {
	-moz-osx-font-smoothing: grayscale;
	border: none;
	content: "\f111";
	display: block;
	font: 18px/1 dashicons;
	padding: 5px;
	text-align: center;
	text-decoration: none !important;
	text-indent: 0;
	left: 6px;
	position: absolute;
	top: 6px;
}

.customize-screen-options-toggle:focus:before,
#customize-controls .customize-info .customize-help-toggle:focus:before {
	-webkit-border-radius: 100%;
	border-radius: 100%;
}

.wp-customizer #screen-options-wrap {
	display: none;
	background: #fff;
	border-top: 1px solid #ddd;
	padding: 4px 15px 15px;
}

.wp-customizer .metabox-prefs label {
	display: block;
	padding-right: 0;
	line-height: 30px;
}

/* rework the arrow indicator implementation for NVDA bug same as #32715 */
.wp-customizer .toggle-indicator {
	display: inline-block;
	font-size: 20px;
	line-height: 1;
	text-indent: -1px; /* account for the dashicon alignment */
}

.rtl .wp-customizer .toggle-indicator {
	text-indent: 1px; /* account for the dashicon alignment */
}

.wp-customizer .toggle-indicator:after {
	content: "\f140";
	speak: none;
	vertical-align: top;
	-webkit-border-radius: 50%;
	border-radius: 50%;
	color: #72777c;
	font: normal 20px/1 dashicons;
	-webkit-font-smoothing: a