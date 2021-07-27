<?php
/**
 * Custom Post Types.
 */


/* Hook to make columns sortable by "Order" */
add_action( 'pre_get_posts', 'manage_wp_posts_be_qe_pre_get_posts', 1 );
function manage_wp_posts_be_qe_pre_get_posts( $query ) {
   if ( $query->is_main_query() && ( $orderby = $query->get( 'orderby' ) ) ) {
      switch( $orderby ) {
         case 'Order':
            $query->set( 'orderby', 'menu_order' );
          break;
      }
   }
}


/**
 * Post Type: cstudies
 */

function cptui_register_my_cpts_cstudies() {

    $labels = array(
        "name" => __( 'Case studies', 'codigo' ),
        "singular_name" => __( 'Case study', 'codigo' ),
		'add_new' => _x('Add New', 'codigo'),
		'add_new_item' => __('Add new case study'),
		'edit_item' => __('Edit case study'),
		'new_item' => __('New case study'),
		'view_item' => __('View case study'),
		'search_items' => __('Search case studies'),
		'not_found' =>  __('No case studies found'),
		'not_found_in_trash' => __('No case studies found in Trash'),
		'parent_item_colon' => '',
		'menu_name' => 'Case studies'
    );

    $args = array(
        "label" => __( 'Case studies', 'codigo' ),
        "labels" => $labels,
        "description" => "IBCS Case studies",
        "public" => true,
        "publicly_queryable" => true,
        "show_ui" => true,
        "show_in_rest" => true,
        "rest_base" => "",
        "has_archive" => true,
        "show_in_menu" => true,
        "exclude_from_search" => false,
        "capability_type" => "post",
        "map_meta_cap" => true,
        "hierarchical" => false,
        "rewrite" => array( "slug" => "case-study", "with_front" => true ),
        "query_var" => true,
        "menu_position" => 5,
        "menu_icon" => "dashicons-media-spreadsheet",
        "supports" => array( "title", "editor", "thumbnail"),
    );

    register_post_type( "casestudy", $args );

    // Solutions
    $labelSolution = array(
        'name' => __( 'Solutions'),
        'singular_name' => __( 'Solution' ),
        'search_items' =>  __( 'Search Solutions' ),
        'all_items' => __( 'All Solutions' ),
        'parent_item' => __( 'Parent Solution' ),
        'parent_item_colon' => __( 'Parent Solution:'),
        'edit_item' => __( 'Edit Solution'),
        'update_item' => __( 'Update Solution'),
        'add_new_item' => __( 'Add New Solution' ),
        'new_item_name' => __( 'New Solution Name' ),
        'choose_from_most_used' => __( 'Choose from the most used Solutions')
     );

    // Custom taxonomy for Solutions
    register_taxonomy('solution',array('casestudy'), array(
		'hierarchical' => true,
		'labels' => $labelSolution,
		'show_ui' => true,
		'query_var' => true,
        'show_in_rest' => true,
		'show_admin_column' => true
	));

    // Regions
    $labelRegion = array(
        'name' => __( 'Regions'),
        'singular_name' => __( 'Region' ),
        'search_items' =>  __( 'Search Regions' ),
        'all_items' => __( 'All Regions' ),
        'parent_item' => __( 'Parent Region' ),
        'parent_item_colon' => __( 'Parent Region:'),
        'edit_item' => __( 'Edit Region'),
        'update_item' => __( 'Update Region'),
        'add_new_item' => __( 'Add New Region' ),
        'new_item_name' => __( 'New Region Name' ),
        'choose_from_most_used' => __( 'Choose from the most used Regions')
     );

    // Custom taxonomy for Regions
    register_taxonomy('region',array('casestudy'), array(
		'hierarchical' => true,
		'labels' => $labelRegion,
		'show_ui' => true,
		'query_var' => true,
        'show_in_rest' => true,
		'show_admin_column' => true
	));


    // Sectors
    $labelSector = array(
        'name' => __( 'Sectors'),
        'singular_name' => __( 'Sector' ),
        'search_items' =>  __( 'Search Sectors' ),
        'all_items' => __( 'All Sectors' ),
        'parent_item' => __( 'Parent Sector' ),
        'parent_item_colon' => __( 'Parent Sector:'),
        'edit_item' => __( 'Edit Sector'),
        'update_item' => __( 'Update Sector'),
        'add_new_item' => __( 'Add New Sector' ),
        'new_item_name' => __( 'New Sector Name' ),
        'choose_from_most_used' => __( 'Choose from the most used Sectors')
     );

    // Custom taxonomy for Sectors
    register_taxonomy('sector',array('casestudy'), array(
		'hierarchical' => true,
		'labels' => $labelSector,
		'show_ui' => true,
		'query_var' => true,
        'show_in_rest' => true,
		'show_admin_column' => true
	));
}

add_action( 'init', 'cptui_register_my_cpts_cstudies' );

/**
 * Post Type: apartments
 */

