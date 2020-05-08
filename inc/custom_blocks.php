<?php
/**
 * Custom Blocks for Gutenberg
 */


if( function_exists('get_field') ) {
    
    function register_acf_block_types() {

        // register a testimonial block.
        acf_register_block_type(array(
            'name'              => 'carousel',
            'title'             => __('Carousel'),
            'description'       => __('TNF carousel block.'),
            'render_callback'   => 'my_acf_block_render_callback',
            //'render_template'   => get_stylesheet_directory()  . '/template-parts/block/content-testimonial.php',
            'category'          => 'rampa-blocks',
            'icon'              => 'slides',
            'mode'              => 'preview',
            'keywords'          => array( 'carousel', 'slider', 'image', 'link' ),
        ));

        acf_register_block_type(array(
            'name'              => 'headline',
            'title'             => __('Headline'),
            'description'       => __('TNF headline block.'),
            'render_callback'   => 'my_acf_block_render_callback',
            'category'          => 'rampa-blocks',
            'icon'              => 'admin-comments',
            'mode'              => 'preview',
            'keywords'          => array( 'headline', 'comment'),
        ));

        acf_register_block_type(array(
            'name'              => 'mosaic',
            'title'             => __('Mosaic'),
            'description'       => __('TNF mosaic block.'),
            'render_callback'   => 'my_acf_block_render_callback',
            'category'          => 'rampa-blocks',
            'icon'              => 'editor-kitchensink',
            'mode'              => 'preview',
            'keywords'          => array( 'mosaic', 'image', 'link' ),
        ));

        acf_register_block_type(array(
            'name'              => 'formatted-section',
            'title'             => __('Formatted Section'),
            'description'       => __('TNF formatted section block.'),
            'render_callback'   => 'my_acf_block_render_callback',
            'category'          => 'rampa-blocks',
            'icon'              => 'admin-appearance',
            'mode'              => 'preview',
            'keywords'          => array( 'paragraph', 'background', 'columns' ),
        ));

        acf_register_block_type(array(
            'name'              => 'articles-row',
            'title'             => __('Articles Row'),
            'description'       => __('TNF Articles Row block.'),
            'render_callback'   => 'my_acf_block_render_callback',
            'category'          => 'rampa-blocks',
            'icon'              => 'excerpt-view',
            'mode'              => 'preview',
            'keywords'          => array( 'article', 'post', 'image', 'link', 'excerpt' ),
        ));

        acf_register_block_type(array(
            'name'              => 'accordion',
            'title'             => __('Accordion'),
            'description'       => __('TNF Accordion block.'),
            'render_callback'   => 'my_acf_block_render_callback',
            'category'          => 'rampa-blocks',
            'icon'              => 'feedback',
            'mode'              => 'preview',
            'keywords'          => array( 'article', 'post', 'text', 'excerpt' ),
        ));

        acf_register_block_type(array(
            'name'              => 'images-slider',
            'title'             => __('Images Slider'),
            'description'       => __('TNF Simple Images Slider block.'),
            'render_callback'   => 'my_acf_block_render_callback',
            'category'          => 'rampa-blocks',
            'icon'              => 'format-gallery',
            'mode'              => 'preview',
            'keywords'          => array( 'article', 'post', 'text', 'excerpt' ),
        ));
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
     * New Group for TNF blocks
     *
     * @link https://loomo.ca/gutenberg-creating-custom-block-categories/
     *
     */
    function rampa_block_category( $categories, $post ) {
        return array_merge(
            $categories,
            array(
                array(
                    'slug' => 'rampa-blocks',
                    'title' => __( 'Rampa', 'rampa-blocks' ),
                ),
            )
        );
    }
    add_filter( 'block_categories', 'rampa_block_category', 10, 2);

}







