<?php
/**
 * Invitations View for sending e-mail invitations
 * 
 * @author      Christophe Weis
 * @license		GNU/GPL
 */

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view' );

class SimpleCalendarViewInvitations extends JView
{
	/**
	 * Invitations view display method
	 * @return void
	 **/
	function display($tpl = null)
	{
		$model =& $this->getModel();
		$events = $model->getData();
		$this->assignRef( 'events',	$events );

		JToolBarHelper::title(   JText::_( 'E-Mail Invitations' ), 'inbox.png' );
        if (count($events) > 0) {
		    JToolBarHelper::custom('preview','preview.png','preview_f2.png','Preview Mail',false);
        }
        JToolBarHelper::cancel( 'close', 'Close' );

		JSubMenuHelper::addEntry( JText::_( 'CALENDAR' ), 'index.php?option=com_simplecalendar&controller=calendar');
		JSubMenuHelper::addEntry( JText::_( 'CATEGORIES' ), 'index.php?option=com_simplecalendar&controller=category');
		JSubMenuHelper::addEntry( JText::_( 'EVENT STATUSES' ), 'index.php?option=com_simplecalendar&controller=status');
		JSubMenuHelper::addEntry( JText::_( 'GROUPS' ), 'index.php?option=com_simplecalendar&controller=group');
		JSubMenuHelper::addEntry( JText::_( 'SETTINGS' ), 'index.php?option=com_simplecalendar&controller=settings&task=edit');

		parent::display($tpl);
	}
}
