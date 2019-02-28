<?php

namespace Wherever_Content;

/**
 * The file to have a place to put all the general purpuse helper functions
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

class Helpers {
	
	private $theme;
	
	private $wherever_places_terms;
	
	function __construct() {
		
		$this->theme = wp_get_theme();
		$this->theme_stylesheet = $this->theme->get_stylesheet();
		$this->wherever_places_terms = array();
		
	}
	
	public function is_rest_request() {
		$bIsRest = false;
		
		if ( function_exists( 'rest_url' ) && !empty( $_SERVER[ 'REQUEST_URI' ] ) ) {
			
			$sRestUrlBase = get_rest_url( get_current_blog_id(), '/' );
			$sRestPath = trim( parse_url( $sRestUrlBase, PHP_URL_PATH ), '/' );
			$sRequestPath = trim( $_SERVER[ 'REQUEST_URI' ], '/' );
			$bIsRest = ( strpos( $sRequestPath, $sRestPath ) === 0 );
		}
		
		return $bIsRest;
	
	}
	
	public function is_metafields_loaded() {
		
		if ( empty( $this->metafields_loaded ) ) {
			$metafields = new Metafields();
			$this->metafields_loaded = $metafields->is_loaded();
		}
		
		return $this->metafields_loaded;

	}
	
	public function get_wherever_place_terms() {
		$terms = $this->wherever_places_terms;
		
		if ( empty( $terms ) ){
			$terms = get_terms(array(
				'taxonomy' => 'wherever_place',
				'hide_empty' => false
			));
			$this->wherever_places_terms = $terms;
		}
		
		return $terms;
		
	}
	
	public function wherever_place_term_exist( $term_check ) {
		$exist = false;

		foreach( $this->get_wherever_place_terms() as $term ){
			if ( $term_check == $term->slug ) {
				$exist = true;
				break;
			}
		}
		
		return $exist;
	}
	
	public function wherever_place_get_term_by( $by, $term_check ) {
		
		foreach( $this->get_wherever_place_terms() as $term ){
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
	 * Setup default and theme defined places by inserting wherever_place terms or 
	 * recovering them from wherever options.
	 *
	 * @since    1.0.1
	 */
	public function setup_wherever_place( $place, $is_default = false ) {
		
		if( !current_user_can('edit_posts' ) )
			return;
		
		$options = get_option( 'wherever_status' );

		$save_options_places_to = ( $is_default ? 'default_places' : 'registered_places' );
		
		$update_options = false;
		
		if ( ! $this->wherever_place_term_exist( $place['slug'] ) ) {
			// Term doesn’t exist in DB -> insert
			
			$args = array(
				'slug' => $place['slug'],
				'description' => ( array_key_exists( 'description', $place ) ? $place['description'] : '' ) 
			);

			$term = wp_insert_term( $place['name'], 'wherever_place', $args );

			if ( $is_default ) {
				// for default_places save right away
				
				$options[ $save_options_places_to ][] = $term['term_id'];
				
			} else {
				// for registered_places save theme dependent 
				
				if ( !in_array( $this->theme_stylesheet, $options[ $save_options_places_to ] ) ) {
					
					$options[ $save_options_places_to ][ $this->theme_stylesheet ] = array();
				
				}
				
				$options[ $save_options_places_to ][ $this->theme_stylesheet ][] = $term['term_id'];
				
			}
			
			
			$update_options = true;
			
		} else {
			// Term already exists in DB -> update
			
			$term = $this->wherever_place_get_term_by( 'slug', $place['slug'] );
			
			// Check if theme already exists
			if ( !array_key_exists( $this->theme_stylesheet, $options[ $save_options_places_to ] ) ) {
				$options[ $save_options_places_to ][ $this->theme_stylesheet ] = array();
			
			}
			
			// Check if terms saved to current theme
			if ( !in_array( $term->term_id, $options[ $save_options_places_to ][ $this->theme_stylesheet ] ) ) {
				// Not present in wherever options -> recover theme dependent
				$options[ $save_options_places_to ][ $this->theme_stylesheet ][] = $term->term_id;
				$update_options = true;
				
			}
			
			
			$post_type = ( isset( $_GET['post'] ) ? get_post_type($_GET['post']) : '' );
			// If in admin and editing wherever content may update term descriptions
			if ( is_admin() && 'wherever' == $post_type ) {
				
				// Update description of places for UI guidance (both default & registered )
				if ( array_key_exists( 'description', $place ) && $term->description !== $place['description'] ) {
					
					$args = array(
						'description' => $place['description']
					);
					
					$term_id = wp_update_term( $term->term_id, 'wherever_place', $args );
				
				}
				
				// Update description of registered places for UI guidance
				if ( ! $is_default && ! array_key_exists( 'description', $place ) ) {
					
					$default_registed_description = sprintf( __( 'Place content into the custom place "%1$s" refered by the slug "%2$s". See the <strong>do_action( \'wherever_place\', \'%2$s\' );</strong> theme function.', 'wherever' ), $place['name'], $place['slug'] );
					
					$args = array(
						'description' => $default_registed_description
					);
					
					if ( empty( $term->description ) || $term->description  != $default_registed_description ) {
						
						$term_id = wp_update_term( $term->term_id, 'wherever_place', $args );
					
					}
					
				}
				
			}
			

			
		}
		
		
		if ( $update_options ) {
			
			update_option( 'wherever_status', $options );
			
		}

	}

	public function register_wherever_places( $places ) {
		
		if ( ! $this->is_metafields_loaded() ) {
			return;
		}
		
		if ( !empty( $places ) ) {
			// Check if current registered is lower than the registered in wherever_status registered places
			$options = get_option( 'wherever_status' );
			$registered_places = $options['registered_places'];

			if ( !empty( $registered_places ) &&  array_key_exists( $this->theme_stylesheet, $registered_places ) ) {
				if ( count($places) < $registered_places[$this->theme_stylesheet] ) {
					// unregister places from options
					$new_registered_places = array();
					
					foreach( $registered_places[$this->theme_stylesheet] as $place_term_id ) {
						$new_registered_places[] = $place_term_id;
					}
					
					$options['registered_places'] = $new_registered_places;
					
					update_option( 'wherever_status', $options );
					
				}
			}
			
			// register current
			foreach( $places as $place ){
				
				$this->setup_wherever_place( $place );
				
			}
			
		}
		
	}
}
