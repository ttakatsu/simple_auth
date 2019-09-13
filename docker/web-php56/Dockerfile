FROM php:5.6-apache

ENV LANG C.UTF-8
RUN set -x && \
    apt-get -y update && \
    apt-get install -y libmcrypt-dev libzip-dev && \
    pecl channel-update pecl.php.net && \
    pecl install apcu-4.0.11 redis-4.3.0 && \
    docker-php-ext-enable apcu redis && \
    NPROC=$(grep -c ^processor /proc/cpuinfo 2>/dev/null || 1) && \
    docker-php-ext-install -j${NPROC} pdo_mysql mcrypt zip && \
    rm -rf /tmp/pear

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Apache rewrite module enable
RUN a2enmod rewrite

COPY ./php /usr/local/etc/php

WORKDIR /var/www
