# HNDE

Application de gestion de la BDD de l'Hospitalité Notre Dame d'Évreux.

## Installation de Symfony

```bash
symfony new hnde --webapp
```

## Installation des Assets

```bash
yarn install
yarn add bootstrap @fortawesome/fontawesome-free @popperjs/core
yarn encore dev
```

## Ajout des composants

```bash
composer require easycorp/easyadmin-bundle
composer require knplabs/knp-paginator-bundle
composer require phpoffice/phpspreadsheet
composer require phpoffice/phpword
composer require symfony/mailer
composer require symfony/mailjet-mailer
composer require symfony/ux-chartjs
composer require symfony/workflow
```

## Base de données et Environnement

Fichier `docker-compose.yml`

```yaml
version: '3'

services:
###> doctrine/doctrine-bundle ###
    database:
        image: 'mariadb:latest'
        container_name: hnde_database
        environment:
            MYSQL_ROOT_PASSWORD: password
            MYSQL_DATABASE: main
        networks:
            - devel
        ports:
            - '3306'
        volumes:
            - db-data:/var/lib/mysql
###< doctrine/doctrine-bundle ###

    dbadmin:
        image: 'phpmyadmin'
        container_name: hnde_dbadmin
        environment:
            PMA_HOST: database
        depends_on:
            - database
        networks:
            - devel
        ports:
            - 8100:80

networks:
    devel:

volumes:
###> doctrine/doctrine-bundle ###
    db-data:
###< doctrine/doctrine-bundle ###
```

Fichier `docker-compose.override.yml`

```yaml
version: '3'

services:
###> symfony/mailer ###
    mailer:
        image: schickling/mailcatcher
        container_name: hnde_mailer
        ports: [1025, 1080]
        networks:
            - devel
###< symfony/mailer ###
```

Fichier `.env`

```env
# .env

###> symfony/framework-bundle ###
APP_ENV=dev
APP_SECRET=5840d603466a07f04514344591d87158
###< symfony/framework-bundle ###

###> symfony/webapp-meta ###
#MESSENGER_TRANSPORT_DSN=doctrine://default?auto_setup=0
###< symfony/webapp-meta ###

###> doctrine/doctrine-bundle ###
DATABASE_URL="mysql://db_user:db_password@127.0.0.1:3306/db_name?serverVersion=5.7"
###< doctrine/doctrine-bundle ###

###> symfony/mailer ###
# MAILER_DSN=null://null
###< symfony/mailer ###

###> symfony/mailjet-mailer ###
# MAILER_DSN=mailjet+api://PUBLIC_KEY:PRIVATE_KEY@api.mailjet.com
# #MAILER_DSN=mailjet+smtp://PUBLIC_KEY:PRIVATE_KEY@in-v3.mailjet.com
###< symfony/mailjet-mailer ###
```

```bash
cp .env .env.local
```

Fichier `.env.local`

```env
# .env.local

###> symfony/framework-bundle ###
APP_ENV=dev
APP_SECRET=5840d603466a07f04514344591d87158
###< symfony/framework-bundle ###

###> symfony/webapp-meta ###
#MESSENGER_TRANSPORT_DSN=doctrine://default?auto_setup=0
###< symfony/webapp-meta ###

###> doctrine/doctrine-bundle ###
DB_USER='root'
DB_PASSWORD='password'
DB_NAME='main'
DB_VERSION='10.7.3'
DATABASE_URL=mysql://${DB_USER}:${DB_PASSWORD}@127.0.0.1:3306/${DB_NAME}?serverVersion=mariadb-${DB_VERSION}
###< doctrine/doctrine-bundle ###

###> symfony/messenger ###
# Choose one of the transports below
# MESSENGER_TRANSPORT_DSN=doctrine://default
# MESSENGER_TRANSPORT_DSN=amqp://guest:guest@localhost:5672/%2f/messages
# MESSENGER_TRANSPORT_DSN=redis://localhost:6379/messages
###< symfony/messenger ###

###> symfony/mailer ###
MAILER_DSN=smtp://localhost:25
MAILER_SENDER_EMAIL=ppitette@gmail.com
MAILER_SENDER_NAME="HNDE Secrétariat"
###< symfony/mailer ###
```

## Mise en cache

```bash
composer dump-env prod
symfony console cache:clear --env=prod
```

## Récupération du code existant

Répertoire racine
Récupérer les fichiers `webpack.config.js`, `.gitignore`

Répertoire `assets`
Récupérer les fichiers `js`

Répertoire `assets/styles`
Effacer le ou les fichiers existants puis puis récupérer les fichies `.scss`

Répertoire racine
Récupérer le fichier `webpack.config.js`

```bash
npm install sass-loader@^12.0.0 sass --dev
npm run build
```

Répertoire `config/packages`
Récupérer les fichiers `knp_paginator.yaml`, `security.yaml`, `translation.yaml`, `twig.yaml`, `workflow.yaml`

Répertoire `config`
Récupérer le fichier `services.yaml`

Récupérer les répertoires `data`, `devel`, `doc`, `docker`, `migrations`

Récupérer le répertoire `public/images`

Récupérer les répertoires `src`, `templates`, `tests`, `translations`
