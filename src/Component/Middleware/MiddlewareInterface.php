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

interface MiddlewareInterface
{
    /**
     * Handle the request and decide whether to pass it on or terminate.
     *
     * @param array $request The server request data (e.g., $_SERVER).
     * @param callable $next The next middleware in the stack.
     * @return mixed
     */
    public function process(array $request, callable $next);
}
