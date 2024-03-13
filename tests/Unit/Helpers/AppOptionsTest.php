<?php

namespace Tests\Unit\Helpers;

use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @coversNothing
 */
class AppOptionsTest extends TestCase
{
    public function test_app_options_with_valid_configs(): void
    {
        $app_path = APP_SRC_PATH . '/inc';
        $result = _app_options($app_path);

        $this->assertIsArray($result);
    }

    public function test_app_options_with_invalid_path(): void
    {
        $app_path = '/invalid/path';
        $result = _app_options($app_path);

        $this->assertNull($result);
    }

    public function test_app_options_with_nonexistent_options_file(): void
    {
        $app_path = '/path/to/your/app';
        $result = _app_options($app_path);

        $this->assertNull($result);
    }
}
