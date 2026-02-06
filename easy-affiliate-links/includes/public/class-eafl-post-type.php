<?php
/**
 * Register the Link post type.
 *
 * @link       https://bootstrapped.ventures
 * @since      2.0.0
 *
 * @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/includes/public
 */

/**
 * Register the Link post type.
 *
 * @since      2.0.0
 * @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/includes/public
 * @author     Brecht Vandersmissen <brecht@bootstrapped.ventures>
 */
class EAFL_Post_Type {

	/**
	 * Register actions and filters.
	 *
	 * @since    2.0.0
	 */
	public static function init() {
		add_action( 'init', array( __CLASS__, 'register_post_type' ), 1 );
		add_action( 'init', array( __CLASS__, 'maybe_exclude_from_link_query' ) );
	}

	/**
	 * Register the Link post type.
	 *
	 * @since    2.0.0
	 */
	public static function register_post_type() {
		$labels = array(
			'name'               => _x( 'Affiliate Links', 'post type general name', 'easy-affiliate-links' ),
			'singular_name'      => _x( 'Affiliate Link', 'post type singular name', 'easy-affiliate-links' ),
		);

		$args = apply_filters( 'eafl_register_post_type', array(
			'labels' => $labels,
			'public' => true,
	        'exclude_from_search' => true,
			'show_ui' => false,
			'has_archive' => false,
			'rewrite' => array(
				'slug' => EAFL_Settings::get( 'shortlink_slug' ),
			),
			'show_in_rest' => true,
			'rest_base' => EAFL_POST_TYPE,
			'rest_controller_class' => 'WP_REST_Posts_Controller',
		));

		register_post_type( EAFL_POST_TYPE, $args );
	}

	/**
	 * Maybe exclude affiliate links from WordPress link query.
	 *
	 * @since    3.8.0
	 */
	public static function maybe_exclude_from_link_query() {
		if ( EAFL_Settings::get( 'exclude_affiliate_links' ) ) {
			// For Classic Editor
			add_filter( 'wp_link_query_args', array( __CLASS__, 'exclude_from_link_query' ) );
			// For Gutenberg Editor - only for link dialog searches
			add_filter( 'rest_post_search_query', array( __CLASS__, 'exclude_from_search_query' ), 10, 2 );
		}
	}

	/**
	 * Exclude affiliate links from WordPress link query (Classic Editor).
	 *
	 * @since    3.8.0
	 * @param    array $query The link query arguments.
	 */
	public static function exclude_from_link_query( $query ) {
		if ( isset( $query['post_type'] ) && is_array( $query['post_type'] ) ) {
			$query['post_type'] = array_diff( (array) $query['post_type'], array( EAFL_POST_TYPE ) );
		}

		return $query;
	}

	/**
	 * Exclude affiliate links from REST API search query (Gutenberg Editor - Link Dialog only).
	 *
	 * @since    3.8.0
	 * @param    array            $args    The query arguments.
	 * @param    WP_REST_Request  $request The request object.
	 */
	public static function exclude_from_search_query( $args, $request ) {
		// Only filter searches that are likely from the link dialog
		$type = $request->get_param( 'type' );
		$context = $request->get_param( 'context' );
		
		// Link dialog searches have type='post' and context='view'
		if ( $type === 'post' && $context === 'view' ) {
			// Exclude EAFL post type from the search
			if ( isset( $args['post_type'] ) && is_array( $args['post_type'] ) ) {
				$args['post_type'] = array_diff( $args['post_type'], array( EAFL_POST_TYPE ) );
			}
		}

		return $args;
	}
}

EAFL_Post_Type::init();
