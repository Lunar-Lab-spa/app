#!/bin/bash
php bin/console cache:clear
echo session.cookie_domain=.$HOST > /usr/local/etc/php/conf.d/session.ini
echo session.cookie_lifetime=$SESSION_LIFETIME >> /usr/local/etc/php/conf.d/session.ini
echo session.gc_maxlifetime=$SESSION_LIFETIME >> /usr/local/etc/php/conf.d/session.ini
mkdir -p /app/var
chmod -R 777 /app/var/
php-fpm