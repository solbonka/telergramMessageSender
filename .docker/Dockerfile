FROM php:8.1-fpm

RUN apt-get update && apt-get install -y \
        libmcrypt-dev \
        libicu-dev \
        libzip-dev \
    && docker-php-ext-install -j$(nproc) intl \
    && docker-php-ext-install -j$(nproc) zip \
    && docker-php-ext-install sockets

# Install Composer
COPY --from=composer:2.5.8 /usr/bin/composer /usr/local/bin/composer

ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

RUN chmod +x /usr/local/bin/install-php-extensions && sync && \
    install-php-extensions amqp

CMD ["php-fpm"]
