/* Author:
	Herman Banken
*/

// jQuery Masonry
$(window).on("load", function(){
	$container = $('.articles');
	
	var options = {
	  itemSelector: 'article, .box',
		// set columnWidth a fraction of the container width
		columnWidth: function( containerWidth ) {
			var w = $container.find(".layout-archive, .box:not(.layout-single)").width();
			//console.log("Width: ",w);
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
	};
	
	if ( $container.find("article, .box").size() > 1 )
	{
		$container.imagesLoaded(function(){
			if(!$container.data( 'masonry' ))
				$container.masonry(options);
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
				
				// Setup if not yet set-up
				if(!$container.data( 'masonry' )) 
					$container.masonry(options);
				else
					$container.masonry( 'appended', $newElems, true );
				
				// make sure all content gets processed the same as when AJAX'ing
				$(window).trigger("load");
      });
    }
  );
});

// Change height of iFrames to always be 16:9
$(window).on("resize load", function(e){
	$(".media-container iframe").each(function(){
		$(this).height(9 * $(this).width() / 16);
	});
	$(".layout-archive iframe:not(.media-container iframe)").each(function(){
		var p = $(this).parents(".inner").width();
		$(this).css("margin", "0 -15px 10px").width(p).height(9 * $(this).width() / 16);
	});
});

// Gallery show functionality
$(window).on("load", function(e){
	$(".media-container.gallery-preview").each(function(){
		var i = 0, gallery = $(this);
				
		function update(i){
			var url = gallery.find(".image").slice(i, i+1).attr('src');
			gallery.find(".ratio-controller").css("backgroundImage", "url("+url+")");
		}
				
		update(0);
		//gallery.find("img").first().show();
		gallery.on("click", ".next", function(){ 
			//var c = gallery.find("img:visible");
			if(i+1 < gallery.find(".image").size())
				update(++i);
			return false;
		}).on("click", ".prev", function(){ 
			//var c = gallery.find("img:visible");
			if(i-1 >= 0)
				update(--i);
			//if(c.prev("img").size() > 0)
			//	c.hide().prev().show();
			return false;
		});
	});
});

$(function(){
	$(document.body).on("submit", "#commentform",function(){
		if($(this).find("[name=facebook]").val()) {
			$(this).removeClass("error");
		} else {
			$(this).addClass("error");
			return false;
		}
	}).on("click", ".providerLogin", function(){
		var provider = $(this).attr('data-provider'),
				target = $($(this).attr('data-target')).addClass("login-in-progress");
		
		if(provider == "facebook" && window.FB){
			FB.login(function(response){
				//console.log(response);
				if (response.authResponse) {
					var sr = response.authResponse.signedRequest;
					target.find('.with-user').append("<input type='hidden' name='facebook' value='"+sr+"' />");

			  	FB.api('/me', function(user) {
						//console.log("/me", user);
						var logout = target.find(".logout").one("click", function(){
							FB.logout();
							target.find("[name=facebook]").remove();
							target.removeClass('loggedin');
						});
						target.addClass('loggedin');
						target.find('.displayname').text(user.name);
						target.removeClass("login-in-progress");
					});

			  } else {
					//console.log('User cancelled login or did not fully authorize.');
					target.removeClass("login-in-progress").append("<div class='warning'>Login failed</div>");
			  }
			}, {scope: 'email,user_likes'});
		}
	});
});