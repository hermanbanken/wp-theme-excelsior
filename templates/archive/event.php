<article id="post-<?php the_ID(); ?>" <?php post_class('box layout-archive'); ?>>
  <div class="inner">
		<?php get_template_part('templates/part/event-header'); ?>
		<?php get_template_part('templates/entry-media', get_post_format()); ?>
		<div class="entry-summary">
			<?php the_excerpt(); ?>
		</div>
		<div class='meta shade'>
			<p class="byline">
				<?php echo get_the_term_list( $post->ID, 'event-category', '', ', ', '' ); ?> 
				<?php
				if(eo_get_venue()){
					printf("in <a href=\"%s\">%s</a>", eo_get_venue_link(), eo_get_venue_name());
				}
				?>
			</p>
			<time class="updated" datetime="<?php echo get_the_time('c'); ?>" pubdate></time>
		</div>
		<footer>
		  <?php the_tags('<ul class="entry-tags"><li>','</li><li>','</li></ul>'); ?>
		</footer>
  </div>
</article>