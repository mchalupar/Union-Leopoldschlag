<?php
defined('_JEXEC') or die('Restricted access');

$document =& JFactory::getDocument();

$document->addScript("http://maps.google.com/maps?file=api&amp;v=2&amp;key=" . $this->config->gmap_api_key . "&sensor=false");
$document->addScript(JURI::base()."components/com_simplecalendar/assets/js/K_CrossControl_v2.js");
JHTML::stylesheet('simplecal.css','administrator/components/com_simplecalendar/assets/css/');

$std_latlon = $this->config->gmap_std_latlon;

$js = "
    var map = null;
    var geocoder = null;
    var marker = null;
    var importlatlon = '';
    var caller = '';
    if ( window.parent.document.getElementById('caller').value != null ) {
    	caller = window.parent.document.getElementById('caller').value;
    }
    var latlon = '';
    var address = '';
    var place = '';
    
    if ( caller == 'frontend' || caller == 'backend_entry' ) {
	    latlon = window.parent.document.getElementById('entryLatLon').value;
	    address = window.parent.document.getElementById('entryAddress').value;
	    place = window.parent.document.getElementById('entryPlace').value;
    } else {
    	latlon = window.parent.document.getElementById('groupLatLon').value;
    }

// NEW PART ------------------------------------------------------------------------

	function initialize() {
        if (GBrowserIsCompatible()) {
            
			map = new GMap2(document.getElementById(\"map_canvas\"));
			map.setCenter(new GLatLng($std_latlon), 5);
			map.addMapType(G_PHYSICAL_MAP);
	
	        map.addControl(new GLargeMapControl());
	        map.addControl(new GOverviewMapControl());
	        
	        map.enableScrollWheelZoom();
	        
	    	crossControl=new K_CrossControl();
	    	map.addControl(crossControl);
	
	        mapControl = new GHierarchicalMapTypeControl();
	        
	        mapControl.clearRelationships();
	        mapControl.addRelationship(G_SATELLITE_MAP, G_HYBRID_MAP, \"Labels\", false);
	
	        map.addControl(mapControl);
	
	        geocoder = new GClientGeocoder();
	        center = map.getCenter();
	        marker2 = new GMarker(center);
	        
	        if (latlon != '') {
	        	setLatLon(latlon);
	        	displaySave();
	        } else if (address != '' || place != '') {
	        	setAddress(address + ' ' + place);
	        	displaySave();
	        } 
	        document.gmapform.address.focus();
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
    
    function setAddress(address) {
      removeMarker();
      if (geocoder) {
        geocoder.getLatLng(
          address,
          function(point) {
            if (!point) {
              alert(address + ' not found');
              displaySave();
            } else {
              map.setCenter(point, 13);
              var marker2 = new GMarker(point);
              map.addOverlay(marker2);
              marker2.openInfoWindowHtml(address);
              center = map.getCenter();
              string = center.lat()+','+center.lng();
			  document.getElementById(\"currlatlon\").value = string;
			  displaySave();
            }
          }
        );
      }
    }
    
    function setLatLon(latlon1) {
    	var latlonArray = new Array();
	    latlonArray = latlon1.split(',');
	    var lat = latlonArray['0'];
	    var lon = latlonArray['1'];
    
    	if(lat.length > 0 && lon.length > 0) {
    		removeMarker();
	    	map.setCenter(new GLatLng ( lat , lon ), 13 );
	    	center = map.getCenter();
	    	marker2 = new GMarker(center);
	    	map.addOverlay(marker2);
	    	document.getElementById(\"currlatlon\").value = latlon1;
	    	displaySave();
    	} else {
    		removeMarker();
		}
    }
    
    function getLatLon() {
    	removeMarker();
		center = map.getCenter();
		marker2 = new GMarker(center);
		map.addOverlay(marker2);
		string = center.lat()+','+center.lng();
		document.getElementById(\"currlatlon\").value = string;
		displaySave();
    }

    function removeMarker() {
		map.removeOverlay(marker2);
		document.getElementById(\"currlatlon\").value = '';
		displaySave();
    }
    	
	function displaySave() {
		document.getElementById(\"saveandclose\").style.visibility=\"visible\";
	}
";
$document->addScriptDeclaration($js);

?>
<form name="gmapform" action="#" onsubmit="setAddress(this.address.value); return false;" style="padding: 10px;">
<h1 class='componentheading'>
	<?php
		echo JText::_('Google Maps');
	?>
</h1>
<div class="clear"></div>
<p><?php echo JText::_('Search'); ?>&nbsp;
	<input type="text" size="60" name="address" value="" />
	<input type="image"
		style="border: 0;"
		border="0"
		src="components/com_simplecalendar/assets/images/magnifier.png"
		alt="<?php echo JText::_('Search'); ?>" />
</p>
<div id="map_canvas" style="width: 100%; height: 400px"></div>
<div id="gmap_buttons">
	<div class="gmap_buttons save" style="float: right; clear: right;" id="saveandclose">
		<span class="gmap_save"
			style="cursor:pointer;"
			onclick="window.parent.selectLatLon(document.getElementById('currlatlon').value);" >
			<?php echo JText::_('Save position and close') ?>
		</span>
		<br />
	</div>
	<div class="gmap_buttons refine">
		<span class="gmap_set" onclick="getLatLon();"><?php echo JText::_('Set marker'); ?></span>&nbsp;
		<span class="gmap_remove" onclick="removeMarker();"><?php echo JText::_('Remove marker'); ?></span>
	</div>
<input type="text"
	size="64"
	name="currlatlon"
	id="currlatlon"
	value=""
	onchange="setLatLon(this.value);" 
	style="display: none;" />
</div>
</form>
