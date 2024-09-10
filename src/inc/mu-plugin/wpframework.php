<?php

/**
 * Plugin Name:       WP-Framework Core
 * Plugin URI:        https://github.com/devuri/wpframework
 * Description:       Framework Core with `wpframework_init` hook.
 * Version:           0.4
 * Requires at least: 5.3.0
 * Requires PHP:      7.3.5
 * Author:            uriel
 * License:           GPLv2
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Network: true
 */

if ( ! \defined( 'ABSPATH' ) ) {
    exit;
}

if ( defined( 'WP_INSTALLING' ) && WP_INSTALLING ) {
    return;
}

if ( ! defined( 'APP_TENANT_ID' ) ) {
    define( 'APP_TENANT_ID', null );
}

/**
 * Must-use plugins are loaded before normal plugins.
 *
 * Must-use plugins are typically used for critical functionality or site-wide customizations
 * that should always be active which makes this hook a good place to add critical functionality
 */
do_action( 'wpframework_init' );

// custom theme directory.
if ( \defined( 'APP_THEME_DIR' ) ) {
	register_theme_directory( APP_THEME_DIR );
}

// Missing theme fix.
$theme_info = _framework_current_theme_info();
if ( false === $theme_info['available'] ) {
	$active_theme = wp_get_theme();
	WPframework\Component\Terminate::exit(
		[ $theme_info['error_message'] . ' -> ' . $active_theme->template ]
	);
}

if ( is_multitenant_app() ) {
	// separate uploads for multi tenant.
	add_filter( 'upload_dir', 'set_multitenant_upload_directory' );
}

if ( env( 'WP_ENVIRONMENT_TYPE' ) && env( 'WPENV_AUTO_LOGIN_SECRET_KEY' ) ) {
    WPframework\Component\AutoLogin::init(
		env( 'WPENV_AUTO_LOGIN_SECRET_KEY' ),
		env( 'WP_ENVIRONMENT_TYPE' )
	);
}

add_filter( 'admin_footer_text', '_framework_footer_label' );
