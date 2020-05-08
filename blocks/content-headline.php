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

$section_id    =  (get_field('block_headline_section_id') ? 'id="'.get_field('block_headline_section_id').'"' : '');
$section_class =  (get_field('block_headline_section_class') ? 'class="'.get_field('block_headline_section_class').'"' : ''); 
$block_id      =  (get_field('block_headline_id') ? 'id="'.get_field('block_headline_id').'"' : '');
$block_class   = 'class="gblock gblock__headline '. (get_field('block_headline_class') ? get_field('block_headline_class') : '') .'"';


echo '
<section '.$section_id.' '.$section_class.'>
  <div class="container">
	<div '.$block_id.' '.$block_class.' >
		<p>'.get_field('block_headline').'</p>
	</div>
  </div>
</section>';
?>



