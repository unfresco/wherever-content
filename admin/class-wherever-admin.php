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
	
	private static $wherever_places_terms;
	
	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		
		self::$theme = wp_get_theme();
		self::$wherever_places_terms = array();
		
	}
	
	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wherever-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wherever-admin.js', array( 'jquery' ), $this->version, false );

	}
	
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
			'publicly_queryable'    => true,
			'capability_type'       => 'page',
		);
		register_post_type( 'wherever', $args );
	}
	
	public function polylang_compat( $post_types, $is_settings ) {
		
		$polylang_options = get_option( 'polylang' );

		if ( ! in_array( 'wherever', $polylang_options['post_types'] ) ) {
			// Auto-include wherever post_type into Polylang options
			$polylang_options['post_types'][] = 'wherever';
			update_option( 'polylang', $polylang_options );
		} 
		
		if ( $is_settings ) {
			// hides 'wherever' from the list of custom post types in Polylang settings
			unset( $post_types['wherever'] );
		
		} else {
			// enables language and translation management for 'wherever'
			$post_types['wherever'] = 'wherever';
		
		}
		
		return $post_types;
	
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
	
	public static function get_wherever_place_terms() {
		$terms = self::$wherever_places_terms;
		
		if ( empty( $terms ) ){
			$terms = get_terms(array(
				'taxonomy' => 'wherever_place',
				'hide_empty' => false
			));
			self::$wherever_places_terms = $terms;
		}
		
		return $terms;
	}
	
	public static function wherever_place_term_exist( $term_check ) {
		$exist = false;

		foreach( self::get_wherever_place_terms() as $term ){
			if ( $term_check == $term->slug ) {
				$exist = true;
				break;
			}
		}
		
		return $exist;
	}
	
	public static function wherever_place_get_term_by( $by, $term_check ) {
		foreach( self::get_wherever_place_terms() as $term ){
			if ( 'slug' == $by && $term_check == $term->slug ) {
				return $term;
				break;
			} else if ( 'id' == $by && $term_check == $term->term_id ) {
				return $term;
				break;
			}
		}
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
				#'description'	=> __( 'Puts content before the content. Default place.', 'wherever' )
			),
			array(
				'name'			=> __( 'Sidebar', 'wherever' ),
				'slug'			=> 'sidebar',
				#'description'	=> __( 'Puts content before the template sidebar. Default place.', 'wherever' )
			),
			array(
				'name'			=> __( 'Footer', 'wherever' ),
				'slug'			=> 'footer',
				#'description'	=> __( 'Puts content before the template footer. Default place.', 'wherever' )
			) 
		);
		
		foreach( $default_places as $place ){
			
			self::setup_wherever_place( $place, true );
			
		}
		
	}
	
	/**
	 * Setup default and theme defined places by inserting wherever_place terms or 
	 * recovering them from wherever options.
	 *
	 * @since    1.0.1
	 */
	public static function setup_wherever_place( $place, $is_default = false ) {
		
		if( !current_user_can('edit_posts' ) )
			return;
		
		$options = get_option( 'wherever' );

		$save_options_places_to = ( $is_default ? 'default_places' : 'registered_places' );
		
		$update_options = false;
		
		if ( ! self::wherever_place_term_exist( $place['slug'] ) ) {
			// Term doesn’t exist in DB -> insert
			
			$args = array(
				'slug' => $place['slug'],
			);
			
			if ( !empty( $place['description'] )) {
				
				$args['description'] = $place['description'];
				
			}

			$term = wp_insert_term( $place['name'], 'wherever_place', $args );

			if ( $is_default ) {
				// for default_places save right away
				
				$options[ $save_options_places_to ][] = $term['term_id'];
				
			} else {
				// for registered_places save theme dependent 
				
				if ( !in_array( self::$theme->stylesheet, $options[ $save_options_places_to ] ) ) {
					
					$options[ $save_options_places_to ][ self::$theme->stylesheet ] = array();
				
				}
				
				$options[ $save_options_places_to ][ self::$theme->stylesheet ][] = $term->term_id;
				
			}
			
			
			$update_options = true;
			
		} else {
			// Term already exists in DB. 
			
			$term = self::wherever_place_get_term_by( 'slug', $place['slug'] );
			
			// Check if theme already exists
			if ( !array_key_exists( self::$theme->stylesheet, $options[ $save_options_places_to ] ) ) {
				$options[ $save_options_places_to ][ self::$theme->stylesheet ] = array();
			
			}
			
			if ( !in_array( $term->term_id, $options[ $save_options_places_to ][ self::$theme->stylesheet ] ) ) {
				// Not present in wherever options -> recover theme dependent
				$options[ $save_options_places_to ][ self::$theme->stylesheet ][] = $term->term_id;
				$update_options = true;
				
			}
			
		}
		
		
		if ( $update_options ) {
			
			update_option( 'wherever', $options );
			
		}
		
		/* 
			ToDo:
			Handle unused, once registered places when there is no UI for deleting places 		
		*/
	}
	
	
	/*
		Setup custom fields
		
	*/	
	private function get_posts_for_select( $post_type ) {
		global $post, $wp;
		
		$post_type_object = get_post_type_object( $post_type );
		
		$post_type_posts = array();
		
		if ( $post_type_object->hierarchical ) {
			
			$args = array(
				'post_type'		=> $post_type,
				'hierarchical'	=> 1,
				'post_status'	=> 'publish'
			);
			
			$pages = get_pages( $args );
			
			if ( !empty( $pages ) ) {
				
				$depths = array();
				
				foreach ( $pages as $page ) {

					if ( array_key_exists( $page->post_parent, $depths ) ) {

						$depths[ $page->ID ] = $depths[$page->post_parent] + 1;
						
					} else {
						
						$depths[ $page->ID ] = 0;
						
					}
					
					$post_type_posts[$page->ID] = '&nbsp;' . str_repeat( '&nbsp;&nbsp;', $depths[ $page->ID ] ) . $page->post_title;
					
				}
				
			}
			
		} else {
			
			$args = array(
				'post_type' => $post_type,
				'post_status' => 'publish',
				'posts_per_page' => -1
			);
			
			$query = new WP_Query( $args );
	
			if ( $query->have_posts() ){
				
				while ( $query->have_posts() ): $query->the_post();
				
					$post_type_posts[$post->ID] = $post->post_title;
				
				endwhile;
				
			}
			
			wp_reset_postdata();
			
		}

		return $post_type_posts;
		
	}
	
	public function get_post_types_for_select() {
		
		$post_types = get_post_types( array( 'public' => true ) );
		
		return $post_types;
		
	}
	
	private function get_post_categories_for_select( $taxonomy ) {
		global $wp_version;
		
		$select_terms = array();
		
		
		if ( version_compare( $wp_version, '4.5'  ) >= 0 ) {
			
			$post_terms = get_terms(array(
				'taxonomy' => $taxonomy,
				'hide_empty' => false
			));
			
		} else {
			
			$post_terms = get_terms( $taxonomy );
		
		}
		
		foreach ( $post_terms as $term ) {
			
			$select_terms[$term->term_id] = $term->name  . ' (' . $term->count . ')';
			
		}
		
		return $select_terms;
		
	}
	
	private function get_rule_info( $key, $condition = '==' ) {

		$condition_string = ( '==' == $condition ? __( 'Show', 'wherever') : __('Don’t show', 'wherever') );
		
		$info = array(
			'location_type_all' => __('Show on all posts, pages and custom post types. Good for general purpose like site navigation and footers.', 'wherever'),
			'location_type_post' => sprintf( __('%s on the selected post.', 'wherever'), $condition_string ),
			'location_type_post_type' => sprintf( __('%s on the selected post type.', 'wherever'), $condition_string ),
			'location_type_post_cat' => sprintf( __('%s on the selected post category.', 'wherever'), $condition_string ),
			'location_type_page' => sprintf( __('%s on the selected page.', 'wherever'), $condition_string ),
			'location_type_page_type' => sprintf( __('%s on the selected page type.', 'wherever'), $condition_string ),
			'location_type_page_parent' => sprintf( __('%s on the selected page parent.', 'wherever'), $condition_string )
		);
		
		if ( array_key_exists( $key, $info ) ) {
			$string = '<p><span class="dashicons dashicons-move"></span> ' . $info[$key] . '</p>';
		} else {
			$string = __('No description yet for this rule', 'wherever' );
		}
		
		return $string;
	}
	
	// Todo private function get_page_type_for_select() {}
				
	public function get_places_for_options() {
		
		$args = array(
			'taxonomy' => 'wherever_place',
			'hide_empty' => false
		);
		
		$terms = get_terms( $args );
		
		$options_terms = array();
		
		foreach ( $terms as $term ) {
			
			$options_terms[ $term->slug ] = __( $term->name, 'wherever' );
			
		};
		
		return $options_terms;
		
	}
	
	/**
	 * Setup custom fields for wherever custom post type
	 * 
	 * @since    1.0.0
	 * @see https://carbonfields.net/docs/
	*/
	public function carbon_fields() {

		Container::make('post_meta', __( 'Configuration', 'wherever' ))
			->where( 'post_type', 'CUSTOM', function( $post_type ){
				$get_post_type = ( isset( $_GET['post_type'] ) ? $_GET['post_type'] : '' );
				if ( 'wherever' == $post_type || 'wherever' == $get_post_type ) {
					return true;
				} else {
					return false;
				}
			})
			->add_fields(array(
				
				Field::make('complex', 'wherever_rules', __( 'Show this content if:', 'wherever' ) )
					->setup_labels(array(
						'singular_name' => __( 'rule', 'wherever' ),
						'plural_name' => __( 'rules', 'wherever' )
					))
					->add_fields(array(
						Field::make('select', 'location_type', __( 'Location', 'wherever' ) )
							->add_options(array(
								'all' => __( 'Everywhere', 'wherever' ),
								'post' => __( 'Post', 'wherever' ),
								'post_type' => __( 'Post Type', 'wherever' ),
								'post_cat' => __( 'Post Category', 'wherever' ),
								'page' => __( 'Page', 'wherever' ),
								'page_type' => __( 'Page Type', 'wherever'),
								'page_parent' => __( 'Page Parent', 'wherever' )
								#'page_template' => 'Page Template'
							))
							->set_default_value('all'),
						Field::make('select', 'location_condition', __( 'Condition', 'wherever' ) )
							->add_options(array(
								'==' => __( 'is', 'wherever' ),
								'!=' => __( 'is not', 'wherever' )
							))
							->set_conditional_logic(array(
								array(
								'field' => 'location_type',
								'value' => array('all'),
								'compare' => 'NOT IN'
								)
							)),
						Field::make('select', 'post', __( 'Post', 'wherever' ) )
							->add_options( $this->get_posts_for_select('post') )
							->set_conditional_logic(array(
								array(
								'field' => 'location_type',
								'value' => 'post',
								'compare' => '='
								)
							)),
						Field::make('select', 'post_type', __( 'Post Type', 'wherever' ) )
							->add_options( array( $this, 'get_post_types_for_select' ) )
							->set_conditional_logic(array(
								array(
								'field' => 'location_type',
								'value' => 'post_type',
								'compare' => '='
								)
							)),
						Field::make('select', 'post_cat', __( 'Post Category', 'wherever' ) )
							->add_options( $this->get_post_categories_for_select( 'category' ) )
							->set_conditional_logic(array(
								array(
								'field' => 'location_type',
								'value' => 'post_cat',
								'compare' => '='
								)
							)),
						Field::make('select', 'page', __( 'Page', 'wherever' ) )
							->add_options( $this->get_posts_for_select('page') )
							->set_conditional_logic(array(
								array(
								'field' => 'location_type',
								'value' => array('page', 'page_parent'),
								'compare' => 'IN'
								)
							)),
						Field::make('select', 'page_type', __( 'Page Type', 'wherever' ) )
							->add_options(array(
								'home' => __( 'Home (Blog & Front Page )', 'wherever' ),
								'front_page' => __( 'Front Page', 'wherever' ),
								'archive' => __( 'Archive', 'wherever' )
							))
							->set_conditional_logic(array(
								array(
								'field' => 'location_type',
								'value' => 'page_type',
								'compare' => '='
								)
							)),
						Field::make('select', 'archive_post_type', __( 'Archive Post Type', 'wherever' ) )
							->add_options( array( $this, 'get_post_types_for_select' ) )
							->set_conditional_logic(array(
								array(
								'field' => 'page_type',
								'value' => 'archive',
								'compare' => '='
								)
							)),
						// Rule descriptions
						Field::make( 'html', 'location_type_all_info' )
							->set_html( $this->get_rule_info('location_type_all') )
							->set_conditional_logic(array(
								array(
								'field' => 'location_type',
								'value' => array('all'),
								'compare' => 'IN'
								)
							)),
						Field::make( 'html', 'location_type_post_info' )
							->set_html( $this->get_rule_info('location_type_post') )
							->set_conditional_logic(array(
								array(
								'field' => 'location_type',
								'value' => array('post'),
								'compare' => '='
								),
								array(
								'field' => 'location_condition',
								'value' => array('=='),
								'compare' => '='
								)
							)),
						Field::make( 'html', 'location_type_post_info_not_in' )
							->set_html( $this->get_rule_info('location_type_post', '!=' ) )
							->set_conditional_logic(array(
								array(
								'field' => 'location_type',
								'value' => array('post'),
								'compare' => '='
								),
								array(
								'field' => 'location_condition',
								'value' => array('!='),
								'compare' => '='
								)
							)),
						Field::make( 'html', 'location_type_post_type_info' )
							->set_html( $this->get_rule_info('location_type_post_type') )
							->set_conditional_logic(array(
								array(
								'field' => 'location_type',
								'value' => array('post_type'),
								'compare' => '='
								),
								array(
								'field' => 'location_condition',
								'value' => array('=='),
								'compare' => '='
								)
							)),
						Field::make( 'html', 'location_type_post_type_info_not_in' )
							->set_html( $this->get_rule_info('location_type_post_type', '!=' ) )
							->set_conditional_logic(array(
								array(
								'field' => 'location_type',
								'value' => array('post_type'),
								'compare' => '='
								),
								array(
								'field' => 'location_condition',
								'value' => array('!='),
								'compare' => '='
								)
							)),
						Field::make( 'html', 'location_type_post_cat_info' )
							->set_html( $this->get_rule_info('location_type_post_cat') )
							->set_conditional_logic(array(
								array(
								'field' => 'location_type',
								'value' => array('post_cat'),
								'compare' => '='
								),
								array(
								'field' => 'location_condition',
								'value' => array('=='),
								'compare' => '='
								)
							)),
						Field::make( 'html', 'location_type_post_cat_info_not_in' )
							->set_html( $this->get_rule_info('location_type_post_cat', '!=' ) )
							->set_conditional_logic(array(
								array(
								'field' => 'location_type',
								'value' => array('post_cat'),
								'compare' => '='
								),
								array(
								'field' => 'location_condition',
								'value' => array('!='),
								'compare' => '='
								)
							)),
						Field::make( 'html', 'location_type_page_info' )
							->set_html( $this->get_rule_info('location_type_page') )
							->set_conditional_logic(array(
								array(
								'field' => 'location_type',
								'value' => array('page'),
								'compare' => '='
								),
								array(
								'field' => 'location_condition',
								'value' => array('=='),
								'compare' => '='
								)
							)),
						Field::make( 'html', 'location_type_page_info_not_in' )
							->set_html( $this->get_rule_info('location_type_page', '!=' ) )
							->set_conditional_logic(array(
								array(
								'field' => 'location_type',
								'value' => array('page'),
								'compare' => '='
								),
								array(
								'field' => 'location_condition',
								'value' => array('!='),
								'compare' => '='
								)
							)),
						Field::make( 'html', 'location_type_page_type_info' )
							->set_html( $this->get_rule_info('location_type_page_type') )
							->set_conditional_logic(array(
								array(
								'field' => 'location_type',
								'value' => array('page_type'),
								'compare' => '='
								),
								array(
								'field' => 'location_condition',
								'value' => array('=='),
								'compare' => '='
								)
							)),
						Field::make( 'html', 'location_type_page_type_info_not_in' )
							->set_html( $this->get_rule_info('location_type_page_type', '!=' ) )
							->set_conditional_logic(array(
								array(
								'field' => 'location_type',
								'value' => array('page_type'),
								'compare' => '='
								),
								array(
								'field' => 'location_condition',
								'value' => array('!='),
								'compare' => '='
								)
							)),
						Field::make( 'html', 'location_type_page_parent_info' )
							->set_html( $this->get_rule_info('location_type_page_parent') )
							->set_conditional_logic(array(
								array(
								'field' => 'location_type',
								'value' => array('page_parent'),
								'compare' => '='
								),
								array(
								'field' => 'location_condition',
								'value' => array('=='),
								'compare' => '='
								)
							)),
						Field::make( 'html', 'location_type_page_parent_info_not_in' )
							->set_html( $this->get_rule_info('location_type_page_parent', '!=' ) )
							->set_conditional_logic(array(
								array(
								'field' => 'location_type',
								'value' => array('page_parent'),
								'compare' => '='
								),
								array(
								'field' => 'location_condition',
								'value' => array('!='),
								'compare' => '='
								)
							)),
					))
					->set_default_value(array(
						array( 'location_type' => 'all' )
					))
					->set_min(1)
					->set_width(50),
				Field::make('complex', 'wherever_places', __( 'Place(s) to show this content:', 'wherever' ) )
					->setup_labels(array(
						'singular_name' => __( 'place', 'wherever' ),
						'plural_name' => __( 'places', 'wherever' )
					))
					->add_fields(array(
						Field::make('select', 'place', __( 'Place', 'wherever' ) )
							->add_options( array( $this,  'get_places_for_options' ) )
							->set_default_value('content'),
						Field::make('radio', 'placement', __( 'Placement', 'wherever' ) )
							->add_options(array(
								'before' => __( 'Before', 'wherever' ),
								'instead' => __( 'Instead', 'wherever' ),
								'after' => __( 'After', 'wherever' )
							))
							->set_default_value('before')
							->set_conditional_logic(array(
								array(
								'field' => 'place',
								'value' => array('content'),
								'compare' => 'IN'
								)
							)),
						Field::make('text', 'order', __( 'Order', 'wherever' ) )
							->set_default_value(5)
							->set_classes('number')
					))
					->set_default_value(array(
						array(
							'place' => 'content',
							'placement' => 'before',
							'order' => 5
						)
					))
					->set_min(1)
					->set_width(50),
			));
	}
	
	/**
	 * Display admin notice if Carbon Field Plugin not installed/activated
	 *
	 * @since    1.0.2
	 */
	public function framework_notice() {
		?>
		<div class="notice notice-error is-dismissible">
			<p><?php _e( 'The <strong>Wherever Content</strong> plugin is not ready yet to work. Please deactivate the Carbon fields plugin or update it to a version higher than 2.0. If you need to work with Carbon fields version lower than 2.0, please install a 1.x version of Wherever Content.', 'wherever' ); ?></p>
		</div>
		<?php
	}
	
	/**
	 * Set wherever_place taxonomy on saving wherever through ajax editing
	 *
	 * @since    1.0.0
	 */
	public function save_post( $post_ID ) {
		
		if ( 'wherever' == get_post_type( $post_ID ) && empty( get_current_screen() ) && ! wp_is_post_autosave( $post_ID )) {
			// Saving though edit.php?post_type=wherever by ajax
			// get_current_screen() is empty in edit.php?post_type=wherever / ajax place editing
			
			$terms = get_the_terms( $post_ID, 'wherever_place' );
			
			$meta_terms = array();
			
			foreach ( $terms as $term ) {
				$meta_terms[] = $term->slug;
			}
			
			update_post_meta( $post_ID, '_wherever_place', $meta_terms );			
			
		}
		
	}


	/**
	 * Set wherever_place taxonomy on saving wherever custom post type
	 *
	 * @since    1.0.0
	 */
	public function carbon_fields_save( $post_ID ) {

		if ( 'wherever' !== get_post_type( $post_ID )  )
			return;
			
		// copy selection to post terms
		$wherever_places = carbon_get_post_meta( $post_ID, 'wherever_places' );
		$wherever_places_terms = array();
				
		foreach ( $wherever_places as $place ) {
			
			$wherever_places_terms[] = $place['place'];
			
		}
		
		wp_set_object_terms( $post_ID, $wherever_places_terms, 'wherever_place' );
		
	}
	
}
