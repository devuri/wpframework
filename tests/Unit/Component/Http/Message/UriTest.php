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
use WPframework\Http\Message\Uri;
use Psr\Http\Message\UriInterface;

class UriTest extends TestCase
{
    public function testItCreatesInstanceOfUriInterface()
    {
        $uri = new Uri('https://example.com');
        $this->assertInstanceOf(UriInterface::class, $uri);
    }

    public function testItReturnsScheme()
    {
        $uri = new Uri('https://example.com');
        $this->assertEquals('https', $uri->getScheme());
    }

    public function testItReturnsHost()
    {
        $uri = new Uri('https://example.com');
        $this->assertEquals('example.com', $uri->getHost());
    }

    public function testItCanChangePath()
    {
        $uri = new Uri('https://example.com');
        $uri = $uri->withPath('/new-path');
        $this->assertEquals('/new-path', $uri->getPath());
    }
}
