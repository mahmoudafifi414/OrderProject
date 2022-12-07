#!/usr/bin/env bash

cd /var/www
php artisan key:generate
chmod -R 777 storage/
php artisan cache:clear
php artisan migrate:fresh
php artisan db:seed

exec "$@"
