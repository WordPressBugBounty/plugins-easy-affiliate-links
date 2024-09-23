<?php
/**
 * Template for the plugin settings structure.
 *
 * @link       https://bootstrapped.ventures
 * @since      3.0.0
 *
 * @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/templates/settings
 */

require_once( 'group-general.php' );
require_once( 'group-defaults.php' );
require_once( 'group-shortcode.php' );
require_once( 'group-disclaimer.php' );
require_once( 'group-broken-links.php' );
require_once( 'group-statistics.php' );

require_once( 'group-amazon.php' );

require_once( 'group-permissions.php' );
require_once( 'group-tools.php' );
require_once( 'group-custom-code.php' );

$settings_structure = array(
	array(
		'id'            => 'documentation',
		'name'          => __( 'Documentation', 'easy-affiliate-links' ),
		'description'   => __( 'Easily manage and cloak all your affiliate links with Easy Affiliate Links. Documentation can be found in our Knowledge Base.', 'easy-affiliate-links' ),
		'documentation' => 'https://help.bootstrapped.ventures/collection/133-easy-affiliate-links',
		'icon'          => 'support',
	),
	$group_general,
	$group_defaults,
	$group_shortcode,
	$group_disclaimer,
	$group_broken_links,
	$group_statistics,
	// array( 'header' => __( 'Integrations', 'easy-affiliate-links' ) ),
	// $group_amazon,
	array( 'header' => __( 'Advanced', 'easy-affiliate-links' ) ),
	$group_permissions,
	$group_tools,
	$group_custom_code,
);
