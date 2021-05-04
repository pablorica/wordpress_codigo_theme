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


$htmlBody   = '';
$block_body = get_field('bcarousel_body');
$block_body['groupname'] = 'bcarousel_body';
$block_animation = $block_body['animation'];

$carousel_autoplay = ($block_body['autoplay'] ? 1: 0);
$carousel_arrows   = ($block_body['arrows'] ? 1: 0);
$carousel_navdots  = ($block_body['dots'] ? true: false);

if( have_rows($block_body['groupname'])): while ( have_rows($block_body['groupname']) ) : the_row(); 
    $htmlBody .= '
    <div class="gblock__carousel">
        <div class="w-100 m-auto slick-carousel-wrapper '.($block_animation ? 'animate-children '.$fade_animation : '').'">
            <div class="slick-carousel" data-autoplay='.$carousel_autoplay.' data-arrows=0 data-dots=0 >';

    if( have_rows('slides') ){ while ( have_rows('slides') ) { the_row(); 


        $slideStyle  = '';
        $slideImage  = '';
        if(get_sub_field('type') == 'color') {
            $slideStyle .= " background-color:".get_sub_field('background_colour').";";
        }
        if(get_sub_field('type') == 'image') {

            $mobile_image_field  = get_sub_field('mobile_image');
            $desktop_image_field = get_sub_field('desktop_image');

            if(!$mobile_image_field) $mobile_image_field = $desktop_image_field;
            if(!$desktop_image_field) $desktop_image_field = $mobile_image_field;

            $mobile_image  = wp_get_attachment_image($mobile_image_field, 'large', false);
            $desktop_image = wp_get_attachment_image($desktop_image_field, 'full', false);

            $slideImage.= '

                    <figure class="d-sm-none hv-100">'. $mobile_image. '</figure>
                    <figure class="d-none d-sm-block hv-100">'. $desktop_image. '</figure>';
        }

        //error_log('$background: ');
        //error_log(print_r($background,true));


        $htmlBody .= '
                <div class="hv-100" style="'.$slideStyle.'">
                '.$slideImage.'
                    <div class="gblock__carousel_slide hv-50 d-flex flex-column col-md-12 " >';

                        if(get_sub_field('headline')) {
                            $headtag    =  'h2';
                            $headclass  = '';
                            $headline   =  get_sub_field('headline');
                            $slide_cite = '';
                            if(get_sub_field('quote')) {
                                $headtag    =  'blockquote';
                                $headclass .= ' h2 mb-5';
                                $headline = "<p>".get_sub_field('headline')."</p>";
                                if(get_sub_field('cite')) {
                                    $slide_cite = '<cite style="color:'.get_sub_field('content_color').'">'.get_sub_field('cite').'</cite>';
                                }
                            }
                            $htmlBody .= '<'.$headtag.' class=" gblock__carousel_slide--htext '.$headclass.'" style="color:'.get_sub_field('content_color').'">'.$headline.'</'.$headtag.'>'.$slide_cite;
                        }
                        
                        

                        $slideCTA = '';
                        if( have_rows('ctas') ){ while ( have_rows('ctas') ) { the_row(); 

                            $cta_download = (get_sub_field('download') ? 'download': false);
                            $cta_color    = get_sub_field('color');
                            $cta_bg       = get_sub_field('bgcolor');
                            $cta          = get_sub_field('link');

                            $cta_class    = "btn btn-primary btn-cta".($cta_download ?  ' btn-download': '');

                            //error_log(print_r($cta,true));


                            if(is_array($cta) && ( isset($cta['title']) && isset($cta['url']) )  ){
                                $slideCTA .= '
                                    <a class="'.$cta_class.'" role="button" href="'.$cta['url'].'" target="'.$cta['target'].'" style="color:'.$cta_color.'; border-color:'.$cta_color.'; background-color:'.$cta_bg.';" '.$cta_download.'>'.$cta['title'].'</a>';
                            }
                        }}

                        $htmlContent = (get_sub_field('content') ? get_sub_field('content') : '');

                        $htmlArrows = '';
                        if($carousel_arrows) {
                            $htmlArrows .= '
                            <div class="gblock__carousel_slide--arrows mt-auto mb-0">
                                <button class="prevbutton slick-prev slick-arrow" style=""></button>
                                <button class="nextbutton slick-next slick-arrow" style=""></button>
                            </div>';
                        }

                        $htmlBody .= '
                        <div class="gblock__carousel_slide--ctext mt-auto mb-5" style="color:'.get_sub_field('content_color').'">
                            '.$htmlContent.'
                            '.$slideCTA.'
                            '.$htmlArrows.'
                        </div>
                    </div>
                </div>  <!-- slide -->';
    }}

    $htmlBody .= '</div>
        </div>
    </div>';
endwhile; endif;




echo $htmlScapeOpen.'
<section id="'.$style['section_id'].'" class="section section__carousel '.$style['section_class'].' " style="'.$style['section_style'].'" >
  <div id="'.$style['block_id'].'" class="gblock__carousel--wrapper container-fluid  p-0 '.$style['block_class'].'">
	'.$htmlBody.'
  </div>
</section>'
.$htmlStyle.$htmlScapeClose;
?>

