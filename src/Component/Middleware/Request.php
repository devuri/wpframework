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

class Request implements RequestInterface
{
    private $get;
    private $post;
    private $server;

    public function __construct(?array $get = null, ?array $post = null, ?array $server = null)
    {
        $this->get = $get ?? $_GET;
        $this->post = $post ?? $_POST;
        $this->server = $server ?? $_SERVER;
    }

    /**
     * Get the entire request data.
     *
     * @return array The current request data.
     */
    public function getRequestData(): array
    {
        return [
            'query'   => $this->get,
            'body'    => $this->post,
            'headers' => $this->getHeaders(),
        ];
    }

    /**
     * Get a specific value from query parameters ($_GET), with optional validation.
     *
     * @param string    $key     The key to fetch from query parameters.
     * @param mixed     $default The default value if the key is not found or validation fails.
     * @param int|null  $filter  The filter to apply (e.g., FILTER_VALIDATE_INT).
     * @param array     $options Optional filter options.
     * @return mixed The validated value from query parameters or the default.
     */
    public function getQueryParam(string $key, $default = null, int $filter = null, array $options = [])
    {
        $value = $this->get[$key] ?? null;

        if ($value === null) {
            return $default;
        }

        if ($filter !== null) {
            $filteredValue = filter_var($value, $filter, $options);
            if ($filteredValue === false || $filteredValue === null) {
                return $default;
            }
            return $filteredValue;
        }

        return $value;
    }

    /**
     * Get a specific value from request body ($_POST), with optional validation.
     *
     * @param string    $key     The key to fetch from request body.
     * @param mixed     $default The default value if the key is not found or validation fails.
     * @param int|null  $filter  The filter to apply.
     * @param array     $options Optional filter options.
     * @return mixed The validated value from request body or the default.
     */
    public function getPostParam(string $key, $default = null, ?int $filter = null, array $options = [])
    {
        $value = $this->post[$key] ?? null;

        if ($value === null) {
            return $default;
        }

        if ($filter !== null) {
            $filteredValue = filter_var($value, $filter, $options);
            if ($filteredValue === false || $filteredValue === null) {
                return $default;
            }
            return $filteredValue;
        }

        return $value;
    }

    /**
     * Get the request method.
     *
     * @return string The request method (GET, POST, etc.).
     */
    public function getRequestMethod(): string
    {
        $method = $this->server['REQUEST_METHOD'] ?? 'GET';
        return strtoupper($method);
    }

    /**
     * Get all request headers.
     *
     * @return array The list of request headers.
     */
    public function getHeaders(): array
    {
        $headers = [];

        foreach ($this->server as $key => $value) {
            if (strpos($key, 'HTTP_') === 0) {
                // Convert HTTP_HEADER_NAME to Header-Name
                $headerName = str_replace('_', '-', substr($key, 5));
                $headerName = ucwords(strtolower($headerName), '-');
                $headers[$headerName] = $value;
            }
        }

        // Add other common headers that are not prefixed by HTTP_
        $additionalHeaders = ['CONTENT_TYPE', 'CONTENT_LENGTH', 'CONTENT_MD5'];
        foreach ($additionalHeaders as $header) {
            if (isset($this->server[$header])) {
                $headerName = str_replace('_', '-', $header);
                $headerName = ucwords(strtolower($headerName), '-');
                $headers[$headerName] = $this->server[$header];
            }
        }

        return $headers;
    }

    // public function getQueryParam(string $key, $default = null)
    // {
    //     $value = filter_input(INPUT_GET, $key, FILTER_SANITIZE_STRING);
    //     return $value !== null ? $value : $default;
    // }
    //
    // public function getPostParam(string $key, $default = null)
    // {
    //     $value = filter_input(INPUT_POST, $key, FILTER_SANITIZE_STRING);
    //     return $value !== null ? $value : $default;
    // }

    public function getQueryParamInt(string $key, int $default = 0): int
    {
        return filter_input(INPUT_GET, $key, FILTER_VALIDATE_INT) ?? $default;
    }

    /**
     * Get a specific request header by name (case-insensitive).
     *
     * @param string $name    The name of the header.
     * @param mixed  $default The default value if the header is not found.
     * @return mixed The header value or the default.
     */
    public function getHeader(string $name, $default = null)
    {
        $headers = $this->getHeaders();

        // Normalize header name for case-insensitive lookup
        $normalizedName = ucwords(strtolower($name), '-');

        return $headers[$normalizedName] ?? $default;
    }
}
