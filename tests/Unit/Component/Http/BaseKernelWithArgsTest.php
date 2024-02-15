<?php

namespace Tests\Unit\Component\Http;

use Tests\BaseTest;
use WPframework\Component\Http\BaseKernel;

/**
 * Test the Kernel.
 *
 * @internal
 *
 * @coversNothing
 */
class BaseKernelWithArgsTest extends BaseTest
{
    public function test_http_app_with_args(): void
    {
        $args = [
            'web_root'      => 'public',
            'wordpress'     => 'cms',
        ];

        $app_with_args = new BaseKernel( getenv('FAKE_APP_DIR_PATH'), $args );

        $output = array_merge( $this->default_args(), [
            "wp_dir_path" => "cms",
            "wordpress" => "cms",
        ] );

        $this->assertEquals( $output, $app_with_args->get_args());
    }
}