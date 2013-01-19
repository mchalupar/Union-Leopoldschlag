<?php
// no direct access
defined('_JEXEC') or die('Restricted access');
// JSColor color picker
$document 	= & JFactory::getDocument();
$document->addScript('components/com_simplecalendar/assets/js/jscolor/jscolor.js');

jimport('joomla.html.pane');
$val = '';
?>
<script language="javascript" type="text/javascript">

	function submitbutton(pressbutton) {
		var form = document.adminForm;

		if (pressbutton == 'cancel') {
			submitform( pressbutton );
			return;
		}

		for (var i=0; i < form.use_gmap.length; i++) {
		   if (form.use_gmap[i].checked) {
		      var use_gmap_val = form.use_gmap[i].value;
		   }
		}
		
		for (var i=0; i < form.use_recaptcha.length; i++) {
		   if (form.use_recaptcha[i].checked) {
		      var use_recaptcha_val = form.use_recaptcha[i].value;
		   }
		}

		// do field validation
		if (form.date_long_format.value == "" ) {
			alert( "<?php echo JText::_( 'A date format must be set', true ); ?>" );
		} else if ( form.date_short_format.value == "" ) {
			alert( "<?php echo JText::_( 'A date format must be set', true ); ?>" );
		} else if ( form.time_format.value == "" ) {
			alert( "<?php echo JText::_( 'A time format must be set', true ); ?>" );
		} if ( use_gmap_val == "1" && form.gmap_api_key.value == "" ) {
			alert( "<?php echo JText::_( 'You selected to use GMAP - please provide an API key!', true ); ?>" );
			form.gmap_api_key.focus();
		} else  {
			<?php
			echo $this->editor->save( 'intro_text' ) . "\n";
			?>
			submitform( pressbutton );
		}
	}

	// Reverse sort order checkbox
	function switchsortorder() {
		var form = document.adminForm;
		var value = form.reverse_sort_order.value;
		form.reverse_sort_order.value = 1 - value;
	}

	function enablefrontendadd() {
		var form = document.adminForm;
		for (var i=0; i < form.allow_unregistered_submission.length; i++) {
		   if (form.allow_unregistered_submission[i].checked) {
		      var rad_val = form.allow_unregistered_submission[i].value;
		   }
		}
		if ( rad_val == "1" ) {
			form.getElementById("frontend_add_gid").disabled = true;			
		} 
		if ( rad_val == "0" ) {
			form.getElementById("frontend_add_gid").disabled = false;
		}
	}

	
	function enablegmap() {
		var form = document.adminForm;
		for (var i=0; i < form.use_gmap.length; i++) {
		   if (form.use_gmap[i].checked) {
		      var rad_val = form.use_gmap[i].value;
		   }
		}
		if ( rad_val == "0" ) {
			form.getElementById("gmap_api_key").disabled = true;
			form.getElementById("gmap_std_latlon").disabled = true;
			for (var i=0; i < form.map_slider_open.length; i++) {
				form.map_slider_open[i].disabled = true;
			}
			
		} 
		if ( rad_val == "1" ) {
			form.getElementById("gmap_api_key").disabled = false;
			form.getElementById("gmap_std_latlon").disabled = false;
			for (var i=0; i < form.map_slider_open.length; i++) {
				form.map_slider_open[i].disabled = false;
			}

		}
	}

	function enableuserecaptcha() {
		var form = document.adminForm;
		for (var i=0; i < form.use_recaptcha.length; i++) {
		   if (form.use_recaptcha[i].checked) {
		      var rad_val = form.use_recaptcha[i].value;
		   }
		}
		if ( rad_val == "0" ) {
			form.getElementById("recaptcha_public").disabled = true;
			form.getElementById("recaptcha_private").disabled = true;			
		} 
		if ( rad_val == "1" ) {
			form.getElementById("recaptcha_public").disabled = false;
			form.getElementById("recaptcha_private").disabled = false;
		}
	}
