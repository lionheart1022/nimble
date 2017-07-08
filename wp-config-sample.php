x-sizing: border-box;
	box-sizing: border-box;
	margin-bottom: 0;
	padding: 12px 16px 0;
	width: 35%;
	height: 100%;
	-webkit-box-shadow: inset 0px 4px 4px -4px rgba(0, 0, 0, 0.1);
	box-shadow: inset 0px 4px 4px -4px rgba(0, 0, 0, 0.1);
	border-bottom: 0;
	border-left: 1px solid #ddd;
	background: #f3f3f3;
}

.edit-attachment-frame .attachment-info .details,
.edit-attachment-frame .attachment-info .settings {
	position: relative; /* RTL fix, #WP29352 */
	overflow: hidden;
	float: none;
	margin-bottom: 15px;
	padding-bottom: 15px;
	border-bottom: 1px solid #ddd;
}

.edit-attachment-frame .attachment-info .filename {
	font-weight: normal;
	color: #666;
}

.edit-attachment-frame .attachment-info .thumbnail {
	margin-bottom: 12px;
}

.attachment-info .actions {
	margin-bottom: 16px;
}

.attachment-info .actions a {
	display: inline;
	text-decoration: none;
}


/*------------------------------------------------------------------------------
  14.2 - Image Editor
------------------------------------------------------------------------------*/

.wp_attachment_details label[for="content"] {
	font-size: 13px;
	line-height: 1.5;
	margin: 1em 0;
}

.wp_attachment_details #attachment_caption {
	height: 4em;
}

.describe .image-editor {
	vertical-align: top;
}

.imgedit-wrap {
	position: relative;
}

.imgedit-settings p {
	margin: 8px 0 0;
}

.describe .imgedit-wrap .imgedit-settings {
	padding: 0 5px;
}

.wp_attachment_holder div.updated {
	margin-top: 0;
}

.wp_attachment_holder .imgedit-wrap > div {
	height: auto;
	overflow: hidden;
}

.wp_attachment_holder .imgedit-wrap .imgedit-panel-content {
	padding-right: 16px;
	width: auto;
	overflow: hidden;
}

.wp_attachment_holder .imgedit-wrap .imgedit-settings {
	float: right;
	width: 250px;
}

.imgedit-settings input {
	margin-top: 0;
	vertical-align: middle;
}

.imgedit-wait {
	position: absolute;
	top: 0;
	background: #fff url(../images/spinner.gif) no-repeat center;
	-webkit-background-size: 20px 20px;
	background-size: 20px 20px;
	opacity: 0.7;
	filter: alpha(opacity=70);
	width: 100%;
	height: 500px;
	display: none;
}

.no-float {
	float: none;
}

.media-disabled,
.imgedit-settings .disabled {
	color: grey;
}

.wp_attachment_image,
.A1B1 {
	overflow: hidden;
}

.wp_attachment_image .button,
.A1B1 .button {
	float: left;
}

.no-js .wp_attachment_image .button {
	display: none;
}

.wp_attachment_image .spinner,
.A1B1 .spinner {
	float: left;
}

.imgedit-menu {
	margin: 0 0 12px;
	min-width: 300px;
}

.imgedit-menu div {
	float: left;
	width: 32px;
	border: 1px solid #d5d5d5;
	background: #f1f1f1;
	margin: 0 8px 0 0;
	height: 32px;
	-webkit-font-smoothing: antialiased;
	-moz-osx-font-smoothing: grayscale;
	text-align: center;
	line-height: 28px;
	color: #777;
	cursor: pointer;
}

.imgedit-menu div:before {
	font: normal 20px/1 'dashicons';
	speak: none;
	vertical-