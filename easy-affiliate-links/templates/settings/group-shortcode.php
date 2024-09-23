<?php

$group_shortcode = array(
	'id' => 'shortcode',
	'name' => __( 'Shortcode', 'easy-affiliate-links' ),
	'description' => __( 'Customize the link that gets output by the shortcode.', 'easy-affiliate-links' ),
	'icon' => 'link',
	'settings' => array(
		array(
			'id'      => 'use_noopener',
			'name'    => __( 'Use Noopener', 'easy-affiliate-links' ),
			'description' => __( 'Enable to add rel="noopener" to the link output', 'easy-affiliate-links' ),
			'type'    => 'toggle',
			'default' => false,
		),
		array(
			'id'      => 'use_noreferrer',
			'name'    => __( 'Use Noreferrer', 'easy-affiliate-links' ),
			'description' => __( 'Enable to add rel="noreferrer" to the link output', 'easy-affiliate-links' ),
			'type'    => 'toggle',
			'default' => false,
		),
	),
);
