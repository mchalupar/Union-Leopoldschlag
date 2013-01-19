<?php
/**
 * @package     Prismatic
 * @author      Robin Jungermann
 * @link        http://www.crosstec.de
 * @license     GNU/GPL
*/
defined('_JEXEC') or die;

$templateURL = $this->baseurl."/templates/".$this->template;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>
	<jdoc:include type="head" />
	    <link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/system/css/system.css" type="text/css" />

    
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
  
  <link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/css/colors.css.php?base_color=<?php echo $this->params->get('base_color','6b7e8f');?>
&amp;accent_color=<?php echo $this->params->get('accent_color','bbff00');?>&amp;text_color=<?php echo $this->params->get('text_color','ffffff');?>&amp;menu_text_color=<?php echo $this->params->get('menu_text_color','ffffff');?>&amp;button_text_color=<?php echo $this->params->get('button_text_color','ffffff');?>&amp;bg_style=<?php echo $this->params->get('bg_style','01_alphablending'); ?>&amp;templateurl=<?php echo $templateURL; ?>" type="text/css" />
 
 <!--[if lt IE 10]>
	<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/css/ie8_fixes.css" type="text/css" />
<![endif]-->

 
 <!--[if IE 9]>
    <style type="text/css">
    
    	body, 
    	#siteWrapper,
        header,
        #main section,
         
        input[type="text"],
        input[type="password"],
        input[type="email"],
        textarea,
         
        #navigation ul,
 
        .autocompleter-choices,
        ul.autocompleter-choices li.autocompleter-selected,
        
  		.flex-direction-nav li .next,
        .flex-direction-nav li .prev,
        .flex-control-nav li a,
        .flex-control-nav li a.active,
        .flex-control-nav li a:hover,
        
        .pane-sliders div.panel,
                    
        input.button,
        button,
        
        ul.pagenav li a,
        
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

<!-- Pulled from http://code.google.com/p/html5shiv/ -->
<!--[if lt IE 9]>
	<script src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/js/html5.js"></script>
<![endif]-->

<!--[if lt IE 9]>
    <style type="text/css">

    	#siteWrapper,
        header,
        
        input[type="text"],
        input[type="password"],
        input[type="email"],
        textarea,
         
  		.flex-direction-nav li .next,
        .flex-direction-nav li .prev,
        .flex-control-nav li a,
        .flex-control-nav li a.active,
        .flex-control-nav li a:hover,
        
        .pane-sliders div.panel,
                    
        input.button,
        button,
        
        ul.pagenav li a,
        
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


<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/css/print.css" type="text/css" media="Print" />

</head>
<body class="contentpane">
	<jdoc:include type="message" />
        <div class="ct_popup_bg" style="margin: 0; padding: 0 10px;">
            <jdoc:include type="component" />
        </div>
</body>
</html>