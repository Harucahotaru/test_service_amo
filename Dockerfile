FROM php:8.1-fpm

RUN apt update && apt upgrade -y && apt install git -y

COPY . /var/www/test-service
WORKDIR /var/www/test-service

COPY --from=composer:2.4 /usr/bin/composer /usr/bin/composer

RUN composer install
