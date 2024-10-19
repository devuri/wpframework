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
use WPframework\Http\Message\Request;
use Nyholm\Psr7\Uri;
use Psr\Http\Message\RequestInterface;

class RequestTest extends TestCase
{
    public function testItCreatesInstanceOfRequestInterface()
    {
        $request = new Request('GET', new Uri('https://example.com'));
        $this->assertInstanceOf(RequestInterface::class, $request);
    }

    public function testItReturnsUri()
    {
        $uri = new Uri('https://example.com');
        $request = new Request('GET', $uri);
        $this->assertEquals($uri, $request->getUri());
    }

    public function testItCanChangeMethod()
    {
        $request = new Request('GET', new Uri('https://example.com'));
        $request = $request->withMethod('POST');
        $this->assertEquals('POST', $request->getMethod());
    }
}
