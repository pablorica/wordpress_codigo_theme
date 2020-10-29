<?php
/**
 * Custom Blocks for Gutenberg
 */


if( function_exists('get_field') ) {
    
    function register_acf_block_types() {

        
        $headline_block = array(
            'name'              => 'headline',
            'title'             => __('Headline'),
            'description'       => __('Codigo headline block.'),
            'render_callback'   => 'my_acf_block_render_callback',
            //'render_template'   => get_stylesheet_directory()  . '/template-parts/block/content-testimonial.php',
            'category'          => 'codigo-blocks',
            'icon'              => 'admin-comments',
            'mode'              => 'editor', //preview, editor
            'keywords'          => array( 'headline', 'comment'),
        );

        $onecolumn_block = array(
            'name'              => 'one-column',
            'title'             => __('One Column'),
            'description'       => __('Codigo One Column block.'),
            'render_callback'   => 'my_acf_block_render_callback',
            'category'          => 'codigo-blocks',
            'icon'              => 'align-center',
            'mode'              => 'editor',
            'keywords'          => array( 'article', 'section', 'post', 'image', 'link', 'excerpt', 'one column' ),
        );
        $twocolumns_block = array(
            'name'              => 'two-columns',
            'title'             => __('Two Columns'),
            'description'       => __('Codigo Two Columns block.'),
            'render_callback'   => 'my_acf_block_render_callback',
            'category'          => 'codigo-blocks',
            'icon'              => 'columns',
            'mode'              => 'editor',
            'keywords'          => array( 'article', 'section', 'post', 'image', 'link', 'excerpt', 'one column' ),
        );
        $gallery_block = array(
            'name'              => 'gallery-row',
            'title'             => __('Gallery Row'),
            'description'       => __('Codigo list of images displayed in a row.'),
            'render_callback'   => 'my_acf_block_render_callback',
            'category'          => 'codigo-blocks',
            'icon'              => 'format-gallery',
            'mode'              => 'editor',
            'keywords'          => array( 'article', 'post', 'text', 'image', 'gallery' ),
        );
        
        /*
        $info_grid_block = array(
            'name'              => 'info-grid',
            'title'             => __('Info Grid'),
            'description'       => __('Codigo data displayed in a grid.'),
            'render_callback'   => 'my_acf_block_render_callback',
            'category'          => 'codigo-blocks',
            'icon'              => 'editor-kitchensink',
            'mode'              => 'editor',
            'keywords'          => array( 'mosaic', 'data', 'grid', 'text' ),
        );

        $carousel_block = array(
            'name'              => 'carousel',
            'title'             => __('Carousel'),
            'description'       => __('Codigo carousel block.'),
            'render_callback'   => 'my_acf_block_render_callback',
            //'render_template'   => get_stylesheet_directory()  . '/template-parts/block/content-testimonial.php',
            'category'          => 'codigo-blocks',
            'icon'              => 'slides',
            'mode'              => 'preview',
            'keywords'          => array( 'carousel', 'slider', 'image', 'link' ),
        );
        
        $mosaic_block = array(
            'name'              => 'mosaic',
            'title'             => __('Mosaic'),
            'description'       => __('Codigo mosaic block.'),
            'render_callback'   => 'my_acf_block_render_callback',
            'category'          => 'codigo-blocks',
            'icon'              => 'editor-kitchensink',
            'mode'              => 'preview',
            'keywords'          => array( 'mosaic', 'image', 'link' ),
        );

        $formatted_block = array(
            'name'              => 'formatted-section',
            'title'             => __('Formatted Section'),
            'description'       => __('Codigo formatted section block.'),
            'render_callback'   => 'my_acf_block_render_callback',
            'category'          => 'codigo-blocks',
            'icon'              => 'admin-appearance',
            'mode'              => 'preview',
            'keywords'          => array( 'paragraph', 'background', 'columns' ),
        );

        $articles_block = array(
            'name'              => 'articles-row',
            'title'             => __('Articles Row'),
            'description'       => __('Codigo Articles Row block.'),
            'render_callback'   => 'my_acf_block_render_callback',
            'category'          => 'codigo-blocks',
            'icon'              => 'excerpt-view',
            'mode'              => 'preview',
            'keywords'          => array( 'article', 'post', 'image', 'link', 'excerpt' ),
        );
        
        $accordion_block = array(
            'name'              => 'accordion',
            'title'             => __('Accordion'),
            'description'       => __('Codigo Accordion block.'),
            'render_callback'   => 'my_acf_block_render_callback',
            'category'          => 'codigo-blocks',
            'icon'              => 'feedback',
            'mode'              => 'preview',
            'keywords'          => array( 'article', 'post', 'text', 'excerpt' ),
        );
        
        $slider_block = array(
            'name'              => 'images-slider',
            'title'             => __('Images Slider'),
            'description'       => __('Codigo Simple Images Slider block.'),
            'render_callback'   => 'my_acf_block_render_callback',
            'category'          => 'codigo-blocks',
            'icon'              => 'format-gallery',
            'mode'              => 'preview',
            'keywords'          => array( 'article', 'post', 'text', 'excerpt' ),
        );
        */

        acf_register_block_type($headline_block);
        acf_register_block_type($onecolumn_block);
        acf_register_block_type($twocolumns_block);
        acf_register_block_type($gallery_block);
    }

    // Check if function exists and hook into setup.
    if( function_exists('acf_register_block_type') ) {
        add_action('acf/init', 'register_acf_block_types');
    }

    function my_acf_block_render_callback( $block ) {
    	
    	// convert name ("acf/carousel") into path friendly slug ("carousel")
    	$slug = str_replace('acf/', '', $block['name']);
    	
    	// include a template part from within the "template-parts/block" folder
    	if( file_exists( get_theme_file_path("/blocks/content-{$slug}.php") ) ) {
    		include( get_theme_file_path("/blocks/content-{$slug}.php") );
    	}
    }


    /**
     * Try to disable wpautop inside ACF blocks.
     *
     * @link https://wordpress.stackexchange.com/q/321662/26317
     *
     * @param string $block_content The HTML generated for the block.
     * @param array  $block         The block.
     *
     * This is a temporary solution discussed in
     * https://github.com/WordPress/gutenberg/issues/12530
     */
    // remove_filter( 'the_content', 'wpautop' );
    // add_filter( 'the_content', function ($content) {
    //     if (has_blocks()) {
    //         return $content;
    //     }

    //     return wpautop($content);
    // });


    /**
     * New Group for Codigo blocks
     *
     * @link https://loomo.ca/gutenberg-creating-custom-block-categories/
     *
     */
    function codigo_block_category( $categories, $post ) {
        return array_merge(
            $categories,
            array(
                array(
                    'slug' => 'codigo-blocks',
                    'title' => __( 'Codigo', 'codigo-blocks' ),
                ),
            )
        );
    }
    add_filter( 'block_categories', 'codigo_block_category', 10, 2);


    /**
     * Display column
     *
     *
     */
    function my_acf_block_column( $body ) {
    	
        $htmlBody   = '';
        
        $inner_col_class = "col-xl-6";
        if($body['classname'] == 'onecolumn') {
            $inner_col_class = "col-md-6";
        }

        if($body['type'] == 'image') {
            if(!$body['mobile_image'])  $body['mobile_image']  = $body['desktop_image'];
            if(!$body['desktop_image']) $body['desktop_image'] = $body['mobile_image'];

            $mobile_image  = wp_get_attachment_image($body['mobile_image'], 'large', false, array('class'=>'d-md-none m-auto'));
            $desktop_image = wp_get_attachment_image($body['desktop_image'], 'full', false, array('class'=>'d-none d-md-block m-auto'));
            $htmlBody .= '
            <div class="gblock__'.$body['classname'].'_body--image row">
                <div class="col-md-12 text-center '.($body['animation'] ? 'animate-children fade_in_up' : '').'">
                    <figure>
                        '.$mobile_image.'
                        '.$desktop_image.'
                    </figure>
                </div>
            </div>';
        }
        elseif($body['type'] == 'carousel') {

            $htmlBody   = '';
            $block_images = $body['carousel'];
            $carousel_autoplay = ($body['autoplay'] ? 1: 0);
            $carousel_arrows   = ($body['arrows'] ? 1: 0);
            $carousel_navdots  = ($body['dots'] ? true: false);
            if($block_images):
                $htmlBody .= '
                <div class="gblock__'.$body['classname'].'_body--carousel row">
                    <div class="col-md-12 text-center '.($body['animation'] ? 'animate-children fade_in_up' : '').' slick-carousel" data-autoplay='.$carousel_autoplay.' data-arrows='.$carousel_arrows.' data-dots='.$carousel_navdots.' >';
                foreach($block_images as $gal_image ):
                    $htmlBody.= '
                    <div>
                        <figure>'.
                            wp_get_attachment_image( $gal_image['id'], 'full').
                            '<figcaption>'.esc_html($gal_image['caption']).'</figcaption>
                        </figure>
                    </div>';

                endforeach;
                $htmlBody .= '</div>
                </div>';
            endif; 
        }
        elseif($body['type'] == 'text') {
            if($body['text']){
                $htmlBody .= '
                <div class="gblock__'.$body['classname'].'_body--content row">
                    <div class="'.$inner_col_class.' mx-auto '.($body['animation'] ? 'animate-children fade_in_up' : '').'">
                    '.$body['text'].'
                    </div>
                </div>';
            }
        }
        elseif($body['type'] == 'quote') {
            $htmlBody .= '
            <div class="gblock__'.$body['classname'].'_body--content row">
                <div class="'.$inner_col_class.' mx-auto '.($body['animation'] ? 'animate-children fade_in_up' : '').'">
                    <blockquote>
                        <p>'.$body['quote'].'</p>
                    </blockquote>
                    <div class="author mt-auto">'.$body['author'].'</div>
                </div>
            </div>';
        } elseif($body['type'] == 'cta') {
            $cta = $body['cta'];
            $htmlBody .= '
            <div class="gblock__'.$body['classname'].'_body--content row">
                <div class="'.$inner_col_class.' mx-auto '.($body['animation'] ? 'animate-children fade_in_up' : '').'">
                    <p>'.$body['cta_text'].'</p>
                    <a class="btn btn-primary btn-cta" role="button" href="'.$cta['url'].'" target="'.$cta['target'].'">'.$cta['title'].'</a>
                </div>
            </div>';
        }
        else {
            $type 		= $body['type'];
            $video_url  = $body['video_url']; 
            $video_file['desktop'] = $body['desktop_video']; 
            $video_file['mobile']  = $body['mobile_video']; 
            $args = array(
                'controls' => $body['controls'],
                'autoplay' => $body['autoplay'],
                'loop' 	   => $body['loop'],
                'muted'    => $body['muted'],
            );
            //error_log($type);
            ob_start(); ?>
            <div class="gblock__<?=$body['classname']?>_body--video <?=($body['animation'] ? 'animate-single fade_in_up' : '')?>">
                <?php //get_template_part( 'global-templates/component', 'video' ); ?>
                <?php include(locate_template('global-templates/component-video.php')); ?>
            </div>
            <?php
            $htmlBody .= ob_get_contents();
            ob_end_clean();
        }

        return $htmlBody;
    }

}

