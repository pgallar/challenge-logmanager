FROM php:8.1-fpm

WORKDIR /app

RUN apt-get update && apt-get install -y \
    git \
    curl \
    libmcrypt-dev \
    libzip-dev \
    zip \
    unzip \
    python-dev

RUN docker-php-ext-install pdo_mysql zip sockets

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY . /app

RUN composer install

RUN apt-get install -y supervisor

COPY .docker/supervisor/laravel.conf /etc/supervisor/conf.d

EXPOSE 8000
CMD ["/app/.docker/start.sh"]