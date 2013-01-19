<?php defined('_JEXEC') or die('Restricted access');?>

<form action="index.php" method="post" name="adminForm" id="adminForm">
<div class="col100">                    
	<fieldset class="adminform">
		<legend><?php echo JText::_( 'Calendar Mailer' ); ?></legend>

		<table class="admintable">
		<tr>
			<td align="right" class="key">
				<label for="event">
					<?php echo JText::_( 'Select Event' ); ?>:
				</label>
			</td>
			<td>
                <?php if( count($this->events) < 1) { ?>
                <select name="event" id="event" class="inputbox" size="1">
                    <option value="" ><?php echo JText::_( '- No Upcoming Events -' ); ?></option>
                </select>
                <?php } else { ?>
                <select name="event" id="event" class="inputbox" size="1">
                    <option value="" ><?php echo JText::_( '- Select an Event -' ); ?></option>
                    <?php foreach ( $this->events as $event ) : ?>
                    <option value="<?php echo $event->id; ?>" ><?php echo $event->entryName; ?></option>
                    <?php endforeach; ?>
                </select>
                <?php } ?>
			</td>
		</tr>
	    </table>
	</fieldset>
</div>
<div class="clr"></div>

<input type="hidden" name="option" value="com_simplecalendar" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="controller" value="invitations" />
<input type="hidden" name="view" value="invitations" />
<?php echo JHTML::_( 'form.token' ); ?>
</form>
