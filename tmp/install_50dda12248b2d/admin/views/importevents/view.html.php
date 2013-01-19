<?php
// no direct access
defined('_JEXEC') or die('Restricted access');

JHTML::stylesheet('simplecal.css','administrator/components/com_simplecalendar/assets/css/');

jimport('joomla.application.component.view');

class SimpleCalendarViewImportEvents extends JView {

	function display($tpl = null) {
		global $mainframe, $option;
		
		$db = & JFactory::getDBO();
		$cids = JRequest::getVar( 'cid', array(), 'post', 'array' );
		$option = JRequest::getCmd( 'option' );
		JArrayHelper::toInteger($cid);

		// load params from the component
		$params =& JComponentHelper::getParams( 'com_simplecalendar' );
		$config = SCOutput::config();
		
		$model = $this->getModel('entry');
		
		JToolBarHelper::title( JText::_( 'Import Events' ), 'cpanel.png');
		JToolBarHelper::custom( 'importEventsSave', 'save.png', 'save_f2.png', 'Save', false );
		JToolBarHelper::cancel();
		
		$lists['categories'] =& $model->getCategoryList();

		$this->assignRef('lists', $lists);

		parent::display($tpl);
	}
}
?>