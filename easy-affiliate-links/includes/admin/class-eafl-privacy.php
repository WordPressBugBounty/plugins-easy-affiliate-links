<?php
/**
 * Responsible for the privacy policy.
 *
 * @link       https://bootstrapped.ventures
 * @since      2.6.2
 *
 * @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/includes/admin
 */

/**
 * Responsible for the privacy policy.
 *
 * @since      2.6.2
 * @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/includes/admin
 * @author     Brecht Vandersmissen <brecht@bootstrapped.ventures>
 */
class EAFL_Privacy {

	/**
	 * Register actions and filters.
	 *
	 * @since    2.6.2
	 */
	public static function init() {
		add_action( 'admin_init', array( __CLASS__, 'privacy_policy' ) );
		add_filter( 'wp_privacy_personal_data_exporters', array( __CLASS__, 'register_exporter' ) );
	}

	/**
	 * Add text to the privacy policy suggestions.
	 *
	 * @since    2.6.2
	 */
	public static function privacy_policy() {
		if ( ! function_exists( 'wp_add_privacy_policy_content' ) ) {
			return;
		}

		ob_start();
		include( EAFL_DIR . 'templates/admin/privacy.php' );
		$content = ob_get_contents();
		ob_end_clean();

		wp_add_privacy_policy_content(
			'Easy Affiliate Links',
			wp_kses_post( wpautop( $content, false ) )
		);
	}

	/**
	 * Register our personal data exporter.
	 *
	 * @since    2.6.2
	 * @param	 array $exporters Personal data exporters.
	 */
	public static function register_exporter( $exporters ) {
		$exporters['easy-affiliate-links'] = array(
			'exporter_friendly_name' => __( 'Easy Affiliate Links Plugin', 'easy-affiliate-links' ),
			'callback' => array( __CLASS__, 'personal_data_exporter' ),
		);
		return $exporters;
	}

	/**
	 * Personal data exporter.
	 *
	 * @since    2.6.2
	 * @param	 array $email Email address.
	 * @param	 array $page  Page.
	 */
	public static function personal_data_exporter( $email, $page = 1 ) {
		$export = array();
		$user = get_user_by( 'email', $email );

		if ( $user && $user->ID ) {
			$click_args = array(
				'order' => 'ASC',
				'orderby' => 'date',
				'where' => 'user_id = ' . intval( $user->ID ),
			);

			$clicks = EAFL_Clicks_Database::get_clicks( $click_args );
			foreach( $clicks['clicks'] as $click ) {
				$click_data = array();

				$click_data[] = array(
					'name' => __( 'Click ID', 'easy-affiliate-links' ),
					'value' => $click->id,
				);
				$click_data[] = array(
					'name' => __( 'Date', 'easy-affiliate-links' ),
					'value' => $click->date,
				);
				$click_data[] = array(
					'name' => __( 'User ID', 'easy-affiliate-links' ),
					'value' => $click->user_id,
				);
				$click_data[] = array(
					'name' => __( 'IP Address', 'easy-affiliate-links' ),
					'value' => $click->ip,
				);
				$click_data[] = array(
					'name' => __( 'User Agent', 'easy-affiliate-links' ),
					'value' => $click->agent,
				);

				$export[] = array(
					'group_id' => 'easy-affiliate-links',
					'group_label' => 'Easy Affiliate Links Clicks',
					'item_id' => 'easy-affiliate-links-' . $click->id,
					'data' => $click_data,
				);
			}
		}
		return array(
			'data' => $export,
			'done' => true,
		);
	}
}

EAFL_Privacy::init();
