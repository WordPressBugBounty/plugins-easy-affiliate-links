<?php
/**
 * Provide information about Easy Affiliate Links addons.
 *
 * @link       https://bootstrapped.ventures
 * @since      2.0.0
 *
 * @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/includes/public
 */

/**
 * Provide information about Easy Affiliate Links addons.
 *
 * @since      2.0.0
 * @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/includes/public
 * @author     Brecht Vandersmissen <brecht@bootstrapped.ventures>
 */
class EAFL_Addons {

	/**
	 * Register actions and filters.
	 *
	 * @since    2.0.0
	 */
	public static function init() {
	}

	/**
	 * Check if a particular addon is active.
	 *
	 * @since    2.0.0
	 * @param	 	 mixed $addon Addon to check.
	 */
	public static function is_active( $addon ) {
		return apply_filters( 'eafl_addon_active', false, $addon );
	}
}

EAFL_Addons::init();
