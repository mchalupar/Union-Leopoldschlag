<?php
/**
 * @version		$Id: $
 * @author		Nguyen Dinh Luan
 * @package		Joomla!
 * $subpackage	Dropline_Menu
 * @copyright	Copyright (C) 2008 - 2011 Joomseller Solutions. All rights reserved.
 * @license		GNU/GPL http://www.gnu.org/licenses/gpl.html, see LICENSE.txt
 */

// no direct access

defined('_JEXEC') or die('Restricted access');
jimport( 'joomla.html.parameter' );
class modDropLineMenu {
	var $_itemid	= null;
	var $_open		= null;
	
	function __construct() {
		$app		= JFactory::getApplication();
		$menu		= $app->getMenu();
		// If no active menu, use default
		$this->_itemid = ($menu->getActive()) ? $menu->getActive() : $menu->getDefault();
		$this->_open		= isset($this->_itemid) ? $this->_itemid->tree : array();
	}

	function createParameterObject($param, $path='', $type='menu') {
		return new JParameter($param, $path);
	}

	// get main menu item
	function getAllMenuItems($menutype) {
	    $db	= &JFactory::getDBO();
		
		$sql = "SELECT *"
			." FROM #__menu AS m"
			." WHERE m.menutype='".$menutype."' AND m.parent_id = 1 AND m.published = 1 ". $this->getaccess('m')
			." ORDER BY m.lft";
			
		$db->setQuery($sql);
		$rows = $db->loadObjectList();
		//echo $db->getQuery();
		if ($n = count($rows)) {
			for ($i = 0; $i < $n; $i++) {
				$this->makecorrectlink($rows[$i]);
				$this->makecorrectNav($rows[$i]);
				$rows[$i]->subs = $this->getSublevel($rows[$i]->id); //Get the first sublevel of main item i
				$rows[$i]->active = in_array($rows[$i]->id, $this->_open)?1:0;
				$rows[$i]->li_id = 'ItemID_'.$rows[$i]->id;
				$rows[$i]->right_ul = ($i>($n/2))?1:0;
			}
		}

		return $rows;
	}
	
	function makecorrectlink(&$row) {
		$params = $this->createParameterObject($row->params);
		
		switch ($row->type) {
			case 'separator':
				break;
	
			case 'url' :
				if ((strpos($row->link, 'index.php?') === 0) && (strpos($row->link, 'Itemid=') === false)) {
					// If this is an internal Joomla link, ensure the Itemid is set.
					$row->link = $row->link.'&Itemid='.$row->id;
				}
				break;
	
			case 'alias':
				// If this is an alias use the item id stored in the parameters to make the link.
				$row->link = 'index.php?Itemid='.$params->get('aliasoptions');
				break;

			default:
				$router = JSite::getRouter();
				if ($router->getMode() == JROUTER_MODE_SEF) {
					$row->link = 'index.php?Itemid='.$row->id;
				} else {
					$row->link .= '&Itemid='.$row->id;
				}
				break;
		}
	}
	
	function makecorrectNav(&$row) {
		switch ($row->browserNav) {
			case 0:
				$row->browserNav = '';
				break;
			case 1:
				$row->browserNav = 'target="_blank"';
				break;
			case 2:
				$row->browserNav = 'onclick="window.open(this.href,\'targetWindow\',\'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,\');return false;"';
		}
	}
	
	function getSublevel($id) {
		$itemid	= (int)$this->_itemid->id;
		$db 	= &JFactory::getDBO();
		//Get menu level 2
		$sql = "SELECT *"
			." FROM #__menu AS m"
			." WHERE m.parent_id = ".$id." AND m.published = 1 ". $this->getaccess('m')
			." ORDER BY m.lft";
		$db->setQuery($sql);
		$rows = $db->loadObjectList();
		for ($i=0;$i<count($rows);$i++) {
			$this->makecorrectlink($rows[$i]);
			$this->makecorrectNav($rows[$i]);
			$rows[$i]->active = in_array($rows[$i]->id, $this->_open)?1:0;
		}
		return $rows;
	}
	
	function getaccess($prex) {
		$user		= &JFactory::getUser();
		$groups		= $user->getAuthorisedViewLevels();
		$groups		= implode(',', $groups);
		$text = " AND $prex.access IN ($groups)";
		return $text;
	}
}