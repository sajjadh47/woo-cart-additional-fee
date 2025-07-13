<?php
/**
 * This file contains the definition of the Woo_Cart_Additional_Fee_Admin class, which
 * is used to load the plugin's admin-specific functionality.
 *
 * @package       Woo_Cart_Additional_Fee
 * @subpackage    Woo_Cart_Additional_Fee/admin
 * @author        Sajjad Hossain Sagor <sagorh672@gmail.com>
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version and other methods.
 *
 * @since    2.0.0
 */
class Woo_Cart_Additional_Fee_Admin {
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
	 * @param     string $plugin_name The name of this plugin.
	 * @param     string $version     The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version     = $version;
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since     2.0.0
	 * @access    public
	 */
	public function enqueue_scripts() {
		$current_screen = get_current_screen();

		// check if current page is plugin settings page.
		if ( 'woocommerce_page_wc-settings' === $current_screen->id ) {
			wp_enqueue_script( $this->plugin_name, WOO_CART_ADDITIONAL_FEE_PLUGIN_URL . 'admin/js/admin.js', array( 'jquery', 'select2' ), $this->version, false );

			wp_localize_script(
				$this->plugin_name,
				'WooCartAdditionalFee',
				array(
					'wcfeeProductFilterTxtI18n' => __( 'Please select a product...', 'wp-edit-username' ),
					'wcfeeCountryFilterTxtI18n' => __( 'Please select a country...', 'wp-edit-username' ),
					'wcfeeTypeTxtI18n'          => __( 'Please select fee apply type...', 'wp-edit-username' ),
					'wcfeeSelectAllTxtI18n'     => __( 'Select all', 'wp-edit-username' ),
					'wcfeeSelectNoneTxtI18n'    => __( 'Select none', 'wp-edit-username' ),
				)
			);
		}
	}

	/**
	 * Adds a settings link to the plugin's action links on the plugin list table.
	 *
	 * @since     2.0.0
	 * @access    public
	 * @param     array $links The existing array of plugin action links.
	 * @return    array $links The updated array of plugin action links, including the settings link.
	 */
	public function add_plugin_action_links( $links ) {
		$links[] = sprintf( '<a href="%s">%s</a>', admin_url( 'admin.php?page=wc-settings&tab=wcfee_settings' ), __( 'Settings', 'wp-edit-username' ) );

		return $links;
	}

	/**
	 * Displays admin notices in the admin area.
	 *
	 * This function checks if the required plugin is active.
	 * If not, it displays a warning notice and deactivates the current plugin.
	 *
	 * @since     2.0.0
	 * @access    public
	 */
	public function admin_notices() {
		if ( ! class_exists( 'WooCommerce', false ) ) {
			sprintf(
				'<div class="notice notice-warning is-dismissible"><p>%s <a href="%s">%s</a> %s</p></div>',
				__( 'Cart Additional Fee for WooCommerce requires', 'woo-cart-additional-fee' ),
				esc_url( 'https://wordpress.org/plugins/woocommerce/' ),
				__( 'WooCommerce', 'woo-cart-additional-fee' ),
				__( 'plugin to be active!', 'woo-cart-additional-fee' ),
			);

			// Deactivate the plugin.
			deactivate_plugins( WOO_CART_ADDITIONAL_FEE_PLUGIN_BASENAME );
		}
	}

