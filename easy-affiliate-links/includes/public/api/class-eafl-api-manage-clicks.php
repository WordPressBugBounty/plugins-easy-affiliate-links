<?php
/**
 * API for managing the clicks.
 *
 * @link       https://bootstrapped.ventures
 * @since      3.1.0
 *
 * @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/includes/public/api
 */

/**
 * API for managing the clicks.
 *
 * @since      3.1.0
 * @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/includes/public/api
 * @author     Brecht Vandersmissen <brecht@bootstrapped.ventures>
 */
class EAFL_API_Manage_Clicks {

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
			register_rest_route( 'easy-affiliate-links/v1', '/manage/clicks', array(
				'callback' => array( __CLASS__, 'api_manage_clicks' ),
				'methods' => 'POST',
				'permission_callback' => array( __CLASS__, 'api_required_permissions' ),
			) );
			register_rest_route( 'easy-affiliate-links/v1', '/manage/clicks/bulk', array(
				'callback' => array( __CLASS__, 'api_manage_clicks_bulk_edit' ),
				'methods' => 'POST',
				'permission_callback' => array( __CLASS__, 'api_required_permissions' ),
			) );
			register_rest_route( 'easy-affiliate-links/v1', '/manage/clicks/(?P<id>\d+)', array(
				'callback' => array( __CLASS__, 'api_delete_clicks' ),
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
	 * @since    3.1.0
	 * @param    mixed           $param Parameter to validate.
	 * @param    WP_REST_Request $request Current request.
	 * @param    mixed           $key Key.
	 */
	public static function api_validate_numeric( $param, $request, $key ) {
		return is_numeric( $param );
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
	 * Handle manage clicks call to the REST API.
	 *
	 * @since    3.1.0
	 * @param    WP_REST_Request $request Current request.
	 */
	public static function api_manage_clicks( $request ) {
		// Parameters.
		$params = $request->get_params();

		$page = isset( $params['page'] ) ? intval( $params['page'] ) : 0;
		$page_size = isset( $params['pageSize'] ) ? intval( $params['pageSize'] ) : 25;
		$sorted = isset( $params['sorted'] ) ? $params['sorted'] : array( array( 'id' => 'id', 'desc' => true ) );
		$filtered = isset( $params['filtered'] ) ? $params['filtered'] : array();

		// Starting query args.
		$args = array(
			'limit' => $page_size,
			'offset' => $page * $page_size,
		);

		// Limit to 10 clicks in free version.
		if ( ! EAFL_Addons::is_active( 'statistics' ) ) {
			$args['offset'] = 0;
			$args['limit'] = 10;
		}

		// Order.
		$args['order'] = $sorted[0]['desc'] ? 'DESC' : 'ASC';
		switch( $sorted[0]['id'] ) {
			case 'date':
				$args['orderby'] = 'date';
				break;
			case 'link_id':
				$args['orderby'] = 'link_id';
				break;
			case 'referer':
				$args['orderby'] = 'referer';
				break;
			case 'user_id':
				$args['orderby'] = 'user_id';
				break;
			case 'ip':
				$args['orderby'] = 'ip';
				break;
			case 'agent':
				$args['orderby'] = 'agent';
				break;
			default:
			 	$args['orderby'] = 'ID';
		}

		// Filter.
		if ( $filtered ) {
			foreach ( $filtered as $filter ) {
				$value = trim( $filter['value'] );
				switch( $filter['id'] ) {
					case 'id':
						$args['filter'][] = 'id LIKE "%' . esc_sql( like_escape( $value ) ) . '%"';
						break;
					case 'date':
						$args['filter'][] = 'date LIKE "%' . esc_sql( like_escape( esc_attr( $value ) ) ) . '%"';
						break;
					case 'link_id':
						$args['filter'][] = 'link_id LIKE "%' . esc_sql( like_escape( intval( $value ) ) ). '%"';
						break;
					case 'referer':
						$args['filter'][] = 'referer LIKE "%' . esc_sql( like_escape( $value ) ) . '%"';
						break;
					case 'user_id':
						$args['filter'][] = 'user_id LIKE "%' . esc_sql( like_escape( intval( $value ) ) ) . '%"';
						break;
					case 'ip':
						$args['filter'][] = 'ip LIKE "%' . esc_sql( like_escape( $value ) ) . '%"';
						break;
					case 'device_type':
						if ( 'all' !== $value ) {
							if ( 'desktop' === $value ) {
								$args['filter'][] = 'is_desktop = 1';
							} elseif ( 'tablet' === $value ) {
								$args['filter'][] = 'is_tablet = 1';
							} else {
								$args['filter'][] = 'is_mobile = 1';
							}
						}
						break;
					case 'agent':
						$args['filter'][] = 'agent LIKE "%' . esc_sql( like_escape( $value ) ) . '%"';
						break;
				}
			}

			if ( $args['filter'] ) {
				$args['where'] = implode( ' AND ', $args['filter'] );
			}
		}

		$query = EAFL_Clicks_Database::get_clicks( $args );

		$total = $query['total'] ? $query['total'] : 0;
		$rows = $query['clicks'] ? array_values( $query['clicks'] ) : array();

		// Add extra infromation for the manage page.
		foreach ( $rows as $click ) {
			$link = EAFL_Link_Manager::get_link( $click->link_id );

			$click->link = $link ? $link->get_data_manage() : false;

			// User.
			$click->user_name = $click->user_id ? __( 'Unknown', 'easy-affiliate-links' ) : __( 'n/a', 'easy-affiliate-links' );

			$user = get_userdata( $click->user_id );
			if ( $user ) {
				$click->user_name = $user->display_name;
				$click->user_link = get_edit_user_link( $click->user_id );
			}
		}

		return array(
			'rows' => array_values( $rows ),
			'total' => EAFL_Clicks_Database::count_clicks(),
			'filtered' => $total,
			'pages' => ceil( $total / $page_size ),
		);
	}

	/**
	 * Handle clicks bulk edit call to the REST API.
	 *
	 * @since    3.1.0
	 * @param    WP_REST_Request $request Current request.
	 */
	public static function api_manage_clicks_bulk_edit( $request ) {
		// Parameters.
		$params = $request->get_params();

		$ids = isset( $params['ids'] ) ? array_map( 'intval', $params['ids'] ) : array();
		$action = isset( $params['action'] ) ? $params['action'] : false;

		if ( $ids && $action && $action['type'] ) {
			switch ( $action['type'] ) {
				case 'delete':
					EAFL_Clicks_Database::delete_clicks( $ids );
					break;
			}

			return true;
		}

		return false;
	}
}

EAFL_API_Manage_Clicks::init();
