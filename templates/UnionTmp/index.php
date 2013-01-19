<?php
// No direct access.
defined('_JEXEC') or die;

JHTML::_('behavior.framework', true);
$app = JFactory::getApplication();
?>

<?php echo '<!DOCTYPE html>'; ?>
<html xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>" >
<head>
		<jdoc:include type="head" />
		<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/system/css/system.css" type="text/css" />
		<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/css/template.css" type="text/css" />
		
</head>
<body>

<div id="wrapper">

    <div id="header">
        <div id="head"></div>
        <div id="mainnav"><jdoc:include type="modules" name="top-menu" style="xhtml" /> </div>
        <div id="subnav"><jdoc:include type="modules" name="sub-menu" style="xhtml" /> </div>
    </div><!--#header-->

    <div id="content">
        <?php //<div id="news"><jdoc:include type="modules" name="left" style="xhtml" /></div><!--#news--> ?>
        <div id="component"><jdoc:include type="component" style="xhtml"/></div><!--#component-->
        <div id="sidebar"><jdoc:include type="modules" name="right" style="xhtml" /></div><!--#menu-->
    </div><!--#content-->
    
    <div id="footer"><jdoc:include type="modules" name="footer" style="xhtml" /></div><!--#footer-->
    
</div><!--#wrapper-->

</body>
</html>


