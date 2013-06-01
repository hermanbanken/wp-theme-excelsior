<?php
/**
 * Custom functions
 */
$regexUrl = "/((([A-Za-z]{3,9}:(?:\/\/)?)(?:[-;:&=\+\$,\w]+@)?[A-Za-z0-9.-]+|(?:www.|[-;:&=\+\$,\w]+@)[A-Za-z0-9.-]+)((?:\/[\+~%\/.\w-_]*)?\??(?:[-\+=&;%@.\w_]*)#?(?:[\w]*))?)/";
define("ROOTS_MEDIACONTAINER_CACHE", "_roots_mediacontainer_cache");

/**
 * Find media in posts and display
 */
function roots_get_featured_media(){
	global $wp_filter, $post;
	
	if(post_password_required()) return;
	
	if($cache = get_post_meta(get_the_ID(), ROOTS_MEDIACONTAINER_CACHE, true)){
		list($date, $value) = split("[|]", $cache, 2);
		if($date == get_the_modified_date("c"))
			return $value;
	}
	
	$content = get_the_content();
	
	$classes = array("media-container", "shade");
	//$content = apply_filters('the_content', $content);	
	$embed = false;
	
	switch( get_post_format() )
	{
		case 'video':
		case 'audio':
			// Directly defined video or audio, also find urls in post
			$urls = array(
				(array) get_post_custom_values("_wp_format_media"),
				(array) roots_get_urls($content)
			);
			$urls = array_unique(array_filter(call_user_func_array('array_merge', $urls)));
			
			if(($oembed = roots_get_first_embed($urls)) && ($embed = $oembed))
				break;
			
			if(count($urls) > 0 && get_post_format() == 'audio'){
				array_push($classes, "playlist");
				// Store playlist for filter
				$playlist = array();
				// Check all urls
				foreach($urls as $url){
					$song = array("html" => "", "data" => &$data);
					
					$filetype = wp_check_filetype($url);
					// No audio: continue;
					if(!in_array($filetype['ext'], array("wav", "wave", "mp3", "ogg")))
						continue;
					
					// Try to find id3 data
					$data = array(
						"src" => $url, "artist" => "", 
						"title" => preg_replace(array("/[^a-zA-Z0-9\.]+/", "/\.[a-z0-9]{3,4}$/"), array(" ", ""), basename($url)), 
						"length" => "", "length_formatted" => "", 
						"album" => "", "track_number" => 1, "genre" => "", "year" => ""
					);
					if($id = roots_get_attachment_id_by_url($url)){
						$meta = get_post_custom($id);
						
						foreach($data as $key => &$val){
							if(isset($meta["id3.".$key]))
								$val = $meta["id3.".$key][0];
						}
						$data["id"] = $id;
					}
					
					$song['html'] .= "<audio ";
					foreach($data as $key => $val) 
						$song['html'] .= "data-".htmlentities($key)."=\"".htmlentities($val)."\"";
					$song['html'] .= " controls><source src=\"$url\" type=\"$filetype[type]\" /></audio>";

					// Store in playlist
					$playlist[] = $song;
					unset($data);
				}
				
				$playlist = apply_filters('playlist_audio', $playlist);
				$embed = "<ul class='song-list'>";
				foreach($playlist as $song) $embed .= "<li class='song'>".$song['html']."</li>";
				$embed.= "</ul>";
				break;
			}
			
		case 'gallery':
			// Display a gallery you can slide and stuff
			$pattern = get_shortcode_regex();
		  if (   preg_match_all( '/'. $pattern .'/s', get_the_content(), $matches )
		         && array_key_exists( 2, $matches )
		         && in_array( 'gallery', $matches[2] ) )
		  {
				$index = array_search( 'gallery', $matches[2] );
				preg_match_all("|([\w]+)=.([^\s]*?). |", $matches[3][$index]." ", $attr);

				$args = array("post_mime_type" => "image", "post_parent" => get_the_ID(), "post_type" => "attachment");
				
				// WP 3+ uses ids
				if(in_array('ids', $attr[1])){
					$ids = $attr[2][array_search('ids', $attr[1])];
					$args += array("include" => explode(",", $ids));
				}
				// Before WP 3 [gallery] used {include} and {exclude}
				else {
					$include = in_array('include', $attr[1]) ? $attr[2][array_search('include', $attr[1])] : false;
					$exclude = in_array('exclude', $attr[1]) ? $attr[2][array_search('exclude', $attr[1])] : false;
					if($include) $args += array("include" => explode(",", $include));
					if($exclude) $args += array("include" => explode(",", $exclude));	 
				}
				
				$images =& get_children( $args );
				if(count($images)){
					$classes[] = "gallery-preview";
					$i = 0;
					list($first) = wp_get_attachment_image_src( current($images)->ID, 'medium' );
					$embed = "<img class='ratio-controller' src='".get_template_directory_uri() . '/assets/img/transparent-16x9.png'."' style='width:100%;background-image:url($first)' />";
					foreach($images as $img){
						$style = array("style"=>"display:none");
						$embed .= wp_get_attachment_image( $img->ID, 'medium', true, array("class"=>"image") + $style );
					}
					$embed .= "<a class='prev control'><i class='icon-chevron-left'></i></a><a class='next control'><i class='icon-chevron-right'></i></a>";
					break;
				}
			}
		default:
			// Use thumbnail if available
			if(has_post_thumbnail())
				 $embed = '<div class="media-container">'.get_the_post_thumbnail('medium').'</div>';
			
			// Look up possible attachments
			$args = array(
				'numberposts' => 1,
				'order' => 'ASC',
				'post_parent' => get_the_ID(),
				'post_type' => 'attachment',
			);
			$images =& get_children( $args + array("post_mime_type" => "image") );
			if(count($images) > 0){
				$att = current($images);
				list($src, $w, $h) = wp_get_attachment_image_src( $att->ID, 'medium' );
				$embed = "<img src='".$src."' width='".$w."' height='".$h."' />";
				break;
			}
			$videos =& get_children( $args + array("post_mime_type" => "video") );
			if(count($videos) > 0){
				$att = current($videos);
				$embed = "<video></video>";
				break;
			}
			if(count($images) + count($videos) > 0)
				echo "<pre>".print_r($images + $videos, 1)."</pre>";
			
			break;
	}

	$output = '<div class="'.implode(" ",$classes).'" data-name="'.get_the_title().'">'.$embed.'</div>';
	add_post_meta( get_the_ID(), ROOTS_MEDIACONTAINER_CACHE, get_the_modified_date("c")."|".$output, true ) 
		|| 
	update_post_meta( get_the_ID(), ROOTS_MEDIACONTAINER_CACHE, get_the_modified_date("c")."|".$output );
	
	return $output;
}

