# Installation Guide for Raydium

Welcome to the comprehensive installation guide for Raydium, the innovative framework designed to enhance WordPress development. This guide will walk you through the prerequisites, installation steps, and initial configuration to get your Raydium-powered WordPress site up and running.

## Prerequisites

Before you begin the installation process, ensure you have the following prerequisites:

1. **PHP**: Raydium requires PHP version 7.4 or higher. Verify your PHP version using the command: `php -v`.

2. **Composer**: Raydium uses Composer for dependency management. Install Composer from [getcomposer.org](https://getcomposer.org/download/) if you haven't already.

3. **MySQL or MariaDB Database**: Ensure you have access to a MySQL or MariaDB database. You'll need the database credentials during the WordPress setup.

4. **Web Server**: Any standard web server like Apache or Nginx capable of serving PHP applications. Make sure it's configured to serve the `public` directory of your Raydium project as the web root.

5. **Command Line Access**: You'll need terminal or command line access to execute Composer commands.

## Installation Steps

### Step 1: Create a New Raydium Project

Start by creating a new Raydium project using Composer. Open your terminal or command line tool and run the following command:

```bash
composer create-project devuri/raydium your-project-name
```

Replace `your-project-name` with your desired directory name for the new project. This command downloads the Raydium framework and sets up a new WordPress project in the specified directory.

### Step 2: Configure Your Environment

Navigate to your project directory:

```bash
cd your-project-name
```

Within this directory, you'll find an `.env` [environment file](../customization/environment-file). Open this file in your text editor and configure the following settings:

- `WP_HOME`: Set this to your site's URL.
- `WP_SITEURL`: This will be your WordPress core directory URL, typically `WP_HOME` appended with `/wp`.
- `DB_NAME`: The name of your database.
- `DB_USER`: Your database username.
- `DB_PASSWORD`: Your database password.
- `DB_HOST`: Your database host, usually `localhost`.

Example `.env` configuration:

```dotenv
WP_HOME='http://yourdomain.com'
WP_SITEURL="${WP_HOME}/wp"
DB_NAME='your_db_name'
DB_USER='your_db_user'
DB_PASSWORD='your_db_password'
DB_HOST='localhost'
```

### Step 3: Serve Your Project

Configure your web server (Apache, Nginx, etc.) to serve the `public` directory of your Raydium project as the web root. This setup ensures that the web server correctly serves the WordPress site managed by Raydium.

For Apache, ensure you have an `.htaccess` file within the `public` directory with the necessary rewrite rules. For Nginx, configure your site's server block to point the root to the `public` directory.

### Step 4: Complete WordPress Setup

Once your web server is serving the Raydium project, navigate to your site's URL in a web browser. You should be greeted with the WordPress installation wizard. Follow the steps to select a language, set up your WordPress admin account, and complete the installation.

## What's Next?

After completing the installation, you're ready to start developing with Raydium. Explore the Raydium documentation to learn about its features, including modular architecture, enhanced security, and streamlined development workflow.

Happy developing!
