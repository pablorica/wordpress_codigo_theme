<?php
/**
 * Block Name: Articles Row
 *
 * This is the block template that displays a row of articles.
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if (!function_exists('get_field')) {
	exit; // Exit if ACF is not enabled
}


$section_id    =  (get_field('block_articles_row_section_id') ? 'id="'.get_field('block_articles_row_section_id').'"' : '');
$section_class =  (get_field('block_articles_row_section_class') ? 'class="'.get_field('block_articles_row_section_class').'"' : '');
$block_id      =  (get_field('block_articles_row_id') ? 'id="'.get_field('block_articles_row_id').'"' : '');
$block_class   = 'class="gblock gblock__articles-row '. (get_field('block_articles_row_class') ? get_field('block_articles_row_class') : '') .'"';

$html = '
	<section '.$section_id.' '.$section_class.'>
		<div '.$block_id.' '.$block_class.' >
			<div class="container p-0">';

	if(get_field('block_articles_row_title')) {
		$html .= '
				<div class="row">
					<div class="col-12">
						<div class="intro-articles-row intro-title main-separator">
							<h2>'.get_field('block_articles_row_title').'</h2>
						</div>
					</div>
				</div>';

	}

	if($cpt = get_field('block_articles_row_type')) {
		$html .= '
		<div class="row">';

		$cpt_images   = get_field('block_articles_row_show_images');
		$cpt_titles   = get_field('block_articles_row_show_titles');
		$cpt_excerpts = get_field('block_articles_row_show_excerpts');
		$cpt_dates    = get_field('block_articles_row_show_dates');
		$cpt_ctas     = get_field('block_articles_row_show_ctas');

		if($cpt_ctas) {
			$cta = get_field('block_articles_row_cta_options');
		}


		if($cpt == 'event') { //Get the next 3 events counting from today
			$events_array = [];
			$args = array(
				'posts_per_page'   => -1,
				'orderby' => 'post_date',
				'order' => 'DESC',
				'post_type' => 'event',
				'post_status' => 'publish',
			);
			$event_list  = get_posts( $args);

			foreach ( $event_list as $post ) {
				setup_postdata( $post );

				$custom_post = [];
				$date_string = (int)get_field('event_date', $post->ID, false);
				$today = (int)date("Ymd");
				if($date_string > $today) {
					//var_dump($date_string);
					//var_dump($today);
					$custom_post['ID'] = $post->ID;
					$events_array[$date_string] = $custom_post;
				}
			}
			wp_reset_postdata();

			ksort($events_array);
			$custom_posts = array_slice($events_array, 0, 3);
		}
		else {
			$args = array(
				'numberposts' => 3,
				'orderby' => 'post_date',
				'order' => 'DESC',
				'post_type' => $cpt,
				'post_status' => 'publish',
			);
			$custom_posts = wp_get_recent_posts( $args);
		}

		if(get_field('block_articles_row_selected_'.$cpt)) {
			$featured_posts = array_reverse(get_field('block_articles_row_selected_'.$cpt));
			foreach ($featured_posts as $post_id) {
				$custom_post 	   = [];
				$custom_post['ID'] = $post_id;
				array_unshift($custom_posts, $custom_post);
				$custom_posts = array_slice($custom_posts, 0, 3);
			}
		}

		foreach($custom_posts as $custom_post) {

			$permalink = get_permalink($custom_post['ID']);

			$image_html = '';
			$cta_html   = '';
			$image_class = 'attachment-medium_large size-medium_large';
			if($cpt_images) {
				

				if($cpt_ctas) {
					$cta_html 	  = '<a style="color:'.$cta['color'].'; background-color:'.$cta['background_color'].'; border-color:'.$cta['background_color'].'" role="button" class="btn-donate btn" href="'.$permalink.'">'.$cta['link_text'].'</a>';
					$image_class .= " hide-onclick";
				}
				//$image_html = '<div class="head-image keep_prop" data-ratiow="499" data-ratioh="460" data-ratiomw="335" data-ratiomh="220">'.get_the_post_thumbnail($custom_post['ID'], 'medium_large', ['class' => $image_class]).$cta_html.'</div>';

				$image = '<img src="'.DEFAULT_IMG_SRC.'" class="'.$image_class.'">';
				if(has_post_thumbnail($custom_post['ID'])) $image = get_the_post_thumbnail($custom_post['ID'], 'medium_large', ['class' => $image_class]);

				$image_html = '<div class="head-image keep_prop" data-ratiow="499" data-ratioh="460" data-ratiomw="335" data-ratiomh="220">'.$image.$cta_html.'</div>';

			}

			$title_html = '';
			if($cpt_titles && $title = get_the_title($custom_post['ID']) ) {
				$title_html = '<div class="articles-row-title">'.$title.'</div>';
				if($cpt_dates) {
					$date = get_field('event_date', $custom_post['ID'], false);
					$date = new DateTime($date);

					$time_string = '<br/><time class="entry-date published updated" datetime="%1$s">%2$s</time>';
					$time_string = sprintf( $time_string, esc_attr( $date->format('c') ), esc_html( $date->format('jS F') ) );
					$title_html = '<div class="articles-row-title">'.$title.$time_string.'</div>';
				}
			}

			$excerpt_html = '';
			if($cpt_excerpts) {
				$excerpt = get_the_excerpt($custom_post['ID']);
				if(!$excerpt) {
					$excerpt = get_the_content($custom_post['ID']);
				}
				$excerpt = apply_filters('the_content', wp_trim_words($excerpt,35));

				$excerpt_html = '<div class="articles-row-excerpt excerpt-text">'.$excerpt.'</div>';
			}

			$atag_open  = '';
			$atag_close = '';
			if(!$cpt_ctas) {
				$atag_open  = '<a href="'.$permalink.'">';
				$atag_close = '</a>';
			}

			$html .= '
			<div class="col-md-4">
			  	'.$atag_open.'
					'.$image_html.'
					'.$title_html.'
					'.$excerpt_html.'
			  	'.$atag_close.'
			</div>';
		}
	}



	$html .= '</div>
		</div>
	</section>';

	echo $html;
?>
