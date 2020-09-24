FROM php:7.4-apache

RUN docker-php-ext-install pdo_mysql \
&& a2enmod rewrite

COPY .docker/php-apache/php.ini /usr/local/etc/php/

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN apt-get update -y && apt-get install -y -qq git \
	apt-transport-https \
	libzip-dev \
	zip unzip \
	wget \
	nano
