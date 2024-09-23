<?php

$group_defaults = array(
	'id' => 'defaults',
	'name' => __( 'Defaults', 'easy-affiliate-links' ),
	'description' => __( 'Default options to use for your affiliate links.', 'easy-affiliate-links' ),
	'icon' => 'edit',
	'subGroups' => array(
		array(
			'name' => __( 'Defaults affecting existing links', 'easy-affiliate-links' ),
			'description' => __( 'Changing the default setting here will affect all links that have this option set to "Use default".', 'easy-affiliate-links' ),
			'settings' => array(
				array(
					'id'      => 'default_cloak',
					'name'    => __( 'Default Cloaking', 'easy-affiliate-links' ),
					'type'    => 'dropdown',
					'options' => array(
						'yes' => __( 'Cloak affiliate link', 'easy-affiliate-links' ),
						'no'  => __( 'Do not cloak affiliate link', 'easy-affiliate-links' ),
					),
					'default' => 'yes',
				),
				array(
					'id'      => 'default_target',
					'name'    => __( 'Default Target', 'easy-affiliate-links' ),
					'type'    => 'dropdown',
					'options' => array(
						'_self'  => __( 'Open in same tab', 'easy-affiliate-links' ),
						'_blank' => __( 'Open in new tab', 'easy-affiliate-links' ),
					),
					'default' => '_blank',
				),
				array(
					'id'      => 'default_redirect_type',
					'name'    => __( 'Default Redirect Type', 'easy-affiliate-links' ),
					'type'    => 'dropdown',
					'options' => array(
						'301' => __( '301 Permanent', 'easy-affiliate-links' ),
						'302' => __( '302 Temporary', 'easy-affiliate-links' ),
						'307' => __( '307 Temporary', 'easy-affiliate-links' ),
					),
					'default' => '301',
				),
				array(
					'id'      => 'default_nofollow',
					'name'    => __( 'Default Use Nofollow', 'easy-affiliate-links' ),
					'type'    => 'dropdown',
					'options' => array(
						'nofollow'  => __( 'Add the nofollow attribute to affiliate links', 'easy-affiliate-links' ),
						'follow'    => __( 'Do NOT add nofollow attribute to affiliate links', 'easy-affiliate-links' ),
					),
					'default' => 'nofollow',
				),
			),
		),
		array(
			'name' => __( 'Defaults for new links', 'easy-affiliate-links' ),
			'description' => __( 'Changing these will not affect existing links, only the values selected by default for new links.', 'easy-affiliate-links' ),
			'settings' => array(
				array(
					'id'      => 'default_sponsored',
					'name'    => __( 'Default use "sponsored" attribute', 'easy-affiliate-links' ),
					'type'    => 'toggle',
					'default' => false,
				),
				array(
					'id'      => 'default_ugc',
					'name'    => __( 'Default use "ugc" attribute', 'easy-affiliate-links' ),
					'type'    => 'toggle',
					'default' => false,
				),
			),
		),
	),
);
