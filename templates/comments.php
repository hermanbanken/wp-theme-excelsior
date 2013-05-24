<?php
  if (post_password_required()) {
    return;
  }

 if (have_comments()) : ?>
  <section class="meta inner" id="comments">
    <h3><?php printf(_n('One Response to &ldquo;%2$s&rdquo;', '%1$s Responses to &ldquo;%2$s&rdquo;', get_comments_number(), 'roots'), number_format_i18n(get_comments_number()), get_the_title()); ?></h3>

    <ol class="media-list">
      <?php wp_list_comments(array('walker' => new Roots_Walker_Comment)); ?>
    </ol>

    <?php if (get_comment_pages_count() > 1 && get_option('page_comments')) : ?>
    <nav>
      <ul class="pager">
        <?php if (get_previous_comments_link()) : ?>
          <li class="previous"><?php previous_comments_link(__('&larr; Older comments', 'roots')); ?></li>
        <?php endif; ?>
        <?php if (get_next_comments_link()) : ?>
          <li class="next"><?php next_comments_link(__('Newer comments &rarr;', 'roots')); ?></li>
        <?php endif; ?>
      </ul>
    </nav>
    <?php endif; ?>

    <?php if (!comments_open() && !is_page() && post_type_supports(get_post_type(), 'comments')) : ?>
    <div class="alert">
      <?php _e('Comments are closed.', 'roots'); ?>
    </div>
    <?php endif; ?>
  </section><!-- /#comments -->
<?php endif; ?>

<?php if (!have_comments() && !comments_open() && !is_page() && post_type_supports(get_post_type(), 'comments')) : ?>
  <section class="meta" id="comments">
    <div class="alert">
      <?php _e('Comments are closed.', 'roots'); ?>
    </div>
  </section><!-- /#comments -->
<?php endif; ?>

<?php if (comments_open()) : ?>
  <section class="meta inner" id="respond">
    <h3><?php comment_form_title(__('Leave a Reply', 'roots'), __('Leave a Reply to %s', 'roots')); ?></h3>
    <form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform">
      <?php if (is_user_logged_in()) : ?>
      <?php elseif(get_option('comment_registration')) : // Need registration to comment ?>
			<?php endif; ?>
	    <textarea name="comment" id="comment" class="input-xlarge" rows="5" aria-required="true"></textarea>
			<p>
				<span class='without-user'><?php _e("Login with", 'roots'); ?>
					<a class='providerLogin' data-provider="facebook" data-target='#commentform'>Facebook</a>
				</span>
				<span class='with-user'>
					<?php printf(__("Logged in as %s", 'roots'), "<span class='displayname'></span>");?>.
					<a class='logout'><?php _e("Logout", 'roots'); ?></a>
				</span>
				<input name="submit" class="btn btn-primary" type="submit" id="submit" value="<?php _e('Submit Comment', 'roots'); ?>">
			</p>
    <?php comment_id_fields(); ?>
		</form>
    <?php do_action('comment_form', $post->ID); ?>
		
		<p class="cancel-comment-reply"><?php cancel_comment_reply_link(); ?></p>
  </section><!-- /#respond -->
<?php endif; ?>
