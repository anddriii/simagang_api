# SIMAGANG Backend Starter - Laravel REST API

Starter backend untuk skripsi:

**Analisis User Experience dan Usability pada Sistem Manajemen Magang Berbasis Progressive Web App dengan AI Chatbot Menggunakan UEQ dan SUS**

Stack:

- Laravel REST API
- Laravel Sanctum untuk API token auth
- MySQL/PostgreSQL
- Flutter / Flutter Web PWA sebagai frontend

> Folder ini adalah starter pack source code backend. Jalankan `composer create-project` terlebih dahulu, lalu copy file dari starter pack ini ke project Laravel baru.

---

## 1. Buat Project Laravel Baru

```bash
composer create-project laravel/laravel simagang-api
cd simagang-api
```

Install package API:

```bash
composer require laravel/sanctum guzzlehttp/guzzle barryvdh/laravel-dompdf maatwebsite/excel
php artisan install:api
```

Kalau `php artisan install:api` belum tersedia di versi Laravel yang dipakai, gunakan:

```bash
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
```

---

## 2. Copy File Starter Pack

Copy semua folder/file dari starter pack ini ke root project Laravel:

```bash
cp -R simagang-backend-starter/* simagang-api/
```

Kalau ada file yang sudah ada, merge manual khususnya:

- `routes/api.php`
- `app/Models/User.php`
- `bootstrap/app.php` atau middleware alias

---

## 3. Konfigurasi `.env`

### MySQL

```env
APP_NAME=SIMAGANG
APP_URL=http://127.0.0.1:8000
FRONTEND_URL=http://localhost:3000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=simagang
DB_USERNAME=root
DB_PASSWORD=
```

### PostgreSQL

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=simagang
DB_USERNAME=postgres
DB_PASSWORD=postgres
```

---

## 4. Middleware Role

Untuk Laravel 11/12/13, tambahkan alias middleware di `bootstrap/app.php`:

```php
use App\Http\Middleware\RoleMiddleware;

->withMiddleware(function (Middleware $middleware): void {
    $middleware->alias([
        'role' => RoleMiddleware::class,
    ]);
})
```

Kalau route di starter ini sudah memakai class middleware langsung, alias tidak wajib. Tapi tetap bagus untuk dipakai nanti.

---

## 5. Jalankan Migration dan Seeder

```bash
php artisan migrate:fresh --seed
php artisan storage:link
php artisan serve
```

---

## 6. Akun Demo

Seeder membuat akun berikut:

| Role | Email | Password |
|---|---|---|
| Admin | admin@simagang.test | password |
| Mahasiswa | mahasiswa@simagang.test | password |
| Dosen | dosen@simagang.test | password |
| Pembimbing Lapangan | pembimbing@simagang.test | password |

---

## 7. Test Login

Endpoint:

```http
POST http://127.0.0.1:8000/api/auth/login
```

Body:

```json
{
  "email": "admin@simagang.test",
  "password": "password"
}
```

Response mengembalikan token. Gunakan token untuk endpoint private:

```http
Authorization: Bearer TOKEN_KAMU
Accept: application/json
```

---

## 8. Modul yang Sudah Disiapkan

- Auth multi-role
- Dashboard role
- Master data mahasiswa, dosen, perusahaan, pembimbing lapangan, periode magang
- Pengajuan magang
- Assignment/penempatan magang
- Logbook digital
- Validasi logbook
- Monitoring progress
- Early warning sederhana berbasis rule
- Konsultasi/kendala
- Penilaian magang
- Notifikasi in-app
- Document upload
- AI Chatbot knowledge base sederhana

---

## 9. Catatan AI Chatbot

Chatbot pada starter ini memakai pencarian sederhana ke tabel `chatbot_knowledge_bases`. Untuk versi skripsi, ini sudah cukup sebagai **AI Chatbot berbasis knowledge base/FAQ** jika ingin dibuat ringan.

Kalau ingin benar-benar pakai LLM API, hubungkan logika di `ChatbotController@ask` ke provider AI menggunakan `guzzlehttp/guzzle`.
