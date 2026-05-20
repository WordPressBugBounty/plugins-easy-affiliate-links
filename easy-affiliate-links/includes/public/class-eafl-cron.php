<?php
/**
 * Handle any cron jobs.
 *
 * @link       https://bootstrapped.ventures
 * @since      3.8.2
 *
 * @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/includes/public
 */

/**
 * Handle any cron jobs.
 *
 * @since      3.8.2
 * @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/includes/public
 * @author     Brecht Vandersmissen <brecht@bootstrapped.ventures>
 */
class EAFL_Cron {

	/**
	 * Register actions and filters.
	 *
	 * @since    3.8.2
	 */
	public static function init() {
		add_filter( 'cron_schedules', array( __CLASS__, 'add_cron_interval' ) );

		add_action( 'admin_init', array( __CLASS__, 'schedule_cron' ) );
	}

	/**
	 * Add interval to cron schedules.
	 *
	 * @since    3.8.2
	 * @param    mixed $schedules Current cron schedules.
	 */
	public static function add_cron_interval( $schedules ) {
		$schedules['eafl_daily'] = array(
			'interval' => 24 * 60 * 60,
			'display' => 'Easy Affiliate Links - Daily',
		);

		$schedules['eafl_hourly'] = array(
			'interval' => 60 * 60,
			'display' => 'Easy Affiliate Links - Hourly',
		);

		return $schedules;
	}

	/**
	 * Schedule cron tasks.
	 *
	 * @since    3.8.2
	 */
	public static function schedule_cron() {
		$next_event = wp_next_scheduled( 'eafl_daily_cron' );

		if ( ! $next_event ) {
			wp_schedule_event( time(), 'eafl_daily', 'eafl_daily_cron' );
		}

		$next_event = wp_next_scheduled( 'eafl_hourly_cron' );

		if ( ! $next_event ) {
			wp_schedule_event( time(), 'eafl_hourly', 'eafl_hourly_cron' );
		}
	}
}

EAFL_Cron::init();
