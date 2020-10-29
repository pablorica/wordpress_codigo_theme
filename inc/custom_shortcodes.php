<?php
/**
 * Shortcodes
 * 
 * Adds new shortcodes to theme.
 * Created by Pablo Rica
 * 
 */


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


add_filter('widget_text', 'do_shortcode'); // Allow shortcodes in Dynamic Sidebar
add_filter('widget_text', 'shortcode_unautop'); // Remove <p> tags in Dynamic Sidebars (better!)

add_filter('the_excerpt', 'shortcode_unautop'); // Remove auto <p> tags in Excerpt (Manual Excerpts only)
add_filter('the_excerpt', 'do_shortcode'); // Allows Shortcodes to be executed in Excerpt (Manual Excerpts only)



// Shortcodes (copied to custom_shortcodes.php)
add_shortcode('codigo_shortcode_demo', 'codigo_shortcode_demo'); // You can place [codigo_shortcode_demo] in Pages, Posts now.
add_shortcode('codigo_shortcode_demo_2', 'codigo_shortcode_demo_2'); // Place [codigo_shortcode_demo_2] in Pages, Posts now.

// Shortcodes above would be nested like this -
// [codigo_shortcode_demo] [codigo_shortcode_demo_2] Here's the page title! [/codigo_shortcode_demo_2] [/codigo_shortcode_demo]



// Shortcode Demo with Nested Capability
function codigo_shortcode_demo($atts, $content = null)
{
    return '<div class="shortcode-demo">' . do_shortcode($content) . '</div>'; // do_shortcode allows for nested Shortcodes
}

// Shortcode Demo with simple <h2> tag
function codigo_shortcode_demo_2($atts, $content = null) // Demo Heading H2 shortcode, allows for nesting within above element. Fully expandable.
{
    return '<h2>' . $content . '</h2>';
}
