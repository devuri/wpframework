<?php

namespace WPframework;

use WPframework\Http\AbstractKernel;

class Kernel extends AbstractKernel
{
    /**
     * Constructs the AbstractKernel object and initializes the application setup.
     * It loads the application configuration and sets up environment-specific settings.
     *
     * @param string     $app_path The base path of the application.
     * @param string[]   $args     Optional arguments for further configuration.
     * @param null|Setup $setup    Optional Setup object for custom setup configuration.
     */
    public function __construct( string $app_path, ?array $args = [], ?Setup $setup = null )
    {
        parent::__construct( $app_path, $args, $setup );
    }
}
