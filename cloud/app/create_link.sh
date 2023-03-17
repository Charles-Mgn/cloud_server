#!/bin/bash

# Script to create an nginx virtual host for a user

# Check if user has been provided as an argument
if [ -z "$1" ]
  then
    echo "No username provided"
    exit 1
fi

# Set the username variable
username=$1

# Set the target directory to the user's home directory
targetDir="/home/$username"

# Check if target directory exists
if [ ! -d "$targetDir" ]; then
  echo "Target directory does not exist: $targetDir"
  exit 1
fi

# Set the config directory and file paths
configDir="/etc/nginx/sites-available"
configFile="$configDir/$username"

# Check if config directory exists
if [ ! -d "$configDir" ]; then
  echo "Config directory does not exist: $configDir"
  exit 1
fi

# Check if config file exists
if [ ! -f "$configFile" ]; then
  echo "Config file does not exist: $configFile"
  exit 1
fi

# Enable the new virtual host
ln -s "$configFile" "/etc/nginx/sites-enabled/"

# Test the nginx configuration
nginx -t

# If there are no syntax errors in the config, reload nginx
if [ $? -eq 0 ]; then
  systemctl reload nginx
  echo "Nginx reloaded successfully"
else
  echo "There are errors in the nginx config. Please fix and try again."
fi
