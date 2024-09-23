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
}

EAFL_Post_Type::init();
