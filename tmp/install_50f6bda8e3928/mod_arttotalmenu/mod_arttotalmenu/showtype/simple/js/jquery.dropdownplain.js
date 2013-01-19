if (typeof (atmjQuery) == 'undefined') {
	atmjQuery = jQuery;
}

atmjQuery(function(){

    atmjQuery("ul.dropdown li").hover(function(){
    
        atmjQuery(this).addClass("hover");
        atmjQuery('ul:first',this).css('visibility', 'visible');
    
    }, function(){
    
        atmjQuery(this).removeClass("hover");
        atmjQuery('ul:first',this).css('visibility', 'hidden');
    
    });
    
    atmjQuery("ul.dropdown li ul li:has(ul)").find("a:first").append(" &raquo; ");

});