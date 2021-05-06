<?php
/**
 * Custom enqueue scripts
 *
 * Created by Pablo Rica
 *
 */

if ( ! function_exists( 'codigo_enqueue_scripts' ) ) {
	function codigo_enqueue_scripts() {
		//wp_enqueue_style( 'animate', get_stylesheet_directory_uri() . '/animate/animate.css', array(), '3.5.1' );
		//wp_enqueue_script( 'match-height', get_stylesheet_directory_uri() . '/matchheight/jquery.matchHeight-min.js', array(), '0.7.2', true);

		wp_enqueue_style( 'slick', get_stylesheet_directory_uri() . '/slick/slick.css', array(), '3.5.1' );
        wp_enqueue_style( 'slick-theme', get_stylesheet_directory_uri() . '/slick/slick-theme.css', array(), '3.5.1' );
        //Imported directly on JS file
		//wp_enqueue_script( 'slick', get_stylesheet_directory_uri() . '/slick/slick.min.js', array(), '0.7.2', true);

	/*	wp_enqueue_style( 'royalslider', get_stylesheet_directory_uri() . '/royalslider/royalslider.css', array(), '1.0.6' );
		wp_enqueue_style( 'royalslider-default', get_stylesheet_directory_uri() . '/royalslider/rs-default.css', array(), '1.0.6' );
		wp_enqueue_script( 'royalslider', get_stylesheet_directory_uri() . '/royalslider/jquery.royalslider.min.js', array(), '9.5.8', true); */

        wp_enqueue_script( 'waypoints', get_stylesheet_directory_uri() . '/waypoints/jquery.waypoints.min.js', array(), '4.0.1', true);
        
        //Imported directly on JS file
        //wp_enqueue_script( 'jarallax', get_stylesheet_directory_uri() . '/parallax/jarallax.min.js"', array(), '1.10.6', true);

        define('VUE_STATUS', 'development');
		wp_enqueue_script( 'vue',  get_stylesheet_directory_uri().'/vue/js/vue.js', array(), '2.6.11', false);
	}
}

add_action( 'wp_enqueue_scripts', 'codigo_enqueue_scripts' );


// Load WP Bootstrap Sass scripts (header.php)
function codigo_header_scripts()
{
    if ($GLOBALS['pagenow'] != 'wp-login.php' && !is_admin()) {

        // Custom scripts
        // Create a version number based on the last time the file was modified
        $mainBundleURI     = get_template_directory_uri() . '/dist/main.bundle.js';
        $mainBundlePath    = get_template_directory() . '/dist/main.bundle.js';
        $mainBundleVersion = (file_exists($mainBundlePath) ? date("ymd-Gis", filemtime($mainBundlePath)) : '1.2.0');
        wp_register_script('codigo-scripts', $mainBundleURI, array('jquery'), $mainBundleVersion);

        // Enqueue it!
        wp_enqueue_script( array('codigo-scripts') );

    }
}
add_action('init', 'codigo_header_scripts'); // Add Custom Scripts to wp_head


// Add attributes to the script tag
// async or defer
// *** for CDN integrity and crossorigin attributes ***
function add_script_tag_attributes($tag, $handle)
{
    switch ($handle) {

        // adding async to main js bundle
        // for defer, replace async="async" with defer="defer"
        case ('codigo-scripts'):
            return str_replace( ' src', ' async="async" src', $tag );
        break;

        // example adding CDN integrity and crossorigin attributes
        // Note: popper.js is loaded into the main.bundle.js from npm
        // This is just an example
        case ('popper-js'):
            return str_replace( ' min.js', 'min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"', $tag );
        break;

        // example adding CDN integrity and crossorigin attributes
        // Note: bootstrap.js is loaded into the main.bundle.js from npm
        // This is just an example
        case ('bootstrap-js'):
            return str_replace( ' min.js', 'min.js" integrity="sha384-a5N7Y/aK3qNeh15eJKGWxsqtnX/wWdSZSKp+81YjTmS15nvnvxKHuzaWwXHDli+4" crossorigin="anonymous"', $tag );
        break;

        default:
            return $tag;

    } // /switch
}
add_filter('script_loader_tag', 'add_script_tag_attributes', 10, 2);

// Load conditional scripts
function codigo_conditional_scripts()
{
    if (is_page('pagenamehere')) {
        wp_register_script('scriptname', get_template_directory_uri() . '/js/scriptname.js', array('jquery'), '1.0.0'); // Conditional script(s)
        wp_enqueue_script('scriptname'); // Enqueue it!
    }
}
//add_action('wp_print_scripts', 'codigo_conditional_scripts'); // Add Conditional Page Scripts


// Load WP Bootstrap Sass styles
function codigo_styles()
{

    // Normalize is loaded in Bootstrap and both are imported into the style.css via Sass
    // Create a version number based on the last time the file was modified
    $customStyleURI     = get_template_directory_uri() . '/dist/style.min.css';
    $customStylePath    = get_template_directory() . '/dist/style.min.css';
    $customStyleVersion = (file_exists($customStylePath) ? date("ymd-Gis", filemtime($customStylePath)) : '1.5.5');
    wp_register_style('codigo-css', get_template_directory_uri() . '/dist/style.min.css', array(), $customStyleVersion, 'all');
    wp_enqueue_style('codigo-css'); // Enqueue it!
}
add_action('wp_enqueue_scripts', 'codigo_styles'); // Add Theme Stylesheet


// Remove 'text/css' from our enqueued stylesheet
function codigo_style_remove($tag)
{
    return preg_replace('~\s+type=["\'][^"\']++["\']~', '', $tag);
}
add_filter('style_loader_tag', 'codigo_style_remove'); 


