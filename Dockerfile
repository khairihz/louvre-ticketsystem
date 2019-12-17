FROM php:7.3-fpm

RUN apt-get update
RUN apt-get upgrade
RUN apt-get install -y curl

RUN curl -sL https://deb.nodesource.com/setup_9.x | bash

RUN apt-get install -y git zip zlib1g-dev libzip-dev libpq-dev nodejs
RUN apt-get clean
RUN rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

RUN curl -o- -L https://yarnpkg.com/install.sh | bash

RUN docker-php-ext-install pdo pdo_pgsql
RUN docker-php-ext-install zip
RUN docker-php-ext-install mbstring
RUN docker-php-ext-install calendar

RUN pecl install apcu

RUN docker-php-ext-enable apcu

COPY docker/php.ini /usr/local/etc/php/conf.d/
COPY docker/php.ini /usr/local/etc/php/cli/conf.d/

ARG USER_UID=1000

RUN usermod -u $USER_UID www-data

ADD ./  /var/www/louvre

RUN echo '<?php return [];' >> /var/www/louvre/.env.php.local

WORKDIR /var/www/louvre

ENV APP_ENV "prod"
ENV APP_SECRET "7f4fb6a4ee2d5e20afb3e0e859b9248d"
ENV DATABASE_URL "postgres://louvre:louvre@db:5432/louvre"
ENV MAILER_DSN "null://USERNAME:PASSWORD@default"
ENV STRIPE_PRIVATE_KEY "xxx"
ENV STRIPE_PUBLIC_KEY "xxx"

RUN docker/build.sh
RUN docker/install.sh

CMD ["php-fpm", "-F"]

EXPOSE 9000
