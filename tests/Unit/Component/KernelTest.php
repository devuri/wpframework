<?php

namespace Tests\Unit\Component;

use PHPUnit\Framework\TestCase;
use WPframework\Component\Kernel;

/**
 * Test the Kernel.
 *
 * @internal
 *
 * @coversNothing
 */
class KernelTest extends TestCase
{
    protected $http_app = null;

    protected function setUp(): void
    {
        $this->http_app = new Kernel( APP_TEST_PATH );
    }

    protected function tearDown(): void
    {
        $this->http_app = null;
    }

    public function test_get_app_path(): void
    {
        $this->assertEquals( APP_TEST_PATH , $this->http_app->get_app_path() );
    }

    public function test_default_args(): void
    {
        $default = [
            "terminate" => [
                'debugger' => false,
            ],
            "security" => [
                "encryption_key" => null,
                "brute-force" => true,
                "two-factor" => true,
                "no-pwned-passwords" => true,
                "admin-ips" => [],
            ],
            "mailer" => [
                "brevo" => ["apikey" => ""],
                "postmark" => ["token" => ""],
                "sendgrid" => ["apikey" => ""],
                "mailerlite" => ["apikey" => ""],
                "mailgun" => [
                    "domain" => "",
                    "secret" => "",
                    "endpoint" => "api.mailgun.net",
                    "scheme" => "https",
                ],
                "ses" => [
                    "key" => "",
                    "secret" => "",
                    "region" => "us-east-1",
                ],
            ],
            "sudo_admin" => 1,
            "sudo_admin_group" => null,
            "web_root" => "public",
            "s3uploads" => [
                "bucket" => "site-uploads",
                "key" => "",
                "secret" => "",
                "region" => "us-east-1",
                "bucket-url" => "https://example.com",
                "object-acl" => "public",
                "expires" => "2 days",
                "http-cache" => 300,
            ],
            "asset_dir" => "assets",
            "content_dir" => "app",
            "plugin_dir" => "plugins",
            "mu_plugin_dir" => "mu-plugins",
            "sqlite_dir" => "sqlitedb",
            "sqlite_file" => ".sqlite-wpdatabase",
            "default_theme" => "brisko",
            "disable_updates" => true,
            "can_deactivate" => true,
            "theme_dir" => "templates",
            "error_handler" => null,
            "redis" => [
                "disabled" => false,
                "host" => "127.0.0.1",
                "port" => 6379,
                "password" => "",
                "adminbar" => false,
                "disable-metrics" => false,
                "disable-banners" => false,
                "prefix" => "c984d06aafbecf6bc55569f964148ea3redis-cache",
                "database" => 0,
                "timeout" => 1,
                "read-timeout" => 1,
            ],
            "publickey" => ["app-key" => "b75b666f-ac11-4342-b001-d2546f1d3a5b"],
            "publickey_dir" => "pubkeys",
            "config_file" => "config",
            "wp_dir_path" => "wp",
            "wordpress" => "wp",
            'templates_dir' => null,
            'sucuri_waf' => false,
        ];

        $this->assertEquals( $default, $this->http_app->get_args());
    }

    public function test_constants_defined(): void
    {
        $this->http_app->set_config_constants();

        $app_test_path = APP_TEST_PATH;

        $const_defaults = [
            "APP_PATH" => $app_test_path,
            "APP_HTTP_HOST" => "default_domain.com",
            "PUBLIC_WEB_DIR" => $app_test_path . "/public",

            // directory setup.
            "WP_DIR_PATH" => $app_test_path . "/public/wp",
            "APP_ASSETS_DIR" => $app_test_path . "/public/assets",
            "APP_CONTENT_DIR" => "app",
            "WP_CONTENT_DIR" => $app_test_path . "/public/app",
            "WP_CONTENT_URL" => "https://example.com/app",
            "WP_PLUGIN_DIR" => $app_test_path . "/public/plugins",
            "WP_PLUGIN_URL" => "https://example.com/plugins",
            "WPMU_PLUGIN_DIR" => $app_test_path . "/public/mu-plugins",
            "WPMU_PLUGIN_URL" => "https://example.com/mu-plugins",

            // admin
            "AUTOMATIC_UPDATER_DISABLED" => true,
            "WP_SUDO_ADMIN" => 1,
            "SUDO_ADMIN_GROUP" => null,
            "CAN_DEACTIVATE_PLUGINS" => true,

            // sqlite.
            "DB_DIR" => $app_test_path . "/sqlitedb",
            "DB_FILE" => ".sqlite-wpdatabase",

            "WP_DEFAULT_THEME" => "brisko",

            // salts.
            "COOKIEHASH" => "c984d06aafbecf6bc55569f964148ea3",
            "USER_COOKIE" => "wpc_user_c984d06aafbecf6bc55569f964148ea3",
            "PASS_COOKIE" => "wpc_pass_c984d06aafbecf6bc55569f964148ea3",
            "AUTH_COOKIE" => "wpc_c984d06aafbecf6bc55569f964148ea3",
            "SECURE_AUTH_COOKIE" => "wpc_sec_c984d06aafbecf6bc55569f964148ea3",
            "LOGGED_IN_COOKIE" => "wpc_logged_in_c984d06aafbecf6bc55569f964148ea3",
            "TEST_COOKIE" => "613df23f4d18ac79c829ba8c18b503e4",

            // sucuri.
            "ENABLE_SUCURI_WAF" => false,
            // "SUCURI_DATA_STORAGE" => $app_test_path . "../../storage/logs/sucuri",

            // redis
            "WP_REDIS_DISABLED" => false,
            "WP_REDIS_PREFIX" => "c984d06aafbecf6bc55569f964148ea3redis-cache",
            "WP_REDIS_DATABASE" => 0,
            "WP_REDIS_HOST" => "127.0.0.1",
            "WP_REDIS_PORT" => 6379,
            "WP_REDIS_PASSWORD" => "",
            "WP_REDIS_DISABLE_ADMINBAR" => false,
            "WP_REDIS_DISABLE_METRICS" => false,
            "WP_REDIS_DISABLE_BANNERS" => false,
            "WP_REDIS_TIMEOUT" => 1,
            "WP_REDIS_READ_TIMEOUT" => 1,
        ];

        $this->assertIsArray( $this->http_app->get_defined() );

        $count = \count( $this->http_app->get_defined() );

        $this->assertEquals( 38, $count );

        $this->assertEquals( $const_defaults, $this->http_app->get_defined());
    }

    public function default_args(): array
    {
        return [
            "web_root" => "public",
            "wp_dir_path" => "wp",
            "wordpress" => "wp",
            "asset_dir" => "assets",
            "content_dir" => "content",
            "plugin_dir" => "plugins",
            "mu_plugin_dir" => "mu-plugins",
            "sqlite_dir" => "sqlitedb",
            "sqlite_file" => ".sqlite-wpdatabase",
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
