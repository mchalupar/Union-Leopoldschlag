<?php
/**
* @Copyright Copyright (C) 2010- Tobias Grahn (joohopia.com)
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
* Author: Tobias Grahn
* Email: info@joohopia.com
* Module: GoboSlide Slideshow
* Version: 3.0
* /*** 
GoboSlide jQuery Slideshow Script -  Original non-joomla code released by Jon Raasch - Strongly modified by joohopia.com for Joomla CMS 
A big thanks goes out to Patrick Dadzio for the css-injection.
***/
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
$gobborder	= $params->get( 'gobborder', "" );
$gobwin	= "1";
$gobcolor	= "#000000";
$goblinks	= "1";
$gobtool	= "0";
$gobspeed	= $params->get( 'gobspeed', "" );
$width	= $params->get( 'width', "" );
$height	= $params->get( 'height', "" );
$slidenumber=$params->get( 'slidenumber',"" );
$slide1url=$params->get( 'slide1url',"");
$slide2url=$params->get( 'slide2url',"" );
$slide3url=$params->get( 'slide3url',"" );
$slide4url=$params->get( 'slide4url',"" );
$slide5url=$params->get( 'slide5url',"" );
$slide6url=$params->get( 'slide6url',"" );
$slide7url=$params->get( 'slide7url',"" );
$slide8url=$params->get( 'slide8url',"" );
$slide9url=$params->get( 'slide9url',"" );
$slide10url=$params->get( 'slide10url',"" );
$slide1=$params->get( 'slide1',"" );
$slide2=$params->get( 'slide2',"" );
$slide3=$params->get( 'slide3',"");
$slide4=$params->get( 'slide4',"" );
$slide5=$params->get( 'slide5',"" );
$slide6=$params->get( 'slide6',"" );
$slide7=$params->get( 'slide7',"" );
$slide8=$params->get( 'slide8',"" );
$slide9=$params->get( 'slide9',"" );
$slide10=$params->get( 'slide10',"" );
$gobuniqe=$params->get( 'gobuniqe',"" );
if (class_exists('JPlatform')) {$jvera =JPlatform::RELEASE;}
?>
<script type="text/javascript" src="modules/mod_goboslide/js/jquery.js"></script>
<script type="text/javascript"> 
jQuery.noConflict(); 
function slideSwitch_<?php echo $gobuniqe; ?>() {
var $active = jQuery('#slideshow_<?php echo $gobuniqe; ?> DIV.active');
if ( $active.length == 0 ) $active = jQuery('#slideshow_<?php echo $gobuniqe; ?> DIV:last');
var $next =  $active.next().length ? $active.next()
: jQuery('#slideshow_<?php echo $gobuniqe; ?> DIV:first');
$active.addClass('last-active');
$next.css({opacity: 0.0})
.addClass('active')
.animate({opacity: 1.0}, 1000, function() {
$active.removeClass('active last-active');
});
}
jQuery(function() {
setInterval( "slideSwitch_<?php echo $gobuniqe; ?>()", <?php echo $gobspeed; ?> );
});
(jQuery); 
</script>
<?php 
$injection='
<style type="text/css">
#slideshow_' . $gobuniqe . 
' {
margin-top:0px;
margin-bottom:' . $gobborder . 'px;
position:relative; 
width: ' . $width . 'px;
height: ' . $height . 'px;
border: ' . $gobborder . 'px solid #000;
}
#slideshow_' . $gobuniqe . '
DIV {
position:absolute;
top:0;
left:0;
z-index:8;
opacity:0.0;
height: ' . $height . 'px;
/* background-color: #000;*/
}
#slideshow_' . $gobuniqe . '
DIV.active {
z-index:10;
opacity:1.0;
}
#slideshow_' . $gobuniqe . '
DIV.last-active {
z-index:9;
}
#slideshow_' . $gobuniqe . ' DIV IMG {
width: ' . $width . 'px;
height: ' . $height . 'px;
display: block;
}
.joohopia a{
background-color: #000000;
z-index:9999;
color: #555555;
display: block;
position: relative;
font-size:7px;
}
</style>';

$picfolder="images/";
$docum =& JFactory::getDocument();
$docum->addCustomTag($injection);

$slide1=$picfolder."".$slide1;
$slide2=$picfolder."".$slide2;
$slide3=$picfolder."".$slide3;
$slide4=$picfolder."".$slide4;
$slide5=$picfolder."".$slide5;
$slide6=$picfolder."".$slide6;
$slide7=$picfolder."".$slide7;
$slide8=$picfolder."".$slide8;
$slide9=$picfolder."".$slide9;
$slide10=$picfolder."".$slide10;
?>
<div id="slideshow_<?php echo $gobuniqe; ?>">
<?php for ($i=1;$i<=$slidenumber; $i++)
{
if ($i==1) 
echo('<div class="active">');
else 
echo('<div>'); 
if ($goblinks==1){?>
<a href="<?php eval ('echo ($slide'.$i.'url);'); ?>" <?php if ($gobwin==1){?> target="_blank"  <?php }; ?> >
<?php }; ?>
<img src="<?php eval ('echo ($slide'.$i.');'); ?>" <?php if ($gobtool==1){?> alt="<?php eval ('echo ($slide'.$i.'text);'); ?>"  title="<?php eval ('echo ($slide'.$i.'text);'); ?>"<?php }; ?>  />
<?php if ($goblinks==1){?>
</a>
<?php }; ?>
</div>
<?php }; ?>
<div class="joohopia"><a href="http://www.joohopia.com/joomla-modules/34-goboslide-module.html?goboslide=30" alt="free joomla slideshow module" title="freejoomla slideshow">Joomla slideshow module from joohopia</a></div>
</div>
