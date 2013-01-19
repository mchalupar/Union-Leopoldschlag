<?php
/**
 * @version		$id: $
 * @author		Joomseller!
 * @package		Joomla!
 * @subpackage	Lightbox Anywhere
 * @copyright	Copyright (C) 2008 - 2011 by Joomseller Solutions. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL version 3, See LICENSE.txt
 */

defined('_JEXEC') or die('Restricted access');


// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die;

jimport('joomla.form.formparam');

/**
 * JElementCategory class.
 */
class JFormFieldItemK2 extends JFormFieldList
{
	/*
	 * Item name
	 *
	 * @access	protected
	 * @var		string
	 */
	public $type = 'ItemK2';
	
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
	function getInput(){
		$this->_controlName = $this->type;
		$db = &JFactory::getDBO();

		$items			= array();
		$items[0]->id	= '0';
		$items[0]->title	= JText::_('PLG_CONTENT_LIGHTBOX_ANYWHERE_SELECT_ITEM');

		//$query = 'SELECT c.* FROM #__categories AS c WHERE c.published = 1 AND c.extension = "com_content" ORDER BY c.parent_id';
		$query = 'SELECT c.* FROM #__k2_categories AS c WHERE c.published = 1 ORDER BY c.ordering';
		$db->setQuery( $query );
		$categories = $db->loadObjectList();


		// Category listings, grouped by Category
		if(count($categories)){
			foreach ($categories as $category) {
			$optgroup = JHTML::_('select.optgroup',$category->name, 'id', 'title');
			//$query = 'SELECT id,title FROM #__content WHERE state=1 AND catid = ' . $category->id . ' ORDER BY ordering';
			$query = 'SELECT id,title FROM #__k2_items WHERE published=1 AND catid = ' . $category->id . ' ORDER BY ordering';
			$db->setQuery( $query );
			$results = $db->loadObjectList();
			array_push( $items,$optgroup );
			foreach ( $results as $result ) {
				array_push( $items,$result );
			}
			$optgroup = JHTML::_('select.optgroup','', 'id', 'title');
			array_push( $items,$optgroup );
		}
		}else{
			$items[0]->id	= '';
			$items[0]->title	= JText::_('PLG_CONTENT_LIGHTBOX_ANYWHERE_K2_COMPONENT_IS_NOT_INSTALLED');
		}

		$out	= JHTML::_('select.genericlist',  $items, $this->name.'[]', 'class="inputbox group" style="width:60%;" multiple="multiple" size="10"', 'id', 'title', $this->value, $this->id );
		$out .= $this->renderJSControl();
		return $out;
	}

	/**
	 * render javasript to control enable or disable options following system.
	 *
	 * return string.
	 */
	function renderJSControl(){
		$string = '<script type="text/javascript" language="javascript">';
		$string .= '
		jQuery("document").ready(function($){

			$("div.pane-slider select[id^=\"jform_params_k2\"]").mousedown(function(){
				var performid	= new Array("jform_params_k2_item","jform_params_k2_catid");
				var componentid	= "paramssource";

				if ($(this).attr("id") == componentid) {
					return false;
				}


				// check SHOW FB COMMENT
				if( $(this).attr("multiple")==false || !$(this).attr("multiple") ){
					return false;
				}

				var objid = $(this).attr("id");

				$("div.pane-slider select[id!=objid]").each(function () {
					var ortherid = $(this).attr("id");
					if(ortherid != objid){
						if (ortherid != componentid && performid.indexOf(ortherid) >= 0) {
							$("select#"+ortherid+" option").each(function (i,val) {
								if(i==0){
									$(this).attr("selected","true");
								}else{
									$(this).attr("selected","");
								}
							});
						}
					}
				});

			});
		});';
		$string .= '</script>';
		return $string;
	}
	function checkComponent($component){
    	$db = JFactory::getDBO();
		$query =" SELECT Count(*) FROM #__components as c WHERE c.option ='$component' and c.parent=0"	;
		$db->setQuery($query);
		return $db->loadResult();
	}
}
?>
