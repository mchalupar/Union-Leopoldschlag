<?php 

/**
* @version 1.0
* @package Prismatic
* @copyright (C) 2012 by Robin Jungermann
* @license Released under the terms of the GNU General Public License
**/

$templateUrl = str_replace('%','/',$_GET['templateurl']);

echo "/* --------------- templateUrl= ".$templateUrl." ------------- */";


$hexTable ='/[^a-zA-Z0-9]/'; 

$base_color = 			"#".preg_replace ($hexTable,'',$_GET['base_color']); // base color from template settings
$accent_color = 		"#".preg_replace ($hexTable,'',$_GET['accent_color']); // accent color from template settings
$text_color = 			"#".preg_replace ($hexTable,'',$_GET['text_color']); // text color from template settings
$menu_text_color = 		"#".preg_replace ($hexTable,'',$_GET['menu_text_color']); // menu-text color from template settings
$button_text_color = 	"#".preg_replace ($hexTable,'',$_GET['button_text_color']); // menu-text color from template settings

$base_transparency = $_GET['base_transparency']; // background style from template settings

echo "/* BG: ".$bg_style."*/";



/* DEFINITION OF THE COLORS & SHADES */

$baseColor = 			$base_color;
$baseColorRGB = 		hex2rgb($base_color);
$accentColor = 			$accent_color;
$textColor = 			$text_color;
$textColorRGB = 		hex2rgb($text_color);
$menuTextColor = 		$menu_text_color;
$buttonTextColor = 		$button_text_color;

$bodyGradientLight = 		ct_hsvLuminance($base_color, 0.15);
$bodyGradientLightIE = 		ct_hsvLuminance($base_color, 0.2);
$bodyGradientDark = 		ct_hsvLuminance($base_color, -0.025);

$darkboxGradientLight = 	ct_hsvLuminance($base_color, -0.02);
$darkboxGradientDark = 		ct_hsvLuminance($base_color, -0.06);

$lightboxGradientLight = 	ct_hsvLuminance($base_color, 0.081);
$lightboxGradientDark = 	ct_hsvLuminance($base_color, 0.025);

$accentGradientLight = 		ct_hsvLuminance($accent_color, 0.1);
$accentGradientDark = 		ct_hsvLuminance($accent_color, -0.2);

$accentBorderLight = 		ct_hsvLuminance($accent_color, 0.05);
$accentBorderDark = 		ct_hsvLuminance($accent_color, -0.1);

$inputColorLight =			ct_hsvLuminance($baseColor, 0.35);
$inputColorDark	=			ct_hsvLuminance($baseColor, -0.1);

$tableRow0RGB = 			hex2rgb($baseColor);
$tabelHeaderBG = 			ct_hsvLuminance($base_color, 0.1);
$tabelRowEvenBG = 			ct_hsvLuminance($base_color, 0.025);
$tabelRowOddBG= 			ct_hsvLuminance($base_color, 0.05);


$base_brightness = ceil(0.299 * $baseColorRGB[0]  + 0.587 * $baseColorRGB[1] + 0.114 * $baseColorRGB[2]);
echo("/* BASE-BRIGHTNESS = ".$base_brightness." */");




if($base_brightness > 210 ) {
    //bright base color
	$bodyGradientLight = 		ct_hsvLuminance($base_color, 0.05);
	$bodyGradientLightIE = 		ct_hsvLuminance($base_color, 0.05);
	$bodyGradientDark = 		ct_hsvLuminance($base_color, -0.07);
	
	$borderColorLight = 		ct_hsvLuminance($baseColor, -0.15);
	
	$inputColorLight =			ct_hsvLuminance($baseColor, -0.08);
	$inputColorDark	=			ct_hsvLuminance($baseColor, -0.12);
}

if($base_brightness > 240 ) {
    //bright base color
	$bodyGradientLight = 		ct_hsvLuminance($base_color, -0.03);
	$bodyGradientLightIE = 		ct_hsvLuminance($base_color, -0.03);
	$bodyGradientDark = 		ct_hsvLuminance($base_color, -0.09);
	
	$borderColorLight = 		ct_hsvLuminance($baseColor, -0.2);
}

if($base_brightness < 20 ) {
    //bright base color
	$bodyGradientLight = 		ct_hsvLuminance($base_color, 0.27);
	$bodyGradientLightIE = 		ct_hsvLuminance($base_color, 0.27);
	$bodyGradientDark = 		ct_hsvLuminance($base_color, 0.15);
	
	$borderColorLight = 		ct_hsvLuminance($baseColor, 0.5);
	
	$darkboxGradientLight = 	ct_hsvLuminance($base_color, 0.11);
	$darkboxGradientDark = 		ct_hsvLuminance($base_color, 0.07);
	
	$lightboxGradientLight = 	ct_hsvLuminance($base_color, 0.27);
	$lightboxGradientDark = 	ct_hsvLuminance($base_color, 0.22);
	
	$inputColorLight =			ct_hsvLuminance($baseColor, 0.23);
	$inputColorDark	=			ct_hsvLuminance($baseColor, 0.17);
}


$textRGB = 					hex2rgb($textColor);

$text_color_r = hexdec(substr($text_color,1,2));
$text_color_g = hexdec(substr($text_color,3,2));
$text_color_b = hexdec(substr($text_color,5,2));
$text_brightness = ceil(0.299 * $text_color_r  + 0.587 * $text_color_g + 0.114 * $text_color_b);

echo("/* R = ".$text_color_r."/ G = ".$text_color_g."/ B= ".$text_color_b."/ BRIGHTNESS = ".$text_brightness." */");

echo ("/* ABERRATION =  ".((($text_color_r + $text_color_g + $text_color_b) / 3)-$text_color_r)." brightness: ".$text_brightness." */" );


/* CHOOSE THEME IN RELATION TO BASE COLOR -------------------------------------------------------------------------- */

$fontColor = $text_color;
$fontColorBase = $fontColor;


if($text_brightness > 138 ) {
    //bright text color
	$themeColor = "light";
	$fontColorBright1 = ct_hsvLuminance($text_color, -0.2);
	$fontColorBright2 = ct_hsvLuminance($text_color, -0.3);
	$fontColorDark1 = 	ct_hsvLuminance($text_color, 0);
	
}else{
    //dark text color
	$themeColor = "dark";
	$fontColorBright1 = ct_hsvLuminance($text_color, 0.1);
	$fontColorBright2 = ct_hsvLuminance($text_color, 0.2);
	$fontColorDark1 = 	ct_hsvLuminance($text_color, 0);		
}

echo ("/*fontColorBright1: ".$fontColorBright1." / fontColorBright2: ".$fontColorBright2." / fontColorDark1: ".$fontColorDark1." */");

?>