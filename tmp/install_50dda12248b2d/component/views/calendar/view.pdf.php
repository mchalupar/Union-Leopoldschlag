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


class SimpleCalendarViewCalendar extends JView {

	function display($tmpl = null) {
		global $option, $mainframe;

		$component		= JComponentHelper::getComponent( 'com_simplecalendar' );
		$params 		= new JParameter( $component->params );
		$config 		= SCOutput::config();
	
		$dispatcher	=& JDispatcher::getInstance();

		$dispatcher->trigger('onPrepareContent', array (& $event, & $params, 0));

		$document = &JFactory::getDocument();
		$document->setMimeEncoding('application/pdf');
		$document->setMetaData('robots', 'noindex, nofollow');

		$document->setTitle(JText::_('CALENDAR'));
		$document->setName(JText::_('CALENDARTEXT'));
		$document->setDescription($mainframe->getCfg( 'sitename' ).' - '.JText::_('CALENDARTEXT'));

		$date =& JFactory::getDate();
		$document->setHeader($mainframe->getCfg( 'sitename' ));

		if (JRequest::getVar('order') != '' || JRequest::getVar('order') != Null ) {
			$order = JRequest::getVar('order');
		} else {
			$order = $config->default_ordering;
		}
		if ($order == '') {
			$order = $config->default_ordering;
		}
		
		$hasEntries =& $this->get('Total');
		$lists = array();
		
		$lists['isPrint'] = false;
		
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
				
		if ($hasEntries > 0) {
			$items =& $this->get('DataForPDF');
			$this->assignRef('items', $items);
			$this->assignRef('params', $params);
			$this->assignRef('config', $config);
			$this->assignRef('order', $order);
			$this->assignRef('lists', $lists);

			parent::display($tmpl);

		} else {
			echo '<br />';
			echo '<p>'.JText::_('NO_EVENTS').'</p>';
			echo '<br />';
		}
		echo '<br />';

		// footer - please do not remove
		$text = '<small><hr>';
		$text .= JText::_('PRINTDATE').': '. JHTML::Date($date->toFormat(), $config->date_long_format).'<br />';
		$text .= SCOutput::showFooter();
		$text .= '</small>';
		echo $text;
	}
}
?>