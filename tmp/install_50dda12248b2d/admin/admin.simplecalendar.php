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

	//no direct access
	defined('_JEXEC') or die('Restricted access');

	//Debugging purposes only!!!
//	error_reporting(E_ALL); 
	
	require_once( JPATH_COMPONENT_ADMINISTRATOR.DS.'controller.php' );
	require_once( JPATH_COMPONENT_SITE . DS . 'classes' . DS . 'output.class.php' );

	if($controller = JRequest::getVar('controller')) {
	    $path = JPATH_COMPONENT_ADMINISTRATOR.DS.'controllers'.DS.strtolower($controller).'.php';
		if (file_exists($path)) {
			require_once $path;
		}
	} else {
		require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'controllers'.DS.'calendar.php');
//		$controller = 'Calendar';
		$controller = '';
	}
	
	$classname = 'SimpleCalendarController'.$controller;
	$controller = new $classname();
	$controller->execute(JRequest::getVar('task'));
	$controller->redirect();
	
?>