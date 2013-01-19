var defaultMenuAreaObject  = false;
var currentMenuAreaObject  = false;
var currentMenuFadeOpacity = 100;
var menuTrueEventCounter   = 0;
var menuParsedEventCounter = 0;
var menuDefaultChecked     = false;
var menuEvents             = new Array();



/* make the current menu area stay visible and set the fade if needed */
function menuMakeCurrent(newMenuAreaObject, fadeMenu)
{
    menuKeepCurrent();
    
    if(defaultMenuAreaObject  == false && menuDefaultChecked == false)
    {
        menuSetDefault();
        
        if(defaultMenuAreaObject != false )
        {
        	currentMenuAreaObject = defaultMenuAreaObject;	
        }
    }
    
     
	   
	if (currentMenuAreaObject == newMenuAreaObject)
	{
		return;	
	}
    
    
	if(defaultMenuAreaObject != false && newMenuAreaObject != defaultMenuAreaObject)
    {
        defaultMenuAreaObject.className = 'menuArea';
        menuTurnOffImage(defaultMenuAreaObject.id + 'Image');
    }
    
    
	if (currentMenuAreaObject)
	{
		currentMenuAreaObject.className = 'menuArea';
        menuTurnOffImage(currentMenuAreaObject.id + 'Image'); 
	}                
	
    
	currentMenuAreaObject = newMenuAreaObject;
	

	currentMenuAreaObject.className = 'menuAreaCurrent';
	menuTurnOnImage(currentMenuAreaObject.id + 'Image'); 
     
	if(fadeMenu == 'fade')
	{
		var menuFade = document.getElementById('menuFade');
		menuFade.style.visibility = 'visible';
		menuFade.className        = 'opacity100';
		currentMenuFadeOpacity    = 100;
		setTimeout(fadeMenuIn, 		100);
	}
}


function menuTurnOffImage(imageId)
{
	
	if(!document.getElementById(imageId))
	{
		return;
	}
	
    var src = document.getElementById(imageId).src;
    
    src     = src.replace(/menuAreaOn/, 'menuAreaOff');
    document.getElementById(imageId).src = src;
    
}

function menuTurnOnImage(imageId)
{
	
	if(!document.getElementById(imageId))
	{
		return;
	}
	
    var src = document.getElementById(imageId).src;
    src     = src.replace(/menuAreaOff/, 'menuAreaOn');
    document.getElementById(imageId).src = src;
    
}

/* Looping timeout function to create the fade effect 
targets a div positioned in front of the menu items rather than the actual items themselves */

function fadeMenuIn()
{
	
	var menuFade = document.getElementById('menuFade');
	currentMenuFadeOpacity -= 20;
	
	if(currentMenuFadeOpacity > 0) 
	{
		menuFade.className        = 'opacity' + currentMenuFadeOpacity;
		setTimeout(fadeMenuIn, 100);
	}
	else
	{
		menuFade.style.visibility = 'hidden';
	}
}



function menuSetDefault()
{
    menuDefaultChecked = true;
    
    var liObjects = document.getElementsByTagName('li');
    
    for (var i = 0; i < liObjects.length; i++)
    { 
    
        if(liObjects[i].className == 'menuAreaCurrent')
        {
            defaultMenuAreaObject =  liObjects[i];
            break;
    
        }
    }
}




function menuReset()
{
	setTimeout(resetMenu, 4000);
	menuTrueEventCounter ++;
    menuEvents.push('off');
}


function menuKeepCurrent()
{
    setTimeout(resetMenu, 4000);
	menuTrueEventCounter ++;
    menuEvents.push('on');
}


function resetMenu()
{
	menuParsedEventCounter++;
    	
	if(defaultMenuAreaObject != false && menuTrueEventCounter == menuParsedEventCounter)
	{
        if(menuEvents[menuTrueEventCounter-1] == 'off')
        {
		    menuMakeCurrent(defaultMenuAreaObject, 'fade');
        }
	}
	
}

/* For the corporate Menu */
/*
var currentCorpMenuObj;
var currentCorpMenuObjOpac;

function fadeIn(obj)
{
	obj.style.className = 'opacity' + 20;
	currentCorpMenuObj = obj;
	currentCorpMenuObjOpac = 0;
	setTimeout(fadeInLoop, 60);
}

function fadeInLoop()
{
	if(currentCorpMenuObjOpac <= 80)
	{
		currentCorpMenuObjOpac += 20;
		currentCorpMenuObj.className = 'opacity' + currentCorpMenuObjOpac;
		setTimeout(fadeInLoop, 60);
	}
	else
	{
		currentCorpMenuObj.className = 'opacity100';
	}
}

*/