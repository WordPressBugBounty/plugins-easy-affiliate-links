<?php
/**
 * REST API for links.
 *
 * @link       https://bootstrapped.ventures
 * @since      3.0.0
 *
 * @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/includes/public/api
 */

/**
 * REST API for links.
 *
 * @since      3.0.0
 * @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/includes/public/api
 * @author     Brecht Vandersmissen <brecht@bootstrapped.ventures>
 */
class EAFL_API_Links {

	/**
	 * Register actions and filters.
	 *
	 * @since    3.0.0
	 */
	public static function init() {
		add_action( 'rest_api_init', array( __CLASS__, 'api_register_data' ) );
		add_action( 'rest_insert_' . EAFL_POST_TYPE, array( __CLASS__, 'api_insert_update_link' ), 10, 3 );
	}

	/**
	 * Register data for the REST API.
	 *
	 * @since    3.0.0
	 */
	public static function api_register_data() {
		if ( function_exists( 'register_rest_field' ) ) {
			register_rest_field( EAFL_POST_TYPE, 'link', array(
				'get_callback'    => array( __CLASS__, 'api_get_link_data' ),
				'update_callback' => null,
				'schema'          => null,
			));
		}
	}

	/**
	 * Handle get calls to the REST API.
	 *
	 * @since    3.0.0
	 * @param    array           $object Details of current post.
	 * @param    mixed           $field_name Name of field.
	 * @param    WP_REST_Request $request Current request.
	 */
	public static function api_get_link_data( $object, $field_name, $request ) {
		$link = EAFL_Link_Manager::get_link( $object['id'] );
		return $link ? $link->get_data() : false;
	}

	/**
	 * Handle link calls to the REST API.
	 *
	 * @since    3.0.0
	 * @param    WP_Post         $post     Inserted or updated post object.
	 * @param    WP_REST_Request $request  Request object.
	 * @param    bool            $creating True when creating a post, false when updating.
	 */
	public static function api_insert_update_link( $post, $request, $creating ) {
		$params = $request->get_params();
		$link = isset( $params['link'] ) ? EAFL_Link_Sanitizer::sanitize( $params['link'] ) : array();
		$link_id = $post->ID;

		if ( $creating ) {
			update_post_meta( $link_id, 'eafl_clicks_total', 0 );
		}
		EAFL_Link_Saver::update_link( $link_id, $link );
		
		$link = EAFL_Link_Manager::get_link( $link_id );
		return $link ? $link->get_data() : false;
	}
}

EAFL_API_Links::init();
