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
$dateString = SCOutput::getFormattedDate( $this->item->date1, $this->item->date2, $this->item->date3, $this->config->date_long_format, $this->config->date_short_format );
$timeString = SCOutput::getFormattedTime( $this->item->from_time, $this->item->to_time, $this->config->time_format, false);
//$showMap = false;
$document 	= & JFactory::getDocument();

JHTML::_('behavior.mootools');
JHTML::_('behavior.tooltip');
		
// check if LatLonGoogleMaps is active & API key is provided (in backend-params)
if ( $this->lists['showMap'] ) {
	$document->addScript('http://maps.google.com/maps?file=api&amp;v=2&amp;key='.trim($this->config->gmap_api_key).'&amp;sensor=false');
	JHTML::stylesheet('gmapsoverlay.css','components/com_simplecalendar/assets/css/');
	$document->addScript('components/com_simplecalendar/assets/js/gmapsoverlay.js');
	JHTML::stylesheet('mapmenu.css','components/com_simplecalendar/assets/css/');
	$document->addScript( 'components/com_simplecalendar/assets/js/gmapunload.js' );
}

?>	
<?php 
$this->pathway->addItem( JText::_( 'EVENT_DETAIL' ). ' :: '.$this->item->entryName /*, JRoute::_('index.php?view=detail&id='.$this->item->slug) */ );
?>
<div id="simplecal">
<span class="buttons">
<?php
if ( !$this->lists['print'] ){
	echo SCOutput::showIcon('back'); 
	if( $this->params->get('linkToPDF', '1') ){
		$pdflink = JRoute::_('index.php?view=detail&catid='.$this->item->catslug.'&id='.$this->item->slug.'&format=pdf');
		echo SCOutput::showIcon('pdf', $pdflink);
	}
	if( $this->params->get('linkToPrint', '1') ){
		$printlink = JRoute::_('index.php?view=detail&catid='.$this->item->catslug.'&id='.$this->item->slug.'&print=1&tmpl=component');
		echo SCOutput::showIcon('print', $printlink);
	}
	if( $this->params->get('linkToEMail', '1') ){
		echo SCOutput::showIcon('email');
	}
	if( $this->params->get('linkToVCal', '1') ){
		$vcallink = JRoute::_('index.php?view=detail&catid='.$this->item->catslug.'&id='.$this->item->slug.'&vcal=1');
		echo SCOutput::showIcon('vcal', $vcallink);
	}
} else {
	echo SCOutput::showIcon('printpreview');
}
	
