<?php

use Carbon_Fields\Container;
use Carbon_Fields\Field;


class Wherever_Admin_Postmeta_Fields_Rules {
	
	private $helpers;
	
	function __construct( $helpers ) {
		$this->helpers = $helpers;
	}
	
	
	public function location_type( $fields ) {

		$fields[] = Field::make('select', 'location_type', __( 'Location', 'wherever' ) )
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
			->set_default_value('all');
		
		return $fields;
		
	}
	
	public function location_condition( $fields ) {
		
		$fields[] = Field::make('select', 'location_condition', __( 'Condition', 'wherever' ) )
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
	
	public function archive_post_type( $fields ) {
		
		$fields[] = Field::make('select', 'archive_post_type', __( 'Archive Post Type', 'wherever' ) )
			->add_options( $this->helpers->get_post_types_for_select() )
			->set_conditional_logic(array(
				array(
				'field' => 'page_type',
				'value' => 'archive',
				'compare' => '='
				)
			));
		
		return $fields;
	}
	
	public function rule_info( $fields ) {
		
		$fields[] = Field::make( 'html', 'rule_content_info' )
			->set_html( '<p><span class="dashicons dashicons-move"></p>' )
			->set_classes('rule-content-info');
		
		return $fields;
	}
	
}
