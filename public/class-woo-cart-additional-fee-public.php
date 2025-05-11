<?php
/**
 * This file contains the definition of the Woo_Cart_Additional_Fee_Public class, which
 * is used to load the plugin's public-facing functionality.
 *
 * @package       Woo_Cart_Additional_Fee
 * @subpackage    Woo_Cart_Additional_Fee/public
 * @author        Sajjad Hossain Sagor <sagorh672@gmail.com>
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version and other methods.
 *
 * @since    2.0.0
 */
class Woo_Cart_Additional_Fee_Public {
	/**
	 * The ID of this plugin.
	 *
	 * @since     2.0.0
	 * @access    private
	 * @var       string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since     2.0.0
	 * @access    private
	 * @var       string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since     2.0.0
	 * @access    public
	 * @param     string $plugin_name The name of the plugin.
	 * @param     string $version     The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version     = $version;
	}

	/**
	 * Applies extra fee to the cart based on condition.
	 *
	 * @since     2.0.0
	 * @access    public
	 */
	public function apply_fee() {
		global $woocommerce;

		// check if additional fee checkbox is enabled.
		$enabled = get_option( 'wcfee_enable', 'no' );

		// if enabled apply fee to cart.
		if ( 'yes' === $enabled ) {
			// fee label, Defults to 'Additional Fee'.
			$wcfee_label = get_option( 'wcfee_label', __( 'Additional Fee', 'woo-cart-additional-fee' ) );

			// check whether fee is fixed or percentage type.
			$wcfee_type = get_option( 'wcfee_type', '' );

			// check if minimum cart ammount is enabled.
			$wcfee_enable_minimum = get_option( 'wcfee_enable_minimum', 'no' );

			// check if maximum cart ammount is enabled.
			$wcfee_enable_maximum = get_option( 'wcfee_enable_maximum', 'no' );

			// get cart total ammount.
			$cart_total = floatval( $woocommerce->cart->cart_contents_total );

			// check if fee is for only specific products.
			$wcfee_enable_product_filter = get_option( 'wcfee_enable_product_filter', 'no' );

			// check if fee is for only specific country.
			$wcfee_country_filter = get_option( 'wcfee_country_filter', array() );

			$apply_fee = true;

			if ( 'fixed' === $wcfee_type ) {
				$fee = floatval( get_option( 'wcfee_fixed', 0 ) );
			} elseif ( 'percentage' === $wcfee_type ) {
				$fee = ( floatval( get_option( 'wcfee_percentage', 0 ) ) / 100 ) * $cart_total;
			} else {
				return; // if no fee type is selected do nothing.
			}

			// check if minumum cart ammount is more than allowed.
			if ( 'yes' === $wcfee_enable_minimum && 'yes' !== $wcfee_enable_maximum ) {
				$wcfee_minimum_amount = floatval( get_option( 'wcfee_minimum', 0 ) );
				$apply_fee            = ( $cart_total >= $wcfee_minimum_amount ) ? true : false;
			}

			// check if maximum cart ammount is less than allowed.
			if ( 'yes' !== $wcfee_enable_minimum && 'yes' === $wcfee_enable_maximum ) {
				$wcfee_maximum_amount = floatval( get_option( 'wcfee_maximum', 0 ) );
				$apply_fee            = ( $cart_total <= $wcfee_maximum_amount ) ? true : false;
			}

			// check if minumum & maximum cart ammount is in range.
			if ( 'yes' === $wcfee_enable_minimum && 'yes' === $wcfee_enable_maximum ) {
				$wcfee_minimum_amount = floatval( get_option( 'wcfee_minimum', 0 ) );
				$wcfee_maximum_amount = floatval( get_option( 'wcfee_maximum', 0 ) );
				$apply_fee            = ( $cart_total >= $wcfee_minimum_amount ) && ( $cart_total <= $wcfee_maximum_amount ) ? true : false;
			}

			if ( is_user_logged_in() && $wcfee_country_filter && ! empty( $wcfee_country_filter ) ) {
				$user_id          = get_current_user_id();
				$billing_country  = get_user_meta( $user_id, 'billing_country', true );
				$shipping_country = get_user_meta( $user_id, 'shipping_country', true );
				$apply_fee        = false;

				if ( ! empty( $billing_country ) && in_array( $billing_country, $wcfee_country_filter, true ) ) {
					$apply_fee = true;
				} elseif ( ! empty( $shipping_country ) && in_array( $shipping_country, $wcfee_country_filter, true ) ) {
					$apply_fee = true;
				}
			}

			// check if fee is applicable.
			if ( $apply_fee ) {
				// get products of enabled fee for.
				$wcfee_product_list = array_map( 'intval', get_option( 'wcfee_product_filter' ) ); // convert string values to int.

				// check if fee is for only specific products is not empty.
				if ( $wcfee_product_list && ! empty( $wcfee_product_list ) ) {
					foreach ( $woocommerce->cart->get_cart() as $cart_item ) {
						$product_id = $cart_item['product_id'];

						// check if enabled product is in cart.
						if ( in_array( $product_id, $wcfee_product_list, true ) ) {
							// apply fee and quit.. to avoid repeated add fee.
							$woocommerce->cart->add_fee( $wcfee_label, $fee );
							break;
						}
					}
				} else {
					// add fee to cart with provided lable and fee ammount.
					$woocommerce->cart->add_fee( $wcfee_label, $fee );
				}
			}
		}
	}
}
