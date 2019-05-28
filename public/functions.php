<?php 

if ( ! function_exists('register_wherever_places') ) {
	
	function register_wherever_places( $places = array() ){
		
		do_action( 'wherever_settings/register_places', $places );
		
	}
	
}
