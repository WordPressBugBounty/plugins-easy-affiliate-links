<?php
/**
 * Show a FAQ in the backend menu.
 *
 * @link       https://bootstrapped.ventures
 * @since      2.0.0
 *
 * @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/includes/admin
 */

/**
 * Show a FAQ in the backend menu.
 *
 * @since      2.0.0
 * @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/includes/admin
 * @author     Brecht Vandersmissen <brecht@bootstrapped.ventures>
 */
class EAFL_Admin_Menu_Faq {

	/**
	 * Register actions and filters.
	 *
	 * @since    2.0.0
	 */
	public static function init() {
		add_action( 'admin_init', array( __CLASS__, 'redirect' ) );
		add_action( 'admin_head-affiliate-links_page_eafl_faq', array( __CLASS__, 'add_support_widget' ) );
		add_action( 'admin_menu', array( __CLASS__, 'add_submenu_page' ), 22 );
	}

	/**
	 * Redirect to FAQ page if this plugin was activated by itself.
	 *
	 * @since    2.0.0
	 */
	public static function redirect() {
		// Check if a single plugin was just activated.
		if ( isset( $_GET['activate'] ) ) { // Input var okay.
			// Make sure it was our plugin that was just activated.
			if ( get_option( 'eafl_activated', false ) ) {
				delete_option( 'eafl_activated' );

				wp_safe_redirect( admin_url( 'admin.php?page=eafl_faq' ) );
				exit();
			}
		}
	}

	/**
	 * Add our support widget to the page.
	 *
	 * @since    2.0.0
	 */
	public static function add_support_widget() {
		require_once( EAFL_DIR . 'templates/admin/menu/support-widget.php' );
	}

	/**
	 * Add the FAQ & Support submenu to the EAFL menu.
	 *
	 * @since    2.0.0
	 */
	public static function add_submenu_page() {
		add_submenu_page( 'easyaffiliatelinks', __( 'FAQ & Support', 'easy-affiliate-links' ), __( 'FAQ & Support', 'easy-affiliate-links' ), 'manage_options', 'eafl_faq', array( __CLASS__, 'page_template' ) );
	}

	/**
	 * Get the template for this submenu.
	 *
	 * @since    2.0.0
	 */
	public static function page_template() {
		require_once( EAFL_DIR . 'templates/admin/menu/faq.php' );
	}
}

EAFL_Admin_Menu_Faq::init();
