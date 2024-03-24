<?php

use WPframework\Component\Kernel;

/*
 * This is the bootstrap file for the web application.
 *
 * It loads the necessary files and sets up the environment for the application to run.
 * This includes initializing the Composer autoloader, which is used to load classes and packages.
 */
if ( file_exists( \dirname( __FILE__ ) . '/vendor/autoload.php' ) ) {
    require_once \dirname( __FILE__ ) . '/vendor/autoload.php';
} else {
    exit( 'Cant find the vendor autoload file.' );
}

/**
 * Start and bootstrap the web application.
 *
 * @var Kernel
 */
$http_app = http_component_kernel( __DIR__ );

/*
 * Load constant overrides.
 *
 * This will load constant values that override constants defined in setup.
 */
$http_app->overrides();

/*
 * Configuration settings for your web application.
 *
 * We recommend using the .env file to set these values.
 * The possible values are: 'debug', 'development', 'staging', 'production', or 'secure'.
 * The web application will use either the value of WP_ENVIRONMENT_TYPE or 'production'.
 */
$http_app->init();

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = env( 'DB_PREFIX' );
