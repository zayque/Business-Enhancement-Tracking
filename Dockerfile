FROM php:8.3-apache

RUN docker-php-ext-install pdo_mysql \
    && echo "date.timezone=Asia/Kuala_Lumpur" > /usr/local/etc/php/conf.d/timezone.ini \
    && echo "expose_php=Off" > /usr/local/etc/php/conf.d/security.ini

COPY . /var/www/html/
