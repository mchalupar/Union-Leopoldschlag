<?php
/**
 * @version		$Id: view.html.php,v 1.14 2010/01/28 21:13:31 fabrizio Exp $
 * @package		Joomla
 * @subpackage	SimpleCal
 * @copyright	Copyright (C) 2008-2009 Fabrizio Albonico.
 * @license		GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant to the
 * GNU General Public License, and as distributed it includes or is derivative
 * of works licensed under the GNU General Public License or other free or open
 * source software licenses. See COPYRIGHT.php for copyright notices and
 * details.
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

//require JPATH_COMPONENT_ADMINISTRATOR . DS . 'classes' . DS . 'output.class.php';
JHTML::stylesheet('simplecal_front.css','components/com_simplecalendar/assets/css/');

jimport('joomla.application.component.view');
require(JPATH_COMPONENT.DS.'classes' . DS . 'recaptchalib.php');

class SimpleCalendarViewForm extends JView {

	function display($tpl = null) {
		
	    global $option, $mainframe;
	    
	    $config 		= SCOutput::config();
	    
		if( $this->getLayout() == 'gmap' ) {
			$this->assignRef('config', $config);
			$this->_displaygmap($tpl);
			return;
		}
		
	    $user 			=& JFactory::getUser();
	    $component		=  JComponentHelper::getComponent( 'com_simplecalendar' );
	    $model 			=& $this->getModel('form');
	    $document		=& JFactory::getDocument();
	    $pathway 		=& $mainframe->getPathWay();
	    $uri			=  JFactory::getURI();
	    $params 		=  new JParameter( $component->params );
	    $editor 		=& JFactory::getEditor();
		$menu			= & JSite::getMenu();
		$menuitem		= $menu->getActive();
		
		if ( !isset($menuitem->name) || $menuitem->name  == '' ) {
			$menuitem->name = JText::_('Calendar');
		}
	    
	    $catid 		= '';
	    $options 	= '';
	    
	    JFilterOutput::objectHTMLSafe( $item, ENT_QUOTES, 'entryInfo' );
	
	    // Initialize form validator
		JHTML::_('behavior.formvalidation');
		JHTML::_('behavior.modal', 'a.modal');

		$item = $model->getDetail();
		$slug = '';
				
		$js = "
		function selectLatLon(latlon) {
			document.getElementById('entryLatLon').value = latlon;
			if ( document.getElementById('entryLatLon').value != '' ) {
				document.getElementById('btnSet').style.display = 'none';
				document.getElementById('btnModify').style.visibility = 'visible';
			} else {
				document.getElementById('btnSet').style.display = 'inline';
				document.getElementById('btnModify').style.visibility = 'hidden';
			}			if(window.parent.document.getElementById('sbox-window')) {
			    window.parent.document.getElementById('sbox-window').close = function() {
			        window.parent.SqueezeBox.close();
			    }
			}
//			window.parent.SqueezeBox.close();
			window.parent.document.getElementById( 'sbox-window' ).close();
		}";

		$document->addScriptDeclaration($js);
		
		//
		
		
		// check if it is a new event 
		if (!isset($isNew) && $item == null) {
			$isNew = true;
		} else {
			$isNew = false;
		}
		if ( ( $isNew && $user->gid >= $config->frontend_add_gid ) ||
			 ( !$isNew && ( $user->gid >= $config->frontend_edit_gid || $user->id == $item->userid )) ||
			 ( $isNew && $config->allow_unregistered_submission ) ) {
			 	
			if ( $isNew ) {
				$document->setTitle( $menuitem->name.' | ' . JText::_('NEW EVENT') );
				$pathway->addItem( JText::_('NEW EVENT') );
				// set default values
				$item->userid = $user->id;
				$item->published = 1;
				$item->entryIsPrivate = 0;
				$item->entryGroupID = 0;
				$item->attached_file = '';
				$item->entryName = '';
				$item->entryAddress = '';
			} else {
				if (isset ($item->slug)) {
					$slug = $item->slug;
				}
				$document->setTitle( $menuitem->name.' | ' . $item->entryName . ' - ' . JText::_('MODIFY EVENT'). ' - '.$item->entryName );
				$pathway->addItem( JText::_('MODIFY EVENT'). ' :: '.$item->entryName, JRoute::_('index.php?view=details&id='.$slug));				
			}
			
			$text = $isNew ? JText::_('New') : JText::_('Edit');
			
			$lists = array();
			
			$lists['showGroups'] = false;
			if ( count($model->getGroupList()) > 0 ) {
				$lists['showGroups'] = true;
			}
			
			$lists['published'] = JHTML::_('select.booleanlist',  'published', 'class="inputbox"', $item->published );
			$lists['file_info'] = explode('|', $item->attached_file);
			$lists['groups'] = SCOutput::getGroupsComboBox( intval($item->entryGroupID) , 'site' );
			$lists['isPrivate'] = JHTML::_('select.booleanlist',  'entryIsPrivate', 'class="inputbox"', $item->entryIsPrivate);
			$lists['is_favourite'] = JHTML::_('select.booleanlist',  'is_favourite', 'class="inputbox"', $item->is_favourite);
			
			$lists['categories'] = $this->get('CategoriesList');
			$lists['statuses'] = $this->get('StatusList');
			$lists['text'] = $text;
			$lists['isNew'] = $isNew;

			$this->assignRef('item', $item);
			$this->assignRef('lists', $lists);
			$this->assignRef('user', $user);
			$this->assignRef('params', $params);
			$this->assignRef('print', $print);
			$this->assignRef('config', $config);
			$this->assignRef('editor', $editor);
			
			parent::display($tpl);
			
		} else {
			$return		= $uri->toString();
			$url  = 'index.php?option=com_user&view=login';
			$url .= '&return='.base64_encode($return);;
			$mainframe->redirect($url, JText::_('ALERTNOTAUTH') );
			
		}
	}
	
	function _displaygmap($tpl) {
		global $mainframe;

		$document	= & JFactory::getDocument();
		$params 	= & $mainframe->getParams();

		$document->setTitle(JText::_( 'Google maps' ));
		parent::display($tpl);
	}
	
}
?>