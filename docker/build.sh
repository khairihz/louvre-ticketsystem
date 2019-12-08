#!/usr/bin/env bash

# Install composer
curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install Yarn
curl -o- -L https://yarnpkg.com/install.sh | bash
ln -s ~/.yarn/bin/yarn /usr/local/bin/yarn

# Export bin directories
export PATH="$PATH:/usr/www/louvre/vendor/bin:/var/www/louvre/bin"
