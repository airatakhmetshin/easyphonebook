# Easyphonebook

Телефоная книга разработанная с применением Symfony и EasyAdmin.

## Установка:

Клонируем репозиторий и переходим в каталог
```
git clone https://github.com/airatakhmetshin/easyphonebook.git phonebook
cd phonebook
```

Задаем подключение к базе данных
```
echo DATABASE_URL=pgsql://db_user:db_password@127.0.0.1:5432/db_name?charset=UTF-8 >> .env.local
```

Устанавливаем зависимости
```
composer install
```

Устанавливаем зависимости для сборки фронтэнда (с помощью [Yarn](https://yarnpkg.com/en/docs/install))
```
yarn install
yarn encore dev
```

Применяем миграции
```
php bin/console doctrine:migrations:migrate
```

Запускаем локальный сервер
```
php bin/console run
```

### Установка для Production-среды:

```
git clone https://github.com/airatakhmetshin/easyphonebook.git phonebook
cd phonebook
echo APP_ENV=prod >> .env.local
echo DATABASE_URL=pgsql://db_user:db_password@127.0.0.1:5432/db_name?charset=UTF-8 >> .env.local
composer install --no-dev
composer dump-autoload --optimize --no-dev --classmap-authoritative
yarn install
yarn encore production
php bin/console doctrine:migrations:migrate
php bin/console cache:warmup
```