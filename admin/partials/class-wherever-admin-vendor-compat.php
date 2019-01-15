<?php 

/**
 * All functions relating other plugins come here
 */
class Wherever_Admin_Vendor_Compat
{
	
	function __construct() {
		
		$this->setup_actions();
	
	}
	
	private function setup_actions() {
		
		add_action( 'wherever_admin_vendor/after_update_settings', function( $arg ) { $this->siteorigin_optimize( $arg ); } );

	}
	
	public function polylang_compat( $post_types, $is_settings ) {
		error_log( "polylang_compat" );
		$polylang_options = get_option( 'polylang' );

		if ( ! in_array( 'wherever', $polylang_options['post_types'] ) ) {
			// Auto-include wherever post_type into Polylang options
			$polylang_options['post_types'][] = 'wherever';
			update_option( 'polylang', $polylang_options );
		} 
		
		if ( $is_settings ) {
			// hides 'wherever' from the list of custom post types in Polylang settings
			unset( $post_types['wherever'] );
		
		} else {
			// enables language and translation management for 'wherever'
			$post_types['wherever'] = 'wherever';
		
		}
		
		return $post_types;
	
	}
	
	public function siteorigin_optimize( $wherever_settings ) {

		$settings = get_option( 'siteorigin_panels_settings' );
		
		if ( empty( $settings ) ) {
			return;
		}
		
		if ( !empty( $wherever_settings['optimize_siteorigin'] ) ) {
			if ( array_key_exists('post-types', $settings ) && !in_array( 'wherever', $settings ) ) {
				// add to post-types
				$settings['post-types'][] = 'wherever';
			}
			update_option( 'siteorigin_panels_settings', $settings );
			
			// disable Gutenberg
			$wherever_settings['show_in_rest'] = 0;
			update_option('wherever_settings', $wherever_settings );
		
		}
		
	}
	

}
