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
use WPframework\AppConfig;
use WPframework\Env\EnvTypes;
use WPframework\Setup;
use WPframework\Terminate;
use WPframework\Config;

use function defined;

/**
 * Setup common elements.
 *
 * Handles global constants.
 */
abstract class AbstractKernel implements KernelInterface
{
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
    protected $siteSetup;

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

    protected $args;

    /**
     * @var AppConfig
     */
    protected $constManager;

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
        $this->args        = self::getDefaultConfig();

        if (! \is_array($args)) {
            throw new InvalidArgumentException('Error: args must be of type array', 1);
        }

        $this->args = new DotAccess(self::multiMerge($this->args, $args));

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
            $this->siteSetup = Setup::init($this->app_path);
        } else {
            $this->siteSetup = $setup;
        }

        // set the environment switcher.
        $this->siteSetup->setSwitcher(new Switcher());

        // get constant builder config.
        $this->constManager = $this->siteSetup->getAppConfig();

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
    public function setKernelConstants(): void
    {
        // define app_path.
        $this->constManager->addConstant('APP_PATH', $this->get_app_path());

        // set app http host.
        $this->constManager->addConstant('APP_HTTP_HOST', self::http()->get_http_host());

        // define public web root dir.
        $this->constManager->addConstant('PUBLIC_WEB_DIR', APP_PATH . '/' . $this->args->get('directory.web_root_dir'));

        // wp dir path
        $this->constManager->addConstant('WP_DIR_PATH', PUBLIC_WEB_DIR . '/' . $this->args->get('directory.wp_dir_path'));

        // define assets dir.
        $this->constManager->addConstant('APP_ASSETS_DIR', PUBLIC_WEB_DIR . '/' . $this->args->get('directory.asset_dir'));

        // Directory PATH.
        $this->constManager->addConstant('APP_CONTENT_DIR', $this->args->get('directory.content_dir'));
        $this->constManager->addConstant('WP_CONTENT_DIR', PUBLIC_WEB_DIR . '/' . APP_CONTENT_DIR);
        $this->constManager->addConstant('WP_CONTENT_URL', env('WP_HOME') . '/' . APP_CONTENT_DIR);

        /*
         * Themes, prefer '/templates'
         *
         * This requires mu-plugin or add `register_theme_directory( APP_THEME_DIR );`
         *
         * path should be a folder within WP_CONTENT_DIR
         *
         * @link https://github.com/devuri/custom-wordpress-theme-dir
         */
        if ($this->args->get('directory.theme_dir')) {
            $this->constManager->addConstant('APP_THEME_DIR', $this->args->get('directory.theme_dir'));
        }

        // Plugins.
        $this->constManager->addConstant('WP_PLUGIN_DIR', PUBLIC_WEB_DIR . '/' . $this->args->get('directory.plugin_dir'));
        $this->constManager->addConstant('WP_PLUGIN_URL', env('WP_HOME') . '/' . $this->args->get('directory.plugin_dir'));

        // Must-Use Plugins.
        $this->constManager->addConstant('WPMU_PLUGIN_DIR', PUBLIC_WEB_DIR . '/' . $this->args->get('directory.mu_plugin_dir'));
        $this->constManager->addConstant('WPMU_PLUGIN_URL', env('WP_HOME') . '/' . $this->args->get('directory.mu_plugin_dir'));

        // Disable any kind of automatic upgrade.
        // this will be handled via composer.
        $this->constManager->addConstant('AUTOMATIC_UPDATER_DISABLED', $this->args->get('disable_updates'));

        // Sudo admin (granted more privilages uses user ID).
        $this->constManager->addConstant('WP_SUDO_ADMIN', $this->args->get('sudo_admin'));

        // A group of users with higher administrative privileges.
        $this->constManager->addConstant('SUDO_ADMIN_GROUP', $this->args->get('sudo_admin_group'));

        /*
         * Prevent Admin users from deactivating plugins, true or false.
         *
         * @link https://gist.github.com/devuri/034ccb7c833f970192bb64317814da3b
         */
        $this->constManager->addConstant('CAN_DEACTIVATE_PLUGINS', $this->args->get('can_deactivate'));

        // SQLite database location and filename.
        $this->constManager->addConstant('DB_DIR', APP_PATH . '/' . $this->args->get('directory.sqlite_dir'));
        $this->constManager->addConstant('DB_FILE', $this->args->get('directory.sqlite_file'));

        /*
         * Slug of the default theme for this installation.
         * Used as the default theme when installing new sites.
         * It will be used as the fallback if the active theme doesn't exist.
         *
         * @see WP_Theme::get_core_default_theme()
         */
        $this->constManager->addConstant('WP_DEFAULT_THEME', $this->args->get('default_theme'));

        // home url md5 value.
        $this->constManager->addConstant('COOKIEHASH', md5(env('WP_HOME')));

        // Defines cookie-related override for WordPress constants.
        $this->constManager->addConstant('USER_COOKIE', 'wpc_user_' . COOKIEHASH);
        $this->constManager->addConstant('PASS_COOKIE', 'wpc_pass_' . COOKIEHASH);
        $this->constManager->addConstant('AUTH_COOKIE', 'wpc_' . COOKIEHASH);
        $this->constManager->addConstant('SECURE_AUTH_COOKIE', 'wpc_sec_' . COOKIEHASH);
        $this->constManager->addConstant('LOGGED_IN_COOKIE', 'wpc_logged_in_' . COOKIEHASH);
        $this->constManager->addConstant('TEST_COOKIE', md5('wpc_test_cookie' . env('WP_HOME')));

        // SUCURI
        $this->constManager->addConstant('ENABLE_SUCURI_WAF', $this->args->get('security.sucuri_waf'));
        // $this->constManager->addConstant( 'SUCURI_DATA_STORAGE', ABSPATH . '../../storage/logs/sucuri' );

        /*
         * Redis cache configuration for the WordPress application.
         *
         * This array contains configuration settings for the Redis cache integration in WordPress.
         * For detailed installation instructions, refer to the documentation at:
         * @link https://github.com/rhubarbgroup/redis-cache/blob/develop/INSTALL.md
         *
         * @return void
         */
        $this->constManager->addConstant('WP_REDIS_DISABLED', $this->args->get('redis.disabled'));

        $this->constManager->addConstant('WP_REDIS_PREFIX', $this->args->get('redis.prefix'));
        $this->constManager->addConstant('WP_REDIS_DATABASE', $this->args->get('redis.database'));
        $this->constManager->addConstant('WP_REDIS_HOST', $this->args->get('redis.host'));
        $this->constManager->addConstant('WP_REDIS_PORT', $this->args->get('redis.port'));
        $this->constManager->addConstant('WP_REDIS_PASSWORD', $this->args->get('redis.password'));

        $this->constManager->addConstant('WP_REDIS_DISABLE_ADMINBAR', $this->args->get('redis.adminbar'));
        $this->constManager->addConstant('WP_REDIS_DISABLE_METRICS', $this->args->get('redis.disable-metrics'));
        $this->constManager->addConstant('WP_REDIS_DISABLE_BANNERS', $this->args->get('redis.disable-banners'));

        $this->constManager->addConstant('WP_REDIS_TIMEOUT', $this->args->get('redis.timeout'));
        $this->constManager->addConstant('WP_REDIS_READ_TIMEOUT', $this->args->get('redis.read-timeout'));

        // web app security key
        $this->constManager->addConstant('WEBAPP_ENCRYPTION_KEY', $this->args->get('security.encryption_key'));
    }

    /**
     * Retrieves the Setup object associated with the application.
     *
     * @return Setup|null The Setup object for the application configuration and environment.
     */
    public function get_app(): ?Setup
    {
        return $this->siteSetup;
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

    public static function getDefaultConfig(): array
    {
        return Config::getDefault();
    }

    public function getArgs(): array
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
        return $this->getArgs();
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
            $this->siteSetup->config(
                array_merge($this->environment_args(), $this->env_type)
            );
        } else {
            $this->siteSetup->config($this->environment_args());
        }

        /*
         * Adds support for `aaemnnosttv/wp-sqlite-db`
         *
         * We want to set USE_MYSQL to set MySQL as the default database.
         *
         * @link https://github.com/aaemnnosttv/wp-sqlite-db/blob/master/src/db.php
         */
        $this->constManager->addConstant('USE_MYSQL', true);

        // make env available.
        $this->constManager->addConstant('HTTP_ENV_CONFIG', $this->siteSetup->getEnvironment());

        if (true === $constants) {
            $this->setKernelConstants();
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
     * @return string[] constants in setKernelConstants().
     */
    public function get_defined(): array
    {
        return $this->constManager->getDefinedConstants();
    }

    /**
     * Retrieve server environment variables and obfuscate sensitive data.
     *
     * This method retrieves the server environment variables (usually stored in $_ENV). If the application
     * is not in debug mode, it returns null. In debug mode, it collects the environment variables, obfuscates
     * any sensitive data within them using the 'hashSecret' method, and returns the resulting array.
     *
     * @return null|array An array of server environment variables with sensitive data obfuscated in debug mode,
     *                    or null if not in debug mode.
     */
    public function get_server_env(): ?array
    {
        if (! self::is_debug_mode()) {
            return null;
        }

        return self::hashSecret($_ENV, self::envSecrets());
    }

    /**
     * Retrieve user-defined constants and obfuscate sensitive data.
     *
     * This method retrieves an array of user-defined constants. If the application is not in debug mode,
     * it returns null. In debug mode, it collects user-defined constants, obfuscates any sensitive data
     * within them using the 'hashSecret' method, and returns the resulting array.
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

        return self::hashSecret($user_constants, self::envSecrets());
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
            $this->siteSetup->getAppPath() . '/.maintenance' => 'For single tenant.',
        ];

        foreach ($maintenance_checks as $path => $scope) {
            if (file_exists($path)) {
                // TODO Log or handle the scope-specific message if needed, e.g., error_log($scope);
                Terminate::exit([ self::get_maintenance_message(), 503 ]);

                break;
                // Terminate the loop after the first match.
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

    /**
     * Merges two multi-dimensional arrays recursively.
     *
     * This function will recursively merge the values of `$array2` into `$array1`.
     * If the same key exists in both arrays, and both corresponding values are arrays,
     * the values are recursively merged.
     * Otherwise, values from `$array2` will overwrite those in `$array1`.
     *
     * @param array $array1 The base array that will be merged into.
     * @param array $array2 The array with values to merge into `$array1`.
     *
     * @return array The merged array.
     */
    private static function multiMerge(array $array1, array $array2): array
    {
        $merged = $array1;

        foreach ($array2 as $key => $value) {
            if (isset($merged[$key]) && is_array($merged[$key]) && is_array($value)) {
                $merged[$key] = self::multiMerge($merged[$key], $value);
            } else {
                $merged[$key] = $value;
            }
        }

        return $merged;
    }
}
