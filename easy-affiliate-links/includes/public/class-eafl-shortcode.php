<?php
/**
 * Handle the link shortcode.
 *
 * @link       https://bootstrapped.ventures
 * @since      2.0.0
 *
 * @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/includes/public
 */

/**
 * Handle the link shortcode.
 *
 * @since      2.0.0
 * @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/includes/public
 * @author     Brecht Vandersmissen <brecht@bootstrapped.ventures>
 */
class EAFL_Shortcode {

	/**
	 * Register actions and filters.
	 *
	 * @since    2.0.0
	 */
	public static function init() {
		add_filter( 'the_content', array( __CLASS__, 'replace_link_with_shortcode' ), 999 );
		// add_filter( 'the_content', array( __CLASS__, 'replace_shortcode_with_link' ), 1 );
		// add_filter( 'content_edit_pre', array( __CLASS__, 'replace_shortcode_with_link' ) );
		add_filter( 'rest_prepare_post', array( __CLASS__, 'replace_shortcode_with_link_rest_api' ), 10, 3 );
		add_filter( 'rest_prepare_page', array( __CLASS__, 'replace_shortcode_with_link_rest_api' ), 10, 3 );

		add_shortcode( 'eafl', array( __CLASS__, 'link_shortcode' ) );
	}

	/**
	 * Get the regex pattern to find affiliate links.
	 *
	 * @since    3.0.0
	 */
	public static function get_link_regex() {
		return '/<a[^>]*?data-eafl-id="(\d+)".*?>(.*?)<\/a>/msi';
	}

	/**
	 * Replace link with the shortcode.
	 *
	 * @since	3.0.0
	 * @param	mixed   $content Content we want to filter before it gets passed along.
	 * @param	boolean $output_shortcode Wether or not to output the shortcode after replacing.
	 */
	public static function replace_link_with_shortcode( $content, $output_shortcode = true ) {
		if ( ! is_feed() ) {
			preg_match_all( self::get_link_regex(), $content, $matches );
			foreach ( $matches[0] as $key => $link ) {
				// This shortcode has already been parsed and replaced with a link.
				if ( false !== strpos( $link, 'data-eafl-parsed="1"' ) ) {
					continue;
				}

				$id = $matches[1][ $key ];
				$text = $matches[2][ $key ];

				// Use surrounding shortcode to prevent quotes in $text from breaking.
				$shortcode = '[eafl id="' . $id . '"]' . $text . '[/eafl]';

				if ( $output_shortcode ) {
					$shortcode = do_shortcode( $shortcode );
				}

				$content = str_replace( $link, $shortcode, $content );
			}
		}

		return $content;
	}

	/**
	 * Replace shortcode with actual link.
	 *
	 * @since	3.0.0
	 * @param	mixed $content Content we want to filter before it gets passed along.
	 */
	public static function replace_shortcode_with_link( $content ) {
		$link_shortcode = array();
		$pattern = get_shortcode_regex( array( 'eafl' ) );

		if ( preg_match_all( '/' . $pattern . '/s', $content, $matches ) && array_key_exists( 2, $matches ) ) {
			foreach ( $matches[2] as $key => $value ) {
				if ( 'eafl' === $value ) {
					$shortcode = $matches[0][ $key ];
					$content = str_replace( $shortcode, do_shortcode( $shortcode ), $content );
				}
			}
		}

		return $content;
	}

	/**
	 * Replace shortcode with actual link in the rest API.
	 *
	 * @since	3.0.0
	 * @param WP_REST_Response $response The response object.
	 * @param WP_Post          $post     Post object.
	 * @param WP_REST_Request  $request  Request object.
	 */
	public static function replace_shortcode_with_link_rest_api( $response, $post, $request ) {
		$params = $request->get_params();

		if ( isset( $params['context'] ) && 'edit' === $params['context'] ) {
			if ( isset( $response->data['content']['raw'] ) ) {
				$response->data['content']['raw'] = self::replace_shortcode_with_link( $response->data['content']['raw'] );
			}
		}
		return $response;
	}

	/**
	 * Output for the link shortcode.
	 *
	 * @since    2.0.0
	 * @param	 array $atts Options passed along with the shortcode.
	 */
	public static function link_shortcode( $atts, $content ) {
		$atts = shortcode_atts( array(
			'id' => false,
			'text' => false,
		), $atts, 'eafl_link' );

		$output = '';
		$id = intval( $atts['id'] );

		if ( $id ) {
			$link = EAFL_Link_Manager::get_link( $id );
			$link = apply_filters( 'eafl_shortcode_link', $link, $id );

			if ( $link ) {
				$classes = array(
					'eafl-link',
					'eafl-link-' . $link->type(),
				);

				if ( 'html' === $link->type() ) {
					$html = trim( $link->html() );

					if ( $html ) {
						$output = '<span data-eafl-id="' . $link->ID() . '" data-eafl-parsed="1" class="' . esc_attr( implode( ' ', $classes ) ) . '" style="display: inline-block;">' . $html . '</span>';
						$output = apply_filters( 'eafl_link_shortcode', $output, $link, $html );
					}
				} else {
					// Cloaked or direct link class.
					if ( 'no' === $link->cloak() ) {
						$url = $link->url();
						$classes[] = 'eafl-link-direct';
					} else {
						$url = get_permalink( $link->ID() );
						$classes[] = 'eafl-link-cloaked';
					}

					// Optional additional classes.
					$custom_classes = trim( $link->classes() );

					if ( $custom_classes ) {
						$classes[] = $custom_classes;
					}

					// Get rel attribute options.
					$rel_options = array();

					if ( 'nofollow' === $link->nofollow() ) {
						$rel_options[] = 'nofollow';
					}
					if ( EAFL_Settings::get( 'use_noopener' ) ) {
						$rel_options[] = 'noopener';
					}
					if ( EAFL_Settings::get( 'use_noreferrer' ) ) {
						$rel_options[] = 'noreferrer';
					}
					if ( $link->sponsored() ) {
						$rel_options[] = 'sponsored';
					}
					if ( $link->ugc() ) {
						$rel_options[] = 'ugc';
					}

					$rel = $rel_options ? ' rel="' . esc_attr( implode( ' ', $rel_options ) ) . '"' : '';

					// Get link text.
					$text = $link->text();
					$text = $atts['text'] ? wp_kses_post( $atts['text'] ) : $text[0];

					// If link is in surrounding mode, use content inside.
					if ( $content ) {
						$text = $content;
					}

					// If there isn't actually a destination, just return the text.
					if ( '' === trim( $link->url() ) ) {
						$output = $text;
					} else {
						$output = '<a href="' . esc_attr( $url ) . '" data-eafl-id="' . $link->ID() . '" data-eafl-parsed="1" class="' . esc_attr( implode( ' ', $classes ) ) . '" target="' . esc_attr( $link->target() ) . '"' . $rel . '>' . $text . '</a>';
					}

					$output = apply_filters( 'eafl_link_shortcode', $output, $link, $text );
				}
			}
		}

		return $output;
	}
}

EAFL_Shortcode::init();
