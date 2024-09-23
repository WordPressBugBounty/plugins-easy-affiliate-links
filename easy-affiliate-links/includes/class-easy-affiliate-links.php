<?php
/**
 * The core plugin class.
 *
 * @link       https://bootstrapped.ventures
 * @since      2.0.0
 *
 * @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      2.0.0
 * @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/includes
 * @author     Brecht Vandersmissen <brecht@bootstrapped.ventures>
 */
class Easy_Affiliate_Links {

	/**
	 * Define any constants to be used in the plugin.
	 *
	 * @since    2.0.0
	 */
	private function define_constants() {
		define( 'EAFL_VERSION', '3.7.4' );
		define( 'EAFL_PREMIUM_VERSION_REQUIRED', '3.1.0' );
		define( 'EAFL_POST_TYPE', 'easy_affiliate_link' );
		define( 'EAFL_DIR', plugin_dir_path( dirname( __FILE__ ) ) );
		define( 'EAFL_URL', plugin_dir_url( dirname( __FILE__ ) ) );
	}

	/**
	 * Make sure all is set up for the plugin to load.
	 *
	 * @since    2.0.0
	 */
	public function __construct() {
		$this->define_constants();
		$this->load_dependencies();
		add_action( 'plugins_loaded', array( $this, 'eafl_init' ), 1 );
		add_action( 'admin_notices', array( $this, 'admin_notice_required_version' ) );
	}

	/**
	 * Init EAFL for Premium add-ons.
	 *
	 * @since	3.1.0
	 */
	public function eafl_init() {
		do_action( 'eafl_init' );
	}

	/**
	 * Load all plugin dependencies.
	 *
	 * @since    2.0.0
	 */
	private function load_dependencies() {
		// General.
		require_once( EAFL_DIR . 'includes/class-eafl-i18n.php' );

		// Priority.
		require_once( EAFL_DIR . 'includes/public/class-eafl-settings.php' );

		// Api.
		require_once( EAFL_DIR . 'includes/public/api/class-eafl-api-clicks.php' );
		require_once( EAFL_DIR . 'includes/public/api/class-eafl-api-links.php' );
		require_once( EAFL_DIR . 'includes/public/api/class-eafl-api-manage-categories.php' );
		require_once( EAFL_DIR . 'includes/public/api/class-eafl-api-manage-clicks.php' );
		require_once( EAFL_DIR . 'includes/public/api/class-eafl-api-manage-links.php' );
		require_once( EAFL_DIR . 'includes/public/api/class-eafl-api-manage-relations.php' );
		require_once( EAFL_DIR . 'includes/public/api/class-eafl-api-notices.php' );
		require_once( EAFL_DIR . 'includes/public/api/class-eafl-api-search.php' );

		// Public.
		require_once( EAFL_DIR . 'includes/public/class-eafl-addons.php' );
		require_once( EAFL_DIR . 'includes/public/class-eafl-assets.php' );
		require_once( EAFL_DIR . 'includes/public/class-eafl-blocks.php' );
		require_once( EAFL_DIR . 'includes/public/class-eafl-clicks.php' );
		require_once( EAFL_DIR . 'includes/public/class-eafl-clicks-database.php' );
		require_once( EAFL_DIR . 'includes/public/class-eafl-compatibility.php' );
		require_once( EAFL_DIR . 'includes/public/class-eafl-disclaimer.php' );
		require_once( EAFL_DIR . 'includes/public/class-eafl-link.php' );
		require_once( EAFL_DIR . 'includes/public/class-eafl-link-manager.php' );
		require_once( EAFL_DIR . 'includes/public/class-eafl-link-sanitizer.php' );
		require_once( EAFL_DIR . 'includes/public/class-eafl-link-saver.php' );
		require_once( EAFL_DIR . 'includes/public/class-eafl-post-type.php' );
		require_once( EAFL_DIR . 'includes/public/class-eafl-redirect.php' );
		require_once( EAFL_DIR . 'includes/public/class-eafl-relations-database.php' );
		require_once( EAFL_DIR . 'includes/public/class-eafl-shortcode.php' );
		require_once( EAFL_DIR . 'includes/public/class-eafl-taxonomies.php' );

		// Admin.
		if ( is_admin() ) {
			// require_once( EAFL_DIR . 'includes/admin/class-eafl-feedback.php' );
			require_once( EAFL_DIR . 'includes/admin/class-eafl-manage-modal.php' );
			require_once( EAFL_DIR . 'includes/admin/class-eafl-marketing.php' );
			require_once( EAFL_DIR . 'includes/admin/class-eafl-migrations.php' );
			require_once( EAFL_DIR . 'includes/admin/class-eafl-notices.php' );
			require_once( EAFL_DIR . 'includes/admin/class-eafl-permalinks.php' );
			require_once( EAFL_DIR . 'includes/admin/class-eafl-privacy.php' );
			require_once( EAFL_DIR . 'includes/admin/class-eafl-statistics.php' );
			require_once( EAFL_DIR . 'includes/admin/class-eafl-tools-manager.php' );

			// Import & Export.
			require_once( EAFL_DIR . 'includes/admin/import-export/class-eafl-ie-export-csv.php' );
			require_once( EAFL_DIR . 'includes/admin/import-export/class-eafl-ie-export-xml.php' );
			require_once( EAFL_DIR . 'includes/admin/import-export/class-eafl-ie-import-csv.php' );
			require_once( EAFL_DIR . 'includes/admin/import-export/class-eafl-ie-import-xml.php' );
			require_once( EAFL_DIR . 'includes/admin/import-export/class-eafl-import-export.php' );

			// Menu.
			require_once( EAFL_DIR . 'includes/admin/menu/class-eafl-admin-menu-addons.php' );
			require_once( EAFL_DIR . 'includes/admin/menu/class-eafl-admin-menu-faq.php' );
			require_once( EAFL_DIR . 'includes/admin/menu/class-eafl-admin-menu.php' );

			// TinyMCE.
			require_once( EAFL_DIR . 'includes/admin/tinymce/class-eafl-button.php' );
			require_once( EAFL_DIR . 'includes/admin/tinymce/class-eafl-shortcode-preview.php' );

			// Tools.
			require_once( EAFL_DIR . 'includes/admin/tools/class-eafl-tools-find-relations.php' );
		}
	}

