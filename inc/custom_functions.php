<?php
/**
 * Custom Functions.
 *
 * Created by Pablo Rica
 *
 */

/**
 * Helpers
 *
 --- Loading sections
      get_template_part( 'global-templates/header' );
   with variables
      include(locate_template('global-templates/include-modal-video.php'));


--- Images
    $image_id=get_field('image')				
    $image=wp_get_attachment_image($image_id, 'medium_large');
	OR
	$image = get_the_post_thumbnail($post->ID, 'medium_large');

 */







/*** Custom Excerpts *****/

// Create 20 Word Callback for Index page Excerpts, call using codigo_excerpt('codigo_index');
function codigo_index($length) 
{
    return 20;
}

// Create 40 Word Callback for Custom Post Excerpts, call using codigo_excerpt('codigo_custom_post');
function codigo_custom_post($length)
{
    return 40;
}

// Create the Custom Excerpts callback
function codigo_excerpt($length_callback = '', $more_callback = '', $post_id = null)
{
	if(!$post_id) {
		global $post;
		$post_id = $post->ID;
		$read_more = '';
	} else {
		$read_more = codigo_view_article('', $post_id);
	}
	$post = get_post($post_id);
	

	$excerpt_style = '';
	if($cstudy_excerpt = get_field('cstudy_excerpt', $post_id)) {
		$excerpt_style = 'style="color:'.$cstudy_excerpt['color'].'"';
	}

    if (function_exists($length_callback)) {
        add_filter('excerpt_length', $length_callback);
    }
    if (function_exists($more_callback)) {
        add_filter('excerpt_more', $more_callback);
	}

	
	$output =false;
	if(get_the_excerpt($post_id)) {
		$output = apply_filters('wptexturize', get_the_excerpt($post_id));
		$output = apply_filters('convert_chars', $output);
		$output = '<p class="post_excerpt" '.$excerpt_style.'>' . $output . $read_more.'</p>';
	}
    return $output;
}


/*** Get content between HTML tags *****/

function getTextBetweenTags($string, $tagname) {

	$d = new DOMDocument();
    $d->loadHTML($string);
    $items = array();
    foreach($d->getElementsByTagName($tagname) as $item){
        $items[] = $item->textContent;
    }
	return $items;
}


/*** Create the Block Custom Excerpts *****/
function codigo_advanced_excerpt($article = null) {
	$excerpt = codigo_excerpt('codigo_index');

	if(!$excerpt){
		$content = get_the_content($article);
		if ( has_blocks( $content ) ) {
			$cblocks = parse_blocks( $content );
			//error_log(print_r($cblocks,true));
			$cblock = $cblocks[0];
			//error_log('$cblock '.print_r($cblock ,true));
			
			if(isset($cblock['attrs']['data'])) {
				foreach($cblock['attrs']['data'] as $key => $val) {
					if($excerpt) {
						break; //There is one excerpt, break the loop 
					}

					if (strpos($key, '_text') !== false || (substr($key, -8) == '_content') ) {
						$excerpt = preg_replace("/\<h(.*)\>(.*)\<\/h[^>]+\>/","", $val); //remove <h1>,<h2>,<h3>
						$excerpt = strip_tags($excerpt);
						$excerpt = wp_trim_words( $excerpt, 20);
						$excerpt = '<p class="post_excerpt">'.$excerpt.'</p>';
					}

				}
			}

			//error_log('$excerpt '.print_r($excerpt ,true));
		}
	}

	return $excerpt;
}



/*** Managing Gutenberg Blocks *****/
// https://awhitepixel.com/blog/wordpress-gutenberg-access-parse-blocks-with-php/
// https://developer.wordpress.org/reference/hooks/render_block/

