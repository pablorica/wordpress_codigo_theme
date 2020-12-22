<?php
/**
 * Set Up Functions.
 * 
 * Adds new settings to theme.
 * Created by Pablo Rica
 * 
 */


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


// Remove Actions
remove_action('wp_head', 'feed_links_extra', 3); // Display the links to the extra feeds such as category feeds
remove_action('wp_head', 'feed_links', 2); // Display the links to the general feeds: Post and Comment Feed
remove_action('wp_head', 'rsd_link'); // Display the link to the Really Simple Discovery service endpoint, EditURI link
remove_action('wp_head', 'wlwmanifest_link'); // Display the link to the Windows Live Writer manifest file.
remove_action('wp_head', 'index_rel_link'); // Index link
remove_action('wp_head', 'parent_post_rel_link', 10, 0); // Prev link
remove_action('wp_head', 'start_post_rel_link', 10, 0); // Start link
remove_action('wp_head', 'adjacent_posts_rel_link', 10, 0); // Display relational links for the posts adjacent to the current post.
remove_action('wp_head', 'wp_generator'); // Display the XHTML generator that is generated on the wp_head hook, WP version
remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
remove_action('wp_head', 'rel_canonical');
remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);



if (function_exists('add_theme_support'))
{
    // Add Menu Support
    add_theme_support('menus');

    // Set up the WordPress Theme logo feature.
    add_theme_support( 'custom-logo' );

    // Add Thumbnail Theme Support
    add_theme_support('post-thumbnails');

    // Enables post and comment RSS feed links to head
    //add_theme_support('automatic-feed-links');
}

if (function_exists('add_image_size'))
{

    /*
    the_post_thumbnail('thumbnail');       // Thumbnail (default 150px x 150px max)
    the_post_thumbnail('medium');          // Medium resolution (default 300px x 300px max)
    the_post_thumbnail('medium_large');    // Medium Large resolution (default 768px x 0px max)
    the_post_thumbnail('large');           // Large resolution (default 1024px x 1024px max)
    the_post_thumbnail('full');            // Original image resolution (unmodified)
    */
    add_image_size( 'retina', 2048, 2048, false );
    add_image_size( 'square', 200, 200, array( 'left', 'top' ) );
    add_image_size( 'icon', 50, 50, false );
}

if (function_exists('load_theme_textdomain'))
{
    // Localisation Support
    //load_theme_textdomain('codigo', get_template_directory() . '/languages');
}


/**
* Site Logo
*
*/
// Filters the custom logo output.
add_filter( 'get_custom_logo', 'codigo_custom_logo' );
function codigo_custom_logo($html) {
    return str_replace('class="custom-logo-link"', 'class="pl-sm-3 pl-md-0 custom-logo-link"',$html);
}



// Adds a second custom logo
function codigo_customizer_setting($wp_customize) {
// add a setting 
    $wp_customize->add_setting('custom_white_logo');
// Add a control to upload the hover logo
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'custom_white_logo', array(
        'label' => __( 'White Logo', 'codigo' ),
        'section' => 'title_tagline', //this is the section where the custom-logo from WordPress is
        'settings' => 'custom_white_logo',
        'priority' => 8 // show it just below the custom-logo
    )));
}
//add_action('customize_register', 'codigo_customizer_setting');





// Register WP Bootstrap Sass Navigation 
add_action('init', 'register_codigo_menu');
function register_codigo_menu()
{
    register_nav_menus(array( // Using array to specify more menus if needed
        'header-menu' => __('Header Menu', 'codigo'), // Main Navigation
        'footer-menu' => __('Footer Menu', 'codigo'), // Footer Navigation
        'extra-menu' => __('Extra Menu', 'codigo') // Extra Navigation
    ));
}


// Remove Admin bar
add_filter('show_admin_bar', 'remove_admin_bar');
function remove_admin_bar()
{
    return false;
}


// Remove Categories and Tags from Posts
add_action('init', 'codigo_remove_tax');
function codigo_remove_tax() {
    register_taxonomy('category', array());
    register_taxonomy('post_tag', array());
}



/**
* Images and Thumbnails
*/

