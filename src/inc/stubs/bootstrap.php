<?php

use WPframework\Component\App;

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

/*
 * Override for .env setup of `WP_ENVIRONMENT_TYPE`.
 *
 * This is optional; if you prefer to use the .env file, set this to null or remove it.
 *
 * @var string|null RAYDIUM_ENVIRONMENT_TYPE The environment type, can be null to use the .env file setup.
 */
if ( ! defined( 'RAYDIUM_ENVIRONMENT_TYPE' ) ) {
	define( 'RAYDIUM_ENVIRONMENT_TYPE', null );
}

/**
 * Start and bootstrap the web application.
 */
$raydium_http = App::init( __DIR__ );

/*
 * Load constant overrides.
 *
 * This will load constant values that override constants defined in setup.
 */
$raydium_http->overrides();

/*
 * Configuration settings for your web application.
 *
 * We recommend using the .env file to set these values.
 * The possible values are: 'debug', 'development', 'staging', 'production', or 'secure'.
 * The web application will use either the value of WP_ENVIRONMENT_TYPE or 'production'.
 * By default the value `RAYDIUM_ENVIRONMENT_TYPE` constant is used.
 * `RAYDIUM_ENVIRONMENT_TYPE` defaults to `null` if it is not set.
 */
$raydium_http->init( RAYDIUM_ENVIRONMENT_TYPE );

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = env( 'DB_PREFIX' );
