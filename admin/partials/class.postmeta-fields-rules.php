<?php

namespace Wherever_Content\Admin;

use Carbon_Fields\Container;
use Carbon_Fields\Field;


class Postmeta_Fields_Rules {
	
	private $helpers;
	
	function __construct( $helpers ) {
		$this->helpers = $helpers;
	}
	
	
	public function location_type( $fields ) {

		$fields[] = Field::make('select', 'location_type', __( 'Location', 'wherever' ) )
			->add_options( apply_filters( 'wherever_admin/rules/location_type', array() ) )
			->set_default_value('all');
		
		return $fields;
		
	}
	
	public function location_type_options( $options ) {
		
		$options = array(
			'all' => __( 'Everywhere', 'wherever' ),
			'post' => __( 'Post', 'wherever' ),
			'post_type' => __( 'Post Type', 'wherever' ),
			'post_cat' => __( 'Post Category', 'wherever' ),
			'page' => __( 'Page', 'wherever' ),
			'page_parent' => __( 'Page Parent', 'wherever' ),
			'template_type' => __( 'Template Type', 'wherever'),
			#'user_state' => __( 'In user state', 'wherever'),
		);
		
		return $options;
		
	}
	
	public function location_condition( $fields ) {

		$fields[] = Field::make('select', 'location_condition', __( 'Condition', 'wherever' ) )
			->add_options(array(
				'=' => __( 'is', 'wherever' ),
				'!=' => __( 'is not', 'wherever' )
			))
			->set_conditional_logic(array(
				array(
				'field' => 'location_type',
				'value' => array('all'),
				'compare' => 'NOT IN'
				)
			));
		
		return $fields;
		
	}

	public function post( $fields ) {
		
		$fields[] = Field::make('select', 'post', __( 'Post', 'wherever' ) )
			->add_options( $this->helpers->get_posts_for_select('post') )
			->set_conditional_logic(array(
				array(
				'field' => 'location_type',
				'value' => 'post',
				'compare' => '='
				)
			));
		
		return $fields;
	}
	
	public function post_type( $fields ) {

		$fields[] = Field::make('select', 'post_type', __( 'Post Type', 'wherever' ) )
			->add_options( $this->helpers->get_post_types_for_select() )
			->set_conditional_logic(array(
				array(
				'field' => 'location_type',
				'value' => 'post_type',
				'compare' => '='
				)
			));
		
		return $fields;
	}
	
	public function post_cat( $fields ) {

		$fields[] = Field::make('select', 'post_cat', __( 'Post Category', 'wherever' ) )
			->add_options( $this->helpers->get_post_categories_for_select( 'category' ) )
			->set_conditional_logic(array(
				array(
				'field' => 'location_type',
				'value' => 'post_cat',
				'compare' => '='
				)
			));
		
		return $fields;
	}
	
	public function page( $fields ) {

		$fields[] = Field::make('select', 'page', __( 'Page', 'wherever' ) )
			->add_options( $this->helpers->get_posts_for_select('page') )
			->set_conditional_logic(array(
				array(
				'field' => 'location_type',
				'value' => array('page', 'page_parent'),
				'compare' => 'IN'
				)
			));
		
		return $fields;
	}
	
