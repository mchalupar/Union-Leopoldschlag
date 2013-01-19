<?php
/**
 * Handles basic output of strings
 *
 */

// --------------------------------------------------------------------------------
// Constants definitions
// --------------------------------------------------------------------------------
define('REMOVE_COPYRIGHT', false);
/* IMPORTANT INFORMATION!!
 * ==========================================================================
 * You are NOT ALLOWED to remove the copyright information ("credits").
 *
 * You are allowed to disable the "Donation" line, but in this case a small
 * donation via my site http://software.albonico.ch/ is very much appreciated.
 * Please contact me through the site for any question. Thank you very much.
 */

class SCOutput {

	// --------------------------------------------------------------------------------
	// Buttons and Icons
	// --------------------------------------------------------------------------------


	/**
	 * Returns the URI string of the page we are looking at.
	 *
	 * @return URI string
	 */
	function _getUriString() {
		//TODO: refactor this part to correct SEO/SEF behaviour - no absolute urls, only relative!
		$uri = JFactory::getURI();
		$uriString = $uri->toString();
		return $uriString;
	}

	/**
	 * Shows the button corresponding to the action.
	 * 
	 * @param $type of action
	 * @param $item to be handled (url, etc.)
	 * @param $itemid of calling item
	 * @return $html string
	 */
	function showIcon($type, $item = '', $itemid = 0) {
		$html = '';
		switch ( strtolower($type) ) {
			case 'back':
				$html = '<a href="javascript:history.back()" title="Back">';
				$html .= JHTML::_('image', 'components/com_simplecalendar/assets/images/arrow_redo.png', JText::_( 'BACK' ), array('title'=>JText::_( 'BACK' ))).'</a>';	
				break;
				
			case 'edit':
				if ( $itemid == '0' ) {
					$link = JRoute::_('index.php?option=com_simplecalendar&view=form&task=edit&catid='.$item->catslug.'&id=' . $item->slug );
				} else {
					$link = JRoute::_('index.php?option=com_simplecalendar&view=form&task=edit&catid='.$item->catslug.'&id='. $item->slug .'&Itemid=' . $itemid);
				}
				$html = '<a href="'. $link .'" title="Modify">';
				$html .= JHTML::_('image', 'components/com_simplecalendar/assets/images/date_edit.png', JText::_( 'MODIFY' ), array()).'</a>';
				break;
			
			case 'print':
				$link = JRoute::_($item);
				$html = '<a href="' . $link . '" onclick="window.open(this.href,\'win2\',\'status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=640,height=480,directories=no,location=no\'); return false;" title="' . JText::_( 'PRINT' ) . '">';
				$html .= JHTML::_('image', 'components/com_simplecalendar/assets/images/printer.png', JText::_( 'PRINT' ), array('title'=>JText::_( 'PRINT' ))).'</a>';
				break;
			
			case 'printpreview':
				$html = '<a href="#" onclick="window.print();return false;" title="Print">';
				$html .= JHTML::_('image', 'components/com_simplecalendar/assets/images/printer.png', JText::_( 'PRINT' ), array()).'</a>';
				break;
				
			case 'rss':
			case 'atom':
				$link = JRoute::_($item);
				$html = '<a href="' . $link . '" onclick="window.open(this.href,\'win2\',\'status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=640,height=480,directories=no,location=no\'); return false;" title="' .  JText::_( $type ) . '">';
				$html .= JHTML::_('image', 'images/M_images/livemarks.png', JText::_( $type ), array('title'=>JText::_( $type ))).'</a>';
				break;
				
			case 'email':
				$link = JRoute::_('index.php?option=com_mailto&tmpl=component&link='.base64_encode( SCOutput::_getUriString() ));
				$html = '<a href="' . $link . '" onclick="window.open(this.href,\'win2\',\'width=400,height=350,menubar=yes,resizable=yes\'); return false;" title="E-Mail">';
				$html .= JHTML::_('image', 'components/com_simplecalendar/assets/images/email_go.png', JText::_( 'E-Mail' ), array('title'=>JText::_( 'E-Mail' ))).'</a>';
				break;
	
			case 'pdf':
				$link = JRoute::_($item);
				$html  = '<a href="' . $link . '" onclick="window.open(this.href,\'win2\',\'status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=640,height=480,directories=no,location=no\'); return false;" title="PDF">';
				$html .= JHTML::_('image', 'images/M_images/pdf_button.png', JText::_( 'PDF' ), array('title'=>JText::_( 'PDF' ))).'</a>';
				break;
				
			case 'vcal':
				$link = JRoute::_($item);
				$html = '<a href="' . $link . '" title="vCal/iCal">';
				$html .= JHTML::_('image', 'components/com_simplecalendar/assets/images/logo_16.png', JText::_( 'vCal/iCal' ), array('title'=>JText::_( 'vCal/iCal' ))).'</a>';
				break;
				
			case 'new':
				$link = JRoute::_($item);
				$html = '<a href="'. $link .'" title="Add">';
				$html .= JHTML::_('image', 'components/com_simplecalendar/assets/images/date_add.png', JText::_( 'Add new' ), array('title'=>JText::_( 'Add new' ))).'</a>';
				break;
				
			default:	
		}
		return $html;
	}


