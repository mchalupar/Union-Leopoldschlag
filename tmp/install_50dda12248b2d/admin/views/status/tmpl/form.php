<?php
defined('_JEXEC') or die('Restricted access');
// JSColor color picker
$document 	= & JFactory::getDocument();
$document->addScript('components/com_simplecalendar/assets/js/jscolor/jscolor.js');

?>

<script type="text/javascript">
	function submitbutton(pressbutton) {
		var form = document.adminForm;
		if (pressbutton == 'cancel') {
			submitform( pressbutton );
			return;
		}

		// do field validation
		if (form.status_description.value == ""){
			alert( "<?php echo JText::_( 'Please specify a status description', true ); ?>" );
		} else {
			submitform( pressbutton );
		}
	}
</script>

<form action="index.php?option=com_simplecalendar&amp;controller=status&amp;view=statuses" method="post" name="adminForm" id="adminForm">
<div class="col100">
	<fieldset class="adminform">
		<legend><?php echo JText::_( 'DETAILS' ); ?></legend>

		<table class="admintable">
		<tr>
			<td width="100" align="right" class="key">
				<label for="status_description">
					<?php echo JText::_( 'Status description' ); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="status_description" id="status_description" size="32" maxlength="250" value="<?php if (isset($this->item->status_description)) echo $this->item->status_description;?>" />
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
				<label for="status_color">
					<?php echo JText::_( 'Status color' ); ?>:
				</label>
			</td>
			<td>
				#<input type="text" class="color" name="status_color" id="status_color" size="8" maxlength="6" value="<?php  if (isset($this->item->status_color)) echo $this->item->status_color;?>" />
			</td>
		</tr>
	</table>
	</fieldset>
</div>
<div class="clr"></div>

<?php echo JHTML::_( 'form.token' ); ?>

<input type="hidden" name="catid" id="catid" value="<?php if (isset($this->item->catid)) echo $this->item->catid; ?>" />
<input type="hidden" name="option" value="com_simplecalendar" />
<input type="hidden" name="statusID" value="<?php if (isset($this->item->statusID)) echo $this->item->statusID; ?>" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="controller" value="status" />
</form>
<?php
//keep session alive while editing
JHTML::_('behavior.keepalive');

// Footer. Please do not remove.
echo SCOutput::showFooter();
?>