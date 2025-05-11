<?php
/**
 * This file contains the definition of the Woo_Cart_Additional_Fee class, which
 * is used to begin the plugin's functionality.
 *
 * @package       Woo_Cart_Additional_Fee
 * @subpackage    Woo_Cart_Additional_Fee/includes
 * @author        Sajjad Hossain Sagor <sagorh672@gmail.com>
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since    2.0.0
 */
class Woo_Cart_Additional_Fee {
	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since     2.0.0
	 * @access    protected
	 * @var       Woo_Cart_Additional_Fee_Loader $loader Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since     2.0.0
	 * @access    protected
	 * @var       string $plugin_name The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since     2.0.0
	 * @access    protected
	 * @var       string $version The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since     2.0.0
	 * @access    public
	 */
	public function __construct() {
		if ( defined( 'WOO_CART_ADDITIONAL_FEE_VERSION' ) ) {
			$this->version = WOO_CART_ADDITIONAL_FEE_VERSION;
		} else {
			$this->version = '1.0.0';
		}

		$this->plugin_name = 'woo-cart-additional-fee';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Woo_Cart_Additional_Fee_Loader. Orchestrates the hooks of the plugin.
	 * - Woo_Cart_Additional_Fee_i18n.   Defines internationalization functionality.
	 * - Woo_Cart_Additional_Fee_Admin.  Defines all hooks for the admin area.
	 * - Woo_Cart_Additional_Fee_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    2.0.0
	 * @access   private
	 */
	private function load_dependencies() {
		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once WOO_CART_ADDITIONAL_FEE_PLUGIN_PATH . 'includes/class-woo-cart-additional-fee-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once WOO_CART_ADDITIONAL_FEE_PLUGIN_PATH . 'includes/class-woo-cart-additional-fee-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once WOO_CART_ADDITIONAL_FEE_PLUGIN_PATH . 'admin/class-woo-cart-additional-fee-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once WOO_CART_ADDITIONAL_FEE_PLUGIN_PATH . 'public/class-woo-cart-additional-fee-public.php';

		$this->loader = new Woo_Cart_Additional_Fee_Loader();
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Woo_Cart_Additional_Fee_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since     2.0.0
	 * @access    private
	 */
	private function set_locale() {
		$plugin_i18n = new Woo_Cart_Additional_Fee_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since     2.0.0
	 * @access    private
	 */
	private function define_admin_hooks() {
		$plugin_admin = new Woo_Cart_Additional_Fee_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		$this->loader->add_action( 'plugin_action_links_' . WOO_CART_ADDITIONAL_FEE_PLUGIN_BASENAME, $plugin_admin, 'add_plugin_action_links' );

		$this->loader->add_action( 'admin_notices', $plugin_admin, 'admin_notices' );

		$this->loader->add_action( 'before_woocommerce_init', $plugin_admin, 'declare_compatibility_with_wc_custom_order_tables' );

		$this->loader->add_filter( 'woocommerce_settings_tabs_array', $plugin_admin, 'woocommerce_settings_tabs_array', 50 );
		$this->loader->add_action( 'woocommerce_settings_tabs_wcfee_settings', $plugin_admin, 'render_settings_tab_content' );
		$this->loader->add_action( 'woocommerce_update_options_wcfee_settings', $plugin_admin, 'update_settings_tab_content' );
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since     2.0.0
	 * @access    private
	 */
	private function define_public_hooks() {
		$plugin_public = new Woo_Cart_Additional_Fee_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'woocommerce_cart_calculate_fees', $plugin_public, 'apply_fee' );
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since     2.0.0
	 * @access    public
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     2.0.0
	 * @access    public
	 * @return    string The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     2.0.0
	 * @access    public
	 * @return    Wp_Create_Multi_Posts_Pages_Loader Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     2.0.0
	 * @access    public
	 * @return    string The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}
}
