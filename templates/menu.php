<nav id="mainNav" class="sidebar fixed left">
			
	<a href="<?php echo home_url(); ?>" class="logo_sm" style="opacity: 0;"><?php bloginfo('name'); ?></a>
	
	<ul class='search menu'>
		<li>
			<form class="form-search">
				<i class="icon-search"></i>
	  		<input type="text" name="s" class="search-query" placeholder="Zoeken">
			</form>
		</li>
	</ul>
  <?php
    if (has_nav_menu('primary_navigation')) :
      wp_nav_menu(array(
				'theme_location' => 'primary_navigation', 
				'menu_class' => 'menu', 
				'menu_id' => 'menu-primary-navigation'
			));
    endif;
  ?>

</nav>