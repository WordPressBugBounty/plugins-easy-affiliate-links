<?php

$group_custom_code = array(
	'id' => 'custom_code',
	'name' => __( 'Custom Code', 'easy-affiliate-links' ),
	'icon' => 'code',
	'settings' => array(
		array(
			'id' => 'output_public_css',
			'name' => __( 'Output Public CSS', 'easy-affiliate-links' ),
			'description' => __( 'Disabling this can affect the styling of our affiliate links.', 'easy-affiliate-links' ),
			'type' => 'toggle',
			'default' => true,
		),
		array(
			'id' => 'public_css',
			'name' => __( 'Public CSS', 'easy-affiliate-links' ),
			'description' => __( 'Use your own custom CSS for styling affiliate links.', 'easy-affiliate-links' ),
			'type' => 'code',
			'code' => 'css',
			'default' => '',
			'dependency' => array(
				'id' => 'output_public_css',
				'value' => true,
			),
		),
	),
);
