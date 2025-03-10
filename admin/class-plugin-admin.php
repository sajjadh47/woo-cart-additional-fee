<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and
 * enqueue the admin-specific JavaScript.
 *
 * @package    Woo_Cart_Additional_Fee
 * @subpackage Woo_Cart_Additional_Fee/admin
 * @author     Sajjad Hossain Sagor <sagorh672@gmail.com>
 */
class Woo_Cart_Additional_Fee_Admin
{
	/**
	 * The ID of this plugin.
	 *
	 * @since    2.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    2.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    2.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version )
	{
		$this->plugin_name 	= $plugin_name;
		
		$this->version 		= $version;
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    2.0.0
	 */
	public function enqueue_scripts()
	{
		$current_screen = get_current_screen();

		// check if current page is plugin settings page
		if( $current_screen->id == 'woocommerce_page_wc-settings' )
		{
			wp_enqueue_script( $this->plugin_name, WOO_CART_ADDITIONAL_FEE_PLUGIN_URL . 'admin/js/admin.js', array( 'jquery', 'select2' ), $this->version, false );

			wp_localize_script( $this->plugin_name, 'Woo_Cart_Additional_Fee',
				array( 
					'wcfee_product_filter_txt_i18n' => __( 'Please select a product...', 'wp-edit-username' ),
					'wcfee_country_filter_txt_i18n' => __( 'Please select a country...', 'wp-edit-username' ),
					'wcfee_type_txt_i18n' 			=> __( 'Please select fee apply type...', 'wp-edit-username' ),
					'wcfee_select_all_txt_i18n' 	=> __( 'Select all', 'wp-edit-username' ),
					'wcfee_select_none_txt_i18n' 	=> __( 'Select none', 'wp-edit-username' ),
				)
			);
		}
	}

