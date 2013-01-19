<?php
/**
* @version		$Id$
* @athour		Nguyen Dinh Luan
* @package		Joomla
* @subpackage	Plugin
* @copyright	Copyright (C) 2008 - 2010 Joomseller Solutions. All rights reserved.
* @license		http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL, see LICENSE.php
*/

// no direct access
defined('_JEXEC') or die('Restricted access');
 
/**
 * Renders a menus multiple item select element
 *
 */
class JElementMenus extends JElement {
  var   $_name = 'menus';

	function fetchElement($name, $value, &$node, $control_name){
		$document =& JFactory::getDocument();
		$menus = array();
		
//		$temp->value = '';
//		$temp->text = JText::_("Select all menus");
		$menus = JHTML::_('menu.linkoptions');
//		array_unshift($menus, $temp);

		return JHTML::_('select.genericlist',  $menus, ''.$control_name.'['.$name.'][]', 'class="inputbox" style="width:98%;" multiple="multiple" size="10"', 'value', 'text', $value );		
	}
}