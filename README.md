🎬 LaraCinema

Веб-приложение на Laravel для просмотра и оценки фильмов.
Проект разворачивается с помощью Docker (Nginx + PHP + MySQL).

🚀 Стек технологий

PHP 8.x

Laravel 12

MySQL 8

Nginx

Docker / Docker Compose

JavaScript (AJAX)

📦 Требования

Перед началом убедись, что у тебя установлены:

Docker

Docker Compose

Git

🛠 Установка и запуск
1️⃣ Клонировать репозиторий
git clone https://github.com/your-username/laracinema.git
cd laracinema

2️⃣ Создать .env

Скопируй пример:

cp .env.example .env


Минимально важные параметры:

APP_NAME=LaraCinema
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laracinema
DB_USERNAME=root
DB_PASSWORD=root

3️⃣ Запуск контейнеров
docker-compose up -d --build


После этого будут запущены:

nginx

php (Laravel)

mysql

так же автоматически запустятся команды:

docker-compose exec app composer install

docker-compose exec app php artisan key:generate

docker-compose exec app php artisan migrate

docker-compose exec app php artisan migrate --seed

🌐 Доступ к проекту

После запуска сайт будет доступен по адресу:

http://http://localhost:8080/

🐳 Docker-сервисы
Сервис	Описание
PHP + Laravel
nginx	Веб-сервер
mysql	База данных

🧪 Полезные команды

Остановить контейнеры:

docker-compose down


Перезапустить:

docker-compose restart


Посмотреть логи:

docker logs app
docker logs app_nginx
docker logs app_mysql


Войти в контейнер:

docker-compose exec app bash

📝 Примечания

MySQL работает внутри Docker, не использует локальный MySQL

DB_HOST=db — обязательно

Все данные БД сохраняются в volume mysql_data

📌 Статус проекта

Проект в активной разработке 🚧
Функционал:

Каталог фильмов

Фильтрация

Отзывы и рейтинги

Профиль пользователя

AJAX-загрузка контента
