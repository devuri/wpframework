# GitHub Actions and rsync

## Automated Deployment of a Raydium Project using GitHub Actions and rsync.

A GitHub Actions workflow that uses `rsync` to deploy your Raydium project. 
This method involves synchronizing the files from your local machine to the remote server.
This is efficient for updating only the changed files and maintaining file permissions.

Here's a detailed guide to set up deployment using the `rsync` strategy.

## Prerequisites

- A GitHub account.
- A remote server with SSH access.
- `rsync` installed on your remote server.
- Git installed on both your local machine and the remote server.
- Raydium installed and configured on your local machine.
- Basic knowledge of Git, SSH, and Raydium's WPFramework.

## Step-by-Step Guide

### 1. Set Up Your Local Raydium Project

1. **Initialize a Raydium Project:**
   ```bash
   raydium init my-project
   cd my-project
   ```

2. **Initialize a Git Repository:**
   ```bash
   git init
   git add .
   git commit -m "Initial commit"
   ```

3. **Create a Repository on GitHub:**
   - Go to GitHub and create a new repository.
   - Follow the instructions to add the remote repository to your local Git setup:
     ```bash
     git remote add origin https://github.com/yourusername/your-repo.git
     git branch -M main
     git push -u origin main
     ```

### 2. Generate and Add SSH Keys for GitHub Actions

1. **Generate SSH Key Pair:**
   ```bash
   ssh-keygen -t rsa -b 4096 -C "your_email@example.com"
   ```
   Follow the prompts and save the keys to the default location (`~/.ssh/id_rsa`).

2. **Add the Public Key to the Remote Server:**
   ```bash
   ssh-copy-id user@yourserver.com
   ```

3. **Add the Private Key to GitHub Secrets:**
   - Display the private key:
     ```bash
     cat ~/.ssh/id_rsa
     ```
   - Copy the output and add it as a secret in your GitHub repository settings:
     - Go to `Settings` > `Secrets and variables` > `Actions`.
     - Click `New repository secret`.
     - Name the secret `SSH_PRIVATE_KEY` and paste the private key content.
     - Click `Add secret`.

### 3. Set Up GitHub Actions Workflow

1. **Create a GitHub Actions Workflow:**
   In your repository, create a directory for GitHub Actions workflows:
   ```bash
   mkdir -p .github/workflows
   ```

2. **Create a Deployment Workflow File:**
   ```bash
   nano .github/workflows/deploy.yml
   ```

3. **Add the Following Workflow Configuration:**

   ```yaml
   name: Deploy to Server

   on:
     push:
       branches:
         - main
     schedule:
       - cron: '0 0 * * 0'  # Runs at 00:00 UTC every Sunday

   jobs:
     deploy:
       runs-on: ubuntu-latest

       steps:
       - name: Checkout code
         uses: actions/checkout@v2

       - name: Set up SSH
         uses: webfactory/ssh-agent@v0.5.3
         with:
           ssh-private-key: ${{ secrets.SSH_PRIVATE_KEY }}

       - name: Sync files with rsync
         run: |
           rsync -avz --delete \
             -e "ssh -o StrictHostKeyChecking=no" \
             ./ user@yourserver.com:/path/to/your/web/directory

       - name: Set permissions (optional)
         run: |
           ssh user@yourserver.com 'chown -R www-data:www-data /path/to/your/web/directory'
   ```

### Explanation of the Workflow

- **Name:** The name of the workflow is "Deploy to Server".
- **On:** Specifies the events that trigger the workflow.
  - **Push:** The workflow runs every time there is a push to the `main` branch.
  - **Schedule:** The workflow runs automatically at a specified interval.
    - **Cron:** The cron expression `0 0 * * 0` means "At 00:00 (midnight) UTC every Sunday".

### Workflow Steps

1. **Checkout Code:**
   - Uses the `actions/checkout@v2` action to check out the repository code.

2. **Set up SSH:**
   - Uses the `webfactory/ssh-agent@v0.5.3` action to set up the SSH agent with the private key stored in the `SSH_PRIVATE_KEY` GitHub secret.

3. **Sync Files with `rsync`:**
   - Uses `rsync` to synchronize the files from the GitHub runner to the remote server.
   - The `-avz` options enable archive mode, verbose output, and compression, respectively.
   - The `--delete` option ensures that files deleted locally are also deleted on the remote server.
   - The `-e` option specifies the remote shell to use for the connection (`ssh` with `StrictHostKeyChecking` disabled).

4. **Set Permissions (Optional):**
   - Sets the appropriate permissions for the files on the remote server, ensuring they are owned by the web server user (e.g., `www-data`).

### Adding the Workflow File

1. **Create the Workflow Directory:**
   If you haven't already, create the `.github/workflows` directory in your repository:
   ```bash
   mkdir -p .github/workflows
   ```

2. **Create the Workflow File:**
   Create a new file named `deploy.yml` in the `.github/workflows` directory and add the above workflow configuration:
   ```bash
   nano .github/workflows/deploy.yml
   ```

3. **Commit and Push the Workflow File:**
   Commit the new workflow file to your repository and push it to GitHub:
   ```bash
   git add .github/workflows/deploy.yml
   git commit -m "Add GitHub Actions workflow for automatic deployment using rsync"
   git push origin main
   ```

GitHub Actions workflow that uses `rsync` to deploy your Raydium project. This method is efficient for updating only the changed files and maintaining file permissions, ensuring a smooth and automated deployment process.
