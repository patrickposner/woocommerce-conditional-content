<?php
/*
Plugin Name: WooCommerce Conditional Content
Text Domain: woocommerce-conditional-content
Description: Plugin to add a shortcode to show or hide content based on user group
Author: patrickposner
Version: 1.0
*/

define( 'WCC_URL', untrailingslashit( plugin_dir_url( __FILE__ ) ) );

/* autoloader */
if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
	require __DIR__ . '/vendor/autoload.php';
}

/* localize */
$textdomain_dir = plugin_basename( dirname( __FILE__ ) ) . '/languages';
load_plugin_textdomain( 'woocommerce-conditional-content', false, $textdomain_dir );

/* add user role */
register_activation_hook( __FILE__, 'add_roles_on_plugin_activation' );

/**
 * Add a new user role an plugin activation
 *
 * @return void
 */
function add_roles_on_plugin_activation() {
	add_role( 'special_customer', __( 'Special Customer', 'woocommerce-conditional-content' ), array( 
		'read'       => true,
		'edit_posts' => false,
	) );
}
add_action( 'init', 'setup' );

/**
 * Fire up the plugin classes
 *
 * @return void
 */
function setup() {
	wcc\WCC_Controller::get_instance();
	wcc\WCC_Metabox::get_instance();
}


