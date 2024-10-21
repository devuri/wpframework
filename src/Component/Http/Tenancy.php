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

use Dotenv\Dotenv;
use Exception;
use Symfony\Component\Filesystem\Filesystem;
use WPframework\EnvGenerator;
use WPframework\Tenant;
use WPframework\Terminate;

class Tenancy
{
    protected static $constants = [];
    private $appPath;
    private $configs_dir;
    private $tenant;

    /**
     * Tenancy constructor.
     *
     * @param string $appPath     The base directory path of the application (e.g., __DIR__).
     * @param string $site_config The site config directory name
     */
    public function __construct(string $appPath, string $site_config)
    {
        $this->tenant      = new Tenant($appPath);
        $this->appPath     = $this->tenant->getCurrentPath();
        $this->configs_dir = $site_config;
    }

    /**
     * Initializes the App Kernel with optional multi-tenant support.
     *
     * @throws Exception If there are issues loading environment variables or initializing the App.
     *
     * @return void
     */
    public function initialize(Dotenv $_dotenv): void
    {
        if (file_exists("{$this->appPath}/{$this->configs_dir}/tenancy.php")) {
            require_once "{$this->appPath}/{$this->configs_dir}/tenancy.php";
        }

        if (\defined('ALLOW_MULTITENANT') && true === ALLOW_MULTITENANT) {
            $this->setup_multi_tenant($_dotenv);
        }
    }

    /**
     * Define a constant with a value.
     *
     * @param string $const The name of the constant to define.
     * @param mixed  $value The value to assign to the constant.
     */
    protected static function define(string $const, $value): void
    {
        if (self::is_defined($const)) {
            return;
        }

        \define($const, $value);

        static::$constants[$const] = $value;
    }

    /**
     * Sets up the environment for a multi-tenant configuration.
     */
    protected function setup_multi_tenant(Dotenv $_dotenv): void
    {
        $_app_http_host = HttpFactory::init()->get_http_host();

        try {
            $_dotenv->required('LANDLORD_DB_HOST')->notEmpty();
            $_dotenv->required('LANDLORD_DB_NAME')->notEmpty();
            $_dotenv->required('LANDLORD_DB_USER')->notEmpty();
            $_dotenv->required('LANDLORD_DB_PASSWORD')->notEmpty();
            $_dotenv->required('LANDLORD_DB_PREFIX')->notEmpty();
        } catch (Exception $e) {
            Terminate::exit([ 'Landlord info is required for multi-tenant', 403 ]);
        }

        $landlord = new DB('tenant', env('LANDLORD_DB_HOST'), env('LANDLORD_DB_NAME'), env('LANDLORD_DB_USER'), env('LANDLORD_DB_PASSWORD'), env('LANDLORD_DB_PREFIX'));
        $hostd    = $landlord->where('domain', $_app_http_host);

        if ( ! $hostd) {
            Terminate::exit([ 'The website is not defined. Please review the URL and try again.', 403 ]);
        } else {
            $this->tenant = $hostd[0];
            $this->define_tenant_constants();
            $this->maybe_regenerate_env_file(APP_TENANT_ID);
        }

        // Clean up sensitive environment variables
        cleanSensitiveEnv([ 'LANDLORD_DB_HOST', 'LANDLORD_DB_NAME', 'LANDLORD_DB_USER', 'LANDLORD_DB_PASSWORD', 'LANDLORD_DB_PREFIX' ]);

        unset($_dotenv);
    }

    /**
     * Check if a constant is defined.
     *
     * @param string $const The name of the constant to check.
     *
     * @return bool True if the constant is defined, false otherwise.
     */
    private static function is_defined(string $const): bool
    {
        return \defined($const);
    }

    /**
     * Defines constants based on the tenant's information.
     */
    private function define_tenant_constants(): void
    {
        \define('APP_HTTP_HOST', $this->tenant->domain);
        \define('APP_TENANT_ID', md5($this->tenant->uuid));
        \define('IS_MULTITENANT', true);

        // allow overrides.
        self::define('REQUIRE_TENANT_CONFIG', false);
        self::define('TENANCY_WEB_ROOT', 'public');
        self::define('PUBLIC_WEB_DIR', $this->appPath . '/' . TENANCY_WEB_ROOT);
        self::define('APP_CONTENT_DIR', 'app');
    }

    /**
     * Regenerates the tenant-specific .env file if it doesn't exist.
     *
     * @param string $tenant_id Tenant's UUID.
     */
    private function maybe_regenerate_env_file(string $tenant_id): void
    {
        $tenant_env_path = "{$this->appPath}/{$this->configs_dir}/{$tenant_id}/.env";
        if ( ! file_exists($tenant_env_path)) {
            $generator = new EnvGenerator(new Filesystem());
            $db_prefix = $this->get_db_prefix($tenant_id);
            $generator->create($tenant_env_path, APP_HTTP_HOST, $db_prefix);
            unset($generator);
        }
    }

    /**
     * Determines the database prefix for the tenant.
     *
     * @param string $tenant_id Tenant's UUID.
     *
     * @return null|string Database prefix or null if not the main site.
     */
    private function get_db_prefix(string $tenant_id): ?string
    {
        if (\defined('LANDLORD_UUID') && LANDLORD_UUID === $tenant_id) {
            return env('LANDLORD_DB_PREFIX');
        }

        return null;
    }
}
