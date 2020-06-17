<?php
/**
 * Partial template for content in page.php
 *
 * @package understrap
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

if (!function_exists('get_field')) {
	exit; // Exit if ACF is not enabled 
}


global $type, $video_url, $video_file;

if($type == 'videofile') {

	$poster = '';
	if($poster_id=$video_file['poster'])  $poster = 'poster="'.wp_get_attachment_image($poster_id, 'medium_large').'"';

	$controls = ($video_file['controls'] ? 'controls' : '');
	$autoplay = ($video_file['autoplay'] ? 'autoplay' : '');
	$loop 	  = ($video_file['loop'] ? 'loop' : '');
	$muted 	  = ($video_file['muted'] ? 'muted' : '');

	$mp4  = ($video_file['mp4'] ? '<source src="'.$video_file['mp4'].'" type="video/mp4">' : '');
	$ogg  = ($video_file['ogg'] ? '<source src="'.$video_file['ogg'].'" type="video/ogg">' : '');
	$webm = ($video_file['webm'] ? '<source src="'.$video_file['webm'].'" type="video/webm">' : '');


	$htmlVideo ='
	 <video '.$controls.' '.$poster.' '.$autoplay.' '.$loop.' '.$muted.'>
	  '.$mp4.'
	  '.$ogg.'
	  '.$webm.'
	Your browser does not support the video tag.
	</video>';
}
if($type == 'videourl') {
	$htmlVideo = codigo_display_html_video($video_url); 
}
?>


<div class="component_video">
	<?php echo $htmlVideo; ?>
</div>
