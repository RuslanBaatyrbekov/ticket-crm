# Ticket CRM (Laravel)

A Customer Relationship Management system for handling support tickets, featuring a role-based admin panel, an embeddable feedback widget, and a RESTful API.

## 🛠 Tech Stack

* **Core:** PHP 8.3, Laravel 11, MySQL 8.0+
* **Frontend:** Blade + TailwindCSS (CDN)
* **Architecture:** Service & Repository Pattern
* **Key Packages:**
  * `spatie/laravel-permission` (Roles and Permissions)
  * `spatie/laravel-medialibrary` (File handling)
  * `propaganistas/laravel-phone` (Phone number validation)

## ⚙️ Installation & Setup

1. Clone the repository and install dependencies:

git clone [https://github.com/RuslanBaatyrbekov/ticket-crm.git](https://github.com/RuslanBaatyrbekov/ticket-crm.git)
cd ticket-crm
composer install
Set up environment variables:Bashcp .env.example .env
php artisan key:generate
Configure your database in the .env file:Фрагмент кодаDB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ticket_crm
DB_USERNAME=root
DB_PASSWORD=
Run migrations, seed the database with default roles/users, and link storage:Bashphp artisan migrate:fresh --seed
php artisan storage:link
Start the local development server:Bashphp artisan serve
🔐 Demo Accounts (Admin Panel)You can access the Admin Panel at http://127.0.0.1:8000/admin/tickets using the following seeded credentials:RoleEmailPasswordAdminadmin@example.compasswordManagermanager@example.compassword🧩 Widget IntegrationTo embed the feedback form on an external website, use the following iframe code. The widget is protected against Clickjacking but is explicitly allowed for embedding via a custom Middleware.HTML<iframe src="[http://127.0.0.1:8000/widget](http://127.0.0.1:8000/widget)" width="100%" height="600" frameborder="0" style="border: 1px solid #e5e7eb; border-radius: 8px;"> 
</iframe>
📡 API Endpoints UsageAll API responses are returned in JSON format using Laravel API Resources. The full OpenAPI specification is available in the swagger.yaml file.1. Create a New TicketHTTPPOST /api/tickets
Accept: application/json
Content-Type: multipart/form-data
Body (FormData):name: "John Doe" (required)phone: "+79991234567" (required, E.164 format)subject: "Login Issue" (required)message: "Detailed description of the issue." (required)file: (binary, optional)Response (201 Created):JSON{
  "data": {
    "id": 15,
    "subject": "Login Issue",
    "status": "New",
    "created_at": "2025-12-10 15:30:00",
    "customer": {
      "name": "John Doe",
      "phone": "+79991234567"
    },
    "files": []
  }
}
2. Get Ticket StatisticsHTTPGET /api/tickets/statistics
Response (200 OK):JSON{
  "data": {
    "total_today": 5,
    "total_week": 12,
    "total_month": 45
  }
}
