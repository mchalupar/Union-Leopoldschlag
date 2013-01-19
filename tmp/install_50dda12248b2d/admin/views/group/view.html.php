<?php
// no direct access
defined('_JEXEC') or die('Restricted access');

JHTML::stylesheet('simplecal.css','administrator/components/com_simplecalendar/assets/css/');

jimport('joomla.application.component.view');

class SimpleCalendarViewGroup extends JView {

	function display($tpl = null) {
		$params			=& JComponentHelper::getParams( 'com_simplecalendar' );
		$config 		= SCOutput::config();
		$document		=& JFactory::getDocument();
		JHTML::_('behavior.modal', 'a.modal');
		
//		Initializing GoogleMaps Info variables 
		$gmailString = '';
		$latlon = '46,9';
		$zoom = '5';
		$addMarker = false;
		
		// check if GoogleMaps is active & API key is provided (in backend-params)
		if ($config->use_gmap == 0 || $config->gmap_api_key == '') {
			$gmailString = JText::_('GoogleMaps is not enabled');
		} else {
			$document->addScript('http://maps.google.com/maps?file=api&amp;v=2&amp;key='.trim($config->gmap_api_key).'&sensor=false');
		}	
		
		// get the data from the model
		$item =& $this->get('Data');
		
		
//		check if entry has Lat/Lng coordinates for map display
		if ( isset($item->groupLatLon) ) {
			if ($item->groupLatLon != '') {
				$latlon = $item->groupLatLon;
				$zoom = '13';
				$addMarker = true;
			}
		} else {
			$latlon = $config->gmap_std_latlon;
			$zoom = '5';
			$addMarker = false;
		}
	
		$js = "
		function selectLatLon(latlon) {
			document.getElementById('groupLatLon').value = latlon;
			document.getElementById('groupLatLon').onchange();
			if ( document.getElementById('groupLatLon').value != '' ) {
				document.getElementById('btnSet').style.display = 'none';
				document.getElementById('btnModify').style.visibility = 'visible';
			} else {
				document.getElementById('btnSet').style.display = 'inline';
				document.getElementById('btnModify').style.visibility = 'hidden';
			}
			document.getElementById('sbox-window').close();
		}
			
		function initialize() {
			if (GBrowserIsCompatible()) {
				map = new GMap2(document.getElementById(\"map_canvas\"));
				map.setCenter(new GLatLng($latlon), $zoom);
				map.addControl(new GLargeMapControl());
		        geocoder = new GClientGeocoder();
		        center = map.getCenter();
		        marker = new GMarker(center);";
		
		if($addMarker) {
	     	$text = JText::_('CURRENTLY_SAVED_POSITION_GROUP');
	     	$js .= "
	     		document.getElementById('groupLatLon').onchange();
				map.addOverlay(marker);
				marker.openInfoWindowHtml('$text<br /><b>$item->groupName</b>');";
		}
		
		$js .= "
			}
		}
		
		function addLoadEvent(func) {
		  var oldonload = window.onload;
		  if (typeof window.onload != 'function') {
		    window.onload = func;
		  } else {
		    window.onload = function() {
		      if (oldonload) {
		        oldonload();
		      }
		      func();
		    };
		  }
		}
		
		addLoadEvent(initialize);
		
		// arrange for our onunload handler to 'listen' for onunload events
		if (window.attachEvent) {
		        window.attachEvent(\"onunload\", function() {
		                GUnload();      // Internet Explorer
		        });
		} else {
		        window.addEventListener(\"unload\", function() {
		                GUnload(); // Firefox and standard browsers
		        }, false);
		}
		
		function setLatLon(latlon) {
	    	if(latlon.length > 0) {
	    		removeMarker();
		    	var latlonArray = new Array();
		    	latlonArray = latlon.split(\",\");
		    	var lat = latlonArray[\"0\"];
		    	var lon = latlonArray[\"1\"];
		    	map.setCenter(new GLatLng ( lat , lon ), 13 );
		    	center = map.getCenter();
		    	marker = new GMarker(center);
		    	map.addOverlay(marker);
		    	document.getElementById(\"groupLatLon\").value = latlon;
	    	} else {
	    		removeMarker();
			}
	    }
	
	    function removeMarker() {
			map.removeOverlay(marker);
			document.getElementById(\"groupLatLon\").value = \"\";
	    }
		";
		$document->addScriptDeclaration($js);
						
		// check if it is new (bool)
		$isNew = false;
		if ( isset($item) ) {		
			$isNew = true;
		} else { 
			$isNew = false;
		}
		
		$text = $isNew ? JText::_( 'New' ) : JText::_( 'Edit' );
		$lists = array();
		$lists['isNew'] = $isNew;

		JToolBarHelper::title(JText::_('GROUP').': <small><small>[ ' . $text.' ]</small></small>', 'user.png');
		JToolBarHelper::save();
		JToolBarHelper::apply();
		
		if ($isNew)  {
			JToolBarHelper::cancel();
		} else {
			JToolBarHelper::cancel( 'cancel', 'Close' );
		}

		$this->assignRef('item', $item);
		$this->assignRef('lists', $lists);
		$this->assignRef('config', $config);
		
		parent::display($tpl);
	}
}
?>