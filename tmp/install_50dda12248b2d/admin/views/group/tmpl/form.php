<?php

defined('_JEXEC') or die('Restricted access');
?>

<script type="text/javascript">
	function submitbutton(pressbutton) {
		var form = document.adminForm;
		if (pressbutton == 'cancel') {
			submitform( pressbutton );
			return;
		}

		// do field validation
		if (form.groupName.value == ""){
			alert( "<?php echo JText::_( 'Please specify the group name', true ); ?>" );
		} else if (form.contactWebSite.value != "" && form.contactWebSite.value.substr(0,7) != "http://"){
			alert( "<?php echo JText::_( 'Wrong website specified. It must begin with http://', true ); ?>" );
		}  else {
			submitform( pressbutton );
		}
	}
</script>
<form action="index.php?option=com_simplecalendar&amp;controller=group&amp;view=groups" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
<div class="col width-60">
	<fieldset class="adminform">
		<legend><?php echo JText::_( 'DETAILS' ); ?></legend>

		<table class="admintable">
		<tr>
			<td width="100" align="right" class="key">
				<label for="name">
					<?php echo JText::_( 'GROUPNAME' ); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="groupName" id="groupName" size="64" maxlength="250" value="<?php if (isset($this->item->groupName)) echo $this->item->groupName;?>" />
			</td>
		</tr>
		<tr>
			<td width="100" align="right" class="key">
				<label for="alias">
					<?php echo JText::_( 'Alias' ); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="alias" id="alias" size="32" maxlength="64" value="<?php if (isset($this->item->alias)) echo $this->item->alias;?>" />
			</td>
		</tr>

		<tr>
			<td width="100" align="right" class="key">
				<label for="groupAbbr">
					<?php echo JText::_( 'GROUPABBR' ); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="groupAbbr" id="groupAbbr" size="6" maxlength="6" value="<?php if (isset($this->item->groupAbbr)) echo $this->item->groupAbbr;?>" />
			</td>
		</tr>
	</table>
	</fieldset>
	<fieldset class="adminform">
		<legend><?php echo JText::_( 'CONTACTINFO' ); ?></legend>
		<table class="admintable">
		<tr>
			<td width="100" align="right" class="key">
				<label for="contactName">
					<?php echo JText::_( 'CONTACTNAME' ); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="contactName" id="contactName" size="32" maxlength="250" value="<?php if (isset($this->item->contactName)) echo $this->item->contactName;?>" />
			</td>
		</tr>
		<tr>
			<td width="100" align="right" class="key">
				<label for="contactEmail">
					<?php echo JText::_( 'CONTACTEMAIL' ); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="contactEmail" id="contactEmail" size="64" maxlength="250" value="<?php if (isset($this->item->contactEmail)) echo $this->item->contactEmail;?>" />
			</td>
		</tr>
		<tr>
			<td width="100" align="right" class="key">
				<label for="contactWebSite">
					<?php echo JText::_( 'CONTACTWEBSITE' ); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="contactWebSite" id="contactWebSite" size="64" maxlength="250" value="<?php if (isset($this->item->contactWebSite))  echo $this->item->contactWebSite;?>" />
			</td>
		</tr>
		<tr>
			<td width="100" align="right" class="key">
				<label for="contactTelephone">
					<?php echo JText::_( 'CONTACTTELEPHONE' ); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="contactTelephone" id="contactTelephone" size="64" maxlength="250" value="<?php if (isset($this->item->contactTelephone)) echo $this->item->contactTelephone;?>" />
			</td>
		</tr>
	</table>
	</fieldset>

	<fieldset class="adminform">
		<legend><?php echo JText::_( 'Logo image upload' ); ?></legend>
		<table class="admintable">
		<tr>
			<?php if ( isset($this->item->imagefile) ): ?>
			<td width="100" align="right" class="key">
				<label for="imagefile">
					<?php echo JText::_( 'Current logo image' ); ?>:
				</label>
			</td>
			<td>
				<?php echo JHTML::_('image', 'images/simplecalendar/' . $this->item->imagefile, JText::_( 'LOGO' ), array('align' => 'absolutemiddle', 'title'=>JText::_( 'LOGO' ))); ?>
			</td>
		</tr>
		<?php endif; ?><!--
		<tr>
			<td width="100" align="right" class="key">
				<label for="imagefile">
					<?php echo JText::_( 'Logo image file' ); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="file" name="imagefile" id="imagefile" size="40" value="" /><br />
				<?php echo JText::_('Allowed extensions: JPG, GIF, PNG'."\n"); ?>
			</td>
		</tr>
		--></table>
	</fieldset>
