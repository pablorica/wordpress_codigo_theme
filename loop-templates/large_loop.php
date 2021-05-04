<?php
if(!isset($page_posts)) global $page_posts;
global $col_loop_pos; 

if(get_post_thumbnail_id($post->ID)) {
    $post_image     = get_the_post_thumbnail($post->ID, 'large');
    $post_image_url = get_the_post_thumbnail_url($post->ID, 'large');
} else {
    $default_image  = get_field('general_default_image','option');
    $post_image     = wp_get_attachment_image($default_image, 'large');
    $post_image_url = wp_get_attachment_image_url($default_image, 'large');
}
$parallax = get_field('pageoptions_parallax',$page_posts);

$title_style = '';
if($cstudy_excerpt = get_field('cstudy_excerpt', $post->ID)) {
    $title_style = ' style="color:'.$cstudy_excerpt['color'].'"';
}


?>
	<!-- article -->
	<article id="post-<?php the_ID(); ?>" <?php post_class('col-md-12 px-0 loop-largepost'); ?>>

        <section id="" class="section d-flex hv-100" style="<?php if($parallax) { echo 'overflow:hidden';} else { echo 'background-image: url('.$post_image_url.');background-size: cover;';} ?>">

            <?php if($parallax): ?>
                <div class="rellax fp-bg fp-bg--image">
                    <figure>
                        <?php echo $post_image?>
                    </figure>
                </div>
            <?php endif; ?>
            <div class="gblock__headline--wrapper container-fluid  mt-auto mb-5  d-flex flex-column">
            
                <div class="row gblock__headline_body--text">
                    <div id="" class="gblock__headline_body--content hv-50 d-flex flex-column col-lg-6 <?=$col_loop_pos?>">
                        <h3 class="loop-title mb-4"><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"<?=$title_style?>><?php the_title(); ?></a></h3>
                        <div class="loop-excerpt">
                            <?php echo codigo_excerpt('codigo_index'); ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>

	</article>
	<!-- /article -->

