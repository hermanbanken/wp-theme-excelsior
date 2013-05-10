<?php get_template_part('templates/head'); ?>
<body <?php body_class(); ?>>

  <!--[if lt IE 7]><div class="alert">Your browser is <em>ancient!</em> <a href="http://browsehappy.com/">Upgrade to a different browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">install Google Chrome Frame</a> to experience this site.</div><![endif]-->
	<div class="wrap">
		
		<div id="columnleft">
			<div id="menu">
				<?php get_template_part('templates/menu'); ?>
			</div>
			
			<?php dynamic_sidebar('sidebar-menu'); ?>
		</div>
	
		<div id="content" style="min-height: 300px;">
	  
			<section class="quick-bar">
				<?php get_template_part('templates/quickbar'); ?>
			</section>
		
			<section class="articles">
	      <?php include roots_template_path(); ?>
				<?php //include roots_sidebar_path(); ?>
			</section>

				
		</div>
		<?php get_template_part('templates/footer'); ?>		
	</div><!-- End of .wrap -->
	
</body>
</html>
