<?php

namespace wcc;

class WCC_Metabox {

	/**
	 * Get an instance of WCC_Metabox
	 *
	 * @return object
	 */
	public function get_instance() {
		$metabox = new WCC_Metabox();
		return $metabox;
	}
	/**
	 * WCC_Metabox constructor
	 */
	public function __construct() {
		add_action( 'add_meta_boxes', array( $this, 'add_product_metabox' ) );
		add_action( 'save_post', array( $this, 'save_product_meta' ) );
	}

	/**
	 * Add the Metabox
	 *
	 * @return void
	 */
	public function add_product_metabox() {
		$screens = ['product'];
		foreach ( $screens as $screen ) {
			add_meta_box(
				'show_product_for_user',
				__( 'Product Visibility per Role', 'woocommerce-conditional-content' ),
				array( $this, 'render_html' ),
				$screen,
				'side'
			);
		}
	}

	/**
	 * Save the current meta
	 *
	 * @param  int $post_id current post id.
	 * @return void
	 */
	public function save_product_meta( $post_id ) {
		if ( array_key_exists( 'show_product_for_user', $_POST ) ) {
			update_post_meta(
				$post_id,
				'show_product_for_user',
				$_POST['show_product_for_user']
			);
		}
	}

	/**
	 * Render the html output
	 *
	 * @param object $post the current post object.
	 * @return void
	 */
	public function render_html( $post ) {
		$status = get_post_meta( $post->ID, 'show_product_for_user', true );
		?>
		<label for="show_product_for_user"><?php _e( 'Visible only for Special Customer?', 'woocommerce-conditional-content' ); ?></label>
		<select name="show_product_for_user" id="show_product_for_user" class="postbox">
			<option value="no" <?php selected($status, 'no'); ?>><?php _e( 'No', 'woocommerce-conditional-content' ); ?></option>
			<option value="yes" <?php selected($status, 'yes'); ?>><?php _e( 'Yes', 'woocommerce-conditional-content' ); ?></option>
		</select>
		<?php
	}
}
