<?php
/**
 * $Id$
 */
// No direct access
defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.filesystem.folder' );
jimport( 'joomla.filesystem.file' );

class SimpleCalendarInstaller {

	/**
	 * returns the current DB object
	 *
	 * @since 0.6
	 * @return object database
	 *
	 */
	function _getDB() {
		$database = JFactory::getDBO();
		return $database;
	}

	/**
	 * Prints the content of a given table for debugging purposes
	 *
	 * @since 0.6b
	 * @param object $table
	 */
	function _debugDB($table) {
		echo '<br/>';
		foreach ($table as $row) {
			echo $row . '; ';
		}
		echo '<br/>';
	}

	/**
	 * Helper to print the UTF charset and collate strings to go with DB creation/upgrade queries
	 *
	 * @param string $collation
	 * @return string if UTF8, empty strings if not.
	 */
	function _isUtf8($collation) {
		$utf8 = '';
		if ( substr($collation, 0, 4) == 'utf8' ) {
			$utf8 = " CHARACTER SET `utf8` COLLATE `" . $collation . "`";
		}
		return $utf8;
	}


	/**
	 * Runs the creation DB scripts. This is run only on first install!
	 * This db is from version 0.5. Subsequent additions / changes are
	 * found under "dbupgrade"
	 *
	 * @since 0.6b
	 * @return true on success;
	 */
	function dbinstall($collation) {

		// test for UTF8
		$utf8 = SimpleCalendarInstaller::_isUtf8($collation);

		$sql1 = "CREATE TABLE `#__simplecal` (
				  `id` int(11) unsigned NOT NULL auto_increment,
				  `categoryID` int(11) default NULL,
				  `date1` date NOT NULL default '0000-00-00',
				  `date2` date default NULL,
				  `date3` date default NULL,
				  `from_time` time default NULL,
				  `to_time` time default NULL,
				  `entryName` varchar(64) NOT NULL default '',
				  `entryPlace` varchar(128) default NULL,
				  `entryLatLon` varchar(64) default NULL,
				  `entryGroupID` int(11) NOT NULL,
				  `entryInfo` varchar(128) default NULL,
				  `entryIsPrivate` int(1) default '0',
				  `contactName` varchar(64) default NULL,
				  `contactEmail` varchar(64) default NULL,
				  `contactWebSite` varchar(64) default NULL,
				  `contactTelephone` varchar(32) default NULL,
				  `data` date default NULL,
				  `published` int(1) default '1',
				  PRIMARY KEY (`id`)
				) ENGINE=MyISAM" . $utf8 . ";";

