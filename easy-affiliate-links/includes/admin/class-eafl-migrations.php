<?php
/**
 * Responsible for handling migrations when updating the plugin.
 *
 * @link       https://bootstrapped.ventures
 * @since      2.0.0
 *
 * @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/includes/admin
 */

/**
 * Responsible for handling migrations when updating the plugin.
 *
 * @since      2.0.0
 * @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/includes/admin
 * @author     Brecht Vandersmissen <brecht@bootstrapped.ventures>
 */
class EAFL_Migrations {
	/**
	 * Array containing the specific migrations that have been performed.
	 *
	 * @since    2.1.0
	 * @access   private
	 * @var      array $links Array containing the specific migrations that have been performed.
	 */
	private static $specific_migrations;

	/**
	 * Register actions and filters.
	 *
	 * @since    2.0.0
	 */
	public static function init() {
		add_action( 'admin_init', array( __CLASS__, 'check_if_migration_needed' ) );
		add_action( 'admin_menu', array( __CLASS__, 'add_submenu_page' ) );
	}

	/**
	 * Check if a plugin migration is needed.
	 *
	 * @since    2.0.0
	 */
	public static function check_if_migration_needed() {
		// Version Migrations.
		$migrated_to_version = get_option( 'eafl_migrated_to_version', '0.0.0' );

		if ( '0.0.0' !== $migrated_to_version && version_compare( $migrated_to_version, EAFL_VERSION ) < 0 ) {
			if ( version_compare( $migrated_to_version, '2.0.0' ) < 0 ) {
				require_once( EAFL_DIR . 'includes/admin/migrations/eafl-2-0-0-settings.php' );
			}
			if ( version_compare( $migrated_to_version, '3.0.0' ) < 0 ) {
				require_once( EAFL_DIR . 'includes/admin/migrations/eafl-3-0-0-settings.php' );
			}

			update_option( 'eafl_migrated_to_version', EAFL_VERSION );

			// Specific Migrations.
			require_once( EAFL_DIR . 'includes/admin/migrations/eafl-2-1-0-clicks-db.php' );
		} elseif ( '0.0.0' === $migrated_to_version ) {
			update_option( 'eafl_migrated_to_version', EAFL_VERSION );
		}
	}

	/**
	 * Check if a specific migration has been performed.
	 *
	 * @since    2.1.0
	 */
	public static function get_specific_migrations() {
		if ( ! is_array( isset( self::$specific_migrations ) ) ) {
			self::$specific_migrations = get_option( 'eafl_specific_migrations', array() );
		}

		return self::$specific_migrations;
	}

	/**
	 * Check if a specific migration has been performed.
	 *
	 * @since    2.1.0
	 * @param    mixed $migration Name of the migration to check.
	 */
	public static function is_migrated_to( $migration ) {
		$migrations = self::get_specific_migrations();
		return isset( $migrations[ $migration ] ) && $migrations[ $migration ];
	}

	/**
	 * Set a specific migration as performed.
	 *
	 * @since    2.1.0
	 * @param    mixed $migration Name of the migration.
	 */
	public static function set_migrated_to( $migration ) {
		$migrations = self::get_specific_migrations();
		$migrations[ $migration ] = true;
		self::$specific_migrations = $migrations;
		update_option( 'eafl_specific_migrations', $migrations, false );
	}

	/**
	 * Add the migration page.
	 *
	 * @since    2.1.0
	 */
	public static function add_submenu_page() {
		add_submenu_page( '', __( 'Migration', 'easy-affiliate-links' ), __( 'Migration', 'easy-affiliate-links' ), 'manage_options', 'eafl_migration', array( __CLASS__, 'page_template' ) );
	}

	/**
	 * Get the template for the migration page.
	 *
	 * @since    2.1.0
	 */
	public static function page_template() {
		require_once( EAFL_DIR . 'templates/admin/menu/migration.php' );
	}
}

EAFL_Migrations::init();
