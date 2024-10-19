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
use WPframework\Http\Message\Stream;
use Psr\Http\Message\StreamInterface;

class StreamTest extends TestCase
{
    public function testItCreatesInstanceOfStreamInterface()
    {
        $stream = Stream::create('php://memory', 'r+');
        $this->assertInstanceOf(StreamInterface::class, $stream);
    }

    public function testItCanWriteToStream()
    {
        $stream = Stream::create('php://memory', 'r+');
        $stream->write('Hello, World!');

        $stream->rewind();
        $this->assertEquals('Hello, World!', $stream->getContents());
    }

    // public function testItCanReadStreamContents()
    // {
    //     $stream = Stream::create('php://memory', 'r+');
    //     $stream->write('Test stream');
    //
    //     $stream->rewind();
    //     $this->assertEquals('Test stream', $stream->getContents());
    // }
}
