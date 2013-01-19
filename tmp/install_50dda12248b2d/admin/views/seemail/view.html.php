<?php
/**
 * CJBAgendasViewSeemail for previewing e-mail
 * 
 * @author     Christophe Weis
 * @license		GNU/GPL
 */

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view' );

class SimplecalendarViewSeemail extends JView {
	/**
	 * CJBAgendas view display method
	 * @return void
	 **/
	function display($tpl = null) {
		JToolBarHelper::title(   JText::_( 'E-Mail Invitations' ), 'inbox.png' );
		JToolBarHelper::custom('send','send.png','send_f2.png','Send Mail',false);
		JToolBarHelper::cancel();
		
		$config 	=  SCOutput::config();
		
		$model =& $this->getModel();
        $event =& $model->getData();
		$this->assignRef( 'event',	$event );
		$this->assignRef( 'config',	$config );
        $mails =& $model->getContactMails();
		$this->assignRef( 'mails',	$mails );

		parent::display($tpl);
	}
}
