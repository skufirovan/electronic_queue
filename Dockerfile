FROM php:apache

# Установка необходимых инструментов и пакетов
RUN apt-get update && apt-get install -y \
    libmemcached-dev \
    zlib1g-dev \
    libssl-dev \
    pkg-config \
    libcurl4-openssl-dev \
    libevent-dev \
    git \
    unzip \
    autoconf \
    && pecl uninstall memcached && pecl install memcached \
    && docker-php-ext-enable memcached

# Установка расширения mysqli
RUN docker-php-ext-install mysqli