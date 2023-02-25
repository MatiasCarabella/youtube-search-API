FROM php:8.2.3

RUN docker-php-ext-install pdo pdo_mysql sockets
RUN curl -sS https://getcomposer.org/installer​ | php -- \
     --install-dir=/usr/local/bin --filename=composer

COPY --from=composer:2.5.4 /usr/bin/composer /usr/bin/composer

WORKDIR /youtube-search-api
COPY . .
RUN composer install