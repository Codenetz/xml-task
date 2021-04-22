FROM php:8.0.3-cli-alpine3.12 as base

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

WORKDIR /var/task

COPY composer.json /var/task
COPY composer.lock /var/task

RUN composer install

COPY . /var/task

FROM base AS test
ENTRYPOINT ["php", "vendor/bin/phpunit", "tests"]

FROM base AS cmd
ENTRYPOINT ["php", "bin/console", "import:xml"]