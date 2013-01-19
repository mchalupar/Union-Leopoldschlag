<?php

/**
* @version 1.0
* @package Prismatic
* @copyright (C) 2012 by Robin Jungermann
* @license Released under the terms of the GNU General Public License
**/

header("content-type: text/css");

echo("
@charset 'utf-8';
/* CSS Document */

.pane-sliders div.panel img,
.contact-image > img {
	 behavior: none !important;
}

.pane-sliders div.panel input[type='text'],
.pane-sliders div.panel input[type='password'],
.pane-sliders div.panel input[type='email'],
.pane-sliders div.panel textarea,
.pane-sliders div.panel button
 {
  behavior: none !important;
}

.pane-sliders div.panel input[type='text'],
.pane-sliders div.panel input[type='password'],
.pane-sliders div.panel input[type='email'],
.pane-sliders div.panel textarea {
  border: none;
}

ul.menu,
.ct_menu_horizontal ul.menu li:first-child a, 
.ct_menu_horizontal ul.menu li:first-child .separator,
.ct_menu_vertical ul.menu li:first-child a, 
.ct_menu_vertical ul.menu li:first-child .separator,
.ct_menu_horizontal ul.menu li:last-child a, 
.ct_menu_horizontal ul.menu li:last-child .separator,
.ct_menu_vertical ul.menu li:last-child a, 
.ct_menu_vertical ul.menu li:last-child .separator,
ul.menu ul
 {	
	-webkit-border-radius: 0px;
	-moz-border-radius: 0px;
	border-radius: 0px;
}


.item-page {
	margin:0;
}

ul.menu li > a,
ul.menu li > span,
ul.menu li ul li > a,
ul.menu li ul li > span,
ul.menu li ul li ul li > a,
ul.menu li ul li ul li > span {
	background: none;
}

#navigation {
	padding: 13px 0 0 0;
}

input[type='text'], input[type='password'], input[type='email'], select, textarea {
	position: relative;
	background-image:url(../images/bg_input_ie.png);
}

select {
	padding-top: 3px;
	padding-bottom: 1px;
}

.btn,
input.button,
button,
.btn-group > .btn:first-child,
.ct_buttonYellow, 
.ct_buttonRed, 
.ct_buttonBlue,
.ct_buttonGreen,
.ct_buttonPink,
.ct_buttonBlack,
.ct_buttonWhite,
.ct_buttonAccent,
ul.pagenav li a,
.ct_pagination > div {
	box-shadow: none;
	-pie-box-shadow: none;
}

");

?>