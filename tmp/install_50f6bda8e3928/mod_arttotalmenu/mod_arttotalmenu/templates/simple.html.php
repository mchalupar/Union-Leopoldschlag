<?php
/*
 * Art Total menu: simple template
 *
 * @version		1.0.0
 * @author		Artetics
 * @copyright	Copyright (c) 2009 www.artetics.com. All rights reserved
 * @license		GNU/GPL (http://www.gnu.org/copyleft/gpl.html)
 */

$moduleParams = $params['moduleParams'];
$moduleId = $params['moduleId'];
$menu = $params['menu']; 
$selectedId = $params['selectedId'];
$selectedIndex = $params['selectedIndex'];
$loadJQuery = $params['loadJQuery'];
?>

<?php

if (!function_exists('artMenuSimple_traverseMenuChildrenItems')) {
	function artMenuSimple_traverseMenuChildrenItems($menu, $parentId = 0) {
    $Itemid = JRequest::getString('Itemid'); 
		foreach ($menu as $menuItem) {
		    $version = new JVersion(); 
		    if ($version->RELEASE >= 1.6) {
			if ($parentId == 0) {
				$parentId = 1;
			}
		    }

			if ((isset($menuItem->parent) && $menuItem->parent != $parentId) || (isset($menuItem->parent_id) && $menuItem->parent_id != $parentId) || $menuItem->type == 'separator') continue;

			$link = $menuItem->link;
			if ($menuItem->type == 'menulink') {
				$jMenu = &JSite::getMenu();
				$itemIdStrPos = strpos($menuItem->link, 'Itemid=');
				$newItem = $jMenu->getItem(substr($menuItem->link, $itemIdStrPos + 7));
			}
			else if ($link && ($menuItem->type == 'component' || strpos($link, 'http') !== 0)) { 
				$link .= (strpos($link, '?') !== false) ? '&' : '?';
				$link .= 'Itemid=' . $menuItem->id;
			}
			$app = &JFactory::getApplication();
			$router = &$app->getRouter();
			$link = JRoute::_($link, false);
      
      if (!$menuItem->name) {
        $menuItem->name = $menuItem->title;
      }

      if ($menuItem->type == 'separator') {
      ?>
        <li class="separator">&nbsp;</li>
      <?php
      } else {
	?>
			<li <?php if ($Itemid == $menuItem->id) {echo ' class="menuactive"';} ?>><a 
      <?php
      if ($menuItem->browserNav) {echo ' target="_blank" ';};
      ?>
      href="<?php echo $link;?>"><?php echo $menuItem->name;?></a>
	<?php
     }
			if (isset($menuItem->children) && count($menuItem->children) > 0) {
				echo '<ul class="sub_menu">';
				artMenuSimple_traverseMenuChildrenItems($menuItem->children, $menuItem->id);
				echo '</ul>';
			}
	?>
			</li>
	<?php
		}
	}
}

if ($menu) {
	$document = &JFactory::getDocument();
	if ($loadJQuery) {
		$document->addScript( JURI::root() . 'modules/mod_arttotalmenu/mod_arttotalmenu/showtype/js/jquery-1.3.2.min.js' );
	}
	$document->addScript( JURI::root() . 'modules/mod_arttotalmenu/mod_arttotalmenu/showtype/simple/js/jquery.dropdownplain.js' );
	$document->addStyleSheet( JURI::root() . 'modules/mod_arttotalmenu/mod_arttotalmenu/showtype/simple/css/style.css' );
?>

<div id="arttotalmenucontent_<?php echo $moduleId; ?>" class="hidden">
	<ul class="dropdown">
	<?php
		artMenuSimple_traverseMenuChildrenItems($menu);
	?>
	</ul>
</div>
<script type="text/javascript">
	<?php
		if ($moduleParams['noConflict']) {
	?>
		jQuery.noConflict();
	<?php
		}
	?>
</script>
<?php
}
?>

<!-- Art Total Menu Joomla! module. Copyright (c) 2009 Artetics, www.artetics.com.com -->
<!-- http://www.artetics.com/ARTools/art-totalmenu -->