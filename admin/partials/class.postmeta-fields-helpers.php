<?php

namespace Wherever_Content\Admin;


class Postmeta_Fields_Helpers {
	
	function __construct() {
		
	}
	
	/**
	 * Helper function for populating carbon fields with post/pages/custom-post-types
	 * @param  string $taxonomy the post_type slug 
	 * @return array            a list of post/page/custom-post-types
	 */	
	public function get_posts_for_select( $post_type ) {
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
					
					$post_type_posts[$page->ID] = str_repeat( '– ', $depths[ $page->ID ] ) . $page->post_title;
					
				}
				
			}
			
		} else {
			
			$args = array(
				'post_type' => $post_type,
				'post_status' => 'publish',
				'posts_per_page' => -1
			);
			
			$query = new \WP_Query( $args );
	
			if ( $query->have_posts() ){
				
				while ( $query->have_posts() ): $query->the_post();
				
					$post_type_posts[$post->ID] = $post->post_title;
				
				endwhile;
				
			}
			
			wp_reset_postdata();
			
		}

		return $post_type_posts;
		
	}
	
	/**
	 * Helper function for populating carbon fields with post-types
	 * @param  string $taxonomy the taxonomy slug 
	 * @return array            the public post-types
	 */
	public function get_post_types_for_select() {
		
		$post_types = get_post_types( array( 'public' => true ) );
		
		return $post_types;
		
	}
	
	/**
	 * Helper function for populating carbon fields with terms of one taxonomy
	 * @param  string $taxonomy the taxonomy slug 
	 * @return array            the terms
	 */
	public function get_post_categories_for_select( $taxonomy ) {
		global $wp_version;
		
		$select_terms = array();

		$post_terms = get_terms(array(
			'taxonomy' => $taxonomy,
			'hide_empty' => false
		));
			
		foreach ( $post_terms as $term ) {
			
			$select_terms[$term->term_id] = $term->name  . ' (' . $term->count . ')';
			
		}
		
		return $select_terms;
		
	}
	
	/**
	 * get terms for populating places in carbon fields
	 * @return array of terms
	 */
	public function get_places_for_options() {

		$theme = wp_get_theme();
		$theme_stylesheet = $theme->get_stylesheet();
		
		$options = get_option('wherever_status');
		$default_places = $options['default_places'];
		$registered_places = $options['registered_places'][$theme_stylesheet];
		$options_terms = array();

		
		// Add default places
		foreach( $default_places as $term_id ) {
			$term = get_term_by('id', $term_id, 'wherever_place' );
			$options_terms[ $term->slug ] = __( $term->name, 'wherever' );
		}
		
		// Add registered places
		foreach( $registered_places as $term_id ) {
			$term = get_term_by('id', $term_id, 'wherever_place' );
			$options_terms[ $term->slug ] = $term->name . ' (' . __( 'Custom', 'wherever') . ')';
		}
		
		// Sort alfabetical
		asort( $options_terms );
		
		return $options_terms;
		
	}
	
}
