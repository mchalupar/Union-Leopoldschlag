<?php
/**
 * @package     Prismatic
 * @author      Robin Jungermann
 * @link        http://www.crosstec.de
 * @license     GNU/GPL
*/

defined('_JEXEC') or die;
if(!defined('DS')){
    define( 'DS', DIRECTORY_SEPARATOR );
}

JHTML::_('behavior.modal');
JHTML::_('behavior.framework', true);
JHtml::_('bootstrap.framework');

$app = JFactory::getApplication();
/*

defined('_JEXEC') or die;
JHTML::_('behavior.framework', true);
$app = JFactory::getApplication();
*/

if (!isset($this->error)) {
	$this->error = JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
	$this->debug = false;
}
//get language and direction
$doc = JFactory::getDocument();
$this->language = $doc->language;
$this->direction = $doc->direction;
$this->params = JFactory::getApplication()->getTemplate(true)->params;
$templateURL = $this->baseurl."/templates/".$this->template;


?>

<!doctype html><head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="description" content="">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
<jdoc:include type="head" />
<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/system/css/system.css" type="text/css" />
<link rel="stylesheet" href="<?php echo $this->baseurl ?>/media/jui/css/bootstrap.css" />
<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/css/basics.css" type="text/css" />
<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/css/layout.css.php?max_sitewidth=<?php echo $this->params->get('max_sitewidth','960');?>
  " type="text/css" />
<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/css/menu.css" type="text/css" />
<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/css/template.css" type="text/css" />
<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/css/content_types.css" type="text/css" />
<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/css/formelements.css" type="text/css" />
<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/css/typography.css" type="text/css" />
<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/css/icons.css" type="text/css" />
<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/css/colors.css.php?base_color=<?php echo $this->params->get('base_color','000000');?>
&amp;accent_color=<?php echo $this->params->get('accent_color','f03b32');?>&amp;text_color=<?php echo $this->params->get('text_color','ffffff');?>&amp;menu_text_color=<?php echo $this->params->get('menu_text_color','ffffff');?>&amp;button_text_color=<?php echo $this->params->get('button_text_color','ffffff');?>&amp;base_transparency=<?php echo $this->params->get('base_transparency','0.7'); ?>&amp;templateurl=<?php echo $templateURL; ?>" type="text/css" />
    
     <!--[if IE 9]>
    <style type="text/css">
    
    	body, 
    	#siteWrapper,
        #errorboxoutline a {
            behavior:url(<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/js/pie/PIE.php);
        }
    </style>
<![endif]-->

<!--[if lte IE 8]>
    <style type="text/css">

    	body, 
    	#siteWrapper,
        #errorboxoutline a  {
            behavior:url(<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/js/pie/PIE.php);
        }
    </style>
<![endif]-->
    
<style>

body, html, #ct_errorWrapper  {
	height:100%;
	overflow: hidden;
}

</style>

    
</head>


<div id="siteWrapper">
  <div id="scroller">

    <header id="header">
      <div class="wrapper container">
        <div class="siteLogo">
          <?php if ($this->params->get('logo')) : ?>
          <a href="<?php echo $this->baseurl ?>" id="logo"> <img src="<?php echo $this->baseurl.'/'.$this->params->get('logo'); ?>"/> </a>
          <?php endif; ?>
        </div>
    </header>

<div id="ct_errorWrapper">


	<div class="error">
    
    <span class="errorNumber"><?php echo $this->error->getCode(); ?></span>
    <div id="errorboxheader"><?php echo $this->error->getMessage(); ?></div>
		
        <div id="outline">
		<div id="errorboxoutline">	
			<div id="errorboxbody">
			<p><strong><?php echo JText::_('JERROR_LAYOUT_NOT_ABLE_TO_VISIT'); ?></strong></p>
				<ol>
					<li><?php echo JText::_('JERROR_LAYOUT_AN_OUT_OF_DATE_BOOKMARK_FAVOURITE'); ?></li>
					<li><?php echo JText::_('JERROR_LAYOUT_SEARCH_ENGINE_OUT_OF_DATE_LISTING'); ?></li>
					<li><?php echo JText::_('JERROR_LAYOUT_MIS_TYPED_ADDRESS'); ?></li>
					<li><?php echo JText::_('JERROR_LAYOUT_YOU_HAVE_NO_ACCESS_TO_THIS_PAGE'); ?></li>
					<li><?php echo JText::_('JERROR_LAYOUT_REQUESTED_RESOURCE_WAS_NOT_FOUND'); ?></li>
					<li><?php echo JText::_('JERROR_LAYOUT_ERROR_HAS_OCCURRED_WHILE_PROCESSING_YOUR_REQUEST'); ?></li>
				</ol>
			<p><strong><?php echo JText::_('JERROR_LAYOUT_PLEASE_TRY_ONE_OF_THE_FOLLOWING_PAGES'); ?></strong></p>

				<ul>
					<li><a href="<?php echo $this->baseurl; ?>/index.php" title="<?php echo JText::_('JERROR_LAYOUT_GO_TO_THE_HOME_PAGE'); ?>"><?php echo JText::_('JERROR_LAYOUT_HOME_PAGE'); ?></a></li>
				</ul>

			<p><?php echo JText::_('JERROR_LAYOUT_PLEASE_CONTACT_THE_SYSTEM_ADMINISTRATOR'); ?>.</p>
			<div id="techinfo">
			<p><?php echo $this->error->getMessage(); ?></p>
			<p>
				<?php if ($this->debug) :
					echo $this->renderBacktrace();
				endif; ?>
			</p>
			</div>
			</div>
		</div>
		</div>
	</div>

 </div>  
</div>

<script src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/js/libs/jquery-1.8.2.min.js"></script> 
<script src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/js/vegas/jquery.vegas.js"></script> 
<script src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/js/iscroll/iscroll.js"></script> 
<script src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/js/libs/jquery.easing.1.3.js"></script> 

<script>
<?php 
	
	$bg_images =  str_replace(' ','',$this->params->get('background_images')); 
	$bg = explode(",", $bg_images);

	if(count($bg) > 1) {
		echo("
			jQuery.vegas('slideshow', {
				 delay:5000,
				 backgrounds:[
					{ src:'".$this->baseurl."/".$bg[0]."', fade:1000 },
					{ src:'".$this->baseurl."/".$bg[1]."', fade:1000 },
					{ src:'".$this->baseurl."/".$bg[2]."', fade:1000 }
				]
			})");
			

			if($this->params->get('base_transparency') != "none") {
				echo("
					('overlay', {
						src:'".$this->baseurl."/templates/".$this->template."/js/vegas/overlays/".$this->params->get('background_overlay','02').".png'
					});
			");}
			
	} else {
		echo("
			jQuery.vegas({
				src:'".$this->baseurl."/".$bg[0]."'	
			});
		");
	}

?>
</script>

</body>
</html>