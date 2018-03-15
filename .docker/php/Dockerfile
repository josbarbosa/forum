FROM php:7.1.13-fpm

LABEL maintainer="Jose Barbosa"

COPY ./.docker/composer-installer.sh /usr/local/bin/composer-installer

# Install composer
RUN apt-get -yqq update \
    && apt-get -yqq install --no-install-recommends apt-utils adduser unzip \
    && chmod +x /usr/local/bin/composer-installer \
    && composer-installer \
    && mv composer.phar /usr/local/bin/composer \
    && chmod +x /usr/local/bin/composer \
    && composer --version

RUN apt-get update && apt-get install -y libmcrypt-dev mysql-client libpng-dev \
    && docker-php-ext-install pdo_mysql mbstring fileinfo gd tokenizer mcrypt

# Install another dependencies
RUN apt-get update && apt-get install -y git apt-utils adduser curl nano debconf-utils bzip2 dialog locales-all zlib1g-dev libicu-dev g++ gcc locales make build-essential

# Install xdebug extension
RUN yes | pecl install xdebug-2.6.0

# Install openssl extension
RUN apt-get install -y openssl

# Install Node.js
RUN curl -sL https://deb.nodesource.com/setup_8.x | bash -
RUN apt-get install -y nodejs build-essential

# Install mcrypt extension for PHP >= 7.2
#RUN yes | pecl install mcrypt-1.0.1
#RUN docker-php-ext-enable mcrypt

WORKDIR /var/www/forum