<?php

namespace Wherever_Content;

class Metafields {
	
	private $loaded;
	private $min_metafields_version;
	
	public function __construct() {
		
		$this->loaded = false;
		$this->min_metafields_version = '3';

	}
	
	public function load_framework() {
		
		if ( $this->loaded ) {
			return;
		}

		if ( $this->use_available_framework() || $this->use_included_framework() ) {
			$this->loaded = true;
		}
		
	}
	
	/**
	 * Checks if the there is a carbon-fields version plugin and if is activated
	 */
	private function use_available_framework() {

		if ( ! function_exists( 'get_plugins' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}
		
		$carbon_fields_plugin_file = 'carbon-fields/carbon-fields-plugin.php';
		$all_plugins = get_plugins();
		$framework_version = ( defined( '\Carbon_Fields\VERSION' ) ? \Carbon_Fields\VERSION : $this->min_metafields_version );

		// Carbon fields exist as plugin
		if ( array_key_exists( $carbon_fields_plugin_file, $all_plugins ) ) {
			
			$current_active_plugins = get_option('active_plugins');

			// Check if is active as plugin on same site. Does not catch network activation but carbon-fields won’t load multiple times 
			if ( in_array( $carbon_fields_plugin_file, $current_active_plugins ) ) {
				// Is active as plugin
				$framework_version = $all_plugins[$carbon_fields_plugin_file]['Version'];
			}

		}

		if ( version_compare( $framework_version, $this->min_metafields_version  ) >= 0 ) {
			// Included Carbon field plugin is higher than 3
			// Can use available framework but if not available (activated), boot now
			if ( ! class_exists( 'Carbon_Fields\\Container' ) ) {
				$this->boot();
			}

			return true;
		} else {
			// Carbon_Fields version is lower than 3
			// Use included framework
			return false;
		} 

	}

	/**
	 * Checks if carbon-fields is available, if not load the included version
	 */
	private function use_included_framework() {

		if ( class_exists( 'Carbon_Fields\\Container' ) || function_exists('carbon_fields_boot_plugin') ) {
			return false;
		} else {
			$this->boot();
			return true;
		}
	}
	
	private function boot() {
		require_once( plugin_dir_path( dirname( __FILE__ ) ) . 'vendor/autoload.php' );
		\Carbon_Fields\Carbon_Fields::boot();
	}


}
