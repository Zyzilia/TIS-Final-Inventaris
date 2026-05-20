# 📦 TIS-Final-Inventaris

<p align="center">
  <strong>Sistem Manajemen Inventaris dengan REST API</strong><br/>
  Dibangun dengan Laravel 12, JWT Authentication, dan Tailwind CSS
</p>

---

## 📋 Daftar Isi

- [Penjelasan Proyek](#-penjelasan-proyek)
- [Fitur Utama](#-fitur-utama)
- [Stack Teknologi](#-stack-teknologi)
- [Instalasi](#-instalasi)
- [Setup Lingkungan](#-setup-lingkungan)
- [Menjalankan Aplikasi](#-menjalankan-aplikasi)
- [API Documentation](#-api-documentation)
- [Struktur Database](#-struktur-database)
- [Kontrol Akses & Peran](#-kontrol-akses--peran)
- [Testing](#-testing)

---

## 🎯 Penjelasan Proyek

**TIS-Final-Inventaris** adalah sebuah sistem manajemen inventaris yang dirancang untuk mengelola stok barang, kategori, supplier, dan transaksi stok. Sistem ini dilengkapi dengan API REST yang aman menggunakan JWT Authentication serta role-based access control untuk membedakan hak akses antara admin dan staff gudang.

Aplikasi ini cocok digunakan untuk:
- Manajemen stok barang di gudang
- Pencatatan transaksi penerimaan dan pengeluaran stok
- Integrasi dengan sistem logistik pihak ketiga
- Multi-user dengan sistem role dan permission

---

## ✨ Fitur Utama

### 1. **Authentication & Authorization**
- Login dengan JWT Token
- Profile management
- Logout dengan token invalidation
- Role-based access control (Admin, Staff Gudang)

### 2. **Manajemen Item/Barang**
- Membuat item baru (Admin only)
- Melihat daftar semua item (Admin & Staff)
- Detail item spesifik (Admin & Staff)
- Update item termasuk stok (Admin only)
- Hapus item (Admin only)

### 3. **Kategori & Supplier**
- Relasi item ke kategori
- Relasi item ke supplier
- Tracking asal barang

### 4. **Integrasi Logistik (Proxy)**
- Ambil data provinsi
- Ambil data kota berdasarkan provinsi
- Cek ongkos pengiriman dari external API

### 5. **Stock Transactions**
- Pencatatan otomatis untuk setiap transaksi
- Tracking history perubahan stok
- Audit trail untuk compliance

---

## 🛠 Stack Teknologi

### Backend
- **PHP 8.2+** - Bahasa pemrograman
- **Laravel 12** - Web framework
- **MySQL/PostgreSQL** - Database (dapat dikonfigurasi)
- **JWT-Auth 2.3** - JWT Authentication
- **Laravel Sanctum 4.0** - API token authentication

### Frontend
- **JavaScript (ES6+)** - Scripting
- **Tailwind CSS 4.0** - Utility-first CSS framework
- **Vite 7.0** - Build tool & development server
- **Axios 1.11** - HTTP client

### Development Tools
- **Composer** - PHP Dependency Manager
- **npm** - JavaScript Package Manager
- **Laravel Sail** - Docker development environment
- **PHPUnit 11.5** - Testing framework
- **Laravel Pint** - Code style formatter

---

## 📦 Instalasi

### Prerequisite
```bash
# Pastikan sudah terinstall:
- PHP 8.2 atau lebih tinggi
- Composer
- Node.js & npm
- MySQL atau database lainnya
```

### Step 1: Clone Repository
```bash
git clone https://github.com/Zyzilia/TIS-Final-Inventaris.git
cd TIS-Final-Inventaris
```

### Step 2: Instalasi Dependencies
```bash
# Install PHP dependencies
composer install

# Install JavaScript dependencies
npm install
```

### Step 3: Setup Environment
```bash
# Copy example env file
cp .env.example .env

# Generate application key
php artisan key:generate

# Generate JWT secret
php artisan jwt:secret
```

### Step 4: Konfigurasi Database
Edit file `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=inventaris_db
DB_USERNAME=root
DB_PASSWORD=
```

### Step 5: Migration & Seeding
```bash
# Jalankan migrations
php artisan migrate

# (Optional) Seed dengan data dummy
php artisan db:seed
```

### Step 6: Build Frontend Assets
```bash
# Production build
npm run build

# Development dengan watch mode
npm run dev
```

---

## 🚀 Setup Lingkungan

### Environment Variables yang Penting
```env
# App Configuration
APP_NAME="Inventaris"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=inventaris_db
DB_USERNAME=root
DB_PASSWORD=

# JWT Configuration
JWT_ALGORITHM=HS256
JWT_SECRET=your-secret-key (generate dengan jwt:secret)

# Cache & Session
CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_CONNECTION=database
```

### Setup Cepat dengan Composer Script
```bash
composer run setup
```

Script ini akan secara otomatis:
1. Install composer dependencies
2. Copy .env.example ke .env
3. Generate APP_KEY
4. Jalankan migrations
5. Install npm dependencies
6. Build frontend assets

---

## ⚙️ Menjalankan Aplikasi

### Mode Development Lengkap
```bash
composer run dev
```

Ini akan menjalankan:
- PHP Development Server (port 8000)
- Queue Listener
- Laravel Pail (logs viewer)
- Vite Dev Server (port 5173)

Akses aplikasi di: `http://localhost:8000`

### Menjalankan Per-komponen

**Server PHP:**
```bash
php artisan serve
```

**Queue Listener:**
```bash
php artisan queue:listen
```

**Logs Streaming:**
```bash
php artisan pail
```

**Frontend Development:**
```bash
npm run dev
```

---

## 📡 API Documentation

### Base URL
```
http://localhost:8000/api
```

### Authentication Endpoints

#### Login
```http
POST /auth/login
Content-Type: application/json

{
  "email": "admin@example.com",
  "password": "password"
}

Response (200):
{
  "access_token": "eyJ0eXAiOiJKV1QiLC...",
  "user": {
    "id": 1,
    "name": "Admin",
    "email": "admin@example.com",
    "role": "admin"
  }
}
```

#### Get Profile
```http
GET /auth/profile
Authorization: Bearer {access_token}

Response (200):
{
  "id": 1,
  "name": "Admin",
  "email": "admin@example.com",
  "role": "admin"
}
```

#### Logout
```http
POST /auth/logout
Authorization: Bearer {access_token}

Response (200):
{
  "message": "Successfully logged out"
}
```

### Items Endpoints

#### List All Items
```http
GET /items
Authorization: Bearer {access_token}
Roles: admin, staff

Response (200):
{
  "data": [
    {
      "id": 1,
      "name": "Laptop Dell XPS 13",
      "sku": "DELL-XPS-13-001",
      "stock": 5,
      "price": 12000000,
      "category_id": 1,
      "supplier_id": 1,
      "category": { "id": 1, "name": "Electronics" },
      "supplier": { "id": 1, "name": "PT. Teknologi Maju" }
    }
  ]
}
```

#### Get Item Detail
```http
GET /items/{id}
Authorization: Bearer {access_token}
Roles: admin, staff

Response (200):
{
  "id": 1,
  "name": "Laptop Dell XPS 13",
  "sku": "DELL-XPS-13-001",
  "stock": 5,
  "price": 12000000,
  "category_id": 1,
  "supplier_id": 1,
  "category": { "id": 1, "name": "Electronics" },
  "supplier": { "id": 1, "name": "PT. Teknologi Maju" }
}
```

#### Create Item
```http
POST /items
Authorization: Bearer {access_token}
Roles: admin (ONLY)
Content-Type: application/json

{
  "name": "Laptop Dell XPS 13",
  "sku": "DELL-XPS-13-001",
  "stock": 5,
  "price": 12000000,
  "category_id": 1,
  "supplier_id": 1
}

Response (201):
{ ... item data ... }
```

#### Update Item
```http
PUT /items/{id}
Authorization: Bearer {access_token}
Roles: admin (ONLY)
Content-Type: application/json

{
  "stock": 10,
  "price": 11500000
}

Response (200):
{ ... updated item data ... }
```

#### Delete Item
```http
DELETE /items/{id}
Authorization: Bearer {access_token}
Roles: admin (ONLY)

Response (200):
{
  "message": "Item deleted successfully"
}
```

### Proxy Endpoints (Logistik Integration)

#### Get Provinces
```http
GET /proxy/provinces
Authorization: Bearer {access_token}
Roles: admin, staff

Response (200):
{
  "rajaongkir": {
    "results": [
      { "province_id": "1", "province": "Bali" },
      { "province_id": "2", "province": "Bangka Belitung" }
    ]
  }
}
```

#### Get Cities by Province
```http
GET /proxy/cities?province_id=1
Authorization: Bearer {access_token}
Roles: admin, staff

Response (200):
{
  "rajaongkir": {
    "results": [
      { "city_id": "1", "province_id": "1", "city_name": "Denpasar", "type": "Kota" }
    ]
  }
}
```

#### Check Shipping Cost
```http
POST /proxy/shipping-cost
Authorization: Bearer {access_token}
Roles: admin, staff
Content-Type: application/json

{
  "origin": 501,
  "destination": 114,
  "weight": 1000,
  "courier": "jne"
}

Response (200):
{
  "rajaongkir": {
    "results": [
      {
        "code": "jne",
        "name": "Jalur Nugraha Ekakurir (JNE)",
        "costs": [
          {
            "service": "OKE",
            "description": "Ongkir Kilat Khusus",
            "cost": [{ "value": 6000, "etd": "1-2" }]
          }
        ]
      }
    ]
  }
}
```

---

## 📊 Struktur Database

### Tabel Users
```sql
CREATE TABLE users (
  id BIGINT PRIMARY KEY AUTO_INCREMENT,
  name VARCHAR(255) NOT NULL,
  email VARCHAR(255) UNIQUE NOT NULL,
  password VARCHAR(255) NOT NULL,
  role ENUM('admin', 'staff') DEFAULT 'staff',
  email_verified_at TIMESTAMP NULL,
  remember_token VARCHAR(100),
  created_at TIMESTAMP,
  updated_at TIMESTAMP
);
```

### Tabel Categories
```sql
CREATE TABLE categories (
  id BIGINT PRIMARY KEY AUTO_INCREMENT,
  name VARCHAR(255) NOT NULL,
  description TEXT,
  created_at TIMESTAMP,
  updated_at TIMESTAMP
);
```

### Tabel Suppliers
```sql
CREATE TABLE suppliers (
  id BIGINT PRIMARY KEY AUTO_INCREMENT,
  name VARCHAR(255) NOT NULL,
  contact_person VARCHAR(255),
  phone VARCHAR(20),
  email VARCHAR(255),
  address TEXT,
  created_at TIMESTAMP,
  updated_at TIMESTAMP
);
```

### Tabel Items
```sql
CREATE TABLE items (
  id BIGINT PRIMARY KEY AUTO_INCREMENT,
  category_id BIGINT NOT NULL,
  supplier_id BIGINT NOT NULL,
  name VARCHAR(255) NOT NULL,
  sku VARCHAR(100) UNIQUE NOT NULL,
  stock INT DEFAULT 0,
  price DECIMAL(12,2) NOT NULL,
  created_at TIMESTAMP,
  updated_at TIMESTAMP,
  FOREIGN KEY (category_id) REFERENCES categories(id),
  FOREIGN KEY (supplier_id) REFERENCES suppliers(id)
);
```

### Tabel Stock Transactions
```sql
CREATE TABLE stock_transactions (
  id BIGINT PRIMARY KEY AUTO_INCREMENT,
  item_id BIGINT NOT NULL,
  type ENUM('in', 'out') NOT NULL,
  quantity INT NOT NULL,
  reference VARCHAR(255),
  notes TEXT,
  created_at TIMESTAMP,
  updated_at TIMESTAMP,
  FOREIGN KEY (item_id) REFERENCES items(id)
);
```

---

## 🔐 Kontrol Akses & Peran

### Role: Admin
- ✅ Create Item
- ✅ Update Item & Stock
- ✅ Delete Item
- ✅ View Items
- ✅ Access Proxy Endpoints
- ✅ Manage Users (expandable)

### Role: Staff Gudang
- ❌ Create Item
- ❌ Update Item
- ❌ Delete Item
- ✅ View Items
- ✅ Access Proxy Endpoints
- ✅ Record Stock Transactions (expandable)

### Middleware yang Digunakan
- `auth:api` - Verifikasi JWT Token
- `role:admin,staff` - Verifikasi role user

Lokasi middleware: [app/Http/Middleware/RoleMiddleware.php](app/Http/Middleware/RoleMiddleware.php)

---

## 🧪 Testing

### Menjalankan Tests
```bash
# Jalankan semua tests
composer run test

# Jalankan specific test
php artisan test tests/Feature/ExampleTest.php

# Jalankan dengan verbose output
php artisan test --verbose

# Jalankan dengan coverage report
php artisan test --coverage
```

### Test Coverage
Tests terdapat di folder `tests/`:
- `Feature/` - Integration tests
- `Unit/` - Unit tests

---

## 📁 Struktur File & Folder

```
TIS-Final-Inventaris/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Api/
│   │   │   │   ├── AuthController.php
│   │   │   │   ├── ItemController.php
│   │   │   │   └── ProxyController.php
│   │   │   └── Controller.php
│   │   └── Middleware/
│   │       └── RoleMiddleware.php
│   ├── Models/
│   │   ├── User.php
│   │   ├── Item.php
│   │   ├── Category.php
│   │   ├── Supplier.php
│   │   └── StockTransaction.php
│   └── Providers/
├── database/
│   ├── migrations/
│   ├── factories/
│   └── seeders/
├── routes/
│   ├── api.php (API Routes)
│   ├── web.php
│   └── console.php
├── resources/
│   ├── css/
│   ├── js/
│   └── views/
├── config/
│   ├── app.php
│   ├── jwt.php
│   ├── auth.php
│   └── ...
├── storage/
├── tests/
├── composer.json
├── package.json
├── vite.config.js
└── README.md
```

---

## 🔧 Troubleshooting

### Masalah: "Unauthenticated" Error
**Solusi:**
```bash
# Pastikan JWT secret sudah di-generate
php artisan jwt:secret

# Verifikasi token di header
# Authorization: Bearer {your_jwt_token}
```

### Masalah: Database Connection Error
**Solusi:**
```bash
# Check koneksi database di .env
# Pastikan MySQL/PostgreSQL sudah running
# Jalankan migration ulang
php artisan migrate:fresh
```

### Masalah: Port sudah terpakai
**Solusi:**
```bash
# Ganti port PHP
php artisan serve --port=8001

# Ganti port Vite
npm run dev -- --port=5174
```

---

## 📝 Notes

- Pastikan gunakan HTTPS di production
- Jangan expose JWT_SECRET di repository
- Implement rate limiting untuk API endpoints
- Setup proper error handling dan logging
- Regular backup database

---

## 📄 License

This project is licensed under the MIT License - see the LICENSE file for details.

---

## 👥 Contributors

Proyek ini dikembangkan sebagai tugas mata kuliah **Teknologi Integrasi Sistem** di Filkom Unpad.

---

**Last Updated:** May 2026  
**Version:** 1.0.0

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
