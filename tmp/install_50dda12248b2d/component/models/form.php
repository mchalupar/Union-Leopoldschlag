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

class SimpleCalendarModelForm extends JModel {

	var $_item= null;
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
		$query = 'SELECT a.*, cat.categoryID, cat.alias AS catalias, g.groupName AS groupName, g.groupAbbr AS groupAbbr, '
				.'g.contactName AS gContactName, g.contactEmail AS gContactEmail, '
				.'g.contactWebSite AS gContactWebSite, g.groupLatLon AS groupLatLon, '
				.'g.contactTelephone AS gContactTelephone FROM #__simplecal AS a '
				.'INNER JOIN #__simplecal_categories AS cat ON a.categoryID = cat.categoryID '
				.'LEFT JOIN #__simplecal_groups AS g ON a.entryGroupID = g.groupID '
				.'WHERE a.id = ' . (int) $this->_id . ' LIMIT 1';
				
		$this->_db->setQuery($query);
		$this->_event= $this->_db->loadObject();
		return $this->_event;
	}
	
	function getGroupList() {
		$query  = 'SELECT groupId AS value, groupName AS text'
				.' FROM #__simplecal_groups'
				.' ORDER BY ordering';

		$this->_db->setQuery( $query );
		$data = $this->_db->loadObjectList();
		return $data;
	}
	
	function getCategoriesList() {
		$query  = 'SELECT categoryId AS value, categoryName AS text'
				.' FROM #__simplecal_categories'
				.' ORDER BY ordering';

		$this->_db->setQuery( $query );
		$data = $this->_db->loadObjectList();
		return $data;
	}
	
	/**
	 * Returns the list of available statuses
	 * @return array of statuses IDs and Descriptions
	 */
	function getStatusList() {
		$query  = 'SELECT statusId AS value, status_description AS text'
				.' FROM #__simplecal_statuses'
				.' ORDER BY ordering';
		$this->_db->setQuery( $query );
		$data = $this->_db->loadAssocList();
		array_unshift($data, array('value' => 0, 'text' => ''));
		return $data;
	}
	
	function store($data)
	{
		$row =& $this->getTable('entry');

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

		// Store the simplecalendar data table to the database
		if (!$row->store(true)) {
			$this->setError( $this->_db->getErrorMsg() );
			return false;
		}
		return true;
	}
}


?>
