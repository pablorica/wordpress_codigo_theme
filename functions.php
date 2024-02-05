<?php
/**
 * Codigo Theme functions and definitions
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


// Array of files to include.
$includes = array(
	'/codigo-setup.php',
	'/codigo-extras.php',
);

if ( function_exists( 'get_field' ) ) { 
	$includes[] = '/codigo-acf.php';
}

if ( class_exists( 'WPSEO_Options' ) ) {
	$includes[] = '/codigo-yoast-seo.php';
}

if ( class_exists( 'Default_Admin_Color_Scheme' ) ) {
	$includes[] = '/codigo-default-admin-color-scheme.php';
}

if ( class_exists( 'WooCommerce' ) ) {
	$includes[] = '/codigo-woocommerce.php';
}


// Include files.
foreach ( $includes  as $file ) {
	require_once get_theme_file_path( 'inc' . $file );
}
