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
- Login aman menggunakan JWT Token
- Manajemen profil user aktif
- Logout terintegrasi dengan invalidasi token JWT
- Role-based Access Control (RBAC) untuk Admin dan Staff Gudang

### 2. **Manajemen Item/Barang (PC Parts System)**
- CRUD barang (Admin only) & view barang (Admin & Staff)
- **Merk / Seri Komponen Kustom**: Input brand khusus untuk CPU (Ryzen, Intel), GPU (Nvidia, AMD, Intel Arc), RAM (Samsung, Crucial, Corsair), dll.
- **Konverter Kurs Otomatis & Margin**: Dukungan harga berbasis USD dengan penyesuaian margin profit dan konversi nilai ke IDR.

### 3. **Kategori & Supplier**
- Relasi item ke kategori komponen
- Relasi item ke supplier eksternal
- Tracking lokasi gudang dan asal barang

### 4. **Integrasi Logistik (Proxy Gateway)**
- Get data provinsi dari API RajaOngkir
- Get data kota berdasarkan provinsi
- Kalkulasi estimasi ongkos kirim berdasarkan berat (gram) dan jenis kurir (JNE, POS, TIKI)

### 5. **Audit Trail & Activity Logs**
- Pencatatan otomatis log aktivitas sistem (pembelian, penambahan stok, edit item, dll.)
- Widget riwayat aktivitas real-time pada dashboard

### 6. **Interactive Sales Analytics & Settings**
- Chart penjualan interaktif yang terhubung langsung antara grafik bulanan (Sales Activity) dengan distribusi persentase wilayah (Doughnut Chart).
- Pengaturan Kurs & Margin Manual untuk bypass/override API eksternal secara fleksibel.

---

## 🛠 Stack Teknologi

### Backend
- **PHP 8.2+** - Bahasa pemrograman utama
- **Laravel 12** - Web framework PHP
- **MySQL/PostgreSQL** - Database
- **JWT-Auth 2.3** - JSON Web Token Authentication
- **L5-Swagger (Swagger UI)** - Dokumentasi API interaktif

### Frontend
- **JavaScript (ES6+)** - Interaksi & AJAX client
- **Tailwind CSS 4.0** - Styling framework
- **Chart.js** - Rendering grafik interaktif
- **FontAwesome 6** - Kumpulan ikon grafis
- **Axios 1.11** - HTTP client untuk AJAX requests

### Development Tools
- **Composer** - PHP Dependency Manager
- **npm** - Node Package Manager
- **PHPUnit 11.5** - Automated testing suite
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

