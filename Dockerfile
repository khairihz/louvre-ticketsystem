FROM php:7.3-fpm

RUN apt-get update
RUN apt-get upgrade
RUN apt-get install -y curl git zip zlib1g-dev libzip-dev
RUN apt-get clean
RUN rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

RUN docker-php-ext-install pdo_mysql
RUN docker-php-ext-install zip
RUN docker-php-ext-install mbstring
RUN docker-php-ext-install calendar

RUN pecl install apcu

RUN docker-php-ext-enable apcu

COPY php.ini /usr/local/etc/php/conf.d/
COPY php.ini /usr/local/etc/php/cli/conf.d/

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php -r "if (hash_file('SHA384', 'composer-setup.php') === 'a5c698ffe4b8e849a443b120cd5ba38043260d5c4023dbf93e1558871f1f07f58274fc6f4c93bcfd858c6bd0775cd8d1') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
RUN php composer-setup.php --filename=composer
RUN php -r "unlink('composer-setup.php');"
RUN mv composer /usr/local/bin/composer

RUN PATH=$PATH:/usr/www/louvre/vendor/bin:bin

ARG USER_UID=1000

RUN usermod -u $USER_UID www-data

ADD . /var/www/louvre

WORKDIR /var/www/louvre

RUN mkdir -p ${PWD}/var/logs
RUN mkdir -p ${PWD}/var/cache
RUN mkdir -p ${PWD}/var/sessions

RUN composer install -o --no-scripts --no-progress --no-suggest --apcu-autoloader

ENV APP_ENV "prod"
ENV APP_SECRET "7f4fb6a4ee2d5e20afb3e0e859b9248d"
ENV DATABASE_URL "postgres://louvre:louvre@db:5432/louvre?serverVersion=8.0"
ENV MAILER_DSN "gmail://USERNAME:PASSWORD@default"
ENV STRIPE_PRIVATE_KEY "xxxx"
ENV STRIPE_PUBLIC_KEY "xxxx"

CMD ["php-fpm", "-F"]

EXPOSE 9001
