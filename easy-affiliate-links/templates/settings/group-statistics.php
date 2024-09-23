<?php

$group_statistics = array(
	'id' => 'statistics',
	'name' => __( 'Statistics', 'easy-affiliate-links' ),
	'icon' => 'button-click',
	'settings' => array(
		array(
			'id'      => 'enable_clicks',
			'name'    => __( 'Count Clicks', 'easy-affiliate-links' ),
			'description' => __( 'Register all clicks on affiliate links', 'easy-affiliate-links' ),
			'type'    => 'toggle',
			'default' => true,
		),
		array(
			'id'      => 'store_ip_address',
			'name'    => __( 'Store IP Address', 'easy-affiliate-links' ),
			'description' => __( 'You might need to disable this to comply with GPDR or other privacy regulations. The IP adress will then be stored as a hash.', 'easy-affiliate-links' ),
			'type'    => 'toggle',
			'default' => true,
			'dependency' => array(
				'id' => 'enable_clicks',
				'value' => true,
			),
		),
	),
	'subGroups' => array(
		array(
			'name' => __( 'Exclude Clicks', 'easy-affiliate-links' ),
			'dependency' => array(
				'id' => 'enable_clicks',
				'value' => true,
			),
			'settings' => array(
				array(
					'id'      => 'statistics_remove_user_roles',
					'name'    => __( 'Remove Clicks By Role', 'easy-affiliate-links' ),
					'description' => __( 'Remove clicks by logged in users with these roles.', 'easy-affiliate-links' ),
					'type'    => 'dropdownMultiselect',
					'optionsCallback' => function() {
						if ( ! function_exists( 'get_editable_roles' ) ) {
							// TODO Better solution?
							require_once ABSPATH . 'wp-admin/includes/user.php';
						}

						$roles = get_editable_roles();
						$role_options = array();

						foreach ( $roles as $role => $options ) {
							$role_options[ $role ] = $options['name'];
						}

						return $role_options;
					},
					'default' => array(
						'administrator',
					),
				),
				array(
					'id'      => 'statistics_exclude_ips',
					'name'    => __( 'Exclude IPs', 'easy-affiliate-links' ),
					'description' => __( 'Remove clicks by these IP addresses. One address or range per line.', 'easy-affiliate-links' ),
					'type'    => 'textarea',
					'default' => '',
				),
			),
		),
		array(
			'name' => __( 'Charts', 'easy-affiliate-links' ),
			'required' => 'premium',
			'settings' => array(
				array(
					'id'      => 'statistics_charts_max_request',
					'name'    => __( 'Max # Clicks per request', 'easy-affiliate-links' ),
					'description' => __( "Decrease if you're experiencing memory problems when loading statistics charts. Defaults to 5000.", 'easy-affiliate-links' ),
					'type'    => 'number',
					'default' => '5000',
				),
			),
		),
	),
);
