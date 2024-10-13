<?php

/*
 * This file is part of the WPframework package.
 *
 * (c) Uriel Wilson <uriel@wpframework.io>
 *
 * The full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

// Production Environment
// Optimized for security and performance
define('DISALLOW_FILE_EDIT', true);
define('WP_DEBUG_DISPLAY', false);
define('SCRIPT_DEBUG', false);
define('WP_CRON_LOCK_TIMEOUT', 60);
define('EMPTY_TRASH_DAYS', 15);
define('WP_DEBUG', false);
define('WP_DEBUG_LOG', false);
ini_set('display_errors', '0');

// Staging Environment
// Testing environment, similar to production but with more logging
define('DISALLOW_FILE_EDIT', false);
define('WP_DEBUG_DISPLAY', true);
define('SCRIPT_DEBUG', false);
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
ini_set('display_errors', '0');

// Development Environment
// Full debugging and logging enabled
define('WP_DEBUG', true);
define('SAVEQUERIES', true);
define('WP_DEBUG_DISPLAY', true);
define('WP_DISABLE_FATAL_ERROR_HANDLER', true);
define('SCRIPT_DEBUG', true);
define('WP_DEBUG_LOG', true);
ini_set('display_errors', '1');

// Debug Environment
// Extensive debugging capabilities
define('WP_DEBUG', true);
define('WP_DEBUG_DISPLAY', true);
define('CONCATENATE_SCRIPTS', false);
define('SAVEQUERIES', true);
define('WP_DEBUG_LOG', true);
ini_set('error_reporting', E_ALL);
ini_set('log_errors', '1');
ini_set('log_errors_max_len', '0');
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');

// Secure Environment
// Max security settings, restricts all editing and updates
define('DISALLOW_FILE_EDIT', true);
define('DISALLOW_FILE_MODS', true);
define('WP_DEBUG_DISPLAY', false);
define('SCRIPT_DEBUG', false);
define('WP_CRON_LOCK_TIMEOUT', 120);
define('EMPTY_TRASH_DAYS', 10);
define('WP_DEBUG', false);
define('WP_DEBUG_LOG', false);
ini_set('display_errors', '0');
