<?php while (have_posts()) : the_post(); ?>

	<?php locate_template(array(
		"templates/single/{$post->post_type}-".get_post_format().".php",
		"templates/single/{$post->post_type}.php",
		"templates/single/post.php",
	), true, false); ?>

<?php endwhile; ?>