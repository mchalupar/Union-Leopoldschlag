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
 * JElementComponents class.
 */
class JElementComponents extends JElement {
	/*
	 * Components name
	 *
	 * @access	protected
	 * @var		string
	 */
	var	$_name = 'Components';

	/*
	 * Control name.
	 *
	 * @access	protected
	 * @var		string
	 */
	var $_controlName = '';

	/**
	 * fetch Element
	 */
	function fetchElement($name, $value, &$node, $control_name){

		$this->_controlName = $name;
		$db = &JFactory::getDBO();

		$components	= array();
		$components[0]->name = '';
		$components[0]->title = JText::_("-- Select Components --");

		// declare the folder
		$ourDir = JPATH_ROOT. DS. 'components';

		// prepare to read directory contents
		$ourDirList	= @opendir($ourDir);
		$i	= 1;
		// loop through the items
		while ($ourItem = readdir($ourDirList)) {
			// check if it is a custom component directory
			if (strpos($ourItem, 'om_')) {
				//echo "directory: $ourItem <br />";
				$sql	= "SELECT `name` FROM #__components WHERE `option` = '$ourItem' AND `parent` = '0' LIMIT 1";
				$db->setQuery($sql);
				$comname	= $db->loadResult();
				if ($comname != '') {
					$components[$i]->name = $ourItem;
					$components[$i]->title = JText::_($comname);
					$i++;
				}
			}
			
		}
		closedir($ourDirList);

		$out	= JHTML::_('select.genericlist',  $components, $control_name.'['.$name.'][]', 'class="inputbox" style="width:98%;" multiple="multiple" size="10"', 'name', 'title', $value );
		return $out;
	}
}

?>
