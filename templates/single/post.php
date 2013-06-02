<article id="post-<?php the_ID(); ?>" <?php post_class('box layout-single'); ?>>
  <div class="inner">
		<header>
      <h1 class="entry-title"><?php the_title(); ?></h1>
			<div class='controls pullright'>
				<?php previous_post_link("%link", "<span class='prev'></span>")?>
				<a href="<?php echo site_url(); ?>"><span class='back'></span></a>
				<?php next_post_link("%link", "<span class='next'></span>")?>
			</div>
    </header>
    <div class="entry-content">
      <?php get_template_part('templates/part/post-meta'); ?>
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
<?php get_template_part('templates/part/single-bar'); ?>