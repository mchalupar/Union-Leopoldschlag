<?php

/*------------------------------------------------------------------------
# com_scheduler
# ------------------------------------------------------------------------
# author DHTMLX LTD
# copyright Copyright (C) 2012 DHTMLX LTD. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://dhtmlx.com/
# Technical Support: Forum - http://forum.dhtmlx.com/viewforum.php?f=16
-------------------------------------------------------------------------*/

/**
 This file is part of dhtmlxScheduler for Joomla.

    dhtmlxScheduler for Joomla is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    dhtmlxScheduler for Joomla is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with dhtmlxScheduler for Joomla.  If not, see <http://www.gnu.org/licenses/>.
**/
// deny direct access
defined( '_JEXEC' ) or die( 'Restricted access' );



jimport( 'joomla.application.component.view');

class SchedulersViewSchedulers extends JView {

	function display($tpl = null) {
		JToolBarHelper::title(JText::_('Scheduler Manager'), 'generic.png');
		JToolBarHelper::custom('default_xml', 'default', '', 'Default', false, false);
		JToolBarHelper::save();

		JToolBarHelper::cancel();
		$options = $this->get('Data');
		$this->assignRef('options', $options);
		parent::display($tpl);
	}
}