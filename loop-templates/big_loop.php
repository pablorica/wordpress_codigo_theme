<?php
global $col_loop_pos; 

if(get_post_thumbnail_id($post->ID)) {
    $post_image = get_the_post_thumbnail($post->ID, 'large');
} else {
    $default_image = get_field('general_default_image','option');
    $post_image    = wp_get_attachment_image($default_image, 'large');
}


?>
	<!-- article -->
	<article id="post-<?php the_ID(); ?>" <?php post_class('col-md-12 px-0 loop-post loop-bigpost'); ?>>

        <div class="row no-gutters">
            <div class="col-lg-6 d-flex flex-column loop-content">
                <h3 class="loop-title mb-4"><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h3>
                <?php echo codigo_excerpt('codigo_index'); ?>
            </div>
            <div class="col-lg-6 <?=$col_loop_pos?>">
                <!-- post thumbnail -->
                <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
                    <figure>
                        <?=$post_image?>
                    </figure>
                </a>
                <!-- /post thumbnail -->
            </div>
        </div>
	</article>
	<!-- /article -->

