<?php
// No direct access
defined('_JEXEC') or die('Restricted access');

?>
<form action="index.php?option=com_simplecalendar&amp;controller=calendar&amp;view=calendar" method="post" name="adminForm" id="adminForm">
<div class="col width-60">
  <fieldset class="adminform">
    <legend><?php echo JText::_( 'DETAILS' ); ?></legend>

    <table class="admintable">
    <tr>
      <td width="100" align="right" class="key">
        <label for="entryName">
          <?php echo JText::_( 'ENTRYNAME' ); ?>:
        </label>
      </td>
      <td>
        <?php echo $this->item->entryName; ?>
      </td>
    </tr>
    <tr>
      <td width="100" align="right" class="key">
        <label for="name">
        <?php echo JText::_( 'ENTRYPLACE' ); ?>:
        </label>
      </td>
      <td>
		<?php echo $this->item->entryPlace;?>
      </td>
    </tr>
    <tr>
      <td width="100" align="right" class="key">
        <label for="date1">
          <?php echo JText::_( 'DATE_FROM' ); ?>:
        </label>
      </td>
      <td>
        <?php echo $this->item->date1; ?>
      </td>
    </tr>
    <tr>
      <td width="100" align="right" class="key">
        <label for="date2">
          <?php echo JText::_( 'DATE_TO' ); ?>:
        </label>
      </td>
      <td>
        <?php echo $this->item->date2; ?>
      </td>
    </tr>
    <tr>
      <td width="100" align="right" class="key">
        <label for="date3">
          <?php echo JText::_( 'DATE3' ); ?>:
        </label>
      </td>
      <td>
        <?php echo $this->item->date3; ?>
      </td>
    </tr>
    <tr>
      <td width="100" align="right" class="key">
        <label for="from_time">
          <?php echo JText::_( 'FROM_TIME' ); ?>:
        </label>
      </td>
      <td>
    <?php echo $this->item->from_time; ?>
    </tr>
    <tr>
      <td width="100" align="right" class="key">
        <label for="to_time">
          <?php echo JText::_( 'TO_TIME' ); ?>:
        </label>
      </td>
      <td>
        <?php echo $this->item->to_time; ?>
      </td>
    </tr>
  </table>
  </fieldset>
  <fieldset class="adminform">
    <legend><?php echo JText::_( 'ITEM TO COPY' ); ?></legend>
    <table class="admintable">
    <tr>
      <td width="100" align="right" class="key">
        <label for="entryName">
          <?php echo JText::_( 'ENTRYNAME' ); ?>:
        </label>
      </td>
      <td>
        <input class="text_area" type="text" name="entryName" id="entryName" size="64" maxlength="250" value="<?php echo $this->item->entryName;?>" />
      </td>
    </tr>
    <tr>
      <td width="100" align="right" class="key">
        <label for="entryPlace">
          <?php echo JText::_( 'ENTRYPLACE' ); ?>:
        </label>
      </td>
      <td>
        <input class="text_area" type="text" name="entryPlace" id="entryPlace" size="32" maxlength="250" value="<?php echo $this->item->entryPlace;?>" />
      </td>
    </tr>
    <tr>
      <td width="100" align="right" class="key">
        <label for="date1">
          <?php echo JText::_( 'DATE_FROM' ); ?>:
        </label>
      </td>
      <td>
        <?php echo JHTML::_('calendar', $this->item->date1, 'date1', 'date1', '%Y-%m-%d', array('class'=>'inputbox', 'size'=>'10',  'maxlength'=>'10')); ?>
      </td>
    </tr>
    <tr>
      <td width="100" align="right" class="key">
        <label for="date2">
          <?php echo JText::_( 'DATE_TO' ); ?>:
        </label>
      </td>
      <td>
		<?php
	        if ( isset($this->item->date2) ) {
				$val = $this->item->date2;
			} else {
				$val = null;
			}
			echo JHTML::_('calendar', $val, 'date2', 'date2', '%Y-%m-%d', array('class'=>'inputbox', 'size'=>'10',  'maxlength'=>'10'));
		?>
      </td>
    </tr>
    <tr>
      <td width="100" align="right" class="key">
        <label for="date3">
          <?php echo JText::_( 'DATE3' ); ?>:
        </label>
      </td>
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
      <td width="100" align="right" class="key">
        <label for="from_time">
          <?php echo JText::_( 'FROM_TIME' ); ?>:
        </label>
      </td>
      <td>
    	<input class="text_area" type="text" name="from_time"
			id="from_time" size="10" maxlength="10"
			value="<?php if (isset($this->item->from_time)) echo JHTML::_('date', $this->item->from_time, '%H:%M', 0);  ?>" />
      </td>
    </tr>
    <tr>
      <td width="100" align="right" class="key">
        <label for="to_time">
          <?php echo JText::_( 'TO_TIME' ); ?>:
        </label>
      </td>
      <td>
		 <input class="text_area" type="text" name="to_time" id="to_time"
         size="10" maxlength="10"
         value="<?php if (isset($this->item->to_time)) echo JHTML::_('date', $this->item->to_time, '%H:%M', 0);  ?>" />
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