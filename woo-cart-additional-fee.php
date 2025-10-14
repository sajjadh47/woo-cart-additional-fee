<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @package           Woo_Cart_Additional_Fee
 * @author            Sajjad Hossain Sagor <sagorh672@gmail.com>
 *
 * Plugin Name:       Cart Additional Fee For WooCommerce
 * Plugin URI:        https://wordpress.org/plugins/woo-cart-additional-fee/
 * Description:       Add Additional Fee to your Customer Cart Based on cart amount, minimun cart or maximum cart amount filter and apply fee for specific product item.
 * Version:           2.0.6
 * Requires at least: 5.6
 * Requires PHP:      8.0
 * Author:            Sajjad Hossain Sagor
 * Author URI:        https://sajjadhsagor.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       woo-cart-additional-fee
 * Domain Path:       /languages
 * Requires Plugins:  woocommerce
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

/**
 * Currently plugin version.
 */
define( 'WOO_CART_ADDITIONAL_FEE_VERSION', '2.0.6' );

/**
 * Define Plugin Folders Path
 */
define( 'WOO_CART_ADDITIONAL_FEE_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );

define( 'WOO_CART_ADDITIONAL_FEE_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

define( 'WOO_CART_ADDITIONAL_FEE_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-woo-cart-additional-fee-activator.php
 *
 * @since    2.0.0
 */
function cafeewoo_on_activate_woo_cart_additional_fee() {
	require_once WOO_CART_ADDITIONAL_FEE_PLUGIN_PATH . 'includes/class-woo-cart-additional-fee-activator.php';

	Woo_Cart_Additional_Fee_Activator::on_activate();
}

register_activation_hook( __FILE__, 'cafeewoo_on_activate_woo_cart_additional_fee' );

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-woo-cart-additional-fee-deactivator.php
 *
 * @since    2.0.0
 */
function cafeewoo_on_deactivate_woo_cart_additional_fee() {
	require_once WOO_CART_ADDITIONAL_FEE_PLUGIN_PATH . 'includes/class-woo-cart-additional-fee-deactivator.php';

	Woo_Cart_Additional_Fee_Deactivator::on_deactivate();
}

register_deactivation_hook( __FILE__, 'cafeewoo_on_deactivate_woo_cart_additional_fee' );

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
function cafeewoo_run_woo_cart_additional_fee() {
	$plugin = new Woo_Cart_Additional_Fee();

	$plugin->run();
}

cafeewoo_run_woo_cart_additional_fee();
