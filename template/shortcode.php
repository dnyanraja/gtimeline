<?php

$container = $atts["containerdiv"];
$args = array(
	    'post_type' => 'timelinr',
	    'posts_per_page' => -1,
        'order' => $atts["order"],
	    'meta_key' => 'timelineDate',
		'orderby' => 'meta_value',
);
$timelinr_query = new WP_Query( $args );
if ($timelinr_query->have_posts()):?>
	<div <?php if (!empty($container)) echo 'class="'.$container.'"'; ?>>
			
		<script type="text/javascript">
			jQuery(document).ready(function($) {
			 	$('<?php if (!empty($container)) echo ".".$container." "; ?>.timeline').timelinr({
				 	orientation: '<?php echo $atts['orientation']; ?>',
				    // value: horizontal | vertical, default to horizontal
				    containerDiv: '<?php echo $atts['containerdiv']; ?>',
				    // value: any HTML tag or #id, default to #timeline
				    arrowKeys: '<?php echo $atts['arrowkeys']; ?>',
				    datesDiv: '#datesid',
				    datesSelectedClass: 'selected',
					datesSpeed: <?php echo $atts['speed']; ?>,
				    // value: integer between 100 and 1000 (recommended) or 'slow', 'normal' or 'fast'; default to normal
				    issuesDiv : '#issuesid',
				    issuesSelectedClass: 'selected',
			  		issuesSpeed: <?php echo $atts['speed']; ?>,
				    // value: integer between 100 and 1000 (recommended) or 'slow', 'normal' or 'fast'; default to fast
				    issuesTransparency: 0.2,
				    // value: integer between 0 and 1 (recommended), default to 0.2
				    issuesTransparencySpeed: 500,
				    // value: integer between 100 and 1000 (recommended), default to 500 (normal)
				    prevButton: '#prev',
			      	// value: any HTML tag or #id, default to #prev
			      	nextButton: '#next',
				    // value: true/false, default to false
				    startAt: <?php echo $atts['startat']; ?>,
				    // value: integer, default to 1 (first)
				    autoPlay: '<?php echo $atts['autoplay']; ?>',
				    // value: true | false, default to false
				    autoPlayDirection: '<?php echo $atts['autoplaydirection']; ?>',
				    // value: forward | backward, default to forward
				    autoPlayPause: <?php echo $atts['autoplaypause']; ?>
				    // value: integer (1000 = 1 seg), default to 2000 (2segs)< });
			   });
			});
		</script>
		<div class="timeline">
			<ul id="datesid" class="dates">
		    <?php
			while ($timelinr_query->have_posts()) : $timelinr_query->the_post();
			 	$timelineDate = get_post_meta($post->ID, 'timelineDate', 'true');
			 	//$date = $this->get_date_format($timelineDate, $atts['dateformat']);
			 	echo '<li><a href="#'.$timelineDate.'">'.$timelineDate.'</a></li>';
			endwhile;?>
			</ul>
			<ul id="issuesid" class="issues"><?php
				while ($timelinr_query->have_posts()) : $timelinr_query->the_post();
				$timelineDate = get_post_meta($post->ID, 'timelineDate', 'true');
				 		echo '<li id="'.$timelineDate.'">';
	                    echo get_the_post_thumbnail($post->ID, "small" );    
                        //if ($desing_options['permalink']) echo '<a href="'. get_permalink($post->ID).'">'.$post->post_title.'</a>';
                        echo '<span>'.$post->post_title.'</span>';
                        //if ($desing_options['postexcerpt']) echo '<p>'.$post->post_excerpt.'</p>';
                        echo '<p>'.$post->post_content.'</p>';
						echo '</li>';
				endwhile;?> 
		   	</ul>
		   	<a href="#" id="next" class="next">+</a>
	   		<a href="#" id="prev" class="prev">-</a>
	   	</div>
   	</div><?php 	
endif;

?>
