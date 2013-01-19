<?php defined('_JEXEC') or die('Restricted access'); 

/* ERROR REPORTING */ 
error_reporting(E_ALL); 
ini_set('display_errors', 0); 

$document = JFactory::getDocument();
$app = JFactory::getApplication();

// Slide variables
$slideimage  = $this->params->get('slide_1_image');
$slidetitle  = $this->params->get('slide_1_titel');
$slidetext  = $this->params->get('slide_1_text');

$slide_2_image  = $this->params->get('slide_2_image');
$slide_2_title  = $this->params->get('slide_2_titel');
$slide_2_text  = $this->params->get('slide_2_text');

$slide_3_image  = $this->params->get('slide_3_image');
$slide_3_title  = $this->params->get('slide_3_titel');
$slide_3_text  = $this->params->get('slide_3_text');

// Column widths
$leftcolgrid  = $this->params->get('columnWidth', 4);
$rightcolgrid = $this->params->get('columnWidth', 4);

// detecting active variables
$option = JRequest::getCmd('option', '');
$view = JRequest::getCmd('view', '');
$layout = JRequest::getCmd('layout', '');
$task = JRequest::getCmd('task', '');
$itemid = JRequest::getCmd('Itemid', '');

// detecting page title
$mydoc =& JFactory::getDocument();
$page_title = $mydoc->getTitle();

if ($this->countModules('left') == 0):?>
<?php $leftcolgrid  = "0";?>
<?php endif; ?>
<?php
if ($this->countModules('right') == 0):?>
<?php $rightcolgrid  = "0";?>
<?php endif; ?>
<?php if ($this->params->get('template-width') == 1):?>
<?php $template_width = "-fluid" ;?>
<?php else :?>
<?php $template_width = "" ;?>
<?php endif; ?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <jdoc:include type="head" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Le styles -->
    <link href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/assets/css/bootstrap.css" rel="stylesheet">
    <link href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/assets/css/bootstrap-responsive.css" rel="stylesheet">
    <link href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/assets/css/template.css" rel="stylesheet">
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400italic,600italic,700italic,800italic,400,300,800,700,600' rel='stylesheet' type='text/css'>
    <?php
    $app = JFactory::getApplication();
    $menu = $app->getMenu();
    if ($menu->getActive() == $menu->getDefault()) : ?>
    <link href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/assets/css/mainStyles.css" rel="stylesheet">
  <?php endif; ?>

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <!-- Fav and touch icons -->
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="../assets/ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="../assets/ico/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="../assets/ico/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="../assets/ico/apple-touch-icon-57-precomposed.png">
    <link rel="shortcut icon" href="../assets/ico/favicon.png">
  </head>

<body class="<?php echo $option . " " . $view . " " . $layout . " " . $task . " item-" . $itemid;?> <?php if($site_home){ echo "home";}?>" data-spy="scroll" data-target=".subnav" data-offset="50" data-redering="true">



    <!-- NAVBAR
    ================================================== -->
    <div class="navbar-wrapper">
      <!-- Wrap the .navbar in .container to center it within the absolutely positioned parent. -->
      <div class="container<?php echo $template_width; ?>">
		<img class="header" src="./images/header.jpg"/>
        <div class="navbar navbar">
          <div class="navbar-inner">
            <!-- Responsive Navbar Part 1: Button for triggering responsive navbar (not covered in tutorial). Include responsive CSS to utilize. -->
            <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </a>
			<!-- Responsive Navbar Part 2: Place all navbar contents you want collapsed withing .navbar-collapse.collapse. -->
            <div class="nav-collapse collapse">
              <jdoc:include type="modules" name="top-menu" style="none" />
              <jdoc:include type="modules" name="sub-menu" style="none" />
            </div><!--/.nav-collapse -->
          </div><!-- /.navbar-inner -->
        </div><!-- /.navbar -->

      </div> <!-- /.container -->
    </div><!-- /.navbar-wrapper -->


    <?php
    $app = JFactory::getApplication();
    $menu = $app->getMenu();
	?>



<!-- Content Container
================================================== -->
<!-- Wrap the rest of the page in another container to center all the content. -->

<div class="container<?php echo $template_width; ?>">

      <!-- Content
      ================================================== -->
      <div id="content">
        <jdoc:include type="message" />
        
        <?php if($this->countModules('breadcrumbs')) : ?>
        <div id="breadcrumbs" class="row<?php echo $template_width; ?>">
          <jdoc:include type="modules" name="breadcrumbs" style="xhtml" />
        </div>
        <?php endif; ?>
        <?php if($this->countModules('top')) : ?>
        <!-- Top Module Position -->  
        <div id="top" class="row<?php echo $template_width; ?>">
          <jdoc:include type="modules" name="top" style="standard" />  
        </div>
        <hr />
        <?php endif; ?>
        <div id="main" class="row<?php echo $template_width; ?>">
          <!-- Left -->
          <?php if($this->countModules('left')) : ?>
          <div id="sidebar" class="span<?php echo $leftcolgrid;?>">
            <jdoc:include type="modules" name="left" style="standard" />
          </div>
          <?php endif; ?>
          <!-- Component -->
          <div id="content" class="span<?php echo (12-$leftcolgrid-$rightcolgrid);?>">
            <?php if($this->countModules('above-content')) : ?>
            <!-- Above Content Module Position -->  
            <div id="above-content">
              <jdoc:include type="modules" name="above-content" style="standard" />  
            </div>
            <hr />
            <?php endif; ?>
            <jdoc:include type="component" />
            <?php if($this->countModules('below-content')) : ?>
            <!-- Below Content Module Position -->  
            <hr />
            <div id="below-content">
              <jdoc:include type="modules" name="below-content" style="standard" />  
            </div>
            <?php endif; ?>
          </div>
          <!-- Right -->
          <?php if($this->countModules('right')) : ?>
          <div id="sidebar-2" class="span<?php echo $rightcolgrid;?>">
            <jdoc:include type="modules" name="right" style="standard" />
          </div>
          <?php endif; ?>
        </div>
        <?php if($this->countModules('bottom')) : ?>
        <!-- Bottom Module Position --> 
        <hr />
        <div id="bottom" class="row<?php echo $template_width; ?>">
          <jdoc:include type="modules" name="bottom" style="standard" /> 
        </div>
        <?php endif; ?>
      </div>
      <?php if($this->countModules('below')) : ?>
      <!-- Below Module Position
      ================================================== -->  
      <hr />
      <div id="below" class="row<?php echo $template_width; ?>">
        <jdoc:include type="modules" name="below" style="standard" />  
      </div>
      <?php endif; ?>
      <!-- Footer
      ================================================== -->
      <footer class="footer">
        <jdoc:include type="modules" name="footer" style="none" />  
      </footer>
      <jdoc:include type="modules" name="debug" />

</div><!-- /.container -->



    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="http://code.jquery.com/jquery.min.js"></script>
    <script src="//netdna.bootstrapcdn.com/twitter-bootstrap/2.2.1/js/bootstrap.min.js"></script>
    <?php
    $app = JFactory::getApplication();
    $menu = $app->getMenu();
    if ($menu->getActive() == $menu->getDefault()) : ?>
    <script>
      !function ($) {
        $(function(){
          // carousel demo
          $('#myCarousel').carousel()
        })
      }(window.jQuery)
    </script>
  <?php endif; ?>
  </body>
</html>
