<?php
/**
 * @version		$Id: $
 * @author		Nguyen Dinh Luan
 * @package		Joomla!
 * $subpackage	Dropline_Menu
 * @copyright	Copyright (C) 2008 - 2011 Joomseller Solutions. All rights reserved.
 * @license		GNU/GPL http://www.gnu.org/licenses/gpl.html, see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

//SERVER CONFIG
if (array_key_exists('HTTP_USER_AGENT', $_SERVER)) {
	$is_ie7 = strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'msie 7') !== false;
	$is_ie6 = strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'msie 6') !== false;
	$is_ff = strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'firefox') !== false;
	$is_sf = strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'safari') !== false;
}

//OsJoomla configuration
global $module_baseurl;

$oj_module_name = 'mod_dropline_menu';
$module_baseurl = 'modules/' . $oj_module_name;

if ($subalign) { ?>
	<style type="text/css">
		.menuArea, .menuAreaCurrent {
			position:relative;
		}
		.subItemsRight {
			right:0;
		}
		/* Sub items for menu areas whose parent is on the right side */
		.subItemsRight li {
			float:right;
		}
	</style>
<?php
} else { ?>
	<style type="text/css">
		.subItemsRight {
			left:0;
		}
		.subItemsRight li {
			float:left;
		}
	</style>
<?php
}

if ($is_ie6) {
	?>
	<style type="text/css">
		body {behavior: url("<?php echo $module_baseurl; ?> /assets/css/csshover.htc");}
	</style>
    <link rel="stylesheet" type="text/css" href="<?php echo $module_baseurl; ?>/assets/css/ie6hacks.css">
	<?php
	}
?>
<div id="menu" style="clear: left; float: none;">
	<ul class="dropline_menu">
		<?php foreach ($navs as $nav) {?>
		<li class="<?php echo $nav->active ? "menuAreaCurrent":"menuArea"; ?>" onmouseover="menuMakeCurrent(this, 'fade')" onmouseout="menuReset()" <?php echo "id=".$nav->li_id; ?>>
			<a <?php echo $nav->browserNav; ?> href="<?php echo $nav->link; ?>" class="mainItem">
				<?php echo $nav->title; ?><img <?php echo "id=".$nav->li_id."Image"; ?> src="<?php echo $module_baseurl; ?>/assets/images/<?php echo $nav->active ? "menuAreaOn-orange.gif":"menuAreaOff-orange.gif"; ?>" width="10" height="10">
			</a>
			<?php if(count($nav->subs)) { ?>
				<ul class="<?php echo $nav->right_ul ? "subItemsRight":"subItems"; ?>">
				<?php foreach ($nav->subs as $sub) { ?>
					<li<?php echo $sub->active ? ' id="menuItemCurrent"':""; ?>>
						<a <?php echo $sub->browserNav; ?> href="<?php echo $sub->link; ?>"><?php echo $sub->title; ?></a>
					</li>
				<?php } ?>
				</ul>
			<?php } ?>
		</li>
		<?php } ?>
	</ul>
    <div class="opacity20" style="visibility: hidden;" id="menuFade">&nbsp;</div>
</div>