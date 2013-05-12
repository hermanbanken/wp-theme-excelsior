<article id="post-<?php the_ID(); ?>" <?php post_class('box layout-single'); ?>>
	<div class="inner">
		<?php get_template_part('templates/page', 'header'); ?>
		<?php get_template_part('templates/content', 'page'); ?>
	</div>
</article>