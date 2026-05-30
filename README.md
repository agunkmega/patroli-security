# 🛡️ Patroli Security - Aplikasi Manajemen Patroli dengan QR Code

Aplikasi web patroli security berbasis **Laravel 12**, **MySQL**, **TailwindCSS**, dan **AlpineJS** yang responsif untuk HP Android. Mendukung scan QR Code menggunakan kamera HP, GPS realtime, dan monitoring live.

## ✨ Fitur Utama

| Fitur | Deskripsi |
|-------|-----------|
| **Login Multi Role** | Admin, Supervisor, Security Guard |
| **Dashboard Modern** | Grafik patroli, statistik realtime, notifikasi |
| **Master Data** | Guard, Area, Checkpoint, Jadwal, Shift |
| **QR Code Checkpoint** | Generate QR unik, print, scan pakai kamera HP |
| **GPS Validation** | Validasi radius 30m, anti fake GPS |
| **Patroli Realtime** | Mulai patroli, scan checkpoint, progress bar |
| **SOS Emergency** | Tombol darurat dengan notifikasi ke supervisor |
| **Monitoring Maps** | Lihat posisi guard di OpenStreetMap |
| **Laporan** | Harian, bulanan, per guard, per area |
| **Absensi** | Check in/out dengan GPS dan foto selfie |
| **Notifikasi** | Checkpoint terlewat, patroli telat, SOS |
| **PWA Ready** | Bisa diinstal di HP Android seperti aplikasi native |
| **Dark Mode** | Tema dark orange-black elegan |
| **Voice Notification** | Suara saat scan berhasil |

## 🖥️ Teknologi

- **Backend:** Laravel 12, PHP 8.2+
- **Database:** MySQL 5.7+ / MariaDB 10+
- **Frontend:** TailwindCSS 3, AlpineJS 3, Chart.js
- **QR Scanner:** html5-qrcode (library QR scanner via kamera)
- **QR Generator:** simple-qr-code
- **Maps:** Leaflet.js + OpenStreetMap
- **Export:** DomPDF, Maatwebsite Excel
- **PWA:** Service Worker + Manifest JSON
- **Build:** Vite 6

## 📋 Persyaratan Sistem

- PHP 8.2 atau lebih baru
- Composer 2.x
- Node.js 18+ & NPM 10+
- MySQL 5.7+ atau MariaDB 10.3+
- Extensions PHP: `BCMath`, `Ctype`, `Fileinfo`, `JSON`, `Mbstring`, `OpenSSL`, `PDO`, `Tokenizer`, `XML`, `GD`, `cURL`

## 🚀 Instalasi Step by Step

### 1. Clone atau Extract Project

```bash
cd /var/www/html
# Extract project ke folder patroli-security
```

### 2. Install PHP Dependencies

```bash
composer install --no-dev --optimize-autoloader
```

### 3. Konfigurasi Environment

```bash
cp .env.example .env
# Atau copy manual file .env.example menjadi .env
```

Edit file `.env`:

```env
APP_NAME=PatroliSecurity
APP_ENV=production
APP_DEBUG=false
APP_URL=http://your-domain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=patroli_security
DB_USERNAME=root
DB_PASSWORD=your_password

QUEUE_CONNECTION=database
```

### 4. Generate App Key

```bash
php artisan key:generate
```

### 5. Buat Database MySQL

```sql
CREATE DATABASE patroli_security CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 6. Migrate Database

```bash
php artisan migrate --seed
```

Perintah di atas akan:
- Membuat semua tabel (users, guards, areas, checkpoints, patrols, patrol_logs, schedules, emergency_reports, attendance, activity_logs)
- Mengisi data dummy untuk testing

### 7. Storage Link

```bash
php artisan storage:link
```

### 8. Install NPM Dependencies & Build Asset

```bash
npm install
npm run build
```

Untuk development:
```bash
npm run dev
```

### 9. Konfigurasi Queue (untuk notifikasi)

```bash
php artisan queue:table
php artisan migrate
```

Jalankan worker queue:
```bash
php artisan queue:work --daemon &
```
Atau setup cron job untuk `schedule:run`.

### 10. Setup Cron Job (untuk jadwal otomatis)

Tambahkan ke crontab:
```bash
* * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1
```

### 11. Generate QR untuk Checkpoint

Setelah login sebagai Admin, buka menu **Checkpoint** → klik **Generate QR** pada setiap checkpoint.

Bisa juga print semua QR dari menu **Print All QR**.

### 12. Jalankan Aplikasi

```bash
php artisan serve --host=0.0.0.0 --port=8000
```

Akses di browser: `http://localhost:8000`

