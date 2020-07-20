FROM php:7.4-cli-alpine

ENV \
    COMPOSER_ALLOW_SUPERUSER="1" \
    COMPOSER_HOME="/tmp/composer"

COPY --from=composer:1.10.8 /usr/bin/composer /usr/bin/composer

RUN set -x \
    && apk add --no-cache binutils git redis libintl icu-dev postgresql-dev gettext-dev \
    && apk add --no-cache --virtual .build-deps \
        autoconf \
        pkgconf \
        make g++ \
        gcc \
        libxml2-dev \
        oniguruma-dev 1>/dev/null \
    # install xdebug (for testing with code coverage), but do not enable it
    && pecl install xdebug-2.9.6 1>/dev/null \
    && pecl install redis-5.3.1 1>/dev/null \
    && docker-php-ext-enable redis \
    && docker-php-ext-configure mbstring --enable-mbstring \
    && docker-php-ext-configure opcache --enable-opcache \
    && docker-php-ext-configure bcmath --enable-bcmath \
    && docker-php-ext-configure pcntl --enable-pcntl \
    && docker-php-ext-configure intl --enable-intl \
    && docker-php-ext-configure pgsql \
    && docker-php-ext-install -j$(nproc) \
        pdo \
        pdo_pgsql \
        pgsql \
        mbstring \
        sockets \
        gettext \
        opcache \
        bcmath \
        pcntl \
        intl \
    && apk del .build-deps \
    && mkdir /src ${COMPOSER_HOME} \
    && composer global require 'hirak/prestissimo' --no-interaction --no-suggest --prefer-dist \
    && ln -s /usr/bin/composer /usr/bin/c \
    && chmod -R 777 ${COMPOSER_HOME}

COPY ./docker/php/opcache.ini /usr/local/etc/php/conf.d/opcache.ini
COPY ./docker/app-entrypoint.sh /app-entrypoint.sh

WORKDIR /app

COPY ./composer.* /app/

RUN set -xe \
    && composer install --no-interaction --no-ansi --no-suggest --prefer-dist  --no-autoloader --no-scripts \
    && composer install --no-dev --no-interaction --no-ansi --no-suggest --prefer-dist  --no-autoloader --no-scripts


COPY . /app

COPY --from=spiralscout/roadrunner:1.8.2 /usr/bin/rr /usr/bin/rr

RUN set -xe \
    && chmod +x /app-entrypoint.sh \
    && composer --version \
    && php -v \
    && php -m \
    && rr -h \
    && composer dump

EXPOSE 8000
VOLUME ["/app"]

# DO NOT OVERRIDE ENTRYPOINT IF YOU CAN AVOID IT! @see <https://github.com/docker/docker.github.io/issues/6142>
ENTRYPOINT ["/app-entrypoint.sh"]
CMD ["rr", "serve", "-c", "/app/.rr.yaml"]
