<?php



// Registering Hover Shortcode
function amazing_hover_effects_shortcode($atts){
	extract( shortcode_atts( array(
	
		'category' => '',	
		'style' => '',	
		'effect' => '',	
		'animation' => '',	
		'colored' => '',
		
	), $atts) );
	
	
	    $q = new WP_Query(
        array('posts_per_page' => -1, 'post_type' => 'hover-effect', 'hover_cat' => $category)
        );
	
		while($q->have_posts()) : $q->the_post();
		//$id = get_the_ID();	

	// Calling metabox data
	$infos = vp_metabox('infosmeta.hover_info', false);
	
	$i = 0;

		$output = '<ul class="ihover-container">';

		foreach ($infos as $info) {	
		
		
		$output .='<li class="hover_effects_li">';
		
		if($style=="circle" && $effect=="effect1"){
            $output .= '<div class="ih-item circle '.$colored.' effect1"><a href="#">
							<div class="spinner"></div>
								<div class="img"><img src="'.$info['image'].'"></div>
									<div class="info">
								<div class="info-back">
								<h3>'.$info['title'].'</h3>
								<p>'.$info['description'].'</p>
					
					</div></div></a></div>';
        }

		if($style=="circle" && $effect=="effect2"){
            $output .= '<div class="ih-item circle '.$colored.' effect2 '.$animation.'"><a href="#">
							<div class="spinner"></div>
								<div class="img"><img src="'.$info['image'].'"></div>
									<div class="info">
								<h3>'.$info['title'].'</h3>
								<p>'.$info['description'].'</p>
					
					</div></a></div>';
        }

		if($style=="circle" && $effect=="effect3"){
            $output .= '<div class="ih-item circle '.$colored.' effect3 '.$animation.'"><a href="#">
							<div class="spinner"></div>
								<div class="img"><img src="'.$info['image'].'"></div>
									<div class="info">
								<h3>'.$info['title'].'</h3>
								<p>'.$info['description'].'</p>
					
					</div></a></div>';
        }
			

		if($style=="circle" && $effect=="effect4"){
            $output .= '<div class="ih-item circle '.$colored.' effect4 '.$animation.'"><a href="#">
							<div class="spinner"></div>
								<div class="img"><img src="'.$info['image'].'"></div>
									<div class="info">
								<h3>'.$info['title'].'</h3>
								<p>'.$info['description'].'</p>
					
					</div></a></div>';
        }

		if($style=="circle" && $effect=="effect5"){
            $output .= '<div class="ih-item circle '.$colored.' effect5 '.$animation.'"><a href="#">
							<div class="spinner"></div>
								<div class="img"><img src="'.$info['image'].'"></div>
									<div class="info">
									<div class="info-back">
								<h3>'.$info['title'].'</h3>
								<p>'.$info['description'].'</p>
					
					</div></div></a></div>';
        }

		if($style=="circle" && $effect=="effect6"){
            $output .= '<div class="ih-item circle '.$colored.' effect6 scale_down_up"><a href="#">
							<div class="spinner"></div>
								<div class="img"><img src="'.$info['image'].'"></div>
									<div class="info">
								<h3>'.$info['title'].'</h3>
								<p>'.$info['description'].'</p>
					
					</div></a></div>';
        }
		
		if($style=="circle" && $effect=="effect7"){
            $output .= '<div class="ih-item circle '.$colored.' effect7 '.$animation.'"><a href="#">
							<div class="spinner"></div>
								<div class="img"><img src="'.$info['image'].'"></div>
									<div class="info">
								<h3>'.$info['title'].'</h3>
								<p>'.$info['description'].'</p>
					
					</div></a></div>';
        }

		if($style=="circle" && $effect=="effect8"){
            $output .= '<div class="ih-item circle '.$colored.' effect8 '.$animation.'"><a href="#">
								<div class="img-container">
								  <div class="img"><img src="'.$info['image'].'"></div>
								</div>
								<div class="info-container">
								  <div class="info">
									<h3>'.$info['title'].'</h3>
									<p>'.$info['description'].'</p>
								  </div>
				</div></a></div>';
        }
		
		
		if($style=="circle" && $effect=="effect9"){
            $output .= '<div class="ih-item circle '.$colored.' effect9 '.$animation.'"><a href="#">
							<div class="spinner"></div>
								<div class="img"><img src="'.$info['image'].'"></div>
									<div class="info">
								<h3>'.$info['title'].'</h3>
								<p>'.$info['description'].'</p>
					
					</div></a></div>';
        }

		if($style=="circle" && $effect=="effect10"){
            $output .= '<div class="ih-item circle '.$colored.' effect10 '.$animation.'"><a href="#">
							<div class="spinner"></div>
								<div class="img"><img src="'.$info['image'].'"></div>
									<div class="info">
								<h3>'.$info['title'].'</h3>
								<p>'.$info['description'].'</p>
					
					</div></a></div>';
        }

		if($style=="circle" && $effect=="effect11"){
            $output .= ' <div class="ih-item circle '.$colored.' effect11 '.$animation.'"><a href="#">
							<div class="spinner"></div>
								<div class="img"><img src="'.$info['image'].'"></div>
									<div class="info">
								<h3>'.$info['title'].'</h3>
								<p>'.$info['description'].'</p>
					
					</div></a></div>';
        }

		if($style=="circle" && $effect=="effect12"){
            $output .= '<div class="ih-item circle '.$colored.' effect12 '.$animation.'"><a href="#">
							<div class="spinner"></div>
								<div class="img"><img src="'.$info['image'].'"></div>
									<div class="info">
								<h3>'.$info['title'].'</h3>
								<p>'.$info['description'].'</p>
					
					</div></a></div>';
        }
		
		if($style=="circle" && $effect=="effect13"){
            $output .= '<div class="ih-item circle '.$colored.' effect13 from_left_and_right"><a href="#">
							<div class="spinner"></div>
								<div class="img"><img src="'.$info['image'].'"></div>
									<div class="info">
									<div class="info-back">
								<h3>'.$info['title'].'</h3>
								<p>'.$info['description'].'</p>
					
					</div></div></a></div>';
        }
		
		if($style=="circle" && $effect=="effect14"){
            $output .= '<div class="ih-item circle '.$colored.' effect14 '.$animation.'"><a href="#">
							<div class="spinner"></div>
								<div class="img"><img src="'.$info['image'].'"></div>
									<div class="info">
								<h3>'.$info['title'].'</h3>
								<p>'.$info['description'].'</p>
					
					</div></a></div>';
        }

		if($style=="circle" && $effect=="effect15"){
            $output .= '<div class="ih-item circle '.$colored.' effect15 left_to_right"><a href="#">
							<div class="spinner"></div>
								<div class="img"><img src="'.$info['image'].'"></div>
									<div class="info">
								<h3>'.$info['title'].'</h3>
								<p>'.$info['description'].'</p>
					
					</div></a></div>';
        }
		if($style=="circle" && $effect=="effect16"){
            $output .= '<div class="ih-item circle '.$colored.' effect16 left_to_right"><a href="#">
							<div class="spinner"></div>
								<div class="img"><img src="'.$info['image'].'"></div>
									<div class="info">
								<h3>'.$info['title'].'</h3>
								<p>'.$info['description'].'</p>
					
					</div></a></div>';
        }		
		
		if($style=="circle" && $effect=="effect17"){
            $output .= '<div class="ih-item circle '.$colored.' effect17 "><a href="#">
							<div class="spinner"></div>
								<div class="img"><img src="'.$info['image'].'"></div>
									<div class="info">
								<h3>'.$info['title'].'</h3>
								<p>'.$info['description'].'</p>
					
					</div></a></div>';
        }
		if($style=="circle" && $effect=="effect18"){
            $output .= '<div class="ih-item circle '.$colored.' effect18 '.$animation.'"><a href="#">
							<div class="spinner"></div>
								<div class="img"><img src="'.$info['image'].'"></div>
									<div class="info">
									<div class="info-back">
								<h3>'.$info['title'].'</h3>
								<p>'.$info['description'].'</p>
					
					</div></div></a></div>';
        }
		
		if($style=="circle" && $effect=="effect19"){
            $output .= '<div class="ih-item circle '.$colored.' effect19 "><a href="#">
							<div class="spinner"></div>
								<div class="img"><img src="'.$info['image'].'"></div>
									<div class="info">
								<h3>'.$info['title'].'</h3>
								<p>'.$info['description'].'</p>
					
					</div></a></div>';
        }
		
		if($style=="circle" && $effect=="effect20"){
            $output .= '<div class="ih-item circle '.$colored.' effect20 '.$animation.'"><a href="#">
							<div class="spinner"></div>
								<div class="img"><img src="'.$info['image'].'"></div>
									<div class="info">
									<div class="info-back">
								<h3>'.$info['title'].'</h3>
								<p>'.$info['description'].'</p>
					
					</div></div></a></div>';
        }
		

		$output .='</li>';


		$i++;
	}

	endwhile;
	$output .='</ul>';
	$output .= '<style>
					.ihover-container {
							clear: both;
							display: inline-block;
							list-style-type: none;
							margin: 0;
							padding: 0;
							position: relative;
							width: 100%;
						}
						.ihover-container li {
							background: none repeat scroll 0 0 transparent;
							float: left;
							list-style: none outside none;
							margin: 0 12px 50px 0;
							padding: 0;
							width: 300px;
						}
						.ihover-container li img {
							border-radius: 0;
							box-shadow: none;
							display: block;
							margin: 0;
							padding: 0;
						}
				</style>';
	wp_reset_query();
	return $output;
}

add_shortcode('hover', 'amazing_hover_effects_shortcode');
?>