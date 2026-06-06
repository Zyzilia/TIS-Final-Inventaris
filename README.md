# 📦 Modern Inventory Management System

<p align="center">
  <strong>Comprehensive Inventory Management System with REST API</strong><br/>
  Built with Laravel 12, JWT Authentication, and Tailwind CSS
</p>

---

## 📋 Table of Contents

- [Introduction](#-introduction)
- [Key Features](#-key-features)
- [Tech Stack](#-tech-stack)
- [Installation](#-installation)
- [Environment Setup](#-environment-setup)
- [Running the Application](#-running-the-application)
- [API Documentation](#-api-documentation)
- [Database Schema](#-database-schema)
- [Access Control & Roles](#-access-control--roles)
- [Testing](#-testing)

---

## 🎯 Introduction

This **Modern Inventory Management System** is a robust solution designed to manage stock, categories, suppliers, customers, and transactions efficiently. It features a secure REST API using JWT Authentication and a role-based access control system to differentiate between administrative and warehouse staff permissions.

The application is suitable for:
- Warehouse stock management
- Recording inbound and outbound stock transactions
- Integration with third-party logistics systems
- Multi-user environments with clear role-based permissions

---

## ✨ Key Features

### 1. **Authentication & Authorization**
- Secure login using JWT (JSON Web Token)
- User profile management
- Integrated logout with JWT token invalidation
- Role-based Access Control (RBAC) for Admin and Warehouse Staff

### 2. **Item Management (Hardware Parts System)**
- Full CRUD for items (Admin) and view-only access (Staff)
- **Custom Branding**: Support for specific hardware brands and series
- **Automated Currency Conversion & Margins**: Base prices in USD with automated margin adjustments and conversion to IDR

### 3. **Categories, Suppliers & Customers**
- Hierarchical item categorization
- Comprehensive supplier tracking and management
- Customer data management for outbound transactions

### 4. **Logistics Integration (Gateway Proxy)**
- Area search (Sub-district/Postal code) via Biteship API
- Shipping cost calculation based on weight (grams) and courier service (JNE, POS, TIKI, etc.)
- Real-time logistics data fetching

### 5. **Audit Trail & Activity Logs**
- Automatic recording of system activities (purchases, stock adjustments, item edits)
- Real-time activity history widget on the dashboard

### 6. **Interactive Analytics & Finance**
- Interactive sales charts (Sales Activity & Distribution)
- Manual Currency & Margin settings to override external API data when needed

---

## 🛠 Tech Stack

### Backend
- **PHP 8.2+** - Primary programming language
- **Laravel 12** - Modern PHP web framework
- **MySQL/PostgreSQL** - Relational database
- **JWT-Auth 2.3** - JWT Authentication
- **L5-Swagger** - Interactive API documentation (Swagger UI)

### Frontend
- **JavaScript (ES6+)** - Client-side logic & AJAX
- **Tailwind CSS 4.0** - Utility-first CSS framework
- **Chart.js** - Interactive data visualization
- **Axios 1.11** - HTTP client for AJAX requests

### Development Tools
- **Composer** - PHP dependency manager
- **npm** - Node package manager
- **PHPUnit 11.5** - Automated testing suite
- **Laravel Pint** - Code style formatter

---

## 📦 Installation

### Prerequisites
- PHP 8.2 or higher
- Composer
- Node.js & npm
- MySQL or PostgreSQL

### Step 1: Clone Repository
```bash
git clone <repository-url>
cd <project-directory>
```

### Step 2: Install Dependencies
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

### Step 4: Database Configuration
Edit the `.env` file to match your database credentials:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=inventory_db
DB_USERNAME=root
DB_PASSWORD=
```

### Step 5: Migration & Seeding
```bash
# Run migrations
php artisan migrate

# (Optional) Seed with dummy data
php artisan db:seed
```

### Step 6: Build Frontend Assets
```bash
# Production build
npm run build

# Development with watch mode
npm run dev
```

---

## 🚀 Environment Setup

### Important Environment Variables
```env
# App Configuration
APP_NAME="InventoryManager"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

# JWT Configuration
JWT_ALGORITHM=HS256
JWT_SECRET=your-secret-key

# Logistics API (e.g., Biteship)
BITESHIP_API_KEY=your-api-key
```

### Quick Setup Script
```bash
composer run setup
```
This script automates dependency installation, environment setup, migrations, and asset building.

---

## ⚙️ Running the Application

### Development Mode
```bash
composer run dev
```
Runs the PHP server, queue listener, logs viewer (Pail), and Vite dev server concurrently.

### Manual Component Start

**PHP Server:**
```bash
php artisan serve
```

**Queue Listener:**
```bash
php artisan queue:listen
```

**Frontend Dev Server:**
```bash
npm run dev
```

---

## 📡 API Documentation

### Interactive Swagger UI
Access the interactive dashboard to explore and test endpoints:
```
URL: http://localhost:8000/api/documentation
```

### Base URL
```
http://localhost:8000/api
```

### Core Endpoints

| Category | Endpoint | Method | Role |
| :--- | :--- | :--- | :--- |
| **Auth** | `/auth/login` | POST | Public |
| | `/auth/profile` | GET | Authenticated |
| | `/auth/logout` | POST | Authenticated |
| **Items** | `/items` | GET | Admin, Staff |
| | `/items` | POST | Admin |
| | `/items/{id}` | GET | Admin, Staff |
| | `/items/{id}` | PUT | Admin |
| | `/items/{id}` | DELETE | Admin |
| **Transactions** | `/transactions` | GET | Admin, Staff |
| | `/transactions` | POST | Admin, Staff |
| | `/transactions/{id}` | PUT | Admin, Staff |
| **Suppliers** | `/suppliers` | GET | Admin, Staff |
| | `/suppliers` | POST | Admin, Staff |
| **Customers** | `/customers` | GET | Admin, Staff |
| | `/customers` | POST | Admin, Staff |
| **Proxy** | `/proxy/areas` | GET | Admin, Staff |
| | `/proxy/shipping-cost` | POST | Admin, Staff |
| | `/proxy/currency-rates` | GET | Admin, Staff |
| **Logs** | `/activities` | GET | Admin, Staff |

---

## 📊 Database Schema

The system uses several core tables to manage data:
- `users`: Managed users and roles.
- `items`: Master data for hardware and inventory.
- `categories`: Item classification.
- `suppliers`: Supplier contact and info.
- `customers`: Customer contact and info.
- `stock_transactions`: Detailed log of stock movements (In/Out).
- `activity_logs`: System-wide audit trail.

Detailed schema definitions can be found in the `database/migrations` directory.

---

## 🔐 Access Control & Roles

### Role: Admin
- Full management of items (Create, Update, Delete)
- Manage transactions and status updates
- View all reports and audit logs
- Access logistics and finance proxies

### Role: Warehouse Staff
- View items and stock levels
- Record and update stock transactions
- Manage suppliers and customers
- Access logistics proxies for shipping calculations

---

## 🧪 Testing

### Running Tests
```bash
# Run all tests
composer run test

# Run with coverage report
php artisan test --coverage
```

---

## 📁 Project Structure

```
.
├── app/                # Application logic (Controllers, Models, Middleware)
├── config/             # Configuration files
├── database/           # Migrations, Seeders, and Factories
├── public/             # Entry point and static assets
├── resources/          # Frontend assets (Views, CSS, JS)
├── routes/             # API and Web route definitions
├── storage/            # Compiled views, sessions, and logs
├── tests/              # Feature and Unit tests
├── composer.json       # PHP dependencies
└── package.json        # Frontend dependencies
```

---

## 📄 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

---

**Version:** 1.0.0  
**Status:** Stable  
