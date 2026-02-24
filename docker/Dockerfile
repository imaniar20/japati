FROM serversideup/php:8.4-fpm

LABEL maintainer="Aziz Ramdan Kurniawan"

ARG UID
ARG GID

ENV PHP_OPCACHE_ENABLE=1

USER root

RUN apt-get update && apt-get install -y ca-certificates && update-ca-certificates && rm -rf /var/lib/apt/lists/*

RUN docker-php-serversideup-set-id www-data $UID:$GID

RUN install-php-extensions gd

USER www-data
