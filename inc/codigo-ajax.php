<?php
/**
 * Custom AJAX functions
 * 
 * php version 7.4.1
 *
 * @package    Understrap
 * @subpackage Codigo
 * @author     Pablo Rica <pablo@codigo.co.uk>
 * @license    MIT 
 * @version    GIT: @1.0.0@
 * @link       link
 * @since      Codigo 3.1.7
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Displays the updated checkout form
 * 
 * @return void
 */
function cdg_update_post() {
    // Check for nonce security
    if(isset($_POST['nonce']) && is_string($_POST['nonce'])) {
        $nonce = sanitize_text_field( $_POST['nonce'] );

        if ( ! wp_verify_nonce( $nonce, 'codigo-ajax-nonce' ) ) {
            die ( 'Busted!');
        }
    } else {
        die ( 'No nonce');
    }
    

	$id_post = absint($_POST['id_post']);
	$content = apply_filters('the_content', get_post_field('post_content', $id_post));

	//sleep(2);
	
	echo $content;

	wp_die();
}
add_action( 'wp_ajax_nopriv_cdg-ajax', 'cdg_update_post' );
add_action( 'wp_ajax_cdg-ajax', 'cdg_update_post' );
