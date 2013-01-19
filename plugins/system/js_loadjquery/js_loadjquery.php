<?php
/**
* @version		$Id$
* @athour		Nguyen Dinh Luan
* @package		Joomla
* @subpackage	Plugin
* @copyright	Copyright (C) 2008 - 2010 Joomseller Solutions. All rights reserved.
* @license		http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL, see LICENSE.php
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.plugin.plugin' );

/**
 * Joomla! Add jQuery Plugin
 * @package		Joomla
 * @subpackage	System
 */
class  plgSystemJS_LoadjQuery extends JPlugin {

	/**
	 * Constructor
	 */
	function __construct(& $subject, $params) {
		parent::__construct($subject, $params);
	}

	function onAfterRender(){
		global $mainframe;

		if ($this->_jQueryExist() && $this->checkCanAdd()) {
			if ($this->checkCanAdd()) {
				$this->_addjQueryLib();
			}
		}
	}

	function _addjQueryLib() {
		$document	= &JFactory::getDocument();
		$scripts	= $document->_scripts;
		$script		= $document->_script;
		$jquery_name	= $this->params->get('jquery_version');
		$scriptlink		= JUri::root().'/plugins/system/js_loadjquery/libraries/jquery/'.$jquery_name;
		$link			= '<script type="text/javascript" src="'. $scriptlink. '"></script>';
		$found	= false;
		foreach ($scripts as $key=>$value) {
			if ($key == $scriptlink) $found = true;
		}
		if (!$found) {

			$html	= JResponse::getBody();
			
			//insert no conflict
			if ($this->params->get('jconflict')) {
				$found	= false;
				foreach ($script as $key=>$value) {
					if ($value == 'jQuery.noConflict();') $found = true;
				}
				if (!$found) {
					$js		= '<script type="text/javascript">jQuery.noConflict();</script>';
					$link	.= $js;
				}
			}

			// insert jquery library
			$html = str_replace ('<head>', "<head>\n\r".$link, $html);

			// Update HTML content
			JResponse::setBody($html);
			unset($html);
		}
	}

	/**
	 *	Check if jQuery exist and don't add anymore
	 *
	 * @access private
	 * @return boolean
	 */

	function _jQueryExist() {
		if (!$this->params->get('anymore')) {
			return true;
		} else {
			$html		= strtolower(JResponse::getBody());
			$pattern	= '|<script[^>]*jquery[^/]*[^/]*.js*[^<]*</script>|s';
			preg_match ($pattern, $html, $matchs);
			if (count($matchs)) {
				return false;
			} else return true;
		}
	}

	/**
	 * Check the page which can add jQuery by configuration
	 *
	 * @return boolean
	 */
	function checkCanAdd() {

		$app	= JFactory::getApplication('site');

		// check to remove on frontend or backend
		$add	= false;
		switch ($this->params->get('addwhere')) {
			case 'frontend':
				if ($app->isSite()) $add = true;
				break;
			case 'backend':
				if ($app->isAdmin()) $add = true;
				break;
			case 'both':
				$add	= true;
				break;
		}

		/*
		if (!$this->params->get('pages', 0) && $add) {
			return true;
		} elseif ($this->checkMenuIds() || $this->checkComponents() && $add) {
			return true;
		}
		return false;
		 */
		return $add;
	}

	function checkMenuIds() {
		global $mainframe;
		if ($mainframe->isSite()) {
			$listMenus = array();
			$listMenus = explode(',',$this->params->get('menuIDs'));

			$menus	= &JSite::getMenu();

			$active	= $menus->getActive();

			if (in_array($menus->getActive()->id, $listMenus) == true ) {
				$menuIdsFound = 1;
			}
			unset($listMenus);

			if ($menuIdsFound == 1) {
				return true;
			} else {
				return false;
			}
		}
		return false;
	}


	function checkComponents() {
		$litComponents	= array();
  		$litComponents	= explode(',',$this->params->get('components'));

		$option	= JRequest::getCmd('option');

		if (in_array($option, $litComponents) == true ) {
			$componentsFound = 1;
		}
  		unset($litComponents);

		if ($componentsFound) {
			return true;
		} else {
			return false;
		}
	}
}