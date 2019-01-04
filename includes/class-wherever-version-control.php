<?php

/**
 * 
 */
class WhereverVersionControl extends Wherever {
	
	function __construct()
	{
		$this->setup();
	}
	
	public function setup() {
		
		
	}
	
	public function getPluginVersion( $plugin_file_path = 'wherever-content/wherever.php' ) {
		
		if ( ! function_exists( 'get_plugins' ) ) {
 			require_once ABSPATH . 'wp-admin/includes/plugin.php';
 		}
		
		$all_plugins = get_plugins();
		
		if ( array_key_exists( $plugin_file_path, $all_plugins ) ) {
			$version = $all_plugins[$plugin_file_path]['Version'];
		}
		
		return $version;
		
	}
	
	// TODO: get version DB
	// 
}
