<?php

/**
* @version 1.0
* @package Prismatic
* @copyright (C) 2012 by Robin Jungermann
* @license Released under the terms of the GNU General Public License
**/

$max_sitewidth = $_GET['max_sitewidth'];
$max_sitewidth_switch = floor($max_sitewidth*0.8);

header("content-type: text/css");


echo("
html,body
{
    width: 100%;
    height: auto;
    margin: 0px;
    padding: 0px;
}


#siteWrapper {
	position:absolute; z-index:1;
	width: 100%;
    height: 100%;
    padding: 0px;
	margin:0 auto 50px;
	overflow:auto;	
}

#scroller {
	position:absolute; z-index:1;
/*	-webkit-touch-callout:none;*/
	-webkit-tap-highlight-color:rgba(0,0,0,0);
	width:100%;
	padding:0;
}


.wrapper {
	width: auto;
	margin: 0 auto;
}

header {
	width: 100%;
	min-height: 45px;	
	padding-top: 15px;
	margin-bottom: 40px;
}

header > .wrapper,
#system-message-container {
   height: auto;
   max-width: ".$max_sitewidth."px;
   margin-left: auto;
   margin-right: auto;
   padding: 0;
}

#ct_headerTools {
    display: inline;
    margin:15px 0;
    min-height:25px;
}

#ct_headerTools > div {
	margin-bottom: 20px;
}

#ct_headerLogin, 
#ct_headerSearch {
    float:right;
    margin-left:25px;
    position:relative;
    width:auto;
}

#ct_headerSearch input {
	margin: 0 20px;
}

#main {
	display: block;
	height:auto;
	max-width: ".$max_sitewidth."px;
	overflow: visible;
	margin-left: auto;
	margin-right: auto;
}

#main section {
	margin-bottom: 0;
	padding: 0;
}

.container:after {
  content: ".";
  display: block;
  clear: both;
  font-size: 0;
  height: 0;
  visibility: hidden;
}

footer,
.ct_spacerBottom {
	display: block;
	height:auto;
	max-width: ".($max_sitewidth)."px;
	overflow: visible;
	margin-left: auto;
	margin-right: auto;
}

.ct_spacerBottom {
	height: 30px;
}

.siteLogo {
	display: inline;
	float: left;
	width: auto;
	height: auto;
	min-height: 30px;
	margin-bottom: 10px;
}

.siteLogo img {
	max-width: 100%;
}

/* GRID ------------------------------------*/

.row,
.row_main {
	height:auto;
	width: 100%;
	margin: 0;
}

.row > div,
.row_main > div {
	display: block;
	vartical-align: top;
	float: left;
	position:relative;
	height: auto;
	margin-left: 1%;
	margin-bottom: 1%;
	padding-bottom: 10px;
}

.row > div:first-child,
.row_main > div:first-child,
.columnWidth_1 > div,
.end,
.span_6 {
	margin-left: 0% !important;
}

.row div div,
.row_main div div {
	width: auto;
}

.moduleContent {
	display: block;
}

.columnWidth_1 div { width: 100%; }
.columnWidth_2 div { width: 49.5%; }
.columnWidth_3 div { width: 32.66666666666666%; }
.columnWidth_4 div { width: 24.25%; }
.columnWidth_5 div { width: 19.2%; }
.columnWidth_6 div { width: 15.83333333333333%; }

.span_6 { width: 100% !important;}
.span_5 { width: 83.1666666665% !important;}
.span_4 { width: 66.3333333332% !important;}
.span_3 { width: 49.5% !important;}
.span_2 { width: 32.66666666666666% !important;}
.span_1 { width: 15.83333333333333% !important;}


.ct_left, .ct_right {
	width: 25%;
	margin-bottom: 1.875%;
	padding: 0 !important;
}

.ct_left {
	margin-right: 1.875%;	
}

.ct_right {
	margin-left: 1.875%;
	margin-right: 0% !important;	
}

.ct_componentContent {
	position: relative;
	display: inline;
	float: left;
	height: auto;
	z-index: 0;
	margin-left: 0 !important;
	margin-right: 0 !important;
	margin-bottom: 1.875%;
	padding: 0;
}

.ct_componentContent > .inner {
	padding: 10px;
}

.ct_componentWidth_2 { width: 46.25% !important }

.ct_componentWidth_3 { width: 73.125% !important }

.ct_componentWidth_4 { width: 100% !important }

.ct_componentContent p:first-child {
	margin-top: 0px;
}



.row > div > h1,
.row > div > h2,
.row > div > h3,
.row > div > h4,
.row > div > h5{
	padding: 0 10px;
	margin-bottom: 0;
	padding-top: 10px;
}

.row > div > p,
.row > div > div,
.row > div > form {
	padding: 0 10px;
	margin-bottom: 0;
	padding-top: 15px;
}

.row > div > ul,
.row > div > ol {
	padding: 0 10px;
	margin-bottom: 0;
	padding-top: 10px;
}



