<?php

/*
 * This file is part of the WPframework package.
 *
 * (c) Uriel Wilson <uriel@wpframework.io>
 *
 * The full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
    public function __construct(string $app_path, ?array $args = [], ?Setup $setup = null)
    {
        parent::__construct($app_path, $args, $setup);
    }
}
