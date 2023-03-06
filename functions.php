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
	'/setup.php',
	'/extras.php',
);

if ( function_exists( 'get_field' ) ) { 
	$includes[] = '/acf.php';
}

if ( class_exists( 'WPSEO_Options' ) ) {
	$includes[] = '/yoast-seo.php';
}

if ( class_exists( 'Default_Admin_Color_Scheme' ) ) {
	$includes[] = '/default-admin-color-scheme.php';
}


// Include files.
foreach ( $includes  as $file ) {
	require_once get_theme_file_path( 'inc' . $file );
}
