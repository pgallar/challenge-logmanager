FROM php:8.1-fpm

WORKDIR /app

RUN apt-get update && apt-get install -y \
    git \
    curl \
    libmcrypt-dev \
    libzip-dev \
    zip \
    unzip

RUN docker-php-ext-install pdo_mysql zip sockets

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY . /app

RUN composer install

EXPOSE 8000
CMD ["php-fpm"]