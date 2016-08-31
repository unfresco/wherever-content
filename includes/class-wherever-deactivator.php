<?php

/**
 * Fired during plugin deactivation
 *
 * @link       http://grell.es
 * @since      1.0.0
 *
 * @package    Wherever
 * @subpackage Wherever/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Wherever
 * @subpackage Wherever/includes
 * @author     Adrián Ortiz Arandes <adrian@grell.es>
 */
class Wherever_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
		$options = get_option( 'wherever' );
		$options['default_places'] = array();
		$options['registered_places'] = array();
		
		update_option( 'wherever', $options );
	}

}
