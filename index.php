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

<?php 
	/* Promotion */
	$i = 0; 
	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
?>
<?php while (have_posts()) : the_post(); ?>
  <?php get_template_part('templates/colorize'); ?>
	<?php locate_template(array(
		"templates/archive/{$post->post_type}-".get_post_format().".php",
		"templates/archive/{$post->post_type}.php",
		"templates/archive/post.php",
	), true, false); ?>
  <?php 
  /* Promotion */ 
  if($i++ == 1 && $paged == 1): ?>
  <article id="post-proms-ticket" class="post-proms-ticket post type-post status-publish format-standard hentry category-proms box layout-archive masonry-brick double">
	<div class="inner" style="background-color:#3280E2">
		<header>
		  <h2><a href="/proms/">Koop nu kaarten voor 17e Promsconcert</a></h2>
		</header>
		<div class="media-container shade" style="margin-bottom:0">
			<a href="/proms/">
				<img src="https://secure.excelsior-woerden.nl/images/ticket-2015.png" style="width:100%">
			</a>
		</div>
		<div class="meta shade" style="background-color:#3280E2">
			<form action="/proms/" method="get" style="margin:0">
				<input type="submit" value="Ga naar ticket site" style="margin:0">
			</form>
		</div>
	</div>
  </article>
  <?php endif; ?>
<?php endwhile; ?>

<?php if ($wp_query->max_num_pages > 1) : ?>
  <nav class="post-nav">
    <ul class="pager">
      <li class="previous"><?php next_posts_link(__('&larr; Older posts', 'roots')); ?></li>
      <li class="next"><?php previous_posts_link(__('Newer posts &rarr;', 'roots')); ?></li>
    </ul>
  </nav>
<?php endif; ?>