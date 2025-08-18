# 🧩 Book Sharing Platform System — Laravel + React + API (Passport + Swagger)

This repository provides a backend API–driven Book Sharing Platform built with **Laravel, with a React.js frontend** consuming the APIs. Authenticated users can discover books shared by others within a configurable **radius (10 km by default)** from their **latitude/longitude** using the **Haversine formula**. The API is secured via Laravel **Passport (OAuth2 bearer tokens)** and fully documented with Swagger/OpenAPI (see /api/documentation). The system supports **role-based access (Admin/User)**, returns consistent JSON via Laravel API Resources (including distance and owner details), and ships with seed data for users and books.

---

## 🚀 Project Overview

This project was built as part of the Digital Frontier Partners interview assessment. It includes:

- **Authentication (Laravel Passport/OAuth2)**: Secure token-based auth with access tokens.
- **Role-based access**:
  - **Admin**: List users, list all books, delete books.
  - **User**: Share books and view nearby books.
- **Nearby Books feature**:
  - Geospatial search using the Haversine formula to find books shared by other users within a configurable radius (default **10 km**).
  - Radius can be adjusted via `config/app.php` → `nearby_books_radius`.
- **API Resources & responses**:
  - Consistent JSON via Laravel API Resources.
  - `BookResource` includes `distance` (km) and `user` info.
- **Documentation (Swagger/OpenAPI)**:
  - Auto-generated docs at `/api/documentation`, with Bearer token authorization.
- **Seed data**:
  - Sample users with latitude/longitude and sample books to test nearby search.
- **Frontend (React.js)**:
  - A React.js client consumes these REST endpoints.

Tech stack: Laravel, Passport (OAuth2), MySQL, Swagger (l5-swagger), React.js.

---

## 📁 Folder Structure (Highlights)

app/
├── Classes/                       # Base helpers for consistent API responses
│   ├── BaseController.php
│   ├── BaseModel.php
│   └── Helper.php
├── Http/
│   ├── Controllers/
│   │   ├── api/                   # Public API (Passport-secured)
│   │   │   ├── AuthController.php
│   │   │   └── BookController.php
│   │   └── admin/                 # Admin-only APIs (middleware: admin)
│   │       ├── BookController.php
│   │       └── UserController.php
│   ├── Requests/
│   │   └── api/                   # Request validation
│   │       ├── RegisterRequest.php
│   │       ├── LoginRequest.php
│   │       └── BookRequest.php
│   ├── Resources/                 # API Resources (transformers)
│   │   ├── api/
│   │   │   ├── BookResource.php
│   │   │   └── BookCollection.php
│   │   └── admin/
│   └── Resources/BaseCollection.php
├── Providers/
│   └── AppServiceProvider.php
└── Repositories/                  # Data access layer
    └── api/
        ├── AuthRepository.php
        └── BookRepository.php
config/
├── app.php                        # nearby_books_radius (configurable)
routes/
└── api.php                        # /login, /register, /books, /books/nearby, /admin/*

# 🧩 Setup Instructions

### 1️⃣ Clone the Repository

```bash
git clone https://github.com/shakib53626/6amTech.git
```
### 2️⃣ Move to Project Directory

```bash
cd 6amTech
```
### 3️⃣ Install PHP Dependencies

```bash
composer install
```

### 4️⃣ Install NPM Packages
```bash
npm install
```

### 5️⃣ Create and Configure .env
```bash
cp .env.example .env
```

Then configure the following in .env:
-Database connection
-JWT Secret:
-Generate it via:
```bash
php artisan jwt:secret
```

### 6️⃣ Generate App Key
```bash
php artisan key:generate
```

### 7️⃣ Migrate your database
```bash
php artisan migrate:fresh --seed
```

### 8️⃣ Start the application

You can run the project using either of the following methods:

- **Method 1:** Run Laravel backend and frontend separately  
```bash
php artisan serve
npm run dev
```

- **Method 2:** Run Laravel backend and frontend a single command  
```bash
composer run dev
```
Or
```bash
composer dev
```

### Need to image file 
```bash
php artisan storage:link
```

### Admin Login Credentials
User : superadmin@gmail.com
Password : password

### User Login Credentials
user : example@gmail.com
password : password


## After running, visit in your browser
```bash
http://localhost:8000
```

# 🧩 Environment configuration details
### Database Configuration
```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=6amtech
DB_USERNAME=root
DB_PASSWORD=
```

### Swagger configuration

```bash
L5_SWAGGER_CONST_HOST=http://localhost:8000
```

### JWT Authenticaton Environment
```bash
JWT_SECRET=I3qd3uDP2JEKZYStxkUcpAiNi6wWJzhKtVcKM3iijTtdLN90kJTgyBL0crYfyyDd
```
Or
```bash
php artisan jwt:secret
```

# 🧩 Optimization Techniques (Task 3)
To improve the performance of the Inventory Management System, the following optimization strategies were applied:

## 1️⃣ Eager Loading (with with())
Instead of using lazy loading, I used eager loading to prevent N+1 query problems.
```bash
    public function index($request)
    {
        $paginateSize = $request->input('paginate_size') ?? 50;

        $query = Product::query()->with('category');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->input('search') . '%')
                  ->orWhere('sku', 'like', '%' . $request->input('search') . '%');
        }

        $products = $query->orderBy('created_at', 'desc')->paginate($paginateSize);

        return [
            'products'   => $products,
            'categories' => Category::select('id', 'name')->get(),
        ];
    }
```
## 2️⃣ Indexing (on searchable fields)
I added database indexes on frequently queried columns such as:
```base
$table->string('name')->index();
```

## 📘 API Documentation

This project includes a full-featured RESTful API for task management, secured using JWT authentication. The API supports user registration, login, and CRUD operations for tasks.

### 🔐 Authentication Endpoints

| Method | Endpoint        | Description              | Auth Required |
| ------ | --------------- | ------------------------ | ------------- |
| POST   | `/api/register` | Register a new user      | ❌ No          |
| POST   | `/api/login`    | Login and get JWT token  | ❌ No          |
| POST   | `/api/logout`   | Invalidate current token | ✅ Yes         |

### 🔑 JWT Token Usage

After login, the token should be included in the `Authorization` header of all subsequent requests:

```http
Authorization: Bearer <your_token_here>
```

---

### 📌 Task Management Endpoints

| Method | Endpoint          | Description             | Auth Required |
| ------ | ----------------- | ----------------------- | ------------- |
| GET    | `/api/tasks`      | Get all tasks           | ✅ Yes         |
| POST   | `/api/tasks`      | Create a new task       | ✅ Yes         |
| PUT    | `/api/tasks/{id}` | Update an existing task | ✅ Yes         |
| DELETE | `/api/tasks/{id}` | Delete a task           | ✅ Yes         |

#### ✅ Sample Task Request Payload:

```json
{
  "title": "Finish Interview Task",
  "description": "Implement all features and document them",
  "priority": "High",
  "completed": false,
  "due_date": "2025-07-20",
  "status": "In Progress",
  "user_id": 1,
  "category": "Development"
}
```

---

### 🧾 Swagger/OpenAPI Integration

All the API endpoints are documented using **Swagger/OpenAPI**.

You can visit the documentation UI at:

```bash
http://localhost:8000/api/documentation
```

Use the **Authorize 🔐** button to input your JWT token and test the protected endpoints directly from the Swagger interface.
