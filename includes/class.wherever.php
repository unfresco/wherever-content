<?php

namespace Wherever_Content;

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
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class.loader.php';
		$this->loader = new Loader();
		
		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class.i18n.php';
		
		/**
		 * Version control 
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class.version-control.php';
		$this->version_control = new Version_Control();

		/**
		 * Load Carbon fields as plugin
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class.metafields.php';
		$this->meta_fields = new Metafields();
		
		/**
		 * The class for general purpuse helper functions
		 *
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class.helpers.php';
		$this->helpers = new Helpers();
		
		/**
		 * Class with admin rendering functions
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/class.display.php';
		
		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class.wherever-admin.php';
		
		/**
		 * Class for setting up the post-meta fields of the wherever CPT.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/class.postmeta-fields.php';
		
		/**
		 * Class for setting up the post-meta rules for the admin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/class.postmeta-fields-rules.php';
		
		/**
		 * Class for setting up the post-meta places for the admin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/class.postmeta-fields-places.php';
		
		/**
		 * Class helpers por setting up postmeta rule and place fields for the admin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/class.postmeta-fields-helpers.php';

		/**
		 * Class adding for vendor compatibility.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/class.vendor-compat.php';
		
		/**
		 * Class for setting up the settings page.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/class.settings.php';
		
		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class.wherever-public.php';
		
		/**
		 * Class for setting up the rules for the public display logic.
		 * 
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/class.rules.php';
		
		/**
		 * Public API functions
		 * 
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/functions.php';

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

		$plugin_i18n = new i18n();

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
		global $pagenow;

		$plugin_admin = new Wherever_Admin( $this->get_plugin_name(), $this->version_control->get_version(), $this->helpers );
		$plugin_admin_postmeta_field_helpers = new \Wherever_Content\Admin\Postmeta_Fields_Helpers();
		$plugin_admin_postmeta_field_rules = new \Wherever_Content\Admin\Postmeta_Fields_Rules( $plugin_admin_postmeta_field_helpers );
		$plugin_admin_postmeta_field_places = new \Wherever_Content\Admin\Postmeta_Fields_Places( $plugin_admin_postmeta_field_helpers );
		$plugin_admin_postmeta_fields = new \Wherever_Content\Admin\Postmeta_Fields();
		$plugin_admin_settings = new \Wherever_Content\Admin\Settings( $this->helpers );
		$plugin_admin_vendor = new \Wherever_Content\Admin\Vendor_Compat();
		$plugin_admin_display = new \Wherever_Content\Admin\Display();
		
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'all_plugins', $plugin_admin, 'localize_plugin_info' );

		$this->loader->add_action( 'plugins_loaded', $this->meta_fields, 'load_framework' );

		$this->loader->add_action( 'init', $this->version_control, 'check_version' );

		$this->loader->add_action( 'init', $plugin_admin_settings, 'options_status_init' );
		$this->loader->add_action( 'init', $plugin_admin_settings, 'options_settings_init' );
		$this->loader->add_action( 'admin_menu', $plugin_admin_settings, 'settings_page');
		$this->loader->add_action( 'admin_init', $plugin_admin_settings, 'settings' );
		$this->loader->add_action( 'update_option_wherever_settings', $plugin_admin_settings, 'after_update_settings', 10, 2);
		$this->loader->add_filter( 'option_wherever_status', $plugin_admin_settings, 'filter_get_options_status', 10, 1);
		$this->loader->add_filter( 'option_wherever_status', $plugin_admin_settings, 'filter_get_options_status_registered_places', 10, 1);
		$this->loader->add_filter( 'option_wherever_settings', $plugin_admin_settings, 'filter_get_options_settings', 10, 1);
		$this->loader->add_filter( 'pre_update_option_wherever_settings', $plugin_admin_settings, 'filter_update_options_settings', 10, 2);

		$this->loader->add_action( 'init', $plugin_admin, 'place_taxonomy' );
        $this->loader->add_action( 'init', $plugin_admin, 'custom_post_types' );
		
		$this->loader->add_filter( 'wherever_admin/admin_js', $plugin_admin, 'wherever_places_for_admin_js' );
		$this->loader->add_filter( 'wherever_admin/admin_js', $plugin_admin, 'wherever_rules_for_admin_js' );


		
		$this->loader->add_action( 'carbon_fields_register_fields', $plugin_admin_postmeta_fields, 'carbon_fields_post_meta' );
		$this->loader->add_action( 'carbon_fields_post_meta_container_saved', $plugin_admin_postmeta_fields, 'carbon_fields_save' );
		
		$this->loader->add_filter( 'wherever_admin/rules', $plugin_admin_postmeta_field_rules, 'location_type', 1, 1 );
		$this->loader->add_filter( 'wherever_admin/rules/location_type', $plugin_admin_postmeta_field_rules, 'location_type_options', 10, 1 );
		
		if ( is_admin() && ( $pagenow == 'post.php' || $pagenow == 'post-new.php' ) ) {
			$this->loader->add_action( 'init', $plugin_admin, 'setup_default_places' );
			
			$this->loader->add_filter('wherever_admin/rules', $plugin_admin_postmeta_field_rules, 'location_condition', 2, 1);
			$this->loader->add_filter('wherever_admin/rules', $plugin_admin_postmeta_field_rules, 'post', 10, 1);
			$this->loader->add_filter('wherever_admin/rules', $plugin_admin_postmeta_field_rules, 'post_type', 20, 1);
			$this->loader->add_filter('wherever_admin/rules', $plugin_admin_postmeta_field_rules, 'post_cat', 30, 1);
			$this->loader->add_filter('wherever_admin/rules', $plugin_admin_postmeta_field_rules, 'page', 40, 1);
			$this->loader->add_filter('wherever_admin/rules', $plugin_admin_postmeta_field_rules, 'template_type', 50, 1);
			$this->loader->add_filter('wherever_admin/rules', $plugin_admin_postmeta_field_rules, 'archive_post_type', 60, 1);
			$this->loader->add_filter('wherever_admin/rules', $plugin_admin_postmeta_field_rules, 'rule_info', 100, 1);
			
		}

		$this->loader->add_filter( 'wherever_admin/places', $plugin_admin_postmeta_field_places, 'place', 10, 1 );
		$this->loader->add_filter( 'wherever_admin/places', $plugin_admin_postmeta_field_places, 'placement', 10, 1 );
		$this->loader->add_filter( 'wherever_admin/places', $plugin_admin_postmeta_field_places, 'order', 10, 1 );
		$this->loader->add_filter( 'wherever_admin/places', $plugin_admin_postmeta_field_places, 'place_info', 10, 1 );

		$this->loader->add_filter( 'save_post', $plugin_admin_postmeta_fields, 'save_post' );
			

		$this->loader->add_action( 'pll_get_post_types', $plugin_admin_vendor, 'polylang_compat', 10, 2 );

	}


	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {
		
		$plugin_public = new Wherever_Public( $this->get_plugin_name(), $this->version_control->get_version(), $this->helpers  );
		$plugin_public_rules = new \Wherever_Content\Wherever_Public\Rules();
		
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		
		// wp_head hook dependency for setting up wherever contents
		$this->loader->add_action( 'wp_head', $plugin_public, 'setup_wherevers' );
		
		// API
		$this->loader->add_action( 'wherever_place', $plugin_public, 'api_get_wherever_place', 10, 1 );
		
		// Rule for displaying wherevers
		$this->loader->add_filter( 'wherever_public/rules', $plugin_public_rules, 'cleanup', 10, 1 );
		$this->loader->add_filter( 'wherever_public/rules/location_type/all', $plugin_public_rules, 'all', 10, 2 );
		$this->loader->add_filter( 'wherever_public/rules/location_type/post', $plugin_public_rules, 'post', 10, 2 );
		$this->loader->add_filter( 'wherever_public/rules/location_type/post_cat', $plugin_public_rules, 'post_cat', 10, 2 );
		$this->loader->add_filter( 'wherever_public/rules/location_type/post_type', $plugin_public_rules, 'post_type', 10, 2 );
		$this->loader->add_filter( 'wherever_public/rules/location_type/page', $plugin_public_rules, 'page', 10, 2 );
		$this->loader->add_filter( 'wherever_public/rules/location_type/page_parent', $plugin_public_rules, 'page_parent', 10, 2 );
		$this->loader->add_filter( 'wherever_public/rules/location_type/page_type', $plugin_public_rules, 'template_type', 10, 2 );
		$this->loader->add_filter( 'wherever_public/rules/location_type/template_type', $plugin_public_rules, 'template_type', 10, 2 );
		// TODO: user state rule into plugin
		#$this->loader->add_filter( 'wherever_public/rules/location_type/user_state', $plugin_public_rules, 'user_state', 10, 2 );
		
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
	

}
