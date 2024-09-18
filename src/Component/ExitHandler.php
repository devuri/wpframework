<?php

/*
 * This file is part of the WPframework package.
 *
 * (c) Uriel Wilson <uriel@wpframework.io>
 *
 * The full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WPframework;

class ExitHandler implements ExitInterface
{
    /**
     * @return never
     */
    public function terminate($status = 0): void
    {
        exit($status);
    }
}
