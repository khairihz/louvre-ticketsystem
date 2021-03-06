# Louvre Ticket System !

Louvre Ticket System application, built with Symfony 5!

Technologies :

- PHP 7.3
- Symfony 5
- Twig
- Encore
- PostgreSQL

### Local setup

To set up the project locally, you would first need to install the following :

- PHP 7.3
- Composer
- NodeJS
- Yarn
- Symfony CLI
- PostgreSQL

1. setup local environment

   create `.env.local` with the following content, and ensure you change the placeholders with actual values :

   - `{{ USERNAME }}` — database username
   - `{{ PASSWORD }}` — database password
   - `{{ USER }}` — Gmail username ( email )
   - `{{ PASS }}` — Gmail password
   - `{{ STRIPE_PUBLIC_KEY }}` — Stripe API public key
   - `{{ STRIPE_PRIVATE_KEY }}` — Stripe API private key

   ```env

   DATABASE_URL=postgres://{{ USERNAME }}:{{ PASSWORD }}@127.0.0.1:5432/louvre

   MAILER_DSN=gmail://{{ USER }}:{{ PASS }}/default

   STRIPE_PUBLIC_KEY={{ STRIPE_PUBLIC_KEY }}
   STRIPE_PRIVATE_KEY={{ STRIPE_PRIVATE_KEY }}
   ```

   This file will hold all the application environment variables for your local setup.

   > Note: if you don't want to set up gmail, you can use the symfony null transport by setting the value for `MAILER_DSN` to `null://user:pass@null`

2. install yarn, and composer dependencies.

   The dependencies' installation is straightforward, all you need to do is run the following commands :

   ```console
   $ composer install -o
   $ yarn install
   ```

3. running migration

   After configuring your environment and installing the depedencies, all you have left to do is run database migration.

   Thanks to doctrine, this operation is as easy as :

   ```console
   $ php bin/console doctrine:migration:migrate --no-interaction
   ```

   or ( short alias ) :

   ```console
   $ php bin/console d:m:m -n
   ```

4. starting the symfony local server

   Now that the application is ready, you can go ahead and run the symfony local http server, using :

   ```console
   $ symfony serve --port 8080 --no-tls
   ```