function codigo_block_wrapper( $block_content, $block ) {
	
	/*
	error_log('block '.print_r($block,true));

    if ( $block['blockName'] === 'core/paragraph' ) {
        $content = '<div class="wp-block-paragraph">';
        $content .= $block_content;
        $content .= '</div>';
        return $content;
    } elseif ( $block['blockName'] === 'core/heading' ) {
        $content = '<div class="wp-block-heading">';
        $content .= $block_content;
        $content .= '</div>';
        return $content;
	}
	*/

	//Wrapping each block. This is needed for jQuery function cssElements()
	//NOTE: This is not compatible with Fullpage!!
	if ( isset($block['attrs']['id']) ) {
		$content = '<div class="block-wrapper" id="'.$block['attrs']['id'].'">';
        $content .= $block_content;
        $content .= '</div>';
        return $content;
	}
    return $block_content;
}
//add_filter( 'render_block', 'codigo_block_wrapper', 10, 2 );


function codigo_block_scape( $block_content, $block ) {
	global $post;
	// only edit specific post types
	$types = array( 'post' );
	if ( $post && in_array( $post->post_type, $types, true ) ) {
		//error_log('block '.print_r($block,true));
		//Scaping the ACF block from container div
		if (strpos($block['blockName'], 'acf/') !== false) {
			$scape_content = '</div>';
			$scape_content .= $block_content;
			$scape_content .= '<div class="container single-container">';
			return $scape_content;
		}
	}

    return $block_content;
}
//add_filter( 'render_block', 'codigo_block_scape', 10, 2 );



/*** Managing Content *****/
// https://wordpress.stackexchange.com/questions/225625/where-to-hook-into-post-content
// the function that edits post content
function my_edit_content( $content ) {
	global $post;
	// only edit specific post types
	$types = array( 'post', 'page' );
	if ( $post && in_array( $post->post_type, $types, true ) ) {
	   $content = 'Hello World '. $content;
	}
  
	return $content;
  }
  /*
  // add the filter when main loop starts
  add_action( 'loop_start', function( WP_Query $query ) {
	 if ( $query->is_main_query() ) {
	   add_filter( 'the_content', 'my_edit_content', -10 );
	 }
  } );
  
  // remove the filter when main loop ends
  add_action( 'loop_end', function( WP_Query $query ) {
	 if ( has_filter( 'the_content', 'my_edit_content' ) ) {
	   remove_filter( 'the_content', 'my_edit_content' );
	 }
  } );
  */





if ( ! function_exists( 'codigo_display_html_video' ) ) :
/**
 * From an URL streaming video, check the source and prints the HTML
 */
function codigo_display_html_video($videourl, $args=false) {
	//$videourl='https://youtu.be/m0m7BPMZHwc';
	//$videourl='https://vimeo.com/144725590';
	//$videourl='https://www.youtube.com/embed/DEIYVFbNjm8';
	$youtubeID=NULL;
	$vimeoID=NULL;

	preg_match("#(?<=v=)[a-zA-Z0-9-]+(?=&)|(?<=v\/)[^&\n]+(?=\?)|(?<=v=)[^&\n]+|(?<=youtu.be/)[^&\n]+|(?<=embed\/)[^&\n]+#", $videourl, $youtmatches);

	if(count($youtmatches)) {
		$youtubeID=$youtmatches[0];
	}
	else {
		preg_match('/(https?:\/\/)?(www\.)?(player\.)?vimeo\.com\/([a-z]*\/)*([0-9]{6,11})[?]?.*/', $videourl, $vimematches);
		if(count($vimematches)) {
			$vimeoID=$vimematches[5];
		}
	}
	//var_dump($videourl);
	//var_dump($youtmatches);
	//var_dump($vimematches);

	$autoplay = ($args['autoplay'] ? '&autoplay=1' : '');
	$muted 	  = ($args['muted'] ? '&mute=1' : '');
	$loop 	  = ($args['loop'] ? '&loop=1&playlist='.$youtubeID : '');
	

	$html=NULL;



	if ($youtubeID) {
		/*$
		<div id="youplayer_'.$youtubeID.'" class="youplayer" data-video-url="'.$youtubeID.'"></div>
		<a class="start-video" data-video-id="'.$youtubeID.'"></a>
		<a class="mute-video" data-video-id="'.$youtubeID.'" style="opacity:0"></a>
		
		<iframe width="100%" height="600" data-url="https://www.youtube.com/embed/'.$youtubeID.'?showinfo=0&rel=0&enablejsapi=1" src="" frameborder="0" allowfullscreen></iframe>
		
		*/
		$html='<iframe width="100%" height=100%" src="https://www.youtube.com/embed/'.$youtubeID.'?rel=0'.$muted.$autoplay.$loop.'" frameborder="0" allowfullscreen></iframe>';
	}
	if ($vimeoID) {
		$html='<iframe id="vimeoplayer" src="https://player.vimeo.com/video/'.$vimeoID.'?api=1&player_id=vimeoplayer" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
		<a class="start-video"></a>';
	}
	


  	return $html;
}
endif;

