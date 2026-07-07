# GoGreen Bank — Frontend Laravel + Bootstrap

Front-end Sistem Informasi Bank Sampah Go Green (UI/UX). Stack: **Laravel + Blade + Bootstrap 5**, database **SQLite** (paling mudah, tanpa setup MySQL). Sudah bisa **input data** (setor sampah, tarik saldo, lapor drop-off, register).

## Isi paket ini
Folder ini berisi file-file kustom yang ditempelkan ke project Laravel baru. Strukturnya sudah mengikuti path Laravel, jadi tinggal di-copy sesuai foldernya.

```
routes/web.php
app/Models/...                 (User, WasteCategory, Transaction, Withdrawal, DropOffReport)
app/Http/Controllers/...       (Public, Auth, Nasabah, Karyawan)
app/Http/Middleware/RoleMiddleware.php
database/migrations/...        (5 file)
database/seeders/...           (catalog harga + akun demo)
resources/views/...            (semua halaman Blade)
public/css/gogreen.css
```

## Langkah Setup (dari nol)

Pastikan sudah terpasang: **PHP 8.2+**, **Composer**. (SQLite sudah bawaan PHP.)

```bash
# 1. Buat project Laravel baru
composer create-project laravel/laravel gogreen-bank
cd gogreen-bank

# 2. Salin SEMUA isi folder paket ini ke dalam project (timpa jika ada)
#    -> routes/, app/, database/, resources/, public/

# 3. Pakai SQLite
#    Buat file database kosong:
touch database/database.sqlite
```

Edit file `.env`, bagian database jadikan seperti ini (hapus baris DB_HOST/PORT/DATABASE/USERNAME/PASSWORD yang lama):

```
DB_CONNECTION=sqlite
# DB_DATABASE boleh dikosongkan, default ke database/database.sqlite
```

### 4. Daftarkan alias middleware `role`

**Laravel 11 / 12** — buka `bootstrap/app.php`, di bagian `->withMiddleware(function (Middleware $middleware) {`, tambahkan:

```php
$middleware->alias([
    'role' => \App\Http\Middleware\RoleMiddleware::class,
]);
```

**Laravel 10** — buka `app/Http/Kernel.php`, di array `$middlewareAliases` (atau `$routeMiddleware`), tambahkan:

```php
'role' => \App\Http\Middleware\RoleMiddleware::class,
```

### 5. Migrasi + isi data awal

```bash
php artisan migrate:fresh --seed
php artisan storage:link        # agar foto drop-off bisa tampil
php artisan serve
```

Buka http://127.0.0.1:8000

## Akun Demo
| Peran | Email | Kata sandi |
|-------|-------|-----------|
| Nasabah | `sarah@gogreen.bank` | `password` |
| Nasabah | `james@gogreen.bank` | `password` |
| Karyawan | `karyawan@gogreen.bank` | `password` |

> Saat login, pilih dulu toggle **Nasabah** atau **Karyawan** sesuai akun.

## Alur "bisa input data"
- **Register** (publik) → buat nasabah baru, langsung masuk dashboard.
- **Karyawan → Scan QR / Drop Off → pilih nasabah → Input** → pilih jenis sampah + isi berat → **Simpan**. Saldo nasabah otomatis bertambah (berat × harga) dan masuk ke riwayat.
- **Nasabah → Ambil** (modal) → ajukan penarikan, saldo berkurang, muncul di "Informasi Penarikan" milik karyawan.
- **Nasabah → Lapor Drop-off** → upload foto bukti (tersimpan di storage).

## Catatan
- Warna & font mengikuti dokumen Bab 10 (Primary Forest #004915, Vibrant Green #006E21, Plus Jakarta Sans).
- QR code dirender via layanan api.qrserver.com (butuh internet). Untuk offline, bisa diganti paket `simplesoftwareio/simple-qrcode`.
- Peta lokasi memakai embed Google Maps.
- Timbangan IoT disimulasikan lewat input berat manual; angka harga otomatis terhitung di sisi klien.
```
