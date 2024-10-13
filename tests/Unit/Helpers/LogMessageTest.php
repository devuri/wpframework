<?php

/*
 * This file is part of the WPframework package.
 *
 * (c) Uriel Wilson <uriel@wpframework.io>
 *
 * The full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WPframework\Tests\Helpers;

use PHPUnit\Framework\TestCase;
use WPframework\Logger\Log;
use Psr\Log\InvalidArgumentException;

class LogMessageTest extends TestCase
{
    private $logFile;
    private $ErrorLogFile;
    private $customLogFile;

    protected function setUp(): void
    {
        $this->customLogFile = APP_TEST_PATH . '/custom.log';
        $this->ErrorLogFile = APP_TEST_PATH . '/errorlog-test.log';
        $this->logFile = APP_TEST_PATH . '/test.log';

        makeLogFile($this->customLogFile);

        makeLogFile($this->logFile);
        $log = Log::init($this->logFile);

        makeLogFile($this->ErrorLogFile);
        ini_set('error_log', $this->ErrorLogFile);
    }

    protected function tearDown(): void
    {
        $this->deleteTestFiles();
        ini_restore('error_log');
    }

    private function deleteTestFiles()
    {
        if (file_exists($this->logFile)) {
            unlink($this->logFile);
        }

        if (file_exists($this->ErrorLogFile)) {
            unlink($this->ErrorLogFile);
        }

        if (file_exists($this->customLogFile)) {
            unlink($this->customLogFile);
        }
    }

    /**
     * Test logging an info message with logMessage()
     */
    public function testLogInfoMessage(): void
    {
        logMessage('This is an info message', 'info', [], $this->logFile);

        $this->assertFileExists($this->logFile);
        $logContent = file_get_contents($this->logFile);
        $this->assertStringContainsString('INFO: This is an info message', $logContent);
    }

    /**
     * Test logging an error message with context using logMessage()
     */
    public function testLogErrorWithContext(): void
    {
        logMessage('Error occurred: {error}', 'error', ['error' => 'Something went wrong'], $this->logFile);

        $this->assertFileExists($this->logFile);
        $logContent = file_get_contents($this->logFile);
        $this->assertStringContainsString('ERROR: Error occurred: Something went wrong', $logContent);
    }

    /**
     * Test logging to a custom file using logMessage()
     */
    public function testLogToCustomFile(): void
    {
        logMessage('Debugging process', 'debug', [], $this->customLogFile);

        $this->assertFileExists($this->customLogFile);
        $logContent = file_get_contents($this->customLogFile);
        $this->assertStringContainsString('DEBUG: Debugging process', $logContent);
    }

    /**
     * Test logging fallback to error_log when no file is provided
     */
    public function testFallbackToErrorLog(): void
    {
        logMessage('This is a warning message', 'warning');

        $ErrorLogContent = file_get_contents($this->ErrorLogFile);
        $this->assertStringContainsString('WARNING: This is a warning message', $ErrorLogContent);
    }

    /**
     * Test logging an invalid log level throws an exception
     */
    public function testInvalidLogLevelThrowsException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        logMessage('This should fail', 'invalid_level');
    }
}
