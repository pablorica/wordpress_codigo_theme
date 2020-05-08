<?php
/**
 * Understrap Functions.
 * 
 * Removes parent theme (Understrap) default settings.
 * Created by Pablo Rica
 * 
 */


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

add_action( 'wp_enqueue_scripts', 'understrap_remove_scripts', 20 );
function understrap_remove_scripts() {
    wp_dequeue_style( 'understrap-styles' );
    wp_deregister_style( 'understrap-styles' );

    wp_dequeue_script( 'understrap-scripts' );
    wp_deregister_script( 'understrap-scripts' );

    // Removes the parent themes stylesheet and scripts from inc/enqueue.php
}


add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );
function theme_enqueue_styles() {

	// Get the theme data
	$the_theme = wp_get_theme();
    wp_enqueue_style( 'codigo-styles', get_stylesheet_directory_uri() . '/css/child-theme.min.css', array(), $the_theme->get( 'Version' ) );
    wp_enqueue_script( 'jquery');
    //wp_enqueue_script( 'popper-scripts', get_stylesheet_directory_uri() . '/js/popper.min.js', array(), false);
    wp_enqueue_script( 'codigo-scripts', get_stylesheet_directory_uri() . '/js/child-theme.min.js', array(), $the_theme->get( 'Version' ), true );
    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
        wp_enqueue_script( 'comment-reply' );
    }
}

add_action( 'after_setup_theme', 'add_child_theme_textdomain' );
function add_child_theme_textdomain() {
    load_child_theme_textdomain( 'codigo', get_stylesheet_directory() . '/languages' );
}

//Moved to custom_setup
/*
add_filter('init', 'remove_understrap_filters');
function remove_understrap_filters() {                                            
    //remove_filter( 'excerpt_more', 'understrap_custom_excerpt_more' ); // this is actually removing the ... from the excerpt read more link, so we can permit it
    remove_filter( 'wp_trim_excerpt', 'understrap_all_excerpts_get_more_link' );
}
*/


