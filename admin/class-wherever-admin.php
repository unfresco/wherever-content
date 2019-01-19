<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://grell.es
 * @since      1.0.0
 *
 * @package    Wherever
 * @subpackage Wherever/admin
 */

use Carbon_Fields\Container;
use Carbon_Fields\Field;


/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wherever
 * @subpackage Wherever/admin
 * @author     Adrián Ortiz Arandes <adrian@grell.es>
 */
class Wherever_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;
	
	/**
	 * The WP_Theme Object.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */	
	private static $theme;
	
	/**
	 * The registered places terms
	 * @var array
	 */
	private static $wherever_places_terms;
	
	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version, $helpers ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->helpers = $helpers;
		
	}
	
	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name . '-admin', plugin_dir_url( __FILE__ ) . 'css/wherever-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		
		$handle = $this->plugin_name . '-admin';
		
		wp_enqueue_script( $handle, plugin_dir_url( __FILE__ ) . 'js/wherever-admin.js', array( 'jquery', 'underscore' ), $this->version, false );
		wp_localize_script( $handle, 'wherever_admin_js', $this->helpers->get_admin_js() );
		
	}
	
	/**
	 * Filter for plugin localisation
	 * @param  array $plugins list of active plugins
	 * @return array          modified plugin info
	 */
	public function localize_plugin_info( $plugins ) {
		
		$plugins['wherever-content/wherever.php']['Name'] = __('Wherever Content', 'wherever' );
		$plugins['wherever-content/wherever.php']['Description'] = __('Put reusable content wherever you want.', 'wherever');
		
		return $plugins;
	}
	
	/**
	 * Register wherever Custom Post Type 
	 *
	 * @since    1.0.0
	 */
	public function custom_post_types() {
		
		$settings = get_option('wherever_settings');
		
		$labels = array(
			'name'                  => _x( 'Wherever Contents', 'Post Type General Name', 'wherever' ),
			'singular_name'         => _x( 'Wherever Content', 'Post Type Singular Name', 'wherever' ),
			'menu_name'             => __( 'Wherever', 'wherever' ),
			#'name_admin_bar'        => __( 'Wherever', 'wherever' ),
			'archives'              => __( 'Item Archives', 'wherever' ),
			'parent_item_colon'     => __( 'Parent Item:', 'wherever' ),
			'all_items'             => __( 'All contents', 'wherever' ),
			'add_new_item'          => __( 'Add New Content', 'wherever' ),
			'add_new'               => __( 'Add New', 'wherever' ),
			'new_item'              => __( 'New Content', 'wherever' ),
			'edit_item'             => __( 'Edit Content', 'wherever' ),
			'update_item'           => __( 'Update Content', 'wherever' ),
			'view_item'             => __( 'View Content', 'wherever' ),
			'search_items'          => __( 'Search Content', 'wherever' ),
			'not_found'             => __( 'Not found', 'wherever' ),
			'not_found_in_trash'    => __( 'Not found in Trash', 'wherever' ),
			'featured_image'        => __( 'Featured Image', 'wherever' ),
			'set_featured_image'    => __( 'Set featured image', 'wherever' ),
			'remove_featured_image' => __( 'Remove featured image', 'wherever' ),
			'use_featured_image'    => __( 'Use as featured image', 'wherever' ),
			'insert_into_item'      => __( 'Insert into item', 'wherever' ),
			'uploaded_to_this_item' => __( 'Uploaded to this Wherever', 'wherever' ),
			'items_list'            => __( 'Items list', 'wherever' ),
			'items_list_navigation' => __( 'Items list navigation', 'wherever' ),
			'filter_items_list'     => __( 'Filter items list', 'wherever' ),
		);
		$args = array(
			'label'                 => __( 'Content', 'wherever' ),
			'description'           => __( 'Post Type Description', 'wherever' ),
			'labels'                => $labels,
			'supports'              => array( 'title', 'editor', 'revisions', 'custom-fields', ),
			'show_in_rest'          => $settings['show_in_rest'],
			'taxonomies'            => array( 'place' ),
			'hierarchical'          => false,
			'public'                => false,
			'show_ui'               => true,
			'show_in_menu'          => true,
			'menu_position'         => 5,
			'menu_icon'             => 'dashicons-move',
			'show_in_admin_bar'     => false,
			'show_in_nav_menus'     => false,
			'can_export'            => true,
			'has_archive'           => false,		
			'exclude_from_search'   => true,
			'capability_type'       => 'page',
		);
		register_post_type( 'wherever', $args );
	}
	
	/**
	 * Register wherever_place custom taxonomy
	 *
	 * @since    1.0.0
	 */
	public function place_taxonomy() {
		
		$labels = array(
			'name'                       => _x( 'Places', 'Taxonomy General Name', 'wherever' ),
			'singular_name'              => _x( 'Place', 'Taxonomy Singular Name', 'wherever' ),
			'menu_name'                  => __( 'Places', 'wherever' ),
			'all_items'                  => __( 'All Places', 'wherever' ),
			'parent_item'                => __( 'Parent Place', 'wherever' ),
			'parent_item_colon'          => __( 'Parent Item:', 'wherever' ),
			'new_item_name'              => __( 'New Place Name', 'wherever' ),
			'add_new_item'               => __( 'Add New Place', 'wherever' ),
			'edit_item'                  => __( 'Edit Place', 'wherever' ),
			'update_item'                => __( 'Update Place', 'wherever' ),
			'view_item'                  => __( 'View Place', 'wherever' ),
			'separate_items_with_commas' => __( 'Separate places with commas', 'wherever' ),
			'add_or_remove_items'        => __( 'Add or remove Places', 'wherever' ),
			'choose_from_most_used'      => __( 'Choose from the most used', 'wherever' ),
			'popular_items'              => __( 'Popular Places', 'wherever' ),
			'search_items'               => __( 'Search Places', 'wherever' ),
			'not_found'                  => __( 'Not Found', 'wherever' ),
			'no_terms'                   => __( 'No Places', 'wherever' ),
			'items_list'                 => __( 'Places list', 'wherever' ),
			'items_list_navigation'      => __( 'Places list navigation', 'wherever' ),
		);
		
		$args = array(
			'labels'                     => $labels,
			'hierarchical'               => false,
			'public'                     => true,
			'show_ui'                    => false,
			'show_admin_column'          => true,
			'show_in_nav_menus'          => false,
			'show_tagcloud'              => false,
		);
	
		register_taxonomy( 'wherever_place', array( 'wherever' ), $args );
	
	}
	
	/**
	 * Setup default places for the wherever_place custom taxonomy
	 *
	 * @since    1.0.0
	 */
	public function setup_default_places() {
				
		$default_places = array(
			array(
				'name'			=> __( 'Content', 'wherever' ),
				'slug'			=> 'content',
				'description'	=> __( 'Place content before, instead or after the content. See <strong>the_content();</strong> template function.', 'wherever' )
			),
			array(
				'name'			=> __( 'Sidebar', 'wherever' ),
				'slug'			=> 'sidebar',
				'description'	=> __( 'Place content before the template sidebar. See <strong>get_sidebar();</strong> template function.', 'wherever' )
			),
			array(
				'name'			=> __( 'Footer', 'wherever' ),
				'slug'			=> 'footer',
				'description'	=> __( 'Place content before the template footer. See <strong>get_footer();</strong> template function.', 'wherever' )
			) 
		);
		
		foreach( $default_places as $place ){
			
			$this->helpers->setup_wherever_place( $place, true );
			
		}
		
	}
	
	/**
	 * Adds wherever_place terms to the admin_js localisation variable
	 * @param  array $js 
	 * @return array     with wherever_place terms
	 */
	public function wherever_places_for_admin_js( $js ) {
		
		$js['wherever_places'] = array();
		
		$terms = $this->helpers->get_wherever_place_terms();
		
		foreach( $terms  as $term ) {
			$js['wherever_places'][] = array(
				'term_id' => $term->term_id,
				'name' => $term->name,
				'slug' => $term->slug,
				'description' => $term->description
			);
		}
		
		return $js;
	}

	/**
	 * Adds wherever_place terms to the admin_js localisation variable
	 * @param  array $js 
	 * @return array     with wherever_place terms
	 */
	public function wherever_rules_for_admin_js( $js ) {
		
		$all = __( 'Show on all posts, pages and custom post types. Good for general purpose like site navigation and footers.', 'wherever' );
		
		$js['wherever_rules'] = array(
			array(
				'location_type' => 'all',
				'condition' => '==',
				'description' => $all,
			),
			array(
				'location_type' => 'all',
				'condition' => '!=',
				'description' => $all,
			),
			array(
				'location_type' => 'post',
				'condition' => '==',
				'description' => __( 'Show on the selected post.', 'wherever' ),
			),
			array(
				'location_type' => 'post',
				'condition' => '!=',
				'description' => __( 'Don’t show on the selected post.', 'wherever' ),
			),
			array(
				'location_type' => 'post_type',
				'condition' => '==',
				'description' => __( 'Show on the selected post type.', 'wherever' ),
			),
			array(
				'location_type' => 'post_type',
				'condition' => '!=',
				'description' => __( 'Don’t show on the selected post type.', 'wherever' ),
			),
			array(
				'location_type' => 'post_cat',
				'condition' => '==',
				'description' => __( 'Show on the selected post category.', 'wherever' ),
			),
			array(
				'location_type' => 'post_cat',
				'condition' => '!=',
				'description' => __( 'Don’t show on the selected post category.', 'wherever' ),
			),
			array(
				'location_type' => 'page',
				'condition' => '==',
				'description' => __( 'Show on the selected page.', 'wherever' ),
			),
			array(
				'location_type' => 'page',
				'condition' => '!=',
				'description' => __( 'Don’t show on the selected page.', 'wherever' ),
			),
			array(
				'location_type' => 'page_type',
				'condition' => '==',
				'description' => __( 'Show on the selected page type.', 'wherever' ),
			),
			array(
				'location_type' => 'page_type',
				'condition' => '!=',
				'description' => __( 'Don’t on the selected page type.', 'wherever' ),
			),
			array(
				'location_type' => 'page_parent',
				'condition' => '==',
				'description' => __( 'Show on children of the selected page.', 'wherever' ),
			),
			array(
				'location_type' => 'page_parent',
				'condition' => '!=',
				'description' => __( 'Don’t show on children of the selected page.', 'wherever' ),
			),
		);
		
		return $js;
	}
}
