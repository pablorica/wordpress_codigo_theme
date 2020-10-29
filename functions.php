<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Remove Understrap Configuration.
 */
//require get_stylesheet_directory()   . '/inc/wpbootstrapsass.php'; //Deprecated

/**
 * Theme Set Up.
 */
require get_stylesheet_directory()   . '/inc/custom_setup.php';

/**
 * Custom Enqueue.
 */
require get_stylesheet_directory()   . '/inc/custom_enqueue.php';

/**
 * Custom Navigation.
 */
require get_stylesheet_directory()   . '/inc/class-wp-codigo-navwalker.php';



/**
 * Custom Pagination.
 */
require get_stylesheet_directory()   . '/inc/custom_pagination.php';

/**
 * Custom Functions.
 */
require get_stylesheet_directory()   . '/inc/custom_functions.php';

/**
 * Custom Types.
 */
//require get_stylesheet_directory()  . '/inc/custom_types.php';

/**
 * Custom Template Management
 */
require get_stylesheet_directory()  . '/inc/custom_template.php';

/**
 * Custom Blocks for Gutenberg
 */
require get_stylesheet_directory()  . '/inc/custom_blocks.php';


/**
 * Custom Sidebars.
 */
//require get_stylesheet_directory()   . '/inc/custom_sidebars.php';

/**
 * Custom Shortcodes.
 */
//require get_stylesheet_directory()  . '/inc/custom_shortcodes.php';


/**
 * AJAX Management
 */
//require get_stylesheet_directory()  . '/inc/custom_ajax.php';





/**
 * Load WooCommerce functions.
 */
//require get_stylesheet_directory() . '/inc/woocommerce.php';


/**
 * Custom Constants
 */
//require get_stylesheet_directory()  . '/inc/custom_constants.php';

