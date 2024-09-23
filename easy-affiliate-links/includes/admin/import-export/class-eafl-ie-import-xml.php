<?php
/**
 * Handle the import XML page.
 *
 * @link       https://bootstrapped.ventures
 * @since      3.0.0
 *
 * @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/includes/admin/import-export
 */

/**
 * Handle the import XML page.
 *
 * @since      3.0.0
 * @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/includes/admin/import-export
 * @author     Brecht Vandersmissen <brecht@bootstrapped.ventures>
 */
class EAFL_Import_XML {

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
	 * Add import to the import & export tabs.
	 *
	 * @since    3.0.0
	 * @param 	 array $tabs Current tabs.
	 */
	public static function tabs( $tabs ) {
		if ( current_user_can( EAFL_Settings::get( 'import_capability' ) ) ) {
			$tabs['import_xml'] = __( 'Import XML', 'easy-affiliate-links' );
		}

		return $tabs;
	}

	/**
	 * Output import XML page.
	 *
	 * @since    3.0.0
	 * @param	 mixed $sub Tab to display.
	 */
	public static function page( $sub ) {
		if ( 'import_xml' === $sub && current_user_can( EAFL_Settings::get( 'import_capability' ) ) ) {

			if ( isset( $_POST['eafl_import'] ) && wp_verify_nonce( $_POST['eafl_import'], 'eafl_import' ) ) { // Input var okay.
				$filename = $_FILES['xml']['tmp_name'];
				if ( $filename ) {
					$links = simplexml_load_file( $filename );

					echo '<p>Links Imported:</p>';

					$i = 1;
					foreach ( $links as $link ) {
						self::import_xml_link( $link, $i );
						$i++;
					}

					if ( $i == 1 ) {
						echo '<p>No links found</p>';
					}
				} else {
					echo '<p>No file selected</p>';
				}
			} else {
				require_once( EAFL_DIR . 'templates/admin/menu/import-export/import-xml.php' );
			}
		}
	}

	/**
	 * Import a single link from XML.
	 *
	 * @since    3.0.0
	 * @param	 mixed $xml_link    Link to import from XML.
	 * @param	 int   $link_number Number of the link we're importing.
	 */
	public static function import_xml_link( $xml_link, $link_number ) {
		$name = isset( $xml_link->attributes()->name ) ? trim( (string) $xml_link->attributes()->name ) : '';
		$description = isset( $xml_link->attributes()->description ) ? trim( (string) $xml_link->attributes()->description ) : '';
		$text = isset( $xml_link->attributes()->text ) ? trim( (string) $xml_link->attributes()->text ) : '';
		$url = isset( $xml_link->attributes()->url ) ? trim( (string) $xml_link->attributes()->url ) : '';
		$slug = isset( $xml_link->attributes()->slug ) ? trim( (string) $xml_link->attributes()->slug ) : '';

		$link = array();

		$link['name'] = $name;
		$link['description'] = $description;
		$link['text'] = array( $text );
		$link['url'] = $url;
		$link['slug'] = $slug;

		$link_id = EAFL_Link_Saver::create_link( $link );

		echo esc_html( $link_number ) . '. ' . esc_html( $name ) . '<br/>';
	}
}

EAFL_Import_XML::init();
