<article id="post-<?php the_ID(); ?>" <?php post_class('box layout-archive'); ?>>
  <div class="inner">
		<date><?php
			$start = get_post_meta($post->ID, "bc_event_start", true);
			$end = get_post_meta($post->ID, "bc_event_end", true);
			echo "<month>".date("M", $start)."</month><day>".date("d", $start)."</day>";
		?></date>
	  <header>
			<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
			<p><?php
				echo date(__("F j, Y @ g:i a", 'roots'), $start) . " - " . date(date("Y-m-d", $start) == date("Y-m-d", $end) ? __("g:i a", 'roots') : __("F j, Y @ g:i a", 'roots'), $end);
			?></p>
		</header>
		<?php get_template_part('templates/entry-media', get_post_format()); ?>
		<div class="entry-summary">
			<?php the_excerpt(); ?>
		</div>
		<div class='meta shade'>
			<p class="byline">
				<?php echo get_the_term_list( $post->ID, 'bc_eventcategory', '', ', ', '' ); ?> 
				<?php
				$contact = get_post_meta($post->ID, "bc_event_contact", true);
				$location = get_post_meta($post->ID, "bc_event_location", true);
				echo "<br>@ {$location[name]}";
				?>
			</p>
			<time class="updated" datetime="<?php echo get_the_time('c'); ?>" pubdate></time>
		</div>
		<footer>
		  <?php the_tags('<ul class="entry-tags"><li>','</li><li>','</li></ul>'); ?>
		</footer>
  </div>
</article>