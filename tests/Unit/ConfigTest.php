<?php

namespace Tests\Unit;

use Tests\BaseTest;
use Urisoft\DotAccess;

/**
 * @internal
 *
 * @coversNothing
 */
class ConfigTest extends BaseTest
{
    public function test_config_function_with_valid_key(): void
    {
        $configData = [
            'app' => [
                'name' => 'MyApp',
                'debug' => true,
            ],
            'database' => [
                'host' => 'localhost',
                'port' => 3306,
            ],
        ];

        $keyToTest = 'app.name';

        $result = config($keyToTest, null, new DotAccess(  $configData ) );

        $this->assertEquals($configData['app']['name'], $result);
    }

    public function test_config_function_with_invalid_key(): void
    {
        $keyToTest = 'nonexistent.key';

        $result = config($keyToTest, null, new DotAccess( [] ) );

        $this->assertNull($result);
    }

    public function test_config_has_array(): void
    {
        // TODO fix: Failed to open stream: No such file or directory
        // happens becuase APP_PATH is set to test dir, so we cant get to the
        // configs in src
        // $configs = config();

        // $this->assertIsArray( $configs );
    }

    private static function array_data()
    {
        $_config_array_data = require \dirname( __FILE__, 3 ) . '/src/inc/configs/app.php';

        return $_config_array_data;
    }

    private static function not_array_data()
    {
        return \dirname( __FILE__, 3 ) . '/src/inc/app.php';
    }
}
