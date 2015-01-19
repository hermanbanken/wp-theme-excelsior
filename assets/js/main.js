(function(){
/* Author: Herman Banken */

var masonryOptions = {
  itemSelector: 'article',
  // set columnWidth a fraction of the container width
  columnWidth: ".layout-archive:not(.double)",
};

// jQuery Masonry
$(window).on("load", function(){
    $container = $('.articles');
    
    // Archive page
    if ( $container.find("article").size() > 1 )
    {
        $container.imagesLoaded(function(){
            if(!$container.data( 'masonry' ) && document.body.clientWidth > 480)
                $container.masonry(masonryOptions);
        });
    
        $container.infinitescroll({
            navSelector  : '.post-nav',// selector for the paged navigation 
            nextSelector : '.post-nav .previous a',  // selector for the NEXT link (to page 2)
            itemSelector : '.box',     // selector for all items you'll retrieve
            stamp        : '.stamp',   // selector for all stamped items
            gutter       : 0,
            loading: {
                finishedMsg: 'No more pages to load.',
                img: 'http://i.imgur.com/6RMhx.gif'
              }
            },
            // trigger Masonry as a callback
            function( newElements ) {
                // hide new items while they are loading
                var $newElems = $( newElements ).css({ opacity: 0 });
                $newElems.filter(":not(.layout-archive)").remove();
                // ensure that images load before adding to masonry layout
                $newElems.imagesLoaded(function(){
                    // show elems now they're ready
                    $newElems.animate({ opacity: 1 });
                            
                    // Setup if not yet set-up
                    if(!$container.data( 'masonry' )) 
                        $container.masonry(masonryOptions);
                    else
                        $container.masonry( 'appended', $newElems, true );
                    
                    // make sure all content gets processed the same as when AJAX'ing
                    $(window).trigger("load");
                });
            }
        );

    } 
    // No archive page
    else {
        if($container.data( 'masonry' )) 
            $container.masonry( 'destroy' );
        $container.infinitescroll('destroy');
    }
});

// Disable masonry if screen is smaller than 480
$(window).on("resize", function(e){
    // On resize: disable 
    $container = $('.articles');
    if(document.body.clientWidth <= 480 && $container.data( 'masonry' )){
        $container.masonry( 'destroy' );
    } else if(!$container.data( 'masonry' )) {
        $container.masonry(masonryOptions);
    }
});

$(window).on("load", function(){
    // Restore lightbox
    if(typeof doLightBox !== 'undefined' && typeof jQuery !== 'undefined'){ doLightBox(); }
});


// Change height of iFrames to always be 16:9
$(window).on("resize load", function(e){
    //$(".media-container iframe").each(function(){
    //  $(this).height(9 * $(this).width() / 16);
    //});
    $(".layout-archive iframe:not(.media-container iframe)").each(function(){
        var p = $(this).closest(".inner").outerWidth();
        $(this).css("margin", "0 -15px 10px").width(p).height(9 * p / 16);
    });
});

$(window).on("ready", function(){
    // UserVoice JavaScript SDK (only needed once on a page)
    (function(){
        var uv=document.createElement('script');
        uv.type='text/javascript';
        uv.async=true;uv.src='//widget.uservoice.com/g32q7nFq1DGROjRp8kjxkQ.js';
        var s=document.getElementsByTagName('script')[0];
        s.parentNode.insertBefore(uv,s)
    })();
    
    $(".feedback a").on("click", function(){
        if($(".feedback .inner").is(':hidden')){
            $(".feedback .inner").show();
            $(".feedback p a").text("sluit feedback dialoog");
        } else {
            $(".feedback .inner").hide();
            $(".feedback p a").text("geef feedback");
        }
        return false;
    });
});

// Gallery show functionality
$(window).on("load", function(e){
    $(".media-container.gallery-preview").each(function(){
        var i = 0, gallery = $(this);
        
        gallery.on("click", ".ratio-controller, .full", function(){
            gallery.find(".image").slice(i, i+1).closest("a").trigger("click");
        });
        
        function update(i){
            var url = gallery.find(".image").slice(i, i+1).attr('src');
            gallery.find(".ratio-controller").css("backgroundImage", "url("+url+")");
        }
                
        update(0);
        //gallery.find("img").first().show();
        gallery.on("click", ".next i", function(){ 
            //var c = gallery.find("img:visible");
            if(i+1 < gallery.find(".image").size())
                update(++i);
            return false;
        }).on("click", ".prev i", function(){ 
            //var c = gallery.find("img:visible");
            if(i-1 >= 0)
                update(--i);
            //if(c.prev("img").size() > 0)
            //  c.hide().prev().show();
            return false;
        });
    });
});

/* Menu toggler for lower screen sizes */
$(function(){
    $(document.body).on("click", "#menu-toggle a", function(){
        var isOpen = !$(this).closest("li").hasClass("current-menu-item");
        $(this).text(isOpen ? "Verberg menu" : "Open menu");
        $(this.getAttribute('href')).toggleClass("hidden-phone", !isOpen);
        $(this).closest("li").toggleClass("current-menu-item", isOpen);
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
                        target.find(".logout").one("click", function(){
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

})(window);