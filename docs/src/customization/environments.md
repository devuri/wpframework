# Managing Environments

The Raydium Framework offers a sophisticated way to handle different environments such as development, staging, and production. This guide will help you understand how to effectively manage these environments within your Raydium-powered application.

## Understanding Environments

Environments are essentially different **states** your application can be in, each with its own set of configurations and behaviors. Common environments include:

- **Production** (`'production'`, `'prod'`): The live site where performance and security are optimized, and debugging tools are typically turned off.
- **Staging** (`'staging'`): Mirrors the production environment for testing purposes. It's a final check before an update goes live.
- **Development** (`'development'`, `'dev'`): Used during the development phase, with extensive logging, error reporting, and debugging tools enabled.
- **Debug** (`'deb'`, `'debug'`, `'local'`): A special case of the development environment with additional debugging capabilities.
- **Secure** (`'secure'`, `'sec'`): An environment with heightened security measures, often used in sensitive applications.

---

## Best Practices

- **Keep `.env` file Secure**: The `.env` [environment file](../customization/environment-file) contains sensitive information. Ensure it's properly excluded from version control systems (e.g., using `.gitignore`).
- **Environment Consistency**: Maintain consistency between your development, staging, and production environments to prevent "it works on my machine" issues.
- **Use Environment-Specific Configurations**: Leverage the capability to define environment-specific settings for database connections, API keys, and other configurations.


## Configuring Environments

### Using the `.env` File

The Raydium Framework leverages a `.env` [environment file](../customization/environment-file) at the root of your project to define environment variables. The key variable for setting the application's environment is `WP_ENVIRONMENT_TYPE`.

Example of a `.env` file configuration:

```plaintext
WP_ENVIRONMENT_TYPE='prod'
```

> [!IMPORTANT]
> Optionally, you can override the `.env` setup of `WP_ENVIRONMENT_TYPE` by using the `RAYDIUM_ENVIRONMENT_TYPE` constant, which should be updated in the bootstrap file. It's important to note that this constant must be defined before setting up `http_component_kernel`. The `.env` file takes precedence, so ensure you remove `WP_ENVIRONMENT_TYPE='prod'` from your `.env` file if it already exists.

This configuration sets the application's environment to production. Depending on this setting, the Raydium Framework adjusts its behavior accordingly, optimizing for performance, security, or debugging capabilities.

### Supported Environment Values

- `prod` or `production`: Sets the environment to production.
- `dev` or `development`: Sets the environment to development.
- `staging`: Sets the environment to staging.
- `debug` or `deb`: Activates the debug environment.
- `secure` or `sec`: Activates the secure environment.

### Switching Environments

To switch environments, simply modify the `WP_ENVIRONMENT_TYPE` value in your `.env` file and redeploy your application if necessary. The Raydium Framework will automatically detect this change and apply the corresponding configuration settings.

---

## Environment-Specific Behaviors

Depending on the `WP_ENVIRONMENT_TYPE` value, the Raydium Framework configures the application as follows:

- **Production**: Maximizes performance and security. Disables debugging tools and error displays.
- **Staging**: Similar to production but might have logging or error reporting enabled for testing.
- **Development**: Enables error reporting, debugging tools, and might have performance optimizations disabled for easier troubleshooting.
- **Debug**: Extends development settings with additional debugging capabilities, such as script debugging and query logging.
- **Secure**: Similar to production with additional security measures like file editing restrictions and stringent error handling.

---

## Environment Constants Documentation

The framework provides a set of constants to configure the application's behavior based on different environments, ensuring optimal settings for performance, security, and debugging.

### Production Environment

**Environment Identifiers**: `'production'`, `'prod'`

In the production environment, the application is optimized for performance and security.

#### Defined Constants

- **`DISALLOW_FILE_EDIT`**: `true`
  - **Purpose**: Disables the plugin and theme editors in the admin panel to prevent direct editing of code.

- **`WP_DEBUG_DISPLAY`**: `false`
  - **Purpose**: Hides debug messages from being displayed on the frontend.

- **`SCRIPT_DEBUG`**: `false`
  - **Purpose**: Uses minified JavaScript and CSS files to improve load times.

- **`WP_CRON_LOCK_TIMEOUT`**: `60`
  - **Purpose**: Sets the time (in seconds) before a cron job is considered failed and can be retried.

