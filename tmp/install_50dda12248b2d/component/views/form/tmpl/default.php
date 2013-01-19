<?php

defined('_JEXEC') or die('Restricted access');

$document =& JFactory::getDocument();
JHTML::_( 'behavior.tooltip' );
JHTML::_( 'behavior.modal' );

jimport('joomla.html.pane');
$document->addScript(JURI::base()."administrator/components/com_simplecalendar/assets/js/getgroupdata_ajax.js");

$validate = "window.addEvent('domready', function() {
   document.formvalidator.setHandler('validurl', function(value) {
      if ( value != '' && value.substr(0,7) != 'http://' ) {
      	return false;
      }
      return true;
   })
})
";
$document->addScriptDeclaration($validate);
JHTML::stylesheet('tabs.css','components/com_simplecalendar/assets/css/');
JHTML::stylesheet('form.css','components/com_simplecalendar/assets/css/');

$gmailString = '';
$showMap = false;
$val = '';

// check if entry has Lat/Lng coordinates for map display
if (!$this->lists['isNew']) {
	if ($this->item->entryLatLon != '' || $this->item->entryLatLon != Null) {
		$latlon = $this->item->entryLatLon;
		$zoom = '13';
		$addMarker = true;
	} else {
		$latlon = $this->config->gmap_std_latlon;
		$zoom = '5';
		$addMarker = false;
	}
}
?>
<div id="simplecal_form" class="simplecal detail">
<form action="#" method="post" name="adminForm" id="adminForm" class="form-validate" enctype="multipart/form-data" onSubmit="validUrl()">

<h1 class="componentheading">
<?php 
if ($this->lists['isNew']) {
	echo JText::_('New event');
} else {
	echo JText::_('Modify event');
	 
}?>
</h1>
<?php if ( !$this->lists['isNew'] ) : ?>
	<h2 class="contentheading">
		<?php if (isset($this->item)) {
		echo SCOutput::showIcon('back') . '&nbsp;' . $this->item->entryName; 
	}?>
	</h2>
<?php endif; ?>
<br/>
<?php
//		 tab view for better reading.
$tabs = &JPane::getInstance('tabs');
echo $tabs->startPane('form-edit-tabs');
		
//		 Main member data tab
echo $tabs->startPanel(JText::_('ENTRYNAME'), 'main-data'); 
 ?>

<table id="sc_form" class="admintable">
	<tr>
		<td width="160" align="right" class="key"><label for="entryName"><?php echo JText::_('ENTRYNAME'); ?>:
		</label></td>
		<td><input class="text_area required" type="text" name="entryName" id="entryName" size="44" maxlength="250" value="<?php if (isset($this->item->entryName)) echo $this->item->entryName;?>" />
		<?php echo '&nbsp;' . JHTML::tooltip(
			JText::_('Insert the name of the event. This field is compulsory.'),
			JText::_('Notice'),
			'tooltip.png',
			'',
			'',
			false
			);  ?></td>
	</tr>
	<tr>
		<td width="160" align="right" class="key"><label for="alias"><?php echo JText::_('Alias'); ?>:
		</label></td>
		<td><input class="text_area" type="text" name="alias" id="alias" size="44" maxlength="250" value="<?php if (isset($this->item->alias)) echo $this->item->alias;?>" />
		</td>
	</tr>
<?php if ( $this->config->custom1_label != '' ): ?>
	<tr>
		<td width="160" align="right" class="key">
			<label for="custom1"><?php echo $this->config->custom1_label; ?>: </label>
		</td>
		<td>
			<input class="text_area" type="text" name="custom1" id="custom1" size="64" maxlength="255" value="<?php if (isset($this->item->custom1)) echo $this->item->custom1;?>" />
		</td>
	</tr>
