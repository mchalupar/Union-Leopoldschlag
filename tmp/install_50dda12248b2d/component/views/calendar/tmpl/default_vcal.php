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

global $mainframe;

require JPATH_COMPONENT . DS . 'classes' . DS . 'vcal.class.php';
$vcalfile = new vCal();

$vcalfile->setHeader();
$vcalfile->setCalendarTimeZone($mainframe->getCfg('offset'));

foreach($this->items as $item) {
	$vcalfile->emptyElements();
	$vcalfile->setTimeZone($mainframe->getCfg('offset'));
	$vcalfile->setCategory($item->categoryName);
	$vcalfile->setSummary($item->entryName);
	$vcalfile->setDescription($item->groupName . " " . $item->contactName . " " . strip_tags($item->entryInfo));
	$vcalfile->setModified($item->modified);
	$vcalfile->setUID($item->id);

	if (strtotime($item->from_time) != strtotime($item->to_time)) {
			
		$hours1 = intval(date("G", strtotime($item->from_time)));
		$mins1 	= intval(date("i", strtotime($item->from_time)));
		if ($item->to_time == NULL){
			$item->to_time = $item->from_time;
			$hours2 = intval(date("G", strtotime($item->to_time) + 3600));
		}
		else{
			$hours2 = intval(date("G", strtotime($item->to_time)));
		}
		$mins2 	= intval(date("i", strtotime($item->to_time)));
			
		$date1_ts = mktime( $hours1, $mins1, 0, date("n", strtotime($item->date1)), date("j", strtotime($item->date1)), date("Y", strtotime($item->date1))  );
		if (strtotime($item->date2)) {
			$date2_ts = mktime( $hours2, $mins2, 0, date("n", strtotime($item->date2)), date("j", strtotime($item->date2)), date("Y", strtotime($item->date2))  );
		} else {
			$date2_ts = mktime( $hours2, $mins2, 0, date("n", strtotime($item->date1)), date("j", strtotime($item->date1)), date("Y", strtotime($item->date1))  );
		}
		$has_time = 1;
	} else {
		$date1_ts = mktime( 0, 0, 0, date("n", strtotime($item->date1)), date("j", strtotime($item->date1)), date("Y", strtotime($item->date1))  );
		if (strtotime($item->date2)) {
			$date2_ts = mktime( 0, 0, 0, date("n", strtotime($item->date2)), date("j", strtotime($item->date2))+1, date("Y", strtotime($item->date2))  );
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

	if ($item->entryPlace != '') {
		$vcalfile->setLocation($item->entryPlace);
	}
	if ($item->entryLatLon != '') {
		$vcalfile->setGeoPosition(str_replace(',', ';', $item->entryLatLon));
	}
	if ($item->contactWebSite != '') {
		$vcalfile->setURL($item->contactWebSite);
	}
	$vcalfile->setItemId($item->id);
	$vcalfile->setCreated($item->created);
	$vcalfile->setEventBody();
}

$vcalfile->setFilename('simplecal_full');
$vcalfile->setFooter();
$vcalfile->generateHTMLvCal();
?>