.row > div > h1:after,
.row > div > h2:after,
.row > div > h3:after,
.row > div > h4:after,
.row > div > h5:after {
    content: '';
    display: block;
    left: 0;
	padding-top: 10px;
    position: absolute;
    width: 100%;
}

.fullWidth {
	max-width: 100%;
	display: block;
	position:absolute;
	left: 0;
	margin: 0 !important;
}

/*-------------------------*/

.moduleContent h1,
.moduleContent h2,
.moduleContent h3,
.moduleContent h4,
.moduleContent h5,
.moduleContent h6 {
	margin-top: 0;
}


/* ACCORDION -------------------------------------*/

#accordion {
	max-width: ".$max_sitewidth."px;
	overflow: hidden;
	position: relative;
	height: auto;
	margin: auto;
	margin-bottom: 30px;
	
	-moz-border-radius: 3px;
	-webkit-border-radius: 3px;
	border-radius: 3px;
}


/* SLIDER -------------------------------------*/

#ct_sliderWrapper, #ct_sliderShadow {
	max-width: ".$max_sitewidth."px;
	overflow: hidden;
}

#ct_sliderWrapper 
{
	position: relative;
	z-index: 300;
	height: auto;
	margin: auto;
}
#ct_sliderShadow
{
	width: auto;
	height: auto;
	margin-left: auto;
	margin-righ: auto;
}
#ct_sliderShadow img {
	vertical-align: top;
	position: relative;
	top: 0;
}
#ct_sliderContent
{
	height: 100%;
	margin: auto;
}
#ct_sliderContent .moduletable
{
	background-color: transparent !important;
}

.flex-control-nav li a {
	height: 16px;
	width: 16px;
	border-radius: 8px;
}

/* BANNERS ----------------------------------------*/

.banneritem img {
	width: 100%;
}


/* Smartphones (portrait and landscape) ----------- */
@media only screen 
and (min-device-width : 320px) 
and (max-device-width : 480px) {
	.print-icon,
	.email-icon {
		display: none;
	}
}


/* Smartphones (portrait and landscape) ----------- */
@media only screen
and (min-width : 320px)
and (max-width : 480px) {
	
	#mainMenu {
		padding-bottom: 15px;
	}

  	.row > div {
		margin-left: 0%;
		margin-right: 0%;
	}
	
	#main {
		padding: 2%;
		padding-top: 3%;
	}
	
	.columnWidth_1 div, 
	.columnWidth_2 div,
	.columnWidth_3 div, 
	.columnWidth_4 div,
	.columnWidth_5 div,
	.columnWidth_6 div { width: 100%;}
	
	.span_1,
	.span_2,
	.span_3,
	.span_4,
	.span_5, 
	.span_6 { width: 100% !important }
	
	.row div:last-child,
	.end,
	.span_1 {	
	}
	
	.columnWidth_6 > div, 
	.columnWidth_5 > div,
	.columnWidth_4 > div, 
	.columnWidth_3 > div,
	.columnWidth_2 > div,
	.columnWidth_1 > div { 
		margin-bottom: 6%;
	}
	
	.siteLogo {
		display: block;
		width: 100%;
		float: none;
		position: relative;
		text-align: center;
	}
  
	#ct_sliderWrapper, 
	#ct_sliderShadow {
		width: 98%;
		overflow: hidden;
	}
	
	.flex-direction-nav {
		display: none;
	}
}



@media only screen
and (min-device-width : 320px)
and (max-device-width : 480px) {
	
	.flex-control-nav li a {
		height: 24px;
		width: 24px;
		border-radius: 12px;
	}
	
	.siteLogo {
		display: block;
		width: 100%;
		float: none;
		position: relative;
		text-align: center;
	}
	
}



