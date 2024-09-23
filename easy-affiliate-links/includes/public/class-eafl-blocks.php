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
}

EAFL_Blocks::init();