<?php endif;?>
	<tr>
		<td width="160" align="right" class="key"><label for="entryPlace"><?php echo JText::_('ENTRYPLACE'); ?>:
		</label></td>
		<td><input class="text_area" type="text" name="entryPlace" id="entryPlace" size="32" maxlength="250" value="<?php if (isset($this->item->entryPlace)) echo $this->item->entryPlace;?>" />
		<?php echo '&nbsp;' . JHTML::tooltip(
			JText::_('Insert the venue where the event will take place'),
			JText::_('Notice'),
			'tooltip.png',
			'',
			'',
			false
			); 
			echo '&nbsp;';
			if ( $this->config->use_gmap && $this->config->gmap_api_key != '' ) {
				echo JHTML::tooltip(
					JText::_(''),
					JText::_('Google Maps'),
					'../../../components/com_simplecalendar/assets/images/map_icon_20.png',
					'',
					'',
					false
					); 
				if( $this->lists['isNew'] || $this->item->entryLatLon == '' ) { ?>
					<a id="btnSet"
						href="<?php echo JRoute::_('index.php?view=form&task=edit&layout=gmap&tmpl=component'); ?>"
						class="modal"
						rel="{handler: 'iframe', size: {x: 630, y: 550}}"
						style="visibility: visible;" ><?php echo JText::_('Set position');?></a>
					<a id="btnModify"
						href="<?php echo JRoute::_('index.php?view=form&task=edit&layout=gmap&tmpl=component'); ?>"
						class="modal"
						rel="{handler: 'iframe', size: {x: 630, y: 550}}"
						style="visibility: hidden;" ><?php echo JText::_('Modify position');?></a>
		<?php } else { ?>
					<a id="btnSet"
						href="<?php echo JRoute::_('index.php?view=form&task=edit&layout=gmap&tmpl=component'); ?>"
						class="modal"
						rel="{handler: 'iframe', size: {x: 630, y: 550}}"
						style="display: none;" ><?php echo JText::_('Set position');?></a>
					<a id="btnModify"
						href="<?php echo JRoute::_('index.php?view=form&task=edit&layout=gmap&tmpl=component'); ?>"
						class="modal"
						rel="{handler: 'iframe', size: {x: 630, y: 550}}"><?php echo JText::_('Modify position');?></a>
		<?php }
		} ?>
				<input class="text_area"
					type="text"
					style="display: none;"
					name="entryLatLon"
					id="entryLatLon"
					size="44"
					maxlength="250"
					value="<?php if (isset($this->item->entryLatLon)) echo $this->item->entryLatLon;?>" />
					
			</td>
	</tr>
	<tr>
		<td width="160" align="right" class="key">
			<label for="entryAddress"> <?php echo JText::_( 'ADDRESS' ); ?>:</label>
		</td>
		<td><input class="text_area" type="text" name="entryAddress" id="entryAddress" size="32" maxlength="128"
				value="<?php if (isset($this->item->entryAddress)) echo $this->item->entryAddress;?>" />
		</td>
	</tr>
	
	<tr>
		<td width="160" align="right" class="key"><label for="status"><?php echo JText::_('Event status'); ?>:
		</label></td>
		<td><?php
			if (isset($this->item->statusID))
				$val = intval( $this->item->statusID ); 
			echo JHTML::_('select.genericlist',   $this->lists['statuses'], 'statusID', 'class="inputbox" size="1"', 'value', 'text', $val, '', 1); ?>
		</td>
	</tr>
	<tr>
		<td width="160" align="right" class="key"><label for="date1"><?php echo JText::_('DATE_FROM'); ?>:
		</label></td>
		<td><?php
			if (isset($this->item->date1)) 
				$val = $this->item->date1;
			echo JHTML::_('calendar', $val, 'date1', 'date1', '%Y-%m-%d', array('class'=>'inputbox required', 'size'=>'10',  'maxlength'=>'10')); ?>
		<?php echo '&nbsp;' . JHTML::tooltip(
		JText::_('Insert the starting date of the event. This field is compulsory.'),
		JText::_('Notice'),
			'tooltip.png',
			'',
			'',
		false
		);  ?>
			<?php echo '&nbsp;' . JHTML::tooltip(
			JText::_('Format: yyyy-mm-dd'),
			JText::_('Warning'),
			'warning.png',
			'',
			'',
			false
			);  ?></td>
	</tr>
	<tr>
		<td width="160" align="right" class="key"><label for="date2"><?php echo JText::_('DATE_TO'); ?>:
		</label></td>
		<td><?php
			if (isset($this->item->date2)) {
				$val = $this->item->date2;
			} else {
				$val = null;
			}
			echo JHTML::_('calendar', $val, 'date2', 'date2', '%Y-%m-%d', array('class'=>'inputbox', 'size'=>'10',  'maxlength'=>'10')); ?>
			<?php echo '&nbsp;' . JHTML::tooltip(
			JText::_('Format: yyyy-mm-dd'),
			JText::_('Warning'),
			'warning.png',
			'',
			'',
			false
			);  ?>
		</td>
	</tr>
	<tr>
		<td width="160" align="right" class="key">
			<label for="date3"> <?php echo JText::_('DATE3'); ?>:</label>
		</td>
		<td><?php
			if (isset($this->item->date3)) { 
				$val = $this->item->date3;
			} else {
				$val = null;
			}
			echo JHTML::_('calendar', $val, 'date3', 'date3', '%Y-%m-%d', array('class'=>'inputbox', 'size'=>'10',  'maxlength'=>'10')); ?>
			<?php echo '&nbsp;' . JHTML::tooltip(
			JText::_('Format: yyyy-mm-dd'),
			JText::_('Warning'),
			'warning.png',
			'',
			'',
			false
			);  ?>
		</td>
	</tr>
	<tr>
		<td width="160" align="right" class="key"><label for="from_time"><?php echo JText::_('FROM_TIME'); ?>:
		</label></td>
		<td><input class="text_area" type="text" name="from_time"
			id="from_time" size="5" maxlength="5"
			value="<?php if (isset($this->item->from_time) && $this->item->from_time) { echo JHTML::_('date', $this->item->from_time, '%H:%M', 0); } ?>" />
			<?php echo '&nbsp;' . JHTML::tooltip(
			JText::_('Format: HH:MM'),
			JText::_('Warning'),
			'warning.png',
			'',
			'',
			false
			);  ?>
		</td>
	</tr>
	<tr>
		<td width="160" align="right" class="key"><label for="to_time"><?php echo JText::_('TO_TIME'); ?>:
		</label></td>
		<td><input class="text_area" type="text" name="to_time" id="to_time" size="5" maxlength="5" value="<?php if (isset($this->item->to_time) && $this->item->to_time) { echo JHTML::_('date', $this->item->to_time, '%H:%M', 0); } ?>" />
			<?php echo '&nbsp;' . JHTML::tooltip(
			JText::_('Format: HH:MM'),
			JText::_('Warning'),
			'warning.png',
			'',
			'',
			false
			);  ?>
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
		<td width="160" align="right" class="key"><label for="category"><?php echo JText::_('CATEGORY'); ?>:
		</label></td>
		<td><?php 
			if (isset($this->item->categoryID)) {
				$val = intval( $this->item->categoryID );
			} else {
				$val = null;
			} 
			echo JHTML::_('select.genericlist',   $this->lists['categories'], 'categoryID', 'class="inputbox" size="1"', 'value', 'text', $val, '', 1); ?>
		</td>
	</tr>
	<tr>
		<td width="160" align="right" class="key"><label for="published"><?php echo JText::_('PUBLISHED'); ?>:
		</label></td>
		<td><?php 
			if ( isset($this->item->published) ) {
				if ( $this->user->gid >= $this->config->frontend_edit_gid ) {
					echo $this->lists['published']; 
					echo '&nbsp;' . JHTML::tooltip(
						JText::_('Documents must be published to be visibile to the users of your site'),
						JText::_('Warning'),
						'warning.png',
						'',
						'',
						false ); 
				} else {
					if ( $this->lists['isNew'] ) {
						if ( $this->config->frontend_auto_publish && !$this->config->allow_unregistered_submission ) {
							echo JHTML::image( 'administrator/images/tick.png', JText::_('Yes') );
							echo '<input type="hidden" name="published" id="published" value="1" />';
						} else {
							echo JHTML::image( 'administrator/images/publish_x.png', JText::_('No') );
							echo '<input type="hidden" name="published" id="published" value="0" />';
						}
					} else {
						if ( $this->item->published ) {
							if ( $this->user->id == $this->item->userid ) {
								echo $this->lists['published']; 
							} else {
								echo JHTML::image( 'administrator/images/tick.png', JText::_('Yes') );
								echo '<input type="hidden" name="published" id="published" value="1" />';
							}
						} else {
							echo JHTML::image( 'administrator/images/publish_x.png', JText::_('No') );
							echo '<input type="hidden" name="published" id="published" value="0" />';
						}
					}
				}
			}
			?>
		</td>
	</tr>
	<tr>
		<td width="160" align="right" class="key"><label for="isPrivate"><?php echo JText::_('ENTRYISPRIVATE'); ?>:
		</label></td>
		<td><?php if (isset($this->item->entryIsPrivate)) echo $this->lists['isPrivate']; ?>
		<?php echo '&nbsp;' . JHTML::tooltip(
			JText::_('Private events are only visible to registered members of the site.'),
			JText::_('Notice'),
			'tooltip.png',
			'',
			'',
			false
			);  ?></td>
	</tr>
	<?php if( $this->config->currency != ''): ?>
	<tr>
		<td width="160" align="right" class="key"><label for="price"><?php echo JText::_('PRICE'); ?>:
		</label></td>
		<td>
			<?php echo $this->config->currency . ' '; ?>
			<input class="text_area" type="text" name="price" id="price" size="10" value="<?php if (isset($this->item->price)) echo $this->item->price ?>" />
			<?php if (isset($this->item->currency)) echo $this->config->currency ?>
		</td>
	</tr>
	<?php endif; ?>
	<?php if ( $this->config->use_recaptcha ): ?>
	<tr>
		<td width="160" align="right" class="key">
			<label for="alternate2"> <?php echo JText::_( 'Spam prevention' ); ?>:</label>
		</td>
		<td>
			<?php
			$script = "
			var RecaptchaOptions = {
			    theme : 'clean'
			};
			";
			$document->addScriptDeclaration($script);
			$publickey = $this->config->recaptcha_public; // you got this from the signup page
          	echo recaptcha_get_html($publickey);
			?>			
		</td>
	</tr>
	<?php endif; ?>
