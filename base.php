<?php get_template_part('templates/part/head'); ?>
<body <?php body_class(); ?>>

	<div class="feedback box hidden-phone">
		<!-- The Classic Widget will be embeded wherever this div is placed -->
		<div class="message">
			<p>Deze website is nieuw en is nog niet helemaal klaar. We ontvangen graag uw feedback om de site te verbeteren: <a href="http://bluecode.uservoice.com/">geef feedback</a></p>
		</div>
		<div class="inner">
			<div class="iframe" data-uv-inline="classic_widget" data-uv-mode="full" data-uv-primary-color="#48648e" data-uv-link-color="#02b09c" data-uv-default-mode="feedback" data-uv-forum-id="209418" data-uv-width="100%" data-uv-height="300px"></div>
		</div>
	</div>

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
