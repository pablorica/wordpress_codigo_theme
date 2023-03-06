<?php
/**
 * Basic setup for codigo template
 * 
 * Adds the needed template hooks.
 * php version 7.4.1
 *
 * @category   Category
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
 * Removes the parent themes stylesheet and scripts from inc/enqueue.php
 * 
 * @return void
 */
function understrap_remove_scripts() {
	wp_dequeue_style( 'understrap-styles' );
	wp_deregister_style( 'understrap-styles' );

	wp_dequeue_script( 'understrap-scripts' );
	wp_deregister_script( 'understrap-scripts' );
}
add_action( 
	'wp_enqueue_scripts', 
	'understrap_remove_scripts', 
	20 
);



/**
 * Enqueue our stylesheet and javascript file
 * 
 * @return void
 */
function theme_enqueue_styles() {

	// Get the theme data.
	$the_theme     = wp_get_theme();
	$theme_version = $the_theme->get( 'Version' );

	$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
	// Grab asset urls.
	$theme_styles  = "/css/codigo{$suffix}.css";
	$theme_scripts = "/js/codigo{$suffix}.js";
	
	$css_version = $theme_version . '.' . filemtime( get_stylesheet_directory() . $theme_styles );

	wp_enqueue_style( 'codigo-styles', get_stylesheet_directory_uri() . $theme_styles, array(), $css_version );
	wp_enqueue_script( 'jquery' );
	
	$js_version = $theme_version . '.' . filemtime( get_stylesheet_directory() . $theme_scripts );
	
	wp_enqueue_script( 'codigo-scripts', get_stylesheet_directory_uri() . $theme_scripts, array(), $js_version, true );
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 
	'wp_enqueue_scripts', 
	'theme_enqueue_styles' 
);



/**
 * Load the child theme's text domain
 * 
 * @return void
 */
function add_child_theme_textdomain() {
	load_child_theme_textdomain( 'codigo', get_stylesheet_directory() . '/languages' );
}
add_action( 
	'after_setup_theme', 
	'add_child_theme_textdomain' 
);



/**
 * Overrides the theme_mod to default to Bootstrap 5
 *
 * This function uses the `theme_mod_{$name}` hook and
 * can be duplicated to override other theme settings.
 *
 * @return string
 */
function understrap_default_bootstrap_version() {
	return 'bootstrap5';
}
add_filter( 
	'theme_mod_understrap_bootstrap_version', 
	'understrap_default_bootstrap_version', 
	20 
);



/**
 * Loads javascript for showing customizer warning dialog.
 * 
 * @return void
 */
function understrap_child_customize_controls_js() {
	wp_enqueue_script(
		'codigo_customizer',
		get_stylesheet_directory_uri() . '/js/customizer-controls.js',
		array( 'customize-preview' ),
		'20130508',
		true
	);
}
add_action( 
	'customize_controls_enqueue_scripts', 
	'understrap_child_customize_controls_js' 
);