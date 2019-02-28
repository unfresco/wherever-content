<?php

namespace Wherever_Content\Admin;

use Carbon_Fields\Container;
use Carbon_Fields\Field;


class Settings {
	
	function __construct() {
		
		$this->setup_actions();
		
	}
	
	public function setup_actions() {
		
		add_action( 'wherever_settings/update_status_version', array( $this, 'update_status_version' ), 10, 1 );
		add_action( 'wherever_settings/downgrade_status_version', function( $arg ) {
			$this->update_status_version( $arg );
			$this->downgrade_status_version( $arg );
		}, 10, 1 );
	
	}
	
	/**
	 * Executes on version update
	 * @param  array $arg  array( 'old_version' => {version}, 'new_version' => {version} )
	 * @return void
	 */
	public function update_status_version( $arg ) {
		
		$options = get_option( 'wherever_status' );
		$options['plugin_version'] = $arg['new_version'];
		
		update_option('wherever_status', $options );
	
	}
	
	/**
	 * Executes o version downgrade
	 * @param  array $arg  array( 'old_version' => {version}, 'new_version' => {version} )
	 * @return void
	 */
	public function downgrade_status_version( $arg ) {
		// TODO Do stuff if downgrading
		
	}
	
	/**
	 * Setup wherever_status options
	 * @return void 
	 */
	public function options_status_init() {
		
		$options_old = get_option( 'wherever' ); // < v2.0		
		$options = get_option( 'wherever_status' );
		
		if ( empty( $options ) ) {
			// First time setup
			update_option('wherever_status', array() ); // Filtered by filter_get_options_status
			$options = get_option( 'wherever_status' );
		}
		
		if ( !empty( $options_old ) ) {
			$options = array_merge( $options, $options_old );
			delete_option('wherever');
			update_option('wherever_status', $options );
		}
		
	}
	
	/**
	 * Setup wherever_settings options
	 * @return void 
	 */
	public function options_settings_init() {
		
		$options_settings = get_option( 'wherever_settings' );
		
		if ( empty( $options_settings ) ) {
			// First time setup
			update_option('wherever_settings', array() );
			$options_settings = get_option( 'wherever_settings' );
		}
		
	}

	/**
	 * Get default value for wherever_status option
	 * @return array default value
	 */
	public function get_options_status_defaults() {
		
		$defaults = array(
			'plugin_version' => '0.0.0',
			'default_places' => array(),
			'registered_places' => array()
		);
		
		return $defaults;
	}
	
	/**
	 * get default value for wherever_settings option
	 * @return array default value
	 */
	public function get_options_settings_defaults() {
		
		$defaults = array(
			'show_in_rest' => 1,
			'optimize_siteorigin' => 0
		);
		
		return $defaults;
	}
	
	/**
	 * Register settings page
	 * @return void
	 */
	public function settings_page() {
		
		add_options_page(
			__('Wherever Settings', 'wherever' ),
			__('Wherever', 'wherever' ),
			'manage_options',
			'wherever_content',
			function() { do_action('wherever_display/settings'); }
		);
		
	}
	
	/**
	 * Register settings page content
	 * @return void
	 */
	public function settings() {

		register_setting( 'wherever_content', 'wherever_settings'  ) ;
		
		add_settings_section(
			'wherever_editing_section',
			__( 'Backend editing', 'wherever' ),
			function() {	do_action('wherever_display/setting_editing_section'); },
			'wherever_content'
		);
		
		add_settings_field(
			'show_in_rest',
			__( 'Block editor (Gutenberg)', 'wherever' ),
			function() { 
				$arg = array(
					'class' => 'checkbox-field',
					'label_for' => 'show_in_rest',
					'option_key' => 'show_in_rest'
				);
				
				do_action('wherever_display/setting_checkbox', $arg ); 
			},
			'wherever_content',
			'wherever_editing_section'
		);
		
		add_settings_field(
			'optimize_siteorigin',
			__( 'SiteOrigin optimisation', 'wherever' ),
			function() { 
				$arg = array(
					'class' => 'checkbox-field',
					'label_for' => 'optimize_siteorigin',
					'option_key' => 'optimize_siteorigin',
					'description' => __( 'Enabeling this feature will disable the block editor (Gutenberg) for Wherever Contents and include Wherever Content to the post types option in SiteOrigin Page Builder options.', 'wherever')
				);
				
				do_action('wherever_display/setting_checkbox', $arg ); 
			},
			'wherever_content',
			'wherever_editing_section'
		);
		
		
	}
	
	/**
	 * Executes after update_option('wherever_settings')
	 * @param  array $old_settings previous DB value
	 * @param  array $new_settings current DB value
	 * @return void
	 */
	public function after_update_settings( $old_settings, $new_settings ) {
		
		do_action('wherever_admin_vendor/after_update_settings', $new_settings );
		
	}
	
	/**
	 * Filter get_option('wherever_status')
	 * @param  array $options value from the DB
	 * @return array          wherever_setaus value merge with default values
	 */
	public function filter_get_options_status( $options ) {
		$options = ( empty( $options ) ? array() : $options );
		$options = array_merge( $this->get_options_status_defaults(), $options );
		return $options;
	}
	
	/**
	 * Filter get_option('wherever_settings')
	 * @param  array $options value from the DB
	 * @return array          wherever_setting value merge with default values
	 */
	public function filter_get_options_settings( $options ) {
		$options = ( empty( $options ) ? array() : $options );
		$options = array_merge( $this->get_options_settings_defaults(), $options );
		return $options;
	}
	
	/**
	 * Applied before wherever_setting option saved to options table 
	 * @param  mixed $new_options current options to be saved
	 * @param  mixed $old_options previous options in wherever_setting
	 * @return array              options to be saved to options table
	 */
	public function filter_update_options_settings( $new_options, $old_options ) {
		
		if ( null == $new_options && null ==  $old_options ) {
			// first time setup
			$new_options = $this->get_options_settings_defaults();
		}

		foreach( $this->get_options_settings_defaults() as $key => $value ) {
			if ( empty( $new_options ) ) {
				// Prepare for array_key_exists if $new_options == null
				$new_options = array();
			}
			
			$options[$key] = ( array_key_exists( $key, $new_options ) ? $new_options[$key] : 0 );
			
		}	
		
		return $options;
	}
	

}
