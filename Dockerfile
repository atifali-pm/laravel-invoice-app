#FROM ubuntu:20.04
#
## Install necessary tools
#RUN apt-get update && apt-get install -y \
#    software-properties-common \
#    wget
#
## Add KeyDB repository
#RUN echo "deb https://download.keydb.dev/ubuntu/ focal main" >> /etc/apt/sources.list.d/keydb.list
#
## Import KeyDB GPG key
#RUN wget -O /etc/apt/trusted.gpg.d/keydb.gpg https://download.keydb.dev/ubuntu/keyring.gpg
#
## Install KeyDB
#RUN apt-get update && apt-get install -y keydb
FROM php:8.2.0-apache
WORKDIR /var/www/html

# Mod Rewrite
RUN a2enmod rewrite

# Linux Library
RUN apt-get update -y && apt-get install -y \
    libicu-dev \
    libmariadb-dev \
    unzip zip \
    zlib1g-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# PHP Extension
RUN docker-php-ext-install gettext intl pdo_mysql gd

RUN docker-php-ext-configure gd --enable-gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd