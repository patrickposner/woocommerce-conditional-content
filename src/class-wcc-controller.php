<?php

namespace wcc;

class WCC_Controller {

	/**
	 * Get instance of WCC_Controller
	 *
	 * @return object
	 */
	public function get_instance() {
		$shortcode = new WCC_Controller();
		return $shortcode;
	}

	/**
	 * WCC_Controller constructor
	 */
	public function __construct() {
		add_shortcode( 'conditional-content', array( $this, 'render_shortcode' ) );
		add_filter( 'woocommerce_is_purchasable', array( $this, 'is_product_purchasable' ), 10, 2 );
		add_action( 'woocommerce_single_product_summary', array( $this, 'show_notice_if_not_purchasable' ), 99 );
		add_shortcode( 'hide-notice', array( $this, 'show_notice_if_not_purchasable_shortcode' ) );
	}

	/**
	 * Add shortcode to show or hide content based on user role
	 *
	 * @param array  $atts additional attributes.
	 * @param string $content the current content inside the shortcode.
	 * @return void
	 */
	public function render_shortcode( $atts, $content = null ) {

		$output = '';

		if ( is_user_logged_in() ) {
			$validation = WCC_Helper::is_user_valid( $atts );

			if ( true === $validation ) {
				$output = $content;
			} elseif ( current_user_can( 'administrator' ) ) {
				$output = $content;
			}
		}

		$output = apply_filters( 'the_content', $output );
		return $output;
	}


	/**
	 * Returns true or false based on user role
	 *
	 * @param string $role the given user role.
	 * @return bool
	 */
	public static function conditional_content( $role ) {
		$valid        = false;
		$atts['role'] = $role;

		if ( is_user_logged_in() ) {
			$validation = WCC_Helper::is_user_valid( $atts );

			if ( true === $validation ) {
				$valid = true;
			} elseif ( current_user_can( 'administrator' ) ) {
				$valid = true;
			}
		}

		return $valid;
	}

	/**
	 * Filter if product is purchasable based on product meta
	 *
	 * @param  bool   $is_purchasable purchasable or not.
	 * @param  object $object current product object.
	 * @return boolean
	 */
	public function is_product_purchasable( $is_purchasable, $object ) {

		$purchase_status = get_post_meta( $object->get_id(), 'show_product_for_user', true );
		$is_purchasable  = true;

		if ( isset( $purchase_status ) && 'yes' === $purchase_status ) {
			$is_purchasable = false;

			if ( is_user_logged_in() ) {
				$user = \wp_get_current_user();
				if ( in_array( 'special_customer', $user->roles ) ) {
					$is_purchasable = true;
				}
			}
		}
		return $is_purchasable;
	}
	/**
	 * Show notice based on purchase status
	 *
	 * @return void
	 */
	public function show_notice_if_not_purchasable() {

		$product_id      = \get_the_id();
		$purchase_status = get_post_meta( $product_id, 'show_product_for_user', true );
		$notice          = '';

		if ( isset( $purchase_status ) && 'yes' === $purchase_status ) {

			if ( is_user_logged_in() ) {
				$user = \wp_get_current_user();

				if ( ! in_array( 'special_customer', $user->roles ) ) {
					$notice = apply_filters( 'wcc_hide_notice', __( 'This product is only available for special customers.', 'woocommerce-conditional-content' ) );
				}
			} else {
				$notice = apply_filters( 'wcc_hide_notice', __( 'This product is only available for special customers.', 'woocommerce-conditional-content' ) );
			}
		}
		echo '<div class="wcc-notice">' . esc_html( $notice ) . '</div>';
	}

	/**
	 * Show notice based on purchase status
	 *
	 * @return void
	 */
	public function show_notice_if_not_purchasable_shortcode() {

		$product_id      = \get_the_id();
		$purchase_status = get_post_meta( $product_id, 'show_product_for_user', true );
		$notice          = '';

		if ( isset( $purchase_status ) && 'yes' === $purchase_status ) {

			if ( is_user_logged_in() ) {
				$user = \wp_get_current_user();

				if ( ! in_array( 'special_customer', $user->roles ) ) {
					$notice = apply_filters( 'wcc_hide_notice', __( 'This product is only available for special customers.', 'woocommerce-conditional-content' ) );
				}
			} else {
				$notice = apply_filters( 'wcc_hide_notice', __( 'This product is only available for special customers.', 'woocommerce-conditional-content' ) );
			}
		}
		$shortcode = '<div class="wcc-notice">' . esc_html( $notice ) . '</div>';
		return $shortcode;
	}
}