</table>
<?php
echo $tabs->endPanel();
//		/* Contact tab */
echo $tabs->startPanel(JText::_('ORGANIZER'), 'additional-data');
 ?>
<table id="sc_form" class="admintable">
	<?php if ( $this->lists['showGroups'] ) : ?>
	<tr>
		<td width="160" align="right" class="key"><label for="group"><?php echo JText::_('ORGANIZER'); ?>:
		</label></td>
		<td><?php if (isset($this->item->entryGroupID)) echo $this->lists['groups']; ?>
		<?php echo JHTML::_('image', 'administrator/components/com_simplecalendar/assets/images/load_small.gif', JText::_('Loading' ), array('title'=>JText::_('Loading'), 'style'=>'visibility:hidden;', 'id'=>'ajax_load')) ?>
		</td>
	</tr>
	<?php endif; ?>
	<tr>
		<td width="160" align="right" class="key"><label for="contactName"><?php echo JText::_('CONTACTNAME'); ?>:
		</label></td>
		<td><input class="text_area" type="text" name="contactName"	id="contactName" size="44" maxlength="250" value="<?php if (isset($this->item->contactName)) echo $this->item->contactName;?>" /></td>
	</tr>
	<tr>
		<td width="160" align="right" class="key">
		<label for="contactEmail"> <?php echo JText::_('CONTACTEMAIL'); ?>:
		</label></td>
		<td><input class="text_area validate-email" type="text" name="contactEmail" id="contactEmail" size="44" maxlength="250" value="<?php if (isset($this->item->contactEmail)) echo $this->item->contactEmail;?>" /></td>
	</tr>
	<tr>
		<td width="160" align="right" class="key"><label for="contactWebSite">
		<?php echo JText::_('CONTACTWEBSITE'); ?>: </label></td>
		<td><input class="text_area validate-validurl" type="text" name="contactWebSite" id="contactWebSite" size="44" maxlength="250" value="<?php if (isset($this->item->contactWebSite)) echo $this->item->contactWebSite;?>" />
		<?php echo '&nbsp;' . JHTML::tooltip(
			JText::_('Here you can insert a web page for the event. It must begin with http://'),
			JText::_('Warning'),
			'warning.png',
			'',
			'',
		false
		);  ?></td>
	</tr>
	<tr>
		<td width="160" align="right" class="key">
			<label for="contactTelephone"> <?php echo JText::_('CONTACTTELEPHONE'); ?>:</label>
		</td>
		<td><input class="text_area" type="text" name="contactTelephone" id="contactTelephone" size="44" maxlength="250" value="<?php if (isset($this->item->contactTelephone)) echo $this->item->contactTelephone;?>" /></td>
	</tr>
