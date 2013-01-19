<?php
/**
 *	com_simplecalendar - a simple calendar component for Joomla
 *  Copyright (C) 2008-2011 Fabrizio Albonico
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


defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.controller');

require_once(JPATH_COMPONENT_ADMINISTRATOR . DS . 'classes' . DS . 'upload.class.php');
require_once(JPATH_COMPONENT_SITE . DS . 'classes' . DS . 'output.class.php');

class SimpleCalendarController extends JController {
	
	function __construct() {
		parent::__construct();
	}

	function submit() {
		// Check for request forgeries
		JRequest::checkToken() or die( 'Invalid Token' );
		
		$user		= JFactory::getUser();
		$config		=  SCOutput::config();
		
		$post = JRequest::get( 'POST' );
		$post['entryInfo'] = JRequest::getVar( 'entryInfo', '', 'post','string', JREQUEST_ALLOWRAW );
		$post['entryInfo']	= str_replace( '<br>', '<br />', $post['entryInfo'] );

		$isNew = false;
		if ( $post['id'] == '' || $post['id'] == NULL ) {
			$isNew = true;
		}
		
		$model =& $this->getModel('form');
		
		// DO reCAPTCHA validation
		if ( $config->use_recaptcha ) {
			require_once(JPATH_COMPONENT_SITE . DS . 'classes' . DS . 'recaptchalib.php');
			$privatekey = $config->recaptcha_private;
			$resp = recaptcha_check_answer( $privatekey, $_SERVER["REMOTE_ADDR"], $post["recaptcha_challenge_field"], $post["recaptcha_response_field"] );
			if (!$resp->is_valid) {
				$link = JRoute::_('index.php?option=com_simplecalendar&view=form', false);
				$msg = JText::_('Your form did not pass our spam prevention test. Please fill it in again.');
				$msg .= '&nbsp;(' . $resp->error . ')';
				$this->setRedirect($link, $msg);
				$this->setError($msg);
				return false;
			} 
		}
		

		if ( $model->store($post) ) {
			if ( $config->allow_unregistered_submission || ( !$config->frontend_auto_publish && $user->gid < $config->frontend_edit_gid ) ) {
				if ( $isNew ) {
					SimpleCalendarController::_sendMail($user, $post);
				}
				$this->setRedirect(
					JRoute::_('index.php?option=com_simplecalendar&view=calendar', false),
					JText::_( 'Form successfully submitted, but publishing subject to confirmation by an administrator' ) . ' ' . $msg
				);
			} else {
				$this->setRedirect(
					JRoute::_('index.php?option=com_simplecalendar&view=calendar', false),
					JText::_( 'Form successfully submitted' ) . ' ' . $msg
				);
			}
		} else {
			$this->setRedirect(
				JRoute::_('index.php?option=com_simplecalendar&view=calendar', false),
				JText::_( 'Error! Form not submitted!' ) . ' ' . $msg
			);
		}
	}

	function display() {
		$view = JRequest::getVar('view', '');
		if ($view == '') {
	        JRequest::setVar('view', 'calendar');
	    }
		parent::display();
	}
	
	
	/**
	 * Sends an e-mail to all super admins and notifies them of a new post.
	 * @param $user the user that added the new event
	 * @param $event the event the user submitted
	 * @return void
	 * @since 0.7.16b
	 */
	function _sendMail(&$user, $event)
	{
		global $mainframe;

		$db		=& JFactory::getDBO();

		$usersConfig 	= &JComponentHelper::getParams( 'com_users' );
		$sitename 		= $mainframe->getCfg( 'sitename' );
//		$useractivation = $usersConfig->get( 'useractivation' );
		$mailfrom 		= $mainframe->getCfg( 'mailfrom' );
		$fromname 		= $mainframe->getCfg( 'fromname' );
		$siteURL		= JURI::base();
		
		$link = JURI::root() . '/index.php?option=com_simplecalendar&view=detail&catid=' . $event['catid'] . '&id=' . $event['id'];

		//get all super administrator
		$query = 'SELECT name, email, sendEmail' .
				' FROM #__users' .
				' WHERE LOWER( usertype ) = "super administrator"';
		$db->setQuery( $query );
		$rows = $db->loadObjectList();
		
		if ( $user ) { 	// only for logged-in users
			$name 		= $user->get('name');
			$email 		= $user->get('email');
			$username 	= $user->get('username');
			
			$message = '<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
                 <html>
                    <head>
                       <title>'.$subject.'</title>
                       <base href="'.JURI::root().'" />
                    </head>
                    <body>';
			
			
			$subject 	= JText::_( 'You posted a new SimpleCalendar event on' ) . ' ' . $sitename;
			$subject 	= html_entity_decode($subject, ENT_QUOTES);
	
			$message .= '<p>' . sprintf ( JText::_( 'SEND_MSG_SC' ), $name, $sitename, $siteURL) . '</p>';
			
			$message .= '<p><a href="' . $link . '">' . JRoute::_($link) . '</a></p></body></html>';
			$message = html_entity_decode($message, ENT_QUOTES);
	
			// Send email to user
			if ( !$mailfrom  || !$fromname )
			{
				$fromname = $rows[0]->name;
				$mailfrom = $rows[0]->email;
			}
	
			JUtility::sendMail($mailfrom, $fromname, $email, $subject, $message);
		}
		
		$subject2 = JText::_( 'New SimpleCalendar event posted for') . ' ' . $sitename;
		$subject2 = html_entity_decode($subject2, ENT_QUOTES);
		
		// get super-administrators id
		foreach ( $rows as $row )
		{
			if ( $row->sendEmail )
			{		
				$message2 = '<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
                 <html>
                    <head>
                       <title>'.$subject.'</title>
                       <base href="'.JURI::root().'" />
                    </head>
                    <body>';
			
				/*
				 * $message2 param list:
				 * 1) Admin name
				 * 2) Site Name
				 * 3) Event Name
				 * 4) Date1
				 * 5) Place
				 * 6) Name of submitter
				 * 7) E-mail of submitter
				 * 8) SiteName
				 * 9) Site URL
				 */
				$message2 .= '<p>' . sprintf ( 
					JText::_( 'SEND_MSG_ADMIN_SC' ),
					$row->name,
					$sitename,
					$event['entryName'],
					$event['date1'],
					$event['entryPlace'],
					$name,
					$email,
					$siteURL
				) . '</p>';
			
				$message2 .= '<p><a href="' . $link . '">' . JRoute::_($link) . '</a></p></body></html>';
				$message2 = html_entity_decode($message, ENT_QUOTES);
				
				$message2 .= '<p><a href="' . $link . '">' . JRoute::_($link) . '</a></p>';
				$message2 = html_entity_decode($message2, ENT_QUOTES);
				JUtility::sendMail($mailfrom, $fromname, $row->email, $subject2, $message2);
			}
		}
	}
}
?>
