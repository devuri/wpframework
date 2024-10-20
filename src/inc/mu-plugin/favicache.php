<?php

/*
 * This file is part of the WPframework package.
 *
 * (c) Uriel Wilson <uriel@wpframework.io>
 *
 * The full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

if (!\defined('ABSPATH')) {
    exit;
}

if (defined('WP_INSTALLING') && WP_INSTALLING) {
    return;
}

class FaviconHandler
{
    public const DEFAULT_RESPONSE_TYPE = 204;
    public const DEFAULT_ENABLE_CACHE = false;
    public const DEFAULT_CACHE_TIME = 3600;

    public function __construct()
    {
        add_action('init', [$this, 'handle_favicon_request']);
    }

    public function handle_favicon_request()
    {
        if ($this->is_favicon_request()) {
            if (defined('FAVICON_ENABLE_CACHE') && FAVICON_ENABLE_CACHE === true) {
                $this->send_cache_headers();
            }
            $response_type = defined('FAVICON_RESPONSE_TYPE') ? FAVICON_RESPONSE_TYPE : self::DEFAULT_RESPONSE_TYPE;
            $this->send_response($response_type);
        }
    }

    /**
     * @return bool
     */
    private function is_favicon_request()
    {
        return strpos($_SERVER['REQUEST_URI'], 'favicon.ico') !== false;
    }

    private function send_cache_headers()
    {
        $cache_time = defined('FAVICON_CACHE_TIME') ? FAVICON_CACHE_TIME : self::DEFAULT_CACHE_TIME;
        header("Cache-Control: public, max-age=$cache_time");
        header('Expires: ' . gmdate('D, d M Y H:i:s', time() + $cache_time) . ' GMT');
    }

    /**
     * @param int $response_type
     */
    private function send_response($response_type)
    {
        if ($response_type == 204) {
            header("HTTP/1.1 204 No Content");
        } elseif ($response_type == 404) {
            header("HTTP/1.1 404 Not Found");
        }
        exit();
    }
}

new FaviconHandler();
