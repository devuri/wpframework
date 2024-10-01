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

use InvalidArgumentException;

class HttpRequest
{
    /**
     * @var array The GET parameters.
     */
    protected $query = [];

    /**
     * @var array The POST parameters.
     */
    protected $request = [];

    /**
     * @var array The request attributes, typically parameters parsed from the PATH_INFO.
     */
    protected $attributes = [];

    /**
     * @var array The COOKIE parameters.
     */
    protected $cookies = [];

    /**
     * @var array The FILES parameters.
     */
    protected $files = [];

    /**
     * @var array The SERVER parameters.
     */
    protected $server = [];

    /**
     * @var string|resource|null The raw body content, either a string, resource, or null.
     */
    protected $content = null;

    /**
     * Set the HTTP global variables for the request.
     *
     * @param array                $query      The GET parameters.
     * @param array                $request    The POST parameters.
     * @param array                $attributes The request attributes (e.g., parameters parsed from the PATH_INFO).
     * @param array                $cookies    The COOKIE parameters.
     * @param array                $files      The FILES parameters.
     * @param array                $server     The SERVER parameters.
     * @param string|resource|null $content    The raw body data, can be a string, a resource, or null.
     *
     * @return void
     */
    public function __construct(
        array $query = [],
        array $request = [],
        array $attributes = [],
        array $cookies = [],
        array $files = [],
        array $server = [],
        $content = null,
    ) {
        $this->validateContent($content);

        $this->query = $query;
        $this->request = $request;
        $this->attributes = $attributes;
        $this->cookies = $cookies;
        $this->files = $files;
        $this->server = $server;
        $this->content = $content;
    }

    /**
     * Get the GET parameters.
     *
     * @return array
     */
    public function getQuery(): array
    {
        return $this->query;
    }

    /**
     * Set the GET parameters.
     *
     * @param array $query The GET parameters.
     * @return void
     */
    public function setQuery(array $query): void
    {
        $this->query = $query;
    }

    /**
     * Get the POST parameters.
     *
     * @return array
     */
    public function getRequest(): array
    {
        return $this->request;
    }

    /**
     * Set the POST parameters.
     *
     * @param array $request The POST parameters.
     * @return void
     */
    public function setRequest(array $request): void
    {
        $this->request = $request;
    }

    /**
     * Get the request attributes.
     *
     * @return array
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * Set the request attributes.
     *
     * @param array $attributes The request attributes.
     * @return void
     */
    public function setAttributes(array $attributes): void
    {
        $this->attributes = $attributes;
    }

    /**
     * Get the COOKIE parameters.
     *
     * @return array
     */
    public function getCookies(): array
    {
        return $this->cookies;
    }

    /**
     * Set the COOKIE parameters.
     *
     * @param array $cookies The COOKIE parameters.
     * @return void
     */
    public function setCookies(array $cookies): void
    {
        $this->cookies = $cookies;
    }

    /**
     * Get the FILES parameters.
     *
     * @return array
     */
    public function getFiles(): array
    {
        return $this->files;
    }

    /**
     * Set the FILES parameters.
     *
     * @param array $files The FILES parameters.
     * @return void
     */
    public function setFiles(array $files): void
    {
        $this->files = $files;
    }

    /**
     * Get the SERVER parameters.
     *
     * @return array
     */
    public function getServer(): array
    {
        return $this->server;
    }

    /**
     * Set the SERVER parameters.
     *
     * @param array $server The SERVER parameters.
     * @return void
     */
    public function setServer(array $server): void
    {
        $this->server = $server;
    }

    /**
     * Get the raw body content.
     *
     * @return string|resource|null
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set the raw body content.
     *
     * @param string|resource|null $content The raw body content.
     * @return void
     */
    public function setContent($content): void
    {
        $this->validateContent($content);
        $this->content = $content;
    }

    /**
     * Validate the content to ensure it is either a string, resource, or null.
     *
     * @param mixed $content The content to validate.
     * @return void
     *
     * @throws InvalidArgumentException If the content is not of the correct type.
     */
    protected function validateContent($content): void
    {
        if (!is_null($content) && !is_string($content) && !is_resource($content)) {
            throw new InvalidArgumentException(
                sprintf(
                    'Content must be a string, a resource, or null. %s given.',
                    gettype($content)
                )
            );
        }
    }
}
