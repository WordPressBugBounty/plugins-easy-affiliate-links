<?php
/**
 * Responsible for handling compatibility with other plugins/themes.
 *
 * @link       https://bootstrapped.ventures
 * @since      3.5.0
 *
 * @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/includes/public
 */

/**
 * Responsible for handling compatibility with other plugins/themes.
 *
 * @since      3.5.0
 * @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/includes/public
 * @author     Brecht Vandersmissen <brecht@bootstrapped.ventures>
 */
class EAFL_Compatibility {
	/**
	 * Register actions and filters.
	 *
	 * @since    3.5.0
	 */
	public static function init() {
		add_filter( 'wp_sitemaps_post_types', array( __CLASS__, 'wp_sitemaps_post_types' ) );
		add_filter( 'wpseo_sitemap_exclude_post_type', array( __CLASS__, 'wpseo_sitemap_exclude_post_type' ), 10, 2 );

		add_filter( 'wpupg_output_item_classes', array( __CLASS__, 'wpupg_output_item_classes' ), 10, 3 );
		add_filter( 'wpupg_output_item_data', array( __CLASS__, 'wpupg_output_item_data' ), 10, 3 );
		add_filter( 'wpupg_output_item_link', array( __CLASS__, 'wpupg_output_item_link' ), 10, 3 );

		add_action( 'elementor/editor/before_enqueue_scripts', array( __CLASS__, 'elementor_assets' ) );
	}

	/**
	 * No default WordPress sitemap for Affiliate Links.
	 *
	 * @since	3.6.0
	 * @param	mixed $post_types Post types to generate a sitemap for.
	 */
	public static function wp_sitemaps_post_types( $post_types ) {
		unset( $post_types[ EAFL_POST_TYPE ] );
		return $post_types;
	}

	/**
	 * No Yoast SEO sitemap for Affiliate Links.
	 *
	 * @since	3.6.0
	 * @param boolean $excluded  Whether the post type is excluded by default.
 	 * @param string  $post_type The post type to exclude.
	 */
	public static function wpseo_sitemap_exclude_post_type( $excluded, $post_type ) {
		if ( EAFL_POST_TYPE === $post_type ) {
			return true;
		}

		return $excluded;
	}

	/**
	 * Affiliate Links in WP Ultimate Post Grid.
	 *
	 * @since	3.5.0
	 * @param	mixed $classes Classes to be used for the grid item.
	 * @param	mixed $grid Grid getting output.
	 * @param	mixed $item Current item getting output.
	 */
	public static function wpupg_output_item_classes( $classes, $grid, $item ) {
		if ( is_a( $item, 'WPUPG_Item_Post' ) && EAFL_POST_TYPE === $item->post_type() ) {
			$affiliate_link = EAFL_Link_Manager::get_link( $item->id() );

			if ( $affiliate_link ) {
				if ( 'no' === $affiliate_link->cloak() ) {
					$classes[] = 'eafl-link-direct';
				}
			}
		}

		return $classes;
	}

	/**
	 * Affiliate Links in WP Ultimate Post Grid.
	 *
	 * @since	3.5.0
	 * @param	mixed $data Data to be used for the grid item.
	 * @param	mixed $grid Grid getting output.
	 * @param	mixed $item Current item getting output.
	 */
	public static function wpupg_output_item_data( $data, $grid, $item ) {
		if ( is_a( $item, 'WPUPG_Item_Post' ) && EAFL_POST_TYPE === $item->post_type() ) {
			$affiliate_link = EAFL_Link_Manager::get_link( $item->id() );

			if ( $affiliate_link ) {
				if ( 'no' === $affiliate_link->cloak() ) {
					$data['eafl-grid-id'] = $affiliate_link->id();
				}
			}
		}

		return $data;
	}

	/**
	 * Affiliate Links in WP Ultimate Post Grid.
	 *
	 * @since	3.5.0
	 * @param	mixed $link Link to be used in the grid.
	 * @param	mixed $grid Grid getting output.
	 * @param	mixed $item Current item getting output.
	 */
	public static function wpupg_output_item_link( $link, $grid, $item ) {
		if ( is_a( $item, 'WPUPG_Item_Post' ) && EAFL_POST_TYPE === $item->post_type() ) {
			$affiliate_link = EAFL_Link_Manager::get_link( $item->id() );

			if ( $affiliate_link ) {
				// No link for HTML Code affiliate links.
				if ( 'html' === $affiliate_link->type() ) {
					return false;
				}

				// Use destination URL if uncloaked link.
				if ( 'no' === $affiliate_link->cloak() ) {
					$link = $affiliate_link->url();
					// $classes[] = 'eafl-link-direct';
				}
			}
		}

		return $link;
	}

	/**
	 * Elementor Compatibility.
	 *
	 * @since	3.6.0
	 */
	public static function elementor_assets() {
		EAFL_Manage_Modal::add_modal_content();
		EAFL_Assets::enqueue_admin();
		EAFL_Manage_Modal::enqueue();

		if ( class_exists( 'EAFLP_Assets' ) ) {
			EAFLP_Assets::enqueue_admin();
		}
	}
}

EAFL_Compatibility::init();
