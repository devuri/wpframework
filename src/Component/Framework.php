<?php

namespace WPframework\Component;

use Urisoft\DotAccess;
use WPframework\Component\Core\Plugin;
use WPframework\Component\Traits\TenantTrait;

class Framework implements TenantInterface
{
    use TenantTrait;

    protected $app_options;
    protected $app_path;

    public function __construct( ?string $app_path = null )
    {
        if ( $app_path ) {
            $this->app_path    = $app_path;
            $this->app_options = _app_options( $this->app_path );
        } else {
            $this->app_path    = \defined( 'APP_PATH' ) ? APP_PATH : null;
            $this->app_options = _app_options( $this->app_path );
        }
    }

    public static function app()
    {
        return new self();
    }

    public function plugin(): Plugin
    {
        return Plugin::init( new self() );
    }

    /*
     * The tenant ID for the application.
     *
     * This sets the tenant ID based on the environment configuration. The `APP_TENANT_ID`
     * can be configured in the `.env` file. Setting `APP_TENANT_ID` to false will disable the
     * custom uploads directory behavior that is typically used in a multi-tenant setup. In a
     * multi-tenant environment, `APP_TENANT_ID` is required and must always be set. The method
     * uses `envTenantId()` function to retrieve the tenant ID from the environment settings.
     */
    public static function env_tenant_id(): ?string
    {
        if ( \defined( 'APP_TENANT_ID' ) ) {
            return APP_TENANT_ID;
        }
        if ( env( 'APP_TENANT_ID' ) ) {
            return env( 'APP_TENANT_ID' );
        }

        return null;
    }

    public function get_app_options(): array
    {
        if ( \is_array( $this->app_options ) ) {
            return $this->app_options;
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
}
