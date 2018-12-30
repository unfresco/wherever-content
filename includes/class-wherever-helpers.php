<?php

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

class Wherever_Helpers {
	
	
	public static function is_rest_request() {
		$bIsRest = false;
		
		if ( function_exists( 'rest_url' ) && !empty( $_SERVER[ 'REQUEST_URI' ] ) ) {
			
			$sRestUrlBase = get_rest_url( get_current_blog_id(), '/' );
			$sRestPath = trim( parse_url( $sRestUrlBase, PHP_URL_PATH ), '/' );
			$sRequestPath = trim( $_SERVER[ 'REQUEST_URI' ], '/' );
			$bIsRest = ( strpos( $sRequestPath, $sRestPath ) === 0 );
		}
		
		return $bIsRest;
	
	}
	
	
}
