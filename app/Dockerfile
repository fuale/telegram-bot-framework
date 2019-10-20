FROM php:fpm

ARG DOCKER_HOST_IP=172.17.0.1
ARG IDE_KEY=app
ARG SERVER_NAME=app
ARG UID=1000
ARG GID=1000

ENV XDEBUG_CONFIG="idekey=${IDE_KEY} remote_enable=1 remote_host=${DOCKER_HOST_IP}"
ENV PHP_IDE_CONFIG="serverName=${SERVER_NAME}"
RUN docker-php-ext-install pdo pdo_mysql \
    && apt update \
    && apt-get install -y git unzip \
    && pecl install xdebug-2.7.1 \
    && docker-php-ext-enable xdebug \
    && curl -s https://getcomposer.org/installer \
    | php -- --install-dir=/usr/local/bin --filename=composer --quiet \
    && groupadd -fr -g ${GID} app \
    && useradd -rm -g ${GID} -u ${UID} app
USER app
WORKDIR /bot
CMD ["./entrypoint.sh"]