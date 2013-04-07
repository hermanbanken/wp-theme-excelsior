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
