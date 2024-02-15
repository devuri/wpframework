# WP-Framework

<p align="center">
  <img src="https://user-images.githubusercontent.com/4777400/225331174-d5ae1c0e-5ec0-493b-aabc-91c4cc6a14c4.png" alt="WP-Framework Logo"/>
</p>

<div align="center">

[![Unit Tests](https://github.com/devuri/wp-framework/actions/workflows/unit-tests.yml/badge.svg)](https://github.com/devuri/wp-framework/actions/workflows/unit-tests.yml)

</div>

Welcome to WP-Framework, the Composer package that serves as the cutting-edge successor to `wp-env-config`. Designed to provide secure and modular WordPress development, WP-Framework equips developers with a solid, flexible foundation for crafting scalable single or multi-tenant web applications. It's packed with features aimed at boosting your productivity, enhancing the maintainability of your projects, and ensuring scalability.

## Features

- **Multi-Tenant Architecture**: Effortlessly develop scalable WordPress applications that support multiple tenants from a single installation, providing customized experiences for each.
- **Modular Design**: Benefit from a modular approach to extend your project's functionality without inflating your codebase, ensuring efficiency and manageability.
- **Enhanced Security**: Take advantage of integrated security features to shield your applications from prevalent vulnerabilities, offering a secure user environment.
- **Developer Tools**: Access an extensive array of tools tailored to facilitate debugging, testing, and deployment, streamlining your development workflow.

## Getting Started

Incorporating WP-Framework into your WordPress project is seamless with Composer. First, make sure your development environment is Composer-ready, then proceed as follows:

### Environment Setup

Begin by creating a `.env` file in your project's root directory. Define necessary environment variables for configuration constants within this file, including database credentials and other essential settings:

```shell
WP_HOME='https://example.com'
WP_SITEURL="${WP_HOME}/wp"

WP_ENVIRONMENT_TYPE='production'
DEVELOPER_ADMIN='0'

MEMORY_LIMIT='256M'
MAX_MEMORY_LIMIT='256M'

DB_NAME=wp_dbName
DB_USER=root
DB_PASSWORD=
DB_HOST=localhost
DB_PREFIX=wp_
```

### Installation & Activation

1. **Install WP-Framework**:
   Execute the following command within your project's root directory to integrate WP-Framework via Composer:

   ```bash
   composer require devuri/wp-framework
   ```

   With WP-Framework installed, you're all set to leverage its capabilities within your WordPress project. Your project structure might resemble the following:

   ```plaintext
   ├── .env
   ├── wp-config.php
   ├── composer.json
   ├── composer.lock
   ├── LICENSE
   ├── public/wp/
   │   ├── index.php
   │   ├── wp-admin/
   │   ├── wp-content/
   │   ├── wp-includes/
   │   ├── .htaccess
   │   ├── robots.txt
   │   └── ...
   └── vendor/
   ```

2. **Activate WP-Framework in `wp-config.php`**:
   Integrate WP-Framework into your WordPress configuration by including the Composer autoload file. The settings from the `.env` file will be loaded automatically:

   ```php
   // Include Composer's autoload file to load WP-Framework and its dependencies.
   require_once __DIR__ . '/vendor/autoload.php';
   
   // Initialize WP-Framework with the base directory as a parameter.
   $http_app = wpframework(__DIR__);
   
   // Apply any necessary overrides provided by WP-Framework.
   $http_app->overrides();
   
   // Initialize WP-Framework to hook into WordPress with its configurations and tools.
   $http_app->init();
   
   // Set the table prefix using an environment variable, or default to 'wp_' if not set.
   $table_prefix = env('DB_PREFIX', 'wp_');
   ```

   This setup ensures WP-Framework is fully integrated and operational within your WordPress environment, enhancing its capabilities and security.


## Documentation

Explore the extensive [Documentation](#) for WP-Framework to learn about its installation, configuration, and the plethora of features it offers. The documentation includes detailed guides, API references, and best practices to help you maximize your use of WP-Framework.

## Contributing

Your contributions are highly valued! Whether it's through feature suggestions, bug reports, or direct code contributions, your input significantly enhances WP-Framework's functionality and reach. Please review our [Contributing Guidelines](#) for details on how to get involved.

## Support

Should you encounter any issues or have questions about the framework, don't hesitate to open an issue on our GitHub repository.

## License

WP-Framework is released under the [MIT License](LICENSE), promoting open and collaborative development.