<?php
// no direct access
defined('_JEXEC') or die('Restricted access');

JHTML::stylesheet('simplecal.css','administrator/components/com_simplecalendar/assets/css/');

jimport('joomla.application.component.view');

class SimpleCalendarViewCopyItem extends JView {

	function display($tpl = null) {
		global $mainframe, $option;
		
		$db = & JFactory::getDBO();
		$cids = JRequest::getVar( 'cid', array(), 'post', 'array' );
		$option = JRequest::getCmd( 'option' );
		JArrayHelper::toInteger($cid);

		// load params from the component
		$params =& JComponentHelper::getParams( 'com_simplecalendar' );
		$config = SCOutput::config();
		
		if (count($cids) < 1) {
			$msg = JText::_('Select an item to copy');
			$mainframe->redirect('index.php?option='.$option, $msg, 'error');
		}
		
		if (count($cids) > 1) {
			$msg = JText::_('You can select only 1 item to copy');
			$mainframe->redirect('index.php?option='.$option, $msg, 'error');
		}
		
		$cid = $cids[0];

		$model = $this->getModel('entry');
		$item =& $model->getDataToCopy($cid);
		
		$lists = array();
		
		$user =& JFactory::getUser();
		
		JToolBarHelper::title( JText::_( 'Copy Calendar Items' ), 'cpanel.png');
		JToolBarHelper::custom( 'copyItemSave', 'save.png', 'save_f2.png', 'Save', false );
		JToolBarHelper::cancel();

		$this->assignRef('item', $item);
		$this->assignRef('lists', $lists);
		$this->assignRef('user', $user);
		$this->assignRef('params', $params);
		$this->assignRef('config', $config);

		parent::display($tpl);
	}
}
?>