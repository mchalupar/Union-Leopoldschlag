<?php
/**
 * @version		$Id: $
 * @author		Nguyen Dinh Luan
 * @package		Joomla!
 * $subpackage	Dropline_Menu
 * @copyright	Copyright (C) 2008 - 2011 Joomseller Solutions. All rights reserved.
 * @license		GNU/GPL http://www.gnu.org/licenses/gpl.html, see LICENSE.txt
 */
error_reporting(1);
// no direct access
defined('_JEXEC') or die('Restricted access');

require_once (dirname(__FILE__).DS.'helper.php');

$menutype = $params->def( 'menutype', 'mainmenu' );
$subalign = $params->def( 'subalign', 1 );

$menu =& new modDropLineMenu;

if ($menutype) {
	$navs		= $menu->getAllMenuItems($menutype);
}

$document = &JFactory::getDocument();
$document->addScript('modules/mod_dropline_menu/assets/js/script.js');
$document->addStyleSheet('modules/mod_dropline_menu/assets/css/style.css');
require(JModuleHelper::getLayoutPath('mod_dropline_menu'));