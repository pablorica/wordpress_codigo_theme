<?php
/**
 * Block Name: Two Columns
 *
 * This is the template that displays the two columns block.
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

$style         = get_field('btwocolumns_style');
$block_color   = (get_field('btwocolumns_color') ? 'color:'.get_field('btwocolumns_color').';' : ''); 
$block_bgcolor = (get_field('btwocolumns_bgcolor') ? 'background-color:'.get_field('btwocolumns_bgcolor').';' : ''); 

$block_menucolor = (get_field('btwocolumns_menu_color')?get_field('btwocolumns_menu_color'):'bg-green');

$left = get_field('btwocolumns_left'); 
$left['classname'] = 'twocolumns-left';
$left['groupname'] = 'btwocolumns_left';
$left['animation'] = get_field('btwocolumns_animation'); 
$htmlLeft = my_acf_block_column( $left );
//$block_class_left = $left['content_class'];
$block_class_left = '';
if(!$htmlLeft) {
	$block_class_left .= ' d-none d-md-block ';
}

$right = get_field('btwocolumns_right'); 
$right['classname'] = 'twocolumns-right';
$right['groupname'] = 'btwocolumns_right';
$right['animation'] = get_field('btwocolumns_animation'); 
$htmlRight= my_acf_block_column( $right );
//$block_class_right = $right['content_class'];
$block_class_right = '';
if(!$htmlRight) {
	$block_class_right .= ' d-none d-md-block ';
}

if(get_field('btwocolumns_animation')) {
  $style['section_class'].= ' animated';
}

$htmlBack = false;
$bodybackc = array();
if( $left['type'] == 'image' ) {
  $bodybackc['left'] = $left;
}
if( $left['column_custom'] != false ) {
  $bodybackc['left'] = $left;
}
if($right['type'] == 'image' ) {
  $bodybackc['right'] = $right;
}
if($right['column_custom'] != false ) {
  $bodybackc['right'] = $right;
}

foreach($bodybackc as $bodyback) {
  if($bodyback['type'] == 'image') {
    if(!$bodyback['mobile_image'])  $bodyback['mobile_image']  = $bodyback['desktop_image'];
    if(!$bodyback['desktop_image']) $bodyback['desktop_image'] = $bodyback['mobile_image'];

    $mobile_back  = wp_get_attachment_image($bodyback['mobile_image'], 'large', false, array('class'=>'d-md-none m-auto'));
    $desktop_back = wp_get_attachment_image($bodyback['desktop_image'], 'full', false, array('class'=>'d-none d-md-block m-auto'));
    $htmlBack .= '
    <div class="fp-bg fp-bg__'.$bodyback['classname'].' d-none d-md-block">
      <figure>
        '.$mobile_back.'
        '.$desktop_back.'
        </figure> 
    </div>';
  }
  if($bodyback['column_custom']) {
    $htmlBack .= '
    <div class="fp-bg fp-bg__'.$bodyback['classname'].' column-style d-none d-md-block" style="color:'.$bodyback['column_color'] .';background-color:'.$bodyback['column_bgcolor'].';"></div>';
  }
}



echo '
<section id="'.$style['section_id'].'" class="section '.$style['section_class'].'" style="'.$block_color.$block_bgcolor.'" data-menucolor="'.$block_menucolor.'" >
'.$htmlBack.'
  <div class="container-md">
    <div id="'.$style['block_id'].'" class="'.$style['block_class'].' gblock gblock__btwocolumns" >
      <div class="row" >
        <div id="'.$left['content_id'].'" class="column-wrapper col-md-6 '.$block_class_left.'" >
            '.$htmlLeft.'
        </div>
        <div id="'.$right['content_id'].'" class="column-wrapper col-md-6 '.$block_class_right.'" >
            '.$htmlRight.'
        </div>
    </div>
	</div>
  </div>
</section>';
?>


