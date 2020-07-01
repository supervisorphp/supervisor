FROM php:7.4-cli-alpine

RUN apk add --no-cache zip git curl supervisor

RUN docker-php-ext-install pcntl

RUN apk add --no-cache --virtual .build-deps autoconf build-base \
    && pecl install xdebug-2.8.1 \
    && docker-php-ext-enable xdebug \
    && apk del .build-deps

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer

RUN mkdir /app
WORKDIR /app

CMD ["composer", "run", "ci"]
