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

use WPframework\Traits\ConstantBuilderTrait;

/**
 * Class EnvSwitch.
 *
 * This class implements the EnvSwitcherInterface and provides methods for setting up different environmental configurations
 * such as development, staging, production, and debugging within your application.
 *
 * @see https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
 */
class Switcher implements EnvSwitcherInterface
{
    use ConstantBuilderTrait;

    /**
     * Sets the Error logs directory relative path.
     *
     * @var string
     */
    protected $error_logs_dir;

    /**
     * Switches between different environments based on the value of $environment.
     *
     * @param string $environment The environment to switch to.
     *
     * @return void
     */
    public function createEnvironment(string $environment, ?string $error_logs_dir): void
    {
        $this->setErrorLogsDir($error_logs_dir);

        switch ($environment) {
            case 'production':
            case 'prod':
                $this->production();
                $this->setCache();

                break;
            case 'staging':
                $this->staging();

                break;
            case 'deb':
            case 'debug':
            case 'local':
                $this->debug();

                break;
            case 'development':
            case 'dev':
                $this->development();

                break;
            case 'secure':
            case 'sec':
                $this->secure();
                $this->setCache();

                break;
            default:
                $this->production();
                $this->setCache();
        }// end switch
    }

    /**
     * Secure.
     */
    public function secure(): void
    {
        // Disable Plugin and Theme Editor.
        $this->define('DISALLOW_FILE_EDIT', true);
        $this->define('DISALLOW_FILE_MODS', true);

        $this->define('WP_DEBUG_DISPLAY', false);
        $this->define('SCRIPT_DEBUG', false);

        $this->define('WP_CRON_LOCK_TIMEOUT', 120);
        $this->define('EMPTY_TRASH_DAYS', 10);

        if ($this->error_logs_dir) {
            $this->define('WP_DEBUG', true);
            $this->define('WP_DEBUG_LOG', $this->error_logs_dir);
        } else {
            $this->define('WP_DEBUG', false);
            $this->define('WP_DEBUG_LOG', false);
        }

        ini_set('display_errors', '0');
    }

    public function production(): void
    {
        // Disable Plugin and Theme Editor.
        $this->define('DISALLOW_FILE_EDIT', true);

        $this->define('WP_DEBUG_DISPLAY', false);
        $this->define('SCRIPT_DEBUG', false);

        $this->define('WP_CRON_LOCK_TIMEOUT', 60);
        $this->define('EMPTY_TRASH_DAYS', 15);

        if ($this->error_logs_dir) {
            $this->define('WP_DEBUG', true);
            $this->define('WP_DEBUG_LOG', $this->error_logs_dir);
        } else {
            $this->define('WP_DEBUG', false);
            $this->define('WP_DEBUG_LOG', false);
        }

        ini_set('display_errors', '0');
    }

    public function staging(): void
    {
        $this->define('DISALLOW_FILE_EDIT', false);

        $this->define('WP_DEBUG_DISPLAY', true);
        $this->define('SCRIPT_DEBUG', false);
        $this->define('SAVEQUERIES', true);

        $this->define('WP_DEBUG', true);
        ini_set('display_errors', '0');

        self::setDebugLog();
    }

    public function development(): void
    {
        $this->define('WP_DEBUG', true);
        $this->define('SAVEQUERIES', true);

        $this->define('WP_DEBUG_DISPLAY', true);
        $this->define('WP_DISABLE_FATAL_ERROR_HANDLER', true);

        $this->define('SCRIPT_DEBUG', true);
        ini_set('display_errors', '1');

        self::setDebugLog();
    }

    /**
     * Debug.
     */
    public function debug(): void
    {
        $this->define('WP_DEBUG', true);
        $this->define('WP_DEBUG_DISPLAY', true);
        $this->define('CONCATENATE_SCRIPTS', false);
        $this->define('SAVEQUERIES', true);
        $this->define('WP_CRON_LOCK_TIMEOUT', 120);
        $this->define('EMPTY_TRASH_DAYS', 50);

        self::setDebugLog();

        error_reporting(E_ALL);
        ini_set('log_errors', '1');
        ini_set('log_errors_max_len', '0');
        ini_set('display_errors', '1');
        ini_set('display_startup_errors', '1');
    }

    /**
     * Set debug environment.
     */
    protected function setDebugLog(): void
    {
        if ($this->error_logs_dir) {
            $this->define('WP_DEBUG_LOG', $this->error_logs_dir);
        } else {
            $this->define('WP_DEBUG_LOG', true);
        }
    }

    /**
     * Set error_logs_dir for environment.
     */
    private function setErrorLogsDir(?string $error_logs_dir): void
    {
        $this->error_logs_dir = $error_logs_dir;
    }

    private function setCache(): void
    {
        if (defined('SWITCH_OFF_CACHE') && constant('SWITCH_OFF_CACHE') === true) {
            return;
        }

        $this->define('WP_CACHE', true);
        $this->define('CONCATENATE_SCRIPTS', true);
        $this->define('COMPRESS_SCRIPTS', true);
        $this->define('COMPRESS_CSS', true);
    }
}
