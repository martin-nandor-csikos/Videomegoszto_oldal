FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    libzip-dev \
    unzip \
    wget \
    libaio1 \
    libaio-dev \
    && docker-php-ext-install zip

RUN a2enmod rewrite

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN composer require php-ffmpeg/php-ffmpeg

RUN mkdir /opt/oracle
# Install Oracle Instantclient
RUN wget https://download.oracle.com/otn_software/linux/instantclient/216000/instantclient-basic-linux.x64-21.6.0.0.0dbru.zip \
    && wget https://download.oracle.com/otn_software/linux/instantclient/216000/instantclient-sdk-linux.x64-21.6.0.0.0dbru.zip \
    && wget https://download.oracle.com/otn_software/linux/instantclient/216000/instantclient-sqlplus-linux.x64-21.6.0.0.0dbru.zip \
    && unzip instantclient-basic-linux.x64-21.6.0.0.0dbru.zip -d /opt/oracle \
    && unzip instantclient-sdk-linux.x64-21.6.0.0.0dbru.zip -d /opt/oracle \
    && unzip instantclient-sqlplus-linux.x64-21.6.0.0.0dbru.zip -d /opt/oracle \
    && rm -rf *.zip \
    && mv /opt/oracle/instantclient_21_6 /opt/oracle/instantclient

#add oracle instantclient path to environment
ENV LD_LIBRARY_PATH /opt/oracle/instantclient/
RUN ldconfig

# Install Oracle extensions
RUN docker-php-ext-configure pdo_oci --with-pdo-oci=instantclient,/opt/oracle/instantclient,21.1 \
    && echo 'instantclient,/opt/oracle/instantclient/' | pecl install oci8 \
    && docker-php-ext-install \
    pdo_oci \
    && docker-php-ext-enable \
    oci8

ENV APACHE_DOCUMENT_ROOT /var/www/html/public

COPY php.ini /usr/local/etc/php

COPY . /var/www/html

WORKDIR /var/www/html
