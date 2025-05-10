<?php
/**
 * Fired when the plugin is uninstalled.
 *
 * @since      2.0.0
 * @package    Woo_Cart_Additional_Fee
 * @author     Sajjad Hossain Sagor <sagorh672@gmail.com>
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	die();
}

/**
 * Remove plugin options on uninstall/delete
 */
delete_option( 'wcfee_enable' );
delete_option( 'wcfee_label' );
delete_option( 'wcfee_type' );
delete_option( 'wcfee_fixed' );
delete_option( 'wcfee_percentage' );
delete_option( 'wcfee_enable_minimum' );
delete_option( 'wcfee_minimum' );
delete_option( 'wcfee_enable_maximum' );
delete_option( 'wcfee_maximum' );
delete_option( 'wcfee_country_filter' );
delete_option( 'wcfee_product_filter' );
