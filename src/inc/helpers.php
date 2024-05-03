<?php

use Defuse\Crypto\Key;
use Dotenv\Dotenv;
use Dotenv\Exception\InvalidPathException;
use Symfony\Component\Filesystem\Filesystem;
use Urisoft\DotAccess;
use Urisoft\Encryption;
use Urisoft\Env;
use WPframework\Component\App;
use WPframework\Component\EnvGenerator;
use WPframework\Component\Framework;
use WPframework\Component\Http\Asset;
use WPframework\Component\Http\HttpFactory;
use WPframework\Component\Http\Tenancy;
use WPframework\Component\Terminate;

// @codingStandardsIgnoreFile.

if ( ! \function_exists( 'asset' ) ) {
    /**
     * The Asset url.
     *
     * You can configure the asset URL by setting the ASSET_URL in your .env
     * Or optionally in the main config file.
     *
     * @param string      $asset path to the asset like: "/images/thing.png"
     * @param null|string $path
     *
     * @return string
     */
    function asset( string $asset, ?string $path = null ): string
    {
        return Asset::url( $asset, $path );
    }
}

if ( ! \function_exists( 'assetUrl' ) ) {
    /**
     * The Asset url only.
     *
     * @param null|string $path
     *
     * @return string
     */
    function assetUrl( ?string $path = null ): string
    {
        return Asset::url( '/', $path );
    }
}

/**
 * Retrieves a sanitized, and optionally encrypted or modified, environment variable by name.
 *
 * @param string $name       The name of the environment variable to retrieve.
 * @param mixed  $default    Default value to return if the environment variable is not set
 * @param bool   $encrypt    Indicate that the value should be encrypted. Defaults to false.
 * @param bool   $strtolower Whether to convert the retrieved value to lowercase. Defaults to false.
 *
 * @throws InvalidArgumentException If the requested environment variable name is not in the whitelist
 *                                  or if encryption is requested but the encryption path is not defined.
 *
 * @return mixed The sanitized environment variable value, possibly encrypted or typecast,
 *               or transformed to lowercase if specified.
 */
function env( $name, $default = null, $encrypt = false, $strtolower = false )
{
    $encryptionPath = \defined('APP_PATH') ? APP_PATH : APP_DIR_PATH;

    // Instance of the Env class with your predefined settings
    static $env = null;
    if (null === $env) {
        $env = new Env( env_whitelist(), $encryptionPath, false );
    }

    // Get the environment variable value
    $env_var = null;

    try {
        $env_var = $env->get($name, $default, $encrypt, $strtolower);
    } catch (Exception $e) {
        $debug = [
            'path'      => $encryptionPath,
            'line'      => __LINE__,
            'exception' => $e,
            'invalidfile' => "Missing env file: {$e->getMessage()}",
        ];

        Terminate::exit( [ "Missing env() var: {$e->getMessage()}", 500, $debug ] );
    }

    return $env_var;
}

