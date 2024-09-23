<?php
/**
 * Define the internationalization functionality.
 *
 * @link       https://bootstrapped.ventures
 * @since      2.0.0
 *
 * @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      2.0.0
 * @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/includes
 * @author     Brecht Vandersmissen <brecht@bootstrapped.ventures>
 */
class EAFL_i18n {

	/**
	 * Register actions and filters.
	 *
	 * @since    2.0.0
	 */
	public static function init() {
		add_action( 'plugins_loaded', array( __CLASS__, 'load_plugin_textdomain' ) );
	}

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    2.0.0
	 */
	public static function load_plugin_textdomain() {
		load_plugin_textdomain(
			'easy-affiliate-links',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);
	}
}

EAFL_i18n::init();
