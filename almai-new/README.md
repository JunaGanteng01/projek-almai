# WPA Platform - Almai E-Learning

Platform e-learning untuk Wakil Penasihat Berjangka (WPA) berbasis CodeIgniter 4.

## Requirements

- PHP 8.1+
- MySQL 5.7+ / MariaDB 10.3+
- Composer
- Apache/Nginx

## Installation

### 1. Clone & Install Dependencies

```bash
cd wpa-platform
composer install
```

### 2. Setup Environment

Copy file `.env` dan sesuaikan konfigurasi database:

```bash
cp env .env
```

Edit `.env`:
```
database.default.hostname = localhost
database.default.database = wpa_platform
database.default.username = root
database.default.password = your_password
```

### 3. Create Database

Buat database MySQL:
```sql
CREATE DATABASE wpa_platform CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 4. Run Migrations

```bash
php spark migrate
```

### 5. Seed Database

```bash
php spark db:seed DatabaseSeeder
```

### 6. Run Development Server

```bash
php spark serve
```

Akses: http://localhost:8080

## Demo Accounts

| Role  | Email           | Password  |
|-------|-----------------|-----------|
| Admin | admin@almai.id  | admin123  |
| User  | user@almai.id   | user123   |

## Project Structure

```
wpa-platform/
├── app/
│   ├── Config/          # Configuration files
│   ├── Controllers/     # Controllers
│   │   ├── Admin/       # Admin controllers
│   │   ├── User/        # User dashboard controllers
│   │   └── Wpa/         # WPA dashboard controllers
│   ├── Database/
│   │   ├── Migrations/  # Database migrations
│   │   └── Seeds/       # Database seeders
│   ├── Filters/         # Auth filters
│   ├── Models/          # Models
│   └── Views/
│       ├── layouts/     # Layout templates
│       ├── pages/       # Page views
│       ├── partials/    # Reusable components
│       └── user/        # User dashboard views
├── public/              # Public assets
└── writable/            # Writable directory
```

## Features

- ✅ Homepage dengan featured WPA, kelas populer, artikel terbaru
- ✅ Daftar WPA dengan filter & search
- ✅ Detail profil WPA
- ✅ Daftar kelas dengan filter kategori & mode
- ✅ Detail kelas
- ✅ Daftar artikel dengan filter kategori
- ✅ Detail artikel
- ✅ Halaman About & Kontak
- ✅ Authentication (Login/Register)
- ✅ User Dashboard
- ✅ Role-based access (User, WPA, Admin)

## Routes

### Public Routes
- `/` - Homepage
- `/about` - About page
- `/kontak` - Contact page
- `/wpa` - WPA list
- `/wpa/{id}` - WPA detail
- `/kelas` - Kelas list
- `/kelas/{id}` - Kelas detail
- `/artikel` - Artikel list
- `/artikel/{id}` - Artikel detail
- `/login` - Login page
- `/register` - Register page

### Protected Routes (User)
- `/user/dashboard` - User dashboard
- `/user/belajar` - My courses
- `/user/invoice` - Invoice list
- `/user/sertifikat` - Certificates

### Protected Routes (WPA)
- `/wpa-dashboard` - WPA dashboard

### Protected Routes (Admin)
- `/admin` - Admin dashboard

## Tech Stack

- CodeIgniter 4.6
- TailwindCSS (CDN)
- Font Awesome 6
- AOS Animation
- MySQL/MariaDB

## License

© 2025 PT. Alma Indonesia Raya - ALMAI
