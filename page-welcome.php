<?php
/*
Template Name: Welkomstpagina
*/
?>
<?php get_template_part('templates/content', 'page'); ?>

<div class='row-fluid'>
	<div class='span8'>
		<h2>Laatste nieuws</h2>
		<div class='row-fluid news-excerpts'>
			<?php
				$query = new WP_Query( array( 
					'post_type' => 'post',
					'posts_per_page' => 2
				) );
			?>
			<?php while ($query->have_posts()) : $query->the_post(); ?>
			  <div class='span6'>
					<article <?php post_class(); ?>>
				    <header>
				      <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
							<p>Geplaatst op: <time class="updated" datetime="<?php echo get_the_time('c'); ?>" pubdate><?php echo get_the_date(); ?></time></p>	
				    </header>
				    <div class="entry-summary">
				      <?php the_excerpt(); ?>
				    </div>
				    <footer>
				      <?php the_tags('<ul class="entry-tags"><li>','</li><li>','</li></ul>'); ?>
				    </footer>
				  </article>
				</div>
			<?php endwhile; ?>
		</div>
		<div class='row-fluid news-latest'>
			<?php
				$query = new WP_Query( array( 
					'post_type' => 'post',
					'posts_per_page' => 8,
					'offset' => 2
				) );
			?>
			<div class='span6'>
				<h2>Nieuwsarchief</h2><ul>
				<?php while ($query->have_posts()) : $query->the_post(); ?>
				  <li>
						<article <?php post_class('ellipsis'); ?>>
							<time class="updated" datetime="<?php echo get_the_time('c'); ?>" pubdate><?php echo get_the_date('m-d'); ?></time>
					    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
								<!--<div class="entry-summary">
					      <?php the_excerpt(); ?>
					    </div>-->
					  </article>
					</li>
				<?php endwhile; ?>
			</ul><p class='readMore alignleft'><a href="<?php echo get_next_posts_link(); ?>">Lees meer nieuws</a></p></div>
			<div class='span6'>
				<h2>Fotoalbums</h2>
				<div class="row-fluid">
					<img src="http://placehold.it/350x150" class="img-polaroid span6" />
					<img src="http://placehold.it/350x150" class="img-polaroid span6" />
				</div>
				<div class="row-fluid">
					<img src="http://placehold.it/350x150" class="img-polaroid span6" />
					<img src="http://placehold.it/350x150" class="img-polaroid span6" />
				</div>
				<p class='readMore alignleft'><a href="<?php echo get_post_format_link('image'); ?>">Bekijk meer foto's</a></p>
			</div>
		</div>
	</div>

	<?php wp_reset_postdata(); ?>
	
	<div class='span4'><?php dynamic_sidebar('frontpage-widgets-column'); ?></div>
</div>