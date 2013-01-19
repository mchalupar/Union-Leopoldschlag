<?php

/**
 * @version		$id: $
 * @author		Joomseller!
 * @package		Joomla!
 * @subpackage	Lightbox Anywhere
 * @copyright	Copyright (C) 2008 - 2011 by Joomseller Solutions. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL version 3, See LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.html.html');
jimport('joomla.form.formparam');
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');

/**
 * Form Field class for the Joomla Framework.
 *
 * @package		Joomla.Framework
 * @subpackage	Form
 * @since		1.6
 */
class JFormFieldCategory extends JFormFieldList
{
	/**
	 * The form param type.
	 *
	 * @var		string
	 * @since	1.6
	 */
	public $type = 'Category';


	/**
	 * Method to get the param input markup.
	 *
	 * @return	string	The param input markup.
	 * @since	1.6
	 */
	protected function getInput(){
		// Initialize variables.
		$attr = 'style="width:60%;"';

		// Initialize some param attributes.
		$attr .= $this->element['class'] ? ' class="'.(string) $this->element['class'].'"' : '';

		// To avoid user's confusion, readonly="true" should imply disabled="true".

		$attr .= $this->element['size'] ? ' size="'.(int) $this->element['size'].'"' : '';
		$attr .= $this->multiple ? ' multiple="multiple"' : '';

		// Initialize JavaScript param attributes.
		$attr .= $this->element['onchange'] ? ' onchange="'.(string) $this->element['onchange'].'"' : '';

		// Get the param options.
		$options = (array) $this->getOptions();

		// Create a read-only list (no name) with a hidden input to store the value.
		$html = JHtml::_('select.genericlist', $options, $this->name, trim($attr), 'value', 'text', $this->value, $this->id);

		return $html;
	}

	/**
	 * Method to get the param options.
	 *
	 * @return	array	The param option objects.
	 * @since	1.6
	 */
	protected function getOptions()
	{
		// Initialise variables.
		$options	= array();

		$extension	= $this->element['extension'] ? (string) $this->element['extension'] : (string) $this->element['scope'];
		$published	= (string) $this->element['published'];

		// Load the category options for a given extension.
		if (!empty($extension)) {

			// Filter over published state or not depending upon if it is present.
			if ($published) {
				$options = JHtml::_('category.options', $extension, array('filter.published' => explode(',', $published)));
			}
			else {
				$options = JHtml::_('category.options', $extension);
			}

			// Verify permissions.  If the action attribute is set, then we scan the options.
			if ($action	= (string) $this->element['action']) {

				// Get the current user object.
				$user = JFactory::getUser();

				foreach($options as $i => $option)
				{
					// To take save or create in a category you need to have create rights for that category
					// unless the item is already in that category.
					// Unset the option if the user isn't authorised for it. In this param assets are always categories.
					if ($user->authorise('core.create', $extension.'.category.'.$option->value) != true ) {
						unset($options[$i]);
					}
				}

			}

			if (isset($this->element['show_root'])) {
				array_unshift($options, JHtml::_('select.option', '0', JText::_('JGLOBAL_ROOT')));
			}
		}
		else {
			JError::raiseWarning(500, JText::_('JLIB_FORM_ERROR_FIELDS_CATEGORY_ERROR_EXTENSION_EMPTY'));
		}

		// Merge any additional options in the XML definition.
		$options = array_merge(parent::getOptions(), $options);

		return $options;
	}
}
?>