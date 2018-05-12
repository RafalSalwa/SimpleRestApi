#!/usr/bin/env bash

set -e

if [ "${1:0:1}" = '-' ]; then
	set -- php-fpm "$@"
fi

cd /var/www && cp app/config/parameters.yml.dist app/config/parameters.yml
cd /var/www && SYMFONY_ENV=dev composer install -n --no-scripts
cd /var/www && SYMFONY_ENV=dev bin/console doctrine:schema:drop --force -n
cd /var/www && SYMFONY_ENV=dev composer install -n -o
cd /var/www && SYMFONY_ENV=dev bin/console doctrine:schema:update --force -n
cd /var/www && SYMFONY_ENV=dev bin/console doctrine:fix:load -n

cd /var/www && chmod -R 777 var


exec "$@"