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

$block_menucolor = (get_field('btwocolumns_menu_color') ? 'data-menucolor="'.get_field('btwocolumns_menu_color').'"':'');

if(get_field('btwocolumns_animation')) {
  $style['section_class'].= ' animated';
}

$col = 'container-md';
if(get_field('btwocolumns_fullwidth')) {
  $col = 'container-fluid';
}

$left = get_field('btwocolumns_left'); 
$left['classname'] = 'twocolumns-left';
$left['groupname'] = 'btwocolumns_left';
$left['animation'] = get_field('btwocolumns_animation'); 
$htmlLeft = my_acf_block_column( $left );
$block_class_left = $left['content_class'];
if(!$htmlLeft) {
	$block_class_left .= ' d-none d-md-block ';
}

$right = get_field('btwocolumns_right'); 
$right['classname'] = 'twocolumns-right';
$right['groupname'] = 'btwocolumns_right';
$right['animation'] = get_field('btwocolumns_animation'); 
$htmlRight= my_acf_block_column( $right );
$block_class_right = $right['content_class'];
if(!$htmlRight) {
	$block_class_right .= ' d-none d-md-block ';
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



echo '
<section id="'.$style['section_id'].'" class="section '.$style['section_class'].'" style="'.$block_color.$block_bgcolor.'" '.$block_menucolor.' >
'.$htmlBack.'
  <div class="'.$col.'">
    <div id="'.$style['block_id'].'" class="'.$style['block_class'].' gblock gblock__btwocolumns" >
      <div class="row" >
        <div id="'.$left['content_id'].'" class="column-wrapper col-lg-6 '.$block_class_left.'" >
            '.$htmlLeft.'
        </div>
        <div id="'.$right['content_id'].'" class="column-wrapper col-lg-6 '.$block_class_right.'" >
            '.$htmlRight.'
        </div>
    </div>
	</div>
  </div>
</section>';
?>


