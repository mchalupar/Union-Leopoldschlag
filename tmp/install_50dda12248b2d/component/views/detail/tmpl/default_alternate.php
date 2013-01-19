<body onunload="GUnload()"></body>
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

jimport( 'joomla.mail.helper' );

$params =& JComponentHelper::getParams( 'com_simplecalendar' );

$dateText = SCOutput::getDatesType( $this->item->date1, $this->item->date2, $this->item->date3 );
$dateString = SCOutput::getFormattedDate($this->item->date1, $this->item->date2, $this->item->date3, $this->config->date_long_format, $this->config->date_short_format);
$timeString = SCOutput::getFormattedTime($this->item->from_time, $this->item->to_time, $this->config->time_format, false);
$showMap = false;
$document 	= & JFactory::getDocument();

JHTML::_('behavior.mootools');
JHTML::_('behavior.tooltip');
		
// check if LatLonGoogleMaps is active & API key is provided (in backend-params)
if ($this->config->use_gmap == 1 && $this->config->gmap_api_key != '' && $this->item->entryLatLon != '') {
	$showMap = TRUE;
	$document->addScript('http://maps.google.com/maps?file=api&amp;v=2&amp;key='.trim($this->config->gmap_api_key).'&sensor=false');
	JHTML::stylesheet('gmapsoverlay.css','components/com_simplecalendar/assets/css/');
	$document->addScript('components/com_simplecalendar/assets/js/gmapsoverlay.js');
	JHTML::stylesheet('mapmenu.css','components/com_simplecalendar/assets/css/');
}

?>	
	<div id="simplecal" class="simplecal detail">
	<p class="buttons">
	<?php
	if (!$lists['print']){
		if($this->params->get('linkToPDF', '1')){
			$pdflink = JRoute::_('index.php?view=detail&id='.$this->item->slug.'&format=pdf');
			echo SCOutput::showPDFIcon($pdflink);
		}
		if($this->params->get('linkToPrint', '1')){
			$printlink = JRoute::_('index.php?view=detail&tmpl=component&id='.$this->item->slug.'&print=1');
			echo SCOutput::showPrintIcon($printlink);
		}
		if($this->params->get('linkToEMail', '1')){
			echo SCOutput::showEMailIcon();
		}
		if($this->params->get('linkToVCal', '1')){
			$vcallink = JRoute::_('index.php?view=detail&id='.$this->item->slug.'&vcal=1');
			echo SCOutput::showVCalButton($vcallink);
		}
	} else {
		echo SCOutput::showPrintPreviewIcon();
	}
?>			
<h1 class="componentheading">
<?php echo $this->item->entryName . ' '. SCOutput::showBackButton(); ?>
<?php
if ( $this->user->id != 0 ) {
	if ( ( ( $this->user->id == $this->item->userid ) && $this->config->allow_owner_edit || 
			$this->user->gid >= $this->config->frontend_edit_gid ) 
			&& $this->user->block == 0 ) {
		if ( !isset($this->menuitem->id) ) {
			$this->menuitem->id = 0;
		}
		echo '&nbsp;&nbsp;'.SCOutput::showEditIcon($this->item->slug, $this->menuitem->id);
	}
}
?></h1>
<?php
if($showMap) {
	$latlon = explode(',',$this->item->entryLatLon);
	$lat = $latlon[0];
	$lon = $latlon[1];
	
	?>
<div id="scmap_menu_container">
<div id="scmap_menu_links">
<?php
	echo '<strong>'.JText::_('Map menu').'</strong><br />';
	echo '<a href="http://maps.google.com/maps?f=q&geocode=&q='.$this->item->entryLatLon.'&place='.$this->item->entryPlace.'&event='.$this->item->entryName.'" class="map" rel="gmapsoverlay">'.JText::_('Show event map').'</a><br />';
	echo '<a href="http://maps.google.com/maps?f=d&geocode=&q='.urlencode($this->item->entryName).'@'.$this->item->entryLatLon.'" target="_blank">'.JText::_('Directions').'</a><br />';
?>
</div>
<?php
echo '<div id="scmap_menu">'.
		JHTML::tooltip(
			JText::_('Click on the icon to show a big map where the event is due to take place'),
			JText::_('This event has a map'),
			'../../../components/com_simplecalendar/assets/images/map_icon.png',
			'',
			'',
			false
		) .'</div>';
	?>

</div>

<script type="text/javascript">
var slide = new Fx.Slide('scmap_menu_links');

window.addEvent('domready', function() {
	<?php if ( !$this->config->map_slider_open ) : ?>
	slide.toggle();
	<?php endif; ?>
})
$('scmap_menu').addEvent('click',function(e){
	e = new Event(e);
	slide.toggle();
	e.stop();
	})
</script>

	
<?php
}
?>

<p></p>
<dl class="sc_detail">
<?php 
	if ($this->item->categoryName != '') {
		echo '<dt class="sc_detail">'. JText :: _('CATEGORY') . ':</dt>';
		$link = JRoute::_('index.php?option=com_simplecalendar&view=calendar&catid=' . $this->item->categoryID);
		echo '<dd class="sc_detail"><a href="'. $link . '">' . $this->item->categoryName .'</a></dd>';
	}
?>
	<dt class="sc_detail"><?php echo SCOutput::getDatesType($this->item->date1, $this->item->date2, $this->item->date3); ?>:</dt>
	<dd class="sc_detail"><?php echo $dateString; ?></dd>
	<?php
	if($timeString != '') {
		echo '<dt class="sc_detail">' . JText::_('TIME'). ':</dt>';
		echo '<dd class="sc_detail">' . $timeString . '</dd>';
	}
	echo '<dt class="sc_detail">' . JText::_('ENTRYPLACE') . ':</dt>';
	echo '<dd class="sc_detail">' . $this->item->entryPlace . '</dd>';
