<?php
// no direct access
defined('_JEXEC') or die('Restricted access');

JHTML::stylesheet('simplecal.css','administrator/components/com_simplecalendar/assets/css/');

jimport('joomla.application.component.view');

class SimpleCalendarViewStatus extends JView {

	function display($tpl = null) {
		// get the data from the model
		$item =& $this->get('Data');

		// check if it is new (bool)
		$isNew = false;
		if ( !isset($item) ) { 
			$isNew = true;
		} else {
			$isNew = false;
		}
		$text = $isNew ? JText::_( 'New' ) : JText::_( 'Edit' );

		JToolBarHelper::title(JText::_('STATUS').': <small><small>[ ' . $text.' ]</small></small>', 'generic.png');
		JToolBarHelper::save();
		JToolBarHelper::apply();
		
		if ($isNew)  {
			JToolBarHelper::cancel();
		} else {
			// for existing items the button is renamed `close`
			JToolBarHelper::cancel( 'cancel', 'Close' );
		}

//		$items =& $this->get('Data');
		$this->assignRef('item', $item);

		parent::display($tpl);
	}
}
?>