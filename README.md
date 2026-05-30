<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## Beasiswa Kampusku (Online)

Aplikasi sederhana pendaftaran beasiswa online dengan 3 menu:

- **Pilihan Beasiswa**: informasi jenis beasiswa + syarat.
- **Daftar**: form pendaftaran (validasi email, nomor HP angka, semester 1–8, upload berkas).
- **Hasil**: menampilkan semua data pendaftaran + `status_ajuan`.

### Catatan Penting (Data Temporari)

- **Tidak menggunakan database**. Data pendaftaran disimpan sementara di **session**.
- Jika browser ditutup / session habis, data hasil dapat hilang.
- Berkas upload disimpan di `storage/app/public/beasiswa`.
- Akses berkas pada halaman **Hasil** menggunakan route download (`/berkas/{id}`), sehingga tidak bergantung pada `public/storage`.

### Aturan Form

- IPK **tidak diinput** user: otomatis dari sistem (konstanta konfigurasi).
- Jika **IPK < 3.0**: pilihan beasiswa, upload berkas, dan tombol daftar **dinonaktifkan**.
- Jika **IPK >= 3.0**: kursor otomatis fokus ke pilihan beasiswa.

IPK demo pada halaman **Daftar** dibuat bergantian (urut): 3.4 lalu 2.9 lalu 3.4, dst.

Nilai IPK dapat diubah lewat beasiswa config or env:

```bash
BEASISWA_DEMO_IPK_1=3.4
BEASISWA_DEMO_IPK_2=2.9
```

### kode Inti

- `config/beasiswa.php` (konstanta IPK demo & daftar beasiswa)
- `app/Http/Controllers/BeasiswaController.php` (alur Pilihan/Daftar/Hasil, session + upload)
- `resources/views/layouts/app.blade.php` (layout + menu tab)
- `resources/views/beasiswa/*.blade.php` (pilihan, daftar, hasil)
- `resources/js/app.js` (aktif/pasif komponen + auto-focus)

### Cara Menjalankan (Windows)

1. Install dependency:

```bash
composer install
npm install
```

2. Siapkan env dan app key:

```bash
copy .env.example .env
php artisan key:generate
```

3. Jalankan aplikasi:

```bash
npm run dev
php artisan serve
```

atau

```bash
composer run dev
```

4. Buka:

- `/beasiswa` (Pilihan)
- `/daftar` (Form)
- `/hasil` (Hasil)

### Running vs Debugging vs Build

- **Running**: menjalankan aplikasi (contoh: `php artisan serve`, `npm run dev`).
- **Debugging**: menjalankan dengan alat debug (contoh: Xdebug + breakpoints di IDE, melihat request lifecycle).
- **Build**: membuat aset siap produksi (contoh: `npm run build` untuk Vite).

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
