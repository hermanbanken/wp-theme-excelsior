<span class="author byline vcard">
	<?php _e('By', 'roots'); ?> <a href="<?php echo get_author_posts_url(get_the_author_meta('ID')); ?>" rel="author" class="fn"><?php echo get_the_author(); ?></a>,
</span>
<span class="date">
	<time class="updated" datetime="<?php echo get_the_time('c'); ?>" pubdate><?php echo roots_relative_date(get_the_date('c')); ?></time>
</span>