<?php
// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

JHTML::stylesheet('simplecal.css','administrator/components/com_simplecalendar/assets/css/');

class SimpleCalendarViewHelp extends JView {

	function display($tpl = null) {
		global $mainframe, $option;
		
		JHTML::_('behavior.tooltip');
		JHTML::_('behavior.keepalive');

		parent::display($tpl);

		// Footer. Please do not remove.
		echo SCOutput::showFooter();
	}
}
?>