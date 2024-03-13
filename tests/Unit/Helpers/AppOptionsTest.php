<?php

namespace Tests\Unit\Helpers;

use PHPUnit\Framework\TestCase;

class AppOptionsTest extends TestCase {

	public function test_app_options_with_valid_configs() {
        $app_path = APP_SRC_PATH.'/inc';
        $result = _app_options($app_path);

        $this->assertIsArray($result);
    }

    public function test_app_options_with_invalid_path() {
        $app_path = '/invalid/path';
        $result = _app_options($app_path);

        $this->assertNull($result);
    }

    public function test_app_options_with_nonexistent_options_file() {
        $app_path = '/path/to/your/app';
        $result = _app_options($app_path);

        $this->assertNull($result);
    }
}
