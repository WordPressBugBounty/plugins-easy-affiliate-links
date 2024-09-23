<?php
/**
 * Show addons in the backend menu.
 *
 * @link       https://bootstrapped.ventures
 * @since      2.0.0
 *
 * @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/includes/admin
 */

/**
 * Show addons in the backend menu.
 *
 * @since      2.0.0
 * @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/includes/admin
 * @author     Brecht Vandersmissen <brecht@bootstrapped.ventures>
 */
class EAFL_Admin_Menu_Addons {

	/**
	 * Register actions and filters.
	 *
	 * @since    2.0.0
	 */
	public static function init() {
		add_action( 'admin_menu', array( __CLASS__, 'add_submenu_page' ), 99 );
	}

	/**
	 * Add the FAQ & Support submenu to the EAFL menu.
	 *
	 * @since    2.0.0
	 */
	public static function add_submenu_page() {
		add_submenu_page( 'easyaffiliatelinks', __( 'Upgrade EAFL', 'easy-affiliate-links' ), __( 'Upgrade EAFL', 'easy-affiliate-links' ), 'manage_options', 'eafl_addons', array( __CLASS__, 'page_template' ) );
	}

	/**
	 * Get the template for this submenu.
	 *
	 * @since    2.0.0
	 */
	public static function page_template() {
		require_once( EAFL_DIR . 'templates/admin/menu/addons.php' );
	}
}

EAFL_Admin_Menu_Addons::init();
