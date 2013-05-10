<div id="single-bar" class="sidebar box">
	<div class="inner">
		<h2>About author</h2>
		<h2>Attachments</h2>
		<div class="entry-attachments">
			<?php
			$att = roots_get_all_attachements();
			if(count($att) > 0){
				foreach($att as $a){
					echo "<a href='$a->guid'>$a->post_title</a> <br>";
				}
			}
			?>
		</div>
		<h2>Social media stats</h2>
	</div>
</div>