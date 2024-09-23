<?php
/**
 * Responsible for showing the EAFL menu in the WP backend.
 *
 * @link       https://bootstrapped.ventures
 * @since      2.0.0
 *
 * @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/includes/admin/menu
 */

/**
 * Responsible for showing the EAFL menu in the WP backend.
 *
 * @since      2.0.0
 * @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/includes/admin/menu
 * @author     Brecht Vandersmissen <brecht@bootstrapped.ventures>
 */
class EAFL_Admin_Menu {

	/**
	 * Register actions and filters.
	 *
	 * @since    2.0.0
	 */
	public static function init() {
		add_action( 'admin_menu', array( __CLASS__, 'add_menu_page' ) );
	}

	/**
	 * Add EAFL to the wordpress menu.
	 *
	 * @since    2.0.0
	 */
	public static function add_menu_page() {
		add_menu_page( 'Easy Affiliate Links', 'Affiliate Links', EAFL_Settings::get( 'manage_capability' ), 'easyaffiliatelinks', array( 'EAFL_Manage_Modal', 'manage_page_template' ), 'dashicons-admin-links', 20 );
	}
}

EAFL_Admin_Menu::init();
