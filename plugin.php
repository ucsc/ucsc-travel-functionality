<?php
/**
 * Plugin Name: UCSC Travel Functionality
 * Plugin URI: https://github.com/ucsc/ucsc-travel-functionality
 * Description: Adds custom functionality to the UCSC Travel website.
 * Version: 0.1.1
 * Requires at least: 6.5.0
 * Requires PHP: 8.1
 * Author: UC Santa Cruz
 * Author URI: https://github.com/ucsc
 * License: GPL3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 * Update URI: https://github.com/ucsc/ucsc-travel-functionality/releases
 * Text Domain: ucsctravel
 *
 * @package ucsc-travel-functionality
 */

defined( 'ABSPATH' ) || exit;

// Set plugin directory and base name.
define( 'UCSCTRAVEL_PLUGIN_DIR', plugin_dir_path( __FILE__ ) ); // Path to plugin directory.
define( 'UCSCTRAVEL_PLUGIN_BASE', plugin_basename( __FILE__ ) ); // Plugin base name 'plugin.php' at root.

// Include taxonomy functions.
if ( file_exists( UCSCTRAVEL_PLUGIN_DIR . 'lib/functions/taxonomies.php' ) ) {
	require_once UCSCTRAVEL_PLUGIN_DIR . 'lib/functions/taxonomies.php';
}

// Include settings page functions.
if ( file_exists( UCSCTRAVEL_PLUGIN_DIR . 'lib/functions/settings.php' ) ) {
	require_once UCSCTRAVEL_PLUGIN_DIR . 'lib/functions/settings.php';
}
