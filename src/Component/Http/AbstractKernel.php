<?php

namespace WPframework\Component\Http;

use function defined;

use Exception;
use InvalidArgumentException;
use Urisoft\DotAccess;
use WPframework\Component\EnvTypes;
use WPframework\Component\Setup;
use WPframework\Component\TenantInterface;
use WPframework\Component\Terminate;
use WPframework\Component\Traits\ConstantBuilderTrait;
use WPframework\Component\Traits\TenantTrait;

/**
 * Setup common elements.
 *
 * Handles global constants.
 */
abstract class AbstractKernel implements TenantInterface
{
    use ConstantBuilderTrait;
    use TenantTrait;

    /**
     * The base path of the application.
     *
     * @var string
     */
    protected $app_path;

    /**
     * The name of the log file for the application.
     *
     * @var string
     */
    protected $log_file;

    /**
     * The directory name where the application is installed.
     *
     * @var string
     */
    protected $dir_name;

    /**
     * The name of the configuration file for the application.
     *
     * @var string
     */
    protected $config_file;

    /**
     * Array holding environment-specific secrets.
     *
     * @var array
     */
    protected $env_secret = [];

    /**
     * Static list used within the AbstractKernel context.
     *
     * @var array
     */
    protected static $list = [];

    /**
     * The Setup object for the application's configuration and environment setup.
     *
     * @var null|Setup
     */
    protected $app_setup;

    /**
     * The tenant ID, used in multi-tenant applications to isolate tenant-specific data.
     *
     * @var null|string
     */
    protected $tenant_id;

    /**
     * The directory where configuration files are stored.
     *
     * @var string
     */
    protected $configs_dir;

    protected $args = [
        'wp_dir_path'      => 'wp',
        'wordpress'        => 'wp',
        'directory'        => [
            'web_root_dir'  => 'public',
            'content_dir'   => 'content',
            'plugin_dir'    => 'content/plugins',
            'mu_plugin_dir' => 'content/mu-plugins',
            'sqlite_dir'    => 'sqlitedb',
            'sqlite_file'   => '.sqlite-wpdatabase',
            'theme_dir'     => 'templates',
            'asset_dir'     => 'assets',
            'publickey_dir' => 'pubkeys',
        ],
        'default_theme'    => 'twentytwentythree',
        'disable_updates'  => true,
        'can_deactivate'   => true,
        'templates_dir'    => null,
        'error_handler'    => 'symfony',
        'config_file'      => 'config',
        'sudo_admin'       => null,
        'sudo_admin_group' => null,
        'sucuri_waf'       => false,
        'redis'            => [],
        'security'         => [],
    ];

    /**
     * Constructs the AbstractKernel object and initializes the application setup.
     * It loads the application configuration and sets up environment-specific settings.
     *
     * @param string     $app_path The base path of the application.
     * @param string[]   $args     Optional arguments for further configuration.
     * @param null|Setup $setup    Optional Setup object for custom setup configuration.
     *
     * @throws Exception                If a critical setup error occurs.
     * @throws InvalidArgumentException If the provided arguments are not valid.
     */
    public function __construct( string $app_path, ?array $args = [], ?Setup $setup = null )
    {
        $this->app_path    = $app_path;
        $this->configs_dir = SITE_CONFIGS_DIR;

        if ( \is_null( $args ) || empty( $args ) ) {
            $this->args = array_merge( $this->args, self::get_default_config() );
        } elseif ( ! \is_array( $args ) ) {
            throw new InvalidArgumentException( 'Error: args must be of type array', 1 );
        }

        if ( \array_key_exists( 'theme_dir', $args ) ) {
            $this->args['templates_dir'] = $args['theme_dir'];
        }

        // @codingStandardsIgnoreLine
        if (\array_key_exists('wordpress', $args)) {
            $this->args['wp_dir_path'] = $args['wordpress'];
        }

        $this->args = new DotAccess( array_merge( $this->args, $args ) );

        $this->config_file = $this->args->get( 'config_file' );

        $this->tenant_id = $this->env_tenant_id();

        /*
         * By default, Dotenv will stop looking for files as soon as it finds one.
         *
         * To disable this behaviour, and load all files in order,
         * we can disable the file loading with the `short_circuit` bool param.
         *
         * ['env', '.env', '.env.secure', '.env.prod', '.env.staging', '.env.dev', '.env.debug', '.env.local']
         * Since these will load in order we can control our env by simply creating file that matches
         * the environment on say staging we would create '.env.staging' since it's the only file available
         * those will be the only values loaded.
         *
         * We can use Setup methods `get_short_circuit()` and `get_env_files()`
         * to know how the enviroment is configured.
         *
         * @link https://github.com/vlucas/phpdotenv/pull/394
         */
        if ( \is_null( $setup ) ) {
            $this->app_setup = Setup::init( $this->app_path );
        } else {
            $this->app_setup = $setup;
        }

        // set the environment switcher.
        $this->app_setup->set_switcher( new Switcher() );
    }

