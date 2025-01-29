FROM php:8.4-apache

WORKDIR /var/www/html

RUN apt-get update && apt-get install -y \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql pgsql

COPY ./conf/site.conf /etc/apache2/sites-available/site.conf
RUN a2ensite site.conf && a2dissite 000-default.conf
RUN a2enmod rewrite