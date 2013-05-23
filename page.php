<article id="post-<?php the_ID(); ?>" <?php post_class('box layout-single'); ?>>
	<div class="inner">
		<?php get_template_part('templates/page', 'header'); ?>
		
		<?php while (have_posts()) : the_post(); ?>
		  <div class="entry-content">
		    <?php the_content(); ?>
		  </div>
		  <?php wp_link_pages(array('before' => '<nav class="pagination">', 'after' => '</nav>')); ?>
		<?php endwhile; ?>
	</div>
</article>