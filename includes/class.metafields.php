<?php

namespace Wherever_Content;

class Metafields {
	
	private $loaded;
	
	public function __construct() {
		
		$this->loaded = false;

	}
	
	private function use_available_framework() {
		if ( ! function_exists( 'get_plugins' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}
		
		$carbon_fields_plugin_file = 'carbon-fields/carbon-fields-plugin.php';
		$all_plugins = get_plugins();
		$framework_version = ( defined( '\Carbon_Fields\VERSION' ) ? \Carbon_Fields\VERSION : '3' );

		// Carbon fields exist as plugin
		if ( array_key_exists( $carbon_fields_plugin_file, $all_plugins ) ) {
			
			$current_active_plugins = get_option('active_plugins');

			// Check if is active as plugin on same site. Does not catch network activation but carbon-fields won’t load multiple times 
			if ( in_array( $carbon_fields_plugin_file, $current_active_plugins ) ) {
				// Is active as plugin
				$framework_version = $all_plugins[$carbon_fields_plugin_file]['Version'];
			}

		}

		if ( version_compare( $framework_version, '3'  ) >= 0 ) {
			// Included Carbon field plugin is higher than 3
			// Use available framework
			return true;
		} else {
			// Carbon_Fields version is lower than 3
			// Use included framework
			return false;
		} 

	}

	private function use_included_framework() {
		if ( class_exists( 'Carbon_Fields\\Container' ) || function_exists('carbon_fields_boot_plugin') ) {
			return false;
		} else {
			return true;
		}
	}
	
	public function load_framework() {
		
		if ( $this->loaded ) {
			return;
		}

		if ( $this->use_included_framework() ) {
			
			require_once( plugin_dir_path( dirname( __FILE__ ) ) . 'vendor/autoload.php' );
			
			if ( class_exists( 'Carbon_Fields\\Container' ) ) {
				$this->boot();
			} else {
				add_action( 'admin_notices', $this->plugin_admin_display, 'notice_framework_missing' );
			}
			
			$this->loaded = true;

		} else if ( $this->use_available_framework() ) {
			$this->loaded = true;
		} else {
			add_action( 'admin_notices', $this->plugin_admin_display, 'notice_framework' );
		}
		
	}

	public function is_loaded() {
		
		return $this->loaded;
	
	}

	private function boot() {

		\Carbon_Fields\Carbon_Fields::boot();

	}

}
