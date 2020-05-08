<?php /*>*/
/**
 * Template options 
 */

if( function_exists('acf_add_options_page') ) {

	acf_add_options_page(array(
		'page_title' 	=> 'Theme General Settings',
		'menu_title'	=> 'Theme Settings',
		'menu_slug' 	=> 'theme-general-settings',
		'capability'	=> 'edit_posts',
	));

	acf_add_options_sub_page(array(
		'page_title' 	=> 'Theme Settings: General',
		'menu_title'	=> 'General',
		'parent_slug'	=> 'theme-general-settings',
	));

		
	// acf_add_options_sub_page(array(
	// 	'page_title' 	=> 'Theme Settings: Header',
	// 	'menu_title'	=> 'Header',
	// 	'parent_slug'	=> 'theme-general-settings',
	// ));
	
	// acf_add_options_sub_page(array(
	// 	'page_title' 	=> 'Theme Settings: News',
	// 	'menu_title'	=> 'News',
	// 	'parent_slug'	=> 'theme-general-settings',
	// ));
	acf_add_options_sub_page(array(
		'page_title' 	=> 'Theme Settings: Footer',
		'menu_title'	=> 'Footer',
		'parent_slug'	=> 'theme-general-settings',
		
	));
	
	acf_add_options_sub_page(array(
		'page_title' 	=> 'Theme Settings: 404',
		'menu_title'	=> '404',
		'parent_slug'	=> 'theme-general-settings',
		
	));

}

if( function_exists('get_field') ) {
	function rampa_login_logo() { 
		$array_image=wp_get_attachment_image_src(get_field('login_logo','option'), 'medium');
		$logoimg=$array_image[0];
		
		$logowidth=get_field('login_logo_width','option');
		$logoheight=get_field('login_logo_height','option');
		
		if($logoimg) {
		?>
	<style type="text/css">
		#login h1 a, .login h1 a {
			background-image: url(<?php echo $logoimg; ?>);
			width:<?php echo $logowidth; ?>;
			height:<?php echo $logoheight; ?>;
			background-size: cover;
			background-repeat: no-repeat;
		}
	</style>
	<?php }
	}
	add_action( 'login_enqueue_scripts', 'rampa_login_logo' );
}