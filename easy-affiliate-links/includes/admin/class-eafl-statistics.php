<?php
/**
 * Show the statistics page in the backend menu.
 *
 * @link       https://bootstrapped.ventures
 * @since      2.1.1
 *
 * @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/includes/admin
 */

/**
 * Show the statistics page in the backend menu.
 *
 * @since      2.1.1
 * @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/includes/admin
 * @author     Brecht Vandersmissen <brecht@bootstrapped.ventures>
 */
class EAFL_Statistics {

	/**
	 * Register actions and filters.
	 *
	 * @since    2.1.1
	 */
	public static function init() {
		add_action( 'admin_menu', array( __CLASS__, 'add_submenu_page' ), 12 );
		add_action( 'eafl_statistics_page', array( __CLASS__, 'statistics_page' ) );
		add_filter( 'eafl_statistics_tabs', array( __CLASS__, 'statistics_tabs' ), 99 );

		add_action( 'admin_post_eafl_statistics_cleanup', array( __CLASS__, 'form_save_cleanup' ) );
		add_action( 'eafl_migration_page', array( __CLASS__, 'cleanup_page' ) );
	}

	/**
	 * Add the Statistics submenu to the EAFL menu.
	 *
	 * @since    2.1.1
	 */
	public static function add_submenu_page() {
		add_submenu_page( 'easyaffiliatelinks', __( 'Click Statistics', 'easy-affiliate-links' ), __( 'Click Statistics', 'easy-affiliate-links' ), EAFL_Settings::get( 'statistics_capability' ), 'eafl_statistics', array( __CLASS__, 'page_template' ) );
	}

	/**
	 * Get the template for this submenu.
	 *
	 * @since    2.1.1
	 */
	public static function page_template() {
		require_once( EAFL_DIR . 'templates/admin/menu/statistics.php' );
	}

	/**
	 * Statistics page tabs to output.
	 *
	 * @since    2.3.0
	 * @param	 mixed $tabs Tabs to display.
	 */
	public static function statistics_tabs( $tabs ) {
		$tabs['charts'] = __( 'Charts', 'easy-affiliate-links' );
		$tabs['export'] = __( 'Export Clicks', 'easy-affiliate-links' );
		$tabs['cleanup'] = __( 'Clean Up', 'easy-affiliate-links' );
		return $tabs;
	}

	/**
	 * Statistics page to output.
	 *
	 * @since    2.0.0
	 * @param	 mixed $sub Sub statistics page to display.
	 */
	public static function statistics_page( $sub ) {
		if ( ( 'charts' === $sub || 'export' === $sub ) && ! EAFL_Addons::is_active( 'premium' ) ) {
			echo '<p>Charts and export for link clicks are available in <a href="https://bootstrapped.ventures/easy-affiliate-links/get-the-plugin/" target="_blank">Easy Affiliate Links Premium</a>.';
		} elseif ( 'cleanup' === $sub ) {
			require_once( EAFL_DIR . 'templates/admin/menu/statistics/cleanup.php' );
		}
	}

	/**
	 * Clean up the click statistics.
	 *
	 * @since    2.3.0
	 */
	public static function form_save_cleanup() {
		if ( isset( $_POST['eafl_statistics'] ) && wp_verify_nonce( sanitize_key( $_POST['eafl_statistics'] ), 'eafl_statistics' ) ) { // Input var okay.
			$remove_all = isset( $_POST['remove_all'] ) && sanitize_key( $_POST['remove_all'] ) ? true : false; // Input var okay.
			$remove_user_roles = isset( $_POST['remove_user_roles'] ) ? array_map( 'sanitize_key', $_POST['remove_user_roles'] ) : array(); // Input var okay.
			$exclude_ips = isset( $_POST['exclude_ips'] ) ? wp_kses_post( wp_unslash( $_POST['exclude_ips'] ) ) : ''; // Input var okay.

			// Save current settings.
			$settings = array();
			$settings['statistics_remove_user_roles'] = $remove_user_roles;
			$settings['statistics_exclude_ips'] = $exclude_ips;
			EAFL_Settings::update_settings( $settings );

			$total_clicks = EAFL_Clicks_Database::count_clicks();

			if ( $remove_all ) {
				EAFL_Clicks_Database::delete_all_clicks();

				wp_safe_redirect( admin_url( 'admin.php?page=eafl_migration&sub=cleanup&d=' . $total_clicks . 'p=' . $total_clicks . 't=' . $total_clicks ) );
				exit();
			} else {
				wp_safe_redirect( admin_url( 'admin.php?page=eafl_migration&sub=cleanup&t=' . $total_clicks ) );
				exit();
			}
		}
		wp_safe_redirect( admin_url( 'admin.php?page=eafl_statistics' ) );
		exit();
	}

