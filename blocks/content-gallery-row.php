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

$style           = get_field('bgallery_style');
$block_color     = (get_field('bgallery_color') ? get_field('bgallery_color') : '#0D223F'); 
$block_bgcolor   = (get_field('bgallery_bgcolor') ? get_field('bgallery_bgcolor') : '#FFFFFF'); 
$block_animation = get_field('bgallery_animation');


$gallery_type    = get_field('bgallery_type');
//$gallery_columns = (get_field('bgallery_columns') ? get_field('bgallery_columns') : 2); 
$gallery_columns = 2; 


$col = "col-12";
switch ($gallery_columns) {
    case 1:
        $col = "col-12";
        break;
    case 2:
		$col = "col-sm-6";
		if($gallery_type == 'ctas') {
			$col = "col-md-6";
		}
        break;
    case 3:
        $col = "col-sm-6 col-md-6 col-lg-4";
		break;
	case 4:
		$col = "col-sm-6 col-md-6 col-lg-3";
		break;
	case 5:
		$col = "col-sm-6 col-md-6 col-lg-3";
		break;
	case 6:
		$col = "col-sm-6 col-md-6 col-lg-2";
		break;
}
$col .= ' gblock__gallery--col px-0 loop-mediumpost';
$container = 'container-fluid'; //'container-xl'

$htmlBody   = '';
if($gallery_type == 'images'):

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

	if( have_rows('bgallery_ctas') ):
		$int = 0;
		while( have_rows('bgallery_ctas') ) : the_row();

			$col_img_pos  = '';
			if($int%4 == 1 ) {
				$col_img_pos  = 'order-sm-first order-md-last';
			}
			if($int%4 == 2 ) {
				$col_img_pos  = 'order-md-first';
			}
			if( $int%4 == 3 ) {
				$col_img_pos  = 'order-sm-first';
			}

			$color      = get_sub_field('color');
			$bgcolor    = get_sub_field('bgcolor');
			$headline   = (get_sub_field('headline')? '<h3 class="card-title my-auto px-3">'.get_sub_field('headline').'</h3>' : '');
			$content    = (get_sub_field('content')? '<div class="card-excerpt mt-auto mb-3 px-3">'.get_sub_field('content').'</div>' : '');
			$image      = (get_sub_field('image')? '<div class="col-sm-6 gblock__gallery--img card-image d-none d-md-block '.$col_img_pos.'"><figure>'. wp_get_attachment_image( get_sub_field('image'), 'large').'</figure></div>' : '');
			$download   = (get_sub_field('download') ? 'download': false);
			$open_card  = (get_sub_field('url')? '<a class="row no-gutters" role="button" style="color:'.$color.'; background-color:'.$bgcolor.';" href="'.get_sub_field('url').'" '.$download.'>' : '');
			$close_card = (get_sub_field('url')? '</a>' : '');

			$htmlBody .= '
			<div class="'.$col.'">
				<div class="gblock__gallery--cta card mx-auto" style="color:'.$color.'; background-color:'.$bgcolor.';">
					'.$open_card.'
						<div class="card-body col-sm-6">
							<div class="card-content d-flex flex-column ">
								'.$headline.'
								'.$content.'
							</div>
						</div>
						'.$image.'
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


		if(get_post_thumbnail_id($post->ID)) {
			$post_image = get_the_post_thumbnail($post->ID, 'large');
		} else {
			$default_image = get_field('general_default_image','option');
			$post_image    = wp_get_attachment_image($default_image, 'large');
		}

		$bgcolor = '#EC8772';

		$htmlBody .= '
		<div class="'.$col.'">
			<div class="gblock__gallery--cta card mx-auto" style="background-color:'.$bgcolor.';">
				<div class="card-body">
					<div class="card-headline mt-0">
						<div class="gblock__gallery--img card-image"><a href="'.get_permalink().'"><figure>'. $post_image.'</figure></a></div>
					</div>
					<p>
						<a class="btn btn-primary btn-cta" role="button" href="'.get_permalink().'">'.get_the_title().'</a>
					</p>
				</div>
			</div>
		</div>';	

		$int++;
		
	endwhile;
	wp_reset_postdata(); 
	
endif; 


echo '<section id="'.$style['section_id'].'" class="section '.$style['section_class'].'" style="color:'.$block_color .';background-color:'.$block_bgcolor.';" >
  <div class="'.$container.'">
	<div id="'.$style['block_id'].'"  class="'.$style['block_class'].' gblock gblock__gallery" >
	  <div id="'.$style['content_id'].'" class="row '.$style['content_class'].' '.($block_animation ? 'animate-children fade_in_up' : '').'">
		'.$htmlBody.'
	  </div>
	</div>
  </div>
</section>'; ?>