### Interactive Swagger UI (Recommended)
Seluruh REST API endpoint telah terdokumentasi menggunakan Swagger Open-API. Anda dapat mengakses dashboard interaktif untuk mencoba langsung endpoint (termasuk autorisasi bearer token):
```
URL: http://localhost:8000/api/documentation
```

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
  "success": true,
  "message": "Daftar barang berhasil diambil",
  "data": [
    {
      "id": 1,
      "category_id": 1,
      "supplier_id": 1,
      "brand": "Nvidia GeForce",
      "name": "NVIDIA RTX 4090 GPU",
      "sku": "GPU-4090-FE",
      "stock": 15,
      "price_usd": "1700.00",
      "profit_margin": "10.00",
      "created_at": "2026-05-20T10:42:42.000000Z",
      "updated_at": "2026-05-20T10:42:42.000000Z",
      "price": 29920000,
      "category": {
        "id": 1,
        "name": "GPU"
      },
      "supplier": {
        "id": 1,
        "name": "NVIDIA Corp"
      }
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
  "success": true,
  "message": "Detail barang berhasil diambil",
  "data": {
    "id": 1,
    "category_id": 1,
    "supplier_id": 1,
    "brand": "Nvidia GeForce",
    "name": "NVIDIA RTX 4090 GPU",
    "sku": "GPU-4090-FE",
    "stock": 15,
    "price_usd": "1700.00",
    "profit_margin": "10.00",
    "created_at": "2026-05-20T10:42:42.000000Z",
    "updated_at": "2026-05-20T10:42:42.000000Z",
    "price": 29920000,
    "category": {
      "id": 1,
      "name": "GPU"
    },
    "supplier": {
      "id": 1,
      "name": "NVIDIA Corp"
    }
  }
}
```

#### Create Item
```http
POST /items
Authorization: Bearer {access_token}
Roles: admin (ONLY)
Content-Type: application/json

{
  "category_id": 1,
  "supplier_id": 1,
  "brand": "Nvidia GeForce",
  "name": "NVIDIA RTX 4090 GPU",
  "sku": "GPU-4090-FE",
  "stock": 15,
  "price_usd": 1700,
  "profit_margin": 10
}

Response (201):
{
  "success": true,
  "message": "Barang berhasil ditambahkan",
  "data": {
    "category_id": 1,
    "supplier_id": 1,
    "brand": "Nvidia GeForce",
    "name": "NVIDIA RTX 4090 GPU",
    "sku": "GPU-4090-FE",
    "stock": 15,
    "price_usd": 1700,
    "profit_margin": 10,
    "updated_at": "2026-05-20T10:42:42.000000Z",
    "created_at": "2026-05-20T10:42:42.000000Z",
    "id": 1
  }
}
```

#### Update Item
```http
PUT /items/{id}
Authorization: Bearer {access_token}
Roles: admin (ONLY)
Content-Type: application/json

{
  "stock": 20,
  "price_usd": 1650,
  "profit_margin": 12
}

Response (200):
{
  "success": true,
  "message": "Barang berhasil diperbarui",
  "data": {
    "id": 1,
    "category_id": 1,
    "supplier_id": 1,
    "brand": "Nvidia GeForce",
    "name": "NVIDIA RTX 4090 GPU",
    "sku": "GPU-4090-FE",
    "stock": 20,
    "price_usd": 1650,
    "profit_margin": 12,
    "created_at": "2026-05-20T10:42:42.000000Z",
    "updated_at": "2026-05-20T10:45:00.000000Z"
  }
}
```

#### Delete Item
```http
DELETE /items/{id}
Authorization: Bearer {access_token}
Roles: admin (ONLY)

Response (200):
{
  "success": true,
  "message": "Barang berhasil dihapus"
}
```

### Proxy Endpoints (Logistik & Finance Integration)

#### Get Provinces
```http
GET /proxy/provinces
Authorization: Bearer {access_token}
Roles: admin, staff

Response (200):
{
  "success": true,
  "message": "Daftar provinsi berhasil diambil",
  "data": [
    { "province_id": "1", "province": "Bali" },
    { "province_id": "2", "province": "Bangka Belitung" }
  ]
}
```

#### Get Cities by Province
```http
GET /proxy/cities?province=1
Authorization: Bearer {access_token}
Roles: admin, staff

Response (200):
{
  "success": true,
  "message": "Daftar kota berhasil diambil",
  "data": [
    { "city_id": "1", "province_id": "1", "city_name": "Denpasar", "type": "Kota" }
  ]
}
```

#### Check Shipping Cost
```http
POST /proxy/shipping-cost
Authorization: Bearer {access_token}
Roles: admin, staff
Content-Type: application/json

{
  "origin": "501",
  "destination": "114",
  "weight": 1000,
  "courier": "jne"
}

Response (200):
{
  "success": true,
  "message": "Kalkulasi biaya pengiriman berhasil",
  "data": [
    {
      "code": "jne",
      "name": "Jalur Nugraha Ekakurir (JNE)",
      "costs": [
        {
          "service": "OKE",
          "description": "Ongkos Kilat Khusus",
          "cost": [{ "value": 6000, "etd": "1-2" }]
        }
      ]
    }
  ]
}
```

#### Get Currency Exchange Rates (USD to IDR/SGD/CNY)
```http
GET /proxy/currency-rates
Authorization: Bearer {access_token}
Roles: admin, staff

Response (200):
{
  "success": true,
  "message": "Kurs berhasil diambil",
  "data": {
    "base": "USD",
    "rates": {
      "IDR": 16250,
      "SGD": 1.34,
      "CNY": 7.24
    },
    "last_updated": "Wed, 20 May 2026 11:32:45 UTC"
  }
}
```

### Audit Trail & Activities Endpoints

#### Get Audit Trail Logs
```http
GET /activities
Authorization: Bearer {access_token}
Roles: admin, staff

Response (200):
{
  "success": true,
  "data": [
    {
      "id": 1,
      "user_id": 1,
      "user_name": "Super Admin",
      "action": "Create",
      "description": "Super Admin created NVIDIA RTX 4090 GPU",
      "item_type": "gpu",
      "amount": "+15 Units",
      "order_id": null,
      "created_at": "2026-05-20T10:42:42.000000Z",
      "updated_at": "2026-05-20T10:42:42.000000Z"
    }
  ]
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
  supplier_id BIGINT NULL,
  brand VARCHAR(255) NULL,
  name VARCHAR(255) NOT NULL,
  sku VARCHAR(100) UNIQUE NOT NULL,
  stock INT DEFAULT 0,
  price_usd DECIMAL(10,2) NOT NULL,
  profit_margin DECIMAL(5,2) DEFAULT 10.00,
  created_at TIMESTAMP,
  updated_at TIMESTAMP,
  FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE,
  FOREIGN KEY (supplier_id) REFERENCES suppliers(id) ON DELETE SET NULL
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
  FOREIGN KEY (item_id) REFERENCES items(id) ON DELETE CASCADE
);
```

### Tabel Activity Logs
```sql
CREATE TABLE activity_logs (
  id BIGINT PRIMARY KEY AUTO_INCREMENT,
  user_id BIGINT NULL,
  user_name VARCHAR(255) NULL,
  action VARCHAR(255) NOT NULL,
  description VARCHAR(255) NOT NULL,
  item_type VARCHAR(255) NULL,
  amount VARCHAR(255) NULL,
  order_id VARCHAR(255) NULL,
  created_at TIMESTAMP,
  updated_at TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
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
