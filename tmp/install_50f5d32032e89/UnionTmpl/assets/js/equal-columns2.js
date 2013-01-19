// This Javascript is written by Peter Velichkov (www.creonfx.com)
// and is distributed under the following license : http://creativecommons.org/licenses/by-sa/3.0/
// Use and modify all you want just keep this comment. Thanks

function equalHeight(cl){

	var className = '.' + cl;
	var maxHeight = 0;

	$$(className).each(function(el){
		if(el.offsetHeight > maxHeight){
			maxHeight = el.offsetHeight;
		}
	});
	
	if($$('.dummyExtender') != ''){
		$$('.dummyExtender').each(function(el){
			el.setStyle('height', maxHeight - el.getParent().offsetHeight + el.offsetHeight);
		});
	}else{
		$$(className).each(function(el){
			var curExtender = new Element('div',{'class': 'dummyExtender'});
			curExtender.injectInside(el);
			curExtender.setStyle('height', maxHeight - el.offsetHeight);

		});
	}
}

window.addEvent('load', function(){
	equalHeight('equals');
});