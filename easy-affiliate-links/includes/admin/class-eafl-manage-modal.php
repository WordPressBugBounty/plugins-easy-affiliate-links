<?php
/**
 * Handle the assets for manage and modal.
 *
 * @link       https://bootstrapped.ventures
 * @since      3.0.0
 *
 * @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/includes/admin/manage
 */

/**
 * Handle the assets for manage and modal.
 *
 * @since      3.0.0
 * @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/includes/admin/manage
 * @author     Brecht Vandersmissen <brecht@bootstrapped.ventures>
 */
class EAFL_Manage_Modal {

	/**
	 * Register actions and filters.
	 *
	 * @since    3.0.0
	 */
	public static function init() {
		add_action( 'admin_menu', array( __CLASS__, 'add_manage_page' ), 11 );
		add_action( 'admin_footer', array( __CLASS__, 'add_modal_content' ) );

		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'enqueue' ) );
	}

	/**
	 * Add the manage submenu to the EAFL menu.
	 *
	 * @since    3.0.0
	 */
	public static function add_manage_page() {
		add_submenu_page( 'easyaffiliatelinks', __( 'Manage', 'easy-affiliate-links' ), __( 'Manage', 'easy-affiliate-links' ), EAFL_Settings::get( 'manage_capability' ), 'easyaffiliatelinks', array( __CLASS__, 'manage_page_template' ) );
	}

	/**
	 * Get the template for this submenu.
	 *
	 * @since    3.0.0
	 */
	public static function manage_page_template() {
		echo '<div class="wrap"><div id="eafl-admin-manage">Loading...</div></div>';
	}

	/**
	 * Add modal template to edit screen.
	 *
	 * @since    3.0.0
	 */
	public static function add_modal_content() {
		echo '<div id="eafl-admin-modal"></div>';
	}

	/**
	 * Enqueue stylesheets and scripts.
	 *
	 * @since    3.0.0
	 */
	public static function enqueue() {
		wp_enqueue_style( 'eafl-admin-manage-modal', EAFL_URL . 'dist/admin-manage-modal.css', array(), EAFL_VERSION, 'all' );
		wp_enqueue_script( 'eafl-admin-manage-modal', EAFL_URL . 'dist/admin-manage-modal.js', array( 'eafl-admin' ), EAFL_VERSION, true );

		$localize_data = apply_filters( 'eafl_admin_modal_localize', array(
			'categories' => array_values( self::get_link_categories() ),
			'link' => self::get_new_link(),
			'link_preview' => site_url( '/' . EAFL_Settings::get( 'shortlink_slug' ) . '/' ),
			'options' => array(
				'type' => self::get_type_options(),
				'cloak' => self::get_cloak_options(),
				'target' => self::get_target_options(),
				'redirect_type' => self::get_redirect_type_options(),
				'nofollow' => self::get_nofollow_options(),
			),
			'notices' => EAFL_Notices::get_notices(),
			'settings' => array(
				'default_page_size' => EAFL_Settings::get( 'default_page_size' ),
			),
		) );

		wp_localize_script( 'eafl-admin-manage-modal', 'eafl_admin_manage_modal', $localize_data );
	}

	/**
	 * Get link categories.
	 *
	 * @since    3.0.0
	 */
	public static function get_link_categories() {
		return get_terms( array(
			'taxonomy' => 'eafl_category',
			'hide_empty' => false,
			'count' => true,
		) );
	}

	/**
	 * Get new link.
	 *
	 * @since    3.0.0
	 */
	public static function get_new_link() {
		return apply_filters( 'eafl_new_link', array(
			'name' => '',
			'decription' => '',
			'categories' => array(),
			'type' => 'text',
			'text' => array( '' ),
			'html' => '',
			'classes' => '',
			'slug' => '',
			'cloak' => 'default',
			'target' => 'default',
			'redirect_type' => 'default',
			'nofollow' => 'default',
			'sponsored' => EAFL_Settings::get( 'default_sponsored' ),
			'ugc' => EAFL_Settings::get( 'default_ugc' ),
		) );
	}

	/**
	 * Get all type options.
	 *
	 * @since    3.4.0
	 */
	public static function get_type_options() {
		return array(
			array(
				'value' => 'text',
				'label' => __( 'Text', 'easy-affiliate-links' ),
			),
			array(
				'value' => 'html',
				'label' => __( 'HTML Code', 'easy-affiliate-links' ),
			),
		);
	}

	/**
	 * Get all cloak options.
	 *
	 * @since    3.0.0
	 */
	public static function get_cloak_options() {
		$labels = array(
			'yes' => __( 'Cloak affiliate link', 'easy-affiliate-links' ),
			'no' => __( 'Do not cloak affiliate link', 'easy-affiliate-links' ),
		);

		$default = EAFL_Settings::get( 'default_cloak' );

		$options = array(
			array(
				'value' => 'default',
				'label' => __( 'Use Default', 'easy-affiliate-links' ) . ' (' . $labels[ $default ] . ')',
				'actual' => $default,
			),
		);

		foreach ( $labels as $value => $label ) {
			$options[] = array(
				'value' => $value,
				'label' => $label,
				'actual' => $value,
			);
		}

		return $options;
	}

	/**
	 * Get all target options.
	 *
	 * @since    3.0.0
	 */
	public static function get_target_options() {
		$labels = array(
			'_self' => __( 'Open in same tab', 'easy-affiliate-links' ),
			'_blank' => __( 'Open in new tab', 'easy-affiliate-links' ),
		);

		$default = EAFL_Settings::get( 'default_target' );

		$options = array(
			array(
				'value' => 'default',
				'label' => __( 'Use Default', 'easy-affiliate-links' ) . ' (' . $labels[ $default ] . ')',
				'actual' => $default,
			),
		);

		foreach ( $labels as $value => $label ) {
			$options[] = array(
				'value' => $value,
				'label' => $label,
				'actual' => $value,
			);
		}

		return $options;
	}

	/**
	 * Get all redirect type options.
	 *
	 * @since    3.0.0
	 */
	public static function get_redirect_type_options() {
		$labels = array(
			'301' => __( '301 Permanent', 'easy-affiliate-links' ),
			'302' => __( '302 Temporary', 'easy-affiliate-links' ),
			'307' => __( '307 Temporary', 'easy-affiliate-links' ),
		);

		$default = EAFL_Settings::get( 'default_redirect_type' );

		$options = array(
			array(
				'value' => 'default',
				'label' => __( 'Use Default', 'easy-affiliate-links' ) . ' (' . $labels[ $default ] . ')',
				'actual' => $default,
			),
		);

		foreach ( $labels as $value => $label ) {
			$options[] = array(
				'value' => $value,
				'label' => $label,
				'actual' => $value,
			);
		}

		return $options;
	}

	/**
	 * Get all nofollow options.
	 *
	 * @since    3.0.0
	 */
	public static function get_nofollow_options() {
		$labels = array(
			'follow' => __( 'Do not add nofollow attribute', 'easy-affiliate-links' ),
			'nofollow' => __( 'Add nofollow attribute', 'easy-affiliate-links' ),
		);

		$default = EAFL_Settings::get( 'default_nofollow' );

		$options = array(
			array(
				'value' => 'default',
				'label' => __( 'Use Default', 'easy-affiliate-links' ) . ' (' . $labels[ $default ] . ')',
				'actual' => $default,
			),
		);

		foreach ( $labels as $value => $label ) {
			$options[] = array(
				'value' => $value,
				'label' => $label,
				'actual' => $value,
			);
		}

		return $options;
	}
}

EAFL_Manage_Modal::init();
