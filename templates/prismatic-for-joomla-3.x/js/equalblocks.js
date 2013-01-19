
	var currentTallest = 0,
		currentRowStart = 0,
		rowDivs = new Array(),
		$el,
		topPosition = 0;
					
	jQuery('.row > div').each( function() {

		jQuery.el = jQuery(this);
		topPostion = jQuery.el.position().top;

		if (currentRowStart != topPostion) {

			// we just came to a new row.  Set all the heights on the completed row
			for (currentDiv = 0 ; currentDiv < rowDivs.length ; currentDiv++) {
			   rowDivs[currentDiv].height(currentTallest);
			}

			// set the variables for the new row
			rowDivs.length = 0; // empty the array
			currentRowStart = topPostion;
			currentTallest = jQuery.el.height();
			rowDivs.push(jQuery.el);

  		} else {

		 // another div on the current row.  Add it to the list and check if it's taller
		 rowDivs.push(jQuery.el);
		 currentTallest = (currentTallest < jQuery.el.height()) ? (jQuery.el.height()) : (currentTallest);
	
	  	}
   
	   // do the last row
	   for (currentDiv = 0 ; currentDiv < rowDivs.length ; currentDiv++) {
		 rowDivs[currentDiv].height(currentTallest);
		}
	});
