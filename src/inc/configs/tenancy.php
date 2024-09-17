<?php

/*
 * This file is part of the WPframework package.
 *
 * (c) Uriel Wilson <uriel@wpframework.io>
 *
 * The full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

\define('ALLOW_MULTITENANT', false);

/*
 * Specifies the UUID of the main site (also known as the landlord) in a multi-tenant setups.
 *
 * This constant should be assigned the UUID value of the primary tenant that acts as the landlord.
 * Setting this value is crucial for identifying the main tenant in a multi-tenant configuration.
 */
\define('LANDLORD_UUID', null);

/*
 * Determines the handling of tenant-specific configurations in a multi-tenant application.
 *
 * When set to `true`, the application enforces a strict requirement where each tenant must
 * have their own `configs/{tenant_id}/app.php` file. If a tenant-specific configuration file
 * is not found, the application will throw an exception, indicating the necessity for tenant
 * specific configurations.
 *
 * This ensures that each tenant has explicitly defined settings and
 * does not fall back to using a shared or default configuration, enhancing security and
 * customization for each tenant.
 */
\define('REQUIRE_TENANT_CONFIG', false);

/*
 * Defines the web root directory in multi-tenant mode.
 *
 * This constant is used to override the default web root directory to support
 * multi-tenancy in the application. Ensure that the specified directory exists
 * and is correctly configured to serve web assets.
 */
\define('TENANCY_WEB_ROOT', 'public');
