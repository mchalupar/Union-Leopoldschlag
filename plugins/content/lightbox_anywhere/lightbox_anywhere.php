<?php
/**
 * @version		$id: $
 * @author		Joomseller!
 * @package		Joomla!
 * @subpackage	Lightbox Anywhere
 * @copyright	Copyright (C) 2008 - 2011 by Joomseller Solutions. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL version 3, See LICENSE.txt
 */

defined ('_JEXEC') or die ('Restricted access');
jimport('joomla.event.plugin');
global $mainframe;
$mainframe = JFactory::getApplication();
class plgContentLightbox_Anywhere extends JPlugin {

	var $plugin					= null;

	function plgContentLightbox_Anywhere (&$subject, $params) {
		parent::__construct($subject, $params);
		$this->plugin		= JPluginHelper::getPlugin('content', 'lightbox_anywhere');
	}

	public function onContentBeforeDisplay($context, &$article, &$pparams, $page=0) {
		global $mainframe;
		$params		= $this->params;
		// get config from plugin configuration
		$option		= JRequest::getVar('option');

		$integration	= $params->get('integration');
		if (is_array($integration)) $components = $integration;
		else $components[]	= $integration;

		//if (! $this->plugin) return ;
		$view =  JRequest::getVar('view');
		$layout =  JRequest::getVar('layout');
		if ($option == 'com_content' || $option == 'com_k2'|| $option == 'com_virtuemart'|| @in_array($option, $components)) {

			switch ($option) {
				case 'com_content':

					if($view == 'article') {
						// check add plugin ??
						$check = $this->checkPluginDisplay( $article->id);
						if($check){
							$this->article($article, $params, $page);
						}
						//$this->article($article, $params, $page);
					}

					break;

				case 'com_k2':
					
					if($view == 'item') {
						// check add plugin ??
						$check = $this->checkPluginDisplayK2($article->id);
						if($check){
							$this->article($article, $params, $page);
						}					
					}
					
					break;
				default:
					break;
			}
		}

	}
	function onContentPrepare($context, &$article, &$params, $page = 0){
		global $mainframe;
		$params		= $this->params;
		// get config from plugin configuration
		$option		= JRequest::getVar('option');

		$integration	= $params->get('integration');
		if (is_array($integration)) $components = $integration;
		else $components[]	= $integration;

		//if (! $this->plugin) return ;
		$view =  JRequest::getVar('view');
		if($option == 'com_virtuemart'){
			// check add plugin ?
			$integration = $this->params->get('integration');
			if(is_array($integration) && $integration){
				$integration = implode(',', $integration);
			}else{
				$integration = (string)$integration;
			}
			$check	= substr_count( $integration, 'com_virtuemart');
			if($check){
				if($view == 'productdetails') {
					$this->article($article, $params, $page);
				}

			}
		}
	}
	/**
	 * Check Plugin Display on com_content
	 *
	 */
	function checkPluginDisplay($id) {
		$db = JFactory::getDBO();
		$integration = $this->params->get('integration');
		if(is_array($integration) && $integration){
			$integration = implode(',', $integration);
		}else{
			$integration = (string)$integration;
		}
		$check	= substr_count( $integration, 'com_content');
		
		if($check){
			$sectionid	= $this->params->get('content_sectionid');
			$catid		= $this->params->get('content_catid');
			$articleid	= $this->params->get('content_articleid');

			if (is_array($sectionid) && count($sectionid)) {
				$sections	= implode(',', $sectionid);
			} else {
				$sections	= $sectionid;
			}
			if (is_array($catid) && count($catid)) {
				$categories	= implode(',', $catid);
			} else {
				$categories	= $catid;
			}
			if (is_array($articleid) && count($articleid)) {
				$articles	= implode(',', $articleid);
			} else {
				$articles	= $articleid;
			}
			///////////
			if($categories != -1 &&  $categories != ''){
			  $categories_str	= ' AND c.catid IN('. $categories.")";
			}else{
			  $categories_str	= "";
			}
			if($sections != -1 && $sections != ''){
			  $sections_str		= ' AND c.sectionid IN('. $sections.")";
			}else{
			  $sections_str		= "";
			}
			if($articles != 0 && $articles != ''){
			  $articles_str		= ' AND c.id IN('. $articles.")";
			}else{
			  $articles_str		= "";
			}
			////////////
			$query = 'SELECT count(c.id) FROM #__content AS c WHERE c.state =1 AND c.id ='. $id . $categories_str. $articles_str. $sections_str;
			$db->setQuery($query);
			$checkPlugin = $db->loadResult();
			return $checkPlugin;
		}

	}

