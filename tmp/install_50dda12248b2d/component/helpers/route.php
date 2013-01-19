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

defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.helper');

class SimpleCalendarHelperRoute {
	function getRoute($id = 0, $catid = 0, $itemid = '', $view = 'detail') {
		
		$needles = array(
			$view  => (int) $id
		);
		
		//Create the link
		if ( $id == 0 && $catid == 0 ) {
			$link = 'index.php?option=com_simplecalendar&view='.$view;
		} elseif ( $id == 0 && $catid != 0 ) {
			$link = 'index.php?option=com_simplecalendar&view='.$view.'&catid='.$catid;
		} else {
			$link = 'index.php?option=com_simplecalendar&view='.$view.'&catid='.$catid.'&id='. $id;
		}

		if( $itemid != '' ) {
			$link .= '&Itemid=' . $itemid;
		} else if($item = SimpleCalendarHelperRoute::_findItem($needles)) {
			$link .= '&Itemid=' . $item->id;
		};
		return $link;
	}

	function _findItem($needles) {
		$component =& JComponentHelper::getComponent('com_simplecalendar');

		$match = null;
		
		$menus	= & JSite::getMenu();
		$items	= $menus->getItems('componentid', $component->id);
		$user 	= & JFactory::getUser();
		$access = (int)$user->get('aid');

		foreach($needles as $needle => $id) {
			if ( isset($items) ) {
				foreach($items as $item) {
					if ((@$item->query['view'] == $needle) && (@$item->query['id'] == $id) && ($item->published == 1) && ($item->access <= $access)) {
						$match = $item;// $item;
						break;
					}
				}
				if(isset($match)) {
					break;
				}
				foreach($items as $item) {
					if (@$item->query['view'] == 'calendar' && $item->published == 1 && $item->access <= $access) {
						$match = $item; // $item;
						break;
					}
				}
			} else {
				return false;
			}
		}
		return $match;
	}
}
?>