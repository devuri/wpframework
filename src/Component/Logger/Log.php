<?php

/*
 * This file is part of the WPframework package.
 *
 * (c) Uriel Wilson <uriel@wpframework.io>
 *
 * The full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WPframework\Logger;

class Log
{
    private static ?FileLogger $logger = null;

    /**
     * Initializes the logger with an optional file path. If no file path is provided,
     * it will fall back to error_log.
     *
     * @param string|null $logFile The file where logs will be stored (optional).
     */
    public static function init(?string $logFile = null): void
    {
        self::$logger = new FileLogger($logFile);
    }

    /**
     * Log an emergency message.
     *
     * @param string $message
     * @param array $context
     */
    public static function emergency(string $message, array $context = []): void
    {
        self::getLogger()->emergency($message, $context);
    }

    /**
     * Log an alert message.
     *
     * @param string $message
     * @param array $context
     */
    public static function alert(string $message, array $context = []): void
    {
        self::getLogger()->alert($message, $context);
    }

    /**
     * Log a critical message.
     *
     * @param string $message
     * @param array $context
     */
    public static function critical(string $message, array $context = []): void
    {
        self::getLogger()->critical($message, $context);
    }

    /**
     * Log an error message.
     *
     * @param string $message
     * @param array $context
     */
    public static function error(string $message, array $context = []): void
    {
        self::getLogger()->error($message, $context);
    }

    /**
     * Log a warning message.
     *
     * @param string $message
     * @param array $context
     */
    public static function warning(string $message, array $context = []): void
    {
        self::getLogger()->warning($message, $context);
    }

    /**
     * Log a notice message.
     *
     * @param string $message
     * @param array $context
     */
    public static function notice(string $message, array $context = []): void
    {
        self::getLogger()->notice($message, $context);
    }

    /**
     * Log an informational message.
     *
     * @param string $message
     * @param array $context
     */
    public static function info(string $message, array $context = []): void
    {
        self::getLogger()->info($message, $context);
    }

    /**
     * Log a debug message.
     *
     * @param string $message
     * @param array $context
     */
    public static function debug(string $message, array $context = []): void
    {
        self::getLogger()->debug($message, $context);
    }

    /**
     * Ensure that the logger is initialized and return the logger instance.
     *
     * @return FileLogger
     */
    private static function getLogger(): FileLogger
    {
        if (self::$logger === null) {
            self::init();
        }

        return self::$logger;
    }
}
