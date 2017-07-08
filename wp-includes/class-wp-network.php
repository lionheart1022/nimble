 }
.menu-item-depth-7  { margin-left: 140px; }
.menu-item-depth-8  { margin-left: 160px; } /* Not likely to be used or useful beyond this depth */
.menu-item-depth-9  { margin-left: 180px; }
.menu-item-depth-10 { margin-left: 200px; }
.menu-item-depth-11 { margin-left: 220px; }

/* @todo handle .menu-item-settings width */
.menu-item-depth-0  > .menu-item-bar { margin-right: 0;     }
.menu-item-depth-1  > .menu-item-bar { margin-right: 20px;  }
.menu-item-depth-2  > .menu-item-bar { margin-right: 40px;  }
.menu-item-depth-3  > .menu-item-bar { margin-right: 60px;  }
.menu-item-depth-4  > .menu-item-bar { margin-right: 80px;  }
.menu-item-depth-5  > .menu-item-bar { margin-right: 100px; }
.menu-item-depth-6  > .menu-item-bar { margin-right: 120px; }
.menu-item-depth-7  > .menu-item-bar { margin-right: 140px; }
.menu-item-depth-8  > .menu-item-bar { margin-right: 160px; }
.menu-item-depth-9  > .menu-item-bar { margin-right: 180px; }
.menu-item-depth-10 > .menu-item-bar { margin-right: 200px; }
.menu-item-depth-11 > .menu-item-bar { margin-right: 220px; }

/* Submenu left margin. */
.menu-item-depth-0  .menu-item-transport { margin-left: 0;      }
.menu-item-depth-1  .menu-item-transport { margin-left: -20px;  }
.menu-item-depth-3  .menu-item-transport { margin-left: -60px;  }
.menu-item-depth-4  .menu-item-transport { margin-left: -80px;  }
.menu-item-depth-2  .menu-item-transport { margin-left: -40px;  }
.menu-item-depth-5  .menu-item-transport { margin-left: -100px; }
.menu-item-depth-6  .menu-item-transport { margin-left: -120px; }
.menu-item-depth-7  .menu-item-transport { margin-left: -140px; }
.menu-item-depth-8  .menu-item-transport { margin-left: -160px; }
.menu-item-depth-9  .menu-item-transport { margin-left: -180px; }
.menu-item-depth-10 .menu-item-transport { margin-left: -200px; }
.menu-item-depth-11 .menu-item-transport { margin-left: -220px; }

/* WARNING: The 20px factor is hard-coded in JS. */
.reordering .menu-item-depth-0  { margin-left: 0;     }
.reordering .menu-item-depth-1  { margin-left: 15px;  }
.reordering .menu-item-depth-2  { margin-left: 30px;  }
.reordering .menu-item-depth-3  { margin-left: 45px;  }
.reordering .menu-item-depth-4  { margin-left: 60px;  }
.reordering .menu-item-depth-5  { margin-left: 75px;  }
.reordering .menu-item-depth-6  { margin-left: 90px;  }
.reordering .menu-item-depth-7  { margin-left: 105px; }
.reordering .menu-item-depth-8  { margin-left: 120px; } /* Not likely to be used or useful beyond this depth */
.reordering .menu-item-depth-9  { margin-left: 135px; }
.reordering .menu-item-depth-10 { margin-left: 150px; }
.reordering .menu-item-depth-11 { margin-left: 165px; }

.reordering .menu-item-depth-0  > .menu-item-bar { margin-right: 0;     }
.reordering .menu-item-depth-1  > .menu-item-bar { margin-right: 15px;  }
.reordering .menu-item-depth-2  > .menu-item-bar { margin-right: 30px;  }
.reordering .menu-item-depth-3  > .menu-item-bar { margin-right: 45px;  }
.reordering .menu-item-depth-4  > .menu-item-bar { margin-right: 60px;  }
.reordering .menu-item-depth-5  > .menu-item-bar { margin-right: 75px;  }
.reordering .menu-item-depth-6  > .menu-item-bar { margin-right: 90px;  }
.reordering .menu-item-depth-7  > .menu-item-bar { margin-right: 105px; }
.reordering .menu-item-depth-8  > .menu-item-bar { margin-right: 120px; }
.reordering .menu-item-depth-9  > .menu-item-bar { margin-right: 135px; }
.reordering .menu-item-depth-10 > .menu-item-bar { margin-right: 150px; }
.reordering .menu-item-depth-11 > .menu-item-bar { margin-right: 165px; }

.control-section-nav_menu .menu .menu-item-edit-active {
	margin-left: 0;
}

.control-section-nav_menu .menu .menu-item-edit-active .menu-item-bar {
	margin-right: 0;
}

.control-section-nav_menu .menu .sortable-placeholder {
	margin-top: 0;
	margin-bottom: 1px;
	max-width: -webkit-calc(100% - 2px);
	max-width: calc(100% - 2px);
	float: left;
	display: list-item;
	border-color: #a0a5aa;
}

