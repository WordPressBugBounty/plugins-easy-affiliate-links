<?php
/**
 * API for managing the links.
 *
 * @link       https://bootstrapped.ventures
 * @since      3.0.0
 *
 * @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/includes/public/api
 */

/**
 * API for managing the links.
 *
 * @since      3.0.0
 * @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/includes/public/api
 * @author     Brecht Vandersmissen <brecht@bootstrapped.ventures>
 */
class EAFL_API_Manage_Links {

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
			register_rest_route( 'easy-affiliate-links/v1', '/manage/links', array(
				'callback' => array( __CLASS__, 'api_manage_links' ),
				'methods' => 'POST',
				'permission_callback' => array( __CLASS__, 'api_required_permissions' ),
			) );
			register_rest_route( 'easy-affiliate-links/v1', '/manage/links/bulk', array(
				'callback' => array( __CLASS__, 'api_manage_links_bulk_edit' ),
				'methods' => 'POST',
				'permission_callback' => array( __CLASS__, 'api_required_permissions' ),
			) );
		}
	}

	/**
	 * Validate ID in API call.
	 *
	 * @since    3.0.0
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
	 * @since    3.0.0
	 */
	public static function api_required_permissions() {
		return current_user_can( EAFL_Settings::get( 'button_capability' ) );
	}

	/**
	 * Handle manage links call to the REST API.
	 *
	 * @since    3.0.0
	 * @param    WP_REST_Request $request Current request.
	 */
	public static function api_manage_links( $request ) {
		// Parameters.
		$params = $request->get_params();

		$page = isset( $params['page'] ) ? intval( $params['page'] ) : 0;
		$page_size = isset( $params['pageSize'] ) ? intval( $params['pageSize'] ) : 25;
		$sorted = isset( $params['sorted'] ) ? $params['sorted'] : array( array( 'id' => 'id', 'desc' => true ) );
		$filtered = isset( $params['filtered'] ) ? $params['filtered'] : array();

		// Starting query args.
		$args = array(
			'post_type' => EAFL_POST_TYPE,
			'post_status' => 'any',
			'posts_per_page' => $page_size,
			'offset' => $page * $page_size,
			'meta_query' => array(
				'relation' => 'AND',
			),
			'tax_query' => array(),
		);

		// Order.
		$args['order'] = $sorted[0]['desc'] ? 'DESC' : 'ASC';
		switch( $sorted[0]['id'] ) {
			case 'type':
				$args['orderby'] = 'meta_value';
				$args['meta_key'] = 'eafl_type';
				break;
			case 'date':
				$args['orderby'] = 'date';
				break;
			case 'name':
				$args['orderby'] = 'title';
				break;
			case 'description':
				$args['orderby'] = 'meta_value';
				$args['meta_key'] = 'eafl_description';
				break;
			case 'replacement':
				$args['orderby'] = 'meta_value_num';
				$args['meta_key'] = 'eafl_replacement';
				break;
			case 'shortlink':
				$args['orderby'] = 'name';
				break;
			case 'text':
				$args['orderby'] = 'meta_value';
				$args['meta_key'] = 'eafl_text';
				break;
			case 'cloak':
				$args['orderby'] = 'meta_value';
				$args['meta_key'] = 'eafl_cloak';
				break;
			case 'target':
				$args['orderby'] = 'meta_value';
				$args['meta_key'] = 'eafl_target';
				break;
			case 'redirect_type':
				$args['orderby'] = 'meta_value';
				$args['meta_key'] = 'eafl_redirect_type';
				break;
			case 'nofollow':
				$args['orderby'] = 'meta_value';
				$args['meta_key'] = 'eafl_nofollow';
				break;
			case 'sponsored':
				$args['orderby'] = 'meta_value';
				$args['meta_key'] = 'eafl_sponsored';
				break;
			case 'ugc':
				$args['orderby'] = 'meta_value';
				$args['meta_key'] = 'eafl_ugc';
				break;
			case 'clicks':
				$args['orderby'] = 'meta_value_num';
				$args['meta_key'] = 'eafl_clicks_total';
				break;
			case 'html':
				$args['orderby'] = 'meta_value';
				$args['meta_key'] = 'eafl_html';
				break;
			case 'url':
				$args['orderby'] = 'meta_value';
				$args['meta_key'] = 'eafl_url';
				break;
			case 'conditional':
				$args['orderby'] = 'meta_value';
				$args['meta_key'] = 'eafl_conditional';
				break;
			case 'status':
				$args['orderby'] = 'meta_value';
				$args['meta_key'] = 'eafl_status_type';
				break;
			case 'status_timestamp':
				$args['orderby'] = 'meta_value_num';
				$args['meta_key'] = 'eafl_status_timestamp';
				break;
			case 'wpupg_custom_image_id':
				$args['orderby'] = 'meta_value_num';
				$args['meta_key'] = 'wpupg_custom_image_id';
				break;
			default:
			 	$args['orderby'] = 'ID';
		}

		// Filter.
		if ( $filtered ) {
			foreach ( $filtered as $filter ) {
				$value = $filter['value'];
				switch( $filter['id'] ) {
					case 'id':
						$args['eafl_search_id'] = $value;
						break;
					case 'type':
						if ( 'all' !== $value ) {
							$args['meta_query'][] = array(
								'key' => 'eafl_type',
								'compare' => '=',
								'value' => $value,
							);
						}
						break;
					case 'date':
						$args['eafl_search_date'] = $value;
						break;
					case 'name':
						$args['eafl_search_title'] = $value;
						break;
					case 'description':
						if ( $value ) {
							$args['meta_query'][] = array(
								'key' => 'eafl_description',
								'compare' => 'LIKE',
								'value' => $value,
							);
						}
						break;
					case 'replacement':
						if ( 'all' !== $value ) {
							if ( 'yes' === $value ) {
								$args['meta_query'][] = array(
									'key' => 'eafl_replacement',
									'compare' => 'EXISTS',
								);
							} else {
								$args['meta_query'][] = array(
									'key' => 'eafl_replacement',
									'compare' => 'NOT EXISTS',
								);
							}
						}
						break;
					case 'shortlink':
						$args['eafl_search_slug'] = $value;
						break;
					case 'text':
						if ( $value ) {
							$args['meta_query'][] = array(
								'key' => 'eafl_text',
								'compare' => 'LIKE',
								'value' => $value,
							);
						}
						break;
					case 'cloak':
						if ( 'all' !== $value ) {
							$args['meta_query'][] = array(
								'key' => 'eafl_cloak',
								'compare' => '=',
								'value' => $value,
							);
						}
						break;
					case 'target':
						if ( 'all' !== $value ) {
							$args['meta_query'][] = array(
								'key' => 'eafl_target',
								'compare' => '=',
								'value' => $value,
							);
						}
						break;
					case 'redirect_type':
						if ( 'all' !== $value ) {
							$args['meta_query'][] = array(
								'key' => 'eafl_redirect_type',
								'compare' => '=',
								'value' => $value,
							);
						}
						break;
					case 'nofollow':
						if ( 'all' !== $value ) {
							$args['meta_query'][] = array(
								'key' => 'eafl_nofollow',
								'compare' => '=',
								'value' => $value,
							);
						}
						break;
					case 'sponsored':
						if ( 'all' !== $value ) {
							if ( '1' === $value ) {
								$args['meta_query'][] = array(
									'key' => 'eafl_sponsored',
									'compare' => '=',
									'value' => '1',
								);
							} else {
								$args['meta_query'][] = array(
									'key' => 'eafl_sponsored',
									'compare' => '!=',
									'value' => '1',
								);
							}
						}
						break;
					case 'ugc':
						if ( 'all' !== $value ) {
							if ( '1' === $value ) {
								$args['meta_query'][] = array(
									'key' => 'eafl_ugc',
									'compare' => '=',
									'value' => '1',
								);
							} else {
								$args['meta_query'][] = array(
									'key' => 'eafl_ugc',
									'compare' => '!=',
									'value' => '1',
								);
							}
						}
						break;
					case 'clicks':
						if ( $value ) {
							$args['meta_query'][] = array(
								'key' => 'eafl_clicks_total',
								'compare' => 'LIKE',
								'value' => $value,
							);
						}
						break;
					case 'url':
						if ( $value ) {
							$args['meta_query'][] = array(
								'key' => 'eafl_url',
								'compare' => 'LIKE',
								'value' => $value,
							);
						}
						break;
					case 'html':
						if ( $value ) {
							$args['meta_query'][] = array(
								'key' => 'eafl_html',
								'compare' => 'LIKE',
								'value' => $value,
							);
						}
						break;
					case 'conditional':
						if ( $value ) {
							$args['meta_query'][] = array(
								'key' => 'eafl_conditional',
								'compare' => 'LIKE',
								'value' => $value,
							);
						}
						break;
					case 'categories':
						if ( 'all' !== $value ) {
							if ( 'none' === $value ) {
								$args['tax_query'][] = array(
									'taxonomy' => 'eafl_category',
									'operator' => 'NOT EXISTS',
								);
							} elseif ( 'any' === $value ) {
								$args['tax_query'][] = array(
									'taxonomy' => 'eafl_category',
									'operator' => 'EXISTS',
								);
							} else {
								$args['tax_query'][] = array(
									'taxonomy' => 'eafl_category',
									'field' => 'term_id',
									'terms' => intval( $value ),
								);
							}
						}
						break;
					case 'status':
						if ( 'all' !== $value ) {
							if ( 'all-good' === $value ) {
								$args['meta_query'][] = array(
									'key' => 'eafl_status_type',
									'compare' => 'IN',
									'value' => array( 'ok', 'redirect-ok' ),
								);
							} elseif ( 'all-bad' === $value ) {
								$args['meta_query'][] = array(
									'key' => 'eafl_status_type',
									'compare' => 'NOT IN',
									'value' => array( 'unknown', 'ok', 'redirect-ok' ),
								);
							} else { 
								$args['meta_query'][] = array(
									'key' => 'eafl_status_type',
									'compare' => '=',
									'value' => $value,
								);
							}
						}
						break;
					case 'status_ignore':
						if ( 'all' !== $value ) {
							if ( '1' === $value ) {
								$args['meta_query'][] = array(
									'key' => 'eafl_status_ignore',
									'compare' => '=',
									'value' => '1',
								);
							} else {
								$args['meta_query'][] = array(
									'key' => 'eafl_status_ignore',
									'compare' => '!=',
									'value' => '1',
								);
							}
						}
						break;
					case 'wpupg_custom_image_id':
						if ( 'all' !== $value ) {
							if ( 'yes' === $value ) {
								$args['meta_query'][] = array(
									'key' => 'wpupg_custom_image_id',
									'compare' => 'EXISTS',
								);
							} else {
								$args['meta_query'][] = array(
									'key' => 'wpupg_custom_image_id',
									'compare' => 'NOT EXISTS',
								);
							}
						}
						break;
				}
			}
		}

		add_filter( 'posts_where', array( __CLASS__, 'api_manage_links_query_where' ), 10, 2 );
		$query = new WP_Query( $args );
		remove_filter( 'posts_where', array( __CLASS__, 'api_manage_links_query_where' ), 10, 2 );

		$links = array();
		$posts = $query->posts;
		foreach ( $posts as $post ) {
			$link = EAFL_Link_Manager::get_link( $post );

			if ( ! $link ) {
				continue;
			}

			$row = $link->get_data_manage();

			// WP Ultimate Post Grid integration.
			$row['wpupg_custom_image_id'] = get_post_meta( $row['id'], 'wpupg_custom_image_id', true );
			$row['wpupg_custom_image_url'] = '';

			if ( $row['wpupg_custom_image_id'] ) {
				$thumb = wp_get_attachment_image_src( $row['wpupg_custom_image_id'], array( 150, 999 ) );

				if ( $thumb && isset( $thumb[0] ) ) {
					$row['wpupg_custom_image_url'] = $thumb[0];
				}
			}

			$links[] = $row;
		}

		// Got total number of links.
		$total = (array) wp_count_posts( EAFL_POST_TYPE );
		unset( $total['trash'] );

		return array(
			'rows' => array_values( $links ),
			'total' => array_sum( $total ),
			'filtered' => intval( $query->found_posts ),
			'pages' => ceil( $query->found_posts / $page_size ),
		);
	}

	/**
	 * Filter the where links query.
	 *
	 * @since    3.0.0
	 */
	public static function api_manage_links_query_where( $where, $wp_query ) {
		global $wpdb;

		$id_search = $wp_query->get( 'eafl_search_id' );
		if ( $id_search ) {
			$where .= ' AND ' . $wpdb->posts . '.ID LIKE \'%' . esc_sql( like_escape( $id_search ) ) . '%\'';
		}

		$date_search = $wp_query->get( 'eafl_search_date' );
		if ( $date_search ) {
			$where .= ' AND ' . $wpdb->posts . '.post_date LIKE \'%' . esc_sql( like_escape( $date_search ) ) . '%\'';
		}

		$title_search = $wp_query->get( 'eafl_search_title' );
		if ( $title_search ) {
			$where .= ' AND ' . $wpdb->posts . '.post_title LIKE \'%' . esc_sql( like_escape( $title_search ) ) . '%\'';
		}

		$slug_search = $wp_query->get( 'eafl_search_slug' );
		if ( $slug_search ) {
			$where .= ' AND ' . $wpdb->posts . '.post_name LIKE \'%' . esc_sql( like_escape( $slug_search ) ) . '%\'';
		}

		return $where;
	}

	/**
	 * Handle link bulk edit call to the REST API.
	 *
	 * @since    3.0.0
	 * @param    WP_REST_Request $request Current request.
	 */
	public static function api_manage_links_bulk_edit( $request ) {
		// Parameters.
		$params = $request->get_params();

		$ids = isset( $params['ids'] ) ? array_map( 'intval', $params['ids'] ) : array();
		$action = isset( $params['action'] ) ? $params['action'] : false;

		if ( $ids && $action && $action['type'] ) {
			// Do once.
			if ( 'update-status' === $action['type'] ) {
				if ( EAFL_Addons::is_active( 'premium' ) ) {
					return EAFLPLC_Checker::check_links( $ids );
				} else {
					return false;
				}
			}

			// Do per post.
			$args = array(
				'post_type' => EAFL_POST_TYPE,
				'post_status' => 'any',
				'nopaging' => true,
				'post__in' => $ids,
				'ignore_sticky_posts' => true,
			);

			$query = new WP_Query( $args );
			$posts = $query->posts;
			foreach ( $posts as $post ) {
				$link = EAFL_Link_Manager::get_link( $post->ID );
				$link_data = $link->get_data();

				switch ( $action['type'] ) {
					case 'remove-categories':
						$remove_categories = array_map( function( $category ) {
							$term_id = intval( $category['term_id'] );

							if ( 0 === $term_id ) {
								$term = get_term_by( 'name', $category['term_id'], 'eafl_category' );

								if ( $term && ! is_wp_error( $term ) ) {
									$term_id = $term->term_id;
								}
							}

							return $term_id;
						}, $action['options'] );

						$new_categories = array_filter( $link_data['categories'], function( $category ) use ( $remove_categories ) {
							return ! in_array( $category->term_id, $remove_categories );
						});

						if ( count( $new_categories ) !== count( $link_data['categories'] ) ) {
							$link_data['categories'] = $new_categories;
						} else {
							$link_data = false;
						}
						break;
					case 'add-categories':
						$link_data['categories'] = array_merge( $link_data['categories'], $action['options'] );
						break;
					case 'change-cloaking':
						$link_data['cloak'] = $action['options'];
						break;
					case 'change-target':
						$link_data['target'] = $action['options'];
						break;
					case 'change-redirect-type':
						$link_data['redirect_type'] = $action['options'];
						break;
					case 'change-nofollow':
						$link_data['nofollow'] = $action['options'];
						break;
					case 'change-sponsored':
						$link_data['sponsored'] = $action['options'];
						break;
					case 'change-ugc':
						$link_data['ugc'] = $action['options'];
						break;
					case 'change-status-ignore':
						$link_data['status_ignore'] = $action['options'];
						break;
					case 'reset-clicks':
						$link_data = false;
						EAFL_Clicks_Database::delete_clicks_for( $link->id() );
						break;
					case 'delete':
						$link_data = false;
						wp_trash_post( $link->id() );
						break;
				}

				if ( $link_data ) {
					$link_data = EAFL_Link_Sanitizer::sanitize( $link_data );
					EAFL_Link_Saver::update_link( $link->id(), $link_data );
				}
			}

			return true;
		}

		return false;
	}
}

EAFL_API_Manage_Links::init();
