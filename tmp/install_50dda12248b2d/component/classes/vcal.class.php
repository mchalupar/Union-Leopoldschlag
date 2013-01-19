<?php
/**
 * @version 0.2 $Id: vcal.class.php,v 1.4 2010/01/28 01:05:11 fabrizio Exp $
 * @package Joomla
 * @subpackage SimpleCalendar
 * @copyright (C) 2008-2009 Fabrizio Albonico
 * @license GNU/GPL, see LICENSE.php
 * EventList is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License 3
 * as published by the Free Software Foundation.

 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.

 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );


/**
 * offers vcal functions
 *
 * @package Joomla
 * @subpackage SimpleCalendar
 * @author Fabrizio Albonico
 * @since 0.2
 */
class vCal {
	// Documentation: http://tools.ietf.org/html/rfc2445, http://tools.ietf.org/html/rfc5545
	// Testing: http://icalvalid.cloudapp.net/Default.aspx

	var $elements;
	var $filename;

	// Cleans up the elements before next use
	function emptyElements() {
		$this->elements['TIMEZONE'] = '';
		$this->elements['SUMMARY'] = '';
		$this->elements['CATEGORY'] = '';
		$this->elements['DESCRIPTION'] = '';
		$this->elements['STARTDATE'] = '';
		$this->elements['ENDDATE'] = '';
		$this->elements['ALLDAYSTART'] = '';
		$this->elements['ALLDAYEND'] = '';
		$this->elements['LOCATION'] = '';
		$this->elements['MODIFIED'] = '';
		$this->elements['GEO'] = '';
		$this->elements['URL'] = '';
		$this->nl = null;
	}

	// sets the timezone
	function setTimeZone($timezone) {
		$this->elements['TIMEZONE'] = 0-$timezone;
	}

	// sets daylight savings time (if applicable)
	function setDST($date) {
		$dst = date("I", $date);
		if ($dst) {
			$this->elements['TIMEZONE'] = $this->elements['TIMEZONE'] - 1;
		}
	}

	function setCalendarTimeZone($timezone) {
		$timezone = timezone_name_from_abbr("", $timezone * 3600, 0);
		$this->elements['OUTPUT'] .= "X-WR-TIMEZONE:".$timezone."\r\n";
	}

	// sets the event summary
	function setSummary($summary) {
		$this->elements['SUMMARY'] = $this->quoted_printable_encode($summary);
	}

	// sets the event description
	function setDescription($description) {
		$this->elements['DESCRIPTION'] = $this->quoted_printable_encode(vCal::_size75($description));
	}

	// sets the event category
	function setCategory($category) {
		$this->elements['CATEGORY'] = $this->quoted_printable_encode($category);
	}

	function setGeoPosition($latlon) {
		$this->elements['GEO'] = $this->quoted_printable_encode($latlon);
	}

	function setURL($url) {
		$this->elements['URL'] = $url;
	}

	function setItemId($itemId) {
		$this->elements['itemId'] = $itemId;
	}

	function setModified($modified) {
		if ( $modified != '' ) {
			$modified = str_replace("-", "", $modified);
			$modified = str_replace(" ", "T", $modified);
			$modified = str_replace(":", "", $modified);
			$modified .= "Z";
			$this->elements['LAST-MODIFIED'] = $modified;
		} else {
			$this->elements['LAST-MODIFIED'] = '';
		}
	}

	function setCreated($created) {
		$this->elements['CREATED'] = $created;
	}

	// sets the start date
	function setStartDate($startDate) {
		$l_startDate = $startDate + ($this->elements['TIMEZONE'] * 60 * 60);
		$this->elements['STARTDATE'] = date("Ymd\THi00", $l_startDate).'Z';
		return;
	}

	// sets the end date
	function setEndDate($endDate) {
		$l_endDate = $endDate + ($this->elements['TIMEZONE'] * 60 * 60);
		$this->elements['ENDDATE'] = date("Ymd\THi00", $l_endDate).'Z';
	}

	// sets the start date if no start time is selected (all-day events)
	function setAllDayStart($startDate) {
		$this->elements['ALLDAYSTART'] = ';VALUE=DATE:'. date("Ymd", $startDate);
	}

	// sets the end date if no end time is selected (all-day events)
	function setAllDayEnd($endDate) {
		$this->elements['ALLDAYEND'] = ';VALUE=DATE:'. date("Ymd", $endDate);
	}

	// sets the location of the event
	function setLocation($location) {
		$this->elements['LOCATION'] = $this->quoted_printable_encode($location);
	}

	// sets the name of the generated ICS file
	function setFilename($filename) {
		$this->filename = $this->quoted_printable_encode($filename);
	}

	// sets the header for output
	function setHeader() {
		$this->elements['OUTPUT'] = "BEGIN:VCALENDAR\r\nVERSION:2.0\r\nMETHOD:PUBLISH\r\nPRODID:SimpleCalendar\r\n";
	}

	function setUID($uid) {
		$this->elements['UID'] = $this->quoted_printable_encode($uid);
	}

