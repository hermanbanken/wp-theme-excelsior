<article id="post-<?php the_ID(); ?>" <?php post_class('box layout-archive'); ?>>
  <div class="inner">
		<header>
		  <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
		</header>
		<?php get_template_part('templates/entry-media', get_post_format()); ?>
		<div class="entry-summary">
			<?php the_excerpt(); ?>
		</div>
		<div class='meta shade'>
			<?php get_template_part('templates/entry-meta'); ?>
			<div class="entry-taxonomies entry-tags">
				<?php the_category(', '); ?>
			  <?php the_tags(__(' met tags', 'roots').'	<span>','</span>, <span>','</span>'); ?>
			</div>
		</div>
  </div>
</article>