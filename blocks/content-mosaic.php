<?php
/**
 * Block Name: Mosaic
 *
 * This is the template that displays the mosaic block.
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
if( have_rows('block_mosaic')) {
	$cint 		   = 0;
	$section_id    =  (get_field('block_mosaic_section_id') ? 'id="'.get_field('block_mosaic_section_id').'"' : '');
	$section_class =  (get_field('block_mosaic_section_class') ? 'class="'.get_field('block_mosaic_section_class').'"' : '');
	$block_id      =  (get_field('block_mosaic_id') ? 'id="'.get_field('block_mosaic_id').'"' : '');
	$block_class   = 'class="gblock gblock__mosaic '. (get_field('block_mosaic_class') ? get_field('block_mosaic_class') : '') .'"';



	$html = '
	<section '.$section_id.' '.$section_class.'>
		<div '.$block_id.' '.$block_class.' >
			<div class="container p-0">';

	if(get_field('block_mosaic_title')) {
		$html .= '
				<div class="row">
					<div class="col-12">
						<div class="intro-mosaic intro-title main-separator">
							<h2>'.get_field('block_mosaic_title').'</h2>
						</div>
					</div>
				</div>';

	}



	while ( have_rows('block_mosaic') ) : the_row();


		if( get_row_layout() == 'one_column' ) {

			$html .= '
				<div class="row mosaic_'.get_row_layout().'">
					<div class="col-12">';

        	$cta_html 	 = '';
        	$image_class = 'attachment-medium_large size-medium_large';
			if(get_sub_field('show_cta')) {
				$cta=get_sub_field('cta');
				if($cta['type'] == "link") {
					$link 	   = $cta['link'];
					$cta_html .= '<a style="color:'.$cta['color'].'; background-color:'.$cta['background_color'].'; border-color:'.$cta['background_color'].'" class="btn-donate btn" role="button" href="'.$link['url'].'" target="'.$link['target'].'">'.$link['title'].'</a>';
					
				}
				if($cta['type'] == "form") {
					$cta_html .= '<div style="--color:'.$cta['color'].'; --bgcolor:'.$cta['background_color'].'; --pcolor:'.get_sub_field('color').';" class="wpcf7-wrapper">'.do_shortcode($cta['form']).'</div>';
				}
				$image_class .= " hide-onclick";
			}


			if($image_field=get_sub_field('image')) {
				$image=wp_get_attachment_image($image_field['ID'], 'medium_large', '' , ['class' => $image_class]);
					$html .= '<div class="mosaic-section mosaic-box keep_prop" data-ratiow="1367" data-ratioh="460" data-ratiomw="335" data-ratiomh="220">'. $image . $cta_html .'</div>';
			} else {
				$html .= '
						<div class="mosaic-cta mosaic-box d-flex justify-content-center align-items-center" style="color:'.get_sub_field('color').'; background-color:'.get_sub_field('background_color').'">
							<div class="cta-content text-center">
								<p style="color:'.get_sub_field('color').';">'.get_sub_field('headline').'</p>
								'.$cta_html .'
							</div>
						</div>';
			}

			$html .= '
					</div>
				</div>';

        }

        if( get_row_layout() == 'two-column' ) {


        	switch (get_sub_field('type')) {
			    case 'big-right':
		        	$col_left    = 'col-md-4';
		        	$ratio_left  = 'data-ratiow="499" data-ratioh="460" data-ratiomw="335" data-ratiomh="220"';
		        	$col_right   = 'col-md-8';
		        	$ratio_right = 'data-ratiow="1018" data-ratioh="460" data-ratiomw="335" data-ratiomh="220"';
		        break;
			    case 'big-left':
		        	$col_left    = 'col-md-8';
		        	$ratio_left  = 'data-ratiow="1018" data-ratioh="460" data-ratiomw="335" data-ratiomh="220"';
		        	$col_right   = 'col-md-4';
		        	$ratio_right = 'data-ratiow="499" data-ratioh="460" data-ratiomw="335" data-ratiomh="220"';
		        break;
			    default:
			        $col_left    = 'col-md-6';
			        $ratio_left  = 'data-ratiow="674" data-ratioh="460" data-ratiomw="335" data-ratiomh="220"';
			        $col_right   = 'col-md-6';
			        $ratio_right = 'data-ratiow="674" data-ratioh="460" data-ratiomw="335" data-ratiomh="220"';
			}


			$left = get_sub_field('left_column');

			$cta_left_html 	  = '';
			$left_image_class = 'attachment-medium_large size-medium_large';
			if($left['show_cta']) {
				$cta=$left['cta'];
				if($cta['type'] == "link") {
					$link 			= $cta['link'];
					$cta_left_html .= '<a style="color:'.$cta['color'].'; background-color:'.$cta['background_color'].'; border-color:'.$cta['background_color'].'" class="btn-donate btn" role="button" href="'.$link['url'].'" target="'.$link['target'].'">'.$link['title'].'</a>';
					
				}
				if($cta['type'] == "form") {
					$cta_left_html .= '<div style="--color:'.$cta['color'].'; --bgcolor:'.$cta['background_color'].'; --pcolor:'.$left['color'].';" class="wpcf7-wrapper">'.do_shortcode($cta['form']).'</div>';
				}
				$left_image_class .= " hide-onclick";
			}

			$left_html = '';
			if($image_field = $left['image']) {
				//$image=wp_get_attachment_image($image_field['ID'], 'medium_large');
				$image=wp_get_attachment_image($image_field['ID'], 'medium_large', '' , ['class' => $left_image_class]);
				$left_html .= '<div class="mosaic-section mosaic-box keep_prop" '.$ratio_left.'>'.$image . $cta_left_html . '</div>';
			} else {
				$left_html .= '
						<div class="mosaic-cta mosaic-box d-flex justify-content-center align-items-center" style="color:'.$left['color'].'; background-color:'.$left['background_color'].'">
							<div class="cta-content text-center">
								<p style="color:'.$left['color'].';">'.$left['headline'].'</p>
								'.$cta_left_html .'
							</div>
						</div>';
			}

			$right = get_sub_field('right_column');

			$cta_right_html    = '';
			$right_image_class = 'attachment-medium_large size-medium_large';
			if($right['show_cta']) {
				$cta=$right['cta'];
				if($cta['type'] == "link") {
					$link 			 = $cta['link'];
					$cta_right_html .= '<a style="color:'.$cta['color'].'; background-color:'.$cta['background_color'].'; border-color:'.$cta['background_color'].'" class="btn-donate btn" role="button" href="'.$link['url'].'" target="'.$link['target'].'">'.$link['title'].'</a>';
					
				}
				if($cta['type'] == "form") {
					$cta_right_html .= '<div style="--color:'.$cta['color'].'; --bgcolor:'.$cta['background_color'].'; --pcolor:'.$right['color'].';" class="wpcf7-wrapper">'.do_shortcode($cta['form']).'</div>';
				}
				$right_image_class .= " hide-onclick";
			}

			$right_html = '';
			if($image_field = $right['image']) {
				//$image=wp_get_attachment_image($image_field['ID'], 'medium_large');
				$image=wp_get_attachment_image($image_field['ID'], 'medium_large', '' , ['class' => $right_image_class]);
				$right_html .= '<div class="mosaic-section mosaic-box keep_prop" '.$ratio_right.'>'.$image . $cta_right_html . '</div>';
			}
			else {
				$right_html .= '
						<div class="mosaic-cta mosaic-box d-flex justify-content-center align-items-center" style="color:'.$right['color'].'; background-color:'.$right['background_color'].'">
							<div class="cta-content text-center">
								<p style="color:'.$right['color'].';">'.$right['headline'].'</p>
								'.$cta_right_html .'
							</div>
						</div>';
			}

        	$html.='
        		<div class="row mosaic_'.get_row_layout().'">
					<div class="'. $col_left.'">
						'.$left_html.'
					</div>
					<div class="'. $col_right.'">
						'.$right_html.'
					</div>
				</div>';
        }


	endwhile;

	$html .= '</div>
		</div>
	</section>';

	echo $html;
}
?>
