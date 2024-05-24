# SSH Keys for GitHub Actions

## Generating and Adding SSH Keys for GitHub Actions Deployment

To set up SSH keys for your deployment, follow these steps. You will generate a new SSH key pair, add the public key to your remote server, and add the private key to your GitHub repository secrets.

### Step 1: Generate SSH Key Pair

1. **Open a Terminal:**
   Open a terminal on your local machine.

2. **Generate the SSH Key Pair:**
   Use the `ssh-keygen` command to create a new SSH key pair. Replace `your_email@example.com` with your email address.
   ```bash
   ssh-keygen -t rsa -b 4096 -C "your_email@example.com"
   ```

3. **Follow the Prompts:**
   - When prompted to "Enter file in which to save the key," press Enter to accept the default location (`~/.ssh/id_rsa`).
   - Optionally, enter a passphrase for added security.

### Step 2: Add the Public Key to Your Remote Server

1. **Copy the Public Key:**
   Display the public key using the following command and copy its output.
   ```bash
   cat ~/.ssh/id_rsa.pub
   ```

2. **Log in to Your Remote Server:**
   ```bash
   ssh user@yourserver.com
   ```

3. **Add the Public Key to the Remote Server:**
   Append the copied public key to the `~/.ssh/authorized_keys` file on your remote server.
   ```bash
   echo "your-copied-public-key" >> ~/.ssh/authorized_keys
   ```

4. **Set Permissions (Optional):**
   Ensure the `authorized_keys` file has the correct permissions.
   ```bash
   chmod 600 ~/.ssh/authorized_keys
   ```

### Step 3: Add the Private Key to GitHub Secrets

1. **Display the Private Key:**
   Use the following command to display your private key. Copy the output, which you will add to GitHub.
   ```bash
   cat ~/.ssh/id_rsa
   ```

2. **Add the Private Key to GitHub:**
   - Go to your GitHub repository.
   - Navigate to `Settings` > `Secrets and variables` > `Actions`.
   - Click on `New repository secret`.
   - Name the secret `SSH_PRIVATE_KEY` and paste the private key content you copied earlier.
   - Click `Add secret`.

### Step 4: Verify SSH Key Access

1. **Test SSH Access from Local Machine:**
   Before using the key in GitHub Actions, verify that you can SSH into your remote server using the generated key.
   ```bash
   ssh -i ~/.ssh/id_rsa user@yourserver.com
   ```

2. **Troubleshoot if Necessary:**
   If you encounter issues, check the following:
   - Ensure the `authorized_keys` file on the server contains the correct public key.
   - Verify file permissions for `~/.ssh` and `authorized_keys` are correct.
   - Confirm that your server’s SSH configuration allows key-based authentication.

### Using the SSH Key in GitHub Actions

With your SSH keys set up, you can now use them in your GitHub Actions workflow to automate deployment. Here's the relevant section of your GitHub Actions workflow file to use the SSH key:

```yaml
name: Deploy to Server

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

    - name: Set up SSH
      uses: webfactory/ssh-agent@v0.5.3
      with:
        ssh-private-key: ${{ secrets.SSH_PRIVATE_KEY }}

    - name: Deploy to Server
      run: |
        ssh user@yourserver.com 'cd /path/to/bare-repo/repo.git && git pull origin main'
```

This will securely set up SSH keys for deploying your Raydium project using GitHub Actions.
This ensures that your deployment process is both secure and automated.
