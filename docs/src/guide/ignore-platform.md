# Ignore Platform

Ignoring Platform Requirements (e.g., PHP Version)

When installing Raydium or its WPFramework, you may encounter platform requirements such as a specific PHP version. In some cases, you might want to bypass these requirements, for example, during development or testing. Here's how you can ignore platform requirements during installation:

#### Step-by-Step Guide

1. **Open Terminal**: Access your command line interface.

2. **Navigate to Project Directory**: Ensure you are in the root directory of your Raydium project or where you intend to install Raydium.

   ```sh
   cd /path/to/your/project
   ```

3. **Ignore Platform Requirements with Composer**: Use the `--ignore-platform-reqs` flag with Composer to bypass platform requirements. This flag tells Composer to ignore all platform requirements, including PHP version, PHP extensions, and other dependencies.

   ```sh
   composer install --ignore-platform-reqs
   ```

   If you're installing Raydium for the first time, use:

   ```sh
   composer create-project raydium/core my-project --ignore-platform-reqs
   ```

#### Alternative: Specify Your Project's PHP Version

Instead of ignoring platform requirements, you can specify the PHP version in your `composer.json` file. This ensures Composer fetches packages based on the configured PHP version, even if it's different from the version you're using to execute Composer.

1. **Edit `composer.json`**: Open your project's `composer.json` file and add the `platform` configuration under the `config` section.

   ```json
   {
       "config": {
           "platform": {
               "php": "7.4.3"
           }
       }
   }
   ```

   Replace `"7.4.3"` with the PHP version you want to target (e.g., `"8.0.4"` or any other version).

2. **Save and Install**: Save the changes to your `composer.json` file and run the Composer install command normally.

   ```sh
   composer install
   ```

#### Verification

- **Successful Installation**: After running the command, Composer will proceed with the installation according to the specified configuration or ignoring platform-specific requirements. Ensure that the installation completes successfully by checking the output in your terminal.

### Important Considerations

- **Development Only**: Ignoring platform requirements is generally recommended for development environments. For production environments, ensure that your server meets all the necessary requirements to maintain stability and security.

- **Compatibility Issues**: Bypassing platform requirements may lead to compatibility issues. Test thoroughly to ensure that Raydium functions correctly with your current setup.

- **Reverting Changes**: If you need to revert to standard installation procedures later, simply run the Composer command without the `--ignore-platform-reqs` flag:

  ```sh
  composer install
  ```

By following these steps, you can successfully install Raydium while ignoring platform-specific requirements or by configuring your project's PHP version, allowing for more flexibility in your development process.
