<?php
/**
 * The main template file
 * php version 7.4.1
 *
 * @package    Understrap
 * @subpackage Codigo
 * @author     Pablo Rica <pablo@codigo.co.uk>
 * @license    MIT 
 * @version    GIT: @1.0.0@
 * @link       link
 * @since      Codigo 1.0
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

get_header();

$container = get_theme_mod( 'understrap_container_type' );
?>

<?php if ( is_front_page() && is_home() ) : ?>
	<?php get_template_part( 'global-templates/hero' ); ?>
<?php endif; ?>

<div id="index-wrapper"
	class="wrapper"
>

	<div id="content"
		class="<?php echo esc_attr( $container ); ?>"
		tabindex="-1"
	>

		<div class="row">

			<?php
			// Do the left sidebar check and open div#primary.
			get_template_part( 'global-templates/left-sidebar-check' );
			?>

			<main id="main"
				class="site-main" 
			>
				<!-- VUE -->
				<div id="vueExample"></div>
				<!-- /VUE -->

				<?php
				if ( have_posts() ) {

					while ( have_posts() ) {
						the_post();
						get_template_part( 
							'loop-templates/content', 
							get_post_format() 
						);
					}
				} else {
					get_template_part( 
						'loop-templates/content', 
						'none' 
					);
				}
				?>

			</main>

			<?php
			understrap_pagination();

			get_template_part( 'global-templates/right-sidebar-check' );
			?>

		</div><!-- .row -->

	</div><!-- #content -->

</div><!-- #index-wrapper -->

<?php
get_footer();
