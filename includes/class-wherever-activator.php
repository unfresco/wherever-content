<?php

/**
 * Fired during plugin activation
 *
 * @link       http://grell.es
 * @since      1.0.0
 *
 * @package    Wherever
 * @subpackage Wherever/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Wherever
 * @subpackage Wherever/includes
 * @author     Adrián Ortiz Arandes <adrian@grell.es>
 */
class Wherever_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		self::setup_wherever_options();
		self::setup_version();
	}
	
	private function setup_wherever_options(){
		
		$options = get_option( 'wherever' );
				
		if ( !$options ) {
			$options = array(
				'plugin_version' => '',
				'default_places' => array(),
				'registered_places' => array()
			);
			
			add_option('wherever', $options );
			
		}
		
	}
	
	private function setup_version(){
		
		$plugin_data = get_plugin_data( plugin_dir_path( dirname( __FILE__ ) ) . 'wherever.php' );
		$current_plugin_version = $plugin_data['Version'];
		
		$options = get_option( 'wherever' );
		$options['plugin_version'] = $current_plugin_version;

		// Roadmap: setup routine for specific versions	
		/*		
		if ( !empty( $options['plugin_version'] ) ) {
			if ( version_compare( $current_plugin_version, '1.0.1'  ) >= 0 ) {
				// This version is >= 1.0.1
			} else {
				// This version is < 1.0.1
			}
		} else {
			
		}
		*/
		
		update_option( 'wherever', $options );
	}

}
