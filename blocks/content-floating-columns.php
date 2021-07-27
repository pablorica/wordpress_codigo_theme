<?php
/**
 * Block Name: Floating Columns
 *
 * This is the template that displays the floating columns block.
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

$style 					= get_field('bfloatcolumns_style');
$style['section_style'] = "";
$style['section_data']  = "";


$htmlBack   = '';
$htmlStyle  = '';

if($style['section_id']){
    $cssIndex = "#".$style['section_id'];
} else {
    $randomClass = 'section_'.substr(str_shuffle(MD5(microtime())), 0, 5);
    $style['section_class'] .= " ".$randomClass;
    $cssIndex = ".".$randomClass;
}

$background = get_field('bfloatcolumns_background'); 
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

		
		
		$htmlStyle .= '
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
                '.$cssIndex.' floating-column{
					display:none;
				}
			}';

		$style['section_class'] .= " d-flex hv-100";
	}

    $htmlStyle .= '
			@media screen and (max-width: 991px) {
                '.$cssIndex.' .floating-column{
					display:none;
				}
			}';
}
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
	<div class="fp-bg fp-bg--video fp-bg--video-floatcolumns">
		<?php //get_template_part( 'global-templates/component', 'video' ); ?>
		<?php include(locate_template('global-templates/component-video.php')); ?>
		
	</div>
	<?php
	$htmlBack .= ob_get_contents();
	ob_end_clean();

	$style['section_class'] .= " d-flex hv-100";

    $htmlStyle .= '
			@media screen and (max-width: 991px) {
                '.$cssIndex.' .floating-column {
					display:none;
				}
			}';
}

$block_menucolor = (get_field('bfloatcolumns_menu_color') ? 'data-menucolor="'.get_field('bfloatcolumns_menu_color').'"':'');


$columnsBody  = array();
$columnsID    = array();
$columnsClass = array();

$columns = get_field('bfloatcolumns_columns');
if( $columns ) {
    $cint = 1;
    foreach( $columns as $column ) {
        if(!$column['id']) {
            $column['id'] = 'column_'.substr(str_shuffle(MD5(microtime())), 0, 5);
        }
        $column['classname'] = 'floatcolumns';
        $column['number']    = $cint;
        $column['animation'] = get_field('bfloatcolumns_animation');
        $columnsBody[]       = my_acf_block_column($column);
        $columnsID[]         = $column['id'];
        $columnsClass[]      = $column['class'];
        $cint++;
    }
}

$htmlBody = '';
$speed = 1;
foreach( $columnsBody as $key => $columnBody ) {
    $htmlBody .= '
        <div id="'.$columnsID[$key].'" class="floating-column col-lg '.$columnsClass[$key].' rellax" data-rellax-speed="'.$speed.'" >
            '.$columnBody.'
        </div>';
    $speed++;
}



echo '
<section id="'.$style['section_id'].'" class="section section__floatcolumns '.$style['section_class'].' " style="'.$style['section_style'].'" '.$style['section_data'].' '.$block_menucolor.' >
'.$htmlBack.'
  <div id="'.$style['block_id'].'" class="gblock__floatcolumns container-fluid  '.$style['block_class'].'">
    <div id="'.$style['content_id'].'" class="row '.$style['content_class'].'"  >
    '.$htmlBody.'
    </div>
  </div>
</section>
<style>'.$htmlStyle.'</style>';

?>

