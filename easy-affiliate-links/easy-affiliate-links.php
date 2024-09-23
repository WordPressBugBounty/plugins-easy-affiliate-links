<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://bootstrapped.ventures/
 * @since             2.0.0
 * @package           Easy_Affiliate_links
 *
 * @wordpress-plugin
 * Plugin Name:       Easy Affiliate Links
 * Plugin URI:        https://bootstrapped.ventures/easy-affiliate-links/
 * Description:       Easily manage and cloak all your affiliate links.
 * Version:           3.7.4
 * Author:            Bootstrapped Ventures
 * Author URI:        https://bootstrapped.ventures/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       easy-affiliate-links
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-eafl-activator.php
 */
function activate_easy_affiliate_links() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-eafl-activator.php';
	EAFL_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-eafl-deactivator.php
 */
function deactivate_easy_affiliate_links() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-eafl-deactivator.php';
	EAFL_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_easy_affiliate_links' );
register_deactivation_hook( __FILE__, 'deactivate_easy_affiliate_links' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-easy-affiliate-links.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    2.0.0
 */
function run_easy_affiliate_links() {
	$plugin = new Easy_Affiliate_Links();
	add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ) , array( $plugin, 'plugin_action_links' ), 1 );
}
run_easy_affiliate_links();
