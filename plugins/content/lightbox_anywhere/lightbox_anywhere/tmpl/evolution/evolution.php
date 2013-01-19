<?php
/**
 * @version		$id: $
 * @author		Joomseller!
 * @package		Joomla!
 * @subpackage	Lightbox Anywhere
 * @copyright	Copyright (C) 2008 - 2011 by Joomseller Solutions. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL version 3, See LICENSE.txt
 */
$document = &JFactory::getDocument();
$document->addStyleSheet( JURI::base() . 'plugins/content/lightbox_anywhere/lightbox_anywhere/tmpl/evolution/css/jquery.lightbox.css');

$document->addScript(JURI::base() . 'plugins/content/lightbox_anywhere/lightbox_anywhere/tmpl/evolution/js/jquery.lightbox.min.js');

$js	= <<<DOCHERE
	 jQuery(document).ready(function($){
      $('.lightbox').lightbox();
    });
DOCHERE;
$document->addScriptDeclaration($js);
?>
<div id="content">
	<?php
	$i = 0;
	foreach ($images as $image) :
	$i++;
	echo $image['new']."\n";
	endforeach;?>
</div>
