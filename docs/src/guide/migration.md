# Migration Guide for Standard WordPress

Transitioning from a standard WordPress setup to a Raydium-powered environment can significantly enhance your development workflow, scalability, and security. This guide is designed to help you migrate your existing WordPress site to Raydium, ensuring a smooth and efficient process.

## Preparation

Before initiating the migration, it's crucial to prepare and ensure that your current WordPress environment and content are ready to be transferred to Raydium.

### Backup Your Site

1. **Database**: Use tools like phpMyAdmin or WordPress plugins to export your WordPress database.
2. **Files**: Backup your `wp-content` folder, which contains your themes, plugins, and uploads.

### Evaluate Plugins and Themes

Review the plugins and themes you're currently using. Raydium's modular approach may offer more efficient alternatives or require adjustments to ensure compatibility.

### Set Up a Local Development Environment

Consider setting up a local environment for the migration process. This allows you to test and make necessary adjustments without affecting your live site.

## Installation of Raydium

1. **Install Raydium**: Start by creating a new Raydium project in your local environment using Composer:

   ```bash
   composer create-project devuri/raydium your-project-name
   ```

2. **Environment Configuration**: Configure your `.env` file within the Raydium project to match your database settings and site URL.

## Migrating Content

With Raydium installed and configured, you can start migrating your WordPress content.

### Import Database

Import your exported database into the new database configured for your Raydium project. You may use tools like phpMyAdmin or command-line interfaces for this process.

### Migrate `wp-content`

Raydium uses a `public/wp-content` directory. Copy your themes, plugins, and uploads from your backup to the corresponding directories within `public/wp-content`.

## Theme and Plugin Compatibility

Ensure that your themes and plugins are compatible with Raydium's structure. You might need to make some adjustments or find alternatives that are specifically designed for or compatible with Raydium.

### Test Plugins and Themes

Activate your themes and plugins within the Raydium environment and test their functionality. Pay special attention to custom functions or any hard-coded paths that might require updating to fit Raydium's directory structure.

## Finalizing the Migration

After moving your content and ensuring compatibility, it's time to finalize your migration and test the site thoroughly.

### Update URLs

If your site URL has changed, or if you're moving from a local environment to production, use WordPress tools or plugins to update the site URLs in your database.

### Test the Site

Go through every aspect of your site to ensure everything is functioning as expected. Check links, images, forms, and plugin functionality.

### Resolve Any Issues

Address any issues that arise during testing. This might involve tweaking theme files, adjusting plugin settings, or resolving database discrepancies.

## Going Live

Once you're confident that your Raydium site is ready and fully functional, it's time to deploy it to your production environment.

1. **Upload Raydium Project**: Transfer your local Raydium project to your production server, ensuring the `public` directory is set as the web root.
2. **Run Composer**: Execute `composer install` on your production server to ensure all dependencies are correctly installed.
3. **Final Testing**: Conduct a final round of testing on your live site to confirm everything is operational.

## Post-Migration Tips

- **Monitor Performance and Security**: Keep an eye on your site's performance and security, making adjustments as needed.
- **Stay Updated**: Regularly update Raydium, WordPress, and all plugins and themes to their latest versions.
- **Engage with the Community**: Join the Raydium community to stay informed about best practices, updates, and to seek assistance if needed.

Migrating to Raydium can transform your WordPress development experience, offering a more structured, efficient, and secure way to build and maintain your sites. Follow this guide to ensure a smooth transition and take the first step towards a more modern WordPress development workflow.
