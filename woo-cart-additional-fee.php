<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @since          2.0.0
 * @package        Woo_Cart_Additional_Fee
 *
 * Plugin Name:    WooCommerce Cart Additional Fee
 * Plugin URI:     https://wordpress.org/plugins/woo-cart-additional-fee/
 * Description:    Add Additional Fee to your Customer Cart Based on cart amount, minimun cart or maximum cart amount filter and apply fee for specific product item.
 * Version:        2.0.1
 * Author:         Sajjad Hossain Sagor
 * Author URI:     https://sajjadhsagor.com/
 * License:        GPL-2.0+
 * License URI:    http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:    woo-cart-additional-fee
 * Domain Path:    /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 */
define( 'WOO_CART_ADDITIONAL_FEE_VERSION', '2.0.1' );

/**
 * Define Plugin Folders Path
 */
define( 'WOO_CART_ADDITIONAL_FEE_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );

define( 'WOO_CART_ADDITIONAL_FEE_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

define( 'WOO_CART_ADDITIONAL_FEE_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-plugin-activator.php
 *
 * @since    2.0.0
 */
function woo_cart_additional_fee_on_activate() {
	require_once WOO_CART_ADDITIONAL_FEE_PLUGIN_PATH . 'includes/class-woo-cart-additional-fee-activator.php';

	Woo_Cart_Additional_Fee_Activator::on_activate();
}

register_activation_hook( __FILE__, 'woo_cart_additional_fee_on_activate' );

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-plugin-deactivator.php
 *
 * @since    2.0.0
 */
function woo_cart_additional_fee_on_deactivate() {
	require_once WOO_CART_ADDITIONAL_FEE_PLUGIN_PATH . 'includes/class-woo-cart-additional-fee-deactivator.php';

	Woo_Cart_Additional_Fee_Deactivator::on_deactivate();
}

register_deactivation_hook( __FILE__, 'woo_cart_additional_fee_on_deactivate' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 *
 * @since    2.0.0
 */
require WOO_CART_ADDITIONAL_FEE_PLUGIN_PATH . 'includes/class-woo-cart-additional-fee.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    2.0.0
 */
function run_woo_cart_additional_fee() {
	$plugin = new Woo_Cart_Additional_Fee();

	$plugin->run();
}

run_woo_cart_additional_fee();
