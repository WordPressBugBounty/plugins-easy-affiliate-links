<?php
/**
 * Responsible for the EAFL blocks.
 *
 * @link       https://bootstrapped.ventures
 * @since      3.4.0
 *
 * @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/includes/public
 */

/**
 * Responsible for the EAFL blocks.
 *
 * @since      3.4.0
 * @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/includes/public
 * @author     Brecht Vandersmissen <brecht@bootstrapped.ventures>
 */
class EAFL_Blocks {
	/**
	 * Register actions and filters.
	 *
	 * @since    3.4.0
	 */
	public static function init() {
		add_action( 'init', array( __CLASS__, 'register_blocks' ) );
		add_filter( 'render_block', array( __CLASS__, 'render_core_button_affiliate_link' ), 10, 2 );
	}

	/**
	 * Register all EAFL blocks.
	 *
	 * @since    3.4.0
	 */
	public static function register_blocks() {
		if ( function_exists( 'register_block_type' ) ) {
			$block_settings = array(
				'attributes' => array(
					'id' => array(
						'type' => 'string',
						'default' => '',
					),
					'type' => array(
						'type' => 'string',
						'default' => 'text',
					),
					'text' => array(
						'type' => 'string',
						'default' => '',
					),
					'textAlign' => array(
						'type' => 'string',
						'default' => 'left',
					),
					'className' => array(
						'type' => 'string',
						'default' => '',
					),
					'updated' => array(
						'type' => 'number',
						'default' => 0,
					),
				),
				'render_callback' => array( __CLASS__, 'render_easy_affilate_link_block' ),
			);
			register_block_type( 'easy-affiliate-links/easy-affiliate-link', $block_settings );
		}
	}

	/**
	 * Render the easy affiliate link block.
	 *
	 * @since	3.4.0
	 * @param	mixed $atts Block attributes.
	 */
	public static function render_easy_affilate_link_block( $atts ) {
		$output = '';
		$link_output = EAFL_Shortcode::link_shortcode( $atts, false );

		if ( $link_output ) {
			$style = '';
			$classes = array(
				'eafl-link-block',
			);

			if ( isset( $atts['className'] ) && $atts['className'] ) {
				$classes[] = esc_attr( $atts['className'] );
			}

			if ( isset( $atts['textAlign'] ) && $atts['textAlign'] ) {
				$style = 'text-align: ' . esc_attr( $atts['textAlign'] ) . ';';
			}			

			$output = '<div class="' . implode( ' ', $classes ) . '" style="' . $style . '">' . $link_output . '</div>';
		}

		return $output;
	}

	/**
	 * Render EAFL metadata stored on core Button blocks.
	 *
	 * @since	3.4.0
	 * @param	string $block_content Rendered block content.
	 * @param	array  $block         Parsed block data.
	 */
	public static function render_core_button_affiliate_link( $block_content, $block ) {
		if ( ! isset( $block['blockName'] ) || 'core/button' !== $block['blockName'] ) {
			return $block_content;
		}

		$atts = isset( $block['attrs'] ) && is_array( $block['attrs'] ) ? $block['attrs'] : array();
		$id = isset( $atts['eaflId'] ) ? intval( $atts['eaflId'] ) : 0;

		if ( ! $id ) {
			return $block_content;
		}

		$link = EAFL_Link_Manager::get_link( $id );
		$link = apply_filters( 'eafl_shortcode_link', $link, $id );

		if ( ! $link || 'trash' === $link->post_status() || 'html' === $link->type() ) {
			return $block_content;
		}

		$link_attributes = self::get_core_button_affiliate_link_attributes( $link );

		return self::replace_first_anchor_attributes(
			$block_content,
			$link_attributes['attributes'],
			$link_attributes['classes']
		);
	}

