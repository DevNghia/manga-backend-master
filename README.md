# Manga backend
## Installation Guid

### Prerequisite
**Install PHP 7.4**
```bash
sudo apt install php7.4 php7.4-{fpm,tidy,gd,intl,mysql,odbc,opcache,soap,dev,curl,bz2,bcmath,json,mbstring,xml,zip,memcached} php-redis
```

### Install dependencies
Fresh install
```bash
composer install
```

If you were working on the project before, please update with
```bash
composer update nothing
```

### Development

Laravel run these commands for fixing storage permission
```bash
sudo chown -R $USER:www-data storage
sudo chmod -R 775 storage
```
Setting .env ( copy file .env.example create file .env)
```bash
APP_URL=

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=hub_maga
DB_USERNAME=root
DB_PASSWORD=

DB_HOST_MANGA_MYSQL=127.0.0.1
DB_PORT_MANGA_MYSQL=3306
DB_DATABASE_MANGA_MYSQL=hub_manga
DB_USERNAME_MANGA_MYSQL=root
DB_PASSWORD_MANGA_MYSQL=

MAIL_MAILER=smtp
MAIL_HOST=
MAIL_PORT=587
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=
MAIL_FROM_NAME=
```

Generate a new Application Key
```bash
php artisan key:generate
```

Run the migration and seeds
```bash
php artisan migrate
php artisan jwt:secret
```

Run project
```bash
php artisan serve --port=8000
```
open link run serve project
```bash
http://localhost:8000
```


### Command run import manga json
path file json: `/storage/json/...`
```bash
php artisan manga:import-json
```
