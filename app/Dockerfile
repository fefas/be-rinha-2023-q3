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
ENV COMPOSER_DUMP_AUTOLOAD_OPTIONS --no-scripts

ENV ENV ${BUILD_TARGET}
ENV PATH $PATH:$CODE_DIR/bin:$COMPOSER_VENDOR_DIR/bin

FROM base AS dev

FROM ${BUILD_TARGET}

RUN curl -sS https://getcomposer.org/installer | php -- \
        --version=2.6.5 \
        --install-dir=/usr/local/bin \
        --filename=composer && \
    mkdir -p $DIRS

WORKDIR $CODE_DIR

COPY ./composer* ./symfony.lock ./
RUN composer install $COMPOSER_INSTALL_OPTIONS

COPY ./ ./
RUN composer dump-autoload $COMPOSER_DUMP_AUTOLOAD_OPTIONS && \
    console cache:warmup

ENTRYPOINT ["php", "-S", "0.0.0.0:9000", "./public/index.php"]