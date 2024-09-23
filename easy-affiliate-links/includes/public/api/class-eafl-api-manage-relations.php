<?php
/**
 * API for managing the relations.
 *
 * @link       https://bootstrapped.ventures
 * @since      3.2.0
 *
 * @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/includes/public/api
 */

/**
 * API for managing the relations.
 *
 * @since      3.2.0
 * @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/includes/public/api
 * @author     Brecht Vandersmissen <brecht@bootstrapped.ventures>
 */
class EAFL_API_Manage_Relations {

	/**
	 * Register actions and filters.
	 *
	 * @since    3.2.0
	 */
	public static function init() {
		add_action( 'rest_api_init', array( __CLASS__, 'api_register_data' ) );
	}

	/**
	 * Register data for the REST API.
	 *
	 * @since    3.2.0
	 */
	public static function api_register_data() {
		if ( function_exists( 'register_rest_field' ) ) {
			register_rest_route( 'easy-affiliate-links/v1', '/manage/relations', array(
				'callback' => array( __CLASS__, 'api_manage_relations' ),
				'methods' => 'POST',
				'permission_callback' => array( __CLASS__, 'api_required_permissions' ),
			) );
		}
	}

	/**
	 * Validate ID in API call.
	 *
	 * @since    3.2.0
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
	 * @since    3.2.0
	 */
	public static function api_required_permissions() {
		return current_user_can( EAFL_Settings::get( 'manage_capability' ) );
	}

	/**
	 * Handle manage relations call to the REST API.
	 *
	 * @since    3.2.0
	 * @param    WP_REST_Request $request Current request.
	 */
	public static function api_manage_relations( $request ) {
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

		// Order.
		$args['order'] = $sorted[0]['desc'] ? 'DESC' : 'ASC';
		switch( $sorted[0]['id'] ) {
			case 'post_id':
				$args['orderby'] = 'post_id';
				break;
			case 'link_id':
				$args['orderby'] = 'link_id';
				break;
			case 'occurrences':
				$args['orderby'] = 'occurrences';
				break;
			default:
			 	$args['orderby'] = 'ID';
		}

		// Filter.
		if ( $filtered ) {
			foreach ( $filtered as $filter ) {
				$value = trim( $filter['value'] );
				switch( $filter['id'] ) {
					case 'post_id':
						$args['filter'][] = 'post_id LIKE "%' . esc_sql( like_escape( intval( $value ) ) ). '%"';
						break;
					case 'link_id':
						$args['filter'][] = 'link_id LIKE "%' . esc_sql( like_escape( intval( $value ) ) ). '%"';
						break;
					case 'occurrences':
						$args['filter'][] = 'occurrences LIKE "%' . esc_sql( like_escape( $value ) ) . '%"';
						break;
				}
			}

			if ( $args['filter'] ) {
				$args['where'] = implode( ' AND ', $args['filter'] );
			}
		}

		$query = EAFL_Relations_Database::query( $args );

		$total = $query['total'] ? $query['total'] : 0;
		$rows = $query['relations'] ? array_values( $query['relations'] ) : array();

		// Add extra infromation for the manage page.
		foreach ( $rows as $relation ) {
			$link = EAFL_Link_Manager::get_link( $relation->link_id );

			$relation->link = $link ? $link->get_data_manage() : false;
			$relation->post = get_post( $relation->post_id );

			// Get view and edit links.
			$relation->post_url = $relation->post_id ? get_permalink( $relation->post_id ) : '';
			$relation->post_edit_url = $relation->post_id ? get_edit_post_link( $relation->post_id ) : '';
		}

		return array(
			'rows' => array_values( $rows ),
			'total' => EAFL_Relations_Database::count(),
			'filtered' => $total,
			'pages' => ceil( $total / $page_size ),
		);
	}
}

EAFL_API_Manage_Relations::init();
