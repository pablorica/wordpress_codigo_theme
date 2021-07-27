<?php
/**
 * Block Name: Gallery Row
 *
 * This is the template that displays the gallery row block.
 *
 * @param   array $block The block settings and attributes.
 * @param   bool $is_preview True during AJAX preview.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if (!function_exists('get_field')) {
	exit; // Exit if ACF is not enabled
}

global $wp;
$current_url = home_url( $wp->request ).'/';

$style           = get_field('bgallery_style');
$block_color     = (get_field('bgallery_color') ? get_field('bgallery_color') : '#0D223F'); 
$block_bgcolor   = (get_field('bgallery_bgcolor') ? get_field('bgallery_bgcolor') : '#FFFFFF'); 
$block_animation = get_field('bgallery_animation');

if($style['section_id']){
	$cssIndex = "#".$style['section_id'];
} else {
	$randomClass = 'section_'.substr(str_shuffle(MD5(microtime())), 0, 5);
	$style['section_class'] .= " ".$randomClass;
	$cssIndex = ".".$randomClass;
}

$gallery_type    = get_field('bgallery_type');
$gallery_columns = (get_field('bgallery_columns') ? get_field('bgallery_columns') : 3); 


$col = "col-12";
switch ($gallery_columns) {
    case 1:
        $col = "col-12";
        break;
    case 2:
		$col = "col-md-6";
        break;
    case 3:
        $col = "col-md-4";
		break;
	case 4:
		$col = "col-md-3";
		break;
	case 5:
		$col = "col-md-3";
		break;
	case 6:
		$col = "col-md-2";
		break;
}
$col .= ' gblock__gallery--col px-1 mb-5 mb-md-0';
$container = 'container-fluid'; //'container-xl'


$extra_div_open  = '';
$extra_div_close = '';
if(get_field('bgallery_hscroll')) {
	$extra_div_open  = '
	<div class="horizontal_wrapper">
	<div class="horizontal_scroll">
		<button class="h_scroll-arrow slick-prev slick-arrow" data-scroll-modifier="-1"></button>';

	$extra_div_close = '
		<button class="h_scroll-arrow slick-next slick-arrow" data-scroll-modifier="1"></button>
	</div>
	</div>';
}

$htmlStyle = '';
$htmlBody  = '';
$htmlTitle  = '';
if(get_field('bgallery_title')) {
	$htmlTitle .= '<h2 class="col-12 text-center gblock__gallery_title mb-4">'.get_field('bgallery_title').'</h2>';
}
if($gallery_type == 'images'):

	$col .= ' py-1';

	if($block_images = get_field('bgallery_images')){

		foreach($block_images as $image_id ):
			$htmlBody .= '
			<div class="'.$col.'">
				<div class="gblock__gallery--img">
					<figure>'. wp_get_attachment_image( $image_id, 'large').'</figure>
				</div>
			</div>';
		endforeach;
	}
endif; 

if($gallery_type == 'ctas'):

	$col .= ' py-5';

	if( have_rows('bgallery_ctas') ):
		$int = 0;
		while( have_rows('bgallery_ctas') ) : the_row();

			$color      = get_sub_field('color') ? 'color:'.get_sub_field('color').';' : '';
			$bgcolor    = get_sub_field('bgcolor') ? 'background-color:'.get_sub_field('bgcolor').';' : '';
			$headline   = (get_sub_field('headline')? '<h3 class="card-title">'.get_sub_field('headline').'</h3>' : '');
			$content    = (get_sub_field('content')? '<div class="card-excerpt">'.get_sub_field('content').'</div>' : '');
			$image      = (get_sub_field('image')? '<div class="gblock__gallery--img card-image"><figure>'. wp_get_attachment_image( get_sub_field('image'), 'large').'</figure></div>' : '');
			$download   = (get_sub_field('download') ? 'download': false);
			$open_card  = (get_sub_field('url')? '<a role="button" style="'.$color.' '.$bgcolor.'" href="'.get_sub_field('url').'" '.$download.'>' : '');
			$close_card = (get_sub_field('url')? '</a>' : '');

			$htmlBody .= '
			<div class="'.$col.'">
				<div class="gblock__gallery--cta card mx-auto" style="'.$color.' '.$bgcolor.'">
					'.$open_card.'
					'.$image.'
					<div class="card-content">
						'.$headline.'
						'.$content.'
					</div>
					'.$close_card.'
				</div>
			</div>';
			$int ++;
		endwhile;
	endif;


endif; 


if($gallery_type == 'articles'):
	$block_articles = get_field('bgallery_articles');

	//error_log('$block_articles ' . print_r($block_articles,true));
	$post_type = null;

	if($block_articles['type'] == 'sametype') {
		$post_type = $block_articles['post_type'];
		$excluded  = $block_articles['excluded'];

		$args = array(  
			'post_type' => $post_type,
			'post_status' => 'publish',
			'posts_per_page' => -1,
			'post__not_in' => $excluded
		);

	}
	if($block_articles['type'] == 'selected') {
		$post_list = $block_articles['articles_list'];

		//error_log('$post_list '.print_r($post_list,true));

		$args = array(  
			'post_status'    	  => 'publish',
			'post_type'      	  => 'any',
			'posts_per_page' 	  => -1,
			'post__in'            => $post_list,
			'ignore_sticky_posts' => true
		);

	}

	//error_log('args '.print_r($args,true));

	$loop = new WP_Query( $args ); 

	//error_log('query '.$loop->request);
	
	$int = 0;		
	while ( $loop->have_posts() ) : $loop->the_post(); 

		$post_id = get_the_id();

		if($block_articles['titles_only']) {
			$col = str_replace('col-4','col-md-4',$col);
			$col = str_replace('col-3','col-md-3',$col);
			$col = str_replace('col-2','col-md-2',$col);
			$col = 'col-sm-6 '.$col;

			$htmlBody .= '
			<div class="'.$col.'">
				<div class="gblock__gallery--cta card mx-auto">
					<div class="card-body">
						<p>
							<a class="btn btn-secondary'.(get_permalink() == $current_url ? " active" : "").'" role="button" href="'.get_permalink().'#post-'.$post_id.'"><span>'.strtoupper(get_the_title()).'</span></a>
						</p>
					</div>
				</div>
			</div>';

		} else {
			if(get_post_thumbnail_id($post_id)) {
				$post_image = get_the_post_thumbnail($post_id, 'large');
			} else {
				$default_image = get_field('general_default_image','option');
				$post_image    = wp_get_attachment_image($default_image, 'large');
			}

			$excerpt = '';
			if($block_articles['display_excerpts']) {
				$excerpt = codigo_advanced_excerpt();
			}

			$htmlBody .= '
			<div class="'.$col.'">
				<div class="gblock__gallery--cta card mx-auto">
					<div class="card-body">
						<div class="card-headline mt-0">
							<div class="gblock__gallery--img card-image"><a href="'.get_permalink().'"><figure>'. $post_image.'</figure></a></div>
						</div>
						<h3 class="card-title">'.strtoupper(get_the_title()).'</h3>
						'.$excerpt.'
						<p>
							<a class="btn btn-secondary'.(get_permalink() == $current_url ? " active" : "").'" role="button" href="'.get_permalink().'#post-'.$post_id.'"><span>'.__( "FIND OUT MORE", "wpbootstrapsass").'</span></a>
						</p>
					</div>
				</div>
			</div>';	
		}

		$int++;
		
	endwhile;
	wp_reset_postdata(); 

	$htmlStyle .= '
			'.$cssIndex.' h3 a {
				color: '.$block_color.' !important;
			}
			'.$cssIndex.' .btn-secondary {
				color: '.$block_color.' !important;
				background-color: '.$block_bgcolor.' !important;
				border-color: '.$block_color.' !important;
			}
			'.$cssIndex.' .btn-secondary:hover,
			'.$cssIndex.' .btn-secondary.active  {
				color: '.$block_bgcolor.' !important;
				background-color: '.$block_color.' !important;
				border-color: '.$block_bgcolor.' !important;
			}';

	
endif; 


if(get_field('bgallery_hscroll')) {
	$htmlBody .= '<div class="col horizontal_scroll__last"></div>';
}


$htmlStyle .= '
			'.$cssIndex.' .gblock__gallery--img img {
				object-fit: '.(get_field('bgallery_image_fit') ? get_field('bgallery_image_fit') :'cover').';
			}';


echo '<section id="'.$style['section_id'].'" class="section '.$style['section_class'].'" style="color:'.$block_color .';background-color:'.$block_bgcolor.';" >
  <div class="'.$container.'">
	<div id="'.$style['block_id'].'"  class="'.$style['block_class'].' gblock gblock__gallery" >
	'.$htmlTitle.'
	'.$extra_div_open.'
	  <div id="'.$style['content_id'].'" class="row no-gutters '.$style['content_class'].' '.($block_animation ? 'animate-children fade_in_up' : '').'">
		'.$htmlBody.'
	  </div>
	'.$extra_div_close.'
	</div>
  </div>
</section>
<style>
'.$htmlStyle.'
</style>'; ?>