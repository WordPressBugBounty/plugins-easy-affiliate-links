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
					$csv_content = file_get_contents( $_FILES['csv']['tmp_name'] );
					
					// Detect and convert encoding to UTF-8
					$encoding = self::detect_encoding( $csv_content );
					if ( $encoding && $encoding !== 'UTF-8' ) {
						$csv_content = mb_convert_encoding( $csv_content, 'UTF-8', $encoding );
					}
					
					// Clean up any remaining invalid UTF-8 sequences
					$csv_content = mb_convert_encoding( $csv_content, 'UTF-8', 'UTF-8' );
					
					// Parse CSV lines
					$lines = explode( "\n", $csv_content );
					$links = array_map( function( $line ) { 
						return str_getcsv( $line, ',', '"', '\\' ); 
					}, $lines );
					
					// Sanitize each field to ensure valid UTF-8
					$links = array_map( function( $link ) {
						return array_map( function( $field ) {
							// Remove or replace invalid UTF-8 sequences
							$field = mb_convert_encoding( $field, 'UTF-8', 'UTF-8' );
							
							// Remove null bytes and other control characters
							$field = str_replace( array( "\0", "\x00" ), '', $field );
							
							// Remove invisible/special Unicode characters (like zero-width spaces, etc.)
							$field = preg_replace( '/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $field );
							
							// Remove other problematic Unicode characters
							$field = preg_replace( '/[\x{200B}-\x{200D}\x{FEFF}]/u', '', $field );
							
							// Trim whitespace
							$field = trim( $field );
							
							return $field;
						}, $link );
					}, $links );
					
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
			if ( false !== $column && isset( $csv_link[ $column ] ) ) {
				$link[ $field ] = $csv_link[ $column ];
			}
		}

		if ( empty( $link ) ) {
			return;
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

	/**
	 * Detect the encoding of a string.
	 *
	 * @since    3.0.0
	 * @param	 string $string String to detect encoding for.
	 * @return   string|false Detected encoding or false if detection failed.
	 */
	private static function detect_encoding( $string ) {
		// Remove BOM if present for cleaner detection
		$clean_string = $string;
		if ( substr( $string, 0, 3 ) === "\xEF\xBB\xBF" ) {
			$clean_string = substr( $string, 3 );
			return 'UTF-8';
		}
		
		// Check for UTF-16 BOM
		if ( substr( $string, 0, 2 ) === "\xFF\xFE" || substr( $string, 0, 2 ) === "\xFE\xFF" ) {
			return 'UTF-16';
		}
		
		// Try mb_detect_encoding with more encodings
		$encodings = array( 'UTF-8', 'ISO-8859-1', 'Windows-1252', 'Windows-1251', 'ISO-8859-15', 'ASCII' );
		$encoding = mb_detect_encoding( $clean_string, $encodings, true );
		
		if ( $encoding ) {
			return $encoding;
		}
		
		// Check if string is valid UTF-8
		if ( mb_check_encoding( $clean_string, 'UTF-8' ) ) {
			return 'UTF-8';
		}
		
		// Try to detect by checking for common Windows-1252 characters
		if ( preg_match( '/[\x80-\x9F]/', $clean_string ) ) {
			return 'Windows-1252';
		}
		
		// Default to UTF-8 if we can't detect
		return 'UTF-8';
	}
}

EAFL_Import_CSV::init();
