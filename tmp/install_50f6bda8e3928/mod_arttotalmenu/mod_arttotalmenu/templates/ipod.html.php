<?php
/*
 * Art Total menu: ipod template
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

if ($moduleParams["_registry"] && $moduleParams["_registry"]["_default"] && $moduleParams["_registry"]["_default"]["data"]) {
  $p = $moduleParams["_registry"]["_default"]["data"];
  $menuLabel = $p -> menuLabel;
} else {
  $menuLabel = $moduleParams['menuLabel'];
}
?>

<?php
if (!function_exists('artMenuIpod_traverseMenuChildrenItems')) {
	function artMenuIpod_traverseMenuChildrenItems($menu, $parentId = 0) {
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


	?>
			<li><a href="<?php echo $link;?>"><?php echo $menuItem->name;?></a>
	<?php
			if (isset($menuItem->children) && count($menuItem->children) > 0) {
				echo '<ul>';
				artMenuIpod_traverseMenuChildrenItems($menuItem->children, $menuItem->id);
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
	$document->addScript( JURI::root() . 'modules/mod_arttotalmenu/mod_arttotalmenu/showtype/js/jquery.nc.js' );
	$document->addScript( JURI::root() . 'modules/mod_arttotalmenu/mod_arttotalmenu/showtype/ipod/js/fg.menu.js' );
	$document->addStyleSheet( JURI::root() . 'modules/mod_arttotalmenu/mod_arttotalmenu/showtype/ipod/css/fg.menu.css' );
	$document->addStyleSheet( JURI::root() . 'modules/mod_arttotalmenu/mod_arttotalmenu/showtype/ipod/css/theme/ui.all.css' );
?>

<a tabindex="0" href="#" class="fg-button fg-button-icon-right ui-widget ui-state-default ui-corner-all" id="arttotalmenu_<?php echo $moduleId; ?>">
	<span class="ui-icon ui-icon-triangle-1-s"></span><?php echo $menuLabel; ?>
</a>
<div id="arttotalmenucontent_<?php echo $moduleId; ?>" class="hidden">
	<ul>
	<?php
		artMenuIpod_traverseMenuChildrenItems($menu);
	?>
	</ul>
</div>
<script type="text/javascript">
	<?php
		if ($moduleParams['noConflict']) {
	?>
		atmjQuery.noConflict();
	<?php
		}
	?>
    atmjQuery(function(){
		atmjQuery('#arttotalmenu_<?php echo $moduleId; ?>').menu({ 
			content: atmjQuery('#arttotalmenucontent_<?php echo $moduleId; ?>').html(),
			width: <?php echo $moduleParams['width'] ;?>,
			maxHeight: <?php echo $moduleParams['maxHeight'] ;?>,
			showSpeed: <?php echo $moduleParams['showSpeed'] ;?>,
			callerOnState: "<?php echo $moduleParams['callerOnState'] ;?>",
			loadingState: "<?php echo $moduleParams['loadingState'] ;?>",
			linkHover: "<?php echo $moduleParams['linkHover'] ;?>",
			linkHoverSecondary: "<?php echo $moduleParams['linkHoverSecondary'] ;?>",
			crossSpeed: <?php echo $moduleParams['crossSpeed'] ;?>,
			crumbDefaultText: "<?php echo $moduleParams['crumbDefaultText'] ;?>",
			backLink: <?php echo $moduleParams['backLink'] ;?>,
			backLinkText: "<?php echo $moduleParams['backLinkText'] ;?>",
			flyOut: <?php echo $moduleParams['flyOut'] ;?>,
			flyOutOnState: "<?php echo $moduleParams['flyOutOnState'] ;?>",
			nextMenuLink: "<?php echo $moduleParams['nextMenuLink'] ;?>",
			topLinkText: "<?php echo $moduleParams['topLinkText'] ;?>",
			nextCrumbLink: "<?php echo $moduleParams['nextCrumbLink'] ;?>"
		});
	});
</script>
<?php
}
?>

<!-- Art Total Menu Joomla! module. Copyright (c) 2009 Artetics, www.artetics.com.com -->
<!-- http://www.artetics.com/ARTools/art-totalmenu -->