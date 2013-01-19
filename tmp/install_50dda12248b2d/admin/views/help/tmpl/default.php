<?php
// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.html.pane');
$val = '';
?>
<?php 
$tabs = &JPane::getInstance('tabs');
echo $tabs->startPane('Help');
echo $tabs->startPanel(JText::_('Introduction'), 'Introduction'); 
?>
	<fieldset class="adminform">
		<legend><?php echo JText::_( 'Welcome to SimpleCalendar' ); ?></legend>
		<p>Text...</p>
	</fieldset>
	<fieldset class="adminform">
		<legend><?php echo JText::_( 'What it is' ); ?></legend>
		<p>Text...</p>
	</fieldset>
	<fieldset class="adminform">
		<legend><?php echo JText::_( 'What it isn\'t' ); ?></legend>
		<p>Text...</p>
	</fieldset>
<?php
echo $tabs->endPanel();
echo $tabs->startPanel(JText::_('Settings Page'), 'Settings');
?>
	<fieldset class="adminform">
		<legend><?php echo JText::_( 'Layout settings' ); ?></legend>
		<p>Text...</p>
	</fieldset>
	<fieldset class="adminform">
		<legend><?php echo JText::_( 'Google Maps settings' ); ?></legend>
		<p>Text...</p>
	</fieldset>
	<fieldset class="adminform">
		<legend><?php echo JText::_( 'Frontend list view settings' ); ?></legend>
		<p>Text...</p>
	</fieldset>
	<?php
echo $tabs->endPanel();
echo $tabs->endPane();
?>
