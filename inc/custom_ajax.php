<?php /*>*/
/**
 * Custom AJAX functions. JS functions stored in custom-javascript.js
 */
 

/* Hook to define AJAX environment */
add_action( 'wp_enqueue_scripts', 'codigo_ajax_enqueue_scripts' );
function codigo_ajax_enqueue_scripts() {
	global $wp_query;
	//enqueue 'codigo-scripts' was defined in understrap.php
	
    wp_localize_script( 'codigo-scripts', 'cdgajax', 
		 array( 
			  'ajaxurl' => admin_url( 'admin-ajax.php' ),
			  'query_vars' => @json_encode( $wp_query->query )
		 ) 
	);
	//AJAX javascript functions are in src/js/custom-javascript.js
}


add_action( 'wp_ajax_nopriv_cdgajax_results', 'cdgajax_results' );
add_action( 'wp_ajax_cdgajax_results', 'cdgajax_results' );

function cdgajax_results() {
    $query_vars = json_decode( stripslashes( $_REQUEST['query_vars'] ), true );
	
	//Unset the defaut static page values
	unset($query_vars['pagename']);
	$taxonomies = get_taxonomies(); 
	foreach ( $taxonomies as $taxonomy ) {
		unset($query_vars[$taxonomy]);
	}
	
	$query_vars['order'] = 'DESC';
	$query_vars['orderby'] = 'date';

	
	if($_REQUEST['custom_query']) {
		$custom_query=$_REQUEST['custom_query'];
		
		if(isset($custom_query['posts_per_page'])) {
			$query_vars['posts_per_page'] = $custom_query['posts_per_page'];
		}
		
		if(isset($custom_query['post_type'])) {
			$query_vars['post_type'] = $custom_query['post_type'];
		}
		
		if(isset($custom_query['order'])) {
			$query_vars['order'] = $custom_query['order'];
		}
		if(isset($custom_query['orderby'])) {
			$query_vars['orderby'] = $custom_query['orderby'];
		}
		if(isset($custom_query['paged'])) {
			$query_vars['paged'] = $custom_query['paged'];
		}
	} 
	/**/
	if($_REQUEST['taxonomy']) {
		$tax=$_REQUEST['taxonomy'];
		$filter=array (
			'taxonomy' => $tax['name'],
			'field' => $tax['field'],
			'terms' => $tax['terms'],
		);
		$query_vars['tax_query'] = array($filter);
		//For more than one filter: $query_vars['tax_query'] = array($filter1,$filter2, etc...);
	}
	/**/
	
	/**
	echo '<pre>';
	print_r($query_vars);
	echo'</pre>';
	/**/
		
	$posts = new WP_Query( $query_vars );
	$GLOBALS['wp_query'] = $posts;
			
	if( ! $posts->have_posts() ) { 
		get_template_part( 'loop-templates/content', 'none' );
	}
	else {
		$ajint=0;
		while ( $posts->have_posts() ) { 
			$posts->the_post();
			//var_dump(get_post_type() );
			if(get_post_type()=='post') { 
				if(isset($query_vars['paged'])) {
					$per_page=get_option('posts_per_page');
					$pint=$ajint+$per_page*($query_vars['paged']-1);
				} else {
					$pint=$ajint;
				}
				
				include(locate_template('loop-templates/content-post.php'));
			}
			$ajint++;
		}
	}



    echo '<!-- The AJAX pagination component -->';
	//print_r($query_vars);
	//print_r($_REQUEST['custom_query']);
	cdgajax_loadmore();

    die();
}


