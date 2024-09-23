<?php
/**
 * Fired during plugin activation.
 *
 * @link       https://bootstrapped.ventures
 * @since      2.0.0
 *
 * @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      2.0.0
 * @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/includes
 * @author     Brecht Vandersmissen <brecht@bootstrapped.ventures>
 */
class EAFL_Activator {

	/**
	 * Execute this on activation of the plugin.
	 *
	 * @since    2.0.0
	 */
	public static function activate() {
		add_option( 'eafl_activated', true );
		update_option( 'eafl_flush', '1' );
	}
}
