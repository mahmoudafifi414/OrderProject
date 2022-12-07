#!/usr/bin/env bash

cd /var/www
chmod -R 777 storage/
#composer install && composer dump-autoload
php artisan cache:clear
php artisan migrate:fresh
php artisan db:seed

exec "$@"