	/**
	 * Cleanup migration page to output.
	 *
	 * @since    2.3.0
	 * @param	 mixed $sub Sub migration page to display.
	 */
	public static function cleanup_page( $sub ) {
		if ( 'cleanup' === $sub ) {
			$limit = 250;
			$deleted = isset( $_GET['d'] ) ? intval( $_GET['d'] ) : 0; // Input var okay.
			$page = isset( $_GET['p'] ) ? intval( $_GET['p'] ) : 0; // Input var okay.
			$total = isset( $_GET['t'] ) ? intval( $_GET['t'] ) : false; // Input var okay.

			if ( ! $total ) {
				$total = EAFL_Clicks_Database::count_clicks();
			}

			$total_pages = ceil( $total / $limit );

			if ( $page >= $total_pages ) {
				echo '<p>Cleanup finished successfully. Cleaned up ' . esc_html( $deleted ) . ' clicks.</p>';
			} else {
				$deleted += self::cleanup_clicks( $page, $limit, $deleted );
				$page++;

				echo '<p>Stage ' . esc_html( $page ) . '/' . esc_html( $total_pages ) . ' of cleanup. Cleaned up ' . esc_html( $deleted ) . ' clicks. Leave this page open until complete.</p>';
				echo '<script>setTimeout(function() { window.location = "' . admin_url( 'admin.php?page=eafl_migration&sub=cleanup&d=' . $deleted . '&t=' . $total . '&p=' . $page ) . '"; }, 500);</script>';
			}
		}
	}

	/**
	 * Cleanup clicks.
	 *
	 * @since    2.3.0
	 * @param	 int $page Page of clicks to cleanup.
	 * @param	 int $limit Number of clicks to cleanup.
	 * @param	 int $deleted Number of clicks deleted so far.
	 */
	public static function cleanup_clicks( $page, $limit, $deleted ) {
		// Crawler Detect vendor.
		require_once( EAFL_DIR . 'vendor/crawlerdetect/CrawlerDetect.php' );

		// User Roles to exclude.
		$exclude_roles = EAFL_Settings::get( 'statistics_remove_user_roles' );

		// Get set of clicks.
		$args = array(
			'orderby' => 'id',
			'order' => 'ASC',
			'limit' => $limit,
			'offset' => $page * $limit - $deleted,
		);
		$clicks = EAFL_Clicks_Database::get_clicks( $args );
		$clicks_to_delete = array();

		foreach ( $clicks['clicks'] as $click ) {
			// Check for excluded IP.
			if ( '' !== $click->ip ) {
				if ( EAFL_Clicks::should_exclude_ip( $click->ip ) ) {
					$clicks_to_delete[] = $click;
					continue;
				}
			}

			// Check for crawler.
			$crawler_detect = new CrawlerDetect( array(), $click->agent );
			if ( $crawler_detect->isCrawler() ) {
				$clicks_to_delete[] = $click;
				continue;
			}

			// Check for User Role.
			if ( $click->user_id && ! empty( $exclude_roles ) ) {
				$user_data = get_userdata( $click->user_id );

				if ( $user_data ) {
					$role_match = array_intersect( $user_data->roles, $exclude_roles );

					if ( ! empty( $role_match ) ) {
						$clicks_to_delete[] = $click;
						continue;
					}
				}
			}
		}

		if ( ! empty( $clicks_to_delete ) ) {
			// Delete clicks from database.
			$click_ids_to_delete = wp_list_pluck( $clicks_to_delete, 'id' );
			EAFL_Clicks_Database::delete_clicks( $click_ids_to_delete );

			// Update summary for affected links.
			$link_ids_to_update = array_unique( wp_list_pluck( $clicks_to_delete, 'link_id' ) );
			foreach ( $link_ids_to_update as $link_id ) {
				EAFL_Clicks::update_summary( $link_id );
			}
		}

		return count( $clicks_to_delete );
	}
}

EAFL_Statistics::init();
