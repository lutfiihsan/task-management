# Sistem Manajemen Tugas

## 📌 Pendahuluan
Sistem Manajemen Tugas adalah aplikasi berbasis web yang memungkinkan pengguna untuk membuat, menetapkan, dan melacak tugas secara efisien. Proyek ini dibangun menggunakan Laravel untuk backend dan Laravel Blade dengan AJAX untuk frontend, serta diamankan dengan Laravel Sanctum untuk autentikasi.

## 🚀 Fitur
- Autentikasi dan otorisasi pengguna menggunakan Laravel Sanctum
- Operasi CRUD untuk tugas (Create, Read, Update, Delete)
- Penugasan tugas kepada pengguna tertentu
- Pembaruan status tugas dan riwayat tugas

## 🛠️ Teknologi yang Digunakan
- **Backend**: Laravel 10, Laravel Sanctum
- **Frontend**: Laravel Blade, AJAX, Bootstrap
- **Database**: MySQL

## 🏗️ Panduan Instalasi

### 1️⃣ Clone Repository
```sh
git clone https://github.com/lutfiihsan/task-management.git
cd task-management
```

### 2️⃣ Install Dependensi
```sh
composer install
npm install
```

### 3️⃣ Konfigurasi Lingkungan
Salin file lingkungan contoh dan perbarui kredensial database.
```sh
cp .env.example .env
```
Edit file `.env` dan atur koneksi database Anda.

### 4️⃣ Generate Application Key
```sh
php artisan key:generate
```

### 5️⃣ Jalankan Migrasi Database & Seeding
```sh
php artisan migrate --seed
```

### 6️⃣ Install dan Konfigurasi Laravel Sanctum
```sh
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
php artisan migrate
```

### 7️⃣ Jalankan Server Pengembangan
```sh
php artisan serve
```

## 🖥️ Penggunaan Frontend
### 1️⃣ Buka Aplikasi
Kunjungi `http://127.0.0.1:8000` di browser Anda.

### 2️⃣ Registrasi & Login Pengguna
- Pengguna dapat mendaftar akun.
- Login menggunakan AJAX dan menerima token autentikasi.

### 3️⃣ Manajemen Tugas
- Buat tugas baru menggunakan formulir berbasis AJAX.
- Edit dan perbarui tugas secara dinamis.
- Assign tugas kepada user tertentu
- Hapus tugas dengan konfirmasi terlebih dahulu.

## 🔗 Dokumentasi API
Untuk detail endpoint API dan penggunaannya, lihat koleksi Postman:
[Dokumentasi API Sistem Manajemen Tugas](https://documenter.getpostman.com/view/21695577/2sAYX3r3mp)

## 🔧 Deployment
Untuk deployment ke produksi:
```sh
php artisan config:cache
php artisan route:cache
php artisan view:cache
```
Pastikan `.env` telah dikonfigurasi dengan kredensial database produksi.