if ( ! function_exists( 'codigo_display_html_video_screenshot' ) ) :
/**
 * From an URL streaming video, get the deafult screenshot (only Youtube for now)
 */
function codigo_display_html_video_screenshot($videourl) {
	//https://stackoverflow.com/questions/2068344/how-do-i-get-a-youtube-video-thumbnail-from-the-youtube-api/2068371#2068371
	$youtubeID=NULL;
	$vimeoID=NULL;

	preg_match("#(?<=v=)[a-zA-Z0-9-]+(?=&)|(?<=v\/)[^&\n]+(?=\?)|(?<=v=)[^&\n]+|(?<=youtu.be/)[^&\n]+|(?<=embed\/)[^&\n]+#", $videourl, $youtmatches);

	if(count($youtmatches)) {
		$youtubeID=$youtmatches[0];
	}
	else {
		preg_match('/(https?:\/\/)?(www\.)?(player\.)?vimeo\.com\/([a-z]*\/)*([0-9]{6,11})[?]?.*/', $videourl, $vimematches);
		if(count($vimematches)) {
			$vimeoID=$vimematches[5];
		}
	}
	//var_dump($videourl);
	//var_dump($youtmatches);
	//var_dump($vimematches);

	$frameURL=NULL;

	if ($youtubeID) {
		$frameURL='https://img.youtube.com/vi/'.$youtubeID.'/0.jpg';
	}
	if ($vimeoID) {
		//do nothing
	}

  	return $frameURL;
}
endif;


/**
 * Retrieves the attachment ID from the file URL
 */
if ( ! function_exists ( 'codigo_get_image_id_by_url' ) ) {
	function codigo_get_image_id_by_url($image_url) {
	    global $wpdb;
	    $attachment = $wpdb->get_col($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid='%s';", $image_url )); 
	        return $attachment[0]; 
	}
}


/**
 * Return an array with posts years
 */
if ( ! function_exists ( 'get_posts_years_array' ) ) {
	function get_posts_years_array($type = 'post') {
	    global $wpdb;
	    $result = array();
	    $years = $wpdb->get_results(
	        $wpdb->prepare("SELECT YEAR(post_date) FROM {$wpdb->posts} WHERE post_type = '%s' AND post_status = 'publish' GROUP BY YEAR(post_date) DESC",$type ),
	        ARRAY_N
	    );
	    if ( is_array( $years ) && count( $years ) > 0 ) {
	        foreach ( $years as $year ) {
	            $result[] = $year[0];
	        }
	    }
	    return $result;
	}
}


/**
 * Prints HTML with meta information for the current post-date/time and author.
 */
if ( ! function_exists ( 'codigo_posted_on' ) ) {
	function codigo_posted_on($echo = true, $dformat = 'jS F Y') {
		$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
		/*if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
			$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s"> (%4$s) </time>';
		}*/
		$time_string = sprintf( $time_string,
			esc_attr( get_the_date( 'c' ) ),
			esc_html( get_the_date($dformat) )
			//esc_attr( get_the_modified_date( 'c' ) ),
			//esc_html( get_the_modified_date('d.m.y') )
		);
		$posted_on = $time_string;
		$return    = '<span class="posted-on">' . $posted_on . '</span>';

		if($echo) {
			echo $return;
		} else {
			return $return;
		}
	}
}


/*** Managing ACF *****/

//Google API Key
function my_acf_init() {
	
	$google_api_key = get_field('general_google_api_key','option');
	
	acf_update_setting('google_api_key', $google_api_key);
}

add_action('acf/init', 'my_acf_init');