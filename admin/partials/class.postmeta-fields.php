<?php

namespace Wherever_Content\Admin;

use Carbon_Fields\Container;
use Carbon_Fields\Field;


class Postmeta_Fields {
	
	function __construct() {
		
	}
	
	/**
	 * Setup custom fields for wherever custom post type
	 * 
	 * @since    1.0.0
	 * @see https://carbonfields.net/docs/
	*/
	public function carbon_fields_post_meta() {
		
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
					->add_fields( apply_filters( 'wherever_admin/rules', array() ) )
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
					->add_fields( apply_filters( 'wherever_admin/places', array() ) )
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

	/**
	 * Set wherever_place taxonomy on saving wherever through ajax editing
	 *
	 * @since    1.0.0
	 */
	public function save_post( $post_ID ) {
		
		if ( ! function_exists( 'get_current_screen' ) )
			return;
		
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
		

}
