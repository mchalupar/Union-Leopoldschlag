<?php
/*
 * Art Total Menu module
 *
 * @version		1.0.0
 * @author		Artetics
 * @copyright	Copyright (c) 2009 www.artetics.com. All rights reserved
 * @license		GNU/GPL (http://www.gnu.org/copyleft/gpl.html)
 */
error_reporting(E_ERROR);

defined('_JEXEC') or die('Restricted access');
require_once(JPATH_SITE . DS . 'modules' . DS . 'mod_arttotalmenu' . DS . 'mod_arttotalmenu' . DS . 'helpers' . DS . 'artmenuhelper.php');

global $Itemid;
$showHiddenMenuItems = $params->get('showHiddenMenuItems', false);
$menuType = $params->get('menuType', 'mainmenu');
$showType = $params->get('showType', 'ipod');
$backLinkText = $params->get('backLinkText', 'Back');
$loadJQuery = $params->get('loadJQuery', true);

$user =& JFactory::getUser();
if ($user) {
	$userId = $user->get('id');
}
$isUserLoggedIn = $userId > 0 ? true: false;

$menu = ArtMenuHelper::getMenu($menuType, ($isUserLoggedIn || $showHiddenMenuItems));

$selectedId = ArtMenuHelper::getParentMenuItemId($menu, $Itemid);
$selectedIndex = ArtMenuHelper::getMenuItemIndex($menu, $selectedId);
$moduleId = $module->id;

switch ($showType) {
	case 'ipod':
		$menuParams = array("width" => 180,
						   "maxHeight" => 180,
						   "showSpeed" => 200,
						   "callerOnState" => "ui-state-active",
						   "loadingState" => "ui-state-loading",
						   "linkHover" => "ui-state-hover",
						   "linkHoverSecondary" => "li-hover",
						   "crossSpeed" => 200,
						   "crumbDefaultText" => "Choose an option:",
						   "backLink" => "true",
						   "backLinkText" => "Back",
						   "flyOut" => "false",
						   "flyOutOnState" => "ui-state-default",
						   "nextMenuLink" => "ui-icon-triangle-1-e",
						   "topLinkText" => "All",
						   "nextCrumbLink" => "ui-icon-carat-1-e");

		$menuParams = ArtMenuHelper::getModuleParams($menuParams, $params);
		ArtMenuHelper::renderMenu($showType, array('menu' => $menu, 
													  'moduleParams' => $menuParams, 
													  'selectedId' => $selectedId,
													  'selectedIndex' => $selectedIndex,
													  'moduleId' => $module->id,
													  'loadJQuery' => $loadJQuery));
	break;
	
	case 'simple':
		$menuParams = array();

		$menuParams = ArtMenuHelper::getModuleParams($menuParams, $params);
		ArtMenuHelper::renderMenu($showType, array('menu' => $menu, 
													  'moduleParams' => $menuParams, 
													  'selectedId' => $selectedId,
													  'selectedIndex' => $selectedIndex,
													  'moduleId' => $module->id,
													  'loadJQuery' => $loadJQuery));
	break;
	
	case 'stylish':
		$menuParams = array();

		$menuParams = ArtMenuHelper::getModuleParams($menuParams, $params);
		ArtMenuHelper::renderMenu($showType, array('menu' => $menu, 
													  'moduleParams' => $menuParams, 
													  'selectedId' => $selectedId,
													  'selectedIndex' => $selectedIndex,
													  'moduleId' => $module->id,
													  'loadJQuery' => $loadJQuery));
	break;
	
	case 'CSS3':
		$menuParams = array();

		$menuParams = ArtMenuHelper::getModuleParams($menuParams, $params);
		ArtMenuHelper::renderMenu($showType, array('menu' => $menu, 
													  'moduleParams' => $menuParams, 
													  'selectedId' => $selectedId,
													  'selectedIndex' => $selectedIndex,
													  'moduleId' => $module->id,
													  'loadJQuery' => $loadJQuery));
	break;
	
	default:
	break;
}


?>