<?php /*>*/
/**
 * WooCommerce support
 *
 * @package codigo
 */



/**
* First unhook the Understrap wrappers
*/
add_action( 'init', 'codigo_remove_woocommerce_action');
function codigo_remove_woocommerce_action() {
    remove_action( 'woocommerce_before_main_content', 'understrap_woocommerce_wrapper_start', 10);
	remove_action( 'woocommerce_after_main_content', 'understrap_woocommerce_wrapper_end', 10);
}

/**
* Second unhook the WooCommerce hooks
*/
//Breadcrumbs
remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0);

//Related Products
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20, 0);


/**
* Then hook in your own functions to display the wrappers your theme requires
*/
add_action('woocommerce_before_main_content', 'codigo_woocommerce_wrapper_start', 10);
add_action('woocommerce_after_main_content', 'codigo_woocommerce_wrapper_end', 10);
if ( ! function_exists( 'codigo_woocommerce_wrapper_start' ) ) {
	function codigo_woocommerce_wrapper_start() {
		echo '<div class="wrapper wrapper-page wrapper-woocommerce" id="woocommerce-wrapper">';
		
		/* Hero */ 
		$page_id=get_option( 'woocommerce_shop_page_id' ); 
		//echo $page_id;
		//We use 'include locate_template' instead of 'get_template_part' to pass $page_id var (no global var)
		//include( locate_template( 'global-templates/hero.php') ); 
		//get_template_part( 'global-templates/hero' );
		
		$shop_page_link=get_permalink($page_id);
		$shop_page_text='Back to<br>shop';
		
		/* Main */
		echo '<section class="main">';
		echo '<div class="container">';
		echo '<div class="row">';
		if(!is_shop()) {
			include( locate_template( 'global-templates/left-sidebar-back.php') ); 
		} else {
			include( locate_template( 'global-templates/left-sidebar.php') ); 
		}
		echo '<main class="main__content" id="main">';
		echo '<div class="entry-content">';
		if(is_product_category()) {
			//$category = get_the_category(); 
			$category = get_queried_object();
			//var_dump($category);
			echo $category->name;
		}
		else {
			//do_action( 'woocommerce_archive_description' );
		}
		echo '</div><!-- .entry-content -->';
		echo '</main><!-- #main -->';
		echo '</div><!-- .col -->';
		include( locate_template( 'global-templates/right-sidebar.php') ); 
		echo '</div><!-- .row -->';
		echo '</div><!-- Container end -->';
		echo '</section>';
		
		/* Products */
		echo '<section class="products">';
		echo '<div class="container">';
		
	}
}
if ( ! function_exists( 'codigo_woocommerce_wrapper_end' ) ) {
function codigo_woocommerce_wrapper_end() {
	
	$page_id=get_option( 'woocommerce_shop_page_id' ); 
	
	/* End Products */
	echo '</div><!-- container end -->';
	echo '</section><!-- products end -->';
	
	/* Sections */
	if( have_rows('page_sections',$page_id) ) {
		$array_default_image=wp_get_attachment_image_src(get_field('general_background','option'), 'retina');
		$default_image=$array_default_image[0];
		echo '<section class="sections">';
		echo '<div class="container sections__container"> ';
		echo '<div class="row">';
		while ( have_rows('page_sections',$page_id) ) : the_row();
		
					$section_title=get_sub_field('title');
					$section_title_colour=get_sub_field('title_colour');
					
					$section_url='#';
					if(get_sub_field('link_type')=='url') {
						$section_url=get_sub_field('url');
					}
					if(get_sub_field('link_type')=='internal') {
						$section_url=get_sub_field('post_link');
					}
					
					$section_image=$default_image;
					if(get_sub_field('image')) {
						$array_image=wp_get_attachment_image_src(get_sub_field('image'), 'large');
						$section_image=$array_image[0];
					}
			  echo '<div class="col-md-4 sections__col">';
			  echo '<a href="'.$section_url.'">';
			  echo '<div class="box keep_prop sections__content " data-ratiow="386" data-ratioh="285" style="background-image:url(\''.$section_image.'\')">';
			  echo '<div class="sections__title" style="color:'.$section_title_colour.'">'.$section_title.'</div>';
			  echo '</div>';
			  echo '</a>';
			  echo '</div>';
		endwhile;
		echo '</div>';
		echo '</div>';
		echo '</section>';		
	} 
	
	
	echo '</div><!-- Wrapper Woocommerce end -->';
 }
}


/**
* Display category name under product title in grid
*/
function codigo_wc_single_product(){

    $product_cats = wp_get_post_terms( get_the_ID(), 'product_cat' );

    if ( $product_cats && ! is_wp_error ( $product_cats ) ){

        $single_cat = array_shift( $product_cats ); ?>

        <h4 itemprop="name" class="woocommerce-loop-product__category"><span><?php echo $single_cat->name; ?></span></h4>

<?php }
}
//add_action( 'woocommerce_shop_loop_item_title', 'codigo_wc_single_product', 15 );


/**
* Grid Product Excerpt
*/
add_action( 'codigo_template_single_excerpt', 'woocommerce_template_single_excerpt', 30 );
/**
* Grid Product Add to cart
*/
add_action( 'codigo_template_single_add_to_cart', 'woocommerce_template_single_add_to_cart', 30 );






/**
* Category name in grid
*/
function codigo_template_loop_category_title( $category ) {
	?>
	<h2 class="woocommerce-loop-category__title"><?php echo esc_html( $category->name ); ?></h2>
    <h4 itemprop="name" class="woocommerce-loop-category__description"><span><?php echo esc_html( $category->description ); ?></span></h4>
    <?php
	if(get_field('prod_cat_general_price',$category)) {
		echo '<span class="price">'.get_field('prod_cat_general_price',$category).'</span>';
	}
	?>
	<?php
}
add_action( 'codigo_shop_loop_subcategory_title', 'codigo_template_loop_category_title', 15 );

