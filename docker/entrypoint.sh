#!/bin/bash
php bin/console cache:clear
echo session.cookie_domain=.$HOST >> /usr/local/etc/php/php.ini
php-fpm