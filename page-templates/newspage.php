<?php
/**
 * Template Name: News & Media Page
 *
 * News & Media page for codigo
 *
 * @package codigo
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;


if (!function_exists('get_field')) {
	exit; // Exit if ACF is not enabled 
}


get_header(); ?>


<main  class="wrapper">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12 p-0">



				<?php if (have_posts()): while (have_posts()) : the_post(); ?>



                        <section id="header" class="section section-header d-flex hv-100" style="background-image: url(<?php echo get_the_post_thumbnail_url($post->ID, 'full')?>);background-size: cover;">
                            <div class="gblock__headline--wrapper container-fluid  mt-auto mb-5  d-flex flex-column">
                                <div class="row gblock__headline_body--text scene_element scene_element--fadeinup">
                                    <div class="gblock__headline_body--content hv-50 d-flex flex-column col-md-12 ">
                                        <h1 class=" gblock__headline_body--htext text-center" style="color:<?php the_field('pageoptions_title_color') ?>"><?php the_title(); ?></h1>
                                    </div>
                                </div>
                            </div>
                        </section>


                        <?php
                        if($categories = get_categories()) {
                            echo '<div class="row no-gutters justify-content-around py-5">';
                            echo '<a class="btn btn-terciary m-3" href="' . get_permalink( get_option( 'page_for_posts' ) ) . '">All</a>';
                            foreach($categories as $category) {
                                echo '<a class="btn btn-terciary m-3" href="' . get_category_link($category->term_id) . '">' . $category->name . '</a>';
                            }
                            echo '</div>';
                        }
                        ?>




						<?php $args = array(
                            'posts_per_page'   => -1,
                            'post_type'        => 'post',
                        );

                        // The Query
                        $the_query = new WP_Query( $args );
                        
                        // The Loop
                        $htmlBig    = '';
                        $htmlNormal = '';
                        if ( $the_query->have_posts() ) {
                            $pint = 0;
                            while ( $the_query->have_posts() ) {
                                $the_query->the_post();
                                $col_loop_pos  = '';

                                ob_start();
                                if($pint < 4 ) {
                                    if($pint%2 == 1 ) {
                                        $col_loop_pos  = 'order-lg-first';
                                    }
                                    get_template_part('loop-templates/big_loop');
                                    $htmlBig.= ob_get_contents();
                                } else {
                                    if($pint%4 == 1 ) {
                                        $col_loop_pos  = 'blue_lighter';
                                    }
                                    if($pint%4 == 2 ) {
                                        $col_loop_pos  = 'blue_lighter';
                                    }
                                    get_template_part('loop-templates/loop');
                                    $htmlNormal.= ob_get_contents();
                                }
                                
	                            ob_end_clean();

                                $pint ++;
                            }
                            echo $htmlBig; ?>
                            <div class="row no-gutters">
                            <?php echo $htmlNormal; ?>
                            </div>
                        <?php
                        } else { ?>
                            <!-- article -->
                            <article>
                                <h2><?php _e( 'Sorry, nothing to display.', 'wpbootstrapsass' ); ?></h2>
                            </article>
                            <!-- /article -->
                        <?php
                        }
                        /* Restore original Post Data */
                        wp_reset_postdata();
                        ?>



				<?php endwhile;  endif; ?>


			</div><!-- /.col-md-12 -->
		</div><!-- /.row -->
	</div><!-- /.container -->
</main>
<?php get_footer(); ?>