function cdgajax_loadmore() {
	global $wp_query;
	$max = intval( $wp_query->max_num_pages );
	
	if ( is_singular() ) {
		//var_dump('singular');
		return;
	}
	// Stop execution if there's only 1 page
	if ( $max <= 1 ) {
		//var_dump('one page');
		return;
	}
	
	$paged = get_query_var( 'paged' ) ? absint( get_query_var( 'paged' ) +1 ) : 2;
	
	if($paged<=$max) {
		
		$tax_fields='';
		if(get_query_var( 'tax_query' )) {
			$tax=get_query_var( 'tax_query' );
			$tax_fields='data-taxonomy-name="'.$tax[0]['taxonomy'].'" data-taxonomy-field="'.$tax[0]['field'].'" data-taxonomy-terms="'.$tax[0]['terms'].'"';
			//var_dump($tax_fields);
		}
		
		echo '<button class="btn load-more ajax-filter" data-ajax-container="ajaxRow" data-post-type="'.get_post_type().'" '.$tax_fields.' data-paged="'.$paged .'" >
		'._('Load more').'
		</button>';
	}
}



add_action( 'wp_ajax_nopriv_cdgajax_mediums', 'cdgajax_mediums' );
add_action( 'wp_ajax_cdgajax_mediums', 'cdgajax_mediums' );

function cdgajax_mediums() {

	if($_REQUEST['custom_query']) {
		$custom_query=$_REQUEST['custom_query'];
		
		if(isset($custom_query['post-id'])) {
			$postId = $custom_query['post-id'];
			$medium = $custom_query['medium'] ?: 0;
			$blint = $custom_query['blint'] ?: 0;
			
			/**
			echo '<pre>';
			print_r('postID '.$postId);
			print_r('medium '.$medium);
			echo'</pre>';
			/**/
			
			while ( have_rows('bloques', $postId) ) : the_row();
				if( get_row_layout() == 'medium' ) {
					if(have_rows('medium_rows')) {
						$rint=0;
						while ( have_rows('medium_rows') ) : the_row();
							if($rint==$medium) {
								$medium_title=get_sub_field('title');
								$medium_image=get_sub_field('image');
								$medium_content=get_sub_field('content');
								
								$medium_cta=get_sub_field('cta');
								$button_id='cta-'.get_row_layout().'-'.$blint.'-'.$rint;
								$medium_cta_style='';
								$medium_cta_class="btn".(get_sub_field('cta_hover') ? " btn-".get_sub_field('cta_hover') : '').(get_sub_field('cta_reverse') ? " reverse" : '');
								if(get_sub_field('cta_hover') == 'gradient') {
									$gradient=get_sub_field('cta_gradient');
									
									$style_gradient='
										background: '.$gradient['left_colour'].';
										background: -moz-linear-gradient(left, '.$gradient['left_colour'].' 0%, '.$gradient['right_colour'].' 100%);
										background: -webkit-linear-gradient(left, '.$gradient['left_colour'].' 0%,'.$gradient['right_colour'].' 100%);
										background: linear-gradient(to right, '.$gradient['left_colour'].' 0%,'.$gradient['right_colour'].' 100%);
										filter: progid:DXImageTransform.Microsoft.gradient( startColorstr=\''.$gradient['left_colour'].'\', endColorstr=\''.$gradient['right_colour'].'\',GradientType=1 );
										';
									
									$medium_cta_style='
									<style>
									#'.$button_id.':hover, #'.$button_id.':focus {
										color: white !important;
										border-color:transparent !important;
									}
									#'.$button_id.'::after {
										'.$style_gradient.'
									}
									#'.$button_id.'.reverse {
										background:transparent;
										border-color:transparent !important;
										color: white !important;
									}
									#'.$button_id.'.reverse::after {
										'.$style_gradient.'
									}
									#'.$button_id.'.reverse:hover {
										color:'.$gradient['left_colour'].' !important;
										border-color: '.$gradient['left_colour'].' !important;
									}
									#'.$button_id.'.reverse:hover::after {
										opacity: 0;
										-webkit-transform: translate3d(-50%,-50%,0) scale(.1);
										transform: translate3d(-50%,-50%,0) scale(.1);
									}
									</style>';
								}
							}
							$rint++;
						endwhile;
					
						include(locate_template('global-templates/medium.php'));
					}
				}
			endwhile;
		}

	} 

	
	

    die();
}