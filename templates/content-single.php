<?php while (have_posts()) : the_post(); ?>
  <article id="post-<?php the_ID(); ?>" <?php post_class('box layout-single'); ?>>
    <div class="inner">
			<header>
	      <h1 class="entry-title"><?php the_title(); ?></h1>
	      <?php get_template_part('templates/entry-meta'); ?>
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
	<?php get_template_part('templates/single-bar'); ?>
<?php endwhile; ?>
