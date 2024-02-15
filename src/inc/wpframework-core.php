<?php

/**
 * @wordpress-plugin
 * Plugin Name:       App Framework Core
 * Plugin URI:        https://github.com/devuri/wp-framework
 * Description:       Web Application Framework provides `wpframework_init` action and bootstrap file.
 * Version:           0.0.2
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

if (defined('WP_INSTALLING') && WP_INSTALLING) {
    return;
}

/**
 * Must-use plugins are loaded before normal plugins.
 *
 * Must-use plugins are typically used for critical functionality or site-wide customizations
 * that should always be active which makes this hook a good place to add critical functionality
 */
do_action( 'wpframework_init', getHttpEnv() );

/**
 * Start and load core plugin.
 */
if ( function_exists( 'wpframework_core' ) ) {
    wpframework_core();
}
