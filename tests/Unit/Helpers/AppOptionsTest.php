<?php

namespace Tests\Unit\Helpers;

use PHPUnit\Framework\TestCase;
use WPframework\Component\Framework;

/**
 * @internal
 *
 * @coversNothing
 */
class AppOptionsTest extends TestCase
{
    public function test_app_options_with_valid_configs(): void
    {
        $result = $this->_app_options();

        $this->assertIsArray($result);
    }
    private function _app_options()
    {
        return (new Framework())->get_app_options();
    }
}
