FROM php:8.3-fpm-bullseye
RUN echo "xdebug.client_host=host.docker.internal" >> $PHP_INI_DIR/php.ini
RUN echo "xdebug.mode=debug" >> $PHP_INI_DIR/php.ini

COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/

RUN install-php-extensions gd xdebug-3.3.2 pdo_mysql zip intl mysqli @composer