- **`EMPTY_TRASH_DAYS`**: `15`
  - **Purpose**: Automatically deletes items in the trash after 15 days.

- **`WP_DEBUG`**:
  - **Value**: `true` if `$error_logs_dir` is provided; otherwise `false`.
  - **Purpose**: Enables or disables the WordPress debugging mode.

- **`WP_DEBUG_LOG`**:
  - **Value**: Path specified by `$error_logs_dir` if provided; otherwise `false`.
  - **Purpose**: Specifies where to log debug messages.

#### PHP Settings

- **`display_errors`**: `'0'`
  - **Purpose**: Prevents PHP errors from being displayed to users.

---

### Staging Environment

**Environment Identifier**: `'staging'`

The staging environment is used for testing before deploying to production.

#### Defined Constants

- **`DISALLOW_FILE_EDIT`**: `false`
  - **Purpose**: Allows editing of plugin and theme files via the admin panel.

- **`WP_DEBUG_DISPLAY`**: `true`
  - **Purpose**: Displays debug messages on the frontend for testing.

- **`SCRIPT_DEBUG`**: `false`
  - **Purpose**: Uses minified scripts.

- **`WP_DEBUG`**: `true`
  - **Purpose**: Enables WordPress debugging mode.

- **`WP_DEBUG_LOG`**:
  - **Value**: Path specified by `$error_logs_dir` if provided; otherwise `true`.
  - **Purpose**: Logs debug messages to the specified file or the default log.

#### PHP Settings

- **`display_errors`**: `'0'`
  - **Purpose**: Keeps PHP errors hidden from frontend users.

---

### Development Environment

**Environment Identifiers**: `'development'`, `'dev'`

The development environment is configured for developers to build and test features.

#### Defined Constants

- **`WP_DEBUG`**: `true`
  - **Purpose**: Enables WordPress debugging mode.

- **`SAVEQUERIES`**: `true`
  - **Purpose**: Saves database queries for analysis.

- **`WP_DEBUG_DISPLAY`**: `true`
  - **Purpose**: Displays debug messages on the frontend.

- **`WP_DISABLE_FATAL_ERROR_HANDLER`**: `true`
  - **Purpose**: Disables the fatal error handler to allow for error visibility.

- **`SCRIPT_DEBUG`**: `true`
  - **Purpose**: Uses unminified scripts for easier debugging.

- **`WP_DEBUG_LOG`**:
  - **Value**: Path specified by `$error_logs_dir` if provided; otherwise `true`.
  - **Purpose**: Logs debug messages.

#### PHP Settings

- **`display_errors`**: `'1'`
  - **Purpose**: Displays PHP errors directly on the frontend.

---

### Debug Environment

**Environment Identifiers**: `'deb'`, `'debug'`, `'local'`

The debug environment provides extensive debugging capabilities for troubleshooting.

#### Defined Constants

- **`WP_DEBUG`**: `true`
  - **Purpose**: Enables WordPress debugging mode.

- **`WP_DEBUG_DISPLAY`**: `true`
  - **Purpose**: Shows debug messages on the frontend.

- **`CONCATENATE_SCRIPTS`**: `false`
  - **Purpose**: Prevents concatenation of scripts for easier debugging.

- **`SAVEQUERIES`**: `true`
  - **Purpose**: Saves database queries for debugging purposes.

- **`WP_DEBUG_LOG`**:
  - **Value**: Path specified by `$error_logs_dir` if provided; otherwise `true`.
  - **Purpose**: Logs debug messages.

#### PHP Settings

- **`error_reporting`**: `E_ALL`
  - **Purpose**: Reports all types of errors.

- **`log_errors`**: `'1'`
  - **Purpose**: Enables logging of errors.

- **`log_errors_max_len`**: `'0'`
  - **Purpose**: Removes the limit on the length of logged errors.

- **`display_errors`**: `'1'`
  - **Purpose**: Displays errors on the frontend.

- **`display_startup_errors`**: `'1'`
  - **Purpose**: Displays startup sequence errors.

---

### Secure Environment

**Environment Identifiers**: `'secure'`, `'sec'`

The secure environment is optimized for maximum security.

#### Defined Constants

