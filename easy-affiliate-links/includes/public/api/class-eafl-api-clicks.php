<?php
/**
 * REST API for clicks.
 *
 * @link       https://bootstrapped.ventures
 * @since      3.1.0
 *
 * @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/includes/public/api
 */

/**
 * REST API for clicks.
 *
 * @since      3.1.0
 * @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/includes/public/api
 * @author     Brecht Vandersmissen <brecht@bootstrapped.ventures>
 */
class EAFL_API_Clicks {

	/**
	 * Register actions and filters.
	 *
	 * @since    3.1.0
	 */
	public static function init() {
		add_action( 'rest_api_init', array( __CLASS__, 'api_register_data' ) );
	}

	/**
	 * Register data for the REST API.
	 *
	 * @since    3.1.0
	 */
	public static function api_register_data() {
		if ( function_exists( 'register_rest_field' ) ) {
			register_rest_route( 'easy-affiliate-links/v1', '/click/(?P<id>\d+)', array(
				'callback' => array( __CLASS__, 'api_delete_click' ),
				'methods' => 'DELETE',
				'args' => array(
					'id' => array(
						'validate_callback' => array( __CLASS__, 'api_validate_numeric' ),
					),
				),
				'permission_callback' => array( __CLASS__, 'api_required_permissions' ),
			));
			register_rest_route( 'easy-affiliate-links/v1', '/click/link/(?P<id>\d+)', array(
				'callback' => array( __CLASS__, 'api_delete_clicks_for_link' ),
				'methods' => 'DELETE',
				'args' => array(
					'id' => array(
						'validate_callback' => array( __CLASS__, 'api_validate_numeric' ),
					),
				),
				'permission_callback' => array( __CLASS__, 'api_required_permissions' ),
			));
		}
	}

	/**
	 * Validate ID in API call.
	 *
	 * @since 3.1.0
	 * @param mixed           $param Parameter to validate.
	 * @param WP_REST_Request $request Current request.
	 * @param mixed           $key Key.
	 */
	public static function api_validate_numeric( $param, $request, $key ) {
		return is_numeric( $param );
	}

	/**
	 * Required permissions for the API.
	 *
	 * @since 3.1.0
	 */
	public static function api_required_permissions() {
		return current_user_can( EAFL_Settings::get( 'manage_capability' ) );
	}

	/**
	 * Handle delete click call to the REST API.
	 *
	 * @since 3.1.0
	 * @param WP_REST_Request $request Current request.
	 */
	public static function api_delete_click( $request ) {
		EAFL_Clicks_Database::delete_click( $request['id'] );
		return true;
	}

	/**
	 * Handle delete clicks for link call to the REST API.
	 *
	 * @since 3.1.0
	 * @param WP_REST_Request $request Current request.
	 */
	public static function api_delete_clicks_for_link( $request ) {
		EAFL_Clicks_Database::delete_clicks_for( $request['id'] );
		return true;
	}
}

EAFL_API_Clicks::init();
