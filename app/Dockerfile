ARG BUILD_TARGET

FROM php:8.2.12-fpm-alpine AS base

ARG BUILD_TARGET

ENV BASE_DIR /var/www
ENV CACHE_DIR $BASE_DIR/cache
ENV CODE_DIR $BASE_DIR/code
ENV COMPOSER_VENDOR_DIR $BASE_DIR/vendor
ENV LOGS_DIR $BASE_DIR/logs
ENV DIRS $CACHE_DIR $CODE_DIR $COMPOSER_VENDOR_DIR $LOGS_DIR

ENV COMPOSER_ALLOW_SUPERUSER 1
ENV COMPOSER_INSTALL_OPTIONS --no-interaction --no-scripts --no-cache

ENV ENV ${BUILD_TARGET}
ENV PATH $PATH:$CODE_DIR/bin:$COMPOSER_VENDOR_DIR/bin

FROM base AS dev

FROM base AS prod

ENV COMPOSER_INSTALL_OPTIONS $COMPOSER_INSTALL_OPTIONS --no-dev --optimize-autoloader --classmap-authoritative

FROM ${BUILD_TARGET}

RUN curl -sS https://getcomposer.org/installer | php -- \
        --version=2.6.5 \
        --install-dir=/usr/local/bin \
        --filename=composer && \
    curl -sSLf \
        -o /usr/local/bin/install-php-extensions \
        https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions && \
    chmod +x \
            /usr/local/bin/install-php-extensions && \
    install-php-extensions pdo pdo_pgsql && \
    mkdir -p $DIRS && \
    chown -R www-data:www-data $BASE_DIR/*

WORKDIR $CODE_DIR
USER www-data

COPY --chown=www-data ./ ./

RUN composer install $COMPOSER_INSTALL_OPTIONS && \
    console cache:warmup
