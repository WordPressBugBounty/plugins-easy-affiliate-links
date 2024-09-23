<?php
/**
 * Register the Link taxonomies.
 *
 * @link       https://bootstrapped.ventures
 * @since      2.0.0
 *
 * @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/includes/public
 */

/**
 * Register the Link taxonomies.
 *
 * @since      2.0.0
 * @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/includes/public
 * @author     Brecht Vandersmissen <brecht@bootstrapped.ventures>
 */
class EAFL_Taxonomies {

	/**
	 * Register actions and filters.
	 *
	 * @since    2.0.0
	 */
	public static function init() {
		add_action( 'init', array( __CLASS__, 'register_taxonomies' ), 1 );
	}

	/**
	 * Register the Link taxonomies.
	 *
	 * @since    2.0.0
	 */
	public static function register_taxonomies() {
		$labels = array(
			'name'               => _x( 'Link Categories', 'link category general name', 'easy-affiliate-links' ),
			'singular_name'      => _x( 'Link Category', 'link categories singular name', 'easy-affiliate-links' ),
		);

		$args = apply_filters( 'eafl_register_link_category', array(
			'labels' => $labels,
			'hierarchical' => true,
			'public' => false,
			'show_ui' => false,
			'query_var' => false,
			'rewrite' => false,
			'show_in_rest' => true,
		));

		register_taxonomy( 'eafl_category', EAFL_POST_TYPE, $args );
	}
}

EAFL_Taxonomies::init();
