FROM php:8.4-apache

WORKDIR /var/www/html

# Install extentions for php
RUN apt-get update && apt-get install -y \
    git \
    zip \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql pgsql

# Config for Apache2
COPY ./conf/site.conf /etc/apache2/sites-available/site.conf
RUN a2ensite site.conf && a2dissite 000-default.conf
RUN a2enmod rewrite

# Getting the latest config of composer
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

COPY ./files/entrypoint.sh /image/
RUN chmod +x /image/entrypoint.sh
ENTRYPOINT [ "sh", "/image/entrypoint.sh" ]