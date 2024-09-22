<?php

/*
 * This file is part of the WPframework package.
 *
 * (c) Uriel Wilson <uriel@wpframework.io>
 *
 * The full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WPframework\Http;

use Exception;
use InvalidArgumentException;
use Urisoft\DotAccess;
use WPframework\Env\EnvTypes;
use WPframework\Setup;
use WPframework\Terminate;
use WPframework\Traits\ConstantBuilderTrait;

use function defined;

/**
 * Setup common elements.
 *
 * Handles global constants.
 */
abstract class AbstractKernel implements KernelInterface
{
    use ConstantBuilderTrait;

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

    /**
     * The current environment type.
     *
     * @var string
     */
    protected $env_type;

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
    public function __construct(string $app_path, ?array $args = [], ?Setup $setup = null)
    {
        $this->app_path    = $app_path;
        $this->configs_dir = SITE_CONFIGS_DIR;
        $this->args        = array_merge($this->args, self::get_default_config());

        if (! \is_array($args)) {
            throw new InvalidArgumentException('Error: args must be of type array', 1);
        }

        // @codingStandardsIgnoreLine
        if (\array_key_exists('wordpress', $args)) {
            $this->args['wp_dir_path'] = $args['wordpress'];
        }

        $this->args = new DotAccess(array_merge($this->args, $args));

        /*
         * Sets the name of the configuration `configs/config.php` file based on arguments.
         *
         * This method defines the configuration file name within the framework. 'config' is the default name, leading to 'config.php'.
         * This name can be customized via the 'config_file' argument, allowing for files like 'constant.php'.
         * This enables tailored settings for the WordPress application by leveraging various configuration files.
         * TODO we are going to remove the ability change this in the future.
         *
         * @param array $args Associative array where 'config_file' specifies the configuration file name, excluding '.php'.
         *
         * @link https://devuri.github.io/wpframework/customization/constants
         */
        $this->config_file = $this->args->get('config_file');

        // Sets the <tenant_id>
        $this->tenant_id = $this->envTenantId();

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
        if (\is_null($setup)) {
            $this->app_setup = Setup::init($this->app_path);
        } else {
            $this->app_setup = $setup;
        }

        // set the environment switcher.
        $this->app_setup->setSwitcher(new Switcher());

        // set config override file.
        $this->configuration_overrides();
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
        /** -------------------------------------
         * Framework Required Constants
         * -------------------------------------- */

        // Define app path.
        $this->define('APP_PATH', $this->get_app_path());

        // Set app HTTP host.
        $this->define('APP_HTTP_HOST', self::http()->get_http_host());

        // Define public web root directory.
        $this->define('PUBLIC_WEB_DIR', APP_PATH . '/' . $this->args->get('directory.web_root_dir'));

        // Define assets directory.
        $this->define('APP_ASSETS_DIR', PUBLIC_WEB_DIR . '/' . $this->args->get('directory.asset_dir'));

        // Directory PATH.
        $this->define('APP_CONTENT_DIR', $this->args->get('directory.content_dir'));

        // Sudo admin (granted more privileges, uses user ID).
        $this->define('WP_SUDO_ADMIN', $this->args->get('sudo_admin'));

        // A group of users with higher administrative privileges.
        $this->define('SUDO_ADMIN_GROUP', $this->args->get('sudo_admin_group'));

        // Web app security encryption key.
        $this->define('WEBAPP_ENCRYPTION_KEY', $this->args->get('security.encryption_key'));

        // Define app theme directory.
        if ($this->args->get('directory.theme_dir')) {
            $this->define('APP_THEME_DIR', $this->args->get('directory.theme_dir'));
        }

        /** -------------------------------------
         * SQLite for WordPress Constants
         * --------------------------------------*/

        // Define SQLite database location and filename.
        $this->define('DB_DIR', APP_PATH . '/' . $this->args->get('directory.sqlite_dir'));
        $this->define('DB_FILE', $this->args->get('directory.sqlite_file'));

        /** -------------------------------------
         * WordPress Core Constants
         * -------------------------------------- */

        // WordPress content directory.
        $this->define('WP_CONTENT_DIR', PUBLIC_WEB_DIR . '/' . APP_CONTENT_DIR);
        $this->define('WP_CONTENT_URL', env('WP_HOME') . '/' . APP_CONTENT_DIR);

        // WordPress directory path.
        $this->define('WP_DIR_PATH', PUBLIC_WEB_DIR . '/' . $this->args->get('wp_dir_path'));

        // WordPress plugin directory.
        $this->define('WP_PLUGIN_DIR', PUBLIC_WEB_DIR . '/' . $this->args->get('directory.plugin_dir'));
        $this->define('WP_PLUGIN_URL', env('WP_HOME') . '/' . $this->args->get('directory.plugin_dir'));

        // WordPress Must-Use plugin directory.
        $this->define('WPMU_PLUGIN_DIR', PUBLIC_WEB_DIR . '/' . $this->args->get('directory.mu_plugin_dir'));
        $this->define('WPMU_PLUGIN_URL', env('WP_HOME') . '/' . $this->args->get('directory.mu_plugin_dir'));

        // Disable automatic updates (handled via composer).
        $this->define('AUTOMATIC_UPDATER_DISABLED', $this->args->get('disable_updates'));

        // Default theme for WordPress installation.
        $this->define('WP_DEFAULT_THEME', $this->args->get('default_theme'));

        // Home URL MD5 hash for cookies.
        $this->define('COOKIEHASH', md5(env('WP_HOME')));

        // Cookie-related WordPress constants.
        $this->define('USER_COOKIE', 'wpc_user_' . COOKIEHASH);
        $this->define('PASS_COOKIE', 'wpc_pass_' . COOKIEHASH);
        $this->define('AUTH_COOKIE', 'wpc_' . COOKIEHASH);
        $this->define('SECURE_AUTH_COOKIE', 'wpc_sec_' . COOKIEHASH);
        $this->define('LOGGED_IN_COOKIE', 'wpc_logged_in_' . COOKIEHASH);
        $this->define('TEST_COOKIE', md5('wpc_test_cookie' . env('WP_HOME')));

        /** -------------------------------------
         * WordPress Plugin-Related Constants
         * -------------------------------------- */

        // Prevent admin users from deactivating plugins.
        $this->define('CAN_DEACTIVATE_PLUGINS', $this->args->get('can_deactivate'));

        // SUCURI WAF (Web Application Firewall) enablement.
        $this->define('ENABLE_SUCURI_WAF', $this->args->get('sucuri_waf'));

        // Redis cache configuration.
        $this->define('WP_REDIS_DISABLED', $this->args->get('redis.disabled'));
        $this->define('WP_REDIS_PREFIX', $this->args->get('redis.prefix'));
        $this->define('WP_REDIS_DATABASE', $this->args->get('redis.database'));
        $this->define('WP_REDIS_HOST', $this->args->get('redis.host'));
        $this->define('WP_REDIS_PORT', $this->args->get('redis.port'));
        $this->define('WP_REDIS_PASSWORD', $this->args->get('redis.password'));
        $this->define('WP_REDIS_DISABLE_ADMINBAR', $this->args->get('redis.adminbar'));
        $this->define('WP_REDIS_DISABLE_METRICS', $this->args->get('redis.disable-metrics'));
        $this->define('WP_REDIS_DISABLE_BANNERS', $this->args->get('redis.disable-banners'));
        $this->define('WP_REDIS_TIMEOUT', $this->args->get('redis.timeout'));
        $this->define('WP_REDIS_READ_TIMEOUT', $this->args->get('redis.read-timeout'));
    }

