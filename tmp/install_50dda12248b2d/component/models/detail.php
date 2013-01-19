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
defined( '_JEXEC' ) or die( 'Restricted access');
jimport( 'joomla.application.component.model');

class SimpleCalendarModelDetail extends JModel {

	var $_event= null;
	var $_id = null;

	function __construct() {
    	parent::__construct();
    	$id = JRequest::getVar('id', 0);
    	$this->_id = $id;
	}

	/**
	 * Returns an object with all detail of the event 
	 *
	 * @return event object
	 */
	function getDetail() {

		$query = 'SELECT a.*, cat.categoryID, cat.alias AS catalias, cat.*, g.groupID, g.groupName AS groupName, g.groupAbbr AS groupAbbr, '
				.'g.contactName AS gContactName, g.contactEmail AS gContactEmail, '
				.'g.contactWebSite AS gContactWebSite, g.groupLatLon AS groupLatLon, '
				.'g.contactTelephone AS gContactTelephone, '
				.'s.statusID AS status_id, s.status_description AS status_description, s.status_color AS status_color, '
				.'CASE WHEN CHAR_LENGTH(a.alias) THEN CONCAT_WS(\':\', a.id, a.alias) ELSE a.id END AS slug, '
				.'CASE WHEN CHAR_LENGTH(cat.alias) THEN CONCAT_WS(\':\', cat.categoryID, cat.alias) ELSE cat.categoryID END AS catslug '
				.'FROM #__simplecal AS a '
				.'INNER JOIN #__simplecal_categories AS cat ON a.categoryID = cat.categoryID '
				.'LEFT JOIN #__simplecal_groups AS g ON a.entryGroupID = g.groupID '
				.'LEFT JOIN #__simplecal_statuses AS s ON a.statusID = s.statusID '
				.'WHERE a.id = ' . (int) $this->_id;
		
		$user =& JFactory::getUser();
		if ( $user->guest ) {
			$query .=  ' AND a.entryIsPrivate = "0" AND a.published = "1"';
		}
		
		$query .=  ' LIMIT 1';
				
		$this->_db->setQuery($query);
		$this->_event = $this->_db->loadObject();
		return $this->_event;
	}
	
	/**
	 * Method to store a record
	 *
	 * @access	public
	 * @return	boolean	True on success
	 */
	function store()
	{
		$row =& $this->getTable();

		$data = JRequest::get( 'post' );

		// Bind the form fields to the table
		if (!$row->bind($data)) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		// Make sure the record is valid
		if (!$row->check()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		// Store the web link table to the database
		if (!$row->store()) {
			$this->setError( $this->_db->getErrorMsg() );
			return false;
		}
		return true;
	}
	
}


?>
