<?php
/**
 * Block Name: Carousel
 *
 * This is the template that displays the carousel block.
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

global $post;
// only edit specific post types
$types = array( 'post' ,'casestudy');
$htmlScapeOpen  = '';
$htmlScapeClose = '';
if ( $post &&  !is_archive() && in_array( $post->post_type, $types, true ) ) {
	$htmlScapeOpen  = '</div>';
	$htmlScapeClose = '<div class="container single-container">';
}

$style 					= get_field('bcarousel_style');
$style['section_style'] = "";
$fade_animation         = 'fade_in_up';

$htmlBack   = '';
$htmlStyle  = '';
$block_body = get_field('bcarousel_body');
$background = get_field('bcarousel_background'); 
if($background['type'] == 'color') {
	$style['section_style'] .= " background-color:".$background['background_colour'].";";
    
	if($background['fullscreen']){
        $style['section_class'] .= " d-flex hv-100";
	} else {
        if(!$block_body['full'] ) {
            $style['section_class'] .= " py-5";
        }
    }
}
if($background['type'] == 'image') {

    $style['section_class'] .= " d-flex hv-100";

	if(!$background['mobile_image']) $background['mobile_image'] = $background['desktop_image'];
    if(!$background['desktop_image']) $background['desktop_image'] = $background['mobile_image'];


	if($background['parallax']){

		
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

		
		if($style['section_id']){
			$cssIndex = "#".$style['section_id'];
		} else {
			$randomClass = 'section_'.substr(str_shuffle(MD5(microtime())), 0, 5);
			$style['section_class'] .= " ".$randomClass;
			$cssIndex = ".".$randomClass;
		}
		$htmlStyle .= '
		<style>
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
			}
		</style>';
	}
}


$htmlBody   = '';

$block_body['groupname'] = 'bcarousel_body';
$block_animation = $block_body['animation'];

if($block_body['full'] ) {
    $carousel_wrapper  = 'w-100';
} else {
    $carousel_wrapper  = 'container';
    $style['block_class'] .= 'd-flex flex-column';
}

$carousel_autoplay = ($block_body['autoplay'] ? 1: 0);
$carousel_arrows   = ($block_body['arrows'] ? 1: 0);
$carousel_navdots  = ($block_body['dots'] ? 1: 0);
$carousel_class    = ($block_body['show_sides'] ? 'show_sides': '');


if( have_rows($block_body['groupname'])): while ( have_rows($block_body['groupname']) ) : the_row(); 
    $htmlBody .= '
    <div class="gblock__carousel my-auto">
        <div class="'.$carousel_wrapper.' m-auto slick-carousel-wrapper '.($block_animation ? 'animate-children '.$fade_animation : '').'">
            <div class="slick-carousel dots-color-'.$block_body['dots_colour'].' '.$carousel_class.'" data-autoplay='.$carousel_autoplay.' data-arrows='.$carousel_arrows.' data-dots='.$carousel_navdots.'>';

    if( have_rows('slides') ){ while ( have_rows('slides') ) { the_row(); 


        $slideStyle  = '';
        $slideImage  = '';
        $slideHeight = ($block_body['full'] ? 'hv-100': '');
        if(get_sub_field('background_colour')) {
            $slideStyle .= " background-color:".get_sub_field('background_colour').";";
        }
        if(get_sub_field('type') == 'color') {
            $slideImage.= '
                    <figure class="figure-empty '.$slideHeight.'"></figure>';
        }
        if(get_sub_field('type') == 'image') {

            $mobile_image_field  = get_sub_field('mobile_image');
            $desktop_image_field = get_sub_field('desktop_image');

            if(!$mobile_image_field) $mobile_image_field = $desktop_image_field;
            if(!$desktop_image_field) $desktop_image_field = $mobile_image_field;

            $mobile_image  = wp_get_attachment_image($mobile_image_field, 'large', false);
            $desktop_image = wp_get_attachment_image($desktop_image_field, 'full', false);

            $slideImage.= '

                    <figure class="d-sm-none '.$slideHeight.'">'. $mobile_image. '</figure>
                    <figure class="d-none d-sm-block '.$slideHeight.'">'. $desktop_image. '</figure>';
        }

        //error_log('$background: ');
        //error_log(print_r($background,true));

        
        $slideContentStyle = '';
        if(get_sub_field('vposition') == 'v-50'){
            $slideContentStyle = 'top: 50%;height: 50%;';
        }

        $htmlBody .= '
                <div class="'.$slideHeight.'" style="'.$slideStyle.'">
                '.$slideImage.'
                    <div class="gblock__carousel_slide  d-flex flex-column col-md-12" style="'.$slideContentStyle.'" >';

                    $headline =  get_sub_field('headline');
                    $quote    =  get_sub_field('quote_content');

                        if($headline || $quote ) {
                            $headtag    =  'div';
                            $headclass  = '';
                            $headStyle  = '';
                            if(get_sub_field('vposition') == 'middle'){
                                $headStyle = 'margin-top:auto; margin-bottom:auto;';
                            }
                            if(get_sub_field('vposition') == 'top'){
                                $headStyle = 'margin-top:15px;';
                            }
                            $headStyle .= 'color:'.get_sub_field('content_color').';';
                            
                            $slide_cite = '';
                            if(get_sub_field('quote')) {
                                $headtag    =  'blockquote';
                                $headclass .= ' h2 mb-5';
                                $headline = "<p>".$quote."</p>";
                                if(get_sub_field('cite')) {
                                    $slide_cite = '<cite style="color:'.get_sub_field('content_color').'">'.get_sub_field('cite').'</cite>';
                                }
                            }
                            $htmlBody .= '<'.$headtag.' class=" gblock__carousel_slide--htext '.$headclass.'" style="'.$headStyle.'">'.$headline.'</'.$headtag.'>'.$slide_cite;
                        }
                        

                        $slideCTA = '';
                        if( have_rows('ctas') ){ while ( have_rows('ctas') ) { the_row(); 

                            $cta_download = (get_sub_field('download') ? 'download': false);
                            $cta_color    = get_sub_field('color');
                            $cta_bg       = get_sub_field('bgcolor') ? get_sub_field('bgcolor') : 'transparent';
                            $cta          = get_sub_field('link');

                            $cta_class    = "btn btn-primary btn-cta".($cta_download ?  ' btn-download': '');

                            //error_log(print_r($cta,true));


                            if(is_array($cta) && ( isset($cta['title']) && isset($cta['url']) )  ){
                                $slideCTA .= '
                                    <a class="'.$cta_class.'" role="button" href="'.$cta['url'].'" target="'.$cta['target'].'" style="color:'.$cta_color.'; border-color:'.$cta_color.'; background-color:'.$cta_bg.';" '.$cta_download.'>'.$cta['title'].'</a>';
                            }
                        }}
                        if($slideCTA != '')  {
                            $ctasClass = get_sub_field('ctas_position') ? get_sub_field('ctas_position') : 'text-left';
                            $slideCTA = '<div class="gblock__carousel_slide--ctas '.$ctasClass.'">'.$slideCTA .'</div>';
                        }

                        $htmlContent = (get_sub_field('content') ? get_sub_field('content') : '');

                        if($htmlContent || $slideCTA ) {  
                            $htmlContentClass ='';
                            if(get_sub_field('vposition') == 'middle'){
                                $htmlContentClass = 'gblock__carousel_slide--ctext-absolute col-12';
                            }
                            $htmlBody .= '
                            <div class="gblock__carousel_slide--ctext '.$htmlContentClass.'" style="color:'.get_sub_field('content_color').'">
                                '.$htmlContent.'
                                '.$slideCTA.'
                            </div>';
                        }

                        $htmlBody .= '
                    </div>
                </div>  <!-- slide -->';
    }}

    $htmlBody .= '</div>
        </div>
    </div>';
endwhile; endif;




echo $htmlScapeOpen.'
<section id="'.$style['section_id'].'" class="section section__carousel '.$style['section_class'].' " style="'.$style['section_style'].'" >
'.$htmlBack.'
  <div id="'.$style['block_id'].'" class="gblock__carousel--wrapper container-fluid  p-0 '.$style['block_class'].'">
	'.$htmlBody.'
  </div>
</section>'
.$htmlStyle.$htmlScapeClose;
?>

