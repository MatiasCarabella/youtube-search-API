FROM php:8.2.3

RUN apt update
RUN apt install -y libzip-dev
RUN docker-php-ext-install zip
RUN docker-php-ext-install pdo pdo_mysql sockets
RUN curl -sS https://getcomposer.org/installer​ | php -- \
     --install-dir=/usr/local/bin --filename=composer

COPY --from=composer:2.5.4 /usr/bin/composer /usr/bin/composer
ENV COMPOSER_ALLOW_SUPERUSER=1

WORKDIR /youtube-search-api
COPY . .
RUN composer install