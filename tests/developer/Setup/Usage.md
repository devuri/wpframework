# WPframework Setup Class

The `Setup` class is designed to simplify the initialization and configuration of WordPress applications. It leverages environment variables to manage configurations, supports multiple environments, and provides easy setup for error handling, database connections, and performance optimizations.

## Table of Contents

- [Features](#features)
- [Requirements](#requirements)
- [Installation](#installation)
- [Quick Start Guide](#quick-start-guide)
- [Configuration](#configuration)
  - [Environment Variables](#environment-variables)
  - [Environment Files](#environment-files)
  - [Error Handling](#error-handling)
- [Usage](#usage)
  - [Initializing the Setup Class](#initializing-the-setup-class)
  - [Configuring the Environment](#configuring-the-environment)
  - [Setting Up Different Components](#setting-up-different-components)
- [Advanced Usage](#advanced-usage)
  - [Custom Environment Switcher](#custom-environment-switcher)
  - [Short Circuit Loading](#short-circuit-loading)
- [Troubleshooting](#troubleshooting)
- [Contributing](#contributing)
- [License](#license)
- [Contact](#contact)

## Features

- **Environment Management**: Load and manage configurations using environment variables from multiple `.env` files.
- **Multiple Environment Support**: Easily switch between development, staging, production, and custom environments.
- **Error Handling**: Integrate with Symfony's Debug component or Whoops for enhanced error reporting during development.
- **Database Configuration**: Simplify database setup using environment variables.
- **URL Configuration**: Define `WP_HOME`, `WP_SITEURL`, and asset URLs effortlessly.
- **Performance Optimization**: Configure memory limits, script concatenation, and other settings for optimal performance.
- **SSL Enforcement**: Force SSL on admin and login pages for enhanced security.
- **Autosave and Revisions**: Customize autosave intervals and control post revisions.
- **Singleton Pattern**: Utilize a singleton instance to prevent multiple initializations.

## Requirements

- **PHP**: 7.4 or higher
- **Composer**: For dependency management
- **WordPress**: Compatible with the latest version
- **Dependencies**:
  - `vlucas/phpdotenv`
  - `symfony/error-handler` (optional)
  - `filp/whoops` (optional)

## Installation

1. **Install via Composer**:

   ```bash
   composer require wpframework/setup
   ```

2. **Include the Autoloader**:

   Ensure your project includes the Composer autoloader at the beginning of your configuration file (e.g., `wp-config.php`):

   ```php
   require_once __DIR__ . '/vendor/autoload.php';
   ```

3. **Set Up Environment Files**:

   Create a `.env` file in the root directory of your application and define your environment variables.

## Quick Start Guide

Here's a quick example to get you started:

```php
use WPframework\Setup;

// Initialize the Setup instance
$setup = Setup::init(__DIR__);

// Configure the environment
$setup->config([
    'environment' => 'development',
    'error_log'   => __DIR__ . '/logs/error.log',
    'errors'      => 'whoops',
]);

// Finalize the setup
$setup->setEnvironment()
      ->debug(__DIR__ . '/logs/error.log')
      ->setErrorHandler()
      ->database()
      ->siteUrl()
      ->assetUrl()
      ->memory()
      ->optimize()
      ->forceSsl()
      ->autosave()
      ->salts();
```

## Configuration

### Environment Variables

The `Setup` class relies on environment variables defined in your `.env` files. Below are the essential variables you need to set:

- **Database Configuration**:

  ```env
  DB_NAME=your_database_name
  DB_USER=your_database_user
  DB_PASSWORD=your_database_password
  DB_HOST=localhost
  DB_CHARSET=utf8mb4
  DB_COLLATE=
  ```

- **Authentication Keys and Salts**:

  Generate these keys using the [WordPress.org secret-key service](https://api.wordpress.org/secret-key/1.1/salt/):

  ```env
  AUTH_KEY=your_auth_key
  SECURE_AUTH_KEY=your_secure_auth_key
  LOGGED_IN_KEY=your_logged_in_key
  NONCE_KEY=your_nonce_key
  AUTH_SALT=your_auth_salt
  SECURE_AUTH_SALT=your_secure_auth_salt
  LOGGED_IN_SALT=your_logged_in_salt
  NONCE_SALT=your_nonce_salt
  ```

- **Site URLs**:

  ```env
  WP_HOME=https://your-site.com
  WP_SITEURL=https://your-site.com/wp
  ASSET_URL=https://your-site.com/assets
  ```

- **Performance Settings**:

  ```env
  MEMORY_LIMIT=256M
  MAX_MEMORY_LIMIT=256M
  CONCATENATE_SCRIPTS=true
  ```

- **SSL Settings**:

  ```env
  FORCE_SSL_ADMIN=true
  FORCE_SSL_LOGIN=true
  ```

- **Autosave and Revisions**:

  ```env
  AUTOSAVE_INTERVAL=180
  WP_POST_REVISIONS=10
  ```

- **Environment Type**:

  ```env
  WP_ENVIRONMENT_TYPE=development
  ```

### Environment Files

By default, the `Setup` class searches for the following `.env` files in the application root:

- `.env`
- `.env.local`
- `.env.dev`
- `.env.staging`
- `.env.prod`
- `.env.secure`
- `.env.debug`
- `env`
- `env.local`

You can specify additional environment files by passing an array of filenames to the constructor:

```php
$setup = new Setup(__DIR__, ['.env.custom']);
```

### Error Handling

Enable detailed error handling during development using Symfony's Debug component or Whoops:

- **Symfony Debug**:

  Set the `errors` parameter to `'symfony'` in the `config` method.

- **Whoops**:

  Set the `errors` parameter to `'oops'` in the `config` method.

Example:

```php
$setup->config([
    'environment' => 'development',
    'errors'      => 'symfony',
]);
```

## Usage

### Initializing the Setup Class

To start using the `Setup` class, you need to initialize it with the path to your application:

```php
use WPframework\Setup;

$setup = Setup::init(__DIR__);
```

### Configuring the Environment

Configure your environment by passing an array to the `config` method:

```php
$setup->config([
    'environment' => 'production', // 'production', 'staging', 'development', etc.
    'error_log'   => __DIR__ . '/logs/error.log',
    'errors'      => 'symfony',    // 'symfony', 'oops', or false
]);
```

- **environment**: The type of environment you're setting up.
- **error_log**: The path to your error log file.
- **errors**: The error handling library you wish to use.

### Setting Up Different Components

After configuring the environment, you can set up various components:

#### Set Environment

```php
$setup->setEnvironment();
```

This method defines the `WP_ENVIRONMENT_TYPE` constant based on your configuration.

#### Debugging

```php
$setup->debug(__DIR__ . '/logs/error.log');
```

Enables debugging and sets the error log directory.

#### Error Handler

```php
$setup->setErrorHandler();
```

Sets up the error handler based on your configuration.

#### Database

```php
$setup->database();
```

Defines database constants using your environment variables.

#### Site URLs

```php
$setup->siteUrl();
```

Sets the `WP_HOME` and `WP_SITEURL` constants.

#### Asset URL

```php
$setup->assetUrl();
```

Defines the `ASSET_URL` constant for loading assets.

#### Memory Limits

```php
$setup->memory();
```

Sets memory limits for your application.

#### Performance Optimization

```php
$setup->optimize();
```

Enables script concatenation for performance optimization.

#### SSL Enforcement

```php
$setup->forceSsl();
```

Forces SSL on admin and login pages.

#### Autosave and Revisions

```php
$setup->autosave();
```

Configures autosave intervals and post revision limits.

#### Authentication Salts

```php
$setup->salts();
```

Defines the necessary WordPress authentication salts.

### Putting It All Together

Here's a full example:

```php
use WPframework\Setup;

$setup = Setup::init(__DIR__);

$setup->config([
    'environment' => 'production',
    'error_log'   => __DIR__ . '/logs/error.log',
    'errors'      => false,
]);

$setup->setEnvironment()
      ->debug(__DIR__ . '/logs/error.log')
      ->setErrorHandler()
      ->database()
      ->siteUrl()
      ->assetUrl()
      ->memory()
      ->optimize()
      ->forceSsl()
      ->autosave()
      ->salts();
```

## Advanced Usage

### Custom Environment Switcher

If you have a custom environment switcher that implements the `EnvSwitcherInterface`, you can set it using:

```php
$setup->setSwitcher(new YourCustomSwitcher());
```

### Short Circuit Loading

By default, the `Setup` class uses "short circuit" mode when loading environment variables, which skips loading if the variables are already set. You can disable this by passing `false` as the third parameter:

```php
$setup = new Setup(__DIR__, [], false);
```

## Troubleshooting

- **Missing Environment Variables**: Ensure all required environment variables are set in your `.env` files.
- **Error Handling Not Working**: Verify that you've set the `errors` parameter correctly and that the necessary dependencies are installed.
- **Constants Not Defined**: Make sure you're calling all the necessary methods after configuration.
