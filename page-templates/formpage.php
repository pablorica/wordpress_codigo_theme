<?php
/**
 * Template Name: Form Page
 *
 * Form page for codigo
 *
 * @package codigo
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;


if (!function_exists('get_field')) {
	exit; // Exit if ACF is not enabled 
}


get_header(); ?>

<main  class="wrapper wrapper-form">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12 p-md-0">

				<?php if (have_posts()): while (have_posts()) : the_post(); ?>

					<!-- article -->
					<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

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