</script>
<form action="index.php?option=com_simplecalendar&amp;controller=simplecal&amp;view=calendar" method="post" name="adminForm" id="adminForm">
<?php 
$tabs = &JPane::getInstance('tabs');
echo $tabs->startPane('settings');
echo $tabs->startPanel(JText::_('Layout'), 'Layout'); 
?>
	<fieldset class="adminform">
		<legend><?php echo JText::_( 'Calendar list view' ); ?></legend>
		<table class="admintable">
			<tr>
				<td width="180" align="right" class="key">
				<label for="frontend_link_color">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'NOTICE' ); ?>::<?php echo JText::_('CALENDAR_LINK_COLOR'); ?>">
			    	<?php echo JText::_( 'CALENDAR_LINK_COLOR' ); ?>:
			    	</span>
				</label>
				</td>
				<td>
					#<input class="color" name="frontend_link_color" id="frontend_link_color" size="8" maxlength="6" value="<?php echo $this->items->frontend_link_color;?>" />
				</td>
			</tr>
			<tr>
				<td width="180" align="right" class="key">
				<label for="show_search_bar">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'NOTICE' ); ?>::<?php echo JText::_('SHOW_SEARCH_BAR_DESC'); ?>">
			    	<?php echo JText::_( 'Show search bar' ); ?>:
			    	</span>
				</label>
				</td>
				<td>
					<?php echo $this->lists['show_search_bar']; ?>
				</td>
			</tr>
			<tr>
				<td width="180" align="right" class="key">
				<label for="show_headers">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'NOTICE' ); ?>::<?php echo JText::_('SHOW_HEADERS_DESC'); ?>">
			    	<?php echo JText::_( 'Show column headers' ); ?>:
			    	</span>
				</label>
				</td>
				<td>
					<?php echo $this->lists['show_headers']; ?>
				</td>
			</tr>
			<tr>
				<td width="180" align="right" class="key">
				<label for="show_category_color">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'NOTICE' ); ?>::<?php echo JText::_('Show categories list'); ?>">
			    	<?php echo JText::_( 'Show categories list' ); ?>:
			    	</span>
				</label>
				</td>
				<td>
					<?php echo $this->lists['show_category_color']; ?>
				</td>
			</tr>
			<tr>
				<td width="180" align="right" class="key">
				<label for="frontend_columns">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'NOTICE' ); ?>::<?php echo JText::_('Here you can select the columns that are shown in the frontend calendar list'); ?>">
			    	<?php echo JText::_( 'Select frontend columns' ); ?>:
			    	</span>
				</label>
				</td>
				<td>
					<input class="text_area" type="text" name="frontend_columns" id="frontend_columns" size="64" value="<?php echo $this->items->frontend_columns;?>" />
					<small><a href="http://software.albonico.ch/phpBB/viewtopic.php?f=3&t=115" target="_blank" title="List">&nbsp;[<?php echo JText::_('List'); ?>]</a></small>
				</td>
			</tr>
			<tr>
				<td width="180" align="right" class="key">
				<label for="intro_text">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'NOTICE' ); ?>::<?php echo JText::_('Optional introductory text that will be printed before the calendar'); ?>">
			    	<?php echo JText::_( 'Intro text' ); ?>:
			    	</span>
				</label>
				</td>
				<td>
		<?php
			// parameters : areaname, content, hidden field, width, height, rows, cols, buttons
			if (isset($this->items->intro_text))
				$val = $this->items->intro_text;
			echo $this->editor->display( 'intro_text',  $val, '100%', '200', '75', '20', '' ) ;
			?>
			</td>
			</tr>

		</table>
	</fieldset>
	<fieldset class="adminform">
		<legend><?php echo JText::_( 'Detail view' ); ?></legend>
		<table class="admintable">
			<tr>
				<td width="180" align="right" class="key">
				<label for="show_username">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'NOTICE' ); ?>::<?php echo JText::_('Username of the user that added the event is printed in the frontend detail view'); ?>">
			    	<?php echo JText::_( 'Print username in detail view' ); ?>:
			    	</span>
				</label>
				</td>
				<td>
					<?php echo $this->lists['show_username']; ?>
				</td>
			</tr>
			<tr>
				<td width="180" align="right" class="key">
				<label for="show_category_in_detail">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'NOTICE' ); ?>::<?php echo JText::_('Category name and link is printed in the frontend detail view'); ?>">
			    	<?php echo JText::_( 'Show category link' ); ?>:
			    	</span>
				</label>
				</td>
				<td>
					<?php echo $this->lists['show_category_in_detail']; ?>
				</td>
			</tr>
			<tr>
				<td width="180" align="right" class="key">
				<label for="show_additionalinfo_label">
			    	<?php echo JText::_( 'Show "Additional Information" label' ); ?>:
			    	</span>
				</label>
				</td>
				<td>
					<?php echo $this->lists['show_additionalinfo_label']; ?>
				</td>
			</tr>

		</table>
	</fieldset>
	<fieldset class="adminform">
		<legend><?php echo JText::_( 'PDF' ); ?></legend>
		<table class="admintable">
			<tr>
				<td width="180" align="right" class="key">
				<label for="pdf_columns">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'NOTICE' ); ?>::<?php echo JText::_('Here you can select the columns that are shown in the auto-generated PDF file'); ?>">
			    	<?php echo JText::_( 'Select PDF columns' ); ?>:
			    	</span>
				</label>
				</td>
				<td>
					<input class="text_area" type="text" name="pdf_columns" id="pdf_columns" size="64" value="<?php echo $this->items->pdf_columns;?>" />
					<small><a href="http://software.albonico.ch/phpBB/viewtopic.php?f=3&t=115" target="_blank" title="List">&nbsp;[<?php echo JText::_('List'); ?>]</a></small>
				</td>
			</tr>
		</table>
	</fieldset>
	<fieldset class="adminform">
		<legend><?php echo JText::_( 'Custom fields' ); ?></legend>
		<table class="admintable">
			<tr>
				<td width="180" align="right" class="key">
				<label for="pdf_columns">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'NOTICE' ); ?>::<?php echo JText::_('Additional, customizable field available in detail view'); ?>">
			    	<?php echo JText::_( 'Label for Custom field 1' ); ?>:
			    	</span>
				</label>
				</td>
				<td>
					<input class="text_area" type="text" name="custom1_label" id="custom1_label" size="64" maxlength="64" value="<?php echo $this->items->custom1_label;?>" />
				</td>
			</tr>
			<tr>
			<td width="180" align="right" class="key">
				<label for="pdf_columns">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'NOTICE' ); ?>::<?php echo JText::_('Additional, customizable field available in detail view'); ?>">
			    	<?php echo JText::_( 'Label for Custom field 2' ); ?>:
			    	</span>
				</label>
				</td>
				<td>
					<input class="text_area" type="text" name="custom2_label" id="custom2_label" size="64" maxlength="64" value="<?php echo $this->items->custom2_label;?>" />
				</td>
			</tr>
		</table>
	</fieldset>

