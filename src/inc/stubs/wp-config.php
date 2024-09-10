<?php

use WPframework\Component\App;

/*
 * This is the bootstrap file for the web application.
 *
 * It loads the necessary files and sets up the environment for the application to run.
 * This includes initializing the Composer autoloader, which is used to load classes and packages.
 */
if ( file_exists( \dirname( __FILE__ ) . '/../vendor/autoload.php' ) ) {
    require_once \dirname( __FILE__ ) . '/../vendor/autoload.php';
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

/* That's all, stop editing! Happy publishing. */

App::init( dirname( __DIR__ ) )->overrides()->init( RAYDIUM_ENVIRONMENT_TYPE );

$table_prefix = env( 'DB_PREFIX' );

if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

require_once ABSPATH . 'wp-settings.php';
