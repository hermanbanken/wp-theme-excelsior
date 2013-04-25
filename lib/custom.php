<?php
/**
 * Custom functions
 */
$regexUrl = "/((([A-Za-z]{3,9}:(?:\/\/)?)(?:[-;:&=\+\$,\w]+@)?[A-Za-z0-9.-]+|(?:www.|[-;:&=\+\$,\w]+@)[A-Za-z0-9.-]+)((?:\/[\+~%\/.\w-_]*)?\??(?:[-\+=&;%@.\w_]*)#?(?:[\w]*))?)/";

function roots_get_featured_media(){
	
	$content = get_the_content();
	$content = apply_filters('the_content', $content);
	
	$embed = false;
	switch( get_post_format() )
	{
		case 'video':
		case 'audio':
			// Directly defined video or audio, also find urls in post
			$urls = get_post_custom_values("_wp_format_media") + roots_get_urls($content);
			if(($oembed = roots_get_first_embed($urls)) && ($embed = $oembed))
				break;
		
		case 'gallery':
			// Display a gallery you can slide and stuff
		
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

	return '<div class="media-container">'.$embed.'</div>';
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
	$html = preg_replace("~http://www.youtube.com/embed/(\w+)?([^\"\']*)~", "http://www.youtube.com/embed/$1?$2&amp;showinfo=0", $html);

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