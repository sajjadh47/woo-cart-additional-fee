<?php

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      2.0.0
 * @package    Woo_Cart_Additional_Fee
 * @subpackage Woo_Cart_Additional_Fee/includes
 * @author     Sajjad Hossain Sagor <sagorh672@gmail.com>
 */
class Woo_Cart_Additional_Fee_i18n
{
	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    2.0.0
	 */
	public function load_plugin_textdomain()
	{
		load_plugin_textdomain(
			'woo-cart-additional-fee',
			false,
			dirname( WOO_CART_ADDITIONAL_FEE_PLUGIN_BASENAME ) . '/languages/'
		);
	}
}
