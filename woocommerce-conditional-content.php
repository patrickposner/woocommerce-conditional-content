<?php
/*
Plugin Name: WooCommerce Conditional Content
Text Domain: woocommerce-conditional-content
Description: Plugin to add a shortcode to show or hide content based on user group
Author: patrickposner
Version: 1.0
*/

/* localize */
$textdomain_dir = plugin_basename( dirname( __FILE__ ) ) . '/languages';
load_plugin_textdomain( 'woocommerce-conditional-content', false, $textdomain_dir );

/**
 * Register a new user role on plugin activation
 *
 * @return void
 */
function add_roles_on_plugin_activation() {
	add_role( 'special_customer', __( 'Special Customer', 'woocommerce-conditional-content' ), array( 'read' => true, 'edit_posts' => false ) );
}

register_activation_hook( __FILE__, 'add_roles_on_plugin_activation' );


/**
 * Add shortcode to show or hide content based on user role
 *
 * @param array  $atts additional attributes.
 * @param string $content the current content inside the shortcode.
 * @return void
 */
function render_shortcode( $atts, $content = null ) {

	$output = '';

	if ( is_user_logged_in() ) {
		$validation = is_user_valid( $atts );

		if ( true === $validation ) {
			$output = $content;
		} elseif ( current_user_can( 'administrator' ) ) {
			$output = $content;
		}
	}

	$output = apply_filters( 'the_content', $output );
	return $output;
}

add_shortcode( 'conditional-content', 'render_shortcode' );

/**
 * Return wether or not the user is allowed to see the given content
 *
 * @param array $atts attributes from the shortcode.
 * @return boolean
 */
function is_user_valid( $atts ) {
	$unlock = false;
	$user   = wp_get_current_user();
	if ( isset( $atts['role'] ) && ! empty( $atts['role'] ) ) {
		$roles = $user->roles;
		if ( strpos( $atts['role'], ',' ) !== false ) {
			$roles_array = explode( ',', $atts['role'] );
			foreach ( $roles_array as $role ) {
				if ( $role === $roles[0] ) {
					$unlock = true;
				}
			}
		} else {
			if ( $atts['role'] === $roles[0] ) {
				$unlock = true;
			}
		}
	}
	if ( isset( $atts['user'] ) && ! empty( $atts['user'] ) ) {
		if ( strpos( $atts['user'], ',' ) !== false ) {
			$users_array = explode( ',', $atts['user'] );
			foreach ( $users_array as $user ) {
				if ( $user === $user->user_login ) {
					$unlock = true;
				}
			}
		} else {
			if ( $atts['user'] === $user->user_login ) {
				$unlock = true;
			}
		}
	}
	return $unlock;
}
/**
 * Returns true or false based on user role
 *
 * @param string $role the given user role.
 * @return bool
 */
function conditional_content( $role ) {
	$valid        = false;
	$atts['role'] = $role;

	if ( is_user_logged_in() ) {
		$validation = is_user_valid( $atts );

		if ( true === $validation ) {
			$valid = true;
		} elseif ( current_user_can( 'administrator' ) ) {
			$valid = true;
		}
	}

	return $valid;
}

