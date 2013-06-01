<ul class="postLinks">
	<li class="category">
		<?php the_category(', '); ?>
	</li>
	<li class="date">
		<time class="updated" datetime="<?php echo get_the_time('c'); ?>" pubdate><?php echo roots_relative_date(get_the_date('c')); ?></time>
	</li>
	<!--<li class="author byline vcard">
		<a href="<?php echo get_author_posts_url(get_the_author_meta('ID')); ?>" rel="author" class="fn"><?php echo get_the_author(); ?></a>
	</li>-->
	<li class="comment-count">
		<?php 
		$counts = wp_count_comments(get_the_ID()); 
		printf(_n("1 Comment", "%d Comments", $counts->approved, 'roots'), $counts->approved);
		?>
	</li>
</ul>