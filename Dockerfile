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

ADD assets/           /var/www/louvre/assets
ADD bin/              /var/www/louvre/bin
ADD config/           /var/www/louvre/config
ADD public/           /var/www/louvre/public
ADD src/              /var/www/louvre/src
ADD templates/        /var/www/louvre/templates
ADD translations/     /var/www/louvre/translations
ADD composer.json     /var/www/louvre
ADD composer.lock     /var/www/louvre
ADD package.json      /var/www/louvre
ADD webpack.config.js /var/www/louvre
ADD yarn.lock         /var/www/louvre
ADD docker/build.sh   /var/www/louvre/bin/
ADD docker/install.sh /var/www/louvre/bin/

RUN echo '<?php return [];' >> /var/www/louvre/.env.php.local

WORKDIR /var/www/louvre

ENV APP_ENV "prod"
ENV APP_SECRET "7f4fb6a4ee2d5e20afb3e0e859b9248d"
ENV DATABASE_URL "postgres://louvre:louvre@db:5432/louvre"
ENV MAILER_DSN "gmail://USERNAME:PASSWORD@default"
ENV STRIPE_PRIVATE_KEY "xxxx"
ENV STRIPE_PUBLIC_KEY "xxxx"

RUN bin/build.sh
RUN bin/install.sh

CMD ["php-fpm", "-F"]

EXPOSE 9000
