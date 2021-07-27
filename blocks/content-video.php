<?php
/**
 * Block Name: Video
 *
 * This is the template that displays the video block.
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

$style 					= get_field('bvideo_style');
$style['section_style'] = "";
$style['section_data']  = "";

$block_menucolor = (get_field('bvideo_menu_color')?get_field('bvideo_menu_color'):'bg-green');


$htmlBack   = '';
$htmlStyle  = '';
$background = get_field('bvideo_background'); 
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



$htmlBody   = '';
$block_body = get_field('bvideo_content');
$block_body['groupname'] = 'bvideo_content';
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
<div class="row gblock__video_body--text my-auto '.$animation_css.'">
	<div id="'.$style['content_id'].'" class="gblock__video_body--content d-flex flex-column '.$style['content_class'].' " '.($block_body['colour'] ? 'style="color:'.$block_body['colour'].'"' : '').' >';

	if($block_body['headline']) {
		$htmlBody .= '<div class="gblock__video_body--headline pt-5">'.$block_body['headline'].'</div>';
	}

    $video_file['mobile'] = $block_body['mobile_video'] ? $block_body['mobile_video'] : null; 
    $video_file['desktop']  = $block_body['desktop_video'] ? $block_body['desktop_video'] : null; 

    if($video_file['mobile'] || $video_file['desktop']) {
        $type 		= 'videofile';
        
        $args = array(
            'controls' => true,
            'autoplay' => false,
            'loop' 	   => $block_body['loop'],
            'muted'    => true,
        );
        //error_log('$type: '.$type);
        ob_start(); ?>
        <div class="gblock__video_body--file my-auto container video-trigger">
            <?php //get_template_part( 'global-templates/component', 'video' ); ?>
            <?php include(locate_template('global-templates/component-video.php')); ?>
        </div>
        <?php
        $htmlBody .= ob_get_contents();
        ob_end_clean();
    
    }

	if($block_body['footer']) {
		$htmlBody .= '
		<div class="gblock__video_body--footer pb-5">'.$block_body['footer'].'</div>';
	}
    $htmlBody .= '
    </div>';

    

	$htmlBody .= '
</div>';


echo '
<section id="'.$style['section_id'].'" class="section '.$style['section_class'].' " style="'.$style['section_style'].'" data-menucolor="'.$block_menucolor.'" '.$style['section_data'].' >
'.$htmlBack.'
  <div id="'.$style['block_id'].'" class="gblock__video container-fluid '.$style['block_class'].' d-flex flex-column">
	'.$htmlBody.'
  </div>
</section>'
.$htmlStyle;