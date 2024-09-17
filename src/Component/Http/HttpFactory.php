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

class HttpFactory
{
    /**
     * Creates and returns an instance of HostManager.
     *
     * @return HostManager An instance of the HostManager class.
     */
    public static function init(): HostManager
    {
        return new HostManager();
    }
}
