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

$section_id    =  (get_field('btwocolumns_section_id') ? 'id="'.get_field('btwocolumns_section_id').'"' : '');
$section_class =  (get_field('btwocolumns_section_class') ? get_field('btwocolumns_section_class') : ''); 
$block_id      =  (get_field('btwocolumns_id') ? 'id="'.get_field('btwocolumns_id').'"' : '');
$block_class   =  (get_field('btwocolumns_class') ? get_field('btwocolumns_class') : '');


$block_color = (get_field('btwocolumns_color') ? get_field('btwocolumns_color') : '#FFFFFF'); 


$left = get_field('btwocolumns_left'); 
$left['classname'] = 'twocolumns-left';
$htmlLeft = my_acf_block_column( $left );
$block_class_left = '';
if(!$htmlLeft) {
	$block_class_left .= ' d-none d-md-block ';
}

$right = get_field('btwocolumns_right'); 
$right['classname'] = 'twocolumns-right';
$htmlRight= my_acf_block_column( $right );
$block_class_right = '';
if(!$htmlRight) {
	$block_class_right .= ' d-none d-md-block ';
}

echo '
<section '.$section_id.' class="section '.$section_class.'" data-color="'.$block_color.'">
  <div class="container-fluid h-100 d-flex flex-column">
    <div '.$block_id.' class="gblock gblock__btwocolumns my-auto text-center '.$block_class.'" >
      <div class="row" >
        <div class="col-md-6 my-auto py-3 text-center '.$block_class_left.'" >
            '.$htmlLeft.'
        </div>
        <div class="col-md-6 my-auto py-3 text-center '.$block_class_right.'" >
            '.$htmlRight.'
        </div>
    </div>
	</div>
  </div>
</section>';
?>