	/**
	 * Declares compatibility with WooCommerce's custom order tables feature.
	 *
	 * This function is hooked into the `before_woocommerce_init` action and checks
	 * if the `FeaturesUtil` class exists in the `Automattic\WooCommerce\Utilities`
	 * namespace. If it does, it declares compatibility with the 'custom_order_tables'
	 * feature. This is important for ensuring the plugin works correctly with
	 * WooCommerce versions that support this feature.
	 *
	 * @since    2.0.0
	 * @access   public
	 */
	public function declare_compatibility_with_wc_custom_order_tables() {
		if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
			\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
		}
	}

	/**
	 * Add a new settings tab to the WooCommerce settings tabs array.
	 *
	 * @since     2.0.0
	 * @access    public
	 * @param     array $settings_tabs Array of WooCommerce setting tabs & labels, excluding the Cart Additional Fee for WooCommerce tab.
	 * @return    array $settings_tabs Array of WooCommerce setting tabs & labels, including the Cart Additional Fee for WooCommerce tab.
	 */
	public function woocommerce_settings_tabs_array( $settings_tabs ) {
		$settings_tabs['wcfee_settings'] = __( 'Cart Additional Fee', 'woo-cart-additional-fee' );

		return $settings_tabs;
	}

	/**
	 * Uses the WooCommerce admin fields API to output settings via the @see woocommerce_admin_fields() function.
	 *
	 * @since     2.0.0
	 * @access    public
	 * @uses      woocommerce_admin_fields()
	 * @uses      $this->get_settings()
	 */
	public function render_settings_tab_content() {
		woocommerce_admin_fields( $this->get_settings() );
	}

	/**
	 * Uses the WooCommerce options API to save settings via the @see woocommerce_update_options() function.
	 *
	 * @since     2.0.0
	 * @access    public
	 * @uses      woocommerce_update_options()
	 * @uses      $this->get_settings()
	 */
	public function update_settings_tab_content() {
		woocommerce_update_options( $this->get_settings() );
	}

	/**
	 * Get all the settings for this plugin for @see woocommerce_admin_fields() function.
	 *
	 * @since     2.0.0
	 * @access    public
	 * @return    array Array of settings for @see woocommerce_admin_fields() function.
	 */
	public function get_settings() {
		$settings = array(
			'section_title'               => array(
				'name' => __( 'Cart Additional Fee Settings', 'woo-cart-additional-fee' ),
				'type' => 'title',
				'id'   => 'wcfee_tab_section_title',
			),
			'enable'                      => array(
				'name' => __( 'Enable ', 'woo-cart-additional-fee' ),
				'type' => 'checkbox',
				'desc' => __( 'Enable / Disable Additional Fee', 'woo-cart-additional-fee' ),
				'id'   => 'wcfee_enable',
			),
			'label'                       => array(
				'name'        => __( 'Additional Fee Label', 'woo-cart-additional-fee' ),
				'type'        => 'text',
				'placeholder' => __( 'Enter Additional Fee Label text', 'woo-cart-additional-fee' ),
				'id'          => 'wcfee_label',
			),
			'charges_type'                => array(
				'name'    => __( 'Fee Type', 'woo-cart-additional-fee' ),
				'type'    => 'select',
				'id'      => 'wcfee_type',
				'options' => array(
					'fixed'      => __( 'Fixed Fee', 'woo-cart-additional-fee' ),
					'percentage' => __( 'Percentage (%) Based Fee', 'woo-cart-additional-fee' ),
				),
			),
			'charges_type_fixed'          => array(
				'name'        => __( 'Fixed Fee Amount', 'woo-cart-additional-fee' ),
				'type'        => 'text',
				'placeholder' => __( 'Set Fixed Fee Amount', 'woo-cart-additional-fee' ),
				'id'          => 'wcfee_fixed',
			),
			'charges_type_percentage'     => array(
				'name'        => __( 'Percentage Fee Amount', 'woo-cart-additional-fee' ),
				'type'        => 'text',
				'placeholder' => __( 'Set Percentage Value', 'woo-cart-additional-fee' ),
				'id'          => 'wcfee_percentage',
			),
			'enable_minimum'              => array(
				'name' => '',
				'type' => 'checkbox',
				'desc' => __( 'Enable Minimum Cart Amount Check', 'woo-cart-additional-fee' ),
				'id'   => 'wcfee_enable_minimum',
			),
			'minimum'                     => array(
				'name'        => __( 'Minimum Cart Amount', 'woo-cart-additional-fee' ),
				'type'        => 'text',
				'placeholder' => __( 'Set Minimum total cart amount to apply Additional Fee', 'woo-cart-additional-fee' ),
				'id'          => 'wcfee_minimum',
			),
			'enable_maximum'              => array(
				'name' => '',
				'type' => 'checkbox',
				'desc' => __( 'Enable Maximum Cart Amount Check', 'woo-cart-additional-fee' ),
				'id'   => 'wcfee_enable_maximum',
			),
			'maximum'                     => array(
				'name'        => __( 'Maximum Cart Amount', 'woo-cart-additional-fee' ),
				'type'        => 'text',
				'placeholder' => __( 'Set Maximum total cart amount to apply Additional Fee', 'woo-cart-additional-fee' ),
				'id'          => 'wcfee_maximum',
			),
			'enable_for_specific_country' => array(
				'name'        => __( 'Apply Fee For Specific Country', 'woo-cart-additional-fee' ),
				'type'        => 'multiselect',
				'placeholder' => __( 'Select Country to apply Additional Fee', 'woo-cart-additional-fee' ),
				'id'          => 'wcfee_country_filter',
				'desc'        => __( 'Note : only applicable for logged in customers only.', 'woo-cart-additional-fee' ),
				'options'     => $this->get_countries(),
			),
			'enable_for_specific_product' => array(
				'name'        => __( 'Apply Fee For Specific Product', 'woo-cart-additional-fee' ),
				'type'        => 'multiselect',
				'placeholder' => __( 'Select Product to apply Additional Fee', 'woo-cart-additional-fee' ),
				'id'          => 'wcfee_product_filter',
				'options'     => $this->get_woo_products(),
			),
			'fee_applicable_for'          => array(
				'name'        => __( 'Fee Applied To', 'woo-cart-additional-fee' ),
				'type'        => 'select',
				'placeholder' => __( 'Where to apply Additional Fee', 'woo-cart-additional-fee' ),
				'id'          => 'wcfee_fee_applicable_for',
				'options'     => array(
					'subtotal'          => __( 'Cart Subtotal', 'woo-cart-additional-fee' ),
					'subtotal_shipping' => __( 'Cart Subtotal + Shipping', 'woo-cart-additional-fee' ),
				),
			),
			'section_end'                 => array(
				'type' => 'sectionend',
				'id'   => 'wcfee_tab_section_end',
			),
		);

		/**
		 * Filters the plugin settings array.
		 *
		 * This filter allows you to modify the settings array.
		 * You can use this filter to add, remove, or change the order of settings fields.
		 *
		 * @since     2.0.0
		 * @param     array $settings Array of settings fields.
		 * @return    array $settings Modified array of settings fields.
		 */
		return apply_filters( 'wcfee_settings', $settings );
	}

	/**
	 * Get all WooCommerce products as array [product_id => product_title].
	 *
	 * This function retrieves all WooCommerce products, caches the result using a transient,
	 * and applies a filter to the returned products array.
	 *
	 * @since     2.0.0
	 * @access    public
	 * @return    array List of product IDs and titles.
	 */
	public function get_woo_products() {
		$transient_key = 'wcfee_woo_products';
		$products      = get_transient( $transient_key );

		if ( false === $products ) {
			$args = array(
				'post_type'      => 'product',
				'posts_per_page' => -1,
			);

			$products = array();
			$loop     = new WP_Query( $args );

			while ( $loop->have_posts() ) :

				$loop->the_post();

				$products[ get_the_ID() ] = get_the_title();

			endwhile;

			wp_reset_postdata();

			// Cache the products for 1 hour.
			set_transient( $transient_key, $products, HOUR_IN_SECONDS );
		}

		/**
		 * Filter the WooCommerce products retrieved.
		 *
		 * @since     2.0.0
		 * @param     array $products Array of product IDs and titles.
		 * @return    array $products Modified array of product IDs and titles.
		 */
		return apply_filters( 'wcfee_wc_products', $products );
	}

	/**
	 * Get All Countries From Woocommerce
	 *
	 * @since     2.0.0
	 * @access    public
	 * @return    array Array of countries
	 */
	public function get_countries() {
		$countries_obj = new WC_Countries();
		$countries     = $countries_obj->__get( 'countries' );

		return $countries;
	}
}
