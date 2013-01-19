<?php
// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

JHTML::stylesheet('simplecal.css','administrator/components/com_simplecalendar/assets/css/');

class SimpleCalendarViewSettings extends JView {

	function display($tpl = null) {
		global $mainframe, $option;
		
		JHTML::_('behavior.tooltip');
		JHTML::_('behavior.keepalive');

		$params 	=& JComponentHelper::getParams( 'com_simplecalendar' );
		$document 	= & JFactory::getDocument();

		// get the data from the model
		$items =& $this->get('Data');
		$acl		=& JFactory::getACL();
		$editor 	=& JFactory::getEditor();
		
		$timespan = SCOutput::getTimeSpanOptions();

		$access = $acl->get_group_children_tree( null, 'USERS', false );
		
		$default_ordering[] = JHTML::_('select.option', 'date', JText::_('by_date') ); 
		$default_ordering[] = JHTML::_('select.option', 'category', JText::_('by_category') );
		
		JToolBarHelper::title(JText::_('SETTINGS'), 'cpanel.png');
		JToolBarHelper::save();
		JToolBarHelper::apply();
		JToolBarHelper::cancel();
		JSubMenuHelper::addEntry( JText::_( 'HELP' ), 'index.php?option=com_simplecalendar&view=help');
		
		//make introtext data safe
		JFilterOutput::objectHTMLSafe( $items, ENT_QUOTES, 'intro_text' );
		
		$lists = array();
		$lists['use_gmap'] = JHTML::_('select.booleanlist',  'use_gmap', 'class="inputbox" onchange="enablegmap();"', $items->use_gmap);
		$lists['show_search_bar'] = JHTML::_('select.booleanlist',  'show_search_bar', 'class="inputbox"', $items->show_search_bar);
		$lists['show_category_color'] = JHTML::_('select.booleanlist',  'show_category_color', 'class="inputbox"', $items->show_category_color);
		$lists['show_status_color'] = JHTML::_('select.booleanlist',  'show_status_color', 'class="inputbox"', $items->show_status_color);
		$lists['show_donation_line'] = JHTML::_('select.booleanlist',  'show_donation_line', 'class="inputbox" onchange="checkDonationBox(this.value);"', $items->show_donation_line);
		$lists['auth_add'] = JHTML::_('select.genericlist', $access, 'frontend_add_gid', 'class="inputbox" size="6"', 'value', 'text', $items->frontend_add_gid );
		$lists['auth_edit'] = JHTML::_('select.genericlist', $access, 'frontend_edit_gid', 'class="inputbox" size="6"', 'value', 'text', $items->frontend_edit_gid );
		$lists['default_ordering'] = JHTML::_('select.genericlist', $default_ordering, 'default_ordering', 'class="inputbox"', 'value', 'text', $items->default_ordering);
		$lists['allow_owner_edit'] = JHTML::_('select.booleanlist',  'allow_owner_edit', 'class="inputbox"', $items->allow_owner_edit);
		$lists['use_jcomments'] = JHTML::_('select.booleanlist',  'use_jcomments', 'class="inputbox"', $items->use_jcomments);
		$lists['show_time'] = JHTML::_('select.booleanlist',  'show_time', 'class="inputbox"', $items->show_time);
		$lists['show_headers'] = JHTML::_('select.booleanlist',  'show_headers', 'class="inputbox"', $items->show_headers);
		$lists['frontend_auto_publish'] = JHTML::_('select.booleanlist',  'frontend_auto_publish', 'class="inputbox"', $items->frontend_auto_publish);
		$lists['map_slider_open'] = JHTML::_('select.booleanlist',  'map_slider_open', 'class="inputbox"', $items->map_slider_open);
		$lists['reverse_sort_order'] = SCOutput::checkbox('reverse_sort_order', $items->reverse_sort_order, 'onclick="switchsortorder();"');
		$lists['show_username'] = JHTML::_('select.booleanlist',  'show_username', 'class="inputbox"', $items->show_username);
//		$lists['rich_text_entryinfo'] = JHTML::_('select.booleanlist',  'rich_text_entryinfo', 'class="inputbox"', $items->rich_text_entryinfo);
		$lists['delete_on_uninstall'] = JHTML::_('select.booleanlist',  'delete_on_uninstall', 'class="inputbox"', $items->delete_on_uninstall);
		$lists['detailview_registered_only'] = JHTML::_('select.booleanlist',  'detailview_registered_only', 'class="inputbox"', $items->detailview_registered_only);
		$lists['show_category_in_detail'] = JHTML::_('select.booleanlist',  'show_category_in_detail', 'class="inputbox"', $items->show_category_in_detail);
		$lists['show_additionalinfo_label'] = JHTML::_('select.booleanlist',  'show_additionalinfo_label', 'class="inputbox"', $items->show_additionalinfo_label);
		$lists['allow_unregistered_submission'] = JHTML::_('select.booleanlist',  'allow_unregistered_submission', 'class="inputbox" onchange="enablefrontendadd();"', $items->allow_unregistered_submission);
		$lists['use_recaptcha'] = JHTML::_('select.booleanlist',  'use_recaptcha', 'class="inputbox" onchange="enableuserecaptcha();"', $items->use_recaptcha);
		
		$this->assignRef('items', $items);
		$this->assignRef('lists', $lists);
		$this->assignRef('params', $params);
		$this->assignRef('editor', $editor);
		
		parent::display($tpl);

		// Footer. Please do not remove.
		echo SCOutput::showFooter();
	}
}
?>