    /**
     * Retrieves the Setup object associated with the application.
     *
     * @return Setup|null The Setup object for the application configuration and environment.
     */
    public function get_app(): ?Setup
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
        return $this->args->get('security');
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
        return _defaultConfigs();
    }

    public function get_args(): array
    {
        return $this->args->export();
    }

    public static function envTenantId(): ?string
    {
        if (\defined('APP_TENANT_ID')) {
            return APP_TENANT_ID;
        }
        if (env('APP_TENANT_ID')) {
            return env('APP_TENANT_ID');
        }

        return null;
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
     * Sets a secret key in the environment secrets array.
     * Ensures that each key is stored only once.
     *
     * @param string $key The key to be stored as a secret.
     *
     * @return void
     */
    public function set_env_secret(string $key): void
    {
        if (! isset($this->env_secret[ $key ])) {
            $this->env_secret[ $key ] = $key;
        }
    }

    /**
     * Retrieves all keys stored in the environment secrets array.
     *
     * @return (int|string)[] An array of keys representing the stored secrets.
     *
     * @psalm-return list<array-key>
     */
    public function get_secret(): array
    {
        return array_keys($this->env_secret);
    }

    /**
     * Initializes the application with environment-specific configurations and constants.
     * Also checks for maintenance mode and WP installation status.
     *
     * @param null|string $environment_type The environment type to initialize with.
     * @param bool        $constants        Whether to load default constants.
     *
     * @return static
     */
    public function app(?string $environment_type = null, bool $constants = true): KernelInterface
    {
        $environment = env('WP_ENVIRONMENT_TYPE', $environment_type);
        $wp_env_type = self::wp_env_type((string) $environment);

        if ($environment && EnvTypes::isValid($wp_env_type)) {
            $this->env_type = [ 'environment' => $environment ];
        } elseif (\defined('WP_ENVIRONMENT_TYPE') && EnvTypes::isValid($wp_env_type)) {
            $this->env_type = [ 'environment' => WP_ENVIRONMENT_TYPE ];
        }

        if (\is_array($this->env_type)) {
            $this->app_setup->config(
                array_merge($this->environment_args(), $this->env_type)
            );
        } else {
            $this->app_setup->config($this->environment_args());
        }

        /*
         * Adds support for `aaemnnosttv/wp-sqlite-db`
         *
         * We want to set USE_MYSQL to set MySQL as the default database.
         *
         * @link https://github.com/aaemnnosttv/wp-sqlite-db/blob/master/src/db.php
         */
        $this->define('USE_MYSQL', true);

        // make env available.
        $this->define('HTTP_ENV_CONFIG', $this->app_setup->getEnvironment());

        if (true === $constants) {
            $this->set_config_constants();
        }

        // maintenance mode
        $this->handle_maintenance_mode();

        if ($this->is_wp_install() && \in_array(env('WP_ENVIRONMENT_TYPE'), [ 'secure', 'sec', 'production', 'prod' ], true)) {
            Terminate::exit([ 'wp is not installed or doing an upgrade change enviroment to run installer' ]);
        }

        return $this;
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
        if (! self::is_debug_mode()) {
            return null;
        }

        return self::encrypt_secret($_ENV, self::env_secrets());
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
        if (! self::is_debug_mode()) {
            return null;
        }

        $user_constants = get_defined_constants(true)['user'];

        return self::encrypt_secret($user_constants, self::env_secrets());
    }

    /**
     * Determines the configuration file to use based on the application's mode and tenant ID.
     * Falls back to the default configuration if no tenant-specific configuration is found.
     *
     * @return static
     */
    protected function configuration_overrides(): self
    {
        $config_override_file = $this->get_tenant_config_file();

        if (empty($config_override_file)) {
            $config_override_file = $this->get_default_config_file();
        }

        if (! empty($config_override_file)) {
            require_once $config_override_file;
        }

        return $this;
    }

    /**
     * Attempts to get the tenant-specific configuration file if multi-tenant mode is active.
     *
     * @return null|string Path to the tenant-specific configuration file or null if not found/applicable.
     */
    protected function get_tenant_config_file(): ?string
    {
        if (isMultitenantApp() && ! empty($this->tenant_id)) {
            $tenant_config_file = "{$this->app_path}/{$this->configs_dir}/{$this->tenant_id}/{$this->config_file}.php";
            if (file_exists($tenant_config_file)) {
                return $tenant_config_file;
            }
        }

        return null;
    }

    /**
     * Gets the default configuration file, preferring the one in the configs directory.
     *
     * @return null|string Path to the default configuration file.
     */
    protected function get_default_config_file(): ?string
    {
        $default_config_file = "{$this->app_path}/{$this->config_file}.php";
        $configs_config_file = "{$this->app_path}/{$this->configs_dir}/{$this->config_file}.php";

        if (file_exists($configs_config_file)) {
            return $configs_config_file;
        }
        if (file_exists($default_config_file)) {
            return $default_config_file;
        }

        return null;
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
            $this->app_setup->getAppPath() . '/.maintenance' => 'For single tenant.',
        ];

        foreach ($maintenance_checks as $path => $scope) {
            if (file_exists($path)) {
                // TODO Log or handle the scope-specific message if needed, e.g., error_log($scope);
                Terminate::exit([ self::get_maintenance_message(), 503 ]);

                break;
            }
        }
    }

    protected function is_wp_install(): bool
    {
        if (\defined('RAYDIUM_INSTALL_PROTECTION') && false === RAYDIUM_INSTALL_PROTECTION) {
            return false;
        }

        if (\defined('WP_INSTALLING') && true === WP_INSTALLING) {
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
     * @return (false|mixed|null|string)[]
     *
     * @psalm-return array{environment: null, error_log: string, debug: false, errors: mixed}
     */
    protected function environment_args(): array
    {
        $this->log_file = mb_strtolower(gmdate('m-d-Y')) . '.log';

        // Determine the error logs directory path based on tenant ID presence.
        $error_logs_dir_suffix = $this->tenant_id ? "/{$this->tenant_id}/" : '/';
        $error_logs_dir        = $this->app_path . '/storage/logs/wp-errors' . $error_logs_dir_suffix . "debug-{$this->log_file}";

        return [
            'environment' => null,
            'error_log'   => $error_logs_dir,
            'debug'       => false,
            'errors'      => $this->args->get('error_handler'),
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

    private static function wp_env_type(string $environment = ''): string
    {
        if (\defined('WP_ENVIRONMENT_TYPE')) {
            return (string) WP_ENVIRONMENT_TYPE;
        }

        return $environment;
    }

    /**
     * Get the maintenance message.
     *
     * This method returns a predefined maintenance message indicating that
     * the server is temporarily unavailable due to maintenance. It's used to
     * inform users about the temporary unavailability of the service.
     *
     * @return string The maintenance message to be displayed to users.
     *
     * @psalm-return 'Service Unavailable: <br>The server is currently unable to handle the request due to temporary maintenance of the server.'
     */
    private static function get_maintenance_message(): string
    {
        return 'Service Unavailable: <br>The server is currently unable to handle the request due to temporary maintenance of the server.';
    }

    /**
     * Retrieve the current month.
     *
     * @return numeric-string The current month value (formatted as "01"-"12").
     */
    private function get_current_month(): string
    {
        return gmdate('m');
    }

    /**
     * Retrieve the current year.
     *
     * @return numeric-string The current year value (formatted as "YYYY").
     */
    private function get_current_year(): string
    {
        return gmdate('Y');
    }

    private static function is_debug_mode(): bool
    {
        if (! \defined('WP_DEBUG')) {
            return false;
        }

        if (\defined('WP_DEBUG') && false === WP_DEBUG) {
            return false;
        }

        if (\defined('WP_DEBUG') && true === WP_DEBUG) {
            return true;
        }

        return false;
    }
}
