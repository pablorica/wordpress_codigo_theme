<?php
/**
 * Pagination layout.
 *
 * @package understrap
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'codigo_pagination' ) ) {

	function codigo_pagination( $args = array(), $class = 'pagination' ) {

		if ( $GLOBALS['wp_query']->max_num_pages <= 1 ) {
			return;
		}

		$args = wp_parse_args(
			$args,
			array(
				'mid_size'           => 2,
				'prev_next'          => true,
				'prev_text'          => __( '&nbsp;', 'understrap' ),
				'next_text'          => __( '&nbsp;', 'understrap' ),
				'screen_reader_text' => __( 'Posts navigation', 'understrap' ),
				'type'               => 'array',
				'current'            => max( 1, get_query_var( 'paged' ) ),
			)
		);

		$links = paginate_links( $args );

		?>
		<div class="container pagination-container">
			<nav aria-label="<?php echo $args['screen_reader_text']; ?>">
				<ul class="pagination">
					<?php
					foreach ( $links as $key => $link ) {
						?>
						<li class="page-item <?php echo strpos( $link, 'current' ) ? 'active' : ''; ?>">
							<?php echo str_replace( 'page-numbers', 'page-link', $link ); ?>
						</li>
						<?php
					}
					?>
				</ul>
			</nav>
		</div>

		<?php
	}
}


// Pagination for paged posts, Page 1, Page 2, Page 3, with Next and Previous Links, No plugin
function codigo_new_pagination()
{
    global $wp_query;
    $big = 999999999;
    $links = paginate_links(array(
        'base' => str_replace($big, '%#%', get_pagenum_link($big)),
        'format' => '?paged=%#%',
        'current' => max(1, get_query_var('paged')),
        'total' => $wp_query->max_num_pages,
        'prev_text' => '<span class="border p-1">&lt;</span>',
        'next_text' => '<span class="border p-1">&gt;</span>',
        'before_page_number' => '<span class="border p-1">',
        'after_page_number' => '</span>',
    ));

    if ( $links ) :

        echo $links;

    endif;

}
//add_action('init', 'codigo_new_pagination'); 

?>
