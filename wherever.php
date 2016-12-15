<?php

/**
 *
 * @link              http://grell.es
 * @since             1.0.0
 * @package           Wherever
 *
 * @wordpress-plugin
 * Plugin Name:       Wherever Content
 * Plugin URI:        http://wherever.grell.es
 * GitHub Plugin URI: boquiabierto/wherever-content
 * Description:       Put reusable content wherever you want.
 * Version:           1.0.6
 * Author:            Adrián Ortiz Arandes
 * Author URI:        http://grell.es
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wherever
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wherever-activator.php
 */
function activate_wherever() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wherever-activator.php';
	Wherever_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wherever-deactivator.php
 */
function deactivate_wherever() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wherever-deactivator.php';
	Wherever_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wherever' );
register_deactivation_hook( __FILE__, 'deactivate_wherever' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wherever.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_wherever() {

	$plugin = new Wherever();
	$plugin->run();

}
run_wherever();
