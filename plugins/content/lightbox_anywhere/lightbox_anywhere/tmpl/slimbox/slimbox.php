<?php
/**
 * @version		$id: $
 * @author		Joomseller!
 * @package		Joomla!
 * @subpackage	Lightbox Anywhere
 * @copyright	Copyright (C) 2008 - 2011 by Joomseller Solutions. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL version 3, See LICENSE.txt
 */
 //get Lightbox_style

$document = &JFactory::getDocument();
$document->addStyleSheet( JURI::base() . 'plugins/content/lightbox_anywhere/lightbox_anywhere/tmpl/slimbox/css/slimbox.css');
$document->addScript( JURI::base() . 'plugins/content/lightbox_anywhere/lightbox_anywhere/tmpl/slimbox/js/slimbox.js');

?>
<div id="content">
	<?php
	$i = 0;
	foreach ($images as $image) :
	$i++;
	echo $image['new']."\n";
	endforeach;?>
</div>