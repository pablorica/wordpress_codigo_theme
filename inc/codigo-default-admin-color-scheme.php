<?php
/**
 * Codigo Theme Default Admin Color Scheme plugin setup
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

// Exit if Yoast SEO is not enabled.
if ( ! class_exists( 'Default_Admin_Color_Scheme' ) ) {
	exit;
}

/**
 * Add additional colour schemes to the backend
 *
 * @see https://www.hongkiat.com/blog/wordpress-admin-color-scheme/
 * @see https://developer.wordpress.org/reference/functions/wp_color_scheme_settings/
 * 
 * @return void
 */
function additional_admin_color_schemes() {
	
	wp_admin_css_color( 
		'yellow',
		__( 'Yellow', 'codigo' ),
		get_stylesheet_directory_uri() . '/admin-colors/yellow.css',
		array( 
			'#ebaf08', 
			'#dd823b', 
			'#fbd564', 
			'#aaa088', 
		),
		array(
			'base'    => '#fff',
			'focus'   => '#fff',
			'current' => '#fff',
		)
	);

}

add_action(
	'admin_init', 
	'additional_admin_color_schemes' 
);
