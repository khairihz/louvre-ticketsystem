FROM php:7.3-fpm

COPY docker/php.ini /usr/local/etc/php/conf.d/
COPY docker/php.ini /usr/local/etc/php/cli/conf.d/

RUN apt-get update && \
    apt-get upgrade -y && \
    apt-get install -y curl && \
    curl -sL https://deb.nodesource.com/setup_9.x | bash && \
    apt-get install -y git zip zlib1g-dev libzip-dev libpq-dev nodejs && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* && \
    curl -o- -L https://yarnpkg.com/install.sh | bash && \
    docker-php-ext-install pdo pdo_pgsql && \
    docker-php-ext-install zip && \
    docker-php-ext-install mbstring && \
    docker-php-ext-install calendar && \
    pecl install apcu && \
    docker-php-ext-enable apcu && \
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && \
    curl -o- -L https://yarnpkg.com/install.sh | bash && \
    ln -s ~/.yarn/bin/yarn /usr/local/bin/yarn

ARG USER_UID=1000

ENV APP_ENV=prod
ENV APP_SECRET=$APP_SECRET
ENV DATABASE_URL=postgres://louvre:louvre@db:5432/louvre
ENV MAILER_DSN=$MAILER_DSN
ENV STRIPE_PRIVATE_KEY=$STRIPE_PRIVATE_KEY
ENV STRIPE_PUBLIC_KEY=$STRIPE_PUBLIC_KEY

RUN usermod -u $USER_UID www-data

ADD ./  /var/www/louvre

WORKDIR /var/www/louvre

RUN mkdir -p /var/www/louvre/var/log && \
    mkdir -p /var/www/louvre/var/cache && \
    mkdir -p /var/www/louvre/var/sessions && \
    yarn install && \
    echo '<?php return [];' >> /var/www/louvre/.env.php.local && \
    composer install -o --no-scripts --no-progress --no-suggest --apcu-autoloader && \
    yarn build && \
    export PATH="$PATH:/usr/www/louvre/vendor/bin:/var/www/louvre/bin"

CMD ["php-fpm", "-F"]

EXPOSE 9000
