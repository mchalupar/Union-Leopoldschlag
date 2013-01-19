<?php
/**
 *	com_simplecalendar - a simple calendar component for Joomla
 *  Copyright (C) 2008-2009 Fabrizio Albonico
 *
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *	(at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');
class SimpleCalendarControllerStatus extends SimplecalendarController {

	function __construct() {
		parent::__construct();

		// Register Extra tasks
		$this->registerTask( 'add'  , 	'edit' );
		$this->registerTask( 'apply' ,  'save' );

		// sets the standard view
		JRequest::setVar('view', 'statuses');
	}

	/**
	 * display the edit form
	 * @return void
	 */
	function edit() {
		JRequest::setVar( 'view', 'status' );
		JRequest::setVar( 'layout', 'form'  );
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
		
		$task = JRequest::getVar('task');
		
		$model = $this->getModel('status');
		$data = $model->getData();

		if ($returnid = $model->store($post)) {
			switch ($task) {
				case 'apply':
					$link = 'index.php?option=com_simplecalendar&controller=status&task=edit&cid[]='.$returnid;
					break;

				default:
					$link = 'index.php?option=com_simplecalendar&controller=status&view=statuses';
					break;
			}
			$msg = JText::_( 'RECORD_SAVED' );
			$this->setRedirect($link, $msg);
		} else {
			$msg = JText::_( 'ERROR_ON_SAVE' );
			$link = 'index.php?option=com_simplecalendar&controller=status&task=edit&cid[]='.$returnid;
			$this->setRedirect($link, $msg);
		}
	}

	/**
	 * remove record(s)
	 * @return void
	 */
	function remove() {
		$model = $this->getModel('status');
		if (!$model->checkBeforeDelete()) {
			$msg = JText::_('RECORD_USED_BY_ITEM');
		} else {
			if(!$model->delete()) {
				$msg = JText::_( 'ERROR_1_OR_MORE_NOT_DELETED' );
			} else {
				$msg = JText::_( 'RECORD_DELETED' );
			}
		}
		$link = 'index.php?option=com_simplecalendar&controller=status&view=statuses';
		$this->setRedirect($link, $msg);

	}

	/**
	 * cancel editing a record
	 * @return void
	 */
	function cancel() {
		
		// Check for request forgeries
		JRequest::checkToken() or die( 'Invalid Token' );

		$msg = JText::_( 'OPERATION_ABORTED' );
		$link = 'index.php?option=com_simplecalendar&controller=status&view=statuses';
		$this->setRedirect($link, $msg);
	}

	function orderup() {
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		$model = $this->getModel('status');
		$model->move(-1);

		$this->setRedirect( 'index.php?option=com_simplecalendar&controller=status&view=statuses');
	}

	function orderdown() {
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		$model = $this->getModel('status');
		$model->move(1);

		$this->setRedirect( 'index.php?option=com_simplecalendar&controller=status&view=statuses');
	}

	function saveorder() {
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		$cid 	= JRequest::getVar( 'cid', array(), 'post', 'array' );
		$order 	= JRequest::getVar( 'order', array(), 'post', 'array' );
		JArrayHelper::toInteger($cid);
		JArrayHelper::toInteger($order);

		$model = $this->getModel('status');
		$model->saveorder($cid, $order);

		$msg = JText::_( 'New ordering saved' );
		$this->setRedirect( 'index.php?option=com_simplecalendar&controller=status&view=statuses', $msg );
	}
}
?>