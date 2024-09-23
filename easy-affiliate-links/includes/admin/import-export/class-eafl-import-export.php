<?php
/**
 * Handle the import & export page.
 *
 * @link       https://bootstrapped.ventures
 * @since      3.0.0
 *
 * @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/includes/admin/import-export
 */

/**
 * Handle the import & export page.
 *
 * @since      3.0.0
 * @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/includes/admin/import-export
 * @author     Brecht Vandersmissen <brecht@bootstrapped.ventures>
 */
class EAFL_Import_Export {

	/**
	 * Register actions and filters.
	 *
	 * @since    3.0.0
	 */
	public static function init() {
		add_action( 'admin_menu', array( __CLASS__, 'add_submenu_page' ), 14 );
	}

	/**
	 * Add the manage submenu to the EAFL menu.
	 *
	 * @since    3.0.0
	 */
	public static function add_submenu_page() {
		add_submenu_page( 'easyaffiliatelinks', __( 'Import & Export', 'easy-affiliate-links' ), __( 'Import & Export', 'easy-affiliate-links' ), EAFL_Settings::get( 'import_capability' ), 'eafl_import_export', array( __CLASS__, 'page_template' ) );
	}

	/**
	 * Get the template for this submenu.
	 *
	 * @since    3.0.0
	 */
	public static function page_template() {
		require_once( EAFL_DIR . 'templates/admin/menu/import-export.php' );
	}
}

EAFL_Import_Export::init();
