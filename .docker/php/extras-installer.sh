#!/bin/sh

set -e

# Install Composer
if [ "$INSTALL_COMPOSER" = "true" ]
then
    printf "\nInstalling Composer ...\n"
    apt-get -yqq update \
    && apt-get -yqq install --no-install-recommends apt-utils adduser unzip \
    && chmod +x /usr/local/bin/composer-installer \
    && composer-installer \
    && mv composer.phar /usr/local/bin/composer \
    && chmod +x /usr/local/bin/composer \
    && composer --version
fi

if [ "$INSTALL_GIT" = "true" ]
then
    printf "\nInstalling Git ...\n"
    apt-get update && apt-get install -y git
fi

# Install Xdebug
if [ "$INSTALL_XDEBUG" = "true" ]
then
    printf "\nInstalling Xdebug ...\n"
    pecl install xdebug-2.6.0
fi

# Install Node
if [ "$INSTALL_NODE" = "true" ]
then
    printf "\nInstalling Node ...\n"
    curl -sL https://deb.nodesource.com/setup_8.x | bash -
    apt-get install -y nodejs build-essential
fi

# Install PHPMD
if [ "$INSTALL_PHPMD" = "true" ]
then
    printf "\nInstalling PHPMD ...\n"
    curl -O http://static.phpmd.org/php/2.6.0/phpmd.phar && mv phpmd.phar /usr/local/bin/phpmd \
    && chmod +x /usr/local/bin/phpmd
fi

if [ "$INSTALL_PHPCS" = "true" ]
then
    printf "\nInstalling PHPCS ...\n"
    curl -OL https://squizlabs.github.io/PHP_CodeSniffer/phpcs.phar && mv phpcs.phar /usr/local/bin/phpcs \
    && chmod +x /usr/local/bin/phpcs
fi

exec "$@"