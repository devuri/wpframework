# Customizing Your Application with `app.php`

## Overview

The `app.php` file in the Raydium Framework is a crucial component for setting and customizing various configuration options for your WordPress application. It allows you to define key-value pairs for different settings, influencing how your application behaves in various environments.

## Configuration Options

### Error Handling

- **Error Handler**: Choose between Oops or Symfony for error handling, or set to `null` to use the default Symfony handler. Customize this according to your project's needs for more efficient debugging and error tracking.

```php
'error_handler' => 'oops', // Options: 'oops', 'symfony', or null
```

### Application Termination

- **Debugger**: Control whether to display detailed error information on application termination. Useful for development but should be disabled in production for security reasons.

```php
'terminate' => [
    'debugger' => true, // Set to false in production
],
```

### Directory Structure

- **Web Root & Content Directories**: Define custom paths for your web root, content, plugins, themes, and assets. This flexibility allows you to structure your project in a way that best suits your workflow and organizational preferences.

```php
'directory' => [
    'web_root'      => 'public_html',
    'content_dir'   => 'content',
    'plugin_dir'    => 'content/plugins',
    'mu_plugin_dir' => 'content/mu-plugins',
    'theme_dir'     => 'content/themes',
    'asset_dir'     => 'assets',
],
```

### Default Theme

- **Fallback Theme**: Specify a default fallback theme for your project. This setting ensures that there's always a reliable baseline theme available.

```php
'default_theme' => 'my_default_theme',
```

### Updates and Plugin Management

- **Disable Updates & Plugin Deactivation**: Manage WordPress core and plugin updates, and control the ability to deactivate plugins directly from the admin panel.

```php
'disable_updates' => true,
'can_deactivate'  => false,
```

### Security Settings

- **Security Configurations**: Enhance the security of your application by configuring encryption keys, enabling brute-force protection, two-factor authentication, and more.

```php
'security' => [
    'encryption_key'     => '/path/to/encryption/key',
    'brute-force'        => true,
    'two-factor'         => true,
    // Additional security settings...
],
```

### Mailer Configuration

- **SMTP Settings**: Configure SMTP settings for different mail services like Brevo, Postmark, or SendGrid to improve email deliverability and management.

```php
'mailer' => [
    'sendgrid' => [
        'apikey' => 'your_sendgrid_api_key',
    ],
    // Additional mailer configurations...
],
```

### Redis Cache

- **Redis Configuration**: Set up Redis caching for your application to enhance performance and reduce database load.

```php
'redis' => [
    'host'     => '127.0.0.1',
    'port'     => 6379,
    'password' => 'your_redis_password',
    // Additional Redis settings...
],
```

## Leveraging `env()` for Dynamic Configurations

In the Raydium Framework, the `env()` function is a powerful tool that bridges the gap between static configuration files and the dynamic nature of different environments (development, staging, production, etc.). This functionality allows you to pull configuration values directly from the environment variables defined in your `.env` [environment file](../guide/environment-file), providing flexibility and security for your application settings.

## Understanding `env()`

### Functionality

The `env()` function fetches the value of an environment variable from the `.env` file. If the environment variable is not set, `env()` can return a default value that you specify.

### Syntax

```php
$value = env('VARIABLE_NAME', 'default_value');
```

- `VARIABLE_NAME`: The name of the environment variable you want to retrieve.
- `default_value`: (Optional) A default value to return if the environment variable is not set.

## Advantages of Using `env()`

### Security

Sensitive information like database credentials, API keys, and secret tokens can be stored in the `.env` file outside of the version-controlled codebase. This practice keeps critical data secure and prevents accidental exposure.

### Environment-Specific Configuration

`env()` enables you to adapt your application's behavior based on the environment without changing the code. For example, you might enable detailed error reporting in a development environment while keeping it disabled in production.

### Centralized Management

Environment variables provide a centralized location for managing application settings, making it easier to update configurations without diving into the codebase.

## Examples in `app.php`

### Database Configuration

Instead of hardcoding your database credentials, use `env()` to pull them from your `.env` file:

```php
'db' => [
    'username' => env('DB_USER', 'default_user'),
    'password' => env('DB_PASSWORD', 'default_password'),
    // Other database configurations...
],
```

### Debug Mode

Control the debug mode based on the environment, ensuring that sensitive debug information is only displayed in a safe context:

```php
'debug' => env('APP_DEBUG', false),
```

### Mailer Service API Key

Configure your mailer service dynamically, allowing for different keys in different environments:

```php
'mailer' => [
    'sendgrid' => [
        'apikey' => env('SENDGRID_API_KEY', ''),
    ],
    // Additional mailer configurations...
],
```

> The `env()` function in the Raydium Framework offers a flexible, secure, and efficient application way to set configurations. By harnessing the power of environment variables, you can achieve a more dynamic, secure, and manageable configuration setup for your WordPress projects.

> [!WARNING]
> While the `env()` function offers a convenient access, variables must be whitelisted or you need to turn off strict env loader by setting the `USE_STRICT_ENV_VARS` to `false`.

## Best Practices

- **Documentation**: Document each configuration option within `app.php` for clarity and future reference.
- **Environment Variables**: Use environment variables in `.env` for sensitive information and to easily switch configurations between different environments.
- **Testing**: Thoroughly test any changes made in `app.php` in a development environment before deploying to production.
- **Version Control**: Keep `app.php` under version control to track changes and maintain a history of configurations.
- **Keep `.env` Secure**: Ensure your `.env` file is properly secured and excluded from version control to prevent unauthorized access to sensitive information.
- **Use Descriptive Variable Names**: Choose clear and descriptive names for your environment variables to avoid confusion and potential conflicts.
- **Default Values**: Provide sensible default values where applicable to ensure your application functions correctly even when specific environment variables are not set.

> The `app.php` file offers a comprehensive and flexible way to configure your WordPress application within the Raydium Framework. By understanding and utilizing the various configuration options available, you can tailor your application to meet specific requirements, ensuring optimal performance, security, and user experience.
