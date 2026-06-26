#!/bin/sh
chown -R www:www-data /var/www/storage /var/www/bootstrap/cache
chmod -R 775 /var/www/storage /var/www/bootstrap/cache

php artisan config:clear
php artisan cache:clear
php artisan view:clear

exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
