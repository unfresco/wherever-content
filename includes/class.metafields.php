<?php

namespace Wherever_Content;

class Metafields extends Wherever {
	
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
		
		$carbon_fields_plugin_file = 'carbon-fields/carbon-fields-plugin.php';
		$all_plugins = get_plugins();

		if ( class_exists( 'Carbon_Fields\\Container' ) || function_exists('carbon_fields_boot_plugin') ) {
			
			// Carbon fields exist as plugin
			if ( array_key_exists( $carbon_fields_plugin_file, $all_plugins ) ) {
				
				$current_active_plugins = get_option('active_plugins');

				// Check if is active as plugin or as framework
				if ( in_array( $carbon_fields_plugin_file, $current_active_plugins ) ) {
					// Is active as plugin
					$framework_version = $all_plugins[$carbon_fields_plugin_file]['Version'];
				} else {
					// Is active as framework
					$framework_version = \Carbon_Fields\VERSION ;
				}
				
			} else {
				// Carbon fields already exist as framework. Asume version is >= 2
				$framework_version = \Carbon_Fields\VERSION ;
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

		if ( ! function_exists('carbon_fields_boot_plugin') ) {
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'lib/carbon-fields/carbon-fields-plugin.php';	
		}
		
		$this->loaded = true;
		
	}
	
	public function is_loaded() {
		return $this->loaded;
	}

}
