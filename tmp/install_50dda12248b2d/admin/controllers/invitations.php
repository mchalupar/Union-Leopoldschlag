<?php
/**
 * SimpleCalendarControllerInvitations for sending e-mail invitations
 *
 * @author      Christophe Weis
 * @license		GNU/GPL
 */

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.application.component.controller');

class SimpleCalendarControllerInvitations extends SimplecalendarController {
	/**
	 * constructor (registers additional tasks to methods)
	 * @return void
	 */
	function __construct() {
		parent::__construct();
		JRequest::setVar('view', 'invitations');
	}

	/**
	 * sending an e-mail (and redirect to main page)
	 * @return void
	 */
	function send() {
		global $mainframe;
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		$model = $this->getModel('seemail');

        $mails = $model->getContactMails();
        $subject = JRequest::getVar('subject');
        $content = JRequest::getVar('content', '', 'post', 'string', JREQUEST_ALLOWHTML);
        $html_content = '<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
                 <html>
                    <head>
                       <title>'.$subject.'</title>
                       <base href="'.JURI::root().'" />
                    </head>
                    <body>'.
                     stripslashes($content).
                     '</body>
                 </html>';

        $mailer =& JFactory::getMailer();

        // get system mail sender
        $config =& JFactory::getConfig();
        $sender = array( 
            $config->getValue( 'config.mailfrom' ),
            $config->getValue( 'config.fromname' ) 
        );
        $mailer->setSender($sender);

        $recipients = array();
        foreach($mails as $mail) {
            $recipients[] = $mail->email;
        }

        $mailer->addRecipient($recipients);
        $mailer->setSubject($subject);
        $mailer->isHTML(true);
        $mailer->setBody($html_content);

        $send =& $mailer->Send();
        if ( $send !== true ) {
            $msg = JText::_( 'Error sending E-mail' ) . ': ' . $send->message;
        } else {
            $msg = JText::_( 'E-mail sent' );
        }
        $this->setRedirect( 'index.php?option=com_simplecalendar', $msg );
	}

	/**
	 * previewing e-mail to edit
	 * @return void
	 */
	function preview() {
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );
        $id = JRequest::getVar('event');
        
        if ($id == '') {
		    $msg = JText::_( 'Please select an event' );
		    $this->setRedirect( 'index.php?option=com_simplecalendar&controller=invitations', $msg );
            return;
        }

        JRequest::setVar( 'view', 'seemail' );
        JRequest::setVar( 'layout', 'mail'  );
        JRequest::setVar('hidemainmenu', 1);
 
        parent::display();
	}

	/**
	 * closes the mailing application
	 * @return void
	 */
	function close() {
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		$this->setRedirect( 'index.php?option=com_simplecalendar', $msg );
	}

	/**
	 * canceling sending e-mail
	 * @return void
	 */
	function cancel() {
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		$this->setRedirect( 'index.php?option=com_simplecalendar&controller=invitations', $msg );
	}
}
