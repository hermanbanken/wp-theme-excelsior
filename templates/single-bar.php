<div id="single-bar" class="sidebar box">
	<div class="inner">
		<h2><?php _e('About', "roots"); ?> <?php the_author_posts_link(); ?></h2>
		<div class="media">
		  <a class="pull-left" href="#">
				<?php echo get_avatar(get_the_author_meta('ID'), 64); ?>
		  </a>
		  <div class="media-body">
		    <p><?php the_author_meta('description'); ?></p>
				<p>
					<a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>">
						<?php printf(__('More from %1$s', "roots"), get_the_author_meta( 'display_name' )); ?>
					</a>
				</p>
		  </div>
		</div>
	</div>
</div>

<?php
$att = roots_get_all_attachements();

$mimes = array(
	"image" => "icon-picture", 
	"video" => "icon-film", 
	"application" => "icon-file", 
	"audio" => "icon-music", 
	"other" => "icon-download-alt"
);

if(count($att) > 0){
	?>
<div id="single-bar" class="sidebar box">
	<div class="inner">
		<h2><?php _e('Attachments', "roots"); ?></h2>
		<div class="entry-attachments media"><ul>
			<?php	
			foreach($att as $a){
				$mime = explode("/", $a->post_mime_type);
				$icon = isset($mimes[$mime[0]]) ? $mimes[$mime[0]] : $mimes["other"];
				$title = apply_filters( 'the_title', $a->post_title );
				$url = $a->ID ? wp_get_attachment_url($a->ID) : $a->guid; 
				
				// Local urls should be converted to WP_Post. 
				// If not: probably a thumbnail url, so skip that
				if(get_class($a) == "WP_Post" || substr($a->guid, 0, strlen(site_url())) != site_url()){
					echo "<li class='media'><i class='$icon'></i>";
					echo "<a href='".$url."'>$title</a></li>";
				}
			}
			?>
		</ul></div>
	</div>
</div>
<?php } ?>