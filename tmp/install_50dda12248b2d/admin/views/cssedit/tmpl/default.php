<?php
// no direct access
defined('_JEXEC') or die('Restricted access');

?>
<form action="index.php" method="post" name="adminForm" id="adminForm">
		<table class="adminform">
		<tr>
			<th>
				<?php echo $this->css_path; ?>
			</th>
		</tr>
		<tr>
			<td>
				<textarea style="width:100%;height:500px" cols="110" rows="25" name="filecontent" class="inputbox"><?php echo $this->content; ?></textarea>
			</td>
		</tr>
		</table>

		<?php echo JHTML::_( 'form.token' ); ?>
		<input type="hidden" name="filename" value="<?php echo $this->filename; ?>" />
		<input type="hidden" name="option" value="com_simplecalendar" />
		<input type="hidden" name="task" value="" />
		<?php
echo JHTML::_( 'form.token' );

// Footer. Please do not remove.
echo SCOutput::showFooter();
?>
</form>
		