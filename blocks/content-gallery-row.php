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

$section_id    =  (get_field('bgallery_section_id') ? 'id="'.get_field('bgallery_section_id').'"' : '');
$section_class =  (get_field('bgallery_section_class') ? get_field('bgallery_section_class') : ''); 
$block_id      =  (get_field('bgallery_id') ? 'id="'.get_field('bgallery_id').'"' : '');
$block_class   =  (get_field('bgallery_class') ? get_field('bgallery_class') : '');


$block_color     = (get_field('bgallery_color') ? get_field('bgallery_color') : '#FFFFFF'); 
$block_animation = get_field('bgallery_animation');





$htmlBody   = '';
$block_images = get_field('bgallery_gallery');
if($block_images):
    $htmlBody .= '
		<div class="post_gallery my-auto row mx-md-0 '.($block_animation ? 'animate-children fade_in_up' : '').'">';
        foreach($block_images as $image_id ):
            $htmlBody .= '
			<div class="col-sm-6 col-md-3 px-sm-0">
				<div class="gblock__gallery--img">
				'. wp_get_attachment_image( $image_id, 'retina').'
				</div>
			</div>';
        endforeach;
        $htmlBody .= '
		</div>';
endif; 
        

if(get_field('bgallery_show_scroll_cta')) {
	$htmlBody .= '
	<button class="gblock__gallery_scroll_cta '.($block_animation ? 'animate-single fade_in_up' : '').' moveScrollDown">
		'.get_field('bgallery_scroll_cta').'
	</button>';
}







echo '
<section '.$section_id.' class="section section-gallery '.$section_class.'" data-color="'.$block_color.'">
  <div class="container-fluid p-0 h-100 d-flex flex-column"">
	<div '.$block_id.' class="gblock gblock__gallery my-auto text-center '.$block_class.'" >
			'.$htmlBody.'
	</div>
  </div>
</section>';
?>

