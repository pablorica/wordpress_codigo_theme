<?php
/**
 * Understrap template for ACF Block Name: Location Map
 * php version 7.4.1
 *
 * @package    Understrap
 * @subpackage Codigo
 * @author     Pablo Rica <pablo@codigo.co.uk>
 * @license    MIT 
 * @version    GIT: @1.0.0@
 * @link       link
 * @since      Codigo 3.1.0
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

// Exit if ACF is not enabled.
if ( ! function_exists( 'get_field' ) ) {
	exit;
}

// Get the theme data.
$the_theme = wp_get_theme();

$google_url = 'https://maps.googleapis.com/maps/api/js?key=' . get_field( 'general_google_api_key', 'option' ) . '&libraries=geometry&callback=Function.prototype';

wp_enqueue_script( 
	'google-map', 
	$google_url, 
	array( 'jquery' ), 
	'weekly', 
	true 
);

wp_enqueue_script(
	'google-custom-map', 
	get_stylesheet_directory_uri() . '/js/googleMapCustom.js', 
	array( 'google-map' ), 
	$the_theme->get( 'Version' ), 
	false
);

$location = get_field( 'location_map' );

?>
<div class="acf-map"
	data-jsonstyle = "<?php echo esc_url( get_stylesheet_directory_uri() . '/js/json/mapStyle.json' ); ?>"
	data-zoom = "16"
>
	<div class="marker" 
		data-lat="<?php echo esc_attr( $location['lat'] ); ?>" 
		data-lng="<?php echo esc_attr( $location['lng'] ); ?>" 
	></div>
</div>
