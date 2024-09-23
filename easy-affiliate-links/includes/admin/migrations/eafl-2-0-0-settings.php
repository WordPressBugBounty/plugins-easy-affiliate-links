<?php
/**
 * Migration to the new settings system.
 *
 * @link       https://bootstrapped.ventures
 * @since      2.0.0
 *
 * @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/includes/admin/migrations
 */

// Delete cache.
delete_option( 'wpurp_cache' );

// Migrate settings.
$old = get_option( 'eafl_option', array() );

$settings = array();
$settings['shortlink_slug'] = isset( $old['link_slug'] ) ? $old['link_slug'] : 'recommends';
$settings['default_target'] = isset( $old['link_target'] ) ? $old['link_target'] : '_blank';
$settings['default_redirect_type'] = isset( $old['link_redirect_type'] ) ? '' . $old['link_redirect_type'] : '301';

$nofollow = isset( $old['link_nofollow'] ) ? $old['link_nofollow'] : '0';
$settings['default_nofollow'] = '1' === $nofollow ? 'nofollow' : 'follow';

$clicks = isset( $old['statistics_enabled'] ) ? $old['statistics_enabled'] : '1';
$settings['enable_clicks'] = '1' === $clicks ? true : false;

$settings['button_capability'] = isset( $old['editor_button_capability'] ) ? $old['editor_button_capability'] : 'edit_posts';
$settings['public_css'] = isset( $old['custom_code_public_css'] ) ? $old['custom_code_public_css'] : '';

EAFL_Settings::update_settings( $settings );
