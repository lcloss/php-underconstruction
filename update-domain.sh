#!/bin/bash
#
# Use: ./update-domain.sh <domain> <user> <email> <password> <dbname> <dbuser> <dbpasswd>
composer install --no-dev --optimize-autoloader
chown $2:$2 -R public_html
chmod 755 -R public_html
php artesao update:domain $1 $2 'info@'$1 $3 $4 $5 $6