?>
</dl>
<p></p>
<dl>
<?php 
	if ( $this->item->entryInfo != '' ) {
		echo '<h2 class="contentheading">' . JText :: _('EXTENDED_INFO') . '</h2>'."\n";
		echo '<div id="entryinfo">'."\n";
		echo $this->item->entryInfo;
		echo '</div>';
	}
?>
</dl>
<p></p>
<dl>
<?php 
	if ( !is_null($this->config->currency) ) {
		if ( $this->config->currency != '' && $this->item->price != '' ) {
			echo '<dt class="sc_detail">' . JText :: _('PRICE') . ':</dt>';
			echo '<dd class="sc_detail">' . $this->config->currency . ' '. $this->item->price . '</dd>';
		}
	}

	if ($this->item->entryGroupID != '0') {
		echo '<dt class="sc_detail">'. JText :: _('ORGANIZER') . ':</dt>';
		echo '<dd class="sc_detail">';
		if ($this->item->gContactWebSite != '' || $this->item->gContactWebSite != Null) {
			echo '&nbsp;<a href="' . $this->item->gContactWebSite . '" target="_blank">';
			echo $this->item->groupName .'</a>';
		} else {
			echo $this->item->groupName;
		}
		echo '</dd>';
	}
	if($this->item->contactName != '') {  
		echo '<dt class="sc_detail">'.JText :: _('CONTACTPERSON').':</dt>';
		echo '<dd class="sc_detail">'.$this->item->contactName;
		if ($this->item->contactEmail != '' && JMailHelper::isEmailAddress($this->item->contactEmail)) {
			echo '&nbsp;<a href="mailto:' . $this->item->contactEmail.'">'.JHTML::_('image', 'images/M_images/emailButton.png', JText::_( 'E-MAIL' ), array('align' => 'absolutemiddle', 'title'=>JText::_( 'E-MAIL' ))).'</a>';
		}
		echo '</dd>';
	} 
	if ($this->item->contactTelephone != '') {
		echo '<dt class="sc_detail">'. JText :: _('CONTACTTELEPHONE') . ':</dt>';
		echo '<dd class="sc_detail">'. $this->item->contactTelephone .'</dd>';
	}
	if ($this->item->contactWebSite != '' && $this->item->contactWebSite != $this->item->gContactWebSite) {
		echo '<dt class="sc_detail">'. JText :: _('CONTACTWEBSITE') . ':</dt>';
//		if ( strlen($this->item->contactWebSite) > 48 ) {
//			echo '<dd class="sc_detail"><a href="' . $this->item->contactWebSite . '" target="_blank">' . substr($this->item->contactWebSite, 0, 32) . '...</a></dd>';
//		} else {
//			echo '<dd class="sc_detail"><a href="' . $this->item->contactWebSite . '" target="_blank">' . $this->item->contactWebSite . '</a></dd>';
//		}
		echo '<dd class="sc_detail"><a href="' . $this->item->contactWebSite . '" target="_blank">' . $this->item->contactWebSite . '</a></dd>';
	}
	if ( $this->item->attached_file != '' ) {
		$link = JURI::base() . 'media/simplecalendar/' . $this->lists['file_info'][0];
		$file = JPATH_SITE . DS . 'media' . DS . 'simplecalendar' . DS . $this->lists['file_info'][0];
		if ( file_exists($file) ) {
			echo '<dt class="sc_detail">'. JHTML::image( 'components/com_simplecalendar/assets/images/attachment.gif', JText::_('Attachment') ) . '&nbsp;&nbsp;' . JText :: _('Attached file') . ':</dt>';
			echo '<dd class="sc_detail">';
			echo SCOutput::showFileDescription($this->item->attached_file, $link, $this->item->attached_file_description);
			echo '</dd>';
		}
	} 
	if ( !$this->user->guest &&	 $this->user->gid >= $this->config->frontend_edit_gid && !$this->item->published ) {
		echo '<dt class="sc_detail">'. JText :: _('PUBLISHED') . ':</dt>';
		echo '<dd class="unpublished">' . JText::_('Unpublished') . '</dd>';
	}
	if ( !$this->user->guest && $this->item->entryIsPrivate ) {
		echo '<dt class="sc_detail">'. JText :: _('ENTRYISPRIVATE') . ':</dt>';
		echo '<dd class="sc_detail">' . JText::_('Yes') . '</dd>';
	}
	if ( $this->config->show_username && $this->item->userid != 0 ) {
		$event_owner = JFactory::getUser($this->item->userid);
		echo '<dt class="sc_detail">'. JText :: _('Added by') . ':</dt>';
		echo '<dd class="sc_detail">' . $event_owner->name . ' ('. $event_owner->username  .')</dd>';
	}  
?>
</dl>
<?php


// 	Footer. Please do not remove.
echo SCOutput::showFooter();

// JComments integration
if ( $this->config->use_jcomments && file_exists(JPATH_SITE.DS.'components'.DS.'com_jcomments'.DS.'jcomments.php') ) {
	require_once(JPATH_SITE.DS.'components'.DS.'com_jcomments'.DS.'jcomments.php');
	echo JComments::show($this->item->id, 'com_simplecalendar', $this->item->entryName);
}

?>
</div>