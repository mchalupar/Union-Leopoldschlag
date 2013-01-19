<?php
/**
 * @package		Joomla
 * @subpackage	SimpleCalendar
 * @copyright	2009, Copyright (C) Fabrizio Albonico. All rights reserved.
 * @license		GNU/GPL.
 * @author 		Fabrizio Albonico -- WORK IN PROGRESS / 07 november 2009
 */

defined( '_JEXEC' ) or die( 'Restricted access' );

function SimpleCalendarBuildRoute( &$query ) {
	$view = '';
	$segments = array();

//	if( isset($query['view']) ) {
//		$segments[] = $query['view'];
//		unset($query['view']);
//	}
	
	if( isset($query['catid']) ) {
		$segments[] = $query['catid'];
		unset($query['catid']);
	}

	if( isset($query['id']) ) {
		$segments[] = $query['id'];
		unset($query['id']);
	}

	if( isset($query['task']) ) {
		$segments[] = $query['task'];
		unset($query['task']);
	}

//
//	if( isset($query['order']) ) {
//		$segments[] = $query['order'];
//		unset($query['order']);
//	}
	unset($query['view']);
	
	return $segments;
}


/**
 *
 * @param $segments
 * @return $vars query parameters
 */
function SimpleCalendarParseRoute($segments) {

	$vars = array();

	$menu =& JMenu::getInstance('site');
	$item =& $menu->getActive();
	// Count segments
	$count = count( $segments );
	
	if ( $item->query['view'] == null && $count == 1 ) {
		$item->query['view'] = 'calendar';
//		array_unshift($segments, 'calendar');
	}
	if ( $item->query['view'] == null && $count == 2 ) {
		$item->query['view'] = 'detail';
		array_unshift($segments, 'detail');
	}
	if ( $item->query['view'] == null && $count == 3 ) {
		$item->query['view'] = 'form';
	}
	
	switch( $item->query['view'] ){
		case 'calendar':
			if ( $count == 1 ) {
				if ($segments[0] == 'edit') {
					$vars['view'] = 'form';
				} else {
					$vars['view'] = 'calendar';
					$catid = explode( ':', $segments[0] );
					$vars['catid'] = $catid[0];
				}
			}
			if ( $count == 2 ) {
				
				$vars['view'] = 'detail';
				$catid = explode( ':', $segments[0] );
				$vars['catid'] = $catid[0];
				$id = explode( ':', $segments[1] );
				$vars['id'] = $id[0];
			}
			if ( $count == 3 ) {
				$vars['view'] = 'form';
				$vars['task'] = 'edit';
				$catid = explode( ':', $segments[0] );
				$vars['catid'] = $catid[0];
				$id = explode( ':', $segments[1] );
				$vars['id'] = $id[0];
			}
			break;

		case 'detail':
			$vars['view'] = 'detail';
			$catid = explode( ':', $segments[1] );
			$vars['catid'] = $catid[0];
			$id = explode( ':', $segments[2] );
			$vars['id'] = $id[0];
			break;

		case 'form':
			if ( $count == 2 ) {
				$vars['view'] = 'form';
				$id = explode( ':', $segments[1] );
				$vars['id'] = (int) $id[0];
				$vars['task'] = 'edit';
				break;
			} else {
				$vars['view'] = 'form';
				$catid = explode( ':', $segments[0] );
				$vars['catid'] = $catid[0];
				$id = explode( ':', $segments[1] );
				$vars['id'] = $id[0];
				$vars['task'] = 'edit';
			}

	}
	return $vars;
}
?>