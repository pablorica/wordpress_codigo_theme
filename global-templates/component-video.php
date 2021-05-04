<?php
/**
 * Partial template for video content
 *
 * @package codigo
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;


if (!function_exists('get_field')) {
	exit; // Exit if ACF is not enabled 
}

if(!isset($type)) 		global $type;
if(!isset($video_url)) 	global $video_url;
if(!isset($video_file)) global $video_file;
if(!isset($args)) 		global $args;

//error_log($type);

if($type == 'videofile') {

	if(is_array($video_file['desktop'])) {
		$desktop = $video_file['desktop'];
	}
	if(is_array($video_file['mobile'])) {
		$mobile = $video_file['mobile'];
	}

	$mposter = '';
	if($poster_id=$mobile['poster'])  {
		$mposter = 'poster="'.wp_get_attachment_image_src($poster_id, 'medium_large')[0].'"';
	}

	$dposter = '';
	if($poster_id=$desktop['poster'])  {
		$dposter = 'poster="'.wp_get_attachment_image_src($poster_id, 'medium_large')[0].'"';
	}

	if(!$mposter) $mposter = $dposter;
	if(!$dposter) $dposter = $mposter;

	$controls  = ($args['controls'] ? true : false);
	$rcontrols = ($args['remote_controls'] ? true : false);
	$autoplay  = ($args['autoplay'] ? 'playsinline autoplay' : '');
	$loop 	   = ($args['loop'] ? 'loop' : '');
	$muted 	   = ($args['muted'] ? 'muted' : '');



	if($mobile['mp4'] || $mobile['ogg'] || $mobile['webm']) {
		$mmp4  = ($mobile['mp4'] ? '<source src="'.$mobile['mp4'].'" type="video/mp4">' : '');
		$mogg  = ($mobile['ogg'] ? '<source src="'.$mobile['ogg'].'" type="video/ogg">' : '');
		$mwebm = ($mobile['webm'] ? '<source src="'.$mobile['webm'].'" type="video/webm">' : '');

		$dmp4  = $mmp4;
		$dogg  = $mogg;
		$dwebm = $mwebm;
	}

	if($desktop['mp4'] || $desktop['ogg'] || $desktop['webm']) {
		$dmp4  = ($desktop['mp4'] ? '<source src="'.$desktop['mp4'].'" type="video/mp4">' : '');
		$dogg  = ($desktop['ogg'] ? '<source src="'.$desktop['ogg'].'" type="video/ogg">' : '');
		$dwebm = ($desktop['webm'] ? '<source src="'.$desktop['webm'].'" type="video/webm">' : '');

		if(!$mmp4)   $mmp4 = $dmp4;
		if(!$mogg)   $mogg = $dogg;
		if(!$mwebm) $mwebm = $dwebm;
	}

	//$mmp4 = $dmp4 ="https://www.w3schools.com/html/mov_bbb.mp4";
	//$mogg = $dogg ="https://www.w3schools.com/html/mov_bbb.ogg";
	/*
	<div style="text-align:center"> 
	  <video id="video1" style="width:600px;max-width:100%;" controls="">
	    <source src="https://www.w3schools.com/html/mov_bbb.mp4" type="video/mp4">
	    <source src="https://www.w3schools.com/html/mov_bbb.ogg" type="video/ogg">
	    Your browser does not support HTML5 video.
	  </video>
	</div>
	/**/

	$cssControls  = '';
	$htmlControls = '';
	if($controls) { 
		$mobileControls = '<button class="mobile_button video_control paused"></button>';
		$desktopControls = '<button class="desktop_button video_control paused"></button>';
		$cssControls  = 'controlled';
	}
	if($rcontrols) { 
		$cssControls  = 'controlled';
	}

	$htmlVideo ='<div class=" component_video d-sm-none m-auto '.$cssControls.'">
		'.$mobileControls.'
		<video '.$mposter.' '.$autoplay.' '.$loop.' '.$muted.' class="mobile_video">
		'.$mmp4.'
		'.$mogg.'
		'.$mwebm.'
		Your browser does not support the video tag.
		</video>
	</div>';

	$htmlVideo .='<div class="component_video d-none d-sm-block m-auto '.$cssControls.'">
		'.$desktopControls.'
		<video '.$dposter.' '.$autoplay.' '.$loop.' '.$muted.' class="desktop_video">
		'.$dmp4.'
		'.$dogg.'
		'.$dwebm.'
		Your browser does not support the video tag.
		</video>
	</div>';
}
if($type == 'videourl') {
	$htmlVideo = '<div class="component_video">'.
	codigo_display_html_video($video_url).
	'</div>';
}
?>

<?php echo $htmlVideo; ?>