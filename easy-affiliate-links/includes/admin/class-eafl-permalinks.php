<?php
/**
 * Responsible for flushing the permalinks when needed.
 *
 * @link       https://bootstrapped.ventures
 * @since      2.0.1
 *
 * @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/includes/admin
 */

/**
 * Responsible for flushing the permalinks when needed.
 *
 * @since      2.0.1
 * @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/includes/admin
 * @author     Brecht Vandersmissen <brecht@bootstrapped.ventures>
 */
class EAFL_Permalinks {

	/**
	 * Register actions and filters.
	 *
	 * @since    2.0.1
	 */
	public static function init() {
		add_action( 'admin_init', array( __CLASS__, 'check_if_flush_needed' ) );
	}

	/**
	 * Check if a flushing of the permalinks is needed.
	 *
	 * @since    2.0.1
	 */
	public static function check_if_flush_needed() {
		if ( '1' === get_option( 'eafl_flush', '1' ) ) {
			flush_rewrite_rules();
			update_option( 'eafl_flush', '0' );
		}
	}

	/**
	 * Set that a flush is needed.
	 *
	 * @since    2.0.1
	 */
	public static function set_flush_needed() {
		update_option( 'eafl_flush', '1' );
	}
}

EAFL_Permalinks::init();
