<?php /*
	$green = new Color(2,176,155);
	if($customColor = get_post_custom_values('_roots_bg_color')){
		$color = Color::fromHex($customColor[0]);
	} else {
		$color = $green;
	}
	
	$isDark = $color->distance(Color::white()) > $color->distance(Color::black());
	
	$colorNeutralize = $color->to($isDark ? Color::white() : Color::black(), .3);
	$colorText = $isDark ? Color::white() : Color::black();
	$colorLink = $colorNeutralize->distance(Color::white()) > $colorNeutralize->distance(Color::black()) ? Color::white() : Color::black();
	$colorInv = $color->invertLargest();
?>
<style>
	body article.post-<?php the_ID(); ?> .inner a {
		color: <?php echo $colorLink; ?>;
	}
	body article.post-<?php the_ID(); ?> .inner {
		background-color: <?php echo $color; ?>;
		color: <?php echo $colorText; ?>;
	}
	body article.post-<?php the_ID(); ?> .inner header a {
		color: <?php echo $colorText; ?>;
	}
	body article.post-<?php the_ID(); ?> .inner .meta {
		background-color: <?php echo $colorNeutralize; ?>;
	}
</style>