    /**
     * Defines constants.
     *
     * @psalm-suppress UndefinedConstant
     *
     * @return void
     */
    public function set_config_constants(): void
    {
        // define app_path.
        $this->define( 'APP_PATH', $this->get_app_path() );

        // set app http host.
        $this->define( 'APP_HTTP_HOST', self::http()->get_http_host() );

        // define public web root dir.
        $this->define( 'PUBLIC_WEB_DIR', APP_PATH . '/' . $this->args->get( 'directory.web_root_dir' ) );

        // wp dir path
        $this->define( 'WP_DIR_PATH', PUBLIC_WEB_DIR . '/' . $this->args->get( 'wp_dir_path' ) );

        // define assets dir.
        $this->define( 'APP_ASSETS_DIR', PUBLIC_WEB_DIR . '/' . $this->args->get( 'directory.asset_dir' ) );

        // Directory PATH.
        $this->define( 'APP_CONTENT_DIR', $this->args->get( 'directory.content_dir' ) );
        $this->define( 'WP_CONTENT_DIR', PUBLIC_WEB_DIR . '/' . APP_CONTENT_DIR );
        $this->define( 'WP_CONTENT_URL', env( 'WP_HOME' ) . '/' . APP_CONTENT_DIR );

        /*
         * Themes, prefer '/templates'
         *
         * This requires mu-plugin or add `register_theme_directory( APP_THEME_DIR );`
         *
         * path should be a folder within WP_CONTENT_DIR
         *
         * @link https://github.com/devuri/custom-wordpress-theme-dir
         */
        if ( $this->args->get( 'templates_dir' ) ) {
            $this->define( 'APP_THEME_DIR', $this->args->get( 'templates_dir' ) );
        }

        // Plugins.
        $this->define( 'WP_PLUGIN_DIR', PUBLIC_WEB_DIR . '/' . $this->args->get( 'directory.plugin_dir' ) );
        $this->define( 'WP_PLUGIN_URL', env( 'WP_HOME' ) . '/' . $this->args->get( 'directory.plugin_dir' ) );

        // Must-Use Plugins.
        $this->define( 'WPMU_PLUGIN_DIR', PUBLIC_WEB_DIR . '/' . $this->args->get( 'directory.mu_plugin_dir' ) );
        $this->define( 'WPMU_PLUGIN_URL', env( 'WP_HOME' ) . '/' . $this->args->get( 'directory.mu_plugin_dir' ) );

        // Disable any kind of automatic upgrade.
        // this will be handled via composer.
        $this->define( 'AUTOMATIC_UPDATER_DISABLED', $this->args->get( 'disable_updates' ) );

        // Sudo admin (granted more privilages uses user ID).
        $this->define( 'WP_SUDO_ADMIN', $this->args->get( 'sudo_admin' ) );

        // A group of users with higher administrative privileges.
        $this->define( 'SUDO_ADMIN_GROUP', $this->args->get( 'sudo_admin_group' ) );

        /*
         * Prevent Admin users from deactivating plugins, true or false.
         *
         * @link https://gist.github.com/devuri/034ccb7c833f970192bb64317814da3b
         */
        $this->define( 'CAN_DEACTIVATE_PLUGINS', $this->args->get( 'can_deactivate' ) );

        // SQLite database location and filename.
        $this->define( 'DB_DIR', APP_PATH . '/' . $this->args->get( 'directory.sqlite_dir' ) );
        $this->define( 'DB_FILE', $this->args->get( 'directory.sqlite_file' ) );

        /*
         * Slug of the default theme for this installation.
         * Used as the default theme when installing new sites.
         * It will be used as the fallback if the active theme doesn't exist.
         *
         * @see WP_Theme::get_core_default_theme()
         */
        $this->define( 'WP_DEFAULT_THEME', $this->args->get( 'default_theme' ) );

        // home url md5 value.
        $this->define( 'COOKIEHASH', md5( env( 'WP_HOME' ) ) );

        // Defines cookie-related override for WordPress constants.
        $this->define( 'USER_COOKIE', 'wpc_user_' . COOKIEHASH );
        $this->define( 'PASS_COOKIE', 'wpc_pass_' . COOKIEHASH );
        $this->define( 'AUTH_COOKIE', 'wpc_' . COOKIEHASH );
        $this->define( 'SECURE_AUTH_COOKIE', 'wpc_sec_' . COOKIEHASH );
        $this->define( 'LOGGED_IN_COOKIE', 'wpc_logged_in_' . COOKIEHASH );
        $this->define( 'TEST_COOKIE', md5( 'wpc_test_cookie' . env( 'WP_HOME' ) ) );

        // SUCURI
        $this->define( 'ENABLE_SUCURI_WAF', $this->args->get( 'sucuri_waf' ) );
        // $this->define( 'SUCURI_DATA_STORAGE', ABSPATH . '../../storage/logs/sucuri' );

        /*
         * Redis cache configuration for the WordPress application.
         *
         * This array contains configuration settings for the Redis cache integration in WordPress.
         * For detailed installation instructions, refer to the documentation at:
         * @link https://github.com/rhubarbgroup/redis-cache/blob/develop/INSTALL.md
         *
         * @return void
         */
        $this->define( 'WP_REDIS_DISABLED', $this->args->get( 'redis.disabled' ) );

        $this->define( 'WP_REDIS_PREFIX', $this->args->get( 'redis.prefix' ) );
        $this->define( 'WP_REDIS_DATABASE', $this->args->get( 'redis.database' ) );
        $this->define( 'WP_REDIS_HOST', $this->args->get( 'redis.host' ) );
        $this->define( 'WP_REDIS_PORT', $this->args->get( 'redis.port' ) );
        $this->define( 'WP_REDIS_PASSWORD', $this->args->get( 'redis.password' ) );

        $this->define( 'WP_REDIS_DISABLE_ADMINBAR', $this->args->get( 'redis.adminbar' ) );
        $this->define( 'WP_REDIS_DISABLE_METRICS', $this->args->get( 'redis.disable-metrics' ) );
        $this->define( 'WP_REDIS_DISABLE_BANNERS', $this->args->get( 'redis.disable-banners' ) );

        $this->define( 'WP_REDIS_TIMEOUT', $this->args->get( 'redis.timeout' ) );
        $this->define( 'WP_REDIS_READ_TIMEOUT', $this->args->get( 'redis.read-timeout' ) );

        // web app security key
        $this->define( 'WEBAPP_ENCRYPTION_KEY', $this->args->get( 'security.encryption_key' ) );
    }

