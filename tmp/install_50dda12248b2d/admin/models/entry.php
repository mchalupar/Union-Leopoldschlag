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
defined('_JEXEC') or die();
jimport('joomla.application.component.model');

class SimpleCalendarModelEntry extends JModel {

	/**
	 * Constructor that retrieves the ID from the request
	 *
	 * @access	public
	 * @return	void
	 */
	function __construct() {
		parent::__construct();

		$array = JRequest::getVar('cid',  0, '', 'array');
		$this->setId((int)$array[0]);
	}

	/**
	 * Method to set the entry identifier
	 *
	 * @access	public
	 * @param	int Hello identifier
	 * @return	void
	 */
	function setId($id) {
		// Set id and wipe data
		$this->_id		= $id;
		$this->_data	= null;
	}

	/**
	 * Method to build the query
	 * @return	string
	 */
	function _buildQuery() {
		$query = 'SELECT a.*, '
				.'cat.categoryID AS categoryID, cat.categoryName AS categoryName, '
				.'g.groupID, g.groupName AS groupName, g.groupAbbr AS groupAbbr, '
				.'g.contactName AS gContactName, g.contactEmail AS gContactEmail, '
				.'g.contactWebSite AS gContactWebSite, g.groupLatLon AS groupLatLon, '
				.'g.contactTelephone AS gContactTelephone FROM #__simplecal AS a '
				.'INNER JOIN #__simplecal_categories AS cat ON a.categoryID = cat.categoryID '
				.'LEFT JOIN #__simplecal_groups AS g ON a.entryGroupID = g.groupID '
				.'WHERE a.id = ' . (int) $this->_id.' '
				.'ORDER BY a.date1';
		return $query;
	}


	/**
	 * Method to get a calendar entry
	 * @return object with data
	 */
	function &getData()
	{
		// Load the data
		if (empty( $this->_data )) {
			$query = $this->_buildQuery();
			$this->_db->setQuery( $query );
			$this->_data = $this->_db->loadObject();
		}
		if (!$this->_data) {
//			$this->_data = new stdClass();
//			$this->_data->id = 0;
//			$this->_data->entryName = null;
		}
		return $this->_data;
	}

	/**
	 * Method to store a record
	 *
	 * @access	public
	 * @return	boolean	True on success
	 */
	function store($data)
	{
		$row =& $this->getTable();

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
		if (!$row->store(true)) {
			$this->setError( $this->_db->getErrorMsg() );
			return false;
		}
		return $row->id;
	}

	/**
	 * Method to delete record(s)
	 *
	 * @access	public
	 * @return	boolean	True on success
	 */
	function delete() {
		$cids = JRequest::getVar( 'cid', array(0), 'post', 'array' );

		$row =& $this->getTable();

		if (count( $cids ))
		{
			foreach($cids as $cid) {
				if (!$row->delete( $cid )) {
					$this->setError( $row->getErrorMsg() );
					return false;
				}
			}
		}
		return true;
	}

	function getCategoryList() {
		$query  = 'SELECT categoryID AS value, categoryName AS text'
				.' FROM #__simplecal_categories'
				.' ORDER BY ordering';
		$this->_db->setQuery( $query );
		$data = $this->_db->loadObjectList();
		return $data;
	}

	function getGroupList() {
		$query  = 'SELECT groupID AS value, groupName AS text'
				.' FROM #__simplecal_groups'
				.' ORDER BY ordering';

		$this->_db->setQuery( $query );
		$data = $this->_db->loadObjectList();
		return $data;
	}
	
	function getStatusList() {
		$query  = 'SELECT statusID AS value, status_description AS text'
				.' FROM #__simplecal_statuses'
				.' ORDER BY ordering';
		$this->_db->setQuery( $query );
		$data = $this->_db->loadAssocList();
		array_unshift($data, array('value' => 0, 'text' => ''));
		return $data;
	}

	function publish($cid = array(), $publish = 1) {
		if (count( $cid )) {
			JArrayHelper::toInteger($cid);
			$cids = implode( ',', $cid );

			$query =  ' UPDATE #__simplecal'
					. ' SET published = '.(int) $publish
					. ' WHERE id IN ( '.$cids.' )';

			$this->_db->setQuery( $query );
			if (!$this->_db->query()) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
		}
		return true;
	}
	
	function getDataToCopy($cid) {
				// get relevant information of item to be copied
		$query = 'SELECT *' .
				' FROM #__simplecal ' .
				' WHERE id = ' . $cid ;
		$this->_db->setQuery($query);
		$data = $this->_db->loadObjectList();		
		return $data[0];
	}
}
?>