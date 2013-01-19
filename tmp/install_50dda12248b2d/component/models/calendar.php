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
jimport('joomla.application.component.model');

class SimpleCalendarModelCalendar extends JModel {

	var $_total = null;
	var $_pagination = null;

	function __construct()	{
		parent::__construct();

		global $mainframe, $option;

		$limit	= $mainframe->getUserStateFromRequest( 'global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int' );
		$limitstart = JRequest::getVar('limitstart',0);

		//		$limitstart = $mainframe->getUserStateFromRequest( $option.'limitstart', 'limitstart', 0, 'int' );
		//		read here on why this was removed: http://docs.joomla.org/Talk:Using_JPagination_in_your_component

		$this->setState('limit', $limit);
		$this->setState('limitstart', $limitstart);
	}

	function store($data) {
		$row =& $this->getTable();
		if (!$row->bind($data)) {
			JError::raiseWarning(500, $row->getError());
			return false;
		}
		if (!$row->check()) {
			JError::raiseWarning(500, $row->getError());
			return false;
		}
		if (!$row->store(true)) {
			JError::raiseWarning(500, $row->getError());
			return false;
		}
		return true;
	}

	/**
	 * Builds the WHERE part of the query
	 *
	 * @return string
	 */
	function _buildContentWhere()
	{
		global $mainframe, $option;
		$db			=& JFactory::getDBO();
		$search		= $mainframe->getUserStateFromRequest( $option.'search', 'search', '', 'string' );
		$search		= JString::strtolower( $search );
		$config 	= SCOutput::config();
		$params 	=& $mainframe->getParams();
		$user 		=& JFactory::getUser();

		$where = array();

		$date =& JFactory::getDate();
		$now  = $date->toMySQL();
		$show_only_future_events = $params->get('show_only_future_events') != null ? $params->get('show_only_future_events') : 2;
		switch ( $show_only_future_events ) {
			case 0: //past events only
				$where[] = '(CASE WHEN a.date2 > a.date1 THEN a.date2 ELSE a.date1 END) <= CAST(CURDATE() AS date)';
				break;
			case 1: //future events only
				$where[] = '(CASE WHEN a.date2 > a.date1 THEN a.date2 ELSE a.date1 END) >= CAST(CURDATE() AS date)';
				break;
			case 2:
			default: //future and past events
				break;
		}

		// reads the category filter from the parameters - if != 0, then filter by this category.
		$catid = JRequest::getVar('catid') ? JRequest::getVar('catid') : $params->get('catid');
		if ( $catid != '' && $catid != '0' ) {
			$where[] = ' a.categoryID = ' . (int) $catid . ' ';
		}

		if ( $user->guest ) {
			$where[] = 'a.entryIsPrivate = "0"';
		}
		if ( $user->guest || $user->gid < $config->frontend_edit_gid ) {
			$where[] = 'a.published = "1"';
		}
			
		if ( $search ) {
			$where[] = '( LOWER(a.entryName) LIKE '.$db->Quote( '%'.$db->getEscaped( $search, true ).'%', false ) . ' OR ' .
					  ' LOWER(a.entryPlace) LIKE '.$db->Quote( '%'.$db->getEscaped( $search, true ).'%', false ) . ' OR ' .
					  ' LOWER(a.entryInfo) LIKE '.$db->Quote( '%'.$db->getEscaped( $search, true ).'%', false ) . ' OR ' .
					  ' LOWER(groups.groupName) LIKE '.$db->Quote( '%'.$db->getEscaped( $search, true ).'%', false ) . ' OR ' .
					  ' LOWER(cat.categoryName) LIKE '.$db->Quote( '%'.$db->getEscaped( $search, true ).'%', false ).
					  ')';
		}

		$where 		= ( count( $where ) ? ' WHERE '. implode( ' AND ', $where ) : '' );

		return $where;
	}


	/**
	 * Builds the query string
	 *
	 * @return string query
	 */
	function _buildQuery() {
		global $mainframe, $option;

		$config 	= SCOutput::config();
		$search 	= $mainframe->getUserStateFromRequest( $option.'search','search','','string' );
		$search		= JString::strtolower( $search );
		$where 		= $this->_buildContentWhere();

		$params 	=& $mainframe->getParams();

		$order = '';
		if ( $params->get('order') == '' && JRequest::getString('order') == '' ) {
			$order = $config->default_ordering;
		} else {
			if ( $params->get('order') == '' ) {
				$order = JRequest::getString('order');
			} else {
				$order = $params->get('order');
			}
		}
		$order_dir = '';
		$reverse_sort_order = $params->get('reverse_sort_order') != null ? $params->get('reverse_sort_order') : 0;
		if ( $reverse_sort_order ) {
			$order_dir = 'DESC';
		} else {
			$order_dir = 'ASC';
		}
		$order_dir = ' '.$order_dir;

		switch($order) {
			case 'category':
				$orderBy = 'ORDER BY cat.ordering '. $order_dir .', a.date1, a.from_time, a.id';
				break;
			default:
				$orderBy = 'ORDER BY a.date1'. $order_dir .', a.from_time, a.id';
				break;
		}

		$query = 'SELECT a.*, cat.categoryID, cat.alias AS catalias, cat.*, status.statusID, status.*, groups.groupID, '.
				 'groups.GroupName as groupName, groups.GroupAbbr as groupAbbr, '.
				 'groups.contactName AS gContactName, groups.contactWebSite AS gContactWebSite, ' .
				 'groups.contactEmail AS gContactEmail, groups.contactTelephone AS gContactTelephone, '.
				 'CASE WHEN CHAR_LENGTH(a.alias) THEN CONCAT_WS(\':\', a.id, a.alias) ELSE a.id END AS slug, '.
				 'CASE WHEN CHAR_LENGTH(cat.alias) THEN CONCAT_WS(\':\', cat.categoryID, cat.alias) ELSE cat.categoryID END AS catslug '.
				 'FROM #__simplecal AS a ' .
				 'INNER JOIN #__simplecal_categories AS cat ON a.categoryID = cat.categoryID ' .
				 'LEFT JOIN #__simplecal_groups AS groups ON a.entryGroupID = groups.groupID ' .
				 'LEFT JOIN #__simplecal_statuses AS status ON a.statusID = status.statusID ' .
		$where . ' ' .
		$orderBy;
		return $query;
	}

