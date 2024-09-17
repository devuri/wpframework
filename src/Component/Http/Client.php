<?php

/*
 * This file is part of the WPframework package.
 *
 * (c) Uriel Wilson <uriel@wpframework.io>
 *
 * The full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WPframework\Http;

use Urisoft\HttpClient;

class Client extends HttpClient
{
    public function __construct(string $base_url, array $context = [ 'timeout' => 20 ])
    {
        parent::__construct($base_url, $context);
    }
}
