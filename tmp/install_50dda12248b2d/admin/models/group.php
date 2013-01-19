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

class SimpleCalendarModelGroup extends JModel {

	/**
	 * Constructor that retrieves the ID from the request
	 *
	 * @access	public
	 * @return	void
	 */
	function __construct() {
		parent::__construct();

		$array = JRequest::getVar('cid',  0, '', 'array');
		$this->setId((int) $array[0]);
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
		$query = 'SELECT * FROM #__simplecal_groups '
				.'WHERE groupID = ' . (int) $this->_id;
		return $query;
	}


	/**
	 * Method to get a calendar entry
	 * @return object with data
	 */
	function &getData() {
		// Load the data
		if (empty( $this->_data )) {
			$query = $this->_buildQuery();
			$this->_db->setQuery( $query );
			$this->_data = $this->_db->loadObject();
		}
		if (!$this->_data) {
//			$this->_data = new stdClass();
//			$this->_data->id = 0;
//			$this->_data->greeting = null;
		}
		return $this->_data;
	}

	/**
	 * Method to store a record
	 *
	 * @access	public
	 * @return	boolean	True on success
	 */
	function store() {
		$row =& $this->getTable();

		$data = JRequest::get( 'post' );

		// ------------------------------------------------------------------------------
		//Retrieve file details from uploaded file, sent from upload form
		$file = JRequest::getVar('imagefile', null, 'files', 'array');
		
		if ($file['name'] == '' ) {
			echo "no file...";
		} else {
			 
			//Import filesystem libraries. Perhaps not necessary, but does not hurt
			jimport('joomla.filesystem.file');
			 
			//Clean up filename to get rid of strange characters like spaces etc
	//		$filename = JFile::makeSafe($file['name']);
			$filename = JFile::makeSafe('gid'.$data['groupID']. '.' .JFile::getExt($file['name']));
			 
			//Set up the source and destination of the file
			$src = $file['tmp_name'];
			$dest = JPATH_SITE. '/images/simplecalendar' . DS . $filename;
			 
			//First check if the file has the right extension, we need jpg, gif & png only
			if ( strtolower(JFile::getExt($filename) ) == 'jpg' || 
					strtolower(JFile::getExt($filename) ) == 'gif' ||
					strtolower(JFile::getExt($filename) ) == 'png' ) {
			   if ( JFile::upload($src, $dest) ) {
			      echo "Upload successful!";
			      $data['imagefile'] = $filename;
			   } else {
			      echo "ERROR!";
			   }
			} else {
			   echo "Incorrect extension!";
			}
		}
		// ------------------------------------------------------------------------------				
		
		// Bind the form fields to the table
		if (!$row->bind($data)) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		if (!$row->groupID) {
			$where = 'catid = ' . (int) $row->catid ;
			$row->ordering = $row->getNextOrder( $where );
		}

		// Make sure the record is valid
		if (!$row->check()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		// Store the group record table to the database
		if (!$row->store()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		return $row->groupID;
	}

	function checkBeforeDelete() {
		$db =& JFactory::getDBO();
		$cids = JRequest :: getVar('cid', array(0), 'post', 'array');
		foreach ($cids as $cid) {
			$_query = 'SELECT COUNT(*) FROM #__simplecal '.
					'WHERE entryGroupID = ' . (int) $cid;
			$db->setQuery($_query);
			$_total = $db->loadResult();
			if ($_total > 0) {
				return false;
			}
			else {
				return true;
			}
		}
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

		if (count( $cids )) {
			foreach($cids as $cid) {
				if (!$row->delete( $cid )) {
					$this->setError( $row->getErrorMsg() );
					return false;
				}
			}
		}
		return true;
	}

	/**
	 * Method to move a category
	 *
	 * @access	public
	 * @return	boolean	True on success
	 */
	function move($direction)
	{
		$row =& $this->getTable();
		if (!$row->load($this->_id)) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		if (!$row->move( $direction, ' catid = '.(int) $row->catid )) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		return true;
	}

	/**
	 * Method to move a category
	 *
	 * @access	public
	 * @return	boolean	True on success
	 */
	function saveorder($cid = array(), $order)
	{
		$row =& $this->getTable();
		$groupings = array();

		// update ordering values
		for( $i=0; $i < count($cid); $i++ )
		{
			$row->load( (int) $cid[$i] );
			// track categories
			$groupings[] = $row->catid;

			if ($row->ordering != $order[$i])
			{
				$row->ordering = $order[$i];
				if (!$row->store()) {
					$this->setError($this->_db->getErrorMsg());
					return false;
				}
			}
		}

		// execute updateOrder for each parent group
		$groupings = array_unique( $groupings );
		foreach ($groupings as $group){
			$row->reorder('catid = '.(int) $group);
		}

		return true;
	}
}
?>
