#!/bin/bash

# Backup all users' apps and databases
for user in /home/*/; do
    # Get the username from the home directory path
    username=$(basename "$user")

    # Skip the backup for root user
    if [ "$username" = "groupe1" ]; then
        continue
    fi

    # Create backup.tar.gz file with database dump and app files in two separate folders.
    # Set paths and filenames for the backup files
    app_path="/home/$username/public_html/"
    backup_folder="/home/$username/backup/"
    log_file="/var/log/monitoring.log"
    timestamp="$(date +%Y%m%d_%H%M%S)"
    db_backup_file="db.sql"
    app_backup_file="app.tar.gz"
    backup_tar_file="backup_$timestamp.tar.gz"

    # Create backup folder if it doesn't exist
    if [ ! -d "$backup_folder" ]; then
        mkdir "$backup_folder"
    fi
    echo "Backup folder created for user $username at $timestamp" >> "$log_file"

    # Get the database associated with the user
    db_name=$(mysql -u root -proot --skip-column-names -e "SELECT schema_name FROM information_schema.schemata WHERE schema_name LIKE '${username}_%';")

    # Create backup file for the database
    if mysqldump -u root -proot "$db_name" > "$backup_folder/$db_backup_file" 2>> "$log_file"; then
        echo "Backup file for the database created for user $username at $timestamp" >> "$log_file"
    else
        echo "ERROR Backup file for the database not created for user $username at $timestamp" >> "$log_file"
        continue
    fi

    # Change to the app path directory to tar the app files from there
    cd "$app_path"

    # Create backup file for the app files
    if tar -czf "$backup_folder/$app_backup_file" .; then
        echo "Backup file for the app files created for user $username at $timestamp" >> "$log_file"
    else
        echo "ERROR Backup file for the app files not created for user $username at $timestamp" >> "$log_file"
        continue
    fi

    # Create backup file for the app files and database
    if tar -czf "$backup_folder/$backup_tar_file" "$backup_folder/$db_backup_file" "$backup_folder/$app_backup_file"; then
        echo "Backup file for the app files and database created for user $username at $timestamp" >> "$log_file"
    else
        echo "ERROR Backup file for the app files and database not created for user $username at $timestamp" >> "$log_file"
        continue
    fi

    # Delete db.sql and app.tar.gz files
    rm "$backup_folder/$db_backup_file" "$backup_folder/$app_backup_file"
done
