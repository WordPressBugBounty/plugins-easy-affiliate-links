<?php

$group_tools = array(
	'id' => 'tools',
	'name' => __( 'Tools', 'easy-affiliate-links' ),
	'icon' => 'sliders',
	'subGroups' => array(
		array(
			'name' => __( 'Find Link Usage', 'easy-affiliate-links' ),
			'settings' => array(
				array(
					'id' => 'find_link_usage_post_types',
					'name' => __( 'Post Types', 'easy-affiliate-links' ),
					'description' => __( 'Which post types do you want to search for link usage?', 'easy-affiliate-links' ),
					'type' => 'dropdownMultiselect',
					'optionsCallback' => function() { return get_post_types( '', 'names' ); },
					'default' => array( 'post', 'page' ),
				),
			),
		),
	),
);
