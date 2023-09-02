## Тема
Подсистема мониторинга состояния пациента в процессе лечения с оценкой необходимости его коррекции

## Научный руководитель 
Шалфеева Елена Арефьевна

## Описание проекта
Данный проект представляет собой интеллектуальную систему, которая на основе признаково 
описания заболевания пациента и пройденного времени лечения формирует оценку необходимости 
коррекции лечения на основе данных, которые вносятся на проятжении всего периода лечения.

## Интсрукция по локальному запуску на ОС Ubuntu
### Установка необходимых пакетов и зависимостей
```angular2html
sudo apt update
sudo apt install curl git unzip
sudo apt install php php-cli php-mbstring php-dom php-pdo php-mysql php-zip
sudo apt install mysql-server
```
### Установка Composer
```angular2html
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
```
### Установка Node.js и NPM
```angular2html
curl -sL https://deb.nodesource.com/setup_14.x | sudo -E bash -
sudo apt install -y nodejs
```
### Глобальная установка пакета Laravel
```angular2html
composer global require laravel/installer
```
### Добавление директории с глобальными Composer-пакетами в переменную PATH
```angular2html
echo 'export PATH="$HOME/.composer/vendor/bin:$PATH"' >> ~/.bashrc
source ~/.bashrc
```
### Создание MySQL базы и пользователя
Замените `database_name`, `username` и `password` на свои значения.
```angular2html
sudo mysql
CREATE DATABASE database_name;
CREATE USER 'username'@'localhost' IDENTIFIED BY 'password';
GRANT ALL PRIVILEGES ON database_name.* TO 'username'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```
### Создание и настройка .env файла в проекте
Перейдите в корневую папку проекта и создай .env файл
```angular2html
cd project_name
touch .env
```
В только что созданном .env файла напишите следующее
```angular2html
APP_NAME=Laravel
APP_ENV=local
APP_KEY=base64:R3bB5w53Z76YwhptB+nRhzlYSKZOTUh3VjVKaRwHhN8=
APP_DEBUG=true
APP_URL=http://localhost

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=database_name
DB_USERNAME=username
DB_PASSWORD=password
```
В полях `DB_DATABASE`, `DB_USERNAME` и `DB_PASSWORD` укажите свои данные, которые создали выше
### Генерация ключа приложения Laravel
Эта команда запускает миграцию, которая создает необходимые таблицы в базе для работы программы
```angular2html
php artisan key:generate
```
### Создание необходимых таблиц в базе
```angular2html
php artisan migrate
```
### Запуск встроенного сервера разработки Laravel
```angular2html
php artisan serve
```
После выполнения всех этих команд и действий приложение Laravel 
должно быть доступно локально по адресу http://localhost:8000//authentication

## Стек технологий
- PHP `v8.1`
- JQuery `v3.7`
- Laravel `v10.10.1`
- Bootstrap `v5.2.3`
- Composer `v2.5.5`
- Npm `v9.6.4`
- MySQL
- PhpStorm
