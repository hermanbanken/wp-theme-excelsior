<?php

add_action('admin_menu', 'register_admin_id3_page');

function register_admin_id3_page() {

	add_media_page( "Metadata", "Metadata", "upload_files", 'media-id3.php', 'admin_id3_page');
	
}

function admin_id3_image_to_base64 ($binary)
{
	$img = imagecreatefromstring($binary);
	$width = imagesx($img);
	$height = imagesy($img);
	$aspect_ratio = $height/$width;
	if($width > 100){
		$new_w = 100;
		$new_h = $new_w*$aspect_ratio;
		$tmp = imagecreatetruecolor($new_w,$new_h); 
		imagecopyresized($tmp,$img,0,0,0,0,$new_w,$new_h,$width,$height);
		imagedestroy($img);
		$img = $tmp;
	}
	ob_start();
	imagepng($img);
	$output = ob_get_contents();
	ob_end_clean();
	imagedestroy($img);
	$base64 = 'data:image/png;base64,' . base64_encode($output);
	return $base64;
}

function admin_id3_page() {
	
	if ( !current_user_can( 'upload_files' ) )  {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}
		?>
<div class="wrap">
	<div id="icon-upload" class="icon32"><br></div>	<h2>Metadata</h2>
	<p><?php _e('Some media files contain ID3-tags. This is information, for example, about who made the media. This data is looked up when uploading files, but you might have activated this theme after uploading some files. These files aren\'t indexed yet. You can do this here.', 'roots'); ?></p>
	
	<?php
	$args = array(
		'post_type' => 'attachment',
		'post_mime_type' => 'audio',
	); 
	$attachments = get_posts($args);
	echo '<h2>Attachments:</h2><ol>';
	foreach($attachments as $a){
		$id3 = get_post_meta($a->ID, 'id3', true);
		
		if(!is_array($id3)){
			echo "<li><a href='".wp_get_attachment_url($a->ID)."'>".$a->post_title."</a></li>";
			$path = get_attached_file($a->ID);
			$id3 = strpos($a->post_mime_type, "audio") == 0 ? @wp_read_audio_metadata($path) : @wp_read_video_metadata($path);
			unset($id3['image']);
			update_post_meta($a->ID, 'id3', $id3);
			foreach($id3 as $key => $value){
				update_post_meta($a->ID, "id3.$key", $value);
			}
		}
		if(is_array($id3)){
			echo "<li>Has ID3: ".$id3['artist']." - ".$id3['title']."</li>";
		}
	}
	echo '</ol>';
	?>
</div>
		<?php
}