function cptui_register_my_cpts_apartments() {

    $labels = array(
        "name" => __( 'Apartments', 'codigo' ),
        "singular_name" => __( 'Apartment', 'codigo' ),
		'add_new' => _x('Add New', 'codigo'),
		'add_new_item' => __('Add new apartment'),
		'edit_item' => __('Edit apartment'),
		'new_item' => __('New apartment'),
		'view_item' => __('View apartment'),
		'search_items' => __('Search apartments'),
		'not_found' =>  __('No apartments found'),
		'not_found_in_trash' => __('No apartments found in Trash'),
		'parent_item_colon' => '',
		'menu_name' => 'Apartments'
    );

    $args = array(
        "label" => __( 'Apartments', 'codigo' ),
        "labels" => $labels,
        "description" => "Florence Apartments",
        "public" => true,
        "publicly_queryable" => true,
        "show_ui" => true,
        "show_in_rest" => true,
        "rest_base" => "",
        "has_archive" => false,
        "show_in_menu" => true,
        "exclude_from_search" => false,
        "capability_type" => "post",
        "map_meta_cap" => true,
        "hierarchical" => false,
        "rewrite" => array( "slug" => "apartment", "with_front" => true ),
        "query_var" => true,
        "menu_position" => 5,
        "menu_icon" => "dashicons-building",
        "supports" => array( "title", "editor", "thumbnail", "excerpt"),
    );

    register_post_type( "apartment", $args );
}

add_action( 'init', 'cptui_register_my_cpts_apartments' );


/**
 * Post Type: data_pages
 */

function cptui_register_my_cpts_datapages() {

    $labels = array(
        "name" => __( 'Data Pages', 'codigo' ),
        "singular_name" => __( 'Data Page', 'codigo' ),
		'add_new' => _x('Add New', 'codigo'),
		'add_new_item' => __('Add new data page'),
		'edit_item' => __('Edit data page'),
		'new_item' => __('New data page'),
		'view_item' => __('View data page'),
		'search_items' => __('Search data page'),
		'not_found' =>  __('No data pages found'),
		'not_found_in_trash' => __('No data pages found in Trash'),
		'parent_item_colon' => '',
		'menu_name' => 'Data Pages'
    );

    $args = array(
        "label" => __( 'Data Pages', 'codigo' ),
        "labels" => $labels,
        "description" => "Florence House Data Pages",
        "public" => true,
        "publicly_queryable" => true,
        "show_ui" => true,
        "show_in_rest" => true,
        "rest_base" => "",
        "has_archive" => false,
        "show_in_menu" => true,
        "exclude_from_search" => false,
        "capability_type" => "post",
        "map_meta_cap" => true,
        "hierarchical" => false,
        "rewrite" => array( "slug" => "data_page", "with_front" => true ),
        "query_var" => true,
        "menu_position" => 5,
        "menu_icon" => "dashicons-analytics",
        "supports" => array( "title", "editor" ),
    );

    register_post_type( "datapage", $args );


}

add_action( 'init', 'cptui_register_my_cpts_datapages' );

/**
 * Post Type: office
 */

function cptui_register_my_cpts_offices() {

    $labels = array(
        "name" => __( 'Offices', 'codigo' ),
        "singular_name" => __( 'Office', 'codigo' ),
		'add_new' => _x('Add New', 'codigo'),
		'add_new_item' => __('Add new office'),
		'edit_item' => __('Edit office'),
		'new_item' => __('New office'),
		'view_item' => __('View office'),
		'search_items' => __('Search offices'),
		'not_found' =>  __('No offices found'),
		'not_found_in_trash' => __('No offices found in Trash'),
		'parent_item_colon' => '',
		'menu_name' => 'Offices'
    );

    $args = array(
        "label" => __( 'Offices', 'codigo' ),
        "labels" => $labels,
        "description" => "The IBCS offices",
        "public" => true,
        "publicly_queryable" => true,
        "show_ui" => true,
        "show_in_rest" => true,
        "rest_base" => "",
        "has_archive" => false,
        "show_in_menu" => true,
        "exclude_from_search" => false,
        "capability_type" => "post",
        "map_meta_cap" => true,
        "hierarchical" => false,
        "rewrite" => array( "slug" => "office", "with_front" => true ),
        "query_var" => true,
        "menu_position" => 5,
        "menu_icon" => "dashicons-store",
        "supports" => array( "title"),
    );

    register_post_type( "office", $args );


	// Groups
    $labelGroup = array(
        'name' => __( 'Groups'),
        'singular_name' => __( 'Group' ),
        'search_items' =>  __( 'Search Groups' ),
        'all_items' => __( 'All Groups' ),
        'parent_item' => __( 'Parent Group' ),
        'parent_item_colon' => __( 'Parent Group:'),
        'edit_item' => __( 'Edit Group'),
        'update_item' => __( 'Update Group'),
        'add_new_item' => __( 'Add New Group' ),
        'new_item_name' => __( 'New Group Name' ),
        'choose_from_most_used' => __( 'Choose from the most used Groups')
     );

    // Custom taxonomy for Groups
    register_taxonomy('group',array('office'), array(
		'hierarchical' => true,
		'labels' => $labelGroup,
		'show_ui' => true,
		'query_var' => true,
		'show_admin_column' => true
	));

}

add_action( 'init', 'cptui_register_my_cpts_offices' );