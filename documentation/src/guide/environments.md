# Managing Environments

The Raydium Framework offers a sophisticated way to handle different environments such as development, staging, and production. This guide will help you understand how to effectively manage these environments within your Raydium-powered application.

## Understanding Environments

Environments are essentially different states your application can be in, each with its own set of configurations and behaviors. Common environments include:

- **Development (`dev`)**: Used during the development phase, with extensive logging, error reporting, and debugging tools enabled.
- **Staging (`staging`)**: Mirrors the production environment for testing purposes. It's a final check before an update goes live.
- **Production (`prod`)**: The live site where performance and security are optimized, and debugging tools are typically turned off.
- **Debug (`debug`)**: A special case of the development environment with additional debugging capabilities.
- **Secure (`secure`)**: An environment with heightened security measures, often used in sensitive applications.

## Configuring Environments

### Using `.env` File

The Raydium Framework leverages a `.env` [environment file](../guide/environment-file) at the root of your project to define environment variables. The key variable for setting the application's environment is `WP_ENVIRONMENT_TYPE`.

Example of a `.env` file configuration:

```plaintext
WP_ENVIRONMENT_TYPE='prod'
```

This configuration sets the application's environment to production. Depending on this setting, the Raydium Framework adjusts its behavior accordingly, optimizing for performance, security, or debugging capabilities.

### Supported Environment Values

- `prod` or `production`: Sets the environment to production.
- `dev` or `development`: Sets the environment to development.
- `staging`: Sets the environment to staging.
- `debug` or `deb`: Activates the debug environment.
- `secure` or `sec`: Activates the secure environment.

## Environment-Specific Behaviors

Depending on the `WP_ENVIRONMENT_TYPE` value, the Raydium Framework configures the application as follows:

- **Production**: Maximizes performance and security. Disables debugging tools and error displays.
- **Staging**: Similar to production but might have logging or error reporting enabled for testing.
- **Development**: Enables error reporting, debugging tools, and might have performance optimizations disabled for easier troubleshooting.
- **Debug**: Extends development settings with additional debugging capabilities, such as script debugging and query logging.
- **Secure**: Similar to production with additional security measures like file editing restrictions and stringent error handling.

## Switching Environments

To switch environments, simply change the `WP_ENVIRONMENT_TYPE` value in your `.env` file and redeploy your application if necessary. The Raydium Framework automatically detects this change and applies the corresponding configuration settings.

## Best Practices

- **Keep `.env` Secure**: The `.env` [environment file](./environment-file) contains sensitive information. Ensure it's properly excluded from version control systems (e.g., using `.gitignore`).
- **Environment Consistency**: Maintain consistency between your development, staging, and production environments to prevent "it works on my machine" issues.
- **Use Environment-Specific Configurations**: Leverage the capability to define environment-specific settings for database connections, API keys, and other configurations.

> Effectively managing environments in the Raydium Framework enhances your application's development lifecycle, offering seamless transitions from development to production. By leveraging the `.env` file for environment configuration, you ensure flexibility, security, and efficiency throughout your application's deployment process.
