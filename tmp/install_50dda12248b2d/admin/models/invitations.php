<?php
/**
 * Invitations model for sending e-mail invitations
 * 
 * @author      Christophe Weis
 * @license		GNU/GPL
 */

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.model' );

class SimpleCalendarModelInvitations extends JModel {

	var $_data;

	/**
	 * Returns the query for retrieving upcoming events
	 * @return string The query to be used to retrieve the rows from the database
	 */
	function _buildQuery()
	{
        # need id and entryName
		$query = 'SELECT a.id, a.date1, a.date2, a.from_time, a.to_time, a.entryName, a.entryPlace, b.categoryName, ' .
	    		 'CASE WHEN CHAR_LENGTH(a.alias) THEN CONCAT_WS(\':\', a.id, a.alias) ELSE a.id END AS slug, '.
				 'CASE WHEN CHAR_LENGTH(b.alias) THEN CONCAT_WS(\':\', b.categoryID, b.alias) ELSE b.categoryID END AS catslug '.
		         'FROM #__simplecal AS a ' .
	    		 'INNER JOIN #__simplecal_categories AS b ON a.categoryID = b.categoryID ' ;

		$where  = 'WHERE CASE WHEN a.from_time IS NOT NULL THEN CASE WHEN a.to_time IS NOT NULL ';
        $where .= 'THEN CAST(CONCAT((CASE WHEN a.date2 > a.date1 THEN a.date2 ELSE a.date1 END), \'-\', a.to_time) AS date) >= CAST(now() AS date) ';
        $where .= 'ELSE CAST(CONCAT((CASE WHEN a.date2 > a.date1 THEN a.date2 ELSE a.date1 END), \'-\', a.from_time) AS date) >= CAST(now() AS date) END ';
    	$where .= 'ELSE (CASE WHEN a.date2 > a.date1 THEN a.date2 ELSE a.date1 END) >= CAST(now() AS date) END ';
		$where .= 'AND a.published = "1" ';

        $orderby = 'ORDER BY a.date1 ASC, a.from_time ASC';

        $query .= $where . ' ' . $orderby;	
		return $query;
	}

	/**
	 * Retrieves the data with upcoming events
	 * @return array Array of objects containing upcoming events
	 */
	function getData()
	{
		// Lets load the data if it doesn't already exist
		if (empty( $this->_data ))
		{
			$query = $this->_buildQuery();
			$this->_data = $this->_getList( $query );
		}

		return $this->_data;
	}
}