    /**
     * Retrieves the Setup object associated with the application.
     *
     * @return Setup The Setup object for the application configuration and environment.
     */
    public function get_app(): Setup
    {
        return $this->app_setup;
    }

    /**
     * Returns the security configurations for the application.
     *
     * @return array An array containing security configuration settings.
     */
    public function get_app_security(): array
    {
        return $this->args->get( 'security' );
    }

    /**
     * Gets the base path of the application.
     *
     * @return string The application's base path.
     */
    public function get_app_path(): string
    {
        return $this->app_path;
    }

    /**
     * Retrieves the default application configuration.
     *
     * This method returns the application's default configuration settings
     * by calling the `appConfig()` function.
     *
     * @return array The default configuration settings.
     */
    public static function get_default_config(): array
    {
        return appConfig();
    }

    public function get_args(): array
    {
        return $this->args->export();
    }

    /**
     * Get app config args.
     *
     * @return string[]
     */
    public function get_app_config(): array
    {
        return $this->get_args();
    }

    /**
     * Loads tenant-specific or default configuration based on the application's multi-tenant status.
     *
     * This function first checks for a tenant-specific configuration file in multi-tenant mode. If not found,
     * or if not in multi-tenant mode, it falls back to the default configuration file. The configuration is applied
     * by requiring the respective file, if it exists.
     *
     * @return void
     */
    public function overrides(): void
    {
        $config_override_file = null;

        // Check if multi-tenant mode is enabled and a tenant ID is set
        if ( $this->is_multitenant_app() && ! empty( $this->tenant_id ) ) {
            $tenant_config_file = $this->app_path . "/{$this->configs_dir}/{$this->tenant_id}/{$this->config_file}.php";

            // Check if the tenant-specific config file exists
            if ( file_exists( $tenant_config_file ) ) {
                $config_override_file = $tenant_config_file;
            }
        }

        // If no tenant-specific file found, use the default config file
        if ( empty( $config_override_file ) ) {
            $default_config_file = $this->app_path . "/{$this->config_file}.php";
            if ( file_exists( $default_config_file ) ) {
                $config_override_file = $default_config_file;
            }
        }

        // If a valid config override file is found, require it
        if ( ! empty( $config_override_file ) ) {
            require_once $config_override_file;
        }
    }

