<?php
// no direct access
defined('_JEXEC') or die('Restricted access'); 
$show_color = FALSE;
$colspan = 4;

if ( $this->lists['config']->show_status_color ) {
	$show_color = TRUE;
	$colspan = 5; 
} else {
	$colspan = 4;
}
?>
<form action="index.php" method="post" name="adminForm">
	<div id="editcell">
		<table class="adminlist">
		<thead>
			<tr>
				<th width="5">
					<?php echo JText::_( 'NUM' ); ?>
				</th>
				<th width="20">
					<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->items ); ?>);" />
				</th>
				
				<th>
					<?php echo JHTML::_('grid.sort',  'Status description', 'a.status_description', $this->lists['order_Dir'], $this->lists['order'] ); ?>
				</th>
				<?php if ( $show_color ): ?>
				<th>
					<?php echo JText::_('Status color'); ?>
				</th>
				<?php endif; ?>
				<th width="100px" nowrap="nowrap">
					<?php echo JHTML::_('grid.sort',  'Order', 'a.ordering', $this->lists['order_Dir'], $this->lists['order'] ); ?>
					<?php echo JHTML::_('grid.order',  $this->items ); ?>
				</th>
			</tr>
		</thead>
		<?php
		$k = 0;
		for ($i=0, $n=count( $this->items ); $i < $n; $i++) {

			$row = &$this->items[$i];

			$checked 	= JHTML::_('grid.id',   $i, $row->statusID );
			$link 		= JRoute::_( 'index.php?option=com_simplecalendar&controller=status&task=edit&cid[]='. $row->statusID );

			$ordering = ($this->lists['order'] == 'a.ordering');
			?>
			<tr class="<?php echo "row$k"; ?>">
				<td>
					<?php echo $this->pagination->getRowOffset( $i ); ?>
				</td>
				<td>
					<?php echo $checked; ?>
				</td>
				<td>
					<a href="<?php echo $link; ?>"><?php echo $row->status_description; ?></a>
				</td>
				<?php if ( $show_color ): ?>
				<td>
					<span style="color: #<?php echo $row->status_color; ?>"><?php echo $row->status_color; ?></span>
				</td>
				<?php endif; ?>
				<td class="order">
					<span><?php echo $this->pagination->orderUpIcon( $i, ($row->catid == @$this->items[$i-1]->catid),'orderup', 'Move Up', $ordering ); ?></span>
					<span><?php echo $this->pagination->orderDownIcon( $i, $n, ($row->catid == @$this->items[$i+1]->catid), 'orderdown', 'Move Down', $ordering ); ?></span>
					<?php $disabled = $ordering ?  '' : 'disabled="disabled"'; ?>
					<input type="text" name="order[]" size="5" value="<?php echo $row->ordering;?>" <?php echo $disabled ?> class="text_area" style="text-align: center" />
				</td>
			</tr>
			<?php
			$k = 1 - $k;
		}
		?>
		<tfoot><tr><td colspan="<?php echo $colspan; ?>"><?php echo $this->pagination->getListFooter(); ?></td></tr></tfoot>
		</table>
	</div>
	<input type="hidden" name="option" value="com_simplecalendar" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="controller" value="status" />
	<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>
<?php
// Footer. Please do not remove.
	echo SCOutput::showFooter();
?>