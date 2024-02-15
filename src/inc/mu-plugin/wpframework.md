# WP Framework Plugin

## Overview

The WP Framework Plugin, is a comprehensive tool designed to enhance and secure your WordPress installation. It introduces a series of features aimed at optimizing the multi-tenancy capabilities, security enhancements, and customizations for your WordPress sites. This document outlines all the features provided by the plugin.

## Features

### Multi-Tenancy Support

- **Tenant-Specific Upload Directories**: Automatically configures WordPress to use separate upload directories for each tenant, ensuring data isolation and organization.
- **Multi-Tenant Plugin Management**: Restricts the deletion of plugins in a multi-tenant environment, promoting stability across different sites.

### Security Enhancements

- **Security Headers**: Adds a series of HTTP security headers to your site, such as Strict-Transport-Security, X-Frame-Options, X-Content-Type-Options, and others, to enhance your site's security posture.
- **Content Security Policy**: Implements a comprehensive Content Security Policy (CSP) to mitigate the risk of XSS attacks and ensure that only trusted scripts and resources are executed or loaded on your site.
- **Password Change Notification**: Disables user notification for password change confirmations, reducing the risk of phishing attacks.

### Admin Customizations

- **Admin Bar Environment Menu**: Adds a custom menu to the WordPress admin bar to display the current environment type, aiding in environment awareness for administrators.
- **Custom Admin Footer**: Customizes the admin footer with a dynamic label that includes the site name, current year, and a custom message.

### Development and Maintenance Tools

- **Composer Plugin List**: Integrates a special admin page that displays a list of Composer-managed plugins, aiding in plugin management and oversight.
- **Scheduled Events**: Facilitates the scheduling of custom WP Framework events, allowing for periodic tasks and maintenance operations to be automated within your WordPress installation.

### Additional Features

- **Deactivation Link Removal**: Hides the plugin deactivation link for Admin users, preventing accidental deactivation of essential plugins.
- **Custom Theme Directory Registration**: Allows for the registration of custom theme directories, supporting advanced theme management and organization.
- **Disable Application Passwords**: Offers the option to disable WordPress application passwords, enhancing security by reducing potential attack vectors.

### Developer-Friendly Tools

- **WP Sudo Admin**: Supports a super admin mode for enhanced control and troubleshooting capabilities.
- **Environment Configuration**: Utilizes environment variables for configuration, enabling flexible and secure management of settings such as tenant IDs and environment types.
