<article id="post-<?php the_ID(); ?>" <?php post_class('box layout-single'); ?>>
  <div class="inner">
		<header>
			<date><?php
				$start = get_post_meta($post->ID, "bc_event_start", true);
				$end = get_post_meta($post->ID, "bc_event_end", true);
				echo "<month>".date("M", $start)."</month><day>".date("d", $start)."</day>";
			?></date>
      <h1 class="entry-title"><?php the_title(); ?></h1>
			<p><?php
				echo date(__("F j, Y @ g:i a", 'roots'), $start) . " - " . date(date("Y-m-d", $start) == date("Y-m-d", $end) ? __("g:i a", 'roots') : __("F j, Y @ g:i a", 'roots'), $end);
			?></p>
    </header>
    <div class="entry-content">
      <?php the_content(); ?>
    </div>
    <footer>
      <?php wp_link_pages(array('before' => '<nav class="page-nav"><p>' . __('Pages:', 'roots'), 'after' => '</p></nav>')); ?>
      <?php the_tags('<ul class="entry-tags"><li>','</li><li>','</li></ul>'); ?>
    </footer>
		<?php comments_template('/templates/comments.php'); ?>
	</div>
</article>

<?php
$contact = get_post_meta($post->ID, "bc_event_contact", true);
$fields = array("name"=>"user", "mail"=>"envelope", "phone" => "question-sign", "url" => "globe");

if(!empty($contact['name'])):
?>
<div class="sidebar box contact">
	<div class="inner">
		<h2><?php _e("More Information", "roots"); ?></h2>
		<div class="media">
		  <a class="pull-left" href="#">
				<?php echo get_avatar( $contact['mail'], 64 ); ?>
			</a>
		  <div class="media-body">
		    <p>
					<?php
					foreach($fields as $key => $icon){
						if(!empty($contact[$key])){
							if($key == "url")
								echo '<i class="icon icon-'.$icon.'"></i> <a href="'.esc_attr($contact[$key]).'">'.$contact[$key].'</a>';
							else if($key == "mail")
								echo '<i class="icon icon-'.$icon.'"></i> <a href="mailto:'.esc_attr($contact[$key]).'">'.$contact[$key].'</a>';
							else
								echo '<i class="icon icon-'.$icon.'"></i> ' . $contact[$key];
							echo '<br>';
						}
					}
					?>
		    </p>
		  </div>
		</div>
	</div>
</div>
<?php endif; ?>

<?php
$location = get_post_meta($post->ID, "bc_event_location", true);

if(!empty($location['addr'])):
?>
<div class="sidebar box contact">
	<div class="inner">
		<h2><?php _e("Location", "roots"); ?></h2>
		<div class="media">
			<p>
				<strong><?php echo $location['name'] ?></strong>
				<br>
				<a href="http://maps.google.com/maps?q=<?php echo esc_attr($location['addr']); ?>">
					<?php echo implode("<br>", explode(",", $location['addr'])); ?>
				</a>
			</p>
		</div>
		<div class="media-container googlemaps" data-googlemaps data-longitude='<?php echo $location['lon'] ?>' data-latitude='<?php echo $location['lat'] ?>'>
		</div>
	</div>
</div>
<?php endif; ?>