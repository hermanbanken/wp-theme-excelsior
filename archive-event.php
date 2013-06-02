<?php if (function_exists("eo_get_venue_map") && function_exists("eo_get_event_archive_date")) :?>
	<article class="type-event-archive layout-single double box venue">
		<div class="inner">
			<header class="page-header">
			  <h1><?php 
					if( is_tax('event-venue') ){
						echo __('Location', 'roots').': '; roots_title();
					}
					elseif( eo_is_event_archive('day') )
						//Viewing date archive
						echo __('Events: ','roots').' '.eo_get_event_archive_date('jS F Y');
					elseif( eo_is_event_archive('month') )
						//Viewing month archive
						echo __('Events: ','roots').' '.eo_get_event_archive_date('F Y');
					elseif( eo_is_event_archive('year') )
						//Viewing year archive
						echo __('Events: ','roots').' '.eo_get_event_archive_date('Y');
					else
						_e('Events','roots');
				
				?></h1>
				<div class='controls pullright'>
					<?php
					$qv = get_query_var("ondate");
					$date = str_replace("/", "-", $qv) . substr("0001-01-01", strlen($qv));
					$selector = $qv ? explode("/", $qv) : array();
					$disc = current(array_slice(array(false, "year", "month", "day"), count($selector), 1));
					$format = array("Y", "Y-m", "Y-m-d");
					
					echo "<a href='#' title='Lijst'><span class='list'></span></a><a href='#' title='Maand'><span class='month'></span></a>";
					
					// Make prev and next buttons, since we are in a date archive
					if($disc){
						$nav = array(
							"prev" => array(strtotime($date . " -1 $disc"), date("Y-m-d", strtotime($date . " -1 $disc"))),
							"next" => array(strtotime($date . " +1 $disc"), date("Y-m-d", strtotime($date . " +1 $disc")))
						);
						echo "<a href=\"".call_user_func_array("eo_get_event_archive_link", explode("-", substr($nav['next'][1], 0, strlen($qv))))."\" rel=\"next\"><span class=\"next\"></span></a>";
						echo "<a href=\"".call_user_func_array("eo_get_event_archive_link", explode("-", substr($nav['prev'][1], 0, strlen($qv))))."\" rel=\"prev\"><span class=\"prev\"></span></a>";
					}
					?>
					<a href="<?php echo site_url(); ?>"><span class='back'></span></a>
				</div>
				<?php if(is_tax("event-venue")): ?><p><?php echo implode(", ", array_filter(eo_get_venue_address())); ?></p><?php endif; ?>
			</header>
			<?php
				$venue = get_query_var('event-venue');
				if(!$venue){
					$venue = array();
					while(have_posts()): the_post();
						 $venue[] = eo_get_venue_slug(get_the_ID());
					endwhile;
					rewind_posts();
				}
				echo eo_get_venue_map(array_unique($venue), array("class"=>"googlemaps media-container venue-map"));
				global $wp_locale;
			?>
			
			<?php if($disc == 'month'):
				$offset = 1;
				
				// Start date for month view
				$start = strtotime("first " . $wp_locale->weekday[$offset] . " of " . substr($date, 0, 7));
				if(date("d", $start) !== "01") $start = strtotime("-1 week", $start);
				
				// End date, always display 6 weeks
				$end = strtotime("+41 days", $start);
				
				// Load events
				$events = eo_get_events(array( 
		       'event_end_before'=>date('Y-m-d', $end),
					 'event_start_after'=>date('Y-m-d', $start)
			  ));
			?>
			<div class="calendar-full">
				<table>
					<thead>
						<?php for($i = $offset; $i < 7+$offset; $i++): ?>
							<th><?php echo $wp_locale->weekday[$i % 7] ?></th>
						<?php endfor; ?>
					</thead>
					<?php
					$current = $start;
					$colcount = 0;
					reset($events);
					$event = current($events);
				
					while($current <= $end){
						if($colcount == 0) echo "<tr>";
						echo "<td class='".($selector[1] == date("m", $current) ? 'selected-month ' : 'other-month ').(date("d-m") == date("d-m", $current)?'today':'')."'>";
						echo "<span class='day'>".date("d", $current)."</span>";
					
						while($event && date("Y-m-d", $current) == eo_get_the_start( "Y-m-d", $event->ID, null, $event->occurrence_id )){
							echo "<div class='event'>".
								"<span class='time'>".eo_get_the_start( "H:i", $event->ID, null, $event->occurrence_id )."</span> ".
								get_the_title($event).
							"</div>";
							if(next($events)) $event = current($events);
							else break;
						}

						echo "</td>";
						$current += 3600*24;
						if($colcount++ > 5){ echo "</tr>"; $colcount = 0; }
					}
					?>
				</table>
			</div>
			<?php endif; ?>
		</div>
	</article>
<?php endif; ?>

<?php if($disc != 'month'): while (have_posts()) : the_post(); ?>
  <?php get_template_part('templates/colorize'); ?>
	<?php locate_template(array(
		"templates/archive/{$post->post_type}-".get_post_format().".php",
		"templates/archive/{$post->post_type}.php",
		"templates/archive/post.php",
	), true, false); ?>
<?php endwhile; endif; ?>

<?php if ($wp_query->max_num_pages > 1) : ?>
  <nav class="post-nav">
    <ul class="pager">
      <li class="previous"><?php next_posts_link(__('&larr; Older posts', 'roots')); ?></li>
      <li class="next"><?php previous_posts_link(__('Newer posts &rarr;', 'roots')); ?></li>
    </ul>
  </nav>
<?php endif; ?>