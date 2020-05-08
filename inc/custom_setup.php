<?php
/**
 * Set Up Functions.
 * 
 * Adds new settings to theme.
 * Created by Pablo Rica
 * 
 */


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


/**
* Adds a second custom logo
*/
function codigo_customizer_setting($wp_customize) {
// add a setting 
    $wp_customize->add_setting('custom_white_logo');
// Add a control to upload the hover logo
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'custom_white_logo', array(
        'label' => __( 'White Logo', 'codigo' ),
        'section' => 'title_tagline', //this is the section where the custom-logo from WordPress is
        'settings' => 'custom_white_logo',
        'priority' => 8 // show it just below the custom-logo
    )));
}
//add_action('customize_register', 'codigo_customizer_setting');


/**
 * Removing Parent's theme hooks scripts
 *
 */
if ( ! function_exists( 'codigo_child_theme_setup' ) ) {
function codigo_child_theme_setup() {
	remove_theme_support('post-formats');
	//Removes the parent post formats ('aside','image','video','quote','link')

	// override parent theme's 'more' text for excerpts
	//remove_filter( 'excerpt_more', 'twentyeleven_auto_excerpt_more' );
	//remove_filter( 'get_the_excerpt', 'twentyeleven_custom_excerpt_more' );
	remove_filter( 'excerpt_more', 'understrap_custom_excerpt_more' );
	remove_filter( 'wp_trim_excerpt', 'understrap_all_excerpts_get_more_link' );
}
}
add_action( 'after_setup_theme', 'codigo_child_theme_setup',60 );

/**
 * Removes the '... read more' from the excerpt read more link (we need to remove the parent's theme hook first, in custom_setup.php)
 */
if ( ! function_exists( 'codigo_custom_excerpt_more' ) ) {
	function codigo_custom_excerpt_more( $more ) {
		global $post;
		return '';
	}
}
//add_filter( 'excerpt_more', 'codigo_custom_excerpt_more' );


/**
 * Removes the "read more" link from the content
 */
if ( ! function_exists( 'codigo_remove_more_link' ) ) {
	function codigo_remove_more_link() {
		return '';
	}
}
//add_filter( 'the_content_more_link', 'codigo_remove_more_link' );


/**
* Removes all "read more" links
*/

if ( ! function_exists( 'codigo_all_excerpts_get_more_link' ) ) {

	function codigo_all_excerpts_get_more_link( $post_excerpt ) {

		$pattern = '/<a class="read-more" href="(.*?)">(.*?)<\/a>/i';
		$post_excerpt=preg_replace($pattern, '', $post_excerpt);

		return $post_excerpt;
	}
}
//add_filter( 'wp_trim_excerpt', 'codigo_all_excerpts_get_more_link' );
