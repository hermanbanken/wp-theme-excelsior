/* Author:
	Herman Banken
*/

// jQuery Masonry
$(window).on("load", function(){
	$container = $('.articles');
	
	if ( $container.find("article").size() > 1 )
	{
		$container.imagesLoaded(function(){
			$container.masonry({
			  itemSelector: 'article',
				// set columnWidth a fraction of the container width
				columnWidth: function( containerWidth ) {
					var w = $container.find(".layout-archive").width();
					console.log("Width: ",w);
					return w || 200;
					
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
	
	$container.infinitescroll({
    navSelector  : '.post-nav',    // selector for the paged navigation 
    nextSelector : '.post-nav .previous a',  // selector for the NEXT link (to page 2)
    itemSelector : '.box',     // selector for all items you'll retrieve
    loading: {
        finishedMsg: 'No more pages to load.',
        img: 'http://i.imgur.com/6RMhx.gif'
      }
    },
    // trigger Masonry as a callback
    function( newElements ) {
      // hide new items while they are loading
      var $newElems = $( newElements ).css({ opacity: 0 });
      // ensure that images load before adding to masonry layout
      $newElems.imagesLoaded(function(){
        // show elems now they're ready
        $newElems.animate({ opacity: 1 });
        $container.masonry( 'appended', $newElems, true ); 
      });
    }
  );
});

// Change height of iFrames to always be 16:9
$(window).on("resize load", function(e){
	$(".media-container iframe").each(function(){
		$(this).height(9 * $(this).width() / 16);
	});
});