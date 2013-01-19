<?php 
/**
 * @version		$id: $
 * @author		Joomseller!
 * @package		Joomla!
 * @subpackage	Lightbox Anywhere
 * @copyright	Copyright (C) 2008 - 2011 by Joomseller Solutions. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL version 3, See LICENSE.txt
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );
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
class JFormFieldCategoryK2 extends JFormFieldList
{
	/**
	 * The form param type.
	 *
	 * @var		string
	 * @since	1.6
	 */
	public $type = 'CategoryK2';


	/**
	 * Method to get the param input markup.
	 *
	 * @return	string	The param input markup.
	 * @since	1.6
	 */
	function getInput(){

		$db = &JFactory::getDBO();

		$category_k2			= array();
		$category_k2[0]->id	= '0';
		$category_k2[0]->name	= JText::_('PLG_CONTENT_LIGHTBOX_ANYWHERE_CATEGORIES_K2');

		//$query = 'SELECT c.* FROM #__categories AS c WHERE c.published = 1 AND c.extension = "com_content" ORDER BY c.parent_id';
		$query = 'SELECT c.* FROM #__k2_categories AS c WHERE c.published = 1 ORDER BY c.ordering';
		$db->setQuery( $query );
		$categories = $db->loadObjectList();
		if(count($categories)){
			foreach ( $categories as $category ) {
				array_push( $category_k2,$category );
		}
		}else{
			$category_k2[0]->id		= '0';
			$category_k2[0]->name	= JText::_('PLG_CONTENT_LIGHTBOX_ANYWHERE_K2_COMPONENT_IS_NOT_INSTALLED');
		}

		$optgroup = JHTML::_('select.optgroup','', 'id', 'name');
		array_push( $category_k2,$optgroup );

		$out	= JHTML::_('select.genericlist',  $category_k2, $this->name.'[]', 'class="inputbox group" style="width:60%;" multiple="multiple" size="10"', 'id', 'name', $this->value, $this->id );
		return $out;
	}
	function checkComponent($component){
			$db = JFactory::getDBO();
			$query =" SELECT Count(*) FROM #__components as c WHERE c.option ='$component' and c.parent=0"	;
			$db->setQuery($query);
			return $db->loadResult();
		}

}

?>