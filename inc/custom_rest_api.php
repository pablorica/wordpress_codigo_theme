<?php 







function  filter_casestudies( $request_data ) {

	//error_log(print_r($request_data,true));
	//error_log('slug '+$request_data->slug);

	 $parameters = $request_data->get_params();
	 //error_log(print_r($parameters,true));

    $args = array(
        'post_type' 	 => 'casestudy',
        'posts_per_page' => -1, 
        'post_status'    => 'publish',
    );

    //$args['tax_query']['relation'] = 'AND';


    if(isset($parameters['date']) && $parameters['date'] != null && $parameters['date'] != 'null' ) {
        //201911
        $year  = substr($parameters['date'], 0, 4);
        $month = substr($parameters['date'], 4, 2);
		$args['date_query'] = array(
            array(
                'year'  => $year,
                'month' => $month
            )
        );
	}

    if(isset($parameters['solution']) && $parameters['solution'] != null && $parameters['solution'] != 'null') {
        $terms = explode(',',$parameters['solution']);
        
		$taxonomy = array (
			'taxonomy' => 'solution',
			'field'    => 'slug',
			'terms'    => $terms,
            'operator' => 'AND'
		);
		$args['tax_query']['solution'] = array($taxonomy);
	}

    if(isset($parameters['region']) && $parameters['region'] != null && $parameters['region'] != 'null') {
        $terms = explode(',',$parameters['region']);

		$taxonomy = array (
			'taxonomy' => 'region',
			'field'    => 'slug',
			'terms'    => $terms,
            'operator' => 'AND'
		);
		$args['tax_query']['region'] = array($taxonomy);
	}

    if(isset($parameters['sector']) && $parameters['sector'] != null && $parameters['sector'] != 'null') {
        $terms = explode(',',$parameters['sector']);

		$taxonomy = array (
			'taxonomy' => 'sector',
			'field'    => 'slug',
			'terms'    => $terms,
            'operator' => 'AND'
		);
		$args['tax_query']['sector'] = array($taxonomy);
	}	

	// The Query DEBUG
	/*
    error_log('args '.print_r($args, true));
	$the_query = new WP_Query( $args );
	error_log('query '.print_r($the_query->request, true));

	if ( $the_query->have_posts() ) {
		while ( $the_query->have_posts() ) {
			$the_query->the_post();
			error_log('post  '.get_the_ID());

		}

	} 

	wp_reset_postdata();
	/**/

    
    if($posts = get_posts($args)) {


	    foreach ($posts as $key => $post) {
	    	$posts[$key]->id		= $post->ID;
            $posts[$key]->class		= esc_attr( implode( ' ', get_post_class( ' ', $post->ID ) ) );
            $posts[$key]->title     = get_the_title($post->ID);
	    	//$posts[$key]->content   = apply_filters('the_content', $posts[$key]->post_content);
	    	$posts[$key]->permalink = get_the_permalink($post->ID);


            if(get_post_thumbnail_id($post->ID)) {
                $posts[$key]->post_image     = get_the_post_thumbnail($post->ID, 'large');
                $posts[$key]->post_image_url = get_the_post_thumbnail_url($post->ID, 'large');
            } else {
                $default_image  = get_field('general_default_image','option');
                $posts[$key]->post_image     = wp_get_attachment_image($default_image, 'large');
                $posts[$key]->post_image_url = wp_get_attachment_image_url($default_image, 'large');
            }

            $posts[$key]->title_style = '';
            if($cstudy_excerpt = get_field('cstudy_excerpt', $post->ID)) {
                $posts[$key]->title_style = 'color:'.$cstudy_excerpt['color'];
            }
            $posts[$key]->excerpt = codigo_excerpt('codigo_index','',$post->ID);

            $posts[$key]->innerClass   = '';
            $posts[$key]->sectionstyle = '';
            $posts[$key]->parallax     = false;

            //error_log('post  '.print_r($posts[$key]->excerpt,true));

	    }

        

	    return  $posts;
	    //return rest_ensure_response($posts);
	}
    else {
        // Return a WP_Error because the request product was not found. In this case we return a 404 because the main resource was not found.
        return new WP_Error( 'rest_product_invalid', esc_html__( 'No results.', 'codigo' ), array( 'status' => 404 ) );
    }

    // If the code somehow executes to here something bad happened return a 500.
    return new WP_Error( 'rest_api_sad', esc_html__( 'Something went wrong.', 'codigo' ), array( 'status' => 500 ) );
}



add_action( 'rest_api_init', 'add_routes_api');

function add_routes_api(){

	// wp-json/wp/v1/projects/sector=office/solution=none/region=year/date=DESC
	register_rest_route( 'wp/v1', 'case-study/solution=(?P<solution>[\,a-zA-Z0-9-]+)/region=(?P<region>[\,a-zA-Z0-9_-]+)/sector=(?P<sector>[\,a-zA-Z0-9-]+)/date=(?P<date>[a-zA-Z0-9-]+)', array(
        // By using this constant we ensure that when the WP_REST_Server changes our readable endpoints will work as intended.
        'methods'  => WP_REST_Server::READABLE,
        'callback' => 'filter_casestudies',
    ));

}
    