	/**
	 * Adds a settings link to the plugin's action links on the plugin list table.
	 *
	 * @since    2.0.0
	 *
	 * @param    array $links The existing array of plugin action links.
	 * @return   array The updated array of plugin action links, including the settings link.
	 */
	public function add_plugin_action_links( $links )
	{
		$links[] = sprintf( '<a href="%s">%s</a>', admin_url( 'admin.php?page=wc-settings&tab=wcfee_settings' ), __( 'Settings', 'wp-edit-username' ) );
		
		return $links;
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    2.0.0
	 */
	public function admin_notices()
	{
		if ( ! is_plugin_active( 'woocommerce/woocommerce.php' ) )
		{
			echo '<div class="notice notice-warning is-dismissible">';
			
				printf(
					wp_kses_post(
					__( '<p>WooCommerce Cart Additional Fee requires <a href="%s">WooCommerce</a> plugin to be active!</p>', 'woo-cart-additional-fee' )
					),
					esc_url( 'https://wordpress.org/plugins/woocommerce/' )
				);
			
			echo '</div>';

			// Deactivate the plugin
			deactivate_plugins( WOO_CART_ADDITIONAL_FEE_PLUGIN_BASENAME );
		}
	}

	/**
	* Add a new settings tab to the WooCommerce settings tabs array.
	* 
	* @since    2.0.0
	* @param 	array $settings_tabs Array of WooCommerce setting tabs & their labels, excluding the Woocommerce Cart Additional Fee tab.
	* @return 	array $settings_tabs Array of WooCommerce setting tabs & their labels, including the Woocommerce Cart Additional Fee tab.
	*/
	public function woocommerce_settings_tabs_array( $settings_tabs )
	{
		$settings_tabs['wcfee_settings'] = __( 'Woo Cart Additional Fee', 'woo-cart-additional-fee' );

		return $settings_tabs;
	}

	/**
	* Uses the WooCommerce admin fields API to output settings via the @see woocommerce_admin_fields() function.
	*
	* @since    2.0.0
	* @uses 	woocommerce_admin_fields()
	* @uses 	$this->get_settings()
	*/
	function render_settings_tab_content()
	{
		woocommerce_admin_fields( $this->get_settings() );
	}

	/**
	 * Uses the WooCommerce options API to save settings via the @see woocommerce_update_options() function.
	 *
	 * @since    2.0.0
	 * @uses 	woocommerce_update_options()
	 * @uses 	$this->get_settings()
	 */
	function update_settings_tab_content()
	{
		woocommerce_update_options( $this->get_settings() );
	}

	/**
	 * Get all the settings for this plugin for @see woocommerce_admin_fields() function.
	 * 
	 * @since	2.0.0
	 * @return 	array Array of settings for @see woocommerce_admin_fields() function.
	 */
	function get_settings()
	{
		$settings = array(
			'section_title' => array(
				'name'	=> __( 'Woocommerce Cart Additional Fee Settings Panel', 'woo-cart-additional-fee' ),
				'type'	=> 'title',
				'id'	=> 'wcfee_tab_section_title',
			),
			'enable' => array(
				'name'	=> __( 'Enable ', 'woo-cart-additional-fee' ),
				'type'	=> 'checkbox',
				'desc'	=> __( 'Enable / Disable Additional Fee Options', 'woo-cart-additional-fee' ),
				'id'	=> 'wcfee_enable',
			),
			'label' => array(
				'name'		 	=> __( 'Additional Fee Label', 'woo-cart-additional-fee' ),
				'type'			=> 'text',
				'placeholder'	=> __( 'Enter Additional Fee Label text', 'woo-cart-additional-fee' ),
				'id'			=> 'wcfee_label',
			),
			'charges_type' => array(
				'name'		=> __( 'Fee Type', 'woo-cart-additional-fee' ),
				'type'		=> 'select',
				'id'		=> 'wcfee_type',
				'options'	=> array(
						'fixed'			=> __( 'Fixed Fee', 'woo-cart-additional-fee' ),
						'percentage'	=> __( 'Percentage (%) Based Fee', 'woo-cart-additional-fee' )
				)
			),
			'charges_type_fixed' => array(
				'name'			=> __( 'Fixed Fee Amount', 'woo-cart-additional-fee' ),
				'type'			=> 'text',
				'placeholder'	=> __( 'Set Fixed Fee Amount', 'woo-cart-additional-fee' ),
				'id'			=> 'wcfee_fixed',
			),
			'charges_type_percentage' => array(
				'name'			=> __( 'Percentage Fee Amount', 'woo-cart-additional-fee' ),
				'type'			=> 'text',
				'placeholder'	=> __( 'Set Percentage Value', 'woo-cart-additional-fee' ),
				'id'			=> 'wcfee_percentage',
			),
			'enable_minimum' => array(
				'name'	=> __( '', 'woo-cart-additional-fee' ),
				'type'	=> 'checkbox',
				'desc'	=> __( 'Enable Minimum Cart Amount Check', 'woo-cart-additional-fee' ),
				'id'	=> 'wcfee_enable_minimum',
			),
			'minimum' => array(
				'name'			=> __( 'Minimum Cart Amount', 'woo-cart-additional-fee' ),
				'type'			=> 'text',
				'placeholder'	=> __( 'Set Minimum total cart amount to apply Additional Fee', 'woo-cart-additional-fee' ),
				'id'			=> 'wcfee_minimum',
			),
			'enable_maximum' => array(
				'name'	=> __( '', 'woo-cart-additional-fee' ),
				'type'	=> 'checkbox',
				'desc'	=> __( 'Enable Maximum Cart Amount Check', 'woo-cart-additional-fee' ),
				'id'	=> 'wcfee_enable_maximum',
			),
			'maximum' => array(
				'name'			=> __( 'Maximum Cart Amount', 'woo-cart-additional-fee' ),
				'type'			=> 'text',
				'placeholder'	=> __( 'Set Maximum total cart amount to apply Additional Fee', 'woo-cart-additional-fee' ),
				'id'			=> 'wcfee_maximum',
			),
			'enable_for_specific_country' => array(
				'name'			=> __( 'Apply Fee For Specific Country', 'woo-cart-additional-fee' ),
				'type'			=> 'multiselect',
				'placeholder'	=> __( 'Select Country to apply Additional Fee', 'woo-cart-additional-fee' ),
				'id'			=> 'wcfee_country_filter',
				'desc'			=> __( 'Note : only for logged in customers & both billing & shipping country will be checked against...', 'woo-cart-additional-fee' ),
				'options'		=> $this->get_countries()
			),
			'enable_for_specific_product' => array(
				'name'			=> __( 'Apply Fee For Specific Product', 'woo-cart-additional-fee' ),
				'type'			=> 'multiselect',
				'placeholder'	=> __( 'Select Product to apply Additional Fee', 'woo-cart-additional-fee' ),
				'id'			=> 'wcfee_product_filter',
				'options'		=> $this->get_woo_products()
			),
			'section_end' => array(
				 'type'	=> 'sectionend',
				 'id'	=> 'wcfee_tab_section_end'
			)
		);
		
		return apply_filters( 'wc_settings_wcfee_settings', $settings );
	}

	/**
	 * Get All Woocommerce Products as Array
	 * 
	 * @since	2.0.0
	 * @return 	array Array of products
	 */
	public function get_woo_products()
	{
		$args								= array( 'post_type' => 'product', 'posts_per_page' => -1 );

		$Products							= array();

		$loop								= new WP_Query( $args );

		    while ( $loop->have_posts() ) : $loop->the_post();

		        $Products[ get_the_ID() ]	= get_the_title();

		    endwhile; wp_reset_query();

		return $Products;
	}

	/**
	 * Get All Countries From Woocommerce
	 * 
	 * @since	2.0.0
	 * @return 	array Array of countries
	 */
	public function get_countries()
	{
		$countries_obj	= new WC_Countries();
	    
	    $countries		= $countries_obj->__get('countries');

		return $countries;
	}
}
