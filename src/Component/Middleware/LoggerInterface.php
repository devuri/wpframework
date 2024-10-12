<?php

/*
 * This file is part of the WPframework package.
 *
 * (c) Uriel Wilson <uriel@wpframework.io>
 *
 * The full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WPframework\Middleware;

interface LoggerInterface
{
    /**
     * Log an emergency message.
     *
     * @param string $message The log message.
     * @param array $context Additional context for the log message.
     */
    public function logEmergency(string $message, array $context = []): void;

    /**
     * Log an alert message.
     *
     * @param string $message The log message.
     * @param array $context Additional context for the log message.
     */
    public function logAlert(string $message, array $context = []): void;

    /**
     * Log a critical message.
     *
     * @param string $message The log message.
     * @param array $context Additional context for the log message.
     */
    public function logCritical(string $message, array $context = []): void;

    /**
     * Log an error message.
     *
     * @param string $message The log message.
     * @param array $context Additional context for the log message.
     */
    public function logError(string $message, array $context = []): void;

    /**
     * Log a warning message.
     *
     * @param string $message The log message.
     * @param array $context Additional context for the log message.
     */
    public function logWarning(string $message, array $context = []): void;

    /**
     * Log a notice message.
     *
     * @param string $message The log message.
     * @param array $context Additional context for the log message.
     */
    public function logNotice(string $message, array $context = []): void;

    /**
     * Log an informational message.
     *
     * @param string $message The log message.
     * @param array $context Additional context for the log message.
     */
    public function logInfo(string $message, array $context = []): void;

    /**
     * Log a debug message.
     *
     * @param string $message The log message.
     * @param array $context Additional context for the log message.
     */
    public function logDebug(string $message, array $context = []): void;
}
