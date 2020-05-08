<?php
/**
 * Block Name: Accordion
 *
 * This is the template that displays the accordion block.
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

$html='';
if( have_rows('block_accordion')) {

	$section_id    =  (get_field('block_accordion_section_id') ? 'id="'.get_field('block_accordion_section_id').'"' : '');
	$section_class =  (get_field('block_accordion_section_class') ? 'class="'.get_field('block_accordion_section_class').'"' : ''); 
	$block_id      =  (get_field('block_accordion_id') ? 'id="'.get_field('block_accordion_id').'"' : '');
	$block_class   = 'class="gblock gblock__accordion '. (get_field('block_accordion_class') ? get_field('block_accordion_class') : '') .'"';


	$html .= '
	<section '.$section_id.' '.$section_class.'>
		<div '.$block_id.' '.$block_class.' >
			<div class="container p-0">';	

	if(get_field('block_accordion_title')) {
		$html .= '
				<div class="row">
					<div class="col-12">
						<div class="intro-articles-row intro-title main-separator">
							<h2>'.get_field('block_accordion_title').'</h2>
						</div>
					</div>
				</div>';	

	}
	$html .= '
				<div class="row">
					<div class="col-12">';	

	$aint = 0;
	while ( have_rows('block_accordion') ) : the_row(); 
		$html .= '
						<button class="accordion__button collapsed" type="button" data-toggle="collapse" data-target="#accordion'.$aint.'" aria-expanded="false" aria-controls="accordion'.$aint.'">'.get_sub_field('header').'</button>

						<div class="accordion__panel collapse" id="accordion'.$aint.'">
						  <div class="card card-body">
						    '.get_sub_field('body').'
						  </div>
						</div>';	
		$aint++;
	endwhile;

	$html .= '
					</div>
				</div>';

	$html .= '
			</div>
		</div>
	</section>';	

	echo $html;
}

?>

