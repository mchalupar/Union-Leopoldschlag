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


$highlights1ModuleCount = $this->countModules('highlights_1_1 + highlights_1_2 + highlights_1_3 + highlights_1_4 + highlights_1_5 + highlights_1_6');
if($highlights1ModuleCount > 0) {$highlights1ModuleWidth = $highlights1ModuleCount;}

$maincontent1ModuleCount = $this->countModules('maincontent_1_1 + maincontent_1_2 + maincontent_1_3 + maincontent_1_4 + maincontent_1_5 + maincontent_1_6');
if($maincontent1ModuleCount > 0) {$maincontent1ModuleWidth = $maincontent1ModuleCount;}

$maincontent2ModuleCount = $this->countModules('maincontent_2_1 + maincontent_2_2 + maincontent_2_3 + maincontent_2_4 + maincontent_2_5 + maincontent_2_6');
if($maincontent2ModuleCount > 0) {$maincontent2ModuleWidth = $maincontent2ModuleCount;}

$footerModuleCount = $this->countModules('footer_1_1 + footer_1_2 + footer_1_3 + footer_1_4 + footer_1_5 + footer_1_6');
if($footerModuleCount > 0) {$footerModuleWidth = $footerModuleCount;}

$contentLeft = 0;
$contentRight = 0;

if($this->countModules('left') > 0) {
	$contentLeft =	1;
}

if($this->countModules('right') > 0) {
	$contentRight =	1;
}
 
$moduleWidthcomponentContent = "ct_componentWidth_".(4 - ($contentLeft + $contentRight));

$templateURL = str_replace('/','%',$this->baseurl."/templates/".$this->template);

?>

<!doctype html>

<head>
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

<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/js/vegas/jquery.vegas.css" type="text/css" />

<!-- Pulled from http://code.google.com/p/html5shiv/ -->
<!--[if lt IE 9]>
	<script src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/js/html5.js"></script>
<![endif]-->

