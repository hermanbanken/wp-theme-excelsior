<?php get_template_part('templates/part/head'); ?>
<body <?php body_class(); ?>>

  <!--[if lt IE 7]><div class="alert">Your browser is <em>ancient!</em> <a href="http://browsehappy.com/">Upgrade to a different browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">install Google Chrome Frame</a> to experience this site.</div><![endif]-->
	<div class="wrap">
		
		<div id="columnleft">
			<div id="menu">
				<?php get_template_part('templates/part/menu'); ?>
			</div>
			
			<div class="hidden-phone">
				<?php 
					ob_start();
					dynamic_sidebar('sidebar-menu'); 
					$_sb = ob_get_flush();
				?></div>
		</div>
	
		<div id="content" style="min-height: 300px;">
	  
			<!--<section class="quick-bar">
				<?php //get_template_part('templates/quickbar'); ?>
			</section>-->
		
			<section class="articles">
	      <?php include roots_template_path(); ?>
				<?php //include roots_sidebar_path(); ?>
			</section>

		</div>

		<div class="visible-phone sidebar-menu-bottom"><?php echo $_sb; ?></div>
		<?php get_template_part('templates/part/footer'); ?>		
	</div><!-- End of .wrap -->
	
</body>
</html>
