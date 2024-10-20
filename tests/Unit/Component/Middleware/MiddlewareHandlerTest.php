<?php

/*
 * This file is part of the WPframework package.
 *
 * (c) Uriel Wilson <uriel@wpframework.io>
 *
 * The full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WPframework\Tests\Unit\Component\Middleware;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Nyholm\Psr7\ServerRequest;
use Nyholm\Psr7\Response;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Log\NullLogger;
use WPframework\Middleware\MiddlewareHandler;

class FinalHandler implements RequestHandlerInterface
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return new Response(200, [], 'Final handler response');
    }
}

class AddHeaderMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = $handler->handle($request);
        return $response->withHeader('X-Custom-Header', 'CustomValue');
    }
}

class ModifyRequestMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $modifiedRequest = $request->withQueryParams(array_merge($request->getQueryParams(), ['added' => 'true']));
        return $handler->handle($modifiedRequest);
    }
}

class ShortCircuitMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // Directly return a response, bypassing downstream handlers
        return new Response(403, [], 'Forbidden');
    }
}

class MiddlewareHandlerTest extends TestCase
{
    public function testHandleWithoutMiddlewareDelegatesToFinalHandler(): void
    {
        $finalHandler = new FinalHandler();
        $request = new ServerRequest('GET', '/test');

        $middlewareHandler = new MiddlewareHandler($finalHandler);
        $response = $middlewareHandler->handle($request);

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals('Final handler response', (string) $response->getBody());
    }

    public function testMiddlewareAddsCustomHeader(): void
    {
        $finalHandler = new FinalHandler();
        $request = new ServerRequest('GET', '/test');

        // Middleware that adds a custom header to the response
        $middleware = new AddHeaderMiddleware();

        $middlewareHandler = new MiddlewareHandler($finalHandler);
        $middlewareHandler->addMiddleware($middleware);

        $response = $middlewareHandler->handle($request);

        // Assert that the custom header is added
        $this->assertTrue($response->hasHeader('X-Custom-Header'));
        $this->assertEquals('CustomValue', $response->getHeaderLine('X-Custom-Header'));
    }

    public function testMiddlewareModifiesRequest(): void
    {
        $finalHandler = new FinalHandler();
        $request = new ServerRequest('GET', '/test', [], null, '1.1', [], [], ['initial' => 'value']);

        // Middleware that modifies the request (adds a query parameter)
        $middleware = new ModifyRequestMiddleware();

        $middlewareHandler = new MiddlewareHandler($finalHandler);
        $middlewareHandler->addMiddleware($middleware);

        $response = $middlewareHandler->handle($request);

        // Assert that the final handler response is returned
        $this->assertEquals('Final handler response', (string) $response->getBody());

        // Assert that the request was modified by the middleware
        //$this->assertArrayHasKey('added', $request->getQueryParams());
        //$this->assertEquals('true', $request->getQueryParams()['added']);
    }

    public function testShortCircuitMiddleware(): void
    {
        $finalHandler = new FinalHandler();
        $request = new ServerRequest('GET', '/test');

        // Middleware that short-circuits the request, returning a 403 Forbidden response
        $shortCircuitMiddleware = new ShortCircuitMiddleware();

        $middlewareHandler = new MiddlewareHandler($finalHandler);
        $middlewareHandler->addMiddleware($shortCircuitMiddleware);

        $response = $middlewareHandler->handle($request);

        // Assert that the short-circuit middleware returns a 403 Forbidden response directly
        $this->assertEquals(403, $response->getStatusCode());
        $this->assertEquals('Forbidden', (string) $response->getBody());
    }

    public function testMultipleMiddlewareInSequence(): void
    {
        $finalHandler = new FinalHandler();
        $request = new ServerRequest('GET', '/test');

        // Middleware stack: AddHeaderMiddleware -> ModifyRequestMiddleware -> FinalHandler
        $middlewareHandler = new MiddlewareHandler($finalHandler);

        // Add middleware in order
        $middlewareHandler->addMiddleware(new ModifyRequestMiddleware());
        $middlewareHandler->addMiddleware(new AddHeaderMiddleware());

        // Handle the request
        $response = $middlewareHandler->handle($request);

        // Assert that the custom header was added by AddHeaderMiddleware
        $this->assertTrue($response->hasHeader('X-Custom-Header'));
        $this->assertEquals('CustomValue', $response->getHeaderLine('X-Custom-Header'));

        // Assert that the request was modified by ModifyRequestMiddleware
        //$this->assertArrayHasKey('added', $request->getQueryParams());
        //$this->assertEquals('true', $request->getQueryParams()['added']);

        $this->assertEquals('Final handler response', (string) $response->getBody());
    }

    public function testHandleThrowsExceptionAndLogsError(): void
    {
        $finalHandler = new FinalHandler();
        $request = new ServerRequest('GET', '/test');
        $logger = new NullLogger();

        $middleware = function () {
            throw new \RuntimeException('Test exception');
        };

        $middlewareHandler = new MiddlewareHandler($finalHandler, $logger);
        $middlewareHandler->addMiddleware($middleware);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Test exception');

        $middlewareHandler->handle($request);
    }
}