</table>
<?php
echo $tabs->endPanel();

echo $tabs->startPanel(JText::_('ENTRYINFO'), 'info-data');
 ?>
<table id="sc_form" class="admintable">
<?php
if ( isset($this->item->attached_file) && isset($this->item->attached_file_description) && $this->item->attached_file != '' && file_exists(JPATH_ROOT.DS.'media'.DS.'simplecalendar'.DS.$this->lists['file_info'][0]) ) { ?>
	<tr>
		<td width="160" align="right" class="key"><label for="file"><?php echo JText::_('Current attachment'); ?>:
		</label></td>
		<td>
		<?php 
			echo JHTML::image( 'components/com_simplecalendar/assets/images/attachment.gif', JText::_('Attachment') );
			$link = JURI::root(). 'media/simplecalendar/' . $this->lists['file_info'][0];
			echo '&nbsp;' . SCOutput::showFileDescription($this->item->attached_file, $link, $this->lists['file_info'][0] );
		?>
		<input type="checkbox" name="remove_attachment" />
		<?php echo '&nbsp;' . JText::_('Remove');
			echo '&nbsp;' . JHTML::tooltip(
				JText::_('Click the checkbox to remove the link to the attached file. The file will not be removed from the directory - please use the media manager to remove files from the file system'),
				JText::_('Notice'),
				'tooltip.png',
				'',
				'',
				false
			);  
}	?>
	</td>
	</tr>
	<tr>
		<td width="160" align="right" class="key"><label for="is_favourite">
			<?php echo JText::_('Favourite'); ?>:
			</label>
		</td>
		<td><?php echo $this->lists['is_favourite']; ?></td>
	</tr>
	<tr>
		<td width="160" align="right" class="key"><label for="entryInfo">
			<?php echo JText::_('Additional information'); ?>:
			</label>
		</td>
		<td></td>
	</tr>
