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

class SimpleCalendarModelCalendar extends JModel {

	var $_total = null;
	var $_pagination = null;

	function __construct() {
		parent :: __construct();

		global $mainframe, $option;

		$limit = $mainframe->getUserStateFromRequest('global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
		$limitstart = $mainframe->getUserStateFromRequest($option . 'limitstart', 'limitstart', 0, 'int');

		$this->setState('limit', $limit);
		$this->setState('limitstart', $limitstart);
	}

	function _buildQuery() {
		$orderby	= $this->_buildContentOrderBy();
		$query = 'SELECT a.*, cat.categoryID AS categoryID, cat.categoryName AS categoryName, g.groupID, '
				.'g.groupName AS groupName, g.contactName AS gContactName, g.contactEmail AS gContactEmail, g.contactWebSite AS gContactWebSite '
				.'FROM #__simplecal AS a '
				.'INNER JOIN #__simplecal_categories AS cat ON a.categoryID = cat.categoryID '
				.'LEFT JOIN #__simplecal_groups AS g ON a.entryGroupID = g.groupID '
				.$orderby;
		return $query;
	}

	function _buildContentOrderBy() {
		global $mainframe, $option;

		$context = 'com_simplecalendar.calendar.';

		$filter_order		= $mainframe->getUserStateFromRequest( $context.'filter_order',		'filter_order',		'a.date1',	'cmd' );
		$filter_order_Dir	= $mainframe->getUserStateFromRequest( $context.'filter_order_Dir',	'filter_order_Dir',	'',				'word' );

		if ($filter_order == 'a.date1'){
			$orderby = 'ORDER BY a.date1 '.$filter_order_Dir;
		} else {
			$orderby = 'ORDER BY '.$filter_order.' '.$filter_order_Dir.' , a.date1';
		}

		return $orderby;
	}

	function getTotal() {
		if (empty ($this->_total)) {
			$query = $this->_buildQuery();
			$this->_total = $this->_getListCount($query);
		}
		return $this->_total;
	}

	function getPagination() {
		if (empty ($this->_pagination)) {
			jimport('joomla.html.pagination');
			$this->_pagination = new JPagination($this->getTotal(), $this->getState('limitstart'), $this->getState('limit'));
		}
		return $this->_pagination;
	}

	function getData() {
		$query = $this->_buildQuery();
		$pagination = $this->getPagination();
		$data = $this->_getList($query, $pagination->limitstart, $pagination->limit);
		return $data;
	}

	function delete() {
		$cids = JRequest :: getVar('cid', array (0), 'post', 'array');
		$row = & $this->getTable();

		foreach ($cids as $cid) {
			if (!$row->delete($cid)) {
				$this->setError($row->getErrorMsg());
				return false;
			}
		}
		return true;
	}
}
?>