if ( ! \function_exists( 'app_kernel' ) ) {
    /**
     * Initializes the App Kernel with optional multi-tenant support.
     *
     * Sets up the application kernel based on the provided application directory path.
     * In multi-tenant configurations, it dynamically adjusts the environment based on
     * the current HTTP host and tenant-specific settings. It ensures all required
     * environment variables for the landlord (main tenant) are set and terminates
     * execution with an error message if critical configurations are missing or if
     * the tenant's domain is not recognized.
     *
     * @param string $app_path     The base directory path of the application (e.g., __DIR__).
     * @param string $options_file Optional. The configuration filename, defaults to 'app'.
     *
     * @throws Exception If there are issues loading environment variables or initializing the App.
     * @throws Exception If required multi-tenant environment variables are missing or if the tenant's domain is not recognized.
     *
     * @return WPframework\Component\Kernel The initialized application kernel.
     */
    function http_component_kernel( string $app_path, string $options_file = 'app' ): WPframework\Component\Kernel
    {
        // load constants early.
        if ( ! \defined('SITE_CONFIGS_DIR') ) {
            \define( 'SITE_CONFIGS_DIR', 'configs');
        }

        if ( ! \defined('APP_DIR_PATH') ) {
            \define( 'APP_DIR_PATH', $app_path );
        }

        if ( ! \defined('APP_HTTP_HOST') ) {
            \define( 'APP_HTTP_HOST', HttpFactory::init()->get_http_host() );
        }

        $app_options = [];
        $supported_env_files =  _supported_env_files();

        // Filters out environment files that do not exist to avoid warnings.
        $_env_files = _env_files_filter( $supported_env_files, APP_DIR_PATH );

        // load env from dotenv early.
        $_dotenv = Dotenv::createImmutable( APP_DIR_PATH, $_env_files, true );

        try {
            $_dotenv->load();
        } catch ( InvalidPathException $e ) {
            try_regenerate_env_file( APP_DIR_PATH, APP_HTTP_HOST, $_env_files );

            $debug = [
                'path'      => APP_DIR_PATH,
                'line'      => __LINE__,
                'exception' => $e,
                'invalidfile' => "Missing env file: {$e->getMessage()}",
            ];

            Terminate::exit( [ "Missing env file: {$e->getMessage()}", 500, $debug ] );
        } catch ( Exception $e ) {
            $debug = [
                'path'      => APP_DIR_PATH,
                'line'      => __LINE__,
                'exception' => $e,
            ];
            Terminate::exit( [ $e->getMessage(), 500, $debug ] );
        }

        /**
         * Handle multi-tenant setups.
         *
         * @var Tenancy
         */
        $tenancy = new Tenancy( APP_DIR_PATH, SITE_CONFIGS_DIR );
        $tenancy->initialize( $_dotenv );

        try {
            $app = new App( APP_DIR_PATH, SITE_CONFIGS_DIR, $options_file );
        } catch ( Exception $e ) {
            $debug = [
                'path'   => APP_DIR_PATH,
                'line'   => __LINE__,
                'exception'   => $e,
            ];
            Terminate::exit( [ 'Framework Initialization Error:', 500, $debug ] );
        }

        // @phpstan-ignore-next-line
        return $app->kernel();
    }
}

if ( ! \function_exists( 'wpframeworkCore' ) ) {
    /**
     * Start and load core plugin.
     *
     * @return null|Framework
     */
    function wpframeworkCore(): ?Framework
    {
        if ( ! \defined( 'ABSPATH' ) ) {
            exit;
        }

        return _wpframework();
    }
}

if ( ! \function_exists( 'wpInstalledPlugins' ) ) {
    /**
     * Get installed plugins.
     *
     * @return string[]
     *
     * @psalm-return list<string>
     */
    function wpInstalledPlugins(): array
    {
        $plugins = get_plugins();

        $plugin_slugs = [];

        foreach ( $plugins as $key => $plugin ) {
            $slug = explode( '/', $key );

            // Add the slug to the array
            $plugin_slugs[] = '"wpackagist-plugin/' . $slug[0] . '": "*",';
        }

        return $plugin_slugs;
    }
}// end if

if ( ! \function_exists( 'appConfig' ) ) {
    /**
     * Get default app config values.
     *
     * @return (null|bool|mixed|(mixed|(mixed|string)[]|true)[]|string)[]
     *
     * @psalm-return array{security: array{'brute-force': true, 'two-factor': true, 'no-pwned-passwords': true, 'admin-ips': array<empty, empty>}, mailer: array{brevo: array{apikey: mixed}, postmark: array{token: mixed}, sendgrid: array{apikey: mixed}, mailerlite: array{apikey: mixed}, mailgun: array{domain: mixed, secret: mixed, endpoint: mixed, scheme: 'https'}, ses: array{key: mixed, secret: mixed, region: mixed}}, sudo_admin: mixed, sudo_admin_group: null, web_root: 'public', s3uploads: array{bucket: mixed, key: mixed, secret: mixed, region: mixed, 'bucket-url': mixed, 'object-acl': mixed, expires: mixed, 'http-cache': mixed}, asset_dir: 'assets', content_dir: 'app', plugin_dir: 'plugins', mu_plugin_dir: 'mu-plugins', sqlite_dir: 'sqlitedb', sqlite_file: '.sqlite-wpdatabase', default_theme: 'brisko', disable_updates: true, can_deactivate: false, theme_dir: 'templates', error_handler: null, redis: array{disabled: mixed, host: mixed, port: mixed, password: mixed, adminbar: mixed, 'disable-metrics': mixed, 'disable-banners': mixed, prefix: mixed, database: mixed, timeout: mixed, 'read-timeout': mixed}, publickey: array{'app-key': mixed}}
     */
    function appConfig( ?string $file_path = null, ?string $filename = null ): array
    {
        $site_configs_dir = site_configs_dir();
        $default_configs_dir = _configs_dir();

        if ( ! $file_path && ! $filename ) {
            // return default app array.
            return require $default_configs_dir . '/app.php';
        }

        $options_file = "{$file_path}/{$site_configs_dir}/{$filename}.php";

        if ( file_exists( $options_file ) ) {
            return require $options_file;
        }
        if ( ! file_exists( $options_file ) ) {
            return require $default_configs_dir . '/app.php';
        }

        return [];
    }
}

