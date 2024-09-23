<?php
/**
 * Handle the manage export CSV page.
 *
 * @link       https://bootstrapped.ventures
 * @since      3.6.0
 *
 * @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/includes/admin/import-export
 */

/**
 * Handle the manage export CSV page.
 *
 * @since      3.6.0
 * @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/includes/admin/import-export
 * @author     Brecht Vandersmissen <brecht@bootstrapped.ventures>
 */
class EAFL_Export_CSV {

	/**
	 * Register actions and filters.
	 *
	 * @since    3.6.0
	 */
	public static function init() {
		add_filter( 'eafl_import_export_tabs', array( __CLASS__, 'tabs' ), 20 );
		add_action( 'eafl_import_export_page', array( __CLASS__, 'page' ) );
	}

	/**
	 * Add import XML to the tabs.
	 *
	 * @since    3.6.0
	 * @param 	 array $tabs Current tabs.
	 */
	public static function tabs( $tabs ) {
		if ( current_user_can( EAFL_Settings::get( 'import_capability' ) ) ) {
			$tabs['export_csv'] = __( 'Export CSV', 'easy-affiliate-links' );
		}

		return $tabs;
	}

	/**
	 * Output import page.
	 *
	 * @since    3.6.0
	 * @param	 mixed $sub Sub manage page to display.
	 */
	public static function page( $sub ) {
		if ( 'export_csv' === $sub && current_user_can( EAFL_Settings::get( 'import_capability' ) ) ) {
			$links = EAFL_Link_Manager::get_links();

			if ( 0 === count( $links ) ) {
				esc_html_e( 'There are no links to export.', 'easy-affiliate-links' );
			} else {
				$links_data = array();

				foreach ( $links as $link ) {
					$links_data[] = self::export_csv_link( $link );
				}

				// Generate CSV.
				ob_start();
				$df = fopen("php://output", 'w');
				fprintf($df, chr(0xEF).chr(0xBB).chr(0xBF)); // Support unicode.
				fputcsv($df, array_keys(reset($links_data)));
				foreach ($links_data as $row) {
					fputcsv($df, $row);
				}
				fclose($df);
				$csv = ob_get_clean();

				echo '<form id="exportLinks" action="' . EAFL_URL . 'templates/admin/menu/import-export/export-csv.php" method="post">';
				echo '<input type="hidden" name="exportLinks" value="' . base64_encode( $csv ) . '"/>';
				submit_button( __( 'Download CSV', 'easy-affiliate-links' ) );
				echo '</form>';
			}
		}
	}

	/**
	 * Export a single link to CSV.
	 *
	 * @since    3.6.0
	 * @param	 mixed $link	Link to export.
	 */
	public static function export_csv_link( $link ) {
		// Flatten categories array.
		$categories = '';
		if ( isset( $link['categories'] ) ) {
			// Use | (html &#124;) to split categories.
			$sanitized_category_names = array_map( function( $term ) {
				return str_replace( '|', '&#124;', $term->name );
			}, $link['categories'] );

			$categories = implode( '|', $sanitized_category_names );
		}

		// Construct CSV rows.
		$csv = array(
			'id' => $link['id'],
			'name' => isset( $link['name'] ) ? $link['name'] : '',
			'description' => isset( $link['description'] ) ? $link['description'] : '',
			'categories' => $categories,
			'text' => isset( $link['text'] ) ? $link['text'][0] : '',
			'url' => isset( $link['url'] ) ? $link['url'] : '',
			'slug' => isset( $link['slug'] ) ? $link['slug'] : '',
		);

		return $csv;
	}
}

EAFL_Export_CSV::init();