/* Smartphones (portrait) ----------- */
@media only screen
and (max-width : 320px) {
	
	#mainMenu {
		padding-bottom: 15px;
	}
	
	#ct_headerSearch,
	#ct_headerLogin {
		display: inline-block;
		float: none;
		position: relative !important;
		top: 0;
		right: auto;
		width: 100%;
		margin-left: auto;
		margin-right: auto;
		margin-bottom: 0;
	}
	
	#ct_headerSearch input,
	#ct_headerLogin .moduletable,
	#mainMenu select
	{
		display: block !important;
		float: none;
		width: 300px !important;
		max-width: 300px !important;
		position: relative;
		margin-left: auto !important;
		margin-right: auto !important;
	}
	
	#ct_headerLogin input[type='text'] {
		width: 131px !important;
	}
	
	ul.autocompleter-choices {
		width: 300px !important;
		margin-left: 0 !important;
	}
	
    .row > div {
		margin-left: 0%;
		margin-right: 1%;
	}
	
	#main section {
		padding: 2%;
		padding-top: 3%;
	}
	
	.columnWidth_6 div, 
	.columnWidth_5 div,
	.columnWidth_4 div,
	.columnWidth_3 div,
	.columnWidth_2 div,
	.columnWidth_1 div { width: 99%;}
	
	.span_6,
	.span_5,
	.span_4,
	.span_3,
	.span_2, 
	.span_1 { width: 99% !important }
	
	.row div:last-child,
	.end,
	.span_1 {	
	}
	
	.columnWidth_6 > div, 
	.columnWidth_5 > div,
	.columnWidth_4 > div, 
	.columnWidth_3 > div,
	.columnWidth_2 > div,
	.columnWidth_1 > div { 
		margin-bottom: 6%;
	}
	
	.ct_left, .ct_right { 
		width: 98%; 
		margin-bottom: 1.875%;
		padding: 1%;
	}

	.ct_left {
		margin-right: 1.875%;	
	}
	
	.ct_right {
		margin-left: 1.875%;	
	}
	
	.ct_componentContent {
		position: relative;
		display: inline;
		float: left;
		height: auto;
		z-index: 0;
		margin-bottom: 1.875%;
	}
	
	.ct_componentWidth_2,
	.ct_componentWidth_3,
	.ct_componentWidth_4 { width: 98% !important }
	
	.moduletable_ct_darkBox,
	.moduletable_ct_lightBox {
		width: 94% !important;
		padding: 3% !important;
	}
	
	.siteLogo {
		display: block;
		width: 100%;
		float: none;
		position: relative;
		text-align: center;
		margin-bottom: 15px;
	}
  
	#ct_sliderWrapper, #ct_sliderShadow {
		width: 98%;
		overflow: hidden;
	}
	
}


@media only screen
and (min-width : 321px)
and (max-width : 786px) {
	
	#mainMenu {
		padding-bottom: 15px;
	}
	
	#ct_headerSearch,
	#ct_headerLogin {
		display: inline-block;;
		float: none;
		position: relative !important;
		top: 0;
		right: auto;
		width: 100%;
		margin-left: auto;
		margin-right: auto;
		margin-bottom: 0;
	}
	
	#ct_headerSearch input,
	#ct_headerLogin .moduletable,
	#mainMenu select
	{
		display: block !important;
		float: none;
		width: 300px !important;
		max-width: 300px !important;
		position: relative;
		margin-left: auto !important;
		margin-right: auto !important;
	}
	
	#ct_headerLogin input[type='text'] {
		width: 131px !important;
	}
	
	ul.autocompleter-choices {
		width: 300px !important;
		margin-left: 0 !important;
	}

	.ct_left, .ct_right { 
		width: 98%; 
		margin-bottom: 1.875%;
		padding: 1%;
	}

	.ct_left {
		margin-right: 1.875%;	
	}
	
	.ct_right {
		margin-left: 1.875%;	
	}
	
	.ct_componentContent {
		position: relative;
		display: inline;
		float: left;
		height: auto;
		z-index: 0;
		margin-bottom: 1.875%;
	}
	
	.ct_componentWidth_2,
	.ct_componentWidth_3,
	.ct_componentWidth_4 { width: 98% !important }
	
	.siteLogo {
		display: block;
		width: 100%;
		float: none;
		position: relative;
		text-align: center;
		margin-bottom: 15px;
	}
	
}



@media only screen
and (min-width : 321px)
and (max-width : ".$max_sitewidth_switch."px) {
	
	#main section {
		margin-left: 1%;
		margin-right: 1%;
	}
  
  	.row > div {
		margin-left: 1%;
	}
	
	.columnWidth_1 div,
	.columnWidth_2 div 	{ width: 100%; }
	.columnWidth_3 div,
	.columnWidth_4 div	{ width: 49.5%; }
	.columnWidth_5 div,
	.columnWidth_6 div	{ width: 32.66666666666666%; }
	
	.span_6,
	.span_5 { width: 98% !important }
	.span_4,
	.span_3 { width: 49.5%!important }
	.span_2, 
	.span_1 { width: 32.66666666666666% !important }


	.row div:last-child,
	.end,
	.span_1 {	
		margin-right: 0%;
	}
	
	.columnWidth_6 > div, 
	.columnWidth_5 > div,
	.columnWidth_4 > div, 
	.columnWidth_3 > div,
	.columnWidth_2 > div,
	.columnWidth_1 > div { 
		border-bottom: none;
		margin-bottom: 1%;
	}
	
	.ct_left, .ct_right { 
		width: 98%; 
		margin-bottom: 1.875%;
		padding: 1%;
	}

	.ct_left {
		margin-right: 1.875%;	
	}
	
	.ct_componentContent {
		position: relative;
		display: inline;
		float: left;
		height: auto;
		z-index: 0;
		margin-right: 1.875%;
		margin-bottom: 1.875%;
	}
	
	.ct_componentWidth_2 { width: 100% !important }
	
	.ct_componentWidth_3 { width: 100% !important }
	
	.ct_componentWidth_4 { width: 100% !important }
	
	
	.siteLogo {
		display: block;
		width: 100%;
		float: none;
		position: relative;
		text-align: center;
	}
  
	#ct_sliderWrapper, #ct_sliderShadow {
		width: 98%;
		overflow: hidden;
	}
	
}

");
?>