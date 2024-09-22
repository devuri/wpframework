# Framework Constants.

> Framework Constants and Environment Setup.

This documentation explains how the environment is configured and the default constants that the framework sets during the setup. The framework allows developers to override these constants if they choose to define them upstream, providing flexibility in customization.

### **Overview of Environment Setup**

The framework initializes the environment by defining key constants that are essential for the application and WordPress to function correctly. These constants include paths, URLs, database configurations, and plugin-related settings.

The constants are defined using a method that checks if a constant is already set. If a constant is not already defined, the framework will assign a default value. This approach allows developers to override specific constants by defining them earlier in the stack (upstream).

### **How Constants Are Set**

The framework uses a custom `define()` method to set constants. This method ensures that the constants are only defined if they haven't been set already. This means that any constant defined earlier (e.g., in a config file or another upstream initialization step) will take precedence over the framework’s default values.

### **Default Framework Constants**

The framework sets the following constants by default. These constants are grouped into three categories: **Framework/Custom**, **WordPress Core**, and **WordPress Plugin-Related**. Below is a list of some these constants along with their descriptions.

### **Framework/Custom Constants**

These constants define paths and settings specific to the framework:

- **`APP_PATH`**: Defines the root path of the application.
- **`APP_HTTP_HOST`**: Sets the HTTP host for the application.
- **`PUBLIC_WEB_DIR`**: Defines the public web root directory.
- **`APP_ASSETS_DIR`**: Directory path for static assets like CSS, JavaScript, etc.
- **`APP_CONTENT_DIR`**: Content directory for the application (can differ from WordPress content).
- **`DB_DIR`**: Path to the directory where the SQLite database is stored.
- **`DB_FILE`**: Name of the SQLite database file.
- **`WP_SUDO_ADMIN`**: User ID or identifier for the sudo admin with elevated privileges.
- **`SUDO_ADMIN_GROUP`**: Group name or identifier for a group of users with higher administrative privileges.
- **`WEBAPP_ENCRYPTION_KEY`**: Security key for web application encryption purposes.
- **`APP_THEME_DIR`**: Directory path for custom themes (if specified).

These constants can be overridden by defining them earlier in the application setup (e.g., in an environment file or configuration file).

### **WordPress Core Constants**

These constants are related to the core WordPress functionality:

- **`WP_CONTENT_DIR`**: Defines the content directory for WordPress (e.g., `wp-content`).
- **`WP_CONTENT_URL`**: The URL to the WordPress content directory.
- **`WP_DIR_PATH`**: Path to the WordPress installation directory.
- **`WP_PLUGIN_DIR`**: Directory path for WordPress plugins.
- **`WP_PLUGIN_URL`**: URL for the WordPress plugins directory.
- **`WPMU_PLUGIN_DIR`**: Directory path for WordPress must-use (MU) plugins.
- **`WPMU_PLUGIN_URL`**: URL for WordPress must-use plugins.
- **`AUTOMATIC_UPDATER_DISABLED`**: Disables automatic WordPress updates (default is handled via composer).
- **`WP_DEFAULT_THEME`**: Slug of the default WordPress theme used when installing new sites or as a fallback if the active theme is not available.
- **`COOKIEHASH`**: MD5 hash of the home URL, used for cookie-related WordPress constants.
- **`USER_COOKIE`, `PASS_COOKIE`, `AUTH_COOKIE`, `SECURE_AUTH_COOKIE`, `LOGGED_IN_COOKIE`, `TEST_COOKIE`**: Cookie names for authentication and session management in WordPress.

These constants play a crucial role in the configuration of a WordPress installation and can be easily customized if developers need specific paths or URLs.

### **WordPress Plugin-Related Constants**

These constants are related to specific plugins or extensions used within the WordPress ecosystem:

- **`CAN_DEACTIVATE_PLUGINS`**: Controls whether admin users can deactivate plugins (true/false).
- **`WP_REDIS_DISABLED`**: Disables the Redis cache integration for WordPress.
- **`WP_REDIS_PREFIX`**: Prefix for Redis cache keys.
- **`WP_REDIS_DATABASE`**: Redis database index used by WordPress.
- **`WP_REDIS_HOST`**: Hostname or IP address of the Redis server.
- **`WP_REDIS_PORT`**: Port number for connecting to the Redis server.
- **`WP_REDIS_PASSWORD`**: Password for authenticating to the Redis server.
- **`WP_REDIS_DISABLE_ADMINBAR`**: Disables Redis admin bar visibility.
- **`WP_REDIS_DISABLE_METRICS`**: Disables Redis metrics collection.
- **`WP_REDIS_DISABLE_BANNERS`**: Disables Redis promotional banners in the WordPress admin.
- **`WP_REDIS_TIMEOUT`**: Connection timeout for Redis.
- **`WP_REDIS_READ_TIMEOUT`**: Read timeout for Redis connections.

These constants help configure how WordPress interacts with plugins such as Redis. Developers can override these defaults by defining their own settings upstream.

### **Overriding Framework Constants**

As mentioned, all constants are only defined if they haven't been set already. To override any default constant, simply define it in a configuration file or before the framework is initialized.

For example, to override the `WP_CONTENT_DIR` constant, you can do so in your `wp-config` file or a custom configuration:

```php
define('WP_CONTENT_DIR', '/custom/path/to/wp-content');
```

This will prevent the framework from overwriting this value with its default configuration.

The framework's default constants provide sensible default values for application paths, WordPress settings, and plugin configurations. By allowing developers to override any of these constants upstream, the framework offers flexibility and control over how the environment is set up, ensuring it can be customized to meet specific projects.
