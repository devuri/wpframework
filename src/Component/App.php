<?php

namespace WPframework\Component;

use Dotenv\Dotenv;
use Dotenv\Exception\InvalidPathException;
use Exception;
use Symfony\Component\ErrorHandler\Debug;
use Urisoft\Env;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;
use WPframework\Component\Http\HttpFactory;
use WPframework\Component\Http\Tenancy;

/**
 * The App class serves as the main entry point for initializing the WordPress application.
 * It sets up the application environment, loads configuration settings, and establishes
 * error handling mechanisms based on the environment. This class ensures that the application
 * is correctly configured before it starts running.
 */
class App
{
    /**
     * The base path of the application.
     * This path is used to locate all necessary files and directories within the application.
     *
     * @var string
     */
    protected $app_path;

    /**
     * The Setup object responsible for initializing and managing the application's environment and configuration.
     * This object provides methods to access environment variables, configuration files, and other setup-related functionalities.
     *
     * @var Setup
     */
    protected $setup;

    /**
     * The application's configuration array, loaded from the specified configuration file.
     * This array contains settings and parameters required for the application's operation.
     *
     * @var array
     */
    protected $config;

    /**
     * The directory path where configuration files are stored.
     * This path is used in conjunction with `$app_path` to locate configuration files.
     *
     * @var string
     */
    protected $configs_dir;

    /**
     * Initializes the App class with essential configuration and setup.
     *
     * It loads the specified configuration options from a file and initializes error handling.
     * The constructor requires the base application path, the configuration directory location,
     * and an optional filename for configuration options.
     *
     * @param string $app_path    The base path of the application (e.g., __DIR__).
     * @param string $site_config The directory where configuration files are stored.
     * @param string $options     The filename of the configuration options without extension (default: 'app').
     *
     * @throws Exception If the configuration file is missing or the loaded configuration is not an array.
     */
    public function __construct( string $app_path, string $site_config, string $options = 'app' )
    {
        $this->app_path    = $app_path;
        $this->configs_dir = $site_config;

        /*
         * We need setup to get access to our env values.
         *
         * @var Setup
         */
        $this->setup = self::define_setup( $this->app_path );

        /**
         * setup params.
         *
         * @var string
         */
        $params_file = $this->setup->get_tenant_file_path( $options, $this->app_path, self::is_required_tenant_config() );

        if ( ! empty( $params_file ) ) {
            $this->config = require $params_file;
        } else {
            $this->config = appConfig( $this->app_path, $options );
        }

        if ( ! \is_array( $this->config ) ) {
            throw new Exception( 'Options array is undefined, not array.', 1 );
        }

        // handle errors early.
        $this->set_app_errors();
    }

    /**
     * Initializes and returns a Kernel object with the application's configuration.
     *
     * This method ensures the configuration (`$this->config`) is an array before
     * passing it to the Kernel constructor. If `$this->config` is not an array,
     * the application is terminated with an error message to prevent type errors.
     *
     * @return Kernel Returns an instance of Kernel initialized with the
     *                application path, configuration array, and setup object.
     */
    public function kernel(): Kernel
    {
        if ( ! \is_array( $this->config ) ) {
            $debug = [
                'class'  => static::class,
                'object' => $this,
                'path'   => $this->app_path,
                'line'   => __LINE__,
            ];
            Terminate::exit( [ 'Uncaught TypeError: Kernel($args) must be of type array', 500, $debug ] );
        }

        return new Kernel( $this->app_path, $this->config, $this->setup );
    }

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
     * @return Kernel The initialized application kernel.
     */
    public static function init( string $app_path, string $options_file = 'app' ): Kernel
    {
        if ( ! \defined( 'SITE_CONFIGS_DIR' ) ) {
            \define( 'SITE_CONFIGS_DIR', 'configs' );
        }

        if ( ! \defined( 'APP_DIR_PATH' ) ) {
            \define( 'APP_DIR_PATH', $app_path );
        }

        if ( ! \defined( 'APP_HTTP_HOST' ) ) {
            \define( 'APP_HTTP_HOST', HttpFactory::init()->get_http_host() );
        }

        if ( ! \defined( 'RAYDIUM_ENVIRONMENT_TYPE' ) ) {
            \define( 'RAYDIUM_ENVIRONMENT_TYPE', null );
        }

        $app_options         = [];
        $supported_env_files = _supported_env_files();

        // Filters out environment files that do not exist to avoid warnings.
        $_env_files = _env_files_filter( $supported_env_files, APP_DIR_PATH );

        // load env from dotenv early.
        $_dotenv = Dotenv::createImmutable( APP_DIR_PATH, $_env_files, true );

        try {
            $_dotenv->load();
        } catch ( InvalidPathException $e ) {
            try_regenerate_env_file( APP_DIR_PATH, APP_HTTP_HOST, $_env_files );

            $debug = [
                'path'        => APP_DIR_PATH,
                'line'        => __LINE__,
                'exception'   => $e,
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
        }// end try

        /**
         * Handle multi-tenant setups.
         *
         * @var Tenancy
         */
        $tenancy = new Tenancy( APP_DIR_PATH, SITE_CONFIGS_DIR );
        $tenancy->initialize( $_dotenv );

        try {
            $app = new self( APP_DIR_PATH, SITE_CONFIGS_DIR, $options_file );
        } catch ( Exception $e ) {
            $debug = [
                'path'      => APP_DIR_PATH,
                'line'      => __LINE__,
                'exception' => $e,
            ];
            Terminate::exit( [ 'Framework Initialization Error:', 500, $debug ] );
        }

        // @phpstan-ignore-next-line
        return $app->kernel();
    }

    /**
     * Factory method for creating a Setup object with the specified application path.
     *
     * This method abstracts the instantiation of a Setup object, allowing for
     * centralized management of Setup object creation. It's particularly useful
     * for encapsulating any future changes in the Setup class instantiation process.
     *
     * @param string $app_path The application path to be used in the Setup object.
     *
     * @return Setup Returns a new Setup object configured with the provided application path.
     */
    protected static function define_setup( string $app_path ): Setup
    {
        return new Setup( $app_path );
    }

    /**
     * Set up the application error handling based on environment settings.
     */
    protected function set_app_errors(): void
    {
        if ( ! \in_array( env( 'WP_ENVIRONMENT_TYPE' ), [ 'debug', 'development', 'dev', 'local' ], true ) ) {
            return;
        }

        if ( \defined( 'WP_INSTALLING' ) ) {
            return;
        }

        if ( false === $this->config['error_handler'] ) {
            return;
        }

        if ( true === $this->config['error_handler'] ) {
            Debug::enable();

            return;
        }

        if ( \is_null( $this->config['error_handler'] ) || 'symfony' === $this->config['error_handler'] ) {
            Debug::enable();
        } elseif ( 'oops' === $this->config['error_handler'] ) {
            $whoops = new Run();
            $whoops->pushHandler( new PrettyPageHandler() );
            $whoops->register();
        }
    }

    private static function is_required_tenant_config(): bool
    {
        return \defined( 'REQUIRE_TENANT_CONFIG' ) && REQUIRE_TENANT_CONFIG === true;
    }
}
