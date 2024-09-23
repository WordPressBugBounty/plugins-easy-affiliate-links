<?php
/**
 * Handle the export XML page.
 *
 * @link       https://bootstrapped.ventures
 * @since      3.0.0
 *
 * @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/includes/admin/import-export
 */

/**
 * Handle the export XML page.
 *
 * @since      3.0.0
 * @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/includes/admin/import-export
 * @author     Brecht Vandersmissen <brecht@bootstrapped.ventures>
 */
class EAFL_Export_XML {

	/**
	 * Register actions and filters.
	 *
	 * @since    3.0.0
	 */
	public static function init() {
		add_filter( 'eafl_import_export_tabs', array( __CLASS__, 'tabs' ), 20 );
		add_action( 'eafl_import_export_page', array( __CLASS__, 'page' ) );
	}

	/**
	 * Add export XML tab.
	 *
	 * @since    3.0.0
	 * @param 	 array $tabs Current tabs.
	 */
	public static function tabs( $tabs ) {
		if ( current_user_can( EAFL_Settings::get( 'import_capability' ) ) ) {
			$tabs['export_xml'] = __( 'Export XML', 'easy-affiliate-links' );
		}

		return $tabs;
	}

	/**
	 * Output export XML page.
	 *
	 * @since    3.0.0
	 * @param	 mixed $sub Tab to display.
	 */
	public static function page( $sub ) {
		if ( 'export_xml' === $sub && current_user_can( EAFL_Settings::get( 'import_capability' ) ) ) {
			$links = EAFL_Link_Manager::get_links();

			if ( 0 === count( $links ) ) {
				esc_html_e( 'There are no links to export.', 'easy-affiliate-links' );
			} else {
				$xml_data = array(
					'name' => 'links',
				);

				foreach ( $links as $link ) {
					$xml_data[] = self::export_xml_link( $link );
				}

				$doc = new DOMDocument();
				$child = self::generate_xml_element( $doc, $xml_data );
				if ( $child ) {
					$doc->appendChild( $child );
				}
				$doc->formatOutput = true; // Add whitespace to make easier to read XML.
				$xml = $doc->saveXML();

				echo '<form id="exportLinks" action="' . EAFL_URL . 'templates/admin/menu/import-export/export-xml.php" method="post">';
				echo '<input type="hidden" name="exportLinks" value="' . base64_encode( $xml ) . '"/>';
				submit_button( __( 'Download XML', 'easy-affiliate-links' ) );
				echo '</form>';
			}
		}
	}

	/**
	 * Export a single link to XML.
	 *
	 * @since    3.0.0
	 * @param	 mixed $link Link to export to XML.
	 */
	public static function export_xml_link( $link ) {
		$xml = array(
			'name' => 'link',
			'attributes' => array(
				'name' => isset( $link['name'] ) ? $link['name'] : '',
				'description' => isset( $link['description'] )   ? $link['description'] : '',
				'text' => isset( $link['text'] ) ? $link['text'][0] : '',
				'url' => isset( $link['url'] ) ? $link['url'] : '',
				'slug' => isset( $link['slug'] ) ? $link['slug'] : '',
			),
		);

		return $xml;
	}

	/**
	 * Generate an XML element.
	 *
	 * @since    3.0.0
	 * @param	 mixed $dom  Dom element.
	 * @param	 mixed $data XML data.
	 */
	private static function generate_xml_element( $dom, $data ) {
		if ( empty( $data['name'] ) ) {
			return false;
		}

		// Create the element.
		$element_value = ( ! empty( $data['value'] ) ) ? $data['value'] : null;
		$element = $dom->createElement( $data['name'] );
		$element->appendChild( $dom->createTextNode( $element_value ) );

		// Add any attributes.
		if ( ! empty( $data['attributes'] ) && is_array( $data['attributes'] ) ) {
			foreach ( $data['attributes'] as $attribute_key => $attribute_value ) {
				$element->setAttribute( $attribute_key, $attribute_value );
			}
		}

		// Any other items in the data array should be child elements.
		foreach ( $data as $data_key => $child_data ) {
			if ( ! is_numeric( $data_key ) ) {
				continue;
			}

			$child = self::generate_xml_element( $dom, $child_data );
			if ( $child ) {
				$element->appendChild( $child );
			}
		}

		return $element;
	}
}

EAFL_Export_XML::init();
