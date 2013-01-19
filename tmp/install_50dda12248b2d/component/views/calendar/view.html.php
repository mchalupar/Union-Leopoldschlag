<?php
/**
 *	com_simplecalendar - a simple calendar component for Joomla
 *  Copyright (C) 2008-2009 Fabrizio Albonico
 *
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *	(at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');
JHTML::stylesheet('simplecal_front.css','components/com_simplecalendar/assets/css/');

class SimpleCalendarViewCalendar extends JView {

	function display($tmpl = null) {

		global $option, $mainframe;
		$document 		= & JFactory::getDocument();
		$catid 			=  '';
		$options 		=  '';
		$user 			=& JFactory::getUser();
		$categoryName 	=  '';
		$component 		=  JComponentHelper::getComponent( 'com_simplecalendar' );
		$params 		=  '';
		$config 		=  SCOutput::config();
		// ---
		$menus			=& JSite::getMenu();
		$menu  			= $menus->getActive();

		$params 		=& $mainframe->getParams();
		$isPrint		= false;
		$isPdf 			= false;

		$lists 			= array();
		$pagination		= '';

		$lists['document'] 	=& JFactory::getDocument();
		$lists['pathway'] 	=& $mainframe->getPathWay();

		$menuitemid = JRequest::getInt( 'Itemid' );

		$lists['search']	= $mainframe->getUserStateFromRequest( $option.'search', 'search', '', 'string' );
		$lists['search']	= JString::strtolower( $lists['search'] );

		$lists['mainframe'] 	= $mainframe;
		$lists['categoryName'] 	= '';
		$lists['isPrint'] 		= false;
		$lists['isPdf'] 		= false;

		// check if entries are available, else print a "no events available" line
		$lists['hasEntries'] =& $this->get('Total');

		// Check whether we're dealing with an iCal/vCal request
		$vcal = JRequest::getBool('vcal');
		if ($vcal) {
//			$document->setMetaData('robots', 'noindex, nofollow');
			$tmpl = 'vcal';
			if ($lists['hasEntries'] > 0) {
				$items =& $this->get('Data');
				$this->assignRef('items', $items);
				$this->assignRef('params', $params);
				parent::display($tmpl);
			}
		}

		$catidValue = JRequest::getString('catid');
		$catid = explode(':', $catidValue);
		if ( $catid[0] != 0 ) {
			$lists['catid'] = $catid[0];
			$lists['categoryName'] =& $this->get('CategoryName');
		} else if ( $params->get('catid') != 0 ) {
			$lists['catid'] = $params->get('catid');
			$lists['categoryName'] =& $this->get('CategoryName');
		} else {
			$lists['catid'] = 0;
		}

		$lists['calendarTitle'] = '';
		if ( is_object($menu) ) {
			$menu_params = new JParameter( $menu->params );
			if ( !$menu_params->get( 'page_title') ) {
				$params->set('page_title',	JText::_('Calendar'));
			}
		} else {
			$params->set('page_title',	JText::_('Calendar'));
		}
		if ( $params->get('page_title') != '' && $lists['categoryName'] != '' ){
			$params->set('page_title', $params->get('page_title') . ' / ' . $lists['categoryName']);
		}

		// Check whether we are dealing with PDF or print version of the calendar
		if (JRequest::getVar('format') == 'pdf') {
			$lists['isPdf'] = TRUE;
		}
		if (JRequest::getBool('print')) {
			$lists['isPrint'] = TRUE;
		}

		//TODO: add metadata from Joomla site
//		$metadata = 'Calendar, SimpleCalendar, List';
//		$lists['document']->setMetadata('keywords', $metadata );

		//http://forum.joomla.org/viewtopic.php?p=1679517
		@ini_set("pcre.backtrack_limit", -1);

		$items =& $this->get('Data');

		if ( $config->show_search_bar && !$lists['isPrint'] ) {
			$pagination =& $this->get('Pagination');
		}

		//feed management
		if ( $params->get('linkToRSS', '1') ) {
			$link    = 'index.php?view=calendar&format=feed';
			$attribs = array('type' => 'application/rss+xml', 'title' => 'RSS 2.0');
			$document->addHeadLink(JRoute::_($link.'&type=rss'), 'alternate', 'rel', $attribs);
			$attribs = array('type' => 'application/atom+xml', 'title' => 'Atom 1.0');
			$document->addHeadLink(JRoute::_($link.'&type=atom'), 'alternate', 'rel', $attribs);
		}

		$this->assignRef('items', $items);
		$this->assignRef('params', $params);
		$this->assignRef('config', $config);
		$this->assignRef('lists', $lists);
		$this->assignRef('user', $user);
		$this->assignRef('pagination', $pagination);
		$this->assignRef('document', $document);

		parent::display($tmpl);
	}
}
?>