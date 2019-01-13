<?php

class Wherever_Admin_Display 
{
	
	function __construct() {
		
	}
	
	public function settings() {
		?>
		<div class="wrap">
			<form action="options.php" method="post">
				<div class="wherever-settings">
				<?php
				do_settings_sections('wherever_content');
				settings_fields('wherever_content');
				submit_button();
				?>
				</div>
			</form>
		</div>
		<?php
	}
	
	public function setting_editing_section( $arg ) {
		?>
		<!-- <h3><?php _e( 'Editing', 'wherever' ); ?></h3> -->
		<?php
	}
		
	public function setting_checkbox( $arg ) {
		$settings = get_option( 'wherever_settings' );
		$checked = ( !empty( $settings[ $arg['option_key'] ] ) ? 'checked="checked"' : '' );
		$label = ( !empty( $arg['label'] ) ? $arg['label'] : __( 'Enabled', 'wherever' ) );
		$description = ( !empty( $arg['description'] ) ? $arg['description'] : '' );
		?>
		<label class="widefat">
			<input type='checkbox' name='wherever_settings[<?php echo $arg['option_key']; ?>]' id="<?php echo $arg['option_key']; ?>" value='1' <?php echo $checked; ?>> <?php echo $label; ?>
		</label>
		<?php
		if ( !empty( $description ) ) {
			echo '<small class="description">' . $description . '</small>';
		}
	}

	
	/**
	 * Display admin notice if Carbon Field Plugin not installed/activated
	 *
	 * @since    1.0.2
	 */
	public function notice_framework() {
		?>
		<div class="notice notice-error is-dismissible">
			<p><?php _e( 'The <strong>Wherever Content</strong> plugin is not ready yet to work. Please deactivate the Carbon fields plugin or update it to a version higher than 2.0. If you need to work with Carbon fields version lower than 2.0, please install a 1.x version of Wherever Content.', 'wherever' ); ?></p>
		</div>
		<?php
	}
}
