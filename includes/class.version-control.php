<?php

namespace Wherever_Content;

/**
 * 
 */
class Version_Control extends Wherever {
	
	protected $version;
	
	function __construct() {
		
	}
	
	/**
	 * Retrieve the version number of any active plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_plugin_version( $plugin_file_path = 'wherever-content/wherever.php' ) {
		
		if ( ! function_exists( 'get_plugins' ) ) {
 			require_once ABSPATH . 'wp-admin/includes/plugin.php';
 		}
		
		$all_plugins = get_plugins();
		
		if ( array_key_exists( $plugin_file_path, $all_plugins ) ) {
			$version = $all_plugins[$plugin_file_path]['Version'];
		}
		
		return $version;
		
	}
	
	/**
	 * Retrieve the version number of this plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		
		if ( empty( $this->version ) ) {
			$this->version = $this->get_plugin_version();
		}
		
		return $this->version;
		
	}
	
	/**
	 * Setup version check/hooks
	 * @return void 
	 */
	public function check_version() {
		$options = get_option( 'wherever_status' );

		$arg = array(
			'old_version' => $options['plugin_version'],
			'new_version' => $this->get_version()
		);
		
		
		if ( version_compare( $this->get_version(), $options['plugin_version']  ) != 0 ) {

			do_action( 'wherever_settings/update_status_version', $arg );

		}
		
		if ( version_compare( $this->get_version(), $options['plugin_version']  ) < 0 ) {
			
			do_action( 'wherever_settings/downgrade_status_version', $arg );

		}
		
	}
	 
}
