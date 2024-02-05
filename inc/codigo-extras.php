<?php
/**
 * Custom functions that act independently of the theme templates
 * 
 * Eventually, some of the functionality here could be replaced by core features.
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


/**
 * Remove hook understrap_body_classes
 * defined in wp-content/themes/understrap/inc/extras.php
 *
 * @param array<string> $classes classes added by understrap.
 * 
 * @return array<string>
 */
function remove_understrap_body_classes( $classes ) {
	remove_filter(
		'body_class',
		'understrap_body_classes',
		10
	);
	return $classes;
}
add_filter(
	'body_class',
	'remove_understrap_body_classes',
	9
);


/**
 * Remove hook understrap_all_excerpts_get_more_link
 * defined in wp-content/themes/understrap/inc/extras.php
 *
 * @param string $post_excerpt Posts's excerpt.
 * 
 * @return string
 */
function remove_understrap_all_excerpts_get_more_link( $post_excerpt ) {
	remove_filter(
		'wp_trim_excerpt',
		'understrap_all_excerpts_get_more_link',
		10 
	);
	return $post_excerpt;
}
add_filter( 
	'wp_trim_excerpt', 
	'remove_understrap_all_excerpts_get_more_link',
	9
);

/**
 * Update excerpt lenght
 *
 * @param int $length lenght of the excerpt.
 * 
 * @return int 
 */
function cdg_excerpt_length( $length ) { 
	$length = 20;

	return $length; 
} 
add_filter( 
	'excerpt_length', 
	'cdg_excerpt_length' 
);


/**
 * Remove default Gutenberg patterns
 *
 * @return void
 */
function fire_theme_support() {
	remove_theme_support( 'core-block-patterns' );
}
add_action(
	'after_setup_theme',
	'fire_theme_support'
);



/**
 * Disable comments
 *
 * @return void
 */
function disable_comments() {
	
	// Redirects any user trying to access comments page.
	global $pagenow;
	
	if ( 'edit-comments.php' === $pagenow ) {
		wp_safe_redirect( admin_url() );
		exit;
	}
	
	remove_meta_box(
		'dashboard_recent_comments', 
		'dashboard', 
		'normal'
	);

	// Disable support for comments and trackbacks in post types.
	foreach ( 
        get_post_types(
            array(), 
            'names', 
            'and'
        ) as $post_type 
	) {
		if ( is_string( $post_type )
			&& post_type_supports(
				$post_type, 
				'comments'
			) 
		) {
			remove_post_type_support(
				$post_type, 
				'comments'
			);
			remove_post_type_support(
				$post_type, 
				'trackbacks'
			);
		
        } else {
            continue;
        }
	}   
}

add_action(
	'admin_init', 
	'disable_comments'
);
// Close comments on the front-end.
add_filter(
	'comments_open',
	'__return_false',
	20
);
// Close pings on the front-end.
add_filter(
	'pings_open',
	'__return_false', 
	20
);
// Hide existing comments.
add_filter(
	'comments_array',
	'__return_empty_array',
	10
);

/**
 * Remove comments page in menu.
 *
 * @return void
 */
function remove_comments_page() {
	remove_menu_page( 'edit-comments.php' );
}
add_action(
	'admin_menu', 
	'remove_comments_page'
);

/**
 * Remove comments links from admin bar.
 *
 * @return void
 */
function remove_comments_admin_bar() {
	if ( is_admin_bar_showing() ) {
		remove_action(
			'admin_bar_menu', 
			'wp_admin_bar_comments_menu', 
			60
		);
	}
}
add_action(
	'init', 
	'remove_comments_admin_bar'
);



/**
 * Get Posts By Taxonomy.
 *
 * 1. Make a query by taxonomy and return if we have sufficient results.
 * 2. If we don't make a query to get default posts ( get extra posts so that we can remove previously
 * found posts, to avoid 'post__not_in' query )
 * 3. Merge the found post ids with previous ones and make return a fresh query by those ids in that order.
 *
 * @param string $post_type  Post type.
 * @param string $taxonomy   Taxonomy.
 * @param string $term_slug  Term slug.
 * @param int    $number     The number of posts to return.
 *
 * @return WP_Query
 */
function cdg_get_posts_by_taxonomy_query( 
	$post_type,
	$taxonomy, 
	$term_slug,
	$number = 4,
) {

	$args = array(
		'posts_per_page'         => $number,
		'post_type'              => $post_type,
		'no_found_rows'          => true,
		'update_post_meta_cache' => false,
		'update_post_term_cache' => false,
	);

	// phpcs:disable
	if ( ! empty( $taxonomy ) 
		&& ! empty( $term_slug )
	) {
		$args['tax_query'] = array(
			array(
				'taxonomy' => $taxonomy,
				'field'    => 'slug',
				'terms'    => $term_slug,
			),
		);
	}
	// phpcs:enable
 
	$post_tax_query  = new WP_Query( $args );
	$post_by_tax_ids = wp_list_pluck(  
		$post_tax_query->posts, 
		'ID' 
	); 
	// So that we don't have to do query twice,when we have sufficient results.

	$the_post_count = count( $post_by_tax_ids );
 
	// If we have sufficient no of results, early return.
	if ( $number === $the_post_count ) {
		return $post_tax_query;
	}
 
	// Otherwise get default posts and merge the two set of ids.
	$args['tax_query'] = array(); // phpcs:ignore
	$args['fields']    = 'ids';
	// So that duplicates could be removed later.
	$args['posts_per_page'] = ( $number - $the_post_count ) + $the_post_count; 
	
	$def_posts_query = new WP_Query( $args );
	$ids             = array_merge( 
		$post_by_tax_ids, 
		$def_posts_query->posts 
	);
 
	// Get the posts and with retained order.
	$all_posts_args = array(
		'post__in'       => $ids,
		'posts_per_page' => $number,
		'orderby'        => 'post__in',
	);
 
	return new WP_Query(
		array_merge( 
			$args, 
			$all_posts_args 
		)
	);
}
