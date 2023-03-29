<?php
/**
 * Codigo Theme Advanced Custom Fields plugin setup
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

// Exit if ACF is not enabled.
if ( ! function_exists( 'get_field' ) ) {
	exit;
}


/**
 * Get API key for Google Maps
 *
 * @return void
 */
function codigo_acf_init() {
	
	$google_api_key = get_field( 
		'general_google_api_key', 
		'option' 
	);
	
	acf_update_setting(
		'google_api_key', 
		$google_api_key
	);
}
if ( function_exists( 'acf_update_setting' ) ) {
	add_action(
		'acf/init', 
		'codigo_acf_init'
	);
}


/**
 * Add new Gutenberg Blocks
 *
 * @return void
 */
function register_acf_block_types() {

	$location_map_block = array(
		'name'            => 'location-map-block',
		'title'           => __( 'Location Map', 'codigo' ),
		'description'     => __( 'It displays a Google map with pin', 'codigo' ),
		'render_callback' => 'codigo_acf_block_render_callback',
		'category'        => 'codigo-blocks',
		'icon'            => 'admin-site-alt',
		'mode'            => 'edit',
		'keywords'        => array( 'map', 'google', 'location' ),
	);
	acf_register_block_type( $location_map_block );

}

if ( function_exists( 'acf_register_block_type' ) ) {
	add_action(
		'acf/init', 
		'register_acf_block_types'
	);
}

/**
 * Render custom ACF blocks
 *
 * @param array $block The new custom block.
 * @return void
 */
function codigo_acf_block_render_callback( $block ) {
	
	$slug = str_replace(
		'acf/', 
		'', 
		$block['name']
	);
	
	if ( file_exists( get_theme_file_path( "/acf-blocks/content-{$slug}.php" ) ) ) {
		get_template_part( "acf-blocks/content-{$slug}.php" );
	}
}
