<?php
/**
 * Responsible for the clicks database.
 *
 * @link       https://bootstrapped.ventures
 * @since      2.1.0
 *
 * @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/includes/public
 */

/**
 * Responsible for the clicks database.
 *
 * @since      2.1.0
 * @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/includes/public
 * @author     Brecht Vandersmissen <brecht@bootstrapped.ventures>
 */
class EAFL_Migration_Clicks_Db {
	/**
	 * Register actions and filters.
	 *
	 * @since    2.1.0
	 */
	public static function init() {
		add_action( 'current_screen', array( __CLASS__, 'check_if_migration_needed' ) );
		add_action( 'eafl_migration_page', array( __CLASS__, 'migration_page' ) );
	}

	/**
	 * Check if a clicks database migration is needed.
	 *
	 * @since    2.0.0
	 */
	public static function check_if_migration_needed() {
		if ( ! EAFL_Migrations::is_migrated_to( 'clicks_db' ) ) {
			$link_ids = EAFL_Link_Manager::get_link_ids();
			if ( 0 === count( $link_ids ) ) {
				EAFL_Migrations::set_migrated_to( 'clicks_db' );
			} else {
				add_action( 'admin_notices', array( __CLASS__, 'click_db_notice' ) );
			}
		}
	}

	/**
	 * Notice for the click database migration.
	 *
	 * @since    2.1.0
	 */
	public static function click_db_notice() {
		$screen = get_current_screen();
		if ( 'admin_page_eafl_migration' !== $screen->id ) {
			echo '<div class="notice notice-warning">';
			echo '<strong>Easy Affiliate Links</strong>';
			echo '<p>Migration to new version required. ';
			echo '<a href="' . esc_url( add_query_arg( 'sub', 'clicks_db', admin_url( 'admin.php?page=eafl_migration' ) ) ) . '">Migrate Now</a></p>';
			echo '</div>';
		}
	}

	/**
	 * Migration page to output.
	 *
	 * @since    2.1.0
	 * @param	 mixed $sub Sub manage page to display.
	 */
	public static function migration_page( $sub ) {
		if ( 'clicks_db' === $sub ) {
			if ( EAFL_Migrations::is_migrated_to( 'clicks_db' ) ) {
				echo '<p>Migration finished successfully.</p>';
			} else {
				if ( ! class_exists( 'Browser' ) ) {
					require_once( EAFL_DIR . 'vendor/browser/Browser.php' );
				}
				$links = self::get_links();

				if ( empty( $links['link_ids'] ) ) {
					EAFL_Migrations::set_migrated_to( 'clicks_db' );
					echo '<p>Migration finished successfully.</p>';
				} else {
					foreach ( $links['link_ids'] as $link_id ) {
						self::migrate_link( $link_id );
					}

					echo '<p>' . esc_html( $links['total'] ) . ' links left to migrate. Leave this page open until complete.</p>';
					echo '<script>window.onload = function () { setTimeout(function() { window.location.reload(); }, 500); };</script>';
				}
			}
		}
	}

	/**
	 * Get links to migrate.
	 *
	 * @since    2.1.0
	 */
	public static function get_links() {
		$args = array(
			'post_type' => EAFL_POST_TYPE,
			'post_status' => 'any',
			'orderby' => 'date',
			'order' => 'DESC',
			'posts_per_page' => 50,
			'offset' => 0,
			'fields' => 'ids',
			'meta_query' => array(
				array(
					'key' => 'eafl_clicks',
					'compare' => 'EXISTS',
				),
			),
		);

		$query = new WP_Query( $args );

		return array(
			'link_ids' => $query->posts,
			'total' => $query->found_posts,
		);
	}

	/**
	 * Migrate clicks for a specific link.
	 *
	 * @since    2.1.0
	 * @param	 int $link_id Link ID to migrate the clicks for.
	 */
	public static function migrate_link( $link_id ) {
		$clicks = get_post_meta( $link_id, 'eafl_clicks', false );
		$summary = array();
		$total = 0;

		foreach ( $clicks as $click ) {
			if ( $click && is_object( $click['browser'] ) && ! $click['browser']->isRobot() ) {
				$is_mobile = $click['browser']->isMobile();
				$is_tablet = $click['browser']->isTablet();

				$new_click = array(
					'date' => date( 'Y-m-d H:m:s', $click['time'] ),
					'link_id' => $link_id,
					'user_id' => $click['user'],
					'ip' => $click['ip'],
					'referer' => $click['referer'],
					'request' => $click['request'],
					'agent' => $click['browser']->getUserAgent(),
					'is_mobile' => $is_mobile,
					'is_tablet' => $is_tablet,
					'is_desktop' => ( ! $is_mobile && ! $is_tablet ),
				);

				$year_month = date( 'Y-m', $click['time'] );
				$summary[ $year_month ] = isset( $summary[ $year_month ] ) ? $summary[ $year_month ] + 1 : 1;
				$total++;

				EAFL_Clicks_Database::add_click( $new_click );
			}
		}
		// Remove old way of saving clicks.
		delete_post_meta( $link_id, 'eafl_clicks' );

		// Update summaries (robots are gone now).
		$summary['all'] = $total;
		update_post_meta( $link_id, 'eafl_clicks_summary', $summary );
		update_post_meta( $link_id, 'eafl_clicks_total', $total );
	}
}

EAFL_Migration_Clicks_Db::init();
