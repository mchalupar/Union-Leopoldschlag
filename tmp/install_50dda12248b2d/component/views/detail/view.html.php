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

JHTML::stylesheet('simplecal_front.css','components/com_simplecalendar/assets/css/');
if (!isset($option))
$option = 'com_simplecalendar';
JTable :: addIncludePath(JPATH_ADMINISTRATOR . DS . 'components' . DS . $option . DS . 'tables');

jimport('joomla.application.component.view');

class SimpleCalendarViewDetail extends JView {

	function display($tmpl = null) {
		global $option, $mainframe;

		$lists 			= array();
		$lists['document'] =& JFactory::getDocument();
		$user 			=& JFactory::getUser();
		$pathway	 	=& $mainframe->getPathWay();
		$model 			=& $this->getModel('detail');
		$dispatcher 	=& JDispatcher::getInstance();
		$component	 	=  JComponentHelper::getComponent( 'com_simplecalendar' );
		$config			=  SCOutput::config();
		$menu			=& JSite::getMenu();
		$lists['menuitem'] =  $menu->getActive();

		if ( !isset($lists['menuitem']->name) || $lists['menuitem']->name  == '' ) {
			$lists['menuitem']->name = JText::_('Calendar');
		}
		// ---

		$vcal = JRequest::getBool('vcal');
		if ($vcal) {
			$lists['document']->setMetaData('robots', 'noindex, nofollow');
			$tmpl = 'vcal';
		}

		$lists['print'] = JRequest::getBool('print');

		if ($lists['print']) {
			$lists['document']->setMetaData('robots', 'noindex, nofollow');
		}
		 
		$params = new JParameter( $component->params );

		$item = $model->getDetail();

		$lists['showMap'] = FALSE;
		if ($config->use_gmap == 1 && $config->gmap_api_key != '' && $item->entryLatLon != '') {
			$lists['showMap'] = TRUE;
		}

		if ( !$item ) {
			$html = '<h1>' . JText::_('Warning') . '</h1>';
			$html .= JText::_('There is no such item to display!') . '&nbsp;&nbsp;&nbsp;';
			$html .= '<a href="javascript:history.back()">' . JText::_('Back') . '</a>';
			$html .= SCOutput::showFooter();
			echo $html;
		} else {
			$item->text = $item->entryInfo;
			JPluginHelper::importPlugin('content');
			$results = $dispatcher->trigger('onPrepareContent', array (& $item, & $params, 0));
			$item->entryInfo = $item->text;
				
			// Check whether we're dealing with an iCal/vCal request
			$vcal = JRequest::getBool('vcal');
				
			if ($vcal) {
				//		$lists['document']->setMetaData('robots', 'noindex, nofollow');
				$tmpl = 'vcal';
				$item = $model->getDetail();
				$this->assignRef('item', $item);
				$this->assignRef('params', $params);
				parent::display($tmpl);
			}

//			$lists['file_info'] = explode('|', $item->attached_file);
				
			$lists['document']->setTitle( $lists['menuitem']->name.' | ' . JText::_( 'EVENT_DETAIL' ). ' :: '.$item->entryName );

			$this->assignRef('item', $item);
			$this->assignRef('user', $user);
			$this->assignRef('lists', $lists);
			$this->assignRef('params', $params);
			$this->assignRef('menuitem', $lists['menuitem']);
			$this->assignRef('config', $config);
			$this->assignRef('pathway', $pathway);
				
			parent::display($tmpl);
		}
	}
}
?>