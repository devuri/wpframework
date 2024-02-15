<?php

namespace Tests\Unit\Framework\Http;

use Tests\BaseTest;

/**
 * Test the Kernel.
 *
 * @internal
 *
 * @coversNothing
 */
class AppTest extends BaseTest
{
    public function test_class_exists_is_true(): void
    {
        $this->assertTrue( class_exists('WPframework\Component\Http\App') );
    }
}
