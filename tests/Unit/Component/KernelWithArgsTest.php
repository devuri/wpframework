<?php

namespace Tests\Unit\Component;

use Tests\BaseTest;
use WPframework\Kernel;

/**
 * Test the Kernel.
 *
 * @internal
 *
 * @coversNothing
 */
class KernelWithArgsTest extends BaseTest
{
    public function test_http_app_with_args(): void
    {
        $args = [
            'web_root_dir'      => 'public',
            'wordpress'     => 'cms',
        ];

        $app_with_args = new Kernel( getenv('FAKE_APP_DIR_PATH'), $args );

        $output = array_merge( $this->default_args(), [
            "wp_dir_path" => "cms",
            "wordpress" => "cms",
        ] );

        $this->assertEquals( $output, $app_with_args->get_args());
    }
}
