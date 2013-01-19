<?php
/**
 * @package		Joomla.Site
 * @subpackage	Templates.beez_20
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

/**
 * beezDivision chrome.
 *
 * @since	1.6
 */


function modChrome_accordion($module, &$params, &$attribs) {
	
    if ($module->showtitle) {
    	echo ("
		<li>
        	<h2><span>".$module->title."</span></h2>
        	<div>".$module->content."</div>
        </li>
	");
	};
}?>