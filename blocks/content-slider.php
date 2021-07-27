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

$style 					= get_field('bslider_style');
$style['section_style'] = "";
$style['section_data']  = "";

$block_menucolor = (get_field('bslider_menu_color')?get_field('bslider_menu_color'):'bg-green');


$htmlBack   = '';
$htmlStyle  = '';
$background = get_field('bslider_background'); 
if($background['type'] == 'color') {
	$htmlBack .= '
	<div class="fp-bg" style="background-color:'.$background['background_colour'].'"></div>';
	$block_color_bg = $background['background_colour'];

	if($background['fullscreen']){
		$style['section_style'] .= " background-color:".$background['background_colour'].";";
		$style['section_class'] .= " d-flex hv-100";
	}
}
if($background['type'] == 'image') {

	if(!$background['mobile_image']) $background['mobile_image'] = $background['desktop_image'];
    if(!$background['desktop_image']) $background['desktop_image'] = $background['mobile_image'];

	

	if($background['parallax']){

		$style['section_class'] .= " d-flex hv-100";
		$style['section_style'] .= ' overflow:hidden';

		$mobile_image  = wp_get_attachment_image($background['mobile_image'], 'large', false, array('class'=>'d-md-none'));
		$desktop_image = wp_get_attachment_image($background['desktop_image'], 'full', false, array('class'=>'d-none d-md-block'));
		$htmlBack .= '
		<div class="rellax fp-bg fp-bg--image">
			<figure>
				'.$mobile_image.'
				'.$desktop_image.'
			</figure>
		</div>';

	}
	else {
		$mobile_image  = wp_get_attachment_image_url($background['mobile_image'], 'large', false);
		$desktop_image = wp_get_attachment_image_url($background['desktop_image'], 'full', false);

		
		if($style['section_id']){
			$cssIndex = "#".$style['section_id'];
		} else {
			$randomClass = 'section_'.substr(str_shuffle(MD5(microtime())), 0, 5);
			$style['section_class'] .= " ".$randomClass;
			$cssIndex = ".".$randomClass;
		}
		$htmlStyle .= '
		<style>
			'.$cssIndex.'{
				background-image: url('.$mobile_image.');
				background-size: cover;
			}
			@media screen and (min-width: 768px) {
				'.$cssIndex.'{
					background-image: url('.$desktop_image.');
				}
			}
		</style>';

		$style['section_class'] .= " d-flex hv-100";
	}
}
if(strpos($background['type'], 'video') !== false){
	$type 		= $background['type'];
	$video_url  = $background['video_url']; 
	$video_file['desktop'] = $background['desktop_video']; 
	$video_file['mobile']  = $background['mobile_video']; 
	$args = array(
		'controls' => $background['controls'],
		'autoplay' => $background['autoplay'],
		'loop' 	   => $background['loop'],
		'muted'    => $background['muted'],
	);
	//error_log('$type: '.$type);
	ob_start(); ?>
	<div class="fp-bg fp-bg--video">
		<?php //get_template_part( 'global-templates/component', 'video' ); ?>
		<?php include(locate_template('global-templates/component-video.php')); ?>
	</div>
	<?php
	$htmlBack .= ob_get_contents();
	ob_end_clean();

	$style['section_class'] .= " d-flex hv-100";
}


$htmlBody   = '';
$block_body = get_field('bslider_content');
$block_body['groupname'] = 'bslider_content';
$block_animation = $block_body['animation'];

if($block_animation) {

	if (strpos($style['section_class'], 'section-header') !== false) {
		$animation_css = 'scene_element scene_element--fadeinup';
	} else {
		$style['section_class'].= ' animated';
		$animation_css = 'animate-children fade_in_up';
	}
}


if(!$style['block_class']) {
	$style['block_class'] = "text-center my-auto";
}
if(!$style['content_class']) {
	$style['content_class'] = "col-md-12";
}



$htmlBody .= '
<div class="row gblock__slider_body--text my-auto '.$animation_css.'">
	<div id="'.$style['content_id'].'" class="gblock__slider_body--content mb-5 '.$style['content_class'].' "  >';

	if($block_body['headline']) {
		$htag =  'h2';
		if (strpos($style['section_class'], 'section-header') !== false) {
			$htag = 'h1';
		}
		$htmlBody .= '<'.$htag.' class=" gblock__slider_body--htext">'.$block_body['headline'].'</'.$htag.'>';
	}
	if($block_body['content']) {
		$htmlBody .= '
		<div class="gblock__slider_body--ctext">
		'.$block_body['content'].'
		</div>';
	}
    $htmlBody .= '
    </div>';

    if(have_rows('bslider_carousel') ):

        $block_images      = $body['carousel'];
        $carousel_autoplay = (get_field('bslider_autoplay') ? 1: 0);
        $carousel_arrows   = (get_field('bslider_arrows') ? 1: 0);
        $carousel_navdots  = (get_field('bslider_dots') ? true: false);
        $carousel_loop     = (get_field('bslider_loop') ? true: false);

        $htmlBody .= '
        <div class="col-10 col-md-6 mx-auto mt-5">
            <div class=" p-0 m-auto slick-carousel-wrapper  '.($body['animation'] ? 'animate-children '.$fade_animation : '').'">
                <div class="slick-carousel top-arrows" data-autoplay='.$carousel_autoplay.' data-arrows='.$carousel_arrows.' data-dots='.$carousel_navdots.' >';
        
                    while( have_rows('bslider_carousel') ) : the_row();
                        // Load sub field value.
                        $slide_title   = get_sub_field('title');

						$mobile_image   = get_sub_field('mobile_image');
						$desktop_image  = get_sub_field('desktop_image');
						if(!get_sub_field('mobile_image'))  $mobile_image  = $desktop_image;
            			if(!get_sub_field('desktop_image')) $desktop_image = $mobile_image;

                        $slide_mobile  = wp_get_attachment_image($mobile_image, 'large', false, array('class'=>'d-md-none'));
                        $slide_desktop = wp_get_attachment_image($desktop_image, 'full', false, array('class'=>'d-none d-md-block'));
                        $htmlBody .= '
                        <div>
                            <h3 class="slide_title">'.$slide_title.'</h3>
                            <figure>
                                '.$slide_mobile.'
                                '.$slide_desktop.'
                            </figure>
                        </div>';

                    endwhile;

        $htmlBody .= '
                </div>
            </div>
        </div>';
    endif; 
    

	$htmlBody .= '
</div>';


echo '
<section id="'.$style['section_id'].'" class="section '.$style['section_class'].' " style="'.$style['section_style'].'" '.$style['section_data'].' >
'.$htmlBack.'
  <div id="'.$style['block_id'].'" class="gblock__slider container-fluid '.$style['block_class'].' d-flex flex-column">
	'.$htmlBody.'
  </div>
</section>'
.$htmlStyle;