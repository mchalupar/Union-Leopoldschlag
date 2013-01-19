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
	defined('_JEXEC') or die('Restricted access');
	global $mainframe;

	JTable :: addIncludePath(JPATH_ADMINISTRATOR . DS . 'components' . DS . $option . DS . 'tables');
	require JPATH_COMPONENT . DS . 'classes' . DS . 'vcal.class.php';
	
	// new vCal object
	$vcalfile = new vCal();
	
	$vcalfile->setTimeZone($mainframe->getCfg('offset'));
	$vcalfile->setCategory($this->item->categoryName);
	$vcalfile->setSummary($this->item->entryName);
	$vcalfile->setGeoPosition(str_replace(',', ';', $this->item->entryLatLon));
	
	$description =  $this->item->entryName . " " . $this->item->entryPlace . " / " .
					$this->item->groupName . " " . $this->item->contactName;
	if ($this->item->price > 0 ) {
		$description .= " " . JText::_('Price') . " " . $this->item->price;
	}
	$vcalfile->setDescription($description);
	
	if (strtotime($this->item->from_time) != strtotime($this->item->to_time)) {
		$hours1 = intval(date("G", strtotime($this->item->from_time)));
		$mins1 	= intval(date("i", strtotime($this->item->from_time)));
		$hours2 = intval(date("G", strtotime($this->item->to_time)));
		$mins2 	= intval(date("i", strtotime($this->item->to_time)));
		
		$date1_ts = mktime( $hours1, $mins1, 0, date("n", strtotime($this->item->date1)), date("j", strtotime($this->item->date1)), date("Y", strtotime($this->item->date1))  );
				
		if (strtotime($this->item->date2)) {
			$date2_ts = mktime( $hours2, $mins2, 0, date("n", strtotime($this->item->date2)), date("j", strtotime($this->item->date2)), date("Y", strtotime($this->item->date2))  );
		} else {
			$date2_ts = mktime( $hours2, $mins2, 0, date("n", strtotime($this->item->date1)), date("j", strtotime($this->item->date1)), date("Y", strtotime($this->item->date1))  );
		}
		$has_time = 1;		
	} else {
		$date1_ts = mktime( 0, 0, 0, date("n", strtotime($this->item->date1)), date("j", strtotime($this->item->date1)), date("Y", strtotime($this->item->date1))  );
		if (strtotime($this->item->date2)) {
			$date2_ts = mktime( 0, 0, 0, date("n", strtotime($this->item->date2)), date("j", strtotime($this->item->date2))+1, date("Y", strtotime($this->item->date2))  );
		} else {
			$date2_ts = $date1_ts;
		}
		$has_time = 0;
	}
	
	$vcalfile->setDST($date1_ts);	
	
	if ($has_time) {
		$vcalfile->setStartDate($date1_ts);
		$vcalfile->setEndDate($date2_ts);
	} else {
		$vcalfile->setAllDayStart($date1_ts);
		$vcalfile->setAllDayEnd($date2_ts);
	}

	$vcalfile->setLocation($this->item->entryPlace);
	$vcalfile->setFilename('simplecal-id'.$this->item->id);
	
	$vcalfile->setHeader();
	$vcalfile->setEventBody();
	$vcalfile->setFooter();
	$vcalfile->generateHTMLvCal();
	
?>
