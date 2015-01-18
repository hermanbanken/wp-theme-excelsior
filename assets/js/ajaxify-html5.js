// Ajaxify
// v1.0.1 - 30 September, 2012
// https://github.com/browserstate/ajaxify
(function(window,undefined){
	
	// Prepare our Variables
	var
		History = window.History,
		$ = window.jQuery,
		document = window.document;

	// Check to see if History.js is enabled for our Browser
	if ( !History.enabled ) {
		return false;
	}

	// Wait for Document
	$(function(){
		// Prepare Variables
		var
			/* Application Specific Variables */
			contentSelector = '#content',
			$content = $(contentSelector).filter(':first'),
			contentNode = $content.get(0),
			$menu = $('#menu-primary-navigation').filter(':first'),
			activeClass = 'current-menu-item current active',
			activeSelector = '.current-menu-item,.current,.active',
			menuChildrenSelector = 'li, ul > li',
			completedEventName = 'statechangecomplete',
			/* Application Generic Variables */
			$window = $(window),
			$body = $(document.body),
			rootUrl = History.getRootUrl(),
			scrollOptions = {
				duration: 800,
				easing:'swing'
			};
		
		// Ensure Content
		if ( $content.length === 0 ) {
			$content = $body;
		}
		
		// Internal Helper
		$.expr[':'].internal = function(obj, index, meta, stack){
			// Prepare
			var
				$this = $(obj),
				url = $this.attr('href')||'',
				isInternalLink;
			
			// Check link
			isInternalLink = url.indexOf("wp-admin") < 0 && url.substring(0,rootUrl.length) === rootUrl || url.indexOf(':') === -1;
			
			// Ignore or Keep
			return isInternalLink;
		};
		
		// HTML Helper
		var documentHtml = function(html){
			// Prepare
			var result = String(html)
				.replace(/<\!DOCTYPE[^>]*>/i, '')
				.replace(/<(body) class="(.*?)"([\s\>])/gi, '<div class="document-$1 $2"$3')
				.replace(/<(html|head|title|meta|script)([\s\>])/gi,'<div class="document-$1"$2')
				.replace(/<\/(html|head|body|title|meta|script)\>/gi,'</div>')
			;
			
			// Return
			return $.trim(result);
		};
		
		// Ajaxify Helper
		$.fn.ajaxify = function(){
			// Prepare
			var $this = $(this);
			
			// Ajaxify
			$this.find('a[href]:internal:not(.no-ajaxy)').click(function(event){
				// Prepare
				var
					$this = $(this).addClass("ajaxified"),
					url = $this.attr('href'),
					title = $this.attr('title')||null;
					
				// Continue for files
				if ( url.indexOf("/assets/") >= 0 || /\.[a-z0-9]{2,4}$/.test(url) ) { return true; }
				
				// Continue as normal for cmd clicks etc
				if ( event.which == 2 || event.metaKey ) { return true; }
				
				// Ajaxify this link
				History.pushState(null,title,url);
				event.preventDefault();
				return false;
			});
			
			// Chain
			return $this;
		};
		
		// Ajaxify our Internal Links
		$body.ajaxify();
		
		// Hook into State Changes
		$window.bind('statechange',function(){
			// Prepare Variables
			var
				State = History.getState(),
				url = State.url,
				relativeUrl = url.replace(rootUrl,'');

			// Set Loading
			$body.addClass('loading');

			// Start Fade Out
			// Animating to opacity to 0 still keeps the element's height intact
			// Which prevents that annoying pop bang issue when loading in new content
			$content.animate({opacity:0},800);
			
			// Ajax Request the Traditional Page
			$.ajax({
				url: url,
				datatype: 'html',
				success: function(data, textStatus, jqXHR){
					
					// Prepare
					var
						$data = $(documentHtml(data)),
						$dataBody = $data.find('.document-body:first'),
						$dataContent = $dataBody.find(contentSelector).filter(':first'),
						$menuChildren, contentHtml, $scripts;
						
					// Fetch the scripts
					$scripts = $data.find('.document-script');
					if ( $scripts.length ) {
						$scripts.detach();
					}
					
					// Fetch the content
					contentHtml = $dataContent.html()||$data.html();
					if ( !contentHtml ) {
						document.location.href = url;
						return false;
					}
					
					$("#columnleft").replaceWith($data.find('#columnleft'));
					$("#content").replaceWith($data.find('#content'));
					$columnleft = $("#columnleft").ajaxify();
					$content = $("#content").ajaxify();
					
					if($data.find("#wpadminbar").size())
						$("#wpadminbar").replaceWith($data.find("#wpadminbar"));
					
					// Update the content
					$content.stop(true,true);
					$content.css('opacity',100).show(); /* you could fade in here if you'd like */

					// Update the title
					document.title = $data.find('.document-title:first').text();
					try {
						document.getElementsByTagName('title')[0].innerHTML = document.title.replace('<','&lt;').replace('>','&gt;').replace(' & ',' &amp; ');
					}
					catch ( Exception ) { }
					
					// Update the body classes
					$body.attr("class", $dataBody.removeClass("document-body").attr('class')).removeClass('loading');
					$window.trigger(completedEventName).trigger("load");
					
					// Add the scripts
					$scripts.each(function(){
						var $script = $(this), scriptText = this.innerHTML, scriptNode = document.createElement('script');
						var src = $script.attr('src') || "";
						
						// For now, only recall DISQUS and event-organiser scripts
						if(src.indexOf("event-organiser") > -1 || src.indexOf("http://maps.googleapis.com") > -1){}
						else
						if(scriptText.indexOf("disqus") == -1 && scriptText.indexOf("eventorganiser") == -1) return;
						
						if ( $script.attr('src') ) {
							if ( !$script[0].async ) { scriptNode.async = false; }
							scriptNode.src = $script.attr('src');
						}
    				try {
							scriptNode.appendChild(document.createTextNode(scriptText));
							document.body.appendChild(scriptNode);
						} catch(e){
							try { eval(scriptText); } catch(e){}
						}
					});
					
					// Complete the change
					if ( $body.ScrollTo||false ) { $body.ScrollTo(scrollOptions); } /* http://balupton.com/projects/jquery-scrollto */
					
					if(typeof eo_load_map !== 'undefined') eo_load_map();
					
					// Inform Google Analytics of the change
					if ( typeof window._gaq !== 'undefined' ) {
						window._gaq.push(['_trackPageview', relativeUrl]);
					}
				},
				error: function(jqXHR, textStatus, errorThrown){
					document.location.href = url;
					return false;
				}
			}); // end ajax

		}); // end onStateChange

	}); // end onDomLoad

})(window); // end closure
