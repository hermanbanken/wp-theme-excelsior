/* Author:
	Herman Banken
*/

$(function(){
	if($('.blog .articles, .archive .articles').find("article").size() > 1) 
	$('.blog .articles, .archive .articles').masonry({
	  itemSelector: 'article',
		// set columnWidth a fraction of the container width
		columnWidth: function( containerWidth ) {
			return $("article").width();
			
			if ( containerWidth > 1000 )
				return containerWidth / 5;
			else if ( containerWidth > 800 )
				return containerWidth / 4;
			else if ( containerWidth > 600 )
				return containerWidth / 3;
			else
				return 200;
		}
	});
});



