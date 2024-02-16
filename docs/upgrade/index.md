## wp-framework version 5.0
> Upgrade from wp-env-config to wp-framework version 5

To upgrade your project to use the latest `wp-framework` version 5 and integrate the new initialization process in your `bootstrap.php` file, follow this guide:

### 1. Update `composer.json`

Begin by updating your project's `composer.json` to require version 5 of `wp-framework`:

1. Open `composer.json` in your project's root directory.
2. Locate the `require` section and replace the entry for `devuri/wp-env-config` with `devuri/wp-framework`, specifying version 5:

    ```json
    "require": {
        "devuri/wp-framework": "^5.0"
    }
    ```

3. Save your changes.

### 2. Update Project Dependencies

Run Composer to update your dependencies, which will remove the old `wp-env-config` package and install the specified version of `wp-framework`:

```bash
composer update
```

### 3. Remove the Old MU-Plugin

Remove the `compose.php` file from your `mu-plugins` directory, as it will be replaced with the new `wpframework.php` file:

```bash
rm mu-plugins/compose.php
```

### 4. Download the New MU-Plugin File

Download the new `wpframework.php` file from the `wp-framework` GitHub repository and place it in your `mu-plugins` directory. You can do this manually or use the following command:

Using `wget`:

```bash
wget https://raw.githubusercontent.com/devuri/wp-framework/master/src/inc/mu-plugin/wpframework.php -O mu-plugins/wpframework.php
```

Or using `curl`:

```bash
curl -o mu-plugins/wpframework.php https://raw.githubusercontent.com/devuri/wp-framework/master/src/inc/mu-plugin/wpframework.php
```

### 5. Update the `bootstrap.php` File

In your `bootstrap.php` file, update the application initialization line to use `wpframework`:

```php
$http_app = wpframework(__DIR__);
```

This line initializes your application with the base directory as its context, leveraging the `wp-framework`'s functionalities.

### 6. Test Your Application

After making all the updates, thoroughly test your application to ensure everything functions correctly. Verify that the `wp-framework` is properly initialized and all configurations are loaded as expected.

By following these steps, your project will be successfully updated to use version 5 of the `wp-framework`, with the old `wp-env-config` removed and replaced by the new initialization process in the `bootstrap.php` file.