    /**
     * Sets a secret key in the environment secrets array.
     * Ensures that each key is stored only once.
     *
     * @param string $key The key to be stored as a secret.
     *
     * @return void
     */
    public function set_env_secret( string $key ): void
    {
        if ( ! isset( $this->env_secret[ $key ] ) ) {
            $this->env_secret[ $key ] = $key;
        }
    }

    /**
     * Retrieves all keys stored in the environment secrets array.
     *
     * @return (int|string)[] An array of keys representing the stored secrets.
     */
    public function get_secret(): array
    {
        return array_keys( $this->env_secret );
    }

    /**
     * Initializes the application with environment-specific configurations and constants.
     * Also checks for maintenance mode and WP installation status.
     *
     * @param null|false|string|string[] $env_type  The environment type to initialize with.
     * @param bool                       $constants Whether to load default constants.
     *
     * @return void
     */
    public function init( $env_type = null, bool $constants = true ): void
    {
        if ( env( 'WP_ENVIRONMENT_TYPE' ) && EnvTypes::is_valid( self::wp_env_type() ) ) {
            $env_type = [ 'environment' => env( 'WP_ENVIRONMENT_TYPE' ) ];
        } elseif ( \defined( 'WP_ENVIRONMENT_TYPE' ) && EnvTypes::is_valid( self::wp_env_type() ) ) {
            $env_type = [ 'environment' => WP_ENVIRONMENT_TYPE ];
        }

        if ( \is_array( $env_type ) ) {
            $this->app_setup->config(
                array_merge( $this->environment_args(), $env_type )
            );
        } else {
            $this->app_setup->config( $this->environment_args() );
        }

        /*
         * Adds support for `aaemnnosttv/wp-sqlite-db`
         *
         * We want to set USE_MYSQL to set MySQL as the default database.
         *
         * @link https://github.com/aaemnnosttv/wp-sqlite-db/blob/master/src/db.php
         */
        $this->define( 'USE_MYSQL', true );

        // make env available.
        $this->define( 'HTTP_ENV_CONFIG', $this->app_setup->get_environment() );

        if ( true === $constants ) {
            $this->set_config_constants();
        }

        // maintenance mode
        $this->handle_maintenance_mode();

        if ( $this->wp_is_not_installed() && \in_array( env( 'WP_ENVIRONMENT_TYPE' ), [ 'secure', 'sec', 'production', 'prod' ], true ) ) {
            Terminate::exit( [ 'wp is not installed change enviroment to run installer' ] );
        }
    }

    /**
     * Get list of defined constants.
     *
     * @return string[] constants in set_config_constants().
     */
    public function get_defined(): array
    {
        return static::$constants;
    }

    /**
     * Retrieve server environment variables and obfuscate sensitive data.
     *
     * This method retrieves the server environment variables (usually stored in $_ENV). If the application
     * is not in debug mode, it returns null. In debug mode, it collects the environment variables, obfuscates
     * any sensitive data within them using the 'encrypt_secret' method, and returns the resulting array.
     *
     * @return null|array An array of server environment variables with sensitive data obfuscated in debug mode,
     *                    or null if not in debug mode.
     */
    public function get_server_env(): ?array
    {
        if ( ! self::is_debug_mode() ) {
            return null;
        }

        return self::encrypt_secret( $_ENV, self::env_secrets() );
    }

    /**
     * Retrieve user-defined constants and obfuscate sensitive data.
     *
     * This method retrieves an array of user-defined constants. If the application is not in debug mode,
     * it returns null. In debug mode, it collects user-defined constants, obfuscates any sensitive data
     * within them using the 'encrypt_secret' method, and returns the resulting array.
     *
     * @return null|array An array of user-defined constants with sensitive data obfuscated in debug mode,
     *                    or null if not in debug mode.
     */
    public function get_user_constants(): ?array
    {
        if ( ! self::is_debug_mode() ) {
            return null;
        }

        $user_constants = get_defined_constants( true )['user'];

        return self::encrypt_secret( $user_constants, self::env_secrets() );
    }

