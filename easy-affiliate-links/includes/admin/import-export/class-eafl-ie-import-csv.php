<?php
/**
 * Handle the manage import CSV page.
 *
 * @link       https://bootstrapped.ventures
 * @since      3.0.0
 *
 * @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/includes/admin/import-export
 */

/**
 * Handle the manage import CSV page.
 *
 * @since      3.0.0
 * @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/includes/admin/import-export
 * @author     Brecht Vandersmissen <brecht@bootstrapped.ventures>
 */
class EAFL_Import_CSV {

	/**
	 * Register actions and filters.
	 *
	 * @since    3.0.0
	 */
	public static function init() {
		add_filter( 'eafl_import_export_tabs', array( __CLASS__, 'tabs' ), 19 );
		add_action( 'eafl_import_export_page', array( __CLASS__, 'page' ) );
	}

	/**
	 * Add import XML to the tabs.
	 *
	 * @since    3.0.0
	 * @param 	 array $tabs Current tabs.
	 */
	public static function tabs( $tabs ) {
		if ( current_user_can( EAFL_Settings::get( 'import_capability' ) ) ) {
			$tabs['import_csv'] = __( 'Import CSV', 'easy-affiliate-links' );
		}

		return $tabs;
	}

	/**
	 * Output import page.
	 *
	 * @since    3.0.0
	 * @param	 mixed $sub Sub manage page to display.
	 */
	public static function page( $sub ) {
		if ( 'import_csv' === $sub && current_user_can( EAFL_Settings::get( 'import_capability' ) ) ) {

			if ( isset( $_POST['eafl_import'] ) && wp_verify_nonce( $_POST['eafl_import'], 'eafl_import' ) ) { // Input var okay.
				$filename = $_FILES['csv']['tmp_name'];

				if ( $filename ) {
					$links = array_map( 'str_getcsv', file( $_FILES['csv']['tmp_name'] ) );
					set_transient( 'eafl_import_links_csv', $links, HOUR_IN_SECONDS );
					require_once( EAFL_DIR . 'templates/admin/menu/import-export/import-csv-mapping.php' );
				} else {
					require_once( EAFL_DIR . 'templates/admin/menu/import-export/import-csv.php' );
				}
			} elseif ( isset( $_POST['eafl_import_mapping'] ) && wp_verify_nonce( $_POST['eafl_import_mapping'], 'eafl_import_mapping' ) ) { // Input var okay.
				$links = get_transient( 'eafl_import_links_csv' );

				if ( isset( $_POST['eafl_skip_first_row'] ) && $_POST['eafl_skip_first_row'] ) {
					unset( $links[0] );
				}

				if ( $links && count( $links ) ) {
					delete_transient( 'eafl_import_links_csv' );

					// Get mapping.
					$mapping = array(
						'id'          => false,
						'name'        => false,
						'description' => false,
						'categories' => false,
						'text'        => false,
						'url'         => false,
						'slug'        => false,
					);

					foreach ( $mapping as $field => $column ) {
						$field_name = 'eafl_column_' . $field;
						if ( isset( $_POST[ $field_name ] ) && '' !== $_POST[ $field_name ] ) {
							$mapping[ $field ] = intval( $_POST[ $field_name ] );
						}
					}

					echo '<p>Links Imported:</p>';

					$i = 1;
					foreach ( $links as $link ) {
						self::import_csv_link( $link, $mapping, $i );
						$i++;
					}

					if ( $i == 1 ) {
						echo '<p>No links found</p>';
					}
				} else {
					require_once( EAFL_DIR . 'templates/admin/menu/import-export/import-csv.php' );
				}
			} else {
				require_once( EAFL_DIR . 'templates/admin/menu/import-export/import-csv.php' );
			}
		}
	}

	/**
	 * Import a single link from CSV.
	 *
	 * @since    3.0.0
	 * @param	 mixed $csv_link    Link to import from CSV.
	 * @param	 mixed $mapping    	Mapping for the link import.
	 * @param	 int   $link_number Number of the link we're importing.
	 */
	public static function import_csv_link( $csv_link, $mapping, $link_number ) {
		$link = array();

		foreach ( $mapping as $field => $column ) {
			if ( false !== $column ) {
				$link[ $field ] = $csv_link[ $column ];
			}
		}

		// Get IDs from category names, if set.
		if ( isset( $link[ 'categories' ] ) ) {
			$category_ids = array();
			$category_names = explode( '|', $link[ 'categories' ] );

			foreach ( $category_names as $category_name ) {
				$name = str_replace( '&#124;', '|', $category_name );

				// Get ID from name.
				if ( $name ) {
					$term = term_exists( $name, 'eafl_category' );
		
					if ( 0 === $term || null === $term ) {
						$term = wp_insert_term( $name, 'eafl_category' );
					}
		
					if ( is_wp_error( $term ) ) {
						if ( isset( $term->error_data['term_exists'] ) ) {
							$term_id = $term->error_data['term_exists'];
						} else {
							$term_id = 0;
						}
					} else {
						$term_id = $term['term_id'];
					}
		
					$id = intval( $term_id );

					if ( $id ) {
						$category_ids[] = $id;
					}
				}
			}

			$link['categories'] = $category_ids;
		}

		// Use update instead of insert if ID is set.
		if ( isset( $link['id'] ) && $link['id'] ) {
			$link_id = intval( $link['id'] );

			if ( EAFL_POST_TYPE === get_post_type( $link_id ) ) {
				EAFL_Link_Saver::update_link( $link_id, $link );
			}
		} else {
			$link_id = EAFL_Link_Saver::create_link( $link );
		}

		echo esc_html( $link_number ) . '. ' . esc_html( $link['name'] ) . '<br/>';
	}
}

EAFL_Import_CSV::init();
