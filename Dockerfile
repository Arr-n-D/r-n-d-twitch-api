FROM php:8.3-fpm-alpine

# set working directory
WORKDIR /var/www/html

# we need git to install composer
RUN apk update && apk add git \
    bash \
    libzip-dev \
    nginx \
    postgresql-dev \
    libpng-dev \
    # sqlite
    sqlite-dev

# install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# install php extensions
RUN docker-php-ext-install pdo pdo_pgsql opcache pcntl gd bcmath posix zip pdo_sqlite

# edit nginx.conf with sed
RUN sed -i 's/user nginx;/user www www;/' /etc/nginx/nginx.conf
# edit php-fpm.conf with sed to use www user and group
RUN sed -i 's/user = www-data/user = www/' /usr/local/etc/php-fpm.d/www.conf
RUN sed -i 's/group = www-data/group = www/' /usr/local/etc/php-fpm.d/www.conf


RUN addgroup -g 1000 -S www && \
    adduser -u 1000 -S www -G www

COPY --chown=www:www . /var/www/html

USER www

RUN composer install -q --no-ansi --no-interaction --no-scripts --no-progress --prefer-dist && composer dump-autoload