<?php
echo $tabs->endPanel();
echo $tabs->startPanel(JText::_('Authorizations'), 'Authorizations');
?>
	<fieldset class="adminform">
		<legend><?php echo JText::_( 'Frontend' ); ?></legend>
		<table class="admintable">
			<tr>
				<td width="180" align="right" class="key">
				<label for="allow_unregistered_submission">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'NOTICE' ); ?>::<?php echo JText::_('All events posted by unregistered users need to be published by an Administrator'); ?>">
			    	<?php echo JText::_( 'Allow unregistered users to submit an event (WARNING)' ); ?>:
			    	</span>
				</label>
				</td>
				<td>
					<?php echo $this->lists['allow_unregistered_submission']; ?>
				</td>
			</tr>

			<tr>
				<td width="180" align="right" class="key">
				<label for="frontend_add_gid">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'NOTICE' ); ?>::<?php echo JText::_('SHOW_FRONTEND_ADD_PERMISSION'); ?>">
			    	<?php echo JText::_( 'Frontend add permissions' ); ?>:
			    	</span>
				</label>
				</td>
				<td>
					<?php echo $this->lists['auth_add']; ?>
				</td>
			</tr>
			<tr>
				<td width="180" align="right" class="key">
				<label for="frontend_edit_gid">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'NOTICE' ); ?>::<?php echo JText::_('SHOW_FRONTEND_EDIT_PERMISSION'); ?>">
			    	<?php echo JText::_( 'Frontend edit permissions' ); ?>:
			    	</span>
				</label>
				</td>
				<td>
					<?php echo $this->lists['auth_edit']; ?>
				</td>
			</tr>
			<tr>
				<td width="180" align="right" class="key">
				<label for="allow_owner_edit">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'NOTICE' ); ?>::<?php echo JText::_('Users can change their own events'); ?>">
			    	<?php echo JText::_( 'Registered users can change the events they insert' ); ?>:
			    	</span>
				</label>
				</td>
				<td>
					<?php echo $this->lists['allow_owner_edit']; ?>
				</td>
			</tr>
		</table>
	</fieldset>
	<fieldset class="adminform">
		<legend><?php echo JText::_( 'Frontend list view' ); ?></legend>
		<table class="admintable">
			<tr>
				<td width="180" align="right" class="key">
				<label for="detailview_registered_only">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'NOTICE' ); ?>::<?php echo JText::_('The detail view is available only to registered users of the site'); ?>">
			    	<?php echo JText::_( 'Detail view only for registered users' ); ?>:
			    	</span>
				</label>
				</td>
				<td>
					<?php echo $this->lists['detailview_registered_only']; ?>
				</td>
			</tr>
		</table>
	</fieldset>
	<fieldset class="adminform">
		<legend><?php echo JText::_( 'Auto-publish' ); ?></legend>
		<table class="admintable">
			<tr>
				<td width="180" align="right" class="key">
				<label for="frontend_auto_publish">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'NOTICE' ); ?>::<?php echo JText::_('FRONTEND_AUTO_PUBLISH_DESC'); ?>">
			    	<?php echo JText::_( 'Auto-publish events from frontend' ); ?>:
			    	</span>
				</label>
				</td>
				<td>
					<?php echo $this->lists['frontend_auto_publish']; ?>
				</td>
			</tr>
		</table>
	</fieldset>
	<fieldset class="adminform">
	<legend><?php echo JText::_( 'Use reCAPTCHA' ); ?></legend>
	<table class="admintable">
		<tr>
			<td width="100" align="right" class="key">
				<span class="editlinktip hasTip" title="<?php echo JText::_( 'NOTICE' ); ?>::<?php echo JText::_('Use reCAPTCHA field to prevent spam submissions through the frontend add event form'); ?>">
				<?php echo JText::_( 'Use reCAPTCHA' ); ?>:
				</span>
			</td>
			<td>
				<?php echo $this->lists['use_recaptcha']; ?>
			</td>
		</tr>
		<tr>
			<td width="100" align="right" class="key">
				<?php echo JText::_( 'reCAPTCHA public key' ); ?>:
			</td>
			<td>
				<input class="text_area" type="text" name="recaptcha_public" id="recaptcha_public" size="64" maxlength="100" value="<?php echo $this->items->recaptcha_public;?>" />
				&nbsp;&nbsp;<a href="https://www.google.com/recaptcha/admin/create" target="_blank">[<?php echo JText::_('Signup'); ?>]</a>
			</td>
		</tr>
		<tr>
			<td width="100" align="right" class="key">
				<?php echo JText::_( 'reCAPTCHA private key' ); ?>:
			</td>
			<td>
				<input class="text_area" type="text" name="recaptcha_private" id="recaptcha_private" size="64" maxlength="100" value="<?php echo $this->items->recaptcha_private;?>" />
			</td>
		</tr>
	</table>
	</fieldset>

