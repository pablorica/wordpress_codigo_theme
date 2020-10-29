<?php
/**
 * Sidebar Functions.
 * 
 * Adds new sidebars to theme.
 * Created by Pablo Rica
 * 
 */


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}




// If Dynamic Sidebar Exists
if (function_exists('register_sidebar'))
{
    // Define Sidebar Widget Area 1
    register_sidebar(array(
        'name' => __('Widget Area 1', 'codigo'),
        'description' => __('Description for this widget-area...', 'codigo'),
        'id' => 'widget-area-1',
        'before_widget' => '<div id="%1$s" class="%2$s card mb-2"><div class="card-body">',
        'after_widget' => '</div></div>',
        'before_title' => '<h3 class="card-title">',
        'after_title' => '</h3>'
    ));

    // Define Sidebar Widget Area 2
    register_sidebar(array(
        'name' => __('Widget Area 2', 'codigo'),
        'description' => __('Description for this widget-area...', 'codigo'),
        'id' => 'widget-area-2',
        'before_widget' => '<div id="%1$s" class="%2$s card mb-2"><div class="card-body">',
        'after_widget' => '</div></div>',
        'before_title' => '<h3 class="card-title">',
        'after_title' => '</h3>'
    ));
}
