<?php require_once(ABSPATH."/wp-includes/class-oembed.php"); ?>
<article <?php post_class(); ?>>
  <div class="inner">
		<header>
		  <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
		</header>
		<?php //echo "<pre>".print_r(get_post_custom(),1)."</pre>"; ?>
		<?php
			$content = get_the_content();
			$content = apply_filters('the_content', $content);
			
			if ( has_post_format( 'image' ) || has_post_format( 'gallery' ) ) {
				echo '<div class="media-container">';
				the_post_thumbnail('medium');
				echo '</div>';
			}
		?>
		<?php if ( has_post_format( 'video' ) | has_post_format( 'audio' ) ): 
			$urls = get_post_custom_values("_wp_format_media");
			if($urls[0]){
				$WPoEmbed = new WP_oEmbed();
				$html = $WPoEmbed->get_html($urls[0]);
				
				// Remove sizes
				$html = preg_replace("/width=\"(?<w>\d+)\"|height=\"(?<h>\d+)\"/", "", $html);
				// If youtube, hide title
				$html = preg_replace("~http://www.youtube.com/embed/(\w+)?([^\"\']*)~", "http://www.youtube.com/embed/$1?$2&amp;showinfo=0", $html);
					
				echo '<div class="media-container">'.$html.'</div>';
			}
			endif; ?>
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
