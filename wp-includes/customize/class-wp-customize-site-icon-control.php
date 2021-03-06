                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                /*
 * Button mixin- creates 3d-ish button effect with correct
 * highlights/shadows, based on a base color.
 */
html {
  background: #f5f5f5;
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
  color: #04a4cc;
}

input[type=radio]:checked:before {
  background: #04a4cc;
}

.wp-core-ui input[type="reset"]:hover,
.wp-core-ui input[type="reset"]:active {
  color: #0096dd;
}

/* Core UI */
.wp-core-ui .button-primary {
  background: #04a4cc;
  border-color: #037c9a #036881 #036881;
  color: white;
  -webkit-box-shadow: 0 1px 0 #036881;
  box-shadow: 0 1px 0 #036881;
  text-shadow: 0 -1px 1px #036881, 1px 0 1px #036881, 0 1px 1px #036881, -1px 0 1px #036881;
}

.wp-core-ui .button-primary:hover, .wp-core-ui .button-primary:focus {
  background: #04b0db;
  border-color: #036881;
  color: white;
  -webkit-box-shadow: 0 1px 0 #036881;
  box-shadow: 0 1px 0 #036881;
}

.wp-core-ui .button-primary:focus {
  -webkit-box-shadow: inset 0 1px 0 #037c9a, 0 0 2px 1px #33b3db;
  box-shadow: inset 0 1px 0 #037c9a, 0 0 2px 1px #33b3db;
}

.wp-core-ui .button-primary:active {
  background: #037c9a;
  border-color: #036881;
  -webkit-box-shadow: inset 0 2px 0 #036881;
  box-shadow: inset 0 2px 0 #036881;
}

.wp-core-ui .button-primary[disabled], .wp-core-ui .button-primary:disabled, .wp-core-ui .button-primary.button-primary-disabled, .wp-core-ui .button-primary.disabled {
  color: #c7cfd1 !important;
  background: #0384a4 !important;
  border-color: #036881 !important;
  text-shadow: none !important;
}

.wp-core-ui