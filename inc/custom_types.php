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
        "supports" => array( "title", "editor", "thumbnail"),
    );

    register_post_type( "apartment", $args );
}

add_action( 'init', 'cptui_register_my_cpts_apartments' );

/**
 * Post Type: members
 */

function cptui_register_my_cpts_members() {

    $labels = array(
        "name" => __( 'Members', 'codigo' ),
        "singular_name" => __( 'Member', 'codigo' ),
		'add_new' => _x('Add New', 'codigo'),
		'add_new_item' => __('Add new member'),
		'edit_item' => __('Edit member'),
		'new_item' => __('New member'),
		'view_item' => __('View member'),
		'search_items' => __('Search members'),
		'not_found' =>  __('No members found'),
		'not_found_in_trash' => __('No members found in Trash'),
		'parent_item_colon' => '',
		'menu_name' => 'Team'
    );

    $args = array(
        "label" => __( 'Members', 'codigo' ),
        "labels" => $labels,
        "description" => "The Lorega Team",
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
        "rewrite" => array( "slug" => "member", "with_front" => true ),
        "query_var" => true,
        "menu_position" => 5,
        "menu_icon" => "dashicons-businesswoman",
        "supports" => array( "title", "thumbnail" ),
    );

    register_post_type( "member", $args );

	//Add new column "Portrait" to members table
	add_filter( 'manage_edit-member_columns', 'member_add_field_column' );
	function member_add_field_column( $columns ) {

		//Create new key "portrait" in position 2
		$new_columns = array_slice($columns, 0, 2, true) +
		array("portrait" => __( 'Portrait')) +
		array_slice($columns, 2, count($columns)-2, true);

		//$columns['portrait'] = __( 'Portrait');
		return $new_columns;
	}


	//Select the values to show in this new column
	add_action( 'manage_member_posts_custom_column', 'cptui_manage_member_columns', 10, 2 );
	function cptui_manage_member_columns( $column, $post_id ) {
		global $post;

		switch( $column ) {

			case 'portrait' :
				echo get_the_post_thumbnail( $post_id, 'thumbnail' );
				break;

			default :
				break;
		}
	}

	// Departments
    $labelDepartment = array(
        'name' => __( 'Departments'),
        'singular_name' => __( 'Department' ),
        'search_items' =>  __( 'Search Departments' ),
        'all_items' => __( 'All Departments' ),
        'parent_item' => __( 'Parent Department' ),
        'parent_item_colon' => __( 'Parent Department:'),
        'edit_item' => __( 'Edit Department'),
        'update_item' => __( 'Update Department'),
        'add_new_item' => __( 'Add New Department' ),
        'new_item_name' => __( 'New Department Name' ),
        'choose_from_most_used' => __( 'Choose from the most used Departments')
     );

    // Custom taxonomy for Departments
    register_taxonomy('department',array('member'), array(
		'hierarchical' => true,
		'labels' => $labelDepartment,
		'show_ui' => true,
		'query_var' => true,
		'show_admin_column' => true
    ));

    //Remove Description textarea field (we are adding WYSYWYG using ACF). 
    add_action("department_edit_form_fields", 'remove_description_textarea', 10, 1);
    function remove_description_textarea($term){
        ?>
        <script>
            jQuery(window).ready(function(){
                jQuery('label[for=description]').parent().parent().remove();
            });
        </script>
        <?php
    } 
    
    

	// Roles
    $labelRole = array(
        'name' => __( 'Roles'),
        'singular_name' => __( 'Role' ),
        'search_items' =>  __( 'Search Roles' ),
        'all_items' => __( 'All Roles' ),
        'parent_item' => __( 'Parent Role' ),
        'parent_item_colon' => __( 'Parent Role:'),
        'edit_item' => __( 'Edit Role'),
        'update_item' => __( 'Update Role'),
        'add_new_item' => __( 'Add New Role' ),
        'new_item_name' => __( 'New Role Name' ),
        'choose_from_most_used' => __( 'Choose from the most used Roles')
     );

    // Custom taxonomy for Roles
    register_taxonomy('role',array('member'), array(
		'hierarchical' => true,
		'labels' => $labelRole,
		'show_ui' => true,
		'query_var' => true,
		'show_admin_column' => true
	));

}

//add_action( 'init', 'cptui_register_my_cpts_members' );