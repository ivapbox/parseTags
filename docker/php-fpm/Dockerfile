FROM php:7.2-fpm-stretch

RUN usermod -u 1000 www-data && groupmod -g 1000 www-data

RUN apt-get update && \
    apt-get install -y --no-install-recommends \
        curl \
        wget \
        unzip \
        libpq-dev \
        zlib1g-dev \
        git \
        dnsutils \
        libxml2-dev \
        libpng-dev \
        && apt-get clean \
        && rm -r /var/lib/apt/lists/*

RUN docker-php-ext-install pdo_pgsql pgsql pdo_pgsql zip xml gd mbstring

COPY --from=composer:1.10 /usr/bin/composer /usr/bin/composer
RUN pecl install xdebug-2.6.1

COPY ./docker/php-fpm/common.ini /usr/local/etc/php/conf.d
RUN chown www-data:www-data /usr/local/etc/php/conf.d

COPY ./docker/php-fpm/entrypoint.sh /tmp/entrypoint.sh
RUN chmod 755 /tmp/entrypoint.sh

COPY ./docker/php-fpm/dev.ini /tmp/dev.ini

COPY src /var/www/app
RUN chown -R www-data:www-data /var/www/app

WORKDIR /var/www/app

ENTRYPOINT ["/tmp/entrypoint.sh"]
CMD ["php-fpm"]