</div>
	
<div class="col width-40">
<fieldset class="adminform">
<legend><?php echo JText::_( 'GoogleMaps' ); ?></legend>
<?php
if( $this->config->use_gmap == 1 && $this->config->gmap_api_key != '') {
?>
<div id="map_canvas" style="width: 100%; height: 300px"></div>
<p>
<?php 
	if( $this->lists['isNew'] || $this->item->entryLatLon == '' ) { ?>
			<a id="btnSet"
				href="<?php echo JRoute::_('../index.php?option=com_simplecalendar&view=form&task=edit&layout=gmap&tmpl=component'); ?>"
				class="modal"
				rel="{handler: 'iframe', size: {x: 630, y: 550}}"
				style="visibility: visible;" ><?php echo JText::_('Set position');?></a>
			<a id="btnModify"
				href="<?php echo JRoute::_('../index.php?option=com_simplecalendar&view=form&task=edit&layout=gmap&tmpl=component'); ?>"
				class="modal"
				rel="{handler: 'iframe', size: {x: 630, y: 550}}"
				style="visibility: hidden;" ><?php echo JText::_('Modify position');?></a>
<?php } else { ?>
			<a id="btnSet"
				href="<?php echo JRoute::_('../index.php?option=com_simplecalendar&view=form&task=edit&layout=gmap&tmpl=component'); ?>"
				class="modal"
				rel="{handler: 'iframe', size: {x: 630, y: 550}}"
				style="display: none;" ><?php echo JText::_('Set position');?></a>
			<a id="btnModify"
				href="<?php echo JRoute::_('../index.php?option=com_simplecalendar&view=form&task=edit&layout=gmap&tmpl=component'); ?>"
				class="modal"
				rel="{handler: 'iframe', size: {x: 630, y: 550}}"><?php echo JText::_('Modify position');?></a>
<?php }
 ?>
		
<input class="text_area" type="text" name="groupLatLon" id="groupLatLon" style="visibility: hidden;" size="64" maxlength="250" value="<?php if (isset($this->item->groupLatLon)) echo $this->item->groupLatLon;?>" onchange="setLatLon(this.value);" />
</p>
<?php
} else {
	echo JText::_('GOOGLEMAPS_IS_NOT_ENABLED'); ?>
	<input class="text_area" type="text" name="groupLatLon" id="groupLatLon" style="visibility: hidden;" value="<?php if (isset($this->item->groupLatLon)) echo $this->item->groupLatLon;?>" />
	<?php
}
?>
</fieldset>
</div>

<div class="clr"></div>
<?php echo JHTML::_( 'form.token' ); ?>
<input type="hidden" name="catid" id="catid" value="<?php if (isset($this->item->catid)) echo $this->item->catid; ?>" />
<input type="hidden" name="option" value="com_simplecalendar" />
<input type="hidden" name="groupID" value="<?php if (isset($this->item->groupID)) echo $this->item->groupID; ?>" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="controller" value="group" />
<input type="hidden" id="caller" value="backend_group" />
</form>
<?php
//keep session alive while editing
JHTML::_('behavior.keepalive');
// Footer. Please do not remove.
echo SCOutput::showFooter();
?>