<?php
// no direct access
defined('_JEXEC') or die('Restricted access');

JHTML::stylesheet('simplecal.css','administrator/components/com_simplecalendar/assets/css/');

jimport('joomla.application.component.view');

class SimpleCalendarViewEntry extends JView {

	function display($tpl = null) {
		global $mainframe, $option;

		// load params from the component
		$params 	=& JComponentHelper::getParams( 'com_simplecalendar' );
		$config 	=  SCOutput::config();
		$user 		=& JFactory::getUser();
		$editor 	=& JFactory::getEditor();
		$document	=& JFactory::getDocument();

		// get the data from the model
		$item =& $this->get('Data');

		// check if it is new (bool)
		$isNew = false;
		if (!isset($item)) { 
			$isNew = true;
		} else {
			$isNew = false;
		}

		$lists = array();
		
		if ( $isNew ) {
			// prevent undefined properties
			$item->userid = $user->id;
			$item->published = 1;
			$item->entryIsPrivate = 0;
			$item->entryGroupID = 0;
			$item->no_to_time = 0;
		} else {
//			$lists['file_info'] = explode('|', $item->attached_file);	
			$lists['file_info'] = '';
		}

		$js = "	
		function selectLatLon(latlon) {
			document.getElementById('entryLatLon').value = latlon;
			if ( document.getElementById('entryLatLon').value != '' ) {
				document.getElementById('btnSet').style.display = 'none';
				document.getElementById('btnModify').style.visibility = 'visible';
			} else {
				document.getElementById('btnSet').style.display = 'inline';
				document.getElementById('btnModify').style.visibility = 'hidden';
			}
			if(window.parent.document.getElementById('sbox-window')) {
			    window.parent.document.getElementById('sbox-window').close = function() {
			        window.parent.SqueezeBox.close();
			    }
			}
//			window.parent.SqueezeBox.close();
			window.parent.document.getElementById( 'sbox-window' ).close();
			document.getElementById('entryLatLon').onchange();
		}";
		$document->addScriptDeclaration($js);

		//make data safe
		JFilterOutput::objectHTMLSafe( $item, ENT_QUOTES, 'entryInfo' );
		
		$text = $isNew ? JText::_( 'New' ) : JText::_( 'Edit' );
		
		$lists['published'] = JHTML::_('select.booleanlist',  'published', 'class="inputbox"', $item->published );
		$lists['isPrivate'] = JHTML::_('select.booleanlist',  'entryIsPrivate', 'class="inputbox"', $item->entryIsPrivate);
		$lists['categories'] = $this->get('CategoryList');
		$lists['statuses'] = $this->get('StatusList');
//		$lists['no_to_time'] = SCOutput::checkbox('no_to_time', $item->no_to_time, 'onclick="nototime();"');
		$lists['is_favourite'] = JHTML::_('select.booleanlist',  'is_favourite', 'class="inputbox"', $item->is_favourite);
		
		$lists['groups'] = false;
		if ( count($this->get('GroupList')) > 0 ) {
			$lists['groups'] = $this->get('GroupList');
		}
		
		$lists['isNew'] = $isNew;
		
		$this->assignRef('params', $params);
		$this->assignRef('config', $config);
		$this->assignRef('lists', $lists);		
		$this->assignRef('editor', $editor);
		$this->assignRef('item', $item);
		
		
		JToolBarHelper::title(JText::_('CALENDARTEXT').': <small><small>[ ' . $text.' ]</small></small>', 'generic.png');
		
		if (sizeof($lists['categories']) == 0)  {
			JToolBarHelper::cancel( 'cancel', 'Close' );
			echo '<form action="index.php?option=com_simplecalendar&amp;controller=calendar&amp;view=calendar" method="post" name="adminForm" id="adminForm">'."\n";
			echo JText::_('No category defined yet. Please define at least one category before adding entries.')."\n";
			echo JText::_('By clicking on Close you will be redirected to the Categories List.')."\n";
			echo '<input type="hidden" name="option" value="com_simplecalendar" />'."\n".
				'<input type="hidden" name="task" value="" />'."\n".
				'<input type="hidden" name="controller" value="category" />';
			echo '</form>'."\n";
		} else {		
			JToolBarHelper::save();
			JToolBarHelper::apply();
			if ($isNew)  {
				JToolBarHelper::cancel();
			} else {
				// for existing items the button is renamed `close`
				JToolBarHelper::cancel( 'cancel', 'Close' );
			}
			parent::display($tpl);
		}
	}
}
?>