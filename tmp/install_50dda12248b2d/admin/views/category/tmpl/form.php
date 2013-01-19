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
		if (form.categoryName.value == ""){
			alert( "<?php echo JText::_( 'Please specify a category name', true ); ?>" );
		} else {
			submitform( pressbutton );
		}
	}
</script>

<form action="index.php?option=com_simplecalendar&amp;controller=category&amp;view=categories" method="post" name="adminForm" id="adminForm">
<div class="col100">
	<fieldset class="adminform">
		<legend><?php echo JText::_( 'DETAILS' ); ?></legend>

		<table class="admintable">
		<tr>
			<td width="100" align="right" class="key">
				<label for="categoryName">
					<?php echo JText::_( 'CATEGORYNAME' ); ?>:
				</label>
			</td>
			<td>
				<?php
					$catName = '';
					if (isset($this->item)) {
						$catName = $this->item->categoryName;
					}
				?>
				<input class="text_area" type="text" name="categoryName" id="categoryName" size="32" maxlength="250" value="<?php echo $catName;?>" />
			</td>
		</tr>
		<tr>
			<td width="100" align="right" class="key">
				<label for="alias">
					<?php echo JText::_( 'Alias' ); ?>:
				</label>
			</td>
			<td>
				<?php
					$alias = '';
					if (isset($this->item)) {
						$alias = $this->item->alias;
					}
				?>
				<input class="text_area" type="text" name="alias" id="alias" size="32" maxlength="250" value="<?php echo $alias;?>" />
			</td>
		</tr>
		<tr>
			<td width="100" align="right" class="key">
				<label for="category_color">
					<?php echo JText::_( 'Category color' ); ?>:
				</label>
			</td>
			<td><?php 
				$catColor = '';
				if (isset($this->item)) {
					$catColor = $this->item->category_color;
				}?>
				#<input type="text" class="color" name="category_color" id="category_color" size="8" maxlength="6" value="<?php echo $catColor;?>" />
			</td>
		</tr>
	</table>
	</fieldset>
</div>
<div class="clr"></div>

<?php echo JHTML::_( 'form.token' ); ?>
<?php
	$catId = '';
	$categoryID = '';

	if (isset($this->item)) {
		$catId = $this->item->catid;
		$categoryID = $this->item->categoryID;
	}

?>
<input type="hidden" name="catid" id="catid" value="<?php echo $catId; ?>" />
<input type="hidden" name="option" value="com_simplecalendar" />
<input type="hidden" name="categoryID" value="<?php echo $categoryID ; ?>" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="controller" value="category" />
</form>
<?php
//keep session alive while editing
JHTML::_('behavior.keepalive');

// Footer. Please do not remove.
echo SCOutput::showFooter();
?>