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

interface RequestInterface
{
    /**
     * Get the entire request data.
     *
     * @return array The current request data.
     */
    public function getRequestData(): array;

    /**
     * Get a specific value from query parameters ($_GET).
     *
     * @param string $key The key to fetch from query parameters.
     * @param mixed|null $default The default value if the key is not found.
     * @return mixed The value from query parameters or the default.
     */
    public function getQueryParam(string $key, $default = null);

    /**
     * Get a specific value from request body ($_POST).
     *
     * @param string $key The key to fetch from request body.
     * @param mixed|null $default The default value if the key is not found.
     * @return mixed The value from request body or the default.
     */
    public function getPostParam(string $key, $default = null);

    /**
     * Get the request method.
     *
     * @return string The request method (GET, POST, etc.).
     */
    public function getRequestMethod(): string;

    /**
     * Get all request headers.
     *
     * @return array The list of request headers.
     */
    public function getHeaders(): array;

    /**
     * Get a specific request header by name.
     *
     * @param string $name The name of the header.
     * @param mixed|null $default The default value if the header is not found.
     * @return mixed The header value or the default.
     */
    public function getHeader(string $name, $default = null);
}
