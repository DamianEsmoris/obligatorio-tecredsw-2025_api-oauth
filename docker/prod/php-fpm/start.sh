#!/bin/bash

if [ -n "${REPO_URL}" ]; then
    git clone --depth=1 ${REPO_URL} /var/www/html
    [ -d /var/www/html/storage ] && chmod -R 757 /var/www/html/storage
fi

[ -z "${SKIP_COMPOSER}" ] \
    && composer install

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
