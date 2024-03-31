FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    libzip-dev \
    unzip \
    && docker-php-ext-install zip

RUN a2enmod rewrite

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN composer require php-ffmpeg/php-ffmpeg

ENV APACHE_DOCUMENT_ROOT /var/www/html/public

COPY . /var/www/html

WORKDIR /var/www/html