function env_whitelist(): array
{
    static $whitelist;
    static $whitelisted;

    if ( \is_null( $whitelist ) ) {
        $framework = new Urisoft\SimpleConfig( _configs_dir(), ['whitelist'] );
        // $app = new Urisoft\SimpleConfig( site_configs_dir(), ['whitelist'] );
        $whitelist = $framework->get('whitelist');
    }

    if ( \is_null( $whitelisted ) ) {
        $whitelisted = array_merge( $whitelist['framework'], $whitelist['wp']);
    }

    return $whitelisted;
}

function site_configs_dir(): ?string
{
    return \defined('SITE_CONFIGS_DIR') ? SITE_CONFIGS_DIR : null;
}

/**
 * Retrieve configuration data using dot notation.
 *
 * This function provides a convenient way to access nested data stored in a configuration file
 * using dot notation. It uses the DotAccess library to facilitate easy access to the data.
 *
 * @param null|string $key     The dot notation key to access the data. If null, the entire
 *                             configuration data will be returned.
 * @param mixed       $default The default value to return if the key is not found.
 *
 * @return mixed The value associated with the specified key or the default value if the key
 *               is not found. If no key is provided (null), the entire configuration data is
 *               returned.
 *
 * @see https://github.com/devuri/dot-access DotAccess library used for dot notation access.
 */
function config( ?string $key = null, $default = null )
{
    $_options = _wpframework()->options();

    if ( \is_null( $key ) ) {
        return $_options;
    }

    return $_options->get( $key, $default );
}

/**
 * Gets hash of given string.
 *
 * If no secret key is provided we will use the SECURE_AUTH_KEY wp key.
 *
 * @param string $data      Message to be hashed.
 * @param string $secretkey Secret key used for generating the HMAC variant.
 * @param string $algo      Name of selected hashing algorithm (i.e. "md5", "sha256", "haval160,4", etc..)
 *
 * @return string Returns a string containing the calculated hash value.
 *
 * @see https://www.php.net/manual/en/function.hash-hmac.php
 */
function envHash( $data, ?string $secretkey = null, string $algo = 'sha256' ): string
{
    if ( \is_null( $secretkey ) ) {
        return hash_hmac( $algo, $data, env( 'SECURE_AUTH_KEY' ) );
    }

    return hash_hmac( $algo, $data, $secretkey );
}

/*
 * Generates a list of WordPress plugins in Composer format.
 *
 * @return array An associative array of Composer package names and their version constraints.
 */
if ( ! \function_exists( 'packagistPluginsList' ) ) {
    function packagistPluginsList()
    {
        if ( ! \function_exists( 'get_plugins' ) ) {
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
        }

        $all_plugins = get_plugins();

        $plugins_list = [];

        foreach ( $all_plugins as $plugin_path => $plugin_data ) {
            // Extract the plugin slug from the directory name.
            $plugin_slug = sanitize_title( \dirname( $plugin_path ) );

            // Format the package name with the 'wpackagist-plugin' prefix.
            $package_name = "wpackagist-plugin/{$plugin_slug}";

            $plugins_list[ $package_name ] = 'latest';
        }

        return $plugins_list;
    }
}

