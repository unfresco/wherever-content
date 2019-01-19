<?php

use Carbon_Fields\Container;
use Carbon_Fields\Field;


class Wherever_Admin_Postmeta_Fields_Places {
	
	private $helpers;
	
	function __construct( $helpers ) {
		$this->helpers = $helpers;
	}
	
	public function place( $fields ) {
		
		$fields[] = Field::make('select', 'place', __( 'Place', 'wherever' ) )
			->add_options( function() { return $this->helpers->get_places_for_options(); } )
			->set_default_value('content');
		
		return $fields;
	
	}
	
	public function placement( $fields ) {
		
		$fields[] = Field::make('radio', 'placement', __( 'Placement', 'wherever' ) )
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
			));
		
		return $fields;
	}

	public function order( $fields ) {
		
		$fields[] = Field::make('text', 'order', __( 'Order', 'wherever' ) )
			->set_default_value(5)
			->set_attribute('type', 'number')
			->set_classes('number');
		
		return $fields;
	}
	
	public function place_info( $fields ) {
		
		$fields[] = Field::make( 'html', 'place_info' )
			->set_html( '<p><span class="dashicons dashicons-location"></p>' )
			->set_classes('place-content-info');
		
		return $fields;
	}
	
}
