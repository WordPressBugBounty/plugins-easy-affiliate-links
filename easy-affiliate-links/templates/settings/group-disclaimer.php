<?php

$group_disclaimer = array(
	'id' => 'disclaimer',
	'name' => __( 'Disclaimer', 'easy-affiliate-links' ),
	'description' => __( 'Automatically show a disclaimer with your affiliate links.', 'easy-affiliate-links' ),
	'icon' => 'text',
	'settings' => array(
		array(
			'id'      => 'automatic_disclaimer',
			'name'    => __( 'Automatic Disclaimer', 'easy-affiliate-links' ),
			'type'    => 'dropdown',
			'options' => array(
				'disabled' => __( 'Do not use an automatic disclaimer', 'easy-affiliate-links' ),
				'append'  => __( 'Append a text disclaimer', 'easy-affiliate-links' ),
				'prepend'  => __( 'Prepend a text disclaimer', 'easy-affiliate-links' ),
				'tooltip'  => __( 'Tooltip Disclaimer', 'easy-affiliate-links' ) . ' (' . __( 'Premium Only', 'easy-affiliate-links' ) . ')',
			),
			'default' => 'disabled',
		),
	),
	'subGroups' => array(
		array(
			'name' => __( 'Appearance', 'easy-affiliate-links' ),
			'dependency' => array(
				'id' => 'automatic_disclaimer',
				'value' => 'disabled',
				'type' => 'inverse',
			),
			'settings' => array(
				array(
					'id'      => 'automatic_disclaimer_text',
					'name'    => __( 'Disclaimer Text', 'easy-affiliate-links' ),
					'type'    => 'richTextarea',
					'default' => '(' . __( 'affiliate link', 'easy-affiliate-links' ) . ')',
				),
				array(
					'id'      => 'automatic_disclaimer_text_style',
					'name'    => __( 'Text Style', 'easy-affiliate-links' ),
					'description' => __( 'Target the eafl-disclaimer class using CSS for even more customizations.', 'easy-affiliate-links' ),
					'type'    => 'dropdown',
					'options' => array(
						'normal' => __( 'Normal', 'easy-affiliate-links' ),
						'small'  => __( 'Small', 'easy-affiliate-links' ),
						'smaller'  => __( 'Smaller', 'easy-affiliate-links' ),
					),
					'default' => 'normal',
					'dependency' => array(
						'id' => 'automatic_disclaimer',
						'value' => 'tooltip',
						'type' => 'inverse',
					),
				),
				array(
					'id'      => 'automatic_disclaimer_tooltip_placement',
					'name'    => __( 'Preferred Tooltip Placement', 'easy-affiliate-links' ),
					'type'    => 'dropdown',
					'options' => array(
						'top' => __( 'Top', 'easy-affiliate-links' ),
						'right'  => __( 'Right', 'easy-affiliate-links' ),
						'bottom'  => __( 'Bottom', 'easy-affiliate-links' ),
						'left'  => __( 'Left', 'easy-affiliate-links' ),
					),
					'default' => 'top',
					'dependency' => array(
						'id' => 'automatic_disclaimer',
						'value' => 'tooltip',
					),
				),
				array(
					'id'      => 'automatic_disclaimer_tooltip_background',
					'name'    => __( 'Tooltip Background Color', 'easy-affiliate-links' ),
					'type'    => 'color',
					'default' => '#333333',
					'dependency' => array(
						'id' => 'automatic_disclaimer',
						'value' => 'tooltip',
					),
				),
				array(
					'id'      => 'automatic_disclaimer_tooltip_text',
					'name'    => __( 'Tooltip Text Color', 'easy-affiliate-links' ),
					'type'    => 'color',
					'default' => '#ffffff',
					'dependency' => array(
						'id' => 'automatic_disclaimer',
						'value' => 'tooltip',
					),
				),
			),
		),
		array(
			'name' => __( 'Display', 'easy-affiliate-links' ),
			'dependency' => array(
				'id' => 'automatic_disclaimer',
				'value' => 'disabled',
				'type' => 'inverse',
			),
			'settings' => array(
				array(
					'id'      => 'automatic_disclaimer_types',
					'name'    => __( 'Types to show the disclaimer for', 'easy-affiliate-links' ),
					'type'    => 'dropdownMultiselect',
					'options' => array(
						'text' => __( 'Text Links', 'easy-affiliate-links' ),
						'html'  => __( 'HTML Code Links', 'easy-affiliate-links' ),
						// 'image'  => __( 'Image Links', 'easy-affiliate-links' ),
					),
					'default' => array( 'text' ),
				),
				array(
					'id'      => 'automatic_disclaimer_display',
					'name'    => __( 'Show Disclaimer for', 'easy-affiliate-links' ),
					'type'    => 'dropdown',
					'options' => array(
						'all' => __( 'All affiliate links', 'easy-affiliate-links' ),
						'include'  => __( 'Affiliate Links in specific categories', 'easy-affiliate-links' ),
						'exclude'  => __( 'Affiliate Links not in specific categories', 'easy-affiliate-links' ),
					),
					'default' => 'all',
				),
				array(
					'id' => 'automatic_disclaimer_categories',
					'name' => __( 'Categories', 'easy-affiliate-links' ),
					'type' => 'dropdownMultiselect',
					'optionsCallback' => function() { return get_terms( array(
						'taxonomy' => 'eafl_category',
						'hide_empty' => false,
						'fields' => 'id=>name',
					) ); },
					'default' => array(),
					'dependency' => array(
						'id' => 'automatic_disclaimer_display',
						'value' => 'all',
						'type' => 'inverse',
					),
				),
			),
		),
	),
);
