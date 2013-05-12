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
<?php endif; ?>


<?php while (have_posts()) : the_post(); ?>
  <?php get_template_part('templates/colorize'); ?>
  <?php get_template_part('templates/content', get_post_format()); ?>
<?php endwhile; ?>

<?php if ($wp_query->max_num_pages > 1) : ?>
  <nav class="post-nav">
    <ul class="pager">
      <li class="previous"><?php next_posts_link(__('&larr; Older posts', 'roots')); ?></li>
      <li class="next"><?php previous_posts_link(__('Newer posts &rarr;', 'roots')); ?></li>
    </ul>
  </nav>
<?php endif; ?>
