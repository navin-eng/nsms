# Green Peace Lincoln College (GPLC) - SMS & Accounting System

A comprehensive School Management System (SMS) and Dedicated Finance & Accounting Portal built with **Laravel 9**, **Bootstrap 5**, and **Vite**.

---

## 📋 Features Overview

- **🏫 School Management System (SMS)**:
  - Student Admissions, Enrollments, and Profiles
  - Class, Section, and Academic Year Management
  - Teacher & Staff Directory
  - Attendance Tracking (Daily Student & Staff Attendance)
  - Examination Management & Grading (Report Cards, Marksheets)
  - Homework & Study Material Submissions
  - Hostel & Transportation Modules
  - Real-time Analytics & Birthday Notification Widgets
- **💼 Finance & Accounting Portal**:
  - Secure, dedicated accounting portal with multi-guard authentication
  - Student Fee Structures & Bulk Invoice Generation (Nepali/Bikram Sambat Calendar support)
  - Partial/Full Fee Collections & Instant Printable Receipts (A4 / A5)
  - Outstanding Fees & Arrears Reports with multi-filter queries
  - Expense Tracking & Vendor Management
  - Bank Account Management & Reconciliation
  - Budgeting & Budget Utilization Tracking
  - Real-time Financial Statements (Income Statement / P&L, Balance Sheet, Trial Balance)
  - Double-entry Journal System & Chart of Accounts

---

## 💻 System Prerequisites

Before running the project on **Windows, macOS, or Linux**, ensure you have installed:

1. **PHP >= 8.1** (Recommended: PHP 8.2+)
   - Required PHP Extensions enabled in `php.ini`:
     - `openssl`, `pdo`, `mbstring`, `tokenizer`, `xml`, `ctype`, `json`, `bcmath`, `fileinfo`, `curl`, `gd`, `zip`
     - Database driver: `pdo_sqlite` / `sqlite3` (if using SQLite) OR `pdo_mysql` (if using MySQL/XAMPP)
2. **Composer >= 2.2** ([Download Composer](https://getcomposer.org/))
3. **Node.js >= 18.x & NPM** ([Download Node.js](https://nodejs.org/))
4. **Git** ([Download Git](https://git-scm.com/))

---

## 🚀 Setup & Installation Guide (Windows / Mac / Linux)

### 1. Clone the Repository
```bash
git clone https://github.com/navin-eng/nsms.git
cd nsms
```

### 2. Install Dependencies
```bash
# Install PHP dependencies
composer install

# Install Frontend / NPM packages
npm install
```

### 3. Environment Configuration
Copy the example environment configuration:

**On Windows (Command Prompt):**
```cmd
copy .env.example .env
```

**On Windows (PowerShell) / Mac / Linux:**
```bash
cp .env.example .env
```

Generate the Laravel application encryption key:
```bash
php artisan key:generate
```

---

### 4. Database Configuration

You can use either **SQLite** (quickest setup) or **MySQL** (e.g. XAMPP/WAMP).

#### Option A: Using SQLite (Recommended for quick local testing)
1. Open `.env` and set:
   ```env
   DB_CONNECTION=sqlite
   # Comment out or remove DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, DB_PASSWORD
   ```
2. Create the SQLite database file:
   - **Windows (Command Prompt):**
     ```cmd
     type nul > database\database.sqlite
     ```
   - **Windows (PowerShell):**
     ```powershell
     New-Item database/database.sqlite -ItemType File
     ```
   - **Mac / Linux:**
     ```bash
     touch database/database.sqlite
     ```

#### Option B: Using MySQL (XAMPP / WAMP / Native MySQL)
1. Create a new database named `gplc_sms` in phpMyAdmin or MySQL CLI.
2. In `.env`, configure:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=gplc_sms
   DB_USERNAME=root
   DB_PASSWORD=
   ```

---

### 5. Run Migrations & Seed Database

Run migrations and seed the initial accounts (including Super Admin & Head Accountant):
```bash
php artisan migrate --seed
```

---

### 6. Create Storage Symlink

Create the public storage link for uploaded student photos, school logos, and documents:
```bash
php artisan storage:link
```

> **Windows Note:** If `php artisan storage:link` gives a permission error on Windows, either run Command Prompt / PowerShell **as Administrator** or enable **Developer Mode** in Windows Settings (*Settings > Update & Security > For Developers*).

---

### 7. Start Local Development Servers

Open two terminal tabs/windows:

**Terminal 1 (Backend Server):**
```bash
php artisan serve
```
*(Server will start on `http://127.0.0.1:8000`)*

**Terminal 2 (Vite Asset Server):**
```bash
npm run dev
```

---

## 🔑 Portals & Default Credentials

| Portal | URL | Default Login |
|---|---|---|
| **Portal Selector** | `http://localhost:8000/admin/portal` | Gateway to all sub-systems |
| **SMS Admin Dashboard** | `http://localhost:8000/admin/sms/dashboard` | Main Administrative Portal |
| **Accounting System** | `http://localhost:8000/accounting/login` | **Email:** `accountant@school.com`<br>**Password:** `password` |

---

## 🪟 Windows Specific Troubleshooting & Tips

### 1. Enabling PHP Extensions on Windows
If you install PHP manually or via XAMPP on Windows, make sure the following lines are uncommented (no leading semicolon `;`) in your `php.ini` file:
```ini
extension=curl
extension=fileinfo
extension=gd
extension=mbstring
extension=openssl
extension=pdo_mysql
extension=pdo_sqlite
extension=sqlite3
extension=zip
```
After modifying `php.ini`, verify with:
```cmd
php -m
```

### 2. Vite Hot Module Replacement (HMR) on Windows
If assets fail to load or live reload doesn't work, ensure your `vite.config.js` is running and you are accessing the app via `http://localhost:8000`.

### 3. Clear Caches if Updating Views or Routes
Whenever switching branches or pulling changes:
```bash
php artisan optimize:clear
php artisan view:clear
php artisan route:clear
php artisan config:clear
```

---

## 📄 License
This project is proprietary software developed for Green Peace Lincoln College.
