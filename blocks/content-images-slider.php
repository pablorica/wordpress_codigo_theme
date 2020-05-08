<?php
/**
 * Block Name: Images Slider
 *
 * This is the template that displays a simple images slider block.
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

if($images = get_field('block_images_slider')){

$section_id    =  (get_field('block_images_slider_section_id') ? 'id="'.get_field('block_images_slider_section_id').'"' : '');
$section_class =  (get_field('block_images_slider_section_class') ? 'class="'.get_field('block_images_slider_section_class').'"' : '');
$block_id      =  (get_field('block_images_slider_id') ? 'id="'.get_field('block_images_slider_id').'"' : '');
$block_class   = 'class="gblock gblock__images_slider '. (get_field('block_images_slider_class') ? get_field('block_images_slider_class') : '') .'"';

$block_autoplay = 'data-autoplay="'.(get_field('block_images_slider_autoplay') ? 'true' : 'false').'"';
$block_arrows 	= 'data-arrows="'.(get_field('block_images_slider_arrows') ? 'true' : 'false').'"';

$html = '
	<section '.$section_id.' '.$section_class.'>
		<div '.$block_id.' '.$block_class.'>
			<div class="container p-0">
				<div class="slick-images_slider" '.$block_autoplay.' '.$block_arrows.'>';



foreach( $images as $image ):

    $html .= '
    <div class="image-element keep_prop" data-ratiow="1538" data-ratioh="865" data-ratiomw="335" data-ratiomh="220">
    '.wp_get_attachment_image( $image['ID'], "full" ).'
    </div>';

endforeach;



$html .= '		</div>
			</div>
		</div>
	</section>';

echo $html;

}
?>
