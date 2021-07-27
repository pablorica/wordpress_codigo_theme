<?php get_header(); ?>
<main  class="wrapper single-wrapper">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12 p-0">




				<?php if (have_posts()): while (have_posts()) : the_post(); 

					if(get_post_thumbnail_id($post->ID)) {
						$post_image     = get_the_post_thumbnail($post->ID, 'full');
						$post_image_url = get_the_post_thumbnail_url($post->ID, 'full');
					} else {
						$default_image  = get_field('general_default_image','option');
						$post_image     = wp_get_attachment_image($default_image, 'full');
						$post_image_url = wp_get_attachment_image_url($default_image, 'full');
					}
					$parallax = get_field('pageoptions_parallax');
					$title    = get_field('pageoptions_uppercase') ? strtoupper(get_the_title()) : get_the_title();
				?>


					<!-- article -->
					<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

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
                                        <h1 class=" gblock__headline_body--htext text-center" style="color:<?php the_field('pageoptions_title_color'); ?>"><?php echo $title ?></h1>
                                    </div>
                                </div>
                            </div>
                        </section>

						<div class="container single-container">
							<?php the_content(); // Dynamic Content ?>
						</div>

						


					</article>
					<!-- /article -->

					<!-- Related -->
					<?php
					//error_log(print_r(get_field('post_related'), true));
					$htmlRelated  = '';
					$col_loop_pos = '';
					$rint         = 0;
					$max_posts    = 2;

					if($post->post_type == 'post') {
						if ($posts_related = get_field('post_related')) {
							$incposts = wp_parse_id_list( $posts_related );
							$args_rel = array(
								'post__in'         => $incposts,
								'posts_per_page'   => count( $incposts ),
								'caller_get_posts' => 1
							);
							$my_query_rel = new WP_Query($args_rel);
							if( $my_query_rel->have_posts() ) {
								while ($my_query_rel->have_posts()) : $my_query_rel->the_post(); 
									if($rint < $max_posts ) {
										if($rint%2 == 1 ) {
											$col_loop_pos = 'order-sm-first order-md-last';
										}
										ob_start();
										get_template_part('loop-templates/medium_loop'); 
										$htmlRelated.= ob_get_contents();
										ob_end_clean();
										$rint++;
									}
								endwhile;
							}
							wp_reset_query();
						}
						//List 2 posts related to first tag on current post
						$tags = wp_get_post_tags($post->ID);
						if ($tags) {
							$first_tag = $tags[0]->term_id;
							$args_tag = array(
								'tag__in' => array($first_tag),
								'post__not_in' => array($post->ID),
								'posts_per_page'=>2,
								'caller_get_posts'=>1
							);
							$my_query_tag = new WP_Query($args_tag);
							if( $my_query_tag->have_posts() ) {
								while ($my_query_tag->have_posts()) : $my_query_tag->the_post(); 
									if($rint < $max_posts ) {
										if($rint%2 == 1 ) {
											$col_loop_pos = 'order-sm-first order-md-last';
										}
										ob_start();
										get_template_part('loop-templates/medium_loop'); 
										$htmlRelated.= ob_get_contents();
										ob_end_clean();
										$rint++;
									}
								endwhile;
							}
							wp_reset_query();
						}
						//List 2 posts related to category on current post
						$categories = wp_get_post_categories($post->ID);
						
						if ($categories) {
							//error_log(print_r($categories, true));
							$first_cat = $categories[0];
							$args_cat = array(
								'category__in' => array($first_cat),
								'post__not_in' => array($post->ID),
								'posts_per_page'=>2,
								'caller_get_posts'=>1
							);
							//error_log(print_r($args_cat, true));
							$my_query_cat = new WP_Query($args_cat);
							if( $my_query_cat->have_posts() ) {
								while ($my_query_cat->have_posts()) : $my_query_cat->the_post(); 
									if($rint < $max_posts ) {
										if($rint%2 == 1 ) {
											$col_loop_pos = 'order-sm-first order-md-last';
										}
										ob_start();
										get_template_part('loop-templates/medium_loop'); 
										$htmlRelated.= ob_get_contents();
										ob_end_clean();
										$rint++;
									}
								endwhile;
							}
							wp_reset_query();
						}
					}

					if($post->post_type == 'casestudy') {

						if ($posts_related = get_field('cstudy_related')) {
							$incposts = wp_parse_id_list( $posts_related );
							$args_rel = array(
								'post_type'        => $post->post_type,
								'post__in'         => $incposts,
								'posts_per_page'   => count( $incposts ),
								'caller_get_posts' => 1
							);
							$my_query_rel = new WP_Query($args_rel);
							if( $my_query_rel->have_posts() ) {
								while ($my_query_rel->have_posts()) : $my_query_rel->the_post(); 
									if($rint < $max_posts ) {
										if($rint%2 == 1 ) {
											$col_loop_pos = 'order-sm-first order-md-last';
										}
										ob_start();
										get_template_part('loop-templates/medium_loop'); 
										$htmlRelated.= ob_get_contents();
										ob_end_clean();
										$rint++;
									}
								endwhile;
							}
							wp_reset_query();
						}

						//List 2 posts related to custom taxonomies on current case study
						$taxonomies = array('solution','region','sector');

						foreach($taxonomies as $taxonomy) {

							$terms = get_the_terms($post->ID, $taxonomy);
							
							if ($terms) {
								//error_log(print_r($categories, true));
								$args_tax = array(
									'post_type'        => $post->post_type,
									'post__not_in'     => array($post->ID),
									'posts_per_page'   => 2,
									'caller_get_posts' => 1,
									'tax_query'        => array(
										array(
											'taxonomy' => $taxonomy,
											'field' => 'term_id',
											'terms' => $terms[0]->term_id,
										)
									)
								);
								//error_log(print_r($args_cat, true));
								$my_query_tax = new WP_Query($args_tax);
								if( $my_query_tax->have_posts() ) {
									while ($my_query_tax->have_posts()) : $my_query_tax->the_post(); 
										if($rint < $max_posts ) {
											if($rint%2 == 1 ) {
												$col_loop_pos = 'order-sm-first order-md-last';
											}
											ob_start();
											get_template_part('loop-templates/medium_loop'); 
											$htmlRelated.= ob_get_contents();
											ob_end_clean();
											$rint++;
										}
									endwhile;
								}
								wp_reset_query();
							}
						}
					}
					
					?>
					
					<?php if($htmlRelated): ?>
					<div class="container-fluidr">
						<div class="row no-gutters">
							<?php echo $htmlRelated ?>
						</div>
					</div>
					<?php endif; ?>
					<!-- /Related -->



				<?php endwhile; ?>

				<?php else: ?>

					<!-- article -->
					<article>

						<h1><?php _e( 'Sorry, nothing to display.', 'codigo' ); ?></h1>

					</article>
					<!-- /article -->

				<?php endif; ?>






			</div><!-- /.col-md-12 -->
		</div><!-- /.row -->
	</div><!-- /.container -->
</main>
<?php get_footer(); ?>
