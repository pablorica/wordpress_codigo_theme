<?php
/**
 * Basic setup for codigo template
 * 
 * Adds the needed template hooks.
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


/**
 * Removes the parent themes stylesheet and scripts from inc/enqueue.php
 * 
 * @return void
 */
function understrap_remove_scripts() {
	wp_dequeue_style( 'understrap-styles' );
	wp_deregister_style( 'understrap-styles' );

	wp_dequeue_script( 'understrap-scripts' );
	wp_deregister_script( 'understrap-scripts' );
}
add_action( 
	'wp_enqueue_scripts', 
	'understrap_remove_scripts', 
	20 
);


/**
 * Enqueue our stylesheet and javascript file
 * 
 * @return void
 */
function codigo_enqueue_libraries() {

	// Get the theme data.
	$the_theme     = wp_get_theme();
	$theme_version = $the_theme->get( 'Version' );

	$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
	// Grab asset urls.
	$theme_styles  = "/css/codigo{$suffix}.css";
	$theme_scripts = "/js/codigo{$suffix}.js";
	
	$css_version = $theme_version . '.' . filemtime( get_stylesheet_directory() . $theme_styles );

	wp_enqueue_style( 'codigo-styles', get_stylesheet_directory_uri() . $theme_styles, array(), $css_version );
	wp_enqueue_script( 'jquery' );
	
	$js_version = $theme_version . '.' . filemtime( get_stylesheet_directory() . $theme_scripts );
	
	wp_enqueue_script( 'codigo-scripts', get_stylesheet_directory_uri() . $theme_scripts, array(), $js_version, true );

    // AJAX
    /*
    wp_localize_script( 
        'codigo-scripts', //We are adding the AJAX variables and route to the script we enqueued earlier
        'cdg_ajax_var', //Name of variable in JS
        array(
            'url'    => admin_url( 'admin-ajax.php' ),
            'nonce'  => wp_create_nonce( 'codigo-ajax-nonce' ),
            'action' => 'cdg-ajax' //This is the name of the function in add_action(wp_ajax_<name-function>) and add_action(wp_ajax_nopriv_<name-function>)
        ) 
    );
    //The AJAX php functions are in inc/codigo-ajax.php, the JS functions are in src/js/modules/customAJAX.js
    */

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 
	'wp_enqueue_scripts', 
	'codigo_enqueue_libraries' 
);


if ( ! function_exists( 'codigo_enqueue_scripts' ) ) {

	/**
	 * Load custom JavaScript and CSS sources.
	 * 
	 * @return void
	 */
	function codigo_enqueue_scripts() {

		// VUE.
		define( 'VUE_STATUS', 'development' ); // development | production.

		$vuescript = get_stylesheet_directory_uri() . '/vue/js/vue.js'; // production : '/vue/js/vue.global.prod.min.js'.

		wp_enqueue_script( 
			'vue',  
			$vuescript, 
			array(), 
			'3.2.41', 
			false
		);
	}
}
add_action(
	'wp_enqueue_scripts',
	'codigo_enqueue_scripts'
);

/**
 * Add style to admin area
 * 
 * @see https://wordpress.stackexchange.com/questions/110895/adding-custom-stylesheet-to-wp-admin
 * @see https://decodecms.com/agregar-css-personalizado-al-area-de-administracion-de-wordpress/
 * 
 * Note: custom-editor-style.min.css contains the custom TinyMCE editor stylesheets, 
 * only works for editor areas, and it's being loaded by the function 
 * add_editor_style() in the parent theme (/understrap/inc/editor.php)
 * @see https://developer.wordpress.org/reference/functions/add_editor_style/
 *
 * @return void
 */
function admin_style() {

	// Get the theme data.
	$the_theme = wp_get_theme();

	$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
	// Grab asset urls.
	$admin_styles = "/css/custom-admin-style{$suffix}.css";

	wp_enqueue_style( 
		'codigo-admin-styles', 
		get_stylesheet_directory_uri() . $admin_styles, 
		array(), 
		$the_theme->get( 'Version' ) 
	);
}
add_action(
	'admin_enqueue_scripts',
	'admin_style'
);


/**
 * Sets up child theme defaults and registers support for various WordPress features.
 * - Loads Textdomain
 * - Registers new menus
 * - Removes understrap widgets
 *
 * @see https://wordpress.stackexchange.com/questions/49864/unregister-sidebar-from-child-theme
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails. 
 * 
 * @return void
 */
function add_codigo_setup() {
	load_child_theme_textdomain( 
		'codigo', 
		get_stylesheet_directory() . '/languages' 
	);

	register_nav_menus(
		array(
			'footer' => __( 'Footer Menu', 'codigo' ),
		)
	);

	remove_action( 
		'widgets_init', 
		'understrap_widgets_init' 
	);
}
add_action( 
	'after_setup_theme', 
	'add_codigo_setup' 
);

/**
 * Initializes codigo widgets.
 * 
 * @return void
 */
function codigo_widgets_init() {
	register_sidebar(
		array(
			'name'          => __( 'Footer Full', 'codigo' ),
			'id'            => 'footerfull',
			'description'   => __( 'Full sized footer widget with dynamic grid', 'codigo' ),
			'before_widget' => '<div id="%1$s" class="footer-widget %2$s dynamic-classes">',
			'after_widget'  => '</div><!-- .footer-widget -->',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		)
	);
}
add_action( 
	'widgets_init', 
	'codigo_widgets_init' 
);



/**
 * Overrides the theme_mod to default to Bootstrap 5
 *
 * This function uses the `theme_mod_{$name}` hook and
 * can be duplicated to override other theme settings.
 *
 * @return string
 */
function understrap_default_bootstrap_version() {
	return 'bootstrap5';
}
add_filter( 
	'theme_mod_understrap_bootstrap_version', 
	'understrap_default_bootstrap_version', 
	20 
);



/**
 * Loads javascript for showing customizer warning dialog.
 * 
 * @return void
 */
function understrap_child_customize_controls_js() {
	wp_enqueue_script(
		'codigo_customizer',
		get_stylesheet_directory_uri() . '/js/customizer-controls.js',
		array( 'customize-preview' ),
		'20130508',
		true
	);
}
add_action( 
	'customize_controls_enqueue_scripts', 
	'understrap_child_customize_controls_js' 
);
