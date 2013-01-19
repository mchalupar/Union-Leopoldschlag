<?php
// No direct access
defined('_JEXEC') or die('Restricted access');
?>
<form action="index.php?option=com_simplecalendar&amp;controller=calendar&amp;view=calendar" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data" >
<div class="col width-60">
  <fieldset class="adminform">
    <legend><?php echo JText::_( 'File' ); ?></legend>

    <table class="admintable">
    <tr>
      <td width="170px" align="right" class="key">
        <?php echo JText::_('CSV file to import') . ':'; ?>
      </td>
      <td>
      	<input type="file" name="csvfile" />
      </td>
    </tr>
	</table>
  </fieldset>
    <fieldset class="adminform">
    <legend><?php echo JText::_( 'Options' ); ?></legend>

    <table class="admintable">
    <tr>
      <td width="170px" align="right" class="key">
        <?php echo JText::_('Delimiter') . ':'; ?>
      </td>
      <td>
      	<input type="text" size=2 name="delimiter" value=";">
      </td>
    </tr>
    <tr>
      <td width="170px" align="right" class="key">
        <?php echo JText::_('Start from line') . ':'; ?>
      </td>
      <td>
      	<input type="text" size=2 name="record_number" value="1">
      </td>
    </tr>
        <tr>
      <td width="170px" align="right" class="key">
        <?php echo JText::_('Category (if not assigned)') . ':'; ?>
      </td>
      <td>
      	<?php echo JHTML::_('select.genericlist',   $this->lists['categories'], 'categoryID', 'class="inputbox" size="1"', 'value', 'text', '', '', 1); ?>
      </td>
    </tr>
    <tr>
      <td width="170px" align="right" class="key">
        <?php echo JText::_('Date 1 column position') . ':'; ?>
      </td>
      <td>
      	<input type="text" size=2 name="date1" value="1">
      </td>
    </tr>
    <tr>
      <td width="170px" align="right" class="key">
        <?php echo JText::_('Date 2 column position') . ':'; ?>
      </td>
      <td>
      	<input type="text" size=2 name="date2" value="2">
      </td>
    </tr>
    <tr>
      <td width="170px" align="right" class="key">
        <?php echo JText::_('Date 3 column position') . ':'; ?>
      </td>
      <td>
      	<input type="text" size=2 name="date3" value="3">
      </td>
    </tr>
    <tr>
      <td width="170px" align="right" class="key">
        <?php echo JText::_('Time 1 column position') . ':'; ?>
      </td>
      <td>
      	<input type="text" size=2 name="time1" value="4">
      </td>
    </tr>
    <tr>
      <td width="170px" align="right" class="key">
        <?php echo JText::_('Time 2 column position') . ':'; ?>
      </td>
      <td>
      	<input type="text" size=2 name="time2" value="5">
      </td>
    </tr>
    <tr>
      <td width="170px" align="right" class="key">
        <?php echo JText::_('Event name column position') . ':'; ?>
      </td>
      <td>
      	<input type="text" size=2 name="eventName" value="6">
      </td>
    </tr>
    <tr>
      <td width="170px" align="right" class="key">
        <?php echo JText::_('Event place column position') . ':'; ?>
      </td>
      <td>
      	<input type="text" size=2 name="eventPlace" value="7">
      </td>
    </tr>
    <tr>
      <td width="170px" align="right" class="key">
        <?php echo JText::_('Event category column position') . ':'; ?>
      </td>
      <td>
      	<input type="text" size=2 name="category" value="8">
      </td>
    </tr>
	</table>
  </fieldset>
  
</div>

<?php echo JHTML::_( 'form.token' ); ?>
<input type="hidden" name="option" value="com_simplecalendar" />
<input type="hidden" name="cids" value="<?php echo $this->item->id; ?>" />
<input type="hidden" name="userid" value="<?php echo $this->user->id; ?>" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="controller" value="entry" />
</form>
<?php
//keep session alive while editing
JHTML::_('behavior.keepalive');

// Footer. Please do not remove.
echo SCOutput::showFooter();
?>