# Configuration Guide

This guide explains how to modify the configuration options of the framework used in this project. The configuration options are defined in the `configs/app.php` file.

## Using Environment Variables

Most configuration values can be set using environment variables defined in the `.env` file. This approach provides flexibility and allows easy customization for different environments.

## Accessing Configuration Options

Configuration options can be accessed in two ways:

1. **Using the `config()` Helper Function:** This function provides easy access to the configuration values throughout the application.

2. **Using the `get_config()` Method:** This method, available in the framework's Kernel, returns the configuration options as an array.

## Configuration Options Overview

The `configs/app.php` config file contains various configuration options organized into sections. Here's a brief overview of some key sections:

### Error Handler

- Defines the error handler for the project.
- Can be set to 'oops', 'symfony', or `false` to disable error handling.

### Directory Structure

- Defines the directory structure for the project, including web root, content directory, plugins directory, etc.

### Default Theme

- Sets the default fallback theme for the project.

### Security Settings

- Contains various security settings to enhance the security of the application.

### Email SMTP Configuration

- Configures the mailer settings for sending emails using different providers such as Brevo, Postmark, SendGrid, Mailgun, and SES.

### Redis Cache Configuration

- Contains configuration settings for the Redis cache integration in WordPress.

### Public Key

- Represents a public key used for encryption or verification purposes.

## Modifying Configuration Options

To modify the configuration options:

1. Open the `app.php` file located in the project's root `configs` directory.
2. Find the section containing the configuration option you want to modify.
3. Update the value according to your requirements.
4. Save the file.

## Using Environment Variables

If you prefer to use environment variables:

1. Open the `.env` file located in the project's root directory.
2. Set the desired environment variable(s) corresponding to the configuration option(s) you want to modify.
3. Save the file.

## Example

To illustrate, let's say you want to modify the default theme:

```php
// app.php
return [
    'default_theme' => env( 'DEFAULT_THEME', 'fallback-theme' ),
    // Other configuration options...
];
```

and then in your `.env`:

```dotenv
# .env
DEFAULT_THEME=my-custom-theme
```

## Notes

- Always ensure to follow best practices and security guidelines when modifying configuration options.
- Make sure to test the changes, especially in different environments.

## Full Configuration Example

check out full Configuration example: [Config Reference](../reference/configuration)
