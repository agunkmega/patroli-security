# Patroli Security - AI Session Log

## 2026-06-01 Session

### Completed
- **SQLite tracked in git**: `database.sqlite` committed and pushed.
- **Demo credentials removed**: `resources/views/auth/login.blade.php` — block deleted.
- **Sidebar mobile fix**: Alpine `$store.sidebar.isOpen` toggle + backdrop overlay in `admin.blade.php`.
- **QR code color**: black (`->color(0, 0, 0)`) in `app/Services/QRCodeService.php`. All QR codes regenerated.
- **Clock timezone removed**: navbar clock uses device `new Date()` locale. Scan page has live clock.
- **QRCodeService**: extracted to `app/Services/QRCodeService.php` with `generate(Checkpoint)`.
- **Artisan command**: `patrol:regenerate-qr` at `app/Console/Commands/RegenerateQRCodes.php`.
- **laravel/tinker** installed.
- **`print-all-qr` 404 fixed**: route moved before `Route::resource('checkpoints')` in `routes/web.php`.
- **Regen QR buttons**: per-row, per-show, and "Regen All QR" in index header. Route `POST admin/checkpoints/regenerate-all`.
- **Scan QR validation popup**: `POST guard/patrol/{patrol}/validate-checkpoint` → GPS radius check via AJAX. Popup shows distance or "Luar Radius". Auto-redirect on patrol complete.
- **Mobile API (Sanctum)**: `laravel/sanctum` installed, migration run, full API built:
  - Login/logout (`/api/login`, `/api/logout`, `/api/me`)
  - Dashboard (`/api/dashboard`)
  - Areas & schedules (`/api/areas`, `/api/schedules`)
  - Patrol CRUD (`/api/patrol/start`, `/api/patrol/active`, `/api/patrol/history`, `/api/patrol/{patrol}`, `/api/patrol/{patrol}/complete`)
  - Validate checkpoint (`/api/validate-checkpoint`)
  - Attendance (`/api/attendance`, `/api/attendance/today`, `/api/attendance/check-in`, `/api/attendance/check-out`)
  - Emergency (`/api/emergency`, `/api/emergency/{report}`, `/api/emergency/sos`)
  - Scan (`/api/scan`, `/api/checkpoint/{code}`)
  - Controllers: `Api\AuthController`, `Api\PatrolController`, `Api\AttendanceController`, `Api\EmergencyController`, `Api\ScanController`

### Pending / Issues
- **HTTPS for phone**: need mkcert on phone or Chrome flag `chrome://flags/#unsafely-treat-insecure-origin-as-secure`, or use ngrok tunnel.
- **FrankenPHP port 443**: needs Administrator restart.
- **Deploy to VPS**: clone, `composer install`, `touch database/database.sqlite`, `php artisan migrate:fresh --seed`, `php artisan storage:link`, `npm run build`, `php artisan patrol:regenerate-qr`.
- After deployment: `php artisan route:cache`.

### Key Files
- `app/Services/QRCodeService.php` — QR generation logic
- `app/Console/Commands/RegenerateQRCodes.php` — QR regeneration command
- `app/Http/Controllers/Api/*.php` — all API controllers
- `routes/api.php` — mobile API routes (21 endpoints)
- `routes/web.php` — web routes (print-all-qr + regenerate-all before resource)
- `resources/views/guard/scan.blade.php` — AJAX validation popup + live clock
- `resources/views/layouts/admin.blade.php` — sidebar backdrop
- `resources/views/components/sidebar.blade.php` — mobile toggle
- `resources/views/auth/login.blade.php` — no demo creds
- `config/sanctum.php` — published Sanctum config
