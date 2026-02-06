<?php
/**
 * Template for the plugin settings structure.
 *
 * @link       https://bootstrapped.ventures
 * @since      3.8.0
 *
 * @package    Easy_Affiliate_Links
 * @subpackage Easy_Affiliate_Links/templates/settings
 */

$amazon_stores = array(
	'australia' => array(
		'label' => 'Australia',
		'host' => 'webservices.amazon.com.au',
		'region' => 'us-west-2',
		'marketplace' => 'www.amazon.com.au',
	),
	'belgium' => array(
		'label' => 'Belgium',
		'host' => 'webservices.amazon.com.be',
		'region' => 'eu-west-1',
		'marketplace' => 'www.amazon.com.be',
	),
	'brazil' => array(
		'label' => 'Brazil',
		'host' => 'webservices.amazon.com.br',
		'region' => 'us-east-1',
		'marketplace' => 'www.amazon.com.br',
	),
	'canada' => array(
		'label' => 'Canada',
		'host' => 'webservices.amazon.ca',
		'region' => 'us-east-1',
		'marketplace' => 'www.amazon.ca',
	),
	'egypt' => array(
		'label' => 'Egypt',
		'host' => 'webservices.amazon.eg',
		'region' => 'eu-west-1',
		'marketplace' => 'www.amazon.eg',
	),
	'france' => array(
		'label' => 'France',
		'host' => 'webservices.amazon.fr',
		'region' => 'eu-west-1',
		'marketplace' => 'www.amazon.fr',
	),
	'germany' => array(
		'label' => 'Germany',
		'host' => 'webservices.amazon.de',
		'region' => 'eu-west-1',
		'marketplace' => 'www.amazon.de',
	),
	'india' => array(
		'label' => 'India',
		'host' => 'webservices.amazon.in',
		'region' => 'eu-west-1',
		'marketplace' => 'www.amazon.in',
	),
	'italy' => array(
		'label' => 'Italy',
		'host' => 'webservices.amazon.it',
		'region' => 'eu-west-1',
		'marketplace' => 'www.amazon.it',
	),
	'japan' => array(
		'label' => 'Japan',
		'host' => 'webservices.amazon.co.jp',
		'region' => 'us-west-2',
		'marketplace' => 'www.amazon.co.jp',
	),
	'mexico' => array(
		'label' => 'Mexico',
		'host' => 'webservices.amazon.com.mx',
		'region' => 'us-east-1',
		'marketplace' => 'www.amazon.com.mx',
	),
	'netherlands' => array(
		'label' => 'Netherlands',
		'host' => 'webservices.amazon.nl',
		'region' => 'eu-west-1',
		'marketplace' => 'www.amazon.nl',
	),
	'poland' => array(
		'label' => 'Poland',
		'host' => 'webservices.amazon.pl',
		'region' => 'eu-west-1',
		'marketplace' => 'www.amazon.pl',
	),
	'singapore' => array(
		'label' => 'Singapore',
		'host' => 'webservices.amazon.sg',
		'region' => 'us-west-2',
		'marketplace' => 'www.amazon.sg',
	),
	'saudi_arabia' => array(
		'label' => 'Saudi Arabia',
		'host' => 'webservices.amazon.sa',
		'region' => 'eu-west-1',
		'marketplace' => 'www.amazon.sa',
	),
	'spain' => array(
		'label' => 'Spain',
		'host' => 'webservices.amazon.es',
		'region' => 'eu-west-1',
		'marketplace' => 'www.amazon.es',
	),
	'sweden' => array(
		'label' => 'Sweden',
		'host' => 'webservices.amazon.se',
		'region' => 'eu-west-1',
		'marketplace' => 'www.amazon.se',
	),
	'turkey' => array(
		'label' => 'Turkey',
		'host' => 'webservices.amazon.com.tr',
		'region' => 'eu-west-1',
		'marketplace' => 'www.amazon.com.tr',
	),
	'united_arab_emirates' => array(
		'label' => 'United Arab Emirates',
		'host' => 'webservices.amazon.ae',
		'region' => 'eu-west-1',
		'marketplace' => 'www.amazon.ae',
	),
	'united_kingdom' => array(
		'label' => 'United Kingdom',
		'host' => 'webservices.amazon.co.uk',
		'region' => 'eu-west-1',
		'marketplace' => 'www.amazon.co.uk',
	),
	'united_states' => array(
		'label' => 'United States',
		'host' => 'webservices.amazon.com',
		'region' => 'us-east-1',
		'marketplace' => 'www.amazon.com',
	),
);

