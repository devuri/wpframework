# Deploying Projects with Deploy Keys

Deploying Raydium-powered WordPress projects using deploy keys and GitHub, especially when set up to use Composer, enhances security and automates the deployment process. This guide provides a detailed, step-by-step approach.

> [!DANGER]
> It is crucial to keep your private keys secure at all times. Private keys provide access to your repositories and servers, and if compromised, can lead to significant security risks, including unauthorized access to your codebase and sensitive data.

### What Are Deploy Keys?

Deploy keys are SSH keys that provide access to a single repository on GitHub. They can be read-only or read-write, offering a secure way to manage repository access without using personal credentials.

### Why Use Deploy Keys?

1. **Security**: They limit access to a specific repository, reducing potential security risks.
2. **Automation**: Facilitate automated deployments without needing user-specific credentials.
3. **Simplicity**: Simplify the deployment process by avoiding complex authentication setups.

## Step-by-Step Deployment Process

##### 1. Generating an SSH Key Pair
First, we need to generate an SSH key pair. This key pair consists of a public key and a private key.

1. Open your terminal or command prompt.
2. Run the following command to generate an SSH key pair:

    ```bash
    ssh-keygen -t ed25519 -C "your_email@example.com"
    ```

    - `-t ed25519` specifies the type of key to create (Ed25519 is a modern and secure choice).
    - `-C "your_email@example.com"` adds a comment with your email to the key.

3. When prompted to "Enter a file in which to save the key," press Enter to accept the default location.
4. When prompted to "Enter passphrase," press Enter twice to skip setting a passphrase (or set one for added security).

This will create two files:
- `id_ed25519` (your private key)
- `id_ed25519.pub` (your public key)

##### 2. Adding the Public Key to GitHub

Next, we need to add the public key to your GitHub repository as a deploy key.

1. Go to your GitHub repository.
2. Click on **Settings**.
3. In the left sidebar, click on **Deploy keys**.
4. Click on **Add deploy key**.
5. Provide a title for the key (e.g., "Deploy Key for Server").
6. Open the `id_ed25519.pub` file in a text editor and copy its contents.
7. Paste the copied contents into the **Key** field on GitHub.
8. Check **Allow write access** if you need the key to have write permissions (optional).
9. Click **Add key**.

##### 3. Configuring Your Raydium-powered WordPress Project

Ensure your Raydium-powered WordPress project is ready for deployment by setting up your environment variables and any deployment scripts.

1. Create a `.env` file if it doesn't exist, and configure it with your production settings.
2. Ensure your `deploy` script is ready. This script should handle tasks like installing dependencies etc.

##### 4. Creating a Deployment Script

Create a deployment script on your server to automate common deployment tasks. For example, create a file named `deploy.sh` in your Raydium-powered project's root directory:

```bash
#!/bin/bash

# Navigate to the project directory
cd /path/to/your/project

# Pull the latest changes from the GitHub repository
git pull origin main

# Install PHP dependencies
composer install --no-dev --optimize-autoloader

# Set correct permissions
chown -R www-data:www-data /path/to/your/project
```

Make this script executable by running:

```bash
chmod +x deploy.sh
```

##### 5. Automating Deployment with GitHub Actions

GitHub Actions can automate the deployment process. Create a workflow file in your repository:

1. In your repository, create a directory named `.github/workflows`.
2. Inside the `workflows` directory, create a file named `deploy.yml`.

Add the following content to `deploy.yml`:

```yaml
name: Deploy Raydium-powered WordPress Application

on:
  push:
    branches:
      - main

jobs:
  deploy:
    runs-on: ubuntu-latest

    steps:
    - name: Checkout code
      uses: actions/checkout@v2
      with:
        ssh-key: ${{ secrets.SSH_KEY }}

    - name: Set up PHP
      uses: shivammathur/setup-php@v2
      with:
        php-version: '8.0'

    - name: Install Dependencies
      run: |
        composer install --no-dev --optimize-autoloader

    - name: Deploy to Server
      run: |
        ssh -o StrictHostKeyChecking=no user@server 'bash -s' < ./deploy.sh
      env:
        SSH_PRIVATE_KEY: ${{ secrets.SSH_KEY }}
```

This workflow does the following:
- Triggers on pushes to the `main` branch.
- Checks out the repository code.
- Sets up PHP.
- Installs dependencies using Composer.
- Executes the `deploy.sh` script on the server.

##### 6. Setting Secrets in GitHub

To securely use your private SSH key in the workflow, add it as a secret in GitHub:

1. Go to your GitHub repository.
2. Click on **Settings**.
3. In the left sidebar, click on **Secrets and variables** > **Actions**.
4. Click on **New repository secret**.
5. Add a new secret with the name `SSH_KEY`.
6. Open the `id_ed25519` file in a text editor and copy its contents.
7. Paste the copied contents into the **Value** field on GitHub.
8. Click **Add secret**.

### Additional Resources

For more detailed information on using deploy keys and GitHub, refer to the following resources:

- [GitHub Docs: Managing deploy keys](https://docs.github.com/en/developers/overview/managing-deploy-keys)
- [GitHub Actions Documentation](https://docs.github.com/en/actions)
- [SSH Key Generation](https://www.ssh.com/academy/ssh/keygen)

### Keep Your Private Keys Secure

Here are some best practices to ensure the security of your private keys:

- **Never Share Your Private Keys**: Keep your private keys confidential and never share them with anyone.
- **Use Strong Passphrases**: If possible, set a strong passphrase when generating your keys to add an extra layer of security.
- **Store Keys Securely**: Store your private keys in a secure location, such as an encrypted file system or a dedicated key management service.
- **Limit Key Permissions**: Restrict the permissions of your private keys to only what is necessary for your deployment process.
- **Regularly Rotate Keys**: Periodically generate new key pairs and update your deploy keys to minimize the risk of key compromise.
- **Monitor Access**: Regularly monitor your repositories and server access logs for any unusual or unauthorized activity.

> By following these practices, you can significantly reduce the risk of unauthorized access and ensure the security of your WordPress project deployments.


Deploying your Raydium-powered projects using deploy keys and GitHub is a best practice that combines security, simplicity, and automation. By following the steps outlined in this guide, you can ensure a seamless and secure deployment process for your applications.
