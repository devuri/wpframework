<?php

/**
 * @wordpress-plugin
 * Plugin Name:       WP-Framework Core
 * Plugin URI:        https://github.com/devuri/wpframework
 * Description:       Web Application Framework provides `wpframework_init` action and bootstrap file.
 * Version:           0.2
 * Requires at least: 5.3.0
 * Requires PHP:      7.3.5
 * Author:            Uriel
 * Author URI:        https://urielwilson.com
 * Text Domain:       wp-wpframework
 * Domain Path:       languages
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

/**
 * Start and load the framework core plugin.
 */
if ( function_exists( 'wpframeworkCore' ) ) {
    $_wpframework = wpframeworkCore();
    $_wpframework->plugin();
} else {
	$_wpframework = null;
}

/**
 * Must-use plugins are loaded before normal plugins.
 *
 * Must-use plugins are typically used for critical functionality or site-wide customizations
 * that should always be active which makes this hook a good place to add critical functionality
 */
if ( is_object( $_wpframework ) ) {
	do_action( 'wpframework_init', $_wpframework->get_app_options() );
} else {
	do_action( 'wpframework_init' );
}
