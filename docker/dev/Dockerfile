FROM php:7.4-fpm

RUN set -x \
    && apt-get update \
    && apt-get install -y libcurl4-openssl-dev curl libzip-dev zlib1g-dev libpq-dev zip unzip openssh-client git wget  \
    && docker-php-ext-install -j$(nproc) curl zip \
    && pecl install xdebug-2.8.0 pcov redis \
    && docker-php-ext-enable xdebug \
    && docker-php-ext-enable pcov \
    && docker-php-ext-enable redis \
    && docker-php-ext-install pdo pdo_pgsql

RUN pecl install zip \
    && php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php --install-dir=/usr/local/bin --filename=composer \
    && php -r "unlink('composer-setup.php');"

WORKDIR /src

COPY docker/dev/docker-php-ext-xdebug.ini /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
COPY docker/dev/fpm.conf /usr/local/etc/php-fpm.d/www.conf
COPY docker/prod/php.ini "$PHP_INI_DIR/php.ini"
