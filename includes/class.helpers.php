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
	
	private $wherever_status_options;
	
	function __construct() {
		
		$this->theme = wp_get_theme();
		$this->theme_stylesheet = $this->theme->get_stylesheet();
		$this->wherever_places_terms = array();
		$this->save_wherever_status = false;
		
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
	
	public function get_wherever_status_options() {
		
		if ( empty( $this->wherever_status_options ) ) {
			$this->wherever_status_options = get_option( 'wherever_status' );
		}
		
		return $this->wherever_status_options;
		
	}
	
	public function get_wherever_status_options_default_places() {
		
		return $this->get_wherever_status_options()['default_places'];
		
	}
	
	public function get_wherever_status_options_registered_places() {
		
		return $this->get_wherever_status_options()[ 'registered_places' ][ $this->theme_stylesheet ];
		
	}
	
	public function add_wherever_status_options_default_place( $value ) {
		
		$this->wherever_status_options['default_places'][] = $value;
		$this->save_wherever_status = true;
		
	}
	
	public function add_wherever_status_options_registered_place( $value ) {
		
		$this->wherever_status_options[ 'registered_places' ][ $this->theme_stylesheet ][] = $value;
		$this->save_wherever_status = true;
		
	}
	
	public function reset_wherever_status_options_registered_places( $places ) {
		
		$this->wherever_status_options[ 'registered_places' ][ $this->theme_stylesheet ] = $places;
		$this->save_wherever_status = true;
		
	}
	
	private function wherever_place_term_exist( $term_check ) {
		$exist = false;

		foreach( $this->get_wherever_place_terms() as $term ){
			if ( $term_check == $term->slug ) {
				$exist = true;
				break;
			}
		}
		
		return $exist;
	}
	
	private function wherever_place_get_term_by( $by, $term_check ) {
		
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
	
	private function insert_wherever_place_term( $place ) {
		
		$args = array(
			'slug' => $place['slug'],
			'description' => ( array_key_exists( 'description', $place ) ? $place['description'] : '' ) 
		);
		
		$term = wp_insert_term( $place['name'], 'wherever_place', $args );
		
		return $term;
		
	}
	
	/**
	 * Setup default and theme defined places by inserting wherever_place terms or 
	 * recovering them from wherever options.
	 *
	 * @since    1.0.1
	 */
	public function setup_wherever_default_place( $place ) {
		
		if( !current_user_can('edit_posts' ) )
			return;
		
		if ( ! $this->wherever_place_term_exist( $place['slug'] ) ) {
			// Term doesn’t exist in DB -> insert
			$term = $this->insert_wherever_place_term( $place );
			$this->add_wherever_status_options_default_place( $term['term_id'] );
			
		} else {
			// Term already exists in DB. Check options & update
			$term = $this->wherever_place_get_term_by( 'slug', $place['slug'] );
			
			// Check if terms saved to current theme
			if ( !in_array( $term->term_id, $this->get_wherever_status_options_default_places() ) ) {
				// Not present in wherever options -> recover theme dependent
				$this->add_wherever_status_options_default_place( $term->term_id );
				
				$this->save_wherever_place_descriptions( $place );
				
			}
			
		}
		
	}
	
	public function setup_wherever_status_options_registered_places( $places ) {
		
		foreach( $places as $place ){
			
			if ( ! $this->wherever_place_term_exist( $place['slug'] ) ) {
				// Term doesn’t exist in DB -> insert
				$term  = $this->insert_wherever_place_term( $place );
				
				$this->add_wherever_status_options_registered_place( $term['term_id'] );
				
			} else {
				// Term already exists in DB. Check options & update
				$term = $this->wherever_place_get_term_by( 'slug', $place['slug'] );
				
				// Check if terms saved to current theme
				if ( ! in_array( $term->term_id, $this->get_wherever_status_options_registered_places() ) ) {
					// Not present in wherever options -> recover theme dependent
					$this->add_wherever_status_options_registered_place( $term->term_id );
					$this->save_wherever_place_descriptions( $place );
					$this->save_wherever_registered_place_descriptions( $place );
				}
				
			}
			
		}
		
	}

	public function save_wherever_status_option() {

		if ( $this->save_wherever_status ) {
			
			update_option( 'wherever_status', $this->wherever_status_options );
			$this->save_wherever_status = false;
			
		}
		
	}

	public function save_wherever_place_descriptions( $place ) {
		$post_type = ( isset( $_GET['post'] ) ? get_post_type($_GET['post']) : '' );
		
		// If in admin and editing wherever content may update term descriptions
		if ( is_admin() && 'wherever' == $post_type ) {
			
			$term = $this->wherever_place_get_term_by( 'slug', $place['slug'] );
			
			// Update description of places for UI guidance (both default & registered )
			if ( array_key_exists( 'description', $place ) && $term->description !== $place['description'] ) {
				
				$args = array(
					'description' => $place['description']
				);
				
				$term_id = wp_update_term( $term->term_id, 'wherever_place', $args );
			
			}
			
			

		}
	}
	
	public function save_wherever_registered_place_descriptions( $place ) {
		// Update description of registered places for UI guidance
		if ( ! array_key_exists( 'description', $place ) ) {
			
			$default_registed_description = sprintf( __( 'Place content into the custom place "%1$s" refered by the slug "%2$s". See the <strong>do_action( \'wherever_place\', \'%2$s\' );</strong> theme function.', 'wherever' ), $place['name'], $place['slug'] );
			
			$args = array(
				'description' => $default_registed_description
			);
			
			$term = $this->wherever_place_get_term_by( 'slug', $place['slug'] );
			
			if ( empty( $term->description ) || $term->description  != $default_registed_description ) {
				
				$term_id = wp_update_term( $term->term_id, 'wherever_place', $args );
			
			}
			
		}
	}
}
