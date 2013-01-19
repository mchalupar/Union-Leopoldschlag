<?php
/**
 *	com_simplecalendar - a simple calendar component for Joomla
 *  Copyright (C) 2008-2010 Fabrizio Albonico
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
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');
require JPATH_COMPONENT_ADMINISTRATOR . DS . 'classes' . DS . 'upload.class.php';

class SimpleCalendarControllerEntry extends SimplecalendarController {

	function __construct() {
		parent::__construct();

		// Register Extra tasks
		$this->registerTask( 'add'  , 	'edit' );
		$this->registerTask( 'apply', 'save' );

		// sets the standard view (which is not SimpleCal in this case...)
		JRequest::setVar('view', 'calendar');
	}

	/**
	 * display the edit form
	 * @return void
	 */
	function edit() {
		JRequest::setVar( 'view', 'entry' );
		JRequest::setVar( 'layout', 'form'  );
		JRequest::setVar('hidemainmenu', 1);

		parent::display();

	}

	function gmap() {
		JRequest::setVar( 'view', 'entry' );
		JRequest::setVar( 'layout', 'gmapsearch'  );
		//		JRequest::setVar('hidemainmenu', 1);

		parent::display();

	}

	/**
	 * save a record (and redirect to main page)
	 * @return void
	 */
	function save() {

		// Check for request forgeries
		JRequest::checkToken() or die( 'Invalid Token' );

		$task = JRequest::getVar('task');

		$post = JRequest::get( 'POST' );
		$post['entryInfo'] = JRequest::getVar( 'entryInfo', '', 'post','string', JREQUEST_ALLOWRAW );
		$post['entryInfo']	= str_replace( '<br>', '<br />', $post['entryInfo'] );

		$model = $this->getModel('entry');

		if ($returnid = $model->store($post)) {
			switch ($task) {
				case 'apply':
					$link = 'index.php?option=com_simplecalendar&controller=entry&task=edit&cid[]='.$returnid;
					break;

				default:
					$link = 'index.php?option=com_simplecalendar&controller=calendar&view=calendar';
					break;
			}
			$msg = JText::_( 'RECORD_SAVED' );
		} else {
			$msg = JText::_( 'ERROR_ON_SAVE' ) . ' ' . $model->getError();
			$link = 'index.php?option=com_simplecalendar&controller=entry&task=edit&cid[]='.$returnid;
		}
		$this->setRedirect($link, $msg);
	}

	/**
	 * remove record(s)
	 * @return void
	 */
	function remove() {
		$model = $this->getModel('entry');
		if(!$model->delete()) {
			$msg = JText::_( 'ERROR_1_OR_MORE_NOT_DELETED' );
		} else {
			$msg = JText::_( 'RECORD_DELETED' );
		}

		$link = 'index.php?option=com_simplecalendar&controller=calendar&view=calendar';
		$this->setRedirect($link, $msg);
	}

	/**
	 * cancel editing a record
	 * @return void
	 */
	function cancel() {

		// Check for request forgeries
		JRequest::checkToken() or die( 'Invalid Token' );

		$msg = JText::_( 'OPERATION_ABORTED' );
		$link = 'index.php?option=com_simplecalendar&controller=calendar&view=calendar';
		$this->setRedirect($link, $msg);
	}

	function publish() {
		// preleviamo gli id da cid[] e li trasformiamo in interi
		$cid = JRequest::getVar( 'cid', array(), 'post', 'array' );
		JArrayHelper::toInteger($cid);

		$model = $this->getModel('entry');	// carichiamo il modello

		if(!$model->publish($cid, 1)) {	// se la pubblicazione da errore
			echo "<script> alert('".$model->getError(true)."'); window.history.go(-1); </script>\n";
			// scriviamo uno script che ci da errore e ritorna indietro
		}
		// altrimenti facciamo il redirect
		$msg = JText::_('PUBLISHED');
		$link = 'index.php?option=com_simplecalendar&controller=calendar&view=calendar';
		$this->setRedirect($link, $msg);
	}

	function unpublish() {
		$cid = JRequest::getVar( 'cid', array(), 'post', 'array' );
		JArrayHelper::toInteger($cid);

		$model = $this->getModel('entry');

		if(!$model->publish($cid, 0)) {
			echo "<script> alert('".$model->getError(true)."'); window.history.go(-1); </script>\n";
		}
		$msg = JText::_('UNPUBLISHED');
		$link = 'index.php?option=com_simplecalendar&controller=calendar&view=calendar';
		$this->setRedirect($link, $msg);
	}

	/**
	 * Provides the functionality of copying a calendar item
	 *
	 */
	function copyItem() {

		JRequest::setVar( 'view', 'copyitem' );
		JRequest::setVar( 'layout', 'form'  );
		//		JRequest::setVar('hidemainmenu', 1);
		$model = $this->getModel('entry');

		$view =& $this->getView('copyitem', 'html');
		$view->setModel($model);

		parent::display();

	}

	/**
	 * Provides the functionality of saving a copied calendar item
	 *
	 */
	function copyItemSave() {
		global $mainframe;
		$db = & JFactory::getDBO();

		// get the POST strings
		$cids = JRequest::getVar( 'cids', array(), 'post', 'array' );
		$date1 = JRequest::getString( 'date1', NULL, 'post', 'string');
		$date2 = JRequest::getString( 'date2', NULL, 'post', 'string');
		$date3 = JRequest::getString( 'date3', NULL, 'post', 'string');
		$from_time = JRequest::getString( 'from_time', NULL, 'post', 'string');
		$to_time = JRequest::getString( 'to_time', NULL, 'post', 'string');
		$entryName = JRequest::getString('entryName', '', 'post', 'string');
		$entryPlace = JRequest::getString('entryPlace', '', 'post', 'string');

		$date1 = JFactory::getDate($date1, 0);
		$date1 = $date1->toMySQL();
		if ( $date2 != NULL || $date2 != '' ) {
			$date2 = JFactory::getDate($date2, 0);
			$date2 = $date2->toMySQL();
		}
		if ( $date3 != NULL || $date3 != '' ) {
			$date3 = JFactory::getDate($date3, 0);
			$date3 = $date3->toMySQL();
		}
		if ( $from_time != NULL || $from_time != '') {
			$from_time = substr($from_time,0,8);
		}
		if ( $to_time != NULL || $to_time != '') {
			$to_time = substr($to_time ,0,8);
		}

		$cid = $cids[0];
		$option		= JRequest::getCmd( 'option' );

		JTable::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_simplecalendar'.DS.'tables');
		$row =& JTable::getInstance('entry', 'Table');

		$query = 'SELECT *' .
				' FROM #__simplecal ' .
				' WHERE id = ' . (int) $cid ;
		$db->setQuery($query);
		$data = $db->loadObjectList();
		$item = $data[0];

		$row->id = NULL;
		$row->categoryID = $item->categoryID;
		$row->date1 = $date1;
		$row->date2 = $date2;
		$row->date3 = $date3;
		$row->entryName = $entryName;
		$row->entryPlace = $entryPlace;
		$row->from_time = $from_time;
		$row->to_time = $to_time;
		$row->entryLatLon = $item->entryLatLon;
		$row->entryGroupID = $item->entryGroupID;
		$row->entryInfo = $item->entryInfo;
		$row->entryIsPrivate = $item->entryIsPrivate;
		$row->contactName = $item->contactName;
		$row->contactEmail = $item->contactEmail;
		$row->contactWebSite = $item->contactWebSite;
		$row->contactTelephone = $item->contactTelephone;
		$row->data = $item->data;
		$row->price = $item->price;
		$row->userid = $item->userid;
		$row->published = '1';
		$row->statusID = $item->statusID;
		$row->entryAddress = $item->entryAddress;

		if (!$row->check()) {
			JError::raiseError( 500, $row->getError() );
			return false;
		}
		if (!$row->store(true)) {
			JError::raiseError( 500, $row->getError() );
			return false;
		}

		$msg = JText::_('Item successfully copied');
		$link = 'index.php?option=com_simplecalendar&controller=calendar&view=calendar';
		$this->setRedirect($link, $msg);
	}

	/**
	 * Imports events from CSV files
	 * @since 0.8.9b
	 */
	function importEvents() {

		JRequest::setVar( 'view', 'importevents' );
		JRequest::setVar( 'layout', 'form'  );
		JRequest::setVar( 'hidemainmenu', 1 );
		$model = $this->getModel('entry');

		$view =& $this->getView('importevents', 'html');
		$view->setModel($model);

		parent::display();
	}

	/**
	 * Imports events from a CSV file and saves them to the DB
	 */
	function importEventsSave() {
		global $mainframe;
		$db =& JFactory::getDBO();
		$msg = '';
		$count = 0;
		$today = date();
		
		if( !empty($_FILES) && (
			($_FILES['csvfile']['type'] == "application/octet-stream") ||
			($_FILES['csvfile']['type'] == "application/vnd.ms-excel") ||
			($_FILES['csvfile']['type'] == "text/plain") ||
			($_FILES['csvfile']['type'] == "text/csv"))
		) {
			$filename = $_FILES['csvfile']['name'];

			$path = JPATH_SITE . '/cache/';
			if(is_writable($path)) {
				if(!move_uploaded_file($_FILES['csvfile']['tmp_name'], $path . $filename)) {
					$msg = "<span class=\"error\">".JText::_('Upload failed with error').": " . $_FILES['csvfile']['error'] . "</span><br>\n";
				} else {
					$date1_column = JRequest::getVar( 'date1', '1' );
					$date2_column = JRequest::getVar( 'date2', '2' );
					$date3_column = JRequest::getVar( 'date3', '3' );
					$time1_column = JRequest::getVar( 'time1', '4' );
					$time2_column = JRequest::getVar( 'time2', '5' );
					$eventName_column = JRequest::getVar( 'eventName', '6' );
					$eventPlace_column = JRequest::getVar( 'eventPlace', '7' );
					$category_column = JRequest::getVar( 'category', '8' );
					$delim = JRequest::getVar( 'delimiter', ';' );
					$offset = JRequest::getVar( 'record_number', 1 );
					$categoryID = JRequest::getVar( 'categoryID' );
					$dateformat = JRequest::getVar( 'dateformat' );
					$timeformat = JRequest::getVar( 'timeformat' );
					$dateseparator = JRequest::getVar( 'dateseparator' );
					$timeseparator = JRequest::getVar( 'timeseparator' );
					
					$offset = $offset - 1; //default an array starts at 0 instead of 1
					$date1_column = $date1_column - 1;
					$date2_column = $date2_column - 1;
					$date3_column = $date3_column - 1;
					$time1_column = $time1_column - 1;
					$time2_column = $time2_column - 1;
					$eventName_column = $eventName_column - 1; //default an array starts at 0 instead of 1
					$eventPlace_column = $eventPlace_column - 1; //default an array starts at 0 instead of 1
					$category_column = $category_column - 1;
					
					$content = file($path . $filename);

					if ( sizeof($content) > 0 ) {
						for ( $i = $offset; $i < sizeof($content); $i++ ) {

							$event = explode($delim, $content[$i]);

							$event[$eventName_column] = ltrim(rtrim($event[$eventName_column]));
							$event[$eventPlace_column] = ltrim(rtrim($event[$eventPlace_column]));

							$date1 = date('Y-m-d', strtotime($event[$date1_column]));
							$date2 = date('Y-m-d', strtotime($event[$date2_column]));
							$date3 = date('Y-m-d', strtotime($event[$date3_column]));
							$event[$date1_column] = ltrim(rtrim($date1));
							$event[$date2_column] = ltrim(rtrim($date2));
							$event[$date3_column] = ltrim(rtrim($date3));

							if ( $event[$time1_column] != '' ) {
								$time1 = date('H:i', strtotime($today . ' ' . $event[$time1_column]));
							} else {
								$time1 = null;
							}
							if ( $event[$time2_column] != '' ) {
								$time2 = date('H:i', strtotime($today . ' ' . $event[$time2_column]));
							} else {
								$time2 = null;
							}
							$event[$time1_column] = ltrim(rtrim($time1));
							$event[$time2_column] = ltrim(rtrim($time2));
							
							$event[$category_column] = ltrim(rtrim($event[$category_column]));
							$event[$eventName_column] = str_replace('"', '', $event[$eventName_column]);
							$event[$eventPlace_column] = str_replace('"', '', $event[$eventPlace_column]);
							$event[$date1_column] = str_replace('"', '', $event[$date1_column]);
							$event[$date2_column] = str_replace('"', '', $event[$date2_column]);
							$event[$date3_column] = str_replace('"', '', $event[$date3_column]);
							$event[$time1_column] = str_replace('"', '', $event[$time1_column]);
							$event[$time2_column] = str_replace('"', '', $event[$time2_column]);
							$event[$category_column] = str_replace('"', '', $event[$category_column]);
							
							if ( $event[$category_column] == null || $event[$category_column] == '' ) {
								$event[$category_column] = $categoryID;
							}
							
							// Formatting dates and times
							if ( $event[$date2_column] == '' || $event[$date2_column] == date('Y-m-d', strtotime('1970-01-01')) ) {
								$event[$date2_column] = null;
							}
							if ( $event[$date3_column] == '' || $event[$date3_column] == date('Y-m-d', strtotime('1970-01-01')) ) {
								$event[$date3_column] = null;
							}
							if ( $event[$time1_column] == '' || $event[$time1_column] == date('H:s', strtotime('00:00')) ) {
								$event[$time1_column] = null;
							}
							if ( $event[$time2_column] == '' || $event[$time2_column] == date('H:s', strtotime('00:00')) ) {
								$event[$time2_column] = null;
							}
							
							jimport( 'joomla.filter.output' );
							$alias = $event[$eventName_column];
							$alias = JFilterOutput::stringURLSafe($alias);
							$user 			=& JFactory::getUser();
	
							if ( !empty($event[$eventName_column]) ) {								
								$query = "REPLACE INTO #__simplecal (entryName, entryPlace, date1, date2, 
										date3, from_time, to_time, published, categoryID, entryIsPrivate,
										entryAddress, entryInfo, contactName, contactEMail, contactWebSite,
										contactTelephone, price, alias, custom1, custom2, created, modified, userid ) "; 
								$query .= " VALUES ('". addslashes($event[$eventName_column]) . "', '". addslashes($event[$eventPlace_column]) . "',"; 
								$query .= "'". addslashes((string)$event[$date1_column]) . "', ";
								
								$event[$date2_column] == null ? $query .= "null, " : $query .= "'" . addslashes((string)$event[$date2_column]) ."', ";
								$event[$date3_column]  == null ? $query .= "null, " : $query .= "'" . addslashes((string)$event[$date3_column]) ."', ";
								$event[$time1_column]  == null ? $query .= "null, " : $query .= "'" . addslashes((string)$event[$time1_column]) ."', ";
								$event[$time2_column]  == null ? $query .= "null, " : $query .= "'" . addslashes((string)$event[$time2_column]) ."', ";
								
								$query .= "'1', '". addslashes((string)$event[$category_column]) . "', '0', ";
								$query .= "'', '', '', '', '', '', '', '" . addslashes($alias) . "', '', '', NOW(), NOW(), ";
								$query .= "'" . $user->id . "');";
								
								$db->setQuery($query);
								$db->query();
								$error = $db->getErrorNum();
								
								if ( $error ) {
									if( $error == 1062 ) {
										$msg = JText::_('Event already exists'). ": ".$event['eventName'];
									} else {
										$msg = $db->getErrorMsg() . "<br />\n";
									}
								} else {
//									$msg = $datescollection . ' ' . $timescollection;
									$count++;
								}
							}
						}
					}
					else{
						$msg = JText::_('Error: empty file!');
					}

					if(!unlink($path . $filename)){
						$msg = JText::_('Error while deleting file').": ".$path ." | ". $filename;
					}
				}
			}
			else{
				$msg = JText::_('Directory not writable');
			}
		}
		else{
			$msg = JText::_('Generic error');
		}
		
		if ( $msg == '' ) {
			$msg = $count . ' ' . JText::_('events successfully imported');
		}
		$link = 'index.php?option=com_simplecalendar&controller=calendar&view=calendar';
		$this->setRedirect($link, $msg);
	}
	
}
?>