$amazon_stores_dropdown = array_map( function( $store ) {
	return $store['label'];
}, $amazon_stores );

$group_amazon = array(
	'id' => 'amazon',
	'icon' => 'shopping-cart',
	'name' => 'Amazon',
	'required' => 'premium',
	'description' => __( 'Use the Amazon Product API to easily search for Amazon products to use as affiliate links.', 'easy-affiliate-links' ),
	'documentation' => 'https://bootstrapped.ventures/easy-affiliate-links/amazon-product-api-links/',
	'settings' => array(
		array(
			'id' => 'amazon_store',
			'name' => __( 'Amazon Store', 'easy-affiliate-links' ),
			'description' => __( 'The Amazon store to use for your affiliate links.', 'easy-affiliate-links' ),
			'type' => 'dropdown',
			'options' => $amazon_stores_dropdown,
			'default' => 'united_states',
		),
		array(
			'id' => 'amazon_partner_tag',
			'name' => __( 'Amazon Store ID', 'easy-affiliate-links' ),
			'description' => __( 'Make sure this is the partner tag or tracking ID for the store selected above.', 'easy-affiliate-links' ),
			'type' => 'text',
			'default' => '',
		),
		array(
			'id' => 'amazon_api_type',
			'name' => __( 'API Type', 'easy-affiliate-links' ),
			'description' => __( 'Choose which Amazon API to use. "Automatically Switch" will detect which credentials you have filled in and use Creators API if available, with PA-API as fallback.', 'easy-affiliate-links' ),
			'type' => 'dropdown',
			'options' => array(
				'auto' => __( 'Automatically Switch', 'easy-affiliate-links' ),
				'paapi' => __( 'PA-API (Product Advertising API)', 'easy-affiliate-links' ),
				'creators' => __( 'Creators API', 'easy-affiliate-links' ),
			),
			'default' => 'auto',
		),
	),
	'subGroups' => array(
		array(
			'name' => __( 'PA-API Details', 'easy-affiliate-links' ),
			'description' => __( 'Your Amazon Product Advertising API (PA-API) credentials.', 'easy-affiliate-links' ),
			'settings' => array(
				array(
					'id' => 'amazon_access_key',
					'name' => __( 'Amazon Access Key', 'easy-affiliate-links' ),
					'type' => 'text',
					'default' => '',
				),
				array(
					'id' => 'amazon_secret_key',
					'name' => __( 'Amazon Secret Key', 'easy-affiliate-links' ),
					'type' => 'text',
					'default' => '',
				),
			),
			'dependency' => array(
				'id' => 'amazon_api_type',
				'value' => 'creators',
				'type' => 'inverse',
			),
		),
		array(
			'name' => __( 'Creators API Details', 'easy-affiliate-links' ),
			'description' => __( 'Your Amazon Creators API credentials. Get these from Associates Central > Tools > CreatorsAPI.', 'easy-affiliate-links' ),
			'settings' => array(
				array(
					'id' => 'amazon_credential_id',
					'name' => __( 'Credential ID', 'easy-affiliate-links' ),
					'type' => 'text',
					'default' => '',
				),
				array(
					'id' => 'amazon_credential_secret',
					'name' => __( 'Credential Secret', 'easy-affiliate-links' ),
					'type' => 'text',
					'default' => '',
				),
				array(
					'id' => 'amazon_credential_version',
					'name' => __( 'Credential Version', 'easy-affiliate-links' ),
					'description' => __( 'Your credential version based on region: 2.1 for North America, 2.2 for Europe, 2.3 for Far East.', 'easy-affiliate-links' ),
					'type' => 'text',
					'default' => '',
				),
			),
			'dependency' => array(
				'id' => 'amazon_api_type',
				'value' => 'paapi',
				'type' => 'inverse',
			),
		),
	),
);
