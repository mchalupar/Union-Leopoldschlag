function equalHeights() {
var height = 0;

divs = $$('#bottom1','#bottom2','#bottom3');
 
divs.each( function(e){
 if (e.offsetHeight > height){
  height = e.offsetHeight;
 }
});
 
divs.each( function(e){
 e.setStyle( 'height', height + 'px' );
 if (e.offsetHeight > height) {
  e.setStyle( 'height', (height - (e.offsetHeight - height)) + 'px' );
 }
}); 
}