.menu-item-transport li.customize-control {
	float: none;
}

.control-section-nav_menu .menu ul.menu-item-transport .menu-item-bar {
	margin-top: 0;
}

/**
 * Add-menu-items mode
 */

.adding-menu-items .control-section {
	opacity: .4;
}

.adding-menu-items .control-panel.control-section,
.adding-menu-items .control-section.open {
	opacity: 1;
}

.menu-item-bar .item-delete {
	color: #a00;
	position: absolute;
	top: 2px;
	right: -19px;
	width: 30px;
	height: 38px;
	cursor: pointer;
	display: none;
}

.menu-item-bar .item-delete:before {
	content: "\f335";
	position: absolute;
	top: 9px;
	left: 5px;
	-webkit-border-radius: 50%;
	border-radius: 50%;
	font: normal 20px/1 dashicons;
	-webkit-font-smoothing: antialiased;
	-moz-osx-font-smoothing: grayscale;
}

.ie8 .menu-item-bar .item-delete:before {
	top: -10px;
}

.menu-item-bar .item-delete:hover,
.menu-item-bar .item-delete:focus {
	-webkit-box-shadow: none;
	box-shadow: none;
	outline: none;
	color: #f00;
}

.adding-menu-items .menu-item-bar .item-edit {
	display: none;
}

.adding-menu-items .menu-item-bar .item-delete {
	display: block;
}

/**
 * Styles for menu-item addition panel
 */

#available-menu-items.opening {
	overflow-y: hidden; /* avoid scrollbar jitter with animating heights */
}

#available-menu-items #available-menu-items-search.open {
	height: 100%;
	border-bottom: none;
}

#available-menu-items .accordion-section-title {
	border-left: none;
	border-right: none;
	background: #fff;
	-webkit-transition: background-color 0.15s;
	transition: background-color 0.15s;
}

#available-menu-items .open .accordion-section-title,
#available-menu-items #available-menu-items-search .accordion-section-title {
	background: #eee;
}

/* rework the arrow indicator implementation for NVDA bug see #32715 */
#available-menu-items .accordion-section-title:after {
	content: none !important;
}

#available-menu-items .accordion-section-title:hover .toggle-indicator:after,
#available-menu-items .button-link:hover .toggle-indicator:after,
#available-menu-items .button-link:focus .toggle-indicator:after {
	color: #23282d;
}

#available-menu-items .open .accordion-section-title .toggle-indicator:after {
	content: "\f142";
	color: #23282d;
}

#available-menu-items .accordion-section-content {
	overflow-y: auto;
	max-height: 200px; /* This gets set in JS to fit the screen size, and based on # of sections. */
	background: transparent;
}

#available-menu-items .accordion-section-title button {
	display: block;
	width: 28px;
	height: 35px;
	position: absolute;
	top: 5px;
	right: 5px;
	-webkit-box-shadow: none;
	box-shadow: none;
	outline: none;
	cursor: pointer;
}

#available-menu-items .accordion-section-title .no-items,
#available-menu-items .cannot-expand .accordion-section-title .spinner,
#available-menu-items .cannot-expand .accordion-section-title > button {
	display: none;
}

#available-menu-items-search.cannot-expand .accordion-section-title .spinner {
	display: block;
}

#available-menu-items .cannot-expand .accordion-section-title .no-items {
	float: right;
	color: #555d66;
	font-weight: normal;
	margin-left: 5px;
}

#available-menu-items .accordion-section-content {
	padding: 1px 15px 15px 15px;
	margin: 0;
	max-height: 290px;
}

#available-menu-items .menu-item-tpl {
	margin: 0;
}

#custom-menu-item-name.invalid,
#custom-menu-item-url.invalid,
.menu-name-field.invalid,
.menu-name-field.invalid:focus {
	border: 1px solid #f00;
}

#available-menu-items .menu-item-handle .item-type {
	padding-right: 0;
}

#available-menu-items .menu-item-handle .item-title {
	padding-left: 20px;
}

#available-menu-items .menu-item-handle {
	cursor: pointer;
}

#available-menu-items .menu-item-handle {
	-webkit-box-shadow: none;
	box-shadow: none;
	margin-top: -1px;
}

#available-menu-items .menu-item-handle:hover {
	z-index: 1;
}

#available-menu-items .item-title h4 {
	padding: 0 0 5px;
	font-size: 14px;
}

#available-menu-items .item-add {
	position: absolute;
	top: 1px;
	left: 1px;
	color: #82878c;
	width: 30px;
	height: 38px;
	-webkit-box-shadow: none;
	box-shadow: none;
	outline: none;
	cursor: pointer;
}

#available-menu-items .menu-item-handle .item-add:focus {
	color: #23282d;
}

#available-menu-items .item-add:before {
	content: "\f543";
	position: relative;
	left: 2px;
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
	right: 20px;
	width: 20px;
	height: 20px;
	cur