<?php
echo $tabs->endPanel();
echo $tabs->startPanel(JText::_('GoogleMaps'), 'GoogleMaps');
?>
		<table class="admintable">
			<tr>
				<td width="180" align="right" class="key">
				<label for="use_gmap">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'NOTICE' ); ?>::<?php echo JText::_('Enable the use of Google Maps (requires API key from Google)'); ?>">
			    	<?php echo JText::_( 'USE_GOOGLE_MAPS' ); ?>:
			    	</span>
				</label>
				</td>
				<td>
					<?php echo $this->lists['use_gmap']; ?>
				</td>
			</tr>
			<tr>
				<td width="180" align="right" class="key">
				<label for="gmap_api_key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'NOTICE' ); ?>::<?php echo JText::_('GOOGLE_MAPS_API_KEY'); ?>">
			    	<?php echo JText::_( 'GOOGLE_MAPS_API_KEY' ); ?>:
				    </span>
				</label>
				</td>
				<td>
					<input class="text_area" type="text" name="gmap_api_key" id="gmap_api_key" size="50" maxlength="" value="<?php echo $this->items->gmap_api_key;?>" />
					&nbsp;<small><a href="http://code.google.com/apis/maps/signup.html" target="_blank">[<?php echo JText::_('Request API key'); ?>]</a></small>
				</td>
			</tr>
			<tr>
				<td width="180" align="right" class="key">
				<label for="gmap_std_latlon">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'NOTICE' ); ?>::<?php echo JText::_('GMAP_STD_LATLON'); ?>">
			    	<?php echo JText::_( 'gmap_std_latlon' ); ?>:
				    </span>
				</label>
				</td>
				<td>
					<input class="text_area" type="text" name="gmap_std_latlon" id="gmap_std_latlon" size="64" maxlength="" value="<?php echo $this->items->gmap_std_latlon;?>" />
				</td>
			</tr>
			<tr>
				<td width="180" align="right" class="key">
				<label for="map_slider_open">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'NOTICE' ); ?>::<?php echo JText::_('Choose whether to have the map menu open (yes) or closed (no) as the default value in detail view'); ?>">
			    	<?php echo JText::_( 'MAP_SLIDER_OPEN' ); ?>:
			    	</span>
				</label>
				</td>
				<td>
					<?php echo $this->lists['map_slider_open']; ?>
				</td>
			</tr>
		</table>
