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

use Psr\Log\LoggerInterface;

class GateKeeper
{
    protected array $middleware = [];
    protected RequestInterface $request;
    protected LoggerInterface $logger;

    /**
     * GateKeeper.
     *
     * @param RequestInterface $request The request handler for retrieving the current request.
     * @param LoggerInterface $logger The logger instance for capturing errors or important information.
     */
    public function __construct(
        RequestInterface $request,
        LoggerInterface $logger,
    ) {
        $this->middleware = [];
        $this->request = $request;
        $this->logger = $logger;
    }

    /**
     * Add a middleware to the stack.
     *
     * @param MiddlewareInterface $middleware The middleware to add.
     */
    public function addMiddleware(MiddlewareInterface $middleware): void
    {
        $this->middleware[] = $middleware;
    }

    /**
     * Run all middleware to handle the incoming request.
     *
     * This method processes each middleware in a pipeline, reducing them
     * into a closure that is executed with the current request.
     *
     * @return bool Whether the request is allowed by the middleware.
     */
    public function run(): bool
    {
        $request = $this->getRequest();

        try {
            $pipeline = array_reduce(
                array_reverse($this->middleware),
                function ($next, MiddlewareInterface $middleware) {
                    return function ($request) use ($middleware, $next) {
                        return $middleware->handle($request, $next);
                    };
                },
                function ($request) {
                    return true;
                }
            );

            return $pipeline($request);
        } catch (\Throwable $e) {

            $this->logger->error('Middleware execution failed', [
                'exception' => $e,
                'request' => $request,
            ]);
            return false;
        }
    }

    protected function getRequest(): array
    {
        return $this->request->getRequestData();
    }
}