		$sql2 = "CREATE TABLE `#__simplecal_groups` (
				  `groupID` int(11) NOT NULL auto_increment,
				  `groupAbbr` varchar(6) default NULL,
				  `groupName` varchar(64) default NULL,
				  `groupLatLon` varchar(64) default NULL,
				  `groupLogo` varchar(32) default NULL,
				  `contactName` varchar(64) default NULL,
				  `contactEmail` varchar(64) default NULL,
				  `contactWebSite` varchar(64) default NULL,
				  `contactTelephone` varchar(32) default NULL,
				  `imageFile` varchar(64) default '',
				  `showAlways` int(1) default '0',
				  `catid` int(11) NOT NULL default 0,
				  `ordering` int(11) NOT NULL default 0,
				  PRIMARY KEY (`groupID`)
				) ENGINE=MyISAM" . $utf8 . ";";

		$sql3 = "CREATE TABLE `#__simplecal_categories` (
				  `categoryID` int(11) NOT NULL auto_increment,
				  `categoryName` varchar(32) NOT NULL default '',
				  `catid` int(11) NOT NULL default 0,
				  `ordering` int(11) NOT NULL default 0,
				   PRIMARY KEY (`categoryID`)
				) ENGINE=MyISAM" . $utf8 . ";";

		$sql4 = "INSERT INTO `#__simplecal_categories` (
						`categoryID`, `categoryName`, `catid`, `ordering` 
					)
					VALUES ( 
						'1', 'Sample category', '0', '1'
					);";

		$sql5 = "CREATE TABLE `#__simplecal_settings` (
				  `id` int(11) NOT NULL default '1',
				  `use_gmap` int(1) default '0',
				  `gmap_api_key` varchar(255) default '- set google api key here -',
				  `show_donation_line` int(1) default '1',
				  `date_long_format` varchar(64) default '%a %d.%m.%Y',
				  `date_short_format` varchar(64) default '%a %d.%m',
				  `time_format` varchar(64) default '%H:%M',
				  `frontend_link_color` varchar(16) default 'B8CDDC',
				  `default_ordering` varchar(16) default 'date',
				  `show_search_bar` int(1) default '1',
				  `show_only_future_events` int(1) default '1',
				  `frontend_add_gid` int(11) default '18',
				  `frontend_edit_gid` int(11) default '18',
				  `gmap_std_latlon` varchar(32) default '46,8',
				  `currency` varchar(32) default '',
				  `userid` int(11) default '0',
				   PRIMARY KEY (`id`)
				) ENGINE=MyISAM" . $utf8 . ";";

		$sql6 = "INSERT INTO `#__simplecal_settings` (
				  `id`,
				  `use_gmap`,
				  `gmap_api_key`,
				  `show_donation_line`,
				  `date_long_format`,
				  `date_short_format`,
				  `time_format`,
				  `frontend_link_color`,
				  `default_ordering`,
				  `show_search_bar`,
				  `show_only_future_events`,
				  `frontend_add_gid`,
				  `frontend_edit_gid`,
				  `gmap_std_latlon`,
				  `currency`,
				  `userid`
					)
					VALUES (
						'1', '0', '- gmap api key here -', '1', '%a %d.%m.%Y', '%a %d.%m', '%H:%M', 'B8CDDC', 'date', '1', '1', '18' ,'18', '46,9', '', '0'
					);";

		$sql7 = "CREATE TABLE `#__simplecal_statuses` (
				  `statusID` int(11) NOT NULL auto_increment,
				  `status_description` varchar(32) NOT NULL default '',
				  `status_color` varchar(16) NOT NULL default '',
				  `catid` int(11) NOT NULL default 0,
				  `ordering` int(11) NOT NULL default 0,
				   PRIMARY KEY (`statusID`)
				) ENGINE=MyISAM" . $utf8 . ";";

		$sql8 = "INSERT INTO `#__simplecal_statuses` (
						`statusID`, `status_description`, `status_color` ,`catid`, `ordering` 
					)
					VALUES ( 
						'1', 'Confirmed', '00FF00', '0', '1'
					);";


		$database = SimpleCalendarInstaller::_getDB();
		$database->setQuery($sql1);
		if ( !$database->query() ) {
			echo "Error on DB table Simplecal: " . $database->getErrorMsg();
			return false;
		}
		$database->setQuery($sql2);
		if ( !$database->query() ) {
			echo "Error on DB table Groups: " . $database->getErrorMsg();
			return false;
		}
		$database->setQuery($sql3);
		if ( !$database->query() ) {
			echo "Error on DB table Categories: " . $database->getErrorMsg();
			return false;
		}
		$database->setQuery($sql4);
		if ( !$database->query() ) {
			echo "Error on DB table Categories while loading default data: " . $database->getErrorMsg();
			return false;
		}
		$database->setQuery($sql5);
		if ( !$database->query() ) {
			echo "Error on DB table Settings: " . $database->getErrorMsg();
			return false;
		}
		$database->setQuery($sql6);
		if ( !$database->query() ) {
			echo "Error on DB table Settings while loading default data: " . $database->getErrorMsg();
			return false;
		}
		$database->setQuery($sql7);
		if ( !$database->query() ) {
			echo "Error on DB table Statuses: " . $database->getErrorMsg();
			return false;
		}
		$database->setQuery($sql8);
		if ( !$database->query() ) {
			echo "Error on DB table Statuses while loading default data: " . $database->getErrorMsg();
			return false;
		}

		return "* Tables created successfully!<br />";
	}


	/**
	 * Upgrades the DB to the latest version. This makes sure that all versions
	 * will be DB consistent.
	 *
	 * @since 0.6
	 * @return string updated tables on success, FALSE on error
	 *
	 */
	function dbupgrade($collation) {

		// test for UTF8
		$utf8 = SimpleCalendarInstaller::_isUtf8($collation);

		$database = SimpleCalendarInstaller::_getDB();
		$string = '';

		// ---------------------------------------------------------------------------
		// DB TABLE #__simplecal
		// ---------------------------------------------------------------------------
		$database->setQuery ("SHOW COLUMNS FROM #__simplecal");
		$fields = $database->loadObjectList();
		$fieldnames = array();
		$fieldtypes = array();
		foreach ($fields as $field) {
			$fieldnames[] = $field->Field;
			$fieldtypes[$field->Field] = $field->Type;
		}

		// -------- Since 0.5b
		if (!in_array('price', $fieldnames)) {
			$sql = "ALTER TABLE `#__simplecal` ADD `price` varchar(32) " . $utf8 . " default NULL;";
			$database->setQuery($sql);
			if ( $database->query() ) {
				$string .= "* column PRICE added to table SIMPLECAL<br />";
			} else {
				echo "<font color='red'>* unsuccesful on DB column PRICE</font><br />";
				SimpleCalendarInstaller::_debugDB($fieldnames);
				return false;
			}
		}
		if (!in_array('userid', $fieldnames)) {
			$sql = "ALTER TABLE `#__simplecal` ADD `userid` int(11) NOT NULL default '0';";
			$database->setQuery($sql);
			if ( $database->query() ) {
				$string .= "* column USERID added to table SIMPLECAL<br />";
			} else {
				echo "<font color='red'>* unsuccesful on DB column USERID</font><br />";
				SimpleCalendarInstaller::_debugDB($fieldnames);
				return false;
			}
		}
		if (in_array('entryInfo', $fieldnames) && $fieldtypes['entryInfo'] != 'text' ) {
			$sql = "ALTER TABLE `#__simplecal` CHANGE `entryInfo` `entryInfo` TEXT " . $utf8 . " NOT NULL default '';";
			$database->setQuery($sql);
			if ( $database->query() ) {
				$string .= "* column ENTRYINFO changed to type TEXT in table SIMPLECAL<br />";
			} else {
				echo "<font color='red'>* unsuccesful on DB column ENTRYINFO (change)</font><br />";
				SimpleCalendarInstaller::_debugDB($fieldnames);
				return false;
			}
		}
		if (in_array('contactWebSite', $fieldnames) && $fieldtypes['contactWebSite'] != 'varchar(255)' ) {
			$sql = "ALTER TABLE `#__simplecal` CHANGE `contactWebSite` `contactWebSite` varchar(255) " . $utf8 . " NOT NULL default '';";
			$database->setQuery($sql);
			if ( $database->query() ) {
				$string .= "* column CONTACTWEBSITE changed to type VARCHAR(255) in table SIMPLECAL<br />";
			} else {
				echo "<font color='red'>* unsuccesful on DB column CONTACTWEBSITE (change)</font><br />";
				SimpleCalendarInstaller::_debugDB($fieldnames);
				return false;
			}
		}
		// -------- Since 0.6b
		//		if (!in_array('attached_file', $fieldnames)) {
		//			$sql = "ALTER TABLE `#__simplecal` ADD `attached_file` varchar(64) " . $utf8 . " default NULL";
		//			$database->setQuery($sql);
		//			if ( $database->query() ) {
		//				$string .= "* column ATTACHED_FILE added to table SIMPLECAL<br />";
		//			} else {
		//				echo "<font color='red'>* unsuccesful on DB column ATTACHED_FILE</font><br />";
		//				SimpleCalendarInstaller::_debugDB($fieldnames);
		//				return false;
		//			}
		//		}
		//		if (!in_array('attached_file_description', $fieldnames)) {
		//			$sql = "ALTER TABLE `#__simplecal` ADD `attached_file_description` varchar(128) " . $utf8 . " default NULL;";
		//			$database->setQuery($sql);
		//			if ( $database->query() ) {
		//				$string .= "* column ATTACHED_FILE_DESCRIPTION added to table SIMPLECAL<br />";
		//			} else {
		//				echo "<font color='red'>* unsuccesful on DB column ATTACHED_FILE_DESCRIPTION</font><br />";
		//				SimpleCalendarInstaller::_debugDB($fieldnames);
		//				return false;
		//			}
		//		}
		if (!in_array('statusID', $fieldnames)) {
			$sql = "ALTER TABLE `#__simplecal` ADD `statusID` int(11) NOT NULL default '0';";
			$database->setQuery($sql);
			if ( $database->query() ) {
				$string .= "* column STATUSID added to table SIMPLECAL<br />";
			} else {
				echo "<font color='red'>* unsuccesful on DB column STATUSID</font><br />";
				SimpleCalendarInstaller::_debugDB($fieldnames);
				return false;
			}
		}
		//		NOTICE: No to time removed with 0.8b
		//		if (!in_array('no_to_time', $fieldnames)) {
		//			$sql = "ALTER TABLE `#__simplecal` ADD `no_to_time` int(1) NOT NULL default '0';";
		//			$database->setQuery($sql);
		//			if ( $database->query() ) {
		//				$string .= "* column NO_TO_TIME added to table SIMPLECAL<br />";
		//			} else {
		//				echo "<font color='red'>* unsuccesful on DB column NO_TO_TIME</font><br />";
		//				SimpleCalendarInstaller::_debugDB($fieldnames);
		//				return false;
		//			}
		//		}
		// -------- Since 0.7.10b
		// thanks to lostsoul
		//		if (in_array('attached_file', $fieldnames) && $fieldtypes['attached_file'] != 'varchar(255)' ) {
		//			$sql = "ALTER TABLE `#__simplecal` CHANGE `attached_file` `attached_file` varchar(255) " . $utf8 . " NOT NULL default '';";
		//			$database->setQuery($sql);
		//			if ( $database->query() ) {
		//				$string .= "* column ATTACHED_FILE changed to type VARCHAR(255) in table SIMPLECAL<br />";
		//			} else {
		//				echo "<font color='red'>* unsuccesful on DB column ATTACHED_FILE (change)</font><br />";
		//				SimpleCalendarInstaller::_debugDB($fieldnames);
		//				return false;
		//			}
		//		}
		// -------- Since 0.7.10b
		if (in_array('date1', $fieldnames)) {
			$sql1 = "ALTER TABLE `#__simplecal` ALTER COLUMN `date2`  DROP DEFAULT;";
			$sql2 = "ALTER TABLE `#__simplecal` ALTER COLUMN `date3`  DROP DEFAULT;";
			$sql3 = "ALTER TABLE `#__simplecal` ALTER COLUMN `from_time`  DROP DEFAULT;";
			$sql4 = "ALTER TABLE `#__simplecal` ALTER COLUMN `to_time`  DROP DEFAULT;";

			$database->setQuery($sql1);
			if ( $database->query() ) {
				$string .= "* DEFAULT NULL value set for column DATE2<br />";
			} else {
				echo "<font color='red'>* unsuccesful on dropping default value on column DATE2</font><br />";
				SimpleCalendarInstaller::_debugDB($fieldnames);
				return false;
			}
			$database->setQuery($sql2);
			if ( $database->query() ) {
				$string .= "* DEFAULT NULL value set for column DATE3<br />";
			} else {
				echo "<font color='red'>* unsuccesful on dropping default value on column DATE3</font><br />";
				SimpleCalendarInstaller::_debugDB($fieldnames);
				return false;
			}
			$database->setQuery($sql3);
			if ( $database->query() ) {
				$string .= "* DEFAULT NULL value set for column FROM_TIME<br />";
			} else {
				echo "<font color='red'>* unsuccesful on dropping default value on column FROM_TIME</font><br />";
				SimpleCalendarInstaller::_debugDB($fieldnames);
				return false;
			}
			$database->setQuery($sql4);
			if ( $database->query() ) {
				$string .= "* DEFAULT NULL value set for column TO_TIME<br />";
			} else {
				echo "<font color='red'>* unsuccesful on dropping default value on column TO_TIME</font><br />";
				SimpleCalendarInstaller::_debugDB($fieldnames);
				return false;
			}
		}
		// Since 0.7.12b
		if (!in_array('alias', $fieldnames)) {
			$sql = "ALTER TABLE `#__simplecal` ADD `alias` varchar(64) default NULL;";
			$database->setQuery($sql);
			if ( $database->query() ) {
				$string .= "* column ALIAS added to table SIMPLECAL<br />";
			} else {
				echo "<font color='red'>* unsuccesful on DB column ALIAS</font><br />";
				SimpleCalendarInstaller::_debugDB($fieldnames);
				return false;
			}
		}
		// Since 0.7.14b
		if (in_array('data', $fieldnames)) {
			$sql = "ALTER TABLE `#__simplecal` DROP COLUMN `data`;";
			$database->setQuery($sql);
			if ( $database->query() ) {
				$string .= "* column DATA dropped from table SIMPLECAL<br />";
			} else {
				echo "<font color='red'>* unsuccesful on dropping DB column DATA</font><br />";
				SimpleCalendarInstaller::_debugDB($fieldnames);
				return false;
			}
		}
		if (!in_array('created', $fieldnames)) {
			$sql = "ALTER TABLE `#__simplecal` ADD `created` datetime NULL default NULL;";
			$database->setQuery($sql);
			if ( $database->query() ) {
				$string .= "* column CREATED added to table SIMPLECAL<br />";
			} else {
				echo "<font color='red'>* unsuccesful on DB column CREATED</font><br />";
				SimpleCalendarInstaller::_debugDB($fieldnames);
				return false;
			}
			$sql = "UPDATE `#__simplecal` SET `created` = now() WHERE `created` IS NULL;";
			$database->setQuery($sql);
			if ( $database->query() ) {
				$string .= "* column CREATED populated with default data<br />";
			} else {
				echo "<font color='red'>* unsuccesful on DB column CREATED - populating default data</font><br />";
				SimpleCalendarInstaller::_debugDB($fieldnames);
				return false;
			}
		}
		if (!in_array('modified', $fieldnames)) {
			$sql = "ALTER TABLE `#__simplecal` ADD `modified` datetime NULL default NULL;";
			$database->setQuery($sql);
			if ( $database->query() ) {
				$string .= "* column MODIFIED added to table SIMPLECAL<br />";
			} else {
				echo "<font color='red'>* unsuccesful on DB column MODIFIED</font><br />";
				SimpleCalendarInstaller::_debugDB($fieldnames);
				return false;
			}
		}
		// Since 0.7.16b
		if (!in_array('entryAddress', $fieldnames)) {
			$sql = "ALTER TABLE `#__simplecal` ADD `entryAddress` varchar(128) default '';";
			$database->setQuery($sql);
			if ( $database->query() ) {
				$string .= "* column ENTRYADDRESS added to table SIMPLECAL<br />";
			} else {
				echo "<font color='red'>* unsuccesful on DB column ENTRYADDRESS</font><br />";
				SimpleCalendarInstaller::_debugDB($fieldnames);
				return false;
			}
		}
		if (in_array('entryName', $fieldnames) && $fieldtypes['entryName'] != 'varchar(64)' ) {
			$sql = "ALTER TABLE `#__simplecal` CHANGE `entryName` `entryName` varchar(128) " . $utf8 . " NOT NULL default '';";
			$database->setQuery($sql);
			if ( $database->query() ) {
				$string .= "* column ENTRYNAME changed to type VARCHAR(128) in table SIMPLECAL<br />";
			} else {
				echo "<font color='red'>* unsuccesful on DB column ENTRYNAME (change)</font><br />";
				SimpleCalendarInstaller::_debugDB($fieldnames);
				return false;
			}
		}
		// Since 0.8b
		if (in_array('no_to_time', $fieldnames)) {
			$sql = "ALTER TABLE `#__simplecal` DROP COLUMN  `no_to_time`;";
			$database->setQuery($sql);
			if ( $database->query() ) {
				$string .= "* column NO_TO_TIME dropped from table SIMPLECAL<br />";
			} else {
				echo "<font color='red'>* unsuccesful on DB column drop NO_TO_TIME</font><br />";
				SimpleCalendarInstaller::_debugDB($fieldnames);
				return false;
			}
		}
		// Since 0.8.5b
		if (!in_array('custom1', $fieldnames)) {
			$sql = "ALTER TABLE `#__simplecal` ADD `custom1` varchar(255) default '';";
			$database->setQuery($sql);
			if ( $database->query() ) {
				$string .= "* column CUSTOM1 added to table SIMPLECAL<br />";
			} else {
				echo "<font color='red'>* unsuccesful on DB column CUSTOM1</font><br />";
				SimpleCalendarInstaller::_debugDB($fieldnames);
				return false;
			}
		}
		if (!in_array('custom2', $fieldnames)) {
			$sql = "ALTER TABLE `#__simplecal` ADD `custom2` varchar(255) default '';";
			$database->setQuery($sql);
			if ( $database->query() ) {
				$string .= "* column CUSTOM2 added to table SIMPLECAL<br />";
			} else {
				echo "<font color='red'>* unsuccesful on DB column CUSTOM2</font><br />";
				SimpleCalendarInstaller::_debugDB($fieldnames);
				return false;
			}
		}
		if (!in_array('contactGroup', $fieldnames)) {
			$sql = "ALTER TABLE `#__simplecal` ADD `contactGroup` varchar(255) default '';";
			$database->setQuery($sql);
			if ( $database->query() ) {
				//				$string .= "* column CONTACTGROUP added to table SIMPLECAL<br />";
			} else {
				echo "<font color='red'>* unsuccesful on DB column CONTACTGROUP</font><br />";
				SimpleCalendarInstaller::_debugDB($fieldnames);
				return false;
			}
		}
		// Remove attachments column - the Attachments component is the way to attach files to SimpleCalendar.
		// Since 0.8.11b
		if ( in_array('attached_file', $fieldnames) ) {
			$sql = "ALTER TABLE `#__simplecal` DROP `attached_file`, DROP `attached_file_description` ";
			$database->setQuery($sql);
			if ( $database->query() ) {
				$string .= "* columns ATTACHED_FILE and ATTACHED_FILE_DESCRIPTION dropped from table SIMPLECAL<br />";
			} else {
				echo "<font color='red'>* unsuccesful on DROP of DB column ATTACHED_FILE or ATTACHED_FILE_DESCRIPTION</font><br />";
				SimpleCalendarInstaller::_debugDB($fieldnames);
				return false;
			}
		}
		// Add favorites icon
		// Since 0.8.12b
		if ( !in_array('is_favourite', $fieldnames) ) {
			$sql = "ALTER TABLE `#__simplecal` ADD `is_favourite` int(1) default '0'";
			$database->setQuery($sql);
			if ( $database->query() ) {
				$string .= "* column IS_FAVOURITE added to table SIMPLECAL<br />";
			} else {
				echo "<font color='red'>* unsuccesful on DB column IS_FAVOURITE</font><br />";
				SimpleCalendarInstaller::_debugDB($fieldnames);
				return false;
			}
		}

		// ---------------------------------------------------------------------------
		// DB TABLE #__simplecal_groups
		// ---------------------------------------------------------------------------
		$database->setQuery ("SHOW COLUMNS FROM #__simplecal_groups");
		$fields = $database->loadObjectList();
		$fieldnames = array();
		$fieldtypes = array();
		foreach ($fields as $field) {
			$fieldnames[] = $field->Field;
			$fieldtypes[$field->Field] = $field->Type;
		}

		// -------- Since 0.6b
		if (in_array('contactWebSite', $fieldnames) && $fieldtypes['contactWebSite'] != 'varchar(255)' ) {
			$sql = "ALTER TABLE `#__simplecal_groups` CHANGE `contactWebSite` `contactWebSite` varchar(255) " . $utf8 . " NOT NULL default '';";
			$database->setQuery($sql);
			if ( $database->query() ) {
				$string .= "* column CONTACTWEBSITE changed to type VARCHAR(255) in table SIMPLECAL_GROUPS<br />";
			} else {
				echo "<font color='red'>* unsuccesful on DB column CONTACTWEBSITE (change)</font><br />";
				SimpleCalendarInstaller::_debugDB($fieldnames);
				return false;
			}
		}
		// Since 0.7.12b
		if (!in_array('alias', $fieldnames)) {
			$sql = "ALTER TABLE `#__simplecal_groups` ADD `alias` varchar(64) default NULL;";
			$database->setQuery($sql);
			if ( $database->query() ) {
				$string .= "* column ALIAS added to table SIMPLECAL_GROUPS<br />";
			} else {
				echo "<font color='red'>* unsuccesful on DB column ALIAS</font><br />";
				SimpleCalendarInstaller::_debugDB($fieldnames);
				return false;
			}
		}

		// ---------------------------------------------------------------------------
		// DB TABLE #__simplecal_categories
		// ---------------------------------------------------------------------------
		$database->setQuery ("SHOW COLUMNS FROM #__simplecal_categories");
		$fields = $database->loadObjectList();
		$fieldnames = array();
		$fieldtypes = array();
		foreach ($fields as $field) {
			$fieldnames[] = $field->Field;
			$fieldtypes[$field->Field] = $field->Type;
		}

		// -------- Since 0.7b
		if (!in_array('category_color', $fieldnames)) {
			$sql = "ALTER TABLE `#__simplecal_categories` ADD `category_color` varchar(8) " . $utf8 . " DEFAULT NULL";
			$database->setQuery($sql);
			if ( $database->query() ) {
				$string .= "* column CATEGORY_COLOR added to table SIMPLECAL_CATEGORIES<br />";
			} else {
				echo "<font color='red'>* unsuccesful on DB column CATEGORY_COLOR</font><br />";
				SimpleCalendarInstaller::_debugDB($fieldnames);
				return false;
			}
		}

		// -------- Since 0.7.12b
		if (!in_array('alias', $fieldnames)) {
			$sql = "ALTER TABLE `#__simplecal_categories` ADD `alias` varchar(64) " . $utf8 . " DEFAULT NULL";
			$database->setQuery($sql);
			if ( $database->query() ) {
				$string .= "* column ALIAS added to table SIMPLECAL_CATEGORIES<br />";
			} else {
				echo "<font color='red'>* unsuccesful on DB column ALIAS</font><br />";
				SimpleCalendarInstaller::_debugDB($fieldnames);
				return false;
			}
		}


		// ---------------------------------------------------------------------------
		// DB TABLE #__simplecal_settings
		// ---------------------------------------------------------------------------
		$database->setQuery ("SHOW COLUMNS FROM #__simplecal_settings");
		$fields = $database->loadObjectList();
		$fieldnames = array();
		$fieldtypes = array();
		foreach ($fields as $field) {
			$fieldnames[] = $field->Field;
			$fieldtypes[$field->Field] = $field->Type;
		}

		// -------- Since 0.5b
		if (!in_array('currency', $fieldnames)) {
			//			$sql = "ALTER TABLE `#__simplecal_settings` ADD `currency` varchar(32) " . $utf8 . " DEFAULT '';";
			$database->setQuery($sql);
			if ( $database->query() ) {
				$string .= "* column CURRENCY added to table SIMPLECAL_SETTINGS<br />";
			} else {
				echo "<font color='red'>* unsuccesful on DB column CURRENCY</font><br />";
				SimpleCalendarInstaller::_debugDB($fieldnames);
				return false;
			}
		}
		if (in_array('gmap_api_key', $fieldnames) && $fieldtypes['gmap_api_key'] != 'varchar(255)' ) {
			$sql = "ALTER TABLE `#__simplecal_settings` CHANGE `gmap_api_key` `gmap_api_key` varchar(255) " . $utf8 . " NOT NULL default '';";
			$database->setQuery($sql);
			if ( $database->query() ) {
				$string .= "* column GMAP_API_KEY changed to type VARCHAR(255) in table SIMPLECAL_SETTINGS<br />";
			} else {
				echo "<font color='red'>* unsuccesful on DB column GMAP_API_KEY (change)</font><br />";
				SimpleCalendarInstaller::_debugDB($fieldnames);
				return false;
			}
		}
		if ( !in_array('allow_owner_edit', $fieldnames) ) {
			$sql = "ALTER TABLE `#__simplecal_settings` ADD `allow_owner_edit` int(1) DEFAULT '1';";
			$database->setQuery($sql);
			if ( $database->query() ) {
				$string .= "* column ALLOW_OWNER_EDIT added to table SIMPLECAL_SETTINGS<br />";
			} else {
				echo "<font color='red'>* unsuccesful on DB column ALLOW_OWNER_EDIT</font><br />";
				SimpleCalendarInstaller::_debugDB($fieldnames);
				return false;
			}
		}
		if ( !in_array('use_jcomments', $fieldnames) ) {
			$name = 'use_jcomments';
			$sql = "ALTER TABLE `#__simplecal_settings` ADD `" . $name . "` int(1) DEFAULT '0';";
			$database->setQuery($sql);
			if ( $database->query() ) {
				$string .= "* column " . strtoupper($name) . " added to table SIMPLECAL_SETTINGS<br />";
			} else {
				echo "<font color='red'>* unsuccesful on DB column " . strtoupper($name) . "</font><br />";
				SimpleCalendarInstaller::_debugDB($fieldnames);
				return false;
			}
		}
		// -------- Since 0.7b
		if (!in_array('show_category_color', $fieldnames)) {
			$sql = "ALTER TABLE `#__simplecal_settings` ADD `show_category_color` int(1) DEFAULT '0'";
			$database->setQuery($sql);
			if ( $database->query() ) {
				$string .= "* column SHOW_CATEGORY_COLOR added to table SIMPLECAL_SETTINGS<br />";
			} else {
				echo "<font color='red'>* unsuccesful on DB column SHOW_CATEGORY_COLOR</font><br />";
				SimpleCalendarInstaller::_debugDB($fieldnames);
				return false;
			}
		}
		//		if (!in_array('allow_file_upload', $fieldnames)) {
		//			$sql = "ALTER TABLE `#__simplecal_settings` ADD `allow_file_upload` int(1) DEFAULT '0'";
		//			$database->setQuery($sql);
		//			if ( $database->query() ) {
		//				$string .= "* column ALLOW_FILE_UPLOAD added to table SIMPLECAL_SETTINGS<br />";
		//			} else {
		//				echo "<font color='red'>* unsuccesful on DB column ALLOW_FILE_UPLOAD</font><br />";
		//				SimpleCalendarInstaller::_debugDB($fieldnames);
		//				return false;
		//			}
		//		}
		if (!in_array('show_time', $fieldnames)) {
			$sql = "ALTER TABLE `#__simplecal_settings` ADD `show_time` int(1) DEFAULT '0'";
			$database->setQuery($sql);
			if ( $database->query() ) {
				$string .= "* column SHOW_TIME added to table SIMPLECAL_SETTINGS<br />";
			} else {
				echo "<font color='red'>* unsuccesful on DB column SHOW_TIME</font><br />";
				SimpleCalendarInstaller::_debugDB($fieldnames);
				return false;
			}
		}
		if (!in_array('pdf_columns', $fieldnames)) {
			$sql = "ALTER TABLE `#__simplecal_settings` ADD `pdf_columns` varchar(255) " . $utf8 . " DEFAULT 'date, entryName, entryPlace';";
			$database->setQuery($sql);
			if ( $database->query() ) {
				$string .= "* column PDF_COLUMNS added to table SIMPLECAL_SETTINGS<br />";
			} else {
				echo "<font color='red'>* unsuccesful on DB column PDF_COLUMNS</font><br />";
				SimpleCalendarInstaller::_debugDB($fieldnames);
				return false;
			}
		}
		if (!in_array('frontend_columns', $fieldnames)) {
			$sql = "ALTER TABLE `#__simplecal_settings` ADD `frontend_columns` varchar(255) " . $utf8 . " DEFAULT 'category_color, date, entryName, entryPlace';";
			$database->setQuery($sql);
			if ( $database->query() ) {
				$string .= "* column FRONTEND_COLUMNS added to table SIMPLECAL_SETTINGS<br />";
			} else {
				echo "<font color='red'>* unsuccesful on DB column FRONTEND_COLUMNS</font><br />";
				SimpleCalendarInstaller::_debugDB($fieldnames);
				return false;
			}
		}
		if (!in_array('show_headers', $fieldnames)) {
			$sql = "ALTER TABLE `#__simplecal_settings` ADD `show_headers` int(1) DEFAULT '0'";
			$database->setQuery($sql);
			if ( $database->query() ) {
				$string .= "* column SHOW_HEADERS added to table SIMPLECAL_SETTINGS<br />";
			} else {
				echo "<font color='red'>* unsuccesful on DB column SHOW_HEADERS</font><br />";
				SimpleCalendarInstaller::_debugDB($fieldnames);
				return false;
			}
		}
		if (!in_array('show_status_color', $fieldnames)) {
			$sql = "ALTER TABLE `#__simplecal_settings` ADD `show_status_color` int(1) DEFAULT '1'";
			$database->setQuery($sql);
			if ( $database->query() ) {
				$string .= "* column SHOW_STATUS_COLOR added to table SIMPLECAL_SETTINGS<br />";
			} else {
				echo "<font color='red'>* unsuccesful on DB column SHOW_STATUS_COLOR</font><br />";
				SimpleCalendarInstaller::_debugDB($fieldnames);
				return false;
			}
		}
		if (!in_array('frontend_auto_publish', $fieldnames)) {
			$sql = "ALTER TABLE `#__simplecal_settings` ADD `frontend_auto_publish` int(1) DEFAULT '1'";
			$database->setQuery($sql);
			if ( $database->query() ) {
				$string .= "* column FRONTEND_AUTO_PUBLISH added to table SIMPLECAL_SETTINGS<br />";
			} else {
				echo "<font color='red'>* unsuccesful on DB column FRONTEND_AUTO_PUBLISH</font><br />";
				SimpleCalendarInstaller::_debugDB($fieldnames);
				return false;
			}
		}
		if (!in_array('map_slider_open', $fieldnames)) {
			$sql = "ALTER TABLE `#__simplecal_settings` ADD `map_slider_open` int(1) DEFAULT '0'";
			$database->setQuery($sql);
			if ( $database->query() ) {
				$string .= "* column MAP_SLIDER_OPEN added to table SIMPLECAL_SETTINGS<br />";
			} else {
				echo "<font color='red'>* unsuccesful on DB column MAP_SLIDER_OPEN</font><br />";
				SimpleCalendarInstaller::_debugDB($fieldnames);
				return false;
			}
		}
		if (!in_array('reverse_sort_order', $fieldnames)) {
			$sql = "ALTER TABLE `#__simplecal_settings` ADD `reverse_sort_order` int(1) DEFAULT '0'";
			$database->setQuery($sql);
			if ( $database->query() ) {
				$string .= "* column REVERSE_SORT_ORDER added to table SIMPLECAL_SETTINGS<br />";
			} else {
				echo "<font color='red'>* unsuccesful on DB column REVERSE_SORT_ORDER</font><br />";
				SimpleCalendarInstaller::_debugDB($fieldnames);
				return false;
			}
		}
		if (!in_array('show_username', $fieldnames)) {
			$sql = "ALTER TABLE `#__simplecal_settings` ADD `show_username` int(1) DEFAULT '0'";
			$database->setQuery($sql);
			if ( $database->query() ) {
				$string .= "* column SHOW_USERNAME added to table SIMPLECAL_SETTINGS<br />";
			} else {
				echo "<font color='red'>* unsuccesful on DB column SHOW_USERNAME</font><br />";
				SimpleCalendarInstaller::_debugDB($fieldnames);
				return false;
			}
		}
		if (!in_array('intro_text', $fieldnames)) {
			$sql = "ALTER TABLE `#__simplecal_settings` ADD `intro_text` TEXT DEFAULT ''";
			$database->setQuery($sql);
			if ( $database->query() ) {
				$string .= "* column INTRO_TEXT added to table SIMPLECAL_SETTINGS<br />";
			} else {
				echo "<font color='red'>* unsuccesful on DB column INTRO_TEXT</font><br />";
				SimpleCalendarInstaller::_debugDB($fieldnames);
				return false;
			}
		}
		if ( in_array('allow_file_upload', $fieldnames) ) {
			$sql = "ALTER TABLE `#__simplecal_settings` DROP COLUMN `allow_file_upload`";
			$database->setQuery($sql);
			if ( $database->query() ) {
				$string .= "* column ALLOW_FILE_UPLOAD dropped from table SIMPLECAL_SETTINGS<br />";
			} else {
				echo "<font color='red'>* unsuccesful on DROP of DB column ALLOW_FILE_UPLOAD</font><br />";
				SimpleCalendarInstaller::_debugDB($fieldnames);
				return false;
			}
		}
		//		Removed from 0.7.17b
		//		-------------------------------------------------------------------------------------------------
		//		if (!in_array('rich_text_entryinfo', $fieldnames)) {
		//			$sql = "ALTER TABLE `#__simplecal_settings` ADD `rich_text_entryinfo` int(1) DEFAULT '1'";
		//			$database->setQuery($sql);
		//			if ( $database->query() ) {
		//				$string .= "* column RICH_TEXT_ENTRYINFO added to table SIMPLECAL_SETTINGS<br />";
		//			} else {
		//				echo "<font color='red'>* unsuccesful on DB column RICH_TEXT_ENTRYINFO</font><br />";
		//				SimpleCalendarInstaller::_debugDB($fieldnames);
		//				return false;
		//			}
		//		}
		if (in_array('rich_text_entryinfo', $fieldnames)) {
			$sql = "ALTER TABLE `#__simplecal_settings` DROP COLUMN `rich_text_entryinfo`";
			$database->setQuery($sql);
			if ( $database->query() ) {
				$string .= "* column RICH_TEXT_ENTRYINFO dropped from table SIMPLECAL_SETTINGS<br />";
			} else {
				echo "<font color='red'>* unsuccesful on DB column DROP RICH_TEXT_ENTRYINFO</font><br />";
				SimpleCalendarInstaller::_debugDB($fieldnames);
				return false;
			}
		}
		if (!in_array('delete_on_uninstall', $fieldnames)) {
			$sql = "ALTER TABLE `#__simplecal_settings` ADD `delete_on_uninstall` int(1) DEFAULT '0'";
			$database->setQuery($sql);
			if ( $database->query() ) {
				$string .= "* column DELETE_ON_UNINSTALL added to table SIMPLECAL_SETTINGS<br />";
			} else {
				echo "<font color='red'>* unsuccesful on DB column DELETE_ON_UNINSTALL</font><br />";
				SimpleCalendarInstaller::_debugDB($fieldnames);
				return false;
			}
		}
		// Since 0.8.4b
		if (!in_array('detailview_registered_only', $fieldnames)) {
			$sql = "ALTER TABLE `#__simplecal_settings` ADD `detailview_registered_only` int(1) DEFAULT '0';";
			$database->setQuery($sql);
			if ( $database->query() ) {
				$string .= "* column DETAILVIEW_REGISTERED_ONLY added to table SIMPLECAL SETTINGS<br />";
			} else {
				echo "<font color='red'>* unsuccesful on DB column DETAILVIEW_REGISTERED_ONLY</font><br />";
				SimpleCalendarInstaller::_debugDB($fieldnames);
				return false;
			}
		}
		// Since 0.8.5b
		if (!in_array('custom1_label', $fieldnames)) {
			$sql = "ALTER TABLE `#__simplecal_settings` ADD `custom1_label` varchar(64) DEFAULT '';";
			$database->setQuery($sql);
			if ( $database->query() ) {
				$string .= "* column CUSTOM1_LABEL added to table SIMPLECAL SETTINGS<br />";
			} else {
				echo "<font color='red'>* unsuccesful on DB column CUSTOM1_LABEL</font><br />";
				SimpleCalendarInstaller::_debugDB($fieldnames);
				return false;
			}
		}
		if (!in_array('custom2_label', $fieldnames)) {
			$sql = "ALTER TABLE `#__simplecal_settings` ADD `custom2_label` varchar(64) DEFAULT '';";
			$database->setQuery($sql);
			if ( $database->query() ) {
				$string .= "* column CUSTOM2_LABEL added to table SIMPLECAL SETTINGS<br />";
			} else {
				echo "<font color='red'>* unsuccesful on DB column CUSTOM2_LABEL</font><br />";
				SimpleCalendarInstaller::_debugDB($fieldnames);
				return false;
			}
		}
		if (!in_array('show_category_in_detail', $fieldnames)) {
			$sql = "ALTER TABLE `#__simplecal_settings` ADD `show_category_in_detail` int(1) DEFAULT '1';";
			$database->setQuery($sql);
			if ( $database->query() ) {
				$string .= "* column SHOW_CATEGORY_IN_DETAIL added to table SIMPLECAL SETTINGS<br />";
			} else {
				echo "<font color='red'>* unsuccesful on DB column SHOW_CATEGORY_IN_DETAIL</font><br />";
				SimpleCalendarInstaller::_debugDB($fieldnames);
				return false;
			}
		}
		if (!in_array('vcal_encoding', $fieldnames)) {
			$sql = "ALTER TABLE `#__simplecal_settings` ADD `vcal_encoding` varchar(32) DEFAULT 'UTF-8';";
			$database->setQuery($sql);
			if ( $database->query() ) {
				$string .= "* column VCAL_ENCODING added to table SIMPLECAL SETTINGS<br />";
			} else {
				echo "<font color='red'>* unsuccesful on DB column VCAL_ENCODING</font><br />";
				SimpleCalendarInstaller::_debugDB($fieldnames);
				return false;
			}
		}
		if (!in_array('show_additionalinfo_label', $fieldnames)) {
			$sql = "ALTER TABLE `#__simplecal_settings` ADD `show_additionalinfo_label` int(1) DEFAULT '1';";
			$database->setQuery($sql);
			if ( $database->query() ) {
				$string .= "* column SHOW_ADDITIONALINFO_LABEL added to table SIMPLECAL SETTINGS<br />";
			} else {
				echo "<font color='red'>* unsuccesful on DB column SHOW_ADDITIONALINFO_LABEL</font><br />";
				SimpleCalendarInstaller::_debugDB($fieldnames);
				return false;
			}
		}
		if (!in_array('allow_unregistered_submission', $fieldnames)) {
			$sql = "ALTER TABLE `#__simplecal_settings` ADD `allow_unregistered_submission` int(1) DEFAULT '0'";
			$database->setQuery($sql);
			if ( $database->query() ) {
				$string .= "* column ALLOW_UNREGISTERED_SUBMISSION added to table SIMPLECAL_SETTINGS<br />";
			} else {
				echo "<font color='red'>* unsuccesful on DB column ALLOW_UNREGISTERED_SUBMISSION</font><br />";
				SimpleCalendarInstaller::_debugDB($fieldnames);
				return false;
			}
		}
		if (!in_array('use_recaptcha', $fieldnames)) {
			$sql = "ALTER TABLE `#__simplecal_settings` ADD `use_recaptcha` int(1) DEFAULT '0'";
			$database->setQuery($sql);
			if ( $database->query() ) {
				$string .= "* column USE_RECAPTCHA added to table SIMPLECAL_SETTINGS<br />";
			} else {
				echo "<font color='red'>* unsuccesful on DB column USE_RECAPTCHA</font><br />";
				SimpleCalendarInstaller::_debugDB($fieldnames);
				return false;
			}
		}
		if (!in_array('recaptcha_public', $fieldnames)) {
			$sql = "ALTER TABLE `#__simplecal_settings` ADD `recaptcha_public` varchar(64) DEFAULT ''";
			$database->setQuery($sql);
			if ( $database->query() ) {
				$string .= "* column RECAPTCHA_PUBLIC added to table SIMPLECAL_SETTINGS<br />";
			} else {
				echo "<font color='red'>* unsuccesful on DB column RECAPTCHA_PUBLIC</font><br />";
				SimpleCalendarInstaller::_debugDB($fieldnames);
				return false;
			}
		}
		if (!in_array('recaptcha_private', $fieldnames)) {
			$sql = "ALTER TABLE `#__simplecal_settings` ADD `recaptcha_private` varchar(64) DEFAULT ''";
			$database->setQuery($sql);
			if ( $database->query() ) {
				$string .= "* column RECAPTCHA_PRIVATE added to table SIMPLECAL_SETTINGS<br />";
			} else {
				echo "<font color='red'>* unsuccesful on DB column RECAPTCHA_PRIVATE</font><br />";
				SimpleCalendarInstaller::_debugDB($fieldnames);
				return false;
			}
		}


		// ---------------------------------------------------------------------------
		// DB TABLE #__simplecal_statuses
		// From: 0.7.5b
		// ---------------------------------------------------------------------------
		$database->setQuery("SHOW COLUMNS FROM #__simplecal_statuses");
		$fields = $database->loadObjectList();
		if ( !$fields ) {
			$sql7 = "CREATE TABLE `#__simplecal_statuses` (
				  `statusID` int(11) NOT NULL auto_increment,
				  `status_description` varchar(32) NOT NULL default '',
				  `status_color` varchar(16) NOT NULL default '',
				  `catid` int(11) NOT NULL default 0,
				  `ordering` int(11) NOT NULL default 0,
				   PRIMARY KEY (`statusID`)
				) ENGINE=MyISAM" . $utf8 . ";";

			$sql8 = "INSERT INTO `#__simplecal_statuses` (
						`statusID`, `status_description`, `status_color` ,`catid`, `ordering` 
					)
					VALUES ( 
						'1', 'Confirmed', '00FF00', '0', '0'
					);";

			$database->setQuery($sql7);
			if ( !$database->query() ) {
				echo "Error creating DB table Statuses";
				return false;
			} else {
				$string .= "* DB table Statuses created successfully<br />";
			}
			$database->setQuery($sql8);
			if ( !$database->query() ) {
				echo "Error on DB table Statuses while loading default data";
				return false;
			}
		}

		$database->setQuery ("SHOW COLUMNS FROM #__simplecal_statuses");
		$fields = $database->loadObjectList();
		$fieldnames = array();
		$fieldtypes = array();
		foreach ($fields as $field) {
			$fieldnames[] = $field->Field;
			$fieldtypes[$field->Field] = $field->Type;
		}
		// Since 0.7.12b
		if (!in_array('alias', $fieldnames)) {
			$sql = "ALTER TABLE `#__simplecal_statuses` ADD `alias` varchar(64) default NULL;";
			$database->setQuery($sql);
			if ( $database->query() ) {
				$string .= "* column ALIAS added to table SIMPLECAL_STATUSES<br />";
			} else {
				echo "<font color='red'>* unsuccesful on DB column ALIAS</font><br />";
				SimpleCalendarInstaller::_debugDB($fieldnames);
				return false;
			}
		}
		// Termination
		if ( $string == '' ) {
			$string = "* everything ok, nothing to upgrade!<br />";
		}
		return $string;
	}

	/**
	 * Prepares the file system for the directories (folders) for SimpleCalendar documents.
	 *
	 * @since 0.7b
	 * @param string $dirname
	 * @return bool true on success
	 */
	function createDir() {

		// Initialize some variables
		$string = '';
		$counterrors = 0;

		// Check for existing dirs: if they are found, then skip this step.
		if ( $imgdirexists = JFolder::exists(JPATH_SITE.'/images/simplecalendar') ) {
			$string = '* Image upload directory already exist - this step is skipped.<br />'."\n";
			return $string;
		} else {
			if ($makedir = JFolder::create( JPATH_SITE.'/images/simplecalendar')) {
				$string .= "* Directory /images/simplecalendar created.<br />";
			} else {
				$string .= "<font color='red'>* ERROR: Directory /images/simplecalendar not created.</font><br />";
				$counterrors = $counterrors + 1;
			}
			if ($makedir = JFolder::create( JPATH_SITE.'/images/simplecalendar/thumb')) {
				$string .= "* Directory /images/simplecalendar/thumb created.<br />";
			} else {
				$string .= "<font color='red'>* ERROR: Directory /images/simplecalendar/thumb not created.</font><br />";
				$counterrors = $counterrors + 1;
			}
		}
		if ( $string == '' ) {
			$string = "* everything ok, nothing to create!<br />";
		} else if ( $counterrors > 0 ) {
			$string .= "<br /><font color = 'red'>WARNING: 1 or more directories could not be created.<br/>Please create the missing directories by hand and set write permissions.</font><br />";
		}
		return $string;
	}

	/**
	 * Install JoomFish content element files
	 *
	 * @since 0.7.15b
	 */
	function installJoomfishCE() {
		$string = '';
		echo "<h3>Installing Joomfish content element files...</h3><br />";
		$basedir_sc = JPATH_ADMINISTRATOR.DS.'components'.DS.'com_simplecalendar'.DS.'contentelements';
		$targetdir_jf = JPATH_ADMINISTRATOR.DS.'components'.DS.'com_joomfish'.DS.'contentelements';
		if ( JFile::exists($targetdir_jf.DS.'simplecal.xml') ) {
			JFile::delete($targetdir_jf.DS.'simplecal.xml');
			$string = '* simplecal.xml deleted<br />';
		}
		if ( !JFile::move( $basedir_sc.DS.'simplecal.xml', $targetdir_jf.DS. 'simplecal.xml' ) )  {
			$string .= "<font color='red'>---> Error while renaming the content element item SIMPLECAL!</font><br />";
		}
		//		------------------------------
		if ( JFile::exists($targetdir_jf.DS.'simplecal_groups.xml') ) {
			JFile::delete($targetdir_jf.DS.'simplecal_groups.xml');
			$string .= '* simplecal_groups.xml deleted<br />';
		}
		if ( !JFile::move( $basedir_sc.DS.'simplecal_groups.xml', $targetdir_jf.DS.'simplecal_groups.xml' ) )  {
			$string .= "<font color='red'>---> Error while renaming the content element item SIMPLECAL_GROUPS!</font><br />";
		}
		//		------------------------------
		if ( JFile::exists($targetdir_jf.DS.'simplecal_categories.xml') ) {
			JFile::delete($targetdir_jf.DS.'simplecal_categories.xml');
			$string .= '* simplecal_categories.xml deleted<br />';
		}
		if ( !JFile::move( $basedir_sc.DS.'simplecal_categories.xml', $targetdir_jf.DS.'simplecal_categories.xml' ) )  {
			$string .= "<font color='red'>---> Error while renaming the content element item SIMPLECAL_CATEGORIES!</font><br />";
		}
		//		------------------------------
		if ( JFile::exists($targetdir_jf.DS.'simplecal_statuses.xml') ) {
			JFile::delete($targetdir_jf.DS.'simplecal_statuses.xml');
			$string .=  '* simplecal_statuses.xml deleted<br />';
		}
		if ( !JFile::move( $basedir_sc.DS.'simplecal_statuses.xml', $targetdir_jf.DS.'simplecal_statuses.xml' ) )  {
			$string .= "<font color='red'>---> Error while renaming the content element item SIMPLECAL_STATUSES!</font><br />";
		}
		//		------------------------------
		if ( JFile::exists($targetdir_jf.DS.'simplecal_settings.xml') ) {
			JFile::delete($targetdir_jf.DS.'simplecal_settings.xml');
			$string .= '* simplecal_settings.xml deleted<br />';
		}
		if ( !JFile::move( $basedir_sc.DS.'simplecal_settings.xml', $targetdir_jf.DS.'simplecal_settings.xml'	) )  {
			$string .= "<font color='red'>---> Error while renaming the content element item SIMPLECAL_SETTINGS!</font><br />";
		}

		if ( !JFolder::delete( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_simplecalendar'.DS.'contentelements' ) ) {
			$string .= "<font color='red'>---> Unable to remove the contentelements folder!</font><br />";;
		}
		if ( $string == '' ) {
			echo "<font color='green'>----> OK!</font><br />";
		}
		return $string;
	}

	/**
	 * Removes JoomFish content element files from the components directory
	 * (if no Joomfish is installed)
	 *
	 * @since 0.7.15b
	 */
	function unlinkJoomfishCE() {
		$basedir_sc = JPATH_ADMINISTRATOR.DS.'components'.DS.'com_simplecalendar'.DS.'contentelements';
		JFile::delete( $basedir_sc.DS.'simplecal.xml' );
		JFile::delete( $basedir_sc.DS.'simplecal_groups.xml' );
		JFile::delete( $basedir_sc.DS.'simplecal_categories.xml');
		JFile::delete( $basedir_sc.DS.'simplecal_statuses.xml' );
		JFile::delete( $basedir_sc.DS.'simplecal_settings.xml' );
		JFolder::delete( $basedir_sc );
		return true;
	}

	function installJCommentsPlugin() {
		echo "<h3>Installing JComments Plugin file...</h3><br />";
		if ( JFile::exists(JPATH_SITE.DS.'components'.DS.'com_jcomments'.DS.'plugins'.DS.'com_simplecalendar.plugin.php') ) {
			JFile::delete(JPATH_SITE.DS.'components'.DS.'com_jcomments'.DS.'plugins'.DS.'com_simplecalendar.plugin.php');
		}
		if ( !JFile::move(
		JPATH_ADMINISTRATOR.DS.'components'.DS.'com_simplecalendar'.DS.'plugins'.DS.'com_simplecalendar.plugin.php',
		JPATH_SITE.DS.'components'.DS.'com_jcomments'.DS.'plugins'.DS.'com_simplecalendar.plugin.php'
		) )  {
			echo JPATH_SITE.DS.'components'.DS.'com_jcomments'.DS.'plugins'.DS.'com_simplecalendar.plugin.php<br/>';
			echo "<font color='red'>---> Error while moving the JComments plugin to its destination folder!</font><br />";
		} else {
			echo "<font color='green'>----> OK!</font><br />";
		}
	}
}


