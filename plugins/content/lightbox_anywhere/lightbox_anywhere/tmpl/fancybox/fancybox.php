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
$document->addStyleSheet( JURI::base() . 'plugins/content/lightbox_anywhere/lightbox_anywhere/tmpl/fancybox/css/jquery.fancybox.css');
$document->addStyleSheet( JURI::base() . 'plugins/content/lightbox_anywhere/lightbox_anywhere/tmpl/fancybox/css/jquery.fancybox-buttons.css');

$document->addScript(JURI::base() . 'plugins/content/lightbox_anywhere/lightbox_anywhere/tmpl/fancybox/js/jquery.fancybox.pack.js');
$document->addScript(JURI::base() . 'plugins/content/lightbox_anywhere/lightbox_anywhere/tmpl/fancybox/js/jquery.fancybox-buttons.js');
$document->addScript(JURI::base() . 'plugins/content/lightbox_anywhere/lightbox_anywhere/tmpl/fancybox/js/jquery.mousewheel-3.0.6.pack.js');

$js	= <<<DOCHERE
jQuery(document).ready(function() {
	/*
	*   Examples - images
	*/
	
	jQuery('.fancybox-buttons').fancybox({
		'openEffect'  : 'none',
		'closeEffect' : 'none',

		'prevEffect' : 'none',
		'nextEffect' : 'none',

		'closeBtn'  : false,

		'helpers' : {
			'title' : {
				'type' : 'inside'
			},
			buttons	: {}
		},

		'afterLoad' : function() {
			this.title = 'Image ' + (this.index + 1) + ' of ' + this.group.length + (this.title ? ' - ' + this.title : '');
		}
	});
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
