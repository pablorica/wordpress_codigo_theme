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
		'menu_name' => 'Members'
    );

    $args = array(
        "label" => __( 'Members', 'codigo' ),
        "labels" => $labels,
        "description" => "The Not Forgotten Team",
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
        "menu_icon" => "dashicons-id",
        "supports" => array( "title", "editor", "thumbnail" ),
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

	// Types
    $labelType = array(
        'name' => __( 'Type'),
        'singular_name' => __( 'Type' ),
        'search_items' =>  __( 'Search Types' ),
        'all_items' => __( 'All Types' ),
        'parent_item' => __( 'Parent Type' ),
        'parent_item_colon' => __( 'Parent Type:'),
        'edit_item' => __( 'Edit Type'),
        'update_item' => __( 'Update Type'),
        'add_new_item' => __( 'Add New Type' ),
        'new_item_name' => __( 'New Type Name' ),
        'choose_from_most_used' => __( 'Choose from the most used Types')
     );

    // Custom taxonomy for Types
    register_taxonomy('type',array('member'), array(
		'hierarchical' => true,
		'labels' => $labelType,
		'show_ui' => true,
		'query_var' => true,
		'show_admin_column' => true
	));

	// Roles
    $labelRole = array(
        'name' => __( 'Role'),
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

    //Add new column to custom taxonomy table
    add_filter( 'manage_edit-role_columns', 'codigo_role_add_field_column' );
    function codigo_role_add_field_column( $columns ) {

        //Create new key "portrait" in position 2
        $new_columns = array_slice($columns, 0, 2, true) +
        array("role_color" => __( 'Colour')) +
        array_slice($columns, 2, count($columns)-2, true);

        //$columns['portrait'] = __( 'Portrait');
        return $new_columns;
    }


    //Select the values to show in this new column
    add_action( 'manage_role_custom_column', 'codigo_manage_role_columns', 10, 3 );
    function codigo_manage_role_columns($out, $column, $term_id) {
        $term= get_term($term_id);

        switch( $column ) {

            case 'role_color' :
                echo '<div style="height:20px;width:20px;border-radius:50%;background-color:'.get_field( 'role_colour',  $term).'" ></div>';
                break;

            default :
                break;
        }
    }


}

//add_action( 'init', 'cptui_register_my_cpts_members' );

/**
 * Use radio inputs instead of checkboxes for Sector checklists.
 *
 * @param   array   $args
 * @return  array
 */
// https://wordpress.stackexchange.com/questions/139269/wordpress-taxonomy-radio-buttons
function codigo_sector_radio_checklist( $args ) {
    if ( ! empty( $args['taxonomy'] ) && $args['taxonomy'] === 'sector') {
        if ( empty( $args['walker'] ) || is_a( $args['walker'], 'Walker' ) ) { // Don't override 3rd party walkers.
            if ( ! class_exists( 'Sector_Walker_Category_Radio_Checklist' ) ) {
                /**
                 * Custom walker for switching checkbox inputs to radio.
                 *
                 * @see Walker_Category_Checklist
                 */
                class Sector_Walker_Category_Radio_Checklist extends Walker_Category_Checklist {
                    function walk( $elements, $max_depth, ...$args) {
                        $output = parent::walk( $elements, $max_depth, ...$args );
                        $output = str_replace(
                            array( 'type="checkbox"', "type='checkbox'" ),
                            array( 'type="radio"', "type='radio'" ),
                            $output
                        );

                        return $output;
                    }
                }
            }

            $args['walker'] = new Sector_Walker_Category_Radio_Checklist;
        }
    }

    return $args;
}

//add_filter( 'wp_terms_checklist_args', 'codigo_sector_radio_checklist' );






/**
 * Post Type: events
 */

function cptui_register_my_cpts_events() {

    $labels = array(
        "name" => __( 'Events', 'codigo' ),
        "singular_name" => __( 'Event', 'codigo' ),
        'add_new' => _x('Add New', 'codigo'),
        'add_new_item' => __('Add new event'),
        'edit_item' => __('Edit event'),
        'new_item' => __('New event'),
        'view_item' => __('View event'),
        'search_items' => __('Search events'),
        'not_found' =>  __('No events found'),
        'not_found_in_trash' => __('No events found in Trash'),
        'parent_item_colon' => '',
        'menu_name' => 'Events'
    );

    $args = array(
        "label" => __( 'Events', 'codigo' ),
        "labels" => $labels,
        "description" => "The Fundraising Events",
        "public" => true,
        "publicly_queryable" => true,
        "show_ui" => true,
        "show_in_rest" => false,
        "rest_base" => "",
        "has_archive" => false,
        "show_in_menu" => true,
        "exclude_from_search" => false,
        "capability_type" => "post",
        "map_meta_cap" => true,
        "hierarchical" => false,
        "rewrite" => array( "slug" => "event", "with_front" => true ),
        "query_var" => true,
        "menu_position" => 6,
        "menu_icon" => "dashicons-megaphone",
        "supports" => array( "title", "editor", "thumbnail" ),
    );

    register_post_type( "event", $args );

    //Add new column "Portrait" to events table
    add_filter( 'manage_edit-event_columns', 'event_add_field_column' );
    function event_add_field_column( $columns ) {

        //Create new key "Image" in position 2
        $new_columns = array_slice($columns, 0, 2, true) +
        array("image" => __( 'Image')) +
        array_slice($columns, 2, count($columns)-2, true);

        //Create new key "Event Date" in new position 3
        $new_columns2 = array_slice($new_columns, 0, 3, true) +
        array("event_date" => __( 'Event Date')) +
        array_slice($new_columns, 3, count($new_columns)-3, true);

        return $new_columns2;
    }


    //Select the values to show in these new columns
    add_action( 'manage_event_posts_custom_column', 'cptui_manage_event_columns', 10, 2 );
    function cptui_manage_event_columns( $column, $post_id ) {
        global $post;

        switch( $column ) {

            case 'image' :
                $fimage = '<img width="150" height="150" src="'.DEFAULT_IMG_SRC.'" class="attachment-thumbnail size-thumbnail wp-post-image">';
                if(has_post_thumbnail($post_id)) $fimage = get_the_post_thumbnail( $post_id, 'thumbnail' );
                echo $fimage;
                break;

            case 'event_date' :
                if (function_exists('get_field')) {
                    echo get_field('event_date');
                }
                break;

            default :
                break;
        }
    }
}

//add_action( 'init', 'cptui_register_my_cpts_events' );


/**
 * Display a custom Month dropdown in Events admin
 *
 */
 
// Remove default Months dropdown
//add_action('admin_head', 'remove_event_date_drop');
function remove_event_date_drop(){
    global $typenow;
    $post_type = 'event';
    /** Ensure this is the correct Post Type*/
    if($post_type == $typenow) {
        add_filter('months_dropdown_results', '__return_empty_array');
    }
}

// Add custom Months dropdown to the Events
//add_action('restrict_manage_posts', 'cptui_filter_event_by_custom_month');
function cptui_filter_event_by_custom_month(){

    global $wpdb,$typenow;
    $post_type = 'event';
    $acf       = 'event_date'; 
    $cdate     = get_query_var($acf);

    /** Ensure this is the correct Post Type*/
    if($post_type !== $typenow)
        return;

    /** Grab the results from the DB
        This is returning a list of the six first, distinct, single characters 
        of each row in the column, that's is the year and month (201709).
    */
    // $query = $wpdb->prepare('
    //     SELECT DISTINCT LEFT(pm.meta_value,6)  FROM %1$s AS pm
    //     LEFT JOIN %2$s p ON p.ID = pm.post_id
    //     WHERE pm.meta_key = "%3$s" 
    //     AND p.post_status = "%4$s" 
    //     AND p.post_type = "%5$s"
    //     ORDER BY pm.meta_value DESC',
    //     $wpdb->postmeta,
    //     $wpdb->posts,
    //     $acf, 
    //     'publish',
    //     $post_type);
    // //var_dump($query);
    //For some reason $wpdb->prepare stopped working. We use srintf instead
    
    $query = sprintf('
        SELECT DISTINCT LEFT(pm.meta_value,6)  FROM %1$s AS pm
        LEFT JOIN %2$s p ON p.ID = pm.post_id
        WHERE pm.meta_key = "%3$s" 
        AND p.post_status = "%4$s" 
        AND p.post_type = "%5$s"
        ORDER BY pm.meta_value DESC',
        $wpdb->postmeta,
        $wpdb->posts,
        $acf, 
        'publish',
        $post_type);
    
    //var_dump($query);
    
    
    $results = $wpdb->get_col($query);

    /** Ensure there are options to show */
    if(empty($results))
        return;

    /** Grab all of the options that should be shown */
    $options[] = sprintf('<option value="-1">%1$s</option>', __('Date'));
    foreach($results as $result) :
        $firstMonth=substr($result, 0, 4) . '/' . substr($result, 4).'/01';
        $time = strtotime($firstMonth);
        $newformat = date('F (Y)',$time);
        $selected ="";
        if ( isset($cdate ) && $cdate  == esc_attr($result) ) { $selected ="selected";}
        $options[] = sprintf('<option value="%1$s" %2$s >%3$s</option>', esc_attr($result), $selected ,$newformat);
        //$options[] = sprintf('<option value="%1$s">%2$s</option>', esc_attr($result), $result);
    endforeach;

    /** Output the dropdown menu */
    echo '<select class="" id="'.$acf.'" name="'.$acf.'">';
    echo join("\n", $options);
    echo '</select>';

}

//Add new query var event_date to Wordpress
//add_filter( 'query_vars', 'add_event_query_vars_filter' );
function add_event_query_vars_filter( $vars ){
  $vars[] = "event_date";
  return $vars;
}


//Filter events by Month in admin
//add_filter( 'parse_query', 'cptui_convert_month_to_meta_in_query' );
function  cptui_convert_month_to_meta_in_query($query) {
   global $pagenow;
   
   $post_type = 'event'; 
   $acf  = 'event_date'; 
   $q_vars    = &$query->query_vars;

   if ( $pagenow == 'edit.php' && isset($q_vars['post_type']) && $q_vars['post_type'] == $post_type && isset($q_vars[$acf]) && is_numeric($q_vars[$acf]) && $q_vars[$acf] > 0 ) {
    $query->query_vars['meta_key'] = $acf;
    $query->query_vars['meta_value'] = $q_vars[$acf];
    $query->query_vars['meta_compare'] = 'LIKE';
    //var_dump($query->query_vars);
  }
}

/**
 * Post Type: Testimonial
 */

function cptui_register_my_cpts_testimonials() {

    $labels = array(
        "name" => __( 'Testimonials', 'codigo' ),
        "singular_name" => __( 'Testimonial', 'codigo' ),
        'add_new' => _x('Add New', 'codigo'),
        'add_new_item' => __('Add new testimonial'),
        'edit_item' => __('Edit testimonial'),
        'new_item' => __('New testimonial'),
        'view_item' => __('View testimonial'),
        'search_items' => __('Search testimonials'),
        'not_found' =>  __('No testimonials found'),
        'not_found_in_trash' => __('No testimonials found in Trash'),
        'parent_item_colon' => '',
        'menu_name' => 'Testimonials'
    );

    $args = array(
        "label" => __( 'Testimonials', 'codigo' ),
        "labels" => $labels,
        "description" => "The Not Forgotten Team",
        "public" => true,
        "publicly_queryable" => true,
        "show_ui" => true,
        "show_in_rest" => false,
        "rest_base" => "",
        "has_archive" => false,
        "show_in_menu" => true,
        "exclude_from_search" => false,
        "capability_type" => "post",
        "map_meta_cap" => true,
        "hierarchical" => false,
        "rewrite" => array( "slug" => "testimonial", "with_front" => true ),
        "query_var" => true,
        "menu_position" => 5,
        "menu_icon" => "dashicons-testimonial",
        "supports" => array( "title", "editor", "thumbnail" ),
    );

    register_post_type( "testimonial", $args );

    //Add new column "Portrait" to testimonials table
    add_filter( 'manage_edit-testimonial_columns', 'testimonial_add_field_column' );
    function testimonial_add_field_column( $columns ) {

        //Create new key "portrait" in position 2
        $new_columns = array_slice($columns, 0, 2, true) +
        array("portrait" => __( 'Portrait')) +
        array_slice($columns, 2, count($columns)-2, true);

        //$columns['portrait'] = __( 'Portrait');
        return $new_columns;
    }


    //Select the values to show in this new column
    add_action( 'manage_testimonial_posts_custom_column', 'cptui_manage_testimonial_columns', 10, 2 );
    function cptui_manage_testimonial_columns( $column, $post_id ) {
        global $post;

        switch( $column ) {

            case 'portrait' :
                $fimage = '<img width="150" height="150" src="'.DEFAULT_IMG_SRC.'" class="attachment-thumbnail size-thumbnail wp-post-image">';
                if(has_post_thumbnail($post_id)) $fimage = get_the_post_thumbnail( $post_id, 'thumbnail' );
                echo $fimage;
                break;

            default :
                break;
        }
    }

}

//add_action( 'init', 'cptui_register_my_cpts_testimonials' );

/**
 * Post Type: gallery
 */

function cptui_galleries() {

    $labels = array(
        "name" => __( 'Galleries', 'codigo' ),
        "singular_name" => __( 'Gallery', 'codigo' ),
		'add_new' => _x('Add New', 'codigo'),
		'add_new_item' => __('Add new gallery'),
		'edit_item' => __('Edit gallery'),
		'new_item' => __('New gallery'),
		'view_item' => __('View gallery'),
		'search_items' => __('Search galleries'),
		'not_found' =>  __('No galleries found'),
		'not_found_in_trash' => __('No galleries found in Trash'),
		'parent_item_colon' => '',
		'menu_name' => 'Galleries'
    );

    $args = array(
        "label" => __( 'Galleries', 'codigo' ),
        "labels" => $labels,
        "description" => "Galleries",
        "public" => true,
        "publicly_queryable" => true,
        "show_ui" => true,
        "show_in_rest" => false,
        "rest_base" => "",
        "has_archive" => false,
        "show_in_menu" => true,
        "exclude_from_search" => false,
        "capability_type" => "post",
        "map_meta_cap" => true,
        "hierarchical" => false,
        //"rewrite" => array( "slug" => "gallery", "with_front" => true ),
        "query_var" => true,
        "menu_position" => 6,
        "menu_icon" => "dashicons-images-alt2",
        "supports" => array( "title", "editor", "thumbnail" ),
    );

    register_post_type( "galleries", $args );

    //Add new column "Featured Image" to members table
    add_filter( 'manage_edit-galleries_columns', 'galleries_add_field_column' );
    function galleries_add_field_column( $columns ) {

        //Create new key "image" in position 2
        $new_columns = array_slice($columns, 0, 2, true) +
        array("image" => __( 'Featured Image')) +
        array_slice($columns, 2, count($columns)-2, true);

        //$columns['portrait'] = __( 'Portrait');
        return $new_columns;
    }


    //Select the values to show in this new column
    add_action( 'manage_galleries_posts_custom_column', 'cptui_manage_galleries_columns', 10, 2 );
    function cptui_manage_galleries_columns( $column, $post_id ) {
        global $post;

        switch( $column ) {

            case 'image' :
                $fimage = '<img width="150" height="150" src="'.DEFAULT_IMG_SRC.'" class="attachment-thumbnail size-thumbnail wp-post-image">';
                if(has_post_thumbnail($post_id)) $fimage = get_the_post_thumbnail( $post_id, 'thumbnail' );
                echo $fimage;
                break;

            default :
                break;
        }
    }

    // Custom taxonomy for Galleries.
    $labelGalleryType = array(
        'name' => __( 'Gallery Type'),
        'singular_name' => __( 'Gallery Type' ),
        'search_items' =>  __( 'Search Gallery Types' ),
        'all_items' => __( 'All Gallery Types' ),
        'parent_item' => __( 'Parent Gallery Type' ),
        'parent_item_colon' => __( 'Parent Gallery Type:'),
        'edit_item' => __( 'Edit Gallery Type'),
        'update_item' => __( 'Update Gallery Type'),
        'add_new_item' => __( 'Add New Gallery Type' ),
        'new_item_name' => __( 'New Gallery Type Name' ),
        'choose_from_most_used' => __( 'Choose from the most used Gallery Types')
     );

    register_taxonomy('gallery-type',array('galleries'), array(
		'hierarchical' => true,
		'labels' => $labelGalleryType,
		'show_ui' => true,
		'query_var' => true,
		'show_admin_column' => true
	));
}

//add_action( 'init', 'cptui_galleries' );
