<form role="search" method="get" id="searchform" class="form-search" action="<?php echo home_url('/'); ?>">
	<p>
	Volg ons via <a href="https://www.facebook.com/pages/Fanfarecorps-Excelsior-Woerden/475062905894372">Facebook</a><!--, <a>Twitter</a>--> of <a href="<?php bloginfo('rss2_url'); ?>" class="no-ajaxy">RSS</a>
	<span class='spacer'></span>
	
	<label class="hide" for="s"><?php _e('Search for:', 'roots'); ?></label>
	<input type="search" placeholder="Typ en druk op Enter" value="<?php if (is_search()) { echo get_search_query(); } ?>" name="s" id="s" class="search-query" />
		
	<a href="/vereniging/lidmaatschap" class="btn btn-primary pull-right visible-desktop" >Lid worden</a>
	</p>
</form>