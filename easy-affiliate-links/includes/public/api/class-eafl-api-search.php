<?php
/**
 * REST API for searching links.
 *
 * @link       https://bootstrapped.ventures
 * @since      3.1.0
 *
 * @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/includes/public/api
 */

/**
 * REST API for searching links.
 *
 * @since      3.1.0
 * @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/includes/public/api
 * @author     Brecht Vandersmissen <brecht@bootstrapped.ventures>
 */
class EAFL_API_Search {

	/**
	 * Register actions and filters.
	 *
	 * @since    3.0.0
	 */
	public static function init() {
		add_action( 'rest_api_init', array( __CLASS__, 'api_register_data' ) );
	}

	/**
	 * Register data for the REST API.
	 *
	 * @since    3.0.0
	 */
	public static function api_register_data() {
		if ( function_exists( 'register_rest_field' ) ) {
			register_rest_route( 'easy-affiliate-links/v1', '/search/links', array(
				'callback' => array( __CLASS__, 'api_search_links' ),
				'methods' => 'POST',
				'permission_callback' => array( __CLASS__, 'api_required_permissions' ),
			) );
		}
	}

	/**
	 * Required permissions for the API.
	 *
	 * @since    3.1.0
	 */
	public static function api_required_permissions() {
		return current_user_can( EAFL_Settings::get( 'manage_capability' ) );
	}

	/**
	 * Handle manage links call to the REST API.
	 *
	 * @since    3.0.0
	 * @param    WP_REST_Request $request Current request.
	 */
	public static function api_search_links( $request ) {
		// Parameters.
		$params = $request->get_params();

		$search = isset( $params['search'] ) ? $params['search'] : '';

		// Starting query args.
		$args = array(
			'post_type' => EAFL_POST_TYPE,
			'post_status' => 'any',
			'posts_per_page' => 50,
			's' => $search,
		);

		$query = new WP_Query( $args );

		$links = array();
		$posts = $query->posts;
		foreach ( $posts as $post ) {
			$link = EAFL_Link_Manager::get_link( $post );

			if ( ! $link ) {
				continue;
			}

			$links[] = $link->get_data_manage();
		}

		return array(
			'links' => array_values( $links ),
		);
	}
}

EAFL_API_Search::init();
