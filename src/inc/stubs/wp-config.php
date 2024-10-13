<?php

use WPframework\App;

/*
 * This is the bootstrap file for the web application.
 *
 * It loads the necessary files and sets up the environment for the application to run.
 * This includes initializing the Composer autoloader, which is used to load classes and packages.
 */
if (file_exists(dirname(__DIR__) . '/vendor/autoload.php')) {
    require_once dirname(__DIR__) . '/vendor/autoload.php';
} else {
    exit('Cannot find the vendor autoload file.');
}

/*
 * Override for .env setup of `WP_ENVIRONMENT_TYPE`.
 *
 * This is optional; if you prefer to use the .env file, set this to null or remove it.
 *
 * @var string|null RAYDIUM_ENVIRONMENT_TYPE The environment type, can be null to use the .env file setup.
 */
if (! defined('RAYDIUM_ENVIRONMENT_TYPE')) {
    define('RAYDIUM_ENVIRONMENT_TYPE', null);
}

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
 */
define( 'WP_DEBUG', false );

/* That's all, stop editing! Happy publishing. */

App::init(dirname(__DIR__))->app(RAYDIUM_ENVIRONMENT_TYPE);

// Set the table prefix.
$table_prefix = env('DB_PREFIX');

// Define ABSPATH.
if (! defined('ABSPATH')) {
    define('ABSPATH', __DIR__ . '/');
}

// Load WordPress settings
require_once ABSPATH . 'wp-settings.php';
