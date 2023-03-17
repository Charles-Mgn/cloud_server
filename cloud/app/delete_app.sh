#!/bin/bash

# Get the file or directory path to delete from the command line argument
username=$1
path=$2

# Delete the file or directory
rm -r "/home/$username/public_html/$path"
