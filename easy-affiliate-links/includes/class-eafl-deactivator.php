<?php
/**
 * Fired during plugin deactivation.
 *
 * @link       https://bootstrapped.ventures
 * @since      2.0.0
 *
 * @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      2.0.0
 * @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/includes
 * @author     Brecht Vandersmissen <brecht@bootstrapped.ventures>
 */
class EAFL_Deactivator {

	/**
	 * Execute this on deactivation of the plugin.
	 *
	 * @since    2.0.0
	 */
	public static function deactivate() {
		$next_event = wp_next_scheduled( 'eafl_daily_cron' );
		if ( $next_event ) {
			wp_unschedule_event( $next_event, 'eafl_daily_cron' );
		}

		$next_event = wp_next_scheduled( 'eafl_hourly_cron' );
		if ( $next_event ) {
			wp_unschedule_event( $next_event, 'eafl_hourly_cron' );
		}
	}
}
