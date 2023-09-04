FROM node:alpine as assets
WORKDIR /app
COPY public .
COPY assets assets
COPY package.json .
COPY webpack.config.js .
RUN npm install && npm run build


FROM php:8.2.8-fpm as php
ARG ENV=dev
ENV COMPOSER_ALLOW_SUPERUSER=1
ENV composer_dev="composer install --no-interaction --optimize-autoloader --no-scripts"
ENV composer_prod="$composer_dev --no-dev"
ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/
RUN chmod +x /usr/local/bin/install-php-extensions && apt-get update && apt-get install -y git unzip && \
    apt-get clean && apt-get autoremove && rm -rf /var/lib/apt/lists/*
WORKDIR /app
COPY --from=composer:latest /usr/bin/composer /usr/local/bin
COPY --from=assets /app/public app/public
COPY . .
RUN install-php-extensions intl opcache apcu xdebug mongodb
RUN if [ "$ENV" = "prod" ]; then $composer_prod; else $composer_dev; fi
RUN APP_ENV=$ENV php bin/console cache:clear

FROM nginx:alpine as nginx
COPY nginx/default.conf /etc/nginx/conf.d/default.conf
COPY nginx/nginx.conf /etc/nginx/nginx.conf
WORKDIR /app
COPY --from=assets /app/public public/