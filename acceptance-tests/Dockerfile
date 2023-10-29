FROM php:8.2.12-cli-alpine

ARG BASE_DIR=/var/www
ENV CODE_DIR=$BASE_DIR/code
ENV COMPOSER_VENDOR_DIR=$BASE_DIR/vendor

ENV COMPOSER_ALLOW_SUPERUSER 1
ENV PATH $PATH:$COMPOSER_VENDOR_DIR/bin

RUN apk add --no-cache \
	    $PHPIZE_DEPS && \
    curl -sS https://getcomposer.org/installer | php -- \
        --version=2.6.5 \
        --install-dir=/usr/local/bin \
        --filename=composer && \
    curl -sSLf \
        -o /usr/local/bin/install-php-extensions \
        https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions && \
    chmod +x \
            /usr/local/bin/install-php-extensions && \
    install-php-extensions pdo pdo_pgsql && \
    rm -rf $HOME_DIR && \
    mkdir -p $CODE_DIR $COMPOSER_VENDOR_DIR

WORKDIR $CODE_DIR

COPY ./composer.* ./
RUN composer install

COPY ./ ./

ENTRYPOINT ["behat"]