<?php
echo $tabs->endPanel();
echo $tabs->startPanel(JText::_('Formats Handling'), 'Formats_Handling');
?>
	<fieldset class="adminform">
		<legend><?php echo JText::_( 'DateTime Handling' ); ?></legend>
		<table class="admintable">
			<tr>
				<td width="180" align="right" class="key">
				<label for="date_long_format">
			    	<?php echo JText::_( 'DATE_FORMAT' ); ?>:
				</label>
				</td>
				<td>
					<input class="text_area" type="text" name="date_long_format" id="date_long_format" size="16" maxlength="64" value="<?php echo $this->items->date_long_format;?>" />
					&nbsp;<a href="http://www.php.net/strftime" target="_blank"><?php echo JText::_('[strftime]') ?></a>
				</td>
			</tr>
			<tr>
				<td width="180" align="right" class="key">
				<label for="date_short_format">
					<?php echo JText::_( 'SHORT_DATE_FORMAT' ); ?>:
				</label>
				</td>
				<td>
					<input class="text_area" type="text" name="date_short_format" id="date_short_format" size="16" maxlength="64" value="<?php echo $this->items->date_short_format;?>" />
					&nbsp;<a href="http://www.php.net/strftime" target="_blank"><?php echo JText::_('[strftime]') ?></a>
				</td>
			</tr>
			<tr>
				<td width="180" align="right" class="key">
				<label for="time_format">
			    	<?php echo JText::_( 'TIME_FORMAT' ); ?>:
				</label>
				</td>
				<td>
					<input class="text_area" type="text" name="time_format" id="time_format" size="16" maxlength="64" value="<?php echo $this->items->time_format;?>" />
					&nbsp;<a href="http://www.php.net/strftime" target="_blank"><?php echo JText::_('[strftime]') ?></a>
				</td>
			</tr>
		</table>
	</fieldset>
	<fieldset class="adminform">
		<legend><?php echo JText::_( 'vCAL/iCal encoding' ); ?></legend>
		<table class="admintable">
			<tr>
				<td width="180" align="right" class="key">
				<label for="vcal_encoding">
			    	<?php echo JText::_( 'Encoding' ); ?>:
				</label>
				</td>
				<td>
					<input class="text_area" type="text" name="vcal_encoding" id="vcal_encoding" size="16" maxlength="32" value="<?php echo $this->items->vcal_encoding;?>" />
					<?php echo JHTML::tooltip( JText::_('Do not change this unless you experience problems with the UTF-8 encoding. Wrong encoding type may result in invalid vCal/iCAL files!'),
						JText::_('Warning'),
							'warning.png',
							'',
							'',
						false
						); ?>
				</td>
			</tr>
		</table>
		</fieldset>
		<?php
