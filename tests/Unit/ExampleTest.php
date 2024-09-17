<?php

/*
 * This file is part of the WPframework package.
 *
 * (c) Uriel Wilson <uriel@wpframework.io>
 *
 * The full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WPframework\Tests;

use Tests\BaseTest;

/**
 * @internal
 *
 * @coversNothing
 */
class ExampleTest extends BaseTest
{
    public function test_example_true_is_true(): void
    {
        $this->assertTrue(true);
    }
}
