<header>
	<date><?php
		if(eo_reoccurs() && !is_archive()) {
			$oid = isset($_GET['occurrence']) ? $_GET['occurrence'] : '';
		 	if(!empty($oid)){
				$os = eo_get_the_occurrences_of();
				if(isset($os[$oid])) $post->occurrence_id = $oid;
				$o = $os[$_GET['occurrence']];
			} else
				$o = eo_get_next_occurrence_of($post->ID);
			$month = $o['start']->format('M');
			$day = $o['start']->format('d');
		} else {
			$month = eo_get_the_start("M");
			$day   = eo_get_the_start("d");
		}
		echo "<month>$month</month><day>$day</day>";
	?></date>
	<?php $tag = is_archive() ? "h2" : "h1"; echo "<$tag>"; ?>
		<a href="<?php the_permalink(); ?><?php echo "?occurrence=$post->occurrence_id"; ?>"><?php the_title(); ?></a>
	<?php echo "</$tag>"; ?>
	<p><?php
		$sameDayEnd = eo_get_the_start("Y-m-d") == eo_get_the_end("Y-m-d");
		
		if(eo_reoccurs()){
			$reoccur = eo_get_event_schedule();
			echo eo_get_schedule_summary(null, count($reoccur) < 10);
			
			echo ' '.__('from','roots').' '.eo_get_the_start(__("g:i a", 'roots')) . ($sameDayEnd ? ' '.__('until','roots').' '.eo_get_the_end(__("g:i a", 'roots')) : '');
		} else { 
			printf(
				"%s - %s",
				eo_get_the_start(__("F j, Y @ g:i a", 'roots')),
				eo_get_the_end($sameDayEnd ? __("g:i a", 'roots') : __("F j, Y @ g:i a", 'roots'))				
			);
		}
	?></p>
</header>
