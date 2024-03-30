# Configuring Multi-Tenancy in Raydium

Multi-tenancy in Raydium allows you to operate multiple WordPress sites from a single installation, making it ideal for managing a network of sites with centralized resources. The `tenancy.php` configuration file is pivotal in enabling and managing these capabilities. Understanding and correctly configuring this file is crucial for the successful implementation of a multi-tenant architecture.

## Key Configuration Options

### Enabling Multi-Tenancy

```php
\define( 'ALLOW_MULTITENANT', false );
```
- **Purpose**: Controls whether multi-tenant capabilities are activated within the framework.
- **Default**: `false`. Set to `true` to enable multi-tenancy.
- **Implication**: When enabled, the framework adjusts to support multiple tenant sites, each potentially having its own configurations and database.

### Landlord Identification

```php
\define( 'LANDLORD_UUID', null );
```
- **Purpose**: Identifies the main site or "landlord" in a multi-tenant setup by specifying its UUID.
- **Default**: `null`. Assign the UUID of the primary tenant to enable landlord-specific functionalities and permissions.

### Tenant-Specific Configurations

```php
\define( 'REQUIRE_TENANT_CONFIG', false );
```
- **Purpose**: Determines the strictness of tenant-specific configurations.
- **Default**: `false`. Setting this to `true` mandates that each tenant must have its own `config/{tenant_id}/app.php` file, enhancing security and customization.

### Customizing Web Root Directory

```php
\define( 'TENANCY_WEB_ROOT', 'public' );
```
- **Purpose**: Overrides the default web root directory to accommodate the multi-tenant architecture.
- **Default**: `'public'`. Change this to specify a different directory that serves as the web root for tenant sites.

## Best Practices for Multi-Tenancy Configuration

- **Security and Isolation**: Ensure that tenant-specific data and configurations are securely isolated to prevent data leakage between tenants.
- **Documentation**: Maintain thorough documentation for each tenant configuration to facilitate management and troubleshooting.
- **Testing**: Rigorously test multi-tenant functionalities in a controlled environment before deploying to production to ensure stability and performance.
- **Backup and Recovery**: Implement robust backup and recovery strategies to protect tenant data and configurations against loss or corruption.

> The `tenancy.php` file offers a structured approach to enabling and managing multi-tenancy within Raydium-powered installations. By carefully configuring the options in this file, you can leverage the full potential of multi-tenancy, optimizing resource use and streamlining the management of multiple WordPress sites. Remember to consult the overview section of the documentation and follow best practices to ensure a secure and efficient multi-tenant environment.
