#!/bin/bash

DBNAME=$1
DBUSER=$2

mysql -u root -p"root" << EOF
CREATE DATABASE $DBNAME;
GRANT ALL PRIVILEGES ON $DBNAME.* TO '$DBUSER'@'localhost';
FLUSH PRIVILEGES;
EOF

echo "Database created."
