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
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view');

/**
 * HTML View class for the Calendar list View
 *
 * @package Joomla
 * @subpackage SimpleCalendar
 * @since 0.7.14b
 */
class SimpleCalendarViewCalendar extends JView
{
	/**
	 * Creates the Event Feed
	 *
	 * @since 0.7.14b
	 */
	function display()
	{
		global $mainframe;

		$doc 		=& JFactory::getDocument();
		$config 	=& SCOutput::config();

		// Get some data from the model
		JRequest::setVar('limit', $mainframe->getCfg('feed_limit'));
		$rows = & $this->get('Data');

		foreach ( $rows as $row )
		{
			// strip html from feed item title
			$title = $this->escape( $row->entryName );
			$title = html_entity_decode( $title );

			// strip html from feed item category
			$category = $this->escape( $row->categoryName );
			$category = html_entity_decode( $category );

			//Format date
			$date = strftime( $config->date_long_format, strtotime( $row->date1 ));
			if (!$row->enddates) {
				$displaydate = $date;
			} else {
				$enddate 	= strftime( $config->date_long_format, strtotime( $row->date1 ));
				$displaydate = $date.' - '.$enddate;
			}

			//Format time
			if ($row->from_time) {
				$time = strftime( $config->time_format, strtotime( $row->from_time ));
//				$time = $time.' '.$config->time_format;
				$displaytime = $time;
			}
			if ($row->to_time) {
				$endtime = strftime( $config->time_format, strtotime( $row->to_time ));
//				$endtime = $endtime;//.' '.$config->timename;
				$displaytime = $time.' - '.$endtime;
			}

			$link = 'index.php?view=detail&catid=' . $row->catslug . '&id='. $row->slug;
			$link = JRoute::_( $link );

			$description = JText::_( 'ENTRYNAME' ).': '.$title.'<br />';
			if ( $row->entryPlace != "") {
				$description .= JText::_( 'ENTRYPLACE' ).': '.$row->entryPlace.'<br />';
			}
			$description .= JText::_( 'CATEGORY' ).': '.$category.'<br />';
			$description .= JText::_( 'DATE' ).': '.$displaydate.'<br />';
			$description .= JText::_( 'TIME' ).': '.$displaytime.'<br />';
			$description .= JText::_( 'EXTENDED_INFO' ).': '. $row->entryInfo;

			@$created = ( $row->created ? date( 'r', strtotime($row->created) ) : '' );

			$feed = new JFeedItem();
			$feed->title 		= $title;
			$feed->link 		= $link;
			$feed->description 	= $description;
			$feed->date			= $created;
			$feed->category   	= $category;

			$doc->addItem( $feed );
		}
	}
}
?>