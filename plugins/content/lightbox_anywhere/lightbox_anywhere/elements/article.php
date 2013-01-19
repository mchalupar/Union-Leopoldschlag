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
class JFormFieldArticle extends JFormFieldList
{
	/**
	 * The form param type.
	 *
	 * @var		string
	 * @since	1.6
	 */
	public $type = 'article';

	/**
	 * fetch Element
	 */
	function getInput(){
		$db = &JFactory::getDBO();

		$articles			= array();
		$articles[0]->id	= '0';
		$articles[0]->title	= JText::_('PLG_CONTENT_LIGHTBOX_ANYWHERE_SELECT_ARTICLES');

		$query = 'SELECT c.* FROM #__categories AS c WHERE c.published = 1 AND c.extension = "com_content" ORDER BY c.parent_id';
		$db->setQuery( $query );
		$categories = $db->loadObjectList();


		// Category listings, grouped by Category
		foreach ($categories as $category) {
			$optgroup = JHTML::_('select.optgroup',$category->title, 'id', 'title');
			$query = 'SELECT id,title FROM #__content WHERE state=1 AND catid = ' . $category->id . ' ORDER BY ordering';
			$db->setQuery( $query );
			$results = $db->loadObjectList();
			array_push( $articles,$optgroup );
			foreach ( $results as $result ) {
				array_push( $articles,$result );
			}
			$optgroup = JHTML::_('select.optgroup','', 'id', 'title');
			array_push( $articles,$optgroup );
		}


		$out	= JHTML::_('select.genericlist',  $articles, $this->name.'[]', 'class="inputbox group" style="width:60%;" multiple="multiple" size="10"', 'id', 'title', $this->value, $this->id );
		$out .= $this->renderJSControl();
		return $out;
	}

	/**
	 * render javasript to control enable or disable options following system.
	 *
	 * return string.
	 */
	function renderJSControl(){
		$string = '<script type="text/javascript" language="javascript" src="http://www.google.com/jsapi">';
		$string .= '</script>';

		$string .= '<script type="text/javascript" language="javascript">';
		$string .= 'google.load("jquery", "1.2.6")';
		$string .= '</script>';
		$string .= '<script type="text/javascript" language="javascript">';
		$string .= '
		$.noConflict();
		jQuery("document").ready(function($){
			$("div.pane-slider select[id^=\"jform_params_content\"]").mousedown(function(){
				var performid	= new Array("jform_params_content_catid","jform_params_content_articleid");
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
}