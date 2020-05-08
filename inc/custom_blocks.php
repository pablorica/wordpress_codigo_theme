<?php
/**
 * Custom Blocks for Gutenberg
 */


if( function_exists('get_field') ) {
    
    function register_acf_block_types() {

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
        
        $headline_block = array(
            'name'              => 'headline',
            'title'             => __('Headline'),
            'description'       => __('Codigo headline block.'),
            'render_callback'   => 'my_acf_block_render_callback',
            'category'          => 'codigo-blocks',
            'icon'              => 'admin-comments',
            'mode'              => 'preview',
            'keywords'          => array( 'headline', 'comment'),
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

        //acf_register_block_type($carousel_block);
        acf_register_block_type($headline_block);
        //acf_register_block_type($mosaic_block);
        //acf_register_block_type($formatted_block);
        //acf_register_block_type($articles_block);
        //acf_register_block_type($accordion_block);
        //acf_register_block_type($slider_block);
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

}







