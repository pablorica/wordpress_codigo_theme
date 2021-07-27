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

global $post;
// only edit specific post types
$types = array( 'post' ,'casestudy');
$htmlScapeOpen  = '';
$htmlScapeClose = '';
if ( $post &&  !is_archive()  && in_array( $post->post_type, $types, true ) ) {
	$htmlScapeOpen  = '</div>';
	$htmlScapeClose = '<div class="container single-container">';
}


$style 					= get_field('bheadline_style');
$style['section_style'] = "";
$style['section_data']  = "";


$htmlBack   = '';
$htmlStyle  = '';
$background = get_field('bheadline_background'); 
if($background['type'] == 'color') {
	$style['section_style'] .= " background-color:".$background['background_colour'].";";

	if($background['fullscreen']){
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
				background-size: '.($background['background_size'] ? $background['background_size'] : 'cover' ).';
				background-color:'.$background['background_colour'].';
				background-position:center;
				background-repeat:no-repeat;
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
		'controls' => false,
		'autoplay' => $background['autoplay'],
		'loop' 	   => $background['loop'],
		'muted'    => $background['muted'],
	);
	if($background['controls']) { 
		$args['remote_controls'] = true;
	}
	//error_log('$type: '.$type);
	ob_start(); ?>
	<?php if($background['controls']) { ?>
			<button class="mobile_button d-sm-none video_control paused"></button>
			<button class="desktop_button d-none d-sm-block video_control paused"></button>
	<?php } ?>
	<div class="fp-bg fp-bg--video fp-bg--video-headline">
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
	$style['block_class'] = "my-auto ";
}

if(!$style['content_class']) {
	$style['content_class'] = "hv-75 ";
}




$htmlBody .= '
<div id="'.$style['content_id'].'" class="row gblock__headline_body--text '.$style['content_class'].' '.$animation_css.'">';

	if($block_body['content']) {
		$headtag    =  'div';
		$headclass  =  'my-auto text-center ';
		$block_cite = '';
		if($block_body['quote']) {
			$headtag    =  'blockquote';
			$headclass = 'text-left mt-auto mt-md-0 h2 mb-5';
			$block_body['content'] = "<p>".wp_strip_all_tags($block_body['content'])."</p>";
			if($block_body['cite']) {
				$block_cite = '<cite style="color:'.$block_body['content_color'].'">'.$block_body['cite']."</cite>";
			}
		}
		if($block_body['large_font']) {
			$headclass .= " large-font";
		}
		$htmlBody .= '
		<div class="gblock__headline_body--content  d-flex flex-column col-md-12"  >
			<'.$headtag.' class=" gblock__headline_body--htext '.$headclass.'" style="color:'.$block_body['content_color'].'">'.$block_body['content'].'</'.$headtag.' >
			'.$block_cite.'
		</div>';
	}
	

	$htmlCTA = '';
	if( have_rows($block_body['groupname'])): while ( have_rows($block_body['groupname']) ) : the_row(); 
		//error_log(print_r($block_body['ctas'],true));
		if( have_rows('ctas') ){ while ( have_rows('ctas') ) { the_row(); 

			$cta_download = (get_sub_field('download') ? 'download': false);
			$cta_color    = get_sub_field('color');
			$cta_bg       = get_sub_field('bgcolor');
			$cta          = get_sub_field('link');

			$cta_class    = "btn btn-primary btn-cta".($cta_download ?  ' btn-download': '');

			//error_log(print_r($cta,true));


			if(is_array($cta) && ( isset($cta['title']) && isset($cta['url']) )  ){
				$htmlCTA .= '
					<p>
					<a class="'.$cta_class.'" role="button" href="'.$cta['url'].'" target="'.$cta['target'].'" style="color:'.$cta_color.'; border-color:'.$cta_color.'; background-color:'.$cta_bg.';" '.$cta_download.'>'.$cta['title'].'</a>
					</p>';
			}
		}}
	endwhile; endif;

	if($htmlCTA) {
		$htmlBody .= '
		<div class="gblock__headline_body--ctas col-md-12 mt-auto mb-0 text-center" style="color:'.$block_body['content_color'].'">
		'.$htmlCTA.'
		</div>';
	}

	$htmlBody .= '
</div>';




echo $htmlScapeOpen.'
<section id="'.$style['section_id'].'" class="section section__headline gblock__headline '.$style['section_class'].' " style="'.$style['section_style'].'" '.$style['section_data'].' >
'.$htmlBack.'
  <div id="'.$style['block_id'].'" class="gblock__headline--wrapper container-fluid  '.$style['block_class'].' d-flex flex-column">
	'.$htmlBody.'
  </div>
</section>'
.$htmlStyle.$htmlScapeClose;

?>

