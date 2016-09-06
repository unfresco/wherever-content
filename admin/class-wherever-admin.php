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
			'add_new_item'          => __( 'Add New Wherever Content', 'wherever' ),
			'add_new'               => __( 'Add New', 'wherever' ),
			'new_item'              => __( 'New Wherever Content', 'wherever' ),
			'edit_item'             => __( 'Edit Wherever Content', 'wherever' ),
			'update_item'           => __( 'Update Wherever Content', 'wherever' ),
			'view_item'             => __( 'View Wherever Content', 'wherever' ),
			'search_items'          => __( 'Search Wherever Content', 'wherever' ),
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
				'name'			=> __( 'Title', 'wherever' ),
				'slug'			=> 'title',
				#'description'	=> __( 'Puts content before the title. Default place.', 'wherever' )
			),
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
				
		if ( !term_exists( $place['slug'], 'wherever_place' ) ) {
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
			
			$term = get_term_by( 'slug', $place['slug'], 'wherever_place' );			
			
			if ( !in_array( $term->term_id, $options[ $save_options_places_to ] ) ) {
				// Not present in wherever options -> recover theme dependent
				
				if ( !in_array( self::$theme->stylesheet, $options[ $save_options_places_to ] ) ) {
					
					$options[ $save_options_places_to ][ self::$theme->stylesheet ] = array();
				
				}
				
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
	
	private function get_posts_for_select( $post_type ){
		global $post, $wp;
				
		$args = array(
			'post_type' => $post_type,
			'post_status' => 'publish'
		);
		
		$post_type_posts = array();
		
		$query = new WP_Query($args);
		
		if ( $query->have_posts() ):
			while ( $query->have_posts() ): $query->the_post();
				$post_type_posts[$post->ID] = $post->post_title;
			endwhile;		
		endif;
		
		wp_reset_postdata();

		return $post_type_posts;
		
	}
	
	private function get_post_types_for_select() {
		
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
		
		foreach( $post_terms as $term ){
			$select_terms[$term->term_id] = $term->name  . ' (' . $term->count . ')';
		}		
		
		return $select_terms;
		
	}

	// Todo private function get_page_type_for_select() {}
				
	private function get_places_for_options() {
		
		$terms = get_terms( array(
		    'taxonomy' => 'wherever_place',
		    'hide_empty' => false,
		) );
		
		$options_terms = array();
		
		foreach( $terms as $term ){
			
			$options_terms[ $term->slug ] = $term->name;
			
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
			->show_on_post_type('wherever')
			->add_fields(array(
				
		    	Field::make('complex', 'wherever_rules', __( 'Show this content if:', 'wherever' ) )
		    		->setup_labels(array(
						'singular_name' => 'rule',
						'plural_name' => 'rules'
		    		))
		    		#->set_required(true)
			    	->add_fields(array(
				    	Field::make('select', 'location_type', __( 'Location', 'wherever' ) )
				    		->add_options(array(
					    		'all' => 'Everywhere',
								'post' => 'Post',
								'post_type' => 'Post Type',
								'post_cat' => 'Post Category',
								'page' => 'Page',
								'page_type' => 'Page Type',
								#'page_parent' => 'Page Parent',
								#'page_template' => 'Page Template'
							)),
				    	Field::make('select', 'location_condition', __( 'Condition', 'wherever' ) )
				    		->add_options(array(
					    		'==' => 'is',
								'!=' => 'is not'
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
				    		->add_options( $this->get_post_types_for_select() )
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
								'value' => 'page',
								'compare' => '='
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
				    		->add_options( $this->get_places_for_options() ),
				    	Field::make('radio', 'placement', __( 'Placement', 'wherever' ) )
				    		->add_options(array(
					    		'before' => __( 'Before', 'wherever' ),
					    		'instead' => __( 'Instead', 'wherever' ),
					    		'after' => __( 'After', 'wherever' )
				    		)),
				    	Field::make('text', 'order', __( 'Order', 'wherever' ) )
				    		->set_default_value(5)
				    		->add_class('number')
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
	public function carbon_fields_missing_notice() {
		
		$network = ( is_multisite() ? '/network' : '' );
		
		$install_url = network_site_url( '/wp-admin' . $network . '/plugin-install.php?tab=search&s=Carbon+Fields' );

		?>
	    <div class="notice notice-error is-dismissible">
	        <p><?php printf( __( 'The <strong>Wherever Content</strong> plugin is not ready yet to work. You still need to install and/or activate the <a href="https://wordpress.org/plugins/carbon-fields/" target="_blank">Carbon Fields</a> plugin. <a href="%s">Do it now!</a>', 'wherever' ), $install_url ); ?></p>
	    </div>
	    <?php
	}
	
	/**
	 * Set wherever_place taxonomy on saving wherever through ajax editing
	 *
	 * @since    1.0.0
	 */
	public function post_updated( $post_ID, $post_after, $post_before ) {		

		if ( 'wherever' == get_post_type( $post_ID ) ) {
			
			$current_screen = get_current_screen();
			
			// get_current_screen() is empty in edit.php?post_type=wherever / ajax place editing
			if ( empty( $current_screen ) ) {
				// Saving though edit.php?post_type=wherever / ajax
				$terms = get_the_terms( $post_ID, 'wherever_place' );
				
				$meta_terms = array();
				
				foreach ( $terms as $term ) {
					$meta_terms[] = $term->slug;
				}
				
				update_post_meta( $post_ID, '_wherever_place', $meta_terms );			
				
			}
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
		$wherever_places = carbon_get_post_meta( $post_ID, 'wherever_places', 'complex' );
		$wherever_places_terms = array();
				
		foreach( $wherever_places as $place ){
			
			$wherever_places_terms[] = $place['place'];
			
		}
		
		wp_set_object_terms( $post_ID, $wherever_places_terms, 'wherever_place' );
		
	}
	
}
