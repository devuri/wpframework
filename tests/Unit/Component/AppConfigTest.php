<?php

/*
 * This file is part of the WPframework package.
 *
 * (c) Uriel Wilson <uriel@wpframework.io>
 *
 * The full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WPframework\Tests\Unit\Component;

use PHPUnit\Framework\TestCase;
use WPframework\AppConfig;
use WPframework\ConstantBuilder;

class AppConfigTest extends TestCase
{
    /**
     * @var ConstantBuilder
     */
    private $constantBuilder;

    protected function setUp(): void
    {
        $this->constantBuilder = new ConstantBuilder();
    }

    /**
     * Test adding a constant using the addConstant method.
     */
    public function testAddConstant()
    {
        $appConfig = new AppConfig($this->constantBuilder);

        $appConfig->addConstant('APP_NAME', 'MyApp');

        $this->assertTrue(defined('APP_NAME'));
        $this->assertEquals('MyApp', constant('APP_NAME'));
    }

    /**
     * Test adding a constant using the addConst method (alias).
     */
    public function testAddConst()
    {
        $appConfig = new AppConfig($this->constantBuilder);

        $appConfig->addConst('APP_VERSION', '1.0.0');

        $this->assertTrue(defined('APP_VERSION'));
        $this->assertEquals('1.0.0', constant('APP_VERSION'));
    }

    /**
     * Test retrieving a constant using the getConstant method.
     */
    public function testGetConstant()
    {
        $appConfig = new AppConfig($this->constantBuilder);
		$appConfig->addConst('APP_NAME', 'MyApp');

        $this->assertEquals('MyApp', $appConfig->getConstant('APP_NAME'));
    }

    /**
     * Test checking if a constant is defined.
     */
    public function testIsConstantDefined()
    {
        $appConfig = new AppConfig($this->constantBuilder);
		$appConfig->addConst('APP_NAME', 'MyApp');

        $this->assertTrue($appConfig->isConstantDefined('APP_NAME'));
        $this->assertFalse($appConfig->isConstantDefined('NON_EXISTENT_CONSTANT'));
    }

    /**
     * Test setConstantMap method.
     */
    public function testSetConstantMap()
    {
        $appConfig = new AppConfig($this->constantBuilder);

        $appConfig->setConstantMap();
        $this->assertIsArray($this->constantBuilder->getConstantMap());
    }

    /**
     * Test getting all defined constants.
     */
    public function testGetDefinedConstants()
    {
        $appConfig = new AppConfig($this->constantBuilder);

        $appConfig->addConstant('APP_NAME', 'MyApp');
        $appConfig->addConstant('APP_VERSION', '1.0.0');

        $definedConstants = $appConfig->getDefinedConstants();

        $this->assertEquals([
            'APP_NAME' => 'MyApp',
            'APP_VERSION' => '1.0.0',
        ], $definedConstants);
    }
}
