#!/bin/bash

[ -z "${SKIP_COMPOSER}" ] \
    && composer install

[ -z "${SKIP_CACHE_DELETION}" ] \
    && php artisan cache:clear

if [ -z "${SKIP_CONFIG_CACHE_DELETION}" ]; then
    php artisan config:clear
    php artisan config:cache
fi

[ -z "${SKIP_MIGRATIONS}" ] \
    && php artisan migrate

[ -z "${SKIP_SEEDERS}" ] \
    && php artisan db:seed

[ -z "${STORAGE_LINK}" ] \
    && php artisan storage:link

if [ ! -f .env ]
then
   cp .env.example .env
   php artisan key:generate
fi

php artisan passport:keys

php-fpm -F -y /etc/php-fpm.conf