	/**
	 * Returns a checkbox
	 * @param $name
	 * @param $value
	 * @return HTML checkbox
	 */
	function checkbox($name, $value, $javascript) {
		if ( $value == 1 ) {
			$html = "<input type=\"checkbox\" name=\"" . $name . "\" value=\"" . $value . "\" checked=\"checked\" " . $javascript  . " />";
		} else if ( $value == 0 ) {
			$html = "<input type=\"checkbox\" name=\"" . $name . "\" value=\"" . $value . "\" " . $javascript  . " />";
		} else {
			$html = JText::_('Error');
		}
		return $html;
	}

	// --------------------------------------------------------------------------------
	// Header / Footer methods (please read IMPORTANT INFORMATION text below)
	// --------------------------------------------------------------------------------

	/**
	 * Reads the component name and version from the XML installation file, and returns a string.
	 *  
	 *  IMPORTANT INFORMATION!!
	 * ==========================================================================
	 * You are NOT ALLOWED to remove the copyright information ("credits").
	 * 
	 * @return string component version;
	 */
	function _readComponentNameAndVersion() {
		$xmlFile = JPATH_COMPONENT_ADMINISTRATOR . DS . 'com_simplecalendar.xml';
		$data = JApplicationHelper::parseXMLInstallFile($xmlFile);
		$name = $data['name'];
		$version = $data['version'];
		if ( substr($version, (count($version)*-1)) == 'a' ) {
			$version .= ' - development release';
		}
		return '<small><a href="http://software.albonico.ch/" target="_blank">'. $name . ' ' . $version .'</a></small>';
	}


	/**
	 * Shows the footer (with credits and optional donation string)
	 *
	 * IMPORTANT INFORMATION!!
	 * ==========================================================================
	 * You are NOT ALLOWED to remove the copyright information ("credits").
	 *
	 * You are allowed to disable the "Donation" line, but in this case a small
	 * donation via my site http://software.albonico.ch/ is very much appreciated.
	 * Please contact me through the site for any question. Thank you very much.
	 * 
	 * http://software.albonico.ch/support-my-work
	 *
	 * @return html string
	 */
	function showFooter() {
		$html = '';
		$config = SCOutput::config();
		
		if ( !REMOVE_COPYRIGHT ) {
			$html .= '<p class="sc-footer">';
			$html .= SCOutput::_readComponentNameAndVersion();
			if ( $config->show_donation_line ) {
				$html .= '<br />'. JText::_('DONATION');
			}
			$html .= '</p>';
		}
		return $html;
	}

	// --------------------------------------------------------------------------------
	// Date manipulation methods
	// --------------------------------------------------------------------------------

	/**
	 * Returns the singular or plural of "DATE" according to the dates set.
	 *
	 * @param date $date1
	 * @param date $date2
	 * @param date $date3
	 * @return string "date" or "dates"
	 */
	function getDatesType($date1, $date2, $date3) {
		$dateText = JText :: _('Date');
		if (strtotime($date2) > strtotime($date1) || strtotime($date3) > strtotime($date1)) {
			$dateText = JText :: _('Dates');
		}
		return $dateText;
	}

