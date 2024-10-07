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

use Urisoft\DotAccess;
use Urisoft\SimpleConfig;

class Framework
{
    protected $app_options;
    protected static $_all_app_options;
    protected $appPath;

    public function __construct(?string $appPath = null)
    {
        if ($appPath) {
            $this->appPath     = $appPath;
            $this->app_options = $this->_app_options();
        } else {
            $this->appPath     = \defined('APP_PATH') ? APP_PATH : APP_DIR_PATH;
            $this->app_options = $this->_app_options();
        }

        self::$_all_app_options = $this->app_options;
    }

    public function getAppOptions(): array
    {
        if (\is_array(self::$_all_app_options)) {
            return self::$_all_app_options;
        }

        return [];
    }

    public function tenant(): Tenant
    {
        return new Tenant($this->appPath);
    }

    public function options(): ?DotAccess
    {
        static $options;
        if (\is_null($options)) {
            $options = new DotAccess($this->getAppOptions());
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
        $config = new SimpleConfig(_configsDir(), [ 'whitelist' ]);

        return $config->get('whitelist');
    }

    /**
     * Options set in the framework configs/app.php.
     *
     * @return array
     */
    private function _app_options(): array
    {
        $site_options = Config::siteConfig($this->tenant()->getCurrentPath());

        if (empty($site_options)) {
            $site_options = Config::getDefault();
        }

        // TODO fix: these are usually merged in the kernel, results here are not merged.

        return $site_options;
    }
}
