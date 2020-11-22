<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       mailto:ialvsconcelos@gmail.com
 * @since      1.0.0
 *
 * @package    WC_Br_Shipping_Only_With_Cep
 * @subpackage WC_Br_Shipping_Only_With_Cep/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    WC_Br_Shipping_Only_With_Cep
 * @subpackage WC_Br_Shipping_Only_With_Cep/includes
 * @author     Alvaro Vasconcelos - @alvsconcelos <mailto:ialvsconcelos@gmail.com>
 */
class WC_Br_Shipping_Only_With_Cep {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      WC_Br_Shipping_Only_With_Cep_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'WC_BR_SHIPPING_ONLY_WITH_CEP_VERSION' ) ) {
			$this->version = WC_BR_SHIPPING_ONLY_WITH_CEP_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'wc-br-shipping-only-with-cep';

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
	 * - WC_Br_Shipping_Only_With_Cep_Loader. Orchestrates the hooks of the plugin.
	 * - WC_Br_Shipping_Only_With_Cep_i18n. Defines internationalization functionality.
	 * - WC_Br_Shipping_Only_With_Cep_Admin. Defines all hooks for the admin area.
	 * - WC_Br_Shipping_Only_With_Cep_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wc-br-shipping-only-with-cep-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wc-br-shipping-only-with-cep-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-wc-br-shipping-only-with-cep-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-wc-br-shipping-only-with-cep-public.php';

		$this->loader = new WC_Br_Shipping_Only_With_Cep_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the WC_Br_Shipping_Only_With_Cep_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new WC_Br_Shipping_Only_With_Cep_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {
		$plugin_admin = new WC_Br_Shipping_Only_With_Cep_Admin( $this->get_plugin_name(), $this->get_version() );
		
		$this->loader->add_action( 'admin_init', $plugin_admin, 'check_woocommerce' );
		$this->loader->add_filter( 'plugin_action_links_woocommerce-br-shipping-only-with-cep/wc-br-shipping-only-with-cep.php', $plugin_admin, 'filter_plugin_links', 10, 1);
		$this->loader->add_filter( 'woocommerce_shipping_settings', $plugin_admin, 'filter_woocommerce_shipping_options', 10, 1);
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {
		$plugin_public = new WC_Br_Shipping_Only_With_Cep_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		$this->loader->add_action( 'woocommerce_calculated_shipping', $plugin_public, 'apply_correct_state' );

		// Disable City, State and Country selectors on shipping form.
		add_filter( 'woocommerce_shipping_calculator_enable_city',  '__return_false' );
		add_filter( 'woocommerce_shipping_calculator_enable_country', '__return_false' );
		add_filter( 'woocommerce_shipping_calculator_enable_state', '__return_false' );
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    WC_Br_Shipping_Only_With_Cep_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