	public function page_type( $fields ) {
		
		$fields[] = Field::make('select', 'page_type', __( 'Page Type', 'wherever' ) )
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
			));
		
		return $fields;
	}
	
	public function template_type( $fields ) {
		
		$fields[] = Field::make('select', 'template_type', __( 'Template Type', 'wherever' ) )
			->add_options(array(
				'front_page' => __( 'Front Page (blog or static page)', 'wherever' ),
				'blog' => __( 'Blog', 'wherever' ),
				'archive' => __( 'Archive', 'wherever' ),
				'author' => __( 'Authors', 'wherever' ),
				'search' => __( 'Search', 'wherever' ),
				'404' => __( '404', 'wherever' ),
			))
			->set_conditional_logic(array(
				'relation' => 'OR',
				array(
					'field' => 'location_type',
					'value' => 'template_type',
					'compare' => '='
				),
				array(
					'field' => 'location_type',
					'value' => 'page_type',
					'compare' => '='
				)
			));
		
		return $fields;
	}
	
	public function archive_post_type( $fields ) {

		$fields[] = Field::make('select', 'archive_post_type', __( 'Archive Post Types', 'wherever' ) )
			->add_options( $this->helpers->get_post_types_for_select() )
			->set_conditional_logic(array(
				'relation' => 'OR',
				array(
					'field' => 'page_type',
					'value' => 'archive',
					'compare' => '='
				),
				array(
					'field' => 'template_type',
					'value' => 'archive',
					'compare' => '='
				)
			));
		
		return $fields;
	}
	
	public function user_state( $fields ) {
		
		$fields[] = Field::make('select', 'user_state', __( 'State', 'wherever' ) )
			->add_options(array(
					'logged-in' => __( 'Logged in', 'wherever' ),
					'current_user_can' => __( 'Current user can', 'wherever' ),
					'current_user_is' => __( 'Current user is', 'wherever' )
			))
			->set_conditional_logic(array(
				array(
				'field' => 'location_type',
				'value' => 'user_state',
				'compare' => '='
				)
			));
		
		return $fields;
	}
	
	public function rule_info( $fields ) {
		
		foreach( $this->wherever_rule_infos() as $key => $info ) {
			$fields[] = Field::make( 'html', 'rule_info_' .  $key  )
			->set_html( '<p><span class="dashicons dashicons-move"></span> <span class="description">' . $info['description'] . '</span></p>' )
			->set_classes('rule-content-info')
			->set_conditional_logic(array(
				array(
				'field' => 'location_type',
				'value' => $info['location_type'],
				'compare' => "="
				),
				array(
				'field' => 'location_condition',
				'value' => $info['condition'],
				'compare' => "="
				),

			));
		}
		
		return $fields;
	}

	/**
	 * Adds wherever_place terms to the admin_js localisation variable
	 * @return array     with wherever_place terms
	 */
	private function wherever_rule_infos() {
		
		$all = __( 'Show on all posts, pages and custom post types. Good for general purpose like site navigation and footers.', 'wherever' );
		
		$wherever_rule_infos = array(
			array(
				'location_type' => 'all',
				'condition' => '=',
				'description' => $all,
			),
			array(
				'location_type' => 'all',
				'condition' => '!=',
				'description' => $all,
			),
			array(
				'location_type' => 'post',
				'condition' => '=',
				'description' => __( 'Show on the selected post.', 'wherever' ),
			),
			array(
				'location_type' => 'post',
				'condition' => '!=',
				'description' => __( 'Don’t show on the selected post.', 'wherever' ),
			),
			array(
				'location_type' => 'post_type',
				'condition' => '=',
				'description' => __( 'Show on the selected post type.', 'wherever' ),
			),
			array(
				'location_type' => 'post_type',
				'condition' => '!=',
				'description' => __( 'Don’t show on the selected post type.', 'wherever' ),
			),
			array(
				'location_type' => 'post_cat',
				'condition' => '=',
				'description' => __( 'Show on the selected post category.', 'wherever' ),
			),
			array(
				'location_type' => 'post_cat',
				'condition' => '!=',
				'description' => __( 'Don’t show on the selected post category.', 'wherever' ),
			),
			array(
				'location_type' => 'page',
				'condition' => '=',
				'description' => __( 'Show on the selected page.', 'wherever' ),
			),
			array(
				'location_type' => 'page',
				'condition' => '!=',
				'description' => __( 'Don’t show on the selected page.', 'wherever' ),
			),
			array(
				'location_type' => 'template_type',
				'condition' => '=',
				'description' => __( 'Show on the selected template type.', 'wherever' ),
			),
			array(
				'location_type' => 'template_type',
				'condition' => '!=',
				'description' => __( 'Don’t on the selected template type.', 'wherever' ),
			),
			array(
				'location_type' => 'page_parent',
				'condition' => '=',
				'description' => __( 'Show on children of the selected page.', 'wherever' ),
			),
			array(
				'location_type' => 'page_parent',
				'condition' => '!=',
				'description' => __( 'Don’t show on children of the selected page.', 'wherever' ),
			),
		);
		
		return $wherever_rule_infos;
		
	}
	
}
