<?php

$group_general = array(
	'id' => 'general',
	'name' => __( 'General', 'easy-affiliate-links' ),
	'icon' => 'cog',
	'subGroups' => array(
		array(
			'name' => __( 'Links', 'easy-affiliate-links' ),
			'settings' => array(
				array(
					'id' => 'shortlink_slug',
					'name' => __( 'Shortlink Slug', 'easy-affiliate-links' ),
					'description' => __( 'Important: changing your slug will break any existing links to the old slug.', 'easy-affiliate-links' ),
					'type' => 'text',
					'default' => 'recommends',
				),
				array(
					'id' => 'pass_query_parameters',
					'name' => __( 'Pass Along Query Parameters', 'easy-affiliate-links' ),
					'description' => __( 'Wether query parameters added to the shortlink should get passed along to the destination URL.', 'easy-affiliate-links' ),
					'required' => 'premium',
					'type' => 'toggle',
					'default' => false,
				),
			),
		),
		array(
			'name' => __( 'Manage Page', 'easy-affiliate-links' ),
			'settings' => array(
				array(
					'id'      => 'default_page_size',
					'name'    => __( 'Default Page Size', 'easy-affiliate-links' ),
					'description' => __( 'Default page size to use on the Affiliate Links > Manage page.', 'easy-affiliate-links' ),
					'type'    => 'dropdown',
					'options' => array(
						'5' => '5',
						'10' => '10',
						'20' => '20',
						'25' => '25',
						'50' => '50',
						'100' => '100',
						'500' => '500',
					),
					'default' => '25',
				),
			),
		),
	),
);
