<?php
/**
 * Template Name: Offices
 *
 * Offices for codigo
 *
 * @package codigo
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;


if (!function_exists('get_field')) {
	exit; // Exit if ACF is not enabled 
}


get_header(); 

$google_api_key = get_field('general_google_api_key','option');
?>


<main  class="wrapper">
	<div class="container-fluid">
		<div class="row">
            <div class="col-md-12 p-0">
			

				<?php if (have_posts()): while (have_posts()) : the_post(); ?>

                <script src="https://maps.googleapis.com/maps/api/js?key=<?=$google_api_key?>"></script>
                <script type="text/javascript">
                (function( $ ) {

                    /**
                    * initMap
                    *
                    * Renders a Google Map onto the selected jQuery element
                    *
                    * @date    22/10/19
                    * @since   5.8.6
                    *
                    * @param   jQuery $el The jQuery element.
                    * @return  object The map instance.
                    */
                    function initMap( $el ) {

                        // Find marker elements within map.
                        var $markers = $el.find('.marker');
                        var mapID    = $el.attr('id');

                        // Create gerenic map.
                        var mapArgs = {
                            zoom        : $el.data('zoom') || 16,
                            mapTypeId   : google.maps.MapTypeId.ROADMAP
                        };
                        var map = new google.maps.Map( $el[0], mapArgs );

                        // Add markers.
                        map.markers = [];
                        $markers.each(function(){
                            initMarker( $(this), map, mapID );
                        });

                        // Center map based on markers.
                        centerMap( map );

                        $('.nav-item').on('click',function(){
                            
                            if( $(this).data('map')==mapID) {
                                //var newLatlng = new google.maps.LatLng(40.748774, -73.985763);
                                var newLatlng = new google.maps.LatLng($(this).data('lat'), $(this).data('lng'));
                                map.markers[0].setPosition(newLatlng);
                                map.panTo( newLatlng ); //center map to new position
                            }

                        });

                        // Return map instance.
                        return map;
                    }

                    /**
                    * initMarker
                    *
                    * Creates a marker for the given jQuery element and map.
                    *
                    * @date    22/10/19
                    * @since   5.8.6
                    *
                    * @param   jQuery $el The jQuery element.
                    * @param   object The map instance.
                    * @return  object The marker instance.
                    */
                    function initMarker( $marker, map, mapID ) {

                        // Get position from marker.
                        var lat = $marker.data('lat');
                        var lng = $marker.data('lng');
                        var latLng = {
                            lat: parseFloat( lat ),
                            lng: parseFloat( lng )
                        };

                        // Create marker instance.
                        marker = new google.maps.Marker({
                            position : latLng,
                            map: map
                        });

                        // Append to reference for later use.
                        map.markers.push( marker );

                        // If marker contains HTML, add it to an infoWindow.
                        if( $marker.html() ){

                            // Create info window.
                            var infowindow = new google.maps.InfoWindow({
                                content: $marker.html()
                            });

                            // Show info window when marker is clicked.
                            google.maps.event.addListener(marker, 'click', function() {
                                infowindow.open( map, marker );
                            });
                        }

                        
                    }

                    /**
                    * centerMap
                    *
                    * Centers the map showing all markers in view.
                    *
                    * @date    22/10/19
                    * @since   5.8.6
                    *
                    * @param   object The map instance.
                    * @return  void
                    */
                    function centerMap( map ) {

                        // Create map boundaries from all map markers.
                        var bounds = new google.maps.LatLngBounds();
                        map.markers.forEach(function( marker ){
                            bounds.extend({
                                lat: marker.position.lat(),
                                lng: marker.position.lng()
                            });
                        });

                        // Case: Single marker.
                        if( map.markers.length == 1 ){
                            map.setCenter( bounds.getCenter() );

                        // Case: Multiple markers.
                        } else{
                            map.fitBounds( bounds );
                        }
                    }


                    // Render maps on page load.
                    $(document).ready(function(){
                        var map;
                        $('.acf-map').each(function(){
                            map = initMap( $(this) );
                        });
                        
                        
                    });

                })(jQuery);
                </script>

                <!-- article -->
				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>


                    <section id="header" class="section section-header d-flex hv-100" style="background-image: url(<?php echo get_the_post_thumbnail_url($post->ID, 'full')?>);background-size: cover;">
                        <div class="gblock__headline--wrapper container-fluid  mt-auto mb-5  d-flex flex-column">
                            <div class="row gblock__headline_body--text scene_element scene_element--fadeinup">
                                <div class="gblock__headline_body--content hv-50 d-flex flex-column col-md-12 ">
                                    <h1 class=" gblock__headline_body--htext text-center" style="color:<?php the_field('pageoptions_title_color') ?>"><?php the_title(); ?></h1>
                                </div>
                            </div>
                        </div>
                    </section>
                

                    <section id="contact" class="section section-contact mt-5">
                        <div class="gblock__headline--wrapper container-fluid pt-md-5 ">
                            <div class="row">

                                <div class="col-md-6 order-md-last mb-5">
                                    <?php the_content(); ?>
                                </div><!-- /.col-md-6 -->

                                <div class="col-md-6">

                                    

                                        <?php
                                        $terms = get_terms( array(
                                            'taxonomy' => 'group',
                                            'hide_empty' => false,
                                        ) );
                                        if($terms):
                                            $tint = 0;
                                            foreach($terms as $term):
                                                echo '<h3 class="mb-5">' . $term->name . '</h3>';

                                                $args = array(
                                                    'posts_per_page'   => -1,
                                                    'post_type'        => 'office',
                                                    'tax_query'         => array(
                                                        array(
                                                            'taxonomy' => 'group',
                                                            'field'    => 'id',
                                                            'terms'    => $term->term_id,
                                                        ),
                                                    ),
                                                );
                                                $the_query = new WP_Query( $args );
                                                
                                                if ( $the_query->have_posts() ) {
                                                    $pint        = 0;
                                                    $htmlTabs    = '';
                                                    $htmlContent = '';
                                                    $orilatlng   = false;

                                                    while ( $the_query->have_posts() ) {
                                                        $the_query->the_post();

                                                        $active   = ($pint==0?'active':'');
                                                        $selected = ($pint==0?'true':'false');
                                                        $show     = ($pint==0?'show':'');

                                                        $latlng = '';
                                                        if( $location = get_field('office_map') ) {
                                                            $latlng = 'data-lat="'.esc_attr($location['lat']).'" data-lng="'.esc_attr($location['lng']).'"';
                                                        }
                                                        if(!$orilatlng) $orilatlng = $latlng;

                                                        $htmlTabs.= '
                                                        <li class="nav-item mb-3" data-map="map'.$tint.'" '.$latlng.'>
                                                            <a class="btn btn-tertiary '.$active.'" id="tab-'.get_the_ID().'"  data-toggle="tab" href="#content'.get_the_ID().'" role="tab" aria-controls="content'.get_the_ID().'" aria-selected="'.$selected.'">'.get_the_title().'</a>
                                                        </li>';

                                                        $htmlContent.= '
                                                        <div class="tab-pane fade '.$show.' '.$active.'" id="content'.get_the_ID().'" role="tabpanel" aria-labelledby="tab-'.get_the_ID().'">';

                                                        if($office_address = get_field('office_address')) {
                                                            $htmlContent.= '<div class="office_address mb-4">'.$office_address.'</div>';
                                                        }
                                                        if($office_email = get_field('office_email')) {
                                                            $htmlContent.= '<div class="office_email mb-4">e-mail: '.$office_email.'</div>';
                                                        }
                                                        if($office_tax = get_field('office_tax')) {
                                                            $htmlContent.= '<div class="office_tax">Tax Number: '.$office_tax.'</div>';
                                                        }
                                                        if($office_reg = get_field('office_reg')) {
                                                            $htmlContent.= '<div class="office_reg">Reg. Number: '.$office_reg.'</div>';
                                                        }

                                                        $htmlContent.= '
                                                        </div>';

                                                        $pint++;

                                                    }
                                                    
                                                } ?>
                                                

                                                <div class="row">
                                                    <div class="col-lg-6">
                                                        <ul class="nav nav-tabs flex-column" id="officeGroup<?=$tint?>" role="tablist">
                                                            <?php echo $htmlTabs ?>
                                                        </ul>
                                                    </div>
                                                    <div class="tab-content col-lg-6" id="officeGroupContent<?=$tint?>">
                                                        <?php echo $htmlContent?>
                                                    </div>
                                                </div>

                                                <div id="map<?=$tint?>" class="acf-map mt-3 mb-5" data-zoom="16">
                                                    <div class="marker" <?php echo $orilatlng; ?>></div>
                                                </div><?php
                                                $tint++;

                                                /* Restore original Post Data */
                                                wp_reset_postdata();

                        
                                            endforeach;
                                        endif;
                                        ?>

                                    

                                    
                                </div><!-- /.col-md-6 -->

                                

                            </div>
                        </div>
                    </section>

                </article><!-- /article -->

				<?php endwhile;  endif; ?>

                
            </div><!-- /.col-md-12 -->
		</div><!-- /.row -->
	</div><!-- /.container -->
</main>
<?php get_footer(); ?>



