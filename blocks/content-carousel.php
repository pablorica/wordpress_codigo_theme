<?php
/**
 * Block Name: Carousel
 *
 * This is the template that displays the carousel block.
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


$html='';
if( have_rows('block_carousel')) {
	$cint 		   = 0;
	$section_id    =  (get_field('block_carousel_section_id') ? 'id="'.get_field('block_carousel_section_id').'"' : '');
	$section_class =  (get_field('block_carousel_section_class') ? 'class="'.get_field('block_carousel_section_class').'"' : '');
	$block_id      =  (get_field('block_carousel_id') ? 'id="'.get_field('block_carousel_id').'"' : '');
	$block_class   = 'class="gblock gblock__carousel slick-carousel '. (get_field('block_carousel_class') ? get_field('block_carousel_class') : '') .'"';


	$block_autoplay = 'data-autoplay="'.(get_field('block_carousel_autoplay') ? 'true' : 'false').'"';
	$block_dots  	= 'data-dots="'.(get_field('block_carousel_dots') ? 'true' : 'false').'"';

	$cint = 0;
	$html = '
	<section '.$section_id.' '.$section_class.'>
		<div '.$block_id.' '.$block_class.' '.$block_autoplay.' '.$block_dots.'>';
	while ( have_rows('block_carousel') ) : the_row();

		//$image = '';
		if($image_field=get_sub_field('image')) {
			$image=wp_get_attachment_image($image_field['ID'], 'medium_large');
			//$image_src=$array_image[0];
		}
		$cta = '';
		if($cta_field=get_sub_field('link')) {
			$cta = '<a href="'.$cta_field['url'].'" style="color:'.get_sub_field('color').'"><span class="intro-link" style="color:'.get_sub_field('color').'">'.$cta_field['title'].'</span></a>';
		}

		$order_img  = '';
		$order_text = '';
		if(get_sub_field('position') == "left") {
			$order_img  = 'order-lg-first';
			$order_text = 'order-lg-last';
		}


		$html .= '
	<div class="carousel-element">
		<div class="row p-0">
			<div class="col-lg-6 p-0 text-element '.$order_text.'">
				<div class=" keep_prop" style="background-color:'.get_sub_field('background_color').'" data-ratiow="960" data-ratiomw="375" data-ratioh="630" data-ratiomh="250">
					<div class="inner-content">
						<p class="lead-content" style="color:'.get_sub_field('color').'">'.get_sub_field('title').'</p>
						<p class="intro-content" style="color:'.get_sub_field('color').'">'.get_sub_field('headline').'</p>
						'.$cta.'
					</div>
				</div>
			</div>
			<div class="col-lg-6 p-0 image-element hide-onclick '.$order_img.'">
				<div class=" keep_prop" data-ratiow="960" data-ratiomw="375" data-ratioh="630" data-ratiomh="250">' . $image .'</div>
			</div>
		</div>
	</div>';

		$cint++;
	endwhile;

	$html .= '</div>
	</section>';

	echo $html;
}
?>
