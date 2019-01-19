<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://grell.es
 * @since      1.0.0
 *
 * @package    Wherever
 * @subpackage Wherever/public
 */

require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/wherever-public-api.php';

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wherever
 * @subpackage Wherever/public
 * @author     Adrián Ortiz Arandes <adrian@grell.es>
 */
class Wherever_Public {

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
	 * Holds an array of all wherever posts.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      array    $wherevers    Holds an array of all wherever posts.
	 */
	private $wherevers;
	
	/**
	 * All places currently associated to wherever posts.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      array    $places    All places currently associated to wherever posts.
	 */
	private $places;
	
	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version, $helpers ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->helpers = $helpers;
		
		$this->wherevers = array();
		$this->places = array();
		
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wherever-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since     1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wherever-public.js', array( 'jquery' ), $this->version, false );

	}
	
	/**
	 * Setup $wherever and $places on wp_head action
	 *
	 * @see		Wherever::define_public_hooks()
	 * @since	1.0.0
	 */
	public function setup_wherevers() {
		global $post, $wp;
		
		$args = array(
			'post_type' => 'wherever',
			'post_status' => 'publish'
		);
		
		$query = new WP_Query($args);
		
		if ( !$query->have_posts() )
			return;
			
		while ( $query->have_posts() ): $query->the_post();
			
			$this->wherevers[] = array(
				'post'	=> $post,
				'the_content' => apply_filters('the_content', $post->post_content ), // Todo: apply_filters only on wherevers to display in build_wherevers
				'wherever_rules' => carbon_get_the_post_meta('wherever_rules'),
				'wherever_places' => carbon_get_the_post_meta('wherever_places'),
				'in_current_location' => false
			);
			
		endwhile;
		
		wp_reset_postdata();
		
		foreach( $this->wherevers as $key => $wherever ){
		
			// Set if in current location
			if ( $this->in_current_location( $wherever ) ) {
				
				$this->wherevers[$key]['in_current_location'] = true;
				
				// Setup page builder styles for $wherever['post']->ID
				if ( function_exists('siteorigin_panels_render') ) {
					
					$panel_content = siteorigin_panels_render( $wherever['post']->ID );
				
				}
				
			}
			
			// Setup post/page objects instead of ID’s
			if( !empty( $wherever['wherever_rules'] ) ){
				
				foreach( $wherever['wherever_rules'] as $location_key => $location ){
					
					if( 'post' ==  $location['location_type'] ){
						
						$this->wherevers[$key]['wherever_rules'][$location_key]['post'] = get_post( $location['post'] );
					
					}
					
					if( 'page' ==  $location['location_type'] ){
					
						$this->wherevers[$key]['wherever_rules'][$location_key]['page'] = get_post( $location['page'] );
					
					}
					
				}
				
			}
			
			// Setup places
			if( !empty( $wherever['wherever_places'] ) ){

				foreach ( $wherever['wherever_places'] as $place_key => $place ) {

					if ( !array_key_exists( $place['place'], $this->places ) ) {
					
						$this->places[ $place['place'] ] = array();
					
					}
					
					$this->places[ $place['place'] ][] = $this->wherevers[$key];
					
				}
				
			}
			
		}
		
	}
	
	/**
	 * Checks if wherever is in current post
	 *
	 * @since	1.0.0
	 */
	private function in_current_location( $wherever ) {
		global $wp_query, $post;
		
		$is_in = array();
		
		// Check every location on this wherever
		foreach( $wherever['wherever_rules'] as $location ){
			
			$condition = ( '==' == $location['location_condition'] ? true : false );
			
			switch ( $location['location_type'] ) {
				
				case 'all' : // Show everywhere
				
					$is_in[] = true;
					
					break;
				
				case get_post_type( $post ) : // Has a rule about this post_type’s location_type f.e. 'page' == $location['location_type']
					
					// Has a rule on this specific post_type
					if ( $location[ get_post_type( $post ) ] == $post->ID ) {	
						
						$is_in[] = $condition;
						
					}
					
					break;
				
				case 'post_type' : // Has a rule on the post_type location_type
					
					// Has a rule on this specific post_type
					if ( $location['post_type'] == get_post_type( $post ) ) {
						
						$is_in[] = $condition;
						
					}
					
					break;
				
				case 'page_parent' : // Has a rule on the page parent
					
					// Has a rule on this post parent
					if ( $location['page'] == wp_get_post_parent_id( $post->ID ) ) {

						$is_in[] = $condition;
						
					}
					
					break;
				
				case 'page_type' : // Has a rule on the page type
					
					if ( 'archive' == $location['page_type'] && is_archive() ) {
						
						$is_in[] = $condition;
						
					} else if ( 'front_page' == $location['page_type']  && is_front_page() ) {
						
						$is_in[] = $condition;
						
					} else if ( 'blog' == $location['page_type']  && is_home() ) {
						
						$is_in[] = $condition;
						
					}
					
					break;
					
				case 'post_cat' : // Has a rule on a specific post category
					
					$terms = get_the_terms( $post, 'category' );
				
					if ( !empty( $terms ) ) {
						
						foreach( $terms as $term ) {
							
							if ( $term->term_id == $location['post_cat'] ) {
								$is_in[] = $condition;
							}
							
						}
						
					}
					
					break;
			}
			
		}
		
		// Returns only true if no false in array
		return ( !empty($is_in) && !in_array( false, $is_in ) ? true : false );
		
	}
	
	/**
	 * Build an array of Wherever posts grouped by placement
	 * @param  string $place_name [description]
	 * @return array            [description]
	 */
	private function get_wherevers( $place_name ) {
		
		$return_wherevers = array(
			'before' => array(),
			'instead' => array(),
			'after' => array()
		);
		
		if ( !empty( $this->places ) && array_key_exists( $place_name, $this->places ) ) {
			
			// Get wherevers by place from $this->places
			foreach( $this->places[ $place_name ] as $wherever ){
				
				// Add if wherever can be displayed in this location
				if ( $wherever['in_current_location'] ) {
					
					// Add to corresponding placement array in $return_wherevers
					foreach( $wherever['wherever_places'] as $place ) {
						
						if ( empty( $place['placement'] )) {
							// No placement declared (footer, sidebars and custom places)
							$place['placement'] = 'instead';
						
						}
						
						if ( $place['place'] ==  $place_name ) {
							// Make sure we get only the place specified in $place_name as we travel through all allowed places 
							$return_wherevers[ $place['placement'] ][] = array(
								'post' => $wherever['post'],
								'the_content' => $wherever['the_content'],
								'order' => $place['order']
							);
							
						}
					
					}

				}
			}
			
			// Sort before, instead and after 
			foreach( $return_wherevers as &$placement_wherevers ) {
				
				if( !empty( $placement_wherevers ) ){

					usort($placement_wherevers, function($a, $b) {
					    return $b['order'] - $a['order'];
					});

				}
			}
			
		}
		
		return $return_wherevers;
		
	}
	
	// Build html output of wherevers for a place
	private function build_wherevers( $place, $content, $wherevers_by_placement ) {
		
		$wherever_contents = array(
			'before' => array(),
			'instead' => array(),
			'after' => array()
		);
		
		foreach ( $wherevers_by_placement as $placement_key => $placement_wherevers ) {
			
			if ( !empty( $wherevers_by_placement[ $placement_key ] ) ) {
						
				foreach ( $wherevers_by_placement[ $placement_key ] as $wherever ) {
					
					// Default contant wrapper classes
					$wherever_content_wrapper_classes = array( 
						'wherever',
						'wherever-' . $place,
						'wherever-' . $placement_key,
						'wherever-id-' . $wherever['post']->ID
					);
					
					// Filter default classes
					$wherever_content_wrapper_classes = apply_filters( 'wherever_content_wrapper_classes', $wherever_content_wrapper_classes );
					
					// Filter classes dependending on place
					$wherever_content_wrapper_classes = apply_filters( 'wherever_content_wrapper_classes_place_' . $place, $wherever_content_wrapper_classes );
					
					// Filter classes depending on placement_key
					$wherever_content_wrapper_classes = apply_filters( 'wherever_content_wrapper_classes_placement_' . $placement_key, $wherever_content_wrapper_classes );					
					
					// Filter classes depending on post id
					$wherever_content_wrapper_classes = apply_filters( 'wherever_content_wrapper_classes_id_' . $wherever['post']->ID, $wherever_content_wrapper_classes );
					
					$wherever_contents[ $placement_key ][] = '<div class="'. implode( ' ', $wherever_content_wrapper_classes ) . '" data-wherever-id="' . $wherever['post']->ID . '" >' . $wherever['the_content'] . '</div>';
					
				}
	
			} else {
				
				if ( 'instead' == $placement_key && !empty( $content ) ) {
				
					$wherever_contents['instead'][] = $content;
				
				}
				
			}
		}
				
		$content = implode(' ', $wherever_contents['before'] ) . implode(' ', $wherever_contents['instead'] ) . implode(' ', $wherever_contents['after'] );
		
		return $content;
		
	}
	
	// Filter for the_content place
	public function the_content( $content ) {
		global $post;

		if ( 'wherever' != get_post_type($post) ) {		
			
			$wherevers = $this->get_wherevers( 'content' );
			
			if ( !( empty( $wherevers['before'] ) && empty( $wherevers['instead'] ) && empty( $wherevers['after'] ) ) ) {
				
				$content = $this->build_wherevers( 'content', $content, $wherevers );
				
			}
			
		}
		
		return $content;
		
	}
	
	// Action/Filter for get_sidebar place
	public function get_sidebar( $place ) {

		$wherevers = $this->get_wherevers( 'sidebar' );
		
		if ( empty( $wherevers['before'] ) && empty( $wherevers['instead'] ) && empty( $wherevers['after'] ) )
			return;
		
		$wherevers_content = $this->build_wherevers( 'sidebar', '', $wherevers );
		
		echo $wherevers_content;
		
	}
	
	// Action/Filter for get_footer place
	public function get_footer( $place ) {

		$wherevers = $this->get_wherevers( 'footer' );

		if ( empty( $wherevers['before'] ) && empty( $wherevers['instead'] ) && empty( $wherevers['after'] ) )
			return;
		
		$wherevers_content = $this->build_wherevers( 'footer', '', $wherevers );
				
		echo $wherevers_content;
		
	}
	
	/**
	 * Output wherevers into custom places with do_action('wherever_place', '{place-slug}');
	 * @param  string $place  place slug declared in do_action(); theme function
	 * @return html           
	 */
	public function api_get_wherever_place( $place ) {
		
		$wherevers = $this->get_wherevers( $place );
		
		if( empty( $wherevers['before'] ) && empty( $wherevers['instead'] ) && empty( $wherevers['after'] ) )
			return;
			
		$wherevers_content = $this->build_wherevers( $place, '', $wherevers );
		
		echo $wherevers_content;
		
	}
	
}
