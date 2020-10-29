<?php
/**
 * Block Name: One Column
 *
 * This is the template that displays the one column block.
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

$section_id    =  (get_field('bonecolumn_section_id') ? 'id="'.get_field('bonecolumn_section_id').'"' : '');
$section_class =  (get_field('bonecolumn_section_class') ? get_field('bonecolumn_section_class') : ''); 
$block_id      =  (get_field('bonecolumn_id') ? 'id="'.get_field('bonecolumn_id').'"' : '');
$block_class   =  (get_field('bonecolumn_class') ? get_field('bonecolumn_class') : '');


$block_color = (get_field('bonecolumn_color') ? get_field('bonecolumn_color') : '#FFFFFF'); 


$body = get_field('bonecolumn_body'); 
$body['classname'] = 'onecolumn';
$body['animation'] = get_field('bonecolumn_animation');

$htmlBody = my_acf_block_column( $body );
if(!$htmlBody) {
	$block_class .= ' d-none d-md-block ';
}

echo '
<section '.$section_id.' class="section '.$section_class.'" data-color="'.$block_color.'">
  <div class="container-fluid h-100 d-flex flex-column">
	<div '.$block_id.' class="gblock gblock__onecolumn py-3 my-auto text-center '.$block_class.'" >
			'.$htmlBody.'
	</div>
  </div>
</section>';
?>

