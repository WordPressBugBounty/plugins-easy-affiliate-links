<?php
/**
 * Add the "Easy Affiliate Links" button to posts and pages.
 *
 * @link       https://bootstrapped.ventures
 * @since      2.0.0
 *
 * @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/includes/admin/tinymce
 */

/**
 * Add the "Easy Affiliate Links" button to posts and pages.
 *
 * @since      2.0.0
 * @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/includes/admin/tinymce
 * @author     Brecht Vandersmissen <brecht@bootstrapped.ventures>
 */
class EAFL_Button {

	/**
	 * Register actions and filters.
	 *
	 * @since    2.0.0
	 */
	public static function init() {
		add_action( 'init', array( __CLASS__, 'add_shortcode_button' ) );
	}

	/**
	 * Add the "Easy Affiliate Links" button to the TinyMCE editor.
	 *
	 * @since    2.0.0
	 */
	public static function add_shortcode_button() {
		if ( current_user_can( EAFL_Settings::get( 'button_capability' ) ) ) {
			add_filter( 'mce_external_plugins', array( __CLASS__, 'add_button' ), 9 );
			add_filter( 'mce_buttons', array( __CLASS__, 'register_button' ), 9 );
		}
	}

	/**
	 * Add the "Easy Affiliate Links" button to the TinyMCE editor.
	 *
	 * @since    2.0.0
	 * @param    mixed $plugin_array TinyMCE plugins.
	 */
	public static function add_button( $plugin_array ) {
		$plugin_array['easy_affiliate_links'] = EAFL_URL . 'assets/js/other/shortcode-button-tinymce-visual.js';
		return $plugin_array;
	}

	/**
	 * Register the "Easy Affiliate Links" button for the TinyMCE editor.
	 *
	 * @since    2.0.0
	 * @param    mixed $buttons TinyMCE buttons.
	 */
	public static function register_button( $buttons ) {
		array_push( $buttons, 'easy_affiliate_links' );
		return $buttons;
	}
}

EAFL_Button::init();
