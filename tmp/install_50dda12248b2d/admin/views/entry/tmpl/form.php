<?php

defined('_JEXEC') or die('Restricted access');

$document =& JFactory::getDocument();

JHTML::_( 'behavior.tooltip' );
JHTML::_( 'behavior.modal' );
JHTML::_( 'behavior.formvalidation' );

$gmailString = '';
$showMap = false;
$latlon = '';
$addMarker = false;
$zoom = '';
$val = '';


// check if entry has Lat/Lng coordinates for map display
if ( isset($this->item->entryLatLon) && $this->item->entryLatLon != '' ) {
	$latlon = $this->item->entryLatLon;
	$zoom = '13';
	$addMarker = true;
} else {
	$latlon = $this->config->gmap_std_latlon;
	$zoom = '5';
	$addMarker = false;
}

$document->addScript(JURI::base()."components/com_simplecalendar/assets/js/getgroupdata_ajax.js");

// check if GoogleMaps is active & API key is provided (in backend-params)
if ($this->config->use_gmap == 0 || $this->config->gmap_api_key == '') {
	$gmailString = JText::_('GOOGLEMAPS_IS_NOT_ENABLED');
} else {
	$showMap = true;
	$document->addScript("http://maps.google.com/maps?file=api&amp;v=2&amp;key=" . $this->config->gmap_api_key . "&amp;sensor=false");	
//	$latlon = $this->item->entryLatLon;
	$entryName = $this->item->entryName;
	
	$js = "
    var map = null;
    var marker = null;
    var geocoder = null;
    var crossControl = null;
    var latlon = '$latlon';
    
    if ( latlon != '' ) {
    	addMarker = true;
	} else {
		addMarker = false;
	}

    function initialize() {
		if (GBrowserIsCompatible()) {
			map = new GMap2(document.getElementById(\"map_canvas\"));
			map.setCenter(new GLatLng( $latlon ),  $zoom);
			map.addControl(new GSmallMapControl());
			geocoder = new GClientGeocoder();
			center = map.getCenter();
			marker = new GMarker(center);
	";
	
	if ( $addMarker ) {
		$js .= "
			map.addOverlay(marker);
			marker.openInfoWindowHtml(\"" . JText::_('CURRENTLY_SAVED_POSITION') . "<br /><b>$entryName</b>\");
			";
	}
	
    $js .= "
		}
    }   
     
    function setLatLon(latlon) {
    	if(latlon.length > 0) {
    		removeMarker();
	    	var latlonArray = new Array();
	    	latlonArray = latlon.split(',');
	    	var lat = latlonArray['0'];
	    	var lon = latlonArray['1'];
	    	map.setCenter(new GLatLng ( lat , lon ), 13 );
	    	center = map.getCenter();
	    	marker = new GMarker(center);
	    	map.addOverlay(marker);
	    	document.getElementById('entryLatLon').value = latlon;
    	} else {
    		removeMarker();
		}
    }

    function removeMarker() {
		map.removeOverlay(marker);
		document.getElementById('entryLatLon').value = '';
    }
    
    function addLoadEvent(func) {
	  var oldonload = window.onload;
	  if (typeof window.onload != 'function') {
	    window.onload = func;
	  } else {
	    window.onload = function() {
	      if (oldonload) {
	        oldonload();
	      }
	      func();
	    };
	  }
	}

   addLoadEvent(initialize);

	// arrange for our onunload handler to 'listen' for onunload events
	if (window.attachEvent) {
        window.attachEvent(\"onunload\", function() {
                GUnload();      // Internet Explorer
        });
	} else {

        window.addEventListener(\"unload\", function() {
                GUnload(); // Firefox and standard browsers
        }, false);
	}";
	$document->addScriptDeclaration( $js );

}
?>
<script type="text/javascript">
	function submitbutton(pressbutton) {
		var form = document.adminForm;
		
		if (pressbutton == 'cancel') {
			submitform( pressbutton );
			return;
		}

		// do field validation
		if (form.entryName.value == ""){
			alert( "<?php echo JText::_( 'Please specify an event name', true ); ?>" );
		} else if (form.contactWebSite.value != "" && form.contactWebSite.value.substr(0,7) != "http://"){
			alert( "<?php echo JText::_( 'Wrong website specified. It must begin with http://', true ); ?>" );
		}  else if (form.date1.value == "" || form.date1.value == "0000-00-00"){
			alert( "<?php echo JText::_( 'No from date specified', true ); ?>" );
		} else {
			<?php
			echo $this->editor->save( 'entryInfo' ) . "\n";
			?>
			submitform( pressbutton );
		}
	}
</script>

<form action="index.php?option=com_simplecalendar&amp;controller=calendar&amp;view=calendar" method="post" class="form-validate" name="adminForm" id="adminForm" enctype="multipart/form-data">
<div class="col width-60">
<fieldset class="adminform">
<legend><?php echo JText::_( 'DETAILS' ); ?></legend>

<table class="admintable">
	<tr>
		<td width="100" align="right" class="key"><label for="entryName"> <?php echo JText::_( 'ENTRYNAME' ); ?>:
		</label></td>
		<td><input class="text_area required" type="text" name="entryName" id="entryName" size="64" maxlength="250" value="<?php if (isset($this->item->entryName)) echo $this->item->entryName;?>" />
		<?php echo '&nbsp;' . JHTML::tooltip(
			JText::_('Insert the name of the event. This field is compulsory'),
			JText::_('Notice'),
			'tooltip.png',
			'',
			'',
			false
			);  ?></td>
	</tr>
	<tr>
		<td width="100" align="right" class="key"><label for="alias"> <?php echo JText::_( 'Alias' ); ?>:
		</label></td>
		<td><input class="text_area" type="text" name="alias" id="alias" size="64" maxlength="250" value="<?php if (isset($this->item->alias)) echo $this->item->alias;?>" />
		</td>
	</tr>
<?php if ( $this->config->custom1_label != '' ): ?>
	<tr>
		<td width="100" align="right" class="key">
			<label for="custom1"> <?php echo $this->config->custom1_label; ?>: </label>
		</td>
		<td>
			<input class="text_area" type="text" name="custom1" id="custom1" size="64" maxlength="255" value="<?php if (isset($this->item->custom1)) echo $this->item->custom1;?>" />
		</td>
	</tr>
<?php endif;?>

	<tr>
		<td width="100" align="right" class="key"><label for="entryPlace"> <?php echo JText::_( 'ENTRYPLACE' ); ?>:
		</label></td>
		<td><input class="text_area" type="text" name="entryPlace"
			id="entryPlace" size="32" maxlength="250"
			value="<?php if (isset($this->item->entryPlace)) echo $this->item->entryPlace;?>" />
		<?php echo '&nbsp;' . JHTML::tooltip(
			JText::_('Insert the venue where the event will take place.'),
			JText::_('Notice'),
			'tooltip.png',
			'',
			'',
			false
			);  ?></td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
			<label for="entryAddress"> <?php echo JText::_( 'ADDRESS' ); ?>:</label>
		</td>
		<td><input class="text_area" type="text" name="entryAddress" id="entryAddress" size="32" maxlength="128"
				value="<?php if (isset($this->item->entryAddress)) echo $this->item->entryAddress;?>" />
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key"><label for="date1"> <?php echo JText::_( 'DATE_FROM' ); ?>:
		</label></td>
		<td><?php 
		if ( isset($this->item->date1) )
			$val = $this->item->date1;
		echo JHTML::_('calendar', $val, 'date1', 'date1', '%Y-%m-%d', array('class'=>'inputbox required', 'size'=>'10',  'maxlength'=>'10')); ?>
		<?php echo '&nbsp;' . JHTML::tooltip(
		JText::_('Insert the starting date of the event. This field is compulsory'),
		JText::_('Notice'),
			'tooltip.png',
			'',
			'',
		false
		);  ?></td>
	</tr>
	<tr>
		<td width="100" align="right" class="key"><label for="date2"> <?php echo JText::_( 'DATE_TO' ); ?>:
		</label></td>
		<td><?php
		if ( isset($this->item->date2) ) {
			$val = $this->item->date2;
		} else {
			$val = null;
		}
		echo JHTML::_('calendar', $val, 'date2', 'date2', '%Y-%m-%d', array('class'=>'inputbox', 'size'=>'10',  'maxlength'=>'10')); ?>
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key"><label for="date3"> <?php echo JText::_( 'DATE3' ); ?>:
		</label></td>
		<td><?php
		if ( isset($this->item->date3) ) {
			$val = $this->item->date3;
		} else {
			$val = null;
		}
		echo JHTML::_('calendar', $val, 'date3', 'date3', '%Y-%m-%d', array('class'=>'inputbox', 'size'=>'10',  'maxlength'=>'10')); ?>
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key"><label for="from_time"> <?php echo JText::_( 'FROM_TIME' ); ?>:
		</label></td>
		<td><input class="text_area" type="text" name="from_time"
			id="from_time" size="10" maxlength="10"
			value="<?php if (isset($this->item->from_time)) echo JHTML::_('date', $this->item->from_time, '%H:%M', 0);  ?>" />
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key"><label for="to_time"> <?php echo JText::_( 'TO_TIME' ); ?>:
		</label></td>
		 <td>
         <input class="text_area" type="text" name="to_time" id="to_time"
         size="10" maxlength="10"
         value="<?php if (isset($this->item->to_time)) echo JHTML::_('date', $this->item->to_time, '%H:%M', 0);  ?>" />
      	</td>
	</tr>
<?php if ( $this->config->custom2_label != '' ): ?>
	<tr>
		<td width="100" align="right" class="key">
			<label for="custom2"><?php echo $this->config->custom2_label; ?>: </label>
		</td>
		<td>
			<input class="text_area" type="text" name="custom2" id="custom2" size="64" maxlength="255" value="<?php if (isset($this->item->custom2)) echo $this->item->custom2;?>" />
		</td>
	</tr>
<?php endif;?>
	<tr>
		<td width="100" align="right" class="key"><label for="category"> <?php echo JText::_( 'CATEGORY' ); ?>:
		</label></td>
		<td><?php
		if (isset($this->item->categoryID))
			$val = intval ($this->item->categoryID); 
		echo JHTML::_('select.genericlist',   $this->lists['categories'], 'categoryID', 'class="inputbox" size="1"', 'value', 'text', $val, '', 1); ?>
		</td>
	</tr>
	<?php if ($this->config->currency != ''): ?>
	<tr>
		<td width="100" align="right" class="key"><label for="price"> <?php echo JText::_( 'PRICE' ); ?>:
		</label></td>
		<td><?php echo $this->config->currency . ' '; ?>
			<input class="text_area" type="text" name="price"
			id="price" size="10" 
			value="<?php if (isset($this->item->price)) echo $this->item->price;  ?>" />&nbsp;		
		</td>
	</tr>
	<?php endif; ?>
	<tr>
		<td width="100" align="right" class="key"><label for="isPrivate"> <?php echo JText::_( 'ENTRYISPRIVATE' ); ?>:
		</label></td>
		<td><?php echo $this->lists['isPrivate']; ?></td>
	</tr>
	<tr>
		<td width="100" align="right" class="key"><label for="published"> <?php echo JText::_( 'PUBLISHED' ); ?>:
		</label></td>
		<td><?php echo $this->lists['published']; ?></td>
	</tr>
</table>
</fieldset>
<fieldset class="adminform">
<legend><?php echo JText::_( 'ENTRYINFO' ); ?></legend>
<table class="admintable">
	<tr>
		<td>
		<?php
			// parameters : areaname, content, hidden field, width, height, rows, cols, buttons
			if (isset($this->item->entryInfo))
				$val = $this->item->entryInfo;
			echo $this->editor->display( 'entryInfo',  $val, '550', '300', '50', '40', false) ;
		?>
		</td>
	</tr>
</table>
</fieldset>
<fieldset class="adminform">
<legend><?php echo JText::_( 'ORGANIZER' ); ?></legend>
<table class="admintable">
	<?php if ( $this->lists['groups'] ) : ?>
	<tr>
		<td width="100" align="right" class="key"><label for="group"> <?php echo JText::_( 'GROUP' ); ?>:
		</label></td>
		<td><?php echo SCOutput::getGroupsComboBox(intval($this->item->entryGroupID), 'admin'); ?>
		<?php echo JHTML::_('image', 'administrator/components/com_simplecalendar/assets/images/load_small.gif', JText::_( 'Loading' ), array('title'=>JText::_( 'Loading' ), 'style'=>'visibility:hidden;', 'id'=>'ajax_load')) ?>
		<input type="hidden" id="groupLatLon" name="groupLatLon" value="<?php if (isset($this->item->groupLatLon)) echo $this->item->groupLatLon; ?>" />
		</td>
	</tr>
	<?php endif; ?>
	<tr>
		<td width="100" align="right" class="key"><label for="contactName"> <?php echo JText::_( 'CONTACTNAME' ); ?>:
		</label></td>
		<td><input class="text_area" type="text" name="contactName"	id="contactName" size="64" maxlength="250" value="<?php if (isset($this->item->contactName)) echo $this->item->contactName;?>" /></td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
		<label for="contactEmail"> <?php echo JText::_( 'CONTACTEMAIL' ); ?>:
		</label></td>
		<td><input class="text_area" type="text" name="contactEmail" id="contactEmail" size="64" maxlength="250" value="<?php if (isset($this->item->contactEmail)) echo $this->item->contactEmail;?>" /></td>
	</tr>
	<tr>
		<td width="100" align="right" class="key"><label for="contactWebSite">
		<?php echo JText::_( 'CONTACTWEBSITE' ); ?>: </label></td>
		<td><input class="text_area" type="text" name="contactWebSite" id="contactWebSite" size="64" maxlength="250" value="<?php if (isset($this->item->contactWebSite)) echo $this->item->contactWebSite;?>" /></td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
			<label for="contactTelephone"> <?php echo JText::_( 'CONTACTTELEPHONE' ); ?>:</label>
		</td>
		<td><input class="text_area" type="text" name="contactTelephone" id="contactTelephone" size="64" maxlength="250" value="<?php if (isset($this->item->contactTelephone)) echo $this->item->contactTelephone;?>" /></td>
	</tr>
</table>
</fieldset>
</div>
<div class="col width-40">
<fieldset class="adminform">
<legend><?php echo JText::_( 'Event Status' ); ?></legend>
<table class="admintable">
	<tr>
		<td width="100" align="right" class="key"><label for="is_favourite"> <?php echo JText::_( 'Favourite' ); ?>:
		</label></td>
		<td><?php echo $this->lists['is_favourite']; ?></td>
	</tr>
	<tr>
		<td width="100" align="right" class="key"><label for="status"> <?php echo JText::_( 'Event status' ); ?>:
		</label></td>
		<td><?php
			if (isset($this->item->statusID))
				$val = $this->item->statusID;
			echo JHTML::_('select.genericlist',   $this->lists['statuses'], 'statusID', 'class="inputbox" size="1"', 'value', 'text', $val, '', 1); ?>
		</td>
	</tr>
</table>
</fieldset>
</div>
<div class="col width-40">
<fieldset class="adminform">
<legend><?php echo JText::_( 'GOOGLEMAPS' ); ?></legend>
		<?php
		if( $this->config->use_gmap == 1 && $this->config->gmap_api_key != '') {
		?>
<div id="map_canvas" style="width: 100%; height: 300px"></div>
<p>
	<?php if( $this->lists['isNew'] || $this->item->entryLatLon == '' ) { ?>
				<a id="btnSet"
					href="<?php echo JRoute::_('../index.php?option=com_simplecalendar&view=form&layout=gmap&tmpl=component'); ?>"
					class="modal"
					rel="{handler: 'iframe', size: {x: 630, y: 550}}"
					style="visibility: visible" >
					<?php echo JText::_('Set position');?>
				</a>
				<a id="btnModify"
					href="<?php echo JRoute::_('../index.php?option=com_simplecalendar&view=form&layout=gmap&tmpl=component'); ?>"
					class="modal"
					rel="{handler: 'iframe', size: {x: 630, y: 550}}"
					style="visibility: hidden;" >
					<?php echo JText::_('Modify position');?>
				</a>
		<?php } else { ?>
				<a id="btnSet"
					href="<?php echo JRoute::_('../index.php?option=com_simplecalendar&view=form&layout=gmap&tmpl=component'); ?>"
					class="modal"
					rel="{handler: 'iframe', size: {x: 630, y: 550}}"
					style="display: none" >
					<?php echo JText::_('Set position');?>
				</a>
				 <a id="btnModify"
					href="<?php echo JRoute::_('../index.php?option=com_simplecalendar&view=form&layout=gmap&tmpl=component'); ?>"
					class="modal"
					rel="{handler: 'iframe', size: {x: 630, y: 550}}">
					<?php echo JText::_('Modify position');?>
				</a>
		<?php } ?>
	<input class="text_area"
		type="text"
		name="entryLatLon"
		id="entryLatLon"
		size="64"
		maxlength="250"
		style="visibility: hidden;"
		value="<?php if (isset($this->item->entryLatLon)) echo $this->item->entryLatLon;?>"
		onchange="setLatLon(this.value);" />
</p>
			<?php
		} else {
			echo JText::_('GOOGLEMAPS_IS_NOT_ENABLED'); ?>
			<input class="text_area" type="text" name="entryLatLon" id="entryLatLon" style="visibility: hidden;" value="<?php if (isset($this->item->entryLatLon)) echo $this->item->entryLatLon;?>" />
			<?php
		}
		?></fieldset>
</div>
<div class="clr"></div>
<?php echo JHTML::_( 'form.token' ); 
$date =& JFactory::getDate();
$today = $date->toMySQL();
if ($this->lists['isNew']) { 
	?><input type="hidden" name="created" value="<?php echo $today;?>" />
<?php } else {
	?><input type="hidden" name="created" value="<?php echo $this->item->created;?>" />
<?php } ?>
<input type="hidden" name="modified" value="<?php echo $today;?>" />
<input type="hidden" name="option" value="com_simplecalendar" />
<input type="hidden" name="id" value="<?php if (isset($this->item->id)) echo $this->item->id; ?>" />
<input type="hidden" name="userid" value="<?php if (isset($this->item->userid)) echo $this->item->userid; ?>" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="controller" value="entry" />
<input type="hidden" name="caller" id="caller" value="backend_entry" />
</form>
<?php
//keep session alive while editing
JHTML::_('behavior.keepalive');

// Footer. Please do not remove.
echo SCOutput::showFooter();
?>