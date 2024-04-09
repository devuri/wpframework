# Constants Config Guide

This guide provides an overview of the framework's constants overrides configuration system, designed to support a range of setups from simple, single-instance applications to complex, multi-tenant platforms. Understanding how the framework selects/overrides constant file and the flexibility built into this process will help you tailor the framework to your specific needs.

## Constants File Selection Process

The framework intelligently selects the most appropriate config file based on the operational context, ensuring optimal settings for every scenario.

### Multi-Tenant and Single-Tenant Modes

- **Multi-Tenant Mode**: In environments hosting multiple tenants, the framework looks for tenant-specific config files within a dedicated directory structure, typically following the pattern: `/path/to/app/configs/<tenant_id>/config.php`.

- **Single-Tenant Mode**: For single-tenant or simpler setups, the framework defaults to a standard config file located at: `/path/to/app/config.php`.

### Fallback Mechanism

If the specified config file is not found, the framework will attempt to use an alternate default file from a secondary configs directory: `/path/to/app/configs/config.php`. This step ensures the application has a configuration to fall back on, maintaining smooth operation.

## Security Considerations

### Handling Sensitive Information

> [!CAUTION]
It's important to handle sensitive information, such as API keys, secrets and other credentials, with utmost caution.

Sensitive information should **never** be hard-coded directly into your constants configuration files. Instead:

- Store sensitive information within the `.env` file, a secure and environment-specific file that is not committed to version control.
- Reference these sensitive values in your application by utilizing the `env()` function. This approach ensures that sensitive details are securely managed and easily configurable across different environments without altering your application's core configuration files.


## Constants File Flexibility

The framework is designed with built-in defaults that allow the application to run smoothly without the need for a custom config file. This design choice emphasizes ease of use and simplicity for straightforward setups.

### Optional Constants Files

- **Ease of Setup**: For many applications, especially those that don't require customization beyond the default settings, no `config.php` file is needed. The framework will operate with its built-in defaults.

- **Conditional Loading**: Config `config.php` files are loaded only if present. This approach ensures that the absence of a config file does not impact the application's functionality.

## Customizing Your Application

While the default settings are suitable for many scenarios, you might need to override these settings as your application grows or your needs become more specific.

### When to Add Constants `config.php` File

Consider adding a config file if you need to:

- **Override Defaults**: Customize SSL settings established by the framework, or other application-specific parameters.

- **Accommodate Multiple Tenants**: In a multi-tenant setup, provide unique config for each tenant without altering the framework's core functionality.

### Getting Started

- **Initial Setup**: Begin with the default settings to get your application up and running quickly. Add config files as your customization needs evolve.

- **Advanced Configuration**: For more complex setups, including multi-tenant environments, organize your config files in the tenant-specific directory structure to maintain clarity and ease of management.

## Example Use Cases

- **Simple Website**: Running a standard website with no special configuration needs? The framework is ready to go as is, no additional config required.

- **Multi-Tenant Platform**: Operating a multi-tenant platform? Create a unique `config.php` file for each tenant within their respective directories to ensure customized experiences.

> This guide aims to provide you with a clear understanding of the framework's flexible and intelligent const configuration system. By following these guidelines, you can tailor the framework to meet your exact requirements, whether you're managing a simple site or a complex, multi-tenant platform.
