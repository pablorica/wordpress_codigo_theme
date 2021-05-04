<?php
/**
 * Block Name: Gallery Row
 *
 * This is the template that displays the gallery row block.
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

$style 					= get_field('blpages_style');
$style['section_style'] = "";
$fade_animation         = 'fade_in_up';

$htmlTitle = (get_field('blpages_title') ? '<h3 class="mt-5 mx-auto text-center">'.get_field('blpages_title').'</h3>' : '');
$container = 'container-fluid'; //'container-xl'
$htmlBody  = $htmlTitle;

$current_id = get_the_ID();

if( have_rows('blpages_pages') ):
  ob_start(); ?>
  <div class="d-flex justify-content-around py-5 w-100"><?php
  while( have_rows('blpages_pages') ) : the_row();
        $blpage_id  = get_sub_field('page');
        $blpage_pic = get_sub_field('image') ? wp_get_attachment_image_url(get_sub_field('image'), 'large', false):'';
    
        $permalink = get_permalink($blpage_id );
        $title = get_the_title($blpage_id );
        $active = ($current_id == $blpage_id  ? 'active':''); ?>
        <a id="page_<?=$blpage_id?>" class="btn btn-quaternary m-3 <?=$active?>" href="<?=$permalink?>"><?=$title?></a>
        <?php if($blpage_pic): ?>
        <style>
          <?php echo '#page_'.$blpage_id?>.btn-quaternary::before {
            background-color: transparent;
            background-image:url(<?=$blpage_pic?>);
            background-size:contain;
            background-position: center;
            background-repeat: no-repeat;
        }
        </style>
        <?php endif;?>
        <?php
  endwhile;?>
  </div><?php
  $htmlBody .= ob_get_contents();
  ob_end_clean(); 

endif;



echo '<section id="'.$style['section_id'].'" class="section '.$style['section_class'].'" style="color:'.$block_color .';background-color:'.$block_bgcolor.';" >
  <div class="'.$container.'">
	<div id="'.$style['block_id'].'"  class="'.$style['block_class'].' gblock gblock__loadpages" >
	  <div id="'.$style['content_id'].'" class="row '.$style['content_class'].' '.($block_animation ? 'animate-children '.$fade_animation : '').'">
		'.$htmlBody.'
	  </div>
	</div>
  </div>
</section>'; ?>