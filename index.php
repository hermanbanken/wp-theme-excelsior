<?php //get_template_part('templates/page', 'header'); ?>

<?php if (!have_posts()) : ?>
	<article class="type-error layout-single">
		<div class="inner">
			<?php get_template_part('templates/page', 'header'); ?>
		
			<div class="entry-content">
				<?php if (is_category() && term_description(null, 'category')) :?>
					<?php echo term_description(null, 'category'); ?>
				<?php endif; ?>
  			
				<div class="alert"><?php _e('Sorry, no posts were found.', 'roots'); ?></div>
				
				<?php get_search_form(); ?>
			</div>
		</div>
	</article>
<?php elseif (is_category() && term_description(null, 'category')) :?>
	<article class="type-category layout-single">
		<div class="inner">
			<?php get_template_part('templates/page', 'header'); ?>
			<div class="entry-content">
				<?php echo term_description(null, 'category'); ?>
			</div>
		<div>
	</article>
<?php elseif ((is_tax('event-venue') || is_post_type_archive('event')) && function_exists("eo_get_venue_map") && function_exists("eo_get_event_archive_date")) :?>
	<article class="type-event-archive layout-single double box venue">
		<div class="inner">
			<header class="page-header">
			  <h1><?php 
					if( is_tax('event-venue') ){
						echo __('Location', 'roots').': '; roots_title();
					}
					elseif( eo_is_event_archive('day') )
						//Viewing date archive
						echo __('Events: ','roots').' '.eo_get_event_archive_date('jS F Y');
					elseif( eo_is_event_archive('month') )
						//Viewing month archive
						echo __('Events: ','roots').' '.eo_get_event_archive_date('F Y');
					elseif( eo_is_event_archive('year') )
						//Viewing year archive
						echo __('Events: ','roots').' '.eo_get_event_archive_date('Y');
					else
						_e('Events','roots');
				
				?></h1>
				<?php if(is_tax("event-venue")): ?><p><?php echo implode(", ", array_filter(eo_get_venue_address())); ?></p><?php endif; ?>
			</header>
			<?php
				$venue = get_query_var('event-venue');
				if(!$venue){
					$venue = array();
					$venues = eo_get_venues( );
					foreach($venues as $v) $venue[] = $v->slug;
				}
				echo eo_get_venue_map($venue, array("class"=>"googlemaps media-container venue-map"));
			?>
			<div class="entry-content">
				<?php echo eo_get_event_archive_link( 2015, 3); ?>
			</div>
		</div>
	</article>
<?php endif; ?>


<?php while (have_posts()) : the_post(); ?>
  <?php get_template_part('templates/colorize'); ?>
	<?php locate_template(array(
		"templates/archive/{$post->post_type}-".get_post_format().".php",
		"templates/archive/{$post->post_type}.php",
		"templates/archive/post.php",
	), true, false); ?>
<?php endwhile; ?>

<?php if ($wp_query->max_num_pages > 1) : ?>
  <nav class="post-nav">
    <ul class="pager">
      <li class="previous"><?php next_posts_link(__('&larr; Older posts', 'roots')); ?></li>
      <li class="next"><?php previous_posts_link(__('Newer posts &rarr;', 'roots')); ?></li>
    </ul>
  </nav>
<?php endif; ?>
