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


//no direct access
defined('_JEXEC') or die('Restricted access');

class TableEntry extends JTable {
	var $id = null;
	var $categoryID = null;
	var $date1 = null;
	var $date2 = null;
	var $date3 = null;
	var $entryName = '';
	var $entryPlace = '';
	var $from_time = null;
	var $to_time = null;
	var $entryLatLon = '';
	var $entryGroupID = '';
	var $entryInfo = null;
	var $entryIsPrivate = '0';
	var $contactName = '';
	var $contactEmail = '';
	var $contactWebSite = '';
	var $contactTelephone = '';
	var $price = '';
	var $published = '1';
	var $userid = '';
//	var $attached_file = '';
//	var $attached_file_description = '';
	var $statusID = '';
	var $alias = '';
	var $created = null;
	var $modified = null;
	var $entryAddress = '';
	var $custom1 = '';
	var $custom2 = '';
//	var $contactGroup = '';
	var $is_favourite = 0;

	function __construct(&$db) {
		parent::__construct( '#__simplecal', 'id', $db);
	}

	function check() {

		jimport( 'joomla.filter.output' );
		if(empty($this->alias)) {
			$this->alias = $this->entryName;
		}
		$this->alias = JFilterOutput::stringURLSafe($this->alias);

		if ( trim($this->categoryID) == '0' || trim($this->categoryID) == '' ) {
			$this->_error = JText::_( 'INSERT_CATEGORY' );
			JError::raiseWarning('SOME_ERROR_CODE', $this->_error );
			return false;
		}
		if ( trim($this->date1) == '' || trim($this->date1) == '0000-00-00' ) {
			$this->_error = JText::_( 'INSERT_DATE1' );
			JError::raiseWarning('SOME_ERROR_CODE', $this->_error );
			return false;
		}
		if ( trim($this->date2) == trim($this->date1) ) {
			$this->date2 = NULL;
		}
		if ( trim($this->date3) == trim($this->date1) ) {
			$this->date3 = NULL;
		}
		if ( trim($this->entryName) == '' ) {
			$this->_error = JText::_( 'INSERT_ENTRY_NAME' );
			JError::raiseWarning('SOME_ERROR_CODE', $this->_error );
			return false;
		}
		if ( trim($this->date2) == '' ) {
			$this->date2 = NULL;
		}
		if ( trim($this->date3) == '' ) {
			$this->date3 = NULL;
		}
		if ( trim($this->from_time) == '' ) {
			$this->from_time = NULL;
		}
		if ( trim($this->to_time) == '' ) {
			$this->to_time = NULL;
		}
		if ( trim($this->date2) == '' && ( trim($this->from_time) == trim($this->to_time)) ) {
			$this->to_time = NULL;
		}
		if ( trim($this->contactWebSite) == 'http://' ) {
			$this->contactWebSite = '';
		}
		if ( trim($this->is_favourite) == '' ) {
			$this->is_favourite = 0;
		}
		return true;
	}
}
?>