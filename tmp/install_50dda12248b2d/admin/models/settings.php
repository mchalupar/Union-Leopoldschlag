<?php


// no direct access
defined('_JEXEC') or die();
jimport('joomla.application.component.model');

class SimpleCalendarModelSettings extends JModel {

	/**
	 * Constructor that retrieves the ID from the request
	 *
	 * @access	public
	 * @return	void
	 */
	function __construct() {
		parent::__construct();

		$this->setId(1);
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
		$query = 'SELECT * FROM #__simplecal_settings WHERE id = 1';
		return $query;
	}


	/**
	 * Method to get a member entry
	 * @return object with data
	 */
	function &getData()	{
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

//		$data = JRequest::get( 'post' );

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
		return true;
	}

	/**
	 * Method to delete record(s)
	 *
	 * @access	public
	 * @return	boolean	True on success
	 */
	function delete() {
		$id = 1;
		$row =& $this->getTable();
		if (!$row->delete( $cid )) {
			$this->setError( $row->getErrorMsg() );
			return false;
		}
		return true;
	}

}
?>