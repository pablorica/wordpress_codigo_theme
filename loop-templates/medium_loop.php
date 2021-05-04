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
	<article id="post-<?php the_ID(); ?>" <?php post_class('col-md-6 gblock__gallery px-0 loop-mediumpost'); ?>>

        <div class="gblock__gallery--cta card mx-auto">
            <a class="row no-gutters" role="button" href="<?=get_permalink()?>" title="<?php the_title(); ?>">
                <div class="card-body col-sm-6">
                    <div class="card-content d-flex flex-column ">
                        <h3 class="card-title my-auto px-3"><?php the_title() ?></h3>
                        
                    </div>
                </div>
                <!-- post thumbnail -->
                <div class="col-sm-6 gblock__gallery--img card-image <?=$col_loop_pos?>">
                    <figure>
                        <?php echo $post_image;  ?>
                    </figure>
                </div>
                <!-- /post thumbnail -->
            </a>
        </div>

	</article>
	<!-- /article -->

