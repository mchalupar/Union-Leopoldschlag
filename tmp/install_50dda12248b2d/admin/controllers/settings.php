<?php


// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');
class SimpleCalendarControllerSettings extends SimplecalendarController {

	function __construct() {
		parent::__construct();

		// Register Extra tasks
		$this->registerTask( 'apply', 'save' );

		// sets the standard view
		JRequest::setVar('view', 'calendar');
	}


	function edit() {
		JRequest::setVar( 'view', 'settings' );
//		JRequest::setVar( 'layout', 'form'  );
		JRequest::setVar('hidemainmenu', 1);

		parent::display();

	}
	
	/**
	 * save a record (and redirect to main page)
	 * @return void
	 */
	function save() {

		// Check for request forgeries
		JRequest::checkToken() or die( 'Invalid Token' );
		
		$model = $this->getModel('settings');
		$data = $model->getData();
		$post = JRequest::get( 'post' );
		
		$post['intro_text'] = JRequest::getVar( 'intro_text', '', 'post','string', JREQUEST_ALLOWRAW );
		$post['intro_text']	= str_replace( '<br>', '<br />', $post['intro_text'] );
		
		$task = JRequest::getVar('task');

		if ($model->store($post)) {
			$msg = JText::_( 'SETTINGS_SAVED' );
			
			switch ($task) {
				case 'apply':
					$link = 'index.php?option=com_simplecalendar&controller=settings&task=edit';
					break;

				default:
					$link = 'index.php?option=com_simplecalendar&controller=calendar&view=calendar';
					break;
			}
			$this->setRedirect($link, $msg);
			
		} else {
			$msg = JText::_( 'ERROR_ON_SAVE_SETTINGS' );
			$link = 'index.php?option=com_simplecalendar&controller=settings&task=edit';
			$this->setRedirect($link, $msg);
		}
	}


	/**
	 * cancel and exit from editing a record
	 * @return void
	 */
	function cancel() {
		$msg = JText::_( 'OPERATION_ABORTED' );
		$link = 'index.php?option=com_simplecalendar&controller=calendar&view=calendar';
		$this->setRedirect($link, $msg);
	}
}
?>