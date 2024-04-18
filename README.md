# WPFramework for Raydium


<div align="center">

[![Unit Tests](https://github.com/devuri/wpframework/actions/workflows/unit-tests.yml/badge.svg)](https://github.com/devuri/wpframework/actions/workflows/unit-tests.yml) [![Static Analysis](https://github.com/devuri/wpframework/actions/workflows/static-analysis.yml/badge.svg)](https://github.com/devuri/wpframework/actions/workflows/static-analysis.yml)

</div>

> This repository contains the foundational core framework for Raydium. If you're developing an application using the wpframework, use the pre-built version available at [Raydium](https://github.com/devuri/raydium/).

Welcome to the WPFramework, the Composer package that serves as the successor to `wp-env-config` its the core framework for Raydium. Designed to provide secure and modular WordPress development, The framework equips developers with a solid, flexible foundation for crafting scalable single or multi-tenant web applications.

## Prerequisites

Before you begin the installation process, ensure you have the following prerequisites:

1. **PHP**: Raydium requires PHP version 7.4 or higher. Verify your PHP version using the command: `php -v`.

2. **Composer**: Raydium uses Composer for dependency management. Install Composer from [getcomposer.org](https://getcomposer.org/download/) if you haven't already.

3. **MySQL or MariaDB Database**: Ensure you have access to a MySQL or MariaDB database. You'll need the database credentials during the WordPress setup.

4. **Web Server**: Any standard web server like Apache or Nginx capable of serving PHP applications. Make sure it's configured to serve the `public` directory of your Raydium project as the web root.

5. **Command Line Access**: You'll need terminal or command line access to execute Composer commands.

## Installation

### Create a New Raydium Project

Start by creating a new Raydium project using Composer. Open your terminal or command line tool and run the following command:

```bash
composer create-project devuri/raydium your-project-name
```

## Documentation

Explore the extensive [Raydium Documentation](https://devuri.github.io/wpframework/) to learn about its installation, configuration, and the features it offers. The documentation includes detailed guides, API references, and best practices to help you maximize your use of Raydium.


## Support

Should you encounter any issues or have questions about the framework, don't hesitate to open an issue on our GitHub repository.

## License

Licensed under the MIT License. See the [LICENSE](LICENSE) file for details.
