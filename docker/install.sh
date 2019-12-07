#!/usr/bin/env bash
mkdir -p /var/www/louvre/var/logs
mkdir -p /var/www/louvre/var/cache
mkdir -p /var/www/louvre/var/sessions

yarn install
composer install -o --no-scripts --no-progress --no-suggest --apcu-autoloader
yarn build
