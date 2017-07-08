customizer .menu-item-settings p.description {
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
	left: 0;
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
	right: 6px;
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
	padding-left: 0;
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
	-webkit-font-smoothing: antialiased;
	-moz-osx-font-smoothing: grayscale;
	text-decoratio