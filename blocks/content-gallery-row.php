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

$block_menucolor = (get_field('bgallery_menu_color')?get_field('bgallery_menu_color'):'bg-green');

$gallery_type    = get_field('bgallery_type');
$gallery_columns = (get_field('bgallery_columns') ? get_field('bgallery_columns') : 3); 


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
$col .= ' gblock__gallery--col';
//$container = 'container-fluid';
$container = 'container';


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
	$container = 'container-xl';
	if( have_rows('bgallery_ctas') ):

		while( have_rows('bgallery_ctas') ) : the_row();
			$color    = get_sub_field('color');
			$bgcolor  = get_sub_field('bgcolor');
			$headline = (get_sub_field('headline')? '<h4 class="card-title">'.get_sub_field('headline').'</h4>' : '');
			$content  = (get_sub_field('content')? '<div class="card-excerpt">'.get_sub_field('content').'</div>' : '');
			$image    = (get_sub_field('image')? '<div class="gblock__gallery--img card-image"><figure>'. wp_get_attachment_image( get_sub_field('image'), 'large').'</figure></div>' : '');

			$htmlCTA  = '';
			if( have_rows('cta') ){ while ( have_rows('cta') ) { the_row();   
                $cta_download = (get_sub_field('download') ? 'download': false);
                $cta_color    = get_sub_field('color');
                $cta_bg       = get_sub_field('bgcolor');
                $cta          = get_sub_field('link');

                $cta_class    = "btn btn-primary btn-cta".($cta_download ?  ' btn-download': '');

                if(is_array($cta) && ( isset($cta['title']) && isset($cta['url']) )  ){
                    $htmlCTA .= '
                        <p>
                        <a class="'.$cta_class.'" role="button" href="'.$cta['url'].'" target="'.$cta['target'].'" style="color:'.$cta_color.'; border-color:'.$cta_color.'; background-color:'.$cta_bg.';" '.$cta_download.'>'.$cta['title'].'</a>
                        </p>';
                    
                }
            }}

			$htmlBody .= '
			<div class="'.$col.'">
				<div class="gblock__gallery--cta card mx-auto" style="color:'.$color.'; background-color:'.$bgcolor.';">
					<div class="card-body">
						<div class="card-headline mt-0">
							'.$headline.'
							'.$content.'
							'.$image.'
						</div>
						'.$htmlCTA.'
					</div>
				</div>
			</div>';
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


echo '<section id="'.$style['section_id'].'" class="section '.$style['section_class'].'" style="color:'.$block_color .';background-color:'.$block_bgcolor.';" data-menucolor="'.$block_menucolor.'">
  <div class="'.$container.'">
	<div id="'.$style['block_id'].'"  class="'.$style['block_class'].' gblock gblock__gallery" >
	  <div id="'.$style['content_id'].'" class="row '.$style['content_class'].' '.($block_animation ? 'animate-children fade_in_up' : '').'">
		'.$htmlBody.'
	  </div>
	</div>
  </div>
</section>'; ?>