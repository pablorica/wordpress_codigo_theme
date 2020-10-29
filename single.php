<?php get_header(); ?>
<main>
	<div class="container">
		<div class="row">
			<div class="col-md-8">
				<!-- section -->
				<section>

				<?php if (have_posts()): while (have_posts()) : the_post(); ?>


					<!-- article -->
					<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

						<!-- post title -->
						<h1>
							<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a>
						</h1>
						<!-- /post title -->
						<!-- Author -->
						<p class="lead">
							<span class="author"><?php _e( 'Published by', 'codigo' ); ?> <?php the_author_posts_link(); ?></span>
						</p>
						<hr>
						<!-- Date -->
						<p>
							<span class="date">
								<?php the_time('F j, Y'); ?> <?php the_time('g:i a'); ?>
							</span>
						</p>
						<!-- /post details -->
						<hr>

						<!-- post thumbnail -->
						<?php if ( has_post_thumbnail()) : // Check if Thumbnail exists ?>
							<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
								<?php the_post_thumbnail('large', ['class' => 'img-fluid']); // Fullsize image for the single post ?>
							</a>
							<hr>
						<?php endif; ?>
						<!-- /post thumbnail -->

						<?php the_content(); // Dynamic Content ?>
						<hr>
						<p>
							<?php the_tags( __( 'Tags: ', 'codigo' ), ', ', '<br>'); // Separated by commas with a line break at the end ?>
						</p>

						<p>
							<?php _e( 'Categorised in: ', 'codigo' ); the_category(', '); // Separated by commas ?>
						</p>

						<p class="text-muted"><?php _e( 'This post was written by ', 'codigo' ); the_author(); ?></p>

						<?php edit_post_link(); // Always handy to have Edit Post Links available ?>


					</article>
					<!-- /article -->

				<?php endwhile; ?>

				<?php else: ?>

					<!-- article -->
					<article>

						<h1><?php _e( 'Sorry, nothing to display.', 'codigo' ); ?></h1>

					</article>
					<!-- /article -->

				<?php endif; ?>

				</section>
				<!-- /section -->
			</div><!-- /.col-md-8 -->
			<?php get_sidebar(); ?>
		</div><!-- /.row -->
	</div><!-- /.container -->
</main>
<?php get_footer(); ?>