/**
	* roots_get_all_attachements
	*	Finds attachments in:
	* - wp_format_media (custom post textfield)
	* - the_content (as url)
 	* - all attached children
	*/
function roots_get_all_attachements(){
	$attachments = array();
	$uploads = wp_upload_dir();
	
	// Custom post + text urls
	$custompostvalues = (array) array_filter((array) get_post_custom_values("_wp_format_media"));
	$urls = array_merge($custompostvalues, roots_get_urls(get_the_content()));
	
	// Load all old-school attachments
	$args = array(
		'post_parent' => get_the_ID(),
		'post_type' => 'attachment',
		'orderby' => 'post_mime_type title',
		'order' => 'ASC'
	);
	$children =& get_children( $args );
	foreach($children as $child){
		if(($index = array_search(wp_get_attachment_url($child->ID), $urls)) || ($index = array_search($child->guid, $urls))){
			if(in_array($urls[$index], $custompostvalues))
				$child->featured = true;
			unset($urls[$index]);
		}
		$attachments[] = $child;
	}

	// Add all urls
	foreach($urls as $url){
		$filetype = wp_check_filetype($url);
		// If we can't find the filetype, it's probably not a file url
		if(empty($filetype['type'])) continue;
		
		// Try to find a local attachment
		if($id = roots_get_attachment_id_by_url($url)){
			$post = get_post($id);
			if(in_array($url, $custompostvalues))
				$post->featured = true;
			$attachments[] = $post;
			continue;
		}
		
		// Remote attachment
		$new = array(
			"guid" => $url,
			"post_title" => basename($url),
			"post_type" => "attachment",
			"post_mime_type" => $filetype['type']
		);
		if(in_array($url, $custompostvalues))
			$new['featured'] = true;
		
		$attachments[] = (object) $new;
	}
	
	// Remove duplicates
	$encountered = array();
	foreach($attachments as $k=>$a){
		if(in_array($a->guid, $encountered)) unset($attachments[$k]);
		$encountered[] = $a->guid;
	}
	
	uasort($attachments, '_roots_order_attachments');
	return $attachments;
}
function _roots_order_attachments($a, $b){
	return $a->post_mime_type == $b->post_mime_type ? strcmp($a->post_title, $b->post_title) : strcmp($a->post_mime_type, $b->post_mime_type);
}

// Lookup attachment by url
function roots_get_attachment_id_by_url($url){
	global $wpdb;
	$uploads = wp_upload_dir();
	
	$query = $wpdb->prepare("SELECT ID FROM {$wpdb->posts} WHERE guid LIKE '%s'", "%".str_replace($uploads['baseurl'], "", $url));
	if($id = $wpdb->get_var($query)){
		return $id;
	}
	return false;
}

// Scan $content for urls
function roots_get_urls($content){
	global $regexUrl;
	
	$r = preg_match_all($regexUrl, $content, $matches);
	return $r ? $matches[0] : array();
}

// Get the embed code of the first oEmbed url
function roots_get_first_embed($urls) {
	foreach($urls as $url) {
		if($html = wp_oembed_get($url)){
			return roots_strip_embed($html);
		}
	}
	return false;
}

function roots_strip_embed($html){
	// Remove sizes
	$html = preg_replace("/width=\"(?<w>\d+)\"|height=\"(?<h>\d+)\"/", "", $html);
	// If youtube, hide title
	$html = preg_replace("~http://www.youtube.com/embed/(\w+)?([^\"\']*)~", "http://www.youtube.com/embed/$1?$2&amp;showinfo=0&amp;origin=".urlencode(home_url()), $html);

	return $html;
}

add_filter('embed_oembed_html','roots_filter_oembed_result', 10, 3);
function roots_filter_oembed_result($html, $url, $args) {

	if(strpos($html, "youtube") >= 0 || strpos($html, "youtu.be") >= 0){
		// Remove sizes
		$html = preg_replace("/width=\"(?<w>\d+)\"|height=\"(?<h>\d+)\"/", "", $html);
	}
  return $html; 
}

/*	<?php
	
	$images =& get_children( 'post_type=attachment&post_mime_type=image' );

	$videos =& get_children( 'post_type=attachment&post_mime_type=video/mp4' );

	if ( empty($images) ) {
		// no attachments here
	} else {
		foreach ( $images as $attachment_id => $attachment ) {
			echo wp_get_attachment_image( $attachment_id, 'full' );
		}
	}
	
	?>*/