	/**
	 * Returns a formatted date
	 *
	 * @param date $date1
	 * @param date $date2
	 * @param date $date3
	 * @param string $longFormat
	 * @param string $shortFormat
	 * @return date string
	 */
	function getFormattedDate($date1, $date2, $date3, $longFormat, $shortFormat) {
		$dateString = '';
		$strDate1 = $strDate2 = $strDate3 = '';
		if (strtotime($date2) >= strtotime($date1)) {
			$strDate1 = JHTML::_('date', $date1, $shortFormat, 0);
			$strDate2 = JHTML::_('date', $date2, $longFormat, 0);
			$dateString .= $strDate1 . ' - ' . $strDate2;
		} else {
			$strDate1 = JHTML::_('date', $date1, $longFormat, 0);
			$dateString = $strDate1;
		}
		if (strtotime($date3) >= strtotime($date1)) {
			$strDate3 = JHTML ::_('date', $date3, $longFormat, 0);
			$dateString .= '<br />('.JText::_('RESERVEDATE_LC').': ' . $strDate3 . ')';
		}
		return $dateString;
	}

	/**
	 * Returns a formatted time
	 *
	 * @param time $from_time
	 * @param time $to_time
	 * @param string $format
	 * @param boolean $short date format
	 * @return time string
	 * @author edited by lostsoul
	 */
	function getFormattedTime($from_time, $to_time, $format = '%H:%M', $short = false) {
		$timeString = '';
		if ( $from_time != NULL ) {
			$timeString = JHTML::_('date', $from_time, $format, 0);
			if ( $to_time != NULL ) {
				$timeString .= ($short == true) ? ('-') : (' '.JText::_('TO_TIME_LC').' ');
				$timeString .= JHTML::_('date', $to_time, $format, 0);
			}
		}
		return $timeString;
	}

	/**
	 * Counts the days between today and a date
	 *
	 * @param date $date1
	 * @param time $time1
	 * @return integer string
	 */
	function countDays($date1, $time1) {
		// catch null times
		if ($time1 == NULL) {
			$time1 = '00:00:00';
		}
		// get today date
		$date =& JFactory::getDate();
		$now  = $date->toMySQL();

		// get the date array
		$gd_today = getdate(strtotime($now));
		$gd_date1 = getdate(strtotime($date1.' '.$time1));

		// get the timestamp of the array
		$date1_ts = mktime($gd_date1['hours'], $gd_date1['minutes'], 0, $gd_date1['mon'], $gd_date1['mday'], $gd_date1['year'] );
		$today_ts = mktime($gd_today['hours'], $gd_today['minutes'], 0, $gd_today['mon'], $gd_today['mday'], $gd_today['year'] );

		// get the result (difference in seconds between today and date1, divided by # of seconds in a day
		$result = round( ($today_ts - $date1_ts) / 86400 );

		// return the result
		return $result;
	}


	// --------------------------------------------------------------------------------
	// Settings and parameter methods
	// --------------------------------------------------------------------------------

	/**
	 * Loads the configuration settings from the db, returns the object
	 *
	 * @return object $config
	 * @version 0.1
	 */
	function config() {
		$db =& JFactory::getDBO();

		$sql = 'SELECT * FROM #__simplecal_settings WHERE id = 1';
		$db->setQuery($sql);
		$config = $db->loadObject();

		return $config;
	}
	
	
	/**
	 * Returns the time span options for the list ordering.
	 * @return array of objects
	 * @since 0.7.16b
	 * 
	 */
	function getTimeSpanOptions() {
			
		$timespan = array();
		$timespan0 = array('value' => 0, 'text' => JText::_('Past'));
		$timespan1 = array('value' => 1, 'text' => JText::_('Future'));
		$timespan2 = array('value' => 2, 'text' => JText::_('Past and future'));
		
		$timespan[] = JArrayHelper::toObject($timespan0);
		$timespan[] = JArrayHelper::toObject($timespan1);
		$timespan[] = JArrayHelper::toObject($timespan2);
		
		return $timespan;
	}


	// --------------------------------------------------------------------------------
	// ComboBox helpers
	// --------------------------------------------------------------------------------

	/**
	 * returns a combo box with the groups list
	 *
	 * @return string HTML
	 */
	function getGroupsComboBox( $selected , $site) {
		$db =& JFactory::getDBO();
		$query = 'SELECT groupID AS id, groupName AS title' .
				' FROM #__simplecal_groups' .
				' ORDER BY groupName';
		$db->setQuery($query);
		$options = $db->loadObjectList();
		array_unshift($options, JHTML::_('select.option', '0', '- '.JText::_('SELECT GROUP').' -', 'id', 'title'));
		$config = SCOutput::config();
		if (!isset($showmap))
		$showmap = '0';
		if ($config->use_gmap && $config->gmap_api_key != '' ) {
			$showmap = '1';
		} else {
			$showmap = '0';
		}
		$onchangetext = "onchange=\"doAjax('$site', '".JURI::base()."', document.getElementById('entryGroupID').value, '" . JText::_('ASK_OVERWRITE_GROUP_INFO'). "', '$showmap')\"";

		return JHTML::_('select.genericlist', $options , 'entryGroupID', 'class="inputbox" size="1" ' . $onchangetext, 'id', 'title', $selected);
	}


