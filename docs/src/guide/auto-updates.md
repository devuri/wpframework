# Automating Updates

Keeping your Raydium-based projects up to date is essential for maintaining security, compatibility, and performance. You can automate this process by setting up a cron job to run the `composer update` command daily at midnight. Follow these steps to configure the cron job for your Raydium project, with an optional setup for daily logging.

## Step-by-Step Guide

### 1. Open the Crontab File for Editing

Open the crontab file to create or edit cron jobs for your user:

```bash
crontab -e
```

### 2. Add the Cron Job Entry

Add the following line to the crontab file to schedule the Composer update command:

```bash
0 0 * * * cd /path/to/your/raydium/project && composer update
```

### Explanation

- `0 0 * * *` – This schedule specifies "run at minute 0 of hour 0 every day", which is midnight.
- `cd /path/to/your/raydium/project` – This command changes the directory to where your Raydium project is located. Make sure to replace `/path/to/your/raydium/project` with the actual path.
- `composer update` – This command runs the Composer update command, which updates the dependencies (plugins, themes, WordPress, and Raydium core) of your project to their latest versions.

### 3. Save and Exit

After adding the line, save the changes and exit the editor. The method to save and exit depends on the text editor you are using (e.g., `:wq` for vi/vim).

## Optional: Log Setup

To keep a comprehensive log of the updates, you can set up daily logging with a structured directory. Here's how to configure it:

### Step-by-Step Guide for Logging

#### 1. Create a Directory for Logs

Create a base directory to store the log files if it doesn't already exist:

```bash
mkdir -p /path/to/your/logs
```

#### 2. Add the Cron Job Entry with Daily Logging

Modify the cron job to log the output to a daily log file within a structured directory:

```bash
0 0 * * * cd /path/to/your/raydium/project && mkdir -p /path/to/your/logs/$(date +\%Y/\%m) && composer update >> /path/to/your/logs/$(date +\%Y/\%m/rd-update-\%Y-\%m-\%d).log 2>&1
```

#### Explanation

- `mkdir -p /path/to/your/logs/$(date +\%Y/\%m)` – Creates a directory structure based on the current year and month.
- `>> /path/to/your/logs/$(date +\%Y/\%m/rd-update-\%Y-\%m-\%d).log 2>&1` – Appends the output to a log file named with the current date, stored within the structured directory.

### Example Directory Structure

Here is an example of what the directory structure will look like:

```
/path/to/your/logs/
├── 2017/
│   ├── 01/
│   │   ├── rd-update-2017-01-01.log
│   │   ├── rd-update-2017-01-02.log
│   │   ├── rd-update-2017-01-03.log
│   │   └── ...
│   ├── 02/
│   │   ├── rd-update-2017-02-01.log
│   │   ├── rd-update-2017-02-02.log
│   │   ├── rd-update-2017-02-03.log
│   │   └── ...
│   └── ...
├── 2018/
│   ├── 01/
│   │   ├── rd-update-2018-01-01.log
│   │   ├── rd-update-2018-01-02.log
│   │   ├── rd-update-2018-01-03.log
│   │   └── ...
│   ├── 02/
│   │   ├── rd-update-2018-02-01.log
│   │   ├── rd-update-2018-02-02.log
│   │   ├── rd-update-2018-02-03.log
│   │   └── ...
│   └── ...
```

### Routine Maintenance

Since logs will accumulate over time, it's a good practice to periodically clean up the log directory to avoid excessive storage use. You can manually purge old logs yearly or set up an additional cron job for this purpose.

Example command to delete logs older than one year:

```bash
find /path/to/your/logs -type f -mtime +365 -name 'rd-update-*.log' -exec rm {} \;
```

### Adding a Yearly Cleanup Cron Job (Optional)

To automate the cleanup process, add the following line to your crontab file:

```bash
0 0 1 1 * find /path/to/your/logs -type f -mtime +365 -name 'rd-update-*.log' -exec rm {} \;
```

#### Explanation

- `0 0 1 1 *` – This schedule specifies "run at minute 0 of hour 0 on January 1st every year".
- `find /path/to/your/logs -type f -mtime +365 -name 'rd-update-*.log' -exec rm {} \;` – Finds and deletes log files older than one year.

## Verifying the Cron Job

To verify that your cron job has been added successfully, you can list all the cron jobs for your user with the following command:

```bash
crontab -l
```

You should see your newly added line among the listed cron jobs.



> With this cron job in place, your Raydium project will automatically update daily at midnight, ensuring that you always have the latest improvements and fixes. This helps in maintaining a secure and efficiently running application without manual intervention. If you choose to enable logging, you will also have a comprehensive record of updates, with an optional yearly cleanup to manage file sizes effectively.