/**
 * Executes the installation and upgrade script
 *
 * @since 0.6b
 * @return bool true on success
 *
 */
function com_install() {

	$version = new JVersion();
	if ( (real) $version->RELEASE < 1.5 ) {
		echo "<h3 style=\"color: red;\">The 'SimpleCalendar' component is designed to only work on <b>Joomla version 1.5</b></h3>";
		return false;
	}
	$installer = new SimpleCalendarInstaller();
	$database = $installer->_getDB();
	$collation = $database->getCollation();
	//	echo "DB collation is: " . $collation . "<br/>";

	// check whether it is an installation or an upgrade
	$database->setQuery ("SHOW TABLES LIKE '%simplecal%'");
	$fields = $database->loadObjectList();
	if ( !count($fields) ) {
		// run DB install scripts if no column is found
		$dbinstall = $installer->dbinstall($collation);
		if ( $dbinstall ) {
			echo "<h3>Creating tables:</h3><br />";
			echo $dbinstall;
			echo "<font color='green'>---> OK!</font><br />";
		} else {
			echo "<h3 style=\"color: red;\">Error during DB creation procedure...<br/></h3>";
			return false;
		}
	}
	// Run DB upgrade scripts - even if we freshly install the component.
	// This updates the columns to the latest version.
	$dbupgrade = $installer->dbupgrade($collation);
	if ( $dbupgrade ) {
		echo "<h3>Updating tables:</h3><br />";
		echo $dbupgrade;
		echo "<font color='green'>----> OK!</font><br />";
	} else {
		echo "<h3 style=\"color: red;\">Error during DB upgrade procedure...<br/></h3>";
		return false;
	}

	// Run directory create scripts
	$createdir = $installer->createDir();
	if ( $createdir ) {
		echo "<h3>Creating file and folder structure...</h3><br />";
		echo $createdir;
		echo "<font color='green'>----> OK!</font><br />";
	} else {
		echo "<h3 style=\"color: red;\">Error during Create Directory procedure...<br/></h3>";
		return false;
	}

	if ( JFile::exists(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_joomfish'.DS.'joomfish.php') ) {
		echo $installer->installJoomfishCE();
	} else {
		//		$installer->unlinkJoomfishCE();
	}

	if ( JFile::exists(JPATH_SITE .DS.'components'.DS.'com_jcomments'.DS.'jcomments.php') ) {
		$installer->installJCommentsPlugin();
	}

	echo "<h3>Installation completed successfully!</h3><br />";
	return true;
}
?>