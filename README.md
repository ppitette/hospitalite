# HNDE

Application de gestion de la BDD de l'Hospitalité Notre Dame d'Évreux.

## Installation de l'application

```bash
cd /var/www
sudo gh repo clone ppitette/hnde
sudo chown -R debian:www-data hnde
cd hnde
composer install
yarn install
yarn add bootstrap @fortawesome/fontawesome-free @popperjs/core
yarn encore prod
cp .env .env.local
```

fichier `.env.local`

```env
APP_ENV=prod

...

###> doctrine/doctrine-bundle ###
DB_USER='xxxx'
DB_PASSWORD='xxxx'
DB_NAME='xxxx'
DB_VERSION='mariadb-10.x.x'
DATABASE_URL=mysql://${DB_USER}:${DB_PASSWORD}@127.0.0.1:3306/${DB_NAME}?serverVersion=${DB_VERSION}
###< doctrine/doctrine-bundle ###
```

```bash
composer dump-env prod
symfony console cache:clear --env=prod
cd ..
sudo chown -R debian:www-data hnde
```

## Migration

```bash
cd /var/www
sudo chown -R debian:www-data hnde
cd hnde
git pull
composer install
composer dump-env prod
symfony console doctrine:migration:migrate
yarn install --force
yarn encore prod
symfony console cache:clear --env=prod
cd ..
sudo chown -R www-data:www-data hnde
```

## Base de données

Récupérer la sauvegarde de la base de données depuis le répertoire /home/debian

```bash
docker exec -i hnde_database mysql -uroot -ppassword main < devel/sql/hnde_database_sav.sql
```

## Contributing

## License
[GNU GPLv3](https://choosealicense.com/licenses/gpl-3.0/)
