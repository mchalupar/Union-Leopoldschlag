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
class JFormFieldVersion extends JFormField {
	/**
	 * Element name
	 *
	 * @access	protected
	 * @var		string
	 */
	var	$_name = 'Version';

	function getInput(){

		// Initialize variables.
		$attr = '';

		// Initialize some field attributes.
		$attr .= $this->element['class'] ? ' class="'.(string) $this->element['class'].'"' : '';

		// To avoid user's confusion, readonly="true" should imply disabled="true".

		$attr .= $this->element['size'] ? ' size="'.(int) $this->element['size'].'"' : '';
		$attr .= $this->multiple ? ' multiple="multiple"' : '';

		// Initialize JavaScript field attributes.
		$attr .= $this->element['onchange'] ? ' onchange="'.(string) $this->element['onchange'].'"' : '';

		// declare the folder
		$ourDir = JPATH_PLUGINS.DS.'system'.DS.'js_loadjquery'.DS.'libraries'.DS.'jquery';

		// prepare to read directory contents
		$ourDirList	= @opendir($ourDir);
		$i	= 1;
		// loop through the items
		$options	= array();
		$i		= 0;
		while ($ourItem = readdir($ourDirList)) {
			// check if it is a custom component directory
			if (strpos($ourItem, '.js')) {
				$options[$i]->value = $ourItem;
				$options[$i]->text = JText::_($ourItem);
				$i++;
			}
			
		}
		closedir($ourDirList);

		return $html = JHtml::_('select.genericlist', $options, $this->name, trim($attr), 'value', 'text', $this->value, $this->id);
	}
}
?>