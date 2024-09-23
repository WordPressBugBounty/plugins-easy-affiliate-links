<?php
/**
 * Add find link relations to the tools page.
 *
 * @link       https://bootstrapped.ventures
 * @since      3.2.0
 *
* @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/includes/admin/tools
 */

/**
 * Add find link relations to the tools page.
 *
 * @since      3.2.0
 * @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/includes/admin/tools
 * @author     Brecht Vandersmissen <brecht@bootstrapped.ventures>
 */
class EAFL_Tools_Find_Relations {

	/**
	 * Register actions and filters.
	 *
	 * @since    3.2.0
	 */
	public static function init() {
		add_action( 'admin_menu', array( __CLASS__, 'add_submenu_page' ) );
		add_action( 'wp_ajax_eafl_find_relations', array( __CLASS__, 'ajax_find_relations' ) );

		add_filter( 'eafl_tools', array( __CLASS__, 'tools' ), 8 );
	}

	/**
	 * Add tool to tools.
	 *
	 * @since    3.2.0
	 * @param	 mixed $tools Current tools.
	 */
	public static function tools( $tools ) {
		$tools['manage'] = array(
			'header' => __( 'Manage', 'easy-affiliate-links' ),
			'tools' => array(
				array(
					'id' => 'link_relations',
					'label' => __( 'Find Link Usage', 'easy-affiliate-links' ),
					'name' => __( 'Check posts &amp; pages', 'easy-affiliate-links' ),
					'description' => __( 'Search for links used in posts and pages on your website.', 'easy-affiliate-links' ) . ' ' . __( 'The post types to search can be changed on the settings page.', 'easy-affiliate-links' ),
					'url' => admin_url( 'admin.php?page=eafl_find_relations' ),
				),
			),
		);
		
		return $tools;
	}

	/**
	 * Add the tools submenu.
	 *
	 * @since	3.2.0
	 */
	public static function add_submenu_page() {
		add_submenu_page( '', __( 'Link Usage', 'easy-affiliate-links' ), __( 'Link Usage', 'easy-affiliate-links' ), EAFL_Settings::get( 'tools_capability' ), 'eafl_find_relations', array( __CLASS__, 'page_content' ) );
	}

	/**
	 * Page content to output.
	 *
	 * @since    3.2.0
	 */
	public static function page_content() {
		// Clear all current relations.
		EAFL_Relations_Database::delete_all();
		update_option( 'eafl_find_relations', time(), false );

		// Get all posts and pages.
		$args = array(
			'post_type' => EAFL_Settings::get( 'find_link_usage_post_types' ),
			'post_status' => 'all',
			'posts_per_page' => -1,
			'fields' => 'ids',
		);

		$items = get_posts( $args );

		// Only when debugging.
		if ( EAFL_Tools_Manager::$debugging ) {
			$result = self::find_relations( $items ); // Input var okay.
			var_dump( $result );
			die();
		}

		// Handle via AJAX.
		wp_localize_script( 'eafl-admin', 'eafl_tools', array(
			'action' => 'find_relations',
			'items' => $items,
			'args' => array(),
		));

		require_once( EAFL_DIR . 'templates/admin/menu/tools/find_relations.php' );
	}

	/**
	 * Check links through AJAX.
	 *
	 * @since	3.2.0
	 */
	public static function ajax_find_relations() {
		if ( check_ajax_referer( 'eafl', 'security', false ) ) {
			if ( current_user_can( EAFL_Settings::get( 'tools_capability' ) ) ) {
				$items = isset( $_POST['items'] ) ? json_decode( wp_unslash( $_POST['items'] ) ) : array(); // Input var okay.

				$items_left = array();
				$items_processed = array();

				if ( count( $items ) > 0 ) {
					$items_left = $items;
					$items_processed = array_map( 'intval', array_splice( $items_left, 0, 10 ) );

					$result = self::find_relations( $items_processed );

					if ( is_wp_error( $result ) ) {
						wp_send_json_error( array(
							'redirect' => admin_url( 'admin.php?page=eafl_tools' ),
						) );
					}
				}

				wp_send_json_success( array(
					'items_processed' => $items_processed,
					'items_left' => $items_left,
				) );
			}
		}

		wp_die();
	}