// Remove thumbnail width and height dimensions that prevent fluid images in the_thumbnail
function remove_thumbnail_dimensions( $html )
{
    $html = preg_replace('/(width|height)=\"\d*\"\s/', "", $html);
    return $html;
}
//add_filter('post_thumbnail_html', 'remove_thumbnail_dimensions', 10); // Remove width and height dynamic attributes to thumbnails
//add_filter('image_send_to_editor', 'remove_thumbnail_dimensions', 10); // Remove width and height dynamic attributes to post images

// add Bootstrap 4 .img-fluid class to images inside post content
function add_class_to_image_in_content($content) 
{

    $content = mb_convert_encoding($content, 'HTML-ENTITIES', "UTF-8");
    $document = new DOMDocument();
    libxml_use_internal_errors(true);
    $document->loadHTML(utf8_decode($content));

    $imgs = $document->getElementsByTagName('img');
    foreach ($imgs as $img) {           
        $img->setAttribute('class','img-fluid');
    }

    $html = $document->saveHTML();
    return $html;   

}
//add_filter('the_content', 'add_class_to_image_in_content');



// Custom "View More" link to Post.

/**
 * Removes the '... read more' from the excerpt read more link
 */
if ( ! function_exists( 'codigo_custom_excerpt_more' ) ) {
	function codigo_custom_excerpt_more( $more ) {
		global $post;
		return '';
	}
}
//add_filter( 'excerpt_more', 'codigo_custom_excerpt_more' );

/**
* Adds 'View Article' button instead of [...] for Excerpts
 */
if ( ! function_exists( 'codigo_view_article' ) ) {
	function codigo_view_article($more) {
	    global $post;
	    return '... <p><a class="view-article btn btn-secondary" href="' . get_permalink($post->ID) . '" role="button">' . __('Read more', 'wpbootstrapsass') . ' </a></p>';
	}
}
//add_filter('excerpt_more', 'codigo_view_article');


/**
 * Removes the "read more" link from the content
 */
if ( ! function_exists( 'codigo_remove_more_link' ) ) {
	function codigo_remove_more_link() {
		return '';
	}
}
//add_filter( 'the_content_more_link', 'codigo_remove_more_link' );


/**
* Removes all "read more" links
*/

if ( ! function_exists( 'codigo_all_excerpts_get_more_link' ) ) {

	function codigo_all_excerpts_get_more_link( $post_excerpt ) {

		$pattern = '/<a class="read-more" href="(.*?)">(.*?)<\/a>/i';
		$post_excerpt=preg_replace($pattern, '', $post_excerpt);

		return $post_excerpt;
	}
}
//add_filter( 'wp_trim_excerpt', 'codigo_all_excerpts_get_more_link' );


/**
 * Remove <p> tags from Excerpt altogether
 */
remove_filter('the_excerpt', 'wpautop'); 


/**
 * Remove editor on "Posts"
 */

function codigo_remove_editor_posts() {
	remove_post_type_support( 'post', 'editor' );
}
//add_action( 'init', 'codigo_remove_editor_posts' );


/**
 * Custom HTML tags in Titles
 */
add_filter('the_title', 'custom_html_tags_title', 10, 2);
function custom_html_tags_title($title, $id){
	$title=str_replace("[sup]", '<sup>', $title);
	$title=str_replace("[/sup]", '</sup>', $title);

	$title=str_replace("[sub]", '<sub>', $title);
	$title=str_replace("[/sub]", '</sub>', $title);

	$title=str_replace("[strong]", '<strong>', $title);
	$title=str_replace("[/strong]", '</strong>', $title);

	$title=str_replace("[b]", '<strong>', $title);
	$title=str_replace("[/b]", '</strong>', $title);

	$title=str_replace("[em]", '<i>', $title);
	$title=str_replace("[/em]", '</i>', $title);

	$title=str_replace("[i]", '<i>', $title);
	$title=str_replace("[/i]", '</i>', $title);

	$title=str_replace("[br]", '<br />', $title);

	return $title;
}



// Add page slug to body class, love this - Credit: Starkers Wordpress Theme
add_filter('body_class', 'add_slug_to_body_class'); 
function add_slug_to_body_class($classes)
{
    global $post;
    if (is_home()) {
        $key = array_search('blog', $classes);
        if ($key > -1) {
            unset($classes[$key]);
        }
    } elseif (is_page()) {
        $classes[] = sanitize_html_class($post->post_name);
    } elseif (is_singular()) {
        $classes[] = sanitize_html_class($post->post_name);
    }

    return $classes;
}



