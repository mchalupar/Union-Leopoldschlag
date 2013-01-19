<?php
/**
 * @version		$id: $
 * @author		Joomseller!
 * @package		Joomla!
 * @subpackage	Lightbox Anywhere
 * @copyright	Copyright (C) 2008 - 2011 by Joomseller Solutions. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL version 3, See LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Form Field class for the Joomla Framework.
 *
 * @package		Joomla.Framework
 * @subpackage	Form
 * @since		1.6
 */
class JFormFieldSeparator extends JFormField
{
	/**
	 * The form param type.
	 *
	 * @var		string
	 * @since	1.6
	 */
	public $type = 'separator';

	/**
	 * fetch Element
	 */
	function getInput(){
		return '<div style="color:#fff; font-size:12px; font-weight:bold; padding:3px; margin:0; text-align:center; background:#333333; float: left; width: 60%;">'.JText::_($this->element['default']).'</div>';
	}
}

