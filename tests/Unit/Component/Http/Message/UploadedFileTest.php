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
use WPframework\Http\Message\UploadedFile;
use Nyholm\Psr7\Stream;
use Psr\Http\Message\UploadedFileInterface;

class UploadedFileTest extends TestCase
{
    public function testItCreatesInstanceOfUploadedFileInterface()
    {
        $stream = Stream::create('php://memory', 'r+');
        $uploadedFile = new UploadedFile($stream, 100, UPLOAD_ERR_OK, 'test.txt', 'text/plain');
        $this->assertInstanceOf(UploadedFileInterface::class, $uploadedFile);
    }

    public function testItReturnsFileSize()
    {
        $stream = Stream::create('php://memory', 'r+');
        $uploadedFile = new UploadedFile($stream, 100, UPLOAD_ERR_OK, 'test.txt', 'text/plain');
        $this->assertEquals(100, $uploadedFile->getSize());
    }

    public function testItReturnsClientFilename()
    {
        $stream = Stream::create('php://memory', 'r+');
        $uploadedFile = new UploadedFile($stream, 100, UPLOAD_ERR_OK, 'test.txt', 'text/plain');
        $this->assertEquals('test.txt', $uploadedFile->getClientFilename());
    }

    public function testItCanMoveUploadedFile()
    {
        $stream = Stream::create('php://memory', 'r+');
        $uploadedFile = new UploadedFile($stream, 100, UPLOAD_ERR_OK, 'test.txt', 'text/plain');

        $tmpFile = tempnam(sys_get_temp_dir(), 'test');
        $uploadedFile->moveTo($tmpFile);

        $this->assertFileExists($tmpFile);
        unlink($tmpFile); // Cleanup
    }
}
