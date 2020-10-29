<?php
/**
 * Block Name: Headline
 *
 * This is the template that displays the headline block.
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

$section_id    =  (get_field('bheadline_section_id') ? get_field('bheadline_section_id') : '');
$section_class =  'section-headline '.(get_field('bheadline_section_class') ? get_field('bheadline_section_class') : ''); 
$block_id      =  (get_field('bheadline_id') ? get_field('bheadline_id') : '');
$block_class   =  (get_field('bheadline_class') ? get_field('bheadline_class') : '');


$block_color     = (get_field('bheadline_color') ? get_field('bheadline_color') : '#FFFFFF'); 
$block_color_bg  = false;
$block_animation = get_field('bheadline_animation');



$htmlBack   = '';
$background = get_field('bheadline_background'); 
if($background['type'] == 'color') {
	$htmlBack .= '
	<div class="fp-bg" style="background-color:'.$background['background_colour'].'"></div>';
	$block_color_bg = $background['background_colour'];
}
if($background['type'] == 'image') {

	if(!$background['mobile_image']) $background['mobile_image'] = $background['desktop_image'];
    if(!$background['desktop_image']) $background['desktop_image'] = $background['mobile_image'];

	$mobile_image  = wp_get_attachment_image($background['mobile_image'], 'large', false, array('class'=>'d-md-none'));
	$desktop_image = wp_get_attachment_image($background['desktop_image'], 'full', false, array('class'=>'d-none d-md-block'));
	$htmlBack .= '
	<div class="fp-bg">
		<figure>
			'.$mobile_image.'
			'.$desktop_image.'
  		</figure> 
	</div>';
}

//error_log('$background: ');
//error_log(print_r($background,true));

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
}

$htmlBody   = '';
$block_body = get_field('bheadline_body');

$htmlBody .= '<div class="gblock__headline_body--text '.($block_animation ? 'animate-children fade_in_up' : '').'">';
	if($block_body['headline']) {
		$htmlBody .= '<div class="h1 gblock__headline_body--ctext" style="color:'.$block_color.'">'.$block_body['headline'].'</div>';
	}
	if($block_body['content']) {
		$htmlBody .= '<div class="gblock__headline_body--content row">
			<div class="col-md-6 mx-auto">
			'.$block_body['content'].'
			</div>
		</div>';
	}
	$htmlBody .= '</div>';



if(get_field('bheadline_show_scroll_cta')) {
	$htmlBody .= '
	<button class="gblock__headline_scroll_cta '.($block_animation ? 'animate-single fade_in_up' : '').' moveScrollDown">
		'.get_field('bheadline_scroll_cta').'
	</button>';
}







echo '
<section id="'.$section_id.'" class="section '.$section_class.'" data-color="'.$block_color.'" data-bgcolor="'.($block_color_bg?  $block_color_bg : $block_color ).'">
'.$htmlBack.'
  <div class="gblock__headline--wrapper container-fluid p-md-0 h-100 d-flex flex-column">
	<div id="'.$block_id.'" class="gblock gblock__headline my-auto text-center '.$block_class.'" >
			'.$htmlBody.'
	</div>
  </div>
</section>';
?>