	/**
	 * Find links in posts.
	 *
	 * @since	3.2.0
	 * @param	array $post_ids IDs of posts to check.
	 */
	public static function find_relations( $post_ids ) {
		$results = array();

		foreach ( $post_ids as $post_id ) {
			$post = get_post( $post_id );

			if ( $post ) {
				self::find_link_in_post( $post );
			}
		}

		return $results;
	}

	/**
	 * Find links in a specific post.
	 *
	 * @since	3.2.0
	 * @param	mixed $post Post to check.
	 */
	public static function find_link_in_post( $post ) {
		$content = $post->post_content;
		$link_ids = array();

		// WPRM Integration.
		if ( 'wprm_recipe' === $post->post_type ) {
			// Search recipe fields.
			if ( class_exists( 'WPRM_Recipe_Manager' ) ) {
				$recipe = WPRM_Recipe_Manager::get_recipe( $post->ID );

				if ( $recipe ) {
					$instructions = $recipe->instructions_flat();

					foreach ( $instructions as $instruction ) {
						if ( isset( $instruction['text'] ) ) {
							$content .= ' ' . $instruction['text'];
						}
					}

					$ingredients = $recipe->ingredients_flat();

					foreach ( $ingredients as $ingredient ) {
						if ( isset( $ingredient['notes'] ) ) {
							$content .= ' ' . $ingredient['notes'];
						}
					}

					$content .= ' ' . $recipe->notes();
				}
			}

			// Search recipe taxonomies.
			$taxonomies = get_object_taxonomies( $post->post_type, 'objects' );
			
			foreach ( $taxonomies as $taxonomy_slug => $taxonomy ){
				$terms = get_the_terms( $post->ID, $taxonomy_slug );
		 
				if ( ! empty( $terms ) ) {
					foreach ( $terms as $term ) {
						switch ( $taxonomy_slug ) {
							case 'wprm_equipment':
								$eafl = get_term_meta( $term->term_id, 'wprmp_equipment_eafl', true );
								break;
							case 'wprm_ingredient':
								$eafl = get_term_meta( $term->term_id, 'wprmp_ingredient_eafl', true );
								break;
							default:
								$eafl = get_term_meta( $term->term_id, 'wprmp_term_eafl', true );
						}

						$id = intval( $eafl );

						if ( $id ) {
							if ( ! isset( $link_ids[ $id ] ) ) {
								$link_ids[ $id ] = 0;
							}
		
							$link_ids[ $id ]++;
						}
					}
				}
			}
		}

		// Replace any inline Gutenberg links with shortcode.
		$content = EAFL_Shortcode::replace_link_with_shortcode( $content, false );

		// Find all shortcodes in content.
		$pattern = get_shortcode_regex( array( 'eafl' ) );

		if ( preg_match_all( '/' . $pattern . '/s', $content, $matches ) && array_key_exists( 2, $matches ) ) {
			foreach ( $matches[2] as $key => $value ) {
				if ( 'eafl' === $value ) {
					$attributes = shortcode_parse_atts( stripslashes( $matches[3][ $key ] ) );
					$id = intval( $attributes['id'] );

					if ( $id ) {
						if ( ! isset( $link_ids[ $id ] ) ) {
							$link_ids[ $id ] = 0;
						}
	
						$link_ids[ $id ]++;
					}
				}
			}
		}

		foreach ( $link_ids as $link_id => $occurrences ) {
			$relation = array(
				'post_id' => $post->ID,
				'link_id' => $link_id,
				'occurrences' => $occurrences,
			);
	
			EAFL_Relations_Database::add( $relation );
		}
	}
}

EAFL_Tools_Find_Relations::init();
