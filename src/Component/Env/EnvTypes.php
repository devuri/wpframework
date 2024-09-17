<?php

/*
 * This file is part of the WPframework package.
 *
 * (c) Uriel Wilson <uriel@wpframework.io>
 *
 * The full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WPframework\Env;

final class EnvTypes
{
    public const SECURE      = 'secure';
    public const SEC         = 'sec';
    public const PRODUCTION  = 'production';
    public const PROD        = 'prod';
    public const STAGING     = 'staging';
    public const DEVELOPMENT = 'development';
    public const DEV         = 'dev';
    public const DEBUG       = 'debug';
    public const DEB         = 'deb';
    public const LOCAL       = 'local';

    /**
     * An array of all environment types.
     *
     * @var string[]
     */
    private static $envTypes = [
        self::SECURE,
        self::SEC,
        self::PRODUCTION,
        self::PROD,
        self::STAGING,
        self::DEVELOPMENT,
        self::DEV,
        self::DEBUG,
        self::DEB,
        self::LOCAL,
    ];

    /**
     * Checks if the given type is a valid environment type.
     *
     * @param null|string $type The environment type to check.
     *
     * @return bool True if valid, false otherwise.
     */
    public static function isValid(?string $type): bool
    {
        return \in_array($type, self::$envTypes, true);
    }

    /**
     * Get all environment types.
     *
     * @return string[] The list of environment types.
     */
    public static function getAll(): array
    {
        return self::$envTypes;
    }
}
