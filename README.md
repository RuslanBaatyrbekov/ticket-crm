# Ticket CRM (Laravel)

[![tests](https://github.com/RuslanBaatyrbekov/ticket-crm/actions/workflows/tests.yml/badge.svg)](https://github.com/RuslanBaatyrbekov/ticket-crm/actions/workflows/tests.yml)

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
```bash
git clone https://github.com/RuslanBaatyrbekov/ticket-crm.git
cd ticket-crm
composer install
```

2. Set up environment variables:
```bash
cp .env.example .env
php artisan key:generate
```

3. Configure your database in the `.env` file:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ticket_crm
DB_USERNAME=root
DB_PASSWORD=
```

4. Run migrations, seed the database with default roles/users, and link storage:
```bash
php artisan migrate:fresh --seed
php artisan storage:link
```

5. Start the local development server:
```bash
php artisan serve
```

## 🔐 Demo Accounts (Admin Panel)

You can access the Admin Panel at `http://127.0.0.1:8000/admin/tickets` using the following seeded credentials:

| Role | Email | Password |
| :--- | :--- | :--- |
| **Admin** | admin@test.com | password |
| **Manager** | manager@test.com | password |

## 🧩 Widget Integration

To embed the feedback form on an external website, use the following iframe code. The widget is protected against Clickjacking but is explicitly allowed for embedding via a custom Middleware.

```html
<iframe src="http://127.0.0.1:8000/widget" width="100%" height="600" frameborder="0" style="border: 1px solid #e5e7eb; border-radius: 8px;"> 
</iframe>
```

A ready-to-open example page is in [`docs/widget-demo.html`](docs/widget-demo.html).

## 📡 API Endpoints Usage

All API responses are returned in JSON format using Laravel API Resources. The full OpenAPI specification is available in the `swagger.yaml` file.

### 1. Create a New Ticket
```http
POST /api/tickets
Accept: application/json
Content-Type: multipart/form-data
```

**Body (FormData):**
* `name`: "John Doe" *(required)*
* `phone`: "+79991234567" *(required, E.164 format)*
* `subject`: "Login Issue" *(required)*
* `message`: "Detailed description of the issue." *(required)*
* `file`: *(binary, optional)*

**Response (201 Created):**
```json
{
  "data": {
    "id": 15,
    "subject": "Login Issue",
    "status": "new",
    "status_label": "Новая",
    "created_at": "2025-12-10 15:30:00",
    "customer": {
      "name": "John Doe",
      "phone": "+79991234567"
    },
    "files": []
  }
}
```

### 2. Get Ticket Statistics
```http
GET /api/tickets/statistics
```

**Response (200 OK):**
```json
{
  "data": {
    "total_today": 5,
    "total_week": 12,
    "total_month": 45
  }
}
```

## ✅ Tests

Feature tests cover the public API (ticket creation, customer de-duplication, file uploads, validation, rate limiting, statistics), the admin panel (access control by role, filtering, status updates) and the embeddable widget headers:

```bash
php artisan test
```