echo $tabs->endPanel();
echo $tabs->startPanel(JText::_('Component-specific settings'), 'Component-specific settings');
?>
	<fieldset class="adminform">
		<legend><?php echo JText::_( 'Miscellaneous' ); ?></legend>
		<table class="admintable">
			<tr>
				<td width="180" align="right" class="key">
				<label for="currency">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'NOTICE' ); ?>::<?php echo JText::_('Sets the optional currency for price display in event'); ?>">
			    	<?php echo JText::_( 'Currency' ); ?>:
			    	</span>
				</label>
				</td>
				<td>
					<input class="text_area" type="text" name="currency" id="currency" size="16" maxlength="32" value="<?php echo $this->items->currency;?>" />
				</td>
			</tr>
		</table>
	</fieldset>
<?php if (file_exists(JPATH_SITE.DS.'components'.DS.'com_jcomments'.DS.'jcomments.php')) : ?>
	<fieldset class="adminform">
		<legend><?php echo JText::_( 'Third-party components' ); ?></legend>
		<table class="admintable">
			<tr>
				<td width="180" align="right" class="key">
				<label for="use_jcomments">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'NOTICE' ); ?>::<?php echo JText::_('Enables the use of JComments commenting system in event details'); ?>">
			    	<?php echo JText::_( 'Use JComments' ); ?>:
			    	</span>
				</label>
				</td>
				<td>
					<?php echo $this->lists['use_jcomments']; ?>
				</td>
			</tr>
		</table>
	</fieldset>
<?php endif; ?>
	<fieldset class="adminform">
		<legend><?php echo JText::_( 'Administrative tools' ); ?></legend>
		<table class="admintable">
			<tr>
				<td width="180" align="right" class="key">
				<label for="delete_on_uninstall">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'WARNING' ); ?>::<?php echo JText::_('Remove all SimpleCalendar DB tables upon uninstallation of the component'); ?>">
			    	<?php echo JText::_( 'Delete DB tables on uninstallation' ); ?>:
			    	</span>
				</label>
				</td>
				<td>
					<?php echo $this->lists['delete_on_uninstall'] . '&nbsp;'; ?>
					<?php echo JHTML::tooltip( JText::_('By selecting YES, on the next uninstallation of this component all DB tables will be deleted! Please be aware that all data will be lost by doing so.'),
						JText::_('Warning'),
							'warning.png',
							'',
							'',
						false
						); ?>
				</td>
			</tr>
		</table>
	</fieldset>
	<input type="hidden" name="option" value="com_simplecalendar" />
	<input type="hidden" name="id" value="<?php echo $this->items->id; ?>" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="controller" value="settings" />
	<?php echo JHTML::_( 'form.token' ); ?>
