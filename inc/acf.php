<?php
/**
 * Codigo Theme Advanced Custom Fields plugin setup
 * php version 7.4.1
 *
 * @package    Understrap
 * @subpackage Codigo
 * @author     Pablo Rica <pablo@codigo.co.uk>
 * @license    MIT 
 * @version    GIT: @1.0.0@
 * @link       link
 * @since      Codigo 1.0
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

// Exit if ACF is not enabled.
if ( ! function_exists( 'get_field' ) ) {
	exit;
}
