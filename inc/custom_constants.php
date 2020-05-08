<?php 
/**
 * Custom Constants.
 * 
 * 
 */


if (function_exists('get_field')) {

	if(get_field('settings_general_default_img','option')) {
		$array_default_image=wp_get_attachment_image_src(get_field('settings_general_default_img','option'), 'large');
		define('DEFAULT_IMG_SRC', $array_default_image[0]);	
	} else {
		define('DEFAULT_IMG_SRC', '');	
	}
}