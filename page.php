<?php get_header(); ?>


<main  class="wrapper">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12 p-0">



				<?php if (have_posts()): while (have_posts()) : the_post(); 

						if(!is_front_page()) {

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
						}
				?>

					<!-- article -->
					<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

						<?php if(!is_front_page()): ?>
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
						<?php endif; ?>

						<?php the_content(); ?>

					</article>
					<!-- /article -->

				<?php endwhile; ?>

				<?php else: ?>

					<!-- article -->
					<article>

						<h2><?php _e( 'Page not found', 'codigo' ); ?></h2>

					</article>
					<!-- /article -->

				<?php endif; ?>


			</div><!-- /.col-md-12 -->
		</div><!-- /.row -->
	</div><!-- /.container -->
</main>
<?php get_footer(); ?>