	/**
	 * Admin notice to show when the required version is not met.
	 *
	 * @since	3.1.0
	 */
	public function admin_notice_required_version() {
		if ( defined( 'EAFLP_VERSION' ) && version_compare( EAFLP_VERSION, EAFL_PREMIUM_VERSION_REQUIRED ) < 0 ) {
			echo '<div class="notice notice-error"><p>';
			echo '<strong>Easy Affiliate Links</strong></br>';
			esc_html_e( 'Please update to at least the following plugin versions:', 'easy-affiliate-links-premium' );
			echo '<br/>Easy Affiliate Links Premium ' . esc_html( EAFL_PREMIUM_VERSION_REQUIRED );
			echo '</p><p>';
			echo '<a href="https://help.bootstrapped.ventures/article/207-updating-easy-affiliate-links" target="_blank">';
			esc_html_e( 'More information on updating the plugin', 'easy-affiliate-links' );
			echo '</a>';
			echo '</p></div>';
		} elseif ( class_exists( 'Easy_Affiliate_Links_Statistics' ) ) {
			// Migration to Premium.
			echo '<div class="notice notice-error"><p>';
			echo '<strong>Easy Affiliate Links</strong></br>';
			echo 'WARNING: The EAFL Statistics add-on has now become a full Premium plugin and needs a manual update.';
			echo '</p><p>';
			echo '<a href="https://help.bootstrapped.ventures/article/207-updating-easy-affiliate-links" target="_blank">';
			esc_html_e( 'Follow these steps to update the plugin', 'easy-affiliate-links' );
			echo '</a>';
			echo '</p></div>';
		}
	}

	/**
	 * Adjust action links on the plugins page.
	 *
	 * @since	3.1.0
	 * @param	array $links Current plugin action links.
	 */
	public function plugin_action_links( $links ) {
		if ( ! EAFL_Addons::is_active( 'premium' ) ) {
			return array_merge( array( '<a href="https://bootstrapped.ventures/easy-affiliate-links/get-the-plugin/" target="_blank">Upgrade to Premium</a>' ), $links );
		} else {
			return $links;
		}
	}
}
