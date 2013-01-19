<?php
// No direct access
defined('_JEXEC') or die('Restricted access');

require JPATH_SITE . DS . 'components' . DS . 'com_simplecalendar' . DS . 'classes' . DS . 'output.class.php';

class SimpleCalendarUninstaller {
	
	/**
	 * returns the current DB object
	 * 
	 * @since 0.7.11b
	 * @return object database
	 *
	 */
	function _getDB() {
		$database = JFactory::getDBO();
		return $database;
	}

	/**
	 * Runs the DB uninstallation scripts. This is run only on uninstall, and if
	 * user selects to do so in the backend admin settings
	 *
	 * @since 0.7.11b
	 * @return true on success;
	 */
	function dbuninstall() {
		$sql1 = "DROP TABLE IF EXISTS `#__simplecal`;";
		$sql2 = "DROP TABLE IF EXISTS `#__simplecal_groups`;";
		$sql3 = "DROP TABLE IF EXISTS `#__simplecal_categories`;";
		$sql4 = "DROP TABLE IF EXISTS `#__simplecal_statuses`;";
		$sql5 = "DROP TABLE IF EXISTS `#__simplecal_settings`;";
		
		$database = SimpleCalendarUninstaller::_getDB();
		$database->setQuery($sql1);
		if ( !$database->query() ) {
			echo "Error removing DB table Simplecal";
			return false;
		}
		$database->setQuery($sql2);
		if ( !$database->query() ) {
			echo "Error removing  DB table Groups";
			return false;
		}
		$database->setQuery($sql3);
		if ( !$database->query() ) {
			echo "Error removing  DB table Categories";
			return false;
		}
		$database->setQuery($sql4);
		if ( !$database->query() ) {
			echo "Error removing DB table Statuses";
			return false;
		}
		$database->setQuery($sql5);
		if ( !$database->query() ) {
			echo "Error removing DB table Settings";
			return false;
		}
		return "* All tables removed successfully!<br />";
	}	
}


/**
 * Executes the uninstallation scripts
 * 
 * @since 0.7.11b
 * @return bool true on success
 *
 */
function com_uninstall() {

    $uninstaller = new SimpleCalendarUninstaller();
	$config =  SCOutput::config();
	
	$string = '';
	
    if ( $config->delete_on_uninstall ) {
		// run DB uninstall scripts
	    $dbuninstall = $uninstaller->dbuninstall($collation);
		if ( $dbuninstall ) {
			echo  "<h3>Dropping tables:</h3><br />";	
			echo  $dbuninstall;
			echo "<font color='green'>---> OK!</font><br />";
		} else {
			echo "<h3 style=\"color: red;\">Error during DB delete procedure...<br/></h3>";
			return false;
		}
	} else {
		$string .= "<h4>* NO tables dropped. You can delete tables on uninstall by<br />using the switch in the backend settings.</h4><br />";
	}	
	echo "<h3>SimpleCalendar removed successfully!</h3><br />";
	echo $string;
	return true;
}

?>