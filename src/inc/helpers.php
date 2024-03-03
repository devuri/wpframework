<?php

use Defuse\Crypto\Key;
use Urisoft\DotAccess;
use Urisoft\Encryption;
use Urisoft\Env;
use Urisoft\SimpleConfig;
use WPframework\Component\Core\Plugin;
use WPframework\Component\Http\App;
use WPframework\Component\Http\Asset;
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
 * A convenient global function to access environment variables using the Env class.
 *
 * @param string $name             The name of the environment variable.
 * @param mixed  $defaultOrEncrypt Default value or encryption flag.
 * @param bool   $strtolower       Whether to convert the value to lowercase.
 *
 * @return mixed The value of the environment variable, processed according to the Env class logic.
 */
function env($name, $defaultOrEncrypt = null, $strtolower = false)
{
    static $whitelist;
    static $whitelisted;

    if ( \is_null( $whitelist ) ) {
        $whitelist = getEnvWhitelist();
    }

    if ( \is_null( $whitelisted ) ) {
        $whitelisted = array_merge( $whitelist['framework'], $whitelist['wp']);
    }

    $encryptionPath = \defined('APP_PATH') ? APP_PATH : '';

    // Instance of the Env class with your predefined settings
    static $env = null;
    if (null === $env) {
        $env = new Env($whitelisted, $encryptionPath, false );
    }

    // Get the environment variable value
    return $env->get($name, $defaultOrEncrypt, $strtolower);
}

/**
 * Retrieves a list of whitelisted environment variable keys.
 *
 * This function includes and returns an array from 'whitelist.php' located in the 'configs' directory.
 * The array contains keys of environment variables that are permitted for use within the framework.
 * Any environment variable not included in this whitelist will not be processed by the framework's
 * environment handling function, enhancing security by restricting access to only those variables
 * explicitly defined in the whitelist.
 *
 * @return array An indexed array containing the keys of allowed environment variables, such as 'DATA_APP', 'APP', etc.
 */
function getEnvWhitelist(): array
{
    $config = new SimpleConfig( __DIR__ . '/configs', ['whitelist'] );

    return $config->get('whitelist');
}

if ( ! \function_exists( 'getHttpEnv' ) ) {
    /**
     * Get the current set wp app env.
     *
     * This is used in the compose mu plugin.
     *
     * @return null|string the current app env set, or null if not defined
     */
    function getHttpEnv(): ?string
    {
        if ( ! \defined( 'HTTP_ENV_CONFIG' ) ) {
            return null;
        }

        return strtoupper( HTTP_ENV_CONFIG );
    }
}

if ( ! \function_exists( 'wpframework' ) ) {
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
     * @param string $app_path The base directory path of the application (e.g., __DIR__).
     * @param string $options  Optional. The configuration filename, defaults to 'app'.
     *
     * @throws Exception If there are issues loading environment variables or initializing the App.
     * @throws Exception If required multi-tenant environment variables are missing or if the tenant's domain is not recognized.
     *
     * @return WPframework\Component\Http\BaseKernel The initialized application kernel.
     */
    function wpframework( string $app_path, string $options = 'app' ): WPframework\Component\Http\BaseKernel
    {
        if ( ! \defined('SITE_CONFIGS_DIR') ) {
            \define( 'SITE_CONFIGS_DIR', 'configs');
        }

        /**
         * Handle multi-tenant setups.
         *
         * @var Tenancy
         */
        $tenancy = new Tenancy( $app_path, SITE_CONFIGS_DIR );
        $tenancy->initialize();

        try {
            $app = new App( $app_path, SITE_CONFIGS_DIR, $options );
        } catch ( Exception $e ) {
            Terminate::exit(['Framework Initialization Error:'], $e );
        }

        // @phpstan-ignore-next-line
        return $app->kernel();
    }
}

if ( ! \function_exists( 'wpframeworkCore' ) ) {
    /**
     * Start and load core plugin.
     *
     * @return void
     */
    function wpframeworkCore(): void
    {
        if ( ! \defined( 'ABSPATH' ) ) {
            exit;
        }

        Plugin::init();
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
    function appConfig(): array
    {
        return require __DIR__ . '/configs/app.php';
    }
}

/**
 * Retrieve configuration data using dot notation.
 *
 * This function provides a convenient way to access nested data stored in a configuration file
 * using dot notation. It uses the DotAccess library to facilitate easy access to the data.
 *
 * @param null|string $key         The dot notation key to access the data. If null, the entire
 *                                 configuration data will be returned.
 * @param mixed       $default     The default value to return if the key is not found.
 * @param mixed       $data_access
 *
 * @return mixed The value associated with the specified key or the default value if the key
 *               is not found. If no key is provided (null), the entire configuration data is
 *               returned.
 *
 * @see https://github.com/devuri/dot-access DotAccess library used for dot notation access.
 */
function config( ?string $key = null, $default = null, $data_access = false )
{
    $dotdata = null;

    if ( $data_access ) {
        $dotdata = $data_access;
    } else {
        $dotdata = new DotAccess( APP_PATH . SITE_CONFIGS_DIR . '/app.php' );
    }

    if ( \is_null( $key ) ) {
        return $dotdata;
    }

    return $dotdata->get( $key, $default );
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

function envTenantId(): ?string
{
    if ( \defined( 'APP_TENANT_ID' ) ) {
        return APP_TENANT_ID;
    }
    if ( env( 'APP_TENANT_ID' ) ) {
        return env( 'APP_TENANT_ID' );
    }

    return null;
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
    if ( json_last_error() !== JSON_ERROR_NONE ) {
        error_log( 'json error');
        return [];
    }

    return $composer_json['require'] ?? [];
}
