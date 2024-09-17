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
