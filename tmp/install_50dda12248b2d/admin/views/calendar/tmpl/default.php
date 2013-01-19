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
			<th><?php echo JHTML::_('grid.sort',  'entryName', 'a.entryName', $this->lists['order_Dir'], $this->lists['order'] ); ?>
			</th>
			<th><?php echo JHTML::_('grid.sort',  'groupName', 'a.entryGroupID', $this->lists['order_Dir'], $this->lists['order'] ); ?>
			</th>
			<th><?php echo JHTML::_('grid.sort',  'categoryName', 'a.categoryID', $this->lists['order_Dir'], $this->lists['order'] ); ?>
			</th>
			<th><?php echo JHTML::_('grid.sort',  'user', 'a.userid', $this->lists['order_Dir'], $this->lists['order'] ); ?>
			</th>
			<th><?php echo JHTML::_('grid.sort',  'published', 'a.published', $this->lists['order_Dir'], $this->lists['order'] ); ?>
			</th>
		</tr>
	</thead>
	<?php
	$k = 0;
	for ($i=0, $n=count( $this->items ); $i < $n; $i++)
	{
		$icons = '';
		$row =& $this->items[$i];

		// formats date and time for display
		$printDate = SCOutput::getFormattedDate($row->date1,$row->date2,$row->date3, $params->date_long_format, $params->date_short_format);
		$printTime = SCOutput::getFormattedTime($row->from_time, $row->to_time, $params->time_format, false);

		if ( strtotime($row->date2) > strtotime($row->date1) ) {
			$countDays = SCOutput::countDays($row->date2, $row->from_time);
		} else {
			$countDays = SCOutput::countDays($row->date1, $row->from_time);
		}

		// checks if element is expired.
		if ( $countDays > 1 ) {
			// expired
			$dateColor = 'red';
		}
		else if ( $countDays >= -1 && $countDays <= 1 ) {
			//current day
			$dateColor = 'orange';
		}
		else {
			// upcoming
			$dateColor = 'green';
		}

		$published 	= JHTML::_('grid.published', $row, $i );

		// some more needed variables
		$checked 	= JHTML::_('grid.id',   $i, $row->id );
		$link 		= JRoute::_( 'index.php?option=com_simplecalendar&controller=entry&task=edit&cid[]='. $row->id );

		// Gets attachments
		if ( file_exists(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_attachments'.DS.'admin.attachments.php') ) {
			$db	=& JFactory::getDBO();
			$query = "SELECT id FROM #__attachments WHERE parent_type = 'com_simplecalendar' AND parent_id = " . $row->id ;
			$db->setQuery( $query );
			$data = $db->loadObject();
			if ( sizeof($data) > 0 ) {
				$icons .= '<a href="index.php?option=com_attachments">' . JHTML::_(
						'image',
						'components/com_simplecalendar/assets/images/attach.png',
						JText::_( 'Attachment' ),
						array('title' => JText::_( 'Attachment' ))
					) . '</a>';
			} 
		}
		
		if ( $row->entryIsPrivate == '1' ) {
			$icons .= JHTML::_(
						'image',
						'components/com_simplecalendar/assets/images/group_key.png',
						JText::_( 'This event is private' ),
						array('title' => JText::_( 'This event is private' ))
					);
		}
		
		$username = '';
		$user = JFactory::getUser($row->userid);
		if ( !$user ) {
			$username = '';
		} else {
			$username = $user->name;
		}
		?>

	<tr class="<?php echo "row$k"; ?>">
		<td><?php echo $row->id; ?></td>
		<td><?php echo $checked; ?></td>
		<td><?php echo '<font color='.$dateColor.'>'.$printDate.'</font>'; ?></td>
		<td><?php echo $printTime; ?></td>
		<td><a href="<?php echo $link; ?>"><?php echo $row->entryName; ?></a>&nbsp;<?php echo $icons; ?></td>
		<td><?php echo $row->groupName; ?></td>
		<td><?php echo $row->categoryName; ?></td>
		<td><?php echo $username; ?></td>
		<td align="center" width="25px"><?php echo $published; ?></td>
	</tr>
	<?php
	$k = 1 - $k;
	}
	?>
	<tfoot>
		<tr>
			<td colspan="9"><?php echo $this->pagination->getListFooter(); ?></td>
		</tr>
	</tfoot>
</table>
</div>
<input type="hidden" name="option" value="com_simplecalendar" /> <input
	type="hidden" name="task" value="" /> <input type="hidden"
	name="boxchecked" value="0" /> <input type="hidden" name="controller"
	value="entry" /> <input type="hidden" name="filter_order"
	value="<?php echo $this->lists['order']; ?>" /> <input type="hidden"
	name="filter_order_Dir"
	value="<?php echo $this->lists['order_Dir']; ?>" /></form>
	<?php
	echo JHTML::_( 'form.token' );

	// Footer. Please do not remove.
	echo SCOutput::showFooter();
	?>