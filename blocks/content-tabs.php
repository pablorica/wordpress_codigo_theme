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



$style 					= get_field('btabs_style');
$style['section_style'] = "";
$fade_animation         = 'fade_in_up';

$htmlTitle = (get_field('btabs_title') ? '<h3>'.get_field('btabs_title').'</h3>' : '');
$htmlLabels = '';
$htmlTabs   = '';
$htmlMobile = '';
$htmlStyle  = '';
$htmlBody   = '';


$default_image = get_field('btabs_default_image');

$mobile_image_default  = $default_image['mobile_image'];
$desktop_image_default = $default_image['desktop_image'];

if(!$mobile_image_default) $mobile_image_default = $desktop_image_default;
if(!$desktop_image_default) $desktop_image_default = $mobile_image_default;

if(!$mobile_image_default) {
    $desktop_image_default = $mobile_image_default = get_field('general_default_image','option');
}

$mobile_image_default_url  = wp_get_attachment_image_url($mobile_image_default, 'large', false);
$desktop_image_default_url = wp_get_attachment_image_url($desktop_image_default, 'full', false);   
$image_default_size         = $default_image['image_position'] ? $default_image['image_position'] : 'cover';


$block_body = get_field('btabs_tabs');
$block_body['groupname'] = 'btabs_tabs';
$block_animation = $block_body['animation'];


if( have_rows($block_body['groupname'])): 

    $defaultSlug    = substr(str_shuffle(MD5(microtime())), 0, 5);
    $defaultAcclug  = 'accordion-'.$defaultSlug;
    $defaultTabSlug = 'default-'.$defaultSlug;

    $htmlTabs .='<div class="tab-pane fade active show w-100" id="'.$defaultTabSlug.'" role="tabpanel" aria-labelledby="'.$defaultTabSlug.'-tab"></div>';
    $htmlStyle .= '
            div#'.$defaultTabSlug.'{
                background-image: url('.$mobile_image_default_url.');
                background-size: '.$image_default_size.';
                background-repeat: no-repeat;
            }
            @media screen and (min-width: 768px) {
                div#'.$defaultTabSlug.'{
                    background-image: url('.$desktop_image_default_url.');
                }
            }';

    while ( have_rows($block_body['groupname']) ) : the_row(); 
        $labelTitle = get_sub_field('title');
        $labelSlug  = sanitize_title($labelTitle).'-'.substr(str_shuffle(MD5(microtime())), 0, 5);
        $htmlLabels .= '
            <li class="nav-item">
                <a class="nav-link" id="'.$labelSlug.'-tab" data-toggle="tab" href="#'.$labelSlug.'" role="tab" aria-controls="'.$labelSlug.'" aria-selected="false">'.$labelTitle.'</a>
            </li>';
        
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

        $htmlContent = (get_sub_field('content') ? '<div class="w-md-50">'.get_sub_field('content').'</div>' : '').$slideCTA;

        $htmlTabs .= '<div class="tab-pane fade " id="'.$labelSlug.'" role="tabpanel" aria-labelledby="'.$labelSlug.'-tab">'.$htmlContent.'</div>';

        
        $htmlMobile .= '
            <div class="accordion-box">
                <div class="accordion-box-header" id="heading'.$labelSlug.'">
                    <button class="btn btn-accordion collapsed" data-toggle="collapse" data-target="#collapse'.$labelSlug.'" aria-expanded="false" aria-controls="collapse'.$labelSlug.'">'.$labelTitle.'</button>
                </div>

                <div id="collapse'.$labelSlug.'" class="collapse" aria-labelledby="heading'.$labelSlug.'" data-parent="#'.$defaultAcclug.'">
                    <div class="accordion-box-body">'.$htmlContent.'</div>
                </div>
            </div>';
        
        $htmlStyle .= '
                div#'.$labelSlug.'{
                    color:'.get_sub_field('content_color').';
                }
                div#'.$labelSlug.' a {
                    color:'.get_sub_field('content_color').';
                }
                div#'.$labelSlug.' a:hover,
                div#'.$labelSlug.' a:focus {
                    color:inherit;
                }';

        if(get_sub_field('type') == 'color') {
            $htmlStyle .= '
                div#'.$labelSlug.'{
                    background-color:'.get_sub_field('background_colour').';
                }';
        }
        if(get_sub_field('type') == 'image') {

            $mobile_image_field  = get_sub_field('mobile_image');
            $desktop_image_field = get_sub_field('desktop_image');

            if(!$mobile_image_field) $mobile_image_field = $desktop_image_field;
            if(!$desktop_image_field) $desktop_image_field = $mobile_image_field;

            $mobile_image  = wp_get_attachment_image_url($mobile_image_field, 'large', false);
            $desktop_image = wp_get_attachment_image_url($desktop_image_field, 'full', false);   
            $image_size    = get_sub_field('image_position') ? get_sub_field('image_position') : 'cover';   

            $htmlStyle .= '
                div#'.$labelSlug.'{
                    background-image: url('.$mobile_image.');
                    background-size: '.$image_size.';
                    background-repeat: no-repeat;
                }
                @media screen and (min-width: 768px) {
                    div#'.$labelSlug.'{
                        background-image: url('.$desktop_image.');
                    }
                }';
        }

    endwhile; 





$htmlBody ='
    <div class="col-md-4 col-xl-3 gblock__tabs--labels '.($block_animation ? 'animate-children '.$fade_animation : '').'">
        '.$htmlTitle.'

        <div id="'.$defaultAcclug.'" class="d-md-none accordion">
            '.$htmlMobile.'
        </div>
        

        <ul class="nav nav-tabs d-none d-md-block" role="tablist">
            '.$htmlLabels.'
        </ul>
    </div>
    <div class="col-md-8 col-xl-9 gblock__tabs--content p-0 d-none d-md-block">
        <div class="tab-content d-flex align-items-stretch">
            '.$htmlTabs.'
        </div>
    </div>';

$htmlStyle = '
<style>
'.$htmlStyle.'
</style>';

endif;
    


echo $htmlScapeOpen.'
<section id="'.$style['section_id'].'" class="section section__tabs '.$style['section_class'].' " style="'.$style['section_style'].'" >
  <div id="'.$style['block_id'].'" class="gblock__tabs--wrapper container-fluid d-flex align-items-stretch hv-100 p-0'.$style['block_class'].'">
    <div id="'.$style['content_id'].'"  class="'.$style['content_class'].' gblock__tabs row w-100 m-0">
	  '.$htmlBody.'
    </div>
  </div>
</section>'
.$htmlStyle.$htmlScapeClose;
?>