?>			
</span>
<div class="componentheading">
<?php
echo $this->item->entryName; 
if ( $this->item->is_favourite ) {
	echo '&nbsp;' . JHTML::tooltip(
			JText::_('Event is favourited'),
			JText::_('Event is favourited'),
			'../../../components/com_simplecalendar/assets/images/star_on.png',
			'',
			'',
			false
		) ;
}
if ( $this->user->id != 0 ) {
	if ( ( ( $this->user->id == $this->item->userid ) && $this->config->allow_owner_edit || 
			$this->user->gid >= $this->config->frontend_edit_gid ) 
			&& $this->user->block == 0 ) {
		if ( !isset($this->menuitem->id) ) {
			$this->menuitem->id = 0;
		}
		echo '&nbsp;&nbsp;'.SCOutput::showIcon('edit', $this->item, $this->menuitem->id);
	}
}
?></div>
<?php
if($this->lists['showMap']) {
?>
<div id="scmap_menu_container">
<div id="scmap_menu_links">
<?php
	echo '<strong>'.JText::_('Map menu').'</strong><br />';
	echo '<a href="http://maps.google.com/maps?f=q&amp;geocode=&amp;q='.$this->item->entryLatLon.'&amp;place='.$this->item->entryPlace.'&amp;event='.$this->item->entryName.'" class="map" rel="gmapsoverlay">'.JText::_('Show event map').'</a><br />';
	echo '<a href="http://maps.google.com/maps?f=d&amp;geocode=&amp;q='.urlencode($this->item->entryName).'@'.$this->item->entryLatLon.'" target="_blank">'.JText::_('Directions').'</a><br />';
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

<dl class="sc_detail">
	<dt class="sc_detail"><?php echo SCOutput::getDatesType($this->item->date1, $this->item->date2, $this->item->date3); ?>:</dt>
	<dd class="sc_detail"><?php echo $dateString; ?>
	<?php 	if( $this->item->status_id != 0 ) {
		if ( $this->item->status_color != '' ) {
			$colored_status = "<span style=\"text-decoration:none;background-color:#" . $this->item->status_color . 
				";border-style:solid;border-width:1px;border-color:#" . $this->item->status_color . 
				";\">&nbsp;&nbsp;&nbsp;</span>";
		} else {
			$colored_status = '<span><small>' . JText::_('STATUS') . '</small></span>';
		}
		echo '&nbsp;' . JHTML::tooltip(
			$this->item->status_description,
			JText::_('Status'),
			'',
			$colored_status,
			'#',
			false
		);
	} ?>
	</dd>
	<?php 
	if($timeString != '') {
		echo '<dt class="sc_detail">' . JText::_('TIMES'). ':</dt>';
		echo '<dd class="sc_detail">' . $timeString . '</dd>';
	}
	if ($this->config->custom1_label != '' && $this->item->custom1 != '') {
		echo '<dt class="sc_detail">' . $this->config->custom1_label . ':</dt>';
		echo '<dd class="sc_detail">' . $this->item->custom1 . '</dd>';
	}
	if ($this->item->entryPlace != '') {
		echo '<dt class="sc_detail">' . JText::_('ENTRYPLACE') . ':</dt>';
		echo '<dd class="sc_detail">' . $this->item->entryPlace . '</dd>';
	}
	if ($this->item->entryAddress != '') {
		echo '<dt class="sc_detail">' . JText::_('ADDRESS') . ':</dt>';
		echo '<dd class="sc_detail">' . $this->item->entryAddress . '</dd>';
	}
	if ($this->item->entryGroupID != '0') {
		echo '<dt class="sc_detail">'. JText::_('ORGANIZER') . ':</dt>';
		echo '<dd class="sc_detail">'. $this->item->groupName;
		if ($this->item->gContactWebSite != '' || $this->item->gContactWebSite != Null) {
			echo '&nbsp;&nbsp;<a href="' . $this->item->gContactWebSite . '" target="_blank">';
			echo JHTML::_('image', 'components/com_simplecalendar/assets/images/world_link.png', JText::_( 'WEBSITE' ), array('align' => 'absolutemiddle', 'title'=>JText::_( 'WEBSITE' ))) .'</a>';
		}
		echo '</dd>';
	}
	if($this->item->contactName != '') {  
		echo '<dt class="sc_detail">'.JText::_('CONTACTPERSON').':</dt>';
		echo '<dd class="sc_detail">'.$this->item->contactName;
		if ($this->item->contactEmail != '' && JMailHelper::isEmailAddress($this->item->contactEmail)) {
			$cloaker =& JPluginHelper::getPlugin('content', 'emailcloak');
			$cloakerParams = new JParameter( $cloaker->params );
			require_once (JPATH_SITE.DS.'plugins'.DS.'content'.DS.'emailcloak.php');
			$row = new stdClass;
			$row->text = '&nbsp;&nbsp;<a href="mailto:' . $this->item->contactEmail.'">'.JHTML::_('image', 'components/com_simplecalendar/assets/images/email_go.png', JText::_( 'E-MAIL' ), array('align' => 'absolutemiddle', 'title'=>JText::_( 'E-MAIL' ))).'</a>';
			plgContentEmailCloak($row,$cloakerParams);
			echo $row->text;
		}
		echo '</dd>';
	} 
	if ($this->item->contactWebSite != '' && $this->item->contactWebSite != $this->item->gContactWebSite) {
		echo '<dt class="sc_detail">'. JText::_('CONTACTWEBSITE') . ':</dt>';
		if ( strlen($this->item->contactWebSite) > 48 ) {
			echo '<dd class="sc_detail"><a href="' . $this->item->contactWebSite . '" target="_blank">' . substr($this->item->contactWebSite, 0, 32) . '...</a></dd>';
		} else {
			echo '<dd class="sc_detail"><a href="' . $this->item->contactWebSite . '" target="_blank">' . $this->item->contactWebSite . '</a></dd>';
		}
	}
	if ($this->item->contactTelephone != '') {
		echo '<dt class="sc_detail">'. JText::_('CONTACTTELEPHONE') . ':</dt>';
		echo '<dd class="sc_detail">'. $this->item->contactTelephone .'</dd>';
	}
	if ($this->config->custom2_label != '' && $this->item->custom2 != '') {
		echo '<dt class="sc_detail">' . $this->config->custom2_label . ':</dt>';
		echo '<dd class="sc_detail">' . $this->item->custom2 . '</dd>';
	}
	if ($this->item->categoryName != '' && $this->config->show_category_in_detail ) {
		echo '<dt class="sc_detail">'. JText::_('CATEGORY') . ':</dt>';
		$link = JRoute::_( 'index.php?option=com_simplecalendar&view=calendar&catid=' . $this->item->catslug );
		echo '<dd class="sc_detail"><a href="'. $link . '">' . $this->item->categoryName .'</a></dd>';
	}
	if ( !is_null($this->config->currency) ) {
		if ( $this->config->currency != '' && $this->item->price != '' ) {
			echo '<dt class="sc_detail">' . JText::_('PRICE') . ':</dt>';
			echo '<dd class="sc_detail">' . $this->config->currency . ' '. $this->item->price . '</dd>';
		}
	}
//	if ( $this->item->attached_file != '' ) {
//		$link = JURI::base() . 'media/simplecalendar/' . $this->lists['file_info'][0];
//		$file = JPATH_SITE . DS . 'media' . DS . 'simplecalendar' . DS . $this->lists['file_info'][0];
//		if ( file_exists($file) ) {
//			echo '<dt class="sc_detail">'. JHTML::_('image', 'components/com_simplecalendar/assets/images/attach.png', JText::_( 'Attachment' ), array('title'=>JText::_( 'Attachment' )) ) . '&nbsp;&nbsp;' . JText::_('Attached file') . ':</dt>';
//			echo '<dd class="sc_detail">';
//			echo SCOutput::showFileDescription($this->item->attached_file, $link, $this->item->attached_file_description);
//			echo '</dd>';
//		}
//	} 
	if ( !$this->user->guest &&	 $this->user->gid >= $this->config->frontend_edit_gid && !$this->item->published ) {
		echo '<dt class="sc_detail">'. JText::_('PUBLISHED') . ':</dt>';
		echo '<dd class="unpublished">' . JText::_('Unpublished') . '</dd>';
	}
	if ( !$this->user->guest && $this->item->entryIsPrivate ) {
		echo '<dt class="sc_detail">'. JText::_('ENTRYISPRIVATE') . ':</dt>';
		echo '<dd class="sc_detail">' . JText::_('Yes') . '</dd>';
	}
	if ( $this->config->show_username && $this->item->userid != 0 ) {
		$event_owner = JFactory::getUser($this->item->userid);
		echo '<dt class="sc_detail">'. JText::_('Added by') . ':</dt>';
		echo '<dd class="sc_detail">' . $event_owner->name . ' ('. $event_owner->username  .')</dd>';
	}  
?>
</dl>
<?php
if ( $this->item->entryInfo != '' ) {
	if ( $this->config->show_additionalinfo_label ) {
		echo '<h2 class="additional_info">' . JText::_('EXTENDED_INFO') . '</h2>'."\n";
	}
	echo '<div id="entryinfo">'."\n";
	echo $this->item->entryInfo;
	echo '</div>';
}

// 	Footer. Please do not remove.
echo SCOutput::showFooter();

// JComments integration
if ( $this->config->use_jcomments && file_exists(JPATH_SITE.DS.'components'.DS.'com_jcomments'.DS.'jcomments.php') ) {
	require_once(JPATH_SITE.DS.'components'.DS.'com_jcomments'.DS.'jcomments.php');
	echo JComments::show($this->item->id, 'com_simplecalendar', $this->item->entryName);
}
?>
</div>