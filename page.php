<?php get_header(); ?>
<main id="<?php echo (get_field('fullpage_enable')?'fullpage':'nofullpage')?>" class="wrapper">
	<div class="container">
		<div class="row">
			<div class="col-md-8">


					<h1 class="page-header"><?php the_title(); ?></h1>

				<?php if (have_posts()): while (have_posts()) : the_post(); ?>

					<!-- article -->
					<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

						<?php the_content(); ?>

						<br class="clear">

						<?php edit_post_link(); ?>

					</article>
					<!-- /article -->

				<?php endwhile; ?>

				<?php else: ?>

					<!-- article -->
					<article>

						<h2><?php _e( 'Sorry, nothing to display.', 'codigo' ); ?></h2>

					</article>
					<!-- /article -->

				<?php endif; ?>


			</div><!-- /.col-md-8 -->
			<?php get_sidebar(); ?>
		</div><!-- /.row -->
	</div><!-- /.container -->
</main>
<?php get_footer(); ?>
