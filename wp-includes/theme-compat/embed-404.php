rgin-top: 0;
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
	left: -19px;
	width: 30px;
	height: 38px;
	cursor: pointer;
	display: none;
}

.menu-item-bar .item-delete:before {
	content: "\f335";
	position: absolute;
	top: 9px;
	right: 5px;
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
