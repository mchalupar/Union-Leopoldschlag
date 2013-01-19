<?php
// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

JHTML::stylesheet('simplecal.css','administrator/components/com_simplecalendar/assets/css/');

class SimpleCalendarViewCssedit extends JView {

	function display($tpl = null) {
		global $mainframe, $option;
		
		JHTML::_('behavior.tooltip');
		JHTML::_('behavior.keepalive');

		$params 	=& JComponentHelper::getParams( 'com_simplecalendar' );
		$document 	=& JFactory::getDocument();
		$user 		=& JFactory::getUser();
		$option		=  JRequest::getVar('option');
		
//		if ($user->get('gid') < 24) {
//			JError::raiseWarning( 'SOME_ERROR_CODE', JText::_( 'ALERTNOTAUTH'));
//			$mainframe->redirect( 'index.php?option=com_simplecalendar&view=calendar' );
//		}
		
		$filename	= 'simplecal_front.css';
		$path		= JPATH_SITE.DS.'components'.DS.'com_simplecalendar'.DS.'assets'.DS.'css';
		$css_path	= $path.DS.$filename;
		
		
		JToolBarHelper::title(JText::_('CSS Editor'), 'thememanager.png');
		JToolBarHelper::save('savecss');
//		JToolBarHelper::apply('applycss');
		JToolBarHelper::cancel();
		//read the the stylesheet
		jimport('joomla.filesystem.file');
		$content = JFile::read($css_path);
		
		jimport('joomla.client.helper');
//		$ftp =& JClientHelper::setCredentialsFromRequest('ftp');

		if ($content !== false)
		{
			$content = htmlspecialchars($content, ENT_COMPAT, 'UTF-8');
		}
		else
		{
			$msg = JText::sprintf('FAILED TO OPEN FILE FOR WRITING', $css_path);
			$mainframe->redirect('index.php?option='.$option, $msg);
		}

		//assign data to template
		$this->assignRef('css_path'		, $css_path);
		$this->assignRef('content'		, $content);
		$this->assignRef('filename'		, $filename);
//		$this->assignRef('ftp'			, $ftp);
		

		parent::display($tpl);
		
		// Footer. Please do not remove.
		echo SCOutput::showFooter();
	}
}
?>