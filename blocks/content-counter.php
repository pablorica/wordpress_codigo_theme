<?php
/**
 * Block Name: Counter
 *
 * This is the template that displays the counter block.
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

$style 					= get_field('bcounter_style');
$style['section_style'] = "";
$style['section_data']  = "";

$block_menucolor = (get_field('bcounter_menu_color')?get_field('bcounter_menu_color'):'bg-green');


$htmlBack   = '';
$htmlStyle  = '';
$background = get_field('bcounter_background'); 
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
$block_body = get_field('bcounter_content');
$block_body['groupname'] = 'bcounter_content';
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
<div class="row gblock__counter_body--text my-auto '.$animation_css.'">
	<div id="'.$style['content_id'].'" class="gblock__counter_body--content d-flex flex-column '.$style['content_class'].' " '.($block_body['colour'] ? 'style="color:'.$block_body['colour'].'"' : '').' >';

	if($block_body['headline']) {
		$htmlBody .= '<div class="gblock__counter_body--headline py-5">'.$block_body['headline'].'</div>';
	}

    $htmlBody .= '
		<div class="gblock__counter_body--number my-auto py-5">
            '.($block_body['prepend'] ?  '<span class="gblock__counter_body--prepend">'.$block_body['prepend'].'</span>' : '').'<span class="gblock__counter_body--digits counter-trigger" data-end="'.$block_body['end'].'">'.$block_body['start'].'</span>'.($block_body['append'] ?  '<span class="gblock__counter_body--append">'.$block_body['append'].'</span>' : '').'
        </div>';

	if($block_body['footer']) {
		$htmlBody .= '
		<div class="gblock__counter_body--footer py-5">'.$block_body['footer'].'</div>';
	}
    $htmlBody .= '
    </div>';

    

	$htmlBody .= '
</div>';


echo '
<section id="'.$style['section_id'].'" class="section '.$style['section_class'].' " style="'.$style['section_style'].'" data-menucolor="'.$block_menucolor.'" '.$style['section_data'].' >
'.$htmlBack.'
  <div id="'.$style['block_id'].'" class="gblock__counter container-fluid '.$style['block_class'].' d-flex flex-column">
	'.$htmlBody.'
  </div>
</section>'
.$htmlStyle;