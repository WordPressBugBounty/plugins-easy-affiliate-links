<?php
/**
 * Responsible for returning links.
 *
 * @link       https://bootstrapped.ventures
 * @since      2.0.0
 *
 * @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/includes/public
 */

/**
 * Responsible for returning links.
 *
 * @since      2.0.0
 * @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/includes/public
 * @author     Brecht Vandersmissen <brecht@bootstrapped.ventures>
 */
class EAFL_Link_Manager {

	/**
	 * Links that have already been requested for easy subsequent access.
	 *
	 * @since    2.0.0
	 * @access   private
	 * @var      array $links Array containing links that have already been requested for easy access.
	 */
	private static $links = array();

	/**
	 * Register actions and filters.
	 *
	 * @since    2.0.0
	 */
	public static function init() {
		add_action( 'wp_ajax_eafl_get_link', array( __CLASS__, 'ajax_get_link' ) );
		add_action( 'wp_ajax_eafl_search_links', array( __CLASS__, 'ajax_search_links' ) );
	}

	/**
	 * Get all link IDs.
	 *
	 * @since    2.1.0
	 */
	public static function get_link_ids() {
		$args = array(
			'post_type' => EAFL_POST_TYPE,
			'post_status' => 'any',
			'nopaging' => true,
			'fields' => 'ids',
		);

		$query = new WP_Query( $args );

		return $query->posts;
	}

	/**
	 * Get all link IDs in a specific category.
	 *
	 * @since    2.3.0
	 * @param    mixed $category_id Category to find the link IDs for.
	 */
	public static function get_link_ids_by_category( $category_id ) {
		$args = array(
			'post_type' => EAFL_POST_TYPE,
			'post_status' => 'any',
			'nopaging' => true,
			'fields' => 'ids',
			'tax_query' => array(
				array(
					'taxonomy' => 'eafl_category',
					'field' => 'id',
					'terms' => $category_id,
				),
			),
		);

		$query = new WP_Query( $args );

		return $query->posts;
	}

	/**
	 * Get all links. Should generally not be used.
	 *
	 * @since    2.0.0
	 */
	public static function get_links() {
		$links = array();

		$limit = 200;
		$offset = 0;

		while ( true ) {
			$args = array(
					'post_type' => EAFL_POST_TYPE,
					'post_status' => 'any',
					'orderby' => 'date',
					'order' => 'DESC',
					'posts_per_page' => $limit,
					'offset' => $offset,
			);

			$query = new WP_Query( $args );

			if ( ! $query->have_posts() ) {
				break;
			}

			$posts = $query->posts;

			foreach ( $posts as $post ) {
				$link = self::get_link( $post );

				$links[ $link->id() ] = array(
					'id' => $link->id(),
					'name' => $link->name(),
					'description' => $link->description(),
					'categories' => $link->categories(),
					'text' => $link->text(),
					'url' => $link->url(),
					'slug' => $link->slug(),
				);

				wp_cache_delete( $post->ID, 'posts' );
				wp_cache_delete( $post->ID, 'post_meta' );
			}

			$offset += $limit/2;
			wp_cache_flush();
		}

		return $links;
	}

	/**
	 * Search for links by keyword.
	 *
	 * @since    2.3.0
	 */
	public static function ajax_search_links() {
		if ( check_ajax_referer( 'eafl', 'security', false ) ) {
			$search = isset( $_POST['search'] ) ? sanitize_text_field( wp_unslash( $_POST['search'] ) ) : ''; // Input var okay.

			$links = array();
			$links_with_id = array();

			$args = array(
				'post_type' => EAFL_POST_TYPE,
				'post_status' => 'any',
				'posts_per_page' => 100,
				's' => $search,
			);

			$query = new WP_Query( $args );

			$posts = $query->posts;
			foreach ( $posts as $post ) {
				$links[] = array(
					'id' => $post->ID,
					'text' => $post->post_title,
				);

				$links_with_id[] = array(
					'id' => $post->ID,
					'text' => $post->ID . ' - ' . $post->post_title,
				);
			}

			wp_send_json_success( array(
				'links' => $links,
				'links_with_id' => $links_with_id,
			) );
		}

		wp_die();
	}

	/**
	 * Get link data by ID through AJAX.
	 *
	 * @since    2.0.0
	 */
	public static function ajax_get_link() {
		if ( check_ajax_referer( 'eafl', 'security', false ) ) {
			$link_id = isset( $_POST['link_id'] ) ? intval( $_POST['link_id'] ) : 0; // Input var okay.

			$link = self::get_link( $link_id );
			$link_data = $link ? $link->get_data() : array();

			wp_send_json_success( array(
				'link' => $link_data,
			) );
		}

		wp_die();
	}

	/**
	 * Get link object by ID.
	 *
	 * @since    2.0.0
	 * @param	 mixed $post_or_link_id ID or Post Object for the link we want.
	 */
	public static function get_link( $post_or_link_id ) {
		$link_id = is_object( $post_or_link_id ) && $post_or_link_id instanceof WP_Post ? $post_or_link_id->ID : intval( $post_or_link_id );

		// Only get new link object if it hasn't been retrieved before.
		if ( ! array_key_exists( $link_id, self::$links ) ) {
			$post = is_object( $post_or_link_id ) && $post_or_link_id instanceof WP_Post ? $post_or_link_id : get_post( intval( $post_or_link_id ) );

			if ( $post instanceof WP_Post && EAFL_POST_TYPE === $post->post_type ) {
				$link = new EAFL_Link( $post );
			} else {
				$link = false;
			}

			self::$links[ $link_id ] = $link;
		}

		return self::$links[ $link_id ];
	}

	/**
	 * Invalidate cached link.
	 *
	 * @since    2.0.0
	 * @param	 int $link_id ID of the link to invalidate.
	 */
	public static function invalidate_link( $link_id ) {
		if ( array_key_exists( $link_id, self::$links ) ) {
			unset( self::$links[ $link_id ] );
		}
	}
}

EAFL_Link_Manager::init();
