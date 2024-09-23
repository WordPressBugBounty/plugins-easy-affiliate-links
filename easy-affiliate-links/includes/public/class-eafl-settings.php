<?php
/**
 * Responsible for the plugin settings.
 *
 * @link       https://bootstrapped.ventures
 * @since      3.0.0
 *
 * @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/includes/public
 */

/**
 * Responsible for the plugin settings.
 *
 * @since      3.0.0
 * @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/includes/public
 * @author     Brecht Vandersmissen <brecht@bootstrapped.ventures>
 */
class EAFL_Settings {
	private static $bvs;

	/**
	 * Register actions and filters.
	 *
	 * @since    3.0.0
	 */
	public static function init() {
		require_once EAFL_DIR . 'templates/settings/settings.php';
		require_once EAFL_DIR . 'vendor/bv-settings/bv-settings.php';

		self::$bvs = new BV_Settings(
			array(
				'uid'           	=> 'eafl',
				'menu_parent'   	=> 'easyaffiliatelinks',
				'menu_title'    	=> __( 'Settings', 'easy-affiliate-links' ),
				'menu_priority' 	=> 20,
				'settings'      	=> $settings_structure,
				'required_addons' 	=> array(),
			)
		);

		add_filter( 'eafl_settings_required_addons', array( __CLASS__, 'required_addons' ) );
	}

	/**
	 * Set required addons for settings.
	 *
	 * @since    3.1.0
	 * @param    mixed $required_addons Required addons for the settings.
	 */
	public static function required_addons( $required_addons ) {
		$required_addons['premium'] = array(
			'active' => EAFL_Addons::is_active( 'premium' ),
			'label' => 'Easy Affiliate Links Premium Required',
			'url' => 'https://bootstrapped.ventures/easy-affiliate-links/get-the-plugin/',
		);

		return $required_addons;
	}

	/**
	 * Get the value for a specific setting.
	 *
	 * @since    3.0.0
	 * @param    mixed $setting Setting to get the value for.
	 */
	public static function get( $setting ) {
		return self::$bvs->get( $setting );
	}

	/**
	 * Get the default value for a specific setting.
	 *
	 * @since    3.5.0
	 * @param    mixed $setting Setting to get the default for.
	 */
	public static function get_default( $setting ) {
		return self::$bvs->get_default( $setting );
	}

	/**
	 * Update the plugin settings.
	 *
	 * @since    3.0.0
	 * @param    array $settings_to_update Settings to update.
	 */
	public static function update_settings( $settings_to_update ) {
		return self::$bvs->update_settings( $settings_to_update );
	}
}

EAFL_Settings::init();
