#!/bin/bash

if [ $# -eq 0 ]
  then
    echo "No arguments supplied. Usage: ./create_user.sh username password"
    exit 1
fi

# Get username and password from command line arguments
username=$1
password=$2

# Create new user and set password
useradd -m $username
echo "$username:$password" | chpasswd

# Create new user inside MariaDB
mysql -u root -p"root" << EOF
CREATE USER '$username'@'localhost' IDENTIFIED BY '$password';
EOF

# Create Nginx config file for new website
cat << EOF > /etc/nginx/sites-available/$username
server {
    listen 80;
    server_name $username.example.com;

    root /home/$username;
    index index.html index.htm index.php;

    location / {
        try_files \$uri \$uri/ /index.php?\$args;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/var/run/php/php7.4-fpm.sock;
    }
}
EOF

# Create symlink to enable the new website
ln -s /etc/nginx/sites-available/$username /etc/nginx/sites-enabled/

# Test Nginx configuration and reload if successful
if nginx -t; then
    systemctl reload nginx
else
    echo "Error: Nginx configuration test failed. Rolling back changes."
    rm /etc/nginx/sites-enabled/$username
    rm /etc/nginx/sites-available/$username
    userdel -r $username
    exit 1
fi

# Create symbolic link to the user's home directory
ln -s /home/$username /var/www/html/$username
