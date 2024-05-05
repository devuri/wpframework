<?php

namespace WPframework\Component;

use Urisoft\DotAccess;
use Urisoft\SimpleConfig;
use WPframework\Component\Core\Plugin;
use WPframework\Component\Traits\TenantTrait;

class Framework implements TenantInterface
{
    use TenantTrait;

    protected $app_options;
    protected static $_all_app_options;
    protected $app_path;

    public function __construct( ?string $app_path = null )
    {
        if ( $app_path ) {
            $this->app_path    = $app_path;
            $this->app_options = $this->_app_options( $this->app_path );
        } else {
            $this->app_path    = \defined( 'APP_PATH' ) ? APP_PATH : APP_DIR_PATH;
            $this->app_options = $this->_app_options( $this->app_path );
        }

        self::$_all_app_options = $this->app_options;
    }

    public function plugin(): Plugin
    {
        return Plugin::init( new self() );
    }

    public function get_app_options(): array
    {
        if ( \is_array( self::$_all_app_options ) ) {
            return self::$_all_app_options;
        }

        return [];
    }

    public function options(): ?DotAccess
    {
        static $options;
        if ( \is_null( $options ) ) {
            $options = new DotAccess( $this->get_app_options() );
        }

        return $options;
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
    public function get_whitelist(): array
    {
        $config = new SimpleConfig( _configs_dir(), [ 'whitelist' ] );

        return $config->get( 'whitelist' );
    }

    /**
     * Options set in the framework configs/app.php.
     *
     * @param null|string $app_path the current application path.
     *
     * @return null|array
     */
    private function _app_options( ?string $app_path = null ): ?array
    {
        $options_file    = $app_path . '/' . site_configs_dir() . '/app.php';
        $default_configs = _configs_dir() . '/app.php';

        if ( ! file_exists( $options_file ) ) {
            $app_options = require $default_configs;
        } elseif ( file_exists( $options_file ) ) {
            $app_options = require $options_file;
        } else {
            $app_options = null;
        }

        return \is_array( $app_options ) ? $app_options : null;
    }
}
