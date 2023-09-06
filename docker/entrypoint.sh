#!/bin/bash
php bin/console cache:clear
echo session.cookie_domain=.$HOST > /usr/local/etc/php/conf.d/session.ini
php-fpm