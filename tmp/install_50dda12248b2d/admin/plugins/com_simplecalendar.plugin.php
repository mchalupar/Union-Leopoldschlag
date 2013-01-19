<?php
/**
 * Class jc_com_simplecalendar
 * @author Fabrizio Albonico
 * @copyright (c) 2010
 */
class jc_com_simplecalendar extends JCommentsPlugin {

	function getObjectTitle( $id ) {
		// Data load from database by given id
		$db =& JFactory::getDBO();
		$db->setQuery( "SELECT entryName, id FROM #__simplecal WHERE id='$id'");
		return $db->loadResult();
	}

	function getTitles($ids) {
		$db =& JFactory::getDBO();
		$db->setQuery( 'SELECT id, entryName FROM #__simplecal WHERE id IN (' . implode(',', $ids) . ')' );
		return $db->loadObjectList('id');
	}

	function getObjectLink( $id ) {
		// Itemid meaning of our component
		$_Itemid = JCommentsPlugin::getItemid( 'com_simplecalendar' );
		$db =& JFactory::getDBO();

		$query = 'SELECT a.id, a.date1, a.date2, a.from_time, a.to_time, a.entryName, a.entryPlace, b.categoryName, ' .
	    	 'CASE WHEN CHAR_LENGTH(a.alias) THEN CONCAT_WS(\':\', a.id, a.alias) ELSE a.id END AS slug, '.
			 'CASE WHEN CHAR_LENGTH(b.alias) THEN CONCAT_WS(\':\', b.categoryID, b.alias) ELSE b.categoryID END AS catslug '.
	         'FROM #__simplecal AS a ' .
	    	 'INNER JOIN #__simplecal_categories AS b ON a.categoryID = b.categoryID '
	    	 .'WHERE a.id = ' . (int) $id;
	    	 $db->setQuery($query);
	    	 $row = $db->loadObject();

	    	 require_once(JPATH_SITE.DS.'components'.DS.'com_simplecalendar'.DS.'helpers'.DS.'route.php');

	    	 $link = JRoute::_( SimpleCalendarHelperRoute::getRoute($row->slug, $row->catslug) );

	    	 // url link creation for given object by id
	    	 return $link;
	}

	function getObjectOwner( $id ) {
		$db = & JFactory::getDBO();
		$db->setQuery( 'SELECT userid, id FROM #__simplecal WHERE id = ' . $id );
		return $db->loadResult();
	}
}
?>