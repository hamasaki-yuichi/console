FROM php:fpm-buster

COPY php.ini /usr/local/etc/php/php.ini

RUN apt-get update \
    && apt-get -y install git zip unzip vim

RUN docker-php-ext-install pdo_mysql

# install laravel composer.
RUN cd /usr/bin && curl -s http://getcomposer.org/installer | php && ln -s /usr/bin/composer.phar /usr/bin/composer

# install node, npm.
RUN apt-get install nodejs npm

WORKDIR /var/www/html