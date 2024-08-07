# Kernel Class Documentation

## Overview

The `Kernel` class is a central component of the WPframework, designed to streamline the initialization and configuration of WordPress applications. It encapsulates the core functionalities needed for setting up the application environment, handling configurations, and managing global constants. This class is engineered to support complex applications, offering flexibility for multi-tenant setups and environment-specific configurations.

## Features

- **Application Setup**: Initializes the application with essential configurations and setup parameters.
- **Environment Handling**: Supports loading of environment-specific configurations, enabling tailored behavior across different deployment scenarios such as development, staging, and production.
- **Multi-Tenancy**: Facilitates multi-tenant application architectures, allowing tenant-specific configurations to override global defaults.
- **Configuration Management**: Provides mechanisms to load, override, and manage application configurations effectively.
- **Security Enhancements**: Incorporates security practices by managing environment secrets and ensuring sensitive information is handled securely.
- **Debug Support**: Offers tools for debugging and environment inspection, especially useful during the development phase.

## Getting Started

To utilize the `Kernel` class in your application, follow these steps:

1. **Initialization**: Instantiate the `Kernel` class by providing the base application path and optional configurations:

    ```php
    $appKernel = new WPframework\Component\Kernel(__DIR__, ['content_dir' => 'content']);
    ```

2. **Configuration**: Pass an array of configurations (`$args`) during instantiation to customize the application setup. This array can include paths, environment flags, and other setup-related parameters.

3. **Setup Object**: Optionally, you can pass a `Setup` object as the third parameter to the constructor to use a custom setup configuration instead of the default.

4. **Application Launch**: Call the `init` method to kickstart the application environment setup. You can specify the environment type and whether to load default constants:

    ```php
    $appKernel->init('development', true);
    ```

5. **Accessing Application Configurations**: Use methods like `get_app_config()` to retrieve application configurations and `get_app_security()` for security settings.

## Usage Examples

### Basic Initialization

```php
$appKernel = new WPframework\Component\Kernel(__DIR__);
$appKernel->init();
```

This basic example demonstrates how to initialize the application with default settings.

### Custom Configuration and Multi-Tenancy

```php
$args = [
        'web_root_dir'        => 'public',
        'wp_dir_path'     => 'wp',
        'asset_dir'       => 'assets',
        'content_dir'     => 'content',
        'plugin_dir'      => 'plugins',
        'mu_plugin_dir'   => 'mu-plugins',
        'disable_updates' => true,
    ];
$appKernel = new WPframework\Component\Kernel(__DIR__, $args);
$appKernel->init(null, true);
```

This example shows how to pass custom configuration arguments and initialize the application, which is particularly useful in multi-tenant setups.

## Advanced Features

- **Environment Overrides**: The `overrides` method allows for dynamic loading of tenant-specific or environment-specific configurations, ensuring that the application adapts to different runtime environments seamlessly.
- **Security Management**: The class provides methods like `set_env_secret` and `get_secret` to manage environment secrets, enhancing the application's security posture.

## Debugging and Maintenance

- The class includes methods to assist in debugging and maintenance, such as `get_server_env` and `get_user_constants`, which are instrumental in inspecting the application's environment and constants in debug mode.

## Conclusion

The `Kernel` class is a foundational component designed to simplify and standardize the setup and configuration process of WordPress applications within the WPframework. By abstracting away the complexities of application initialization, environment management, and multi-tenancy support, it allows developers to focus on building robust and scalable WordPress applications.