/**
 * CSS on CMS
 *
 *
 */
add_action('admin_head', 'hb_acf_custom_fonts');
function hb_acf_custom_fonts() {
    echo '  
    <style>
        .acf-block-preview {
            background-color: #EEE;
            padding: 10px;
        }
        
        .acf-block-preview video {
            height: 300px;
            padding: 0;
            object-fit: cover;
            width: 100%;
        }
        .acf-block-preview .component_video {
            position: relative;
        }
        .acf-block-preview .component_video button.video_control {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 42px;
            height: 42px;
            background: transparent;
            background-image: url(data:image/png;base64,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);
            background-position: center;
            background-size: contain;
            background-repeat: no-repeat;
            border: none;
            z-index: 10;
        }
        .acf-block-preview img + img,
        .acf-block-preview .component_video + .component_video {
            display:none;
        }

        .acf-block-preview .slick-carousel {
            position:relative
        }
        .acf-block-preview .slick-carousel div + div {
            display:none;
        }
        .acf-block-preview .slick-carousel::before,
        .acf-block-preview .slick-carousel::after {
            content: "";
            font-size: 15px;
            width: 15px;
            height: 15px;
            color: white;
            border: solid 2px white;
            border-radius: 50%;
            opacity: 1;
            position: absolute;
            top:80%;
            z-index:10;
        }
        .acf-block-preview .slick-carousel::before {
            left:calc(50% - 18px);
        }
        .acf-block-preview .slick-carousel::after {
            right:calc(50% - 18px);
            background-color: white;
        }
    </style>';
}