# 📦 Sistem Manajemen Inventaris Modern

<p align="center">
  <strong>Sistem Manajemen Inventaris Lengkap dengan REST API</strong><br/>
  Dibangun menggunakan Laravel 12, JWT Authentication, dan Tailwind CSS
</p>

---

## 📋 Daftar Isi

- [Pendahuluan](#-pendahuluan)
- [Fitur Utama](#-fitur-utama)
- [Stack Teknologi](#-stack-teknologi)
- [Instalasi](#-instalasi)
- [Konfigurasi Lingkungan](#-konfigurasi-lingkungan)
- [Cara Menjalankan](#-cara-menjalankan)
- [Dokumentasi API](#-dokumentasi-api)
- [Struktur Database](#-struktur-database)
- [Hak Akses & Peran](#-hak-akses--peran)
- [Testing](#-testing)

---

## 🎯 Pendahuluan

Proyek ini adalah sistem manajemen inventaris yang dirancang untuk mengelola stok barang, kategori, supplier, customer, serta transaksi keluar-masuk barang secara efisien. Sistem ini dilengkapi dengan REST API yang aman menggunakan JWT Authentication dan sistem kontrol akses berbasis peran (RBAC) untuk membedakan hak akses antara Admin dan Staff Gudang.

Aplikasi ini dapat digunakan untuk:
- Manajemen stok barang di gudang.
- Pencatatan transaksi stok masuk (restock) dan keluar (penjualan).
- Integrasi dengan sistem logistik pihak ketiga untuk cek ongkir.
- Pelacakan aktivitas sistem melalui audit trail.

---

## ✨ Fitur Utama

### 1. **Autentikasi & Otorisasi**
- Login aman menggunakan JWT (JSON Web Token).
- Manajemen profil pengguna.
- Sistem Logout dengan pembatalan token JWT.
- Role-based Access Control (RBAC) untuk Admin dan Staff Gudang.

### 2. **Manajemen Barang (Hardware Parts)**
- Operasi CRUD lengkap untuk barang (khusus Admin).
- **Custom Branding**: Dukungan untuk berbagai brand hardware dan seri komponen.
- **Konversi Kurs Otomatis & Margin**: Harga dasar dalam USD yang otomatis dikonversi ke IDR dengan penyesuaian margin profit.

### 3. **Kategori, Supplier & Customer**
- Pengelompokan barang berdasarkan kategori.
- Manajemen data supplier untuk pengadaan barang.
- Manajemen data customer untuk transaksi keluar.

### 4. **Integrasi Logistik (Proxy Gateway)**
- Pencarian area (Kecamatan/Kodepos) melalui API Biteship.
- Kalkulasi biaya pengiriman berdasarkan berat (gram) dan kurir (JNE, POS, TIKI, dll).
- Pengambilan data kurs mata uang real-time (USD ke IDR).

### 5. **Audit Trail & Log Aktivitas**
- Pencatatan otomatis setiap aktivitas penting (penambahan stok, pengeditan barang, transaksi).
- Widget riwayat aktivitas pada dashboard untuk monitoring real-time.

### 6. **Analitik & Dashboard Interaktif**
- Grafik aktivitas penjualan dan distribusi stok yang interaktif.
- Pengaturan kurs dan margin manual untuk penyesuaian fleksibel.

---

## 🛠 Stack Teknologi

### Backend
- **PHP 8.2+** - Bahasa pemrograman utama.
- **Laravel 12** - Framework PHP modern.
- **MySQL/PostgreSQL** - Database relasional.
- **JWT-Auth 2.3** - Autentikasi berbasis token.
- **L5-Swagger** - Dokumentasi API interaktif (Swagger UI).

### Frontend
- **JavaScript (ES6+)** - Logika client-side & AJAX.
- **Tailwind CSS 4.0** - Framework CSS utility-first.
- **Chart.js** - Visualisasi data grafik.
- **Axios 1.11** - HTTP client untuk request API.

---

## 📦 Instalasi

### Prasyarat
- PHP 8.2 atau lebih tinggi.
- Composer.
- Node.js & npm.
- Database MySQL atau PostgreSQL.

### Langkah 1: Clone Repository
```bash
git clone <url-repository>
cd <nama-folder-proyek>
```

### Langkah 2: Install Dependensi
```bash
# Install library PHP
composer install

# Install library JavaScript
npm install
```

### Langkah 3: Setup Environment
```bash
# Copy file .env
cp .env.example .env

# Generate application key
php artisan key:generate

# Generate JWT secret
php artisan jwt:secret
```

### Langkah 4: Konfigurasi Database
Sesuaikan file `.env` dengan database lokal Anda:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nama_db_anda
DB_USERNAME=root
DB_PASSWORD=
```

### Langkah 5: Migrasi & Seeding
```bash
# Jalankan migrasi tabel
php artisan migrate

# (Opsional) Isi data awal/dummy
php artisan db:seed
```

### Langkah 6: Build Assets Frontend
```bash
# Untuk produksi
npm run build

# Untuk mode development
npm run dev
```

---

## 🚀 Konfigurasi Lingkungan

### Variabel .env yang Penting
```env
# Konfigurasi Aplikasi
APP_NAME="SistemInventaris"
APP_URL=http://localhost:8000

# Konfigurasi JWT
JWT_SECRET=hasil_dari_php_artisan_jwt_secret

# API Key Logistik (Contoh: Biteship)
BITESHIP_API_KEY=api_key_anda
```

---

## ⚙️ Cara Menjalankan

### Mode Development (Sekaligus)
```bash
composer run dev
```
Perintah ini akan menjalankan server PHP, listener queue, dan Vite secara bersamaan.

### Menjalankan Secara Manual

**Server PHP:**
```bash
php artisan serve
```

**Vite (Frontend):**
```bash
npm run dev
```

---

## 📡 Dokumentasi API

### Swagger UI (Interaktif)
Anda bisa mencoba API secara langsung melalui dashboard Swagger di:
```
URL: http://localhost:8000/api/documentation
```

### Endpoint Utama

| Kategori | Endpoint | Method | Role |
| :--- | :--- | :--- | :--- |
| **Auth** | `/auth/login` | POST | Publik |
| | `/auth/profile` | GET | Admin, Staff |
| **Barang** | `/items` | GET | Admin, Staff |
| | `/items` | POST | Admin |
| | `/items/{id}` | PUT | Admin |
| **Transaksi**| `/transactions` | POST | Admin, Staff |
| **Proxy** | `/proxy/areas` | GET | Admin, Staff |
| | `/proxy/shipping-cost`| POST | Admin, Staff |

---

## 📊 Struktur Database

Sistem ini menggunakan beberapa tabel utama:
- `users`: Data pengguna dan peran (Admin/Staff).
- `items`: Data master barang hardware.
- `categories`: Kategori komponen.
- `suppliers`: Data pemasok barang.
- `customers`: Data pelanggan.
- `stock_transactions`: Riwayat stok masuk dan keluar.
- `activity_logs`: Log audit aktivitas sistem.

---

## 🔐 Hak Akses & Peran

### Role: Admin
- Memiliki akses penuh (CRUD) ke data barang.
- Mengelola supplier, customer, dan kategori.
- Melihat log aktivitas seluruh sistem.
- Mengatur konfigurasi kurs dan margin.

### Role: Staff Gudang
- Melihat daftar stok barang.
- Mencatat transaksi masuk dan keluar.
- Mengelola data supplier dan customer.
- Melakukan cek ongkir melalui fitur proxy.

---

## 🧪 Testing

```bash
# Jalankan semua unit & feature test
composer run test
```

---

## 📁 Struktur Folder Proyek

```
.
├── app/                # Logika inti aplikasi (Controller, Model, Middleware)
├── config/             # File konfigurasi Laravel
├── database/           # Migrasi, Seeder, dan Factory
├── public/             # Entry point (index.php) dan file statis
├── resources/          # File frontend (Views, CSS, JS)
├── routes/             # Definisi route API dan Web
├── tests/              # File pengujian (PHPUnit)
├── composer.json       # Dependensi PHP
└── package.json        # Dependensi JS
```

---

## 📄 Lisensi

Proyek ini menggunakan lisensi [MIT](https://opensource.org/licenses/MIT).

---

**Versi:** 1.0.0  
**Status:** Pengembangan Selesai  
