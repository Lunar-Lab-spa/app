FROM node:alpine as assets
WORKDIR /app
COPY public .
COPY assets assets
COPY package.json .
COPY webpack.config.js .
RUN npm install && npm run build

FROM php:8.2.8-fpm as php
ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/
RUN chmod +x /usr/local/bin/install-php-extensions && apt-get update && apt-get install -y git
WORKDIR /app
COPY --from=composer:latest /usr/bin/composer /usr/local/bin
COPY --from=assets /app/public app/public
COPY . .
RUN install-php-extensions intl opcache apcu xdebug mongodb zip 
RUN composer install --no-dev --no-scripts --optimize-autoloader

FROM nginx:alpine as nginx
COPY nginx/default.conf /etc/nginx/conf.d/default.conf
COPY nginx/nginx.conf /etc/nginx/nginx.conf
WORKDIR /app
COPY --from=php --link /app/public public/