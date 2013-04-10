/* Author:
	Herman Banken
*/

// jQuery Masonry
$(function(){
	$container = $('.blog .articles, .archive .articles');
	if ( $container.find("article").size() > 1 )
	{
		$container.imagesLoaded(function(){
			$container.masonry({
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
	}
});