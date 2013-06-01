<article id="post-<?php the_ID(); ?>" <?php post_class('box layout-single'); ?>>
  <div class="inner">
		<?php get_template_part('templates/part/event-header'); ?>
		<div class="entry-content">
			<?php if(eo_reoccurs()): ?>
				<div class='reoccurs alignright well'>
					<?php
					echo "<h4>".__("Next occurences:", 'roots')."</h4>";
				  $occurrences = eo_get_the_occurrences_of();
				  echo '<ul>';
				 	$count = 5;
				  foreach( $occurrences as $key => $occurrence) {
						$diff = $occurrence['start']->diff(new DateTime());
						if($diff->invert){
 							 if($count-- <= 0) break;
							 printf( 
						 		'<li><a href="%s">%s</a></li>',
								get_permalink()."?occurrence=".$key, 
						 		$diff->days < 7 ? 
									roots_relative_date($occurrence['start']->getTimestamp()) : 
									eo_format_datetime( $occurrence['start'] , __("F j, Y", 'roots') )
							);
						}
					}
				  echo '</ul>';
					?>
				</div>
			<?php endif; ?>
      <?php the_content(); ?>
    </div>
    <footer>
      <?php wp_link_pages(array('before' => '<nav class="page-nav"><p>' . __('Pages:', 'roots'), 'after' => '</p></nav>')); ?>
      <?php the_tags('<ul class="entry-tags"><li>','</li><li>','</li></ul>'); ?>
    </footer>
		
		<div class="disqus">
			<?php comments_template('/templates/comments.php'); ?>
		</div>
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
if(eo_get_venue()):
?>
<div class="sidebar box contact">
	<div class="inner">
		<h2><?php _e("Location", "roots"); ?></h2>
		<div class="media">
			<p>
				<strong><a href="<?php echo eo_get_venue_link(); ?>"><?php echo eo_get_venue_name() ?></a></strong>
				<br>
				<?php echo implode("<br>", array_filter(eo_get_venue_address())); ?>
			</p>
		</div>
		<?php echo eo_get_venue_map(null, array("class"=>"media-container googlemaps")); ?>
	</div>
</div>
<?php endif; ?>