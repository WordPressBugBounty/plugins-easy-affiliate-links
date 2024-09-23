<?php

$group_permissions = array(
	'id' => 'permissions',
	'name' => __( 'Permissions', 'easy-affiliate-links' ),
	'description' => __( 'Accepts one value only. Set the minimum capability required to access specific features. For example, set to edit_others_posts to provide access to editors and administrators.', 'easy-affiliate-links' ),
	'documentation' => 'https://codex.wordpress.org/Roles_and_Capabilities',
	'icon' => 'lock',
	'settings' => array(
		array(
			'id' => 'manage_capability',
			'name' => __( 'Access to Manage Page', 'easy-affiliate-links' ),
			'type' => 'text',
			'default' => 'manage_options',
			'sanitize' => function( $value ) {
				return preg_replace( '/[,\s]/', '', $value );
			},
		),
		array(
			'id' => 'statistics_capability',
			'name' => __( 'Access to Click Statistics Page', 'easy-affiliate-links' ),
			'type' => 'text',
			'default' => 'manage_options',
			'sanitize' => function( $value ) {
				return preg_replace( '/[,\s]/', '', $value );
			},
		),
		array(
			'id' => 'import_capability',
			'name' => __( 'Access to Import & Export Page', 'easy-affiliate-links' ),
			'type' => 'text',
			'default' => 'manage_options',
			'sanitize' => function( $value ) {
				return preg_replace( '/[,\s]/', '', $value );
			},
		),
		array(
			'id' => 'tools_capability',
			'name' => __( 'Access to Tools Page', 'easy-affiliate-links' ),
			'type' => 'text',
			'default' => 'manage_options',
			'sanitize' => function( $value ) {
				return preg_replace( '/[,\s]/', '', $value );
			},
		),
		array(
			'id' => 'button_capability',
			'name' => __( 'Classic Editor Button', 'easy-affiliate-links' ),
			'description' => __( 'EAFL Icon in the Visual Editor', 'easy-affiliate-links' ),
			'type' => 'text',
			'default' => 'edit_posts',
			'sanitize' => function( $value ) {
				return preg_replace( '/[,\s]/', '', $value );
			},
		),
	),
);