/**
 * Basic Sanitize and prepare for a string input for safe usage in the application.
 *
 * This function sanitizes the input by removing leading/trailing whitespace,
 * stripping HTML and PHP tags, converting special characters to HTML entities,
 * and removing potentially dangerous characters for security.
 *
 * @param string $input The input string to sanitize.
 *
 * @return string The sanitized input ready for safe usage within the application.
 */
function wpSanitize( string $input ): string
{
    $input = trim($input);
    $input = strip_tags($input);
    $input = htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
    $input = str_replace(["'", "\"", "--", ";"], "", $input);

    return filter_var($input, FILTER_UNSAFE_RAW, FILTER_FLAG_NO_ENCODE_QUOTES);
}

/**
 * Cleans up sensitive environment variables.
 *
 * This function removes specified environment variables from the $_ENV superglobal
 * and the environment to help secure sensitive information.
 *
 * @param array $sensitives An array of environment variable names to be cleaned up.
 */
function cleanSensitiveEnv(array $sensitives): void
{
    foreach ($sensitives as $var) {
        unset($_ENV[$var]);
        // Ensure to concatenate '=' to effectively unset it
        putenv($var . '=');
    }
}

/**
 * Retrieves all packages listed in the 'require' section of the composer.json file.
 *
 * @param string $app_path The path to the application root directory.
 *
 * @return array An array of required packages, or an empty array if the file doesn't exist or on error.
 */
function get_packages( string $app_path ): array
{
    $composer_path = $app_path . DIRECTORY_SEPARATOR . 'composer.json';

    // Check if the composer.json file exists.
    if ( ! is_file( $composer_path ) ) {
        return [];
    }

    // Attempt to decode the JSON content from composer.json.
    $composer_json = json_decode( file_get_contents( $composer_path ), true );

    // Check for JSON errors.
    if ( JSON_ERROR_NONE !== json_last_error() ) {
        error_log( 'json error');

        return [];
    }

    return $composer_json['require'] ?? [];
}

function _configs_dir(): string
{
    return  __DIR__ . '/configs';
}

function _wpframework( ?string $app_path = null ): ?Framework
{
    static $framework;

    if ( \is_null( $framework ) ) {
        $framework = new Framework( $app_path );
    }

    return $framework;
}


/**
 * Retrieves the default file names for environment configuration.
 *
 * This is designed to return an array of default file names
 * used for environment configuration in a WordPress environment.
 * These file names include various formats and stages of environment setup,
 * such as production, staging, development, and local environments.
 *
 * @since [version number]
 *
 * @return array An array of default file names for environment configurations.
 *               The array includes the following file names:
 *               - 'env'
 *               - '.env'
 *               - '.env.secure'
 *               - '.env.prod'
 *               - '.env.staging'
 *               - '.env.dev'
 *               - '.env.debug'
 *               - '.env.local'
 *               - 'env.local'
 */
function _supported_env_files(): array
{
    return [
        'env',
        '.env',
        '.env.secure',
        '.env.prod',
        '.env.staging',
        '.env.dev',
        '.env.debug',
        '.env.local',
        'env.local',
    ];
}

/**
 * Filters out environment files that do not exist to avoid warnings.
 */
function _env_files_filter( array $env_files, string $app_path ): array
{
    foreach ( $env_files as $key => $file ) {
        if ( ! file_exists( $app_path . '/' . $file ) ) {
            unset( $env_files[ $key ] );
        }
    }

    return $env_files;
}

/**
 * Regenerates the tenant-specific .env file if it doesn't exist.
 *
 * @param string $app_path
 * @param string $app_http_host
 * @param array  $available_files
 */
function try_regenerate_env_file( string $app_path, string $app_http_host, array $available_files = [] ): void
{
    $app_main_env_file = "{$app_path}/.env";
    if ( ! file_exists( $app_main_env_file ) && empty( $available_files ) ) {
        $generator = new EnvGenerator( new Filesystem() );
        $generator->create( $app_main_env_file, $app_http_host );
    }
}
