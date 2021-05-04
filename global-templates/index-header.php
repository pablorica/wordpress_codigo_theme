<?php
/**
 * Partial template for video content
 *
 * @package codigo
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;


if (!function_exists('get_field')) {
	exit; // Exit if ACF is not enabled 
}



if(!isset($page_posts)) global $page_posts;
if(!isset($page_title)) global $page_title;

if(get_post_thumbnail_id($page_posts)) {
    $post_image     = get_the_post_thumbnail($page_posts, 'full');
    $post_image_url = get_the_post_thumbnail_url($page_posts, 'full');
} else {
    $default_image  = get_field('general_default_image','option');
    $post_image     = wp_get_attachment_image($default_image, 'full');
    $post_image_url = wp_get_attachment_image_url($default_image, 'full');
}
$parallax = get_field('pageoptions_parallax',$page_posts);


?>
<section id="header" class="section section-header d-flex hv-100" style="<?php if($parallax) { echo 'overflow:hidden';} else { echo 'background-image: url('.$post_image_url.');background-size: cover;';} ?>">
    <?php if($parallax): ?>
    <div class="rellax fp-bg fp-bg--image">
        <figure>
            <?php echo $post_image?>
        </figure>
    </div>
    <?php endif; ?>
    <div class="gblock__headline--wrapper container-fluid  mt-auto mb-5  d-flex flex-column">
        <div class="row gblock__headline_body--text scene_element scene_element--fadeinup">
            <div class="gblock__headline_body--content hv-50 d-flex flex-column col-md-12 ">
                <h1 class=" gblock__headline_body--htext text-center" style="color:<?php the_field('pageoptions_title_color',$page_posts) ?>"><?=$page_title?></h1>
            </div>
        </div>
    </div>
</section>