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


// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

jimport( 'joomla.application.component.view');

/**
 * HTML Article View class for the Content component
 * Changes by FA for com_simplecalendar
 *
 * @package		Joomla
 * @subpackage	Content
 * @since 1.5
 */
class SimpleCalendarViewDetail extends JView {

	function display($tpl = null) {

		global $option, $mainframe;

		// load params from the component
		$params 	=& JComponentHelper::getParams( 'com_simplecalendar' );
		$model 		=& $this->getModel('detail');
		$config     =  SCOutput::config();
		$event 		= $model->getDetail();

		$dispatcher	=& JDispatcher::getInstance();

		$dispatcher->trigger('onPrepareContent', array (& $event, & $params, 0));

		$document = &JFactory::getDocument();
		$document->setMimeEncoding('application/pdf');

		// prepare some strings
		$dateString = '';

		$dateString = SCOutput::getFormattedDate($event->date1, $event->date2, $event->date3, $config->date_long_format, $config->date_short_format);
		$timeString = SCOutput::getFormattedTime($event->from_time, $event->to_time, $config->time_format, false);
		
		// set document information
		$document->setTitle(JText::_('CALENDAR').' - '.JText::_('EVENT_DETAIL'));
		$document->setName(JText::_('EVENT_DETAIL').'-'.$event->entryName);
		$document->setDescription($mainframe->getCfg( 'sitename' ).' - '.JText::_('CALENDARTEXT'));

		// prepare header lines
		$date =& JFactory::getDate();
		$document->setHeader($mainframe->getCfg( 'sitename' ));
		$text = '';
		$text .= '<b><font size="+2">'.$event->entryName.'</font></b><br />';
		$text .= $dateString.'<br /><br />';
		$text .= '<table>';
		if ( $event->entryPlace != '' ) {
			$text .= '<tr><td><i>'.JText::_('ENTRYPLACE').':</i></td><td>'.$event->entryPlace.'</td></tr>';
		}
		if ( $timeString != '') {
			$text .= '<tr><td><i>'.JText::_('TIMES').':</i></td><td>'.$timeString.'</td></tr>';
		}
		if ( $config->custom1_label != '' && $event->custom1 != '' ) {
			$text .= '<tr><td><i>'.$config->custom1_label.':</i></td><td>'.$event->custom1.'</td></tr>';
		}
		if ( $event->groupName != '' ) {
			$text .= '<tr><td><i>'.JText::_('ORGANIZER').':</i></td><td>'.$event->groupName.'</td></tr>';
		}
		if ( $event->contactName != '' ) {
			$text .= '<tr><td><i>'.JText::_('CONTACTPERSON').':</i></td><td>'.$event->contactName.'</td></tr>';
		}
		if ( $event->contactEmail != '' ) {
			$text .= '<tr><td><i>'.JText::_('CONTACTEMAIL').':</i></td><td>'.$event->contactEmail.'</td></tr>';
		}
		if ( $event->contactWebSite != '' ) {
			$text .= '<tr><td><i>'.JText::_('CONTACTWEBSITE').':</i></td><td><a href="'.$event->contactWebSite.'" target="_blank">'.$event->contactWebSite.'</a></td></tr>';
		}
		if ( $event->contactTelephone != '' ) {
			$text .= '<tr><td><i>'.JText::_('CONTACTTELEPHONE').':</i></td><td>'.$event->contactTelephone.'</td></tr>';
		}
		if ( $config->custom2_label != '' && $event->custom2 != '' ) {
			$text .= '<tr><td><i>'.$config->custom2_label.':</i></td><td>'.$event->custom2.'</td></tr>';
		}
		if ( $event->categoryID != '' ) {
			$text .= '<tr><td><i>'.JText::_('CATEGORY').':</i></td><td>'.$event->categoryName.'</td></tr>';
		}
		
		if ( !is_null($config->currency) ) {
			if ( $config->currency != '' && $event->price != '' ) {
				$text .= '<tr><td><i>'.JText::_('PRICE').':</i></td><td>'.$event->price.'&nbsp;'. $config->currency.'</td></tr>';
			}
		}
		$text .= '</table>';

		if ( $event->entryInfo != '' ) {
			$text .= '<i>'.JText::_('EXTENDED_INFO').':</i><br />'.nl2br($event->entryInfo).'<br />';
		}
		$text .= '<br />';
		$text .= '<small><hr>';
		$text .= JText::_('PRINTDATE').': '. JHTML::Date($date->toFormat(), $config->date_long_format).'<br />';
		$text .= SCOutput::showFooter();
		$text .= '</small>';

		// output
		echo $text;
	}
}
?>
