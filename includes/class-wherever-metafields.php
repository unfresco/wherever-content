<?php

class Wherever_Metafields extends Wherever {
	
	private $loaded;
	
	public function __construct() {
		$this->loaded = false;
		
		if ( $this->check_framework() ) {
			$this->load_framework();
		}

	}
	
	private function check_framework() {
		if ( ! function_exists( 'get_plugins' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}
		
		$all_plugins = get_plugins();

		if ( class_exists( 'Carbon_Fields\\Container' ) ) {
			
			if ( array_key_exists( 'carbon-fields/carbon-fields-plugin.php', $all_plugins ) ) {
				// Carbon fields already active as plugin. Check version >= 2
				$framework_version = $all_plugins['carbon-fields/carbon-fields-plugin.php']['Version'];
			} else {
				// Carbon fields already exist as framework. Asume version is >= 2
				$framework_version = '2';
			}
			
			
			if ( version_compare( $framework_version, '2'  ) >= 0 ) {
				// Included Carbon field plugin is higher than 2
				return true;
			} else {
				// Carbon_Fields version is lower than 2
				return false;
			} 
			
		} else {
			// Use included framework
			return true;
		}
	}
	
	private function load_framework() {

		$dir_name = dirname( __FILE__ ) . '/';
		$autoload_path = dirname( $dir_name, 1 )  . '/vendor/autoload.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'vendor/autoload.php';
		$this->loaded = true;
		
	}
	
	public function is_loaded() {
		return $this->loaded;
	}
	
	public function boot() {
		if ( $this->loaded ) {
			\Carbon_Fields\Carbon_Fields::boot();
		}
	}
}
