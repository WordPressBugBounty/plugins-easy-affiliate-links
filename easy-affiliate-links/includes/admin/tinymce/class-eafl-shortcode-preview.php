<?php
/**
 * Handle the display of the shortcode in the TinyMCE editor.
 *
 * @link       https://bootstrapped.ventures
 * @since      2.0.0
 *
 * @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/includes/admin/tinymce
 */

/**
 * Handle the display of the shortcode in the TinyMCE editor.
 *
 * @since      2.0.0
 * @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/includes/admin/tinymce
 * @author     Brecht Vandersmissen <brecht@bootstrapped.ventures>
 */
class EAFL_Shortcode_Preview {

	/**
	 * Register actions and filters.
	 *
	 * @since    2.0.0
	 */
	public static function init() {
			add_filter( 'mce_external_plugins', array( __CLASS__, 'tinymce_shortcode_plugin' ) );
	}

	/**
	 * Load custom TinyMCE plugin for handling the link shortcode.
	 *
	 * @since    2.0.0
	 * @param	 array $plugin_array Plugins to be used by TinyMCE.
	 */
	public static function tinymce_shortcode_plugin( $plugin_array ) {
		 $plugin_array['easyaffiliatelinks'] = EAFL_URL . 'assets/js/other/shortcode-tinymce-visual-preview.js';
		 return $plugin_array;
	}
}

EAFL_Shortcode_Preview::init();
