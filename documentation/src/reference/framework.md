# WPFramework Usage Guide

## Introduction
Raydium core is the WPFramework, a robust, secure, and modular framework designed for WordPress development. It serves as the core engine behind Raydium, providing a solid foundation for both single and multi-tenant web applications. This guide walks you through using WPFramework as a standalone package, enhancing scalability, security, and development efficiency.

## Features
- **Multi-Tenant Support**: Facilitates the development of scalable applications with multi-tenant capabilities, allowing for customized experiences under a single WordPress installation.
- **Modularity**: Offers a structured, component-based approach to development, making your projects more manageable and extendable.
- **Security**: Integrates enhanced security measures to protect applications from common vulnerabilities.
- **Developer Tools**: Equips developers with essential tools for debugging, testing, and deployment, streamlining the development process.

## Prerequisites
- PHP 7.4
- Composer for dependency management
- WordPress 5.2 or higher

## Installation
1. **Create a `.env` File**: In your project's root, create a `.env` [environment file](../customization/environment-file) to define essential configuration constants, including database settings and other environment-specific variables.

    ```dotenv
    WP_HOME='https://example.com'
    WP_SITEURL="${WP_HOME}/wp"
    WP_ENVIRONMENT_TYPE='production'
    DB_NAME='wp_database'
    DB_USER='your_db_user'
    DB_PASSWORD='your_db_password'
    DB_HOST='localhost'
    ```

2. **Install WPFramework**:
    Use Composer to install WPFramework in your project directory.

    ```bash
    composer require devuri/wpframework
    ```

## Configuration and Usage
After installation, integrate WPFramework into your WordPress setup:

1. **Bootstrap WPFramework**: Create a setparate `bootstrap.php` or include these the Initialization in your `wp-config.php` file, this will ensure framework is loaded and initialized correctly.

    ```php
    // Include Composer autoload.
    require_once __DIR__ . '/vendor/autoload.php';

    // Initialize WPFramework.
    $http_app = wpframework(__DIR__);

    // Override default settings if necessary.
    $http_app->overrides();

    // Initiate WPFramework.
    $http_app->init();

    // Set WordPress database table prefix.
    $table_prefix = env('DB_PREFIX', 'wp_');
    ```

2. **Activate Multi-Tenancy (Optional)**: If you're utilizing WPFramework's multi-tenancy features, ensure your `.env` file and other configurations align with multi-tenant requirements. Refer to the [Multi-Tenancy Guide](../multi-tenant/overview) for detailed instructions.

3. **Development and Deployment**: Develop your WordPress site as usual. the framework works behind the scenes, providing a secure, scalable, and modular environment.

## Documentation
For a comprehensive understanding of the framework's core features, configuration options, and best practices, refer to the [Raydium Documentation](../guide/getting-started). The documentation provides in-depth guides, API references, and examples to assist in leveraging the framework effectively.

## Contributing
Contributions are welcome! Whether it's through feature suggestions, bug reporting, or direct code contributions, your involvement helps improve the framework. Check out the [Contributing Guidelines](#) for more information on how to contribute.

## Support
Encountering issues or have questions? Open an issue on our [GitHub repository](#), and we'll be happy to assist.

## License
WPFramework is open-sourced software licensed under the [MIT License](https://github.com/devuri/wpframework/blob/master/LICENSE).