<?php
if(strstr($_SERVER['HTTP_USER_AGENT'],'iPad')){
	echo('
	<style>
	
		ul.menu ul {	
			display: none;

			padding: 0;
			width: auto;
			white-space: nowrap;
			position: absolute;
		
			-webkit-border-radius: 5px;
			-moz-border-radius: 5px;
			border-radius: 5px;
		
			-webkit-box-shadow: 0 1px 3px rgba(0, 0, 0, .3);
			-moz-box-shadow: 0 1px 3px rgba(0, 0, 0, .3);
			box-shadow: 0 1px 3px rgba(0, 0, 0, .3);
			
			-pie-box-shadow: 0 2px 0px rgba(0, 0, 0, 0.15);
		}
		
		/* dropdown */
		.ct_menu_horizontal ul.menu li:hover > ul {
			display: block;			
		}
		
		.ct_menu_vertical ul.menu li:hover > ul {
			display: inline-block;
		}

	</style>
	');
    }?>

<!--[if IE 9]>
    <style>
    
    	body, 
    	#siteWrapper,
        header,
        #main section,
        
        .moduletable_ct_lightBox,
        .moduletable_ct_darkBox,
         
        input[type="text"],
        input[type="password"],
        input[type="email"],
        textarea,
        
        #main img,
           
        ul.menu,
        ul.menu ul,
        ul.menu ul ul,
        ul.menu li > a,
        ul.menu li > span,
        ul.menu li ul li > a,
        ul.menu li ul li > span,
        ul.menu li ul li ul li > a,
        ul.menu li ul li ul li > span,
        
        .nav-tabs > li > a,
        .nav-tabs > li:active > a,
        .nav-tabs > li:hover > a,
        
        .ct_pagination div,

        .autocompleter-choices,
        ul.autocompleter-choices li.autocompleter-selected,
        
        .flex-direction-nav li .next,
        .flex-direction-nav li .prev,
        .flex-control-nav li a,
        .flex-control-nav li a.active,
        .flex-control-nav li a:hover,
        
        ul.pagenav li a,
        
        .pane-sliders div.panel,
                    
        .button, 
        button,
        .btn,
        #errorboxoutline a
        
        ul.pagenav li a,
        
        .input-append .add-on,
        .input-prepend .add-on,
        
        .ct_buttonAccent, 
        .ct_buttonYellow, 
        .ct_buttonRed, 
        .ct_buttonBlue,
        .ct_buttonGreen,
        .ct_buttonPink,
        .ct_buttonBlack,
        .ct_buttonWhite,
        
        #login-form.compact .button,
        #ct_headerLogin input.button,
        .tip  {
            behavior:url(<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/js/pie/PIE.php);
        }
    
    </style>
<![endif]-->

<!--[if lt IE 9]>
	<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/css/ie_fixes.css.php" type="text/css" />
<![endif]-->

<!--[if lt IE 9]>
    <style>
    
    	body, 
    	#siteWrapper,
        header,
        #main section,
        
        .moduletable_ct_lightBox,
        .moduletable_ct_darkBox,
         
        input, 
        input[type="text"],
        input[type="password"],
        input[type="email"],
        textarea,

        ul.menu,

        .ct_pagination div,

        .autocompleter-choices,
        ul.autocompleter-choices li.autocompleter-selected,

  		.flex-direction-nav li .next,
        .flex-direction-nav li .prev,
        .flex-control-nav li a,
        .flex-control-nav li a.active,
        .flex-control-nav li a:hover,
        
        ul.pagenav li a,
        
        .pane-sliders div.panel,
                  
        .button, 
        button,
        .btn,
        #errorboxoutline a,
        
        ul.pagenav li a,
        
        .input-append .add-on,
        .input-prepend .add-on,
        
        .ct_buttonAccent, 
        .ct_buttonYellow, 
        .ct_buttonRed, 
        .ct_buttonBlue,
        .ct_buttonGreen,
        .ct_buttonPink,
        .ct_buttonBlack,
        .ct_buttonWhite,
        
        #login-form.compact .button,
        #ct_headerLogin input.button,
        .tip  {
            behavior:url(<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/js/pie/PIE.php);
        }
        
        ul.menu {
            -webkit-border-radius: 0px;
        	-moz-border-radius: 0px;
        	border-radius: 0px; 
       	}
    
    </style>
<![endif]-->

</head>

<body id="body">
<div id="siteWrapper">
  <div id="scroller">
    <header id="header">
      <div class="wrapper container">
        <div class="siteLogo">
          <?php if ($this->params->get('logo')) : ?>
          <a href="<?php echo $this->baseurl ?>" id="logo"> <img src="<?php echo $this->baseurl.'/'.$this->params->get('logo'); ?>"/> </a>
          <?php endif; ?>
        </div>
        
        <?php if ($this->countModules( 'searchHeader or loginHeader' )) : ?>
        <div id="ct_headerTools">
          <div id="ct_headerSearch">
            <jdoc:include type="modules" name="searchHeader" style="xhtml" />
          </div>
          <div id="ct_headerLogin">
            <jdoc:include type="modules" name="loginHeader" style="xhtml" />
          </div>
        </div>
        <?php endif; ?>
        
        <div class="ct_clearFloatRight"></div>
        <div id="mainMenu">
          <jdoc:include type="modules" name="mainNav" style="xhtml" />
        </div>
        <div class="ct_clearFloatBoth"></div>
      </div>
    </header>
    
    <jdoc:include type="message" />
    
    <?php if ($this->countModules( 'slider' )) : ?>
    <div id="ct_sliderWrapper">
      <div id="ct_sliderContent">
        <jdoc:include type="modules" name="slider" style="xhtml" />
      </div>
    </div>
    <?php endif; ?>
   
    <div id="main">
      <div class="wrapper container">
        <?php if ($this->countModules( 'highlights_1_1 or highlights_1_2 or highlights_1_3 or highlights_1_4 or highlights_1_5 or highlights_1_6' )) : ?>
        <section>
          <div class="row columnWidth_<?php echo $highlights1ModuleWidth ?>">
            <?php if ($this->countModules( 'highlights_1_1' )) : ?>
            <jdoc:include type="modules" name="highlights_1_1" style="xhtml" />
            <?php endif; ?>
            <?php if ($this->countModules( 'highlights_1_2' )) : ?>
            <jdoc:include type="modules" name="highlights_1_2" style="xhtml" />
            <?php endif; ?>
            <?php if ($this->countModules( 'highlights_1_3' )) : ?>
            <jdoc:include type="modules" name="highlights_1_3" style="xhtml" />
            <?php endif; ?>
            <?php if ($this->countModules( 'highlights_1_4' )) : ?>
            <jdoc:include type="modules" name="highlights_1_4" style="xhtml" />
            <?php endif; ?>
            <?php if ($this->countModules( 'highlights_1_5' )) : ?>
            <jdoc:include type="modules" name="highlights_1_5" style="xhtml" />
            <?php endif; ?>
            <?php if ($this->countModules( 'highlights_1_6' )) : ?>
            <jdoc:include type="modules" name="highlights_1_6" style="xhtml" />
            <?php endif; ?>
          </div>
        </section>
        <div class="ct_clearFloatLeft"></div>
        <?php endif; ?>
        <?php if ($this->countModules( 'maincontent_1_1 or maincontent_1_2 or maincontent_1_3 or maincontent_1_4 or maincontent_1_5 or maincontent_1_6' )) : ?>
        <section>
          <div class="row columnWidth_<?php echo $maincontent1ModuleWidth ?>">
            <?php if ($this->countModules( 'maincontent_1_1' )) : ?>
            <jdoc:include type="modules" name="maincontent_1_1" style="xhtml" />
            <?php endif; ?>
            <?php if ($this->countModules( 'maincontent_1_2' )) : ?>
            <jdoc:include type="modules" name="maincontent_1_2" style="xhtml" />
            <?php endif; ?>
            <?php if ($this->countModules( 'maincontent_1_3' )) : ?>
            <jdoc:include type="modules" name="maincontent_1_3" style="xhtml" />
            <?php endif; ?>
            <?php if ($this->countModules( 'maincontent_1_4' )) : ?>
            <jdoc:include type="modules" name="maincontent_1_4" style="xhtml" />
            <?php endif; ?>
            <?php if ($this->countModules( 'maincontent_1_5' )) : ?>
            <jdoc:include type="modules" name="maincontent_1_5" style="xhtml" />
            <?php endif; ?>
            <?php if ($this->countModules( 'maincontent_1_6' )) : ?>
            <jdoc:include type="modules" name="maincontent_1_6" style="xhtml" />
            <?php endif; ?>
          </div>
        </section>
        <?php endif; ?>
        <section>
          <div class="row_main">
            <?php if ($this->countModules( 'left' )) : ?>
            <div class="ct_left">
              <jdoc:include type="modules" name="left" style="xhtml" />
            </div>
            <?php endif; ?>
            <div class="ct_componentContent <?php echo $moduleWidthcomponentContent?>">
              <?php if ($this->countModules( 'breadcrumbs' )) : ?>
              <div class="ct_breadcrumbs">
                <jdoc:include type="modules" name="breadcrumbs" style="xhtml" />
              </div>
              <?php endif; ?>
              <div class="inner">
                <jdoc:include type="component" />
              </div>
            </div>
            <?php if ($this->countModules( 'right' )) : ?>
            <div class="ct_right">
              <jdoc:include type="modules" name="right" style="xhtml" />
            </div>
            <?php endif; ?>
            <div class="ct_clearFloatBoth"></div>
          </div>
        </section>
        <?php if ($this->countModules( 'maincontent_2_1 or maincontent_2_2 or maincontent_2_3 or maincontent_2_4 or maincontent_2_5 or maincontent_2_6' )) : ?>
        <section>
          <div class="row columnWidth_<?php echo $maincontent2ModuleWidth ?>">
            <?php if ($this->countModules( 'maincontent_2_1' )) : ?>
            <jdoc:include type="modules" name="maincontent_2_1" style="xhtml" />
            <?php endif; ?>
            <?php if ($this->countModules( 'maincontent_2_2' )) : ?>
            <jdoc:include type="modules" name="maincontent_2_2" style="xhtml" />
            <?php endif; ?>
            <?php if ($this->countModules( 'maincontent_2_3' )) : ?>
            <jdoc:include type="modules" name="maincontent_2_3" style="xhtml" />
            <?php endif; ?>
            <?php if ($this->countModules( 'maincontent_2_4' )) : ?>
            <jdoc:include type="modules" name="maincontent_2_4" style="xhtml" />
            <?php endif; ?>
            <?php if ($this->countModules( 'maincontent_2_5' )) : ?>
            <jdoc:include type="modules" name="maincontent_2_5" style="xhtml" />
            <?php endif; ?>
            <?php if ($this->countModules( 'maincontent_2_6' )) : ?>
            <jdoc:include type="modules" name="maincontent_2_6" style="xhtml" />
            <?php endif; ?>
          </div>
        </section>
        <?php endif; ?>
        <div class="ct_clearFloatLeft"></div>
      </div>
    </div>
    <?php if ($this->countModules( 'footer_1_1 or footer_1_2 or footer_1_3 or footer_1_4 or footer_1_5 or footer_1_6' )) : ?>
    <footer>
      <section>
        <div class="row columnWidth_<?php echo $footerModuleWidth ?>">
          <?php if ($this->countModules( 'footer_1_1' )) : ?>
          <jdoc:include type="modules" name="footer_1_1" style="xhtml" />
          <?php endif; ?>
          <?php if ($this->countModules( 'footer_1_2' )) : ?>
          <jdoc:include type="modules" name="footer_1_2" style="xhtml" />
          <?php endif; ?>
          <?php if ($this->countModules( 'footer_1_3' )) : ?>
          <jdoc:include type="modules" name="footer_1_3" style="xhtml" />
          <?php endif; ?>
          <?php if ($this->countModules( 'footer_1_4' )) : ?>
          <jdoc:include type="modules" name="footer_1_4" style="xhtml" />
          <?php endif; ?>
          <?php if ($this->countModules( 'footer_1_5' )) : ?>
          <jdoc:include type="modules" name="footer_1_5" style="xhtml" />
          <?php endif; ?>
          <?php if ($this->countModules( 'footer_1_6' )) : ?>
          <jdoc:include type="modules" name="footer_1_6" style="xhtml" />
          <?php endif; ?>
        </div>
      </section>
    </footer>
    <?php endif; ?>
    
    <div class="ct_spacerBottom"></div>
    
    <div style="display: block; text-align: center;">Get more <a href="http://crosstec.de/en/joomla-templates.html"><br>
Joomla!&reg; Templates</a> and <a href="http://crosstec.de/en/extensions/joomla-forms-download.html">Joomla!&reg; Forms</a> From <a href="http://crosstec.de/">Crosstec</a>
<p><br></p>
</div>   
    
  </div>
</div>
<script src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/js/libs/jquery-1.8.2.min.js"></script> 
<script src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/js/jquery.mobilemenu.js"></script> 
<script src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/js/jquery.ba-resize.min.js"></script> 
<script src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/js/touchmenu.js"></script> 
<script src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/js/vegas/jquery.vegas.js"></script> 
<script src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/js/iscroll/iscroll.js"></script> 
<script src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/js/libs/jquery.easing.1.3.js"></script>
<script>
	
	jQuery(document).ready(function() {
		jQuery(".btn:has(i)").addClass('btnPlain');
		jQuery(".input-append:has(button > i) > input").addClass('inputCalendar');	
	});

	// Convert menu to select-list for small displays
	jQuery(document).ready(function() {
		jQuery('ul.menu').mobileMenu({switchWidth:787, prependTo: '#mainMenu', topOptionText: '<?php echo $this->params->get('show_menu_text', 'Select a Page');?>'});
	});
	
	
	
	<?php 
	
	$bg_images =  str_replace(' ','',$this->params->get('background_images')); 
	$bg = explode(",", $bg_images);

	if(count($bg) > 1) {
		echo("
			jQuery.vegas('slideshow', {
				 delay:".$this->params->get('slideshow_speed','5000').",
				 backgrounds:[");
				 
				 foreach($bg as $bg_img) {
					echo ("{ src:'".$this->baseurl."/".$bg_img."', fade:".$this->params->get('slideshow_transition','1000')." },");
				 }
				 
			echo("
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
	
	
	function equalHeight(){
		
		var currentTallest = 0,
			currentRowStart = 0,
			rowDivs = new Array(),
			$el,
			$elementHeight,
			$totalHeight = 0,
			tallestContent = 0,
			topPosition = 0,
			lineNumber = 1;
						
		jQuery('.row > div').each( function() {
	
			$el = jQuery(this);
	
			topPostion = $el.position().top;
			
			jQuery(this).each(function(){
				jQuery(this).removeAttr("style")
			})
	
			// Get the total height of the module's content
			jQuery(this).children().each(function(){
				$totalHeight = $totalHeight + jQuery(this).outerHeight(true);
			})
			
			tallestContent =  (tallestContent < $totalHeight) ? ($totalHeight) : (tallestContent); 
			
			//console.log($totalHeight);
			//console.log("tallestContent: "+tallestContent);
			
			$totalHeight = 0;
			

			if (currentRowStart != topPostion) {
				
				lineNumber = lineNumber++;
				
				// we just came to a new row.  Set all the heights on the completed row
				for (currentDiv = 0 ; currentDiv < rowDivs.length ; currentDiv++) {
					if(currentDiv == 0) {rowDivs[currentDiv].css('margin-left', 0);};
					rowDivs[currentDiv].css('min-height', currentTallest+12);
				}
	
				// set the variables for the new row
				rowDivs.length = 0; // empty the array
				currentRowStart = topPostion;
				currentTallest = $el.height();
				rowDivs.push($el);

			} else {
	
				 // another div on the current row.  Add it to the list and check if it's taller
				 rowDivs.push($el);
				 
				 //currentTallest = (currentTallest < $el.height()) ? ($el.height()) : (currentTallest);
				 currentTallest = tallestContent;
			}
	   
		   // do the last row
		   for (currentDiv = 0 ; currentDiv < rowDivs.length ; currentDiv++) {
				if(currentDiv == 0) {rowDivs[currentDiv].css('margin-left', 0);};
				rowDivs[currentDiv].css('min-height', currentTallest+12);
			}
		});
	}
	
	function makeFullWidth(){
		jQuery('.fullWidth').each(function(){
			var imgHeight = jQuery(this).height();
			jQuery(this).closest('p').css('margin', '0');
			jQuery(this).closest('p').css('height', imgHeight);
		});
	};

	jQuery(window).resize(function() {
		makeFullWidth();
	 	setTimeout(equalHeight, 100);
		
	});
	
	jQuery(document).ready(function() {
		makeFullWidth();
		setTimeout(equalHeight, 100);
	});
	
</script>
<?php
if(strstr($_SERVER['HTTP_USER_AGENT'],'iPhone') || strstr($_SERVER['HTTP_USER_AGENT'],'iPad') || strstr($_SERVER['HTTP_USER_AGENT'],'iPod')){
	echo("
		<script>
		
			function getOsVersion() {
				var agent = window.navigator.userAgent,
					start = agent.indexOf( 'OS ' );
				
				return window.Number( agent.substr( start + 3, 3 ).replace( '_', '.' ) );
			};
		
			
			if( getOsVersion() < 5 || getOsVersion() == 'other') {
					
				var scrollContent;
				
				function loaded() { scrollContent = new iScroll('siteWrapper'); };
				
				document.addEventListener('touchmove', function (e) { e.preventDefault(); }, false);
				document.addEventListener('DOMContentLoaded', function () { setTimeout(loaded, 200); }, false);	
			};
		
		</script>
	");
};

?>
</body>
</html>