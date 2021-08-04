<?php

namespace Wherever_Content\Wherever_Public;


/**
 * 
 */
class Rules {
	
	function __construct() {
		
	}
	
	private function get_location_condition( $rules ) {
		
		return $rules['location_condition'];
		
	}
	
	public function cleanup( $rules ) {
		
		unset( $rules['_type'], $rules['rule_info'] );
		
		return $rules;

	}
	
	public function all( $is_in, $rules ) {

		$is_in[] = '==';
		
		return $is_in;
		
	}
	
	/**
	 * Applies when location_type == post
	 * @param  array  $is_in [description]
	 * @param  array  $rules [description]
	 * @return array         [description]
	 */
	public function post( $is_in, $rules ) {
		global $post;
		
		if ( $rules['post'] === $post->ID ) {
			$is_in[] = $this->get_location_condition( $rules );
		}
		
		return $is_in;
		
	}
	
	/**
	 * Applies when location_type == post_cat
	 * @param  array  $is_in [description]
	 * @param  array  $rules [description]
	 * @return array         [description]
	 */
	public function post_cat( $is_in, $rules ) {
		global $post;
		
		$terms = get_the_terms( $post, 'category' );

		if ( !empty( $terms ) ) {
			
			foreach( $terms as $term ) {
				
				if ( $term->term_id == $location['post_cat'] ) {
					$is_in[] = $this->get_location_condition( $rules );
				}
				
			}
			
		}
		
		return $is_in;
		
	}
	
	/**
	 * Applies when location_type == page
	 * @param  array  $is_in [description]
	 * @param  array  $rules [description]
	 * @return array         [description]
	 */
	public function page( $is_in, $rules ) {
		global $post;

		if ( $rules['page'] === $post->ID ) {
			$is_in[] = $this->get_location_condition( $rules );
		}
		
		return $is_in;
		
	}
	
	/**
	 * Applies when location_type == post_type
	 * @param  array  $is_in [description]
	 * @param  array  $rules [description]
	 * @return array         [description]
	 */
	public function post_type( $is_in, $rules ) {
		global $post;
		
		if ( $rules['post_type'] == get_post_type( $post ) ) {
			
			$is_in[] = $this->get_location_condition( $rules );
			
		}
		
		return $is_in;
		
	}

	/**
	 * Applies when location_type == post_parent
	 * @param  array  $is_in [description]
	 * @param  array  $rules [description]
	 * @return array         [description]
	 */
	public function page_parent( $is_in, $rules ) {
		global $post;
		
		if ( $rules['page'] == wp_get_post_parent_id( $post->ID ) ) {
			
			$is_in[] = $this->get_location_condition( $rules );
			
		}
		
		return $is_in;
		
	}
	
	/**
	 * Applies when location_type == template_type
	 * @param  array  $is_in [description]
	 * @param  array  $rules [description]
	 * @return array         [description]
	 */
	public function template_type( $is_in, $rules ) {
		global $post;
		
		// Backward compatible to page_type option
		if ( 'page_type' === $rules['location_type'] ) {
			$type = $rules['page_type'];
		} else if ( 'template_type' === $rules['location_type'] ) {
			$type = $rules['template_type'];
		}
		
		if ( 'archive' == $type && is_archive() ) {
			
			$is_in[] = $this->get_location_condition( $rules );
			
		} else if ( 'front_page' == $type && is_front_page() ) {
			
			$is_in[] = $this->get_location_condition( $rules );
			
		} else if ( 'blog' == $type && is_home() ) {
			
			$is_in[] = $this->get_location_condition( $rules );
			
		} else if ( '404' == $type && is_404() ) {
			
			$is_in[] = $this->get_location_condition( $rules );
			
		} else if ( 'author' == $type && is_author() ) {
			
			$is_in[] = $this->get_location_condition( $rules );
			
		} else if ( 'search' == $type && is_search() ) {
			
			$is_in[] = $this->get_location_condition( $rules );
			
		}
		
		return $is_in;
		
	}
	
	/**
	 * Applies when location_type == user_state
	 * @param  array  $is_in [description]
	 * @param  array  $rules [description]
	 * @return array         [description]
	 */
	public function user_state( $is_in, $rules ) {
		global $post;

		return $is_in;
		
	}	

}
