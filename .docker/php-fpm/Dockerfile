FROM php:8.3-fpm-alpine AS base

ARG GID
ARG UID
ENV PATH="$PATH:/home/application/.composer/vendor/bin:/var/www/html/vendor/bin"
ENV COMPOSER_ALLOW_SUPERUSER=0

WORKDIR /var/www/html/

COPY ./.docker/php-fpm/conf/* /usr/local/etc/php/conf.d

RUN addgroup -g ${GID} --system application && \
    adduser -G application --system -D -s /bin/sh -u ${UID} application && \
    sed -i "s/user = www-data/user = application/g" /usr/local/etc/php-fpm.d/www.conf && \
    sed -i "s/group = www-data/group = application/g" /usr/local/etc/php-fpm.d/www.conf && \
    mkdir "/usr/local/logs" && chmod 755 "/usr/local/logs" && chown application:application "/usr/local/logs" && \
    curl --silent --show-error https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin/ --filename=composer && \
    printf "alias composer=\"XDEBUG_MODE=off composer\"" >> /etc/profile.d/aliases.sh

COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/

# RUN install-php-extensions pcntl
