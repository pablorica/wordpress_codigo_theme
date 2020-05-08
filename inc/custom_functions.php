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

 */

/**
 * Image sizes
 */

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



if ( ! function_exists( 'codigo_display_html_video' ) ) :
/**
 * From an URL streaming video, check the source and prints the HTML
 */
function codigo_display_html_video($videourl, $royalSlider=false) {
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

	$html=NULL;

	if($royalSlider) {
		if ($youtubeID) {
			//$youtubeID='Kg2edsWOAEc';
			$html='<div class="rsContent rsVideoFrame"><iframe id="rsYouVideo" class="" src="https://www.youtube.com/embed/'.$youtubeID.'?showinfo=0&rel=0&enablejsapi=1" frameborder="0" allowfullscreen></iframe>';
		}
	}
	else {
		if ($youtubeID) {
			/*$html='<div id="youplayer_'.$youtubeID.'" class="youplayer" data-video-url="'.$youtubeID.'"></div>
			<a class="start-video" data-video-id="'.$youtubeID.'"></a>
			<a class="mute-video" data-video-id="'.$youtubeID.'" style="opacity:0"></a>';*/
      $html='<iframe width="100%" height="600" data-url="https://www.youtube.com/embed/'.$youtubeID.'?showinfo=0&rel=0&enablejsapi=1" src="" frameborder="0" allowfullscreen></iframe>';
		}
		if ($vimeoID) {
			$html='<iframe id="vimeoplayer" src="https://player.vimeo.com/video/'.$vimeoID.'?api=1&player_id=vimeoplayer" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
			<a class="start-video"></a>';
		}
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

