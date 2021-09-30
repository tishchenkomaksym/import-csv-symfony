FROM php:8.0-fpm

#RUN apt update \
#    && apt install -y zlib1g-dev g++ git libicu-dev zip libzip-dev zip \
#    && docker-php-ext-install intl opcache pdo pdo_mysql \
#    && pecl install apcu \
#    && docker-php-ext-enable apcu \
#    && docker-php-ext-configure zip \
#    && docker-php-ext-install zip

RUN apt-get update -y
RUN apt-get install -y \
git \
curl \
libz-dev \
libcurl4-openssl-dev \
libxml2-dev \
libssl-dev \
libpng-dev \
libonig-dev \
libmpdec-dev \
libzip-dev \
libevent-dev \
libicu-dev


# PHP extension
RUN docker-php-ext-install intl
RUN docker-php-ext-install bcmath
RUN docker-php-ext-install calendar
RUN docker-php-ext-install pcntl
RUN docker-php-ext-install gd
RUN docker-php-ext-install sockets
RUN docker-php-ext-install zip
RUN pecl install igbinary && docker-php-ext-enable igbinary
RUN docker-php-ext-install  opcache pdo pdo_mysql
RUN pecl install xdebug && docker-php-ext-enable xdebug

WORKDIR /var/www/html

RUN cp docker/php-fpm/php.ini /usr/local/etc/php/
#RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN apt update && apt install bash
RUN curl -sS https://get.symfony.com/cli/installer | bash
RUN mv /root/.symfony/bin/symfony /usr/local/bin/symfony
#RUN git config --global user.email "you@example.com" \
#    && git config --global user.name "Your Name"