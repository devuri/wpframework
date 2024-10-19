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
use WPframework\ConstantBuilder;

class ConstantBuilderTest extends TestCase
{
    /**
     * @var ConstantBuilder
     */
    private $constantBuilder;

    /**
     * Set up the test environment.
     */
    protected function setUp(): void
    {
        // Create a new instance of ConstantBuilder
        $this->constantBuilder = new ConstantBuilder();
    }

    /**
     * Test defining a constant.
     */
    public function testDefineConstant()
    {
        // Define a constant using ConstantBuilder
        $this->constantBuilder->define('APP_NAME', 'MyApp');

        // Check if the constant is defined and has the correct value
        $this->assertTrue(defined('APP_NAME'));
        $this->assertEquals('MyApp', constant('APP_NAME'));

        // Check if it's also stored in the internal array
        $this->assertEquals('MyApp', $this->constantBuilder->getConstant('APP_NAME'));
    }

    /**
     * Test defining a constant that is already defined.
     */
    public function testDefineAlreadyDefinedConstant()
    {
        // Define a constant
        $this->constantBuilder->define('APP_NAME', 'MyApp');

        // Try defining the same constant again (shouldn't throw an error but won't redefine it)
        $this->constantBuilder->define('APP_NAME', 'MyNewApp');

        // Ensure the constant's value hasn't changed
        $this->assertEquals('MyApp', constant('APP_NAME'));
    }

    /**
     * Test checking if a constant is defined.
     */
    public function testIsDefined()
    {
        // Define a constant
        $this->constantBuilder->define('APP_NAME', 'MyApp');

        // Check if the constant is defined
        $this->assertTrue($this->constantBuilder->isDefined('APP_NAME'));

        // Check for a constant that hasn't been defined
        $this->assertFalse($this->constantBuilder->isDefined('NON_EXISTENT_CONSTANT'));
    }

    /**
     * Test getting a constant.
     */
    public function testGetConstant()
    {
        // Define a constant
        $this->constantBuilder->define('APP_NAME', 'MyApp');

        // Retrieve the constant's value
        $this->assertEquals('MyApp', $this->constantBuilder->getConstant('APP_NAME'));

        // Try to get a non-existent constant
        $this->assertNull($this->constantBuilder->getConstant('NON_EXISTENT_CONSTANT'));
    }

    /**
     * Test getting all defined constants.
     */
    public function testGetAllConstants()
    {
        // Define multiple constants
        $this->constantBuilder->define('APP_NAME', 'MyApp');
        $this->constantBuilder->define('APP_VERSION', '1.0.0');

        // Get all defined constants
        $allConstants = $this->constantBuilder->getAllConstants();

        // Check that all constants are returned correctly
        $this->assertEquals([
            'APP_NAME' => 'MyApp',
            'APP_VERSION' => '1.0.0',
        ], $allConstants);
    }

    /**
     * Test setting and getting the constant map.
     */
    public function testSetConstantMap()
    {
        // Assuming WP_DEBUG is not defined
        $this->constantBuilder->setMap();

        $constantMap = $this->constantBuilder->getConstantMap();
        $this->assertEquals(['disabled'], $constantMap);

        // Define WP_DEBUG and check if the constant map changes accordingly
        define('WP_DEBUG', true);
        define('WP_ENVIRONMENT_TYPE', 'development');

        $this->constantBuilder->define('TEST_CONSTANT', 'value');
        $this->constantBuilder->setMap();
        $constantMap = $this->constantBuilder->getConstantMap();

        // Check that the constant map now includes defined constants
        $this->assertEquals(['TEST_CONSTANT' => 'value'], $constantMap);
    }

    /**
     * Test hashing sensitive keys.
     */
    public function testHashSecret()
    {
        $config = [
            'DB_USER' => 'admin',
            'DB_PASSWORD' => 'secret',
            'NON_SENSITIVE_KEY' => 'publicValue',
        ];

        $hashedConfig = $this->constantBuilder->hashSecret($config, ['DB_USER', 'DB_PASSWORD']);

        $this->assertNotEquals('admin', $hashedConfig['DB_USER']);
        $this->assertNotEquals('secret', $hashedConfig['DB_PASSWORD']);
        $this->assertEquals('publicValue', $hashedConfig['NON_SENSITIVE_KEY']);
    }

    public function test_define_constant(): void
    {
        $constName = 'MY_CONSTANT';
        $this->assertFalse(\defined($constName));

        $constValue = 'my_value';
        $this->constantBuilder->define($constName, $constValue);

        $this->assertTrue(\defined($constName));

        $this->assertEquals($constValue, \constant($constName));

        // $this->expectException(ConstantAlreadyDefinedException::class);
        // $this->constantBuilder->define($constName, 'new_value');
    }

    public function test_is_constant_defined(): void
    {
        $constName = 'ANOTHER_CONSTANT';

        $this->assertFalse($this->constantBuilder->isDefined($constName));

        $this->constantBuilder->define($constName, 'some_value');

        $this->assertTrue($this->constantBuilder->isDefined($constName));
    }

    public function test_get_constant(): void
    {
        $constName = 'BOOLEAN_CONSTANT';
        $constValue = true;

        $this->constantBuilder->define($constName, $constValue);

        $result = $this->constantBuilder->getConstant($constName);

        $this->assertEquals($constValue, $result);

        $result = $this->constantBuilder->getConstant('UNDEFINED_CONSTANT');
        $this->assertNull($result);
    }

    public function test_get_constant_map(): void
    {
        unset($_ENV['WP_DEBUG']);
        $this->assertEquals(['disabled'], $this->constantBuilder->getConstantMap());

        $_ENV['WP_DEBUG'] = false;
        $this->assertEquals(['disabled'], $this->constantBuilder->getConstantMap());

        $_ENV['WP_DEBUG'] = true;
        $_ENV['WP_ENVIRONMENT_TYPE'] = 'development';
        $this->assertEquals(['disabled'], $this->constantBuilder->getConstantMap());

        $_ENV['WP_ENVIRONMENT_TYPE'] = 'staging';
        $this->assertEquals(['disabled'], $this->constantBuilder->getConstantMap());

        $_ENV['WP_ENVIRONMENT_TYPE'] = 'production';
        $this->assertEquals(['disabled'], $this->constantBuilder->getConstantMap());

        $_ENV['WP_ENVIRONMENT_TYPE'] = 'custom_environment';
        $this->assertEquals(['disabled'], $this->constantBuilder->getConstantMap());

        $_ENV['WP_ENVIRONMENT_TYPE'] = 'debug';
        $this->assertEquals(['disabled'], $this->constantBuilder->getConstantMap());
    }

    // public function test_define_constant(): void
    // {
    //     $constName = 'TEST_CONSTANT';
    //     $constValue = 'TestValue';
    //
    //     $this->assertFalse($this->is_defined($constName));
    //
    //     $this->constantBuilder->define($constName, $constValue);
    //
    //     $this->assertTrue($this->is_defined($constName));
    //
    //     $this->assertEquals($constValue, $this->get_constant($constName));
    // }

    // public function test_define_duplicate_constant(): void
    // {
    //     $constName = 'DUPLICATE_CONSTANT';
    //     $constValue1 = 'Value1';
    //     $constValue2 = 'Value2';
    //
    //     $this->constantBuilder->define($constName, $constValue1);
    //
    //     $this->expectException(ConstantAlreadyDefinedException::class);
    //     $this->expectExceptionMessage("Constant: $constName has already been defined");
    //
    //     $this->constantBuilder->define($constName, $constValue2);
    // }
}