<?php
echo $tabs->endPanel();
echo $tabs->startPanel(JText::_('Informations'), 'Informations');
?>
		<table class="admintable">
			<tr>
				<td width="180" align="right" class="key">
					<?php echo JText::_( 'Version control' ); ?>:
				</td>
				<td>
					<!-- This version: <br />
					Latest version: <br /> -->
					[UNDER CONSTRUCTION]
				</td>
			</tr>
			<tr>
				<td width="180" align="right" class="key">
					<?php echo JText::_( 'Design by' ); ?>:
				</td>
				<td>
					Fabrizio Albonico
				</td>
			</tr>
			<tr>
				<td width="180" align="right" class="key">
					<?php echo JText::_( 'Contact' ); ?>:
				</td>
				<td>
					Website: <a href="http://software.albonico.ch" target="_blank">http://software.albonico.ch</a><br />
					Support forum: <a href="http://software.albonico.ch/phpBB" target="_blank">http://software.albonico.ch/phpBB</a><br />
				</td>
			</tr>

			<tr>
				<td width="180" align="right" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'NOTICE' ); ?>::<?php echo JText::_('SHOW_DONATION_LINE_DESCR'); ?>">
			    		<?php echo JText::_( 'show_donation_line' ); ?>:
					</span>
				</td>
				<td>
					<?php echo $this->lists['show_donation_line']; ?>&nbsp;&nbsp;
				</td>
			</tr>
		</table>
</form>
<div id="donationBox" style="width: 450px; margin-left: 10px; border: 1px solid #007AA6; padding: 5px;"> 
	<div style="float: right; clear: right; margin-left: 10px; margin-bottom: 10px; margin-top: 10px;">
		<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
			<script language="javascript" type="text/javascript">
				var id = '0';
				id = '<?php echo $this->items->show_donation_line ?>';
				window.onDomReady = checkDonationBox(id);
				window.onDomReady = enablegmap();
				window.onDomReady = enablefrontendadd();				
				
				function checkDonationBox(id) {
					switch (id) {
						case '0':
							document.getElementById('donationBox').style.display = 'inherit';
							break;
						case '1':
							document.getElementById('donationBox').style.display = 'none';
							break;
					}
				}
			</script>
			<input type="hidden" name="business" value="J3Y59R335NA3E">
			<input type="hidden" name="cmd" value="_donations">
			<input type="hidden" name="item_name" value="SimpleCalendar project - Donation">
			<input type="hidden" value="0" name="undefined_quantity"/>
			<input type="hidden" value="utf-8" name="charset"/>
			<input type="hidden" value="1" name="no_shipping"/>
			<input type="text" style="text-align: right;" value="" maxlength="10" size="4" name="amount"/>
			<select name="currency_code">
				<option value="EUR">EUR</option>
				<option value="CHF">CHF</option>
				<option value="USD">USD</option>
				<option value="GBP">GBP</option>
			</select>	
			<br />
			<input type="image" name="submit" style="border: 0px; padding-top: 5px;" src="https://www.paypal.com/en_US/i/btn/btn_donate_LG.gif" alt="PayPal - The safer, easier way to pay online">
			<img alt="" width="1" height="1" src="https://www.paypal.com/en_US/i/scr/pixel.gif" >
		</form>
	</div>
	<p>This component is brought to you for free by <a href="http://software.albonico.ch/">Fabrizio Albonico</a>. If you feel that this component suits your needs and are happy with it, I kindly encourage you to donate a small amount (a couple of Euros, or the equivalent in your currency) in order to keep up with the costs associated in running a website and for the time spent developing features you request and answering to your questions in the <a href="http://software.albonico.ch/phpBB">support forum</a>.</p>
	<p>You can use the PayPal button on the right. No problems if you don't, and THANK YOU very much if you do or did!</p>
	<p><i>Fabrizio Albonico</i></p>
</div>
	<?php
echo $tabs->endPanel();
echo $tabs->endPane();
?>
