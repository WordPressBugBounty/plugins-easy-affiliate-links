<?php
/**
 * Responsible for outputting the disclaimer.
 *
 * @link       https://bootstrapped.ventures
 * @since      3.5.0
 *
 * @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/includes/public
 */

/**
 * Responsible for outputting the disclaimer.
 *
 * @since      3.5.0
 * @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/includes/public
 * @author     Brecht Vandersmissen <brecht@bootstrapped.ventures>
 */
class EAFL_Disclaimer {

	/**
	 * Register actions and filters.
	 *
	 * @since    3.5.0
	 */
	public static function init() {
		add_filter( 'eafl_link_shortcode', array( __CLASS__, 'link_shortcode' ), 10, 2 );
	}
	
	/**
	 * Filter the link shortcode output.
	 *
	 * @since    3.5.0
	 * @param    mixed $output 	Current shortcode output.
	 * @param    mixed $link 	Link getting output.
	 */
	public static function link_shortcode( $output, $link ) {
		$automatic_disclaimer = EAFL_Settings::get( 'automatic_disclaimer' );

		if ( in_array( $automatic_disclaimer, array( 'prepend', 'append' ) ) && self::should_add_disclaimer_for( $link ) ) {
			$text_style = EAFL_Settings::get( 'automatic_disclaimer_text_style' );
			$disclaimer = '<span class="eafl-disclaimer eafl-disclaimer-text eafl-disclaimer-' . $text_style . '">' . self::get_disclaimer_text() . '</span>';

			switch ( $automatic_disclaimer ) {
				case 'prepend':
					$output = $disclaimer . ' ' . $output;
					break;
				case 'append':
					$output = $output . ' ' . $disclaimer;
					break;
			}
		}

		return $output;
	}

	/**
	 * Check if we should output a disclaimer for this link.
	 *
	 * @since    3.5.0
	 * @param    mixed $link Link to check.
	 */
	public static function should_add_disclaimer_for( $link ) {
		if ( isset( $GLOBALS['wp']->query_vars['rest_route'] ) && '/wp/v2/block-renderer/easy-affiliate-links/easy-affiliate-link' === $GLOBALS['wp']->query_vars['rest_route'] ) {
			return false;
		}

		if ( 'disabled' !== EAFL_Settings::get( 'automatic_disclaimer' ) ) {
			// Check link type.
			$setting_types = EAFL_Settings::get( 'automatic_disclaimer_types' );
			if ( ! in_array( $link->type(), $setting_types ) ) {
				return false;
			}

			// Check link categories.
			if ( 'all' === EAFL_Settings::get( 'automatic_disclaimer_display' ) ) {
				return true;
			} else {
				$setting_categories = array_map( 'intval', EAFL_Settings::get( 'automatic_disclaimer_categories' ) );
				$link_categories = wp_list_pluck( $link->categories(), 'term_id' );

				switch ( EAFL_Settings::get( 'automatic_disclaimer_display' ) ) {
					case 'include':
						if ( 0 < count( array_intersect( $setting_categories, $link_categories ) ) ) {
							return true;
						}
						break;
					case 'exclude':
						if ( 0 === count( array_intersect( $setting_categories, $link_categories ) ) ) {
							return true;
						}
						break;
				}
			}
		}

		return false;
	}

	/**
	 * Get the disclaimer text.
	 *
	 * @since    3.5.0
	 */
	public static function get_disclaimer_text() {
		$disclaimer_text = EAFL_Settings::get( 'automatic_disclaimer_text' );

		$disclaimer_text = str_ireplace( '</p><p>', '<br/>', $disclaimer_text );
		$disclaimer_text = str_ireplace( '<p>', '', $disclaimer_text );
		$disclaimer_text = str_ireplace( '</p>', '', $disclaimer_text );

		return $disclaimer_text;
	}
}

EAFL_Disclaimer::init();
