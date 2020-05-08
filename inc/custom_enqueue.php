<?php
/**
 * Custom enqueue scripts
 *
 * Created by Pablo Rica
 *
 */

if ( ! function_exists( 'codigo_enqueue_scripts' ) ) {
	function codigo_enqueue_scripts() {
		wp_enqueue_style( 'animate', get_stylesheet_directory_uri() . '/animate/animate.css', array(), '3.5.1' );
		wp_enqueue_script( 'match-height', get_stylesheet_directory_uri() . '/matchheight/jquery.matchHeight-min.js', array(), '0.7.2', true);

		wp_enqueue_style( 'slick', get_stylesheet_directory_uri() . '/slick/slick.css', array(), '3.5.1' );
		wp_enqueue_style( 'slick-theme', get_stylesheet_directory_uri() . '/slick/slick-theme.css', array(), '3.5.1' );
		wp_enqueue_script( 'slick', get_stylesheet_directory_uri() . '/slick/slick.min.js', array(), '0.7.2', true);

	/*	wp_enqueue_style( 'royalslider', get_stylesheet_directory_uri() . '/royalslider/royalslider.css', array(), '1.0.6' );
		wp_enqueue_style( 'royalslider-default', get_stylesheet_directory_uri() . '/royalslider/rs-default.css', array(), '1.0.6' );
		wp_enqueue_script( 'royalslider', get_stylesheet_directory_uri() . '/royalslider/jquery.royalslider.min.js', array(), '9.5.8', true); */

		//wp_enqueue_script( 'waypoints', get_stylesheet_directory_uri() . '/waypoints/jquery.waypoints.min.js', array(), '4.0.1', true);
	}
}

add_action( 'wp_enqueue_scripts', 'codigo_enqueue_scripts' );
