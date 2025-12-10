Технический стек
PHP 8.3
Laravel 11
MySQL 8.0+
Blade + TailwindCSS (CDN)
Service & Repository Pattern
`spatie/laravel-permission`
`spatie/laravel-medialibrary`
`propaganistas/laravel-phone` 

Инструкция по запуску

git clone https://github.com/RuslanBaatyrbekov/ticket-crm
cd ticket-crm
composer install

cp .env.example .env
php artisan key:generate

в .env

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ticket_crm
DB_USERNAME=root
DB_PASSWORD=

php artisan migrate:fresh --seed
php artisan storage:link

php artisan serve

Менеджер (доступ в Админку)
Login: manager@example.com

Password: password

URL: http://127.0.0.1:8000/admin/tickets

Администратор
Login: admin@example.com

Password: password

Для встраивания формы обратной связи на сторонний сайт используйте следующий код. Виджет защищен от Clickjacking, но разрешен для встраивания через специальный Middleware.

HTML

<iframe
src="[http://127.0.0.1:8000/widget](http://127.0.0.1:8000/widget)"
width="100%"
height="600"
frameborder="0"
style="border: 1px solid #e5e7eb; border-radius: 8px;">
</iframe>


Примеры API
Все ответы API возвращаются в формате JSON (API Resources). Полная спецификация доступна в файле swagger.yaml.

1. Создание заявки
   POST /api/tickets

Headers:
Accept: application/json
Content-Type: multipart/form-data

Body (FormData):

name: "Иван Иванов" (required)
phone: "+79991234567" (required, E.164 format)
subject: "Проблема" (required)
message: "Подробно" (required)

file: (binary, optional)

Response (201 Created):

JSON

{
"data": {
"id": 15,
"subject": "Проблема",
"status": "Новая",
"created_at": "2025-12-10 15:30:00",
"customer": {
"name": "Иван Иванов",
"phone": "+79991234567"
},
"files": []
}
}
2. Получение статистики
   GET /api/tickets/statistics

Response (200 OK):

JSON

{
"data": {
"total_today": 5,
"total_week": 12,
"total_month": 45
}
}
