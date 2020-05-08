<?php
/**
 * Block Name: formatted
 *
 * This is the template that displays the formatted block.
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

$section_id    =  (get_field('block_formatted_section_id') ? get_field('block_formatted_section_id') : 'formatted_'.get_the_id());
$section_class =  (get_field('block_formatted_section_class') ? 'class="'.get_field('block_formatted_section_class').'"' : '')." ".(get_field('block_formatted_bgcolor') ? 'style="background-color:'.get_field('block_formatted_bgcolor').'" ' : '');  

$block_id      =  (get_field('block_formatted_id') ? 'id="'.get_field('block_formatted_id').'"' : '');
$block_class   = 'class="gblock gblock__formatted '. (get_field('block_formatted_class') ? get_field('block_formatted_class') : '') .' '. (get_field('block_formatted_textsize') ? get_field('block_formatted_textsize') : '') .' "';


$html = '';

if(get_field('block_formatted_bgcolor')) {
	$html .= '
<style>
section#'.$section_id .' {
	position:relative;
}
section#'.$section_id .'::before,
section#'.$section_id .'::after {
	content:"";
	position:absolute;
	top:0;
	left:-100%;
	width:100%;
	height:100%;
	background-color:'.get_field('block_formatted_bgcolor').'
}
section#'.$section_id .'::after {
	left:100%;
}
</style>';
}

$html .= '
<section id="'.$section_id.'" '.$section_class.'>
  <div class="container">
	<div '.$block_id.' '.$block_class.' >';

if(get_field('block_formatted_type') == 'one') {
	$column = get_field('block_formatted_one_column');
	$html .= '
	<div class="row formatted_one-column">
		<div class="col-12">
			'.apply_filters('the_content', $column['content']).'
		</div>
	</div>';
}

if(get_field('block_formatted_type') == 'two') {
	$column = get_field('block_formatted_two_column');
	$html .= '
	<div class="row formatted_two-column">
		<div class="col-lg-4">
			'.apply_filters('the_content', $column['content_left']).'
		</div>
		<div class="col-lg-6 pl-lg-3">
			'.apply_filters('the_content', $column['content_right']).'
		</div>
	</div>';
}


$html .= '
	</div>
  </div>
</section>';

echo $html;
?>



