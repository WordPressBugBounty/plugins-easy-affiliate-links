<?php

$group_broken_links = array(
	'id' => 'broken-links',
	'name' => __( 'Broken Links', 'easy-affiliate-links' ),
	'icon' => 'unlink',
	'required' => 'premium',
	'subGroups' => array(
		array(
			'name' => __( 'Automatic Broken Links Checker', 'easy-affiliate-links' ),
			'settings' => array(
				array(
					'id'      => 'enable_broken_links_cron',
					'name'    => __( 'Automatically Check Links', 'easy-affiliate-links' ),
					'description' => __( 'Run a cron job that will automatically check for broken links in the background.', 'easy-affiliate-links' ),
					'type'    => 'toggle',
					'default' => false,
				),
				array(
					'id'      => 'broken_links_cron_interval',
					'name'    => __( 'Number of links to check per day', 'easy-affiliate-links' ),
					'description' => __( 'The number of links to check per day will affect the number of times the background cron job is run.', 'easy-affiliate-links' ),
					'type'    => 'number',
					'default' => 500,
					'dependency' => array(
						'id' => 'enable_broken_links_cron',
						'value' => true,
					),
				),
				array(
					'id'      => 'broken_links_send_email',
					'name'    => __( 'Email when broken link is found', 'easy-affiliate-links' ),
					'description' => __( 'Send an email if the automatic check finds a broken link.', 'easy-affiliate-links' ),
					'type'    => 'toggle',
					'default' => false,
					'dependency' => array(
						'id' => 'enable_broken_links_cron',
						'value' => true,
					),
				),
				array(
					'id'      => 'broken_links_send_email_to',
					'name'    => __( 'Address to email', 'easy-affiliate-links' ),
					'description' => __( 'Make sure WordPress is able to send emails using the "Check Email" plugin.', 'easy-affiliate-links' ),
					'type'    => 'text',
					'default' => get_option( 'admin_email' ),
					'dependency' => array(
						array(
							'id' => 'enable_broken_links_cron',
							'value' => true,
						),
						array(
							'id' => 'broken_links_send_email',
							'value' => true,
						)
					),
				),
				array(
					'id'      => 'broken_links_send_email_frequency',
					'name'    => __( 'Email Frequency', 'easy-affiliate-links' ),
					'description' => __( 'How often you want to get emailed if problems are found.', 'easy-affiliate-links' ),
					'type'    => 'dropdown',
					'options' => array(
						'instant' => __( 'As soon as a broken link is found', 'easy-affiliate-links' ),
						'hourly' => __( 'Once per hour', 'easy-affiliate-links' ),
						'daily' => __( 'Once per day', 'easy-affiliate-links' ),
						'weekly' => __( 'Once per week', 'easy-affiliate-links' ),
					),
					'default' => 'daily',
					'dependency' => array(
						array(
							'id' => 'enable_broken_links_cron',
							'value' => true,
						),
						array(
							'id' => 'broken_links_send_email',
							'value' => true,
						)
					),
				),
			),
		),
	),
);
