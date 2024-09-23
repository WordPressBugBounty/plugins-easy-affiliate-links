<?php
/**
 * Responsible for showing admin notices.
 *
 * @link       http://bootstrapped.ventures
 * @since      3.1.0
 *
 * @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/includes/admin
 */

/**
 * Responsible for showing admin notices.
 *
 * @since      3.1.0
 * @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/includes/admin
 * @author     Brecht Vandersmissen <brecht@bootstrapped.ventures>
 */
class EAFL_Notices {

	/**
	 * Register actions and filters.
	 *
	 * @since    3.1.0
	 */
	public static function init() {
		add_filter( 'eafl_admin_notices', array( __CLASS__, 'new_user_notice' ) );
		add_filter( 'eafl_admin_notices', array( __CLASS__, 'limited_clicks_notice' ) );
		add_filter( 'eafl_admin_notices', array( __CLASS__, 'manage_relations_notice' ) );
	}

	/**
	 * Get all notices to show.
	 *
	 * @since    3.1.0
	 */
	public static function get_notices() {
		$notices_to_display = array();
		$current_user_id = get_current_user_id();

		if ( $current_user_id ) {
			$notices = apply_filters( 'eafl_admin_notices', array() );
			$dismissed_notices = get_user_meta( $current_user_id, 'eafl_dismissed_notices', false );

			foreach ( $notices as $notice ) {
				// Set defaults.
				$notice = wp_parse_args( $notice, array(
					'dismissable' => true,
					'location' => false,
					'capability' => false,
				));
				
				// Check capability.
				if ( false !== $notice['capability'] && ! current_user_can( $notice['capability'] ) ) {
					continue;
				}

				// Only dismissable when ID is set.
				if ( ! isset( $notice['id'] ) ) {
					$notice['dismissable'] = false;
				}

				// Check if user has already dismissed notice.
				if ( false !== $notice['dismissable'] && in_array( $notice['id'], $dismissed_notices ) ) {
					continue;
				}

				$notices_to_display[] = $notice;
			}
		}

		return $notices_to_display;
	}

	/**
	 * Show a notice to new users.
	 *
	 * @since    3.1.0
	 */
	public static function new_user_notice( $notices ) {
		$count = wp_count_posts( EAFL_POST_TYPE )->publish;

		if ( 3 >= intval( $count ) ) {
			$notices[] = array(
				'id' => 'new_user',
				'title' => __( 'Welcome to Easy Affiliate Links', 'easy-affiliate-links' ),
				'text' => __( 'Not sure how to get started?', 'easy-affiliate-links' ) . ' <a href="' . esc_url( admin_url( 'admin.php?page=eafl_faq' ) ). '">' . __( 'Check out our documentation!', 'easy-affiliate-links' ) . '</a>',
			);
		}

		return $notices;
	}

	/**
	 * Show a notice about limited click statistics.
	 *
	 * @since    3.1.0
	 */
	public static function limited_clicks_notice( $notices ) {
		if ( ! EAFL_Addons::is_active( 'statistics' ) ) {
			$notices[] = array(
				'id' => 'limited_clicks',
				'dismissable' => false,
				'location' => 'clicks',
				'title' => __( 'Data is limited to 10 clicks', 'easy-affiliate-links' ),
				'text' => __( 'Full data is available in', 'easy-affiliate-links' ) . ' <a href="https://bootstrapped.ventures/easy-affiliate-links/get-the-plugin/" target="_blank">Easy Affiliate Links Premium</a>.',
			);
		}

		return $notices;
	}

	/**
	 * Show a notice about the manage relations page.
	 *
	 * @since    3.2.0
	 */
	public static function manage_relations_notice( $notices ) {
		$notice = array(
			'id' => 'manage_relations',
			'dismissable' => false,
			'location' => 'usage',
			'text' => __( 'Does not update automatically.', 'easy-affiliate-links' ) . ' <a href="' . admin_url( 'admin.php?page=eafl_find_relations' ) . '">' . __( 'Click here to update this data', 'easy-affiliate-links' ) . '</a>.',
		);

		$last_refresh_time = get_option( 'eafl_find_relations', false );

		if ( $last_refresh_time ) {
			$notice['title'] = __( 'Last update:', 'easy-affiliate-links' ) . ' ' . date( 'Y-m-d H:i:s', $last_refresh_time );
		}

		$notices[] = $notice;
		return $notices;
	}
}

EAFL_Notices::init();
