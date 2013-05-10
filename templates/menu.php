<header role="banner" class="banner mainHeader">
	<a href="<?php echo home_url(); ?>/?ref=logo">
		<h1><img alt="<?php bloginfo('name'); ?>" src="/assets/img/name.png" /></h1>
	  <center>
			<img src="/assets/img/logo.png" />
		</center>
	</a>
	<p>Opgericht in 1903 en sindsdien een gezellige club mensen met een passie voor muziek.</p>
</header>

<?php
  if (has_nav_menu('primary_navigation')) :
    wp_nav_menu(array(
			'theme_location' => 'primary_navigation', 
			'menu_class' => 'menu', 
			'menu_id' => 'menu-primary-navigation',
			'depth' => 2,
			'before'     => '<p>',
			'after'      => '</p>',
		));
  endif;
?>