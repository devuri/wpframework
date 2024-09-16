<?php

namespace WPframework\Tests\Helpers;

use Tests\BaseTest;

/**
 * @internal
 *
 * @coversNothing
 */
class HelpersTest extends BaseTest
{
    public function test_asset_function(): void
    {
        \define( 'WP_HOME', 'https://example.com');

        $assetUrl = asset( "/images/thing.png" );

        $this->assertIsString($assetUrl);

        $default = "https://example.com/assets/dist/images/thing.png";

        $this->assertSame($default, $assetUrl);
    }

    public function test_asset_custom_path(): void
    {
        $assetUrl = asset( "/images/thing.png", "/static" );

        $this->assertIsString($assetUrl);

        $static_path = "https://example.com/static/images/thing.png";

        $this->assertSame($static_path, $assetUrl);
    }

    public function test_asset_url_return_url_only(): void
    {
        $assets = assetUrl();

        $assetUrl = $assets . "images/thing.png";

        $this->assertIsString($assets);

        $this->assertSame($assets, "https://example.com/assets/dist/");

        $this->assertIsString($assetUrl);

        $url = "https://example.com/assets/dist/images/thing.png";

        $this->assertSame($url, $assetUrl);
    }

    public function test_static_asset_url_return(): void
    {
        $static_url = assetUrl('/static');

        $this->assertIsString($static_url);

        $url = "https://example.com/static/";

        $this->assertSame($url, $static_url);
    }
}