## 📱 Akses via HP Android

1. Pastikan HP dan server dalam satu jaringan (atau deploy ke hosting)
2. Buka browser Chrome di HP
3. Akses URL aplikasi
4. Untuk install sebagai PWA: buka menu Chrome → "Add to Home Screen"
5. Scan QR Code menggunakan kamera HP langsung dari browser

## 🔑 Akun Demo

| Role | Email | Password |
|------|-------|----------|
| **Admin** | admin@patroli.com | password |
| **Supervisor** | supervisor@patroli.com | password |
| **Guard** | guard@patroli.com | password |
| Guard 2 | bambang@patroli.com | password |
| Guard 3 | citra@patroli.com | password |

## 📁 Struktur Folder

```
patroli-security/
├── app/
│   ├── Enums/              # Enums (UserRole, PatrolStatus, CheckpointStatus)
│   ├── Http/
│   │   ├── Controllers/    # Controllers (Admin, Supervisor, Guard, Api)
│   │   └── Middleware/     # RoleMiddleware, LogActivity, CheckGPSRadius
│   ├── Models/             # Eloquent Models
│   └── Notifications/      # Queue Notifications
├── config/
│   ├── patrol.php          # Konfigurasi patroli
│   └── ...
├── database/
│   ├── migrations/         # Schema database
│   └── seeders/            # Data dummy
├── resources/
│   ├── css/                # TailwindCSS
│   ├── js/                 # AlpineJS, Scanner, Maps
│   └── views/              # Blade Templates
├── routes/
│   ├── web.php             # Web routes
│   └── api.php             # API routes
└── public/
    ├── manifest.json       # PWA manifest
    └── sw.js               # Service Worker
```

## 🔄 API Endpoints

### Auth
- `POST /api/login` - Login
- `GET /api/me` - Profile user
- `POST /api/logout` - Logout

### Scan
- `POST /api/scan` - Scan checkpoint (dengan GPS)
- `GET /api/checkpoint/{code}` - Detail checkpoint

### Patroli
- `POST /api/patrol/start` - Mulai patroli
- `GET /api/patrol/active` - Patroli aktif
- `GET /api/patrol/history` - Riwayat patroli

### Emergency
- `POST /api/emergency/sos` - Kirim SOS

## 📱 Fitur Mobile (HP Android)

- **Scan QR Kamera:** Buka halaman scan → izinkan kamera → arahkan ke QR
- **GPS Otomatis:** Lokasi terdeteksi otomatis saat scan
- **PWA Install:** Add to Home Screen → seperti aplikasi native
- **Offline Cache:** Data tetap bisa diakses walau internet putus
- **Sync Otomatis:** Data akan sinkron saat online kembali
- **Mobile Bottom Nav:** Navigasi mudah dengan jempol
- **Fullscreen Scan:** Scan QR dalam mode fullscreen

## 🛠️ Troubleshooting

### QR Code tidak muncul
```bash
php artisan storage:link
# Generate QR dari menu Admin → Checkpoint → Generate QR
```

### Error 403 saat akses
Pastikan middleware role sudah benar di `bootstrap/app.php`.

### Error 500
```bash
php artisan optimize:clear
chmod -R 775 storage bootstrap/cache
```

### Kamera tidak mau menyala
- Pastikan HTTPS (atau localhost)
- Izin kamera sudah diberikan
- Gunakan browser Chrome/Firefox terbaru

### GPS tidak akurat
- Aktifkan GPS high accuracy di HP
- Gunakan di luar ruangan
- Set `enableHighAccuracy: true`

## 📦 Deployment ke Shared Hosting

1. Upload semua file ke hosting via FTP
2. Set `public/` sebagai document root
3. Konfigurasi `.env` dengan database hosting
4. Jalankan `composer install --no-dev`
5. Jalankan `php artisan migrate --seed`
6. Generate key: `php artisan key:generate`
7. Storage link: `php artisan storage:link`

## 📄 Lisensi

Hak cipta dilindungi. Aplikasi ini dibuat untuk kebutuhan manajemen patroli security.

---

**Dibuat dengan ❤️ untuk keamanan Anda**
