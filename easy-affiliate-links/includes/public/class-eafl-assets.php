<?php
/**
 * Responsible for loading the EAFL assets.
 *
 * @link       https://bootstrapped.ventures
 * @since      2.5.0
 *
 * @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/includes/public
 */

/**
 * Responsible for loading the EAFL assets.
 *
 * @since      2.5.0
 * @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/includes/public
 * @author     Brecht Vandersmissen <brecht@bootstrapped.ventures>
 */
class EAFL_Assets {
	/**
	 * Register actions and filters.
	 *
	 * @since    2.5.0
	 */
	public static function init() {
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'enqueue' ) );
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'enqueue_admin' ), 1 );
		add_action( 'wp_head', array( __CLASS__, 'custom_css' ) );
		add_action( 'enqueue_block_editor_assets', array( __CLASS__, 'block_assets' ) );
	}

	/**
	 * Enqueue stylesheets and scripts.
	 *
	 * @since    2.5.0
	 */
	public static function enqueue() {
		wp_enqueue_script( 'eafl-public', EAFL_URL . 'dist/public.js', array(), EAFL_VERSION, true );

		wp_localize_script( 'eafl-public', 'eafl_public', array(
			'home_url' => home_url( '/' ),
			'ajax_url' => admin_url( 'admin-ajax.php' ),
			'nonce' => wp_create_nonce( 'eafl' ),
		));
	}

	/**
	 * Enqueue admin stylesheets and scripts.
	 *
	 * @since    3.0.0
	 */
	public static function enqueue_admin() {
		// Load shared JS first.
		wp_enqueue_script( 'eafl-shared', EAFL_URL . 'dist/shared.js', array(), EAFL_VERSION, true );

		// Add Premium JS to dependencies when active.
		$dependencies = array( 'eafl-shared' );
		if ( EAFL_Addons::is_active( 'premium' ) ) {
			$dependencies[] = 'eaflp-admin';
		}

		wp_enqueue_style( 'eafl-admin', EAFL_URL . 'dist/admin.css', array(), EAFL_VERSION, 'all' );
		wp_enqueue_script( 'eafl-admin', EAFL_URL . 'dist/admin.js', $dependencies, EAFL_VERSION, true );

		// Required for classic editor.
		wp_enqueue_script( 'eafl-code-button', EAFL_URL . 'assets/js/other/shortcode-button-tinymce-code.js', array( 'jquery' ), EAFL_VERSION, true );

		// Translations.
		include( EAFL_DIR . 'templates/admin/translations.php' );

		$eafl_admin = array(
			'eafl_url' => EAFL_URL,
			'api_nonce' => wp_create_nonce( 'wp_rest' ),
			'ajax_url' => admin_url( 'admin-ajax.php' ),
			'nonce' => wp_create_nonce( 'eafl' ),
			'endpoints' => array(
				'link' => rtrim( get_rest_url( null, 'wp/v2/' . EAFL_POST_TYPE ), '/' ),
				'category' => rtrim( get_rest_url( null, 'wp/v2/eafl_category' ), '/' ),
				'manage' => rtrim( get_rest_url( null, 'easy-affiliate-links/v1/manage' ), '/' ),
				'click' => rtrim( get_rest_url( null, 'easy-affiliate-links/v1/click' ), '/' ),
				'notices' => rtrim( get_rest_url( null, 'easy-affiliate-links/v1/notice' ), '/' ),
				'search' => rtrim( get_rest_url( null, 'easy-affiliate-links/v1/search' ), '/' ),
			),
			'addons' => array(
				'premium' => EAFL_Addons::is_active( 'premium' ),
			),
			'translations' => $translations ? $translations : array(),
		);

		// Shared loads first, so localize then.
		wp_localize_script( 'eafl-shared', 'eafl_admin', $eafl_admin );
	}

	/**
	 * Enqueue Gutenberg block assets.
	 *
	 * @since    2.6.3
	 */
	public static function block_assets() {
		wp_enqueue_style( 'eafl-blocks', EAFL_URL . 'dist/blocks.css', array(), EAFL_VERSION, 'all' );
		wp_enqueue_script( 'eafl-blocks', EAFL_URL . 'dist/blocks.js', array( 'wp-i18n', 'wp-editor', 'wp-element', 'wp-blocks', 'wp-components', 'wp-format-library'  ), EAFL_VERSION );
	}

	/**
	 * Output the custom CSS.
	 *
	 * @since    2.5.0
	 */
	public static function custom_css() {
		if ( EAFL_Settings::get( 'output_public_css' ) ) {
			// Inline CSS for EAFL.
			ob_start();
			include( EAFL_DIR . 'assets/css/other/inline.css' );
			$inline_css = ob_get_contents();
			ob_end_clean();
			echo '<style type="text/css">' . $inline_css . '</style>';

			// Public CSS set on settings.
			$public_css = EAFL_Settings::get( 'public_css' );

			if ( trim( $public_css ) ) {
				echo '<style type="text/css">' . $public_css . '</style>';
			}
		}
	}
}

EAFL_Assets::init();