	// --------------------------------------------------------------------------------
	// Category helpers
	// --------------------------------------------------------------------------------

	/**
	 * Shows a coloured bar in the list view. Can be either the category colour, or the status colour
	 * @param string $color
	 * @param string $title
	 * @param string $text
	 * @param string $width
	 * @return string html
	 */
	function showCategoryColor( $color, $title, $text = 'Category', $width = '5px') {
		JHTML::_('behavior.tooltip');
		$html = '<div style="height:100%; width: '.$width.'; background-color: #'.$color.';">'. JHTML::tooltip($title, JText::_($text), '', '&nbsp;') .'</div>';
		return $html;
	}

	// --------------------------------------------------------------------------------
	// File download helper
	// --------------------------------------------------------------------------------

	/**
	 * Helper to return the file size with the most appropriate size unit
	 *
	 * @since 0.7
	 * @param string $size
	 * @return string formatted size with unit
	 */
	function _getFileSize($size) {
		if ( (int) $size < 1024 ) {
			return (int) $size . ' bytes';
		} else if ( (int) $size >= 1024 && (int) $size < 1048576 ) {
			return (int) ($size/1024) . ' kB';
		} else if ( (int) $size >= 1048576 ) {
			return (int) ($size/1048576) . ' MB';
		}
		return '';
	}

	/**
	 * Returns the file Description and download link - checking the extension against its Mime Type
	 *
	 * @since 0.7
	 * @param string $file filename (incl. mime type and size info)
	 * @param string $link full path to file
	 * @param string $description the description of the file
	 * @return string $html the complete download link.
	 */
	function showFileDescription ( $file, $link, $description ) {
		$html = '';
		$filepart = explode('|', $file);
		if ( $description == '' ) {
			$html .= '<a href="'. JRoute::_($link). '">' . $filepart[0] . '</a>';
		} else {
			$html .= '<a href="'. JRoute::_($link). '">' . $description . '</a>';
		}
		$filetype = SCUpload::extensionFromMime($filepart[1]);
		$html .= ' (' . strtoupper($filetype) . ', ' . SCOutput::_getFileSize($filepart[2]) . ')';
		return $html;
	}

	// --------------------------------------------------------------------------------
	// Frontend list view - column helper functions
	// --------------------------------------------------------------------------------