    /**
     * Checks for maintenance mode across different scopes and terminates execution if enabled.
     *
     * This function checks for a .maintenance file in various locations, affecting different
     * scopes of the application:
     * - The entire tenant network (when located in PUBLIC_WEB_DIR or APP_PATH/configs_dir).
     * - A single tenant (when located in the current application path).
     * If a .maintenance file is found, it terminates the execution with a maintenance message
     * and sends a 503 Service Unavailable status code.
     */
    protected function handle_maintenance_mode(): void
    {
        $maintenance_checks = [
            // Affects the entire tenant network.
            PUBLIC_WEB_DIR . '/.maintenance' => 'Will affect the entire tenant network.',
            APP_PATH . "/{$this->configs_dir}/.maintenance" => 'Will affect the entire tenant network.',

            // Affects a single tenant.
            $this->app_setup->get_current_path() . '/.maintenance' => 'For single tenant.',
        ];

        foreach ( $maintenance_checks as $path => $scope ) {
            if ( file_exists( $path ) ) {
                // TODO Log or handle the scope-specific message if needed, e.g., error_log($scope);
                Terminate::exit( [ self::get_maintenance_message(), 503 ] );

                break;
                // Terminate the loop after the first match.
            }
        }
    }

    protected function wp_is_not_installed(): bool
    {
        if ( \defined( 'WP_INSTALLING' ) && true === WP_INSTALLING ) {
            return true;
        }

        return false;
    }

    /**
     * Generate environment-specific arguments, including customized error log paths.
     *
     * This method constructs the arguments needed for the environment setup,
     * particularly focusing on the error logging mechanism. It differentiates
     * the error log directory based on the presence of a tenant ID, allowing
     * for tenant-specific error logging.
     *
     * @return array The array of environment-specific arguments.
     */
    protected function environment_args(): array
    {
        $this->log_file = mb_strtolower( gmdate( 'm-d-Y' ) ) . '.log';

        // Determine the error logs directory path based on tenant ID presence.
        $error_logs_dir_suffix = $this->tenant_id ? "/{$this->tenant_id}/" : '/';
        $error_logs_dir        = $this->app_path . '/storage/logs/wp-errors' . $error_logs_dir_suffix . "debug-{$this->log_file}";

        return [
            'environment' => null,
            'error_log'   => $error_logs_dir,
            'debug'       => false,
            'errors'      => $this->args->get( 'error_handler' ),
        ];
    }

    /**
     * Initialize the HTTP client.
     *
     * This method leverages the HttpFactory to create and return an instance
     * of the HostManager, which is used to manage HTTP requests and responses.
     *
     * @return HostManager An instance of HostManager for HTTP operations.
     */
    protected static function http(): HostManager
    {
        return HttpFactory::init();
    }

    private static function wp_env_type(): string
    {
        if ( \defined( 'WP_ENVIRONMENT_TYPE' ) ) {
            return (string) WP_ENVIRONMENT_TYPE;
        }

        return '';
    }

    /**
     * Get the maintenance message.
     *
     * This method returns a predefined maintenance message indicating that
     * the server is temporarily unavailable due to maintenance. It's used to
     * inform users about the temporary unavailability of the service.
     *
     * @return string The maintenance message to be displayed to users.
     */
    private static function get_maintenance_message(): string
    {
        return 'Service Unavailable: <br>The server is currently unable to handle the request due to temporary maintenance of the server.';
    }

    /**
     * Retrieve the current month.
     *
     * @return string The current month value (formatted as "01"-"12").
     */
    private function get_current_month(): string
    {
        return gmdate( 'm' );
    }

    /**
     * Retrieve the current year.
     *
     * @return string The current year value (formatted as "YYYY").
     */
    private function get_current_year(): string
    {
        return gmdate( 'Y' );
    }

    private static function is_debug_mode(): bool
    {
        if ( ! \defined( 'WP_DEBUG' ) ) {
            return false;
        }

        if ( \defined( 'WP_DEBUG' ) && false === WP_DEBUG ) {
            return false;
        }

        if ( \defined( 'WP_DEBUG' ) && true === WP_DEBUG ) {
            return true;
        }

        return false;
    }
}
