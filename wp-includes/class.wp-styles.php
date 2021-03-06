ontent: "\f543";
	position: relative;
	right: 2px;
	top: 3px;
	display: inline-block;
	height: 20px;
	-webkit-border-radius: 50%;
	border-radius: 50%;
	font: normal 20px/1.05 dashicons; /* line height is to account for the dashicon's vertical alignment */
}

#available-menu-items .menu-item-handle.item-added .item-type,
#available-menu-items .menu-item-handle.item-added .item-title,
#available-menu-items .menu-item-handle.item-added:hover .item-add,
#available-menu-items .menu-item-handle.item-added .item-add:focus {
	color: #82878c;
}

#available-menu-items .menu-item-handle.item-added .item-add:before {
	content: "\f147";
}

#available-menu-items .accordion-section-title.loading .spinner,
#available-menu-items-search.loading .accordion-section-title .spinner {
	visibility: visible;
	margin: 0 20px;
}

#available-menu-items-search .clear-results {
	position: absolute;
	top: 20px;
	left: 20px;
	width: 20px;
	height: 20px;
	cursor: pointer;
	color: #a00;
	text-decoration: none;
}

#available-menu-items-search .clear-results,
#available-menu-items-search.loading .clear-results.is-visible {
	display: none;
}

#available-menu-items-search .clear-results.is-visible {
	display: block;
}

#available-menu-items-search .clear-results:before {
	content: "\f335";
	font: normal 20px/1 dashicons;
	-webkit-font-smoothing: antialiased;
	-moz-osx-font-smoothing: grayscale;
}

#available-menu-items-search .clear-results:hover,
#available-menu-items-search .clear-results:focus {
	color: #f00;
}

#available-menu-items-search .spinner {
	position: absolute;
	top: 20px;
	margin: 0 !important;
	left: 20px;
}

/* search results list */
#available-menu-items #available-menu-items-search .accordion-section-content {
	position: absolute;
	right: 1px;
	top: 60px; /* below title div / search input */
	bottom: 0px; /* 100% height that still triggers lazy load */
	max-height: none;
	width: 100%;
	padding: 1px 15px 15px;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
}

#available-menu-items-search .accordion-section-title:after {
	display: none;
}

#available-menu-items-search .accordion-section-content:empty {
	min-height: 0;
	padding: 0;
}

#available-menu-items-search.loading .accordion-section-content div {
	opacity: .5;
}

#available-menu-items-search.loading.loading-more .accordion-section-content div {
	opacity: 1;
}

#customize-preview {
	-webkit-transition: all 0.2s;
	transition: all 0.2s;
}

body.adding-menu-items #available-menu-items {
	right: 0;
	visibility: visible;
}

body.adding-menu-items .wp-full-overlay-main {
	right: 300px;
}

body.adding-menu-items #customize-preview {
	opacity: 0.4;
}

.menu-item-handle .spinner {
	display: none;
	float: right;
	margin: 0 0 0 8px;
}

.nav-menu-inserted-item-loading .spinner {
	display: block;
}

.nav-menu-inserted-item-loading .menu-item-handle .item-type {
	padding: 0 8px 0 0;
}

.nav-menu-inserted-item-loading .menu-item-handle,
.added-menu-item .menu-item-handle.loading {
	padding: 10px 8px 10px 15px;
	cursor: default;
	opacity: .5;
	background: #fff;
	color: #727773;
}

.added-menu-item .menu-item-handle {
	-webkit-transition-property: opacity, background, color;
	transition-property: opacity, background, color;
	-webkit-transition-duration: 1.25s;
	transition-duration: 1.25s;
	-webkit-transition-timing-function: cubic-bezier( .25, -2.5, .75, 8 );
	transition-timing-function: cubic-bezier( .25, -2.5, .75, 8 ); /* Replacement for .hide().fadeIn('slow') in JS to add emphasis when it's loaded. */
}

/* Add/delete Menus */

/* @todo update selector */
#accordion-section-add_menu {
	margin: 15px 12px;
}

.new-menu-section-content {
	display: none;
	padding: 15px 0 0 0;
	clear: both;
}

/* @todo update selector */
#accordion-section-add_menu .accordion-section-title {
	padding-right: 45px;
}

/* @todo update selector */
#accordion-section-add_menu .accordion-section-title:before {
	font: normal 20px/1 dashicons;
	position: absolute;
	top: 12px;
	right: 14px;
	content: "\f132";
}

#create-new-menu-submit {
	float: left;
	margin: 0 0 12px 0;
}

.menu-delete-item {
	float: right;
	padding: 1em 0;
	width: 100%;
}

li.assigned-to-menu-location .menu-delete-item {
	display: none;
}

li.assigned-to-menu-location .add-new-menu-item {
	margin-bottom: 1em;
}

.menu-delete {
	color: #a00;
	cursor: pointer;
	text-decoration: underline;
}

.menu-delete:hover,
.menu-delete:focus {
	color: #f00;
	text-decoration: none;
}

.menu-item-handle {
	margin-top: -1px;
}
.ui-sortable-disabled .menu-item-handle {
	cursor: default;
}

.menu-item-handle:hover {
	position: relative;
	z-index: 10;
	color: #0073aa;
}

.menu-item-handle:hover .item-type,
.menu-item-handle:hover .item-edit,
#available-menu-items .menu-item-handle:hover .item-add {
	color: #0073aa;
}

.menu-item-edit-active .menu-item-handle {
	border-color: #999;
	border-bottom: none;
}

.customize-control-nav_menu_item {
	margin-bottom: 0;
}

.customize-control-nav_menu {
	margin-top: 12px;
}

/**
 * box-shadows
 */

.wp-customizer .menu-item .submitbox .submitdelete:focus,
.customize-screen-options-toggle:focus:before,
#customize-controls .customize-info .customize-help-toggle:focus:before,
.wp-customizer button:focus .toggle-indicator:after,
#available-menu-items-search .clear-results:focus,
.menu-delete:focus,
.menu-item-bar .item-delete:focus:before,
#available-menu-items .item-add:focus:before {
	-webkit-box-shadow:
		0 0 0 1px #5b9dd9,
		0 0 2px 1px rgba(30, 140, 190, .8);
	box-shadow:
		0 0 0 1px #5b9dd9,
		0 0 2px 1px rgba(30, 140, 190, .8);
}


@media screen and ( max-width: 782px ) {
	#available-menu-items #available-menu-items-search .accordion-section-content {
		top: 63px;
	}
}

@media screen and ( max-width: 640px ) {
	#available-menu-items #available-menu-items-search .accordion-section-content {
		top: 133px;
	}
}
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                body,fieldset{margin:0}.screen-reader-text,fieldset,legend,td,th{padding:0}.current-site,.post-options .post-option,.postform{text-overflow:ellipsis;white-space:nowrap}html{font-family:sans-serif;-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%}*,:after,:before{-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box}@media only screen and (-webkit-min-device-pixel-ratio:1.5),only screen and (min-resolution:144d