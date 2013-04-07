<?php if (!have_posts()) : ?>
  <div class="alert">
    <?php _e('Sorry, no results were found.', 'roots'); ?>
  </div>
  <?php get_search_form(); ?>
<?php endif; ?>
<?php function print_color($color){
			echo "rgb($color[0],$color[1],$color[2])";
		} ?>
<?php while (have_posts()) : the_post(); ?>
	
	<?php
		$green = new Color(2,176,155);
		$color = $green;//Color::random();
		$isDark = $color->distance(Color::white()) > $color->distance(Color::black());
		
		$colorNeutralize = $color->to($isDark ? Color::white() : Color::black(), .3);
		$colorText = $isDark ? Color::white() : Color::black();
		$colorLink = $colorText->to($green, .3);
		$colorInv = $color->invertLargest();
	?>
	<style>
		body article.post-<?php the_ID(); ?> .inner a {
			color: <?php echo $colorLink; ?>;
		}
	</style>
  <article <?php post_class(); ?>>
    <div class="inner" style="background-color:<?php echo $color; ?>;color:<?php echo $colorText; ?>">
			<header>
	      <h2><a href="<?php the_permalink(); ?>" style="color:<?php echo $colorText; ?>"><?php the_title(); ?></a></h2>
	    </header>
	    <div class="entry-summary">
	      <?php the_excerpt(); ?>
	    </div>
			<div class='meta' style="background-color:<?php echo $colorNeutralize; ?>">
	      <?php get_template_part('templates/entry-meta'); ?>
			</div>
	    <footer>
	      <?php the_tags('<ul class="entry-tags"><li>','</li><li>','</li></ul>'); ?>
	    </footer>
		</div>
  </article>
<?php endwhile; ?>
<div class="clearfix"></div>

<?php if ($wp_query->max_num_pages > 1) : ?>
  <nav class="post-nav">
    <ul class="pager">
      <?php if (get_next_posts_link()) : ?>
        <li class="previous"><?php next_posts_link(__('&larr; Older posts', 'roots')); ?></li>
      <?php endif; ?>
      <?php if (get_previous_posts_link()) : ?>
        <li class="next"><?php previous_posts_link(__('Newer posts &rarr;', 'roots')); ?></li>
      <?php endif; ?>
    </ul>
  </nav>
<?php endif; ?>
