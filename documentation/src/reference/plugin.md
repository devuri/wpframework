# Raydium Plugin Component Documentation

The Raydium Plugin Component serves as a crucial part of the Raydium framework, focusing on enhancing WordPress's functionality with added flexibility and control. It encompasses a wide range of features from security enhancements to multi-tenancy support. This document delves into the various aspects of the Plugin component and how it integrates with WordPress.

## Overview

The Plugin component integrates seamlessly with the WordPress core, providing a layer of enhancements that are crucial for a modern, secure, and efficient WordPress setup. It's designed to work harmoniously with the Raydium framework, ensuring that all enhancements are aligned with WordPress standards while offering advanced features like multi-tenancy and improved security headers.

> [!IMPORTANT]
> The Plugin component is automatically included in WordPress as a Must-Use plugin, which guarantees its immediate activation and continuous operation, ensuring that the core enhancements it provides are always in effect.

## Key Features

### Security Headers

The Plugin component sets various HTTP security headers to enhance the security posture of the WordPress site. These headers include:

- Strict-Transport-Security
- X-Frame-Options
- X-Content-Type-Options
- X-XSS-Protection
- Referrer-Policy
- Content-Security-Policy

These headers help mitigate common web security vulnerabilities such as cross-site scripting (XSS), clickjacking, and other injection attacks.

> [!TIP]
> Optional you can activate security headers if the `SET_SECURITY_HEADERS` constant is set and true.

### Multi-Tenancy Support

For environments where multi-tenant capabilities are required, the Plugin component offers robust support. It allows for separate WordPress instances within a single WordPress installation, facilitating a multi-tenant architecture. This is particularly useful for SaaS platforms or any setup requiring isolated WordPress instances under a single framework.

### Admin UI Enhancements

The Plugin component introduces several enhancements to the WordPress admin interface:

- **Admin Bar Menu**: Adds an environment indicator to the admin bar, allowing quick insights into the current environment (development, staging, production).
- **Custom Theme Directory**: Registers a custom theme directory, enabling the use of themes located outside the standard WordPress `themes` directory.
- **Admin Settings Page**: Provides an interface for managing Composer plugins directly from the WordPress admin area.

### Tenant-Specific Uploads

In a multi-tenant setup, the Plugin component ensures that each tenant's uploads are stored in isolated directories. This separation maintains data integrity and privacy across different tenants.

### Enhanced Plugin Management

The Plugin component modifies the plugin management experience in WordPress, especially in multi-tenant environments. It can restrict plugin deletion and deactivation capabilities based on tenant permissions, ensuring stability and security across the board.

## Integration with WordPress

The Plugin component is initialized within the WordPress ecosystem, hooking into various WordPress actions and filters to provide its features. It utilizes WordPress's plugin architecture, ensuring full compatibility and seamless integration with the core WordPress functionalities.

## Customization and Extension

Developers can extend the Plugin component's capabilities by hooking into its actions and filters. This allows for custom security headers, environment-specific enhancements, and more refined control over the multi-tenant functionalities.

> The Raydium Plugin Component is an essential part of the Raydium framework, offering advanced features that enhance WordPress's security, flexibility, and multi-tenancy capabilities. Its seamless integration with WordPress ensures that developers can leverage these features without compromising on WordPress's core principles and ease of use.














# Raydium Plugin Component Documentation

The Raydium Plugin Component is an integral element of the Raydium framework, designed to extend WordPress's capabilities with additional security, efficiency, and flexibility. Loaded as a Must-Use (MU) plugin, it ensures automatic activation and persistent integration with WordPress, providing a robust layer of enhancements that are essential for modern, secure, and efficient WordPress installations.

## Overview

The Plugin component is automatically included in WordPress as a Must-Use plugin, which guarantees its immediate activation and continuous operation, ensuring that the core enhancements it provides are always in effect. This approach is particularly beneficial for critical features that need to be present across the entire site, such as security headers and multi-tenancy support.

## Key Features

### Enhanced Security with HTTP Headers

The Plugin component enforces several HTTP security headers to bolster the WordPress site's defense against common web vulnerabilities, including:

- **Strict-Transport-Security**: Enforces HTTPS across the site.
- **X-Frame-Options**: Protects against clickjacking attacks.
- **X-Content-Type-Options**: Prevents MIME type sniffing.
- **X-XSS-Protection**: Offers basic protection against cross-site scripting (XSS) attacks.
- **Referrer-Policy**: Controls information sent as a referrer.
- **Content-Security-Policy**: Helps prevent a wide range of attacks, including XSS and data injection.

### Multi-Tenancy Support

The Plugin component facilitates a multi-tenant architecture within WordPress, allowing for the management of multiple sites or instances within a single installation. This feature is crucial for platforms requiring isolated environments for different users or groups under one framework.

### Admin UI Improvements

- **Environment Indicator**: Adds a visual indicator in the admin bar, displaying the current environment (e.g., Development, Staging, Production), enhancing awareness and reducing configuration errors.
- **Custom Theme Directory**: Allows themes to be stored and used from a custom directory, providing more flexibility in theme management.
- **Composer Plugin Management**: Introduces a dedicated admin page for managing Composer-based plugins, streamlining plugin updates and maintenance.

### Tenant-Specific Upload Directories

In multi-tenant setups, the Plugin component ensures that each tenant's uploads are stored in dedicated directories, maintaining data isolation and integrity across tenants.

### Refined Plugin Management

The Plugin component enhances the control over plugin management, particularly in multi-tenant setups, by:

- Restricting the ability to delete or deactivate plugins, ensuring site stability and security.
- Allowing finer control over plugin capabilities based on tenant permissions.

## Seamless WordPress Integration

As a Must-Use plugin, the Plugin component is seamlessly integrated into WordPress, engaging with the core through actions and filters. This deep integration ensures that the provided enhancements work harmoniously with WordPress functionalities.

## Customization and Extensibility

Developers can further customize and extend the Plugin component by utilizing its hooks, enabling tailored security configurations, environment-specific adjustments, and extended multi-tenant features.

## Conclusion

The Raydium Plugin Component, loaded as a Must-Use plugin within WordPress, is a cornerstone of the Raydium framework, offering essential enhancements for security, multi-tenancy, and admin UI. Its automatic activation and persistent operation ensure that WordPress installations remain secure, efficient, and adaptable to various requirements.
