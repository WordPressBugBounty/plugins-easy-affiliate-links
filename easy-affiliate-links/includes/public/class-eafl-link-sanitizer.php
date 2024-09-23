<?php
/**
 * Santize link input fields.
 *
 * @link       https://bootstrapped.ventures
 * @since      2.0.0
 *
 * @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/includes/public
 */

/**
 * Santize link input fields.
 *
 * @since      2.0.0
 * @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/includes/public
 * @author     Brecht Vandersmissen <brecht@bootstrapped.ventures>
 */
class EAFL_Link_Sanitizer {

	/**
	 * Sanitize link array.
	 *
	 * @since    2.0.0
	 * @param	 array $link Array containing all link input data.
	 */
	public static function sanitize( $link ) {
		$sanitized_link = array();

		// Text fields.
		if ( isset( $link['name'] ) ) 			{ $sanitized_link['name'] = sanitize_text_field( $link['name'] ); }
		if ( isset( $link['description'] ) ) 	{ $sanitized_link['description'] = wp_kses_post( $link['description'] ); }
		if ( isset( $link['slug'] ) ) 			{ $sanitized_link['slug'] = sanitize_title( $link['slug'] ); }
		if ( isset( $link['classes'] ) ) 		{ $sanitized_link['classes'] = sanitize_text_field( $link['classes'] ); }

		// Leave these fields intact to make sure they are identical, expect for whitespace at the start.
		if ( isset( $link['url'] ) ) 			{ $sanitized_link['url'] = ltrim( $link['url'] ); }
		if ( isset( $link['html'] ) ) 			{ $sanitized_link['html'] = self::sanitize_html( $link['html'] ); }

		// Boolean fields.
		if ( isset( $link['sponsored'] ) ) 		{ $sanitized_link['sponsored'] = $link['sponsored'] ? true : false; }
		if ( isset( $link['ugc'] ) ) 			{ $sanitized_link['ugc'] = $link['ugc'] ? true : false; }
		if ( isset( $link['status_ignore'] ) ) 	{ $sanitized_link['status_ignore'] = $link['status_ignore'] ? true : false; }

		// Limited options fields.
		$options = array( 'text', 'html', 'image' );
		if ( isset( $link['type'] ) && in_array( $link['type'], $options, true ) ) {
			$sanitized_link['type'] = $link['type'];
		}

		$options = array( 'default', 'yes', 'no' );
		if ( isset( $link['cloak'] ) && in_array( $link['cloak'], $options, true ) ) {
			$sanitized_link['cloak'] = $link['cloak'];
		}

		$options = array( 'default', '_self', '_blank' );
		if ( isset( $link['target'] ) && in_array( $link['target'], $options, true ) ) {
			$sanitized_link['target'] = $link['target'];
		}

		$options = array( 'default', 'nofollow', 'follow' );
		if ( isset( $link['nofollow'] ) && in_array( $link['nofollow'], $options, true ) ) {
			$sanitized_link['nofollow'] = $link['nofollow'];
		}

		// Redirect Type.
		if ( isset( $link['redirect_type'] ) && 'default' !== $link['redirect_type'] ) {
			$link['redirect_type'] = intval( $link['redirect_type'] );
		}
		$options = array( 'default', 301, 302, 307 );
		if ( isset( $link['redirect_type'] ) && in_array( $link['redirect_type'], $options, true ) ) {
			$sanitized_link['redirect_type'] = '' . $link['redirect_type'];
		}

		// Link Tags.
		if ( isset( $link['categories'] ) ) {
			$sanitized_link['categories'] = $link['categories'] ? array_map( array( __CLASS__, 'sanitize_tags' ), $link['categories'] ) : array();
		}

		// Other Fields.
		if ( isset( $link['text'] ) ) {
			$sanitized_link['text'] = $link['text'] ? array_map( 'sanitize_text_field', $link['text'] ) : array();
			if ( 0 === count( $sanitized_link['text'] ) ) {
				$sanitized_link['text'][] = '';
			}
		}
		if ( isset( $link['replacement'] ) ) {
			$replacement = intval( $link['replacement'] );
			$sanitized_link['replacement'] = 0 < $replacement ? $replacement : false;
		}

		// Compatibility.
		if ( isset( $link['wpupg_custom_image_id'] ) ) {
			$sanitized_link['wpupg_custom_image_id'] = intval( $link['wpupg_custom_image_id'] );
		}

		return apply_filters( 'eafl_link_sanitize', $sanitized_link, $link );
	}

	/**
	 * Sanitize link tags.
	 *
	 * @since    2.0.0
	 * @param	mixed $tag Tag ID or new tag name.
	 */
	public static function sanitize_tags( $tag ) {
		if ( is_array( $tag ) || is_object( $tag ) ) {
			$tag = (array) $tag;

			if ( is_numeric( $tag['term_id'] ) ) {
				return intval( $tag['term_id'] );
			} else {
				return sanitize_text_field( $tag['term_id'] );
			}
		} elseif ( is_numeric( $tag ) ) {
			return intval( $tag );
		} else {
			return sanitize_text_field( $tag );
		}
	}

	/**
	 * Sanitize HTML content.
	 *
	 * @since   3.7.3
	 * @param	mixed $text Text to sanitize.
	 */
	public static function sanitize_html( $text ) {
		$allowed_tags = wp_kses_allowed_html( 'post' );

		// Allow administrators to use any html they want.
		if ( current_user_can( 'unfiltered_html' ) ) {
			return $text;
		}

		return wp_kses( $text, $allowed_tags );
	}
}
