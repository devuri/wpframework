<?php

/*
 * This file is part of the WPframework package.
 *
 * (c) Uriel Wilson <uriel@wpframework.io>
 *
 * The full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WPframework\Tests\Unit\Component\Http\Message;

use PHPUnit\Framework\TestCase;
use WPframework\Http\Message\ServerRequest;
use Nyholm\Psr7\Uri;
use Psr\Http\Message\ServerRequestInterface;

class ServerRequestTest extends TestCase
{
    public function testItCreatesInstanceOfServerRequestInterface()
    {
        $request = new ServerRequest('GET', new Uri('https://example.com'));
        $this->assertInstanceOf(ServerRequestInterface::class, $request);
    }

    public function testItReturnsMethod()
    {
        $request = new ServerRequest('POST', new Uri('https://example.com'));
        $this->assertEquals('POST', $request->getMethod());
    }

    public function testItCanAddHeaders()
    {
        $request = new ServerRequest('GET', new Uri('https://example.com'));
        $request = $request->withHeader('X-Test', 'test-value');
        $this->assertEquals(['test-value'], $request->getHeader('X-Test'));
    }

    public function testItCanRetrieveQueryParams()
    {
        $request = new ServerRequest('GET', new Uri('https://example.com?foo=bar'));
        $this->assertEquals(['foo' => 'bar'], $request->getQueryParams());
    }
}
