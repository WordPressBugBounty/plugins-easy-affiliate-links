<?php
/**
 * Migration to the new settings component.
 *
 * @link       https://bootstrapped.ventures
 * @since      3.0.0
 *
 * @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/includes/admin/migrations
 */

// Migrate settings.
$old = get_option( 'eafl_option', array() );

// Make sure redirect type is stored as string and not an int.
$redirect_type = EAFL_Settings::get( 'default_redirect_type' );
$settings = array(
	'default_redirect_type' => '' . $redirect_type,
);

EAFL_Settings::update_settings( $settings );
