<?php if (have_posts()): while (have_posts()) : the_post(); ?>

	<!-- article -->
	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>



		<!-- post title -->
		<h4>
			<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a>
		</h4>
		<!-- /post title -->
		<!-- post excerpt -->
		<p class="post_excerpt"><?php echo codigo_advanced_excerpt(get_the_ID()); ?></p>
		<!-- /post excerpt -->
		<hr>

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