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

$style         = get_field('bonecolumn_style');
$block_color = (get_field('bonecolumn_color') ? get_field('bonecolumn_color') : '#122D28'); 
$block_bgcolor = (get_field('bonecolumn_bgcolor') ? get_field('bonecolumn_bgcolor') : '#FFFFFF'); 


$body = get_field('bonecolumn_body'); 
$body['classname'] = 'onecolumn';
$body['animation'] = get_field('bonecolumn_animation');

$htmlBody = my_acf_block_column( $body );

echo '
<section id="'.$style['section_id'].'" class="section '.$style['section_class'].'" style="color:'.$block_color .';background-color:'.$block_bgcolor.';">
  <div class="container-md">
	<div id="'.$style['block_id'].'"  class="'.$style['block_class'].' gblock gblock__onecolumn" >
	  <div class="row">
		<div id="'.$style['content_id'].'" class="col-12 '.$style['content_class'].'" >
		'.$htmlBody.'
		</div>
	  </div>
	</div>
  </div>
</section>';
?>