	/**
	 * Check Plugin Display on com_k2
	 *
	 */
	function checkPluginDisplayK2($id) {
		$db = JFactory::getDBO();
		$integration = $this->params->get('integration');
		if(is_array($integration) && $integration){
			$integration = implode(',', $integration);
		}else{
			$integration = (string)$integration;
		}
		$check	= substr_count( $integration, 'com_k2');
		if($check){
			$catid	= $this->params->get('k2_catid');
			$itemid	= $this->params->get('k2_itemid');
			if (is_array($catid) && count($catid)) {
				$categories	= implode(',', $catid);
			} else {
				$categories	= $catid;
			}
			if (is_array($itemid) && count($itemid)) {
				$items	= implode(',', $itemid);
			} else {
				$items	= $itemid;
			}
			///////////
			if($categories != -1 && $categories != 0){
			  $categories_str	= 'AND i.catid IN('. $categories.")";
			}else{
			  $categories_str	= "";
			}
			if($items != 0){
			  $item_str		= 'AND i.id IN('. $items.")";
			}else{
			  $item_str		= "";
			}
			////////////
			$query = 'SELECT count(i.id) FROM #__k2_items AS i WHERE i.published = 1 AND i.id = '.$id .$categories_str. $item_str;
			$db->setQuery($query);
			$check = $db->loadResult();
		}
		return $check;

	}
	/**
	 * Check Plugin Display on com_k2
	 *
	 */
	function checkPluginDisplayVM() {
		$integration = $this->params->get('integration');
		if(is_array($integration) && $integration){
			$integration = implode(',', $integration);
		}else{
			$integration = (string)$integration;
		}
		$check	= substr_count( $integration, 'com_virtuemart');
		return $check;

	}
	
	function article(&$article, &$params, $page) {
		
		$images = $this->getImage($article->text);
		// insert link and class="lightbox" in images
		if($images){
			foreach ($images as $image) {
					$linkimg	 = $image['org_src'];
					// get type lightbox
					$rel = $this->params->get('lightbox_style', 'lightbox[roadtrip]');

					if($rel!='fancybox-buttons'){
						$lightbox	= '<span class="formInfo"><a rel="'.$rel.'" class="lightbox" href="'.$linkimg.'">'.$image['org']. '</a></span>';
						$article->text = str_replace($image['org'], $lightbox, $article->text);
					}else{
						$lightbox	= '<span class="formInfo"><a data-fancybox-group="button" class="fancybox-buttons" href="'.$linkimg.'">'.$image['org']. '</a></span>';
						$article->text = str_replace($image['org'], $lightbox, $article->text);
					}
			}
		}
		
	}
	
	function getImage($text) {
		$regex = "/\<img[^\>]*>/";
		$regex = "/\<img[^\>]*>/";

		//Get all images
		if (!preg_match_all ($regex, $text, $matches)) return;
		$images = array();
		foreach ($matches[0] as $image) {
			$regex = '#(<img.*)src\s*=\s*(["\'])(.*?)\2(.*\/?>)#im';
			if (!preg_match ($regex, $image, $srcs)) continue;
			//Check exclude images
			if(!empty($this->_excludeImgs) && in_array($srcs[3], $this->_excludeImgs)) continue;
			//end checked
			if (($src = $this->processImage ($srcs[3]))) {
				$new_image = $srcs[1]."src=".$srcs[2].$src.$srcs[2].$srcs[4];
				//remove height/width
				$regex = '#(<img.*)height\s*=\s*(["\'])(.*?)\2(.*\/?>)#im';
				if (preg_match ($regex, $new_image, $srcs1))
					$new_image = $srcs1[1].$srcs1[4];
				$regex = '#(<img.*)width\s*=\s*(["\'])(.*?)\2(.*\/?>)#im';
				if (preg_match ($regex, $new_image, $srcs1))
					$new_image = $srcs1[1].$srcs1[4];
				//$obj = array('org'=>$srcs[3], 'new'=>$src);
				$images[] = array('org'=>$image, 'org_src'=>$srcs[3], 'new'=>$new_image);
			}
		}

		if (!count($images)) return '';
		$this->renderThumbnail ($images);
		return $images;
	}

	function renderThumbnail ($images) {
		// get Lightbox Style
		if($this->params){
			$lightboxstyle = $this->params->get('lightbox_style');
		}
		// get Layout
		switch ($lightboxstyle) {
		 case 'lightbox[roadtrip]':
			 $layout_box = 'slimbox';
		  break;

		  case 'fancybox-buttons':
			 $layout_box = 'fancybox';
		  break;
		  case 'group1':
			 $layout_box = 'evolution';
		  break;
	  
		 default:
			 $layout_box = 'slimbox';
		  break;
		}
		$layout = $this->getLayoutPath ($this->plugin, $layout_box);
		if ($layout) {
			ob_start();
			require $layout;
			$content = ob_get_contents();
			ob_end_clean();
			return $content;
		}
		return $images;
	}

	function processImage ( $img ) {
		if(!$img) return '';
		$img = str_replace(JURI::base(),'',$img);
		$img = rawurldecode($img);
		if (preg_match('/https?:\/\//', $img)) return $img;
		return $img;
	}

	function getLayoutPath($plugin, $layout = 'default')
	{
		global $mainframe;

		// Build the template and base path for the layout
		$tPath = JPATH_BASE.DS.'templates'.DS.$mainframe->getTemplate().DS.'html'.DS.$plugin->name.DS.$layout.'.php';
		$bPath = JPATH_BASE.DS.'plugins'.DS.$plugin->type.DS.$plugin->name.DS.$plugin->name.DS.'tmpl'.DS.$layout.DS.$layout.'.php';
		// If the template has a layout override use it
		if (file_exists($tPath)) {
			return $tPath;
		} elseif (file_exists($bPath)) {
			return $bPath;
		}
		return '';
	}

}
