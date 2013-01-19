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

class TableGroup extends JTable {
	var $groupID = null;
	var $groupAbbr = null;
	var $groupName = null;
	var $contactName = null;
	var $contactEmail = null;
	var $contactWebSite = null;
	var $contactTelephone = null;
	var $groupLatLon = null;
//	var $showAlways = null;
	var $imagefile = null;
	var $catid = null;
	var $ordering = null;
	var $alias = '';

	function __construct(&$db) {
		parent::__construct( '#__simplecal_groups', 'groupID', $db);
	}

	function check() {		
		jimport( 'joomla.filter.output' );
	    if(empty($this->alias)) {
	            $this->alias = $this->groupName;
	    }
	    $this->alias = JFilterOutput::stringURLSafe($this->alias);
	    
		if (trim($this->groupName) == '') {
			$this->_error = JText::_( 'INSERT_GROUP_NAME' );
			JError::raiseWarning('SOME_ERROR_CODE', $this->_error );
			return false;
		}
		return true;
	}
}
?>