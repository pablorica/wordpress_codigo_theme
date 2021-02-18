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

$style 					= get_field('bheadline_style');
$style['section_style'] = "";
$style['section_data']  = "";

$block_menucolor = (get_field('bheadline_menu_color')?get_field('bheadline_menu_color'):'bg-green');


$htmlBack   = '';
$htmlStyle  = '';
$background = get_field('bheadline_background'); 
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

	$style['section_class'] .= " d-flex hv-100";
}

$htmlBody   = '';
$block_body = get_field('bheadline_body');
$block_body['groupname'] = 'bheadline_body';
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
<div class="row gblock__headline_body--text '.$animation_css.'">
	<div id="'.$style['content_id'].'" class="gblock__headline_body--content '.$style['content_class'].' "  >';

	if($block_body['headline']) {
		$htag =  'h2';
		if (strpos($style['section_class'], 'section-header') !== false) {
			$htag = 'h1';
		}
		$htmlBody .= '<'.$htag.' class=" gblock__headline_body--htext" style="color:'.$block_body['head_color'].'">'.$block_body['headline'].'</'.$htag.'>';
	}
	if($block_body['content']) {
		$htmlBody .= '
		<div class="gblock__headline_body--ctext" style="color:'.$block_body['content_color'].'">
		'.$block_body['content'].'
		</div>';
	}

	if(get_field('bheadline_show_scroll_cta')) {
		$htmlBody .= '
		<button class="gblock__headline_scroll_cta '.($block_animation ? 'animate-single fade_in_up' : '').' moveScrollDown">
			'.get_field('bheadline_scroll_cta').'
		</button>';
	}

	$htmlBody .= '
	</div>
</div>';



/*
echo '
<section id="'.$style['section_id'].'" class="section '.$style['section_class'].' " style="'.$section_style.'" data-color="'.$block_color.'" data-bgcolor="'.($block_color_bg?  $block_color_bg : $block_color ).'" >
'.$htmlBack.'
	<div id="'.$style['block_id'].'" class="gblock gblock__headline container h-100 d-flex flex-column" >
		'.$htmlBody.'
  	</div><!-- /.container-->
</section> <!-- /section -->';
*/

/* */
echo '
<section id="'.$style['section_id'].'" class="section '.$style['section_class'].' " style="'.$style['section_style'].'" data-menucolor="'.$block_menucolor.'" '.$style['section_data'].' >
'.$htmlBack.'
  <div id="'.$style['block_id'].'" class="   gblock__headline--wrapper container-fluid '.$style['block_class'].' d-flex flex-column">
	'.$htmlBody.'
  </div>
</section>'
.$htmlStyle;
/**/
?>