	/**
	 * Decodes the column specifier in the columns list
	 *
	 * @since 0.7.4
	 * @param string $field (can be 'date' or 'time')
	 * @param object $array item
	 * @return string html
	 */
	function decodeColumns($field, $array) {
		$config = SCOutput::config();
		if (!isset($html)) {
			$html = '';
		}
		
		$user = JFactory::getUser($array->userid);

		switch ($field) {
			case 'category_color':
				$html .= SCOutput::showCategoryColor($array->category_color, $array->categoryName, 'Category');
				break;
			case 'date':
				$html .= SCOutput::getFormattedDate($array->date1, $array->date2, $array->date3, $config->date_long_format, $config->date_short_format );
				break;
			case 'time':
				$html .= SCOutput::getFormattedTime($array->from_time, $array->to_time, $config->time_format, TRUE);
				break;
			case 'from_time':
				if ($array->from_time != '') {
					$html .= JHTML::_('date', $array->from_time, $config->time_format, 0);
				}
				break;
			case 'to_time':
				if ($array->to_time != '') {
					$html .= JHTML::_('date', $array->to_time, $config->time_format, 0);
				}
				break;
			case 'date1':
				if ($array->date1 != '') {
					$html .= JHTML::_('date', $array->date1, $config->date_long_format, 0);
				}
				break;
			case 'date2':
				if ($array->date2 != '') {
					$html .= JHTML::_('date', $array->date2, $config->date_long_format, 0);
				}
				break;
			case 'date3':
				if ($array->date3 != '') {
					$html .= JHTML::_('date', $array->date3, $config->date_long_format, 0);
				}
				break;
			case 'price':
				if ( $config->currency != '' && $array->price != '' ) {
					$html .= $config->currency . " " . $array->price;
				}
				break;
			case 'entryName':
				$link = JRoute::_( 'index.php?view=detail&id='. $array->id );
				$html .= $array->entryName . '<a href="'.$link.'"></a>';
				// add unpublished / private event icons for logged-in users
				if ( $array->published == '0' ) {
					$html .= '&nbsp;' . JHTML::_(
						'image',
						'components/com_simplecalendar/assets/images/delete.png',
						JText::_( 'This event is unpublished' ),
						array('title' => JText::_( 'This event is unpublished' ))
					);
				}
				if ( $array->entryIsPrivate == '1' ) {
					$html .= '&nbsp;' . JHTML::_(
						'image',
						'components/com_simplecalendar/assets/images/group_key.png',
						JText::_( 'This event is private' ),
						array('title' => JText::_( 'This event is private' ))
					);
				}
				break;
			case 'status':
				$html .= '<small><span style="color:#'. $array->status_color . '">';
				$html .= $array->status_description;
				$html .= '</span></small>';
				break;
			case 'name':
				$html .= $user->name;
				break;
			case 'username':
				$html .= $user->username;
				break;
			case 'attachment_old':
				$html .= 'Not available anymore';
			case 'attachment':
				if ( file_exists(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_attachments'.DS.'admin.attachments.php') ) {
					$db	=& JFactory::getDBO();
					$currentUser = JFactory::getUser();
					$query = "SELECT id FROM #__attachments WHERE parent_type = 'com_simplecalendar' AND parent_id = " . $array->id ;
					if ( $currentUser->guest ) {
						$query .= " AND published = '1'";
					} else {
						if ( $currentUser->gid < 23 ) {
							$query .= " AND uploader_id = " . (int) $user->id;
						}
					}
					$db->setQuery( $query );
					$data = $db->loadObject();
					if ( sizeof($data) > 0 ) {
						$html .= JHTML::_('image', 'components/com_simplecalendar/assets/images/attach.png', JText::_( 'Attachment' ), array('title'=>JText::_( 'Attachment' )));
					}
				}
				break;
			case 'status_color':
				$html .= SCOutput::showCategoryColor($array->status_color, $array->status_description, 'Status');
				break;
			case 'custom1':
				if ( $config->custom1_label != '' ) {
					$html .= $array->custom1;
				} 
				break;
			case 'custom2':
				if ( $config->custom2_label != '' ) {
					$html .= $array->custom2;
				} 
				break;
			case 'is_favourite':
				if ( $array->is_favourite == 1 ) {
					$html .= JHTML::_('image', 'components/com_simplecalendar/assets/images/star_on.png', JText::_( 'Favourite' ), array('title'=>JText::_( 'Favourite' )));
				} else {
//					$html .= JHTML::_('image', 'components/com_simplecalendar/assets/images/star_off.png', JText::_( 'Favourite' ), array('title'=>JText::_( 'Favourite' )));
				}
				break;
			default:
				if ( array_key_exists((string)$field, get_object_vars($array)) ) {
					if ( $field == 'date1' || $field == 'date2' || $field == 'date3' ) {
						$html .= JHTML::_('date', $array->$field, $config->date_long_format, 0);
					} else if ( $field == 'from_time' || $field == 'to_time' )  {
						if ( $array->$field != '00:00:00' || $array->$field != '' ) {
							$html .= JHTML::_('date', $array->$field, $config->time_format, 0);
						}
					} else {
						$html .= $array->$field;
					}
				} else {
					$html .= "Error!\n";
				}
				break;
		}
		return $html;
	}
	
	/**
	 * Shows the categories and their colours on the frontend calendar list view.
	 * 
	 * @since 0.7.11b
	 * @return html data
	 */
	function showCategoryColors($categories) {
		$html = '';
		$i = 0;
		// if ( sizeof($categories) != 0 ) {
		$html .= JText::_('CATEGORIESTEXT') . ': ';
		foreach ( $categories as $category ) {
			$link = JRoute::_( 'index.php?view=calendar&catid='. $category->catslug );
			$html .= '<a href="' . $link . '" style="color:#'.$category->category_color.'">' . $category->categoryName . '</a>';
			if ( $i < sizeof($categories)-1 ) {
				$html .= ' | ';
			}
			$i++;
		}
		//} //endif
		return $html;
	}
	
	// --------------------------------------------------------------------------------
	// Checking for updates
	// --------------------------------------------------------------------------------
	function getLatestVersion($currentVersion) {
		//TODO
	}
	
}
?>