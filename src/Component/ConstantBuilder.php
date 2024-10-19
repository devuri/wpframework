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

use WPframework\Exceptions\ConstantAlreadyDefinedException;

/**
 * Class ConstantBuilder.
 *
 * This class provides methods for defining and managing constants in your application.
 */
class ConstantBuilder
{
    /**
     * List of constants defined.
     *
     * @var array
     */
    protected $constants = [];

    /**
     * @var array
     */
    protected $constantMap = ['disabled'];

    /**
     * @var bool
     */
    protected $errorNotice;

    /**
     * Define a constant with a value.
     *
     * @param string $const The name of the constant to define.
     * @param mixed  $value The value to assign to the constant.
     *
     * @throws ConstantAlreadyDefinedException if the constant has already been defined.
     */
    public function define(string $const, $value): void
    {
        if ($this->isDefined($const)) {
            // throw new ConstantAlreadyDefinedException( "Constant: $const has already been defined" );

            if ($this->errorNotice) {
                trigger_error('Constant Already Defined:' . $const);
            }

            return;
        }

        \define($const, $value);
        $this->constants[$const] = $value;
    }

    public function setErrorNotice()
    {
        $this->errorNotice = true;
    }

    /**
     * Check if a constant is defined.
     *
     * @param string $const The name of the constant to check.
     *
     * @return bool True if the constant is defined, false otherwise.
     */
    public function isDefined(string $const): bool
    {
        return \defined($const);
    }

    /**
     * Get the value of a defined constant.
     *
     * @param string $key The name of the constant to retrieve.
     *
     * @return null|mixed The value of the constant if defined, null otherwise.
     */
    public function getConstant(string $key)
    {
        return $this->constants[$key] ?? null;
    }

    public function getAllConstants()
    {
        return $this->constants;
    }

    /**
     * Display a list of constants defined by Setup.
     *
     * Retrieves a list of constants defined by the Setup class,
     * but only if the WP_ENVIRONMENT_TYPE constant is set to 'development', 'debug', or 'staging'.
     * If WP_DEBUG is not defined or is set to false, the function returns ['disabled'].
     *
     * @return string[] Returns an array containing a list of constants defined by Setup, or null if WP_DEBUG is not defined or set to false.
     */
    public function getConstantMap(): array
    {
        return $this->hashSecret($this->constantMap, self::envSecrets());
    }

    public function setMap(): void
    {
        $this->setConstantMap();
    }

    /**
     * Set the constant map based on environmental conditions.
     *
     * This method determines the constant map based on the presence of WP_DEBUG and the environment type.
     * If WP_DEBUG is not defined or set to false, the constant map will be set to ['disabled'].
     * If the environment type is 'development', 'debug', or 'staging', it will use the static $constants property
     * as the constant map if it's an array; otherwise, it will set the constant map to ['invalid_type_returned'].
     */
    private function setConstantMap(): void
    {
        if (! \defined('WP_DEBUG')) {
            $this->constantMap = ['disabled'];
            return;
        }

        if (\defined('WP_DEBUG') && false === constant('WP_DEBUG')) {
            $this->constantMap = ['disabled'];
            return;
        }

        //if (\in_array(env('WP_ENVIRONMENT_TYPE'), ['development', 'debug', 'dev', 'staging'], true)) {
        $constantMap = $this->constants;

        if (\is_array($constantMap)) {
            $this->constantMap = $constantMap;
        } else {
            $this->constantMap = ['invalid_type_returned'];
        }
        //}
    }

    /**
     * Encrypts the values of sensitive data in the given configuration array.
     *
     * This method iterates through the provided $config array, checking each key against the list
     * of sensitive keys provided by the optional $secrets parameter. If a key is found in the sensitive list,
     * the value is hashed using SHA-256 before being added to the resulting $hashed array. Non-sensitive
     * values are added to the array without modification.
     *
     * @param array $config  An associative array containing keys and their corresponding values.
     * @param array $secrets An optional array of sensitive keys that need to be hashed (defaults to null).
     *
     * @return array $hashed An associative array with sensitive values hashed.
     */
    public function hashSecret(array $config, array $secrets = []): array
    {
        $hashed = [];

        foreach ($config as $key => $value) {
            if (\in_array($key, $secrets, true)) {
                $hashed[$key] = hash('sha256', $value);
            } else {
                $hashed[$key] = $value;
            }
        }

        return $hashed;
    }

    /**
     * List of secret values that should always be hashed.
     *
     * @param array $secrets Optional array to merge with the default secrets.
     *
     * @return array
     */
    protected static function envSecrets(array $secrets = []): array
    {
        return array_merge(
            $secrets,
            [
                'DB_USER', 'DB_PASSWORD', 'AUTH_KEY', 'SECURE_AUTH_KEY', 'LOGGED_IN_KEY',
                'NONCE_KEY', 'AUTH_SALT', 'SECURE_AUTH_SALT', 'LOGGED_IN_SALT', 'NONCE_SALT'
            ]
        );
    }
}