	function debugQuery() {
		return $this->_buildQuery();
	}

	/**
	 * Returns the total number of records for a given query
	 *
	 * @return integer
	 */
	function getTotal() {
		$query = '';
		if ( empty($this->_total) ) {
			$query = $this->_buildQuery();
			$this->_total = $this->_getListCount($query);
		}
		return $this->_total;
	}

	function getPagination() {
		global $mainframe;

		$params =& $mainframe->getParams();
		
		$limitstart	= JRequest::getVar('limitstart', 0, '', 'int');
		$limit = $mainframe->getUserStateFromRequest('com_simplecalendar.list.limit', 'limit', $params->get('display_num', 0), 'int');

		if ( empty($this->_pagination) ) {
			jimport('joomla.html.pagination');
			$this->_pagination = new JPagination( $this->getTotal(), $limitstart, $limit );
		}
		return $this->_pagination;
	}

	/**
	 * Gets the data.
	 *
	 * @return object data
	 */
	function getData() {
		$component = JComponentHelper::getComponent( 'com_simplecalendar' );
		//		$params = new JParameter( $component->params );
		$config 	= SCOutput::config();

		$query = $this->_buildQuery();
		if (!isset($pagination))
		$pagination  = '';
		if (!isset($data))
		$data = '';

		// If the param says not to paginate, return a clean query
		if ( $config->show_search_bar ) {
			$pagination = $this->getPagination();
			$data = $this->_getList($query, $pagination->limitstart, $pagination->limit);
		} else {
			$data = $this->_getList($query);
		}

		return $data;
	}

	/**
	 * Gets the data for the PDF view
	 * @return object data
	 */
	function getDataForPDF() {
		$component = JComponentHelper::getComponent( 'com_simplecalendar' );
		//		$params = new JParameter( $component->params );
		$config 	= SCOutput::config();

		$query = $this->_buildQuery();
		if (!isset($pagination))
		$pagination  = '';
		if (!isset($data))
		$data = '';

		$data = $this->_getList($query);

		return $data;
	}

	/**
	 * Returns a list of categories that are included in the dataobject
	 * @since 0.7.18b
	 * @return object list
	 */
	function getActiveCategories() {
		$db = JFactory::getDBO();
		$where 		= $this->_buildContentWhere();
		$query = 'SELECT DISTINCT a.categoryID, a.entryGroupID, cat.categoryID AS catcategoryID, cat.categoryName, '
		.'cat.alias AS catalias, cat.category_color, cat.ordering, '
		.'CASE WHEN CHAR_LENGTH(cat.alias) THEN CONCAT_WS(\':\', cat.categoryID, cat.alias) ELSE cat.categoryID END AS catslug '
		.'FROM #__simplecal AS a '
		.'LEFT JOIN #__simplecal_categories AS cat ON a.categoryID = cat.categoryID '
		.'LEFT JOIN #__simplecal_groups AS groups ON a.entryGroupID = groups.groupID ';
		$query .= ' ' . $where;
		$query .= ' ' . 'GROUP BY a.categoryID';
		$query .= ' ' . 'ORDER BY cat.ordering';
		$db->setQuery($query);
		$data = $db->loadObjectList();
		return $data;
	}

	/**
	 * Returns the category name for a given catid
	 *
	 * @return string name of category
	 */
	function getCategoryName() {
		$catid = JRequest::getVar('catid');
		$query = 'SELECT categoryID, alias, categoryName FROM #__simplecal_categories WHERE categoryID = ' . (int) $catid . ' LIMIT 1';
		$data = $this->_getList($query);
		if( sizeof($data) > 0 ) {
			$catName = $data[0]->categoryName;
		} else {
			$catName = '';
		}
		return $catName;
	}

	/**
	 * Returns a list of all categories along with their colour
	 *
	 * @return array of categories
	 * @since 0.7.11b
	 */
	function getCategories() {
		$catid = JRequest::getVar('catid');
		$query = 'SELECT categoryID, categoryName, category_color FROM #__simplecal_categories';
		$data = $this->_getList($query);
		return $data;
	}


	/**
	 * Returns the status description for a given statusID
	 *
	 * @return string name of status
	 * @since 0.7.5b
	 */
	function getStatusName() {
		$catid = JRequest::getVar('catid');
		$query = 'SELECT statusID, status_description FROM #__simplecal_statuses WHERE statusID = ' . (int) $catid . ' LIMIT 1';
		$data = $this->_getList($query);
		if( sizeof($data) > 0 ) {
			$statusDescription = $data[0]->status_description;
		} else {
			$statusDescription = '';
		}
		return $statusDescription;
	}
}

?>