- **`DISALLOW_FILE_EDIT`**: `true`
  - **Purpose**: Disables file editing via the admin panel.

- **`DISALLOW_FILE_MODS`**: `true`
  - **Purpose**: Prevents updates and installations of plugins and themes.

- **`WP_DEBUG_DISPLAY`**: `false`
  - **Purpose**: Hides debug messages from the frontend.

- **`SCRIPT_DEBUG`**: `false`
  - **Purpose**: Uses minified scripts.

- **`WP_CRON_LOCK_TIMEOUT`**: `120`
  - **Purpose**: Sets a longer timeout for cron jobs.

- **`EMPTY_TRASH_DAYS`**: `10`
  - **Purpose**: Empties trash more frequently to reduce data retention.

- **`WP_DEBUG`**:
  - **Value**: `true` if `$error_logs_dir` is provided; otherwise `false`.
  - **Purpose**: Enables or disables debugging mode.

- **`WP_DEBUG_LOG`**:
  - **Value**: Path specified by `$error_logs_dir` if provided; otherwise `false`.
  - **Purpose**: Specifies debug log location.

#### PHP Settings

- **`display_errors`**: `'0'`
  - **Purpose**: Ensures errors are not displayed to users.

---

## Commonly Used Constants Explained

- **`DISALLOW_FILE_EDIT`**:
  - **Usage**: Prevents file modifications via the WordPress admin panel, enhancing security.

- **`DISALLOW_FILE_MODS`**:
  - **Usage**: Disables all file modifications including updates and installations.

- **`WP_DEBUG`**:
  - **Usage**: Activates the built-in debugging system in WordPress.

- **`WP_DEBUG_DISPLAY`**:
  - **Usage**: Controls whether debug messages are shown on the frontend.

- **`WP_DEBUG_LOG`**:
  - **Usage**: Determines if debug messages are logged to a file.

- **`SCRIPT_DEBUG`**:
  - **Usage**: Forces WordPress to use the unminified versions of scripts and styles.

- **`SAVEQUERIES`**:
  - **Usage**: Saves database queries for debugging purposes.

- **`CONCATENATE_SCRIPTS`**:
  - **Usage**: When disabled, scripts are loaded individually rather than concatenated.

- **`WP_DISABLE_FATAL_ERROR_HANDLER`**:
  - **Usage**: Disables the fatal error handler for better error visibility during development.

- **`WP_CRON_LOCK_TIMEOUT`**:
  - **Usage**: Adjusts the timeout for cron jobs, affecting how quickly they can be retried after failure.

- **`EMPTY_TRASH_DAYS`**:
  - **Usage**: Sets the number of days before trash is emptied automatically.

### Overriding Constants

The constants defined by the framework can be overridden in the `wp-config.php` file. The framework will only define these constants if they have not already been set, allowing you to adjust them as needed.

For more information about WordPress constants and how they work, refer to the official WordPress documentation:

- [WordPress Debugging Tools](https://wordpress.org/support/article/debugging-in-wordpress/)
- [Editing wp-config.php](https://wordpress.org/support/article/editing-wp-config-php/)

These resources provide additional insights into how to customize WordPress settings to suit your development, staging, or production needs.

---

## PHP Settings Explained

- **`error_reporting`**:
  - **Purpose**: Defines which errors are reported by PHP.

- **`display_errors`**:
  - **Purpose**: Controls whether errors are displayed to the user.

- **`display_startup_errors`**:
  - **Purpose**: Displays errors that occur during PHP's startup sequence.

- **`log_errors`**:
  - **Purpose**: Enables or disables error logging.

- **`log_errors_max_len`**:
  - **Purpose**: Sets the maximum length of error messages in logs.

---

## Notes

- **Error Logs Directory (`$error_logs_dir`)**:
  - If provided, this path is used for logging debug messages.
  - Affects the values of `WP_DEBUG` and `WP_DEBUG_LOG`.

- **`ini_set` Function**:
  - Used to modify PHP runtime configuration settings for error display and logging.

- **Error Handling in Environments**:
  - **Production** and **Secure** environments hide errors from users and may disable debugging.
  - **Development** and **Debug** environments display errors and enable extensive logging for troubleshooting.

---

By understanding the constants and settings defined in each environment, developers and administrators can configure their application to behave appropriately, ensuring security, performance, and effective debugging.
