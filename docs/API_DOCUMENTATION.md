# Backend API Documentation - DonasiKu

## Daftar API Endpoints

### 1. Login API
**Endpoint:** `POST /api/login.php`

**Deskripsi:** Melakukan login user atau admin

**Request Body:**
```json
{
    "email": "user@email.com",
    "password": "password123",
    "role": "user" // atau "admin"
}
```

**Response Success (200):**
```json
{
    "success": true,
    "message": "Login berhasil",
    "data": {
        "id": 1,
        "name": "Nama User",
        "email": "user@email.com",
        "role": "user"
    }
}
```

**Response Error (401):**
```json
{
    "success": false,
    "message": "Email atau password salah"
}
```

---

### 2. Register API
**Endpoint:** `POST /api/register.php`

**Deskripsi:** Mendaftarkan user baru

**Request Body:**
```json
{
    "name": "Nama Lengkap",
    "email": "user@email.com",
    "password": "password123"
}
```

**Validasi:**
- Nama minimal 3 karakter
- Email harus valid dan belum terdaftar
- Password minimal 6 karakter

**Response Success (201):**
```json
{
    "success": true,
    "message": "Akun berhasil dibuat",
    "data": {
        "id": 1,
        "name": "Nama Lengkap",
        "email": "user@email.com",
        "role": "user"
    }
}
```

**Response Error (409):**
```json
{
    "success": false,
    "message": "Email sudah terdaftar"
}
```

---

### 3. Check Session API
**Endpoint:** `GET /api/check-session.php`

**Deskripsi:** Mengecek status login user

**Response Jika Login:**
```json
{
    "logged_in": true,
    "user": {
        "id": 1,
        "email": "user@email.com",
        "name": "Nama User",
        "role": "user"
    }
}
```

**Response Jika Belum Login:**
```json
{
    "logged_in": false,
    "user": null
}
```

---

### 4. Logout API
**Endpoint:** `POST /api/logout.php`

**Deskripsi:** Menghapus session dan logout user

**Response:**
```json
{
    "success": true,
    "message": "Logout berhasil"
}
```

---

## Fungsi Helper di auth.php

### 1. `isLoggedIn()`
Cek apakah user sudah login
```php
if (isLoggedIn()) {
    // User sudah login
}
```

### 2. `isAdmin()`
Cek apakah user adalah admin
```php
if (isAdmin()) {
    // User adalah admin
}
```

### 3. `isUser()`
Cek apakah user adalah user biasa
```php
if (isUser()) {
    // User adalah user biasa
}
```

### 4. `getUserData()`
Mendapatkan data user dari session
```php
$user = getUserData();
echo $user['name']; // Nama user
echo $user['email']; // Email user
echo $user['role']; // Role user
```

### 5. `requireLogin($redirectTo)`
Redirect jika belum login
```php
requireLogin('dashboard.php');
```

### 6. `requireAdmin()`
Redirect jika bukan admin
```php
requireAdmin();
```

---

## Cara Menggunakan di Halaman

### Melindungi halaman yang butuh login:
```php
<?php
require_once 'protect.php';
// Kode halaman di sini hanya bisa diakses jika sudah login
?>
```

### Melindungi halaman admin:
```php
<?php
require_once 'protect-admin.php';
// Kode halaman di sini hanya bisa diakses jika sudah login sebagai admin
?>
```

### Cek status user di halaman:
```php
<?php
require_once 'auth.php';

if (isLoggedIn()) {
    $user = getUserData();
    echo "Halo, " . $user['name'];
} else {
    echo "Anda belum login";
}
?>
```

---

## Password Security

- Password baru di-hash menggunakan **BCRYPT** (`password_hash()`)
- Password lama (plain text) masih kompatibel saat login
- Untuk keamanan maksimal, data user dengan password plain text sebaiknya di-update

### Update password ke format hash:
```php
$password = "12345";
$hashedPassword = password_hash($password, PASSWORD_BCRYPT);
// Simpan ke database
```

---

## Session Management

- Session disimpan di **server** (lebih aman dari localStorage)
- localStorage hanya digunakan untuk **indikator client-side**
- Session ID di-track otomatis oleh PHP

---

## Testing

### Test Login:
```bash
curl -X POST http://localhost/Donasiku-main/api/login.php \
  -H "Content-Type: application/json" \
  -d '{
    "email": "user@email.com",
    "password": "12345",
    "role": "user"
  }'
```

### Test Register:
```bash
curl -X POST http://localhost/Donasiku-main/api/register.php \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Nama Baru",
    "email": "baru@email.com",
    "password": "password123"
  }'
```

---

## Database Tables

### users table
- `id` - Primary key
- `name` - Nama user/admin
- `email` - Email (unique)
- `password` - Password (hashed)
- `role` - 'user' atau 'admin'
- `phone` - Nomor telepon
- `avatar_url` - URL avatar
- `created_at` - Timestamp pembuatan
