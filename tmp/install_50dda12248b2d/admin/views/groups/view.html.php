<?php
// no direct access
defined('_JEXEC') or die('Restricted access');

JHTML::stylesheet('simplecal.css','administrator/components/com_simplecalendar/assets/css/');

jimport('joomla.application.component.view');


class SimpleCalendarViewGroups extends JView {

	function display($tpl = null) {

 		global $option, $mainframe;

		$context = 'com_simplecalendar.groups.';

		$filter_state		= $mainframe->getUserStateFromRequest( $context.'filter_state',		'filter_state',		'',				'word' );
		$filter_order		= $mainframe->getUserStateFromRequest( $context.'filter_order',		'filter_order',		'a.ordering',	'cmd' );
		$filter_order_Dir	= $mainframe->getUserStateFromRequest( $context.'filter_order_Dir',	'filter_order_Dir',	'',				'word' );
		$filter_catid		= $mainframe->getUserStateFromRequest( $context.'filter_catid',		'filter_catid',		0,				'int' );

		JToolBarHelper::title(JText::_('GROUPS'), 'user.png');
		
		JToolBarHelper::deleteList();
		JToolBarHelper::editListX();
		JToolBarHelper::addNewX();

		JSubMenuHelper::addEntry( JText::_( 'CALENDAR' ), 'index.php?option=com_simplecalendar&controller=calendar');
		JSubMenuHelper::addEntry( JText::_( 'CATEGORIES' ), 'index.php?option=com_simplecalendar&controller=category');
		JSubMenuHelper::addEntry( JText::_( 'EVENT STATUSES' ), 'index.php?option=com_simplecalendar&controller=status');
		JSubMenuHelper::addEntry( JText::_( 'E-MAIL INVITATIONS' ), 'index.php?option=com_simplecalendar&controller=invitations');
		JSubMenuHelper::addEntry( JText::_( 'SETTINGS' ), 'index.php?option=com_simplecalendar&controller=settings&task=edit');
		
		$lists = array();

		// table ordering
		$lists['order_Dir'] = $filter_order_Dir;
		$lists['order'] = $filter_order;

		// build list of categories
		$javascript 	= 'onchange="document.adminForm.submit();"';
		$lists['catid'] = JHTML::_('list.category',  'filter_catid', $context, intval( $filter_catid ), $javascript );

		// state filter
		$lists['state']	= JHTML::_('grid.state',  $filter_state );

		$items =& $this->get('Data');
		$pagination =& $this->get('Pagination');
		$this->assignRef('items', $items);
		$this->assignRef('lists', $lists);
		$this->assignRef('pagination', $pagination);

		parent::display($tpl);	}
}
?>