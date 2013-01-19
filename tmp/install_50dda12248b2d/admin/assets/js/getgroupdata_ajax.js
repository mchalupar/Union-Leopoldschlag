    // AJAX request
    // Tutorial: http://www.xul.fr/en-xml-ajax.html

	var myRequest = false;
	var mySite = "";
	var myPath = "";
	var myArray = new Array();
	var use_gmap = "";
	
	function doAjax(site, path, cid, confirmText, gmap) {
		// variable "site" can be either "admin" or "site"
		if ( cid != 0 ) {
			var isOk = confirm("" + confirmText + "");
			use_gmap = "" + gmap + "";
			mySite = site;
			myPath = path;
			if(isOk) {
				 document.getElementById("ajax_load").style.visibility = "visible";
			     myRequest = false;
			     if (window.XMLHttpRequest) {
			          myRequest = new XMLHttpRequest();
			          if (myRequest.overrideMimeType) {
			               myRequest.overrideMimeType("text/plain");
			          }
			     } else if (window.ActiveXObject) {
			          try {
			               myRequest = new
			                    ActiveXObject("Msxml2.XMLHTTP");
			          } catch (e) {
			               try {
			                    myRequest = new
			                         ActiveXObject("Microsoft.XMLHTTP");
			               } catch (e) {}
			          }
			     }
			     if (!myRequest) {
			          alert("Error: Cannot create XMLHTTP object");
			          return false;
			     }
			     
			     myRequest.onreadystatechange = displayReturn;
			     if (mySite == "admin") {
			     	if ( use_gmap == "1" ) {
			     		removeMarker();
			     	}
			     	myRequest.open("GET", myPath + "index.php?option=com_simplecalendar&controller=group&task=edit&cid[]="+ cid +"&format=raw", "false");
			     } else {
			     	myRequest.open("GET", myPath + "index.php?option=com_simplecalendar&view=group&cid[]="+ cid +"&format=raw", "false");
			     }
			     myRequest.send(null);
			}
		}
	}
	
	function displayReturn() {
	    if (myRequest.readyState == 4) {
	          if (myRequest.status == 200) {
	               var httpResponse = myRequest.responseText
	               var myArray = new Array();
	               myArray = httpResponse.split("<br>");
	               document.getElementById("contactName").value = myArray["0"];
	               document.getElementById("contactEmail").value = myArray["1"];
	               document.getElementById("contactWebSite").value = myArray["2"];
	               document.getElementById("contactTelephone").value = myArray["3"];
	               if (mySite == "admin") {
	                	if (myArray["4"] != "" && use_gmap ) {
	                		setLatLon(myArray["4"]);
	                	}
	                	document.getElementById("entryLatLon").value = myArray["4"];
	               }
	               document.getElementById("ajax_load").style.visibility = "hidden";
	          } else {
	               alert("There was a problem with the request. Status code: " + myRequest.status);
	          }
    	 }
	}