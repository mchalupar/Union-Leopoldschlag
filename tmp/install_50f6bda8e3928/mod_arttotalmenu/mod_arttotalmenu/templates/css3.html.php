<?php
/*
 * Art Total menu: CSS3 template
 *
 * @version		1.0.0
 * @author		Artetics
 * @copyright	Copyright (c) 2009 www.artetics.com. All rights reserved
 * @license		GNU/GPL (http://www.gnu.org/copyleft/gpl.html)
 */
error_reporting(E_ERROR);

$moduleParams = $params['moduleParams'];
$moduleId = $params['moduleId'];
$menu = $params['menu']; 
$selectedId = $params['selectedId'];
$selectedIndex = $params['selectedIndex'];
$loadJQuery = $params['loadJQuery'];
$itemId = JRequest::getString('Itemid');
if ($moduleParams["_registry"] && $moduleParams["_registry"]["_default"] && $moduleParams["_registry"]["_default"]["data"]) {
  $p = $moduleParams["_registry"]["_default"]["data"];
  $menuLabel = $p -> menuLabel;
}
?>

<?php

if (!function_exists('artMenuCSS3_traverseMenuChildrenItems')) {
	function artMenuCSS3_traverseMenuChildrenItems($menu, $parentId = 0, $itemId = 0) {
		foreach ($menu as $menuItem) {
		    $version = new JVersion(); 
		    if ($version->RELEASE >= 1.6) {
			if ($parentId == 0) {
				$parentId = 1;
			}
		    }

			if ((isset($menuItem->parent) && $menuItem->parent != $parentId) || (isset($menuItem->parent_id) && $menuItem->parent_id != $parentId) || $menuItem->type == 'separator') continue;


			$link = $menuItem->link;
      if (!$menuItem->name) {
        $menuItem->name = $menuItem->title;
      }
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
			$liClass = '';
			if ($menuItem->id == $itemId) {
				$liClass = 'active';
			} else {
        $parentActive = false;
        foreach ($menuItem->children as $childItem) {
          if ($childItem->id == $itemId) {
            $parentActive = true;
            break;
          }
        }
      }
      if ($parentActive) {
        $liClass = 'active';
      }

	?>
			<li class="<?php echo $liClass; ?>"><a href="<?php echo $link;?>"><?php echo $menuItem->name;?></a>
	<?php
			if (isset($menuItem->children) && count($menuItem->children) > 0) {
				echo '<ul>';
				artMenuCSS3_traverseMenuChildrenItems($menuItem->children, $menuItem->id, $itemId);
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
	$document->addStyleSheet( JURI::root() . 'modules/mod_arttotalmenu/mod_arttotalmenu/showtype/css3/css/style.css' );
?>

<ul id="nav">
<?php
	artMenuCSS3_traverseMenuChildrenItems($menu, 0, $itemId);
?>
</ul>
<?php
}
?>

<!-- Art Total Menu Joomla! module. Copyright (c) 2009 Artetics, www.artetics.com.com -->
<!-- http://www.artetics.com/ARTools/art-totalmenu -->