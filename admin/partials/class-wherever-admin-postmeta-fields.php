<?php

use Carbon_Fields\Container;
use Carbon_Fields\Field;


class Wherever_Admin_Postmeta_Fields {
	
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
	
	/**
	 * Helper function for populating the description of carbon fields
	 * @param  string $key       slug for lating the description
	 * @param  string $condition == or !=
	 * @return string            resulting string
	 */
	public function get_rule_info( $key, $condition = '==' ) {

		$condition_string = ( '==' == $condition ? __( 'Show', 'wherever') : __('Don’t show', 'wherever') );
		
		$info = array(
			'location_type_all' => __('Show on all posts, pages and custom post types. Good for general purpose like site navigation and footers.', 'wherever'),
			'location_type_post' => sprintf( __('%s on the selected post.', 'wherever'), $condition_string ),
			'location_type_post_type' => sprintf( __('%s on the selected post type.', 'wherever'), $condition_string ),
			'location_type_post_cat' => sprintf( __('%s on the selected post category.', 'wherever'), $condition_string ),
			'location_type_page' => sprintf( __('%s on the selected page.', 'wherever'), $condition_string ),
			'location_type_page_type' => sprintf( __('%s on the selected page type.', 'wherever'), $condition_string ),
			'location_type_page_parent' => sprintf( __('%s on children of the selected page.', 'wherever'), $condition_string )
		);
		
		if ( array_key_exists( $key, $info ) ) {
			$string = '<p><span class="dashicons dashicons-move"></span> ' . $info[$key] . '</p>';
		} else {
			$string = __('No description yet for this rule', 'wherever' );
		}
		
		return $string;
	}
	
	/**
	 * get terms for poplating places in carbon fields
	 * @return array of terms
	 */
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
							->add_options(   $this->get_post_types_for_select() )
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
