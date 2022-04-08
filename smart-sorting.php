<?php

/**
 *
 * @link              http://example.com
 * @since             1.0.0
 * @package           SmartSorting
 *
 * @wordpress-plugin
 * Plugin Name:       SmartSorting
 * Plugin URI:        http://example.com/smart-sorting-uri/
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            SmartSorting
 * Author URI:        http://example.com/
 * License:           LGPL-3.0+
 * License URI:       https://www.gnu.org/licenses/lgpl-3.0.txt
 * Text Domain:       smart-sorting
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 */
define( 'SMART_SORTING_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-smart-sorting-activator.php
 */
function activate_smart_sorting() {
	require_once plugin_dir_path( __FILE__ ) .
        'includes/class-smart-sorting-activator.php';
	Smart_Sorting_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-smart-sorting-deactivator.php
 */
function deactivate_smart_sorting() {
	require_once plugin_dir_path( __FILE__ ) .
        'includes/class-smart-sorting-deactivator.php';
	Smart_Sorting_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_smart_sorting' );
register_deactivation_hook( __FILE__, 'deactivate_smart_sorting' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-smart-sorting.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_smart_sorting() {

	$plugin = new Smart_Sorting();
	$plugin->run();

}
run_smart_sorting();
