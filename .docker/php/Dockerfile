FROM php:7.1.13-fpm

LABEL maintainer="Jose Barbosa"
ARG INSTALL_XDEBUG="false"
ARG INSTALL_COMPOSER="false"
ARG INSTALL_NODE="false"
ARG INSTALL_GIT="false"
ARG INSTALL_PHPMD="false"
ARG INSTALL_PHPCS="false"

RUN apt-get update && apt-get install -y libmcrypt-dev mysql-client libpng-dev \
    && docker-php-ext-install pdo_mysql mbstring fileinfo gd tokenizer mcrypt

# Install another dependencies
RUN apt-get update && apt-get install -y apt-utils adduser curl nano debconf-utils bzip2 dialog locales-all zlib1g-dev libicu-dev g++ gcc locales make build-essential

# Install openssl extension
RUN apt-get install -y openssl

COPY ./.docker/php/composer-installer.sh /usr/local/bin/composer-installer
COPY ./.docker/php/extras-installer.sh /usr/local/bin/extras-installer
RUN chmod +x /usr/local/bin/extras-installer
RUN extras-installer

# Install mcrypt extension for PHP >= 7.2
#RUN yes | pecl install mcrypt-1.0.1
#RUN docker-php-ext-enable mcrypt

WORKDIR /var/www/forum