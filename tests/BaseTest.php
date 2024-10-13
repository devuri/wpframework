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

use Brain\Monkey;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;

abstract class BaseTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    protected function setUp(): void
    {
        parent::setUp();
        Monkey\setUp();
    }

    protected function tearDown(): void
    {
        Monkey\tearDown();
        parent::tearDown();
    }

    public function default_args(): array
    {
        return [
            "web_root" => "public",
            "wp_dir_path" => "wp",
            "wordpress" => "wp",
            "directory" => [
                "web_root_dir" => "public",
                "content_dir" => "content",
                "plugin_dir" => "content/plugins",
                "mu_plugin_dir" => "content/mu-plugins",
                "sqlite_dir" => "sqlitedb",
                "sqlite_file" => ".sqlite-wpdatabase",
                "theme_dir" => "templates",
                "asset_dir" => "assets",
                "publickey_dir" => "pubkeys",
            ],
            "default_theme" => "twentytwentythree",
            "disable_updates" => true,
            "can_deactivate" => true,
            "error_handler" => "symfony",
            "config_file" => "config",
            'templates_dir' => null,
            "sudo_admin" => null,
            "sudo_admin_group" => null,
            "sucuri_waf" => false,
            'redis' => [],
            'security' => [],
        ];
    }
}
