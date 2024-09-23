<?php
/**
 * API for managing the categories.
 *
 * @link       https://bootstrapped.ventures
 * @since      3.0.0
 *
 * @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/includes/public/api
 */

/**
 * API for managing the categories.
 *
 * @since      3.0.0
 * @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/includes/public/api
 * @author     Brecht Vandersmissen <brecht@bootstrapped.ventures>
 */
class EAFL_API_Manage_Categories {

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
			register_rest_route( 'easy-affiliate-links/v1', '/manage/categories', array(
				'callback' => array( __CLASS__, 'api_manage_categories' ),
				'methods' => 'POST',
				'permission_callback' => array( __CLASS__, 'api_required_permissions' ),
			) );
			register_rest_route( 'easy-affiliate-links/v1', '/manage/categories/bulk', array(
				'callback' => array( __CLASS__, 'api_manage_categories_bulk_edit' ),
				'methods' => 'POST',
				'permission_callback' => array( __CLASS__, 'api_required_permissions' ),
			) );
			register_rest_route( 'easy-affiliate-links/v1', '/manage/categories/merge', array(
				'callback' => array( __CLASS__, 'api_merge_categories' ),
				'methods' => 'POST',
				'permission_callback' => array( __CLASS__, 'api_required_permissions' ),
			) );
		}
	}

	/**
	 * Required permissions for the API.
	 *
	 * @since    3.0.0
	 */
	public static function api_required_permissions() {
		return current_user_can( EAFL_Settings::get( 'manage_capability' ) );
	}

	/**
	 * Handle manage categories call to the REST API.
	 *
	 * @since    3.0.0
	 * @param    WP_REST_Request $request Current request.
	 */
	public static function api_manage_categories( $request ) {
		// Parameters.
		$params = $request->get_params();

		$page = isset( $params['page'] ) ? intval( $params['page'] ) : 0;
		$page_size = isset( $params['pageSize'] ) ? intval( $params['pageSize'] ) : 25;
		$sorted = isset( $params['sorted'] ) ? $params['sorted'] : array( array( 'id' => 'id', 'desc' => true ) );
		$filtered = isset( $params['filtered'] ) ? $params['filtered'] : array();

		// Starting query args.
		$args = array(
			'taxonomy' => 'eafl_category',
			'hide_empty' => false,
			'number' => $page_size,
			'offset' => $page * $page_size,
			'count' => true,
		);

		// Order.
		$args['order'] = $sorted[0]['desc'] ? 'DESC' : 'ASC';
		switch( $sorted[0]['id'] ) {
			case 'name':
				$args['orderby'] = 'title';
				break;
			case 'count':
				$args['orderby'] = 'count';
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
						$args['eafl_search_id'] = $value;
						break;
					case 'name':
						$args['search'] = $value;
						break;
				}
			}
		}

		add_filter( 'terms_clauses', array( __CLASS__, 'api_manage_categories_query' ), 10, 3 );
		$query = new WP_Term_Query( $args );

		unset( $args['number'] );
		unset( $args['offset'] );
		$filtered_terms = wp_count_terms( 'eafl_category', $args );
		remove_filter( 'terms_clauses', array( __CLASS__, 'api_manage_categories_query' ), 10, 3 );

		$total_terms = wp_count_terms( 'eafl_category', array( 'hide_empty' => false ) );

		return array(
			'rows' => $query->terms ? array_values( $query->terms ) : array(),
			'total' => intval( $total_terms ),
			'filtered' => intval( $filtered_terms ),
			'pages' => ceil( $filtered_terms / $page_size ),
		);
	}

	/**
	 * Filter the where categories query.
	 *
	 * @since    3.0.0
	 */
	public static function api_manage_categories_query( $pieces, $taxonomies, $args ) {		
		$id_search = isset( $args['eafl_search_id'] ) ? $args['eafl_search_id'] : false;
		if ( $id_search ) {
			$pieces['where'] .= ' AND t.term_id LIKE \'%' . esc_sql( like_escape( $id_search ) ) . '%\'';
		}

		return $pieces;
	}

	/**
	 * Handle merge categories call to the REST API.
	 *
	 * @since    3.0.0
	 * @param    WP_REST_Request $request Current request.
	 */
	public static function api_merge_categories( $request ) {
		// Parameters.
		$params = $request->get_params();

		$old_id = isset( $params['oldId'] ) ? intval( $params['oldId'] ) : false;
		$new_id = isset( $params['newId'] ) ? intval( $params['newId'] ) : false;

		if ( $old_id && $new_id ) {
			$old_term = get_term( $old_id, 'eafl_category' );
			$new_term = get_term( $new_id, 'eafl_category' );

			if ( $old_term && ! is_wp_error( $old_term ) && $new_term && ! is_wp_error( $new_term ) ) {
				// Add new term ID to links using the old term ID.
				$args = array(
					'post_type' => EAFL_POST_TYPE,
					'post_status' => 'any',
					'nopaging' => true,
					'tax_query' => array(
						array(
							'taxonomy' => $old_term->taxonomy,
							'field' => 'id',
							'terms' => $old_term->term_id,
						),
					)
				);
		
				$query = new WP_Query( $args );
				$posts = $query->posts;
				foreach ( $posts as $post ) {
					wp_set_object_terms( $post->ID, $new_term->term_id, $new_term->taxonomy, true );
				}

				// Delete old term.
				wp_delete_term( $old_term->term_id, 'eafl_category' );
				return true;
			}
		}

		return false;
	}

	/**
	 * Handle categories bulk edit call to the REST API.
	 *
	 * @since    3.1.0
	 * @param    WP_REST_Request $request Current request.
	 */
	public static function api_manage_categories_bulk_edit( $request ) {
		// Parameters.
		$params = $request->get_params();

		$ids = isset( $params['ids'] ) ? array_map( 'intval', $params['ids'] ) : array();
		$action = isset( $params['action'] ) ? $params['action'] : false;

		if ( $ids && $action && $action['type'] ) {
			// Do per post.
			$args = array(
				'taxonomy' => 'eafl_category',
				'hide_empty' => false,
				'include' => $ids,
			);

			$query = new WP_Term_Query( $args );
			$terms = $query->terms ? array_values( $query->terms ) : array();

			foreach ( $terms as $term ) {
				switch ( $action['type'] ) {
					case 'delete':
						wp_delete_term( $term->term_id, 'eafl_category' );
						break;
				}
			}

			return true;
		}

		return false;
	}
}

EAFL_API_Manage_Categories::init();