	/**
	 * Get the EAFL attributes to apply to a core Button block anchor.
	 *
	 * @since	3.4.0
	 * @param	EAFL_Link $link Link object.
	 */
	private static function get_core_button_affiliate_link_attributes( $link ) {
		$attributes = array(
			'href' => null,
			'target' => null,
			'rel' => null,
			'data-eafl-id' => null,
			'data-eafl-parsed' => null,
		);
		$classes = array();

		if ( 'no' === $link->active() || '' === trim( $link->url() ) ) {
			return array(
				'attributes' => $attributes,
				'classes' => $classes,
			);
		}

		$classes = array(
			'eafl-link',
			'eafl-link-' . $link->type(),
		);

		if ( 'no' === $link->cloak() ) {
			$url = $link->url();
			$classes[] = 'eafl-link-direct';
		} else {
			$url = get_permalink( $link->ID() );
			$classes[] = 'eafl-link-cloaked';
		}

		$custom_classes = trim( $link->classes() );
		if ( $custom_classes ) {
			$classes = array_merge( $classes, preg_split( '/\s+/', $custom_classes ) );
		}

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

		$attributes = array(
			'href' => $url,
			'target' => $link->target(),
			'rel' => $rel_options ? implode( ' ', $rel_options ) : null,
			'data-eafl-id' => $link->ID(),
			'data-eafl-parsed' => '1',
		);

		return array(
			'attributes' => $attributes,
			'classes' => array_unique( array_filter( $classes ) ),
		);
	}

	/**
	 * Replace attributes on the first anchor in a rendered block.
	 *
	 * @since	3.4.0
	 * @param	string $html       Rendered HTML.
	 * @param	array  $attributes Attributes to set. Null values remove attributes.
	 * @param	array  $classes    Classes to add.
	 */
	private static function replace_first_anchor_attributes( $html, $attributes, $classes = array() ) {
		if ( class_exists( 'WP_HTML_Tag_Processor' ) ) {
			$processor = new WP_HTML_Tag_Processor( $html );

			if ( $processor->next_tag( 'a' ) ) {
				foreach ( $classes as $class ) {
					$processor->add_class( $class );
				}

				foreach ( $attributes as $name => $value ) {
					if ( null === $value || false === $value || '' === $value ) {
						$processor->remove_attribute( $name );
					} else {
						$processor->set_attribute( $name, $value );
					}
				}

				return $processor->get_updated_html();
			}
		}

		return preg_replace_callback(
			'/<a\b[^>]*>/i',
			function( $matches ) use ( $attributes, $classes ) {
				return self::replace_anchor_tag_attributes( $matches[0], $attributes, $classes );
			},
			$html,
			1
		);
	}

	/**
	 * Replace attributes on a single anchor opening tag.
	 *
	 * @since	3.4.0
	 * @param	string $tag        Anchor opening tag.
	 * @param	array  $attributes Attributes to set. Null values remove attributes.
	 * @param	array  $classes    Classes to add.
	 */
	private static function replace_anchor_tag_attributes( $tag, $attributes, $classes = array() ) {
		if ( $classes ) {
			$existing_classes = array();

			if ( preg_match( '/\sclass=(["\'])(.*?)\1/i', $tag, $class_match ) ) {
				$existing_classes = preg_split( '/\s+/', trim( html_entity_decode( $class_match[2], ENT_QUOTES ) ) );
			}

			$attributes['class'] = implode( ' ', array_unique( array_filter( array_merge( $existing_classes, $classes ) ) ) );
		}

		foreach ( $attributes as $name => $value ) {
			$tag = self::replace_html_attribute( $tag, $name, $value );
		}

		return $tag;
	}

	/**
	 * Set or remove an HTML attribute on a tag.
	 *
	 * @since	3.4.0
	 * @param	string $tag   Opening tag.
	 * @param	string $name  Attribute name.
	 * @param	mixed  $value Attribute value. Null, false, and empty string remove it.
	 */
	private static function replace_html_attribute( $tag, $name, $value ) {
		$attribute_pattern = '/\s' . preg_quote( $name, '/' ) . '(?:\s*=\s*(?:"[^"]*"|\'[^\']*\'|[^\s>]*))?/i';

		if ( null === $value || false === $value || '' === $value ) {
			return preg_replace( $attribute_pattern, '', $tag, 1 );
		}

		$attribute = ' ' . $name . '="' . esc_attr( $value ) . '"';

		if ( preg_match( $attribute_pattern, $tag ) ) {
			return preg_replace( $attribute_pattern, $attribute, $tag, 1 );
		}

		return preg_replace( '/>$/', $attribute . '>', $tag, 1 );
	}
}

EAFL_Blocks::init();
