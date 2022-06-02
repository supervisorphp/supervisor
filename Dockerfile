FROM php:8.1-cli-alpine

RUN apk add --no-cache bash zip git curl supervisor

COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/

RUN install-php-extensions @composer pcntl posix xdebug

RUN mkdir /app
WORKDIR /app

CMD ["composer", "run", "ci"]
