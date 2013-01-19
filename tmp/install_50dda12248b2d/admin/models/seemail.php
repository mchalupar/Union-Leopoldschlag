<?php
/**
 * SimpleCalendarModelSeemail for previewing e-mail
 * 
 * @author  Christophe Weis
 * @license	GNU/GPL
 */

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.model' );

class SimpleCalendarModelSeemail extends JModel {
	/**
	 * Constructor that retrieves the ID from the request
	 *
	 * @access	public
	 * @return	void
	 */
	function __construct() {
		parent::__construct();

		$id = JRequest::getVar('event');
		$this->_id = (int)$id;
		$this->_mails = null;
	}

	/**
	 * SimpleCalendarModelSeemail mail array
	 *
	 * @var array
	 */
	var $_mails;

	/**
	 * Retrieves data for given event
	 * @return array Array of objects containing event data.
	 */
	function getData() {
		// Lets load the data if it doesn't already exist
		$query =    'SELECT id, date1, date2, entryName, entryPlace, from_time, to_time, entryInfo ' .
		            'FROM #__simplecal ' .
                    'WHERE  id = ' . $this->_id . '; ';
        return $this->_getList( $query );
	}

	/**
	 * Retrieves list of recipients
	 * @return array Array of objects containing recipients data.
	 */
	function getContactMails() {
		// Lets load the data if it doesn't already exist

        if (empty( $this->_mails )) {
		    $query =    'SELECT name, email ' .
		                'FROM #__users ' .
                        "WHERE  block  = '0' " .
                        'ORDER BY name ASC';
            $this->_mails = $this->_getList( $query );
        }
        return $this->_mails;
	}
}
