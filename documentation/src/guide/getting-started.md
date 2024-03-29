# Getting Started

## Try It Online

Currently, Raydium does not provide an online trial ( coming soon). To experiment with Raydium, you'll need to set it up locally by following the installation and configuration steps outlined below.

## Installation

## Prerequisites

To get started with Raydium, make sure you have the following prerequisites:

- PHP version 7.4 or higher.
- Composer to manage PHP dependencies.
- A MySQL or MariaDB database.
- Terminal access to execute Raydium and other related commands.
- A text editor or IDE that supports PHP and WordPress development, such as [VSCode](https://code.visualstudio.com/) with appropriate extensions.

To integrate Raydium into your WordPress project, you can install it using Composer with the following command:

```shell
composer create-project devuri/raydium your-project-name
```

## Setup

After installing Raydium, proceed by navigating to your project directory to configure your environment:

```bash
cd your-project-name
```

You'll need to adjust the `.env` file to align with your database settings and site URL. Ensure the `WP_HOME` variable accurately reflects your site's URL.

## File Structure

Raydium organizes your project in a structured manner, promoting a modular approach to development. A typical Raydium project's file structure looks like this:

```
.
├─ your-project-name
│  ├─ public
│  │  └─ content
│  │     ├─ themes
│  │     ├─ plugins
│  │     └─ uploads
│  │  ├── wp
│  ├─ vendor
│  ├─ .env
│  └─ composer.json
└─ .gitignore
```

- `public/content`: This directory replaces the standard `wp-content` folder in WordPress, containing themes, plugins, and uploads, ensuring web-accessible assets are stored securely within the `public` directory.
- `wp`: WordPress core and, located in the web-accessible `public` directory.
- `vendor`: Managed by Composer, this directory includes all PHP dependencies, and additional libraries, located outside the web-accessible `public` directory for enhanced security.
- `.env`: This file, stored one level above the `public` directory, holds environment-specific settings like database credentials and API keys, offering a secure way to manage sensitive configurations.

## The Config File

In Raydium, the primary configuration is handled through the `.env` file, offering a more secure and flexible way to manage settings compared to WordPress's traditional `wp-config.php`.

```shell
# Sample .env file content
WP_HOME='https://yourdomain.com'
WP_SITEURL="${WP_HOME}/wp"
DB_NAME='your_db_name'
DB_USER='your_db_user'
DB_PASSWORD='your_db_password'
DB_HOST='localhost'
```

## Source Files

Themes and plugins will reside within the `public/content/themes` and `public/content/plugins` directories.

## Up and Running

To launch your Raydium-based WordPress site, ensure your web server (such as Apache or Nginx) is correctly configured to serve your project directory. Then, visit your site's URL to go through the WordPress installation and initial setup.

## What's Next?

- Dive deeper into the functionalities and features of Raydium by exploring its documentation.
- Get acquainted with the modular design principles of Raydium to efficiently extend and customize it.
- Connect with the Raydium community for additional support, insights, and to share your experiences and best practices.
