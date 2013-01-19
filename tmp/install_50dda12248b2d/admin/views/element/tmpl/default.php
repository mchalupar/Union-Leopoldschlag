<?php
// no direct access
defined('_JEXEC') or die('Restricted access');

$params = SCOutput::config(); 
?>
<form action="index.php" method="post" name="adminForm">
<div id="editcell">
<table class="adminlist">
	<thead>
		<tr>
			<th width="5"><?php echo JText::_( 'ID' ); ?></th>
			<th width="20"><input type="checkbox" name="toggle" value=""
				onclick="checkAll(<?php echo count( $this->items ); ?>);" /></th>
			<th><?php echo JHTML::_('grid.sort',  'date', 'a.date1', $this->lists['order_Dir'], $this->lists['order'] ); ?>
			</th>
 			<th><?php echo JText::_('Time'); ?></th>
			<th><?php echo JHTML::_('grid.sort',  'entryname', 'a.entryName', $this->lists['order_Dir'], $this->lists['order'] ); ?>
			</th>
			<th><?php echo JHTML::_('grid.sort',  'groupName', 'a.entryGroupID', $this->lists['order_Dir'], $this->lists['order'] ); ?>
			</th>
			<th><?php echo JHTML::_('grid.sort',  'categoryName', 'a.categoryID', $this->lists['order_Dir'], $this->lists['order'] ); ?>
			</th>
			<th><?php echo JHTML::_('grid.sort',  'published', 'a.published', $this->lists['order_Dir'], $this->lists['order'] ); ?>
			</th>
		</tr>
	</thead>
	<?php
	$k = 0;
	for ($i=0, $n=count( $this->items ); $i < $n; $i++)
	{
		$attachment = '';
		$row =& $this->items[$i];

		// formats date and time for display
		$printDate = SCOutput::getFormattedDate($row->date1,$row->date2,$row->date3, $params->date_long_format, $params->date_short_format);
		$printTime = SCOutput::getFormattedTime($row->from_time, $row->to_time, $params->time_format, false);
		
//		if ( strtotime($row->date2) > strtotime($row->date1) ) {
//			$countDays = SCOutput::countDays($row->date2, $row->from_time);
//		} else {
//			$countDays = SCOutput::countDays($row->date1, $row->from_time);
//		}
//		
//		// checks if element is expired.
//		if ( $countDays > 1 ) {
//			// expired
//			$dateColor = 'red';
//		}
//		else if ( $countDays >= -1 && $countDays <= 1 ) {
//			//current day
//			$dateColor = 'orange';
//		}
//		else {
//			// upcoming
//			$dateColor = 'green';
//		}

		$published 	= JHTML::_('grid.published', $row, $i );

		// some more needed variables
		$checked 	= JHTML::_('grid.id',   $i, $row->id );
		$link 		= JRoute::_( 'index.php?option=com_simplecalendar&controller=entry&task=edit&cid[]='. $row->id );
		 
//		if ( $row->attached_file != '' && $params->allow_file_upload ) {
//			$attachment = JHTML::image( '../../../components/com_simplecalendar/assets/images/attachment.gif', JText::_('Attachment') );
//		}

		?>
	<tr class="<?php echo "row$k"; ?>">
		<td><?php echo $row->id; ?></td>
		<td><?php echo $checked; ?></td>
		<td><?php echo $printDate; ?></td>
		<td><?php echo $printTime; ?></td>
		<td>
			<a style="cursor: pointer;" onclick="window.parent.jSelectArticle('<?php echo $row->id; ?>', '<?php echo str_replace(array("'", "\""), array("\\'", ""),$row->entryName); ?>', '<?php echo JRequest::getVar('object'); ?>');">
							<?php echo htmlspecialchars($row->entryName, ENT_QUOTES, 'UTF-8'); ?></a>
		</td>
		<td><?php echo $row->groupName; ?></td>
		<td><?php echo $row->categoryName; ?></td>
		<td align="center" width="25px"><?php echo $published; ?></td>
	</tr>
	<?php
		$k = 1 - $k;
	}
	?>
	<tfoot>
		<tr>
			<td colspan="8"><?php echo $this->pagination->getListFooter(); ?></td>
		</tr>
	</tfoot>
</table>
</div>
<input type="hidden" name="option" value="com_simplecalendar" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" value="0" /> <input type="hidden" name="controller" value="entry" />
<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
</form>
<?php
echo JHTML::_( 'form.token' );

// Footer. Please do not remove.
echo SCOutput::showFooter();
?>