	// sets the event body for output
	function setEventBody() {
		global $mainframe;
		$created = str_replace(" ", "", $this->elements['CREATED']);
		$siteName = str_replace(" ", "", JFactory::getConfig()->getValue('config.sitename') );
		$this->elements['OUTPUT'] .= "BEGIN:VEVENT\r\nUID:".$this->elements['UID']."@simplecalendar-".$this->quoted_printable_encode($mainframe->getCfg( 'sitename' ))."\r\n";
		$this->elements['OUTPUT'] .= "CATEGORIES:".$this->elements['CATEGORY']."\r\n";
		$this->elements['OUTPUT'] .= "CLASS:PUBLIC\r\n";
		$this->elements['OUTPUT'] .= "DTSTAMP:".date("Ymd\THis", time()).'Z'."\r\n";
		$this->elements['OUTPUT'] .= "CREATED:".date("Ymd\THis", time()).'Z'."\r\n";
		$this->elements['OUTPUT'] .= "SUMMARY:".$this->elements['SUMMARY']."\r\n";
		$this->elements['OUTPUT'] .= "DESCRIPTION:".$this->elements['DESCRIPTION'] . "\r\n";
		$this->elements['OUTPUT'] .= "LOCATION:".$this->elements['LOCATION']."\r\n";
		if ( $this->elements['LAST-MODIFIED'] != '' ) {
			$this->elements['OUTPUT'] .= "LAST-MODIFIED:".$this->elements['LAST-MODIFIED']."\r\n";
		}
		if ($this->elements['GEO'] != '') {
			$this->elements['OUTPUT'] .= "GEO:".$this->elements['GEO']."\r\n";
		}
		if ($this->elements['URL'] != '') {
			$this->elements['OUTPUT'] .= "URL:".$this->elements['URL']."\r\n";
		}
		$this->elements['OUTPUT'] .= "TRANSP:OPAQUE\r\n";
		if ($this->elements['ALLDAYSTART'] == '') {
			$this->elements['OUTPUT'] .= "DTSTART:".$this->elements['STARTDATE']."\r\n";
		} else {
			$this->elements['OUTPUT'] .= "DTSTART".$this->elements['ALLDAYSTART']."\r\n";
		}
		if ($this->elements['ALLDAYEND'] == '') {
			$this->elements['OUTPUT'] .= "DTEND:".$this->elements['ENDDATE']."\r\n";
		} else {
			$this->elements['OUTPUT'] .= "DTEND".$this->elements['ALLDAYEND']."\r\n";
		}
		$this->elements['OUTPUT'] .= "END:VEVENT\r\n";
	}

	// sets the event footer for output
	function setFooter() {
		$this->elements['OUTPUT'] .= "END:VCALENDAR";
	}

	// generates the HTML data for our vCal/iCal
	// ical validator: http://severinghaus.org/projects/icv/
	function generateHTMLvCal() {
		global $mainframe;
		
		$params = SCOutput::config();
		$encoding = $params->vcal_encoding;
		
		header( 'Content-Type: text/calendar; charset=' . strtolower($encoding));
		header( 'Content-Disposition: inline; filename=' . $this->filename . '.ics');
		if ( strtoupper($encoding) != 'UTF-8' ) {
			$this->elements['OUTPUT'] = iconv("UTF-8", $encoding, $this->elements['OUTPUT']);
		} 
		echo $this->elements['OUTPUT'];

		$mainframe->close();

	}

	/**
	 * Reduces strings to 75 char and adds a " " before every new line, as recommended by the standard.
	 * @param $string
	 * @return string reduced to 75 char
	 * @author Kjell-Inge Gustafsson
	 */
	function _size75( $string ) {
		$strlen = strlen( $string );
		$tmp    = $string;
		$string = null;
		while( $strlen > 75 ) {
			$breakAtChar = 75;
			if( substr( $tmp, ( $breakAtChar - 1 ), strlen( '\n' )) == '\n' ) {
				$breakAtChar = $breakAtChar - 1;
			}
			$string .= substr( $tmp, 0, $breakAtChar );
			$string .= $this->nl;
			$tmp     = ' '.substr( $tmp, $breakAtChar );
			$strlen  = strlen( $tmp );
		} // while
		$string .= rtrim( $tmp ); // the rest
		$nl = '';
		if( $nl != substr( $string, ( 0 - strlen( $nl )))) {
			$string .= $nl;
		}
		return $string;
	}

	function quoted_printable_encode($input) {
//		$phpVersion = phpversion();
//		$phpVersionArray = explode('.', $phpVersion);
//		
//		if ( intval($phpVersionArray[0]) >= 5 && intval($phpVersionArray[1]) >= 3  ) {
//			$output = parent::quoted_printable_encode($input);
//		} else {
			$text1 = strip_tags($input, "<br><p>");
			$text1 = preg_replace('@([\r\n])[\s]+@',' ', $text1);    // Strip out white space
			$text1 = html_entity_decode($text1, ENT_QUOTES, "ISO-8859-15");
			$text1 = str_replace("<br />", "\r", $text1);
			$text1 = str_replace("<br/>" , "\r", $text1);
			$text1 = str_replace("<p>"   , "\r", $text1);
			$text1 = str_replace("</p>"  , "\r", $text1);

			$hex 		= array('0','1','2','3','4','5','6','7','8','9','A','B','C','D','E','F');
			$lines 		= preg_split("/(?:\r\n|\r|\n)/", $text1);
			$linebreak 	= "\n\r";
			$escape 	= "\\";
			$output 	= $text1;

//			for ($j=0;$j<count($lines);$j++) {
//				$line 		= $lines[$j];
//				$linlen 	= strlen($line);
//				$newline 	= '';
//
//				for($i = 0; $i < $linlen; $i++) {
//					$c 		= substr($line, $i, 1);
//					$dec 	= ord($c);
//
//					if ( ($dec == 32) && ($i == ($linlen - 1)) ) { // convert space at eol only
//						$c = '=20';
//					} elseif ( ($dec == 61) || ($dec < 32 ) || ($dec > 126) ) { // always encode "\t", which is *not* required
//						$h2 = floor($dec/16);
//						$h1 = floor($dec%16);
//						$c 	= $escape.$hex["$h2"] . $hex["$h1"];
//					}
//
//					$newline .= $c;
//				} // end of for
//				$output .= $newline;
//				if ($j<count($lines)-1) {
//					$output .= $linebreak;
//				}
//			}
////		}
//		$output = $input;
		return trim($output);
	}

}
?>