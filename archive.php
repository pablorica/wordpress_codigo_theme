<?php get_header(); ?>
<main  class="wrapper">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12 p-0">



                        <?php 
                        $page_posts = get_option( 'page_for_posts' );
                        $page_title = get_the_title($page_posts);
                        get_template_part('global-templates/index-header'); 
                        ?>

                        <?php
                        if($categories = get_categories()) {
                            echo '<div class="row no-gutters justify-content-around py-5">';
                            echo '<a class="btn btn-tertiary m-3" href="' . get_permalink( get_option( 'page_for_posts' ) ) . '">All</a>';
                            foreach($categories as $category) {
                                echo '<a class="btn btn-tertiary m-3" href="' . get_category_link($category->term_id) . '">' . $category->name . '</a>';
                            }
                            echo '</div>';
                        }
                        
                        
                        // The Loop
                        $htmlBig    = '';
                        $htmlNormal = '';
                        if (have_posts()):
                            $pint = 0;
                            while (have_posts()) : the_post();
                                $col_loop_pos  = '';

                                ob_start();
                                if($pint < 4 ) {
                                    if($pint%2 == 1 ) {
                                        $col_loop_pos  = 'order-lg-first';
                                    }
                                    get_template_part('loop-templates/big_loop');
                                    $htmlBig.= ob_get_contents();
                                } else {
                                    /*
                                    if($pint%4 == 1 ) {
                                        $col_loop_pos  = 'blue_lighter';
                                    }
                                    if($pint%4 == 2 ) {
                                        $col_loop_pos  = 'blue_lighter';
                                    }
                                    */
                                    get_template_part('loop-templates/loop');
                                    $htmlNormal.= ob_get_contents();
                                }
                                
	                            ob_end_clean();

                                $pint ++;
                            endwhile;
                            echo $htmlBig; ?>
                            <div class="row no-gutters">
                            <?php echo $htmlNormal; ?>
                            </div>
                        <?php
						else: ?>
                            <!-- article -->
                            <article>
                                <h2><?php _e( 'Sorry, nothing to display.', 'wpbootstrapsass' ); ?></h2>
                            </article>
                            <!-- /article -->
                        <?php
                        endif;
                        ?>



			</div><!-- /.col-md-12 -->
		</div><!-- /.row -->
	</div><!-- /.container -->
</main>

<?php get_footer(); ?>
