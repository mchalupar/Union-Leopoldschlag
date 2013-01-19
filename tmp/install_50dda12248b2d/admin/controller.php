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

class SimpleCalendarController extends JController {

	function display() {

		$view = JRequest::getVar('view', '');
		if ($view == '') {
			JRequest::setVar('view', 'calendar');
		}

		parent::display();
		 
		// Register Extra task
		$this->registerTask( 'applycss', 	'savecss' );
	}

	/**
	 * Saves the css
	 *
	 */
	function savecss()
	{
		global $mainframe;

		JRequest::checkToken() or die( 'Invalid Token' );

		// Initialize some variables
		$option			= JRequest::getVar('option');
		$filename		= JRequest::getVar('filename', '', 'post', 'cmd');
		$filecontent	= JRequest::getVar('filecontent', '', '', '', JREQUEST_ALLOWRAW);

		if (!$filecontent) {
			$mainframe->redirect('index.php?option='.$option, JText::_('OPERATION FAILED').': '.JText::_('CONTENT EMPTY'));
		}

		// Set FTP credentials, if given
		jimport('joomla.client.helper');
		JClientHelper::setCredentialsFromRequest('ftp');
		$ftp = JClientHelper::getCredentials('ftp');

		$file = JPATH_SITE.DS.'components'.DS.'com_simplecalendar'.DS.'assets'.DS.'css'.DS.$filename;

		// Try to make the css file writeable
		if (!$ftp['enabled'] && JPath::isOwner($file) && !JPath::setPermissions($file, '0755')) {
			JError::raiseNotice('SOME_ERROR_CODE', 'COULD NOT MAKE CSS FILE WRITABLE');
		}

		jimport('joomla.filesystem.file');
		$return = JFile::write($file, $filecontent);

		// Try to make the css file unwriteable
		if (!$ftp['enabled'] && JPath::isOwner($file) && !JPath::setPermissions($file, '0555')) {
			JError::raiseNotice('SOME_ERROR_CODE', 'COULD NOT MAKE CSS FILE UNWRITABLE');
		}

		if ($return)
		{
			$task = JRequest::getVar('task');
			switch($task)
			{
				case 'applycss' :
					$mainframe->redirect(
						'index.php?option=com_simplecalendar&view=cssedit',
					JText::_('CSS FILE SUCCESSFULLY EDITED')
					);
					break;

				case 'savecss'  :
				default         :
					$mainframe->redirect(
						'index.php?option=com_simplecalendar&controller=calendar',
					JText::_('CSS FILE SUCCESSFULLY EDITED')
					);
					break;
			}
		} else {
			$mainframe->redirect(
				'index.php?option=com_simplecalendar&controller=calendar',
			JText::_('OPERATION FAILED').': '.JText::sprintf('FAILED TO OPEN FILE FOR WRITING', $file)
			);
		}
	}

	function element() {
		$model	= &$this->getModel( 'calendar' );
		$view	= &$this->getView( 'element');
		$view->setModel( $model, true );
		$view->display();
	}



}
?>
