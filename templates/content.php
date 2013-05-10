<article id="post-<?php the_ID(); ?>" <?php post_class('box'); ?>>
  <div class="inner">
		<header>
		  <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
		</header>
		<?php get_template_part('templates/entry-media', get_post_format()); ?>
		<div class="entry-summary">
			<?php the_excerpt(); ?>
		</div>
		<div class='meta'>
			<?php get_template_part('templates/entry-meta'); ?>
		</div>
		<footer>
		  <?php the_tags('<ul class="entry-tags"><li>','</li><li>','</li></ul>'); ?>
		</footer>
  </div>
</article>
