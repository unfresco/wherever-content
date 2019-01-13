<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://grell.es
 * @since      1.0.0
 *
 * @package    Wherever
 * @subpackage Wherever/includes
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
 * @package    Wherever
 * @subpackage Wherever/includes
 * @author     Adrián Ortiz Arandes <adrian@grell.es>
 */
class Wherever {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Wherever_Loader    $loader    Maintains and registers all hooks for the plugin.
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
	
	
	protected $default_places;
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
		
		$this->plugin_name = 'wherever';
		$this->version = $this->get_version();

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
	 * - Wherever_Loader. Orchestrates the hooks of the plugin.
	 * - Wherever_i18n. Defines internationalization functionality.
	 * - Wherever_Admin. Defines all hooks for the admin area.
	 * - Wherever_Public. Defines all hooks for the public side of the site.
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
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wherever-loader.php';
		$this->loader = new Wherever_Loader();
		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wherever-i18n.php';
		
		/**
		 * Version control 
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wherever-version-control.php';
		
		/**
		 * Composer autoload
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wherever-metafields.php';
		$this->metafields = new Wherever_Metafields();
		$this->metafields->boot();
		
		
		/**
		 * The class for general purpuse functions
		 *
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wherever-helpers.php';
		
		/**
		 * Class with admin rendering functions
		 */
		 require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/class-wherever-admin-display.php';
		
		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-wherever-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-wherever-public.php';
		
		

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Wherever_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Wherever_i18n();

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

		$plugin_admin = new Wherever_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'all_plugins', $plugin_admin, 'localize_plugin_info' );
		// WP
		$this->loader->add_filter( 'save_post', $plugin_admin, 'save_post' );

		// Wherever UI
		if ( $this->metafields->is_loaded() ) {
			
			$this->loader->add_action( 'init', $plugin_admin, 'options_status_init' );
			$this->loader->add_action( 'init', $plugin_admin, 'options_settings_init' );
			$this->loader->add_action( 'init', $plugin_admin, 'check_version' );
			$this->loader->add_action( 'init', $plugin_admin, 'place_taxonomy' );
			$this->loader->add_action( 'init', $plugin_admin, 'setup_default_places' );
			$this->loader->add_action( 'init', $plugin_admin, 'custom_post_types' );
			$this->loader->add_action( 'carbon_fields_register_fields', $plugin_admin, 'carbon_fields_post_meta' );
			$this->loader->add_action( 'carbon_fields_post_meta_container_saved', $plugin_admin, 'carbon_fields_save' );
			
			$this->loader->add_action( 'admin_menu', $plugin_admin, 'settings_page');
			$this->loader->add_action( 'admin_init', $plugin_admin, 'settings' );
			$this->loader->add_action( 'update_option_wherever_settings', $plugin_admin, 'after_update_settings', 10, 2);
			
			$this->loader->add_filter( 'option_wherever_status', $plugin_admin, 'filter_get_options_status', 10, 1);
			$this->loader->add_filter( 'option_wherever_settings', $plugin_admin, 'filter_get_options_settings', 10, 1);
			$this->loader->add_filter( 'pre_update_option_wherever_settings', $plugin_admin, 'filter_update_options_settings', 10, 2);
			
			$this->loader->add_action( 'wherever_update_version', $plugin_admin, 'update_options_status_version' );
			
			$this->loader->add_action( 'pll_get_post_types', $plugin_admin, 'polylang_compat', 10, 2 );
			
		} else {
			
			$this->loader->add_action( 'admin_notices', $plugin_admin->display(), 'notice_framework' );
			
		}
		
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Wherever_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		
		// wp_head hook dependency for setting up wherever contents
		$this->loader->add_action( 'wp_head', $plugin_public, 'setup_wherevers' );
		
		// API
		$this->loader->add_action( 'wherever_place', $plugin_public, 'api_get_wherever_place', 10, 1 );
		
		// Filters
		$this->loader->add_filter( 'the_content', $plugin_public, 'the_content' );
		$this->loader->add_action( 'get_sidebar', $plugin_public, 'get_sidebar', 10, 1 );
		$this->loader->add_action( 'get_footer', $plugin_public, 'get_footer', 10, 1 );
		
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
	 * @return    Wherever_Loader    Orchestrates the hooks of the plugin.
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
		if ( empty( $this->version ) ) {
			if( !function_exists('get_plugin_data') ){
				require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
			}
			$plugin_data = get_plugin_data( plugin_dir_path( dirname( __FILE__ ) ) . 'wherever.php' );
			$this->version = $plugin_data['Version'];
		}
		
		return $this->version;
	}
	
	

}
