<?php
/**
 * Responsible for the EAFL tools.
 *
 * @link       http://bootstrapped.ventures
 * @since      3.1.0
 *
 * @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/includes/admin
 */

/**
 * Responsible for the EAFL tools.
 *
 * @since      3.1.0
 * @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/includes/admin
 * @author     Brecht Vandersmissen <brecht@bootstrapped.ventures>
 */
class EAFL_Tools_Manager {

	/**
	 * Only to be enabled when debugging the tools.
	 *
	 * @since    3.1.0
	 * @access   private
	 * @var      boolean    $debugging    Wether or not we are debugging the tools.
	 */
	public static $debugging = false;

	/**
	 * Register actions and filters.
	 *
	 * @since    3.1.0
	 */
	public static function init() {
		add_action( 'admin_menu', array( __CLASS__, 'add_submenu_page' ), 16 );
		add_action( 'wp_ajax_eafl_reset_settings', array( __CLASS__, 'ajax_reset_settings' ) );

		add_filter( 'eafl_tools', array( __CLASS__, 'tools' ), 99 );
	}

	/**
	 * Add the tools submenu to the EAFL menu.
	 *
	 * @since    3.1.0
	 */
	public static function add_submenu_page() {
		add_submenu_page( 'easyaffiliatelinks', __( 'EAFL Tools', 'easy-affiliate-links' ), __( 'Tools', 'easy-affiliate-links' ), EAFL_Settings::get( 'tools_capability' ), 'eafl_tools', array( __CLASS__, 'tools_page_template' ) );
	}

	/**
	 * Get the template for the tools page.
	 *
	 * @since    3.1.0
	 */
	public static function tools_page_template() {
		$tools = self::get_tools();
		require_once( EAFL_DIR . 'templates/admin/menu/tools/overview.php' );
	}

	/**
	 * Get the different tools.
	 *
	 * @since    3.1.0
	 */
	public static function get_tools() {
		return apply_filters( 'eafl_tools', array() );
	}

	/**
	 * Add reset settings to tools.
	 *
	 * @since    3.2.0
	 * @param	 mixed $tools Current tools.
	 */
	public static function tools( $tools ) {
		$tools['settings'] = array(
			'header' => __( 'Settings', 'easy-affiliate-links' ),
			'tools' => array(
				array(
					'id' => 'reset_settings',
					'label' => __( 'Reset Settings', 'easy-affiliate-links' ),
					'name' => __( 'Reset Settings to Default', 'easy-affiliate-links' ),
					'description' => __( 'Try using this if the settings page is not working at all.', 'easy-affiliate-links' ),
				),
			),
		);

		return $tools;
	}

	/**
	 * Reset settings through AJAX.
	 *
	 * @since    3.1.0
	 */
	public static function ajax_reset_settings() {
		if ( check_ajax_referer( 'eafl', 'security', false ) ) {
			if ( current_user_can( EAFL_Settings::get( 'tools_capability' ) ) ) {
				// Clear all settings.
				delete_option( 'eafl_settings' );

				wp_send_json_success( array(
					'redirect' => admin_url( 'admin.php?page=bv_settings_eafl' ),
				) );
			}
		}

		wp_die();
	}
}

EAFL_Tools_Manager::init();