</table>
<?php
	 // parameters : areaname, content, hidden field, width, height, rows, cols, buttons
	if (isset($this->item->entryInfo)) {
		$val = $this->item->entryInfo;
	} else {
		$val = null;
	}
	$params = array(
		'safari' => 1
	);
	echo $this->editor->display( 'entryInfo',  $val, '550', '200', '50', '50', false, $params ) ;
?>
<p>&nbsp;</p>
<?php
echo $tabs->endPanel();
echo $tabs->endPane();
?>

<p></p>
<button class="button validate" type="submit" onClick="javascript:submitbutton('save')"><?php echo JText::_('Submit'); ?></button>&nbsp;
<button class="button validate" type="reset" onClick="javascript:history.back()"><?php echo JText::_('Cancel'); ?></button>
<?php
echo JHTML::_( 'form.token' ); 
$date =& JFactory::getDate();
$today = $date->toMySQL();
if ($this->lists['isNew']) { 
?>
<input type="hidden" name="created" value="<?php echo $today;?>" />
<?php } else {?>
<input type="hidden" name="created" value="<?php echo $this->item->created;?>" />
<?php } ?>
<input type="hidden" name="modified" value="<?php echo $today;?>" />
<input type="hidden" name="task" value="submit" />
<input type="hidden" name="option" value="com_simplecalendar" />
<input type="hidden" name="id" value="<?php if (isset($this->item->id)) echo $this->item->id; ?>" />
<input type="hidden" name="userid" value="<?php if (isset($this->item->userid)) echo $this->item->userid; ?>" />
<input type="hidden" name="controller" value="entry" />
<input type="hidden" id="caller" value="frontend" />
</form>
<?php
//keep session alive while editing
JHTML::_('behavior.keepalive');

// Footer. Please do not remove.
echo SCOutput::showFooter();
?>
</div>