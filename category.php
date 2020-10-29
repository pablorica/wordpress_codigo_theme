<?php get_header(); ?>
<main>
	<div class="container">
		<div class="row">
			<div class="col-md-8">
				<!-- section -->
				<section>

					<h1 class="page-header"><?php _e( 'Categories for ', 'codigo' ); single_cat_title(); ?></h1>

					<?php get_template_part('loop-templates/loop'); ?>

					<?php get_template_part('global-templates/pagination'); ?>

				</section>
				<!-- /section -->
			</div><!-- /.col-md-8 -->
			<?php get_sidebar(); ?>
		</div><!-- /.row -->
	</div><!-- /.container -->
</main>
<?php get_footer(); ?>
