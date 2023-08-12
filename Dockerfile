FROM node:alpine as assets
WORKDIR /app
COPY public .
COPY assets assets
COPY package.json .
COPY webpack.config.js .
RUN npm install && npm run build

FROM php:8.2.8-fpm
ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/
RUN chmod +x /usr/local/bin/install-php-extensions && apt-get update && apt-get install -y git acl procps
WORKDIR /app
COPY --from=composer:latest /usr/bin/composer /usr/local/bin
COPY --from=assets /app/public app/public
COPY . .
RUN install-php-extensions intl opcache apcu xdebug mongodb 
RUN chmod -R +w /app/var/cache && composer install --no-dev --no-scripts --optimize-autoloader
RUN HTTPDUSER=$(ps axo user,comm | grep -E '[a]pache|[h]ttpd|[_]www|[w]ww-data|[n]ginx' | grep -v root | head -1 | cut -d\  -f1) \
    setfacl -dR -m u:"$HTTPDUSER":rwX -m u:$(whoami):rwX var \
    setfacl -R -m u:"$HTTPDUSER":rwX -m u:$(whoami):rwX var